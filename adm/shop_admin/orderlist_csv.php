<?php
$sub_menu = '400400';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "r");

if (!sql_query(" SELECT ct_delivery_company FROM {$g5['g5_shop_cart_table']} LIMIT 1 ", false)) {
    sql_query("ALTER TABLE `{$g5['g5_shop_cart_table']}` ADD `ct_delivery_company` VARCHAR(100) NOT NULL DEFAULT '' AFTER `ct_status`, ADD `ct_invoice` VARCHAR(100) NOT NULL DEFAULT '' AFTER `ct_delivery_company`, ADD `ct_invoice_time` DATETIME NULL DEFAULT NULL AFTER `ct_invoice`", true);
}

/*
 * 배송조건 <-> 지역 추가비(sendcostlist) 연결 테이블
 * deliverymanage.php에서 만든 테이블을 CSV에서도 안전하게 참조합니다.
 */
sql_query("
    CREATE TABLE IF NOT EXISTS donuts_delivery_condition_sendcosts (
        dc_id BIGINT UNSIGNED NOT NULL,
        sc_id BIGINT UNSIGNED NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (dc_id, sc_id),
        KEY idx_sc_id (sc_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
", false);

/*************************************************
 * orderlist.php 와 동일한 검색조건 처리
 *************************************************/

$where = array();

$sort1 = (isset($_GET['sort1']) && in_array($_GET['sort1'],
array(
    'od_id',
    'od_cart_price',
    'od_receipt_price',
    'od_cancel_price',
    'od_misu',
    'od_cash'
))) ? $_GET['sort1'] : '';

$sort2 = (isset($_GET['sort2']) && in_array($_GET['sort2'],
array(
    'desc',
    'asc'
))) ? $_GET['sort2'] : 'desc';

$sel_field = (isset($_GET['sel_field']) && in_array($_GET['sel_field'],
array(
    'od_id',
    'mb_id',
    'od_name',
    'od_tel',
    'od_hp',
    'od_b_name',
    'od_b_tel',
    'od_b_hp',
    'od_deposit_name',
    'od_invoice'
))) ? $_GET['sel_field'] : '';

$od_status          = isset($_GET['od_status']) ? get_search_string($_GET['od_status']) : '';
$search             = isset($_GET['search']) ? get_search_string($_GET['search']) : '';
$od_misu            = isset($_GET['od_misu']) ? preg_replace('/[^0-9a-z]/i', '', $_GET['od_misu']) : '';
$od_cancel_price    = isset($_GET['od_cancel_price']) ? preg_replace('/[^0-9a-z]/i', '', $_GET['od_cancel_price']) : '';
$od_refund_price    = isset($_GET['od_refund_price']) ? preg_replace('/[^0-9a-z]/i', '', $_GET['od_refund_price']) : '';
$od_receipt_point   = isset($_GET['od_receipt_point']) ? preg_replace('/[^0-9a-z]/i', '', $_GET['od_receipt_point']) : '';
$od_coupon          = isset($_GET['od_coupon']) ? preg_replace('/[^0-9a-z]/i', '', $_GET['od_coupon']) : '';
$od_settle_case     = isset($_GET['od_settle_case']) ? clean_xss_tags($_GET['od_settle_case'], 1, 1) : '';
$od_escrow          = isset($_GET['od_escrow']) ? clean_xss_tags($_GET['od_escrow'], 1, 1) : '';

$fr_date = (isset($_GET['fr_date'])) ? $_GET['fr_date'] : '';
$to_date = (isset($_GET['to_date'])) ? $_GET['to_date'] : '';

if ($search != "") {
    if ($sel_field != "") {
        $where[] = " $sel_field like '%{$search}%' ";
    }
}

if ($od_status) {

    switch($od_status) {

        case '전체취소':
            $where[] = " od_status = '취소' ";
            break;

        case '부분취소':
            $where[] = " od_status IN('주문','입금','준비','배송','완료')
                         and od_cancel_price > 0 ";
            break;

        default:
            $where[] = " od_status = '{$od_status}' ";
            break;
    }

    switch ($od_status) {

        case '주문':
            $sort1 = "od_id";
            $sort2 = "desc";
            break;

        case '입금':
            $sort1 = "od_receipt_time";
            $sort2 = "desc";
            break;

        case '배송':
            $sort1 = "od_invoice_time";
            $sort2 = "desc";
            break;
    }
}

if ($od_settle_case) {

    if ($od_settle_case === '간편결제') {

        $where[] = "
            od_settle_case in (
                '간편결제',
                '삼성페이',
                'lpay',
                'inicis_kakaopay'
            )
        ";

    } else {

        $where[] = " od_settle_case = '{$od_settle_case}' ";
    }
}

if ($od_misu)
    $where[] = " od_misu != 0 ";

if ($od_cancel_price)
    $where[] = " od_cancel_price != 0 ";

if ($od_refund_price)
    $where[] = " od_refund_price != 0 ";

if ($od_receipt_point)
    $where[] = " od_receipt_point != 0 ";

if ($od_coupon)
    $where[] = " (od_cart_coupon > 0 or od_coupon > 0 or od_send_coupon > 0) ";

if ($od_escrow)
    $where[] = " od_escrow = 1 ";

if ($fr_date && $to_date)
    $where[] = " od_time between '{$fr_date} 00:00:00'
                 and '{$to_date} 23:59:59' ";

$sql_search = '';

if ($where)
    $sql_search = ' where '.implode(' and ', $where);
    // 브랜드 계정 체크
    $brand = sql_fetch("
        SELECT brand_id
        FROM donuts_brand
        WHERE brand_id = '{$member['mb_id']}'
    ");

    if ($brand['brand_id']) {

        $brand_where = "
            o.od_id IN (
                SELECT DISTINCT c.od_id
                FROM {$g5['g5_shop_cart_table']} c
                INNER JOIN {$g5['g5_shop_item_table']} i
                    ON c.it_id = i.it_id
                WHERE TRIM(i.it_brand) = '{$member['mb_id']}'
            )
        ";

        if ($sql_search) {
            $sql_search .= " AND {$brand_where}";
        } else {
            $sql_search = " WHERE {$brand_where}";
        }
    }

if (!$sort1)
    $sort1 = 'od_id';

if (!$sort2)
    $sort2 = 'desc';

/*************************************************
 * CSV 다운로드
 *************************************************/

$filename = "orders_" . date("Ymd_His") . ".csv";

header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename={$filename}");

echo "\xEF\xBB\xBF";
$fp = fopen("php://output", "w");

/*
 * 브랜드 설정
 * 브랜드 회원의 it_sc_type=0(쇼핑몰 기본설정) 상품은
 * donuts_brand_settings 배송설정을 사용합니다.
 */
$brand_settings = array();

if (!empty($brand['brand_id'])) {
    $brand_id_sql = sql_real_escape_string($member['mb_id']);

    $bs_result = sql_query("
        SELECT *
        FROM donuts_brand_settings
        WHERE brand_id = '{$brand_id_sql}'
        LIMIT 1
    ", false);

    if ($bs_result) {
        $brand_settings = sql_fetch_array($bs_result);
    }
}

/* 브랜드 기본 배송비 계산 */

/*************************************************
 * 배송비 계산 로직 분리
 * - 기존 함수 구현은 orderlist_csv_shipping.lib.php로 이동
 * - CSV 검색/조회/출력 흐름은 기존과 동일
 *************************************************/
include_once('./orderlist_csv_shipping.lib.php');

/* CSV 헤더 */
fputcsv($fp, array(
    '주문일시',
    '주문번호',
    '주문순번',
    '쇼핑몰 상품코드',
    '상품명',
    '옵션명',
    '상품상태',
    '주문수량',
    '상품 기본판매가',
    '옵션 추가금액',
    '상품별 판매가',
    '주문 별 판매가',
    '주문 별 결제가',
    '공급가',
    '주문자명',
    '주문자 전화번호',
    '주문자 휴대폰',
    '주문자 주소',
    '주문자 우편번호',
    '수취인명',
    '수취인 전화번호',
    '수취인 휴대폰',
    '수취인 주소',
    '수취인 우편번호',
    '통관번호',
    '배송메시지',
    '배송비',
    '배송비 결제구분',
    '배송비 유형',
    '묶음배송 그룹',
    '지역 추가비',
    '최종 배송비',
    '택배사',
    '운송장 번호',
    '운송장 등록일시',
    '주문상태',
    '결제수단',
    '정산금액',
    '정산예정일'
));

/*
 * 기존 sql_search는 o alias를 사용하므로 그대로 사용.
 * 브랜드 회원은 주문번호뿐 아니라 실제 CSV 행도 자기 브랜드 상품으로 제한.
 */
$csv_brand_where = '';

if (!empty($brand['brand_id'])) {
    $brand_id_sql = sql_real_escape_string($member['mb_id']);
    $csv_brand_where = " AND TRIM(i.it_brand) = '{$brand_id_sql}' ";
}

/* WHERE가 없는 경우 AND를 붙일 수 없으므로 별도 처리 */
if (!$sql_search && $csv_brand_where) {
    $csv_brand_where = " WHERE TRIM(i.it_brand) = '{$brand_id_sql}' ";
}

/* 주문상품 기준 조회 */
$sql = "
SELECT
    o.*,

    c.ct_id,
    c.it_id,
    c.it_name,
    c.ct_option,
    c.ct_qty,
    c.ct_price,
    c.ct_send_cost,
    c.ct_status,
    c.io_type,
    c.io_price,
    c.ct_delivery_company,
    c.ct_invoice,
    c.ct_invoice_time,

    i.it_brand,
    i.it_sc_type,
    i.it_sc_method,
    i.it_sc_price,
    i.it_sc_minimum,
    i.it_sc_qty

FROM {$g5['g5_shop_order_table']} o

INNER JOIN {$g5['g5_shop_cart_table']} c
    ON o.od_id = c.od_id

LEFT JOIN {$g5['g5_shop_item_table']} i
    ON c.it_id = i.it_id

{$sql_search}
{$csv_brand_where}

ORDER BY o.{$sort1} {$sort2}, c.ct_id ASC
";

$result = sql_query($sql);

/*
 * 같은 주문/상품의 옵션행마다 배송비가 반복되는 것을 막음.
 */
$shipping_written = array();
$new_delivery_cache = array();
$final_shipping_cache = array();

while ($row = sql_fetch_array($result)) {

    /*
     * orderform.php와 같은 옵션 가격 계산
     *
     * 일반옵션(io_type=0)
     *   ct_price + io_price
     *
     * 추가옵션(io_type=1)
     *   io_price
     */
    if ((int)$row['io_type'] === 1) {
        $unit_sale_price = (int)$row['io_price'];
    } else {
        $unit_sale_price = (int)$row['ct_price'] + (int)$row['io_price'];
    }

    $option_add_price = (int)$row['io_price'];
    $line_sale_price = $unit_sale_price * (int)$row['ct_qty'];

    /*
     * 현재 구현된 배송관리 정책으로 주문/브랜드 전체 배송비를 계산합니다.
     * 주문 단위로 캐시하여 같은 주문의 옵션행마다 다시 계산하지 않습니다.
     */
    $row_brand_id = trim((string)$row['it_brand']);

    if (!isset($new_delivery_cache)) {
        $new_delivery_cache = array();
    }

    $delivery_cache_key =
        $row['od_id'] . '|' . $row_brand_id;

    if (
        $row_brand_id !== '' &&
        !isset($new_delivery_cache[$delivery_cache_key])
    ) {
        $receiver_addr_for_shipping = trim(
            $row['od_b_addr1'] . ' ' .
            $row['od_b_addr2'] . ' ' .
            $row['od_b_addr3']
        );

        $receiver_zip_for_shipping =
            (string)$row['od_b_zip1'] .
            (string)$row['od_b_zip2'];

        $same_order_product_total_for_row =
            csv_new_delivery_order_product_total($row['od_id']);

        $new_delivery_cache[$delivery_cache_key] =
            csv_new_delivery_order_brand(
                $row['od_id'],
                $row_brand_id,
                $receiver_addr_for_shipping,
                $receiver_zip_for_shipping,
                $same_order_product_total_for_row
            );
    }

    $delivery_calc =
        ($row_brand_id !== '' && isset($new_delivery_cache[$delivery_cache_key]))
        ? $new_delivery_cache[$delivery_cache_key]
        : null;

    $row_shipping_amount = 0;
    $shipping_method = '무료';
    $shipping_type = '';

    if ($delivery_calc) {
        $row_shipping_amount =
            isset($delivery_calc['item_charges'][$row['it_id']])
            ? (int)$delivery_calc['item_charges'][$row['it_id']]
            : 0;

        $shipping_method =
            isset($delivery_calc['item_methods'][$row['it_id']])
            ? $delivery_calc['item_methods'][$row['it_id']]
            : ($row_shipping_amount > 0 ? '선불' : '무료');

        $shipping_type =
            isset($delivery_calc['item_types'][$row['it_id']])
            ? $delivery_calc['item_types'][$row['it_id']]
            : '배송관리 정책';

        /*
         * 같은 상품의 옵션행에는 배송비를 한 번만 출력.
         */
        $shipping_key = $row['od_id'] . '|' . $row_brand_id . '|' . $row['it_id'];

        if (isset($shipping_written[$shipping_key])) {
            $row_shipping_amount = 0;
        } else {
            $shipping_written[$shipping_key] = true;
        }

    } else {
        /*
         * 브랜드 정보가 없는 예외 행은 기존 주문 저장 배송비를 fallback.
         */
        $shipping_key = $row['od_id'] . '|fallback';

        if (!isset($shipping_written[$shipping_key])) {
            $shipping_written[$shipping_key] = true;
            $row_shipping_amount =
                (int)$row['od_send_cost'] +
                (int)$row['od_send_cost2'];
        }

        $shipping_method =
            $row_shipping_amount > 0
            ? '선불'
            : '무료';

        $shipping_type = '기존 주문 배송비';
    }

    /*
     * 착불은 결제금액에 포함하지 않음.
     */
    $shipping_in_payment =
        ($shipping_method === '착불')
        ? 0
        : $row_shipping_amount;

    /*
     * 옵션 추가금액까지 반영한 행 기준 결제가.
     */
    $line_payment_price =
        $line_sale_price +
        $shipping_in_payment;

    $supply_price = (int)round($line_sale_price / 1.1);

    /*
     * 현재 확인 가능한 정산 규칙은 기존 파일과 동일하게
     * 행 결제가 기준으로 유지.
     */
    $settle_price = $line_payment_price;

    $settle_date = '';
    if (!empty($row['od_time']) && strtotime($row['od_time']) !== false) {
        $settle_date =
            date('Y-m-d', strtotime($row['od_time'] . ' +7 days'));
    }

    /* 주문자 주소 */
    $orderer_addr = trim(
        $row['od_addr1'] . ' ' .
        $row['od_addr2'] . ' ' .
        $row['od_addr3']
    );

    /* 수취인 주소 */
    $receiver_addr = trim(
        $row['od_b_addr1'] . ' ' .
        $row['od_b_addr2'] . ' ' .
        $row['od_b_addr3']
    );

    /*
     * 영카트 우편번호는 zip1 + zip2.
     * 기존 파일처럼 zip1만 출력하면 뒤가 잘림.
     */
    $orderer_zip =
        (string)$row['od_zip1'] .
        (string)$row['od_zip2'];

    $receiver_zip =
        trim((string)$row['od_b_zip1']) .
        trim((string)$row['od_b_zip2']);

    // 수취인 우편번호는 숫자만 정리해서 그대로 출력합니다.
    // 예: 12661 -> 12661
    $receiver_zip = preg_replace('/[^0-9]/', '', $receiver_zip);
    $receiver_zip_csv = ($receiver_zip !== '')
        ? (int)$receiver_zip
        : '';

    /*
     * 같은 주문번호의 모든 CSV 행에는 동일한 "최종 배송비"를 기록합니다.
     *
     * 브랜드 계정이면 해당 브랜드 기준,
     * 최고관리자이면 주문번호 전체 브랜드의 배송비 합계 기준입니다.
     */
    $final_shipping_cache_key =
        (string)$row['od_id'] . '|' .
        (!empty($brand['brand_id']) ? (string)$member['mb_id'] : 'ALL');

    if (!isset($final_shipping_cache[$final_shipping_cache_key])) {
        $final_shipping_brand_id = !empty($brand['brand_id'])
            ? trim((string)$member['mb_id'])
            : '';

        $final_shipping_cache[$final_shipping_cache_key] =
            csv_new_delivery_final_order_shipping_detail(
                $row['od_id'],
                $final_shipping_brand_id,
                $receiver_addr,
                $receiver_zip
            );
    }

    $final_shipping_detail =
        $final_shipping_cache[$final_shipping_cache_key];

    $region_extra_amount =
        isset($final_shipping_detail['region_extra'])
        ? (int)$final_shipping_detail['region_extra']
        : 0;

    $final_shipping_amount =
        isset($final_shipping_detail['shipping_total'])
        ? (int)$final_shipping_detail['shipping_total']
        : 0;

    fputcsv($fp, array(
        $row['od_time'],
        $row['od_id'],
        $row['ct_id'],
        $row['it_id'],
        $row['it_name'],
        $row['ct_option'],
        $row['ct_status'],
        $row['ct_qty'],

        $row['ct_price'],
        $option_add_price,
        $unit_sale_price,
        $line_sale_price,
        $line_payment_price,
        $supply_price,

        $row['od_name'],
        $row['od_tel'],
        $row['od_hp'],
        $orderer_addr,
        $orderer_zip,

        $row['od_b_name'],
        $row['od_b_tel'],
        $row['od_b_hp'],
        $receiver_addr,
        $receiver_zip_csv,

        '', // 통관번호: 현재 확인한 테이블/파일에 전용 필드를 확인하지 못해 비움

        $row['od_memo'],

        $row_shipping_amount,
        $shipping_method,
        $shipping_type,
        ($delivery_calc && isset($delivery_calc['item_groups'][$row['it_id']]))
            ? $delivery_calc['item_groups'][$row['it_id']]
            : '',

        $region_extra_amount,
        $final_shipping_amount,

        $row['ct_delivery_company'],
        $row['ct_invoice'],
        $row['ct_invoice_time'],

        $row['od_status'],
        $row['od_settle_case'],

        $settle_price,
        $settle_date
    ));
}

fclose($fp);

exit;

<?php
$sub_menu = '400400';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "r");

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
                WHERE i.it_brand = '{$member['mb_id']}'
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

/*************************************************
 * CSV 헤더
 *************************************************/

fputcsv($fp, array(
    '주문일자',
    '주문번호',
    '주문순번',
    '쇼핑몰 상품코드',
    '상품명',
    '옵션명',
    '주문수량',
    '상품별 판매가',
    '주문 별 판매가',
    '주문 별 결제가',
    '공급가',
    '주문자명',
    '수취인명',
    '수취인 연락처',
    '수취인 주소',
    '수취인 우편번호',
    '통관번호',
    '배송메시지',
    '배송비',
    '배송구분',
    '정산금액',
    '정산예정일'
));

/*************************************************
 * 주문상품 기준 조회
 *************************************************/

$sql = "
SELECT
    o.od_id,
    o.od_time,
    o.od_status,

    o.od_name,
    o.od_b_name,
    o.od_b_hp,
    o.od_b_zip1,

    o.od_b_addr1,
    o.od_b_addr2,
    o.od_b_addr3,

    o.od_memo,

    c.ct_id,
    c.it_id,
    c.it_name,
    c.ct_option,
    c.ct_qty,
    c.ct_price,
    c.ct_send_cost,

    i.it_brand

FROM {$g5['g5_shop_order_table']} o

INNER JOIN {$g5['g5_shop_cart_table']} c
    ON o.od_id = c.od_id

LEFT JOIN {$g5['g5_shop_item_table']} i
    ON c.it_id = i.it_id

{$sql_search}
";

/*************************************************
 * 브랜드 계정 필터
 *************************************************/

// if (!empty($brand['brand_id'])) {

//     $sql .= "
//     AND i.it_brand = '{$member['mb_id']}'
//     ";
// }

$sql .= "
ORDER BY o.{$sort1} {$sort2}
";

$result = sql_query($sql);

/*************************************************
 * CSV 출력
 *************************************************/

while ($row = sql_fetch_array($result))
{
    $order_price =
        $row['ct_price'] * $row['ct_qty'];

    $pay_price =
        $order_price +
        (int)$row['ct_send_cost'];

    $supply_price =
        round($order_price / 1.1);

    $settle_price =
        $pay_price;

    $settle_date =
        date(
            'Y-m-d',
            strtotime($row['od_time'].' +7 days')
        );

    $receiver_addr =
        trim(
            $row['od_b_addr1'].' '.
            $row['od_b_addr2'].' '.
            $row['od_b_addr3']
        );

    fputcsv($fp, array(

        substr($row['od_time'], 0, 10),

        $row['od_id'],

        $row['ct_id'],

        $row['it_id'],

        $row['it_name'],

        $row['ct_option'],

        $row['ct_qty'],

        $row['ct_price'],

        $order_price,

        $pay_price,

        $supply_price,

        $row['od_name'],

        $row['od_b_name'],

        $row['od_b_hp'],

        $receiver_addr,

        $row['od_b_zip1'],

        '', // 통관번호

        $row['od_memo'],

        $row['ct_send_cost'],

        ($row['ct_send_cost'] > 0 ? '유료배송' : '무료배송'),

        $settle_price,

        $settle_date
    ));
}

fclose($fp);
exit;
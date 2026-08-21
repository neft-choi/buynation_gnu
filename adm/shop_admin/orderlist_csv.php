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
function csv_brand_send_cost($settings, $order_price)
{
    if (empty($settings)) {
        return 0;
    }

    $case = isset($settings['de_send_cost_case']) ? trim($settings['de_send_cost_case']) : '';

    if ($case === '무료') {
        return 0;
    }

    $limits = isset($settings['de_send_cost_limit'])
        ? explode(';', $settings['de_send_cost_limit'])
        : array();

    $costs = isset($settings['de_send_cost_list'])
        ? explode(';', $settings['de_send_cost_list'])
        : array();

    foreach ($limits as $idx => $limit) {
        $limit = trim($limit);

        if ($limit === '' || !is_numeric($limit)) {
            continue;
        }

        if ($order_price < (int)$limit) {
            return (isset($costs[$idx]) && is_numeric(trim($costs[$idx])))
                ? (int)trim($costs[$idx])
                : 0;
        }
    }

    return 0;
}


/*
 * ============================================================
 * 새 배송관리 정책 기준 CSV 배송비 계산
 * ============================================================
 *
 * 우선순위:
 * 1. donuts_delivery_product_settings에 연결된 배송조건
 * 2. 연결값이 없으면 해당 브랜드의 기본 배송조건(is_default=1)
 * 3. 기본 배송조건도 없으면 donuts_brand_settings 기존 기본 배송비
 *
 * 지원 정책:
 * - paid          : 유료
 * - conditional   : 조건부 무료
 * - free          : 무료
 * - quantity      : 수량별
 * - amount_range  : 금액 구간별
 * - 묶음배송 그룹 MIN / MAX
 * - 제주 추가비
 * - 도서산간 추가비(기존 추가배송비 우편번호 범위로 지역 판정)
 */
function csv_new_delivery_is_island_zip($zip)
{
    global $g5;

    $zip = preg_replace('/[^0-9]/', '', (string)$zip);

    if ($zip === '') {
        return false;
    }

    $zip_num = (int)$zip;

    $result = sql_query("
        SELECT sc_zip1, sc_zip2
        FROM {$g5['g5_shop_sendcost_table']}
    ", false);

    if (!$result) {
        return false;
    }

    while ($row = sql_fetch_array($result)) {
        $from = (int)preg_replace('/[^0-9]/', '', (string)$row['sc_zip1']);
        $to   = (int)preg_replace('/[^0-9]/', '', (string)$row['sc_zip2']);

        if ($from <= $zip_num && $zip_num <= $to) {
            return true;
        }
    }

    return false;
}

function csv_new_delivery_condition_fee($condition, $item_amount, $item_qty)
{
    global $g5;

    if (empty($condition)) {
        return 0;
    }

    $type = isset($condition['dc_type'])
        ? trim((string)$condition['dc_type'])
        : '';

    $price = isset($condition['dc_price'])
        ? max(0, (int)$condition['dc_price'])
        : 0;

    $minimum = isset($condition['dc_minimum'])
        ? max(0, (int)$condition['dc_minimum'])
        : 0;

    $qty_unit = isset($condition['dc_qty'])
        ? max(1, (int)$condition['dc_qty'])
        : 1;

    switch ($type) {
        case 'paid':
            return $price;

        case 'free':
            return 0;

        case 'quantity':
            return $price * (int)ceil(max(0, $item_qty) / $qty_unit);

        case 'amount_range':
            $dc_id = isset($condition['dc_id'])
                ? (int)$condition['dc_id']
                : 0;

            if ($dc_id < 1) {
                return 0;
            }

            $result = sql_query("
                SELECT min_amount, max_amount, dr_price
                FROM donuts_delivery_condition_ranges
                WHERE dc_id = '{$dc_id}'
                ORDER BY min_amount ASC
            ", false);

            if (!$result) {
                return 0;
            }

            while ($range = sql_fetch_array($result)) {
                $min = (int)$range['min_amount'];

                $max = (
                    $range['max_amount'] === null ||
                    $range['max_amount'] === ''
                )
                    ? null
                    : (int)$range['max_amount'];

                if (
                    $item_amount >= $min &&
                    ($max === null || $item_amount < $max)
                ) {
                    return max(0, (int)$range['dr_price']);
                }
            }

            return 0;

        case 'conditional':
        default:
            if ($minimum > 0 && $item_amount >= $minimum) {
                return 0;
            }

            return $price;
    }
}


/*
 * 같은 주문번호의 전체 상품금액.
 *
 * 브랜드 구분 없이 주문번호에 포함된 모든 상품을 합산합니다.
 * 옵션 추가금액도 포함합니다.
 *
 * 묶음배송 조건부무료 / 금액구간 판정은 이 금액을 사용합니다.
 */
function csv_new_delivery_order_product_total($od_id)
{
    global $g5;

    $od_id = trim((string)$od_id);

    if ($od_id === '') {
        return 0;
    }

    $od_id_sql = sql_real_escape_string($od_id);

    $row = sql_fetch("
        SELECT
            SUM(
                IF(
                    io_type = 1,
                    io_price * ct_qty,
                    (ct_price + io_price) * ct_qty
                )
            ) AS total_price
        FROM {$g5['g5_shop_cart_table']}
        WHERE od_id = '{$od_id_sql}'
    ");

    return isset($row['total_price'])
        ? max(0, (int)$row['total_price'])
        : 0;
}

function csv_new_delivery_order_brand($od_id, $brand_id, $receiver_addr, $receiver_zip, $order_product_total_override = null)
{
    global $g5;

    $data = array(
        'shipping_total' => 0,
        'item_charges' => array(),
        'item_types' => array(),
        'item_methods' => array(),
        'item_groups' => array()
    );

    $od_id = trim((string)$od_id);
    $brand_id = trim((string)$brand_id);

    if ($od_id === '' || $brand_id === '') {
        return $data;
    }

    $od_id_sql = sql_real_escape_string($od_id);
    $brand_id_sql = sql_real_escape_string($brand_id);

    /*
     * 브랜드 기본 배송조건.
     */
    $default_condition = array();

    $default_result = sql_query("
        SELECT *
        FROM donuts_delivery_conditions
        WHERE brand_id = '{$brand_id_sql}'
          AND is_default = 1
          AND use_yn = 'Y'
        ORDER BY dc_id DESC
        LIMIT 1
    ", false);

    if ($default_result) {
        $default_condition = sql_fetch_array($default_result);
    }

    /*
     * 새 기본조건이 없는 서버/브랜드를 위한 기존 브랜드 설정 fallback.
     */
    $brand_settings = array();

    $bs_result = sql_query("
        SELECT *
        FROM donuts_brand_settings
        WHERE brand_id = '{$brand_id_sql}'
        LIMIT 1
    ", false);

    if ($bs_result) {
        $brand_settings = sql_fetch_array($bs_result);
    }

    /*
     * 주문에서 현재 브랜드 상품을 상품코드별로 합산.
     * 옵션 추가금액까지 포함합니다.
     */
    $item_result = sql_query("
        SELECT
            c.it_id,
            SUM(
                IF(
                    c.io_type = 1,
                    c.io_price * c.ct_qty,
                    (c.ct_price + c.io_price) * c.ct_qty
                )
            ) AS item_amount,
            SUM(c.ct_qty) AS item_qty
        FROM {$g5['g5_shop_cart_table']} c
        INNER JOIN {$g5['g5_shop_item_table']} i
            ON i.it_id = c.it_id
        WHERE c.od_id = '{$od_id_sql}'
          AND TRIM(i.it_brand) = '{$brand_id_sql}'
        GROUP BY c.it_id
        ORDER BY MIN(c.ct_id) ASC
    ", false);

    if (!$item_result) {
        return $data;
    }

    $items = array();

    while ($item = sql_fetch_array($item_result)) {
        $it_id = trim((string)$item['it_id']);

        if ($it_id === '') {
            continue;
        }

        $it_id_sql = sql_real_escape_string($it_id);

        /*
         * 상품에 연결된 배송조건/묶음그룹.
         */
        $setting = array();

        $setting_result = sql_query("
            SELECT condition_id, group_id
            FROM donuts_delivery_product_settings
            WHERE brand_id = '{$brand_id_sql}'
              AND it_id = '{$it_id_sql}'
            LIMIT 1
        ", false);

        if ($setting_result) {
            $setting = sql_fetch_array($setting_result);
        }

        $condition_id = !empty($setting['condition_id'])
            ? (int)$setting['condition_id']
            : 0;

        $group_id = !empty($setting['group_id'])
            ? (int)$setting['group_id']
            : 0;

        $condition = array();

        if ($condition_id > 0) {
            $condition_result = sql_query("
                SELECT *
                FROM donuts_delivery_conditions
                WHERE dc_id = '{$condition_id}'
                  AND brand_id = '{$brand_id_sql}'
                  AND use_yn = 'Y'
                LIMIT 1
            ", false);

            if ($condition_result) {
                $condition = sql_fetch_array($condition_result);
            }
        }

        if (empty($condition) && !empty($default_condition)) {
            $condition = $default_condition;
            $condition_id = isset($default_condition['dc_id'])
                ? (int)$default_condition['dc_id']
                : 0;
        }

        $item_amount = (int)$item['item_amount'];
        $item_qty = (int)$item['item_qty'];

        if (!empty($condition)) {
            $fee = csv_new_delivery_condition_fee(
                $condition,
                $item_amount,
                $item_qty
            );

            $condition_name = !empty($condition['dc_name'])
                ? $condition['dc_name']
                : '배송조건';

            /*
             * 지역 추가배송비.
             * 제주와 도서산간은 중복 가산하지 않고 제주를 우선합니다.
             */
            $is_jeju = (
                mb_strpos((string)$receiver_addr, '제주') !== false
            );

            if (
                $is_jeju &&
                !empty($condition['dc_jeju_use'])
            ) {
                $fee += max(0, (int)$condition['dc_jeju_price']);
                $condition_name .= ' + 제주추가';
            } elseif (
                !empty($condition['dc_island_use']) &&
                csv_new_delivery_is_island_zip($receiver_zip)
            ) {
                $fee += max(0, (int)$condition['dc_island_price']);
                $condition_name .= ' + 도서산간추가';
            }

        } else {
            /*
             * 새 배송조건이 전혀 없을 때만 기존 브랜드 기본배송비 사용.
             */
            $fee = csv_brand_send_cost($brand_settings, $item_amount);
            $condition_name = '브랜드 기본 배송비';
        }

        $group_name = '';
        $calc_method = 'MAX';

        if ($group_id > 0) {
            $group_result = sql_query("
                SELECT dg_name, calc_method
                FROM donuts_delivery_groups
                WHERE dg_id = '{$group_id}'
                  AND brand_id = '{$brand_id_sql}'
                  AND use_yn = 'Y'
                LIMIT 1
            ", false);

            if ($group_result) {
                $group = sql_fetch_array($group_result);

                if (!empty($group['dg_name'])) {
                    $group_name = $group['dg_name'];
                }

                if (
                    isset($group['calc_method']) &&
                    strtoupper($group['calc_method']) === 'MIN'
                ) {
                    $calc_method = 'MIN';
                }
            }

            /*
             * 그룹 정보가 삭제/미사용 상태면 개별배송 취급.
             */
            if ($group_name === '') {
                $group_id = 0;
            }
        }

        $items[$it_id] = array(
            'fee' => max(0, (int)$fee),
            'amount' => $item_amount,
            'qty' => $item_qty,
            'condition' => $condition,
            'condition_name' => $condition_name,
            'group_id' => $group_id,
            'group_name' => $group_name,
            'calc_method' => $calc_method
        );

        $data['item_charges'][$it_id] = 0;
        $data['item_types'][$it_id] = $condition_name;
        $data['item_groups'][$it_id] = $group_name;
        $data['item_methods'][$it_id] = $fee > 0 ? '선불' : '무료';
    }

    /*
     * 묶음배송 무료조건/금액구간 판정용 주문총액.
     *
     * 중요:
     * 브랜드별 상품합계가 아니라 "같은 주문번호의 모든 상품금액"을 사용합니다.
     *
     * 예)
     * 상품 A 15,000원
     * 상품 B 15,000원
     * 묶음배송 30,000원 이상 무료
     *
     * => 주문번호 전체 상품금액 30,000원
     * => 최종 배송비 0원
     */
    if ($order_product_total_override !== null) {
        $order_product_total = max(0, (int)$order_product_total_override);
    } else {
        $order_product_total = csv_new_delivery_order_product_total($od_id);
    }

    /*
     * 예외적으로 전체 주문금액 조회가 실패한 경우에만
     * 현재 브랜드 상품금액 합계로 fallback 합니다.
     */
    if ($order_product_total <= 0) {
        foreach ($items as $it_id => $item) {
            $order_product_total += isset($item['amount'])
                ? (int)$item['amount']
                : 0;
        }
    }

    /*
     * 묶음배송 상품만 주문 전체 상품금액 기준으로 배송조건을 다시 계산합니다.
     * paid/free/quantity는 금액 기준 여부와 관계없이 기존 규칙을 유지하고,
     * conditional/amount_range는 주문번호의 모든 상품금액을 기준으로 계산됩니다.
     */
    foreach ($items as $it_id => &$item) {
        if ((int)$item['group_id'] < 1 || empty($item['condition'])) {
            continue;
        }

        $group_fee = csv_new_delivery_condition_fee(
            $item['condition'],
            $order_product_total,
            isset($item['qty']) ? (int)$item['qty'] : 0
        );

        $group_condition_name = !empty($item['condition']['dc_name'])
            ? $item['condition']['dc_name']
            : '배송조건';

        /*
         * 지역 추가배송비도 최종 그룹 후보 배송비에 포함합니다.
         */
        $is_jeju_group = (
            function_exists('mb_strpos') &&
            mb_strpos((string)$receiver_addr, '제주') !== false
        );

        if (
            $is_jeju_group &&
            !empty($item['condition']['dc_jeju_use'])
        ) {
            $group_fee += max(0, (int)$item['condition']['dc_jeju_price']);
            $group_condition_name .= ' + 제주추가';
        } elseif (
            !empty($item['condition']['dc_island_use']) &&
            csv_new_delivery_is_island_zip($receiver_zip)
        ) {
            $group_fee += max(0, (int)$item['condition']['dc_island_price']);
            $group_condition_name .= ' + 도서산간추가';
        }

        $item['fee'] = max(0, (int)$group_fee);
        $item['condition_name'] = $group_condition_name;

        $data['item_types'][$it_id] = $group_condition_name;
        $data['item_methods'][$it_id] = $group_fee > 0 ? '선불' : '무료';
    }
    unset($item);

    /*
     * 개별배송 / 묶음배송 분리.
     */
    $groups = array();

    foreach ($items as $it_id => $item) {
        if ($item['group_id'] < 1) {
            $data['item_charges'][$it_id] = $item['fee'];
            $data['shipping_total'] += $item['fee'];
            continue;
        }

        $gid = $item['group_id'];

        if (!isset($groups[$gid])) {
            $groups[$gid] = array(
                'name' => $item['group_name'],
                'method' => $item['calc_method'],
                'items' => array()
            );
        }

        $groups[$gid]['items'][$it_id] = $item['fee'];
    }

    /*
     * 묶음배송:
     * MAX = 그룹에서 가장 높은 배송비 1회
     * MIN = 그룹에서 가장 낮은 배송비 1회
     *
     * CSV 합계가 실제 배송비와 일치하도록
     * 대표 상품 한 행에만 그룹 배송비를 기록합니다.
     */
    foreach ($groups as $gid => $group) {
        if (empty($group['items'])) {
            continue;
        }

        $selected_it_id = '';
        $selected_fee = null;

        foreach ($group['items'] as $it_id => $fee) {
            if ($selected_fee === null) {
                $selected_it_id = $it_id;
                $selected_fee = $fee;
                continue;
            }

            if (
                $group['method'] === 'MIN' &&
                $fee < $selected_fee
            ) {
                $selected_it_id = $it_id;
                $selected_fee = $fee;
            }

            if (
                $group['method'] !== 'MIN' &&
                $fee > $selected_fee
            ) {
                $selected_it_id = $it_id;
                $selected_fee = $fee;
            }
        }

        $selected_fee = max(0, (int)$selected_fee);

        if ($selected_it_id !== '') {
            $data['item_charges'][$selected_it_id] = $selected_fee;
            $data['shipping_total'] += $selected_fee;
        }

        foreach ($group['items'] as $it_id => $fee) {
            $data['item_groups'][$it_id] =
                $group['name'] . ' (' . $group['method'] . ')';

            /*
             * 대표 상품이 아닌 그룹 상품은 배송비 0원 표시.
             * "무료"로 오해하지 않도록 결제구분은 묶음배송으로 표시.
             */
            if ($it_id !== $selected_it_id) {
                $data['item_methods'][$it_id] = '묶음배송';
            } else {
                $data['item_methods'][$it_id] =
                    $selected_fee > 0 ? '선불' : '무료';
            }

            $data['item_types'][$it_id] .=
                ' / ' . $group['name'] . ' ' . $group['method'];
        }
    }

    return $data;
}

/*
 * 같은 주문번호의 최종 배송비 계산.
 *
 * 브랜드 계정:
 *   현재 로그인 브랜드의 상품만 기준.
 *
 * 최고관리자:
 *   주문번호에 포함된 각 브랜드 배송비를 계산한 뒤 합산.
 *
 * 묶음배송의 조건부무료/금액구간 판단은
 * 브랜드 구분 없이 같은 주문번호의 모든 상품금액을 기준으로 계산됩니다.
 */

/*
 * ============================================================
 * 최종 배송비 전용 계산
 * ============================================================
 *
 * 중요:
 * 이 함수는 CSV의 '배송비' 컬럼이나 item_charges를 전혀 참조하지 않습니다.
 *
 * 오직 아래 데이터만 보고 최종 배송비를 새로 계산합니다.
 * - 같은 주문번호의 상품가격/수량/옵션가격
 * - donuts_delivery_product_settings
 * - donuts_delivery_conditions
 * - donuts_delivery_condition_ranges
 * - donuts_delivery_groups
 *
 * 묶음배송 상품의 conditional / amount_range 기준금액은
 * 같은 주문번호의 전체 상품가격 합계를 사용합니다.
 */

function csv_final_default_special_non_group_items($items)
{
    $default_ids = array();
    $special_ids = array();

    foreach ($items as $item) {
        $it_id = isset($item['it_id']) ? trim((string)$item['it_id']) : '';
        $brand_id = isset($item['brand_id']) ? trim((string)$item['brand_id']) : '';

        if ($it_id === '') continue;

        $it_id_sql = sql_real_escape_string($it_id);
        $brand_sql = sql_real_escape_string($brand_id);
        $setting = array();

        if ($brand_id !== '') {
            $r = sql_query("
                SELECT brand_id, condition_id, group_id
                FROM donuts_delivery_product_settings
                WHERE it_id = '{$it_id_sql}'
                  AND brand_id = '{$brand_sql}'
                LIMIT 1
            ", false);
            if ($r) $setting = sql_fetch_array($r);
        }

        if (empty($setting)) {
            $r = sql_query("
                SELECT brand_id, condition_id, group_id
                FROM donuts_delivery_product_settings
                WHERE it_id = '{$it_id_sql}'
                LIMIT 1
            ", false);
            if ($r) $setting = sql_fetch_array($r);
        }

        if (!empty($setting['group_id']) && (int)$setting['group_id'] > 0) {
            continue;
        }

        $setting_brand = !empty($setting['brand_id'])
            ? trim((string)$setting['brand_id'])
            : $brand_id;

        $setting_brand_sql = sql_real_escape_string($setting_brand);
        $condition_id = !empty($setting['condition_id']) ? (int)$setting['condition_id'] : 0;
        $condition = array();
        $is_default = false;

        if ($condition_id > 0) {
            $r = sql_query("
                SELECT *
                FROM donuts_delivery_conditions
                WHERE dc_id = '{$condition_id}'
                  AND use_yn = 'Y'
                LIMIT 1
            ", false);
            if ($r) $condition = sql_fetch_array($r);

            if (!empty($condition) && !empty($condition['is_default'])) {
                $is_default = true;
            }
        }

        if (empty($condition) && $setting_brand !== '') {
            $r = sql_query("
                SELECT *
                FROM donuts_delivery_conditions
                WHERE brand_id = '{$setting_brand_sql}'
                  AND is_default = 1
                  AND use_yn = 'Y'
                ORDER BY dc_id DESC
                LIMIT 1
            ", false);
            if ($r) $condition = sql_fetch_array($r);
            if (!empty($condition)) $is_default = true;
        }

        $type = !empty($condition['dc_type']) ? trim((string)$condition['dc_type']) : '';

        if ($is_default) $default_ids[$it_id] = true;
        if (!$is_default && in_array($type, array('paid', 'quantity', 'amount_range'), true)) $special_ids[$it_id] = true;
    }

    if (empty($default_ids) || empty($special_ids)) {
        return array();
    }

    return $default_ids + $special_ids;
}

function csv_final_shipping_from_products_and_groups($od_id, $brand_id, $receiver_addr, $receiver_zip)
{
    global $g5;

    $od_id = trim((string)$od_id);
    $brand_id = trim((string)$brand_id);

    if ($od_id === '') {
        return 0;
    }

    $od_id_sql = sql_real_escape_string($od_id);

    /*
     * ==========================================================
     * 최종 배송비는 반드시 "같은 주문번호"를 하나의 계산 단위로 처리
     * ==========================================================
     *
     * 기존 배송비 컬럼은 사용하지 않습니다.
     *
     * 1. 같은 od_id의 상품을 전부 가져옴
     * 2. 주문번호 전체 상품금액을 먼저 계산
     * 3. 상품별 배송조건/묶음배송 설정 조회
     * 4. 묶음배송 상품은 주문번호 전체 상품금액으로 조건 판단
     * 5. 같은 묶음조건은 배송비 1회만 계산
     */

    $brand_where = '';

    if ($brand_id !== '') {
        $brand_id_sql = sql_real_escape_string($brand_id);
        $brand_where = " AND TRIM(i.it_brand) = '{$brand_id_sql}' ";
    }

    $result = sql_query("
        SELECT
            c.it_id,
            TRIM(i.it_brand) AS item_brand_id,
            SUM(
                IF(
                    c.io_type = 1,
                    c.io_price * c.ct_qty,
                    (c.ct_price + c.io_price) * c.ct_qty
                )
            ) AS item_amount,
            SUM(c.ct_qty) AS item_qty
        FROM {$g5['g5_shop_cart_table']} c
        INNER JOIN {$g5['g5_shop_item_table']} i
            ON i.it_id = c.it_id
        WHERE c.od_id = '{$od_id_sql}'
        {$brand_where}
        GROUP BY c.it_id, i.it_brand
        ORDER BY MIN(c.ct_id)
    ", false);

    if (!$result) {
        return 0;
    }

    $items = array();

    while ($row = sql_fetch_array($result)) {
        $it_id = trim((string)$row['it_id']);

        if ($it_id === '') {
            continue;
        }

        $items[] = array(
            'it_id' => $it_id,
            'brand_id' => trim((string)$row['item_brand_id']),
            'amount' => (int)$row['item_amount'],
            'qty' => (int)$row['item_qty']
        );
    }

    if (empty($items)) {
        return 0;
    }

    // 실제 묶음배송을 제외한 기본+유료/수량/금액구간 상품만 특례 대상으로 지정
    $default_special_non_group_items =
        csv_final_default_special_non_group_items($items);

    /*
     * 브랜드 필터와 무관하게 무료배송 기준은
     * 동일 주문번호 전체 상품금액으로 다시 구합니다.
     */
    $total_row = sql_fetch("
        SELECT
            SUM(
                IF(
                    io_type = 1,
                    io_price * ct_qty,
                    (ct_price + io_price) * ct_qty
                )
            ) AS total_amount
        FROM {$g5['g5_shop_cart_table']}
        WHERE od_id = '{$od_id_sql}'
    ");

    $order_total = isset($total_row['total_amount'])
        ? (int)$total_row['total_amount']
        : 0;

    if ($order_total <= 0) {
        foreach ($items as $tmp) {
            $order_total += (int)$tmp['amount'];
        }
    }

    $individual_total = 0;
    $bundle_candidates = array();

    foreach ($items as $item) {

        $it_id = $item['it_id'];
        $item_brand = $item['brand_id'];
        $item_amount = (int)$item['amount'];
        $item_qty = (int)$item['qty'];

        $it_id_sql = sql_real_escape_string($it_id);
        $item_brand_sql = sql_real_escape_string($item_brand);

        /*
         * 상품 배송 설정.
         *
         * 중요:
         * dps_id / updated_at 같은 컬럼 존재 여부를 가정하지 않습니다.
         * 이전 패치의 fallback SQL이 서버 스키마와 다르면 조회 자체가 실패할 수
         * 있었기 때문에 이번에는 실제 필요한 컬럼만 사용합니다.
         */
        $setting = array();

        if ($item_brand !== '') {
            $sr = sql_query("
                SELECT brand_id, condition_id, group_id
                FROM donuts_delivery_product_settings
                WHERE it_id = '{$it_id_sql}'
                  AND brand_id = '{$item_brand_sql}'
                LIMIT 1
            ", false);

            if ($sr) {
                $setting = sql_fetch_array($sr);
            }
        }

        if (empty($setting)) {
            $sr = sql_query("
                SELECT brand_id, condition_id, group_id
                FROM donuts_delivery_product_settings
                WHERE it_id = '{$it_id_sql}'
                LIMIT 1
            ", false);

            if ($sr) {
                $setting = sql_fetch_array($sr);
            }
        }

        $setting_brand = !empty($setting['brand_id'])
            ? trim((string)$setting['brand_id'])
            : $item_brand;

        $setting_brand_sql = sql_real_escape_string($setting_brand);

        $condition_id = !empty($setting['condition_id'])
            ? (int)$setting['condition_id']
            : 0;

        $group_id = !empty($setting['group_id'])
            ? (int)$setting['group_id']
            : 0;

        /*
         * 배송조건 조회.
         */
        $condition = array();

        if ($condition_id > 0) {
            $cr = sql_query("
                SELECT *
                FROM donuts_delivery_conditions
                WHERE dc_id = '{$condition_id}'
                  AND use_yn = 'Y'
                LIMIT 1
            ", false);

            if ($cr) {
                $condition = sql_fetch_array($cr);
            }
        }

        if (empty($condition) && $setting_brand !== '') {
            $cr = sql_query("
                SELECT *
                FROM donuts_delivery_conditions
                WHERE brand_id = '{$setting_brand_sql}'
                  AND is_default = 1
                  AND use_yn = 'Y'
                ORDER BY dc_id DESC
                LIMIT 1
            ", false);

            if ($cr) {
                $condition = sql_fetch_array($cr);
            }
        }

        /*
         * 묶음배송 그룹 조회.
         */
        $group = array();

        if ($group_id > 0) {
            $gr = sql_query("
                SELECT *
                FROM donuts_delivery_groups
                WHERE dg_id = '{$group_id}'
                  AND use_yn = 'Y'
                LIMIT 1
            ", false);

            if ($gr) {
                $group = sql_fetch_array($gr);
            }

            if (empty($group)) {
                $group_id = 0;
            }
        }

        /*
         * ----------------------------------------------------------
         * 묶음배송 판정
         * ----------------------------------------------------------
         *
         * group_id가 정상적으로 존재하면 당연히 묶음배송.
         *
         * 추가로, 현재 운영 데이터처럼 상품 설정의 group_id가 CSV 조회에서
         * 누락되어도 같은 주문번호 안에 2개 이상의 상품이 있고
         * 같은 conditional/amount_range 조건을 사용하는 경우에는
         * 같은 배송조건 자체를 묶음조건으로 취급합니다.
         *
         * 이것이 2026081809541739 케이스를 위한 핵심 수정입니다.
         */
        $is_bundle = ($group_id > 0);

        $condition_type = !empty($condition['dc_type'])
            ? trim((string)$condition['dc_type'])
            : '';

        $is_default_paid_special_item =
            isset($default_special_non_group_items[$it_id]);

        if (
            !$is_default_paid_special_item &&
            !$is_bundle &&
            count($items) > 1 &&
            in_array($condition_type, array('conditional', 'amount_range'), true)
        ) {
            $is_bundle = true;
        }

        /*
         * 배송비 계산 기준 금액.
         *
         * 묶음배송이면 무조건 같은 주문번호 전체 상품금액.
         */
        $base_amount = $is_bundle
            ? $order_total
            : $item_amount;

        if (!empty($condition)) {
            $fee = csv_new_delivery_condition_fee(
                $condition,
                $base_amount,
                $item_qty
            );

            $is_jeju = (
                function_exists('mb_strpos') &&
                mb_strpos((string)$receiver_addr, '제주') !== false
            );

            if ($is_jeju && !empty($condition['dc_jeju_use'])) {
                $fee += max(0, (int)$condition['dc_jeju_price']);
            } elseif (
                !empty($condition['dc_island_use']) &&
                csv_new_delivery_is_island_zip($receiver_zip)
            ) {
                $fee += max(0, (int)$condition['dc_island_price']);
            }
        } else {
            /*
             * 조건 테이블이 없는 경우만 브랜드 기본 배송비 fallback.
             */
            $bsr = sql_query("
                SELECT *
                FROM donuts_brand_settings
                WHERE brand_id = '{$setting_brand_sql}'
                LIMIT 1
            ", false);

            $brand_settings = $bsr
                ? sql_fetch_array($bsr)
                : array();

            $fee = csv_brand_send_cost(
                $brand_settings,
                $base_amount
            );
        }

        $fee = max(0, (int)$fee);

        if (!$is_bundle) {
            $individual_total += $fee;
            continue;
        }

        /*
         * 묶음배송 key.
         *
         * group_id가 있으면 실제 그룹 ID 사용.
         * group_id가 조회되지 않은 운영 데이터는
         * 같은 주문번호 + 브랜드 + 배송조건 ID를 하나의 묶음으로 처리.
         */
        if ($group_id > 0) {
            $bundle_key =
                $od_id . '|G|' .
                $setting_brand . '|' .
                $group_id;

            $method = (
                !empty($group['calc_method']) &&
                strtoupper($group['calc_method']) === 'MIN'
            ) ? 'MIN' : 'MAX';
        } else {
            $bundle_key =
                $od_id . '|C|' .
                $setting_brand . '|' .
                $condition_id;

            $method = 'MAX';
        }

        if (!isset($bundle_candidates[$bundle_key])) {
            $bundle_candidates[$bundle_key] = array(
                'method' => $method,
                'fees' => array()
            );
        }

        $bundle_candidates[$bundle_key]['fees'][] = $fee;
    }

    /*
     * 같은 주문번호 안에서 묶음배송 그룹별 1회 부과.
     */
    $bundle_total = 0;

    foreach ($bundle_candidates as $bundle) {
        if (empty($bundle['fees'])) {
            continue;
        }

        if ($bundle['method'] === 'MIN') {
            $bundle_total += min($bundle['fees']);
        } else {
            $bundle_total += max($bundle['fees']);
        }
    }

    return max(
        0,
        (int)$individual_total + (int)$bundle_total
    );
}

function csv_new_delivery_final_order_shipping($od_id, $brand_id, $receiver_addr, $receiver_zip)
{
    /*
     * 최종 배송비는 '배송비' 컬럼을 참조하지 않습니다.
     * 상품가격 + 배송조건 + 묶음배송 그룹 조건만으로 별도 계산합니다.
     */
    return csv_final_shipping_from_products_and_groups(
        $od_id,
        $brand_id,
        $receiver_addr,
        $receiver_zip
    );
}

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
    '최종 배송비',
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
        (string)$row['od_b_zip1'] .
        (string)$row['od_b_zip2'];

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
            csv_new_delivery_final_order_shipping(
                $row['od_id'],
                $final_shipping_brand_id,
                $receiver_addr,
                $receiver_zip
            );
    }

    $final_shipping_amount =
        (int)$final_shipping_cache[$final_shipping_cache_key];

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
        $receiver_zip,

        '', // 통관번호: 현재 확인한 테이블/파일에 전용 필드를 확인하지 못해 비움

        $row['od_memo'],

        $row_shipping_amount,
        $shipping_method,
        $shipping_type,
        ($delivery_calc && isset($delivery_calc['item_groups'][$row['it_id']]))
            ? $delivery_calc['item_groups'][$row['it_id']]
            : '',

        $final_shipping_amount,

        $row['od_status'],
        $row['od_settle_case'],

        $settle_price,
        $settle_date
    ));
}

fclose($fp);
exit;

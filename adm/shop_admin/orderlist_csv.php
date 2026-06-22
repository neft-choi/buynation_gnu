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

fputcsv($fp, array(
    '주문번호',
    '주문일시',
    '주문상태',
    '회원ID',
    '주문자',
    '주문자전화',
    '받는분',
    '결제수단',
    '상품수',
    '주문금액',
    '입금금액',
    '취소금액',
    '쿠폰금액',
    '미수금',
    '상품명'
));

$sql = "
select *,
       (od_cart_coupon + od_coupon + od_send_coupon) as couponprice
from {$g5['g5_shop_order_table']}
{$sql_search}
order by {$sort1} {$sort2}
";

$result = sql_query($sql);

while($row = sql_fetch_array($result)) {

    $items = array();

    $item_sql = "
        select it_name
        from {$g5['g5_shop_cart_table']}
        where od_id = '{$row['od_id']}'
    ";

    $item_result = sql_query($item_sql);

    while($item = sql_fetch_array($item_result)) {
        $items[] = $item['it_name'];
    }

    $item_names = implode(' | ', $items);

    fputcsv($fp, array(
        $row['od_id'],
        $row['od_time'],
        $row['od_status'],
        $row['mb_id'],
        $row['od_name'],
        $row['od_tel'],
        $row['od_b_name'],
        $row['od_settle_case'],
        $row['od_cart_count'],
        $row['od_cart_price'] + $row['od_send_cost'] + $row['od_send_cost2'],
        $row['od_receipt_price'],
        $row['od_cancel_price'],
        $row['couponprice'],
        $row['od_misu'],
        $item_names
    ));
}

fclose($fp);
exit;
<?php
$sub_menu = '400300';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "r");

if (isset($sfl) && $sfl && !in_array($sfl, array('it_name', 'it_id', 'it_maker', 'it_brand', 'it_model', 'it_origin', 'it_sell_email'))) {
    $sfl = '';
}

$where = " and ";
$sql_search = "";
if ($stx != "") {
    if ($sfl != "") {
        $sql_search .= " $where $sfl like '%$stx%' ";
        $where = " and ";
    }
}

if ($sca != "") {
    $sql_search .= " $where (a.ca_id like '$sca%' or a.ca_id2 like '$sca%' or a.ca_id3 like '$sca%') ";
}

$sql_common = " from {$g5['g5_shop_item_table']} a ,
                     {$g5['g5_shop_category_table']} b
               where (a.ca_id = b.ca_id";

if ($is_admin != 'super') {
    $sql_common .= " and a.it_brand = '{$member['mb_id']}'";
}
$sql_common .= ") ";
$sql_common .= $sql_search;

if (!$sst) {
    $sst  = "it_id";
    $sod = "desc";
}
$sql_order = "order by $sst $sod";

$sql = " select a.*, b.ca_name $sql_common $sql_order ";
$result = sql_query($sql);

$filename = "상품목록_" . date("Ymd") . ".csv";

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

echo "\xEF\xBB\xBF";

$output = fopen('php://output', 'w');

$headers = array(
    '상품코드', '카테고리명', '상품명', '브랜드(계정)', '모델명', 
    '시중가격', '판매가격', '재고수량', '판매여부', '등록일'
);
fputcsv($output, $headers);

while ($row = sql_fetch_array($result)) {
    $it_use = ($row['it_use'] == 1) ? '판매중' : '판매중지';
    
    $csv_line = array(
        $row['it_id'],
        $row['ca_name'],
        $row['it_name'],
        $row['it_brand'],
        $row['it_model'],
        $row['it_cust_price'],
        $row['it_price'],
        $row['it_stock_qty'],
        $it_use,
        $row['it_time']
    );
    
    fputcsv($output, $csv_line);
}

fclose($output);
exit;
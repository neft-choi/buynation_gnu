<?php
$sub_menu = '710200';
include_once('./_common.php');

header('Content-Type: application/json; charset=utf-8');

if (!$is_member) {
    echo json_encode(array(
        'success' => false,
        'message' => '로그인이 필요합니다.',
        'items' => array()
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

auth_check_menu($auth, $sub_menu, "r");

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

if ($keyword === '') {
    echo json_encode(array(
        'success' => false,
        'message' => '검색어를 입력해 주세요.',
        'items' => array()
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

$keyword_sql = sql_real_escape_string($keyword);
$mb_id_sql = sql_real_escape_string($member['mb_id']);

$setting = sql_fetch("
    SELECT contribution_rate, discount_rate
    FROM donuts_dotty_settings
    WHERE mb_id = '{$mb_id_sql}'
    LIMIT 1
");

$contribution_rate = isset($setting['contribution_rate']) ? (float)$setting['contribution_rate'] : 0;
$discount_rate = isset($setting['discount_rate']) ? (float)$setting['discount_rate'] : 0;

// 이미 등록한 상품은 검색 결과에서 제외
$sql = "
    SELECT
        i.it_id,
        i.it_name,
        i.it_price,
        i.it_brand
    FROM {$g5['g5_shop_item_table']} i
    LEFT JOIN donuts_dotty_items d
        ON d.dotty_mb_id = '{$mb_id_sql}'
       AND d.it_id = i.it_id
    WHERE d.id IS NULL
      AND i.it_use = 1
      AND (
            i.it_name LIKE '%{$keyword_sql}%'
            OR i.it_id LIKE '%{$keyword_sql}%'
          )
    ORDER BY i.it_time DESC
    LIMIT 30
";

$result = sql_query($sql);

$items = array();

while ($row = sql_fetch_array($result)) {
    $items[] = array(
        'it_id' => $row['it_id'],
        'it_name' => $row['it_name'],
        'it_price' => (int)$row['it_price'],
        'it_brand' => $row['it_brand'],
        'contribution_rate' => $contribution_rate,
        'discount_rate' => $discount_rate,
        'image_url' => shop_item_url($row['it_id']) ? G5_SHOP_URL . '/img/no_image.gif' : G5_SHOP_URL . '/img/no_image.gif'
    );
}

echo json_encode(array(
    'success' => true,
    'message' => '',
    'items' => $items
), JSON_UNESCAPED_UNICODE);
exit;

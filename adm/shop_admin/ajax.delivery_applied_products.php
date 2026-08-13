<?php
$sub_menu = '400760';
include_once('./_common.php');

header('Content-Type: application/json; charset=utf-8');

function delivery_applied_json($success, $message = '', $items = array())
{
    echo json_encode(array(
        'success' => (bool)$success,
        'message' => $message,
        'items'   => $items
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

if (!$is_member) {
    delivery_applied_json(false, '로그인이 필요합니다.');
}

auth_check_menu($auth, $sub_menu, 'r');

$condition_id = isset($_GET['condition_id']) ? (int)$_GET['condition_id'] : 0;
$request_brand_id = isset($_GET['brand_id']) ? trim($_GET['brand_id']) : '';

if ($condition_id < 1) {
    delivery_applied_json(true, '', array());
}

/*
 * 현재 브랜드 결정
 */
$current_mb_id = isset($member['mb_id']) ? trim($member['mb_id']) : '';
$current_mb_id_sql = sql_real_escape_string($current_mb_id);

$current_brand = sql_fetch("
    SELECT brand_id
    FROM donuts_brand
    WHERE TRIM(brand_id) = '{$current_mb_id_sql}'
    LIMIT 1
");

if (!empty($current_brand['brand_id'])) {

    $brand_id = trim($current_brand['brand_id']);

} elseif ($is_admin === 'super') {

    $brand_id = $request_brand_id;

    if ($brand_id === '') {
        delivery_applied_json(false, '브랜드 정보가 없습니다.');
    }

    $brand_id_check_sql = sql_real_escape_string($brand_id);

    $brand_check = sql_fetch("
        SELECT brand_id
        FROM donuts_brand
        WHERE TRIM(brand_id) = '{$brand_id_check_sql}'
        LIMIT 1
    ");

    if (empty($brand_check['brand_id'])) {
        delivery_applied_json(false, '올바르지 않은 브랜드입니다.');
    }

    $brand_id = trim($brand_check['brand_id']);

} else {

    delivery_applied_json(false, '브랜드 상품 조회 권한이 없습니다.');
}

$brand_id_sql = sql_real_escape_string($brand_id);

/*
 * 1단계
 *
 * 적용 상품 갯수를 세는 deliverymanage.php의 조건과 동일하게
 * donuts_delivery_product_settings 테이블만 조회합니다.
 *
 * JOIN을 제거했습니다.
 * 이전 AJAX SQL 오류는 여기서 상품테이블까지 JOIN했던 부분에서 발생할
 * 가능성이 높으므로, 적용관계와 상품정보 조회를 완전히 분리합니다.
 */
$setting_sql = "
    SELECT it_id
    FROM donuts_delivery_product_settings
    WHERE brand_id = '{$brand_id_sql}'
      AND condition_id = '{$condition_id}'
    ORDER BY it_id ASC
";

$setting_result = sql_query($setting_sql, false);

if (!$setting_result) {
    delivery_applied_json(
        false,
        '적용 상품 설정 조회 SQL 오류가 발생했습니다.'
    );
}

$items = array();

/*
 * 2단계
 *
 * 설정 테이블에서 얻은 it_id 하나씩 상품테이블에서 조회합니다.
 * 상품이 삭제되어 있어도 적용 설정 자체는 목록에 표시합니다.
 */
while ($setting = sql_fetch_array($setting_result)) {

    $it_id = isset($setting['it_id']) ? trim($setting['it_id']) : '';

    if ($it_id === '') {
        continue;
    }

    $it_id_sql = sql_real_escape_string($it_id);

    $item_result = sql_query("
        SELECT it_id, it_name, it_price
        FROM {$g5['g5_shop_item_table']}
        WHERE it_id = '{$it_id_sql}'
        LIMIT 1
    ", false);

    if ($item_result) {

        $item = sql_fetch_array($item_result);

    } else {

        $item = array();
    }

    $items[] = array(
        'it_id' => $it_id,
        'it_name' => !empty($item['it_name'])
            ? $item['it_name']
            : '(상품정보를 찾을 수 없음)',
        'it_price' => isset($item['it_price'])
            ? (int)$item['it_price']
            : 0,
        'it_image' => get_it_image($it_id, 50, 50)
    );
}

delivery_applied_json(true, '', $items);

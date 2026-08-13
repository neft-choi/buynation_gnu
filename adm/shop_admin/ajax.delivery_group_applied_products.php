<?php
$sub_menu = '400760';
include_once('./_common.php');

header('Content-Type: application/json; charset=utf-8');

function delivery_group_applied_json($success, $message = '', $items = array())
{
    echo json_encode(array(
        'success' => (bool)$success,
        'message' => $message,
        'items'   => $items
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

if (!$is_member) {
    delivery_group_applied_json(false, '로그인이 필요합니다.');
}

auth_check_menu($auth, $sub_menu, 'r');

$group_id = isset($_GET['group_id']) ? (int)$_GET['group_id'] : 0;
$request_brand_id = isset($_GET['brand_id']) ? trim($_GET['brand_id']) : '';

if ($group_id < 1) {
    delivery_group_applied_json(true, '', array());
}

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
        delivery_group_applied_json(false, '브랜드 정보가 없습니다.');
    }

    $brand_check_sql = sql_real_escape_string($brand_id);

    $brand_check = sql_fetch("
        SELECT brand_id
        FROM donuts_brand
        WHERE TRIM(brand_id) = '{$brand_check_sql}'
        LIMIT 1
    ");

    if (empty($brand_check['brand_id'])) {
        delivery_group_applied_json(false, '올바르지 않은 브랜드입니다.');
    }

    $brand_id = trim($brand_check['brand_id']);

} else {

    delivery_group_applied_json(false, '브랜드 상품 조회 권한이 없습니다.');
}

$brand_id_sql = sql_real_escape_string($brand_id);

/*
 * 1단계:
 * 묶음배송 그룹에 연결된 상품코드만 설정테이블에서 조회.
 * JOIN 없이 단순 쿼리로 구성합니다.
 */
$setting_result = sql_query("
    SELECT it_id, condition_id
    FROM donuts_delivery_product_settings
    WHERE brand_id = '{$brand_id_sql}'
      AND group_id = '{$group_id}'
    ORDER BY it_id ASC
", false);

if (!$setting_result) {
    delivery_group_applied_json(
        false,
        '묶음배송 현재 적용 상품 설정 조회 SQL 오류가 발생했습니다.'
    );
}

$items = array();

while ($setting = sql_fetch_array($setting_result)) {

    $it_id = isset($setting['it_id']) ? trim($setting['it_id']) : '';

    if ($it_id === '') {
        continue;
    }

    $it_id_sql = sql_real_escape_string($it_id);

    /*
     * 2단계:
     * 상품정보 개별 조회.
     */
    $item_result = sql_query("
        SELECT it_id, it_name, it_price
        FROM {$g5['g5_shop_item_table']}
        WHERE it_id = '{$it_id_sql}'
        LIMIT 1
    ", false);

    $item = $item_result ? sql_fetch_array($item_result) : array();

    /*
     * 3단계:
     * 배송조건명 조회.
     */
    $dc_name = '';
    $condition_id = isset($setting['condition_id'])
        ? (int)$setting['condition_id']
        : 0;

    if ($condition_id > 0) {

        $condition_result = sql_query("
            SELECT dc_name
            FROM donuts_delivery_conditions
            WHERE dc_id = '{$condition_id}'
              AND brand_id = '{$brand_id_sql}'
            LIMIT 1
        ", false);

        if ($condition_result) {
            $condition = sql_fetch_array($condition_result);
            $dc_name = isset($condition['dc_name'])
                ? $condition['dc_name']
                : '';
        }
    }

    $items[] = array(
        'it_id' => $it_id,
        'it_name' => !empty($item['it_name'])
            ? $item['it_name']
            : '(상품정보를 찾을 수 없음)',
        'it_price' => isset($item['it_price'])
            ? (int)$item['it_price']
            : 0,
        'condition_id' => $condition_id,
        'dc_name' => $dc_name,
        'it_image' => get_it_image($item['it_id'], 50, 50)
    );
}

delivery_group_applied_json(true, '', $items);

<?php
$sub_menu = '400760';
include_once('./_common.php');

header('Content-Type: application/json; charset=utf-8');

function delivery_group_search_json($success, $message = '', $items = array())
{
    echo json_encode(array(
        'success' => (bool)$success,
        'message' => $message,
        'items'   => $items
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

if (!$is_member) {
    delivery_group_search_json(false, '로그인이 필요합니다.');
}

auth_check_menu($auth, $sub_menu, 'r');

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$source_group = isset($_GET['source_group']) ? trim($_GET['source_group']) : 'all';
$target_group_id = isset($_GET['target_group_id']) ? (int)$_GET['target_group_id'] : 0;
$request_brand_id = isset($_GET['brand_id']) ? trim($_GET['brand_id']) : '';

if ($keyword === '') {
    delivery_group_search_json(true, '', array());
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
        delivery_group_search_json(false, '브랜드 정보가 없습니다.');
    }

    $brand_check_sql = sql_real_escape_string($brand_id);

    $brand_check = sql_fetch("
        SELECT brand_id
        FROM donuts_brand
        WHERE TRIM(brand_id) = '{$brand_check_sql}'
        LIMIT 1
    ");

    if (empty($brand_check['brand_id'])) {
        delivery_group_search_json(false, '올바르지 않은 브랜드입니다.');
    }

    $brand_id = trim($brand_check['brand_id']);

} else {

    delivery_group_search_json(false, '브랜드 상품 조회 권한이 없습니다.');
}

$brand_id_sql = sql_real_escape_string($brand_id);
$keyword_sql = sql_real_escape_string($keyword);

/*
 * 1단계: 상품 테이블에서 현재 브랜드 + 상품명/상품코드 검색
 * 커스텀 배송 테이블과 JOIN하지 않아 검색 SQL 오류 가능성을 줄입니다.
 */
$item_sql = "
    SELECT
        it_id,
        it_name,
        it_price
    FROM {$g5['g5_shop_item_table']}
    WHERE TRIM(it_brand) = '{$brand_id_sql}'
      AND (
            it_name LIKE '%{$keyword_sql}%'
            OR it_id LIKE '%{$keyword_sql}%'
          )
    ORDER BY
        CASE WHEN it_id = '{$keyword_sql}' THEN 0 ELSE 1 END,
        it_time DESC,
        it_id DESC
    LIMIT 50
";

$item_result = sql_query($item_sql, false);

if (!$item_result) {
    delivery_group_search_json(false, '묶음배송 상품 검색 SQL 오류가 발생했습니다.');
}

$items = array();

while ($item = sql_fetch_array($item_result)) {

    $it_id = isset($item['it_id']) ? trim($item['it_id']) : '';

    if ($it_id === '') {
        continue;
    }

    $it_id_sql = sql_real_escape_string($it_id);

    /*
     * 2단계: 현재 배송조건/묶음배송 그룹 조회
     */
    $setting = array(
        'condition_id' => 0,
        'group_id' => 0
    );

    $setting_result = sql_query("
        SELECT condition_id, group_id
        FROM donuts_delivery_product_settings
        WHERE brand_id = '{$brand_id_sql}'
          AND it_id = '{$it_id_sql}'
        LIMIT 1
    ", false);

    if ($setting_result) {
        $setting_row = sql_fetch_array($setting_result);

        if (!empty($setting_row)) {
            $setting['condition_id'] = isset($setting_row['condition_id'])
                ? (int)$setting_row['condition_id']
                : 0;

            $setting['group_id'] = isset($setting_row['group_id'])
                ? (int)$setting_row['group_id']
                : 0;
        }
    }

    $group_id = (int)$setting['group_id'];

    /*
     * 대상 그룹에 이미 포함된 상품은 검색결과에서 제외
     */
    if ($target_group_id > 0 && $group_id === $target_group_id) {
        continue;
    }

    /*
     * 현재 소속 필터
     */
    if ($source_group === 'none' && $group_id !== 0) {
        continue;
    }

    if (
        $source_group !== 'all' &&
        $source_group !== 'none' &&
        ctype_digit($source_group) &&
        $group_id !== (int)$source_group
    ) {
        continue;
    }

    $dc_name = '';

    if ((int)$setting['condition_id'] > 0) {
        $condition_id = (int)$setting['condition_id'];

        $condition_result = sql_query("
            SELECT dc_name
            FROM donuts_delivery_conditions
            WHERE dc_id = '{$condition_id}'
              AND brand_id = '{$brand_id_sql}'
            LIMIT 1
        ", false);

        if ($condition_result) {
            $condition = sql_fetch_array($condition_result);
            $dc_name = isset($condition['dc_name']) ? $condition['dc_name'] : '';
        }
    }

    $dg_name = '';

    if ($group_id > 0) {
        $group_result = sql_query("
            SELECT dg_name
            FROM donuts_delivery_groups
            WHERE dg_id = '{$group_id}'
              AND brand_id = '{$brand_id_sql}'
            LIMIT 1
        ", false);

        if ($group_result) {
            $group = sql_fetch_array($group_result);
            $dg_name = isset($group['dg_name']) ? $group['dg_name'] : '';
        }
    }

    $items[] = array(
        'it_id' => $it_id,
        'it_name' => isset($item['it_name']) ? $item['it_name'] : '',
        'it_price' => isset($item['it_price']) ? (int)$item['it_price'] : 0,
        'it_image' => get_it_image($item['it_id'], 50, 50),
        'condition_id' => (int)$setting['condition_id'],
        'group_id' => $group_id,
        'dc_name' => $dc_name,
        'dg_name' => $dg_name
    );
}

delivery_group_search_json(true, '', $items);

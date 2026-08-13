<?php
$sub_menu = '400760';
include_once('./_common.php');
include_once(G5_LIB_PATH . '/donuts_delivery.lib.php');

header('Content-Type: application/json; charset=utf-8');

function delivery_search_json($success, $message = '', $items = array(), $debug = '')
{
    $data = array(
        'success' => (bool)$success,
        'message' => $message,
        'items' => $items
    );

    // 서버 내부 확인용. 화면에는 일반적으로 사용하지 않습니다.
    if ($debug !== '') {
        $data['debug'] = $debug;
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

if (!$is_member) {
    delivery_search_json(false, '로그인이 필요합니다.');
}

auth_check_menu($auth, $sub_menu, 'r');

$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$condition_id = isset($_GET['condition_id']) ? (int)$_GET['condition_id'] : 0;
$filter = isset($_GET['filter']) ? trim($_GET['filter']) : 'all';

if ($keyword === '') {
    delivery_search_json(true, '', array());
}

/*
 * 현재 관리 브랜드 결정
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
    $brand_id = isset($_GET['brand_id']) ? trim($_GET['brand_id']) : '';

    if ($brand_id === '') {
        delivery_search_json(false, '검색할 브랜드가 없습니다.');
    }

    $brand_id_check_sql = sql_real_escape_string($brand_id);

    $brand_check = sql_fetch("
        SELECT brand_id
        FROM donuts_brand
        WHERE TRIM(brand_id) = '{$brand_id_check_sql}'
        LIMIT 1
    ");

    if (empty($brand_check['brand_id'])) {
        delivery_search_json(false, '올바르지 않은 브랜드입니다.');
    }

    $brand_id = trim($brand_check['brand_id']);
} else {
    delivery_search_json(false, '브랜드 상품 검색 권한이 없습니다.');
}

$brand_id_sql = sql_real_escape_string($brand_id);
$keyword_sql = sql_real_escape_string($keyword);

/*
 * 중요:
 * 상품 검색 자체는 g5_shop_item 단일 테이블만 조회합니다.
 *
 * 이전 버전은 배송 커스텀 테이블을 LEFT JOIN했는데,
 * 기존 설치된 커스텀 테이블의 컬럼 구조가 조금이라도 다르면
 * 전체 상품검색 SQL이 실패했습니다.
 *
 * 여기서는 이미 확인된 영카트 상품 컬럼만 사용합니다.
 */
$sql = "
    SELECT
        it_id,
        it_name,
        it_price,
        it_stock_qty,
        it_use,
        it_soldout
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

$result = sql_query($sql, false);

if (!$result) {
    /*
     * 혹시 TRIM() 사용 환경 문제까지 대비한 2차 검색.
     */
    $sql2 = "
        SELECT
            it_id,
            it_name,
            it_price,
            it_stock_qty,
            it_use,
            it_soldout
        FROM {$g5['g5_shop_item_table']}
        WHERE it_brand = '{$brand_id_sql}'
          AND (
                it_name LIKE '%{$keyword_sql}%'
                OR it_id LIKE '%{$keyword_sql}%'
              )
        ORDER BY it_id DESC
        LIMIT 50
    ";

    $result = sql_query($sql2, false);

    if (!$result) {
        delivery_search_json(false, '상품 검색 SQL 실행 중 오류가 발생했습니다. 상품 테이블 조회 자체를 확인해 주세요.');
    }
}

$items = array();

while ($row = sql_fetch_array($result)) {

    $it_id_sql = sql_real_escape_string($row['it_id']);

    /*
     * 배송 설정은 상품 검색과 분리해서 조회합니다.
     * 커스텀 배송 테이블 조회가 실패하더라도 상품 자체는 검색 결과에 표시합니다.
     */
    $setting = array(
        'condition_id' => 0,
        'group_id' => 0,
        'dc_name' => '',
        'dg_name' => ''
    );

    $ps_result = sql_query("
        SELECT condition_id, group_id
        FROM donuts_delivery_product_settings
        WHERE brand_id = '{$brand_id_sql}'
          AND it_id = '{$it_id_sql}'
        LIMIT 1
    ", false);

    if ($ps_result) {
        $ps = sql_fetch_array($ps_result);

        if (!empty($ps)) {
            $setting['condition_id'] = isset($ps['condition_id']) ? (int)$ps['condition_id'] : 0;
            $setting['group_id'] = isset($ps['group_id']) ? (int)$ps['group_id'] : 0;

            if ($setting['condition_id'] > 0) {
                $dc_result = sql_query("
                    SELECT dc_name
                    FROM donuts_delivery_conditions
                    WHERE dc_id = '{$setting['condition_id']}'
                      AND brand_id = '{$brand_id_sql}'
                    LIMIT 1
                ", false);

                if ($dc_result) {
                    $dc = sql_fetch_array($dc_result);
                    $setting['dc_name'] = isset($dc['dc_name']) ? $dc['dc_name'] : '';
                }
            }

            if ($setting['group_id'] > 0) {
                $dg_result = sql_query("
                    SELECT dg_name
                    FROM donuts_delivery_groups
                    WHERE dg_id = '{$setting['group_id']}'
                      AND brand_id = '{$brand_id_sql}'
                    LIMIT 1
                ", false);

                if ($dg_result) {
                    $dg = sql_fetch_array($dg_result);
                    $setting['dg_name'] = isset($dg['dg_name']) ? $dg['dg_name'] : '';
                }
            }
        }
    }

    /*
     * 현재 배송조건 필터는 PHP에서 처리.
     * 따라서 JOIN 조건 문제로 검색 SQL 자체가 실패하지 않습니다.
     */
    if ($filter === 'target' && $condition_id > 0) {
        if ((int)$setting['condition_id'] !== $condition_id) {
            continue;
        }
    } elseif ($filter === 'other' && $condition_id > 0) {
        if ((int)$setting['condition_id'] === $condition_id) {
            continue;
        }
    }

    $items[] = array(
        'it_id' => $row['it_id'],
        'it_name' => $row['it_name'],
        'it_price' => (int)$row['it_price'],
        'it_stock_qty' => (int)$row['it_stock_qty'],
        'it_use' => (int)$row['it_use'],
        'it_image' => get_it_image($row['it_id'], 50, 50),
        'it_soldout' => (int)$row['it_soldout'],
        'condition_id' => (int)$setting['condition_id'],
        'group_id' => (int)$setting['group_id'],
        'dc_name' => $setting['dc_name'],
        'dg_name' => $setting['dg_name']
    );
}

delivery_search_json(true, '', $items);

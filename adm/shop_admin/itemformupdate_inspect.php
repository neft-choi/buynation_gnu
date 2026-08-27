<?php
/*
 * 브랜드 상품 저장 + 검수 + 분류/전체 일괄적용 범위 제한
 *
 * 핵심:
 * 기존 영카트 itemformupdate.php의 chk_ca_* / chk_all_* 기능은
 * WHERE 조건에 브랜드 범위가 없어 다른 브랜드 상품까지 변경될 수 있습니다.
 *
 * 브랜드 회원은 기존 일괄적용 처리를 먼저 차단하고,
 * 상품 저장이 끝난 뒤 현재 로그인 브랜드의 상품에 대해서만
 * 별도로 일괄적용합니다.
 */
include_once('./_common.php');
include_once(G5_LIB_PATH . '/donuts_item_inspection.lib.php');

$member_id = isset($member['mb_id']) ? trim((string)$member['mb_id']) : '';
$is_inspection_brand = $member_id !== ''
    ? donuts_item_inspection_is_brand($member_id)
    : false;

/*
 * 브랜드 계정이 아니면 기존 저장 로직을 그대로 사용합니다.
 * 최고관리자 등 비브랜드 계정의 기존 영카트 동작은 유지합니다.
 */
if (!$is_inspection_brand) {
    include('./itemformupdate.php');
    exit;
}

$brand_id = $member_id;
$brand_sql = sql_real_escape_string($brand_id);

$it_id = isset($_POST['it_id'])
    ? preg_replace('/[^A-Za-z0-9_\-]/', '', trim((string)$_POST['it_id']))
    : '';

if ($it_id === '') {
    alert('상품코드가 없습니다.');
}

$it_id_sql = sql_real_escape_string($it_id);

$current_inspection = donuts_item_inspection_get($it_id);

if (
    !empty($current_inspection['inspection_id']) &&
    $current_inspection['status'] === 'pending'
) {
    alert(
        '현재 플랫폼 검수 중인 상품은 수정할 수 없습니다.',
        './iteminspectresult.php?status=pending'
    );
}

$before = sql_fetch("
    SELECT it_id
    FROM {$g5['g5_shop_item_table']}
    WHERE it_id = '{$it_id_sql}'
    LIMIT 1
");

$request_type = !empty($before['it_id']) ? 'update' : 'new';

/*
 * 브랜드 소유권 및 승인 전 판매중지.
 */
$_POST['it_brand'] = $brand_id;
$it_brand = $brand_id;

$_POST['it_use'] = '0';
$it_use = 0;

/*
 * ----------------------------------------------------------
 * 분류적용 / 전체적용 요청 보관
 * ----------------------------------------------------------
 *
 * 원본 itemformupdate.php가 전 상품에 적용하지 못하도록
 * 체크값을 0으로 바꾸기 전에 요청내용을 따로 저장합니다.
 */
$brand_bulk_requests = array();

foreach ($_POST as $post_name => $post_value) {
    if (
        strpos($post_name, 'chk_ca_') !== 0 &&
        strpos($post_name, 'chk_all_') !== 0
    ) {
        continue;
    }

    if (empty($post_value)) {
        continue;
    }

    $scope = strpos($post_name, 'chk_ca_') === 0
        ? 'category'
        : 'all';

    $field_key = $scope === 'category'
        ? substr($post_name, strlen('chk_ca_'))
        : substr($post_name, strlen('chk_all_'));

    $field_key = trim((string)$field_key);

    if ($field_key === '') {
        continue;
    }

    $brand_bulk_requests[] = array(
        'scope' => $scope,
        'field_key' => $field_key
    );

    /*
     * 원본 영카트의 전역 일괄적용 차단.
     * $_POST뿐 아니라 _common.php에서 생성됐을 수 있는 지역변수도 0 처리.
     */
    $_POST[$post_name] = 0;
    ${$post_name} = 0;
}

/*
 * 상품테이블 실제 컬럼 목록.
 * 임의 컬럼 업데이트 방지를 위한 whitelist 역할.
 */
$shop_item_columns = array();

$column_result = sql_query("
    SHOW COLUMNS FROM {$g5['g5_shop_item_table']}
", false);

if ($column_result) {
    while ($column = sql_fetch_array($column_result)) {
        if (!empty($column['Field'])) {
            $shop_item_columns[$column['Field']] = true;
        }
    }
}

/*
 * SQL SET 절 생성 helper.
 */
function donuts_brand_bulk_sql_value($value)
{
    if (is_array($value)) {
        return null;
    }

    return "'" . sql_real_escape_string((string)$value) . "'";
}

function donuts_brand_bulk_build_set($field_key, $shop_item_columns)
{
    $sets = array();

    /*
     * 영카트 특수 묶음필드: 상품유형
     */
    if ($field_key === 'it_type') {
        for ($i = 1; $i <= 5; $i++) {
            $field = 'it_type' . $i;

            if (empty($shop_item_columns[$field])) {
                continue;
            }

            $value = isset($_POST[$field]) ? 1 : 0;
            $sets[] = "`{$field}` = '{$value}'";
        }

        return $sets;
    }

    /*
     * 영카트 특수 묶음필드: 배송비
     */
    if ($field_key === 'it_sendcost') {
        $send_fields = array(
            'it_sc_type',
            'it_sc_method',
            'it_sc_price',
            'it_sc_minimum',
            'it_sc_qty'
        );

        foreach ($send_fields as $field) {
            if (
                empty($shop_item_columns[$field]) ||
                !array_key_exists($field, $_POST)
            ) {
                continue;
            }

            $sql_value = donuts_brand_bulk_sql_value($_POST[$field]);

            if ($sql_value !== null) {
                $sets[] = "`{$field}` = {$sql_value}";
            }
        }

        return $sets;
    }

    /*
     * 일반필드
     * chk_ca_it_price -> it_price
     * chk_all_it_basic -> it_basic
     * chk_ca_it_1 -> it_1 등
     */
    if (
        isset($shop_item_columns[$field_key]) &&
        array_key_exists($field_key, $_POST)
    ) {
        $sql_value = donuts_brand_bulk_sql_value($_POST[$field_key]);

        if ($sql_value !== null) {
            $sets[] = "`{$field_key}` = {$sql_value}";
        }
    }

    return $sets;
}

/*
 * 원본 itemformupdate.php는 alert()/goto_url() 등으로 종료될 수 있으므로,
 * 실제 상품 저장 성공 후 shutdown에서 브랜드 범위 일괄적용을 수행합니다.
 */
register_shutdown_function(function () use (
    $g5,
    $it_id,
    $it_id_sql,
    $brand_id,
    $brand_sql,
    $request_type,
    $brand_bulk_requests,
    $shop_item_columns
) {
    $after = sql_fetch("
        SELECT it_id, ca_id
        FROM {$g5['g5_shop_item_table']}
        WHERE it_id = '{$it_id_sql}'
        LIMIT 1
    ");

    if (empty($after['it_id'])) {
        return;
    }

    /*
     * 현재 저장 상품은 반드시 현재 브랜드 소유 + 판매대기.
     */
    sql_query("
        UPDATE {$g5['g5_shop_item_table']}
        SET
            it_brand = '{$brand_sql}',
            it_use = 0
        WHERE it_id = '{$it_id_sql}'
    ");

    /*
     * ------------------------------------------------------
     * 브랜드 범위 일괄적용
     * ------------------------------------------------------
     *
     * 분류적용:
     *   현재 로그인 브랜드 + 현재 상품의 기본분류(ca_id)
     *
     * 전체적용:
     *   현재 로그인 브랜드의 모든 상품
     *
     * 다른 브랜드 상품은 절대 UPDATE 대상이 되지 않습니다.
     */
    foreach ($brand_bulk_requests as $request) {
        $scope = isset($request['scope'])
            ? $request['scope']
            : '';

        $field_key = isset($request['field_key'])
            ? $request['field_key']
            : '';

        $sets = donuts_brand_bulk_build_set(
            $field_key,
            $shop_item_columns
        );

        if (empty($sets)) {
            continue;
        }

        $where = " WHERE TRIM(it_brand) = '{$brand_sql}' ";

        if ($scope === 'category') {
            $ca_id = isset($after['ca_id'])
                ? trim((string)$after['ca_id'])
                : '';

            if ($ca_id === '') {
                continue;
            }

            $ca_sql = sql_real_escape_string($ca_id);

            $where .= " AND ca_id = '{$ca_sql}' ";
        } elseif ($scope !== 'all') {
            continue;
        }

        sql_query("
            UPDATE {$g5['g5_shop_item_table']}
            SET " . implode(",\n                ", $sets) . "
            {$where}
        ");
    }

    /*
     * 현재 상품 검수 draft 처리.
     */
    donuts_item_inspection_ensure_draft(
        $it_id,
        $brand_id,
        $request_type,
        $brand_id
    );
});

include('./itemformupdate.php');

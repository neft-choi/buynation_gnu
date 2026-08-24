<?php
$sub_menu = '400300';
include_once('./_common.php');

header('Content-Type: application/json; charset=utf-8');

function item_delivery_group_json($success, $message = '', $current_group_id = 0)
{
    echo json_encode(array(
        'success' => (bool)$success,
        'message' => $message,
        'current_group_id' => (int)$current_group_id
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

if (!$is_member) {
    item_delivery_group_json(false, '로그인이 필요합니다.');
}

auth_check_menu($auth, $sub_menu, 'w');

$it_id = isset($_POST['it_id']) ? trim($_POST['it_id']) : '';
$group_id = isset($_POST['group_id']) ? (int)$_POST['group_id'] : 0;

if ($it_id === '') {
    item_delivery_group_json(false, '상품코드가 없습니다.');
}

$it_id_sql = sql_real_escape_string($it_id);

/*
 * 상품 확인
 */
$item = sql_fetch("
    SELECT it_id, it_brand
    FROM {$g5['g5_shop_item_table']}
    WHERE it_id = '{$it_id_sql}'
    LIMIT 1
");

if (empty($item['it_id'])) {
    item_delivery_group_json(false, '상품정보가 존재하지 않습니다.');
}

$item_brand_id = trim((string)$item['it_brand']);

if ($item_brand_id === '') {
    item_delivery_group_json(false, '상품의 브랜드 정보가 없습니다.');
}

/*
 * 권한:
 * - 최고관리자: 해당 상품 브랜드 설정 가능
 * - 브랜드회원: 자기 브랜드 상품만 가능
 */
if ($is_admin !== 'super') {
    if (strcasecmp($item_brand_id, trim((string)$member['mb_id'])) !== 0) {
        item_delivery_group_json(false, '해당 상품의 배송그룹을 수정할 권한이 없습니다.');
    }
}

$brand_id = $item_brand_id;
$brand_id_sql = sql_real_escape_string($brand_id);

/*
 * 선택한 그룹이 실제 해당 브랜드 그룹인지 검증.
 * group_id=0 은 그룹 해제.
 */
if ($group_id > 0) {
    $group = sql_fetch("
        SELECT dg_id
        FROM donuts_delivery_groups
        WHERE dg_id = '{$group_id}'
          AND brand_id = '{$brand_id_sql}'
          AND use_yn = 'Y'
        LIMIT 1
    ");

    if (empty($group['dg_id'])) {
        item_delivery_group_json(false, '선택한 배송그룹이 올바르지 않습니다.');
    }
}

/*
 * 기존 상품 배송설정 확인.
 * 조건(condition_id)은 절대 변경하지 않고 group_id만 바꿉니다.
 */
$setting = sql_fetch("
    SELECT condition_id, group_id
    FROM donuts_delivery_product_settings
    WHERE brand_id = '{$brand_id_sql}'
      AND it_id = '{$it_id_sql}'
    LIMIT 1
");

if (!empty($setting) || isset($setting['condition_id'])) {

    if ($group_id > 0) {
        sql_query("
            UPDATE donuts_delivery_product_settings
            SET group_id = '{$group_id}',
                updated_at = NOW()
            WHERE brand_id = '{$brand_id_sql}'
              AND it_id = '{$it_id_sql}'
        ");
    } else {
        sql_query("
            UPDATE donuts_delivery_product_settings
            SET group_id = NULL,
                updated_at = NOW()
            WHERE brand_id = '{$brand_id_sql}'
              AND it_id = '{$it_id_sql}'
        ");
    }

} else {

    /*
     * 아직 product_settings 행이 없으면 브랜드 기본 배송조건을 연결한 뒤
     * 선택한 묶음배송 group_id를 저장합니다.
     */
    $default_condition = sql_fetch("
        SELECT dc_id
        FROM donuts_delivery_conditions
        WHERE brand_id = '{$brand_id_sql}'
          AND is_default = 1
          AND use_yn = 'Y'
        ORDER BY dc_id DESC
        LIMIT 1
    ");

    if (empty($default_condition['dc_id'])) {
        item_delivery_group_json(
            false,
            '브랜드 기본 배송조건이 없습니다. 배송관리에서 기본 배송조건을 먼저 확인해 주세요.'
        );
    }

    $condition_id = (int)$default_condition['dc_id'];

    $group_value = $group_id > 0
        ? "'{$group_id}'"
        : "NULL";

    sql_query("
        INSERT INTO donuts_delivery_product_settings
            (brand_id, it_id, condition_id, group_id, created_at, updated_at)
        VALUES
            (
                '{$brand_id_sql}',
                '{$it_id_sql}',
                '{$condition_id}',
                {$group_value},
                NOW(),
                NOW()
            )
    ");
}

item_delivery_group_json(
    true,
    $group_id > 0
        ? '배송그룹에 적용되었습니다.'
        : '배송그룹에서 제외되었습니다.',
    $group_id
);

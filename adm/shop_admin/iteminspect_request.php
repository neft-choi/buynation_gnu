<?php
$sub_menu = '400300';
include_once('./_common.php');
include_once(G5_LIB_PATH . '/donuts_item_inspection.lib.php');

check_admin_token();

if (empty($member['mb_id'])) {
    alert('로그인이 필요합니다.');
}

/*
 * 검수 요청 권한:
 * - 최고관리자 전용 메뉴권한을 요구하지 않음
 * - donuts_brand에 등록된 현재 로그인 브랜드 계정이면 허용
 * - 아래에서 실제 상품의 it_brand도 현재 계정과 일치하는지 다시 확인
 */
if (!donuts_item_inspection_is_brand($member['mb_id'])) {
    alert('브랜드 회원만 검수 요청할 수 있습니다.');
}

$it_id = isset($_POST['it_id']) ? preg_replace('/[^A-Za-z0-9_\-]/', '', $_POST['it_id']) : '';
$brand_message = isset($_POST['brand_message']) ? trim($_POST['brand_message']) : '';

if ($it_id === '') {
    alert('상품코드가 없습니다.');
}

$it_sql = sql_real_escape_string($it_id);
$brand_id = trim($member['mb_id']);
$brand_sql = sql_real_escape_string($brand_id);

$item = sql_fetch("
    SELECT it_id, it_name, it_brand, ca_id, it_price
    FROM {$g5['g5_shop_item_table']}
    WHERE it_id = '{$it_sql}'
      AND TRIM(it_brand) = '{$brand_sql}'
    LIMIT 1
");

if (empty($item['it_id'])) {
    alert('검수 요청할 상품이 없거나 본인 브랜드 상품이 아닙니다.');
}

if (trim($item['it_name']) === '' || trim($item['ca_id']) === '') {
    alert('상품명과 기본분류를 먼저 입력해 주세요.');
}

/*
 * 배송비 검수
 *
 * 주문/CSV 배송비 계산 로직은 상품별 condition_id가 없을 경우
 * 브랜드 기본 배송조건(is_default=1)으로 fallback 합니다.
 *
 * 따라서 검수 요청에서도 상품별 condition_id만 강제하지 않고,
 * 아래 순서로 실제 적용 가능한 배송조건을 확인합니다.
 *
 * 1. 상품별 donuts_delivery_product_settings.condition_id
 * 2. 브랜드 기본 배송조건 donuts_delivery_conditions.is_default = 1
 * 3. 기존 donuts_brand_settings 배송비 설정
 */
$delivery = sql_fetch("
    SELECT condition_id, group_id
    FROM donuts_delivery_product_settings
    WHERE brand_id = '{$brand_sql}'
      AND it_id = '{$it_sql}'
    LIMIT 1
");

$delivery_condition_id = !empty($delivery['condition_id'])
    ? (int)$delivery['condition_id']
    : 0;

/*
 * 상품별 배송조건이 없으면 브랜드 기본 배송조건을 자동 연결합니다.
 */
if ($delivery_condition_id <= 0) {
    $default_delivery = sql_fetch("
        SELECT dc_id
        FROM donuts_delivery_conditions
        WHERE brand_id = '{$brand_sql}'
          AND is_default = 1
          AND use_yn = 'Y'
        ORDER BY dc_id DESC
        LIMIT 1
    ");

    if (!empty($default_delivery['dc_id'])) {
        $delivery_condition_id = (int)$default_delivery['dc_id'];

        /*
         * 기존 product_settings row가 있는지 확인.
         * group_id는 절대 변경하지 않고 condition_id만 보충합니다.
         */
        $setting_exists = sql_fetch("
            SELECT it_id
            FROM donuts_delivery_product_settings
            WHERE brand_id = '{$brand_sql}'
              AND it_id = '{$it_sql}'
            LIMIT 1
        ");

        if (!empty($setting_exists['it_id'])) {
            sql_query("
                UPDATE donuts_delivery_product_settings
                SET condition_id = '{$delivery_condition_id}'
                WHERE brand_id = '{$brand_sql}'
                  AND it_id = '{$it_sql}'
            ");
        } else {
            sql_query("
                INSERT INTO donuts_delivery_product_settings
                    (brand_id, it_id, condition_id, group_id)
                VALUES
                    ('{$brand_sql}', '{$it_sql}', '{$delivery_condition_id}', NULL)
            ");
        }
    }
}

/*
 * 새 배송조건 테이블에 기본 조건이 전혀 없더라도,
 * 기존 브랜드 쇼핑몰 배송설정이 존재하면 검수 요청을 막지 않습니다.
 *
 * 실제 주문 배송비 계산도 이런 경우 donuts_brand_settings를 fallback으로 사용합니다.
 */
$legacy_delivery_available = false;

if ($delivery_condition_id <= 0) {
    $legacy_delivery = sql_fetch("
        SELECT brand_id
        FROM donuts_brand_settings
        WHERE brand_id = '{$brand_sql}'
        LIMIT 1
    ");

    if (!empty($legacy_delivery['brand_id'])) {
        $legacy_delivery_available = true;
    }
}

if ($delivery_condition_id <= 0 && !$legacy_delivery_available) {
    alert(
        '적용 가능한 배송비 설정이 없습니다. 배송관리에서 브랜드 기본 배송조건을 먼저 설정해 주세요.'
    );
}

$inspection = donuts_item_inspection_get($it_id);

if (empty($inspection['inspection_id'])) {
    $inspection = donuts_item_inspection_ensure_draft(
        $it_id, $brand_id, 'new', $brand_id
    );
}

if ($inspection['status'] === 'pending') {
    alert('이미 심사 요청된 상품입니다.', './iteminspectresult.php?status=pending');
}

if ($inspection['status'] === 'approved') {
    alert('이미 승인된 상품입니다.', './iteminspectresult.php?status=approved');
}

if (!in_array($inspection['status'], array('draft', 'revision', 'rejected'), true)) {
    alert('현재 상태에서는 검수 요청을 할 수 없습니다.', './iteminspectresult.php');
}

$from = $inspection['status'];
$msg_sql = sql_real_escape_string($brand_message);

sql_query("
    UPDATE donuts_item_inspections
    SET status = 'pending',
        brand_message = '{$msg_sql}',
        review_fields = NULL,
        admin_message = NULL,
        requested_at = NOW(),
        reviewed_at = NULL,
        approved_at = NULL,
        reviewed_by = NULL,
        updated_at = NOW()
    WHERE inspection_id = '" . (int)$inspection['inspection_id'] . "'
");

sql_query("
    UPDATE {$g5['g5_shop_item_table']}
    SET it_use = 0
    WHERE it_id = '{$it_sql}'
");

donuts_item_inspection_log(
    $inspection['inspection_id'],
    $it_id,
    $brand_id,
    $from,
    'pending',
    $brand_id,
    $brand_message
);

alert('플랫폼 상품 검수를 요청했습니다.', './iteminspectresult.php?status=pending');

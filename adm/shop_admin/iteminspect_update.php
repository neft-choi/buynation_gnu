<?php
$sub_menu = '400310';
include_once('./_common.php');
include_once(G5_LIB_PATH . '/donuts_item_inspection.lib.php');

auth_check_menu($auth, $sub_menu, 'w');
check_admin_token();

if ($is_admin !== 'super') {
    alert('플랫폼 최고관리자만 검수 결과를 처리할 수 있습니다.');
}

$it_id = isset($_POST['it_id']) ? preg_replace('/[^A-Za-z0-9_\-]/', '', $_POST['it_id']) : '';
$decision = isset($_POST['decision']) ? trim($_POST['decision']) : '';
$admin_message = isset($_POST['admin_message']) ? trim($_POST['admin_message']) : '';
$review_fields = isset($_POST['review_fields']) && is_array($_POST['review_fields'])
    ? array_values(array_unique(array_map('trim', $_POST['review_fields'])))
    : array();

if ($it_id === '') {
    alert('상품코드가 없습니다.');
}

$allowed = array('approved', 'revision', 'rejected');
if (!in_array($decision, $allowed, true)) {
    alert('처리 상태가 올바르지 않습니다.');
}

$inspection = donuts_item_inspection_get($it_id);

if (empty($inspection['inspection_id'])) {
    alert('검수 요청정보가 없습니다.');
}

if ($inspection['status'] !== 'pending') {
    alert('심사 중인 상품만 처리할 수 있습니다.');
}

if ($decision === 'revision' && empty($review_fields) && $admin_message === '') {
    alert('보완 요청 항목 또는 보완 내용을 입력해 주세요.');
}

$it_sql = sql_real_escape_string($it_id);
$message_sql = sql_real_escape_string($admin_message);
$fields_json = !empty($review_fields)
    ? json_encode($review_fields, JSON_UNESCAPED_UNICODE)
    : '';
$fields_sql = sql_real_escape_string($fields_json);
$admin_id_sql = sql_real_escape_string($member['mb_id']);

if ($decision === 'approved') {
    sql_query("
        UPDATE {$g5['g5_shop_item_table']}
        SET it_use = 1
        WHERE it_id = '{$it_sql}'
    ");

    sql_query("
        UPDATE donuts_item_inspections
        SET status = 'approved',
            review_fields = NULL,
            admin_message = '{$message_sql}',
            reviewed_at = NOW(),
            approved_at = NOW(),
            reviewed_by = '{$admin_id_sql}',
            updated_at = NOW()
        WHERE inspection_id = '" . (int)$inspection['inspection_id'] . "'
    ");
} else {
    sql_query("
        UPDATE {$g5['g5_shop_item_table']}
        SET it_use = 0
        WHERE it_id = '{$it_sql}'
    ");

    sql_query("
        UPDATE donuts_item_inspections
        SET status = '" . sql_real_escape_string($decision) . "',
            review_fields = '{$fields_sql}',
            admin_message = '{$message_sql}',
            reviewed_at = NOW(),
            approved_at = NULL,
            reviewed_by = '{$admin_id_sql}',
            updated_at = NOW()
        WHERE inspection_id = '" . (int)$inspection['inspection_id'] . "'
    ");
}

donuts_item_inspection_log(
    $inspection['inspection_id'],
    $it_id,
    $inspection['brand_id'],
    'pending',
    $decision,
    $member['mb_id'],
    $admin_message,
    $fields_json
);

$msg = $decision === 'approved'
    ? '상품이 승인되어 쇼핑몰 판매가 활성화되었습니다.'
    : ($decision === 'revision' ? '보완 요청 처리했습니다.' : '상품 검수를 거절했습니다.');

alert($msg, './iteminspect.php?it_id=' . urlencode($it_id));

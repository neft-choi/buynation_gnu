<?php
$sub_menu = '710300';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "w");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    alert('올바른 방법으로 이용해 주십시오.', './item_list.php');
}

check_admin_token();

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id < 1) {
    alert('잘못된 접근입니다.', './item_list.php');
}

$mb_id_sql = sql_real_escape_string($member['mb_id']);

$row = sql_fetch("
    SELECT id, use_yn
    FROM donuts_dotty_items
    WHERE id = '{$id}'
      AND dotty_mb_id = '{$mb_id_sql}'
    LIMIT 1
");

if (empty($row['id'])) {
    alert('수정 권한이 없는 상품입니다.', './item_list.php');
}

$new_use = ($row['use_yn'] === 'Y') ? 'N' : 'Y';

$result = sql_query("
    UPDATE donuts_dotty_items
    SET use_yn = '{$new_use}',
        updated_at = NOW()
    WHERE id = '{$id}'
      AND dotty_mb_id = '{$mb_id_sql}'
", false);

if (!$result) {
    alert('노출 상태 변경 중 DB 오류가 발생했습니다.', './item_list.php');
}

// 실제 변경 여부 재확인
$check = sql_fetch("
    SELECT use_yn
    FROM donuts_dotty_items
    WHERE id = '{$id}'
      AND dotty_mb_id = '{$mb_id_sql}'
    LIMIT 1
");

if (!isset($check['use_yn']) || $check['use_yn'] !== $new_use) {
    alert('노출 상태가 변경되지 않았습니다.', './item_list.php');
}

goto_url('./item_list.php');

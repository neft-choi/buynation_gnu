<?php
$sub_menu = '710300';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "d");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    alert('올바른 방법으로 이용해 주십시오.', './item_list.php');
}

check_admin_token();

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id < 1) {
    alert('잘못된 접근입니다.', './item_list.php');
}

$mb_id_sql = sql_real_escape_string($member['mb_id']);

// 반드시 현재 로그인한 도티가 등록한 항목만 삭제 가능
$row = sql_fetch("
    SELECT id, it_id
    FROM donuts_dotty_items
    WHERE id = '{$id}'
      AND dotty_mb_id = '{$mb_id_sql}'
    LIMIT 1
");

if (empty($row['id'])) {
    alert('삭제 권한이 없는 상품입니다.', './item_list.php');
}

// "도티 등록 상품"에서 실제 DELETE
// 원본 영카트 상품(g5_shop_item)은 삭제하지 않습니다.
$result = sql_query("
    DELETE FROM donuts_dotty_items
    WHERE id = '{$id}'
      AND dotty_mb_id = '{$mb_id_sql}'
", false);

if (!$result) {
    alert('상품 삭제 중 DB 오류가 발생했습니다.', './item_list.php');
}

// 실제 삭제 여부 확인
$check = sql_fetch("
    SELECT id
    FROM donuts_dotty_items
    WHERE id = '{$id}'
      AND dotty_mb_id = '{$mb_id_sql}'
    LIMIT 1
");

if (!empty($check['id'])) {
    alert('상품이 실제로 삭제되지 않았습니다.', './item_list.php');
}

goto_url('./item_list.php');

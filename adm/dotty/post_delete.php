<?php
$sub_menu = '710500';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "d");
check_admin_token();

$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;
$mb_id_sql = sql_real_escape_string($member['mb_id']);

$post = sql_fetch("
    SELECT post_id
    FROM donuts_dotty_posts
    WHERE post_id = '{$post_id}'
      AND dotty_mb_id = '{$mb_id_sql}'
    LIMIT 1
");

if (empty($post['post_id'])) {
    alert('삭제 권한이 없는 게시글입니다.');
}

// FK ON DELETE CASCADE로 댓글도 함께 삭제됨
sql_query("
    DELETE FROM donuts_dotty_posts
    WHERE post_id = '{$post_id}'
      AND dotty_mb_id = '{$mb_id_sql}'
");

goto_url('./post_list.php');

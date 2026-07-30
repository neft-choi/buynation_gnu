<?php
$sub_menu = '710500';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "w");
check_admin_token();

$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;
$mb_id_sql = sql_real_escape_string($member['mb_id']);

$post = sql_fetch("
    SELECT post_id, use_yn
    FROM donuts_dotty_posts
    WHERE post_id = '{$post_id}'
      AND dotty_mb_id = '{$mb_id_sql}'
    LIMIT 1
");

if (empty($post['post_id'])) {
    alert('수정 권한이 없는 게시글입니다.');
}

$new_use = $post['use_yn'] === 'Y' ? 'N' : 'Y';

sql_query("
    UPDATE donuts_dotty_posts
    SET use_yn = '{$new_use}',
        updated_at = NOW()
    WHERE post_id = '{$post_id}'
      AND dotty_mb_id = '{$mb_id_sql}'
");

goto_url('./post_list.php');

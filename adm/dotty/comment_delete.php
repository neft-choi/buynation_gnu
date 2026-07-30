<?php
$sub_menu = '710500';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "d");
check_admin_token();

$comment_id = isset($_GET['comment_id']) ? (int)$_GET['comment_id'] : 0;
$mb_id_sql = sql_real_escape_string($member['mb_id']);

$comment = sql_fetch("
    SELECT c.comment_id, c.post_id
    FROM donuts_dotty_post_comments c
    INNER JOIN donuts_dotty_posts p
        ON c.post_id = p.post_id
    WHERE c.comment_id = '{$comment_id}'
      AND p.dotty_mb_id = '{$mb_id_sql}'
    LIMIT 1
");

if (empty($comment['comment_id'])) {
    alert('삭제 권한이 없는 댓글입니다.');
}

sql_query("
    DELETE FROM donuts_dotty_post_comments
    WHERE comment_id = '{$comment_id}'
");

sql_query("
    UPDATE donuts_dotty_posts
    SET comment_count = (
        SELECT COUNT(*)
        FROM donuts_dotty_post_comments
        WHERE post_id = '{$comment['post_id']}'
    )
    WHERE post_id = '{$comment['post_id']}'
");

goto_url('./post_list.php');

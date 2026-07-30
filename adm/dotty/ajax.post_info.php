<?php
$sub_menu = '710500';
include_once('./_common.php');

header('Content-Type: application/json; charset=utf-8');

auth_check_menu($auth, $sub_menu, "r");

$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;
$mb_id_sql = sql_real_escape_string($member['mb_id']);

$post = sql_fetch("
    SELECT post_id, post_subject, post_content
    FROM donuts_dotty_posts
    WHERE post_id = '{$post_id}'
      AND dotty_mb_id = '{$mb_id_sql}'
    LIMIT 1
");

if (empty($post['post_id'])) {
    echo json_encode(array(
        'success' => false,
        'message' => '열람 권한이 없는 게시글입니다.'
    ), JSON_UNESCAPED_UNICODE);
    exit;
}

$comments = array();

$result = sql_query("
    SELECT
        comment_id,
        mb_id,
        comment_name,
        comment_nick,
        comment_content,
        created_at
    FROM donuts_dotty_post_comments
    WHERE post_id = '{$post_id}'
    ORDER BY comment_id ASC
");

while ($row = sql_fetch_array($result)) {
    $comments[] = array(
        'comment_id' => (int)$row['comment_id'],
        'mb_id' => $row['mb_id'],
        'comment_name' => $row['comment_name'],
        'comment_nick' => $row['comment_nick'],
        'comment_content' => $row['comment_content'],
        'created_at' => $row['created_at']
    );
}

echo json_encode(array(
    'success' => true,
    'post' => array(
        'post_id' => (int)$post['post_id'],
        'post_subject' => $post['post_subject'],
        'post_content' => conv_content($post['post_content'], 1)
    ),
    'comments' => $comments,
    'token' => get_admin_token()
), JSON_UNESCAPED_UNICODE);
exit;

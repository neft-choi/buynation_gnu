<?php
$sub_menu = '710500';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "w");
check_demo();
check_admin_token();

$post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
$comment_content = isset($_POST['comment_content'])
    ? trim($_POST['comment_content'])
    : '';

if ($post_id < 1) {
    alert('게시글 정보가 올바르지 않습니다.');
}

if ($comment_content === '') {
    alert('댓글 내용을 입력해 주세요.');
}

// 과도하게 긴 테스트 댓글 방지
if (mb_strlen($comment_content, 'UTF-8') > 5000) {
    alert('댓글은 5,000자 이하로 입력해 주세요.');
}

$dotty_mb_id_sql = sql_real_escape_string($member['mb_id']);

// 현재 도티 계정 소유 게시글인지 확인
$post = sql_fetch("
    SELECT post_id
    FROM donuts_dotty_posts
    WHERE post_id = '{$post_id}'
      AND dotty_mb_id = '{$dotty_mb_id_sql}'
    LIMIT 1
");

if (empty($post['post_id'])) {
    alert('댓글 테스트 권한이 없는 게시글입니다.');
}

$mb_id = isset($member['mb_id']) ? $member['mb_id'] : '';
$mb_name = isset($member['mb_name']) ? $member['mb_name'] : '';
$mb_nick = isset($member['mb_nick']) ? $member['mb_nick'] : '';

$mb_id_sql = sql_real_escape_string($mb_id);
$mb_name_sql = sql_real_escape_string($mb_name);
$mb_nick_sql = sql_real_escape_string($mb_nick);

$comment_content = clean_xss_tags($comment_content, 1, 1);
$comment_content_sql = sql_real_escape_string($comment_content);

$result = sql_query("
    INSERT INTO donuts_dotty_post_comments
    SET
        post_id = '{$post_id}',
        mb_id = '{$mb_id_sql}',
        comment_name = '{$mb_name_sql}',
        comment_nick = '{$mb_nick_sql}',
        comment_content = '{$comment_content_sql}',
        created_at = NOW()
", false);

if (!$result) {
    alert('댓글 저장 중 DB 오류가 발생했습니다.');
}

// 실제 댓글 개수와 게시글 comment_count 동기화
sql_query("
    UPDATE donuts_dotty_posts
    SET comment_count = (
        SELECT COUNT(*)
        FROM donuts_dotty_post_comments
        WHERE post_id = '{$post_id}'
    ),
    updated_at = NOW()
    WHERE post_id = '{$post_id}'
");

alert('테스트 댓글이 등록되었습니다.', './post_view.php?post_id=' . $post_id);

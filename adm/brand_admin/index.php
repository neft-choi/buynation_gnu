<?php
$sub_menu = '200000';
require_once './_common.php';

@require_once '../safe_check.php';

if (function_exists('social_log_file_delete')) {
    //소셜로그인 디버그 파일 24시간 지난것은 삭제
    social_log_file_delete(86400);
}

$g5['title'] = '브랜드 관리자메인';
require_once '../admin.head.php';
?>

<?php
require_once '../admin.tail.php';

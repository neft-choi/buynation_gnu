<?php
$sub_menu = '610000';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '대시 보드';
require_once '../admin.head.php';
?>

<?php
include_once('./_common.php');

goto_url(G5_ADMIN_URL . '/dot/dashboard.php');
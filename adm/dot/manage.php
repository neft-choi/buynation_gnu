<?php
$sub_menu = '640100';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '내 관리 내역';
require_once '../admin.head.php';
?>

<section></section>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

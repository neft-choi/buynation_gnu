<?php
$sub_menu = '940700';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '커뮤니티 모니터링';
require_once '../../admin.head.php';
?>

<?php
require_once '../../admin.tail.php';
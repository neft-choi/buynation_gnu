<?php
$sub_menu = '940500';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '도넛 운영자 권한';
require_once '../../admin.head.php';
?>

<?php
require_once '../../admin.tail.php';
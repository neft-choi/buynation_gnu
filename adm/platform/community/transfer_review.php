<?php
$sub_menu = '940600';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '도티 승계 심사';
require_once '../../admin.head.php';
?>

<?php
require_once '../../admin.tail.php';
<?php
$sub_menu = '610100';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '대시보드';
require_once '../admin.head.php';
?>

<section>
    <h2 class="sr-only">도트 관리 대시보드</h2>
    <p class="text-gray-500">도트 관리 현황을 한눈에 확인합니다.</p>
</section>

<?php
require_once '../admin.tail.php';
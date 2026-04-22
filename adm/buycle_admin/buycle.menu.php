<?php
// 관리자 사이드바 메뉴 초기화
$menu = array();
$amenu = array();

// buycle 전용 사이드바 메뉴 설정
$menu['menu710'] = array(
    array('710000', '바이클관리', G5_ADMIN_URL . '/buycle_admin/buycle_dashboard.php', 'buycle'),
    array('710100', '대시보드', G5_ADMIN_URL . '/buycle_admin/buycle_dashboard.php', 'buycle_dashboard'),
);

$menu['menu720'] = array(
    array('720000', '환경설정', G5_ADMIN_URL . '/buycle_admin/buycle_settings.php', 'buycle_config'),
    array('720100', '바이클설정', G5_ADMIN_URL . '/buycle_admin/buycle_settings.php', 'buycle_cf_settings'),
);

// 메뉴 권한 (admin.head.php 에서 auth_check_menu 필터 통과를 위해)
$auth['710100'] = 'r,w,d';
$auth['720100'] = 'r,w,d';

// 직접 amenu에 넣어 사이드바 메뉴 적용
$amenu['710'] = 'buygent_menu710';
$amenu['720'] = 'buygent_menu720';


<?php
// 관리자 사이드바 메뉴 초기화
$menu = array();
$amenu = array();

// buygent 전용 사이드바 메뉴 설정
$menu['menu910'] = array(
    array('910000', '환경설정', G5_ADMIN_URL . '/buygent_admin/buygent_settings.php', 'buygent_config'),
    // array('910100', '기본환경설정', G5_ADMIN_URL . '/buygent_admin/', 'buygent_cf_basic'),
    array('910200', '바이전트설정', G5_ADMIN_URL . '/buygent_admin/buygent_settings.php', 'buygent_cf_settings'),
);

$menu['menu920'] = array(
    array('920000', '바이전트관리', G5_ADMIN_URL . '/buygent_admin/buygent_dashboard.php', 'buygent'),
    array('920100', '대시보드', G5_ADMIN_URL . '/buygent_admin/buygent_dashboard.php', 'buygent_dashboard'),
);

// 메뉴 권한 (admin.head.php 에서 auth_check_menu 필터 통과를 위해)
$auth['910100'] = 'r,w,d';
$auth['910200'] = 'r,w,d';
$auth['920100'] = 'r,w,d';

// 직접 amenu에 넣어 사이드바 메뉴 적용
$amenu['920'] = 'buygent_menu920';
$amenu['910'] = 'buygent_menu910';


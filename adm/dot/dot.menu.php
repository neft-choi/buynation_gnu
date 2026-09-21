<?php
$menu = array();
$amenu = array();

$dot_menu_icon_svg_map = array(
    '610' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard preview-icon w-4 h-4"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>',
    '620' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-4 h-4"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
    '630' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-handbag w-4 h-4"><path d="M2.048 18.566A2 2 0 0 0 4 21h16a2 2 0 0 0 1.952-2.434l-2-9A2 2 0 0 0 18 8H6a2 2 0 0 0-1.952 1.566z"/><path d="M8 11V6a4 4 0 0 1 8 0v5"/></svg>',
    '640' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scan-face preview-icon w-4 h-4"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><path d="M9 9h.01"/><path d="M15 9h.01"/></svg>',
);

$menu['menu610'] = array(
    array('610000', '대시보드', G5_ADMIN_URL . '/dot/dashboard.php', 'dot_dashboard'),
    array('610100', '대시보드', G5_ADMIN_URL . '/dot/dashboard.php', 'dot_dashboard'),
);

$menu['menu620'] = array(
    array('620000', '커뮤니티', G5_ADMIN_URL . '/dot/join_request.php', 'dot_community'),
    array('620100', '가입 신청', G5_ADMIN_URL . '/dot/join_request.php', 'dot_join_request'),
    array('620200', '가입 도트', G5_ADMIN_URL . '/dot/member_activity.php', 'dot_member_activity'),
    array('620300', '콘텐츠', G5_ADMIN_URL . '/dot/content.php', 'dot_content'),
    array('620400', '공지 핀', G5_ADMIN_URL . '/dot/notice_pin.php', 'dot_notice_pin'),
);

$menu['menu630'] = array(
    array('630000', '쇼핑', G5_ADMIN_URL . '/dot/featured_products.php', 'dot_shopping'),
    array('630100', '추천 상품', G5_ADMIN_URL . '/dot/featured_products.php', 'featured_products'),
);

$menu['menu640'] = array(
    array('640000', '내 관리 내역', G5_ADMIN_URL . '/dot/manage.php', 'dot_manage'),
    array('640100', '내 관리 내역', G5_ADMIN_URL . '/dot/manage.php', 'dot_manage'),
);

$auth['610100'] = 'r,w,d';
$auth['620100'] = 'r,w,d';
$auth['620200'] = 'r,w,d';
$auth['620300'] = 'r,w,d';
$auth['620400'] = 'r,w,d';
$auth['630100'] = 'r,w,d';
$auth['640100'] = 'r,w,d';

$amenu['610'] = 'dot_menu610';
$amenu['620'] = 'dot_menu620';
$amenu['630'] = 'dot_menu630';
$amenu['640'] = 'dot_menu640';

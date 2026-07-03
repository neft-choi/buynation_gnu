<?php
// 관리자 사이드바 메뉴 초기화
$menu = array();
$amenu = array();

// dotty 전용 사이드바 메뉴 설정
$menu['menu710'] = array(
    array('710000', '도티 관리', G5_ADMIN_URL . '/dotty/dashboard.php', 'dotty'),
    array('710100', '대시보드', G5_ADMIN_URL . '/dotty/dashboard.php', 'dotty_dashboard'),
    array('710800', '도티 설정', G5_ADMIN_URL . '/dotty/settings.php', 'dotty_cf_settings'),
    array('710200', '상품등록', G5_ADMIN_URL . '/dotty/itemform.php', 'dotty_item_form'),
    array('710300', '상품리스트', G5_ADMIN_URL . '/dotty/item_list.php', 'dotty_item_list'),
    array('710400', '회원관리', G5_ADMIN_URL . '/dotty/member_list.php', 'dotty_member_list'),
    array('710500', '게시글관리', G5_ADMIN_URL . '/dotty/post_list.php', 'dotty_post_list'),
    array('710600', '주문목록', G5_ADMIN_URL . '/dotty/order_list.php', 'dotty_order_list'),
    array('710700', '매출관리', G5_ADMIN_URL . '/dotty/sales.php', 'dotty_sales'),
);

// $menu['menu720'] = array(
//     array('720000', '환경설정', G5_ADMIN_URL . '/dotty/settings.php', 'dotty_config'),
//     array('710800', '도티 설정', G5_ADMIN_URL . '/dotty/settings.php', 'dotty_cf_settings'),
// );

// 메뉴 권한 (admin.head.php 에서 auth_check_menu 필터 통과를 위해)
$auth['710100'] = 'r,w,d';
$auth['710200'] = 'r,w,d';
$auth['710300'] = 'r,w,d';
$auth['710400'] = 'r,w,d';
$auth['710500'] = 'r,w,d';
$auth['710600'] = 'r,w,d';
$auth['710700'] = 'r,w,d';
$auth['710800'] = 'r,w,d';

// 직접 amenu에 넣어 사이드바 메뉴 적용
$amenu['710'] = 'dotty_menu710';
// $amenu['720'] = 'dotty_menu720';


<?php
// 관리자 사이드바 메뉴 초기화
$menu = array();
$amenu = array();

// brand 전용 사이드바 메뉴 설정
$menu['menu810'] = array(
    array('810000', '브랜드관리', G5_ADMIN_URL . '/brand_admin/brand_dashboard.php', 'brand'),
    array('810100', '대시보드', G5_ADMIN_URL . '/brand_admin/brand_dashboard.php', 'brand_dashboard'),
    array('810100', '브랜드현황', G5_ADMIN_URL . '/brand_admin/brand_dashboard.php', 'brand_dashboard'),
    array('810100', '브랜드설정', G5_ADMIN_URL . '/brand_admin/configform.php', 'brand_configform'),
    array('810100', '주문내역', G5_ADMIN_URL . '/brand_admin/orderlist.php', 'brand_orderlist'),
    array('810100', '상품관리', G5_ADMIN_URL . '/brand_admin/itemlist.php', 'brand_itemlist'),
    array('810100', '상품문의', G5_ADMIN_URL . '/brand_admin/itemqalist.php', 'brand_itemqalist'),
    array('810100', '사용후기', G5_ADMIN_URL . '/brand_admin/itemuselist.php', 'brand_itemuselist'),
    array('810100', '상품재고관리', G5_ADMIN_URL . '/brand_admin/itemstocklist.php', 'brand_itemstocklist'),
    array('810100', '상품옵션재고관리', G5_ADMIN_URL . '/brand_admin/optionstocklist.php', 'brand_optionstocklist'),
    array('810100', '추가배송비관리', G5_ADMIN_URL . '/brand_admin/sendcostlist.php', 'brand_sendcostlist'),
    array('810100', '미완료주문', G5_ADMIN_URL . '/brand_admin/inorderlist.php', 'brand_inorderlist'),
);

$menu['menu820'] = array(
    array('820000', '환경설정', G5_ADMIN_URL . '/brand_admin/brand_settings.php', 'brand_config'),
    array('820100', '브랜드설정', G5_ADMIN_URL . '/brand_admin/brand_settings.php', 'brand_cf_settings'),
);

// 메뉴 권한 (admin.head.php 에서 auth_check_menu 필터 통과를 위해)
$auth['810100'] = 'r,w,d';
$auth['820100'] = 'r,w,d';

// 직접 amenu에 넣어 사이드바 메뉴 적용
$amenu['810'] = 'buygent_menu810';
$amenu['820'] = 'buygent_menu820';

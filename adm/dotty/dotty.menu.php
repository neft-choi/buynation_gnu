<?php
// 관리자 사이드바 메뉴 초기화
$menu = array();
$amenu = array();

// dotty 전용 메뉴 아이콘
$dotty_menu_icon_svg_map = array(
    '710' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-icon lucide-user-round h-4 w-4"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>',
    '730' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag-icon lucide-shopping-bag h-4 w-4"><path d="M16 10a4 4 0 0 1-8 0"/><path d="M3.103 6.034h17.794"/><path d="M3.4 5.467a2 2 0 0 0-.4 1.2V20a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6.667a2 2 0 0 0-.4-1.2l-2-2.667A2 2 0 0 0 17 2H7a2 2 0 0 0-1.6.8z"/></svg>',
);

// dotty 전용 사이드바 메뉴 설정
$menu['menu710'] = array(
    array('710000', '도티 관리', G5_ADMIN_URL . '/dotty/management.php', 'dotty'),
    array('710110', '통합 관리', G5_ADMIN_URL . '/dotty/management.php', 'dotty_management'),
    array('710120', '도넛 정보', G5_ADMIN_URL . '/dotty/donuts_info.php', 'dotty_donuts_info'),
    array('710130', '가입 신청', G5_ADMIN_URL . '/dotty/join_request.php', 'dotty_join_request'),
    array('710140', '가입 도트', G5_ADMIN_URL . '/dotty/member_activity.php', 'dotty_member_activity'),
    array('710150', '콘텐츠', G5_ADMIN_URL . '/dotty/community_content.php', 'dotty_community_content'),
    array('710160', '공지·핀 관리', G5_ADMIN_URL . '/dotty/notice_pin.php', 'dotty_notice_pin'),
    array('710170', '운영자 관리', G5_ADMIN_URL . '/dotty/admin_list.php', 'dotty_admin_list'),
    array('710180', '운영권 승계', G5_ADMIN_URL . '/dotty/ownership_transfer.php', 'dotty_ownership_transfer'),
    array('710190', '활동 로그', G5_ADMIN_URL . '/dotty/activity_log.php', 'dotty_activity_log'),
    array('710100', '대시보드', G5_ADMIN_URL . '/dotty/dashboard.php', 'dotty_dashboard'),
    array('710800', '도티 설정', G5_ADMIN_URL . '/dotty/settings.php', 'dotty_cf_settings'),
    array('710200', '상품등록', G5_ADMIN_URL . '/dotty/itemform.php', 'dotty_item_form'),
    array('710300', '등록상품관리', G5_ADMIN_URL . '/dotty/item_list.php', 'dotty_item_list'),
    // array('710900', '상품리스트', G5_ADMIN_URL . '/dotty/product_list.php', 'dotty_product_list'),
    array('710400', '회원관리', G5_ADMIN_URL . '/dotty/member_list.php', 'dotty_member_list'),
    array('710500', '게시글관리', G5_ADMIN_URL . '/dotty/post_list.php', 'dotty_post_list'),
    array('710600', '주문목록', G5_ADMIN_URL . '/dotty/order_list.php', 'dotty_order_list'),
    array('710700', '매출관리', G5_ADMIN_URL . '/dotty/sales.php', 'dotty_sales'),
);

// $menu['menu720'] = array(
//     array('730000', '커뮤니티', G5_ADMIN_URL . '/dotty/community.php', 'dotty_community'),
// );

$menu['menu730'] = array(
    array('730000', '쇼핑', G5_ADMIN_URL . '/dotty/shopping.php', 'dotty_shopping'),
    array('730100', '추천상품 현황', G5_ADMIN_URL . '/dotty/featured_products.php', 'dotty_featured_products'),
    array('730200', '상품 검색', G5_ADMIN_URL . '/dotty/search_products.php', 'dotty_search_products'),
    array('730300', '토핑 지급', G5_ADMIN_URL . '/dotty/topping_award.php', 'dotty_topping_award'),
);

// 메뉴 권한 (admin.head.php 에서 auth_check_menu 필터 통과를 위해)
$auth['710100'] = 'r,w,d';
$auth['710110'] = 'r,w,d';
$auth['710120'] = 'r,w,d';
$auth['710130'] = 'r,w,d';
$auth['710140'] = 'r,w,d';
$auth['710150'] = 'r,w,d';
$auth['710160'] = 'r,w,d';
$auth['710170'] = 'r,w,d';
$auth['710180'] = 'r,w,d';
$auth['710190'] = 'r,w,d';
$auth['710200'] = 'r,w,d';
$auth['710300'] = 'r,w,d';
$auth['710400'] = 'r,w,d';
$auth['710500'] = 'r,w,d';
$auth['710600'] = 'r,w,d';
$auth['710700'] = 'r,w,d';
$auth['710800'] = 'r,w,d';
$auth['710900'] = 'r,w,d';

$auth['730100'] = 'r,w,d';
$auth['730200'] = 'r,w,d';
$auth['730300'] = 'r,w,d';

// 직접 amenu에 넣어 사이드바 메뉴 적용
$amenu['710'] = 'dotty_menu710';
$amenu['720'] = 'dotty_menu720';
$amenu['730'] = 'dotty_menu720';

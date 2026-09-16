<?php
// 관리자 사이드바 메뉴 초기화
$menu = array();
$amenu = array();

// dotty 전용 메뉴 아이콘
$dotty_menu_icon_svg_map = array(
    '710' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-icon lucide-user-round h-4 w-4"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>',
    '720' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square-more w-4 h-4"><path d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z"/><path d="M12 11h.01"/><path d="M16 11h.01"/><path d="M8 11h.01"/></svg>',
    '730' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-4 h-4"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>',
    '740' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-handbag w-4 h-4"><path d="M2.048 18.566A2 2 0 0 0 4 21h16a2 2 0 0 0 1.952-2.434l-2-9A2 2 0 0 0 18 8H6a2 2 0 0 0-1.952 1.566z"/><path d="M8 11V6a4 4 0 0 1 8 0v5"/></svg>',
    '750' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-donut w-4 h-4"><path d="M20.5 10a2.5 2.5 0 0 1-2.4-3H18a2.95 2.95 0 0 1-2.6-4.4 10 10 0 1 0 6.3 7.1c-.3.2-.8.3-1.2.3"/><circle cx="12" cy="12" r="3"/></svg>',
    '760' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-id-card w-4 h-4"><path d="M13 19a4 4 0 00-8 0"/><path d="M16 10h2"/><path d="M16 14h2"/><circle cx="9" cy="12" r="3"/><rect x="2" y="5" width="20" height="14" rx="2"/></svg>',
);

// dotty 전용 사이드바 메뉴 설정
$menu['menu710'] = array(
    array('710000', '대시보드', G5_ADMIN_URL . '/dotty/dashboard.php', 'dotty_dashboard'),
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

$menu['menu720'] = array(
    array('720000', '브랜드 쪽지', G5_ADMIN_URL . '/dotty/messages.php', 'dotty_messages'),
    array('720100', '브랜드 쪽지', G5_ADMIN_URL . '/dotty/messages.php', 'dotty_messages'),
);

$menu['menu730'] = array(
    array('730000', '커뮤니티', G5_ADMIN_URL . '/dotty/management.php', 'dotty_management'),
    array('730100', '통합 관리', G5_ADMIN_URL . '/dotty/management.php', 'dotty_management'),
    array('730200', '도넛 정보', G5_ADMIN_URL . '/dotty/donuts_info.php', 'dotty_donuts_info'),
    array('730300', '가입 신청', G5_ADMIN_URL . '/dotty/join_request.php', 'dotty_join_request'),
    array('730400', '가입 도트', G5_ADMIN_URL . '/dotty/member_activity.php', 'dotty_member_activity'),
    array('730500', '콘텐츠', G5_ADMIN_URL . '/dotty/community_content.php', 'dotty_community_content'),
    array('730600', '공지·핀', G5_ADMIN_URL . '/dotty/notice_pin.php', 'dotty_notice_pin'),
    array('730700', '운영자 권한', G5_ADMIN_URL . '/dotty/admin_list.php', 'dotty_admin_list'),
    array('730800', '운영권 승계', G5_ADMIN_URL . '/dotty/ownership_transfer.php', 'dotty_ownership_transfer'),
    array('730900', '활동 로그', G5_ADMIN_URL . '/dotty/activity_log.php', 'dotty_activity_log'),
);

$menu['menu740'] = array(
    array('740000', '쇼핑', G5_ADMIN_URL . '/dotty/shopping.php', 'dotty_shopping'),
    array('740100', '추천상품 현황', G5_ADMIN_URL . '/dotty/featured_products.php', 'dotty_featured_products'),
    array('740200', '상품 검색', G5_ADMIN_URL . '/dotty/search_products.php', 'dotty_search_products'),
);

$menu['menu750'] = array(
    array('750000', '토핑', G5_ADMIN_URL . '/dotty/topping_award.php', 'dotty_topping_award'),
    array('750100', '토핑 지급', G5_ADMIN_URL . '/dotty/topping_award.php', 'dotty_topping_award'),
);

$menu['menu760'] = array(
    array('760000', '사업자·정산', G5_ADMIN_URL . '/dotty/business_verification.php', 'dotty_business_verification'),
    array('760100', '사업자 인증', G5_ADMIN_URL . '/dotty/business_verification.php', 'dotty_business_verification'),
    array('760200', '정산 관리', G5_ADMIN_URL . '/dotty/settlement.php', 'dotty_settlement'),
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

$auth['720100'] = 'r,w,d';

$auth['730100'] = 'r,w,d';
$auth['730200'] = 'r,w,d';
$auth['730300'] = 'r,w,d';
$auth['730400'] = 'r,w,d';
$auth['730500'] = 'r,w,d';


// 직접 amenu에 넣어 사이드바 메뉴 적용
$amenu['710'] = 'dotty_menu710';
$amenu['720'] = 'dotty_menu720';
$amenu['730'] = 'dotty_menu730';
$amenu['740'] = 'dotty_menu740';
$amenu['750'] = 'dotty_menu750';
$amenu['760'] = 'dotty_menu760';

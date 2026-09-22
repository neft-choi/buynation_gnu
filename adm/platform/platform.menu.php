<?php
$menu = array();
$amenu = array();

$platform_menu_icon_svg_map = array(
    '910' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard preview-icon w-4 h-4"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>',
    '920' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-4 h-4"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
    '930' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-handbag w-4 h-4"><path d="M2.048 18.566A2 2 0 0 0 4 21h16a2 2 0 0 0 1.952-2.434l-2-9A2 2 0 0 0 18 8H6a2 2 0 0 0-1.952 1.566z"/><path d="M8 11V6a4 4 0 0 1 8 0v5"/></svg>',
    '940' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scan-face preview-icon w-4 h-4"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><path d="M9 9h.01"/><path d="M15 9h.01"/></svg>',
    '950' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scan-face preview-icon w-4 h-4"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><path d="M9 9h.01"/><path d="M15 9h.01"/></svg>',
    '960' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scan-face preview-icon w-4 h-4"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><path d="M9 9h.01"/><path d="M15 9h.01"/></svg>',
    '970' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scan-face preview-icon w-4 h-4"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><path d="M9 9h.01"/><path d="M15 9h.01"/></svg>',
);

$menu['menu910'] = array(
    array('910000', '대시보드', G5_ADMIN_URL . '/platform/dashboard.php', 'platform_dashboard'),
    array('910100', '대시보드', G5_ADMIN_URL . '/platform/dashboard.php', 'platform_dashboard'),
);

$menu['menu920'] = array(
    array('920000', '처리업무', G5_ADMIN_URL . '/platform/work_queue.php', 'platform_work_queue'),
    array('920100', '처리업무함', G5_ADMIN_URL . '/platform/work_queue.php', 'platform_work_queue'),
);

$menu['menu930'] = array(
    array('930000', '브랜드·상품', G5_ADMIN_URL . '/platform/partners/brands.php', 'platform_partners'),
    array('930100', '브랜드 서류 심사', G5_ADMIN_URL . '/platform/partners/brands.php', 'platform_partners_brands'),
    array('930200', '입점사 관리', G5_ADMIN_URL . '/platform/partners/brand_list.php', 'platform_partners_brand_list'),
    array('930300', '상품 검수', G5_ADMIN_URL . '/platform/partners/product_review.php', 'platform_partners_product_review'),
    array('930400', '추가 토핑 심사', G5_ADMIN_URL . '/platform/partners/campaign_review.php', 'platform_partners_campaign_review'),
);

$menu['menu940'] = array(
    array('940000', '도트·도넛', G5_ADMIN_URL . '/platform/community/dots.php', 'platform_community'),
    array('940100', '도트 관리', G5_ADMIN_URL . '/platform/community/dots.php', 'platform_community_dots'),
    array('940200', '도티 역할 부여 예외', G5_ADMIN_URL . '/platform/community/account_link_review.php', 'platform_community_account_link_review'),
    array('940300', '도넛 관리', G5_ADMIN_URL . '/platform/community/donuts.php', 'platform_community_donuts'),
    array('940400', '도티 사업자 심사', G5_ADMIN_URL . '/platform/community/dotty_review.php', 'platform_community_dotty_review'),
    array('940500', '도넛 운영자 권한', G5_ADMIN_URL . '/platform/community/delegate_audit.php', 'platform_community_delegate_audit'),
    array('940600', '도티 승계 심사', G5_ADMIN_URL . '/platform/community/transfer_review.php', 'platform_community_transfer_review'),
    array('940700', '커뮤니티 모니터링', G5_ADMIN_URL . '/platform/community/community_audit.php', 'platform_community_community_audit'),
);

$menu['menu950'] = array(
    array('950000', '주문·배송', G5_ADMIN_URL . '/platform/trade/orders.php', 'platform_trade'),
    array('950100', '거래 조회', G5_ADMIN_URL . '/platform/trade/orders.php', 'platform_trade_orders'),
    array('950200', '배송정책·그룹', G5_ADMIN_URL . '/platform/trade/shipping_audit.php', 'platform_trade_shipping_audit'),
    array('950300', '클레임·분쟁', G5_ADMIN_URL . '/platform/trade/disputes.php', 'platform_trade_disputes'),
    array('950400', '결제 대사', G5_ADMIN_URL . '/platform/trade/payment_audit.php', 'platform_trade_payment_audit'),
);

$menu['menu960'] = array(
    array('960000', '토핑·정산', G5_ADMIN_URL . '/platform/money/manual_review.php', 'platform_money'),
    array('960100', '검토 기록', G5_ADMIN_URL . '/platform/money/manual_review.php', 'platform_money_manual_review'),
    array('960200', '토핑 발생 로그', G5_ADMIN_URL . '/platform/money/ledger.php', 'platform_money_ledger'),
    array('960300', '도넛 토핑 자격', G5_ADMIN_URL . '/platform/money/topping_eligibility.php', 'platform_money_topping_eligibility'),
    array('960400', '정산 검토', G5_ADMIN_URL . '/platform/money/settlement.php', 'platform_money_settlement'),
    array('960500', '보류·에스크로', G5_ADMIN_URL . '/platform/money/escrow.php', 'platform_money_escrow'),
);

$menu['menu970'] = array(
    array('970000', '정책·시스템', G5_ADMIN_URL . '/platform/policy/policy.php', 'platform_policy'),
    array('970100', '정책 파라미터', G5_ADMIN_URL . '/platform/policy/policy.php', 'platform_policy_policy'),
    array('970200', '플랫폼 관리자·권한', G5_ADMIN_URL . '/platform/policy/operators.php', 'platform_policy_operators'),
    array('970300', '시스템·연동', G5_ADMIN_URL . '/platform/policy/system.php', 'platform_policy_system'),
    array('970400', '관리자 처리 로그', G5_ADMIN_URL . '/platform/policy/logs.php', 'platform_policy_logs'),
);

// 권한 부여
$auth['910100'] = 'r,w,d';

$auth['920100'] = 'r,w,d';

$auth['930100'] = 'r,w,d';
$auth['930200'] = 'r,w,d';
$auth['930300'] = 'r,w,d';
$auth['930400'] = 'r,w,d';

$auth['940100'] = 'r,w,d';
$auth['940200'] = 'r,w,d';
$auth['940300'] = 'r,w,d';
$auth['940400'] = 'r,w,d';
$auth['940500'] = 'r,w,d';
$auth['940600'] = 'r,w,d';
$auth['940700'] = 'r,w,d';

$auth['950100'] = 'r,w,d';
$auth['950200'] = 'r,w,d';
$auth['950300'] = 'r,w,d';
$auth['950400'] = 'r,w,d';

$auth['960100'] = 'r,w,d';
$auth['960200'] = 'r,w,d';
$auth['960300'] = 'r,w,d';
$auth['960400'] = 'r,w,d';
$auth['960500'] = 'r,w,d';

$auth['970100'] = 'r,w,d';
$auth['970200'] = 'r,w,d';
$auth['970300'] = 'r,w,d';
$auth['970400'] = 'r,w,d';


$amenu['910'] = 'platform_menu910';
$amenu['920'] = 'platform_menu920';
$amenu['930'] = 'platform_menu930';
$amenu['940'] = 'platform_menu940';
$amenu['950'] = 'platform_menu950';
$amenu['960'] = 'platform_menu960';
$amenu['970'] = 'platform_menu970';

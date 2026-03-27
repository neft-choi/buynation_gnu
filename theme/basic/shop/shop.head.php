<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$q = isset($_GET['q']) ? clean_xss_tags($_GET['q'], 1, 1) : '';

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MSHOP_PATH . '/shop.head.php');
    return;
}

include_once(G5_THEME_PATH . '/head.sub.php');
include_once(G5_LIB_PATH . '/outlogin.lib.php');
include_once(G5_LIB_PATH . '/poll.lib.php');
include_once(G5_LIB_PATH . '/visit.lib.php');
include_once(G5_LIB_PATH . '/connect.lib.php');
include_once(G5_LIB_PATH . '/popular.lib.php');
include_once(G5_LIB_PATH . '/latest.lib.php');

add_javascript('<script src="' . G5_JS_URL . '/owlcarousel/owl.carousel.min.js"></script>', 10);
add_stylesheet('<link rel="stylesheet" href="' . G5_JS_URL . '/owlcarousel/owl.carousel.css">', 0);

// 현재 실행 중인 PHP 파일명만 체크
$current_shop_page = basename($_SERVER['SCRIPT_NAME']);

// 페이지별 헤더 직접 설정 시 사용할 기본값
$header_defaults = array(
    'layout' => 'default',
    'show_back' => false,
    'show_title' => false,
    'show_search_button' => false,
    'show_searchbar' => false,
    'show_notice' => false,
    'show_cart' => false,
    'title' => $g5['title'],
    'title_class' => 'text-base font-semibold',
    'back_mode' => 'fallback',
);

// 페이지에서 전달한 헤더 타입 (미지정 시 default)
$variant = isset($shop_header_variant) ? (string)$shop_header_variant : 'default';

// 헤더 타입별 표시 옵션 맵
$header_map = array(
    'default' => array(
        'layout' => 'default',
        'show_back' => false,
        'show_title' => false,
        'show_search_button' => false,
        'show_cart' => false,
        'title' => $g5['title'],
        'title_class' => 'text-base font-semibold',
        'back_mode' => 'fallback',
    ),
    'cart.php' => array(
        'layout' => 'center_title',
        'show_back' => true,
        'show_title' => true,
        'show_search_button' => false,
        'show_cart' => false,
        'title' => '장바구니',
        'title_class' => 'text-center text-xl font-semibold',
        'back_mode' => 'fallback',
    ),
    'orderform.php' => array(
        'layout' => 'center_title',
        'show_back' => true,
        'show_title' => true,
        'show_search_button' => false,
        'show_cart' => false,
        'title' => '주문하기',
        'title_class' => 'text-center text-xl font-semibold',
        'back_mode' => 'fallback',
    ),
    'item.php' => array(
        'layout' => 'side_actions',
        'show_back' => true,
        'show_title' => false,
        'show_search_button' => true,
        'show_cart' => true,
        'title' => '',
        'title_class' => 'text-base font-semibold',
        'back_mode' => 'fallback',
    ),

);

// 별도 커스텀 variant를 주지 않았으면 현재 페이지 파일명으로 직접 조회
if ($variant === 'default' && isset($header_map[$current_shop_page])) {
    $variant = $current_shop_page;
}

// 기존 variant 값(cart/orderform/item/list)도 파일명 키(cart.php ...)로 호환
if (!isset($header_map[$variant]) && isset($header_map[$variant . '.php'])) {
    $variant = $variant . '.php';
}

// 페이지에서 $shop_header를 직접 전달하면 해당 값을 우선 사용
if (isset($shop_header) && is_array($shop_header)) {
    $header = array_merge($header_defaults, $shop_header);
    $is_custom_header = ($header['layout'] !== 'default');
} else {
    // 현재 타입에 맞는 설정 선택 (없으면 default)
    $header = isset($header_map[$variant]) ? $header_map[$variant] : $header_map['default'];
    $header = array_merge($header_defaults, $header);
    $is_custom_header = ($variant !== 'default' && isset($header_map[$variant]));
}

$shop_home_url = G5_SHOP_URL . '/';
$back_onclick_with_fallback = "if (history.length > 1) { history.back(); } else { location.href='" . $shop_home_url . "'; }";
$custom_back_onclick = ($header['back_mode'] === 'history') ? 'history.back();' : $back_onclick_with_fallback;
?>


<div id="app-shell" class="app-shell">
    <!-- 상단 시작 { -->
    <header id="hd">
        <h1 id="hd_h1"><?php echo $g5['title'] ?></h1>
        <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>

        <?php if (defined('_INDEX_')) { // index에서만 실행
            include G5_BBS_PATH . '/newwin.inc.php'; // 팝업레이어
        } ?>

        <!-- 상품 커스텀 헤더 시작 -->
        <?php if ($is_custom_header) { ?>
            <div id="hd_wrapper">
                <div id="shop-header" class="<?php echo ($header['layout'] === 'center_title') ? 'w-full min-h-14 grid grid-cols-[36px_1fr_36px] items-center px-2' : 'w-full min-h-14 flex items-center justify-between pl-2 pr-4'; ?>">
                    <?php if ($header['layout'] === 'center_title') { ?>
                        <?php if ($header['show_back']) { ?>
                            <button type="button"
                                class="inline-flex h-9 w-9 items-center justify-center text-gray-800"
                                onclick="<?php echo $custom_back_onclick; ?>"
                                aria-label="뒤로가기">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m15 18-6-6 6-6" />
                                </svg>
                            </button>
                        <?php } else { ?>
                            <span aria-hidden="true" class="inline-flex h-9 w-9"></span>
                        <?php } ?>

                        <?php if ($header['show_title']) { ?>
                            <div class="<?php echo $header['title_class']; ?>"><?php echo $header['title']; ?></div>
                        <?php } else { ?>
                            <span aria-hidden="true"></span>
                        <?php } ?>

                        <span aria-hidden="true" class="inline-flex h-9 w-9"></span>
                    <?php } else { ?>
                        <div class="flex items-center gap-2">
                            <?php if ($header['show_back']) { ?>
                                <button type="button"
                                    class="inline-flex h-9 w-9 items-center justify-center text-gray-800"
                                    onclick="<?php echo $custom_back_onclick; ?>"
                                    aria-label="뒤로가기">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="m15 18-6-6 6-6" />
                                    </svg>
                                </button>
                            <?php } ?>

                            <?php if ($header['show_title']) { ?>
                                <div class="<?php echo $header['title_class']; ?>"><?php echo $header['title']; ?></div>
                            <?php } ?>
                        </div>

                        <div class="flex items-center gap-2">
                            <?php if ($header['show_search_button']) { ?>
                                <span class="inline-flex h-9 w-9 items-center justify-center text-gray-800" aria-label="검색">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="11" cy="11" r="8" />
                                        <path d="m21 21-4.3-4.3" />
                                    </svg>
                                </span>
                            <?php } ?>

                            <?php if ($header['show_notice']) { ?>
                                <button type="button"
                                    class="inline-flex h-9 w-9 items-center justify-center text-gray-800"
                                    aria-label="알림"
                                    aria-expanded="false"
                                    aria-controls="notice-panel">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell-icon lucide-bell">
                                        <path d="M10.268 21a2 2 0 0 0 3.464 0" />
                                        <path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />
                                    </svg>
                                </button>
                            <?php } ?>

                            <?php if ($header['show_cart']) { ?>
                                <a href="<?php echo G5_SHOP_URL; ?>/cart.php"
                                    class="relative inline-flex h-9 w-9 items-center justify-center text-gray-800"
                                    aria-label="장바구니">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart-icon lucide-shopping-cart">
                                        <circle cx="8" cy="21" r="1" />
                                        <circle cx="19" cy="21" r="1" />
                                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                                    </svg>
                                    <span class="absolute -top-1 -right-1 inline-flex min-w-4 h-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-semibold leading-none text-white">
                                        <?php echo get_boxcart_datas_count(); ?>
                                    </span>
                                </a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <?php if ($header['show_searchbar']) { ?>
                <?php include_once(G5_THEME_SHOP_PATH . '/searchbar.modern.php'); ?>
            <?php } ?>
            <!-- 상품 커스텀 헤더 끝 -->

        <?php } else { ?>

            <!-- 기본 헤더 + 검색 바 시작 -->
            <div id="hd_wrapper" class="!bg-white">
                <div id="shop-header" class="w-full min-h-16 flex items-center justify-between px-4">
                    <a href="<?php echo G5_SHOP_URL; ?>/" class="text-lg font-semibold tracking-tight text-gray-900">
                        BUYNATION
                    </a>

                    <div class="flex items-center gap-2 text-2xl">
                        <?= get_notification() ?>
                    </div>
                </div>

                <!-- 커스텀 검색 바 -->
                <?php include_once(G5_THEME_SHOP_PATH . '/searchbar.modern.php'); ?>
                <!-- 커스텀 검색 바 끝 -->

                <!-- 쇼핑몰 배너 시작 { -->
                <?php // echo display_banner('왼쪽'); 
                ?>
                <!-- } 쇼핑몰 배너 끝 -->

                <ul class="hd_login !hidden">
                    <?php if ($is_member) {  ?>
                        <li class="shop_login">
                            <?php echo outlogin('theme/shop_basic'); // 아웃로그인 
                            ?>
                        </li>
                        <li class="shop_cart"><a href="<?php echo G5_SHOP_URL; ?>/cart.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span class="sound_only">장바구니</span><span class="count"><?php echo get_boxcart_datas_count(); ?></span></a></li>
                    <?php } else { ?>
                        <li class="login"><a href="<?php echo G5_BBS_URL ?>/login.php?url=<?php echo $urlencode; ?>">로그인</a></li>
                    <?php }  ?>
                </ul>
            </div>

            <?php
            $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
            $current_type = isset($_GET['type']) ? (string) $_GET['type'] : '';

            // rewrite URL(/shop/type-1) 대비 fallback
            if ($current_type === '' && preg_match('~/shop/type-([0-9]+)~', $request_uri, $m)) {
                $current_type = $m[1];
            }

            $is_home = defined('_INDEX_') || preg_match('~/shop/?$~', parse_url($request_uri, PHP_URL_PATH));
            $is_new = ($current_type === '1');
            $is_best = ($current_type === '2');
            $is_pick = ($current_type === '3');
            $is_benefit = ($current_type === '4');
            ?>

            <nav id="hd_menu" class="!w-full !bg-white py-2">
                <button type="button" id="menu_open" class="!hidden"><i class="fa fa-bars" aria-hidden="true"></i> 카테고리</button>
                <?php include_once(G5_THEME_SHOP_PATH . '/category.php'); // 분류 
                ?>
                <ul class="shop-nav scrollbar-hidden flex gap-8 overflow-x-auto whitespace-nowrap text-base text-gray-600 font-medium px-4">
                    <li class="<?php echo $is_home ? 'is-active' : ''; ?>">
                        <a href="/shop" <?php echo $is_home ? 'aria-current="page"' : ''; ?>>홈</a>
                    </li>

                    <!-- <li><a href="<?php echo shop_type_url(1); ?>">히트상품</a></li>
                    <li><a href="<?php echo shop_type_url(2); ?>">추천상품</a></li>
                    <li><a href="<?php echo shop_type_url(3); ?>">신상품</a></li>
                    <li><a href="<?php echo shop_type_url(4); ?>">인기상품</a></li>
                    <li><a href="<?php echo shop_type_url(5); ?>">베스트상품</a></li>
                    <li><a href="<?php echo shop_type_url(5); ?>">할인상품</a></li> -->

                    <li class="<?php echo $is_new ? 'is-active' : ''; ?>">
                        <a href="<?php echo shop_type_url(1); ?>" <?php echo $is_new ? 'aria-current="page"' : ''; ?>>히트상품</a>
                    </li>

                    <li class="<?php echo $is_best ? 'is-active' : ''; ?>">
                        <a href="<?php echo shop_type_url(2); ?>" <?php echo $is_best ? 'aria-current="page"' : ''; ?>>추천상품</a>
                    </li>

                    <li class="<?php echo $is_pick ? 'is-active' : ''; ?>">
                        <a href="<?php echo shop_type_url(3); ?>" class="flex items-center gap-2" <?php echo $is_pick ? 'aria-current="page"' : ''; ?>>
                            <!-- <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="var(--color-primary)" stroke="var(--color-primary)" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkle-icon lucide-sparkle">
                                <path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z" />
                            </svg> -->
                            <span>신상품</span>
                        </a>
                    </li>

                    <li class="<?php echo $is_benefit ? 'is-active' : ''; ?>">
                        <a href="<?php echo shop_type_url(4); ?>" <?php echo $is_benefit ? 'aria-current="page"' : ''; ?>>베스트상품</a>
                    </li>
                </ul>
            </nav>

            <?php if ($is_best) { ?>
                <!-- <section id="best-category-bar" class="px-4 py-2 bg-white" aria-label="베스트 카테고리 필터">
                    <div class="relative">
                        <div class="scrollbar-hidden overflow-x-auto whitespace-nowrap pr-12">
                            <div class="inline-flex items-center gap-3">
                                <button type="button" class="inline-flex items-center rounded bg-neutral-700 px-4 py-2 text-sm font-semibold text-white" aria-pressed="true" data-best-filter="all">전체</button>
                                <button type="button" class="inline-flex items-center rounded bg-neutral-100 px-4 py-2 text-sm font-medium text-neutral-500" aria-pressed="false" data-best-filter="fashion">패션/의류</button>
                                <button type="button" class="inline-flex items-center rounded bg-neutral-100 px-4 py-2 text-sm font-medium text-neutral-500" aria-pressed="false" data-best-filter="luxury">명품</button>
                                <button type="button" class="inline-flex items-center rounded bg-neutral-100 px-4 py-2 text-sm font-medium text-neutral-500" aria-pressed="false" data-best-filter="beauty">뷰티</button>
                                <button type="button" class="inline-flex items-center rounded bg-neutral-100 px-4 py-2 text-sm font-medium text-neutral-500" aria-pressed="false" data-best-filter="baby">유아/완구</button>
                                <button type="button" class="inline-flex items-center rounded bg-neutral-100 px-4 py-2 text-sm font-medium text-neutral-500" aria-pressed="false" data-best-filter="baby">스포츠/레저</button>
                                <button type="button" class="inline-flex items-center rounded bg-neutral-100 px-4 py-2 text-sm font-medium text-neutral-500" aria-pressed="false" data-best-filter="baby">가구/인테리어</button>
                                <button type="button" class="inline-flex items-center rounded bg-neutral-100 px-4 py-2 text-sm font-medium text-neutral-500" aria-pressed="false" data-best-filter="baby">주방/생활/건강</button>
                                <button type="button" class="inline-flex items-center rounded bg-neutral-100 px-4 py-2 text-sm font-medium text-neutral-500" aria-pressed="false" data-best-filter="baby">반려동물</button>
                                <button type="button" class="inline-flex items-center rounded bg-neutral-100 px-4 py-2 text-sm font-medium text-neutral-500" aria-pressed="false" data-best-filter="baby">식품</button>
                                <button type="button" class="inline-flex items-center rounded bg-neutral-100 px-4 py-2 text-sm font-medium text-neutral-500" aria-pressed="false" data-best-filter="baby">가전</button>
                                <button type="button" class="inline-flex items-center rounded bg-neutral-100 px-4 py-2 text-sm font-medium text-neutral-500" aria-pressed="false" data-best-filter="baby">디지털/렌탈</button>
                            </div>
                        </div>
                        <button type="button"
                            id="best-category-more"
                            class="absolute right-0 top-1/2 inline-flex h-8 w-8 -translate-y-1/2 items-center justify-center rounded-full border border-neutral-300 bg-white text-neutral-700 shadow-sm"
                            aria-label="베스트 카테고리 전체 보기"
                            aria-haspopup="dialog"
                            aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                    </div>
                </section> -->
            <?php } ?>

        <?php } ?>
    </header>
    <!-- } 상단 끝 -->

    <div id="side_menu" class="!hidden">
        <ul id="quick">
            <li><button class="btn_sm_cl1 btn_sm"><i class="fa fa-user-o" aria-hidden="true"></i><span class="qk_tit">마이메뉴</span></button></li>
            <li><button class="btn_sm_cl2 btn_sm"><i class="fa fa-archive" aria-hidden="true"></i><span class="qk_tit">오늘 본 상품</span></button></li>
            <li><button class="btn_sm_cl3 btn_sm"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span class="qk_tit">장바구니</span></button></li>
            <li><button class="btn_sm_cl4 btn_sm"><i class="fa fa-heart-o" aria-hidden="true"></i><span class="qk_tit">위시리스트</span></button></li>
        </ul>
        <button type="button" id="top_btn"><i class="fa fa-arrow-up" aria-hidden="true"></i><span class="sound_only">상단으로</span></button>
        <div id="tabs_con">
            <div class="side_mn_wr1 qk_con">
                <div class="qk_con_wr">
                    <?php echo outlogin('theme/shop_side'); // 아웃로그인 
                    ?>
                    <ul class="side_tnb">
                        <?php if ($is_member) { ?>
                            <li><a href="<?php echo G5_SHOP_URL; ?>/mypage.php">마이페이지</a></li>
                        <?php } ?>
                        <li><a href="<?php echo G5_SHOP_URL; ?>/orderinquiry.php">주문내역</a></li>
                        <li><a href="<?php echo G5_BBS_URL ?>/faq.php">FAQ</a></li>
                        <li><a href="<?php echo G5_BBS_URL ?>/qalist.php">1:1문의</a></li>
                        <li><a href="<?php echo G5_SHOP_URL ?>/personalpay.php">개인결제</a></li>
                        <li><a href="<?php echo G5_SHOP_URL ?>/itemuselist.php">사용후기</a></li>
                        <li><a href="<?php echo G5_SHOP_URL ?>/itemqalist.php">상품문의</a></li>
                        <li><a href="<?php echo G5_SHOP_URL; ?>/couponzone.php">쿠폰존</a></li>
                    </ul>
                    <?php // include_once(G5_SHOP_SKIN_PATH.'/boxcommunity.skin.php'); // 커뮤니티 
                    ?>
                    <button type="button" class="con_close"><i class="fa fa-times-circle" aria-hidden="true"></i><span class="sound_only">나의정보 닫기</span></button>
                </div>
            </div>
            <div class="side_mn_wr2 qk_con">
                <div class="qk_con_wr">
                    <?php include(G5_SHOP_SKIN_PATH . '/boxtodayview.skin.php'); // 오늘 본 상품 
                    ?>
                    <button type="button" class="con_close"><i class="fa fa-times-circle" aria-hidden="true"></i><span class="sound_only">오늘 본 상품 닫기</span></button>
                </div>
            </div>
            <div class="side_mn_wr3 qk_con">
                <div class="qk_con_wr">
                    <?php include_once(G5_SHOP_SKIN_PATH . '/boxcart.skin.php'); // 장바구니 
                    ?>
                    <button type="button" class="con_close"><i class="fa fa-times-circle" aria-hidden="true"></i><span class="sound_only">장바구니 닫기</span></button>
                </div>
            </div>
            <div class="side_mn_wr4 qk_con">
                <div class="qk_con_wr">
                    <?php include_once(G5_SHOP_SKIN_PATH . '/boxwish.skin.php'); // 위시리스트 
                    ?>
                    <button type="button" class="con_close"><i class="fa fa-times-circle" aria-hidden="true"></i><span class="sound_only">위시리스트 닫기</span></button>
                </div>
            </div>
        </div>
    </div>
    <script>
        jQuery(function($) {
            $(".btn_member_mn").on("click", function() {
                $(".member_mn").toggle();
                $(".btn_member_mn").toggleClass("btn_member_mn_on");
            });

            var active_class = "btn_sm_on",
                side_btn_el = "#quick .btn_sm",
                quick_container = ".qk_con";

            $(document).on("click", side_btn_el, function(e) {
                e.preventDefault();

                var $this = $(this);

                if (!$this.hasClass(active_class)) {
                    $(side_btn_el).removeClass(active_class);
                    $this.addClass(active_class);
                }

                if ($this.hasClass("btn_sm_cl1")) {
                    $(".side_mn_wr1").show();
                } else if ($this.hasClass("btn_sm_cl2")) {
                    $(".side_mn_wr2").show();
                } else if ($this.hasClass("btn_sm_cl3")) {
                    $(".side_mn_wr3").show();
                } else if ($this.hasClass("btn_sm_cl4")) {
                    $(".side_mn_wr4").show();
                }
            }).on("click", ".con_close", function(e) {
                $(quick_container).hide();
                $(side_btn_el).removeClass(active_class);
            });

            $(document).mouseup(function(e) {
                var container = $(quick_container),
                    mn_container = $(".shop_login");
                if (container.has(e.target).length === 0) {
                    container.hide();
                    $(side_btn_el).removeClass(active_class);
                }
                if (mn_container.has(e.target).length === 0) {
                    $(".member_mn").hide();
                    $(".btn_member_mn").removeClass("btn_member_mn_on");
                }
            });

            $("#top_btn").on("click", function() {
                $("html, body").animate({
                    scrollTop: 0
                }, '500');
                return false;
            });
        });
    </script>
    <?php
    $wrapper_class = array();
    if (defined('G5_IS_COMMUNITY_PAGE') && G5_IS_COMMUNITY_PAGE) {
        $wrapper_class[] = 'is_community';
    }
    ?>
    <!-- 전체 콘텐츠 시작 { -->
    <main id="wrapper" class="<?php echo implode(' ', $wrapper_class); ?>">
        <!-- #container 시작 { -->
        <div id="container" class="!w-full !bg-white">

            <?php if (defined('_INDEX_')) { ?>
                <aside id="aside" class="!hidden">
                    <?php include_once(G5_SHOP_SKIN_PATH . '/boxcategory.skin.php'); // 상품분류 
                    ?>
                    <?php if ($default['de_type4_list_use']) { ?>
                        <!-- 인기상품 시작 { -->
                        <section id="side_pd">
                            <h2><a href="<?php echo shop_type_url('4'); ?>">인기상품</a></h2>
                            <?php
                            $list = new item_list();
                            $list->set_type(4);
                            $list->set_view('it_id', false);
                            $list->set_view('it_name', true);
                            $list->set_view('it_basic', false);
                            $list->set_view('it_cust_price', false);
                            $list->set_view('it_price', true);
                            $list->set_view('it_icon', false);
                            $list->set_view('sns', false);
                            $list->set_view('star', true);
                            echo $list->run();
                            ?>
                        </section>
                        <!-- } 인기상품 끝 -->
                    <?php } ?>

                    <?php echo display_banner('왼쪽', 'boxbanner.skin.php'); ?>
                    <?php echo poll('theme/shop_basic'); // 설문조사 
                    ?>
                </aside>
            <?php } // end if 
            ?>
            <?php
            $content_class = array('shop-content');
            if (isset($it_id) && isset($it) && isset($it['it_id']) && $it_id === $it['it_id']) {
                $content_class[] = 'is_item';
            }
            if (defined('IS_SHOP_SEARCH') && IS_SHOP_SEARCH) {
                $content_class[] = 'is_search';
            }
            if (defined('_INDEX_') && _INDEX_) {
                $content_class[] = 'is_index';
            }
            ?>
            <!-- .shop-content 시작 { -->
            <div class="<?php echo implode(' ', $content_class); ?> !p-0 !ml-0">
                <?php if ((!$bo_table || $w == 's') && !defined('_INDEX_') && $current_shop_page !== 'listtype.php') { ?><div id="wrapper_title"><?php echo $g5['title'] ?></div><?php } ?>
                <!-- 글자크기 조정 display:none 되어 있음 시작 { -->
                <div id="text_size">
                    <button class="no_text_resize" onclick="font_resize('container', 'decrease');">작게</button>
                    <button class="no_text_resize" onclick="font_default('container');">기본</button>
                    <button class="no_text_resize" onclick="font_resize('container', 'increase');">크게</button>
                </div>
                <!-- } 글자크기 조정 display:none 되어 있음 끝 -->
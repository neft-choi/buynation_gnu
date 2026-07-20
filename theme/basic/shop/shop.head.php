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
?>

<div id="app-shell" class="app-shell">
    <!-- 상단 시작 -->
    <header id="hd" class="sticky pc:static top-0 left-0 z-30 !bg-white">
        <h1 id="hd_h1"><?php echo $g5['title'] ?></h1>
        <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>

        <?php if (defined('_INDEX_')) { // index에서만 실행
            include G5_BBS_PATH . '/newwin.inc.php'; // 팝업레이어
        } ?>

        <!-- 기본 헤더 본문 (로고/아이콘/검색) -->
        <div id="hd_wrapper">
            <div class="header-inner mx-auto w-full max-w-[var(--breakpoint-pc)] p-4 pb-0 space-y-4">
                <div id="shop-header" class="flex items-center justify-between">
                    <div class="inline-flex items-center gap-10">
                        <a href="<?php echo G5_SHOP_URL; ?>/" id="shop-logo-link" class="block">
                            <img src="<?php echo G5_DATA_URL; ?>/common/logo_img" alt="<?php echo $config['cf_title']; ?>">
                        </a>

                        <div id="shop-searchbar-desktop-slot"></div>
                    </div>

                    <div class="flex items-center gap-2 text-2xl">
                        <?= get_notification() ?>
                    </div>
                </div>

                <!-- 최근 본 상품 -->
                <div id="recent-viewed-backdrop" class="hidden fixed inset-0 z-40 bg-black/40"></div>

                <div id="recent-viewed-panel" class="fixed top-0 right-0 z-50 flex flex-col h-full w-full max-w-[440px] translate-x-full bg-white">
                    <div class="flex items-center justify-between border-b border-zinc-200 px-5 py-6">
                        <p class="text-lg font-semibold text-gray-900">최근 본 상품</p>

                        <button type="button"
                            id="recent-viewed-close"
                            class="inline-flex items-center justify-center w-9 h-9 rounded text-gray-700 hover:bg-gray-300 cursor-pointer"
                            aria-label="최근 본 상품 닫기">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x w-6 h-6">
                                <path d="M18 6 6 18" />
                                <path d="m6 6 12 12" />
                            </svg>
                        </button>
                    </div>

                    <div id="recent-viewed-list" class="min-h-0 flex-1 overflow-y-auto px-5 py-6">
                        <?php include(G5_SHOP_SKIN_PATH . '/boxtodayview.skin.php'); // 최근 본 상품 
                        ?>
                    </div>
                </div>

                <script>
                    // 최근 본 상품 패널 열기/닫기 제어
                    $(function() {
                        $(document).on('click', '#recent-viewed-open', function() {
                            $('#recent-viewed-backdrop').removeClass('hidden');
                            $('#recent-viewed-panel').removeClass('translate-x-full');
                            $('html, body').addClass('!overflow-hidden');
                        });

                        $(document).on('click', '#recent-viewed-close, #recent-viewed-backdrop', function() {
                            $('#recent-viewed-backdrop').addClass('hidden');
                            $('#recent-viewed-panel').addClass('translate-x-full');
                            $('html, body').removeClass('!overflow-hidden');
                        });
                    });
                </script>

                <!-- 검색 바 -->
                <div id="shop-searchbar-default-slot">
                    <?php
                    $searchbar_mode = 'shop';
                    include_once(G5_THEME_PATH . '/_searchbar.php');
                    ?>
                </div>
                <script>
                    // PC 반응형 분기
                    syncWithPcBreakpoint(function(isPc) {
                        const searchbarRoot = document.getElementById('shop-searchbar-root');
                        const desktopSlot = document.getElementById('shop-searchbar-desktop-slot');
                        const defaultSlot = document.getElementById('shop-searchbar-default-slot');

                        // 검색 바 위치 변경
                        if (searchbarRoot && desktopSlot && defaultSlot) {
                            if (isPc) {
                                desktopSlot.appendChild(searchbarRoot);
                            } else {
                                defaultSlot.appendChild(searchbarRoot);
                            }
                        }
                    });
                </script>
                <!-- 검색 바 끝 -->

                <ul class="hd_login !hidden">
                    <?php if ($is_member) {  ?>
                        <li class="shop_login">
                            <?php 
                            // echo outlogin('theme/shop_basic'); 
                            // 아웃로그인 
                            ?>
                        </li>
                        <li class="shop_cart"><a href="<?php echo G5_SHOP_URL; ?>/cart.php"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span class="sound_only">장바구니</span><span class="count"><?php echo get_boxcart_datas_count(); ?></span></a></li>
                    <?php } else { ?>
                        <li class="login"><a href="<?php echo G5_BBS_URL ?>/login.php?url=<?php echo $urlencode; ?>">로그인</a></li>
                    <?php }  ?>
                </ul>
            </div>
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

        <nav id="hd_menu" class="!w-full max-w-[var(--breakpoint-pc)] !bg-white py-2">
            <button type="button" id="menu_open" class="!hidden"><i class="fa fa-bars" aria-hidden="true"></i> 카테고리</button>
            <ul class="shop-nav scrollbar-hidden flex items-center gap-8 overflow-x-auto overflow-y-hidden whitespace-nowrap text-base text-gray-600 pc:text-[#111] font-medium px-4">
                <li class="hidden pc:block">
                    <button type="button" class="flex items-center gap-2 cursor-pointer" id="category_open" aria-label="카테고리 열기">
                        <span id="category_open_icon" class="inline-flex items-center justify-center h-7 w-7 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu-icon lucide-menu h-5 w-5">
                                <path d="M4 5h16" />
                                <path d="M4 12h16" />
                                <path d="M4 19h16" />
                            </svg>
                        </span>
                        <span>카테고리</span>
                    </button>
                </li>

                <li class="pc:hidden <?php echo $is_home ? 'is-active' : ''; ?>">
                    <a href="/shop" <?php echo $is_home ? 'aria-current="page"' : ''; ?>>홈</a>
                </li>

                <li class="<?php echo $is_new ? 'is-active' : ''; ?>">
                    <a href="<?php echo shop_type_url(1); ?>" <?php echo $is_new ? 'aria-current="page"' : ''; ?>>히트상품</a>
                </li>

                <li class="<?php echo $is_best ? 'is-active' : ''; ?>">
                    <a href="<?php echo shop_type_url(2); ?>" <?php echo $is_best ? 'aria-current="page"' : ''; ?>>추천상품</a>
                </li>

                <li class="<?php echo $is_pick ? 'is-active' : ''; ?>">
                    <a href="<?php echo shop_type_url(3); ?>" <?php echo $is_pick ? 'aria-current="page"' : ''; ?>>
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="var(--color-primary)" stroke="var(--color-primary)" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkle-icon lucide-sparkle">
                                <path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z" />
                            </svg> -->
                        <span>신상품</span>
                    </a>
                </li>

                <li class="<?php echo $is_benefit ? 'is-active' : ''; ?>">
                    <a href="<?php echo shop_type_url(4); ?>" <?php echo $is_benefit ? 'aria-current="page"' : ''; ?>>베스트상품</a>
                </li>


                <li class="hidden pc:block ml-auto">
                    <a href="<?php echo G5_URL; ?>/index.php" class="inline-flex items-center justify-center gap-1 px-4 py-2 text-white bg-[var(--color-primary-strong)] rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="var(--color-primary-strong)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-more-icon lucide-message-circle-more w-5 h-5">
                            <path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719" />
                            <path d="M8 12h.01" />
                            <path d="M12 12h.01" />
                            <path d="M16 12h.01" />
                        </svg>
                        <span>커뮤니티</span>
                    </a>
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
                    <?php 
                    // echo outlogin('theme/shop_side'); 
                    // 아웃로그인 
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
                    <?php
                    // include(G5_SHOP_SKIN_PATH . '/boxtodayview.skin.php'); // 오늘 본 상품 
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
    <main id="wrapper" class="relative <?php echo implode(' ', $wrapper_class); ?>">
        <!-- #container 시작 { -->
        <div id="container" class="max-w-[var(--breakpoint-pc)] !w-full !bg-white">

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
                <?php if ((!$bo_table || $w == 's') && !defined('_INDEX_') && basename($_SERVER['SCRIPT_NAME']) !== 'listtype.php') { ?><div id="wrapper_title"><?php echo $g5['title'] ?></div><?php } ?>
                <!-- 글자크기 조정 display:none 되어 있음 시작 { -->
                <div id="text_size">
                    <button class="no_text_resize" onclick="font_resize('container', 'decrease');">작게</button>
                    <button class="no_text_resize" onclick="font_default('container');">기본</button>
                    <button class="no_text_resize" onclick="font_resize('container', 'increase');">크게</button>
                </div>
                <!-- } 글자크기 조정 display:none 되어 있음 끝 -->
<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH . '/head.php');
    return;
}

// 커뮤니티 기능을 사용하지 않을 시 이후 커뮤니티용 헤더 처리를 멈추고 
// 쇼핑몰 shop.head.php를 대신 불러오는 분기문
if (G5_COMMUNITY_USE === false) {
    define('G5_IS_COMMUNITY_PAGE', true);
    include_once(G5_THEME_SHOP_PATH . '/shop.head.php');
    return;
}

include_once(G5_THEME_PATH . '/head.sub.php');
include_once(G5_LIB_PATH . '/latest.lib.php');
include_once(G5_LIB_PATH . '/outlogin.lib.php');
include_once(G5_LIB_PATH . '/poll.lib.php');
include_once(G5_LIB_PATH . '/visit.lib.php');
include_once(G5_LIB_PATH . '/connect.lib.php');
include_once(G5_LIB_PATH . '/popular.lib.php');

// 그누보드 내장 케러셀
add_javascript('<script src="' . G5_JS_URL . '/owlcarousel/owl.carousel.min.js"></script>', 10);
add_stylesheet('<link rel="stylesheet" href="' . G5_JS_URL . '/owlcarousel/owl.carousel.min.css">', 10);
?>

<div id="app-shell" class="flex flex-col w-full min-h-screen bg-[#F5F5F5]">
    <!-- 상단 시작 { -->
    <header id="hd" class="min-w-0">
        <h1 id="hd_h1" class="sr-only"><?php echo $g5['title'] ?></h1>
        <div id="skip_to_container">
            <a href="#container">본문 바로가기</a>
        </div>

        <?php
        if (defined('_INDEX_')) { // index에서만 실행
            include G5_BBS_PATH . '/newwin.inc.php'; // 팝업레이어
        }
        ?>

        <div id="hd_wrapper">
            <div id="hd_inner" class="flex flex-col gap-4 p-4">
                <div class="flex items-center justify-between">
                    <a id="hd_logo" href="<?php echo G5_URL ?>" aria-label="메인으로 이동">
                        <img src="<?php echo G5_DATA_URL; ?>/common/logo_img" alt="<?php echo $config['cf_title']; ?>"
                            class="block">
                    </a>

                    <?php echo get_header_actions('community') ?>
                </div>

                <?php
                $searchbar_mode = 'community';
                include_once(G5_THEME_PATH . '/_searchbar.php');
                ?>
            </div>
        </div>
    </header>
    <!-- } 상단 끝 -->

    <!-- 콘텐츠 시작 { -->
    <div id="wrapper" class="min-w-0">
        <div id="container_wr">

            <div id="container">
                <?php if (!defined("_INDEX_")) { ?>
                    <h2 id="container_title"><span
                            title="<?php echo get_text($g5['title']); ?>"><?php echo get_head_title($g5['title']); ?></span>
                    </h2>
                <?php }

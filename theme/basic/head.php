<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH . '/head.php');
    return;
}

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

// 현재 링크(스크립트 경로) 기준으로 헤더 노출 여부 제어
$header_hide_path_suffixes = array(
    '/bbs/login.php',  // 로그인
    '/bbs/register.php',  // 회원가입 약관
    '/bbs/register_form.php',  // 회원가입 폼
    '/bbs/register_result.php', // 회원가입 완료
    '/bbs/board.php', // 게시글
    '/bbs/faq.php', // FAQ
    '/bbs/qalist.php', // QA 리스트
    '/bbs/qawrite.php', // QA 작성
    '/bbs/notification.php', // 알림
    '/bbs/mypage.php', // 커뮤니티 마이페이지
);

$current_script_path = isset($_SERVER['SCRIPT_NAME']) ? (string) $_SERVER['SCRIPT_NAME'] : '';
$is_show_header = true;

foreach ($header_hide_path_suffixes as $suffix) {
    $suffix = (string) $suffix;
    if ($suffix === '') {
        continue;
    }

    $suffix_len = strlen($suffix);
    if ($suffix_len > 0 && substr($current_script_path, -$suffix_len) === $suffix) {
        $is_show_header = false;
        break;
    }
}
?>

<style>
    /* 영역 구분 용 배경색 (임시) */
    body {
        background: white;
    }
</style>

<div id="app-shell">
    <!-- 상단 시작 { -->
    <?php if ($is_show_header) { ?>
        <header id="hd" class="!min-w-0 !bg-white">
            <h1 id="hd_h1"><?php echo $g5['title'] ?></h1>
            <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>

            <?php
            if (defined('_INDEX_')) { // index에서만 실행
                include G5_BBS_PATH . '/newwin.inc.php'; // 팝업레이어
            }
            ?>
            <div id="tnb">
                <div class="inner">
                    <?php if (G5_COMMUNITY_USE) { ?>
                        <ul id="hd_define">
                            <li class="active"><a href="<?php echo G5_URL ?>/">커뮤니티</a></li>
                            <?php if (defined('G5_USE_SHOP') && G5_USE_SHOP) { ?>
                                <li><a href="<?php echo G5_SHOP_URL ?>/">쇼핑몰</a></li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                    <ul id="hd_qnb">
                        <li><a href="<?php echo G5_BBS_URL ?>/faq.php">FAQ</a></li>
                        <li><a href="<?php echo G5_BBS_URL ?>/qalist.php">Q&A</a></li>
                        <li><a href="<?php echo G5_BBS_URL ?>/new.php">새글</a></li>
                        <li><a href="<?php echo G5_BBS_URL ?>/current_connect.php" class="visit">접속자<strong class="visit-num"><?php echo connect('theme/basic'); // 현재 접속자수, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정  
                                                                                                                                ?></strong></a></li>
                    </ul>
                </div>
            </div>
            <div id="hd_wrapper" class="!p-4">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <a href="<?php echo G5_URL ?>" aria-label="메인으로 이동" class="text-lg font-semibold tracking-tight text-gray-900">BUYNATION</a>

                        <div class="flex items-center gap-2 text-2xl">
                            <?= get_notification() ?>
                        </div>
                    </div>

                    <form name="fsearchbox_top" method="get" action="<?php echo G5_BBS_URL ?>/search.php" onsubmit="return fsearchbox_submit(this);" class="relative">
                        <input type="hidden" name="sfl" value="wr_subject||wr_content">
                        <input type="hidden" name="sop" value="and">
                        <label for="sch_stx_top" class="sound_only">검색어 입력</label>
                        <input type="text" name="stx" id="sch_stx_top" maxlength="20" class="h-11 w-full rounded-full border-2 border-[var(--color-primary)] pl-4 pr-11 text-sm text-gray-900 placeholder:text-gray-400 focus:border-gray-300 focus:bg-white focus:outline-none">
                        <button type="submit" class="absolute right-1 top-1 inline-flex h-9 w-9 items-center justify-center rounded-full text-gray-500" aria-label="검색">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </button>

                        <script>
                            function fsearchbox_submit(f) {
                                var stx = f.stx.value.trim();
                                if (stx.length < 2) {
                                    alert("검색어는 두글자 이상 입력하십시오.");
                                    f.stx.select();
                                    f.stx.focus();
                                    return false;
                                }

                                // 검색에 많은 부하가 걸리는 경우 이 주석을 제거하세요.
                                var cnt = 0;
                                for (var i = 0; i < stx.length; i++) {
                                    if (stx.charAt(i) == ' ')
                                        cnt++;
                                }

                                if (cnt > 1) {
                                    alert("빠른 검색을 위하여 검색어에 공백은 한개만 입력할 수 있습니다.");
                                    f.stx.select();
                                    f.stx.focus();
                                    return false;
                                }
                                f.stx.value = stx;

                                return true;
                            }
                        </script>

                        <?php //echo popular('theme/basic'); // 인기검색어, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정 
                        ?>
                    </form>

                </div>

                <ul class="hd_login !hidden">
                    <?php if ($is_member) {  ?>
                        <li><a href="<?php echo G5_BBS_URL ?>/member_confirm.php?url=<?php echo G5_BBS_URL ?>/register_form.php">정보수정</a></li>
                        <li><a href="<?php echo G5_BBS_URL ?>/logout.php">로그아웃</a></li>
                        <?php if ($is_admin) {  ?>
                            <li class="tnb_admin"><a href="<?php echo correct_goto_url(G5_ADMIN_URL); ?>">관리자</a></li>
                        <?php }  ?>
                    <?php } else {  ?>
                        <li><a href="<?php echo G5_BBS_URL ?>/register.php">회원가입</a></li>
                        <li><a href="<?php echo G5_BBS_URL ?>/login.php">로그인</a></li>
                    <?php }  ?>
                </ul>
            </div>

            <nav id="gnb" class="!hidden">
                <?php include G5_THEME_PATH . '/_quick_nav.php'; ?>
            </nav>
        </header>
        <!-- } 상단 끝 -->
    <?php } ?>

    <!-- 콘텐츠 시작 { -->
    <div id="wrapper">
        <div id="container_wr" class="!w-full">

            <div id="container">
                <?php if (!defined("_INDEX_")) { ?><h2 id="container_title"><span title="<?php echo get_text($g5['title']); ?>"><?php echo get_head_title($g5['title']); ?></span></h2><?php }

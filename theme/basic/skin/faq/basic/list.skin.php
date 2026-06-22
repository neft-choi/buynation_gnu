<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . $faq_skin_url . '/style.css">', 0);
?>

<!-- FAQ 시작 { -->
<?php
if ($himg_src)
    echo '<div id="faq_himg" class="faq_img"><img src="' . $himg_src . '" alt=""></div>';

// 상단 HTML
echo '<div id="faq_hhtml">' . conv_content($fm['fm_head_html'], 1) . '</div>';
?>
<?php
// $service_links = array(
//     array('label' => 'FAQ', 'href' => $category_href, 'active' => true),
//     array('label' => '문의내역', 'href' => '#', 'active' => false),
//     array('label' => '문의하기', 'href' => '#', 'active' => false),
//     array('label' => '공지사항', 'href' => '#', 'active' => false),
// );

$selected_get_fm_id = isset($_GET['fm_id']) ? (int) $_GET['fm_id'] : 0;
$is_all_tab = isset($is_all_faq) ? $is_all_faq : !$selected_get_fm_id;

$category_items = array();
$category_items[] = array(
    'label' => '전체',
    'href' => $category_href,
    'is_active' => $is_all_tab
);

foreach ($faq_master_list as $v) {
    $category_items[] = array(
        'label' => $v['fm_subject'],
        'href' => $category_href . '?fm_id=' . $v['fm_id'],
        'is_active' => !$is_all_tab && ((int) $v['fm_id'] === (int) $fm_id)
    );
}
?>

<!-- 모바일 헤더 -->
<div class="flex pc:hidden items-center justify-between p-4">
    <button type="button" class="inline-flex items-center justify-center text-zinc-700" aria-label="뒤로가기" onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '<?php echo G5_URL ?>'; }">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left w-6 h-6">
            <path d="m15 18-6-6 6-6" />
        </svg>
    </button>
    <h1 class="text-lg font-semibold text-zinc-900 leading-0">FAQ</h1>
    <div class="w-6 h-6" aria-hidden="true"></div>
</div>

<!-- 회원 요약 정보 (PC) -->
<?php include_once(G5_THEME_SHOP_PATH . '/_mypage_summary_pc.php'); ?>

<div class="block pc:flex gap-6 pc:px-5 pc:py-12">

    <!-- 마이페이지 메뉴 (PC) -->
    <?php include_once(G5_THEME_SHOP_PATH . '/_mypage_menu_pc.php'); ?>

    <!-- FAQ -->
    <section class="min-w-0 flex-1 bg-white text-zinc-900">

        <!-- PC 너비 타이틀 -->
        <div class="hidden pc:block px-4">
            <h2 class="text-2xl font-bold pb-4 border-b-2 border-gray-900">FAQ</h2>
        </div>

        <div class="mx-auto w-full max-w-full p-4">
            <div class="!hidden p-4">
                <nav aria-label="고객센터 메뉴" class="border-b border-zinc-200">
                    <div class="grid grid-cols-4">
                        <?php foreach ($service_links as $link) { ?>
                            <a href="<?php echo $link['href']; ?>" class="flex h-8 items-center justify-center border-b-2 text-base <?php echo $link['active'] ? 'border-zinc-900 font-semibold text-zinc-900' : 'border-transparent text-zinc-400'; ?>">
                                <?php echo $link['label']; ?>
                            </a>
                        <?php } ?>
                    </div>
                </nav>
            </div>

            <div>
                <fieldset class="m-0 p-0">
                    <legend class="sound_only">FAQ 검색</legend>
                    <form name="faq_search_form" method="get">
                        <span class="sound_only">FAQ 검색</span>
                        <?php if (!$is_all_tab) { ?>
                            <input type="hidden" name="fm_id" value="<?php echo $fm_id; ?>">
                        <?php } ?>
                        <label for="stx" class="sound_only">검색어 <span class="sound_only">필수</span></label>
                        <div class="relative">
                            <input type="text" name="stx" value="<?php echo $stx; ?>" required id="stx" class="h-12 w-full rounded border border-zinc-300 px-4 pr-14 text-base text-zinc-900 placeholder-zinc-400" size="15" maxlength="15" placeholder="궁금하신 내용을 검색해보세요.">
                            <button type="submit" value="검색" class="absolute right-4 top-3.5 text-zinc-700" aria-label="검색">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.3-4.3"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </fieldset>
            </div>

            <?php if (count($faq_master_list)) { ?>
                <nav class="pt-4" aria-label="자주하시는질문 분류">
                    <h2 class="sound_only">자주하시는질문 분류</h2>
                    <div class="overflow-hidden rounded border border-zinc-300 bg-white">
                        <div class="grid grid-cols-3">
                            <?php
                            $item_count = count($category_items);
                            foreach ($category_items as $index => $item) {
                                $is_last_col = (($index + 1) % 3 === 0);
                                $is_last_row = ($index >= $item_count - ($item_count % 3 ?: 3));
                                $border_class = 'border-zinc-200';
                                $right_border_class = $is_last_col ? '' : ' border-r';
                                $bottom_border_class = $is_last_row ? '' : ' border-b';
                                $active_class = $item['is_active'] ? ' bg-zinc-700 font-semibold text-white' : ' bg-white text-zinc-800';
                            ?>
                                <a href="<?php echo $item['href']; ?>" class="flex h-12 items-center justify-center p-2 text-center text-sm <?php echo $border_class . $right_border_class . $bottom_border_class . $active_class; ?>">
                                    <?php echo $item['label']; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </nav>
            <?php } ?>

            <div class="mt-6 h-3 bg-zinc-100 pc:bg-white"></div>

            <div class="faq_<?php echo $fm_id; ?>">
                <?php // FAQ 내용
                if (count($faq_list)) {
                ?>
                    <section id="faq_con" class="bg-white">
                        <h2 class="sound_only"><?php echo $g5['title']; ?> 목록</h2>
                        <ol class="m-0 list-none p-0">
                            <?php
                            foreach ($faq_list as $key => $v) {
                                if (empty($v))
                                    continue;
                                $faq_category = isset($v['master_subject']) && $v['master_subject'] ? $v['master_subject'] : $fm['fm_subject'];
                            ?>
                                <li class="border-b border-zinc-200 bg-white">
                                    <h3 class="flex items-start justify-between gap-3 px-4 py-5">
                                        <a href="#none" onclick="return faq_open(this);" class="block pc:flex flex-1 items-center">
                                            <span class="block text-base font-semibold text-zinc-900 pc:w-50">[<?php echo $faq_category; ?>]</span>
                                            <span class="mt-2 pc:mt-0 block text-sm pc:text-base text-zinc-900"><?php echo conv_content($v['fa_subject'], 1); ?></span>
                                        </a>

                                        <button type="button" class="tit_btn mt-1 text-zinc-500" onclick="return faq_open(this);" aria-label="열기">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="m6 9 6 6 6-6"></path>
                                            </svg>
                                            <span class="sound_only">열기</span>
                                        </button>
                                    </h3>

                                    <div class="con_inner px-4 pb-4 pc:pb-6">
                                        <div class="bg-zinc-100 text-[15px] pc:text-base text-zinc-700 px-6 py-7 pc:px-10 pc:py-10">
                                            <?php echo conv_content($v['fa_content'], 1); ?>

                                            <div class="flex flex-col items-center justify-center pt-10">
                                                <span class="text-[15px] text-gray-400">찾으시는 내용이 없나요?</span>
                                                <a href="<?php echo G5_BBS_URL; ?>/qawrite.php" class="text-base text-gray-900 underline">1:1 문의 바로가기</a>
                                            </div>
                                        </div>
                                        <button type="button" class="closer_btn sound_only"><span class="sound_only">닫기</span></button>
                                    </div>
                                </li>
                            <?php
                            }
                            ?>
                        </ol>
                    </section>
                    <?php

                } else {
                    if ($stx) {
                        echo '<p class="empty_list px-4 py-8 text-center text-sm text-zinc-500">검색된 게시물이 없습니다.</p>';
                    } else {
                    ?>
                        <div class="flex flex-col items-center justify-center gap-3 text-gray-400 min-h-[calc(100dvh-var(--mobile-header-height)-var(--bottom-nav-height))] pc:min-h-0 pc:h-100">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search w-12 h-12">
                                <path d="m21 21-4.34-4.34" />
                                <circle cx="11" cy="11" r="8" />
                            </svg>
                            <span>찾으시는 내용이 없나요?</span>
                            <a href="<?php echo G5_BBS_URL; ?>/qawrite.php" class="text-sm font-semibold px-4 py-2 mt-3 bg-gray-800 text-white rounded">1:1 문의 바로가기</a>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>

            <div class="sound_only">
                <?php echo get_paging($page_rows, $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page='); ?>
            </div>

            <?php
            // 하단 HTML
            echo '<div id="faq_thtml">' . conv_content($fm['fm_tail_html'], 1) . '</div>';

            if ($timg_src)
                echo '<div id="faq_timg" class="faq_img"><img src="' . $timg_src . '" alt=""></div>';
            ?>

        </div>
        <!-- } FAQ 끝 -->
        <?php
        if ($admin_href)
            echo '<div class="faq_admin mx-auto w-full max-w-full px-4 pb-4 text-right"><a href="' . $admin_href . '" class="inline-flex h-10 w-10 items-center justify-center rounded border border-zinc-300 text-zinc-700" title="FAQ 수정"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 15.5A3.5 3.5 0 1 0 12 8.5a3.5 3.5 0 0 0 0 7Z"></path><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3h.1a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5h.1a1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8v.1a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1Z"></path></svg><span class="sound_only">FAQ 수정</span></a></div>';
        ?>
    </section>
</div>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>
<script>
    jQuery(function() {
        $(".closer_btn").on("click", function() {
            $(this).closest(".con_inner").stop(true, true).slideToggle(200, function() {
                var $h3 = $(this).closest("li").find("h3");

                $("#faq_con li h3").removeClass("faq_li_open");
                if ($(this).is(":visible")) {
                    $h3.addClass("faq_li_open");
                }
            });
        });
    });

    function faq_open(el) {
        var $li = $(el).closest("li"),
            $con = $li.children(".con_inner"),
            $h3 = $li.children("h3"),
            $opened = $("#faq_con .con_inner:visible").not($con);

        if ($con.is(":visible")) {
            $con.stop(true, true).slideUp(200);
            $h3.removeClass("faq_li_open");
        } else {
            $opened.stop(true, true).slideUp(200);
            $("#faq_con li h3").not($h3).removeClass("faq_li_open");

            $con.stop(true, true).slideDown(200, function() {
                // 이미지 리사이즈
                $con.viewimageresize2();
                $h3.addClass("faq_li_open");
            });
        }

        return false;
    }

    // 반응형 쇼핑몰 헤더 숨기기
    syncWithPcBreakpoint(function(isPc) {
        if (isPc) {
            $('#hd').css('display', '');
        } else {
            $('#hd').css('display', 'none');
        }
    });
</script>
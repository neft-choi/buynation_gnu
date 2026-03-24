<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 6;

if ($is_checkbox) $colspan++;

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . $qa_skin_url . '/style.css">', 0);
?>

<?php
$qa_category_items = array();
$qa_category_items[] = array(
    'label' => '전체',
    'href' => $list_href,
    'is_active' => ($sca === '')
);

if (!empty($qaconfig['qa_category'])) {
    $categories = explode('|', $qaconfig['qa_category']);
    for ($ci = 0; $ci < count($categories); $ci++) {
        $category = trim($categories[$ci]);
        if ($category === '') {
            continue;
        }

        $qa_category_items[] = array(
            'label' => $category,
            'href' => $list_href . '?sca=' . urlencode($category),
            'is_active' => ($category === $sca)
        );
    }
}
?>

<div id="bo_list" class="w-full max-w-full mx-auto bg-white text-zinc-900">
    <div class="p-4">
        <div class="flex items-center justify-between">
            <button type="button" onclick="history.back();" class="inline-flex items-center justify-center h-8 w-8 text-zinc-700" aria-label="뒤로가기">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="m15 18-6-6 6-6"></path>
                </svg>
            </button>
            <div class="text-lg font-semibold text-zinc-900"><?php echo $qaconfig['qa_title']; ?></div>
            <div class="h-8 w-8" aria-hidden="true"></div>
        </div>
    </div>

    <div class="px-4 pt-4">
        <fieldset class="m-0 p-0">
            <legend class="sound_only">문의 검색</legend>
            <form name="fsearch" method="get">
                <span class="sound_only">문의 검색</span>
                <?php if ($sca !== '') { ?>
                    <input type="hidden" name="sca" value="<?php echo $sca; ?>">
                <?php } ?>
                <input type="hidden" name="sop" value="and">
                <input type="hidden" name="sfl" value="<?php echo $sfl ? $sfl : 'qa_subject'; ?>">
                <label for="stx" class="sound_only">검색어 <span class="sound_only">필수</span></label>
                <div class="relative">
                    <input type="text" name="stx" value="<?php echo stripslashes($stx); ?>" required id="stx" class="h-12 w-full px-4 pr-14 rounded border border-zinc-300 text-base text-zinc-900 placeholder-zinc-400" size="15" maxlength="15" placeholder="궁금하신 내용을 검색해보세요.">
                    <button type="submit" value="검색" class="absolute top-3.5 right-4 text-zinc-700" aria-label="검색">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </fieldset>
    </div>

    <?php if (count($qa_category_items)) { ?>
        <nav class="px-4 pt-4" aria-label="문의 분류">
            <h2 class="sound_only">문의 분류</h2>
            <div class="overflow-hidden rounded border border-zinc-300 bg-white">
                <div class="grid grid-cols-3">
                    <?php
                    $item_count = count($qa_category_items);
                    foreach ($qa_category_items as $index => $item) {
                        $is_last_col = (($index + 1) % 3 === 0);
                        $is_last_row = ($index >= $item_count - ($item_count % 3 ?: 3));
                        $right_border_class = $is_last_col ? '' : ' border-r';
                        $bottom_border_class = $is_last_row ? '' : ' border-b';
                        $active_class = $item['is_active'] ? ' bg-zinc-700 font-semibold text-white' : ' bg-white text-zinc-800';
                    ?>
                        <a href="<?php echo $item['href']; ?>" class="flex items-center justify-center h-12 p-2 border-zinc-200 text-center text-sm <?php echo $right_border_class . $bottom_border_class . $active_class; ?>">
                            <?php echo $item['label']; ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </nav>
    <?php } ?>

    <div class="px-4 pt-4 pb-3 text-sm text-zinc-600">
        <span>Total <?php echo number_format($total_count); ?>건</span>
        <span><?php echo $page; ?> 페이지</span>
    </div>

    <section class="p-4 space-y-4">
        <div class="flex items-center justify-between gap-2">
            <h2 class="text-lg font-bold">상품 문의</h2>
            <?php if ($write_href) { ?>
                <a href="<?php echo $write_href; ?>" title="문의하기" class="px-4 py-2 rounded bg-gray-900 text-sm text-white">문의하기</a>
            <?php } ?>
        </div>

        <form name="fqalist" id="fqalist" action="./qadelete.php" onsubmit="return fqalist_submit(this);" method="post">
            <input type="hidden" name="stx" value="<?php echo $stx; ?>">
            <input type="hidden" name="sca" value="<?php echo $sca; ?>">
            <input type="hidden" name="page" value="<?php echo $page; ?>">
            <input type="hidden" name="token" value="<?php echo get_text($token); ?>">

            <div>
                <?php if ($is_checkbox) { ?>
                    <div class="all_chk flex items-center gap-2 px-2 py-4 text-xs text-slate-600">
                        <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);" class="h-4 w-4 rounded border-slate-300 text-slate-700 focus:ring-slate-400">
                        <label for="chkall" class="cursor-pointer">전체선택</label>
                    </div>
                <?php } ?>

                <div class="border-b border-slate-200 bg-white">
                    <?php
                    for ($i = 0; $i < count($list); $i++) {
                        if ($i % 2 == 0) $lt_class = "even";
                        else $lt_class = "";
                    ?>
                        <div class="<?php echo $lt_class ?> py-4 border-b border-slate-100 last:border-b-0">
                            <?php if ($is_checkbox) { ?>
                                <div class="td_chk hidden">
                                    <input type="checkbox" name="chk_qa_id[]" value="<?php echo $list[$i]['qa_id'] ?>" id="chk_qa_id_<?php echo $i ?>" class="h-4 w-4 rounded border-slate-300 text-slate-700 focus:ring-slate-400">
                                    <label for="chk_qa_id_<?php echo $i ?>" class="sound_only"><?php echo $list[$i]['subject'] ?></label>
                                </div>
                            <?php } ?>
                            <span class="td_num hidden text-xs text-slate-500"><?php echo $list[$i]['num']; ?></span>

                            <div class="grid gap-2 text-sm text-slate-700">
                                <div class="td_subject">
                                    <span class="bo_cate_link mr-2 text-xs text-slate-500 !hidden"><?php echo $list[$i]['category']; ?></span>
                                    <a href="<?php echo $list[$i]['view_href']; ?>" class="bo_tit inline-flex items-center gap-2 text-sm text-slate-900 !font-normal !break-all">
                                        <span><?php echo $list[$i]['subject']; ?></span>

                                        <?php if ($list[$i]['icon_file']) { ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                <path d="m7 10 5 5 5-5" />
                                                <path d="M12 15V3" />
                                            </svg>
                                        <?php } ?>
                                    </a>
                                </div>

                                <div class="flex items-center gap-4 text-sm text-slate-500">
                                    <span class="td_stat !w-fit text-xs <?php echo ($list[$i]['qa_status'] ? 'text-blue-400' : 'text-amber-600'); ?>"><?php echo ($list[$i]['qa_status'] ? '답변완료' : '답변대기'); ?></span>
                                    <div class="td_name_custom min-w-0 truncate text-left"><?php echo $list[$i]['name']; ?></div>
                                    <div class="td_date_custom !w-fit text-right"><?php echo date('Y.m.d', strtotime($list[$i]['qa_datetime'])); ?></div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>

                    <?php if ($i == 0) {
                        echo '<div class="empty_table px-3 py-6 text-center text-sm text-slate-500">게시물이 없습니다.</div>';
                    } ?>
                </div>
            </div>
            <!-- 페이지 -->
            <?php echo $list_pages; ?>
            <!-- 페이지 -->

            <div class="bo_fx !my-2 !hidden">
                <ul class="btn_bo_user">
                    <?php if ($is_checkbox) { ?>
                        <li><button type="submit" name="btn_submit" value="선택삭제" title="선택삭제" onclick="document.pressed=this.value" class="btn btn_b01 btn_admin"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M3 6h18" />
                                    <path d="M8 6V4h8v2" />
                                    <path d="M19 6l-1 14H6L5 6" />
                                    <path d="M10 11v6" />
                                    <path d="M14 11v6" />
                                </svg><span class="sound_only">선택삭제</span></button></li>
                    <?php } ?>
                    <?php if ($list_href) { ?><li><a href="<?php echo $list_href ?>" class="btn_b01 btn" title="목록"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M8 6h13" />
                                    <path d="M8 12h13" />
                                    <path d="M8 18h13" />
                                    <path d="M3 6h.01" />
                                    <path d="M3 12h.01" />
                                    <path d="M3 18h.01" />
                                </svg><span class="sound_only">목록</span></a></li><?php } ?>
                    <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b01 btn" title="문의등록"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4Z" />
                                </svg><span class="sound_only">문의등록</span></a></li><?php } ?>
                </ul>
            </div>
        </form>
    </section>
</div>

<?php if ($is_checkbox) { ?>
    <noscript>
        <p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
    </noscript>
<?php } ?>

<?php if ($is_checkbox) { ?>
    <script>
        function all_checked(sw) {
            var f = document.fqalist;

            for (var i = 0; i < f.length; i++) {
                if (f.elements[i].name == "chk_qa_id[]")
                    f.elements[i].checked = sw;
            }
        }

        function fqalist_submit(f) {
            var chk_count = 0;

            for (var i = 0; i < f.length; i++) {
                if (f.elements[i].name == "chk_qa_id[]" && f.elements[i].checked)
                    chk_count++;
            }

            if (!chk_count) {
                alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
                return false;
            }

            if (document.pressed == "선택삭제") {
                if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다"))
                    return false;
            }

            return true;
        }
    </script>
<?php } ?>
<!-- } 게시판 목록 끝 -->

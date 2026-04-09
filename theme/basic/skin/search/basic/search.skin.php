<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . $search_skin_url . '/style.css">', 0);
?>

<!-- 전체검색 시작 { -->
<form name="fsearch" onsubmit="return fsearch_submit(this);" method="get" class="!hidden">
    <input type="hidden" name="srows" value="<?php echo $srows ?>">
    <fieldset id="sch_res_detail">
        <legend>상세검색</legend>
        <?php echo $group_select ?>
        <script>
            document.getElementById("gr_id").value = "<?php echo $gr_id ?>";
        </script>

        <label for="sfl" class="sound_only">검색조건</label>
        <select name="sfl" id="sfl">
            <option value="wr_subject||wr_content" <?php echo get_selected($sfl, "wr_subject||wr_content") ?>>제목+내용</option>
            <option value="wr_subject" <?php echo get_selected($sfl, "wr_subject") ?>>제목</option>
            <option value="wr_content" <?php echo get_selected($sfl, "wr_content") ?>>내용</option>
            <option value="mb_id" <?php echo get_selected($sfl, "mb_id") ?>>회원아이디</option>
            <option value="wr_name" <?php echo get_selected($sfl, "wr_name") ?>>이름</option>
        </select>

        <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
        <span class="sch_wr">
            <input type="text" name="stx" value="<?php echo $text_stx ?>" id="stx" required class="frm_input" size="40">
            <button type="submit" class="btn_submit"><i class="fa fa-search" aria-hidden="true"></i> 검색</button>
        </span>

        <script>
            function fsearch_submit(f) {
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

                f.action = "";
                return true;
            }
        </script>

        <div class="switch_field">
            <input type="radio" value="and" <?php echo ($sop == "and") ? "checked" : ""; ?> id="sop_and" name="sop">
            <label for="sop_and">AND</label>
            <input type="radio" value="or" <?php echo ($sop == "or") ? "checked" : ""; ?> id="sop_or" name="sop">
            <label for="sop_or">OR</label>
        </div>
    </fieldset>
</form>

<div id="sch_result">
    <?php
    if ($stx) {
        if ($board_count) {
    ?>
            <section id="sch_res_ov_custom" class="!mx-4">
                <h2 class="text-sm font-bold"><span class="text-red-500"><?php echo $stx ?></span> 전체검색 결과</h2>
                <ul class="!hidden">
                    <li>게시판 <?php echo $board_count ?>개</li>
                    <li>게시물 <?php echo number_format($total_count) ?>개</li>
                    <li><?php echo number_format($page) ?>/<?php echo number_format($total_page) ?> 페이지 열람 중</li>
                </ul>
            </section>
    <?php
        }
    }
    ?>

    <?php
    if ($stx) {
        if ($board_count) {
    ?>
            <ul id="sch_res_board" class="!m-4 text-sm font-medium">
                <li><a href="?<?php echo $search_query ?>&amp;gr_id=<?php echo $gr_id ?>" <?php echo $sch_all ?>>전체</a></li>
                <?php echo $str_board_list; ?>
            </ul>
        <?php
        } else {
        ?>
            <div class="empty_list">검색된 자료가 하나도 없습니다.</div>
    <?php }
    }  ?>

    <?php if ($stx && $board_count) { ?><section class="sch_res_list"><?php }  ?>
        <?php
        $k = 0;
        for ($idx = $table_index, $k = 0; $idx < count($search_table) && $k < $rows; $idx++) {
        ?>
            <div class="search_board_result !text-sm border-t-1 m-4">
                <div class="flex items-center justify-between gap-2">
                    <h2 class="!py-4 !font-bold">
                        <a href="<?php echo get_pretty_url($search_table[$idx], '', $search_query); ?>">
                            <?php echo $bo_subject[$idx] ?>
                            <span class="cnt_cmt">(<?php echo number_format(isset($board_result_count[$search_table[$idx]]) ? $board_result_count[$search_table[$idx]] : 0); ?>)</span>
                        </a>
                    </h2>
                    <a href="<?php echo get_pretty_url($search_table[$idx], '', $search_query); ?>" class="sch_more text-xs text-zinc-500 font-medium">더보기</a>
                </div>
                <ul class="space-y-4">
                    <?php
                    for ($i = 0; $i < count($list[$idx]) && $k < $rows; $i++, $k++) {
                        if ($list[$idx][$i]['wr_is_comment']) {
                            $comment_def = '<span class="cmt_def"><span class="sound_only">댓글</span></span> ';
                            $comment_href = '#c_' . $list[$idx][$i]['wr_id'];
                        } else {
                            $comment_def = '';
                            $comment_href = '';
                        }
                    ?>

                        <li class="space-y-2">
                            <div class="sch_tit !flex items-center justify-between gap-2 !p-4 border border-zinc-300 rounded">
                                <a href="<?php echo $list[$idx][$i]['href'] ?><?php echo $comment_href ?>" class="sch_res_title !text-sm"><?php echo $comment_def ?><?php echo $list[$idx][$i]['subject'] ?></a>
                                <div class="sch_info text-xs text-zinc-500 font-medium"><?php echo $list[$idx][$i]['wr_datetime_display'] ?></div>
                            </div>

                            <?php if (!empty($list[$idx][$i]['has_latest_comment'])) { ?>
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-corner-down-right-icon lucide-corner-down-right w-6 h-6 m-2">
                                        <path d="m15 10 5 5-5 5" />
                                        <path d="M4 4v7a4 4 0 0 0 4 4h12" />
                                    </svg>
                                    <div class="sch_comment flex flex-1 items-center justify-between p-4 border border-zinc-300 rounded">
                                        <div class="flex items-center gap-2">
                                            <span class="h-fit flex items-center justify-center text-xs px-1 rounded bg-[#222] text-white font-medium"><?php echo $list[$idx][$i]['wr_name'] ?></span>
                                            <p><?php echo $list[$idx][$i]['content'] ?></p>
                                        </div>
                                        <span class="sch_datetime text-xs text-zinc-500 font-medium"><?php echo $list[$idx][$i]['latest_comment_datetime_display'] ?></span>
                                    </div>
                                </div>
                            <?php } ?>
                        </li>
                    <?php }  ?>
                </ul>
            </div>
        <?php }        //end for
        ?>
        <?php if ($stx && $board_count) {  ?>
        </section><?php }  ?>

    <?php echo $write_pages ?>

</div>
<!-- } 전체검색 끝 -->

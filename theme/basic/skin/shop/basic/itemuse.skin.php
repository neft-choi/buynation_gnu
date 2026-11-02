<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_SHOP_SKIN_URL . '/style.css">', 0);
?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>
<style>
    /* 이 스킨의 리뷰 본문만 항상 펼침 처리 */
    #sit_use_list .sit_use_con {
        display: block;
    }
</style>

<!-- 상품 사용후기 시작 { -->
<section id="sit_use_list" class="space-y-4 mt-4">
    <h3 class="text-lg font-bold text-gray-900">상품 리뷰</h3>

    <?php
    // $it는 현재 상품 정보 배열, it_use_avg는 상품에 저장된 리뷰 평균 평점
    $review_avg = ($review_total_count > 0 && isset($it['it_use_avg']))
        ? number_format((float) $it['it_use_avg'], 1)
        : '0.0';
    $review_star_fill = max(0, min(5, (int) round((float) $review_avg)));
    ?>

    <div class="flex items-center justify-between h-30 border border-gray-300 rounded-lg p-6">
        <div class="" aria-label="평점 <?php echo $review_avg; ?>점">
            <span class="text-2xl font-bold leading-none text-gray-900"><?php echo $review_avg; ?><span class="text-base text-gray-500 font-medium"> / 5</span></span>
            <div class="mt-2 flex items-center">
                <?php for ($s = 1; $s <= 5; $s++) { ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        class="h-5 w-5 <?php echo ($s <= $review_star_fill) ? 'text-amber-300' : 'text-gray-300'; ?>"
                        fill="<?php echo ($s <= $review_star_fill) ? 'currentColor' : 'none'; ?>"
                        stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                    </svg>
                <?php } ?>
            </div>
        </div>
        <p class="text-sm text-gray-500 font-medium">구매자가 남긴 리뷰 <?php echo number_format($review_total_count); ?>개</p>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div id="review_filter" class="flex items-center gap-2" role="group" aria-label="리뷰 필터">
            <button type="button" data-review-filter="all" aria-pressed="<?php echo $review_filter === 'all' ? 'true' : 'false'; ?>"
                class="cursor-pointer rounded-full border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-500 aria-pressed:border-gray-800 aria-pressed:bg-gray-800 aria-pressed:text-white">
                전체 리뷰
            </button>
            <button type="button" data-review-filter="photo" aria-pressed="<?php echo $review_filter === 'photo' ? 'true' : 'false'; ?>"
                class="cursor-pointer rounded-full border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-500 aria-pressed:border-gray-800 aria-pressed:bg-gray-800 aria-pressed:text-white">
                사진 리뷰만
            </button>
        </div>

        <div class="ml-auto shrink-0">
            <label for="review_sort" class="sr-only">리뷰 정렬 기준</label>
            <select id="review_sort" name="review_sort"
                class="h-9 cursor-pointer rounded border border-gray-300 bg-white px-2 text-sm text-gray-900">
                <option value="latest" <?php echo $review_sort === 'latest' ? 'selected' : ''; ?>>최신순</option>
                <option value="score_high" <?php echo $review_sort === 'score_high' ? 'selected' : ''; ?>>평점 높은 순</option>
                <option value="score_low" <?php echo $review_sort === 'score_low' ? 'selected' : ''; ?>>평점 낮은 순</option>
            </select>
        </div>
    </div>

    <?php
    $thumbnail_width = 500;

    for ($i = 0; $row = sql_fetch_array($result); $i++) {
        $is_num     = $total_count - ($page - 1) * $rows - $i;
        $is_star    = get_star($row['is_score']);
        $is_name    = get_text($row['is_name']);
        $is_subject = conv_subject($row['is_subject'], 50, "…");
        $is_content = get_view_thumbnail(conv_content($row['is_content'], 1), $thumbnail_width);
        $is_reply_name = !empty($row['is_reply_name']) ? get_text($row['is_reply_name']) : '';
        $is_reply_subject = !empty($row['is_reply_subject']) ? conv_subject($row['is_reply_subject'], 50, "…") : '';
        $is_reply_content = !empty($row['is_reply_content']) ? get_view_thumbnail(conv_content($row['is_reply_content'], 1), $thumbnail_width) : '';
        $is_time    = substr($row['is_time'], 2, 8);
        $is_time_dot = date('Y.m.d', strtotime($row['is_time']));

        $hash = md5($row['is_id'] . $row['is_time'] . $row['is_ip']);

        if ($i == 0) echo '<ol id="sit_use_ol">';
    ?>

        <li class="sit_use_li relative space-y-4 min-h-30 border-b border-gray-300 py-4">
            <div class="flex items-center justify-between gap-4">
                <p class="text-sm text-gray-900 font-bold"><?php echo $is_name; ?></p>

                <div class="flex items-center gap-1" aria-label="별점 <?php echo (int) $is_star; ?>점">
                    <?php for ($rs = 1; $rs <= 5; $rs++) { ?>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            class="h-4 w-4 <?php echo ($rs <= (int) $is_star) ? 'text-orange-500' : 'text-gray-300'; ?>"
                            fill="<?php echo ($rs <= (int) $is_star) ? 'currentColor' : 'none'; ?>"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                        </svg>
                    <?php } ?>
                </div>
            </div>

            <div id="sit_use_con_<?php echo $i; ?>" class="sit_use_con">
                <p class="text-base font-medium text-gray-900"><?php echo $is_subject; ?></p>

                <div class="sit_use_p text-sm">
                    <?php echo $is_content; // 사용후기 내용 
                    ?>
                </div>

                <?php if ($is_admin || $row['mb_id'] == $member['mb_id']) { ?>
                    <div class="sit_use_cmd flex items-center gap-1">
                        <a href="<?php echo $itemuse_form . "&amp;is_id={$row['is_id']}&amp;w=u"; ?>" class="itemuse_form px-2 py-1 border border-gray-400 text-gray-500 rounded" onclick="return false;">수정</a>
                        <a href="<?php echo $itemuse_formupdate . "&amp;is_id={$row['is_id']}&amp;w=d&amp;hash={$hash}"; ?>" class="itemuse_delete px-2 py-1 border border-gray-400 text-gray-500 rounded">삭제</a>
                    </div>
                <?php } ?>

                <?php if ($is_reply_subject) {  //  사용후기 답변 내용이 있다면 
                ?>
                    <div class="sit_use_reply">
                        <div class="use_reply_icon">답변</div>
                        <div class="use_reply_tit">
                            <?php echo $is_reply_subject; // 답변 제목 
                            ?>
                        </div>
                        <div class="use_reply_name">
                            <?php echo $is_reply_name; // 답변자 이름 
                            ?>
                        </div>
                        <div class="use_reply_p">
                            <?php echo $is_reply_content; // 답변 내용 
                            ?>
                        </div>
                    </div>
                <?php } //end if 
                ?>
            </div>
        </li>

    <?php }

    if ($i > 0) echo '</ol>';

    if (!$i) echo '<p class="text-center py-50">등록된 리뷰가 없습니다.</p>';
    ?>
</section>

<?php
echo itemuse_page($config['cf_write_pages'], $page, $total_page, G5_SHOP_URL . "/itemuse.php?it_id=$it_id&amp;review_filter=$review_filter&amp;review_sort=$review_sort&amp;page=", "");
?>

<script>
    $(function() {
        const $reviewFilterButtons = $("#review_filter button[data-review-filter]");

        $reviewFilterButtons.on("click", function() {
            const reviewFilter = $(this).attr("data-review-filter");
            const $reviewSort = $("#review_sort");
            const requestData = $.param({
                it_id: <?php echo json_encode($it_id); ?>,
                review_filter: reviewFilter,
                review_sort: $reviewSort.val(),
                page: 1
            });
            const requestUrl = <?php echo json_encode(G5_SHOP_URL . '/itemuse.php'); ?> + "?" + requestData;

            $reviewFilterButtons.prop("disabled", true);
            $reviewSort.prop("disabled", true);

            $("#itemuse").load(requestUrl, function(responseText, status) {
                if (status === "error") {
                    $reviewFilterButtons.prop("disabled", false);
                    $reviewSort.prop("disabled", false);
                    alert("리뷰를 불러오지 못했습니다. 다시 시도해 주세요.");
                }
            });
        });

        $("#review_sort").on("change", function() {
            const $reviewSort = $(this);
            const requestData = $.param({
                it_id: <?php echo json_encode($it_id); ?>,
                review_filter: <?php echo json_encode($review_filter); ?>,
                review_sort: $reviewSort.val(),
                page: 1
            });
            const requestUrl = <?php echo json_encode(G5_SHOP_URL . '/itemuse.php'); ?> + "?" + requestData;

            $reviewFilterButtons.prop("disabled", true);
            $reviewSort.prop("disabled", true);

            $("#itemuse").load(requestUrl, function(responseText, status) {
                if (status === "error") {
                    $reviewFilterButtons.prop("disabled", false);
                    $reviewSort.prop("disabled", false).val(<?php echo json_encode($review_sort); ?>);
                    alert("리뷰를 불러오지 못했습니다. 다시 시도해 주세요.");
                }
            });
        });

        $(".itemuse_form").click(function() {
            window.open(this.href, "itemuse_form", "width=810,height=680,scrollbars=1");
            return false;
        });

        $(".itemuse_delete").click(function() {
            if (confirm("정말 삭제 하시겠습니까?\n\n삭제후에는 되돌릴수 없습니다.")) {
                return true;
            } else {
                return false;
            }
        });

        // '내용보기' 버튼 클릭 시 리뷰 본문 아코디언 기능
        // $(".sit_use_li_title").click(function(){
        //     var $con = $(this).siblings(".sit_use_con");
        //     if($con.is(":visible")) {
        //         $con.slideUp();
        //     } else {
        //         $(".sit_use_con:visible").hide();
        //         $con.slideDown(
        //             function() {
        //                 // 이미지 리사이즈
        //                 $con.viewimageresize2();
        //             }
        //         );
        //     }
        // });

        // 이미지 리사이즈 기능만 살리기
        $(".sit_use_con").each(function() {
            $(this).viewimageresize2();
        });

        $(".pg_page").click(function() {
            $("#itemuse").load($(this).attr("href"));
            return false;
        });
    });
</script>
<!-- } 상품 사용후기 끝 -->
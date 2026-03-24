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
<section id="sit_use_list" class="space-y-4">
    <h3>리뷰</h3>
    <?php
    $review_avg = isset($it['it_use_avg']) ? number_format((float) $it['it_use_avg'], 1) : '0.0';
    $review_star_fill = max(0, min(5, (int) round((float) $review_avg)));
    ?>
    <div class="pb-2">
        <div class="py-4">
            <div class="flex items-center justify-center gap-2" aria-label="평점 <?php echo $review_avg; ?>점">
                <?php for ($s = 1; $s <= 5; $s++) { ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        class="h-6 w-6 <?php echo ($s <= $review_star_fill) ? 'text-orange-500' : 'text-gray-300'; ?>"
                        fill="<?php echo ($s <= $review_star_fill) ? 'currentColor' : 'none'; ?>"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"></path>
                    </svg>
                <?php } ?>
                <span class="ml-2 text-[34px] font-bold leading-none text-gray-900"><?php echo $review_avg; ?></span>
            </div>
            <p class="mt-2 text-center text-sm text-gray-500">총 <?php echo number_format($total_count); ?>개의 리뷰</p>
        </div>

        <div class="mt-4 w-full space-y-8">
            <div class="grid grid-cols-[56px_1fr_110px_40px] items-center gap-2">
                <div class="row-span-3 self-start pt-0.5">
                    <span class="inline-flex rounded-md bg-zinc-200 px-3 py-1 text-sm text-zinc-900">기능</span>
                </div>
                <p class="text-sm font-bold text-zinc-700">아주 마음에 들어요</p>
                <div class="h-1.5 rounded-full bg-zinc-200" aria-label="아주 마음에 들어요 72%">
                    <div class="h-1.5 rounded-full bg-orange-500" style="width:72%"></div>
                </div>
                <p class="text-right text-sm text-zinc-700">72%</p>

                <p class="text-sm text-zinc-700">마음에 들어요</p>
                <div class="h-1.5 rounded-full bg-zinc-200" aria-label="마음에 들어요 31%">
                    <div class="h-1.5 rounded-full bg-zinc-400" style="width:31%"></div>
                </div>
                <p class="text-right text-sm text-zinc-700">31%</p>

                <p class="text-sm text-zinc-700">마음에 들지 않아요</p>
                <div class="h-1.5 rounded-full bg-zinc-200" aria-label="마음에 들지 않아요 6%">
                    <div class="h-1.5 rounded-full bg-zinc-400" style="width:6%"></div>
                </div>
                <p class="text-right text-sm text-zinc-700">6%</p>
            </div>

            <div class="grid grid-cols-[56px_1fr_110px_40px] items-center gap-2">
                <div class="row-span-3 self-start pt-0.5">
                    <span class="inline-flex rounded-md bg-zinc-200 px-2 py-1 text-sm text-zinc-900">디자인</span>
                </div>
                <p class="text-sm font-bold text-zinc-700">아주 마음에 들어요</p>
                <div class="h-1.5 rounded-full bg-zinc-200" aria-label="아주 마음에 들어요 72%">
                    <div class="h-1.5 rounded-full bg-orange-500" style="width:72%"></div>
                </div>
                <p class="text-right text-sm text-zinc-700">72%</p>

                <p class="text-sm text-zinc-700">마음에 들어요</p>
                <div class="h-1.5 rounded-full bg-zinc-200" aria-label="마음에 들어요 31%">
                    <div class="h-1.5 rounded-full bg-zinc-400" style="width:31%"></div>
                </div>
                <p class="text-right text-sm text-zinc-700">31%</p>

                <p class="text-sm text-zinc-700">마음에 들지 않아요</p>
                <div class="h-1.5 rounded-full bg-zinc-200" aria-label="마음에 들지 않아요 6%">
                    <div class="h-1.5 rounded-full bg-zinc-400" style="width:6%"></div>
                </div>
                <p class="text-right text-sm text-zinc-700">6%</p>
            </div>

            <div class="grid grid-cols-[56px_1fr_110px_40px] items-center gap-2">
                <div class="row-span-3 self-start pt-0.5">
                    <span class="inline-flex rounded-md bg-zinc-200 px-3 py-1 text-sm text-zinc-900">색상</span>
                </div>
                <p class="text-sm font-semibold text-zinc-700">화면과 같아요</p>
                <div class="h-1.5 rounded-full bg-zinc-200" aria-label="화면과 같아요 72%">
                    <div class="h-1.5 rounded-full bg-orange-500" style="width:72%"></div>
                </div>
                <p class="text-right text-sm text-zinc-700">72%</p>

                <p class="text-sm text-zinc-700">화면과 비슷해요</p>
                <div class="h-1.5 rounded-full bg-zinc-200" aria-label="화면과 비슷해요 31%">
                    <div class="h-1.5 rounded-full bg-zinc-400" style="width:31%"></div>
                </div>
                <p class="text-right text-sm text-zinc-700">31%</p>

                <p class="text-sm text-zinc-700">화면과 달라요</p>
                <div class="h-1.5 rounded-full bg-zinc-200" aria-label="화면과 달라요 6%">
                    <div class="h-1.5 rounded-full bg-zinc-400" style="width:6%"></div>
                </div>
                <p class="text-right text-sm text-zinc-700">6%</p>
            </div>
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

        <li class="sit_use_li space-y-4">
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

                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="inline-flex rounded bg-[#F4F4F4] px-2 py-1 text-xs text-[#6F6F6F]">기능 - 아주 마음에 들어요</span>
                    <span class="inline-flex rounded bg-[#F4F4F4] px-2 py-1 text-xs text-[#6F6F6F]">디자인 - 마음에 들어요</span>
                    <span class="inline-flex rounded bg-[#F4F4F4] px-2 py-1 text-xs text-[#6F6F6F]">색상 - 화면과 같아요</span>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <p class="text-sm text-gray-400"><?php echo $is_time_dot; ?></p>
                    <button type="button" class="inline-flex items-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm text-[#6F6F6F]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M7 10v12"></path>
                            <path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.95 2.45l-1.37 6A2 2 0 0 1 18.46 20H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 1a3 3 0 0 1 3 4.88Z"></path>
                        </svg>
                        <span>도움돼요 6</span>
                    </button>
                </div>

                <?php if ($is_admin || $row['mb_id'] == $member['mb_id']) { ?>
                    <div class="sit_use_cmd">
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

    if (!$i) echo '<p class="sit_empty !py-50">사용후기가 없습니다.</p>';
    ?>
</section>

<?php
echo itemuse_page($config['cf_write_pages'], $page, $total_page, G5_SHOP_URL . "/itemuse.php?it_id=$it_id&amp;page=", "");
?>

<script>
    $(function() {
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
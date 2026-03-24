<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_SHOP_SKIN_URL . '/style.css">', 0);
?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<?php
// 공통 상수/설정 변수 선언(행 데이터 $row를 쓰는 변수는 루프 내부에서 선언)
?>

<!-- 상품문의 목록 시작 { -->
<section id="sit_qa_list">
    <div class="flex items-center gap-2 justify-between mb-4">
        <h3 class="text-lg font-bold">상품 문의</h3>
        <a href="<?php echo $itemqa_form; ?>" class="itemqa_form text-white bg-gray-900 px-4 py-2 rounded text-sm">문의하기<span class="sound_only">새 창</span></a>
    </div>

    <?php
    $thumbnail_width = 500;
    $iq_num     = $total_count - ($page - 1) * $rows;

    for ($i = 0; $row = sql_fetch_array($result); $i++) {
        $iq_name    = get_text($row['iq_name']);
        $iq_subject = conv_subject($row['iq_subject'], 50, "…");

        $is_secret = false;
        if ($row['iq_secret']) {
            $iq_subject .= ' <span aria-label="비밀글" title="비밀글"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock-keyhole-icon lucide-lock-keyhole"><circle cx="12" cy="16" r="1"/><rect x="3" y="10" width="18" height="12" rx="2"/><path d="M7 10V7a5 5 0 0 1 10 0v3"/></svg></span>';

            if ($is_admin || $member['mb_id'] == $row['mb_id']) {
                $iq_question = get_view_thumbnail(conv_content($row['iq_question'], 1), $thumbnail_width);
            } else {
                $iq_question = '비밀글로 보호된 문의입니다.';
                $is_secret = true;
            }
        } else {
            $iq_question = get_view_thumbnail(conv_content($row['iq_question'], 1), $thumbnail_width);
        }
        $iq_time    = substr($row['iq_time'], 2, 8);
        $iq_time_dot = date('Y.m.d', strtotime($row['iq_time']));

        $hash = md5($row['iq_id'] . $row['iq_time'] . $row['iq_ip']);

        $iq_stats = '';
        $iq_style = '';
        $iq_answer = '';

        if ($row['iq_answer']) {
            $iq_answer = get_view_thumbnail(conv_content($row['iq_answer'], 1), $thumbnail_width);
            $iq_stats = '답변완료';
            $iq_style = 'text-blue-500 font-semibold';
            $is_answer = true;
        } else {
            $iq_stats = '답변대기';
            $iq_style = 'text-gray-800 font-semibold';
            $iq_answer = '답변이 등록되지 않았습니다.';
            $is_answer = false;
        }

        if ($i == 0) echo '<ol id="sit_qa_ol">';
    ?>

        <li class="sit_qa_li">
            <p class="flex items-center gap-2 text-base"><?php echo $iq_subject; ?></p>

            <div class="flex items-center gap-2 text-sm text-gray-400 mt-2">
                <span class="<?php echo $iq_style; ?>"><?php echo $iq_stats; ?></span>
                <span><?php echo $iq_name; ?></span>
                <span><?php echo $iq_time_dot; ?></span>
            </div>

            <div id="sit_qa_con_<?php echo $i; ?>" class="sit_qa_con text-sm">
                <div class="sit_qa_p">
                    <div class="sit_qa_qaq">
                        <strong class="sound_only">문의내용</strong>
                        <span class="qa_alp">Q</span>
                        <?php echo $iq_question; // 상품 문의 내용 
                        ?>
                    </div>
                    <?php if (!$is_secret) { ?>
                        <div class="sit_qa_qaa">
                            <strong class="sound_only">답변</strong>
                            <span class="qa_alp">A</span>
                            <?php echo $iq_answer; ?>
                        </div>
                    <?php } ?>
                </div>

                <?php if ($is_admin || ($row['mb_id'] == $member['mb_id'] && !$is_answer)) { ?>
                    <div class="sit_qa_cmd">
                        <a href="<?php echo $itemqa_form . "&amp;iq_id={$row['iq_id']}&amp;w=u"; ?>" class="itemqa_form px-2 py-1 border border-gray-400 text-gray-500 rounded" onclick="return false;">수정</a>
                        <a href="<?php echo $itemqa_formupdate . "&amp;iq_id={$row['iq_id']}&amp;w=d&amp;hash={$hash}"; ?>" class="itemqa_delete px-2 py-1 border border-gray-400 text-gray-500 rounded">삭제</a>
                    </div>
                <?php } ?>
            </div>
        </li>

    <?php
        $iq_num--;
    }

    if ($i > 0) echo '</ol>';

    if (!$i) echo '<p class="sit_empty">상품문의가 없습니다.</p>';
    ?>
</section>

<?php
echo itemqa_page($config['cf_write_pages'], $page, $total_page, G5_SHOP_URL . "/itemqa.php?it_id=$it_id&amp;page=", "");
?>

<script>
    $(function() {
        $(".itemqa_form").click(function() {
            window.open(this.href, "itemqa_form", "width=810,height=680,scrollbars=1");
            return false;
        });

        $(".itemqa_delete").click(function() {
            return confirm("정말 삭제 하시겠습니까?\n\n삭제후에는 되돌릴수 없습니다.");
        });

        // sit_qa_li 를 클릭하면 아코디언 작동
        $(".sit_qa_li").click(function(e) {
            // 내용영역과 링크/폼 요소 클릭은 기본 동작 유지
            if ($(e.target).closest(".sit_qa_con, a, button, input, textarea, select, label").length) {
                return;
            }

            var $con = $(this).children(".sit_qa_con");
            if ($con.is(":visible")) {
                $con.slideUp(0);
            } else {
                $(".sit_qa_con:visible").hide();
                $con.slideDown(
                    0,
                    function() {
                        // 이미지 리사이즈
                        $con.viewimageresize2();
                    }
                );
            }
        });

        $(".qa_page").click(function() {
            $("#itemqa").load($(this).attr("href"));
            return false;
        });
    });
</script>
<!-- } 상품문의 목록 끝 -->
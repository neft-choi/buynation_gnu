<?php
include_once('./_common.php');

add_stylesheet('<link rel="stylesheet" href="' . G5_SHOP_SKIN_URL . '/style.css">', 0);
add_javascript('<script src="' . G5_JS_URL . '/viewimageresize.js"></script>', 0);

$g5['title'] = '상품 문의 내역';
include_once('./_head.php');

$sql_common = " from `{$g5['g5_shop_item_qa_table']}` a join `{$g5['g5_shop_item_table']}` b on (a.it_id=b.it_id) ";
$sql_search = " where (1) ";

$currentUserMbId = $member['mb_id'];

$sql_search .= " and ( ";
$sql_search .= " ( a.mb_id = '$currentUserMbId') ";
$sql_search .= " ) ";

if (!$sst) {
    $sst  = "a.iq_id";
    $sod = "desc";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt
         $sql_common
         $sql_search
         $sql_order ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) {
    $page = 1;
} // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select a.*, b.it_name
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);
?>

<!-- 모바일 헤더 -->
<div class="flex pc:hidden items-center justify-between p-4">
    <button type="button" class="inline-flex items-center justify-center text-zinc-700" aria-label="뒤로가기"
        onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '<?php echo G5_URL ?>'; }">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-chevron-left-icon lucide-chevron-left w-6 h-6">
            <path d="m15 18-6-6 6-6" />
        </svg>
    </button>
    <h1 class="text-lg font-semibold text-zinc-900 leading-0"><?php echo $g5['title']; ?></h1>
    <div class="w-6 h-6" aria-hidden="true"></div>
</div>

<!-- 회원 요약 정보 (PC) -->
<?php include_once(G5_THEME_SHOP_PATH . '/_mypage_summary_pc.php'); ?>

<!-- 취소/교환/반품 내역 -->
<div class="block pc:flex gap-6 pc:px-5 pc:py-12">
    <!-- 마이페이지 메뉴 (PC) -->
    <?php include_once(G5_THEME_SHOP_PATH . '/_mypage_menu_pc.php'); ?>

    <!-- 상품 문의 내역 -->
    <section class="min-w-0 flex-1">

        <!-- PC 너비 타이틀 -->
        <div class="hidden pc:block px-4">
            <h2 class="text-2xl font-bold pb-4 border-b-2 border-gray-900">
                <?php echo $g5['title']; ?>
            </h2>
        </div>

        <div id="sqa" class="p-4">
            <?php
            $thumbnail_width = 500;

            for ($i = 0; $row = sql_fetch_array($result); $i++) {
                $iq_subject = conv_subject($row['iq_subject'], 50, "…");

                $is_secret = false;
                if ($row['iq_secret']) {
                    $iq_subject .= ' <i class="fa fa-lock" aria-hidden="true"></i>';

                    if ($is_admin || $member['mb_id'] == $row['mb_id']) {
                        $iq_question = get_view_thumbnail(conv_content($row['iq_question'], 1), $thumbnail_width);
                    } else {
                        $iq_question = '비밀글로 보호된 문의입니다.';
                        $is_secret = true;
                    }
                } else {
                    $iq_question = get_view_thumbnail(conv_content($row['iq_question'], 1), $thumbnail_width);
                }

                $it_href = shop_item_url($row['it_id']);

                if ($row['iq_answer']) {
                    $iq_stats = '답변완료';
                    $iq_style = 'text-blue-400';
                    $iq_answer = get_view_thumbnail(conv_content($row['iq_answer'], 1), $thumbnail_width);
                } else {
                    $iq_stats = '답변대기';
                    $iq_style = 'text-amber-600';
                    $iq_answer = '답변이 등록되지 않았습니다.';
                }

                if ($i == 0) echo '<ul class="space-y-4">';
            ?>
                <li>
                    <div class="flex items-start gap-4">
                        <div class="sqa_img shrink-0">
                            <a href="<?php echo $it_href; ?>">
                                <?php echo get_it_image($row['it_id'], 50, 50); ?>
                                <span class="sr-only"><?php echo $row['it_name']; ?></span>
                            </a>
                        </div>

                        <section class="sqa_section flex-1 min-w-0">
                            <p class="text-sm"><?php echo $iq_subject; ?></p>

                            <div class="flex items-center gap-4 text-sm text-slate-500">
                                <span class="text-xs <?php echo $iq_style; ?>"><?php echo $iq_stats; ?></span>
                                <span><?php echo $row['iq_name']; ?></span>
                                <span><?php echo substr($row['iq_time'], 0, 10); ?></span>
                            </div>

                            <div id="itemqa_panel_<?php echo $row['iq_id']; ?>" class="hidden text-sm mt-3" aria-hidden="true">
                                <div class="sit_qa_qaq min-h-14 rounded-lg bg-gray-100 px-4 py-2">
                                    <strong class="sound_only">문의내용</strong>
                                    <span class="qa_alp">Q</span>
                                    <?php echo $iq_question; ?>
                                </div>

                                <?php if (!$is_secret) { ?>
                                    <div class="sit_qa_qaa min-h-14 rounded-lg bg-gray-100 px-4 py-2 mt-2">
                                        <strong class="sound_only">답변</strong>
                                        <span class="qa_alp">A</span>
                                        <?php echo $iq_answer; ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </section>

                        <div class="sqa_con_btn">
                            <button
                                type="button"
                                class="js-itemqa-toggle inline-flex items-center gap-2 border border-gray-900 rounded text-xs px-2 py-1"
                                aria-controls="itemqa_panel_<?php echo $row['iq_id']; ?>"
                                aria-expanded="false">
                                <span class="js-itemqa-toggle-label">내용보기</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="js-itemqa-toggle-icon w-3 h-3">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </li>
            <?php
            }

            if ($i > 0) echo '</ul>';
            if ($i == 0) echo '<p id="sqa_empty">자료가 없습니다.</p>';
            ?>
        </div>

        <?php echo get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
    </section>

    <script>
        $(function() {
            $('.js-itemqa-toggle').on('click', function() {
                const $button = $(this);
                const panelId = $button.attr('aria-controls');
                const $panel = $('#' + panelId);
                const isExpanded = $button.attr('aria-expanded') === 'true';

                $('.js-itemqa-toggle[aria-expanded="true"]').not($button).each(function() {
                    const $openButton = $(this);
                    const openPanelId = $openButton.attr('aria-controls');
                    const $openPanel = $('#' + openPanelId);

                    $openPanel.stop(true, true).slideUp(function() {
                        $openPanel.addClass('hidden');
                        $openPanel.attr('aria-hidden', 'true');
                    });

                    $openButton.attr('aria-expanded', 'false');
                    $openButton.find('.js-itemqa-toggle-label').text('내용보기');
                    $openButton.find('.js-itemqa-toggle-icon').removeClass('rotate-180');
                });

                if (isExpanded) {
                    $panel.stop(true, true).slideUp(function() {
                        $panel.addClass('hidden');
                        $panel.attr('aria-hidden', 'true');
                    });

                    $button.attr('aria-expanded', 'false');
                    $button.find('.js-itemqa-toggle-label').text('내용보기');
                    $button.find('.js-itemqa-toggle-icon').removeClass('rotate-180');
                    return;
                }

                $panel
                    .stop(true, true)
                    .removeClass('hidden')
                    .hide()
                    .slideDown(function() {
                        $panel.attr('aria-hidden', 'false');
                        $panel.viewimageresize2();
                    });

                $button.attr('aria-expanded', 'true');
                $button.find('.js-itemqa-toggle-label').text('내용닫기');
                $button.find('.js-itemqa-toggle-icon').addClass('rotate-180');
            });
        });

        // 반응형 쇼핑몰 헤더 숨기기
        syncWithPcBreakpoint(function(isPc) {
            if (isPc) {
                $('#hd').css('display', '');
            } else {
                $('#hd').css('display', 'none');
            }
        });
    </script>
</div>

<?php
include_once('./_tail.php');

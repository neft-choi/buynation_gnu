<?php
include_once('./_common.php');

// 비회원 접근 차단
if (!$is_member) {
    alert('회원만 이용하실 수 있습니다.', G5_BBS_URL . '/login.php?url=' . urlencode(G5_SHOP_URL . '/myitemqalist.php'));
}

add_stylesheet('<link rel="stylesheet" href="' . G5_SHOP_SKIN_URL . '/style.css">', 0);
add_javascript('<script src="' . G5_JS_URL . '/viewimageresize.js"></script>', 1);

$g5['title'] = '상품 문의 내역';
include_once('./_head.php');

$sql_common = " from `{$g5['g5_shop_item_qa_table']}` a join `{$g5['g5_shop_item_table']}` b on (a.it_id=b.it_id) ";
$sql_search = " where (1) ";

$currentUserMbId = $member['mb_id'];

$sql_search .= " and ( ";
$sql_search .= " ( a.mb_id = '$currentUserMbId') ";
$sql_search .= " ) ";

$stx = trim($stx);
if ($stx !== '') {
    $sql_search .= " and a.iq_subject like '%{$stx}%' ";
}

$qa_status_filter = isset($_GET['qa_status_filter'])
    && in_array($_GET['qa_status_filter'], array('0', '1'), true)
    ? $_GET['qa_status_filter']
    : '';

if ($qa_status_filter === '0') {
    $sql_search .= " and cast(a.iq_answer as binary) in ('', '0') ";
} elseif ($qa_status_filter === '1') {
    $sql_search .= " and cast(a.iq_answer as binary) not in ('', '0') ";
}

if ($qa_status_filter !== '') {
    $qstr .= '&amp;qa_status_filter=' . $qa_status_filter;
}

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

        <form name="fsearch" method="get" action="<?php echo G5_SHOP_URL; ?>/myitemqalist.php"
            class="flex items-center gap-2 p-4">
            <input type="hidden" name="sfl" value="a.iq_subject">
            <input type="hidden" name="qa_status_filter" value="<?php echo $qa_status_filter; ?>">
            <label for="itemqa-search" class="sound_only">문의 제목 검색</label>
            <input type="text" name="stx" id="itemqa-search"
                value="<?php echo get_text(stripslashes($stx)); ?>" required maxlength="20"
                placeholder="문의 제목 검색"
                class="min-w-0 flex-1 rounded border border-zinc-300 bg-white text-sm text-zinc-900 placeholder:text-zinc-400 px-3 py-2">
            <button type="submit"
                class="shrink-0 rounded border border-zinc-300 bg-white text-sm text-zinc-900 hover:bg-zinc-50 px-3 py-2">검색</button>
            <button type="button" id="itemqa-search-reset"
                class="shrink-0 rounded border border-zinc-300 bg-white text-sm text-zinc-700 hover:bg-zinc-50 px-3 py-2">초기화</button>
        </form>

        <div class="flex flex-wrap items-center gap-2 px-4">
            <label for="itemqa-type-filter" class="sound_only">문의유형</label>
            <select id="itemqa-type-filter"
                class="!w-auto rounded border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900">
                <option value="">전체 문의유형</option>
                <option value="product">상품</option>
                <option value="delivery">배송</option>
                <option value="return_cancel">반품·취소</option>
                <option value="exchange">교환</option>
                <option value="other">기타</option>
            </select>

            <label for="itemqa-status-filter" class="sound_only">답변상태</label>
            <select id="itemqa-status-filter"
                class="!w-auto rounded border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900">
                <option value="" <?php echo get_selected($qa_status_filter, ''); ?>>전체 답변상태</option>
                <option value="0" <?php echo get_selected($qa_status_filter, '0'); ?>>답변대기</option>
                <option value="1" <?php echo get_selected($qa_status_filter, '1'); ?>>답변완료</option>
            </select>
        </div>

        <div id="sqa" class="p-4">
            <div class="hidden pc:grid pc:grid-cols-[12%_19%_47%_22%] items-center border-y border-slate-200 bg-zinc-50 text-xs text-slate-500">
                <span class="p-3">유형</span>
                <span class="p-3">상태</span>
                <span class="p-3">제목</span>
                <span class="p-3">작성일</span>
            </div>
            <?php
            $thumbnail_width = 500;

            for ($i = 0; $row = sql_fetch_array($result); $i++) {
                $iq_subject = conv_subject($row['iq_subject'], 50, "…");

                $is_secret = false;
                if ($row['iq_secret']) {
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
                    $iq_style = '';
                    $iq_answer = get_view_thumbnail(conv_content($row['iq_answer'], 1), $thumbnail_width);
                } else {
                    $iq_stats = '답변대기';
                    $iq_style = 'text-gray-500';
                    $iq_answer = '답변이 아직 등록되지 않았습니다.';
                }

                if ($i == 0) echo '<ul class="space-y-4 pc:space-y-0">';
            ?>
                <li class="border pc:border-0 pc:border-b! border-gray-300 rounded-lg pc:rounded-none">
                    <div class="grid min-h-[60px] grid-cols-[1fr_auto] items-center gap-x-3 gap-y-2 py-4 text-sm pc:grid-cols-[12%_19%_47%_22%] pc:gap-0">
                        <a href="<?php echo $it_href; ?>" title="<?php echo get_text($row['it_name']); ?>"
                            class="hidden px-3 text-slate-900 hover:underline pc:block">상품</a>
                        <div class="px-3">
                            <span data-status="<?php echo $row['iq_answer'] ? 'answered' : 'pending'; ?>"
                                class="inline-flex whitespace-nowrap rounded bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-500 data-[status=answered]:bg-emerald-50 data-[status=answered]:text-emerald-700">
                                <?php echo $iq_stats; ?>
                            </span>
                        </div>
                        <button type="button"
                            class="js-itemqa-toggle group order-3 col-span-2 flex min-w-0 items-center gap-2 px-3 text-left text-slate-900 pc:order-none pc:col-span-1"
                            aria-controls="itemqa_panel_<?php echo $row['iq_id']; ?>" aria-expanded="false">
                            
                            <span class="min-w-0 truncate font-medium"><?php echo $iq_subject; ?></span>
                            <?php if ($row['iq_secret']) { ?>
                                <i class="fa fa-lock shrink-0" aria-hidden="true"></i>
                            <?php } ?>

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="h-4 w-4 shrink-0 group-aria-expanded:rotate-180">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                        <time datetime="<?php echo substr($row['iq_time'], 0, 10); ?>"
                            class="whitespace-nowrap px-3 text-right text-slate-900 pc:text-left">
                            <?php echo date('Y.m.d', strtotime($row['iq_time'])); ?>
                        </time>
                    </div>

                    <div id="itemqa_panel_<?php echo $row['iq_id']; ?>" class="hidden px-3 pb-4 text-sm">
                        <div class="sit_qa_qaq min-h-14 rounded-lg bg-gray-100 p-3">
                            <strong class="sr-only">문의내용</strong>
                            <span class="qa_alp mb-1 block font-medium">Q</span>
                            <?php echo $iq_question; ?>
                        </div>

                        <?php if (!$is_secret) { ?>
                            <div class="sit_qa_qaa mt-2 min-h-14 rounded-lg bg-gray-100 p-3">
                                <strong class="sr-only">답변</strong>
                                <span class="qa_alp mb-1 block font-medium">A</span>
                                <div class="<?php echo $iq_style; ?>">
                                    <?php echo $iq_answer; ?>
                                </div>
                            </div>
                        <?php } ?>
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
            $('#itemqa-search-reset').on('click', function() {
                const url = new URL(window.location.href);

                url.searchParams.delete('stx');
                url.searchParams.delete('page');

                window.location.href = url.toString();
            });

            $('#itemqa-status-filter').on('change', function() {
                const url = new URL(window.location.href);

                url.searchParams.delete('page');

                if (this.value === '') {
                    url.searchParams.delete('qa_status_filter');
                } else {
                    url.searchParams.set('qa_status_filter', this.value);
                }

                window.location.href = url.toString();
            });

            $('.js-itemqa-toggle').on('click', function() {
                const $button = $(this);
                const panelId = $button.attr('aria-controls');
                const $panel = $('#' + panelId);
                const isExpanded = $button.attr('aria-expanded') === 'true';

                $('.js-itemqa-toggle[aria-expanded="true"]').not($button).each(function() {
                    const $openButton = $(this);
                    const openPanelId = $openButton.attr('aria-controls');
                    const $openPanel = $('#' + openPanelId);

                    $openPanel.hide();
                    $openButton.attr('aria-expanded', 'false');
                });

                $panel.toggle(!isExpanded);
                $button.attr('aria-expanded', isExpanded ? 'false' : 'true');

                if (!isExpanded && !$panel.data('imageResizeInitialized') && $panel.find('img').length > 0) {
                    $panel.viewimageresize2();
                    $panel.data('imageResizeInitialized', true);
                }
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

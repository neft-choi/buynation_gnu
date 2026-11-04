<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가

// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 6;

if ($is_checkbox)
    $colspan++;

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

<!-- 모바일 헤더 -->
<div class="flex pc:hidden items-center justify-between h-[var(--mobile-header-height)] px-4">
    <button type="button" class="inline-flex items-center justify-center text-zinc-700" aria-label="뒤로가기"
        onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '<?php echo G5_URL ?>'; }">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-chevron-left-icon lucide-chevron-left w-6 h-6">
            <path d="m15 18-6-6 6-6" />
        </svg>
    </button>
    <h1 class="text-lg font-semibold text-zinc-900 leading-0">문의내역</h1>
    <div class="w-6 h-6" aria-hidden="true"></div>
</div>

<!-- 회원 요약 정보 (PC) -->
<?php include_once(G5_THEME_SHOP_PATH . '/_mypage_summary_pc.php'); ?>

<div class="block pc:flex gap-6 pc:px-5 pc:py-12">

    <!-- 마이페이지 메뉴 (PC) -->
    <?php include_once(G5_THEME_SHOP_PATH . '/_mypage_menu_pc.php'); ?>

    <!-- 문의 내역 -->
    <div id="bo_list" class="flex-1 min-w-0">

        <!-- PC 너비 타이틀 -->
        <div class="hidden pc:block px-4">
            <h2 class="text-2xl font-bold pb-4 border-b-2 border-gray-900">문의내역</h2>
        </div>

        <div class="flex items-center gap-2 px-4 pt-4" role="group" aria-label="문의 범위 선택">
            <button type="button" aria-pressed="true"
                class="qa-scope-button rounded-full border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-500 aria-pressed:border-zinc-700 aria-pressed:bg-zinc-700 aria-pressed:text-white">전체 문의</button>
            <button type="button" aria-pressed="false"
                class="qa-scope-button rounded-full border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-500 aria-pressed:border-zinc-700 aria-pressed:bg-zinc-700 aria-pressed:text-white">내 문의</button>
        </div>

        <div class="mx-auto w-full max-w-full p-4">
            <fieldset class="m-0 p-0">
                <legend class="sound_only">문의 검색</legend>
                <form name="fsearch" method="get">
                    <span class="sound_only">문의 검색</span>
                    <?php if ($sca !== '') { ?>
                        <input type="hidden" name="sca" value="<?php echo $sca; ?>">
                    <?php } ?>
                    <input type="hidden" name="sop" value="and">
                    <input type="hidden" name="qa_status_filter" value="<?php echo $qa_status_filter; ?>">
                    <input type="hidden" name="sfl" value="<?php echo $sfl ? $sfl : 'qa_subject'; ?>">
                    <label for="stx" class="sound_only">검색어 <span class="sound_only">필수</span></label>
                    <div class="flex items-center gap-2">
                        <div class="relative min-w-0 flex-1">
                            <input type="text" name="stx" value="<?php echo stripslashes($stx); ?>" required id="stx"
                                class="h-12 w-full px-4 pr-14 rounded border border-zinc-300 text-base text-zinc-900 placeholder-zinc-400"
                                size="15" maxlength="15" placeholder="궁금하신 내용을 검색해보세요.">
                            <button type="submit" value="검색" class="absolute top-3.5 right-4 text-zinc-700" aria-label="검색">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.3-4.3"></path>
                                </svg>
                            </button>
                        </div>
                        <button type="button" id="qa-search-reset"
                            class="h-12 shrink-0 rounded border border-zinc-300 bg-white px-4 text-sm text-zinc-700 hover:bg-zinc-50">초기화</button>
                    </div>
                </form>
            </fieldset>
        </div>

        <?php if (count($qa_category_items)) { ?>
            <div class="flex flex-wrap items-center gap-2 px-4 pt-4">
                <label for="qa-category-filter" class="sound_only">문의종류</label>
                <select id="qa-category-filter"
                    class="rounded border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900">
                    <?php foreach ($qa_category_items as $index => $item) { ?>
                        <option value="<?php echo get_text($item['href']); ?>"
                            <?php echo $item['is_active'] ? 'selected' : ''; ?>>
                            <?php echo get_text($index === 0 ? '전체 문의종류' : $item['label']); ?>
                        </option>
                    <?php } ?>
                </select>

                <label for="qa-status-filter" class="sound_only">답변상태</label>
                <select id="qa-status-filter"
                    class="rounded border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900">
                    <option value="" <?php echo get_selected($qa_status_filter, ''); ?>>전체 답변상태</option>
                    <option value="0" <?php echo get_selected($qa_status_filter, '0'); ?>>답변대기</option>
                    <option value="1" <?php echo get_selected($qa_status_filter, '1'); ?>>답변완료</option>
                </select>
            </div>
        <?php } ?>

        <div class="px-4 pt-4 pb-3 text-sm text-zinc-600">
            <span>Total <?php echo number_format($total_count); ?>건</span>
            <span><?php echo $page; ?> 페이지</span>
        </div>

        <section class="p-4">
            <div class="flex items-center justify-between gap-2 mb-2">
                <h2 class="text-lg font-bold">1:1 문의</h2>
                <?php if ($write_href) { ?>
                    <a href="<?php echo $write_href; ?>" title="문의하기"
                        class="px-4 py-2 rounded bg-gray-900 text-sm text-white">문의하기</a>
                <?php } ?>
            </div>

            <form name="fqalist" id="fqalist" action="./qadelete.php" onsubmit="return fqalist_submit(this);"
                method="post">
                <input type="hidden" name="stx" value="<?php echo $stx; ?>">
                <input type="hidden" name="sca" value="<?php echo $sca; ?>">
                <input type="hidden" name="page" value="<?php echo $page; ?>">
                <input type="hidden" name="token" value="<?php echo get_text($token); ?>">

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[640px] table-fixed border-collapse text-left text-sm">
                        <caption class="sr-only">문의 목록</caption>
                        <colgroup>
                            <col class="w-[12%]">
                            <col class="w-[19%]">
                            <col class="w-[47%]">
                            <col class="w-[22%]">
                        </colgroup>
                        <thead class="border-y border-slate-200 bg-zinc-50 text-xs text-slate-500">
                            <tr>
                                <th scope="col" class="h-12 px-3 text-left font-normal">유형</th>
                                <th scope="col" class="h-12 px-3 text-left font-normal">상태</th>
                                <th scope="col" class="h-12 px-3 text-left font-normal">제목</th>
                                <th scope="col" class="h-12 px-3 text-left font-normal">작성일</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < count($list); $i++) { ?>
                                <tr class="h-[60px] border-b border-slate-200 bg-white">
                                    <td class="px-3 py-4 text-slate-900">
                                        <?php if ($is_checkbox) { ?>
                                            <div class="td_chk hidden">
                                                <input type="checkbox" name="chk_qa_id[]" value="<?php echo $list[$i]['qa_id']; ?>"
                                                    id="chk_qa_id_<?php echo $i; ?>"
                                                    class="h-4 w-4 rounded border-slate-300 text-slate-700 focus:ring-slate-400">
                                                <label for="chk_qa_id_<?php echo $i; ?>"
                                                    class="sound_only"><?php echo $list[$i]['subject']; ?></label>
                                            </div>
                                        <?php } ?>
                                        <span class="td_num hidden"><?php echo $list[$i]['num']; ?></span>
                                        <?php echo $list[$i]['category']; ?>
                                    </td>
                                    <td class="px-3 py-4">
                                        <span data-status="<?php echo (int) $list[$i]['qa_status']; ?>"
                                            class="inline-flex whitespace-nowrap rounded bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-500 data-[status=1]:bg-emerald-50 data-[status=1]:text-emerald-700">
                                            <?php echo $list[$i]['qa_status'] ? '답변완료' : '답변대기'; ?>
                                        </span>
                                    </td>
                                    <td class="px-3 py-4">
                                        <a href="<?php echo $list[$i]['view_href']; ?>"
                                            class="flex items-center gap-2 text-slate-900 hover:underline">
                                            <span class="truncate"><?php echo $list[$i]['subject']; ?></span>
                                            <?php if ($list[$i]['icon_file']) { ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-slate-500"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    aria-hidden="true">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                    <path d="m7 10 5 5 5-5" />
                                                    <path d="M12 15V3" />
                                                </svg>
                                            <?php } ?>
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-slate-900">
                                        <?php echo date('Y.m.d', strtotime($list[$i]['qa_datetime'])); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if ($i === 0) { ?>
                                <tr>
                                    <td colspan="4" class="px-3 py-10 text-center text-slate-500">게시물이 없습니다.</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <!-- 페이지 -->
                <?php echo $list_pages; ?>
                <!-- 페이지 -->

                <div class="bo_fx !my-2 !hidden">
                    <ul class="btn_bo_user">
                        <?php if ($is_checkbox) { ?>
                            <li><button type="submit" name="btn_submit" value="선택삭제" title="선택삭제"
                                    onclick="document.pressed=this.value" class="btn btn_b01 btn_admin"><svg
                                        xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M3 6h18" />
                                        <path d="M8 6V4h8v2" />
                                        <path d="M19 6l-1 14H6L5 6" />
                                        <path d="M10 11v6" />
                                        <path d="M14 11v6" />
                                    </svg><span class="sound_only">선택삭제</span></button></li>
                        <?php } ?>
                        <?php if ($list_href) { ?>
                            <li><a href="<?php echo $list_href ?>" class="btn_b01 btn" title="목록"><svg
                                        xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M8 6h13" />
                                        <path d="M8 12h13" />
                                        <path d="M8 18h13" />
                                        <path d="M3 6h.01" />
                                        <path d="M3 12h.01" />
                                        <path d="M3 18h.01" />
                                    </svg><span class="sound_only">목록</span></a></li><?php } ?>
                        <?php if ($write_href) { ?>
                            <li><a href="<?php echo $write_href ?>" class="btn_b01 btn" title="문의등록"><svg
                                        xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path d="M12 20h9" />
                                        <path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4Z" />
                                    </svg><span class="sound_only">문의등록</span></a></li><?php } ?>
                    </ul>
                </div>
            </form>
        </section>
    </div>
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
<script>
    $(function() {
        const $scopeButtons = $('#bo_list .qa-scope-button');

        $scopeButtons.on('click', function() {
            $scopeButtons.attr('aria-pressed', 'false');
            $(this).attr('aria-pressed', 'true');
        });

        $('#qa-category-filter, #qa-status-filter').on('change', function() {
            const targetUrl = this.id === 'qa-category-filter' ? this.value : window.location.href;
            const url = new URL(targetUrl, window.location.href);
            const selectedStatus = $('#qa-status-filter').val();

            url.searchParams.delete('page');

            if (selectedStatus === '') {
                url.searchParams.delete('qa_status_filter');
            } else {
                url.searchParams.set('qa_status_filter', selectedStatus);
            }

            window.location.href = url.toString();
        });

        // 검색어 초기화 버튼
        $('#qa-search-reset').on('click', function() {
            const url = new URL(window.location.href);

            url.searchParams.delete('stx');
            url.searchParams.delete('page');

            window.location.href = url.toString();
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
<!-- } 게시판 목록 끝 -->
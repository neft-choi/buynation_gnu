<?php
if (!defined("_GNUBOARD_"))
    exit; // 개별 페이지 접근 불가

// 검색 바가 커뮤니티에 있는지 쇼핑에 있는지 확인
$searchbar_mode = isset($searchbar_mode) ? (string) $searchbar_mode : 'shop';
if ($searchbar_mode !== 'community') {
    $searchbar_mode = 'shop';
}

$is_community_mode = ($searchbar_mode === 'community');

// 커뮤니티 검색 바일 경우 bbs/search.php 아니면 shop/search.php
$search_action = $is_community_mode
    ? G5_BBS_URL . '/search.php'
    : G5_SHOP_URL . '/search.php';

// 사용자 검색어 파라미터
$search_input_name = $is_community_mode ? 'stx' : 'q';

$search_input_id = $is_community_mode ? 'community_searchbar_input' : 'shop_searchbar_input';
$search_input_value = '';

if ($is_community_mode && isset($stx)) {
    $search_input_value = stripslashes(get_text(get_search_string($stx)));
} else if (!$is_community_mode && isset($q)) {
    $search_input_value = stripslashes(get_text(get_search_string($q)));
}

$shop_searchbar_bookmark_icon = '
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bookmark-icon lucide-bookmark w-5 h-5">
    <path d="M17 3a2 2 0 0 1 2 2v15a1 1 0 0 1-1.496.868l-4.512-2.578a2 2 0 0 0-1.984 0l-4.512 2.578A1 1 0 0 1 5 20V5a2 2 0 0 1 2-2z" />
</svg>';

$shop_searchbar_arrow_up_right_icon = '
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-right-icon lucide-arrow-up-right w-5 h-5"><path d="M7 7h10v10"/><path d="M7 17 17 7"/>
</svg>';
?>
<?php if ($is_community_mode) { ?>
    <div id="community-searchbar-root">
        <form name="frmsearch_modern" class="flex items-center gap-3" action="<?php echo $search_action; ?>"
            onsubmit="return searchbar_modern_submit(this, '<?php echo $searchbar_mode; ?>');">
            <input type="hidden" name="sfl" value="wr_subject||wr_content">
            <input type="hidden" name="sop" value="and">

            <label for="<?php echo $search_input_id; ?>" class="sound_only">검색어<span class="sound_only"> 필수</span></label>
            <input type="search" name="<?php echo $search_input_name; ?>" id="<?php echo $search_input_id; ?>"
                value="<?php echo $search_input_value; ?>"
                class="flex-1 min-w-0 h-[45px] rounded-full bg-white px-4 py-2 text-sm font-medium focus-visible:outline-none"
                required>
            <button type="submit" id="community_searchbar_submit"
                class="shrink-0 inline-flex items-center justify-center w-[45px] h-[45px] rounded-full text-black bg-white cursor-pointer"
                value="검색">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-search-icon lucide-search w-5 h-5">
                    <path d="m21 21-4.34-4.34" />
                    <circle cx="11" cy="11" r="8" />
                </svg>
                <span class="sound_only">검색</span>
            </button>
        </form>
    </div>
<?php } else { ?>
    <div id="shop-searchbar-root">
        <form name="frmsearch_modern"
            class="flex items-center gap-2 w-full px-4 py-2 border-2 border-[var(--color-primary)] rounded-full bg-white"
            action="<?php echo $search_action; ?>"
            onsubmit="return searchbar_modern_submit(this, '<?php echo $searchbar_mode; ?>');">

            <label for="<?php echo $search_input_id; ?>" class="sound_only">검색어<span class="sound_only"> 필수</span></label>
            <input type="search" name="<?php echo $search_input_name; ?>" id="<?php echo $search_input_id; ?>"
                value="<?php echo $search_input_value; ?>" class="text-sm flex-auto focus-visible:outline-0" required
                placeholder="검색어를 입력해주세요" autocomplete="off">
            <button type="submit" id="shop_searchbar_submit" class="text-[var(--color-primary)]" value="검색">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-search-icon lucide-search">
                    <path d="m21 21-4.34-4.34" />
                    <circle cx="11" cy="11" r="8" />
                </svg>
                <span class="sound_only">검색</span>
            </button>
        </form>

        <div id="shop-searchbar-panel"
            class="hidden absolute top-full left-1/2 z-20 h-[calc(100vh-112px)] w-screen -translate-x-1/2 border-b border-gray-300 bg-white overflow-y-auto"
            role="dialog" aria-label="쇼핑 검색 영역" aria-hidden="true">

            <div id="shop-searchbar-panel-inner" class="max-w-[var(--breakpoint-pc)] mx-auto pb-4">
                <div class="flex items-center justify-end px-2 pc:px-4 py-4">
                    <button type="button" id="shop-searchbar-panel-close"
                        class="rounded-full hover:bg-gray-200 p-1 cursor-pointer" aria-label="검색 패널 닫기">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-x-icon lucide-x w-6 h-6">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex flex-col pc:flex-row divide-y pc:divide-none divide-gray-300">
                    <div class="p-4 w-full">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm pc:text-base font-bold">최근 검색어</h3>
                            <span class="text-xs pc:text-sm">전체 삭제</span>
                        </div>
                        <div class="flex flex-row pc:flex-col items-center pc:items-start gap-2 mt-4">
                            <div
                                class="inline-flex items-center gap-1 text-xs pc:text-sm bg-gray-200 rounded-full px-2 py-1">
                                <span>테니스 라켓</span>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-x-icon lucide-x w-3 pc:w-4 h-3 pc:h-4">
                                        <path d="M18 6 6 18" />
                                        <path d="m6 6 12 12" />
                                    </svg>
                                </span>
                            </div>
                            <div
                                class="inline-flex items-center gap-1 text-xs pc:text-sm bg-gray-200 rounded-full px-2 py-1">
                                <span>여행 도넛</span>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-x-icon lucide-x w-3 pc:w-4 h-3 pc:h-4">
                                        <path d="M18 6 6 18" />
                                        <path d="m6 6 12 12" />
                                    </svg>
                                </span>
                            </div>
                            <div
                                class="inline-flex items-center gap-1 text-xs pc:text-sm bg-gray-200 rounded-full px-2 py-1">
                                <span>고추장</span>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-x-icon lucide-x w-3 pc:w-4 h-3 pc:h-4">
                                        <path d="M18 6 6 18" />
                                        <path d="m6 6 12 12" />
                                    </svg>
                                </span>
                            </div>
                            <div
                                class="inline-flex items-center gap-1 text-xs pc:text-sm bg-gray-200 rounded-full px-2 py-1">
                                <span>러닝화</span>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-x-icon lucide-x w-3 pc:w-4 h-3 pc:h-4">
                                        <path d="M18 6 6 18" />
                                        <path d="m6 6 12 12" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 w-full">
                        <h3 class="text-sm pc:text-base font-bold">인기 검색어</h3>

                        <div class="flex items-center gap-4 mt-4">
                            <div class="text-xs pc:text-sm space-y-2 w-full">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="bg-yellow-300 rounded-full px-2">1</span>
                                        <span class="text-nowrap">테니스 라켓</span>
                                    </div>
                                    <span><?= $shop_searchbar_arrow_up_right_icon ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="bg-yellow-300 rounded-full px-2">2</span>
                                        <span class="text-nowrap">테니스 화</span>
                                    </div>
                                    <span><?= $shop_searchbar_arrow_up_right_icon ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="bg-yellow-300 rounded-full px-2">3</span>
                                        <span class="text-nowrap">테니스 가방</span>
                                    </div>
                                    <span><?= $shop_searchbar_arrow_up_right_icon ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="bg-gray-200 rounded-full px-2">4</span>
                                        <span class="text-nowrap">테니스 라켓</span>
                                    </div>
                                    <span><?= $shop_searchbar_arrow_up_right_icon ?></span>
                                </div>
                            </div>

                            <div class="text-xs pc:text-sm space-y-2 w-full">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="bg-gray-200 rounded-full px-2">5</span>
                                        <span class="text-nowrap">테니스 공</span>
                                    </div>
                                    <span><?= $shop_searchbar_arrow_up_right_icon ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="bg-gray-200 rounded-full px-2">6</span>
                                        <span class="text-nowrap">윌슨 라켓</span>
                                    </div>
                                    <span><?= $shop_searchbar_arrow_up_right_icon ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="bg-gray-200 rounded-full px-2">7</span>
                                        <span class="text-nowrap">바볼랏 퓨어드라이브</span>
                                    </div>
                                    <span><?= $shop_searchbar_arrow_up_right_icon ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="bg-gray-200 rounded-full px-2">8</span>
                                        <span class="text-nowrap">테니스 입문</span>
                                    </div>
                                    <span>
                                        <?= $shop_searchbar_arrow_up_right_icon ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 w-full">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm pc:text-base font-bold">인기 도넛</h3>
                            <div class="flex items-center">
                                <span class="text-xs pc:text-sm">더보기</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-4">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex flex-col items-start gap-2 mt-4">
                            <div class="inline-flex items-center gap-2 text-xs pc:text-sm">
                                <div class="w-12 h-12 bg-gray-200 rounded-full"></div>
                                <div>
                                    <p class="text-sm pc:text-base font-semibold">테니스 커뮤니티</p>
                                    <span class="text-xs pc:text-sm text-gray-300">멤버 1.2만명 · 게시글 3.4천개</span>
                                </div>
                            </div>

                            <div class="inline-flex items-center gap-2 text-xs pc:text-sm">
                                <div class="w-12 h-12 bg-gray-200 rounded-full"></div>
                                <div>
                                    <p class="text-sm pc:text-base font-semibold">맛집 도넛</p>
                                    <span class="text-xs pc:text-sm text-gray-300">멤버 8천명 · 게시글 2.1천개</span>
                                </div>
                            </div>

                            <div class="inline-flex items-center gap-2 text-xs pc:text-sm">
                                <div class="w-12 h-12 bg-gray-200 rounded-full"></div>
                                <div>
                                    <p class="text-sm pc:text-base font-semibold">러닝 도넛</p>
                                    <span class="text-xs pc:text-sm text-gray-300">멤버 6천명 · 게시글 1.8천개</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 w-full">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm pc:text-base font-bold">추천 상품</h3>
                            <div class="flex items-center">
                                <span class="text-xs pc:text-sm">더보기</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-4">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2 mt-4">
                            <div class="flex items-center gap-4 text-xs pc:text-sm">
                                <div class="w-12 h-12 bg-gray-200 rounded"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="line-clamp-1">바볼랏 퓨어 드라이브 98 2023</p>
                                    <span class="font-bold">240,000원</span>
                                    <span class="text-xs bg-yellow-300 rounded px-1 py-0.5">도넛배송</span>
                                    <p class="text-gray-300">판매자 ★4.9 | 78개 판매</p>
                                </div>
                                <div>
                                    <?php echo $shop_searchbar_bookmark_icon; ?>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 text-xs pc:text-sm">
                                <div class="w-12 h-12 bg-gray-200 rounded"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="line-clamp-1">나이키 코트 에어 줌 GP 터보</p>
                                    <span class="font-bold">159,000원</span>
                                    <span class="text-xs bg-yellow-300 rounded px-1 py-0.5">도넛배송</span>
                                    <p class="text-gray-300">판매자 ★4.8 | 56개 판매</p>
                                </div>
                                <div>
                                    <?php echo $shop_searchbar_bookmark_icon; ?>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 text-xs pc:text-sm">
                                <div class="w-12 h-12 bg-gray-200 rounded"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="line-clamp-1">윌슨 챔피언십 테니스공 (3구)</p>
                                    <span class="font-bold">8,900원</span>
                                    <span class="text-xs bg-yellow-300 rounded px-1 py-0.5">도넛배송</span>
                                    <p class="text-gray-300">판매자 ★4.9 | 320개 판매</p>
                                </div>
                                <div>
                                    <?php echo $shop_searchbar_bookmark_icon; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            const $html = $('html');
            const $header = $('#hd');
            const $searchbarInput = $('#shop_searchbar_input');
            const $searchbarPanel = $('#shop-searchbar-panel');
            const $searchbarClose = $('#shop-searchbar-panel-close');

            $searchbarInput.on('focus', function () {
                $html.css({
                    'overflow': 'hidden',
                    'scrollbar-gutter': 'stable'
                });
                $header.css('z-index', 51);
                $searchbarPanel.removeClass('hidden').attr('aria-hidden', 'false');
            });

            // 검색 패널 닫기 버튼 클릭 시
            $searchbarClose.on('click', function () {
                $html.css({
                    'overflow': '',
                    'scrollbar-gutter': ''
                });
                $header.css('z-index', '');
                $searchbarPanel.addClass('hidden').attr('aria-hidden', 'true');
            })

            $(document).on('click', function (event) {
                if ($(event.target).closest('#shop-searchbar-root').length) {
                    return;
                }

                $html.css({
                    overflow: '',
                    'scrollbar-gutter': ''
                });

                $header.css('z-index', '');
                $searchbarPanel.addClass('hidden').attr('aria-hidden', 'true');
            });
        });
    </script>
<?php } ?>

<script>
    // 제출 검증 함수
    function searchbar_modern_submit(form, mode) {
        const inputName = mode === 'community' ? 'stx' : 'q';
        const raw = form[inputName] ? form[inputName].value : '';
        const keyword = raw.trim();

        if (keyword.length < 2) {
            alert("검색어는 두글자 이상 입력하십시오.");
            if (form[inputName]) {
                form[inputName].select();
                form[inputName].focus();
            }
            return false;
        }

        if (mode === 'community') {
            let spaceCount = 0;
            for (let i = 0; i < keyword.length; i++) {
                if (keyword.charAt(i) === ' ') {
                    spaceCount++;
                }
            }

            if (spaceCount > 1) {
                alert("빠른 검색을 위하여 검색어에 공백은 한개만 입력할 수 있습니다.");
                if (form[inputName]) {
                    form[inputName].select();
                    form[inputName].focus();
                }
                return false;
            }
        }

        if (form[inputName]) {
            form[inputName].value = keyword;
        }

        return true;
    }
</script>
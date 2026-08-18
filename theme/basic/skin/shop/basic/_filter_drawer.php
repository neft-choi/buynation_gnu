<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가
?>

<!-- filterDrawer 시작 -->
<div id="filterDrawer" class="hidden fixed inset-0 z-50" aria-hidden="true">
    <div id="filterDrawerBackdrop" class="absolute inset-0 bg-black/40"></div>
    <form id="filterDrawerForm" method="get" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>"
        class="fixed left-0 right-0 bottom-[var(--bottom-nav-height)] w-full h-[90vh] bg-white rounded-t-xl">
        <?php if ($this->type) { ?>
            <input type="hidden" name="type" value="<?php echo (int) $this->type; ?>">
        <?php } ?>

        <?php if (isset($brand['brand_id']) && $brand['brand_id'] !== '') { ?>
            <input type="hidden" name="brand_id" value="<?php echo get_text($brand['brand_id']); ?>">
        <?php } ?>

        <?php if ($this->ca_id) { ?>
            <input type="hidden" name="ca_id" value="<?php echo get_text($this->ca_id); ?>">
        <?php } ?>

        <?php if (isset($_GET['sort']) && $_GET['sort'] !== '') { ?>
            <input type="hidden" name="sort" value="<?php echo get_text($_GET['sort']); ?>">
        <?php } ?>

        <?php if (isset($_GET['sortodr']) && $_GET['sortodr'] !== '') { ?>
            <input type="hidden" name="sortodr" value="<?php echo get_text($_GET['sortodr']); ?>">
        <?php } ?>

        <?php if (defined('IS_SHOP_SEARCH') && IS_SHOP_SEARCH && isset($_GET['q'])) { ?>
            <input type="hidden" name="q" value="<?php echo get_text($_GET['q']); ?>">
        <?php } ?>

        <div class="px-4 pt-5">
            <div class="flex items-center justify-between">
                <p class="text-[20px] font-bold">필터</p>
                <button type="button" id="filterDrawerClose">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-x-icon lucide-x w-6 h-6">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex items-center gap-8 text-sm border-b border-gray-200 mt-3">
                <button type="button" id="filterTabType"
                    class="pb-2 font-semibold text-gray-900 border-b-2 border-gray-900">
                    종류
                </button>

                <button type="button" id="filterTabPrice" class="pb-2 text-gray-400">
                    가격대
                </button>

                <?php if (basename($_SERVER['SCRIPT_NAME']) !== 'listtype.php' && basename($_SERVER['SCRIPT_NAME']) !== 'brand.php') { ?>
                    <button type="button" id="filterTabCategory" class="pb-2 text-gray-400">
                        카테고리
                    </button>
                <?php } ?>
            </div>
        </div>

        <div id="filterPanelType" class="filterDrawerPanel mt-6 px-4">
            <ul class="space-y-3">
                <li>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <div class="relative w-5 h-5">
                            <input type="checkbox" name="it_type1" value="1"
                                class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900 checked:bg-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-check-icon lucide-check absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-gray-300 peer-checked:text-white pointer-events-none">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                        </div>
                        <span>히트상품</span>
                    </label>
                </li>
                <li>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <div class="relative w-5 h-5">
                            <input type="checkbox" name="it_type2" value="1"
                                class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900 checked:bg-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-check-icon lucide-check absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-gray-300 peer-checked:text-white pointer-events-none">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                        </div>
                        <span>추천상품</span>
                    </label>
                </li>
                <li>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <div class="relative w-5 h-5">
                            <input type="checkbox" name="it_type3" value="1"
                                class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900 checked:bg-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-check-icon lucide-check absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-gray-300 peer-checked:text-white pointer-events-none">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                        </div>
                        <span>신상품</span>
                    </label>
                </li>
                <li>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <div class="relative w-5 h-5">
                            <input type="checkbox" name="it_type4" value="1"
                                class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900 checked:bg-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-check-icon lucide-check absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-gray-300 peer-checked:text-white pointer-events-none">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                        </div>
                        <span>베스트상품</span>
                    </label>
                </li>
            </ul>
        </div>

        <div id="filterPanelPrice" class="filterDrawerPanel mt-6 px-4 hidden">
            <div>
                <ul class="space-y-3 mb-3">
                    <li>
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <div class="relative w-5 h-5">
                                <input type="radio" name="price_range" value="0_30000"
                                    class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900">
                                <div
                                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 bg-gray-900 rounded-full opacity-0 peer-checked:opacity-100 pointer-events-none">
                                </div>
                            </div>
                            <span>~3만원</span>
                        </label>
                    </li>
                    <li>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <div class="relative w-5 h-5">
                                <input type="radio" name="price_range" value="30000_50000"
                                    class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900">
                                <div
                                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 bg-gray-900 rounded-full opacity-0 peer-checked:opacity-100 pointer-events-none">
                                </div>
                            </div>
                            <span>3만원~5만원</span>
                        </label>
                    </li>
                    <li>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <div class="relative w-5 h-5">
                                <input type="radio" name="price_range" value="50000_100000"
                                    class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900">
                                <div
                                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 bg-gray-900 rounded-full opacity-0 peer-checked:opacity-100 pointer-events-none">
                                </div>
                            </div>
                            <span>5만원~10만원</span>
                        </label>
                    </li>
                    <li>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <div class="relative w-5 h-5">
                                <input type="radio" name="price_range" value="100000_up"
                                    class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900">
                                <div
                                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 bg-gray-900 rounded-full opacity-0 peer-checked:opacity-100 pointer-events-none">
                                </div>
                            </div>
                            <span>10만원 이상</span>
                        </label>
                    </li>
                </ul>

                <div class="flex items-center gap-1">
                    <input type="text" name="price_min"
                        class="w-full border border-gray-300 !px-2 !py-1 text-sm placeholder:text-right"
                        placeholder="원">
                    <span>~</span>
                    <input type="text" name="price_max"
                        class="w-full border border-gray-300 !px-2 !py-1 text-sm placeholder:text-right"
                        placeholder="원">
                    <button type="submit" id="filterDrawerPriceApply"
                        class="border border-gray-300 rounded p-2 bg-gray-300 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-search-icon lucide-search w-3.5 h-3.5">
                            <path d="m21 21-4.34-4.34" />
                            <circle cx="11" cy="11" r="8" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <?php if (basename($_SERVER['SCRIPT_NAME']) !== 'listtype.php' && basename($_SERVER['SCRIPT_NAME']) !== 'brand.php') { ?>
            <div id="filterPanelCategory" class="filterDrawerPanel mt-6 px-4 hidden">
                <?php include G5_THEME_PATH . '/' . G5_SKIN_DIR . '/shop/basic/_category.php'; ?>
            </div>
        <?php } ?>

        <div class="flex items-center gap-3 text-base px-4 pt-5 pb-4 mt-auto">
            <button type="button" id="filterDrawerReset"
                class="flex items-center justify-center gap-2 w-full bg-white border border-gray-400 rounded px-5 py-4 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-rotate-ccw-icon lucide-rotate-ccw w-5 h-5">
                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                    <path d="M3 3v5h5" />
                </svg>
                <span>초기화</span>
            </button>
            <button type="submit" id="filterDrawerApply"
                class="w-full bg-[var(--color-primary)] rounded px-5 py-4 cursor-pointer">
                <span>적용하기</span>
            </button>
        </div>
    </form>
</div>

<script>
    // 필터 버튼 누르면 Drawer 열림
    $('#filterDrawerOpen').on('click', function() {
        $('#hd').css('z-index', 4);
        $('#filterDrawer').removeClass('hidden');
    })

    // 필터 Drawer 안 X 버튼과 뒷배경 누르면 Drawer hidden 처리
    $('#filterDrawerClose, #filterDrawerBackdrop').on('click', function() {
        $('#filterDrawer').addClass('hidden');
        $('#hd').css('z-index', '');
    });

    function setActiveFilterTab($activeTab) {
        $('#filterTabType, #filterTabPrice, #filterTabCategory')
            .removeClass('font-semibold text-gray-900 border-b-2 border-gray-900')
            .addClass('text-gray-400');

        $activeTab
            .removeClass('text-gray-400')
            .addClass('font-semibold text-gray-900 border-b-2 border-gray-900');
    }

    // 필터 탭 선택 시 탭 밑줄 스타일 적용
    $('#filterTabType').on('click', function() {
        setActiveFilterTab($(this));
    });

    $('#filterTabPrice').on('click', function() {
        setActiveFilterTab($(this));
    });

    $('#filterTabCategory').on('click', function() {
        setActiveFilterTab($(this));
    });

    // 필터 탭 선택 시 패널 전환
    $('#filterTabType').on('click', function() {
        $('.filterDrawerPanel').addClass('hidden');
        $('#filterPanelType').removeClass('hidden');
    });

    $('#filterTabPrice').on('click', function() {
        $('.filterDrawerPanel').addClass('hidden');
        $('#filterPanelPrice').removeClass('hidden');
    });

    $('#filterTabCategory').on('click', function() {
        $('.filterDrawerPanel').addClass('hidden');
        $('#filterPanelCategory').removeClass('hidden');
    });
</script>
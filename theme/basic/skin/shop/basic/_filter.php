<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가
?>

<!-- PC filter 시작 -->
<form id="filterPcForm" method="get" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>"
    class="p-4 text-sm">
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

    <div class="flex items-center justify-between border-b pb-4">
        <h2 class="text-base font-bold">필터</h2>
        <button type="button" id="btn_filter_reset"
            class="inline-flex items-center gap-1 text-xs border border-gray-300 rounded px-2 py-1 cursor-pointer hover:bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-rotate-ccw-icon lucide-rotate-ccw w-3 h-3">
                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                <path d="M3 3v5h5" />
            </svg>
            <span>초기화</span>
        </button>
    </div>

    <div>
        <div class="py-4 border-b border-gray-300">
            <button type="button" class="btn_filter_accordion flex items-center justify-between w-full mb-3"
                aria-expanded="true">
                <span class="font-semibold">종류</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="ico_filter_accordion lucide lucide-chevron-down-icon lucide-chevron-down w-4 h-4 transition-transform">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>

            <ul class="filter_accordion_panel space-y-3">
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

        <div class="py-4">
            <button type="button" class="btn_filter_accordion flex items-center justify-between w-full mb-3"
                aria-expanded="true">
                <span class="font-semibold">가격</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="ico_filter_accordion lucide lucide-chevron-down-icon lucide-chevron-down w-4 h-4 transition-transform">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>

            <div class="filter_accordion_panel">
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
                    <button type="submit" id="btn_filter_apply" class="border border-gray-300 rounded p-2 bg-gray-300">
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
    </div>
</form>

<script>
    $(function() {
        // 체크박스와 라디오 상태 복원
        $('#filterPcForm input[type="checkbox"], #filterPcForm input[type="radio"], #filterDrawerForm input[type="checkbox"], #filterDrawerForm input[type="radio"]').each(function() {
            const url = new URL(window.location.href);
            const inputName = $(this).attr('name');
            const inputValue = $(this).val();
            const selectedValues = url.searchParams.getAll(inputName);

            $(this).prop(
                'checked',
                selectedValues.includes(inputValue)
            );
        });

        // 직접 입력 가격 복원
        $('#filterPcForm input[name="price_min"], #filterPcForm input[name="price_max"], #filterDrawerForm input[name="price_min"], #filterDrawerForm input[name="price_max"]').each(function() {
            const url = new URL(window.location.href);
            const inputName = $(this).attr('name');
            const inputValue = url.searchParams.get(inputName);

            $(this).val(inputValue || '');
        });

        // form 제출 시 빈 가격 input 제외
        $('#filterPcForm, #filterDrawerForm').on('submit', function() {
            $(this)
                .find('input[name="price_min"], input[name="price_max"]')
                .each(function() {
                    const priceValue = $(this).val().trim();

                    if (!priceValue) {
                        $(this).prop('disabled', true);
                    }
                });
        });

        // 수동 가격 입력 시 기존 가격대 radio 해제
        $('#filterPcForm input[name="price_min"], #filterPcForm input[name="price_max"], #filterDrawerForm input[name="price_min"], #filterDrawerForm input[name="price_max"]').on('input', function() {
            const priceValue = $(this).val().trim();

            if (priceValue) {
                $(this).closest('form').find('input[name="price_range"]')
                    .prop('checked', false);
            }
        });

        // 가격대 radio 선택 시 수동 가격 입력값 제거
        $('#filterPcForm input[name="price_range"], #filterDrawerForm input[name="price_range"]').on('change', function() {
            $(this).closest('form').find('input[name="price_min"], input[name="price_max"]')
                .val('');
        });

        // 초기화 버튼
        $("#btn_filter_reset, #filterDrawerReset").on("click", function() {
            const $form = $(this).closest('form');

            // form 안에 모든 입력값을 처음 상태로 되돌림
            $form[0].reset();

            // hidden input 을 제외한 input은 전송되지 않음
            $form
                .find('input:not([type="hidden"])')
                .prop('disabled', true);

            // 현재 페이지 정보가 들어 있는 hidden input만 form 제출
            $form[0].submit();
        });

        // 필터 별 아코디언 토글
        $(".btn_filter_accordion").on("click", function() {
            const $button = $(this);
            const $panel = $button.next(".filter_accordion_panel")
            const isExpanded = $button.attr("aria-expanded") === "true";

            // 애니메이션 겹치는 것 감소, 200ms 동안 열리고 닫힘 
            $panel.stop().slideToggle(200);
            $button.attr("aria-expanded", !isExpanded);
            $button.find(".ico_filter_accordion").toggleClass("rotate-180");
        })
    });
</script>
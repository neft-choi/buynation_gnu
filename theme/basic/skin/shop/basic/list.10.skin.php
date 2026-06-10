<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_SHOP_SKIN_URL . '/style.css">', 0);

// 장바구니 또는 위시리스트 ajax 스크립트
add_javascript('<script src="' . G5_THEME_JS_URL . '/theme.shop.list.js"></script>', 10);
?>

<div class="flex gap-6 items-start p-4">
    <!-- filter 시작 -->
    <div class="hidden pc:block w-60 p-4 border border-gray-300 rounded text-sm">
        <div class="flex items-center justify-between border-b pb-4 text-base">
            <p class="font-bold">필터</p>
            <button type="button" id="btn_filter_reset" class="inline-flex items-center gap-1 text-xs border border-gray-300 rounded px-2 py-1 cursor-pointer hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw-icon lucide-rotate-ccw w-3 h-3">
                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                    <path d="M3 3v5h5" />
                </svg>
                <span>초기화</span>
            </button>
        </div>

        <div>
            <div class="py-4 border-b border-gray-300">
                <button type="button" class="btn_filter_accordion flex items-center justify-between w-full mb-3" aria-expanded="true">
                    <span class="font-semibold">배송유형</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ico_filter_accordion lucide lucide-chevron-down-icon lucide-chevron-down w-4 h-4 transition-transform">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>

                <ul class="filter_accordion_panel space-y-3">
                    <li>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <div class="relative w-5 h-5">
                                <input type="checkbox" name="delivery_type[]" value="today_start" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900 checked:bg-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-gray-300 peer-checked:text-white pointer-events-none">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                            </div>
                            <span>오늘출발</span>
                        </label>
                    </li>
                    <li>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <div class="relative w-5 h-5">
                                <input type="checkbox" name="delivery_type[]" value="gift_wrap" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900 checked:bg-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-gray-300 peer-checked:text-white pointer-events-none">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                            </div>
                            <span>선물포장</span>
                        </label>
                    </li>
                    <li>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <div class="relative w-5 h-5">
                                <input type="checkbox" name="delivery_type[]" value="quick_delivery" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900 checked:bg-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-gray-300 peer-checked:text-white pointer-events-none">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                            </div>
                            <span>퀵배송</span>
                        </label>
                    </li>
                </ul>
            </div>

            <div class="py-4 border-b border-gray-300">
                <button type="button" class="btn_filter_accordion flex items-center justify-between w-full mb-3" aria-expanded="true">
                    <span class="font-semibold">혜택</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ico_filter_accordion lucide lucide-chevron-down-icon lucide-chevron-down w-4 h-4 transition-transform">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>

                <ul class="filter_accordion_panel space-y-3">
                    <li>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <div class="relative w-5 h-5">
                                <input type="checkbox" name="benefit_type[]" value="free_shipping" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900 checked:bg-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-gray-300 peer-checked:text-white pointer-events-none">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                            </div>
                            <span>무료배송</span>
                        </label>
                    </li>
                    <li>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <div class="relative w-5 h-5">
                                <input type="checkbox" name="benefit_type[]" value="special_price" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900 checked:bg-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-gray-300 peer-checked:text-white pointer-events-none">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                            </div>
                            <span>특가상품</span>
                        </label>
                    </li>
                    <li>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <div class="relative w-5 h-5">
                                <input type="checkbox" name="benefit_type[]" value="coupon" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900 checked:bg-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-gray-300 peer-checked:text-white pointer-events-none">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                            </div>
                            <span>쿠폰</span>
                        </label>
                    </li>
                    <li>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <div class="relative w-5 h-5">
                                <input type="checkbox" name="benefit_type[]" value="event" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900 checked:bg-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-gray-300 peer-checked:text-white pointer-events-none">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                            </div>
                            <span>이벤트</span>
                        </label>
                    </li>
                </ul>
            </div>

            <div class="py-4">
                <button type="button" class="btn_filter_accordion flex items-center justify-between w-full mb-3" aria-expanded="true">
                    <span class="font-semibold">가격</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ico_filter_accordion lucide lucide-chevron-down-icon lucide-chevron-down w-4 h-4 transition-transform">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>

                <div class="filter_accordion_panel">
                    <ul class="space-y-3 mb-3">
                        <li>
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <div class="relative w-5 h-5">
                                    <input type="radio" name="price_range" value="0_30000" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900">
                                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 bg-gray-900 rounded-full opacity-0 peer-checked:opacity-100 pointer-events-none"></div>
                                </div>
                                <span>~3만원</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <div class="relative w-5 h-5">
                                    <input type="radio" name="price_range" value="30000_50000" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900">
                                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 bg-gray-900 rounded-full opacity-0 peer-checked:opacity-100 pointer-events-none"></div>
                                </div>
                                <span>3만원~5만원</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <div class="relative w-5 h-5">
                                    <input type="radio" name="price_range" value="50000_100000" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900">
                                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 bg-gray-900 rounded-full opacity-0 peer-checked:opacity-100 pointer-events-none"></div>
                                </div>
                                <span>5만원~10만원</span>
                            </label>
                        </li>
                        <li>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <div class="relative w-5 h-5">
                                    <input type="radio" name="price_range" value="100000_up" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900">
                                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 bg-gray-900 rounded-full opacity-0 peer-checked:opacity-100 pointer-events-none"></div>
                                </div>
                                <span>10만원 이상</span>
                            </label>
                        </li>
                    </ul>

                    <div class="flex items-center gap-1">
                        <input type="text" name="price_min" class="w-full border border-gray-300 !px-2 !py-1 text-sm placeholder:text-right" placeholder="원">
                        <span>~</span>
                        <input type="text" name="price_max" class="w-full border border-gray-300 !px-2 !py-1 text-sm placeholder:text-right" placeholder="원">
                        <button type="button" id="btn_price_apply" class="border border-gray-300 rounded p-2 bg-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search w-3.5 h-3.5">
                                <path d="m21 21-4.34-4.34" />
                                <circle cx="11" cy="11" r="8" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            // 초기화 버튼
            $("#btn_filter_reset").on("click", function() {
                // 체크박스, 라디오, 금액 직접 입력 필터 전부 초기화
                $('input[type="checkbox"], input[type="radio"]').prop("checked", false);
                $('input[name="price_min"], input[name="price_max"]').val("");
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


    <!-- 상품진열 10 시작 { -->
    <div class="flex-1">
        <?php
        $i = 0;

        $this->view_star = (method_exists($this, 'view_star')) ? $this->view_star : true;
        ?>
        <?php foreach ((array) $list as $row) { ?>
            <?php
            if (empty($row)) {
                continue;
            }

            $item_link_href = shop_item_url($row['it_id']);
            $is_soldout = is_soldout($row['it_id'], true);
            $star_score = $row['it_use_avg'] ? (int) get_star($row['it_use_avg']) : '';
            $star_avg = $row['it_use_avg'] ? number_format((float) $row['it_use_avg'], 1) : '';
            $use_cnt = isset($row['it_use_cnt']) ? (int) $row['it_use_cnt'] : 0;
            $it_price = (int) $row['it_price'];
            $it_cust_price = (int) $row['it_cust_price'];
            $it_basic = stripslashes($row['it_basic']);
            $discount_percent = 0;

            if ($it_cust_price > 0 && $it_cust_price > $it_price) {
                $discount_percent = (int) round((($it_cust_price - $it_price) / $it_cust_price) * 100);
            }

            $list_mod = $this->list_mod; // 분류관리에서 1줄당 이미지 수 값 또는 파일에서 지정한 가로 수

            $classes = array();
            // $classes[] = 'col-row-' . $list_mod;
            $classes[] = 'grid-cols-' . $list_mod;

            // if ($i && ($i % $list_mod == 0)) {
            //     $classes[] = 'row-clear';
            // }

            $i++;
            ?>
            <?php if ($i === 1) { ?>
                <ul class="<?= $this->css ? $this->css : 'sct sct_10 lists-row grid ' . implode(' ', $classes) . ' pc:grid-cols-4 gap-4 pc:!m-0' ?>">
                <?php } ?>
                <li class="sct_li" data-css="nocss" style="height:auto">
                    <article class="space-y-2">
                        <div class="relative rounded-lg overflow-hidden">
                            <?php if ($this->href) { ?>
                                <a href="<?= $item_link_href ?>">
                                <?php } ?>

                                <?php if ($this->view_it_img) { ?>
                                    <?= get_it_image($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name'])) ?>
                                <?php } ?>

                                <?php if ($this->href) { ?>
                                </a>
                            <?php } ?>

                            <?php if (!$is_soldout) { ?>
                                <div class="sct_btn list-10-btn">
                                    <button type="button" class="btn_cart sct_cart" data-it_id="<?= $row['it_id'] ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i> 장바구니</button>
                                </div>
                            <?php } ?>



                            <div class="cart-layer"></div>

                            <?php if ($this->view_it_icon && $is_soldout) { ?>
                                <span class="shop_icon_soldout"><span class="soldout_txt">SOLD OUT</span></span>
                            <?php } ?>
                        </div>
                        <div class="flex w-full gap-1">
                            <button type="button" class="btn_wish  sct_btn flex items-center justify-center w-full border border-gray-300 rounded-lg px-4 py-2" data-it_id="<?= $row['it_id'] ?>" aria-label="위시리스트">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart" aria-hidden="true">
                                    <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                </svg>
                            </button>
                            <button type="button" class="btn_cart sct_btn flex items-center justify-center w-full border border-gray-300 rounded-lg px-4 py-2" data-it_id="<?= $row['it_id'] ?>" aria-label="장바구니">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart-icon lucide-shopping-cart">
                                    <circle cx="8" cy="21" r="1" />
                                    <circle cx="19" cy="21" r="1" />
                                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                                </svg>
                            </button>
                        </div>

                        <div class="sct_ct_wrap space-y-2">
                            <?php if ($this->view_it_id) { ?>
                                <div class="sct_id">&lt;<?= stripslashes($row['it_id']) ?>&gt;</div>
                            <?php } ?>

                            <?php if ($this->href) { ?>
                                <h3 class="font-medium line-clamp-2"><a href="<?= $item_link_href ?>">
                                    <?php } ?>

                                    <?php if ($this->view_it_name) { ?>
                                        <?= stripslashes($row['it_name']) ?>
                                    <?php } ?>

                                    <?php if ($this->href) { ?>
                                    </a></h3>
                            <?php } ?>

                            <div class="sct_bottom">
                                <?php if ($this->view_it_cust_price || $this->view_it_price) { ?>
                                    <div class="flex flex-col">
                                        <?php if ($this->view_it_cust_price && $it_cust_price > 0 && $it_cust_price > $it_price) { ?>
                                            <span class="text-base text-gray-400 line-through"><?= display_price($it_cust_price, $row['it_tel_inq']) ?></span>
                                        <?php } ?>

                                        <div class="flex gap-1 items-center">
                                            <?php if ($discount_percent > 0) { ?>
                                                <span class="text-lg text-red-500 font-bold"><?= $discount_percent ?>%</span>
                                            <?php } ?>

                                            <?php if ($this->view_it_price) { ?>
                                                <span class="text-lg font-bold"><?= display_price(get_price($row), $row['it_tel_inq']) ?></span>
                                            <?php } ?>
                                        </div>

                                        <div class="flex gap-2 text-gray-900 text-sm">
                                            <span class="flex gap-1 items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star-icon lucide-star">
                                                    <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z" />
                                                </svg>
                                                <?= $star_avg !== '' ? $star_avg : '0.0' ?>
                                            </span>
                                            <span class="text-gray-300">|</span>
                                            <span class="text-gray-400"><?= $use_cnt ?>건</span>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                            <?php if ($this->view_it_icon) { ?>
                                <div class="sit_icon_li"><?= item_icon($row) ?></div>
                            <?php } ?>
                        </div>
                    </article>
                </li>
            <?php } ?>

            <?php if ($i >= 1) { ?>
                </ul>
            <?php } ?>

            <?php if ($i === 0) { ?>
                <p class="sct_noitem">등록된 상품이 없습니다.</p>
            <?php } ?>
    </div>
    <!-- } 상품진열 10 끝 -->
</div>

<script>
    //SNS 공유
    $(function() {
        $(".btn_share").on("click", function() {
            $(this).parent("div").children(".sct_sns_wrap").show();
        });
        $('.sct_sns_bg, .sct_sns_cls').click(function() {
            $('.sct_sns_wrap').hide();
        });
    });
</script>
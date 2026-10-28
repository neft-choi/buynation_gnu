<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가

global $ca;

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_SHOP_SKIN_URL . '/style.css">', 0);

// 장바구니 또는 위시리스트 ajax 스크립트
add_javascript('<script src="' . G5_THEME_JS_URL . '/theme.shop.list.js"></script>', 10);
?>

<div class="flex gap-6 items-start p-4">
    <!-- 카테고리 필터 PC 사이드바 -->
    <div class="hidden pc:block w-60 border border-gray-300 rounded">
        <?php if (basename($_SERVER['SCRIPT_NAME']) !== 'listtype.php') { ?>
            <?php include __DIR__ . '/_category.php'; ?>
        <?php } ?>
        <?php include __DIR__ . '/_filter.php'; ?>
    </div>
    <?php include __DIR__ . '/_filter_drawer.php'; ?>

    <!-- 상품진열 10 시작 { -->
    <div class="flex-1">
        <h2 class="text-2xl pc:text-3xl font-bold mb-4"><?php echo get_text($ca['ca_name']); ?></h2>

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
                <ul
                    class="<?= $this->css ? $this->css : 'sct sct_10 lists-row grid ' . implode(' ', $classes) . ' pc:grid-cols-3 gap-4 pc:!m-0' ?>">
                <?php } ?>
                <li class="sct_li" data-css="nocss" style="height:auto">
                    <article class="space-y-2">
                        <div class="relative z-0 flex flex-col items-center justify-center bg-[#EEEEEE] rounded-lg overflow-hidden" style="aspect-ratio: <?= (int) $this->img_width ?> / <?= (int) $this->img_height ?>;">
                            <?php if ($this->href) { ?>
                                <a href="<?= $item_link_href ?>">
                                <?php } ?>

                                <?php if ($this->view_it_img) { ?>
                                    <?= get_it_image($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name'])) ?>
                                <?php } ?>

                                <?php if ($this->href) { ?>
                                </a>
                            <?php } ?>

                            <?php if ($this->view_it_icon && $is_soldout) { ?>
                                <span class="shop_icon_soldout"><span class="soldout_txt">SOLD OUT</span></span>
                            <?php } ?>

                            <button type="button"
                                class="btn_wish sct_btn absolute top-2 right-2 z-20 flex items-center justify-center w-8 h-8 border border-gray-300 rounded-full bg-white/90 shadow-sm cursor-pointer"
                                data-it_id="<?= $row['it_id'] ?>" aria-label="위시리스트">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-heart w-5 h-5 text-black/70" aria-hidden="true">
                                    <path
                                        d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                </svg>
                            </button>

                            <?php if ($row['it_type1']) { ?>
                                <p class="absolute inset-x-0 bottom-0 z-10 bg-amber-100/60 py-2 text-center text-xs font-semibold text-amber-700">
                                    마감까지 <span class="ml-1">01:23:45</span>
                                </p>
                            <?php } ?>
                        </div>

                        <div class="sct_ct_wrap space-y-1">
                            <?php
                            $item_type_labels = array();

                            if ($row['it_type1']) {
                                $item_type_labels[] = '히트 상품';
                            }
                            if ($row['it_type2']) {
                                $item_type_labels[] = '추천 상품';
                            }
                            if ($row['it_type3']) {
                                $item_type_labels[] = '신상품';
                            }
                            if ($row['it_type4']) {
                                $item_type_labels[] = '인기 상품';
                            }
                            if ($row['it_type5']) {
                                $item_type_labels[] = '할인 상품';
                            }

                            $item_type_text = $item_type_labels ? implode(' · ', $item_type_labels) : '일반 상품';
                            ?>

                            <?php if ($this->view_it_id) { ?>
                                <div class="sct_id">&lt;<?= stripslashes($row['it_id']) ?>&gt;</div>
                            <?php } ?>

                            <?php if ($this->href) { ?>
                                <h3 class="text-base font-semibold line-clamp-2">
                                    <a href="<?= $item_link_href ?>">
                                    <?php } ?>

                                    <?php if ($this->view_it_name) { ?>
                                        <?= stripslashes($row['it_name']) ?>
                                    <?php } ?>

                                    <?php if ($this->href) { ?>
                                    </a>
                                </h3>
                            <?php } ?>

                            <div class="sct_bottom">
                                <?php if ($this->view_it_cust_price || $this->view_it_price) { ?>
                                    <div class="flex flex-col gap-1">
                                        <?php if ($this->view_it_cust_price && $it_cust_price > 0 && $it_cust_price > $it_price) { ?>
                                            <span
                                                class="text-sm text-gray-400 line-through"><?= display_price($it_cust_price, $row['it_tel_inq']) ?></span>
                                        <?php } ?>

                                        <div class="flex gap-1 items-center">
                                            <?php if ($discount_percent > 0) { ?>
                                                <span class="text-lg text-red-500 font-bold"><?= $discount_percent ?>%</span>
                                            <?php } ?>

                                            <?php if ($this->view_it_price) { ?>
                                                <span
                                                    class="text-lg font-bold"><?= display_price(get_price($row), $row['it_tel_inq']) ?></span>
                                            <?php } ?>
                                        </div>

                                        <p class="text-sm font-semibold text-orange-500">예상 기여금 1,000원</p>

                                        <div class="flex gap-1 text-gray-900 text-sm">
                                            <span class="flex gap-1 items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    viewBox="0 0 24 24" fill="currentColor" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-star-icon lucide-star">
                                                    <path
                                                        d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z" />
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
                                <div class="sit_icon_li">
                                    <span class="inline-flex rounded-md px-2 py-1 text-xs font-semibold <?= $item_type_labels ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-500' ?>">
                                        <?= $item_type_text ?>
                                    </span>
                                </div>
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
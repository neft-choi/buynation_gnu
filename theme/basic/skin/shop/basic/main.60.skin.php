<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_SHOP_SKIN_URL . '/style.css">', 0);
add_javascript('<script src="' . G5_THEME_JS_URL . '/theme.shop.list.js"></script>', 10);
?>

<!-- 상품진열 60 시작 { -->
<?php
$i = 0;
foreach ((array) $list as $row) {
    if (empty($row)) {
        continue;
    }

    $i++;
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

    if ($i == 1) {
        if ($this->css) {
?>
            <ul class="<?= $this->css ?>">
            <?php
        } else {
            ?>
                <ul class="sct owl-carousel js-shop-slider" data-items="1.22" data-margin="12" data-stage-padding="12">
            <?php
        }
    }
            ?>
            <li class="sct_li">
                <article class="space-y-2">
                    <div class="relative rounded-lg overflow-hidden">
                        <a href="<?= $item_link_href ?>">
                            <?= get_it_image($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name'])) ?>
                        </a>

                        <!-- <?php if ($discount_percent > 0) { ?>
                            <span class="badge ethical">착한소비</span>
                        <?php } ?> -->

                        <?php if ($is_soldout && $this->view_it_icon) { ?>
                            <span class="shop_icon_soldout h160"><span class="soldout_txt">SOLD OUT</span></span>
                        <?php } ?>

                        <div class="absolute inset-x-0 bottom-0 z-20 bg-black/60 p-2 text-center text-sm font-semibold text-gray-200">
                            기부금 <?= number_format($it_price) ?>원 ~
                        </div>
                    </div>

                    <h3 class="font-medium line-clamp-2"><a href="<?= $item_link_href ?>"><?= stripslashes($row['it_name']) ?></a></h3>
                    <?php if ($this->view_it_id) { ?>
                        <div class="best_id">&lt;<?= stripslashes($row['it_id']) ?>&gt;</div>
                    <?php } ?>
                    <?php
                    // if ($this->view_it_basic && $row['it_basic']) {
                    //     echo '<p class="best_basic">' . $it_basic . '</p>';
                    // }
                    ?>
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
                                    </svg><?= $star_avg !== '' ? $star_avg : '0.0' ?></span>
                                <span class="text-gray-300">|</span>
                                <span class="text-gray-400"><?= $use_cnt ?>건</span>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($this->view_it_icon) { ?>
                        <div class="mt-2 text-xs font-semibold text-gray-900">
                            <?php if (!empty($row['it_type1'])) { ?>
                                <span class="inline-block px-2 py-1 border border-gray-600 rounded-md bg-white">품절임박</span>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </article>
            </li>
        <?php
    }

    if ($i >= 1) {
        ?>
                </ul>
            <?php
        }

        if ($i == 0) {
            ?>
                <p class="sct_noitem">등록된 상품이 없습니다.</p>
            <?php
        }
            ?>
            <!-- } 상품진열 60 끝 -->
<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_SHOP_SKIN_URL . '/style.css">', 0);
add_javascript('<script src="' . G5_THEME_JS_URL . '/theme.shop.list.js"></script>', 10);
?>

<!-- 상품진열 80 시작 { -->
<?php
$i = 0;
foreach ((array) $list as $row) {
    if (empty($row)) {
        continue;
    }

    if ($i === 0) {
        if ($this->css) {
            echo '<ul class="' . $this->css . '">' . "\n";
        } else {
            echo "<ul class=\"py-4 space-y-4\">\n";
        }
    }

    $i++;
    $item_link_href = shop_item_url($row['it_id']);
    $is_soldout = is_soldout($row['it_id'], true);
    $star_avg = $row['it_use_avg'] ? number_format((float) $row['it_use_avg'], 1) : '';
    $use_cnt = isset($row['it_use_cnt']) ? (int) $row['it_use_cnt'] : 0;
    $it_price = (int) $row['it_price'];
    $it_cust_price = (int) $row['it_cust_price'];
    $discount_percent = 0;

    if ($it_cust_price > 0 && $it_cust_price > $it_price) {
        $discount_percent = (int) round((($it_cust_price - $it_price) / $it_cust_price) * 100);
    }
?>
    <li class="sct_li flex items-start gap-2">
        <div class="w-10 shrink-0 pt-1 text-center text-4xl font-extrabold leading-none"><?php echo $i; ?></div>

        <div class="w-[132px] shrink-0">
            <div class="relative overflow-hidden rounded-md">
                <a class="block" href="<?php echo $item_link_href; ?>">
                    <?php echo get_it_image($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name'])); ?>
                </a>

                <!-- <?php if ($discount_percent > 0) { ?>
                    <span class="badge ethical">착한소비</span>
                <?php } ?> -->

                <?php if ($is_soldout && $this->view_it_icon) { ?>
                    <span class="shop_icon_soldout h160"><span class="soldout_txt">SOLD OUT</span></span>
                <?php } ?>

                <div class="cart-layer"></div>
            </div>
        </div>

        <div class="min-w-0 flex-1">
            <h3 class="font-medium line-clamp-2">
                <a href="<?php echo $item_link_href; ?>"><?php echo stripslashes($row['it_name']); ?></a>
            </h3>

            <?php if ($this->view_it_id) { ?>
                <div class="best_id">&lt;<?php echo stripslashes($row['it_id']); ?>&gt;</div>
            <?php } ?>

            <?php if ($this->view_it_cust_price || $this->view_it_price) { ?>
                <div class="flex flex-col">
                    <?php if ($this->view_it_cust_price && $it_cust_price > 0 && $it_cust_price > $it_price) { ?>
                        <span class="text-base text-gray-400 line-through"><?php echo display_price($it_cust_price, $row['it_tel_inq']); ?></span>
                    <?php } ?>

                    <div class="flex gap-1 items-center">
                        <?php if ($discount_percent > 0) { ?>
                            <span class="text-lg text-red-500 font-bold"><?php echo $discount_percent; ?>%</span>
                        <?php } ?>
                        <?php if ($this->view_it_price) { ?>
                            <span class="text-lg font-bold"><?php echo display_price(get_price($row), $row['it_tel_inq']); ?></span>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

            <div class="mt-2 flex gap-2">
                <button type="button" class="btn_wish sct_btn flex flex-1 items-center justify-center px-4 py-2 rounded-lg border border-[#ddd] bg-white p-0 text-[#333]" data-it_id="<?php echo $row['it_id']; ?>" aria-label="위시리스트">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart" aria-hidden="true">
                        <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                    </svg>
                </button>
                <button type="button" class="btn_cart sct_btn flex flex-1 items-center justify-center px-4 py-2 rounded-lg border border-[#ddd] bg-white p-0 text-[#333]" data-it_id="<?php echo $row['it_id']; ?>" aria-label="장바구니">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart-icon lucide-shopping-cart">
                        <circle cx="8" cy="21" r="1" />
                        <circle cx="19" cy="21" r="1" />
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                    </svg>
                </button>
            </div>
        </div>
    </li>
<?php
}

if ($i >= 1) {
    echo "</ul>\n";
} else {
    echo "<p class=\"sct_noitem\">등록된 상품이 없습니다.</p>\n";
}
?>
<!-- } 상품진열 80 끝 -->
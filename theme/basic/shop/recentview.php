<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

add_javascript('<script src="' . G5_THEME_JS_URL . '/theme.shop.list.js"></script>', 10);

$g5['title'] = '최근 본 상품';
include_once('./_head.php');

$tv_datas = get_view_today_items(true);
$view_date = str_replace('-', '.', G5_TIME_YMD);
?>

<style>
    #wrapper_title {
        display: none;
    }
</style>

<div class="!m-0">
    <section class="mx-auto w-full max-w-full px-4 py-4">
        <?php if ($tv_datas) { ?>
            <div class="mt-4">
                <p class="text-sm font-semibold text-gray-900"><?php echo $view_date; ?></p>

                <ul class="mt-4 flex flex-col gap-3">
                    <?php foreach ($tv_datas as $row) {
                        if (!$row['it_id']) {
                            continue;
                        }

                        $it_id = $row['it_id'];
                        $it_name = get_text($row['it_name']);
                        $item_url = shop_item_url($it_id);
                        $thumb = get_it_image($it_id, 80, 100, false, '', $it_name);
                        $it_price = (int) get_price($row);
                        $it_cust_price = isset($row['it_cust_price']) ? (int) $row['it_cust_price'] : 0;
                        $has_discount = ($it_cust_price > 0 && $it_cust_price > $it_price);
                        $sale_rate = 0;

                        if ($has_discount) {
                            $sale_rate = (int) ((($it_cust_price - $it_price) * 100) / $it_cust_price);
                        }
                    ?>
                        <li class="sct_li relative grid grid-cols-[80px_1fr_48px] items-center gap-4">
                            <a href="<?php echo $item_url; ?>" class="block h-25 w-20 overflow-hidden rounded-md bg-zinc-200">
                                <?php echo $thumb; ?>
                            </a>

                            <div>
                                <a href="<?php echo $item_url; ?>" class="line-clamp-2 text-sm text-gray-900"><?php echo $it_name; ?></a>

                                <?php if ($has_discount) { ?>
                                    <p class="text-sm text-zinc-400 line-through"><?php echo number_format($it_cust_price); ?>원</p>
                                <?php } ?>

                                <p class="mt-2 flex items-center gap-1 text-base font-bold text-zinc-900">
                                    <?php if ($sale_rate > 0) { ?>
                                        <span class="text-red-500"><?php echo $sale_rate; ?>%</span>
                                    <?php } ?>
                                    <span><?php echo number_format($it_price); ?>원</span>
                                </p>

                            </div>

                            <button type="button" class="btn_cart inline-flex h-11 w-11 items-center justify-center rounded-md border border-zinc-300 text-zinc-700" data-it_id="<?php echo $it_id; ?>" aria-label="장바구니 담기">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="8" cy="21" r="1" />
                                    <circle cx="19" cy="21" r="1" />
                                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.72a2 2 0 0 0 2-1.58L22 6H6" />
                                </svg>
                            </button>
                            <div class="cart-layer"></div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        <?php } else { ?>
            <div class="h-[calc(100dvh-var(--bottom-nav-height)-56px)] flex items-center justify-center flex flex-col items-center justify-center gap-3 text-zinc-400">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert-icon lucide-circle-alert h-12 w-12">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" x2="12" y1="8" y2="12" />
                    <line x1="12" x2="12.01" y1="16" y2="16" />
                </svg>
                <span>최근 본 상품이 없습니다</span>
            </div>
        <?php } ?>
    </section>
</div>

<?php
include_once('./_tail.php');

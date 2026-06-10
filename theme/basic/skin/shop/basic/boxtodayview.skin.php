<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$tv_datas = get_view_today_items(true);

$tv_div['top'] = 0;
$tv_div['img_width'] = 80;
$tv_div['img_height'] = 102;
$tv_div['img_length'] = 10; // 한번에 보여줄 이미지 수

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_SHOP_SKIN_URL . '/style.css">', 0);


$tv_items = array();
$tv_tot_count = 0;
$k = 0;

foreach ($tv_datas as $rowx) {
    if (!$rowx['it_id']) {
        continue;
    }

    $tv_it_id = $rowx['it_id'];

    if ($tv_tot_count % $tv_div['img_length'] == 0) {
        $k++;
    }

    $it_name = get_text($rowx['it_name']);
    $it_price = (int) get_price($rowx);
    $it_cust_price = isset($rowx['it_cust_price']) ? (int) $rowx['it_cust_price'] : 0;
    $has_discount = false;
    $sale_rate = 0;

    if ($it_cust_price > 0 && $it_cust_price > $it_price) {
        $has_discount = true;
        $sale_rate = (int) ((($it_cust_price - $it_price) * 100) / $it_cust_price);
    }

    $tv_items[] = array(
        'it_id' => $tv_it_id,
        'group' => $k,
        'img' => get_it_image($tv_it_id, $tv_div['img_width'], $tv_div['img_height'], $tv_it_id, '', $it_name),
        'url' => shop_item_url($tv_it_id),
        'name' => cut_str($it_name, 10, ''),
        'price' => $it_price,
        'cust_price' => $it_cust_price,
        'has_discount' => $has_discount,
        'sale_rate' => $sale_rate,
    );

    $tv_tot_count++;
}

$tv_page_count = 0;
if ($tv_tot_count > 0) {
    $tv_page_count = (int) ceil($tv_tot_count / $tv_div['img_length']);
}
?>

<!-- 오늘 본 상품 시작 { -->
<div id="stv">

    <?php if ($tv_items) { // 오늘 본 상품이 1개라도 있을 때 
    ?>
        <ul id="stv_ul" class="space-y-3">
            <?php foreach ($tv_items as $item) { ?>
                <li class="stv_item sct_li c<?php echo $item['group']; ?> flex items-start gap-4">
                    <div class="prd_img rounded overflow-hidden">
                        <?php echo $item['img']; ?>
                    </div>

                    <div class="prd_cnt flex-1 min-w-0 text-sm">
                        <a href="<?php echo $item['url']; ?>" class="prd_name line-clamp-3"><?php echo $item['name']; ?></a>

                        <?php if ($item['has_discount']) { ?>
                            <p class="text-gray-400 line-through">
                                <?php echo number_format($item['cust_price']); ?>원
                            </p>
                        <?php } ?>

                        <div class="flex items-center gap-1">
                            <?php if ($item['sale_rate'] > 0) { ?>
                                <span class="text-base font-bold text-red-500">
                                    <?php echo $item['sale_rate']; ?>%
                                </span>
                            <?php } ?>

                            <span class="text-base font-bold text-gray-900">
                                <?php echo number_format($item['price']); ?>원
                            </span>
                        </div>
                    </div>

                    <button
                        type="button"
                        data-it_id="<?php echo $item['it_id']; ?>"
                        class="btn_cart inline-flex items-center justify-center w-11 h-11 rounded border border-zinc-300 text-gray-700 ml-1 shrink-0"
                        aria-label="장바구니 담기">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                            <circle cx="8" cy="21" r="1"></circle>
                            <circle cx="19" cy="21" r="1"></circle>
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                        </svg>
                    </button>

                    <div class="cart-layer"></div>
                </li>
            <?php } ?>
        </ul>

        <script>
            $(function() {
                var itemQty = <?php echo $tv_tot_count; ?>; // 총 아이템 수량
                var itemShow = <?php echo $tv_div['img_length']; ?>; // 한번에 보여줄 아이템 수량
                if (itemQty > itemShow) {
                    $('#stv_btn').append('<button type="button" id="up"><i class="fa fa-angle-left" aria-hidden="true"></i> 이전</button><button type="button" id="down">다음 <i class="fa fa-angle-right" aria-hidden="true"></i></button>');
                }
                var Flag = 1; // 페이지
                var EOFlag = <?php echo $tv_page_count; ?>; // 전체 페이지 수
                $('.c' + Flag).css('display', 'flex');
                $('#stv_pg').text(Flag + '/' + EOFlag); // 페이지 초기 출력값
                $('#up').click(function() {
                    if (Flag == 1) {
                        alert('목록의 처음입니다.');
                    } else {
                        Flag--;
                        $('.c' + Flag).css('display', 'flex');
                        $('.c' + (Flag + 1)).css('display', 'none');
                    }
                    $('#stv_pg').text(Flag + '/' + EOFlag); // 페이지 값 재설정
                })
                $('#down').click(function() {
                    if (Flag == EOFlag) {
                        alert('더 이상 목록이 없습니다.');
                    } else {
                        Flag++;
                        $('.c' + Flag).css('display', 'flex');
                        $('.c' + (Flag - 1)).css('display', 'none');
                    }
                    $('#stv_pg').text(Flag + '/' + EOFlag); // 페이지 값 재설정
                });
            });
        </script>

    <?php } else { // 오늘 본 상품이 없을 때 
    ?>
        <div class="flex flex-col h- items-center justify-center text-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert-icon lucide-circle-alert w-12 h-12">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" x2="12" y1="8" y2="12" />
                <line x1="12" x2="12.01" y1="16" y2="16" />
            </svg>
            <p class="">최근 본 상품이 없습니다</p>
        </div>
    <?php } ?>
</div>

<script src="<?php echo G5_JS_URL ?>/scroll_oldie.js"></script>
<!-- } 오늘 본 상품 끝 -->
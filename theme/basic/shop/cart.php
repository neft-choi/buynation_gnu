<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

if (!isset($cart_action_url) || !$cart_action_url) {
    $cart_action_url = G5_SHOP_URL . '/cartupdate.php';
}

if (!isset($naverpay_button_js)) {
    $naverpay_button_js = '';
}

$g5['title'] = '장바구니';

// 헤더 분기
$shop_header_variant = 'cart';
include_once(G5_SHOP_PATH . '/_head.php');

$sql = " select a.ct_id,
                a.it_id,
                a.it_name,
                a.ct_price,
                a.ct_point,
                a.ct_qty,
                a.ct_status,
	                a.ct_send_cost,
	                a.it_sc_type,
	                b.ca_id,
	                b.it_cust_price,
	                b.it_maker
           from {$g5['g5_shop_cart_table']} a
           left join {$g5['g5_shop_item_table']} b on ( a.it_id = b.it_id )
          where a.od_id = '$s_cart_id' ";
$sql .= " group by a.it_id ";
$sql .= " order by a.ct_id ";
$result = sql_query($sql);
$cart_count = sql_num_rows($result);

// 장바구니 SQL 디버그 출력 스위치 임시 (?cart_debug=1)
$cart_debug = g5_debug_flag('cart_debug');
$cart_debug_rows = $cart_debug ? g5_sql_fetch_all($sql) : array();
?>

<?php if ($cart_debug) { ?>
    <script>
        console.table(<?php echo json_encode($cart_debug_rows, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
    </script>
<?php } ?>

<script src="<?php echo G5_JS_URL; ?>/shop.js?ver=<?php echo G5_JS_VER; ?>"></script>
<script src="<?php echo G5_JS_URL; ?>/shop.override.js?ver=<?php echo G5_JS_VER; ?>"></script>

<style>
    .chk_box input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        width: 1px;
        height: 1px;
        margin: 0;
        padding: 0;
    }

    .chk_box input[type="checkbox"]+label {
        padding: 0;
        display: inline-flex;
        align-items: center;
        cursor: pointer;
    }

    .chk_box input[type="checkbox"]+label span {
        position: relative;
        width: 22px;
        height: 22px;
        top: 0;
        left: 0;
        flex: 0 0 22px;
        border: 0;
        background-repeat: no-repeat;
        background-position: center;
        background-size: 22px 22px;
        background-image: url("data:image/svg+xml,%3Csvg width='22' height='22' viewBox='0 0 22 22' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='22' height='22' rx='11' fill='white'/%3E%3Crect x='0.75' y='0.75' width='20.5' height='20.5' rx='10.25' stroke='%238D8D8D' stroke-opacity='0.52' stroke-width='1.5'/%3E%3Cpath d='M15.0975 7.05801C15.4337 6.74377 15.961 6.76159 16.2753 7.09772C16.5895 7.43389 16.5717 7.96115 16.2356 8.27546L9.8189 14.2755C9.4986 14.5749 9.00118 14.5749 8.68088 14.2755L5.76421 11.5482C5.42809 11.2339 5.41028 10.7067 5.7245 10.3705C6.03881 10.0344 6.56606 10.0165 6.90223 10.3308L9.24924 12.5255L15.0975 7.05801Z' fill='%238D8D8D' fill-opacity='0.52'/%3E%3C/svg%3E");
    }

    .chk_box input[type="checkbox"]:checked+label span {
        border: 0;
        background-color: transparent;
        background-image: url("data:image/svg+xml,%3Csvg width='22' height='22' viewBox='0 0 22 22' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Crect width='22' height='22' rx='11' fill='%23393939'/%3E%3Cpath d='M15.0975 7.05801C15.4337 6.74377 15.961 6.76159 16.2753 7.09772C16.5895 7.43389 16.5717 7.96115 16.2356 8.27546L9.8189 14.2755C9.4986 14.5749 9.00118 14.5749 8.68088 14.2755L5.76421 11.5482C5.42809 11.2339 5.41028 10.7067 5.7245 10.3705C6.03881 10.0344 6.56606 10.0165 6.90223 10.3308L9.24924 12.5255L15.0975 7.05801Z' fill='white'/%3E%3C/svg%3E");
    }

    .chk_box input[type="checkbox"]:focus-visible+label span {
        outline: 2px solid #393939;
        outline-offset: 2px;
    }

    #wrapper_title {
        display: none;
    }
</style>

<div id="sod_bsk" class="cart-mobile-frame bg-[#FAFAFA] !m-0 <?php echo $cart_count ? 'pb-40' : 'pb-10'; ?>">
    <form name="frmcartlist" id="sod_bsk_list" class="2017_renewal_itemform space-y-4 p-4" method="post" action="<?php echo $cart_action_url; ?>">

        <?php if ($cart_count) { ?>
            <div id="sod_chk" class="chk_box cart-head-check flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="ct_all" value="1" id="ct_all" class="selec_chk" checked>
                    <label for="ct_all" class="flex items-center gap-2"><span></span>전체 선택</label>
                    <div><span id="checked_count"><?php echo (int)$cart_count; ?></span> / <span id="total_count"><?php echo (int)$cart_count; ?></span></div>
                </div>
                <button type="button" class="cursor-pointer hover:opacity-80" onclick="return form_check('seldelete');">선택삭제</button>
                <!-- <button type="button" onclick="return form_check('alldelete');" class="btn01">비우기(전체삭제)</button> -->
            </div>
        <?php } ?>

        <ul class="sod_list cart-card-list space-y-4">
            <?php
            $tot_point = 0;
            $tot_sell_price = 0;
            $tot_discount_price = 0;
            $grouped_cart = array();
            $item_index = 0;

            while ($row = sql_fetch_array($result)) {
                $sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((ct_price + io_price) * ct_qty))) as price,
                                SUM(ct_point * ct_qty) as point,
                                SUM(ct_qty) as qty
                           from {$g5['g5_shop_cart_table']}
                          where it_id = '{$row['it_id']}'
                            and od_id = '$s_cart_id' ";
                $sum = sql_fetch($sql);

                if ($item_index == 0) {
                    $continue_ca_id = $row['ca_id'];
                }

                $a1 = '<a href="' . shop_item_url($row['it_id']) . '"><strong>';
                $a2 = '</strong></a>';

                // 상품 이미지 썸네일
                $image = get_it_image($row['it_id'], 72, 72);

                $it_name = $a1 . stripslashes(string: $row['it_name']) . $a2;
                $it_options = print_item_options($row['it_id'], $s_cart_id);
                $mod_options = '';
                if ($it_options) {
                    $mod_options = '<button type="button" id="mod_opt_' . $row['it_id'] . '" class="mod_btn mod_options">선택사항수정</button>';
                }

                switch ($row['ct_send_cost']) {
                    case 1:
                        $ct_send_cost = '착불';
                        break;
                    case 2:
                        $ct_send_cost = '무료';
                        break;
                    default:
                        $ct_send_cost = '선불';
                        break;
                }

                if ($row['it_sc_type'] == 2) {
                    $sendcost = get_item_sendcost($row['it_id'], $sum['price'], $sum['qty'], $s_cart_id);
                    if ($sendcost == 0) {
                        $ct_send_cost = '무료';
                    }
                }

                $point = $sum['point'];
                $sell_price = (int) $sum['price']; // 실제 주문(결제) 상품금액
                $qty = (int) $sum['qty'];
                $unit_market_price = ((int) $row['it_cust_price'] > 0) ? (int) $row['it_cust_price'] : (int) $row['ct_price'];
                $market_price = $unit_market_price * $qty;
                $discount_price = max(0, $market_price - $sell_price);
                $unit_sell_price = $qty > 0 ? (int) round($sell_price / $qty) : (int) $sell_price;
                $unit_discount_price = $qty > 0 ? (int) round($discount_price / $qty) : (int) $discount_price;
                $unit_market_price_calc = $qty > 0 ? (int) round($market_price / $qty) : (int) $market_price;

                $maker_name = trim((string) $row['it_maker']);
                if ($maker_name === '') {
                    $maker_name = '기타';
                }

                if (!isset($grouped_cart[$maker_name])) {
                    $grouped_cart[$maker_name] = array(
                        'items' => array(),
                        'market_price' => 0,
                        'discount_price' => 0,
                        'sell_price' => 0,
                        'send_collect' => 0,
                        'send_prepaid' => 0,
                        'send_free' => 0
                    );
                }

                if ($ct_send_cost === '착불') {
                    $grouped_cart[$maker_name]['send_collect']++;
                } elseif ($ct_send_cost === '선불') {
                    $grouped_cart[$maker_name]['send_prepaid']++;
                } else {
                    $grouped_cart[$maker_name]['send_free']++;
                }

                $grouped_cart[$maker_name]['items'][] = array(
                    'idx' => $item_index,
                    'it_id' => $row['it_id'],
                    'it_name' => get_text($row['it_name']),
                    'it_name_html' => $it_name,
                    'qty' => (int) $qty,
                    'unit_market_price' => (int) $unit_market_price_calc,
                    'unit_discount_price' => (int) $unit_discount_price,
                    'unit_sell_price' => (int) $unit_sell_price,
                    'image' => $image,
                    'it_options' => $it_options,
                    'mod_options' => $mod_options,
                    'market_price' => (int) $market_price,
                    'discount_price' => (int) $discount_price,
                    'sell_price' => (int) $sell_price
                );

                $grouped_cart[$maker_name]['market_price'] += (int) $market_price;
                $grouped_cart[$maker_name]['discount_price'] += (int) $discount_price;
                $grouped_cart[$maker_name]['sell_price'] += (int) $sell_price;

                $tot_point += $point;
                $tot_sell_price += $sell_price;
                $tot_discount_price += $discount_price;
                $item_index++;
            }

            $i = $item_index;

            if ($i > 0) {
                $maker_index = 0;
                foreach ($grouped_cart as $maker_name => $maker_group) {
                    $maker_send_cost = '무료';
                    if ($maker_group['send_collect'] > 0 && ($maker_group['send_prepaid'] > 0 || $maker_group['send_free'] > 0)) {
                        $maker_send_cost = '상품별 상이';
                    } elseif ($maker_group['send_collect'] > 0) {
                        $maker_send_cost = '착불';
                    } elseif ($maker_group['send_prepaid'] > 0 && $maker_group['send_free'] > 0) {
                        $maker_send_cost = '상품별 상이';
                    } elseif ($maker_group['send_prepaid'] > 0) {
                        $maker_send_cost = '선불';
                    }
            ?>
                    <li class="sod_li cart-maker-card overflow-hidden rounded-xl border border-[#8D8D8D38] bg-white">
                        <div class="border-b border-[#8D8D8D38] p-4">
                            <div class="li_chk chk_box flex items-center gap-4">
                                <input type="checkbox" name="vendor_chk[<?php echo $maker_index; ?>]" value="1" id="vendor_chk_<?php echo $maker_index; ?>" class="maker_chk selec_chk" data-maker-index="<?php echo $maker_index; ?>" checked>
                                <label for="vendor_chk_<?php echo $maker_index; ?>"><span></span><b class="sound_only">업체선택</b></label>
                                <div><?php echo get_text($maker_name); ?></div>
                            </div>
                        </div>

                        <div id="maker_items_<?php echo $maker_index; ?>" class="divide-y divide-[#8D8D8D38]">
                            <?php foreach ($maker_group['items'] as $item) { ?>
                                <div class="cart-item-card"
                                    data-maker-index="<?php echo $maker_index; ?>"
                                    data-it-id="<?php echo $item['it_id']; ?>"
                                    data-qty="<?php echo (int) $item['qty']; ?>"
                                    data-unit-market-price="<?php echo (int) $item['unit_market_price']; ?>"
                                    data-unit-discount-price="<?php echo (int) $item['unit_discount_price']; ?>"
                                    data-unit-sell-price="<?php echo (int) $item['unit_sell_price']; ?>"
                                    data-market-price="<?php echo (int) $item['market_price']; ?>"
                                    data-sell-price="<?php echo (int) $item['sell_price']; ?>"
                                    data-discount-price="<?php echo (int) $item['discount_price']; ?>">
                                    <input type="hidden" name="it_id[<?php echo $item['idx']; ?>]" value="<?php echo $item['it_id']; ?>">
                                    <input type="hidden" name="it_name[<?php echo $item['idx']; ?>]" value="<?php echo $item['it_name']; ?>">

                                    <div class="li_primary p-4">
                                        <div class="flex gap-4">
                                            <div class="li_chk chk_box">
                                                <input type="checkbox" name="ct_chk[<?php echo $item['idx']; ?>]" value="1" id="ct_chk_<?php echo $item['idx']; ?>" class="selec_chk" data-maker-index="<?php echo $maker_index; ?>" checked>
                                                <label for="ct_chk_<?php echo $item['idx']; ?>"><span></span><b class="sound_only">상품선택</b></label>
                                            </div>
                                            <div class="flex flex-col gap-4">
                                                <div class="total_img"><?php echo $item['image']; ?></div>

                                                <div class="cart-qty-control flex items-center" data-it-id="<?php echo $item['it_id']; ?>">
                                                    <button type="button" class="qty-minus inline-flex h-6 w-6 items-center justify-center border border-r-0 border-zinc-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-minus-icon lucide-minus">
                                                            <path d="M5 12h14" />
                                                        </svg>
                                                    </button>

                                                    <input type="text" value="<?php echo (int) $item['qty']; ?>" inputmode="numeric" class="qty-input !h-6 !w-8 !rounded-none !border-zinc-500 !p-0 text-center text-xs">

                                                    <button type="button" class="qty-plus inline-flex h-6 w-6 items-center justify-center border border-l-0 border-zinc-500">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus">
                                                            <path d="M5 12h14" />
                                                            <path d="M12 5v14" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="li_info flex-1">
                                                <div class="li_name"><?php echo $item['it_name_html']; ?></div>

                                                <?php if ($item['it_options']) { ?>
                                                    <div class="sod_opt mt-2 text-sm"><?php echo $item['it_options']; ?></div>
                                                <?php } ?>

                                                <?php
                                                // 선택사항수정 버튼 hidden 처리 
                                                if ($item['mod_options']) { ?>
                                                    <div class="li_mod hidden mt-2 w-fit rounded border border-[#8D8D8D38] px-2"><?php echo $item['mod_options']; ?></div>
                                                <?php } ?>
                                            </div>
                                            <button type="button" class="item-delete-btn ml-auto inline-flex h-6 w-6 items-center justify-center text-zinc-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x">
                                                    <path d="M18 6 6 18" />
                                                    <path d="m6 6 12 12" />
                                                </svg>
                                                <span class="sound_only">상품 삭제</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="total_price total_span border-t border-[#8D8D8D38] bg-[#8D8D8D0D] p-4">
                            <div class="grid grid-cols-[1fr_auto] gap-y-1">
                                <span class="text-[#8D8D8D] text-sm">상품금액</span>
                                <span id="maker_market_price_<?php echo $maker_index; ?>" class="justify-self-end font-semibold"><?php echo number_format((int) $maker_group['market_price']); ?>원</span>
                                <span class="text-[#8D8D8D] text-sm">할인금액</span>
                                <span id="maker_discount_price_<?php echo $maker_index; ?>" class="justify-self-end text-red-400 font-semibold"><?php echo (int) $maker_group['discount_price'] > 0 ? '-' . number_format((int) $maker_group['discount_price']) . '원' : '0원'; ?></span>
                                <span class="text-[#8D8D8D] text-sm">배송비</span>
                                <span class="justify-self-end text-blue-400 font-semibold"><?php echo $maker_send_cost; ?></span>
                                <span class="text-sm">주문금액</span>
                                <span id="maker_sell_price_<?php echo $maker_index; ?>" class="justify-self-end text-xl font-bold"><?php echo number_format((int) $maker_group['sell_price']); ?>원</span>
                            </div>
                        </div>
                    </li>
                <?php
                    $maker_index++;
                }
            }

            // 장바구니 비어있을 때 빈 상태 UI 표시
            if ($i == 0) {
                ?>
                <li id="cart_empty_state" class="flex flex-col items-center justify-center gap-4 py-32 text-zinc-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart-icon lucide-shopping-cart" aria-hidden="true">
                        <circle cx="8" cy="21" r="1" />
                        <circle cx="19" cy="21" r="1" />
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                    </svg>
                    <span>장바구니에 담긴 상품이 없습니다</span>
                    <div class="go_shopping">
                        <a href="<?php echo G5_SHOP_URL; ?>/" class="btn01">쇼핑 계속하기</a>
                    </div>
                </li>
            <?php
            } else {
                $send_cost = get_sendcost($s_cart_id, 0);
            }
            ?>
        </ul>

        <?php
        // 장바구니에 상품이 있을 때만 표시 
        if ($i > 0) { ?>
            <section class="mt-6 border-t border-[#8D8D8D29] pt-8 space-y-2">
                <h3 class="font-semibold">장바구니 안내</h3>
                <ul class="space-y-1 text-sm text-zinc-500">
                    <li>· 장바구니에는 최근 6개월 동안 담은 상품이 100개까지 보관됩니다.</li>
                    <li>· 한 번에 최대 20가지 종류의 상품을 주문하실 수 있습니다.</li>
                </ul>
            </section>
        <?php } ?>

        <?php if ($i > 0) { ?>
            <div class="sod_ta_wr cart-total-wrap !hidden">
                <?php
                $tot_price = $tot_sell_price + $send_cost;
                if ($tot_price > 0 || $send_cost > 0) {
                ?>
                    <dl id="m_sod_bsk_tot">
                        <?php if ($send_cost > 0) { ?>
                            <dt class="sod_bsk_dvr">배송비</dt>
                            <dd class="sod_bsk_dvr"><strong><?php echo number_format($send_cost); ?> 원</strong></dd>
                        <?php } ?>
                        <dt>포인트</dt>
                        <dd><strong><?php echo number_format($tot_point); ?> 점</strong></dd>
                        <dt class="sod_bsk_cnt">총계</dt>
                        <dd class="sod_bsk_cnt"><strong><?php echo number_format($tot_price); ?></strong> 원</dd>
                    </dl>
                <?php } ?>

                <div id="sod_bsk_act" class="btn_confirm">
                    <div class="total">총계 <strong class="total_cnt"><?php echo number_format($tot_price); ?>원</strong></div>
                    <input type="hidden" name="url" value="<?php echo G5_SHOP_URL; ?>/orderform.php">
                    <input type="hidden" name="act" value="">
                    <input type="hidden" name="records" value="<?php echo $i; ?>">
                    <button type="button" onclick="return form_check('buy');" class="btn_submit">주문하기</button>
                </div>
            </div>
        <?php } ?>

        <?php if ($naverpay_button_js) { ?>
            <div class="naverpay-cart"><?php echo $naverpay_request_js . $naverpay_button_js; ?></div>
        <?php } ?>
    </form>

    <section class="mt-6 rounded bg-orange-400 mx-4 p-4 text-white">
        <div>이벤트 배너</div>
    </section>

    <section id="cart_recommend" class="mt-8">
        <div class="m-4 flex items-center justify-between">
            <h3 class="text-xl font-bold">이런 상품은 어때요?</h3>
            <a href="<?php echo shop_type_url('2'); ?>" aria-label="추천상품 더보기">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right" aria-hidden="true">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>
        </div>

        <?php
        // 장바구니 하단 추천 상품 슬라이더 (item_list 기반)
        $list = new item_list();
        $list->set_type(2);
        $list->set_list_skin(G5_SHOP_SKIN_PATH . '/main.10.skin.php');
        $list->set_view('it_id', false);
        $list->set_view('it_name', true);
        $list->set_view('it_basic', true);
        $list->set_view('it_cust_price', true);
        $list->set_view('it_price', true);
        $list->set_view('it_icon', true);
        $list->set_view('sns', true);
        $list->set_view('star', true);
        echo $list->run();
        ?>
    </section>

    <?php
    // 하단 고정 결제 바 기본값
    $floating_send_cost = isset($send_cost) ? (int)$send_cost : 0;
    $floating_discount_price = isset($tot_discount_price) ? (int)$tot_discount_price : 0;
    $floating_order_price = (int)$tot_sell_price + $floating_send_cost;
    ?>
    <?php if ($i > 0) { ?>
        <section class="cart-floating-pay fixed inset-x-0 bottom-0 z-40 border-t border-[#8D8D8D29] bg-white/95 shadow-[0_-8px_24px_rgba(0,0,0,0.08)] backdrop-blur">
            <div class="mx-auto p-4 w-full max-w-full space-y-2 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-zinc-500">총 할인금액</span>
                    <strong id="floating_discount_price">- <?php echo number_format($floating_discount_price); ?>원</strong>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-zinc-500">배송비</span>
                    <strong id="floating_send_cost"><?php echo number_format($floating_send_cost); ?>원</strong>
                </div>
                <button type="button" onclick="return form_check('buy');" id="floating_buy_button" class="mt-2 w-full rounded-lg bg-[#FFD137] p-4 text-lg font-semibold text-zinc-900">
                    <span id="floating_order_price"><?php echo number_format($floating_order_price); ?></span>원 주문하기
                </button>
            </div>
        </section>
    <?php } ?>
</div>

<script>
    $(function() {
        var qtySyncInProgress = false;

        // 상단 "선택 수 / 전체 수" 텍스트를 갱신
        function update_checked_count() {
            var $cartChecks = $("#sod_bsk_list .sod_list input[name^=ct_chk][id^=ct_chk_]");
            var checkedCount = $cartChecks.filter(":checked").length;
            var totalCount = $cartChecks.length;

            $("#checked_count").text(checkedCount);
            $("#total_count").text(totalCount);
        }

        function toCurrencyText(amount) {
            return Number(amount || 0).toLocaleString("ko-KR");
        }

        function toDiscountText(amount) {
            var value = parseInt(amount, 10) || 0;
            if (value > 0) {
                return "- " + toCurrencyText(value) + "원";
            }
            return "0원";
        }

        function normalizeQtyValue($input) {
            var cleaned = String($input.val() || "").replace(/[^0-9]/g, "");
            var value = parseInt(cleaned, 10) || 1;
            if (value < 1) {
                value = 1;
            }
            $input.val(value);
            return value;
        }

        function update_card_totals_by_qty($card, qty) {
            var unitSell = parseInt($card.attr("data-unit-sell-price"), 10) || 0;
            var unitDiscount = parseInt($card.attr("data-unit-discount-price"), 10) || 0;
            var unitMarket = parseInt($card.attr("data-unit-market-price"), 10) || 0;

            $card.attr("data-qty", qty);
            $card.attr("data-sell-price", unitSell * qty);
            $card.attr("data-discount-price", unitDiscount * qty);
            $card.attr("data-market-price", unitMarket * qty);
        }

        function update_maker_summary(makerIndex) {
            var marketTotal = 0;
            var discountTotal = 0;
            var sellTotal = 0;

            $("#maker_items_" + makerIndex + " .cart-item-card").each(function() {
                var $card = $(this);
                var isChecked = $card.find("input[name^=ct_chk][id^=ct_chk_]").is(":checked");
                if (!isChecked) {
                    return;
                }

                marketTotal += parseInt($card.attr("data-market-price"), 10) || 0;
                discountTotal += parseInt($card.attr("data-discount-price"), 10) || 0;
                sellTotal += parseInt($card.attr("data-sell-price"), 10) || 0;
            });

            $("#maker_market_price_" + makerIndex).text(toCurrencyText(marketTotal) + "원");
            $("#maker_discount_price_" + makerIndex).text(discountTotal > 0 ? "-" + toCurrencyText(discountTotal) + "원" : "0원");
            $("#maker_sell_price_" + makerIndex).text(toCurrencyText(sellTotal) + "원");
        }

        function update_all_maker_summaries() {
            $(".maker_chk").each(function() {
                update_maker_summary($(this).data("makerIndex"));
            });
        }

        function collect_qty_sync_payload() {
            var payload = {};
            $(".cart-item-card").each(function() {
                var itId = String($(this).attr("data-it-id") || "");
                var qty = parseInt($(this).find(".qty-input").first().val(), 10) || 1;
                if (!itId) {
                    return;
                }
                if (qty < 1) {
                    qty = 1;
                }
                payload[itId] = qty;
            });

            return payload;
        }

        function sync_qty_before_buy(done) {
            if (qtySyncInProgress) {
                return;
            }

            qtySyncInProgress = true;
            $("#floating_buy_button").prop("disabled", true).addClass("opacity-50");

            $.ajax({
                url: "<?php echo G5_SHOP_URL; ?>/cartupdate.php",
                method: "POST",
                dataType: "json",
                data: {
                    act: "qtysync",
                    qty_sync: collect_qty_sync_payload()
                }
            }).done(function(resp) {
                qtySyncInProgress = false;
                $("#floating_buy_button").prop("disabled", false).removeClass("opacity-50");
                if (resp && resp.ok) {
                    done(true);
                } else {
                    done(false, "수량 동기화에 실패했습니다.");
                }
            }).fail(function() {
                qtySyncInProgress = false;
                $("#floating_buy_button").prop("disabled", false).removeClass("opacity-50");
                done(false, "수량 동기화 중 오류가 발생했습니다.");
            });
        }

        window.sync_qty_before_buy = sync_qty_before_buy;

        // 선택된 상품 기준으로 하단 고정 결제 바 금액 갱신
        function update_floating_payment() {
            var $checked = $("#sod_bsk_list .sod_list input[name^=ct_chk][id^=ct_chk_]:checked");
            var selectedCount = $checked.length;
            var totalOrder = 0;
            var totalDiscount = 0;

            $checked.each(function() {
                var $card = $(this).closest(".cart-item-card");
                totalOrder += parseInt($card.attr("data-sell-price"), 10) || 0;
                totalDiscount += parseInt($card.attr("data-discount-price"), 10) || 0;
            });

            var sendCost = selectedCount > 0 ? (window.cartSendCost || 0) : 0;
            var orderTotal = selectedCount > 0 ? totalOrder + sendCost : 0;

            $("#floating_discount_price").text(toDiscountText(totalDiscount));
            $("#floating_send_cost").text(toCurrencyText(sendCost) + "원");
            $("#floating_order_price").text(toCurrencyText(orderTotal));
            $("#floating_buy_button").prop("disabled", selectedCount < 1).toggleClass("opacity-50", selectedCount < 1);
        }

        function sync_maker_checkbox(makerIndex) {
            var $items = $("#maker_items_" + makerIndex + " input[name^=ct_chk][id^=ct_chk_]");
            var checkedCount = $items.filter(":checked").length;
            $("#vendor_chk_" + makerIndex).prop("checked", $items.length > 0 && checkedCount === $items.length);
        }

        function sync_all_maker_checkboxes() {
            $(".maker_chk").each(function() {
                sync_maker_checkbox($(this).data("makerIndex"));
            });
        }

        function sync_all_checkbox() {
            var $cartChecks = $("#sod_bsk_list .sod_list input[name^=ct_chk][id^=ct_chk_]");
            var checkedCount = $cartChecks.filter(":checked").length;
            $("input[name=ct_all]").prop("checked", $cartChecks.length > 0 && checkedCount === $cartChecks.length);
        }

        // 썸네일 하단 수량 UI (+/-) 동작
        $(document).on("click", ".cart-qty-control .qty-minus, .cart-qty-control .qty-plus", function() {
            var $wrap = $(this).closest(".cart-qty-control");
            var $input = $wrap.find(".qty-input");
            var current = normalizeQtyValue($input);
            var next = $(this).hasClass("qty-plus") ? current + 1 : current - 1;

            if (next < 1) {
                next = 1;
            }

            $input.val(next);
            var $card = $wrap.closest(".cart-item-card");
            update_card_totals_by_qty($card, next);
            update_maker_summary($card.attr("data-maker-index"));
            update_floating_payment();
        });

        $(document).on("change", ".cart-qty-control .qty-input", function() {
            var value = normalizeQtyValue($(this));
            var $card = $(this).closest(".cart-item-card");
            update_card_totals_by_qty($card, value);
            update_maker_summary($card.attr("data-maker-index"));
            update_floating_payment();
        });

        // 상품별 X 버튼 클릭 시 해당 상품만 선택 후 기존 선택삭제 동작 실행
        $(document).on("click", ".item-delete-btn", function() {
            var $card = $(this).closest(".cart-item-card");
            var $targetCheck = $card.find("input[name^=ct_chk][id^=ct_chk_]");
            var makerIndex = $targetCheck.data("makerIndex");

            if (!$targetCheck.length) {
                return false;
            }

            $("#sod_bsk_list .sod_list input[name^=ct_chk][id^=ct_chk_]").prop("checked", false);
            $(".maker_chk").prop("checked", false);
            $("input[name=ct_all]").prop("checked", false);

            $targetCheck.prop("checked", true);
            sync_maker_checkbox(makerIndex);
            sync_all_checkbox();
            update_checked_count();
            update_floating_payment();

            return form_check("seldelete");
        });

        // 선택사항수정 버튼 클릭 시 옵션 수정 모달 로드
        $(".mod_options").click(function() {
            var it_id = $(this).attr("id").replace("mod_opt_", "");
            var $this = $(this);
            close_btn_idx = $(".mod_options").index($(this));

            $.post(
                "./cartoption.php", {
                    it_id: it_id
                },
                function(data) {
                    $("#mod_option_frm").remove();
                    $this.after("<div id=\"mod_option_frm\"></div><div class=\"mod_option_bg\"></div>");
                    $("#mod_option_frm").html(data);
                    price_calculate();
                }
            );
        });

        // 전체 선택 체크박스 토글
        $("input[name=ct_all]").click(function() {
            var $cartChecks = $("#sod_bsk_list .sod_list input[name^=ct_chk][id^=ct_chk_]");
            var $makerChecks = $(".maker_chk");
            if ($(this).is(":checked")) {
                $cartChecks.prop("checked", true);
                $makerChecks.prop("checked", true);
            } else {
                $cartChecks.prop("checked", false);
                $makerChecks.prop("checked", false);
            }
            update_checked_count();
            update_floating_payment();
        });

        // 업체 체크박스 변경 시 해당 업체 상품 체크 동기화
        $(document).on("change", ".maker_chk", function() {
            var makerIndex = $(this).data("makerIndex");
            $("#maker_items_" + makerIndex + " input[name^=ct_chk][id^=ct_chk_]").prop("checked", $(this).is(":checked"));
            sync_all_checkbox();
            update_checked_count();
            update_floating_payment();
        });

        // 개별 상품 체크 변경 시 카운트 갱신
        $(document).on("change", "#sod_bsk_list .sod_list input[name^=ct_chk][id^=ct_chk_]", function() {
            sync_maker_checkbox($(this).data("makerIndex"));
            sync_all_checkbox();
            update_checked_count();
            update_floating_payment();
        });

        // 옵션 수정 모달 닫기 버튼 처리
        $(document).on("click", "#mod_option_close", function() {
            $("#mod_option_frm, .mod_option_bg").remove();
            $("#win_mask, .window").hide();
            $(".mod_options").eq(close_btn_idx).focus();
        });

        // 모달 마스크 클릭 시 닫기 처리
        $("#win_mask").click(function() {
            $("#mod_option_frm").remove();
            $("#win_mask").hide();
            $(".mod_options").eq(close_btn_idx).focus();
        });

        // 초기 카운트 1회 반영
        update_checked_count();
        sync_all_maker_checkboxes();
        sync_all_checkbox();
        update_all_maker_summaries();
        window.cartSendCost = <?php echo isset($send_cost) ? (int)$send_cost : 0; ?>;
        update_floating_payment();

        // 장바구니 하단 추천 상품 슬라이더 초기화
        var $cartSlider = $("#cart_recommend .js-shop-slider");
        if ($cartSlider.length && $.fn.owlCarousel && !$cartSlider.hasClass("owl-loaded")) {
            $cartSlider.owlCarousel({
                loop: true,
                nav: false,
                autoplay: false,
                autoplayHoverPause: false,
                margin: parseInt($cartSlider.attr("data-margin"), 10) || 12,
                stagePadding: parseInt($cartSlider.attr("data-stage-padding"), 10) || 12,
                items: parseFloat($cartSlider.attr("data-items")) || 2.15
            });
        }
    });

    // 장바구니 폼 액션(주문/전체삭제/선택삭제) 공통 처리
    function form_check(act) {
        var f = document.frmcartlist;

        if (act === "buy") {
            if ($("#sod_bsk_list .sod_list input[name^=ct_chk][id^=ct_chk_]:checked").length < 1) {
                alert("주문하실 상품을 하나이상 선택해 주십시오.");
                return false;
            }
            if (typeof window.sync_qty_before_buy === "function") {
                window.sync_qty_before_buy(function(success, message) {
                    if (!success) {
                        alert(message || "수량 동기화에 실패했습니다.");
                        return;
                    }
                    f.act.value = act;
                    f.submit();
                });
                return false;
            } else {
                f.act.value = act;
                f.submit();
            }
        } else if (act === "alldelete") {
            f.act.value = act;
            f.submit();
        } else if (act === "seldelete") {
            if ($("#sod_bsk_list .sod_list input[name^=ct_chk][id^=ct_chk_]:checked").length < 1) {
                alert("삭제하실 상품을 하나이상 선택해 주십시오.");
                return false;
            }
            f.act.value = act;
            f.submit();
        }

        return true;
    }
</script>

<?php
include_once(G5_SHOP_PATH . '/_tail.php');

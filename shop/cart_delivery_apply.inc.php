<?php
if (!defined('_GNUBOARD_')) exit;

include_once(G5_LIB_PATH . '/donuts_delivery_policy.lib.php');

/*
 * cart.php용 배송비 적용 include.
 *
 * cart.php 원본 파일이 제공되지 않아 직접 덮어쓰기하지 않고
 * 이 include 파일을 준비했습니다.
 *
 * cart.php에서 현재 장바구니 ID가 정해진 뒤,
 * 배송비/총액을 출력하기 전에:
 *
 * include_once(G5_SHOP_PATH . '/cart_delivery_apply.inc.php');
 */

$donuts_cart_id = '';

if (isset($s_cart_id) && $s_cart_id) {
    $donuts_cart_id = $s_cart_id;
} elseif (isset($cart_id) && $cart_id) {
    $donuts_cart_id = $cart_id;
} elseif (get_session('ss_cart_id')) {
    $donuts_cart_id = get_session('ss_cart_id');
}

if ($donuts_cart_id) {
    $donuts_cart_quote = donuts_delivery_policy_quote(
        $donuts_cart_id,
        '',
        '',
        '',
        true
    );

    $send_cost = (int)$donuts_cart_quote['shipping_total'];

    if (isset($tot_sell_price)) {
        $tot_price = (int)$tot_sell_price + $send_cost;
    }
}

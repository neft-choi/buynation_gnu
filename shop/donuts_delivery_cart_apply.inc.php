<?php
if (!defined('_GNUBOARD_')) exit;

include_once(G5_LIB_PATH . '/donuts_delivery_policy.lib.php');

/*
 * cart.php / orderform.php 사용자 화면용 배송비 계산 include
 *
 * 사용 위치:
 * - $s_cart_id 가 만들어진 뒤
 * - 화면에 배송비/총 결제금액을 출력하기 전
 *
 * 계산 결과:
 * $donuts_delivery_user_policy
 * $donuts_delivery_user_shipping
 * $donuts_delivery_user_item_total
 * $donuts_delivery_user_order_total
 */

$donuts_delivery_user_addr = '';
$donuts_delivery_user_zip = '';

if (isset($_POST['od_b_addr1'])) {
    $donuts_delivery_user_addr = trim(
        $_POST['od_b_addr1'].' '.
        $_POST['od_b_addr2'].' '.
        $_POST['od_b_addr3']
    );
}

if (isset($_POST['od_b_zip'])) {
    $donuts_delivery_user_zip = preg_replace('/[^0-9]/', '', $_POST['od_b_zip']);
} elseif (isset($_POST['od_b_zip1']) || isset($_POST['od_b_zip2'])) {
    $donuts_delivery_user_zip =
        preg_replace('/[^0-9]/', '', isset($_POST['od_b_zip1']) ? $_POST['od_b_zip1'] : '').
        preg_replace('/[^0-9]/', '', isset($_POST['od_b_zip2']) ? $_POST['od_b_zip2'] : '');
}

$donuts_delivery_user_policy = donuts_delivery_policy_calc_all_brands_by_cart_id(
    $s_cart_id,
    $donuts_delivery_user_addr,
    $donuts_delivery_user_zip
);

$donuts_delivery_user_shipping = (int)$donuts_delivery_user_policy['shipping_total'];
$donuts_delivery_user_item_total = (int)$donuts_delivery_user_policy['item_total'];
$donuts_delivery_user_order_total =
    $donuts_delivery_user_item_total +
    $donuts_delivery_user_shipping;

/*
 * 영카트 기본 변수명과 맞춰서 덮어쓰기.
 * 테마/버전에 따라 사용하는 변수가 조금씩 다르므로 가장 많이 쓰이는 값을 같이 세팅합니다.
 */
if ($donuts_delivery_user_item_total > 0) {
    $send_cost = $donuts_delivery_user_shipping;
    $tot_send_cost = $donuts_delivery_user_shipping;
    $od_send_cost = $donuts_delivery_user_shipping;
    $od_send_cost2 = 0;

    if (isset($tot_sell_price)) {
        $tot_price = (int)$tot_sell_price + $donuts_delivery_user_shipping;
    }

    if (isset($tot_price) && isset($tot_sell_price)) {
        $tot_price = (int)$tot_sell_price + $donuts_delivery_user_shipping;
    }
}

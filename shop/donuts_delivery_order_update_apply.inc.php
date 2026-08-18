<?php
if (!defined('_GNUBOARD_')) exit;

include_once(G5_LIB_PATH . '/donuts_delivery_policy.lib.php');

/*
 * orderformupdate.php 주문 저장 직전 include
 *
 * 사용 위치:
 * - $s_cart_id 확정 후
 * - 주문 INSERT/UPDATE에서 od_send_cost, od_send_cost2 값을 사용하기 전
 *
 * 역할:
 * 현재 배송관리 정책 기준 배송비를 계산해서 실제 주문 저장값에 반영합니다.
 */

$donuts_delivery_update_addr = trim(
    (isset($_POST['od_b_addr1']) ? $_POST['od_b_addr1'] : '').' '.
    (isset($_POST['od_b_addr2']) ? $_POST['od_b_addr2'] : '').' '.
    (isset($_POST['od_b_addr3']) ? $_POST['od_b_addr3'] : '')
);

if (isset($_POST['od_b_zip'])) {
    $donuts_delivery_update_zip = preg_replace('/[^0-9]/', '', $_POST['od_b_zip']);
} else {
    $donuts_delivery_update_zip =
        preg_replace('/[^0-9]/', '', isset($_POST['od_b_zip1']) ? $_POST['od_b_zip1'] : '').
        preg_replace('/[^0-9]/', '', isset($_POST['od_b_zip2']) ? $_POST['od_b_zip2'] : '');
}

$donuts_delivery_update_policy = donuts_delivery_policy_calc_all_brands_by_cart_id(
    $s_cart_id,
    $donuts_delivery_update_addr,
    $donuts_delivery_update_zip
);

$donuts_delivery_update_shipping = (int)$donuts_delivery_update_policy['shipping_total'];

if ((int)$donuts_delivery_update_policy['item_total'] > 0) {
    $od_send_cost = $donuts_delivery_update_shipping;
    $od_send_cost2 = 0;

    $_POST['od_send_cost'] = $od_send_cost;
    $_POST['od_send_cost2'] = 0;
}

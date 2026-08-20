<?php
include_once('./_common.php');
include_once(G5_LIB_PATH . '/donuts_delivery_policy.lib.php');

$zipcode = isset($_POST['zipcode'])
    ? preg_replace('/[^0-9]/', '', $_POST['zipcode'])
    : '';

$addr1 = isset($_POST['addr1'])
    ? trim(clean_xss_tags($_POST['addr1'], 1, 1))
    : '';

if (get_session('ss_direct')) {
    $cart_id = get_session('ss_cart_direct');
} else {
    $cart_id = get_session('ss_cart_id');
}

if (!$cart_id) {
    echo '0';
    exit;
}

$shipping = donuts_delivery_policy_final_shipping(
    $cart_id,
    '',
    $addr1,
    $zipcode,
    true
);

echo (int)$shipping;
exit;

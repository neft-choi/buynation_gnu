<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

$g5['title'] = '위시리스트';
include_once('./_head.php');

$preview_sql  = " select a.wi_id, a.wi_time, b.* from {$g5['g5_shop_wish_table']} a ";
$preview_sql .= " left join {$g5['g5_shop_item_table']} b on ( a.it_id = b.it_id ) ";
$preview_sql .= " where a.mb_id = '{$member['mb_id']}' ";
$preview_sql .= " order by a.wi_id desc ";
if (!class_exists('item_list')) {
    include_once(G5_LIB_PATH . '/shop.lib.php');
}

$wishlist_preview = new item_list();
$wishlist_preview->set_list_skin(G5_SHOP_SKIN_PATH . '/wishlist.10.skin.php');
$wishlist_preview->set_query($preview_sql);
$wishlist_preview->set_list_mod(2);
$wishlist_preview->set_list_row(999);
$wishlist_preview->set_img_size(225, 300);
$wishlist_preview->set_view('it_id', false);
$wishlist_preview->set_view('it_name', true);
$wishlist_preview->set_view('it_basic', true);
$wishlist_preview->set_view('it_cust_price', true);
$wishlist_preview->set_view('it_price', true);
$wishlist_preview->set_view('it_icon', true);
$wishlist_preview->set_view('star', true);
echo $wishlist_preview->run();

include_once('./_tail.php');

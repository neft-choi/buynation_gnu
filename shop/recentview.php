<?php
include_once('./_common.php');

if (defined('G5_THEME_SHOP_PATH')) {
    $theme_recentview_file = G5_THEME_SHOP_PATH . '/recentview.php';
    if (is_file($theme_recentview_file)) {
        include_once($theme_recentview_file);
        return;
        unset($theme_recentview_file);
    }
}

$g5['title'] = '최근 본 상품';
include_once('./_head.php');
include_once(G5_SHOP_SKIN_PATH . '/boxtodayview.skin.php');
include_once('./_tail.php');


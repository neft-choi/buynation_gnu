<?php
include_once('./_common.php');

// bbs 페이지를 쇼핑몰 페이지로 사용하게 만드는 상수
if (!defined('_SHOP_')) {
    define('_SHOP_', true);
}

if (isset($_SESSION['ss_mb_reg']))
    $mb = get_member($_SESSION['ss_mb_reg']);

// 회원정보가 없다면 초기 페이지로 이동
if (!(isset($mb['mb_id']) && $mb['mb_id']))
    goto_url(G5_SHOP_URL);

$g5['title'] = '회원가입 완료';
// include_once('./_head.php');
include_once(G5_SHOP_PATH . '/_head.php');

include_once($member_skin_path.'/register_result.skin.php');

// include_once('./_tail.php');
include_once(G5_SHOP_PATH . '/_tail.php');
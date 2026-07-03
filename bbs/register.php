<?php
include_once('./_common.php');

// 로그인중인 경우 회원가입 할 수 없습니다.
if ($is_member) {
    goto_url(G5_URL);
}

// 세션을 지웁니다.
set_session("ss_mb_reg", "");

// bbs 페이지를 쇼핑몰 페이지로 사용하게 만드는 상수
if (!defined('_SHOP_')) {
    define('_SHOP_', true);
}

$g5['title'] = '회원가입약관';
// include_once('./_head.php');
include_once(G5_SHOP_PATH . '/_head.php');

// type 쿼리 스트링 조회
$type = isset($_GET['type']) ? preg_replace('#[^a-z_]#', '', $_GET['type']) : '';

$register_action_url = G5_BBS_URL . '/register_form.php';

// type 인자 확인해서 붙이기
if ($type == 'dots' || $type == 'donuts' || $type == 'brand') {
    $register_action_url .= '?type=' . $type;
}

include_once($member_skin_path . '/register.skin.php');

// include_once('./_tail.php');
include_once(G5_SHOP_PATH . '/_tail.php');
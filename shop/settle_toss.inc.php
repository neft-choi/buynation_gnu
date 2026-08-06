<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
require_once(G5_SHOP_PATH.'/toss/toss.inc.php');

if (empty($config['cf_toss_client_key']) || empty($config['cf_toss_secret_key'])) {
    alert('토스페이먼츠 API 키가 설정되지 않았습니다. 쇼핑몰 관리자에서 클라이언트 키와 시크릿 키를 확인해 주세요.');
}

$toss_client_live = strpos($config['cf_toss_client_key'], 'live_') === 0;
$toss_secret_live = strpos($config['cf_toss_secret_key'], 'live_') === 0;
if ($toss_client_live !== $toss_secret_live) {
    alert('토스페이먼츠 클라이언트 키와 시크릿 키의 테스트/라이브 환경이 서로 다릅니다.');
}

$toss = new TossPayments(
    $config['cf_toss_client_key'],
    $config['cf_toss_secret_key'],
    $config['cf_lg_mid']
);

$toss->setPaymentHeader();

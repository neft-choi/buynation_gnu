<?php
$sub_menu = '400760';
include_once('./_common.php');
include_once(G5_LIB_PATH . '/donuts_delivery.lib.php');

auth_check_menu($auth, $sub_menu, 'w');
check_demo();
check_admin_token();

donuts_delivery_install();

/*
 * 배송조건 <-> 추가배송비(sendcostlist) 연결 테이블
 */
sql_query("
    CREATE TABLE IF NOT EXISTS donuts_delivery_condition_sendcosts (
        dc_id BIGINT UNSIGNED NOT NULL,
        sc_id BIGINT UNSIGNED NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (dc_id, sc_id),
        KEY idx_sc_id (sc_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
", false);

$action = isset($_POST['action']) ? trim($_POST['action']) : '';
$requested_brand_id = isset($_POST['brand_id']) ? trim($_POST['brand_id']) : '';

if ($is_admin === 'super') {
    $brand_id = $requested_brand_id;
} else {
    $brand_id = $member['mb_id'];
}

if (!$brand_id || !donuts_delivery_brand_exists($brand_id)) {
    alert('브랜드 정보가 올바르지 않습니다.', './deliverymanage.php');
}

if ($is_admin !== 'super' && $brand_id !== $member['mb_id']) {
    alert('다른 브랜드의 배송설정을 수정할 수 없습니다.', './deliverymanage.php');
}

$brand_id_sql = sql_real_escape_string($brand_id);
$return_url = './deliverymanage.php?brand_id=' . urlencode($brand_id);

function delivery_redirect($msg, $url)
{
    alert($msg, $url);
}

include_once('./deliverymanage_actions.php');

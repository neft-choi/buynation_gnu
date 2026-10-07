<?php
$sub_menu = '400400';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'w');
check_admin_token();

$od_id = isset($_POST['od_id']) ? safe_replace_regex($_POST['od_id'], 'od_id') : '';
if (!$od_id) {
    alert('주문번호가 없습니다.');
}

$od = sql_fetch(" SELECT od_id FROM {$g5['g5_shop_order_table']} WHERE od_id = '".sql_real_escape_string($od_id)."' LIMIT 1 ");
if (empty($od['od_id'])) {
    alert('주문정보를 찾을 수 없습니다.');
}

// 송장 컬럼 보장
if (!sql_query(" SELECT ct_delivery_company FROM {$g5['g5_shop_cart_table']} LIMIT 1 ", false)) {
    sql_query("
        ALTER TABLE `{$g5['g5_shop_cart_table']}`
            ADD `ct_delivery_company` VARCHAR(100) NOT NULL DEFAULT '' AFTER `ct_status`,
            ADD `ct_invoice` VARCHAR(100) NOT NULL DEFAULT '' AFTER `ct_delivery_company`,
            ADD `ct_invoice_time` DATETIME NULL DEFAULT NULL AFTER `ct_invoice`
    ", true);
}

$ct_ids = isset($_POST['ct_id']) && is_array($_POST['ct_id']) ? $_POST['ct_id'] : array();
$companies = isset($_POST['ct_delivery_company']) && is_array($_POST['ct_delivery_company']) ? $_POST['ct_delivery_company'] : array();
$invoices = isset($_POST['ct_invoice']) && is_array($_POST['ct_invoice']) ? $_POST['ct_invoice'] : array();

if (!$ct_ids) {
    alert('저장할 주문상품이 없습니다.');
}

// 일반 브랜드 계정이면 자기 it_seller 상품만 수정 가능
$is_brand = false;
$brand_id = '';
if ($is_admin !== 'super' && !empty($member['mb_id'])) {
    $login_id_sql = sql_real_escape_string(trim((string)$member['mb_id']));
    $brand = sql_fetch("
        SELECT brand_id
          FROM donuts_brand
         WHERE LOWER(TRIM(brand_id)) = LOWER('{$login_id_sql}')
         LIMIT 1
    ");
    if (!empty($brand['brand_id'])) {
        $is_brand = true;
        $brand_id = trim((string)$brand['brand_id']);
    }
}

$od_id_sql = sql_real_escape_string($od_id);
$saved = 0;

foreach ($ct_ids as $idx => $raw_ct_id) {
    $ct_id = (int)$raw_ct_id;
    if ($ct_id <= 0) {
        continue;
    }

    // POST의 ct_id가 실제 이 주문에 속하는지 서버에서 재검증
    $sql = "
        SELECT c.ct_id, c.it_id
          FROM {$g5['g5_shop_cart_table']} c
          LEFT JOIN {$g5['g5_shop_item_table']} i
            ON i.it_id = c.it_id
         WHERE c.ct_id = '{$ct_id}'
           AND c.od_id = '{$od_id_sql}'
    ";

    if ($is_brand) {
        $brand_sql = sql_real_escape_string($brand_id);
        $sql .= " AND LOWER(TRIM(i.it_seller)) = LOWER('{$brand_sql}') ";
    }

    $sql .= " LIMIT 1 ";
    $cart = sql_fetch($sql);

    if (empty($cart['ct_id'])) {
        continue;
    }

    $company = isset($companies[$idx]) ? trim((string)$companies[$idx]) : '';
    $invoice = isset($invoices[$idx]) ? trim((string)$invoices[$idx]) : '';

    // DB 컬럼 길이와 동일하게 제한
    $company = mb_substr($company, 0, 100, 'UTF-8');
    $invoice = mb_substr($invoice, 0, 100, 'UTF-8');

    $company_sql = sql_real_escape_string($company);
    $invoice_sql = sql_real_escape_string($invoice);

    // 운송장 번호가 새로 생기거나 변경된 경우 등록일시 갱신.
    // 운송장 번호를 비우면 등록일시도 NULL.
    $old = sql_fetch("
        SELECT ct_invoice
          FROM {$g5['g5_shop_cart_table']}
         WHERE ct_id = '{$ct_id}'
         LIMIT 1
    ");
    $old_invoice = trim((string)($old['ct_invoice'] ?? ''));

    if ($invoice === '') {
        $invoice_time_sql = "NULL";
    } elseif ($invoice !== $old_invoice) {
        $invoice_time_sql = "'".G5_TIME_YMDHIS."'";
    } else {
        // 기존 번호가 그대로면 최초 등록일시 유지
        $invoice_time_sql = "ct_invoice_time";
    }

    sql_query("
        UPDATE {$g5['g5_shop_cart_table']}
           SET ct_delivery_company = '{$company_sql}',
               ct_invoice = '{$invoice_sql}',
               ct_invoice_time = {$invoice_time_sql}
         WHERE ct_id = '{$ct_id}'
           AND od_id = '{$od_id_sql}'
    ");

    $saved++;
}

if ($saved < 1) {
    alert('저장된 송장정보가 없습니다. 판매자 상품 권한 또는 주문상품을 확인해 주세요.');
}

$q = 'od_id='.urlencode($od_id);
foreach (array('sort1','sort2','sel_field','search','page') as $key) {
    if (isset($_POST[$key]) && $_POST[$key] !== '') {
        $q .= '&'.$key.'='.urlencode((string)$_POST[$key]);
    }
}

alert($saved.'개 주문상품의 송장정보를 저장했습니다.', './orderform.php?'.$q);

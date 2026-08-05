<?php
$sub_menu = '400400';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "w");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    alert('올바른 방법으로 이용해 주십시오.', './orderlist.php');
}

$od_id = isset($_POST['od_id']) ? safe_replace_regex($_POST['od_id'], 'od_id') : '';

if (!$od_id) {
    alert('주문번호가 없습니다.', './orderlist.php');
}

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

$brand_id_sql = sql_real_escape_string($member['mb_id']);

$brand = sql_fetch("
    SELECT brand_id
    FROM donuts_brand
    WHERE brand_id = '{$brand_id_sql}'
    LIMIT 1
");

$is_brand = !empty($brand['brand_id']);
$updated = 0;

foreach ($ct_ids as $idx => $ct_id_value) {
    $ct_id = (int)$ct_id_value;

    if ($ct_id < 1) {
        continue;
    }

    $company = isset($companies[$idx]) ? trim(clean_xss_tags($companies[$idx], 1, 1)) : '';
    $invoice = isset($invoices[$idx]) ? trim(clean_xss_tags($invoices[$idx], 1, 1)) : '';

    $company = substr($company, 0, 100);
    $invoice = substr($invoice, 0, 100);

    $company_sql = sql_real_escape_string($company);
    $invoice_sql = sql_real_escape_string($invoice);
    $od_id_sql = sql_real_escape_string($od_id);

    $row = sql_fetch("
        SELECT c.ct_id, c.it_id, i.it_brand
        FROM {$g5['g5_shop_cart_table']} c
        LEFT JOIN {$g5['g5_shop_item_table']} i ON c.it_id = i.it_id
        WHERE c.ct_id = '{$ct_id}'
          AND c.od_id = '{$od_id_sql}'
        LIMIT 1
    ");

    if (empty($row['ct_id'])) {
        continue;
    }

    if ($is_brand && $row['it_brand'] !== $member['mb_id']) {
        continue;
    }

    $invoice_time_sql = ($invoice === '') ? 'NULL' : "'" . G5_TIME_YMDHIS . "'";

    $result = sql_query("
        UPDATE {$g5['g5_shop_cart_table']}
        SET
            ct_delivery_company = '{$company_sql}',
            ct_invoice = '{$invoice_sql}',
            ct_invoice_time = {$invoice_time_sql}
        WHERE ct_id = '{$ct_id}'
          AND od_id = '{$od_id_sql}'
    ", false);

    if ($result) {
        $updated++;
    }
}

$qstr = 'od_id=' . urlencode($od_id);

foreach (array('sort1', 'sort2', 'sel_field', 'search', 'page') as $key) {
    if (isset($_POST[$key]) && $_POST[$key] !== '') {
        $qstr .= '&' . $key . '=' . urlencode($_POST[$key]);
    }
}

alert($updated . '개의 송장정보를 저장했습니다.', './orderform.php?' . $qstr);

<?php
$sub_menu = '400760';
include_once('./_common.php');
include_once(G5_LIB_PATH . '/donuts_delivery.lib.php');

auth_check_menu($auth, $sub_menu, 'w');
check_demo();
check_admin_token();

donuts_delivery_install();

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

if ($action === 'save_condition') {
    $dc_id = isset($_POST['dc_id']) ? (int)$_POST['dc_id'] : 0;
    $name = isset($_POST['dc_name']) ? trim(clean_xss_tags($_POST['dc_name'], 1, 1)) : '';
    $type = isset($_POST['dc_type']) ? trim($_POST['dc_type']) : 'conditional';
    $allowed = array('paid', 'conditional', 'free', 'quantity', 'amount_range');
    if (!in_array($type, $allowed, true)) $type = 'conditional';

    $price = isset($_POST['dc_price']) ? max(0, (int)$_POST['dc_price']) : 0;
    $minimum = isset($_POST['dc_minimum']) ? max(0, (int)$_POST['dc_minimum']) : 0;
    $qty = isset($_POST['dc_qty']) ? max(1, (int)$_POST['dc_qty']) : 1;
    $jeju_use = !empty($_POST['dc_jeju_use']) ? 1 : 0;
    $island_use = !empty($_POST['dc_island_use']) ? 1 : 0;
    $jeju_price = isset($_POST['dc_jeju_price']) ? max(0, (int)$_POST['dc_jeju_price']) : 3000;
    $island_price = isset($_POST['dc_island_price']) ? max(0, (int)$_POST['dc_island_price']) : 5000;

    if ($name === '') delivery_redirect('배송조건명을 입력해 주세요.', $return_url);

    $name_sql = sql_real_escape_string(mb_substr($name, 0, 100, 'UTF-8'));

    $dup_sql = "SELECT dc_id FROM donuts_delivery_conditions WHERE brand_id = '{$brand_id_sql}' AND dc_name = '{$name_sql}'";
    if ($dc_id > 0) $dup_sql .= " AND dc_id <> '{$dc_id}'";
    $dup_sql .= ' LIMIT 1';
    $dup = sql_fetch($dup_sql);
    if (!empty($dup['dc_id'])) delivery_redirect('같은 이름의 배송조건이 이미 있습니다.', $return_url);

    if ($dc_id > 0) {
        $current = sql_fetch("SELECT dc_id FROM donuts_delivery_conditions WHERE dc_id = '{$dc_id}' AND brand_id = '{$brand_id_sql}' LIMIT 1");
        if (empty($current['dc_id'])) delivery_redirect('수정 권한이 없는 배송조건입니다.', $return_url);

        sql_query("UPDATE donuts_delivery_conditions SET
            dc_name = '{$name_sql}',
            dc_type = '" . sql_real_escape_string($type) . "',
            dc_price = '{$price}',
            dc_minimum = '{$minimum}',
            dc_qty = '{$qty}',
            dc_jeju_use = '{$jeju_use}',
            dc_jeju_price = '{$jeju_price}',
            dc_island_use = '{$island_use}',
            dc_island_price = '{$island_price}',
            updated_at = NOW()
            WHERE dc_id = '{$dc_id}' AND brand_id = '{$brand_id_sql}'");
    } else {
        sql_query("INSERT INTO donuts_delivery_conditions SET
            brand_id = '{$brand_id_sql}',
            dc_name = '{$name_sql}',
            dc_type = '" . sql_real_escape_string($type) . "',
            dc_price = '{$price}',
            dc_minimum = '{$minimum}',
            dc_qty = '{$qty}',
            dc_jeju_use = '{$jeju_use}',
            dc_jeju_price = '{$jeju_price}',
            dc_island_use = '{$island_use}',
            dc_island_price = '{$island_price}',
            is_default = 0,
            use_yn = 'Y',
            created_at = NOW(),
            updated_at = NOW()");
        $dc_id = sql_insert_id();
    }

    sql_query("DELETE FROM donuts_delivery_condition_ranges WHERE dc_id = '{$dc_id}'");

    if ($type === 'amount_range') {
        $mins = isset($_POST['dc_range_min']) && is_array($_POST['dc_range_min']) ? $_POST['dc_range_min'] : array();
        $maxs = isset($_POST['dc_range_max']) && is_array($_POST['dc_range_max']) ? $_POST['dc_range_max'] : array();
        $fees = isset($_POST['dc_range_price']) && is_array($_POST['dc_range_price']) ? $_POST['dc_range_price'] : array();

        if (!$mins) delivery_redirect('금액 구간을 1개 이상 입력해 주세요.', $return_url);

        $prev_max = null;
        foreach ($mins as $idx => $raw_min) {
            $min = max(0, (int)$raw_min);
            $max_raw = isset($maxs[$idx]) ? trim((string)$maxs[$idx]) : '';
            $max = ($max_raw === '') ? null : max(0, (int)$max_raw);
            $fee = isset($fees[$idx]) ? max(0, (int)$fees[$idx]) : 0;

            if ($idx === 0 && $min !== 0) delivery_redirect('첫 금액 구간은 0원부터 시작해야 합니다.', $return_url);
            if ($max !== null && $max <= $min) delivery_redirect('배송비 구간의 종료 금액은 시작 금액보다 커야 합니다.', $return_url);
            if ($idx > 0 && $prev_max !== null && $min !== $prev_max) delivery_redirect('배송비 구간 사이에 빈 금액이 없도록 입력해 주세요.', $return_url);
            if ($max === null && $idx !== count($mins) - 1) delivery_redirect('상한 없는 구간은 마지막 구간이어야 합니다.', $return_url);

            $max_sql = ($max === null) ? 'NULL' : "'{$max}'";
            $sort = (int)$idx;
            sql_query("INSERT INTO donuts_delivery_condition_ranges SET
                dc_id = '{$dc_id}', min_amount = '{$min}', max_amount = {$max_sql}, dr_price = '{$fee}', sort_order = '{$sort}'");
            $prev_max = $max;
        }

        if ($prev_max !== null) delivery_redirect('마지막 구간은 종료 금액을 비워 상한 없음으로 설정해 주세요.', $return_url);
    }

    delivery_redirect('배송조건이 저장되었습니다.', $return_url);
}

if ($action === 'clone_condition') {
    $dc_id = isset($_POST['dc_id']) ? (int)$_POST['dc_id'] : 0;
    $row = sql_fetch("SELECT * FROM donuts_delivery_conditions WHERE dc_id = '{$dc_id}' AND brand_id = '{$brand_id_sql}' LIMIT 1");
    if (empty($row['dc_id'])) delivery_redirect('복제할 배송조건이 없습니다.', $return_url);

    $base_name = $row['dc_name'] . ' 복사본';
    $name = $base_name;
    $n = 2;
    while (true) {
        $name_sql = sql_real_escape_string($name);
        $dup = sql_fetch("SELECT dc_id FROM donuts_delivery_conditions WHERE brand_id = '{$brand_id_sql}' AND dc_name = '{$name_sql}' LIMIT 1");
        if (empty($dup['dc_id'])) break;
        $name = $base_name . ' ' . $n++;
    }

    $name_sql = sql_real_escape_string($name);
    sql_query("INSERT INTO donuts_delivery_conditions SET
        brand_id = '{$brand_id_sql}', dc_name = '{$name_sql}', dc_type = '" . sql_real_escape_string($row['dc_type']) . "',
        dc_price = '" . (int)$row['dc_price'] . "', dc_minimum = '" . (int)$row['dc_minimum'] . "', dc_qty = '" . (int)$row['dc_qty'] . "',
        dc_jeju_use = '" . (int)$row['dc_jeju_use'] . "', dc_jeju_price = '" . (int)$row['dc_jeju_price'] . "',
        dc_island_use = '" . (int)$row['dc_island_use'] . "', dc_island_price = '" . (int)$row['dc_island_price'] . "',
        is_default = 0, use_yn = 'Y', created_at = NOW(), updated_at = NOW()");
    $new_id = sql_insert_id();

    $ranges = sql_query("SELECT * FROM donuts_delivery_condition_ranges WHERE dc_id = '{$dc_id}' ORDER BY sort_order, dr_id");
    while ($r = sql_fetch_array($ranges)) {
        $max_sql = ($r['max_amount'] === null || $r['max_amount'] === '') ? 'NULL' : "'" . (int)$r['max_amount'] . "'";
        sql_query("INSERT INTO donuts_delivery_condition_ranges SET dc_id = '{$new_id}', min_amount = '" . (int)$r['min_amount'] . "', max_amount = {$max_sql}, dr_price = '" . (int)$r['dr_price'] . "', sort_order = '" . (int)$r['sort_order'] . "'");
    }

    delivery_redirect('배송조건을 복제했습니다.', $return_url);
}

if ($action === 'delete_condition') {
    $dc_id = isset($_POST['dc_id']) ? (int)$_POST['dc_id'] : 0;
    $row = sql_fetch("SELECT * FROM donuts_delivery_conditions WHERE dc_id = '{$dc_id}' AND brand_id = '{$brand_id_sql}' LIMIT 1");
    if (empty($row['dc_id'])) delivery_redirect('삭제할 배송조건이 없습니다.', $return_url);
    if (!empty($row['is_default'])) delivery_redirect('기본 배송조건은 삭제할 수 없습니다.', $return_url);

    $used = sql_fetch("SELECT COUNT(*) AS cnt FROM donuts_delivery_product_settings WHERE brand_id = '{$brand_id_sql}' AND condition_id = '{$dc_id}'");
    if ((int)$used['cnt'] > 0) delivery_redirect('상품에 적용 중인 배송조건은 삭제할 수 없습니다.', $return_url);

    sql_query("DELETE FROM donuts_delivery_condition_ranges WHERE dc_id = '{$dc_id}'");
    sql_query("DELETE FROM donuts_delivery_conditions WHERE dc_id = '{$dc_id}' AND brand_id = '{$brand_id_sql}'");
    delivery_redirect('배송조건을 삭제했습니다.', $return_url);
}

if ($action === 'save_group') {
    $name = isset($_POST['dg_name']) ? trim(clean_xss_tags($_POST['dg_name'], 1, 1)) : '';
    $method = isset($_POST['calc_method']) && strtoupper($_POST['calc_method']) === 'MIN' ? 'MIN' : 'MAX';
    if ($name === '') delivery_redirect('그룹명을 입력해 주세요.', $return_url);
    $name_sql = sql_real_escape_string(mb_substr($name, 0, 100, 'UTF-8'));
    $dup = sql_fetch("SELECT dg_id FROM donuts_delivery_groups WHERE brand_id = '{$brand_id_sql}' AND dg_name = '{$name_sql}' LIMIT 1");
    if (!empty($dup['dg_id'])) delivery_redirect('같은 이름의 묶음배송 그룹이 이미 있습니다.', $return_url);

    sql_query("INSERT INTO donuts_delivery_groups SET brand_id = '{$brand_id_sql}', dg_name = '{$name_sql}', calc_method = '{$method}', use_yn = 'Y', created_at = NOW(), updated_at = NOW()");
    delivery_redirect('묶음배송 그룹이 생성되었습니다.', $return_url);
}

if ($action === 'delete_group') {
    $dg_id = isset($_POST['dg_id']) ? (int)$_POST['dg_id'] : 0;
    $group = sql_fetch("SELECT dg_id FROM donuts_delivery_groups WHERE dg_id = '{$dg_id}' AND brand_id = '{$brand_id_sql}' LIMIT 1");
    if (empty($group['dg_id'])) delivery_redirect('삭제할 그룹이 없습니다.', $return_url);

    $used = sql_fetch("SELECT COUNT(*) AS cnt FROM donuts_delivery_product_settings WHERE brand_id = '{$brand_id_sql}' AND group_id = '{$dg_id}'");
    if ((int)$used['cnt'] > 0) delivery_redirect('상품이 포함된 그룹은 삭제할 수 없습니다. 상품을 먼저 이동해 주세요.', $return_url);

    sql_query("DELETE FROM donuts_delivery_groups WHERE dg_id = '{$dg_id}' AND brand_id = '{$brand_id_sql}'");
    delivery_redirect('묶음배송 그룹을 삭제했습니다.', $return_url);
}


if ($action === 'sync_condition_products') {
    $condition_id = isset($_POST['condition_id']) ? (int)$_POST['condition_id'] : 0;
    $it_ids = isset($_POST['it_ids']) && is_array($_POST['it_ids']) ? $_POST['it_ids'] : array();

    $condition = sql_fetch("
        SELECT dc_id
        FROM donuts_delivery_conditions
        WHERE dc_id = '{$condition_id}'
          AND brand_id = '{$brand_id_sql}'
        LIMIT 1
    ");

    if (empty($condition['dc_id'])) {
        delivery_redirect('배송조건이 올바르지 않습니다.', $return_url);
    }

    if (!$it_ids) {
        delivery_redirect('적용할 상품을 선택해 주세요.', $return_url . '&tab=conditions');
    }

    $saved = 0;

    foreach ($it_ids as $it_id) {
        $it_id = trim($it_id);
        if ($it_id === '') continue;

        $it_id_sql = sql_real_escape_string($it_id);

        $item = sql_fetch("
            SELECT it_id
            FROM {$g5['g5_shop_item_table']}
            WHERE it_id = '{$it_id_sql}'
              AND TRIM(it_brand) = '{$brand_id_sql}'
            LIMIT 1
        ");

        if (empty($item['it_id'])) {
            continue;
        }

        /*
         * 배송조건만 변경하고 기존 묶음배송 그룹(group_id)은 유지합니다.
         */
        sql_query("
            INSERT INTO donuts_delivery_product_settings
                (brand_id, it_id, condition_id, group_id, created_at, updated_at)
            VALUES
                ('{$brand_id_sql}', '{$it_id_sql}', '{$condition_id}', NULL, NOW(), NOW())
            ON DUPLICATE KEY UPDATE
                condition_id = VALUES(condition_id),
                updated_at = NOW()
        ");

        $saved++;
    }

    delivery_redirect(
        number_format($saved) . '개 상품에 배송조건을 적용했습니다.',
        $return_url . '&tab=conditions'
    );
}

if ($action === 'apply_products') {
    $condition_id = isset($_POST['condition_id']) ? (int)$_POST['condition_id'] : 0;
    $group_id = isset($_POST['group_id']) && (int)$_POST['group_id'] > 0 ? (int)$_POST['group_id'] : 0;
    $it_ids = isset($_POST['it_ids']) && is_array($_POST['it_ids']) ? $_POST['it_ids'] : array();

    $condition = sql_fetch("SELECT dc_id FROM donuts_delivery_conditions WHERE dc_id = '{$condition_id}' AND brand_id = '{$brand_id_sql}' LIMIT 1");
    if (empty($condition['dc_id'])) delivery_redirect('배송조건을 선택해 주세요.', $return_url);

    if ($group_id > 0) {
        $group = sql_fetch("SELECT dg_id FROM donuts_delivery_groups WHERE dg_id = '{$group_id}' AND brand_id = '{$brand_id_sql}' LIMIT 1");
        if (empty($group['dg_id'])) delivery_redirect('묶음배송 그룹이 올바르지 않습니다.', $return_url);
    }

    if (!$it_ids) delivery_redirect('적용할 상품을 선택해 주세요.', $return_url);

    foreach ($it_ids as $it_id) {
        $it_id = trim($it_id);
        $it_id_sql = sql_real_escape_string($it_id);
        $item = sql_fetch("SELECT it_id FROM {$g5['g5_shop_item_table']} WHERE it_id = '{$it_id_sql}' AND it_brand = '{$brand_id_sql}' LIMIT 1");
        if (empty($item['it_id'])) continue;

        $group_sql = $group_id > 0 ? "'{$group_id}'" : 'NULL';
        sql_query("INSERT INTO donuts_delivery_product_settings (brand_id, it_id, condition_id, group_id, created_at, updated_at)
            VALUES ('{$brand_id_sql}', '{$it_id_sql}', '{$condition_id}', {$group_sql}, NOW(), NOW())
            ON DUPLICATE KEY UPDATE condition_id = VALUES(condition_id), group_id = VALUES(group_id), updated_at = NOW()");
    }

    delivery_redirect('선택 상품의 배송설정을 저장했습니다.', $return_url . '&tab=individual');
}

if ($action === 'remove_group_product') {
    $group_id = isset($_POST['group_id']) ? (int)$_POST['group_id'] : 0;
    $it_id = isset($_POST['it_id']) ? trim((string)$_POST['it_id']) : '';

    if ($group_id <= 0 || $it_id === '') {
        delivery_redirect(
            '적용 해제할 상품 또는 묶음배송 그룹 정보가 올바르지 않습니다.',
            $return_url . '&tab=groups'
        );
    }

    $it_id_sql = sql_real_escape_string($it_id);

    /*
     * 선택 그룹이 현재 관리 브랜드의 그룹인지 확인
     */
    $group = sql_fetch("
        SELECT dg_id
        FROM donuts_delivery_groups
        WHERE dg_id = '{$group_id}'
          AND brand_id = '{$brand_id_sql}'
        LIMIT 1
    ");

    if (empty($group['dg_id'])) {
        delivery_redirect(
            '묶음배송 그룹이 올바르지 않습니다.',
            $return_url . '&tab=groups'
        );
    }

    /*
     * 상품이 현재 브랜드 소유인지 확인
     */
    $item = sql_fetch("
        SELECT it_id
        FROM {$g5['g5_shop_item_table']}
        WHERE it_id = '{$it_id_sql}'
          AND TRIM(it_brand) = '{$brand_id_sql}'
        LIMIT 1
    ");

    if (empty($item['it_id'])) {
        delivery_redirect(
            '해제할 상품이 없거나 다른 브랜드의 상품입니다.',
            $return_url . '&tab=groups'
        );
    }

    /*
     * condition_id는 그대로 유지하고 group_id만 NULL로 변경.
     * 즉 배송조건은 유지되고 묶음배송에서만 빠집니다.
     */
    $setting = sql_fetch("
        SELECT condition_id, group_id
        FROM donuts_delivery_product_settings
        WHERE brand_id = '{$brand_id_sql}'
          AND it_id = '{$it_id_sql}'
        LIMIT 1
    ");

    if (empty($setting) || (int)$setting['group_id'] !== $group_id) {
        delivery_redirect(
            '해당 상품은 현재 선택한 묶음배송 그룹에 적용되어 있지 않습니다.',
            $return_url . '&tab=groups'
        );
    }

    sql_query("
        UPDATE donuts_delivery_product_settings
        SET group_id = NULL,
            updated_at = NOW()
        WHERE brand_id = '{$brand_id_sql}'
          AND it_id = '{$it_id_sql}'
          AND group_id = '{$group_id}'
    ");

    delivery_redirect(
        '묶음배송 그룹 적용을 해제했습니다.',
        $return_url . '&tab=groups'
    );
}

if ($action === 'move_products') {
    $group_id = isset($_POST['group_id']) ? (int)$_POST['group_id'] : 0;
    $it_ids = isset($_POST['it_ids']) && is_array($_POST['it_ids']) ? $_POST['it_ids'] : array();

    $group = sql_fetch("SELECT dg_id FROM donuts_delivery_groups WHERE dg_id = '{$group_id}' AND brand_id = '{$brand_id_sql}' LIMIT 1");
    if (empty($group['dg_id'])) delivery_redirect('이동할 그룹이 올바르지 않습니다.', $return_url);
    if (!$it_ids) delivery_redirect('이동할 상품을 선택해 주세요.', $return_url);

    $default = donuts_delivery_ensure_default_condition($brand_id);
    foreach ($it_ids as $it_id) {
        $it_id_sql = sql_real_escape_string(trim($it_id));
        $item = sql_fetch("SELECT it_id FROM {$g5['g5_shop_item_table']} WHERE it_id = '{$it_id_sql}' AND it_brand = '{$brand_id_sql}' LIMIT 1");
        if (empty($item['it_id'])) continue;

        sql_query("INSERT INTO donuts_delivery_product_settings (brand_id, it_id, condition_id, group_id, created_at, updated_at)
            VALUES ('{$brand_id_sql}', '{$it_id_sql}', '" . (int)$default['dc_id'] . "', '{$group_id}', NOW(), NOW())
            ON DUPLICATE KEY UPDATE group_id = '{$group_id}', updated_at = NOW()");
    }

    delivery_redirect('선택 상품을 묶음배송 그룹으로 이동했습니다.', $return_url . '&tab=groups');
}

delivery_redirect('처리할 작업이 없습니다.', $return_url);

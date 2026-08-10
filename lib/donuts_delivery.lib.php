<?php
if (!defined('_GNUBOARD_')) exit;

/**
 * 브랜드별 배송조건 / 묶음배송 관리 라이브러리
 * - 관리자 배송관리 페이지
 * - 주문내역 / CSV
 * - 추후 장바구니 / 주문서에서도 그대로 재사용 가능
 */

function donuts_delivery_install()
{
    sql_query("CREATE TABLE IF NOT EXISTS donuts_delivery_conditions (
        dc_id INT NOT NULL AUTO_INCREMENT,
        brand_id VARCHAR(20) NOT NULL DEFAULT '',
        dc_name VARCHAR(100) NOT NULL DEFAULT '',
        dc_type VARCHAR(20) NOT NULL DEFAULT 'conditional',
        dc_price INT NOT NULL DEFAULT 0,
        dc_minimum INT NOT NULL DEFAULT 0,
        dc_qty INT NOT NULL DEFAULT 1,
        dc_jeju_use TINYINT NOT NULL DEFAULT 0,
        dc_jeju_price INT NOT NULL DEFAULT 3000,
        dc_island_use TINYINT NOT NULL DEFAULT 0,
        dc_island_price INT NOT NULL DEFAULT 5000,
        is_default TINYINT NOT NULL DEFAULT 0,
        use_yn CHAR(1) NOT NULL DEFAULT 'Y',
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (dc_id),
        UNIQUE KEY uk_brand_name (brand_id, dc_name),
        KEY idx_brand (brand_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4", false);

    sql_query("CREATE TABLE IF NOT EXISTS donuts_delivery_condition_ranges (
        dr_id INT NOT NULL AUTO_INCREMENT,
        dc_id INT NOT NULL,
        min_amount INT NOT NULL DEFAULT 0,
        max_amount INT NULL DEFAULT NULL,
        dr_price INT NOT NULL DEFAULT 0,
        sort_order INT NOT NULL DEFAULT 0,
        PRIMARY KEY (dr_id),
        KEY idx_condition (dc_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4", false);

    sql_query("CREATE TABLE IF NOT EXISTS donuts_delivery_groups (
        dg_id INT NOT NULL AUTO_INCREMENT,
        brand_id VARCHAR(20) NOT NULL DEFAULT '',
        dg_name VARCHAR(100) NOT NULL DEFAULT '',
        calc_method VARCHAR(3) NOT NULL DEFAULT 'MAX',
        use_yn CHAR(1) NOT NULL DEFAULT 'Y',
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (dg_id),
        UNIQUE KEY uk_brand_group (brand_id, dg_name),
        KEY idx_brand (brand_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4", false);

    sql_query("CREATE TABLE IF NOT EXISTS donuts_delivery_product_settings (
        dps_id INT NOT NULL AUTO_INCREMENT,
        brand_id VARCHAR(20) NOT NULL DEFAULT '',
        it_id VARCHAR(20) NOT NULL DEFAULT '',
        condition_id INT NOT NULL DEFAULT 0,
        group_id INT NULL DEFAULT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (dps_id),
        UNIQUE KEY uk_brand_item (brand_id, it_id),
        KEY idx_condition (condition_id),
        KEY idx_group (group_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4", false);
}

function donuts_delivery_brand_exists($brand_id)
{
    $brand_id = sql_real_escape_string($brand_id);
    $row = sql_fetch("SELECT brand_id FROM donuts_brand WHERE brand_id = '{$brand_id}' LIMIT 1");
    return !empty($row['brand_id']);
}

function donuts_delivery_default_from_brand_config($brand_id)
{
    $out = array(
        'type' => 'conditional',
        'price' => 3000,
        'minimum' => 50000,
        'qty' => 1,
    );

    $brand_id_sql = sql_real_escape_string($brand_id);
    $result = sql_query("SELECT settings_json FROM donuts_brand_config WHERE brand_id = '{$brand_id_sql}' LIMIT 1", false);
    if (!$result) return $out;

    $row = sql_fetch_array($result);
    if (empty($row['settings_json'])) return $out;

    $settings = json_decode($row['settings_json'], true);
    if (!is_array($settings)) return $out;

    $case = isset($settings['de_send_cost_case']) ? trim((string)$settings['de_send_cost_case']) : '';
    if ($case === '무료') {
        $out['type'] = 'free';
        $out['price'] = 0;
        $out['minimum'] = 0;
        return $out;
    }

    $limits = isset($settings['de_send_cost_limit']) ? explode(';', (string)$settings['de_send_cost_limit']) : array();
    $costs = isset($settings['de_send_cost_list']) ? explode(';', (string)$settings['de_send_cost_list']) : array();

    if (!empty($costs[0]) && is_numeric(trim($costs[0]))) {
        $out['price'] = (int)trim($costs[0]);
    }
    if (!empty($limits[0]) && is_numeric(trim($limits[0]))) {
        $out['minimum'] = (int)trim($limits[0]);
    }

    return $out;
}

function donuts_delivery_ensure_default_condition($brand_id)
{
    donuts_delivery_install();

    $brand_id_sql = sql_real_escape_string($brand_id);
    $row = sql_fetch("SELECT * FROM donuts_delivery_conditions WHERE brand_id = '{$brand_id_sql}' AND is_default = 1 LIMIT 1");
    if (!empty($row['dc_id'])) return $row;

    $cfg = donuts_delivery_default_from_brand_config($brand_id);
    sql_query("INSERT INTO donuts_delivery_conditions SET
        brand_id = '{$brand_id_sql}',
        dc_name = '기본 택배',
        dc_type = '" . sql_real_escape_string($cfg['type']) . "',
        dc_price = '" . (int)$cfg['price'] . "',
        dc_minimum = '" . (int)$cfg['minimum'] . "',
        dc_qty = '" . (int)$cfg['qty'] . "',
        dc_jeju_use = 1,
        dc_jeju_price = 3000,
        dc_island_use = 1,
        dc_island_price = 5000,
        is_default = 1,
        use_yn = 'Y'", false);

    return sql_fetch("SELECT * FROM donuts_delivery_conditions WHERE brand_id = '{$brand_id_sql}' AND is_default = 1 LIMIT 1");
}

function donuts_delivery_sync_brand_products($brand_id)
{
    global $g5;

    $default = donuts_delivery_ensure_default_condition($brand_id);
    if (empty($default['dc_id'])) return;

    $brand_id_sql = sql_real_escape_string($brand_id);
    $dc_id = (int)$default['dc_id'];

    $result = sql_query("SELECT it_id FROM {$g5['g5_shop_item_table']} WHERE TRIM(it_brand) = '{$brand_id_sql}'");
    while ($row = sql_fetch_array($result)) {
        $it_id = sql_real_escape_string($row['it_id']);
        sql_query("INSERT IGNORE INTO donuts_delivery_product_settings
            (brand_id, it_id, condition_id, group_id, created_at, updated_at)
            VALUES ('{$brand_id_sql}', '{$it_id}', '{$dc_id}', NULL, NOW(), NOW())", false);
    }
}

function donuts_delivery_condition_ranges($dc_id)
{
    $rows = array();
    $dc_id = (int)$dc_id;
    $result = sql_query("SELECT * FROM donuts_delivery_condition_ranges WHERE dc_id = '{$dc_id}' ORDER BY sort_order, dr_id");
    while ($row = sql_fetch_array($result)) $rows[] = $row;
    return $rows;
}

function donuts_delivery_condition_fee($condition, $item_amount, $qty)
{
    $type = isset($condition['dc_type']) ? $condition['dc_type'] : 'conditional';
    $price = isset($condition['dc_price']) ? (int)$condition['dc_price'] : 0;
    $minimum = isset($condition['dc_minimum']) ? (int)$condition['dc_minimum'] : 0;
    $unit_qty = max(1, isset($condition['dc_qty']) ? (int)$condition['dc_qty'] : 1);

    switch ($type) {
        case 'free':
            return 0;
        case 'paid':
            return max(0, $price);
        case 'quantity':
            return max(0, $price) * (int)ceil(max(0, $qty) / $unit_qty);
        case 'amount_range':
            $ranges = donuts_delivery_condition_ranges((int)$condition['dc_id']);
            foreach ($ranges as $range) {
                $min = (int)$range['min_amount'];
                $max = ($range['max_amount'] === null || $range['max_amount'] === '') ? null : (int)$range['max_amount'];
                if ($item_amount >= $min && ($max === null || $item_amount < $max)) {
                    return max(0, (int)$range['dr_price']);
                }
            }
            return 0;
        case 'conditional':
        default:
            return ($minimum > 0 && $item_amount >= $minimum) ? 0 : max(0, $price);
    }
}

function donuts_delivery_region_type($brand_id, $order)
{
    $addr = isset($order['od_b_addr1']) ? trim($order['od_b_addr1']) : '';
    if ($addr !== '' && mb_strpos($addr, '제주') !== false) return 'jeju';

    // 기존 브랜드별 우편번호 추가배송비 테이블이 있으면 도서산간 판별에 재사용
    $zip = '';
    if (isset($order['od_b_zip1'])) $zip .= $order['od_b_zip1'];
    if (isset($order['od_b_zip2'])) $zip .= $order['od_b_zip2'];
    $zip = preg_replace('/[^0-9]/', '', $zip);

    if ($zip !== '') {
        $brand_id_sql = sql_real_escape_string($brand_id);
        $test = sql_query("SELECT sc_id FROM donuts_brand_sendcost LIMIT 1", false);
        if ($test) {
            $zip_int = (int)$zip;
            $row = sql_fetch("SELECT sc_id FROM donuts_brand_sendcost
                WHERE brand_id = '{$brand_id_sql}'
                  AND CAST(sc_zip1 AS UNSIGNED) <= '{$zip_int}'
                  AND CAST(sc_zip2 AS UNSIGNED) >= '{$zip_int}'
                LIMIT 1");
            if (!empty($row['sc_id'])) return 'island';
        }
    }

    return '';
}

function donuts_delivery_calculate_order_brand($od_id, $brand_id)
{
    global $g5;

    donuts_delivery_install();
    donuts_delivery_sync_brand_products($brand_id);

    $od_id_sql = sql_real_escape_string($od_id);
    $brand_id_sql = sql_real_escape_string($brand_id);

    $order = sql_fetch("SELECT * FROM {$g5['g5_shop_order_table']} WHERE od_id = '{$od_id_sql}' LIMIT 1");

    $items = array();
    $result = sql_query("SELECT
            c.it_id,
            c.ct_qty,
            c.ct_price,
            c.io_type,
            c.io_price,
            i.it_name,
            ps.condition_id,
            ps.group_id,
            dc.dc_name,
            dc.dc_type,
            dc.dc_price,
            dc.dc_minimum,
            dc.dc_qty,
            dc.dc_jeju_use,
            dc.dc_jeju_price,
            dc.dc_island_use,
            dc.dc_island_price,
            dg.dg_name,
            dg.calc_method
        FROM {$g5['g5_shop_cart_table']} c
        INNER JOIN {$g5['g5_shop_item_table']} i ON c.it_id = i.it_id
        LEFT JOIN donuts_delivery_product_settings ps
            ON ps.brand_id = '{$brand_id_sql}' AND ps.it_id = c.it_id
        LEFT JOIN donuts_delivery_conditions dc ON dc.dc_id = ps.condition_id
        LEFT JOIN donuts_delivery_groups dg ON dg.dg_id = ps.group_id
        WHERE c.od_id = '{$od_id_sql}'
          AND TRIM(i.it_brand) = '{$brand_id_sql}'
        ORDER BY c.ct_id ASC");

    $default = donuts_delivery_ensure_default_condition($brand_id);

    while ($row = sql_fetch_array($result)) {
        $it_id = $row['it_id'];
        if (!isset($items[$it_id])) {
            $condition = !empty($row['condition_id']) ? $row : $default;
            $items[$it_id] = array(
                'it_id' => $it_id,
                'it_name' => $row['it_name'],
                'amount' => 0,
                'qty' => 0,
                'condition' => $condition,
                'condition_id' => !empty($row['condition_id']) ? (int)$row['condition_id'] : (int)$default['dc_id'],
                'condition_name' => !empty($row['dc_name']) ? $row['dc_name'] : $default['dc_name'],
                'group_id' => !empty($row['group_id']) ? (int)$row['group_id'] : 0,
                'group_name' => !empty($row['dg_name']) ? $row['dg_name'] : '',
                'calc_method' => !empty($row['calc_method']) ? $row['calc_method'] : 'MAX',
            );
        }

        $line = ((int)$row['io_type'] === 1)
            ? ((int)$row['io_price'] * (int)$row['ct_qty'])
            : (((int)$row['ct_price'] + (int)$row['io_price']) * (int)$row['ct_qty']);

        $items[$it_id]['amount'] += $line;
        $items[$it_id]['qty'] += (int)$row['ct_qty'];
    }

    $region = donuts_delivery_region_type($brand_id, $order);
    $item_total = 0;
    $item_base_fees = array();
    $item_charges = array();
    $item_rule_names = array();
    $item_group_names = array();
    $groups = array();

    foreach ($items as $it_id => &$item) {
        $item_total += $item['amount'];
        $condition = $item['condition'];
        $fee = donuts_delivery_condition_fee($condition, $item['amount'], $item['qty']);

        if ($region === 'jeju' && !empty($condition['dc_jeju_use'])) {
            $fee += (int)$condition['dc_jeju_price'];
        } elseif ($region === 'island' && !empty($condition['dc_island_use'])) {
            $fee += (int)$condition['dc_island_price'];
        }

        $item['fee'] = max(0, (int)$fee);
        $item_base_fees[$it_id] = $item['fee'];
        $item_charges[$it_id] = 0;
        $item_rule_names[$it_id] = $item['condition_name'];
        $item_group_names[$it_id] = $item['group_name'];

        if ($item['group_id'] > 0) {
            if (!isset($groups[$item['group_id']])) {
                $groups[$item['group_id']] = array(
                    'name' => $item['group_name'],
                    'method' => strtoupper($item['calc_method']) === 'MIN' ? 'MIN' : 'MAX',
                    'items' => array(),
                );
            }
            $groups[$item['group_id']]['items'][$it_id] = $item['fee'];
        } else {
            $item_charges[$it_id] = $item['fee'];
        }
    }
    unset($item);

    foreach ($groups as $group) {
        if (!$group['items']) continue;
        $target_fee = $group['method'] === 'MIN' ? min($group['items']) : max($group['items']);
        $chosen_it = null;
        foreach ($group['items'] as $it_id => $fee) {
            if ($chosen_it === null && (int)$fee === (int)$target_fee) $chosen_it = $it_id;
        }
        if ($chosen_it !== null) $item_charges[$chosen_it] += (int)$target_fee;
    }

    return array(
        'brand_id' => $brand_id,
        'region' => $region,
        'item_total' => (int)$item_total,
        'shipping_total' => (int)array_sum($item_charges),
        'item_base_fees' => $item_base_fees,
        'item_charges' => $item_charges,
        'item_rule_names' => $item_rule_names,
        'item_group_names' => $item_group_names,
        'items' => $items,
    );
}

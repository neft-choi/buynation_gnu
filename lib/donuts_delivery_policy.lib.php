<?php
if (!defined('_GNUBOARD_')) exit;

/*
 * donuts_delivery_policy.lib.php
 *
 * 배송관리에서 구현한 현재 정책을 관리자/CSV/사용자 페이지에서 공통으로 사용하기 위한 계산 라이브러리.
 *
 * 반영 정책:
 * - donuts_delivery_product_settings 상품별 배송조건
 * - donuts_delivery_conditions: paid / conditional / free / quantity / amount_range
 * - donuts_delivery_condition_ranges 금액구간
 * - donuts_delivery_groups MIN / MAX 묶음배송
 * - 제주/도서산간 추가 배송비
 * - 상품 옵션 추가금액 포함 계산
 */

if (!function_exists('donuts_delivery_policy_is_island_zip')) {
    function donuts_delivery_policy_is_island_zip($zip)
    {
        global $g5;

        $zip = preg_replace('/[^0-9]/', '', (string)$zip);

        if ($zip === '') {
            return false;
        }

        $zip_num = (int)$zip;

        $result = sql_query("
            SELECT sc_zip1, sc_zip2
            FROM {$g5['g5_shop_sendcost_table']}
        ", false);

        if (!$result) {
            return false;
        }

        while ($row = sql_fetch_array($result)) {
            $from = (int)preg_replace('/[^0-9]/', '', (string)$row['sc_zip1']);
            $to   = (int)preg_replace('/[^0-9]/', '', (string)$row['sc_zip2']);

            if ($from <= $zip_num && $zip_num <= $to) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('donuts_delivery_policy_brand_default_cost')) {
    function donuts_delivery_policy_brand_default_cost($brand_id, $order_price)
    {
        $brand_id = trim((string)$brand_id);

        if ($brand_id === '') {
            return 0;
        }

        $brand_id_sql = sql_real_escape_string($brand_id);

        $bs_result = sql_query("
            SELECT *
            FROM donuts_brand_settings
            WHERE brand_id = '{$brand_id_sql}'
            LIMIT 1
        ", false);

        if (!$bs_result) {
            return 0;
        }

        $settings = sql_fetch_array($bs_result);

        if (empty($settings)) {
            return 0;
        }

        $case = isset($settings['de_send_cost_case']) ? trim($settings['de_send_cost_case']) : '';

        if ($case === '무료') {
            return 0;
        }

        $limits = isset($settings['de_send_cost_limit'])
            ? explode(';', $settings['de_send_cost_limit'])
            : array();

        $costs = isset($settings['de_send_cost_list'])
            ? explode(';', $settings['de_send_cost_list'])
            : array();

        foreach ($limits as $idx => $limit) {
            $limit = trim($limit);

            if ($limit === '' || !is_numeric($limit)) {
                continue;
            }

            if ($order_price < (int)$limit) {
                return (isset($costs[$idx]) && is_numeric(trim($costs[$idx])))
                    ? (int)trim($costs[$idx])
                    : 0;
            }
        }

        return 0;
    }
}

if (!function_exists('donuts_delivery_policy_condition_fee')) {
    function donuts_delivery_policy_condition_fee($condition, $item_amount, $item_qty)
    {
        global $g5;

        if (empty($condition)) {
            return 0;
        }

        $type = isset($condition['dc_type'])
            ? trim((string)$condition['dc_type'])
            : '';

        $price = isset($condition['dc_price'])
            ? max(0, (int)$condition['dc_price'])
            : 0;

        $minimum = isset($condition['dc_minimum'])
            ? max(0, (int)$condition['dc_minimum'])
            : 0;

        $qty_unit = isset($condition['dc_qty'])
            ? max(1, (int)$condition['dc_qty'])
            : 1;

        switch ($type) {
            case 'paid':
                return $price;

            case 'free':
                return 0;

            case 'quantity':
                return $price * (int)ceil(max(0, $item_qty) / $qty_unit);

            case 'amount_range':
                $dc_id = isset($condition['dc_id']) ? (int)$condition['dc_id'] : 0;

                if ($dc_id < 1) {
                    return 0;
                }

                $result = sql_query("
                    SELECT min_amount, max_amount, dr_price
                    FROM donuts_delivery_condition_ranges
                    WHERE dc_id = '{$dc_id}'
                    ORDER BY min_amount ASC
                ", false);

                if (!$result) {
                    return 0;
                }

                while ($range = sql_fetch_array($result)) {
                    $min = (int)$range['min_amount'];

                    $max = (
                        $range['max_amount'] === null ||
                        $range['max_amount'] === ''
                    )
                        ? null
                        : (int)$range['max_amount'];

                    if (
                        $item_amount >= $min &&
                        ($max === null || $item_amount < $max)
                    ) {
                        return max(0, (int)$range['dr_price']);
                    }
                }

                return 0;

            case 'conditional':
            default:
                if ($minimum > 0 && $item_amount >= $minimum) {
                    return 0;
                }

                return $price;
        }
    }
}

if (!function_exists('donuts_delivery_policy_calc_by_cart_id')) {
    function donuts_delivery_policy_calc_by_cart_id($cart_id, $brand_id, $receiver_addr = '', $receiver_zip = '')
    {
        global $g5;

        $data = array(
            'item_total' => 0,
            'shipping_total' => 0,
            'item_charges' => array(),
            'item_types' => array(),
            'item_methods' => array(),
            'item_groups' => array()
        );

        $cart_id = trim((string)$cart_id);
        $brand_id = trim((string)$brand_id);

        if ($cart_id === '' || $brand_id === '') {
            return $data;
        }

        $cart_id_sql = sql_real_escape_string($cart_id);
        $brand_id_sql = sql_real_escape_string($brand_id);

        $default_condition = array();

        $default_result = sql_query("
            SELECT *
            FROM donuts_delivery_conditions
            WHERE brand_id = '{$brand_id_sql}'
              AND is_default = 1
              AND use_yn = 'Y'
            ORDER BY dc_id DESC
            LIMIT 1
        ", false);

        if ($default_result) {
            $default_condition = sql_fetch_array($default_result);
        }

        $item_result = sql_query("
            SELECT
                c.it_id,
                SUM(
                    IF(
                        c.io_type = 1,
                        c.io_price * c.ct_qty,
                        (c.ct_price + c.io_price) * c.ct_qty
                    )
                ) AS item_amount,
                SUM(c.ct_qty) AS item_qty
            FROM {$g5['g5_shop_cart_table']} c
            INNER JOIN {$g5['g5_shop_item_table']} i
                ON i.it_id = c.it_id
            WHERE c.od_id = '{$cart_id_sql}'
              AND TRIM(i.it_brand) = '{$brand_id_sql}'
            GROUP BY c.it_id
            ORDER BY MIN(c.ct_id) ASC
        ", false);

        if (!$item_result) {
            return $data;
        }

        $items = array();

        while ($item = sql_fetch_array($item_result)) {
            $it_id = trim((string)$item['it_id']);

            if ($it_id === '') {
                continue;
            }

            $it_id_sql = sql_real_escape_string($it_id);

            $setting = array();

            $setting_result = sql_query("
                SELECT condition_id, group_id
                FROM donuts_delivery_product_settings
                WHERE brand_id = '{$brand_id_sql}'
                  AND it_id = '{$it_id_sql}'
                LIMIT 1
            ", false);

            if ($setting_result) {
                $setting = sql_fetch_array($setting_result);
            }

            $condition_id = !empty($setting['condition_id'])
                ? (int)$setting['condition_id']
                : 0;

            $group_id = !empty($setting['group_id'])
                ? (int)$setting['group_id']
                : 0;

            $condition = array();

            if ($condition_id > 0) {
                $condition_result = sql_query("
                    SELECT *
                    FROM donuts_delivery_conditions
                    WHERE dc_id = '{$condition_id}'
                      AND brand_id = '{$brand_id_sql}'
                      AND use_yn = 'Y'
                    LIMIT 1
                ", false);

                if ($condition_result) {
                    $condition = sql_fetch_array($condition_result);
                }
            }

            if (empty($condition) && !empty($default_condition)) {
                $condition = $default_condition;
            }

            $item_amount = (int)$item['item_amount'];
            $item_qty = (int)$item['item_qty'];

            $data['item_total'] += $item_amount;

            if (!empty($condition)) {
                $fee = donuts_delivery_policy_condition_fee($condition, $item_amount, $item_qty);

                $condition_name = !empty($condition['dc_name'])
                    ? $condition['dc_name']
                    : '배송조건';

                $is_jeju = (
                    function_exists('mb_strpos') &&
                    mb_strpos((string)$receiver_addr, '제주') !== false
                );

                if ($is_jeju && !empty($condition['dc_jeju_use'])) {
                    $fee += max(0, (int)$condition['dc_jeju_price']);
                    $condition_name .= ' + 제주추가';
                } elseif (
                    !empty($condition['dc_island_use']) &&
                    donuts_delivery_policy_is_island_zip($receiver_zip)
                ) {
                    $fee += max(0, (int)$condition['dc_island_price']);
                    $condition_name .= ' + 도서산간추가';
                }
            } else {
                $fee = donuts_delivery_policy_brand_default_cost($brand_id, $item_amount);
                $condition_name = '브랜드 기본 배송비';
            }

            $group_name = '';
            $calc_method = 'MAX';

            if ($group_id > 0) {
                $group_result = sql_query("
                    SELECT dg_name, calc_method
                    FROM donuts_delivery_groups
                    WHERE dg_id = '{$group_id}'
                      AND brand_id = '{$brand_id_sql}'
                      AND use_yn = 'Y'
                    LIMIT 1
                ", false);

                if ($group_result) {
                    $group = sql_fetch_array($group_result);

                    if (!empty($group['dg_name'])) {
                        $group_name = $group['dg_name'];
                    }

                    if (isset($group['calc_method']) && strtoupper($group['calc_method']) === 'MIN') {
                        $calc_method = 'MIN';
                    }
                }

                if ($group_name === '') {
                    $group_id = 0;
                }
            }

            $items[$it_id] = array(
                'fee' => max(0, (int)$fee),
                'condition_name' => $condition_name,
                'group_id' => $group_id,
                'group_name' => $group_name,
                'calc_method' => $calc_method
            );

            $data['item_charges'][$it_id] = 0;
            $data['item_types'][$it_id] = $condition_name;
            $data['item_groups'][$it_id] = $group_name;
            $data['item_methods'][$it_id] = $fee > 0 ? '선불' : '무료';
        }

        $groups = array();

        foreach ($items as $it_id => $item) {
            if ($item['group_id'] < 1) {
                $data['item_charges'][$it_id] = $item['fee'];
                $data['shipping_total'] += $item['fee'];
                continue;
            }

            $gid = $item['group_id'];

            if (!isset($groups[$gid])) {
                $groups[$gid] = array(
                    'name' => $item['group_name'],
                    'method' => $item['calc_method'],
                    'items' => array()
                );
            }

            $groups[$gid]['items'][$it_id] = $item['fee'];
        }

        foreach ($groups as $gid => $group) {
            if (empty($group['items'])) {
                continue;
            }

            $selected_it_id = '';
            $selected_fee = null;

            foreach ($group['items'] as $it_id => $fee) {
                if ($selected_fee === null) {
                    $selected_it_id = $it_id;
                    $selected_fee = $fee;
                    continue;
                }

                if ($group['method'] === 'MIN' && $fee < $selected_fee) {
                    $selected_it_id = $it_id;
                    $selected_fee = $fee;
                }

                if ($group['method'] !== 'MIN' && $fee > $selected_fee) {
                    $selected_it_id = $it_id;
                    $selected_fee = $fee;
                }
            }

            $selected_fee = max(0, (int)$selected_fee);

            if ($selected_it_id !== '') {
                $data['item_charges'][$selected_it_id] = $selected_fee;
                $data['shipping_total'] += $selected_fee;
            }

            foreach ($group['items'] as $it_id => $fee) {
                $data['item_groups'][$it_id] = $group['name'] . ' (' . $group['method'] . ')';

                if ($it_id !== $selected_it_id) {
                    $data['item_methods'][$it_id] = '묶음배송';
                } else {
                    $data['item_methods'][$it_id] = $selected_fee > 0 ? '선불' : '무료';
                }

                $data['item_types'][$it_id] .= ' / ' . $group['name'] . ' ' . $group['method'];
            }
        }

        return $data;
    }
}

if (!function_exists('donuts_delivery_policy_calc_all_brands_by_cart_id')) {
    function donuts_delivery_policy_calc_all_brands_by_cart_id($cart_id, $receiver_addr = '', $receiver_zip = '')
    {
        global $g5;

        $total = array(
            'item_total' => 0,
            'shipping_total' => 0,
            'brands' => array(),
            'item_charges' => array(),
            'item_types' => array(),
            'item_methods' => array(),
            'item_groups' => array()
        );

        $cart_id = trim((string)$cart_id);

        if ($cart_id === '') {
            return $total;
        }

        $cart_id_sql = sql_real_escape_string($cart_id);

        $brand_result = sql_query("
            SELECT DISTINCT TRIM(i.it_brand) AS brand_id
            FROM {$g5['g5_shop_cart_table']} c
            INNER JOIN {$g5['g5_shop_item_table']} i
                ON i.it_id = c.it_id
            WHERE c.od_id = '{$cart_id_sql}'
              AND TRIM(i.it_brand) <> ''
        ", false);

        if (!$brand_result) {
            return $total;
        }

        while ($brand = sql_fetch_array($brand_result)) {
            $brand_id = trim((string)$brand['brand_id']);

            if ($brand_id === '') {
                continue;
            }

            $calc = donuts_delivery_policy_calc_by_cart_id($cart_id, $brand_id, $receiver_addr, $receiver_zip);

            $total['brands'][$brand_id] = $calc;
            $total['item_total'] += (int)$calc['item_total'];
            $total['shipping_total'] += (int)$calc['shipping_total'];

            foreach (array('item_charges', 'item_types', 'item_methods', 'item_groups') as $key) {
                foreach ($calc[$key] as $it_id => $value) {
                    $total[$key][$it_id] = $value;
                }
            }
        }

        return $total;
    }
}

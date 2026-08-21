<?php
if (!defined('_GNUBOARD_')) exit;

/*
 * donuts_delivery_policy.lib.php
 *
 * 주문내역 CSV에서 확정한 최종 배송비 규칙을 모든 화면에서 공통 사용.
 *
 * 핵심:
 * - 같은 주문번호(od_id)를 계산 단위로 사용
 * - 상품가격 + 옵션가격 + 배송조건 + 묶음배송 조건으로 배송비 계산
 * - 기존 od_send_cost / ct_send_cost 값을 계산 근거로 사용하지 않음
 * - 묶음배송 조건부무료/금액구간은 같은 주문번호 전체 상품금액 기준
 */

if (!function_exists('donuts_delivery_policy_is_island_zip')) {
    function donuts_delivery_policy_is_island_zip($zip)
    {
        global $g5;

        $zip = preg_replace('/[^0-9]/', '', (string)$zip);
        if ($zip === '') return false;

        $zip_num = (int)$zip;

        $result = sql_query("
            SELECT sc_zip1, sc_zip2
            FROM {$g5['g5_shop_sendcost_table']}
        ", false);

        if (!$result) return false;

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

if (!function_exists('donuts_delivery_policy_condition_fee')) {
    function donuts_delivery_policy_condition_fee($condition, $amount, $qty)
    {
        global $g5;

        if (empty($condition)) return 0;

        $type = isset($condition['dc_type']) ? trim((string)$condition['dc_type']) : '';
        $price = isset($condition['dc_price']) ? max(0, (int)$condition['dc_price']) : 0;
        $minimum = isset($condition['dc_minimum']) ? max(0, (int)$condition['dc_minimum']) : 0;
        $qty_unit = isset($condition['dc_qty']) ? max(1, (int)$condition['dc_qty']) : 1;

        switch ($type) {
            case 'paid':
                return $price;

            case 'free':
                return 0;

            case 'quantity':
                return $price * (int)ceil(max(0, (int)$qty) / $qty_unit);

            case 'amount_range':
                $dc_id = isset($condition['dc_id']) ? (int)$condition['dc_id'] : 0;
                if ($dc_id < 1) return 0;

                $result = sql_query("
                    SELECT min_amount, max_amount, dr_price
                    FROM donuts_delivery_condition_ranges
                    WHERE dc_id = '{$dc_id}'
                    ORDER BY min_amount ASC
                ", false);

                if (!$result) return 0;

                while ($range = sql_fetch_array($result)) {
                    $min = (int)$range['min_amount'];
                    $max = ($range['max_amount'] === null || $range['max_amount'] === '')
                        ? null
                        : (int)$range['max_amount'];

                    if ((int)$amount >= $min && ($max === null || (int)$amount < $max)) {
                        return max(0, (int)$range['dr_price']);
                    }
                }

                return 0;

            case 'conditional':
            default:
                if ($minimum > 0 && (int)$amount >= $minimum) {
                    return 0;
                }

                return $price;
        }
    }
}

if (!function_exists('donuts_delivery_policy_brand_fallback')) {
    function donuts_delivery_policy_brand_fallback($brand_id, $amount)
    {
        $brand_id = trim((string)$brand_id);
        if ($brand_id === '') return 0;

        $brand_sql = sql_real_escape_string($brand_id);

        $result = sql_query("
            SELECT *
            FROM donuts_brand_settings
            WHERE brand_id = '{$brand_sql}'
            LIMIT 1
        ", false);

        if (!$result) return 0;

        $settings = sql_fetch_array($result);
        if (empty($settings)) return 0;

        $case = isset($settings['de_send_cost_case']) ? trim((string)$settings['de_send_cost_case']) : '';

        if ($case === '무료') return 0;

        $limits = isset($settings['de_send_cost_limit']) ? explode(';', $settings['de_send_cost_limit']) : array();
        $costs  = isset($settings['de_send_cost_list']) ? explode(';', $settings['de_send_cost_list']) : array();

        foreach ($limits as $idx => $limit) {
            $limit = trim($limit);

            if ($limit === '' || !is_numeric($limit)) continue;

            if ((int)$amount < (int)$limit) {
                return (isset($costs[$idx]) && is_numeric(trim($costs[$idx])))
                    ? (int)trim($costs[$idx])
                    : 0;
            }
        }

        return 0;
    }
}

if (!function_exists('donuts_delivery_policy_item_total')) {
    function donuts_delivery_policy_item_total($od_id, $brand_id = '', $selected_only = false)
    {
        global $g5;

        $od_id = trim((string)$od_id);
        $brand_id = trim((string)$brand_id);

        if ($od_id === '') return 0;

        $od_sql = sql_real_escape_string($od_id);
        $brand_where = '';
        $select_where = $selected_only ? " AND c.ct_select = '1' " : '';

        if ($brand_id !== '') {
            $brand_sql = sql_real_escape_string($brand_id);
            $brand_where = " AND TRIM(i.it_brand) = '{$brand_sql}' ";
        }

        $row = sql_fetch("
            SELECT SUM(
                IF(
                    c.io_type = 1,
                    c.io_price * c.ct_qty,
                    (c.ct_price + c.io_price) * c.ct_qty
                )
            ) AS total_price
            FROM {$g5['g5_shop_cart_table']} c
            INNER JOIN {$g5['g5_shop_item_table']} i
                ON i.it_id = c.it_id
            WHERE c.od_id = '{$od_sql}'
            {$select_where}
            {$brand_where}
        ");

        return isset($row['total_price']) ? max(0, (int)$row['total_price']) : 0;
    }
}


if (!function_exists('donuts_delivery_policy_default_special_non_group_items')) {
    function donuts_delivery_policy_default_special_non_group_items($items)
    {
        $default_ids = array();
        $special_ids = array();

        foreach ($items as $item) {
            $it_id = isset($item['it_id']) ? trim((string)$item['it_id']) : '';
            $brand_id = isset($item['brand_id']) ? trim((string)$item['brand_id']) : '';

            if ($it_id === '') continue;

            $it_id_sql = sql_real_escape_string($it_id);
            $brand_sql = sql_real_escape_string($brand_id);
            $setting = array();

            if ($brand_id !== '') {
                $r = sql_query("
                    SELECT brand_id, condition_id, group_id
                    FROM donuts_delivery_product_settings
                    WHERE it_id = '{$it_id_sql}'
                      AND brand_id = '{$brand_sql}'
                    LIMIT 1
                ", false);
                if ($r) $setting = sql_fetch_array($r);
            }

            if (empty($setting)) {
                $r = sql_query("
                    SELECT brand_id, condition_id, group_id
                    FROM donuts_delivery_product_settings
                    WHERE it_id = '{$it_id_sql}'
                    LIMIT 1
                ", false);
                if ($r) $setting = sql_fetch_array($r);
            }

            // 실제 묶음배송 상품은 특례에서 완전히 제외
            if (!empty($setting['group_id']) && (int)$setting['group_id'] > 0) {
                continue;
            }

            $setting_brand = !empty($setting['brand_id'])
                ? trim((string)$setting['brand_id'])
                : $brand_id;

            $setting_brand_sql = sql_real_escape_string($setting_brand);
            $condition_id = !empty($setting['condition_id']) ? (int)$setting['condition_id'] : 0;
            $condition = array();
            $is_default = false;

            if ($condition_id > 0) {
                $r = sql_query("
                    SELECT *
                    FROM donuts_delivery_conditions
                    WHERE dc_id = '{$condition_id}'
                      AND use_yn = 'Y'
                    LIMIT 1
                ", false);
                if ($r) $condition = sql_fetch_array($r);

                if (!empty($condition) && !empty($condition['is_default'])) {
                    $is_default = true;
                }
            }

            if (empty($condition) && $setting_brand !== '') {
                $r = sql_query("
                    SELECT *
                    FROM donuts_delivery_conditions
                    WHERE brand_id = '{$setting_brand_sql}'
                      AND is_default = 1
                      AND use_yn = 'Y'
                    ORDER BY dc_id DESC
                    LIMIT 1
                ", false);
                if ($r) $condition = sql_fetch_array($r);
                if (!empty($condition)) $is_default = true;
            }

            $type = !empty($condition['dc_type']) ? trim((string)$condition['dc_type']) : '';

            if ($is_default) $default_ids[$it_id] = true;
            if (!$is_default && in_array($type, array('paid', 'quantity', 'amount_range'), true)) $special_ids[$it_id] = true;
        }

        if (empty($default_ids) || empty($special_ids)) {
            return array();
        }

        return $default_ids + $special_ids;
    }
}

if (!function_exists('donuts_delivery_policy_final_shipping')) {
    function donuts_delivery_policy_final_shipping(
        $od_id,
        $brand_id = '',
        $receiver_addr = '',
        $receiver_zip = '',
        $selected_only = false
    ) {
        global $g5;

        $od_id = trim((string)$od_id);
        $brand_id = trim((string)$brand_id);

        if ($od_id === '') return 0;

        $od_id_sql = sql_real_escape_string($od_id);
        $brand_where = '';
        $select_where = $selected_only ? " AND c.ct_select = '1' " : '';

        if ($brand_id !== '') {
            $brand_id_sql = sql_real_escape_string($brand_id);
            $brand_where = " AND TRIM(i.it_brand) = '{$brand_id_sql}' ";
        }

        /*
         * 같은 주문번호의 계산 대상 상품을 먼저 모두 집계.
         */
        $result = sql_query("
            SELECT
                c.it_id,
                TRIM(i.it_brand) AS item_brand_id,
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
            WHERE c.od_id = '{$od_id_sql}'
            {$select_where}
            {$brand_where}
            GROUP BY c.it_id, i.it_brand
            ORDER BY MIN(c.ct_id)
        ", false);

        if (!$result) return 0;

        $items = array();

        while ($row = sql_fetch_array($result)) {
            $it_id = trim((string)$row['it_id']);
            if ($it_id === '') continue;

            $items[] = array(
                'it_id' => $it_id,
                'brand_id' => trim((string)$row['item_brand_id']),
                'amount' => (int)$row['item_amount'],
                'qty' => (int)$row['item_qty']
            );
        }

        if (empty($items)) return 0;

        // group_id가 없는 기본+유료/수량/금액구간 혼합 상품만 특례 대상으로 지정
        $default_special_non_group_items =
            donuts_delivery_policy_default_special_non_group_items($items);

        /*
         * 묶음배송 무료조건은 "같은 주문번호 전체 상품금액" 기준.
         * 브랜드 관리자 화면에서도 무료기준은 주문번호 전체 기준을 유지.
         */
        $all_select_where = $selected_only ? " AND ct_select = '1' " : '';

        $total_row = sql_fetch("
            SELECT SUM(
                IF(
                    io_type = 1,
                    io_price * ct_qty,
                    (ct_price + io_price) * ct_qty
                )
            ) AS total_amount
            FROM {$g5['g5_shop_cart_table']}
            WHERE od_id = '{$od_id_sql}'
            {$all_select_where}
        ");

        $order_total = isset($total_row['total_amount']) ? (int)$total_row['total_amount'] : 0;

        if ($order_total <= 0) {
            foreach ($items as $tmp) {
                $order_total += (int)$tmp['amount'];
            }
        }

        $individual_total = 0;
        $bundle_candidates = array();

        foreach ($items as $item) {
            $it_id = $item['it_id'];
            $item_brand = $item['brand_id'];
            $item_amount = (int)$item['amount'];
            $item_qty = (int)$item['qty'];

            $it_id_sql = sql_real_escape_string($it_id);
            $item_brand_sql = sql_real_escape_string($item_brand);

            /*
             * 상품 배송 설정.
             * 스키마에 확실히 존재하는 컬럼만 사용.
             */
            $setting = array();

            if ($item_brand !== '') {
                $sr = sql_query("
                    SELECT brand_id, condition_id, group_id
                    FROM donuts_delivery_product_settings
                    WHERE it_id = '{$it_id_sql}'
                      AND brand_id = '{$item_brand_sql}'
                    LIMIT 1
                ", false);

                if ($sr) $setting = sql_fetch_array($sr);
            }

            if (empty($setting)) {
                $sr = sql_query("
                    SELECT brand_id, condition_id, group_id
                    FROM donuts_delivery_product_settings
                    WHERE it_id = '{$it_id_sql}'
                    LIMIT 1
                ", false);

                if ($sr) $setting = sql_fetch_array($sr);
            }

            $setting_brand = !empty($setting['brand_id'])
                ? trim((string)$setting['brand_id'])
                : $item_brand;

            $setting_brand_sql = sql_real_escape_string($setting_brand);

            $condition_id = !empty($setting['condition_id'])
                ? (int)$setting['condition_id']
                : 0;

            $group_id = !empty($setting['group_id'])
                ? (int)$setting['group_id']
                : 0;

            /*
             * 배송조건.
             */
            $condition = array();

            if ($condition_id > 0) {
                $cr = sql_query("
                    SELECT *
                    FROM donuts_delivery_conditions
                    WHERE dc_id = '{$condition_id}'
                      AND use_yn = 'Y'
                    LIMIT 1
                ", false);

                if ($cr) $condition = sql_fetch_array($cr);
            }

            if (empty($condition) && $setting_brand !== '') {
                $cr = sql_query("
                    SELECT *
                    FROM donuts_delivery_conditions
                    WHERE brand_id = '{$setting_brand_sql}'
                      AND is_default = 1
                      AND use_yn = 'Y'
                    ORDER BY dc_id DESC
                    LIMIT 1
                ", false);

                if ($cr) $condition = sql_fetch_array($cr);
            }

            /*
             * 묶음배송 그룹.
             */
            $group = array();

            if ($group_id > 0) {
                $gr = sql_query("
                    SELECT *
                    FROM donuts_delivery_groups
                    WHERE dg_id = '{$group_id}'
                      AND use_yn = 'Y'
                    LIMIT 1
                ", false);

                if ($gr) $group = sql_fetch_array($gr);

                if (empty($group)) {
                    $group_id = 0;
                }
            }

            /*
             * 운영 데이터에서 group_id가 누락된 경우에도
             * 같은 주문번호에 여러 상품 + 같은 conditional/amount_range 조건이면
             * 주문번호 전체 금액 기준 묶음조건으로 처리.
             */
            $is_bundle = ($group_id > 0);

            $condition_type = !empty($condition['dc_type'])
                ? trim((string)$condition['dc_type'])
                : '';

            $is_default_paid_special_item =
                isset($default_special_non_group_items[$it_id]);

            if (
                !$is_default_paid_special_item &&
                !$is_bundle &&
                count($items) > 1 &&
                in_array($condition_type, array('conditional', 'amount_range'), true)
            ) {
                $is_bundle = true;
            }

            $base_amount = $is_bundle ? $order_total : $item_amount;

            if (!empty($condition)) {
                $fee = donuts_delivery_policy_condition_fee(
                    $condition,
                    $base_amount,
                    $item_qty
                );

                $is_jeju = (
                    function_exists('mb_strpos') &&
                    mb_strpos((string)$receiver_addr, '제주') !== false
                );

                if ($is_jeju && !empty($condition['dc_jeju_use'])) {
                    $fee += max(0, (int)$condition['dc_jeju_price']);
                } elseif (
                    !empty($condition['dc_island_use']) &&
                    donuts_delivery_policy_is_island_zip($receiver_zip)
                ) {
                    $fee += max(0, (int)$condition['dc_island_price']);
                }
            } else {
                $fee = donuts_delivery_policy_brand_fallback(
                    $setting_brand,
                    $base_amount
                );
            }

            $fee = max(0, (int)$fee);

            if (!$is_bundle) {
                $individual_total += $fee;
                continue;
            }

            if ($group_id > 0) {
                $bundle_key =
                    $od_id . '|G|' .
                    $setting_brand . '|' .
                    $group_id;

                $method = (
                    !empty($group['calc_method']) &&
                    strtoupper($group['calc_method']) === 'MIN'
                ) ? 'MIN' : 'MAX';
            } else {
                $bundle_key =
                    $od_id . '|C|' .
                    $setting_brand . '|' .
                    $condition_id;

                $method = 'MAX';
            }

            if (!isset($bundle_candidates[$bundle_key])) {
                $bundle_candidates[$bundle_key] = array(
                    'method' => $method,
                    'fees' => array()
                );
            }

            $bundle_candidates[$bundle_key]['fees'][] = $fee;
        }

        $bundle_total = 0;

        foreach ($bundle_candidates as $bundle) {
            if (empty($bundle['fees'])) continue;

            if ($bundle['method'] === 'MIN') {
                $bundle_total += min($bundle['fees']);
            } else {
                $bundle_total += max($bundle['fees']);
            }
        }

        return max(0, (int)$individual_total + (int)$bundle_total);
    }
}

if (!function_exists('donuts_delivery_policy_quote')) {
    function donuts_delivery_policy_quote(
        $od_id,
        $brand_id = '',
        $receiver_addr = '',
        $receiver_zip = '',
        $selected_only = false
    ) {
        $item_total = donuts_delivery_policy_item_total(
            $od_id,
            $brand_id,
            $selected_only
        );

        $shipping_total = donuts_delivery_policy_final_shipping(
            $od_id,
            $brand_id,
            $receiver_addr,
            $receiver_zip,
            $selected_only
        );

        return array(
            'item_total' => (int)$item_total,
            'shipping_total' => (int)$shipping_total,
            'order_total' => (int)$item_total + (int)$shipping_total
        );
    }
}

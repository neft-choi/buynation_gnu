<?php
if (!defined('_GNUBOARD_')) exit;

/**
 * 주문 CSV 배송비 계산 전용 라이브러리
 *
 * 중요:
 * - 아래 함수 본문은 기존 orderlist_csv.php의 동작을 그대로 옮긴 것입니다.
 * - 배송조건, 묶음배송, 제주/도서산간, 지역 추가비 계산 로직은 변경하지 않았습니다.
 * - 기능 변경 없이 메인 CSV 파일의 책임만 분리합니다.
 */

function csv_brand_send_cost($settings, $order_price)
{
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


/*
 * ============================================================
 * 새 배송관리 정책 기준 CSV 배송비 계산
 * ============================================================
 *
 * 우선순위:
 * 1. donuts_delivery_product_settings에 연결된 배송조건
 * 2. 연결값이 없으면 해당 브랜드의 기본 배송조건(is_default=1)
 * 3. 기본 배송조건도 없으면 donuts_brand_settings 기존 기본 배송비
 *
 * 지원 정책:
 * - paid          : 유료
 * - conditional   : 조건부 무료
 * - free          : 무료
 * - quantity      : 수량별
 * - amount_range  : 금액 구간별
 * - 묶음배송 그룹 MIN / MAX
 * - 제주 추가비
 * - 도서산간 추가비(기존 추가배송비 우편번호 범위로 지역 판정)
 */
function csv_new_delivery_is_island_zip($zip)
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

function csv_new_delivery_condition_fee($condition, $item_amount, $item_qty)
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
            $dc_id = isset($condition['dc_id'])
                ? (int)$condition['dc_id']
                : 0;

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


/*
 * 같은 주문번호의 전체 상품금액.
 *
 * 브랜드 구분 없이 주문번호에 포함된 모든 상품을 합산합니다.
 * 옵션 추가금액도 포함합니다.
 *
 * 묶음배송 조건부무료 / 금액구간 판정은 이 금액을 사용합니다.
 */
function csv_new_delivery_order_product_total($od_id)
{
    global $g5;

    $od_id = trim((string)$od_id);

    if ($od_id === '') {
        return 0;
    }

    $od_id_sql = sql_real_escape_string($od_id);

    $row = sql_fetch("
        SELECT
            SUM(
                IF(
                    io_type = 1,
                    io_price * ct_qty,
                    (ct_price + io_price) * ct_qty
                )
            ) AS total_price
        FROM {$g5['g5_shop_cart_table']}
        WHERE od_id = '{$od_id_sql}'
    ");

    return isset($row['total_price'])
        ? max(0, (int)$row['total_price'])
        : 0;
}

function csv_new_delivery_order_brand($od_id, $brand_id, $receiver_addr, $receiver_zip, $order_product_total_override = null)
{
    global $g5;

    $data = array(
        'shipping_total' => 0,
        'item_charges' => array(),
        'item_types' => array(),
        'item_methods' => array(),
        'item_groups' => array()
    );

    $od_id = trim((string)$od_id);
    $brand_id = trim((string)$brand_id);

    if ($od_id === '' || $brand_id === '') {
        return $data;
    }

    $od_id_sql = sql_real_escape_string($od_id);
    $brand_id_sql = sql_real_escape_string($brand_id);

    /*
     * 브랜드 기본 배송조건.
     */
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

    /*
     * 새 기본조건이 없는 서버/브랜드를 위한 기존 브랜드 설정 fallback.
     */
    $brand_settings = array();

    $bs_result = sql_query("
        SELECT *
        FROM donuts_brand_settings
        WHERE brand_id = '{$brand_id_sql}'
        LIMIT 1
    ", false);

    if ($bs_result) {
        $brand_settings = sql_fetch_array($bs_result);
    }

    /*
     * 주문에서 현재 브랜드 상품을 상품코드별로 합산.
     * 옵션 추가금액까지 포함합니다.
     */
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
        WHERE c.od_id = '{$od_id_sql}'
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

        /*
         * 상품에 연결된 배송조건/묶음그룹.
         */
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
            $condition_id = isset($default_condition['dc_id'])
                ? (int)$default_condition['dc_id']
                : 0;
        }

        $item_amount = (int)$item['item_amount'];
        $item_qty = (int)$item['item_qty'];

        if (!empty($condition)) {
            $fee = csv_new_delivery_condition_fee(
                $condition,
                $item_amount,
                $item_qty
            );

            $condition_name = !empty($condition['dc_name'])
                ? $condition['dc_name']
                : '배송조건';

            /*
             * 지역 추가배송비.
             * 제주와 도서산간은 중복 가산하지 않고 제주를 우선합니다.
             */
            $is_jeju = (
                mb_strpos((string)$receiver_addr, '제주') !== false
            );

            if (
                $is_jeju &&
                !empty($condition['dc_jeju_use'])
            ) {
                $fee += max(0, (int)$condition['dc_jeju_price']);
                $condition_name .= ' + 제주추가';
            } elseif (
                !empty($condition['dc_island_use']) &&
                csv_new_delivery_is_island_zip($receiver_zip)
            ) {
                $fee += max(0, (int)$condition['dc_island_price']);
                $condition_name .= ' + 도서산간추가';
            }

        } else {
            /*
             * 새 배송조건이 전혀 없을 때만 기존 브랜드 기본배송비 사용.
             */
            $fee = csv_brand_send_cost($brand_settings, $item_amount);
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

                if (
                    isset($group['calc_method']) &&
                    strtoupper($group['calc_method']) === 'MIN'
                ) {
                    $calc_method = 'MIN';
                }
            }

            /*
             * 그룹 정보가 삭제/미사용 상태면 개별배송 취급.
             */
            if ($group_name === '') {
                $group_id = 0;
            }
        }

        $items[$it_id] = array(
            'fee' => max(0, (int)$fee),
            'amount' => $item_amount,
            'qty' => $item_qty,
            'condition' => $condition,
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

    /*
     * 묶음배송 무료조건/금액구간 판정용 주문총액.
     *
     * 중요:
     * 브랜드별 상품합계가 아니라 "같은 주문번호의 모든 상품금액"을 사용합니다.
     *
     * 예)
     * 상품 A 15,000원
     * 상품 B 15,000원
     * 묶음배송 30,000원 이상 무료
     *
     * => 주문번호 전체 상품금액 30,000원
     * => 최종 배송비 0원
     */
    if ($order_product_total_override !== null) {
        $order_product_total = max(0, (int)$order_product_total_override);
    } else {
        $order_product_total = csv_new_delivery_order_product_total($od_id);
    }

    /*
     * 예외적으로 전체 주문금액 조회가 실패한 경우에만
     * 현재 브랜드 상품금액 합계로 fallback 합니다.
     */
    if ($order_product_total <= 0) {
        foreach ($items as $it_id => $item) {
            $order_product_total += isset($item['amount'])
                ? (int)$item['amount']
                : 0;
        }
    }

    /*
     * 묶음배송 상품만 주문 전체 상품금액 기준으로 배송조건을 다시 계산합니다.
     * paid/free/quantity는 금액 기준 여부와 관계없이 기존 규칙을 유지하고,
     * conditional/amount_range는 주문번호의 모든 상품금액을 기준으로 계산됩니다.
     */
    foreach ($items as $it_id => &$item) {
        if ((int)$item['group_id'] < 1 || empty($item['condition'])) {
            continue;
        }

        $group_fee = csv_new_delivery_condition_fee(
            $item['condition'],
            $order_product_total,
            isset($item['qty']) ? (int)$item['qty'] : 0
        );

        $group_condition_name = !empty($item['condition']['dc_name'])
            ? $item['condition']['dc_name']
            : '배송조건';

        /*
         * 지역 추가배송비도 최종 그룹 후보 배송비에 포함합니다.
         */
        $is_jeju_group = (
            function_exists('mb_strpos') &&
            mb_strpos((string)$receiver_addr, '제주') !== false
        );

        if (
            $is_jeju_group &&
            !empty($item['condition']['dc_jeju_use'])
        ) {
            $group_fee += max(0, (int)$item['condition']['dc_jeju_price']);
            $group_condition_name .= ' + 제주추가';
        } elseif (
            !empty($item['condition']['dc_island_use']) &&
            csv_new_delivery_is_island_zip($receiver_zip)
        ) {
            $group_fee += max(0, (int)$item['condition']['dc_island_price']);
            $group_condition_name .= ' + 도서산간추가';
        }

        $item['fee'] = max(0, (int)$group_fee);
        $item['condition_name'] = $group_condition_name;

        $data['item_types'][$it_id] = $group_condition_name;
        $data['item_methods'][$it_id] = $group_fee > 0 ? '선불' : '무료';
    }
    unset($item);

    /*
     * 개별배송 / 묶음배송 분리.
     */
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

    /*
     * 묶음배송:
     * MAX = 그룹에서 가장 높은 배송비 1회
     * MIN = 그룹에서 가장 낮은 배송비 1회
     *
     * CSV 합계가 실제 배송비와 일치하도록
     * 대표 상품 한 행에만 그룹 배송비를 기록합니다.
     */
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

            if (
                $group['method'] === 'MIN' &&
                $fee < $selected_fee
            ) {
                $selected_it_id = $it_id;
                $selected_fee = $fee;
            }

            if (
                $group['method'] !== 'MIN' &&
                $fee > $selected_fee
            ) {
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
            $data['item_groups'][$it_id] =
                $group['name'] . ' (' . $group['method'] . ')';

            /*
             * 대표 상품이 아닌 그룹 상품은 배송비 0원 표시.
             * "무료"로 오해하지 않도록 결제구분은 묶음배송으로 표시.
             */
            if ($it_id !== $selected_it_id) {
                $data['item_methods'][$it_id] = '묶음배송';
            } else {
                $data['item_methods'][$it_id] =
                    $selected_fee > 0 ? '선불' : '무료';
            }

            $data['item_types'][$it_id] .=
                ' / ' . $group['name'] . ' ' . $group['method'];
        }
    }

    return $data;
}

/*
 * 같은 주문번호의 최종 배송비 계산.
 *
 * 브랜드 계정:
 *   현재 로그인 브랜드의 상품만 기준.
 *
 * 최고관리자:
 *   주문번호에 포함된 각 브랜드 배송비를 계산한 뒤 합산.
 *
 * 묶음배송의 조건부무료/금액구간 판단은
 * 브랜드 구분 없이 같은 주문번호의 모든 상품금액을 기준으로 계산됩니다.
 */

/*
 * ============================================================
 * 최종 배송비 전용 계산
 * ============================================================
 *
 * 중요:
 * 이 함수는 CSV의 '배송비' 컬럼이나 item_charges를 전혀 참조하지 않습니다.
 *
 * 오직 아래 데이터만 보고 최종 배송비를 새로 계산합니다.
 * - 같은 주문번호의 상품가격/수량/옵션가격
 * - donuts_delivery_product_settings
 * - donuts_delivery_conditions
 * - donuts_delivery_condition_ranges
 * - donuts_delivery_groups
 *
 * 묶음배송 상품의 conditional / amount_range 기준금액은
 * 같은 주문번호의 전체 상품가격 합계를 사용합니다.
 */

function csv_final_default_special_non_group_items($items)
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


/*
 * deliverymanage.php에서 배송조건별로 선택한 지역 추가비 계산.
 *
 * 데이터:
 * donuts_delivery_condition_sendcosts
 *   -> g5_shop_sendcost_table
 *
 * 수취인 우편번호가 선택된 지역 범위에 들어오면 해당 추가비를 반환합니다.
 * 잘못 겹친 우편번호 범위가 여러 개 있을 경우 중복 가산하지 않고
 * 가장 큰 추가비 1건만 적용합니다.
 */
function csv_delivery_condition_region_extra($condition_id, $receiver_zip, $brand_id = '')
{
    global $g5;

    $condition_id = (int)$condition_id;

    if ($condition_id <= 0) {
        return 0;
    }

    $zip = preg_replace('/[^0-9]/', '', (string)$receiver_zip);

    if ($zip === '') {
        return 0;
    }

    $zip_num = (int)$zip;
    $matched_fee = 0;
    $brand_id = trim((string)$brand_id);
    $brand_where = '';

    if ($brand_id !== '') {
        $brand_id_sql = sql_real_escape_string($brand_id);
        $brand_where = " AND s.brand_id = '{$brand_id_sql}' ";
    }

    $result = sql_query("
        SELECT
            s.sc_id,
            s.sc_zip1,
            s.sc_zip2,
            s.sc_price
        FROM donuts_delivery_condition_sendcosts m
        INNER JOIN donuts_brand_sendcost s
            ON s.sc_id = m.sc_id
        WHERE m.dc_id = '{$condition_id}'
          {$brand_where}
        ORDER BY s.sc_id ASC
    ", false);

    if (!$result) {
        return 0;
    }

    while ($row = sql_fetch_array($result)) {
        $from = (int)preg_replace(
            '/[^0-9]/',
            '',
            (string)$row['sc_zip1']
        );

        $to = (int)preg_replace(
            '/[^0-9]/',
            '',
            (string)$row['sc_zip2']
        );

        if ($from <= $zip_num && $zip_num <= $to) {
            $matched_fee = max(
                $matched_fee,
                max(0, (int)$row['sc_price'])
            );
        }
    }

    return $matched_fee;
}

function csv_final_shipping_from_products_and_groups($od_id, $brand_id, $receiver_addr, $receiver_zip, $return_detail = false)
{
    global $g5;

    $od_id = trim((string)$od_id);
    $brand_id = trim((string)$brand_id);

    if ($od_id === '') {
        return $return_detail
            ? array('shipping_total' => 0, 'region_extra' => 0)
            : 0;
    }

    $od_id_sql = sql_real_escape_string($od_id);

    /*
     * ==========================================================
     * 최종 배송비는 반드시 "같은 주문번호"를 하나의 계산 단위로 처리
     * ==========================================================
     *
     * 기존 배송비 컬럼은 사용하지 않습니다.
     *
     * 1. 같은 od_id의 상품을 전부 가져옴
     * 2. 주문번호 전체 상품금액을 먼저 계산
     * 3. 상품별 배송조건/묶음배송 설정 조회
     * 4. 묶음배송 상품은 주문번호 전체 상품금액으로 조건 판단
     * 5. 같은 묶음조건은 배송비 1회만 계산
     */

    $brand_where = '';

    if ($brand_id !== '') {
        $brand_id_sql = sql_real_escape_string($brand_id);
        $brand_where = " AND TRIM(i.it_brand) = '{$brand_id_sql}' ";
    }

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
        {$brand_where}
        GROUP BY c.it_id, i.it_brand
        ORDER BY MIN(c.ct_id)
    ", false);

    if (!$result) {
        return $return_detail
            ? array('shipping_total' => 0, 'region_extra' => 0)
            : 0;
    }

    $items = array();

    while ($row = sql_fetch_array($result)) {
        $it_id = trim((string)$row['it_id']);

        if ($it_id === '') {
            continue;
        }

        $items[] = array(
            'it_id' => $it_id,
            'brand_id' => trim((string)$row['item_brand_id']),
            'amount' => (int)$row['item_amount'],
            'qty' => (int)$row['item_qty']
        );
    }

    if (empty($items)) {
        return $return_detail
            ? array('shipping_total' => 0, 'region_extra' => 0)
            : 0;
    }

    // 실제 묶음배송을 제외한 기본+유료/수량/금액구간 상품만 특례 대상으로 지정
    $default_special_non_group_items =
        csv_final_default_special_non_group_items($items);

    /*
     * 브랜드 필터와 무관하게 무료배송 기준은
     * 동일 주문번호 전체 상품금액으로 다시 구합니다.
     */
    $total_row = sql_fetch("
        SELECT
            SUM(
                IF(
                    io_type = 1,
                    io_price * ct_qty,
                    (ct_price + io_price) * ct_qty
                )
            ) AS total_amount
        FROM {$g5['g5_shop_cart_table']}
        WHERE od_id = '{$od_id_sql}'
    ");

    $order_total = isset($total_row['total_amount'])
        ? (int)$total_row['total_amount']
        : 0;

    if ($order_total <= 0) {
        foreach ($items as $tmp) {
            $order_total += (int)$tmp['amount'];
        }
    }

    $individual_total = 0;
    $individual_region_extra = 0;
    $bundle_candidates = array();

    /*
     * 같은 주문번호가 '유료(paid) 상품만'으로 구성된 경우를 별도로 판정합니다.
     *
     * 이 경우 각 상품 배송비를 더하지 않고 가장 비싼 배송비 1회만 적용합니다.
     *
     * 예)
     * 유료 4,000원 + 유료 4,000원
     * => 최종 배송비 4,000원
     *
     * conditional / amount_range / 기본택배 / 수량배송 / 실제 묶음배송이
     * 하나라도 섞이면 이 특례는 적용하지 않고 기존 계산을 그대로 사용합니다.
     */
    $paid_only_order = true;
    $paid_only_fees = array();

    foreach ($items as $item) {

        $it_id = $item['it_id'];
        $item_brand = $item['brand_id'];
        $item_amount = (int)$item['amount'];
        $item_qty = (int)$item['qty'];

        $it_id_sql = sql_real_escape_string($it_id);
        $item_brand_sql = sql_real_escape_string($item_brand);

        /*
         * 상품 배송 설정.
         *
         * 중요:
         * dps_id / updated_at 같은 컬럼 존재 여부를 가정하지 않습니다.
         * 이전 패치의 fallback SQL이 서버 스키마와 다르면 조회 자체가 실패할 수
         * 있었기 때문에 이번에는 실제 필요한 컬럼만 사용합니다.
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

            if ($sr) {
                $setting = sql_fetch_array($sr);
            }
        }

        if (empty($setting)) {
            $sr = sql_query("
                SELECT brand_id, condition_id, group_id
                FROM donuts_delivery_product_settings
                WHERE it_id = '{$it_id_sql}'
                LIMIT 1
            ", false);

            if ($sr) {
                $setting = sql_fetch_array($sr);
            }
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
         * 배송조건 조회.
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

            if ($cr) {
                $condition = sql_fetch_array($cr);
            }
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

            if ($cr) {
                $condition = sql_fetch_array($cr);
            }
        }

        /*
         * 묶음배송 그룹 조회.
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

            if ($gr) {
                $group = sql_fetch_array($gr);
            }

            if (empty($group)) {
                $group_id = 0;
            }
        }

        /*
         * ----------------------------------------------------------
         * 묶음배송 판정
         * ----------------------------------------------------------
         *
         * group_id가 정상적으로 존재하면 당연히 묶음배송.
         *
         * 추가로, 현재 운영 데이터처럼 상품 설정의 group_id가 CSV 조회에서
         * 누락되어도 같은 주문번호 안에 2개 이상의 상품이 있고
         * 같은 conditional/amount_range 조건을 사용하는 경우에는
         * 같은 배송조건 자체를 묶음조건으로 취급합니다.
         *
         * 이것이 2026081809541739 케이스를 위한 핵심 수정입니다.
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

        /*
         * 배송비 계산 기준 금액.
         *
         * 묶음배송이면 무조건 같은 주문번호 전체 상품금액.
         */
        $base_amount = $is_bundle
            ? $order_total
            : $item_amount;

        if (!empty($condition)) {
            $fee = csv_new_delivery_condition_fee(
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
                csv_new_delivery_is_island_zip($receiver_zip)
            ) {
                $fee += max(0, (int)$condition['dc_island_price']);
            }

            /*
             * deliverymanage에서 이 배송조건에 선택한 "지역 추가비".
             * 기존 제주/도서산간 필드와 별도로 계산합니다.
             */
            $region_extra = csv_delivery_condition_region_extra(
                isset($condition['dc_id']) ? (int)$condition['dc_id'] : 0,
                $receiver_zip,
                $setting_brand
            );

            $fee += $region_extra;
        } else {
            $region_extra = 0;
            /*
             * 조건 테이블이 없는 경우만 브랜드 기본 배송비 fallback.
             */
            $bsr = sql_query("
                SELECT *
                FROM donuts_brand_settings
                WHERE brand_id = '{$setting_brand_sql}'
                LIMIT 1
            ", false);

            $brand_settings = $bsr
                ? sql_fetch_array($bsr)
                : array();

            $fee = csv_brand_send_cost(
                $brand_settings,
                $base_amount
            );
        }

        $fee = max(0, (int)$fee);
        $region_extra = isset($region_extra)
            ? max(0, (int)$region_extra)
            : 0;

        /*
         * 유료 상품만 구성된 주문 판정.
         *
         * - 실제 묶음배송(group_id > 0)이면 paid-only 특례 제외
         * - 조건 타입이 paid가 아니면 특례 제외
         */
        if ($is_bundle || $condition_type !== 'paid') {
            $paid_only_order = false;
        } else {
            $paid_only_fees[] = array(
                'fee' => $fee,
                'region_extra' => $region_extra
            );
        }

        if (!$is_bundle) {
            $individual_total += $fee;
            $individual_region_extra += $region_extra;
            continue;
        }

        /*
         * 묶음배송 key.
         *
         * group_id가 있으면 실제 그룹 ID 사용.
         * group_id가 조회되지 않은 운영 데이터는
         * 같은 주문번호 + 브랜드 + 배송조건 ID를 하나의 묶음으로 처리.
         */
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

        $bundle_candidates[$bundle_key]['fees'][] = array(
            'fee' => $fee,
            'region_extra' => $region_extra
        );
    }

    /*
     * 같은 주문번호가 유료(paid) 상품으로만 구성된 경우:
     * 가장 비싼 배송비 1회만 최종 배송비로 사용합니다.
     *
     * 상품이 2개 이상일 때만 적용합니다.
     */
    if (
        $paid_only_order &&
        count($items) > 1 &&
        count($paid_only_fees) === count($items)
    ) {
        $selected_paid = array(
            'fee' => 0,
            'region_extra' => 0
        );

        foreach ($paid_only_fees as $candidate) {
            if ((int)$candidate['fee'] > (int)$selected_paid['fee']) {
                $selected_paid = $candidate;
            }
        }

        $paid_shipping_total = max(0, (int)$selected_paid['fee']);
        $paid_region_extra = max(
            0,
            (int)$selected_paid['region_extra']
        );

        return $return_detail
            ? array(
                'shipping_total' => $paid_shipping_total,
                'region_extra' => $paid_region_extra
            )
            : $paid_shipping_total;
    }

    /*
     * 같은 주문번호 안에서 묶음배송 그룹별 1회 부과.
     */
    $bundle_total = 0;
    $bundle_region_extra = 0;

    foreach ($bundle_candidates as $bundle) {
        if (empty($bundle['fees'])) {
            continue;
        }

        $selected = null;

        foreach ($bundle['fees'] as $candidate) {
            if ($selected === null) {
                $selected = $candidate;
                continue;
            }

            if (
                $bundle['method'] === 'MIN' &&
                (int)$candidate['fee'] < (int)$selected['fee']
            ) {
                $selected = $candidate;
            }

            if (
                $bundle['method'] !== 'MIN' &&
                (int)$candidate['fee'] > (int)$selected['fee']
            ) {
                $selected = $candidate;
            }
        }

        if ($selected !== null) {
            $bundle_total += max(0, (int)$selected['fee']);
            $bundle_region_extra += max(
                0,
                (int)$selected['region_extra']
            );
        }
    }

    $shipping_total = max(
        0,
        (int)$individual_total + (int)$bundle_total
    );

    $region_extra_total = max(
        0,
        (int)$individual_region_extra +
        (int)$bundle_region_extra
    );

    return $return_detail
        ? array(
            'shipping_total' => $shipping_total,
            'region_extra' => $region_extra_total
        )
        : $shipping_total;
}

function csv_new_delivery_final_order_shipping($od_id, $brand_id, $receiver_addr, $receiver_zip)
{
    /*
     * 최종 배송비는 '배송비' 컬럼을 참조하지 않습니다.
     * 상품가격 + 배송조건 + 묶음배송 그룹 + 지역 추가비로 계산합니다.
     */
    return csv_final_shipping_from_products_and_groups(
        $od_id,
        $brand_id,
        $receiver_addr,
        $receiver_zip,
        false
    );
}

function csv_new_delivery_final_order_shipping_detail($od_id, $brand_id, $receiver_addr, $receiver_zip)
{
    return csv_final_shipping_from_products_and_groups(
        $od_id,
        $brand_id,
        $receiver_addr,
        $receiver_zip,
        true
    );
}


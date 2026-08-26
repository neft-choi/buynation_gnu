<?php
if (!defined('_GNUBOARD_')) exit;

function donuts_item_inspection_table_exists()
{
    $row = sql_fetch("SHOW TABLES LIKE 'donuts_item_inspections'", false);
    return !empty($row);
}

function donuts_item_inspection_is_brand($mb_id)
{
    $mb_id = trim((string)$mb_id);
    if ($mb_id === '') return false;

    $mb_sql = sql_real_escape_string($mb_id);
    $row = sql_fetch("
        SELECT brand_id
        FROM donuts_brand
        WHERE TRIM(brand_id) = '{$mb_sql}'
        LIMIT 1
    ");
    return !empty($row['brand_id']);
}

function donuts_item_inspection_no($inspection_id, $created_at = '')
{
    $ts = $created_at ? strtotime($created_at) : G5_SERVER_TIME;
    if (!$ts) $ts = G5_SERVER_TIME;

    return 'PRD-RV-' . date('ymd', $ts) . '-' . str_pad((string)$inspection_id, 6, '0', STR_PAD_LEFT);
}

function donuts_item_inspection_get($it_id)
{
    $it_id = trim((string)$it_id);
    if ($it_id === '') return array();

    $it_sql = sql_real_escape_string($it_id);
    return sql_fetch("
        SELECT *
        FROM donuts_item_inspections
        WHERE it_id = '{$it_sql}'
        LIMIT 1
    ");
}

function donuts_item_inspection_log($inspection_id, $it_id, $brand_id, $from_status, $to_status, $action_by, $message = '', $review_fields = '')
{
    $inspection_id = (int)$inspection_id;
    $it_sql = sql_real_escape_string($it_id);
    $brand_sql = sql_real_escape_string($brand_id);
    $from_sql = sql_real_escape_string((string)$from_status);
    $to_sql = sql_real_escape_string((string)$to_status);
    $by_sql = sql_real_escape_string((string)$action_by);
    $message_sql = sql_real_escape_string((string)$message);
    $fields_sql = sql_real_escape_string((string)$review_fields);

    sql_query("
        INSERT INTO donuts_item_inspection_logs
        SET inspection_id = '{$inspection_id}',
            it_id = '{$it_sql}',
            brand_id = '{$brand_sql}',
            from_status = " . ($from_status === '' ? "NULL" : "'{$from_sql}'") . ",
            to_status = '{$to_sql}',
            action_by = '{$by_sql}',
            message = '{$message_sql}',
            review_fields = '{$fields_sql}',
            created_at = NOW()
    ");
}

function donuts_item_inspection_ensure_draft($it_id, $brand_id, $request_type = 'new', $action_by = '')
{
    $it_id = trim((string)$it_id);
    $brand_id = trim((string)$brand_id);
    $request_type = ($request_type === 'update') ? 'update' : 'new';

    if ($it_id === '' || $brand_id === '') return array();

    $it_sql = sql_real_escape_string($it_id);
    $brand_sql = sql_real_escape_string($brand_id);
    $type_sql = sql_real_escape_string($request_type);

    $old = donuts_item_inspection_get($it_id);

    if (empty($old['inspection_id'])) {
        sql_query("
            INSERT INTO donuts_item_inspections
            SET inspect_no = '',
                it_id = '{$it_sql}',
                brand_id = '{$brand_sql}',
                request_type = '{$type_sql}',
                status = 'draft',
                created_at = NOW(),
                updated_at = NOW()
        ");

        $id = sql_insert_id();
        $inspect_no = donuts_item_inspection_no($id);
        $no_sql = sql_real_escape_string($inspect_no);

        sql_query("
            UPDATE donuts_item_inspections
            SET inspect_no = '{$no_sql}'
            WHERE inspection_id = '{$id}'
        ");

        donuts_item_inspection_log(
            $id, $it_id, $brand_id, '', 'draft',
            $action_by ?: $brand_id,
            '브랜드 상품 저장으로 검수 항목이 생성되었습니다.'
        );

        return donuts_item_inspection_get($it_id);
    }

    $from = (string)$old['status'];

    sql_query("
        UPDATE donuts_item_inspections
        SET brand_id = '{$brand_sql}',
            request_type = '{$type_sql}',
            status = 'draft',
            review_fields = NULL,
            brand_message = NULL,
            admin_message = NULL,
            requested_at = NULL,
            reviewed_at = NULL,
            approved_at = NULL,
            reviewed_by = NULL,
            updated_at = NOW()
        WHERE inspection_id = '" . (int)$old['inspection_id'] . "'
    ");

    if ($from !== 'draft') {
        donuts_item_inspection_log(
            $old['inspection_id'], $it_id, $brand_id, $from, 'draft',
            $action_by ?: $brand_id,
            '상품이 수정되어 검수 요청 전 상태로 변경되었습니다.'
        );
    }

    return donuts_item_inspection_get($it_id);
}


function donuts_item_inspection_sync_untracked_brand_products($recent_days = 7)
{
    global $g5;

    $recent_days = max(1, (int)$recent_days);

    /*
     * 중요:
     * donuts_brand.brand_id 와 g5_shop_item.it_brand 를 SQL JOIN으로 직접 비교하지 않습니다.
     *
     * 현재 DB:
     * - donuts_brand.brand_id : utf8mb4_0900_ai_ci
     * - g5_shop_item.it_brand : utf8mb4_general_ci
     *
     * 서로 다른 collation 컬럼을 컬럼 대 컬럼으로 비교하면 1267 오류가 날 수 있으므로,
     * 먼저 상품을 읽은 뒤 brand_id를 "컬럼 = 문자열 리터럴" 방식으로 별도 검증합니다.
     */
    $result = sql_query("
        SELECT
            i.it_id,
            i.it_brand,
            i.it_use,
            i.it_time
        FROM {$g5['g5_shop_item_table']} i
        LEFT JOIN donuts_item_inspections r
          ON r.it_id COLLATE utf8mb4_general_ci = i.it_id COLLATE utf8mb4_general_ci
        WHERE r.inspection_id IS NULL
          AND TRIM(i.it_brand) <> ''
          AND (
                i.it_use = 0
                OR i.it_time >= DATE_SUB(NOW(), INTERVAL {$recent_days} DAY)
              )
        ORDER BY i.it_time ASC, i.it_id ASC
    ", false);

    if (!$result) {
        return 0;
    }

    $synced = 0;

    while ($row = sql_fetch_array($result)) {
        $it_id = trim((string)$row['it_id']);
        $brand_id = trim((string)$row['it_brand']);

        if ($it_id === '' || $brand_id === '') {
            continue;
        }

        /*
         * 컬럼과 문자열 리터럴 비교이므로 collation mix 오류가 발생하지 않습니다.
         */
        $brand_sql = sql_real_escape_string($brand_id);

        $brand = sql_fetch("
            SELECT brand_id
            FROM donuts_brand
            WHERE TRIM(brand_id) = '{$brand_sql}'
            LIMIT 1
        ");

        if (empty($brand['brand_id'])) {
            continue;
        }

        $it_sql = sql_real_escape_string($it_id);

        sql_query("
            UPDATE {$g5['g5_shop_item_table']}
            SET it_use = 0
            WHERE it_id = '{$it_sql}'
        ");

        $inspection = donuts_item_inspection_ensure_draft(
            $it_id,
            $brand_id,
            'new',
            $brand_id
        );

        if (!empty($inspection['inspection_id'])) {
            $synced++;
        }
    }

    return $synced;
}

function donuts_item_inspection_status_label($status)
{
    $map = array(
        'draft' => '임시저장',
        'pending' => '심사 중',
        'revision' => '보완 요청',
        'approved' => '승인',
        'rejected' => '거절'
    );

    return isset($map[$status]) ? $map[$status] : $status;
}

function donuts_item_inspection_status_badge($status)
{
    if ($status === 'approved') return 'green';
    if ($status === 'pending') return 'yellow';
    if ($status === 'revision' || $status === 'rejected') return 'red';
    return '';
}

function donuts_item_inspection_delivery_label($brand_id, $it_id)
{
    $brand_sql = sql_real_escape_string(trim((string)$brand_id));
    $it_sql = sql_real_escape_string(trim((string)$it_id));

    $setting = sql_fetch("
        SELECT s.group_id, s.condition_id, g.dg_name, c.dc_name
        FROM donuts_delivery_product_settings s
        LEFT JOIN donuts_delivery_groups g
          ON g.dg_id = s.group_id
         AND g.brand_id = s.brand_id
        LEFT JOIN donuts_delivery_conditions c
          ON c.dc_id = s.condition_id
         AND c.brand_id = s.brand_id
        WHERE s.brand_id = '{$brand_sql}'
          AND s.it_id = '{$it_sql}'
        LIMIT 1
    ");

    if (empty($setting)) return '미지정';
    if (!empty($setting['group_id'])) {
        return (!empty($setting['dg_name']) ? $setting['dg_name'] : '묶음배송') . ' · 묶음배송';
    }
    return !empty($setting['dc_name']) ? $setting['dc_name'] . ' · 개별배송' : '개별배송';
}

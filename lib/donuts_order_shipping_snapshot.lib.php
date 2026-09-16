<?php
if (!defined('_GNUBOARD_')) exit;

/**
 * 주문시점 배송비 스냅샷
 *
 * 핵심 원칙:
 * - 주문 완료 시점의 배송비를 별도 테이블에 저장
 * - 이후 배송정책/추가배송비/묶음배송 설정이 바뀌어도 기존 주문값은 변경하지 않음
 * - brand_id='__ALL__' 행은 주문 전체 배송비
 * - 브랜드별 행은 해당 브랜드 배송비
 */

function donuts_order_shipping_snapshot_table()
{
    return 'donuts_order_shipping_snapshot';
}

function donuts_order_shipping_snapshot_ensure_table()
{
    $table = donuts_order_shipping_snapshot_table();

    sql_query("
        CREATE TABLE IF NOT EXISTS {$table} (
            oss_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            od_id VARCHAR(50) NOT NULL,
            brand_id VARCHAR(255) NOT NULL DEFAULT '',
            shipping_total INT NOT NULL DEFAULT 0,
            region_extra INT NOT NULL DEFAULT 0,
            snapshot_json LONGTEXT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (oss_id),
            UNIQUE KEY uq_order_brand (od_id, brand_id),
            KEY idx_od_id (od_id),
            KEY idx_brand_id (brand_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ", false);
}

function donuts_order_shipping_snapshot_get($od_id, $brand_id = '')
{
    donuts_order_shipping_snapshot_ensure_table();

    $od_id = trim((string)$od_id);
    $brand_id = trim((string)$brand_id);

    if ($od_id === '') {
        return array();
    }

    $od_id_sql = sql_real_escape_string($od_id);
    $brand_id_sql = sql_real_escape_string($brand_id);

    $row = sql_fetch("
        SELECT *
        FROM " . donuts_order_shipping_snapshot_table() . "
        WHERE od_id = '{$od_id_sql}'
          AND brand_id = '{$brand_id_sql}'
        LIMIT 1
    ", false);

    return !empty($row['oss_id']) ? $row : array();
}

function donuts_order_shipping_snapshot_save_once(
    $od_id,
    $brand_id,
    $shipping_total,
    $region_extra,
    $snapshot_data = array()
) {
    donuts_order_shipping_snapshot_ensure_table();

    $od_id = trim((string)$od_id);
    $brand_id = trim((string)$brand_id);

    if ($od_id === '') {
        return false;
    }

    $od_id_sql = sql_real_escape_string($od_id);
    $brand_id_sql = sql_real_escape_string($brand_id);
    $shipping_total = max(0, (int)$shipping_total);
    $region_extra = max(0, (int)$region_extra);

    $json = '';
    if (!empty($snapshot_data)) {
        $json = json_encode(
            $snapshot_data,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
        if ($json === false) {
            $json = '';
        }
    }

    $json_sql = sql_real_escape_string($json);

    /*
     * INSERT IGNORE가 핵심입니다.
     * 이미 저장된 주문시점 배송비는 절대로 UPDATE하지 않습니다.
     */
    return sql_query("
        INSERT IGNORE INTO " . donuts_order_shipping_snapshot_table() . "
            (
                od_id,
                brand_id,
                shipping_total,
                region_extra,
                snapshot_json,
                created_at
            )
        VALUES
            (
                '{$od_id_sql}',
                '{$brand_id_sql}',
                '{$shipping_total}',
                '{$region_extra}',
                '{$json_sql}',
                NOW()
            )
    ", false);
}

function donuts_order_shipping_snapshot_detail($od_id, $brand_id = '')
{
    $row = donuts_order_shipping_snapshot_get($od_id, $brand_id);

    if (empty($row)) {
        return array();
    }

    $data = array();

    if (!empty($row['snapshot_json'])) {
        $decoded = json_decode($row['snapshot_json'], true);
        if (is_array($decoded)) {
            $data = $decoded;
        }
    }

    $data['shipping_total'] = (int)$row['shipping_total'];
    $data['region_extra'] = (int)$row['region_extra'];
    $data['_snapshot_exists'] = true;
    $data['_snapshot_created_at'] = isset($row['created_at'])
        ? $row['created_at']
        : '';

    return $data;
}

/**
 * 과거 주문 호환용.
 *
 * 스냅샷이 없을 때만 현재 계산값을 1회 저장합니다.
 * 한 번 저장된 뒤에는 배송정책이 변경되어도 값이 바뀌지 않습니다.
 *
 * 주의: 패치 적용 전 주문은 실제 주문 당시 정책을 완벽히 복원할 수 없으므로
 * 최초 조회/다운로드 시점의 계산값을 고정합니다.
 */
function donuts_order_shipping_snapshot_capture_if_missing(
    $od_id,
    $brand_id,
    $receiver_addr,
    $receiver_zip
) {
    $existing = donuts_order_shipping_snapshot_detail($od_id, $brand_id);

    if (!empty($existing['_snapshot_exists'])) {
        return $existing;
    }

    if (!function_exists('csv_new_delivery_final_order_shipping_detail')) {
        return array();
    }

    $detail = csv_new_delivery_final_order_shipping_detail(
        $od_id,
        $brand_id === '__ALL__' ? '' : $brand_id,
        $receiver_addr,
        $receiver_zip
    );

    $shipping_total = isset($detail['shipping_total'])
        ? (int)$detail['shipping_total']
        : 0;

    $region_extra = isset($detail['region_extra'])
        ? (int)$detail['region_extra']
        : 0;

    $payload = array(
        'source' => 'legacy_first_capture',
        'final_detail' => $detail
    );

    if (
        $brand_id !== '__ALL__' &&
        function_exists('csv_new_delivery_order_brand') &&
        function_exists('csv_new_delivery_order_product_total')
    ) {
        $payload['delivery_calc'] = csv_new_delivery_order_brand(
            $od_id,
            $brand_id,
            $receiver_addr,
            $receiver_zip,
            csv_new_delivery_order_product_total($od_id)
        );
    }

    donuts_order_shipping_snapshot_save_once(
        $od_id,
        $brand_id,
        $shipping_total,
        $region_extra,
        $payload
    );

    return donuts_order_shipping_snapshot_detail($od_id, $brand_id);
}

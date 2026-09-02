<?php
/*
 * itemform 저장 후 deliverymanage 배송설정 등록 동기화
 *
 * 핵심:
 * 1) 실제 상품 저장이 끝난 뒤 상품의 it_brand를 다시 읽음
 * 2) donuts_delivery_sync_brand_products()로 deliverymanage 관리대상 등록 보장
 * 3) 화면에서 선택한 condition/group을 deliverymanage_update.php의 apply_products와
 *    동일한 UPSERT 방식으로 최종 반영
 */
include_once('./_common.php');
include_once(G5_LIB_PATH . '/donuts_delivery.lib.php');

/*
 * itemform의 dm_* hidden을 최우선으로 사용합니다.
 * sendcostform.php 구현에 따라 실제 field name이 다른 경우도 있으므로
 * 기존 field name들을 fallback으로 함께 지원합니다.
 */
$dm_post_condition_id = 0;
$dm_condition_keys = array(
    'dm_condition_id',
    'delivery_condition_id',
    'condition_id',
    'dc_id'
);

foreach ($dm_condition_keys as $dm_key) {
    if (isset($_POST[$dm_key]) && (int)$_POST[$dm_key] > 0) {
        $dm_post_condition_id = (int)$_POST[$dm_key];
        break;
    }
}

$dm_post_group_id = 0;
$dm_group_keys = array(
    'dm_group_id',
    'delivery_group_id',
    'group_id',
    'dg_id'
);

foreach ($dm_group_keys as $dm_key) {
    if (isset($_POST[$dm_key])) {
        $dm_value = (int)$_POST[$dm_key];

        /*
         * dm_group_id는 0도 '묶음배송 선택 안 함'이라는 유효한 값입니다.
         */
        if ($dm_key === 'dm_group_id' || $dm_value > 0) {
            $dm_post_group_id = $dm_value;
            break;
        }
    }
}
$dm_post_it_id = isset($_POST['it_id'])
    ? preg_replace('/[^A-Za-z0-9_\-]/', '', trim((string)$_POST['it_id']))
    : '';
$dm_login_brand_id = !empty($member['mb_id']) ? trim((string)$member['mb_id']) : '';

/*
 * 신규등록 배송설정 진단 로그.
 * 서버에서 저장이 안 되는 경우 실제 POST 값과 최종 UPSERT 값을 확인할 수 있습니다.
 */
$dm_debug_log = function ($message) {
    if (!defined('G5_DATA_PATH')) {
        return;
    }

    $dir = G5_DATA_PATH . '/log';

    if (!is_dir($dir)) {
        @mkdir($dir, G5_DIR_PERMISSION, true);
    }

    if (!is_dir($dir)) {
        return;
    }

    @file_put_contents(
        $dir . '/itemform_delivery.log',
        '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL,
        FILE_APPEND
    );
};

$dm_debug_log(
    'POST it_id=' . $dm_post_it_id .
    ' w=' . (isset($_POST['w']) ? (string)$_POST['w'] : '') .
    ' condition_id=' . $dm_post_condition_id .
    ' group_id=' . $dm_post_group_id .
    ' login_brand=' . $dm_login_brand_id
);

if ($dm_post_it_id !== '') {
    register_shutdown_function(function () use (
        $g5,
        $dm_post_it_id,
        $dm_post_condition_id,
        $dm_post_group_id,
        $dm_login_brand_id,
        $dm_debug_log
    ) {
        $it_id_sql = sql_real_escape_string($dm_post_it_id);

        // 기존 itemformupdate가 끝난 뒤 실제 저장된 상품 기준으로 처리한다.
        $item = sql_fetch("SELECT it_id, it_brand
            FROM {$g5['g5_shop_item_table']}
            WHERE it_id = '{$it_id_sql}'
            LIMIT 1");

        if (empty($item['it_id'])) {
            $dm_debug_log('FAIL item not found: ' . $dm_post_it_id);
            return;
        }

        $brand_id = trim((string)$item['it_brand']);
        if ($brand_id === '') $brand_id = $dm_login_brand_id;
        if ($brand_id === '') {
            $dm_debug_log('FAIL brand_id empty for item=' . $dm_post_it_id);
            return;
        }

        // 브랜드 등록 여부 확인. deliverymanage와 동일한 라이브러리를 사용한다.
        if (!donuts_delivery_brand_exists($brand_id)) {
            $dm_debug_log('FAIL brand not registered: ' . $brand_id);
            return;
        }

        $brand_id_sql = sql_real_escape_string($brand_id);

        /*
         * deliverymanage.php 진입 시 실행되는 것과 동일한 동기화.
         * 신규 상품에 아직 설정 행이 없다면 기본 배송조건으로 먼저 등록한다.
         * INSERT IGNORE이므로 이미 선택값이 있는 상품은 덮어쓰지 않는다.
         */
        donuts_delivery_sync_brand_products($brand_id);

        $condition_id = (int)$dm_post_condition_id;

        // 선택값이 없으면 deliverymanage의 브랜드 기본 배송조건 사용.
        if ($condition_id <= 0) {
            $default = donuts_delivery_ensure_default_condition($brand_id);
            $condition_id = !empty($default['dc_id']) ? (int)$default['dc_id'] : 0;
        }

        if ($condition_id <= 0) return;

        // 선택한 배송조건이 현재 상품 브랜드 소유인지 검증.
        $condition = sql_fetch("SELECT dc_id
            FROM donuts_delivery_conditions
            WHERE dc_id = '{$condition_id}'
              AND brand_id = '{$brand_id_sql}'
              AND use_yn = 'Y'
            LIMIT 1");

        if (empty($condition['dc_id'])) {
            $default = donuts_delivery_ensure_default_condition($brand_id);
            $condition_id = !empty($default['dc_id']) ? (int)$default['dc_id'] : 0;
            if ($condition_id <= 0) return;
        }

        // 그룹은 선택 안 함(0)이면 NULL. 선택된 경우 브랜드 소유 그룹인지 검증.
        $group_sql = 'NULL';
        if ($dm_post_group_id > 0) {
            $group_id = (int)$dm_post_group_id;
            $group = sql_fetch("SELECT dg_id
                FROM donuts_delivery_groups
                WHERE dg_id = '{$group_id}'
                  AND brand_id = '{$brand_id_sql}'
                  AND use_yn = 'Y'
                LIMIT 1");

            if (!empty($group['dg_id'])) {
                $group_sql = "'{$group_id}'";
            }
        }

        /*
         * deliverymanage_update.php -> apply_products와 동일한 저장 형태.
         * 따라서 신규 등록 직후 deliverymanage.php에서도 바로 조회된다.
         */
        $dm_saved = sql_query("INSERT INTO donuts_delivery_product_settings
                (brand_id, it_id, condition_id, group_id, created_at, updated_at)
            VALUES
                ('{$brand_id_sql}', '{$it_id_sql}', '{$condition_id}', {$group_sql}, NOW(), NOW())
            ON DUPLICATE KEY UPDATE
                condition_id = VALUES(condition_id),
                group_id = VALUES(group_id),
                updated_at = NOW()", false);

        $dm_debug_log(
            ($dm_saved ? 'OK' : 'FAIL SQL') .
            ' item=' . $dm_post_it_id .
            ' brand=' . $brand_id .
            ' condition=' . $condition_id .
            ' group=' . ($dm_post_group_id > 0 ? $dm_post_group_id : 0)
        );
    });
}

// 기존 상품등록/수정 + 검수 처리
include('./itemformupdate_inspect.php');

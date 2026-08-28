<?php
/*
 * itemform -> deliverymanage 저장 동기화 wrapper
 *
 * 기존 itemformupdate_inspect.php는 수정하지 않고 그대로 실행합니다.
 * 이 파일은 상품 수정 화면에서 선택한
 * dm_condition_id / dm_group_id를 받아 상품 저장 종료 시
 * donuts_delivery_product_settings에 반영합니다.
 */
include_once('./_common.php');

$dm_post_condition_id = isset($_POST['dm_condition_id'])
    ? (int)$_POST['dm_condition_id']
    : 0;

$dm_post_group_id = isset($_POST['dm_group_id'])
    ? (int)$_POST['dm_group_id']
    : 0;

$dm_post_it_id = isset($_POST['it_id'])
    ? preg_replace(
        '/[^A-Za-z0-9_\-]/',
        '',
        trim((string)$_POST['it_id'])
    )
    : '';

/*
 * 신규등록은 item_id가 원본 저장로직에서 생성/확정되는 방식이 있을 수 있으므로
 * 이번 동기화는 우선 기존 상품 수정(w=u)에 안전하게 적용합니다.
 */
$dm_post_w = isset($_POST['w'])
    ? trim((string)$_POST['w'])
    : '';

if (
    $dm_post_w === 'u' &&
    $dm_post_it_id !== '' &&
    $dm_post_condition_id > 0
) {
    register_shutdown_function(function () use (
        $g5,
        $dm_post_it_id,
        $dm_post_condition_id,
        $dm_post_group_id
    ) {
        $it_id_sql = sql_real_escape_string($dm_post_it_id);

        /*
         * 상품 저장 후 실제 소유 브랜드를 다시 읽습니다.
         * admin이 브랜드 상품을 수정해도 기존 it_brand를 기준으로 저장합니다.
         */
        $item = sql_fetch("
            SELECT it_id, it_brand
            FROM {$g5['g5_shop_item_table']}
            WHERE it_id = '{$it_id_sql}'
            LIMIT 1
        ");

        if (
            empty($item['it_id']) ||
            trim((string)$item['it_brand']) === ''
        ) {
            return;
        }

        $brand_id = trim((string)$item['it_brand']);
        $brand_sql = sql_real_escape_string($brand_id);

        /*
         * 선택한 배송조건이 이 브랜드 소유인지 검증.
         */
        $condition = sql_fetch("
            SELECT dc_id
            FROM donuts_delivery_conditions
            WHERE dc_id = '{$dm_post_condition_id}'
              AND LOWER(TRIM(brand_id)) = LOWER('{$brand_sql}')
              AND use_yn = 'Y'
            LIMIT 1
        ");

        if (empty($condition['dc_id'])) {
            return;
        }

        /*
         * 배송그룹 선택 시에도 같은 브랜드의 그룹인지 검증.
         * 선택 안 함(0)이면 NULL 저장.
         */
        $group_sql_value = 'NULL';

        if ($dm_post_group_id > 0) {
            $group = sql_fetch("
                SELECT dg_id
                FROM donuts_delivery_groups
                WHERE dg_id = '{$dm_post_group_id}'
                  AND LOWER(TRIM(brand_id)) = LOWER('{$brand_sql}')
                  AND use_yn = 'Y'
                LIMIT 1
            ");

            if (empty($group['dg_id'])) {
                return;
            }

            $group_sql_value = "'" . (int)$dm_post_group_id . "'";
        }

        /*
         * deliverymanage_update.php의 apply_products와 동일한 저장 구조.
         */
        sql_query("
            INSERT INTO donuts_delivery_product_settings
                (
                    brand_id,
                    it_id,
                    condition_id,
                    group_id,
                    created_at,
                    updated_at
                )
            VALUES
                (
                    '{$brand_sql}',
                    '{$it_id_sql}',
                    '{$dm_post_condition_id}',
                    {$group_sql_value},
                    NOW(),
                    NOW()
                )
            ON DUPLICATE KEY UPDATE
                condition_id = VALUES(condition_id),
                group_id = VALUES(group_id),
                updated_at = NOW()
        ");
    });
}

/*
 * 기존 검수/상품저장 로직은 그대로 사용.
 */
include('./itemformupdate_inspect.php');

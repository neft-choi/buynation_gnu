<?php
/*
 * 브랜드 상품 검수 저장 래퍼
 *
 * 실제 itemform.php는 이 파일로 저장합니다.
 * 기존 영카트 itemformupdate.php는 그대로 실행하고,
 * 저장 완료 시 브랜드 상품을 반드시 draft 상태로 등록합니다.
 */
include_once('./_common.php');
include_once(G5_LIB_PATH . '/donuts_item_inspection.lib.php');

$is_inspection_brand = donuts_item_inspection_is_brand($member['mb_id']);

if (!$is_inspection_brand) {
    include('./itemformupdate.php');
    exit;
}

$brand_id = trim((string)$member['mb_id']);

$it_id = isset($_POST['it_id'])
    ? preg_replace('/[^A-Za-z0-9_\-]/', '', trim($_POST['it_id']))
    : '';

if ($it_id === '') {
    alert('상품코드가 없습니다.');
}

$it_id_sql = sql_real_escape_string($it_id);

$current_inspection = donuts_item_inspection_get($it_id);

if (!empty($current_inspection['inspection_id']) && $current_inspection['status'] === 'pending') {
    alert(
        '현재 플랫폼 검수 중인 상품은 수정할 수 없습니다.',
        './iteminspectresult.php?status=pending'
    );
}

$before = sql_fetch("
    SELECT it_id
    FROM {$g5['g5_shop_item_table']}
    WHERE it_id = '{$it_id_sql}'
    LIMIT 1
");

$request_type = !empty($before['it_id']) ? 'update' : 'new';

/*
 * 실제 기존 itemformupdate.php가 읽는 값들을 브랜드 검수 정책에 맞춤.
 */
$_POST['it_brand'] = $brand_id;
$it_brand = $brand_id;

$_POST['it_use'] = '0';
$it_use = 0;

/*
 * 기존 itemformupdate.php는 alert/goto_url 등으로 종료될 수 있으므로
 * shutdown에서 실제 저장 성공 여부를 확인한 후 검수 row를 만듭니다.
 */
register_shutdown_function(function () use (
    $g5,
    $it_id,
    $it_id_sql,
    $brand_id,
    $request_type
) {
    $after = sql_fetch("
        SELECT it_id, it_brand
        FROM {$g5['g5_shop_item_table']}
        WHERE it_id = '{$it_id_sql}'
        LIMIT 1
    ");

    if (empty($after['it_id'])) {
        return;
    }

    /*
     * 브랜드 소유권과 판매대기를 확정.
     */
    $brand_sql = sql_real_escape_string($brand_id);

    sql_query("
        UPDATE {$g5['g5_shop_item_table']}
        SET
            it_brand = '{$brand_sql}',
            it_use = 0
        WHERE it_id = '{$it_id_sql}'
    ");

    /*
     * 상품관리에서 별도 배송조건을 아직 지정하지 않은 경우,
     * 해당 브랜드의 기본 배송조건을 상품에 자동 연결합니다.
     *
     * 이미 condition_id 또는 group_id가 지정된 경우 기존 설정은 보존합니다.
     */
    $delivery_setting = sql_fetch("
        SELECT condition_id, group_id
        FROM donuts_delivery_product_settings
        WHERE brand_id = '{$brand_sql}'
          AND it_id = '{$it_id_sql}'
        LIMIT 1
    ");

    if (empty($delivery_setting['condition_id'])) {
        $default_delivery = sql_fetch("
            SELECT dc_id
            FROM donuts_delivery_conditions
            WHERE brand_id = '{$brand_sql}'
              AND is_default = 1
              AND use_yn = 'Y'
            ORDER BY dc_id DESC
            LIMIT 1
        ");

        if (!empty($default_delivery['dc_id'])) {
            $default_condition_id = (int)$default_delivery['dc_id'];

            if (!empty($delivery_setting) || isset($delivery_setting['group_id'])) {
                /*
                 * 묶음배송 group_id가 이미 있다면 그대로 유지하고 condition_id만 보충.
                 */
                sql_query("
                    UPDATE donuts_delivery_product_settings
                    SET condition_id = '{$default_condition_id}'
                    WHERE brand_id = '{$brand_sql}'
                      AND it_id = '{$it_id_sql}'
                ");
            } else {
                sql_query("
                    INSERT INTO donuts_delivery_product_settings
                        (brand_id, it_id, condition_id, group_id)
                    VALUES
                        ('{$brand_sql}', '{$it_id_sql}', '{$default_condition_id}', NULL)
                ");
            }
        }
    }

    /*
     * 상품 저장 성공 시 무조건 draft.
     * 신규상품이 검수 목록에서 빠지는 것을 방지합니다.
     */
    donuts_item_inspection_ensure_draft(
        $it_id,
        $brand_id,
        $request_type,
        $brand_id
    );
});

include('./itemformupdate.php');

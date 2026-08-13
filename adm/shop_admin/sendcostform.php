<?php
include_once(G5_LIB_PATH . '/donuts_delivery.lib.php');

$item_delivery_brand_id = trim($it['it_brand']);
if ($item_delivery_brand_id === '') {
    $item_delivery_brand_id = trim($member['mb_id']);
}

$brand_id_sql = sql_real_escape_string($item_delivery_brand_id);

$conditions = array();
$condition_result = sql_query("SELECT c.*,
        (
            SELECT COUNT(*) 
            FROM donuts_delivery_product_settings ps 
            WHERE ps.brand_id = c.brand_id 
            AND ps.condition_id = c.dc_id
        ) AS product_count
    FROM donuts_delivery_conditions c
    WHERE c.brand_id = '{$brand_id_sql}'
    AND c.is_default = 1
    AND c.use_yn = 'Y'
    LIMIT 1");

// SQL 결과 묶음에서 한 행을 꺼내서 $row 배열에 저장하는 것을 반복
while ($row = sql_fetch_array($condition_result)) {
    $ranges = donuts_delivery_condition_ranges($row['dc_id']);

    // $row 배열에 ranges_json 키와 값을 추가로 저장
    // ranges_json 의 값은 배열 형태
    $row['ranges_json'] = json_encode(array_map(function ($r) {
        return array('min' => (int)$r['min_amount'], 'max' => ($r['max_amount'] === '' || $r['max_amount'] === null) ? null : (int)$r['max_amount'], 'fee' => (int)$r['dr_price']);
    }, $ranges), JSON_UNESCAPED_UNICODE);

    // 최종 완성된 $row 한 행을 $conditions 배열에 저장
    $conditions[] = $row;
}
?>
<style>
    :root {
        --line: #e7e9ed;
        --line-strong: #d8dce2;
        --text: #1c2028;
        --muted: #717782;
        --brand: #ff6a3d;
        --brand-dark: #e84d21;
        --brand-soft: #fff1eb;
        --yellow-soft: #fff8df;
    }

    .register-main {
        min-width: 0;
        display: flex;
        flex-direction: column;
    }

    .register-screen.active {
        display: block;
    }

    .register-screen-head {
        margin-bottom: 24px;
    }

    .register-screen-head h2 {
        margin-bottom: 5px;
        font-size: 20px;
        letter-spacing: -.03em;
    }

    .register-screen-head p {
        margin-bottom: 0;
        color: var(--muted);
        font-size: 12px;
    }

    .section-title {
        margin-bottom: 11px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
        font-weight: 800;
    }

    .register-condition {
        position: relative;
        min-height: 90px;
        padding: 14px 42px 14px 14px;
        border: 1px solid var(--line-strong);
        border-radius: 11px;
        background: #fff;
        cursor: pointer;
    }

    .register-condition:hover {
        border-color: #bfc4cc;
    }

    .register-condition.selected {
        border-color: var(--brand);
        background: #fffaf8;
        box-shadow: 0 0 0 2px rgba(255, 106, 61, .08);
    }

    .badge {
        min-height: 22px;
        padding: 2px 7px;
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        color: #5a616b;
        background: #f0f1f3;
        font-size: 10px;
        font-weight: 780;
        white-space: nowrap;
    }

    .badge-base {
        color: var(--brand-dark);
        background: var(--brand-soft);
    }
</style>

<div class="register-screen">
    <div class="register-screen-head">
        <h2>배송설정</h2>
        <p>배송조건으로 금액을 정하고, 배송그룹 선택 여부로 묶음·개별을 결정합니다.</p>
    </div>
    <div class="section-title">배송조건</div>
    <div id="registerConditions">
        <?php foreach ($conditions as $index => $condition) { ?>
            <button
                class="register-condition<?php echo $index === 0 ? ' selected' : ''; ?> w-full"
                type="button"
                data-condition-id="<?php echo (int) $condition['dc_id']; ?>"
                data-condition="<?php echo get_text($condition['dc_name']); ?>">
                <span class="block text-sm font-bold mb-1">
                    <?php echo get_text($condition['dc_name']); ?>
                    <?php if ($condition['is_default']) { ?>
                        <span class="badge badge-base">기본</span>
                    <?php } ?>
                </span>

                <span class="block text-xs text-(--muted)">
                    <?php echo delivery_type_label($condition['dc_type']); ?>
                </span>

                <div class="flex items-center justify-self-center gap-2 text-xs text-(--muted)">
                    <span>
                        배송비 <?php echo number_format((int) $condition['dc_price']); ?>원
                    </span>

                    <?php if ($condition['dc_type'] === 'conditional') { ?>
                        <span>/</span>

                        <span>
                            <?php echo number_format((int) $condition['dc_minimum']); ?>원 이상 무료
                        </span>
                    <?php } ?>
                </div>

                <?php if ((int) $condition['dc_jeju_use'] === 1) { ?>
                    <span class="block text-xs text-(--muted) mt-2">
                        제주 +<?php echo number_format((int) $condition['dc_jeju_price']); ?>원
                    </span>
                <?php } ?>

                <?php if ((int) $condition['dc_island_use'] === 1) { ?>
                    <span class="block text-xs text-(--muted)">
                        도서산간 +<?php echo number_format((int) $condition['dc_island_price']); ?>원
                    </span>
                <?php } ?>

                <span class="block text-xs text-(--muted) mt-2">
                    적용 상품 <span class="font-bold"><?php echo number_format((int) $condition['product_count']); ?>개</span>
                </span>
            </button>
        <?php } ?>
    </div>

    <div class="flex items-start gap-3 border border-[#f0e2b0] rounded-lg text-xs text-[#69531a] bg-(--yellow-soft) px-4 py-3 mt-3" id="regCalcResult">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info-icon lucide-info w-5 h-5">
            <circle cx="12" cy="12" r="10" />
            <path d="M12 16v-4" />
            <path d="M12 8h.01" />
        </svg>
        <div>
            <span class="block font-bold">합배송으로 등록됩니다.</span>
            <span class="block">기본 택배 적용시 같은 조건에 대해 1회 배송비를 부과합니다.</span>
            <span class="block">다른 조건을 적용시키려면 배송관리 > 배송조건 생성을 사용하십시오.</span>
        </div>
    </div>
</div>

<script>
    // 배송 조건 카드 클릭 시, 기존 배송비 전송 필드에 동기화 (현재 수정 전)
    // function bindRegisterCondition(card) {
    //     $(card).on("click", function() {
    //         $("#registerConditions .register-condition").removeClass("selected");
    //         $(this).addClass("selected");

    //         // it_sc_type는 배송유형, it_sc_method는 선불 후불, it_sc_price는 기본 배송비, it_sc_minimum 배송비 무료 최소 주문 금액 기준, 
    //         $("#it_sc_type").val($(this).attr("data-sc-type"));
    //         $("#it_sc_method").val($(this).attr("data-sc-method"));
    //         $("#it_sc_price").val($(this).attr("data-sc-price"));
    //         $("#it_sc_minimum").val($(this).attr("data-sc-minimum"));
    //         $("#it_sc_qty").val($(this).attr("data-sc-qty"));
    //     });
    // }

    // document.querySelectorAll('#registerConditions .register-condition').forEach(bindRegisterCondition);

    // <?php if ($w === '') { ?>
    //     $("#registerConditions .register-condition.selected").trigger("click");
    // <?php } ?>
</script>
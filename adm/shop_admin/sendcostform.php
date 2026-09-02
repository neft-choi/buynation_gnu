<?php
include_once(G5_LIB_PATH . '/donuts_delivery.lib.php');

$item_delivery_brand_id = trim($it['it_brand']);
if ($item_delivery_brand_id === '') {
    $item_delivery_brand_id = trim($member['mb_id']);
}

$brand_id_sql = sql_real_escape_string($item_delivery_brand_id);

$conditions = array();
$condition_result = sql_query(" SELECT c.*,
        (
            SELECT COUNT(*) 
            FROM donuts_delivery_product_settings ps 
            WHERE ps.brand_id = c.brand_id 
            AND ps.condition_id = c.dc_id
        ) AS product_count
    FROM donuts_delivery_conditions c
    WHERE c.brand_id = '{$brand_id_sql}'
    AND c.use_yn = 'Y'
    ORDER BY c.is_default DESC, c.dc_id ASC ");

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
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 14px;
        font-weight: 700;
    }

    /* .register-condition {
        position: relative;
        min-height: 90px;
        padding: 14px 42px 14px 14px;
        border: 1px solid var(--line-strong);
        border-radius: 11px;
        background: #fff;
        cursor: pointer;
    } */

    /* .register-condition:hover {
        border-color: #bfc4cc;
    } */

    /* .register-condition.selected {
        border-color: var(--brand);
        background: #fffaf8;
        box-shadow: 0 0 0 2px rgba(255, 106, 61, .08);
    } */

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

    <section class="mt-4">
        <div class="flex items-center justify-between">
            <div class="section-title">
                <span>배송조건<span class="text-red-500 ml-1">(필수)</span></span>
            </div>
        </div>

        <div id="registerConditions" class="grid grid-cols-2 gap-2">
            <?php foreach ($conditions as $index => $condition) {
                /*
                 * 수정 화면에서는 itemform.php가 전달한 실제 저장 condition_id를 우선 사용합니다.
                 * 신규등록에서만 저장값이 없으므로 첫 번째(기본) 조건을 초기 선택합니다.
                 */
                $render_selected_condition_id = 0;

                /*
                 * itemform.php에서 실제 DB 값을 담아 전달하는 변수는
                 * $dm_selected_condition_id 입니다. 이것을 최우선으로 사용합니다.
                 */
                if (isset($dm_selected_condition_id) && (int)$dm_selected_condition_id > 0) {
                    $render_selected_condition_id = (int)$dm_selected_condition_id;
                } elseif (isset($selected_condition_id) && (int)$selected_condition_id > 0) {
                    $render_selected_condition_id = (int)$selected_condition_id;
                } elseif (isset($delivery_condition_id) && (int)$delivery_condition_id > 0) {
                    $render_selected_condition_id = (int)$delivery_condition_id;
                } elseif (isset($condition_id) && (int)$condition_id > 0) {
                    $render_selected_condition_id = (int)$condition_id;
                }

                if ($render_selected_condition_id > 0) {
                    $is_condition_selected =
                        ((int)$condition['dc_id'] === $render_selected_condition_id);
                } else {
                    $is_condition_selected = ($index === 0);
                }
            ?>
                <button
                    class="register-condition <?php echo $is_condition_selected ? 'selected active !border-(--brand) !bg-[#fffaf8] !shadow-[0_0_0_2px_rgba(255,106,61,.08)]' : ''; ?> relative min-h-20 border border-(--line-strong) rounded-xl bg-white px-10 py-3 space-y-1 cursor-pointer hover:border-[#bfc4cc]"
                    type="button"
                    aria-checked="<?php echo $is_condition_selected ? 'true' : 'false'; ?>"
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
                <span class="block">동일한 배송조건의 상품을 여러 개 주문하면 배송비는 1회만 부과됩니다.</span>
            </div>
        </div>
    </section>

    <?php
    // 배송 그룹 SQL
    $groups = array();

    $group_result = sql_query("
                        SELECT g.*
                        FROM donuts_delivery_groups AS g
                        WHERE g.brand_id = '{$brand_id_sql}'
                        AND g.use_yn = 'Y'
                        ORDER BY g.dg_id ASC
                    ");

    while ($row = sql_fetch_array($group_result)) {
        $groups[] = $row;
    }
    ?>
    <section class="mt-8">
        <div class="section-title">
            <span>배송그룹<span class="text-blue-500 ml-1">(선택)</span></span>
        </div>

        <select
            name="group_id"
            id="delivery_group_id"
            class="w-full h-11 mt-3 px-3 border border-(--line-strong) rounded-lg bg-white">
            <option value="0" <?php
                $render_selected_group_id = 0;
                if (isset($dm_selected_group_id)) {
                    $render_selected_group_id = (int)$dm_selected_group_id;
                } elseif (isset($selected_group_id)) {
                    $render_selected_group_id = (int)$selected_group_id;
                } elseif (isset($delivery_group_id)) {
                    $render_selected_group_id = (int)$delivery_group_id;
                } elseif (isset($group_id)) {
                    $render_selected_group_id = (int)$group_id;
                }
                echo $render_selected_group_id <= 0 ? 'selected' : '';
            ?>>선택 안 함</option>

            <?php foreach ($groups as $group) { ?>
                <option
                    value="<?php echo (int) $group['dg_id']; ?>"
                    <?php echo ((int)$group['dg_id'] === $render_selected_group_id) ? 'selected' : ''; ?>>
                    <?php echo get_text($group['dg_name']); ?> - 계산 방식 <?php echo get_text($group['calc_method']); ?>
                </option>
            <?php } ?>
        </select>

        <div class="flex items-start gap-3 border border-[#f0e2b0] rounded-lg text-xs text-[#69531a] bg-(--yellow-soft) px-4 py-3 mt-3" id="regCalcResult">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info-icon lucide-info w-5 h-5">
                <circle cx="12" cy="12" r="10" />
                <path d="M12 16v-4" />
                <path d="M12 8h.01" />
            </svg>
            <div>
                <span class="block">서로 다른 배송조건의 상품을 하나의 그룹으로 묶어 배송비를 1회만 부과합니다.</span>
                <span class="block">그룹 내 배송비는 최고 배송비 또는 최저 배송비를 기준으로 부과할 수 있습니다.</span>
            </div>
        </div>
    </section>
</div>

<script>
    $(
        // 배송조건 선택 시 UI 클래스 반영
        function() {
            const selectedClasses = "selected !border-(--brand) !bg-[#fffaf8] !shadow-[0_0_0_2px_rgba(255,106,61,.08)]";

            $("#registerConditions .register-condition").on("click", function() {
                /*
                 * itemform.php가 수정값 복원을 위해 active 클래스를 사용할 수 있습니다.
                 * 사용자가 다른 조건을 클릭하면 이전 카드의 selected/active를 모두 제거해야
                 * 저장 직전 기본 배송조건(active)이 다시 선택되는 문제가 생기지 않습니다.
                 */
                $("#registerConditions .register-condition")
                    .removeClass(selectedClasses)
                    .removeClass("active")
                    .attr("aria-checked", "false");

                $(this)
                    .addClass(selectedClasses)
                    .addClass("active")
                    .attr("aria-checked", "true");

                // 실제로 클릭한 배송조건의 dc_id를 상품등록 hidden 필드에 즉시 저장
                const conditionId =
                    parseInt($(this).attr("data-condition-id"), 10) || 0;

                $("#dm_condition_id").val(conditionId);
            });

            // 배송그룹도 실제 select 값을 상품등록 hidden 필드에 즉시 저장
            $("#delivery_group_id").on("change", function() {
                $("#dm_group_id").val(parseInt($(this).val(), 10) || 0);
            });
        }
    );

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

    // 상품 저장 직전, 현재 UI에서 실제 선택된 값으로 최종 확정
    (function() {
        const form = document.querySelector('form[name="fitemform"]');
        if (!form) return;

        form.addEventListener("submit", function() {
            const selected = document.querySelector(
                "#registerConditions .register-condition.selected"
            );
            const conditionHidden = document.getElementById("dm_condition_id");
            const groupHidden = document.getElementById("dm_group_id");
            const groupSelect = document.getElementById("delivery_group_id");

            if (selected && conditionHidden) {
                conditionHidden.value =
                    parseInt(selected.getAttribute("data-condition-id"), 10) || 0;
            }

            if (groupHidden && groupSelect) {
                groupHidden.value =
                    parseInt(groupSelect.value, 10) || 0;
            }
        }, true);
    })();

</script>
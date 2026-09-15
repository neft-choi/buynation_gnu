<?php
$sub_menu = '400760';
include_once('./_common.php');
include_once(G5_LIB_PATH . '/donuts_delivery.lib.php');

auth_check_menu($auth, $sub_menu, 'r');
donuts_delivery_install();

sql_query("
    CREATE TABLE IF NOT EXISTS donuts_brand_sendcost (
        sc_id INT NOT NULL AUTO_INCREMENT,
        brand_id VARCHAR(255) NOT NULL DEFAULT '',
        sc_name VARCHAR(255) NOT NULL DEFAULT '',
        sc_zip1 VARCHAR(10) NOT NULL DEFAULT '',
        sc_zip2 VARCHAR(10) NOT NULL DEFAULT '',
        sc_price INT NOT NULL DEFAULT 0,
        reg_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        update_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (sc_id),
        KEY idx_brand_id (brand_id),
        KEY idx_brand_zip (brand_id, sc_zip1, sc_zip2)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
", false);

/*
 * 배송조건 <-> 추가배송비(sendcostlist) 연결 테이블
 *
 * sendcostlist.php의 실제 추가배송비 항목(g5_shop_sendcost_table)을
 * 배송조건별로 여러 개 선택해서 저장합니다.
 */
sql_query("
    CREATE TABLE IF NOT EXISTS donuts_delivery_condition_sendcosts (
        dc_id BIGINT UNSIGNED NOT NULL,
        sc_id BIGINT UNSIGNED NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (dc_id, sc_id),
        KEY idx_sc_id (sc_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
", false);

$brand_rows = array();
$brand_result = sql_query("SELECT brand_id FROM donuts_brand ORDER BY brand_id");
while ($br = sql_fetch_array($brand_result)) $brand_rows[] = $br['brand_id'];

/*
 * 관리할 브랜드 결정
 *
 * 브랜드 계정에 관리자 권한이 부여되어 $is_admin === 'super' 로 잡히는 경우에도
 * 자기 계정이 donuts_brand에 등록되어 있으면 자기 브랜드를 우선 사용합니다.
 * 최고관리자 계정처럼 donuts_brand에 없는 계정만 brand_id 선택값/첫 브랜드를 사용합니다.
 */
$current_mb_id = isset($member['mb_id']) ? trim($member['mb_id']) : '';
$current_brand = '';

if ($current_mb_id !== '') {
    $current_mb_id_sql = sql_real_escape_string($current_mb_id);

    $current_brand_row = sql_fetch("
        SELECT brand_id
        FROM donuts_brand
        WHERE TRIM(brand_id) = '{$current_mb_id_sql}'
        LIMIT 1
    ");

    if (!empty($current_brand_row['brand_id'])) {
        $current_brand = trim($current_brand_row['brand_id']);
    }
}

if ($current_brand !== '') {
    $manage_brand_id = $current_brand;
} elseif ($is_admin === 'super') {
    $manage_brand_id = isset($_GET['brand_id']) ? trim($_GET['brand_id']) : '';

    if (!$manage_brand_id || !in_array($manage_brand_id, $brand_rows, true)) {
        $manage_brand_id = !empty($brand_rows[0]) ? trim($brand_rows[0]) : '';
    }
} else {
    $manage_brand_id = $current_mb_id;

    if (!donuts_delivery_brand_exists($manage_brand_id)) {
        alert('브랜드 계정만 접근할 수 있습니다.');
    }
}

if (!$manage_brand_id) alert('등록된 브랜드가 없습니다.');

donuts_delivery_sync_brand_products($manage_brand_id);
$brand_id_sql = sql_real_escape_string($manage_brand_id);

$conditions = array();
$condition_result = sql_query("SELECT c.*,
    (SELECT COUNT(*) FROM donuts_delivery_product_settings ps WHERE ps.brand_id = c.brand_id AND ps.condition_id = c.dc_id) AS product_count
    FROM donuts_delivery_conditions c
    WHERE c.brand_id = '{$brand_id_sql}'
    ORDER BY c.is_default DESC, c.dc_id ASC");
while ($row = sql_fetch_array($condition_result)) {
    $ranges = donuts_delivery_condition_ranges($row['dc_id']);
    $row['ranges_json'] = json_encode(array_map(function ($r) {
        return array('min' => (int)$r['min_amount'], 'max' => ($r['max_amount'] === '' || $r['max_amount'] === null) ? null : (int)$r['max_amount'], 'fee' => (int)$r['dr_price']);
    }, $ranges), JSON_UNESCAPED_UNICODE);

    /*
     * 이 배송조건에 선택된 sendcostlist 항목.
     * 수정 버튼을 눌렀을 때 기존 선택을 다시 체크하기 위해 사용합니다.
     */
    $row['sendcost_ids'] = array();
    $row['sendcost_items'] = array();

    $sendcost_map_result = sql_query("
    SELECT m.sc_id, s.sc_name, s.sc_zip1, s.sc_zip2, s.sc_price
    FROM donuts_delivery_condition_sendcosts m
    INNER JOIN donuts_brand_sendcost s ON m.sc_id = s.sc_id
    WHERE s.brand_id = '{$brand_id_sql}'
      AND m.dc_id = '" . (int)$row['dc_id'] . "'
    ORDER BY m.sc_id ASC
", false);

    if ($sendcost_map_result) {
        while ($sendcost_map = sql_fetch_array($sendcost_map_result)) {
            $row['sendcost_ids'][] = (int)$sendcost_map['sc_id'];
            $row['sendcost_items'][] = array(
                'id' => (int)$sendcost_map['sc_id'],
                'name' => get_text($sendcost_map['sc_name']),
                'zip1' => get_text($sendcost_map['sc_zip1']),
                'zip2' => get_text($sendcost_map['sc_zip2']),
                'price' => (int)$sendcost_map['sc_price']
            );
        }
    }

    $row['sendcost_ids_json'] = json_encode(
        $row['sendcost_ids'],
        JSON_UNESCAPED_UNICODE
    );

    $row['sendcost_items_json'] = json_encode(
        $row['sendcost_items'],
        JSON_UNESCAPED_UNICODE
    );

    $conditions[] = $row;
}

$groups = array();
$group_result = sql_query("SELECT g.*,
    (SELECT COUNT(*) FROM donuts_delivery_product_settings ps WHERE ps.brand_id = g.brand_id AND ps.group_id = g.dg_id) AS product_count
    FROM donuts_delivery_groups g
    WHERE g.brand_id = '{$brand_id_sql}'
    ORDER BY g.dg_id ASC");
while ($row = sql_fetch_array($group_result)) $groups[] = $row;

$products = array();
$product_result = sql_query("SELECT i.it_id, i.it_name, i.it_price, i.it_stock_qty,
    ps.condition_id, ps.group_id, c.dc_name, c.dc_type, c.dc_price, c.dc_minimum, c.dc_qty,
    g.dg_name, g.calc_method
    FROM {$g5['g5_shop_item_table']} i
    LEFT JOIN donuts_delivery_product_settings ps ON ps.brand_id = '{$brand_id_sql}' AND ps.it_id = i.it_id
    LEFT JOIN donuts_delivery_conditions c ON c.dc_id = ps.condition_id
    LEFT JOIN donuts_delivery_groups g ON g.dg_id = ps.group_id
    WHERE TRIM(i.it_brand) = '{$brand_id_sql}'
    ORDER BY i.it_time DESC, i.it_id DESC");
while ($row = sql_fetch_array($product_result)) $products[] = $row;

$ungrouped = array_values(array_filter($products, function ($p) {
    return empty($p['group_id']);
}));
$applied_count = count($products);
$default_count = 0;
foreach ($conditions as $c) if (!empty($c['is_default'])) $default_count++;

function delivery_condition_main($row)
{
    if ($row['dc_type'] === 'free') return '0원';
    if ($row['dc_type'] === 'amount_range') {
        $r = donuts_delivery_condition_ranges($row['dc_id']);
        return '구간 ' . count($r) . '개';
    }
    return number_format((int)$row['dc_price']) . '원';
}
function delivery_condition_sub($row)
{
    if ($row['dc_type'] === 'conditional') return number_format((int)$row['dc_minimum']) . '원 이상 무료';
    if ($row['dc_type'] === 'quantity') return max(1, (int)$row['dc_qty']) . '개마다 반복';
    if ($row['dc_type'] === 'paid') return '주문 1건당 부과';
    if ($row['dc_type'] === 'amount_range') {
        $r = donuts_delivery_condition_ranges($row['dc_id']);
        if ($r) {
            $last = end($r);
            return number_format((int)$last['min_amount']) . '원 이상 ' . ((int)$last['dr_price'] === 0 ? '무료' : number_format((int)$last['dr_price']) . '원');
        }
    }
    return '';
}
function delivery_product_fee_text($p)
{
    if ($p['dc_type'] === 'free') return '무료';
    if ($p['dc_type'] === 'conditional') return number_format((int)$p['dc_price']) . '원 / ' . number_format((int)$p['dc_minimum']) . '원 이상 무료';
    if ($p['dc_type'] === 'quantity') return number_format((int)$p['dc_price']) . '원 / ' . max(1, (int)$p['dc_qty']) . '개마다';
    if ($p['dc_type'] === 'amount_range') return '금액 구간별';
    return number_format((int)$p['dc_price']) . '원';
}

$g5['title'] = '배송관리';
include_once(G5_ADMIN_PATH . '/admin.head.php');
$admin_token = get_admin_token();
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'conditions';
?>
<link rel="stylesheet" href="./assets/deliverymanage.css?v=20260908">
<div class="app-shell">
    <main class="main">
        <div class="content">
            <div class="page-head">
                <div>
                    <h1>배송관리</h1>
                    <p><strong><?php echo get_text($manage_brand_id); ?></strong> 브랜드의 배송조건과 묶음배송 그룹을 별도로 관리합니다.</p>
                </div>
                <div style="display:flex;gap:8px;align-items:center">
                    <?php if ($is_admin === 'super') { ?>
                        <form method="get" style="display:flex;gap:8px;align-items:center">
                            <select class="select" name="brand_id" onchange="this.form.submit()">
                                <?php foreach ($brand_rows as $bid) { ?><option value="<?php echo get_text($bid); ?>" <?php echo $bid === $manage_brand_id ? 'selected' : ''; ?>><?php echo get_text($bid); ?></option><?php } ?>
                            </select>
                        </form>
                    <?php } ?>
                    <button class="btn btn-primary" id="openCreate" type="button"><span class="plus">＋</span><span class="btn-label">배송조건 추가</span></button>
                </div>
            </div>

            <nav class="tabs" aria-label="배송 관리 탭">
                <button class="tab <?php echo $active_tab === 'conditions' ? 'active' : ''; ?>" type="button" data-tab="conditions">배송조건 <span><?php echo count($conditions); ?></span></button>
                <button class="tab <?php echo $active_tab === 'groups' ? 'active' : ''; ?>" type="button" data-tab="groups">묶음배송 그룹 <span><?php echo count($groups); ?></span></button>
            </nav>

            <section class="panel <?php echo $active_tab === 'conditions' ? 'active' : ''; ?>" id="panel-conditions">
                <div class="guide">
                    <div>
                        <h2>배송조건과 배송그룹은 별개의 설정입니다</h2>
                        <p>배송조건은 금액을 정하고, 배송그룹은 함께 묶어 한 번만 부과할 상품 범위를 정합니다.</p>
                    </div>
                    <div class="flow">
                        <div class="flow-step"><span class="step-no">1</span>
                            <div><strong>배송조건</strong><small>상품별 금액 규칙</small></div>
                        </div>
                        <div class="flow-arrow">›</div>
                        <div class="flow-step"><span class="step-no">2</span>
                            <div><strong>배송그룹</strong><small>선택 시 묶음배송</small></div>
                        </div>
                        <div class="flow-arrow">›</div>
                        <div class="flow-step"><span class="step-no">3</span>
                            <div><strong>주문 계산</strong><small>개별합산 또는 MIN/MAX</small></div>
                        </div>
                    </div>
                </div>
                <div class="summary-grid">
                    <div class="summary-card">
                        <div class="label">사용 중인 배송조건</div>
                        <div class="value"><?php echo count($conditions); ?>개</div>
                        <div class="sub">브랜드별 독립 저장</div>
                    </div>
                    <div class="summary-card">
                        <div class="label">배송조건 적용 상품</div>
                        <div class="value"><?php echo number_format($applied_count); ?>개</div>
                        <div class="sub">미설정 상품은 기본조건 자동 적용</div>
                    </div>
                    <div class="summary-card">
                        <div class="label">자동 생성 기본조건</div>
                        <div class="value"><?php echo number_format($default_count); ?>개</div>
                        <div class="sub">기본조건은 삭제 불가</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-head">
                        <div>
                            <h2>배송조건 목록</h2>
                            <p>현재 브랜드에만 적용됩니다.</p>
                        </div>
                        <div class="filters">
                            <div class="search-wrap"><input class="field search-field" id="conditionSearch" type="search" placeholder="조건명 검색"></div><select class="select" id="typeFilter">
                                <option value="all">전체 유형</option>
                                <option value="paid">유료</option>
                                <option value="conditional">조건부 무료</option>
                                <option value="free">무료</option>
                                <option value="quantity">수량별</option>
                                <option value="amount_range">금액 구간별</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>배송조건명</th>
                                    <th>배송비 유형</th>
                                    <th>배송비 설정</th>
                                    <th>적용 상품</th>
                                    <th>지역 추가비</th>
                                    <th>관리</th>
                                </tr>
                            </thead>
                            <tbody id="conditionRows">
                                <?php foreach ($conditions as $c) { ?>
                                    <tr data-type="<?php echo get_text($c['dc_type']); ?>" data-name="<?php echo get_text($c['dc_name']); ?>">
                                        <td>
                                            <div class="condition-name"><strong><?php echo get_text($c['dc_name']); ?></strong><?php if ($c['is_default']) { ?><span class="badge badge-base">기본</span><?php } ?></div><span class="condition-desc"><?php echo $c['is_default'] ? '브랜드 기본 배송조건' : '직접 추가한 배송조건'; ?></span>
                                        </td>

                                        <td><?php echo delivery_type_label($c['dc_type']); ?></td>

                                        <td><strong><?php echo delivery_condition_main($c); ?></strong><?php $sub = delivery_condition_sub($c);
                                                                                                        if ($sub) { ?><span class="condition-desc"><?php echo get_text($sub); ?></span><?php } ?></td>
                                        <td>
                                            <button class="usage condition-products-btn"
                                                type="button"
                                                data-condition-id="<?php echo (int)$c['dc_id']; ?>"
                                                data-condition-name="<?php echo get_text($c['dc_name']); ?>"
                                                title="이 배송조건을 적용할 상품 선택"><?php echo number_format((int)$c['product_count']); ?>개</button>
                                        </td>

                                        <td>
                                            <?php if (!empty($c['sendcost_items'])) { ?>
                                                <button
                                                    class="text-btn view-sendcosts"
                                                    type="button"
                                                    data-condition-id="<?php echo (int)$c['dc_id']; ?>"
                                                    data-condition-name="<?php echo get_text($c['dc_name']); ?>"
                                                    data-sendcost-items='<?php echo htmlspecialchars($c['sendcost_items_json'], ENT_QUOTES); ?>'>보기</button>
                                            <?php } else { ?>
                                                <span class="condition-desc">미사용</span>
                                            <?php } ?>
                                        </td>

                                        <td>
                                            <div class="row-actions">
                                                <button class="text-btn edit-condition" type="button"
                                                    data-id="<?php echo (int)$c['dc_id']; ?>" data-name="<?php echo get_text($c['dc_name']); ?>" data-type="<?php echo get_text($c['dc_type']); ?>" data-price="<?php echo (int)$c['dc_price']; ?>" data-minimum="<?php echo (int)$c['dc_minimum']; ?>" data-qty="<?php echo (int)$c['dc_qty']; ?>" data-jeju-use="<?php echo (int)$c['dc_jeju_use']; ?>" data-jeju-price="<?php echo (int)$c['dc_jeju_price']; ?>" data-island-use="<?php echo (int)$c['dc_island_use']; ?>" data-island-price="<?php echo (int)$c['dc_island_price']; ?>" data-ranges='<?php echo htmlspecialchars($c['ranges_json'], ENT_QUOTES); ?>' data-sendcosts='<?php echo htmlspecialchars($c['sendcost_ids_json'], ENT_QUOTES); ?>'>수정</button>
                                                <form method="post" action="./deliverymanage_update.php" style="display:inline"><input type="hidden" name="token" value="<?php echo get_text($admin_token); ?>"><input type="hidden" name="brand_id" value="<?php echo get_text($manage_brand_id); ?>"><input type="hidden" name="action" value="clone_condition"><input type="hidden" name="dc_id" value="<?php echo (int)$c['dc_id']; ?>"><button class="text-btn" type="submit">복제</button></form>
                                                <?php if (!$c['is_default']) { ?><form method="post" action="./deliverymanage_update.php" style="display:inline" onsubmit="return confirm('이 배송조건을 삭제하시겠습니까?');"><input type="hidden" name="token" value="<?php echo get_text($admin_token); ?>"><input type="hidden" name="brand_id" value="<?php echo get_text($manage_brand_id); ?>"><input type="hidden" name="action" value="delete_condition"><input type="hidden" name="dc_id" value="<?php echo (int)$c['dc_id']; ?>"><button class="text-btn" type="submit">삭제</button></form><?php } ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="empty-search" id="emptySearch">조건에 맞는 배송조건이 없습니다.</div>
                </div>
            </section>

            <section class="panel <?php echo $active_tab === 'groups' ? 'active' : ''; ?>" id="panel-groups">
                <div class="guide">
                    <div>
                        <h2>묶음배송 그룹</h2>
                        <p>같은 그룹 상품은 주문 시 배송비를 한 번만 계산합니다.</p>
                    </div>
                    <div class="flow">
                        <div class="flow-step"><span class="step-no">1</span>
                            <div><strong>그룹 생성</strong><small>합포장 가능한 범위</small></div>
                        </div>
                        <div class="flow-arrow">›</div>
                        <div class="flow-step"><span class="step-no">2</span>
                            <div><strong>MIN / MAX</strong><small>대표 배송비 기준</small></div>
                        </div>
                        <div class="flow-arrow">›</div>
                        <div class="flow-step"><span class="step-no">3</span>
                            <div><strong>상품 이동</strong><small>실제 DB에 저장</small></div>
                        </div>
                    </div>
                </div>
                <div class="card" style="padding:20px">
                    <div class="card-head" style="padding:0 0 17px;min-height:auto;margin-bottom:15px">
                        <div>
                            <h2>묶음배송 그룹</h2>
                            <p>브랜드별로 독립 관리됩니다.</p>
                        </div><button class="btn btn-small" id="openGroupCreate" type="button">＋ 그룹 추가</button>
                    </div>
                    <div class="group-grid" id="groupGrid">
                        <?php foreach ($groups as $g) { ?>
                            <article class="group-card" data-group-id="<?php echo (int)$g['dg_id']; ?>" data-group-name="<?php echo get_text($g['dg_name']); ?>">
                                <div class="group-top">
                                    <div>
                                        <h3><?php echo get_text($g['dg_name']); ?></h3>
                                        <p>묶음배송 그룹</p>
                                    </div><span class="badge badge-bundle">사용 중</span>
                                </div>
                                <div class="group-meta">
                                    <div><span>상품 수</span><strong><?php echo number_format((int)$g['product_count']); ?>개</strong></div>
                                    <div><span>계산 방식</span><strong><?php echo get_text($g['calc_method']); ?></strong></div>
                                    <div><span>적용 방식</span><strong>1회 부과</strong></div>
                                </div>
                                <div style="display:flex;gap:8px;margin-top:14px"><button class="btn btn-small group-products" type="button" style="flex:1">기존 상품 추가·이동</button>
                                    <form method="post" action="./deliverymanage_update.php" onsubmit="return confirm('그룹을 삭제하시겠습니까? 상품이 포함되어 있으면 삭제되지 않습니다.');"><input type="hidden" name="token" value="<?php echo get_text($admin_token); ?>"><input type="hidden" name="brand_id" value="<?php echo get_text($manage_brand_id); ?>"><input type="hidden" name="action" value="delete_group"><input type="hidden" name="dg_id" value="<?php echo (int)$g['dg_id']; ?>"><button class="btn btn-small" type="submit">삭제</button></form>
                                </div>
                            </article>
                        <?php } ?>
                        <?php if (!$groups) { ?><div class="empty-products" style="display:block">등록된 묶음배송 그룹이 없습니다.</div><?php } ?>
                    </div>
                </div>
            </section>

        </div>
    </main>
</div>

<!-- 지역 추가비 보기 -->
<div class="sendcost-view-overlay" id="sendcostViewOverlay" aria-hidden="true">
    <section
        class="sendcost-view-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="sendcostViewTitle">
        <div class="sendcost-view-head">
            <div>
                <h3 id="sendcostViewTitle">지역 추가비</h3>
                <p id="sendcostViewSub">선택된 추가배송비 내역입니다.</p>
            </div>
            <button
                class="close-btn"
                id="closeSendcostView"
                type="button"
                aria-label="닫기">×</button>
        </div>

        <div class="sendcost-view-body">
            <div class="sendcost-view-list" id="sendcostViewList"></div>
            <div class="sendcost-view-empty" id="sendcostViewEmpty" style="display:none;">
                적용된 지역 추가비가 없습니다.
            </div>
        </div>
    </section>
</div>

<div class="overlay" id="overlay"></div>

<aside class="drawer" id="conditionDrawer">
    <div class="drawer-head">
        <div>
            <h2 id="drawerTitle">배송조건 추가</h2>
            <p>실제 브랜드 배송비 규칙으로 저장됩니다.</p>
        </div><button class="close-btn" type="button" data-close>×</button>
    </div>
    <form id="fdeliverycondition" method="post" action="./deliverymanage_update.php"><input type="hidden" name="token" value="<?php echo get_text($admin_token); ?>"><input type="hidden" name="brand_id" value="<?php echo get_text($manage_brand_id); ?>"><input type="hidden" name="action" value="save_condition"><input type="hidden" name="dc_id" id="dc_id" value=""><input type="hidden" name="dc_type" id="dc_type" value="conditional">
        <div class="drawer-body">
            <div class="form-section"><label class="form-label">배송조건명</label><input class="form-control" id="conditionName" name="dc_name" maxlength="100" required></div>
            <div class="form-section">
                <div class="section-title">배송비 유형</div>
                <div class="fee-types" id="feeTypes"><button class="fee-type" type="button" data-fee="paid">유료</button><button class="fee-type active" type="button" data-fee="conditional">조건부 무료</button><button class="fee-type" type="button" data-fee="free">무료</button><button class="fee-type" type="button" data-fee="quantity">수량별</button><button class="fee-type" type="button" data-fee="amount_range">금액 구간별</button></div>
            </div>
            <div class="form-section" id="feeFields">
                <div class="two-col">
                    <div class="input-unit"><label class="form-label">기본 배송비</label><input class="form-control" id="baseFee" name="dc_price" type="number" min="0" value="3000"><span class="unit">원</span></div>
                    <div class="input-unit" id="thresholdWrap"><label class="form-label">무료배송 기준</label><input class="form-control" id="freeThreshold" name="dc_minimum" type="number" min="0" value="50000"><span class="unit">원</span></div>
                    <div class="input-unit" id="quantityWrap" style="display:none"><label class="form-label">반복 부과 수량</label><input class="form-control" id="repeatQuantity" name="dc_qty" type="number" min="1" value="1"><span class="unit">개</span></div>
                </div>
            </div>
            <div class="form-section" id="amountRangeFields" style="display:none">
                <div class="section-title">주문금액별 배송비 구간</div>
                <div class="amount-range-editor">
                    <div class="amount-range-head"><span>시작 금액</span><span>종료 금액</span><span>배송비</span><span></span></div>
                    <div id="amountRangeRows"></div><button class="range-add" id="addAmountRange" type="button">＋ 구간 추가</button>
                </div>
            </div>

            <?php
            // 추가 배송비 SQL
            $sendcost_result = sql_query("
                SELECT sc_id, sc_name, sc_zip1, sc_zip2, sc_price
                FROM donuts_brand_sendcost
                WHERE brand_id = '{$brand_id_sql}'
                ORDER BY sc_id DESC
            ");
            ?>
            <div class="form-section">
                <div class="section-title">추가배송비 적용</div>
                <p class="condition-desc">추가배송비관리에서 등록한 지역을 선택합니다.</p>

                <div class="product-list mt-4">
                    <?php for ($i = 0; $sendcost = sql_fetch_array($sendcost_result); $i++) { ?>
                        <label
                            class="product-row sendcost-row"
                            data-sendcost-id="<?php echo (int)$sendcost['sc_id']; ?>"
                            style="cursor:pointer">
                            <input
                                class="sendcost-check"
                                type="checkbox"
                                name="sendcost_ids[]"
                                value="<?php echo (int)$sendcost['sc_id']; ?>">

                            <div class="product-info">
                                <div>
                                    <p class="text-xs font-medium"><?php echo get_text($sendcost['sc_name']); ?></p>
                                    <div class="flex items-center gap-2 text-xs text-gray-500 font-normal mt-1">
                                        <span>우편번호 <?php echo get_text($sendcost['sc_zip1']); ?> ~ <?php echo get_text($sendcost['sc_zip2']); ?></span>
                                        <span>/</span>
                                        <span>추가배송비 + <?php echo number_format((int)$sendcost['sc_price']); ?>원</span>
                                    </div>
                                </div>
                            </div>

                            <span class="badge badge-base sendcost-selected-badge" style="display:none;">선택됨</span>
                        </label>
                    <?php } ?>

                    <?php if ($i === 0) { ?>
                        <div class="empty-products" style="display:block">등록된 추가배송비 내역이 없습니다.</div>
                    <?php } ?>
                </div>
            </div>
            <div class="fee-preview" id="feePreview"></div>
        </div>
        <div class="drawer-foot"><button class="btn" type="button" data-close>취소</button><button class="btn btn-brand" type="submit">저장</button></div>
    </form>
</aside>

<aside class="drawer" id="groupDrawer">
    <div class="drawer-head">
        <div>
            <h2>묶음배송 그룹 추가</h2>
            <p>같이 주문된 상품의 배송비를 MIN 또는 MAX 한 번만 부과합니다.</p>
        </div><button class="close-btn" type="button" data-close>×</button>
    </div>
    <form method="post" action="./deliverymanage_update.php"><input type="hidden" name="token" value="<?php echo get_text($admin_token); ?>"><input type="hidden" name="brand_id" value="<?php echo get_text($manage_brand_id); ?>"><input type="hidden" name="action" value="save_group">
        <div class="drawer-body">
            <div class="form-section"><label class="form-label">그룹명</label><input class="form-control" name="dg_name" required maxlength="100"></div>
            <div class="form-section">
                <div class="section-title">계산 방식</div>
                <div class="choice-grid"><label class="choice-card"><input type="radio" name="calc_method" value="MIN"><span class="radio-mark"></span><strong>MIN · 최저 배송비</strong><small>그룹 상품 중 가장 낮은 배송비 1회</small></label><label class="choice-card selected"><input type="radio" name="calc_method" value="MAX" checked><span class="radio-mark"></span><strong>MAX · 최고 배송비</strong><small>그룹 상품 중 가장 높은 배송비 1회</small></label></div>
            </div>
        </div>
        <div class="drawer-foot"><button class="btn" type="button" data-close>취소</button><button class="btn btn-brand" type="submit">그룹 저장</button></div>
    </form>
</aside>


<aside class="drawer" id="conditionProductDrawer">
    <div class="drawer-head shrink-0">
        <div>
            <h2 id="conditionProductTitle">배송조건 적용 상품</h2>
            <p id="conditionProductSub">이 배송조건을 사용할 상품을 선택합니다.</p>
        </div>
        <button class="close-btn" type="button" data-close>×</button>
    </div>

    <form method="post" action="./deliverymanage_update.php" id="conditionProductForm" class="flex min-h-0 flex-1 flex-col">
        <input type="hidden" name="token" value="<?php echo get_text($admin_token); ?>">
        <input type="hidden" name="brand_id" value="<?php echo get_text($manage_brand_id); ?>">
        <input type="hidden" name="action" value="sync_condition_products">
        <input type="hidden" name="condition_id" id="conditionProductConditionId" value="">

        <div class="drawer-body min-h-0 flex-1 overflow-y-auto px-6 pt-[22px] pb-[34px]">
            <div class="rule-note" style="margin:0 0 16px">
                <span class="info-dot">i</span>
                <span>
                    <strong>체크한 상품에 선택한 배송조건이 적용됩니다.</strong><br>
                    다른 배송조건을 사용 중인 상품을 체크하면 해당 조건으로 변경됩니다.
                    검색 결과에서 체크한 상품만 저장되며 기존 다른 상품의 설정은 유지됩니다.
                </span>
            </div>

            <div style="margin-bottom:18px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <strong>현재 적용 상품</strong>
                    <span><strong id="conditionAppliedCount">0</strong>개</span>
                </div>

                <div class="product-pick-list" id="conditionAppliedList"></div>

                <div class="empty-products" id="conditionAppliedEmpty" style="display:block;">
                    적용 상품을 불러오는 중입니다.
                </div>
            </div>

            <hr style="border:0;border-top:1px solid #eee;margin:18px 0;">

            <div class="two-col select-block">
                <div>
                    <label class="form-label" for="conditionProductSearch">상품 검색</label>
                    <input class="form-control" id="conditionProductSearch" type="search" placeholder="상품명 또는 상품코드">
                </div>
                <div>
                    <label class="form-label" for="conditionProductFilter">현재 배송조건</label>
                    <select class="form-control" id="conditionProductFilter">
                        <option value="all">전체 상품</option>
                        <option value="target">현재 이 조건 사용 상품</option>
                        <option value="other">다른 조건 사용 상품</option>
                    </select>
                </div>
            </div>

            <div class="picker-summary">
                <span><strong id="conditionProductVisibleCount">0</strong>개 상품 표시</span>
                <span>선택 <strong id="conditionProductSelectedCount">0</strong>개</span>
            </div>

            <label style="display:flex;align-items:center;gap:8px;margin:10px 0 12px">
                <input type="checkbox" id="conditionProductSelectAll">
                현재 표시 상품 전체 선택
            </label>

            <div class="product-pick-list" id="conditionProductList"></div>

            <div class="empty-products" id="conditionProductEmpty" style="display:block">
                상품명 또는 상품코드를 입력하면 검색 결과가 이곳에 표시됩니다.
            </div>

            <div id="conditionProductSelectedHidden"></div>
        </div>

        <div class="drawer-foot shrink-0">
            <button class="btn" type="button" data-close>취소</button>
            <button class="btn btn-brand" type="submit">선택 상품 적용</button>
        </div>
    </form>
</aside>

<aside class="drawer" id="applyDrawer">
    <div class="drawer-head">
        <div>
            <h2>상품 배송설정</h2>
            <p>선택 상품의 배송조건과 묶음배송 그룹을 저장합니다.</p>
        </div><button class="close-btn" type="button" data-close>×</button>
    </div>
    <form method="post" action="./deliverymanage_update.php" id="applyForm"><input type="hidden" name="token" value="<?php echo get_text($admin_token); ?>"><input type="hidden" name="brand_id" value="<?php echo get_text($manage_brand_id); ?>"><input type="hidden" name="action" value="apply_products">
        <div id="applyProductIds"></div>
        <div class="drawer-body">
            <div class="select-block"><label class="form-label">배송조건</label><select name="condition_id" id="applyCondition" required><?php foreach ($conditions as $c) { ?><option value="<?php echo (int)$c['dc_id']; ?>"><?php echo get_text($c['dc_name']); ?> — <?php echo delivery_type_label($c['dc_type']); ?></option><?php } ?></select></div>
            <div class="select-block"><label class="form-label">묶음배송 그룹</label><select name="group_id" id="applyGroup">
                    <option value="0">선택 안 함 · 개별배송</option><?php foreach ($groups as $g) { ?><option value="<?php echo (int)$g['dg_id']; ?>"><?php echo get_text($g['dg_name']); ?> · <?php echo get_text($g['calc_method']); ?></option><?php } ?>
                </select></div>
            <div class="branch-box individual"><span class="info-dot">i</span><span>그룹을 선택하지 않으면 상품별 배송비가 각각 합산됩니다.</span></div>
        </div>
        <div class="drawer-foot"><button class="btn" type="button" data-close>취소</button><button class="btn btn-brand" type="submit">상품에 적용</button></div>
    </form>
</aside>

<aside class="drawer" id="productGroupDrawer">
    <div class="drawer-head">
        <div>
            <h2 id="productGroupTitle">기존 상품 추가·이동</h2>
            <p>상품명 또는 상품코드를 검색한 뒤 대상 그룹으로 이동합니다.</p>
        </div>
        <button class="close-btn" type="button" data-close>×</button>
    </div>

    <form method="post" action="./deliverymanage_update.php" id="moveForm">
        <input type="hidden" name="token" value="<?php echo get_text($admin_token); ?>">
        <input type="hidden" name="brand_id" value="<?php echo get_text($manage_brand_id); ?>">
        <input type="hidden" name="action" value="move_products">
        <input type="hidden" name="group_id" id="moveGroupId">

        <div id="moveSelectedProductIds"></div>

        <div class="drawer-body">
            <div style="margin-bottom:18px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <strong>현재 적용 상품</strong>
                    <span><strong id="groupAppliedCount">0</strong>개</span>
                </div>

                <div class="product-pick-list" id="groupAppliedList"></div>

                <div class="empty-products" id="groupAppliedEmpty" style="display:block;">
                    현재 적용 상품을 불러오는 중입니다.
                </div>
            </div>

            <hr style="border:0;border-top:1px solid #eee;margin:18px 0;">

            <div class="two-col select-block">
                <div>
                    <label class="form-label" for="productPickSearch">상품 검색</label>
                    <input class="form-control"
                        id="productPickSearch"
                        type="search"
                        placeholder="상품명 또는 상품코드 입력"
                        autocomplete="off">
                </div>

                <div>
                    <label class="form-label" for="productSourceFilter">현재 소속</label>
                    <select class="form-control" id="productSourceFilter">
                        <option value="all">전체</option>
                        <option value="none">그룹 미지정</option>
                        <?php foreach ($groups as $g) { ?>
                            <option value="<?php echo (int)$g['dg_id']; ?>">
                                <?php echo get_text($g['dg_name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="picker-summary" style="margin-top:12px;">
                <span>검색 결과 <strong id="productPickCount">0</strong>개</span>
                <span>선택 <strong id="moveSelectedCount">0</strong>개</span>
            </div>

            <div class="product-pick-list" id="productPickList"></div>

            <div class="empty-products" id="emptyProductPick" style="display:block;">
                상품명 또는 상품코드를 입력하면 검색 결과가 표시됩니다.
            </div>
        </div>

        <div class="drawer-foot">
            <button class="btn" type="button" data-close>취소</button>
            <button class="btn btn-brand" type="submit">선택 상품 이동</button>
        </div>
    </form>
</aside>

<div class="toast" id="toast"><span class="toast-check">✓</span><span id="toastText"></span></div>

<!-- <script src="./assets/deliverymanage.js"></script> -->
 <script>
    (function() {
        const $ = (id) => document.getElementById(id);

        const overlay = $('overlay');
        const drawers = [...document.querySelectorAll('.drawer')];
        const conditionDrawer = $('conditionDrawer');
        const conditionProductDrawer = $('conditionProductDrawer');
        const applyDrawer = $('applyDrawer');
        const groupDrawer = $('groupDrawer');
        const productGroupDrawer = $('productGroupDrawer');

        const conditionProductTitle = $('conditionProductTitle');
        const conditionProductSub = $('conditionProductSub');
        const conditionProductConditionId = $('conditionProductConditionId');
        const conditionProductSearch = $('conditionProductSearch');
        const conditionProductFilter = $('conditionProductFilter');
        const conditionProductList = $('conditionProductList');
        const conditionProductSelectAll = $('conditionProductSelectAll');
        const conditionProductVisibleCount = $('conditionProductVisibleCount');
        const conditionProductSelectedCount = $('conditionProductSelectedCount');
        const conditionProductEmpty = $('conditionProductEmpty');
        const conditionProductSelectedHidden = $('conditionProductSelectedHidden');

        const conditionAppliedList = $('conditionAppliedList');
        const conditionAppliedEmpty = $('conditionAppliedEmpty');
        const conditionAppliedCount = $('conditionAppliedCount');

        const conditionSelectedIds = new Set();
        let conditionSearchTimer = null;
        let conditionSearchController = null;

        const openCreate = $('openCreate');
        const openGroupCreate = $('openGroupCreate');
        const conditionSearch = $('conditionSearch');
        const typeFilter = $('typeFilter');
        const emptySearch = $('emptySearch');

        const dcId = $('dc_id');
        const dcType = $('dc_type');
        const conditionName = $('conditionName');
        const baseFee = $('baseFee');
        const freeThreshold = $('freeThreshold');
        const repeatQuantity = $('repeatQuantity');
        const jejuUse = $('jejuUse');
        const jejuPrice = $('jejuPrice');
        const islandUse = $('islandUse');
        const islandPrice = $('islandPrice');
        const amountRangeRows = $('amountRangeRows');
        const addAmountRange = $('addAmountRange');
        const drawerTitle = $('drawerTitle');
        const feePreview = $('feePreview');

        const applyIds = $('applyProductIds');
        const applyCondition = $('applyCondition');
        const applyGroup = $('applyGroup');
        const openApplySecond = $('openApplySecond');
        const selectAllUngrouped = $('selectAllUngrouped');

        const moveGroupId = $('moveGroupId');
        const productGroupTitle = $('productGroupTitle');
        const productPickSearch = $('productPickSearch');
        const productSourceFilter = $('productSourceFilter');
        const productPickList = $('productPickList');
        const productPickCount = $('productPickCount');
        const emptyProductPick = $('emptyProductPick');
        const moveSelectedProductIds = $('moveSelectedProductIds');
        const moveSelectedCount = $('moveSelectedCount');

        const groupAppliedList = $('groupAppliedList');
        const groupAppliedEmpty = $('groupAppliedEmpty');
        const groupAppliedCount = $('groupAppliedCount');

        const moveSelectedIds = new Set();
        let moveSearchTimer = null;
        let moveSearchController = null;
        const moveForm = $('moveForm');

        function openDrawer(el) {
            if (!el || !overlay) return;
            drawers.forEach(d => d.classList.remove('open'));
            overlay.classList.add('open');
            el.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeDrawers() {
            if (overlay) overlay.classList.remove('open');
            drawers.forEach(d => d.classList.remove('open'));
            document.body.style.overflow = '';
        }

        document.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', closeDrawers));
        if (overlay) overlay.addEventListener('click', closeDrawers);

        document.querySelectorAll('.tab').forEach(b => b.addEventListener('click', () => {
            document.querySelectorAll('.tab').forEach(x => x.classList.remove('active'));
            document.querySelectorAll('.panel').forEach(x => x.classList.remove('active'));
            b.classList.add('active');
            const panel = $('panel-' + b.dataset.tab);
            if (panel) panel.classList.add('active');
        }));

        let activeFee = 'conditional';

        function setFeeType(type) {
            activeFee = type;
            if (dcType) dcType.value = type;
            document.querySelectorAll('.fee-type').forEach(b => b.classList.toggle('active', b.dataset.fee === type));
            const feeFields = $('feeFields');
            const amountRangeFields = $('amountRangeFields');
            const thresholdWrap = $('thresholdWrap');
            const quantityWrap = $('quantityWrap');
            if (feeFields) feeFields.style.display = (type === 'free' || type === 'amount_range') ? 'none' : 'block';
            if (amountRangeFields) amountRangeFields.style.display = type === 'amount_range' ? 'block' : 'none';
            if (thresholdWrap) thresholdWrap.style.display = type === 'conditional' ? 'block' : 'none';
            if (quantityWrap) quantityWrap.style.display = type === 'quantity' ? 'block' : 'none';
            updatePreview();
        }

        /*
         * 배송조건 목록 > 지역 추가비 > 보기
         *
         * 서버에서 미리 조회한 sendcost_items_json을 그대로 사용하므로
         * 보기 클릭 시 별도 AJAX 없이 즉시 표시합니다.
         */
        const sendcostViewOverlay = $('sendcostViewOverlay');
        const sendcostViewTitle = $('sendcostViewTitle');
        const sendcostViewSub = $('sendcostViewSub');
        const sendcostViewList = $('sendcostViewList');
        const sendcostViewEmpty = $('sendcostViewEmpty');
        const closeSendcostView = $('closeSendcostView');

        function escapeSendcostHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function closeSendcostViewModal() {
            if (!sendcostViewOverlay) return;

            sendcostViewOverlay.classList.remove('open');
            sendcostViewOverlay.setAttribute('aria-hidden', 'true');
        }

        function openSendcostViewModal(button) {
            if (
                !sendcostViewOverlay ||
                !sendcostViewList ||
                !sendcostViewEmpty
            ) {
                return;
            }

            let items = [];

            try {
                items = JSON.parse(
                    button.dataset.sendcostItems || '[]'
                );
            } catch (e) {
                items = [];
            }

            if (!Array.isArray(items)) {
                items = [];
            }

            const conditionName =
                String(button.dataset.conditionName || '').trim();

            if (sendcostViewTitle) {
                sendcostViewTitle.textContent =
                    conditionName
                        ? conditionName + ' · 지역 추가비'
                        : '지역 추가비';
            }

            if (sendcostViewSub) {
                sendcostViewSub.textContent =
                    '적용된 지역 추가비 ' +
                    items.length.toLocaleString('ko-KR') +
                    '건';
            }

            sendcostViewList.innerHTML = '';

            if (items.length === 0) {
                sendcostViewEmpty.style.display = 'block';
            } else {
                sendcostViewEmpty.style.display = 'none';

                items.forEach(item => {
                    const row = document.createElement('div');
                    row.className = 'sendcost-view-item';

                    const name = escapeSendcostHtml(item.name || '-');
                    const zip1 = escapeSendcostHtml(item.zip1 || '');
                    const zip2 = escapeSendcostHtml(item.zip2 || '');
                    const price = Number(item.price || 0)
                        .toLocaleString('ko-KR');

                    row.innerHTML = `
                        <div>
                            <strong>${name}</strong>
                            <small>우편번호 ${zip1} ~ ${zip2}</small>
                        </div>
                        <div class="sendcost-view-price">
                            + ${price}원
                        </div>
                    `;

                    sendcostViewList.appendChild(row);
                });
            }

            sendcostViewOverlay.classList.add('open');
            sendcostViewOverlay.setAttribute('aria-hidden', 'false');
        }

        document.querySelectorAll('.view-sendcosts').forEach(button => {
            button.addEventListener('click', () => {
                openSendcostViewModal(button);
            });
        });

        if (closeSendcostView) {
            closeSendcostView.addEventListener(
                'click',
                closeSendcostViewModal
            );
        }

        if (sendcostViewOverlay) {
            sendcostViewOverlay.addEventListener('click', event => {
                if (event.target === sendcostViewOverlay) {
                    closeSendcostViewModal();
                }
            });
        }

        document.addEventListener('keydown', event => {
            if (
                event.key === 'Escape' &&
                sendcostViewOverlay &&
                sendcostViewOverlay.classList.contains('open')
            ) {
                closeSendcostViewModal();
            }
        });

        document.querySelectorAll('.fee-type').forEach(b => b.addEventListener('click', () => setFeeType(b.dataset.fee)));

        function money(v) {
            return Number(v || 0).toLocaleString('ko-KR') + '원';
        }

        function updatePreview() {
            if (!feePreview) return;
            let t = '';
            if (activeFee === 'free') t = '항상 무료배송';
            else if (activeFee === 'paid') t = '주문 1건에 ' + money(baseFee ? baseFee.value : 0) + ' 부과';
            else if (activeFee === 'conditional') t = money(freeThreshold ? freeThreshold.value : 0) + ' 미만 ' + money(baseFee ? baseFee.value : 0) + ', 이상 무료';
            else if (activeFee === 'quantity') t = ((repeatQuantity && repeatQuantity.value) || 1) + '개마다 ' + money(baseFee ? baseFee.value : 0) + ' 반복';
            else t = '상품 주문금액 구간에 따라 배송비 계산';
            feePreview.innerHTML = '<strong>미리보기</strong><br>' + t;
        }

        ['baseFee', 'freeThreshold', 'repeatQuantity'].forEach(id => {
            const el = $(id);
            if (el) el.addEventListener('input', updatePreview);
        });

        function addRange(min = 0, max = '', fee = 0) {
            if (!amountRangeRows) return;
            const row = document.createElement('div');
            row.className = 'amount-range-row';
            row.innerHTML = `<label class="range-input"><input name="dc_range_min[]" type="number" min="0" value="${min}"><span>원</span></label><label class="range-input"><input name="dc_range_max[]" type="number" min="0" value="${max ?? ''}" placeholder="제한 없음"><span>원</span></label><label class="range-input"><input name="dc_range_price[]" type="number" min="0" value="${fee}"><span>원</span></label><button class="range-remove" type="button">×</button>`;
            row.querySelector('.range-remove').addEventListener('click', () => row.remove());
            amountRangeRows.appendChild(row);
        }

        if (addAmountRange) addAmountRange.addEventListener('click', () => addRange());

        function syncSendcostRowState() {
            document.querySelectorAll('#conditionDrawer .sendcost-row').forEach(row => {
                const checkbox = row.querySelector('.sendcost-check');
                const badge = row.querySelector('.sendcost-selected-badge');
                const selected = !!(checkbox && checkbox.checked);

                row.classList.toggle('selected', selected);

                if (badge) {
                    badge.style.display = selected ? 'inline-flex' : 'none';
                }
            });
        }

        function clearSendcostSelection() {
            document.querySelectorAll('#conditionDrawer .sendcost-check').forEach(check => {
                check.checked = false;
            });
            syncSendcostRowState();
        }

        function applySendcostSelection(ids) {
            const selected = new Set(
                (Array.isArray(ids) ? ids : []).map(v => String(v))
            );

            document.querySelectorAll('#conditionDrawer .sendcost-check').forEach(check => {
                check.checked = selected.has(String(check.value));
            });

            syncSendcostRowState();
        }

        document.querySelectorAll('#conditionDrawer .sendcost-check').forEach(check => {
            check.addEventListener('change', syncSendcostRowState);
        });

        function resetCondition() {
            if (dcId) dcId.value = '';
            if (conditionName) conditionName.value = '';
            if (baseFee) baseFee.value = 3000;
            if (freeThreshold) freeThreshold.value = 50000;
            if (repeatQuantity) repeatQuantity.value = 1;
            if (jejuUse) jejuUse.checked = false;
            if (islandUse) islandUse.checked = false;
            if (jejuPrice) jejuPrice.value = 3000;
            if (islandPrice) islandPrice.value = 5000;
            if (amountRangeRows) amountRangeRows.innerHTML = '';
            addRange(0, '', 0);
            clearSendcostSelection();
            if (drawerTitle) drawerTitle.textContent = '배송조건 추가';
            setFeeType('conditional');
        }

        if (openCreate) openCreate.addEventListener('click', () => {
            resetCondition();
            openDrawer(conditionDrawer);
        });

        document.querySelectorAll('.edit-condition').forEach(b => b.addEventListener('click', () => {
            resetCondition();
            if (dcId) dcId.value = b.dataset.id;
            if (conditionName) conditionName.value = b.dataset.name;
            if (baseFee) baseFee.value = b.dataset.price;
            if (freeThreshold) freeThreshold.value = b.dataset.minimum;
            if (repeatQuantity) repeatQuantity.value = b.dataset.qty;
            if (jejuUse) jejuUse.checked = b.dataset.jejuUse === '1';
            if (jejuPrice) jejuPrice.value = b.dataset.jejuPrice;
            if (islandUse) islandUse.checked = b.dataset.islandUse === '1';
            if (islandPrice) islandPrice.value = b.dataset.islandPrice;
            if (amountRangeRows) amountRangeRows.innerHTML = '';
            let ranges = [];
            try {
                ranges = JSON.parse(b.dataset.ranges || '[]');
            } catch (e) {}
            (ranges.length ? ranges : [{
                min: 0,
                max: null,
                fee: 0
            }]).forEach(r => addRange(r.min, r.max ?? '', r.fee));

            let sendcostIds = [];
            try {
                sendcostIds = JSON.parse(b.dataset.sendcosts || '[]');
            } catch (e) {
                sendcostIds = [];
            }

            /*
             * 수정 시 기존에 저장된 sendcostlist 선택값을 그대로 체크.
             */
            applySendcostSelection(sendcostIds);

            if (drawerTitle) drawerTitle.textContent = '배송조건 수정';
            setFeeType(b.dataset.type);
            openDrawer(conditionDrawer);
        }));


        /* 배송조건 목록의 "적용 상품 N개" 클릭 -> AJAX 상품 검색/선택 */
        function conditionProductItems() {
            return [...document.querySelectorAll('#conditionProductList .condition-product-item')];
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function syncConditionSelectedHidden() {
            if (!conditionProductSelectedHidden) return;

            conditionProductSelectedHidden.innerHTML = '';

            conditionSelectedIds.forEach(itId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'it_ids[]';
                input.value = itId;
                conditionProductSelectedHidden.appendChild(input);
            });

            if (conditionProductSelectedCount) {
                conditionProductSelectedCount.textContent = conditionSelectedIds.size;
            }
        }

        function refreshConditionProductPicker() {
            const items = conditionProductItems();
            let visible = 0;
            let visibleSelected = 0;

            items.forEach(item => {
                const checkbox = item.querySelector('input[type="checkbox"]');
                if (!checkbox) return;

                visible++;
                checkbox.checked = conditionSelectedIds.has(checkbox.value);
                item.classList.toggle('selected', checkbox.checked);

                if (checkbox.checked) {
                    visibleSelected++;
                }
            });

            if (conditionProductVisibleCount) {
                conditionProductVisibleCount.textContent = visible;
            }

            if (conditionProductSelectAll) {
                conditionProductSelectAll.checked = visible > 0 && visibleSelected === visible;
                conditionProductSelectAll.indeterminate = visibleSelected > 0 && visibleSelected < visible;
            }

            syncConditionSelectedHidden();
        }

        function renderConditionProductResults(items) {
            if (!conditionProductList || !conditionProductEmpty) return;

            conditionProductList.innerHTML = '';

            if (!Array.isArray(items) || items.length === 0) {
                conditionProductEmpty.style.display = 'block';
                conditionProductEmpty.innerHTML = '검색 결과가 없습니다.';
                if (conditionProductVisibleCount) conditionProductVisibleCount.textContent = '0';
                refreshConditionProductPicker();
                return;
            }

            conditionProductEmpty.style.display = 'none';

            items.forEach(item => {
                const itId = String(item.it_id || '');
                const currentConditionId = String(item.condition_id || '0');
                const targetConditionId = String(conditionProductConditionId ? conditionProductConditionId.value : '');

                // 현재 이 배송조건을 사용 중인 상품은 검색 결과에 나타날 때 선택 상태로 표시
                if (currentConditionId === targetConditionId) {
                    conditionSelectedIds.add(itId);
                }

                const label = document.createElement('label');
                label.className = 'product-pick condition-product-item';
                label.dataset.conditionId = currentConditionId;
                console.log(item)

                const checked = conditionSelectedIds.has(itId) ? ' checked' : '';
                const conditionName = item.dc_name ? escapeHtml(item.dc_name) : '미설정';
                const price = Number(item.it_price || 0).toLocaleString();
                label.innerHTML = `
                <input type="checkbox" value="${escapeHtml(itId)}"${checked}>
                <span class="product-thumb">${item.it_image || ''}</span>
                <span class="product-pick-copy">
                    <strong>${escapeHtml(item.it_name || '')}</strong>
                    <small>${escapeHtml(itId)} · ${price}원 · 현재 조건: ${conditionName}</small>
                </span>
                <span class="product-source ${currentConditionId === '0' ? 'ungrouped' : ''}">
                    ${conditionName}
                </span>
            `;

                conditionProductList.appendChild(label);
            });

            refreshConditionProductPicker();
        }


        /*
         * 현재 적용 상품은 별도 AJAX로 조회합니다.
         * 적용 상품 숫자와 동일한 donuts_delivery_product_settings 기준입니다.
         */
        function renderAppliedProductsAjax(items) {
            if (!conditionAppliedList || !conditionAppliedEmpty) return;

            conditionAppliedList.innerHTML = '';

            if (!Array.isArray(items) || items.length === 0) {
                conditionAppliedEmpty.style.display = 'block';
                conditionAppliedEmpty.textContent = '현재 적용된 상품이 없습니다.';
                if (conditionAppliedCount) conditionAppliedCount.textContent = '0';
                return;
            }

            conditionAppliedEmpty.style.display = 'none';
            if (conditionAppliedCount) conditionAppliedCount.textContent = String(items.length);

            items.forEach(item => {
                const row = document.createElement('div');
                row.className = 'product-pick';

                const price = Number(item.it_price || 0).toLocaleString();
                console.log(item, 'ttt');
                row.innerHTML = `
                 <span class="product-thumb">${item.it_image || ''}</span>
                <span class="product-pick-copy">
                    <strong>${escapeHtml(item.it_name || '(상품명 없음)')}</strong>
                    <small>${escapeHtml(item.it_id || '')} · ${price}원</small>
                </span>
            `;

                conditionAppliedList.appendChild(row);
            });
        }

        async function loadAppliedProductsAjax(conditionId) {
            if (!conditionAppliedList || !conditionAppliedEmpty) return;

            conditionAppliedList.innerHTML = '';
            conditionAppliedEmpty.style.display = 'block';
            conditionAppliedEmpty.textContent = '현재 적용 상품을 불러오는 중입니다.';
            if (conditionAppliedCount) conditionAppliedCount.textContent = '0';

            const params = new URLSearchParams({
                condition_id: String(conditionId || ''),
                brand_id: <?php echo json_encode($manage_brand_id, JSON_UNESCAPED_UNICODE); ?>
            });

            try {
                const response = await fetch(
                    './ajax.delivery_applied_products.php?' + params.toString(), {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    }
                );

                const raw = await response.text();
                let data;

                try {
                    data = JSON.parse(raw);
                } catch (e) {
                    console.error('적용 상품 AJAX 응답:', raw);
                    throw new Error('JSON_PARSE_ERROR');
                }

                if (!response.ok || !data.success) {
                    conditionAppliedList.innerHTML = '';
                    conditionAppliedEmpty.style.display = 'block';
                    conditionAppliedEmpty.textContent = data.message || '현재 적용 상품을 불러오지 못했습니다.';
                    if (conditionAppliedCount) conditionAppliedCount.textContent = '0';
                    return;
                }

                renderAppliedProductsAjax(data.items || []);
            } catch (e) {
                console.error(e);
                conditionAppliedList.innerHTML = '';
                conditionAppliedEmpty.style.display = 'block';
                conditionAppliedEmpty.textContent = '현재 적용 상품 조회 중 오류가 발생했습니다.';
                if (conditionAppliedCount) conditionAppliedCount.textContent = '0';
            }
        }

        async function searchConditionProducts() {
            if (!conditionProductSearch || !conditionProductConditionId) return;

            const keyword = conditionProductSearch.value.trim();
            const conditionId = conditionProductConditionId.value;
            const filterValue = conditionProductFilter ? conditionProductFilter.value : 'all';

            if (keyword === '') {
                if (conditionSearchController) {
                    conditionSearchController.abort();
                    conditionSearchController = null;
                }

                if (conditionProductList) conditionProductList.innerHTML = '';
                if (conditionProductEmpty) {
                    conditionProductEmpty.style.display = 'block';
                    conditionProductEmpty.innerHTML = '상품명 또는 상품코드를 입력하면 검색 결과가 이곳에 표시됩니다.';
                }
                if (conditionProductVisibleCount) conditionProductVisibleCount.textContent = '0';
                refreshConditionProductPicker();
                return;
            }

            if (conditionSearchController) {
                conditionSearchController.abort();
            }

            conditionSearchController = new AbortController();

            if (conditionProductEmpty) {
                conditionProductEmpty.style.display = 'block';
                conditionProductEmpty.innerHTML = '상품을 검색하고 있습니다...';
            }

            const params = new URLSearchParams({
                keyword: keyword,
                condition_id: conditionId,
                filter: filterValue,
                brand_id: <?php echo json_encode($manage_brand_id, JSON_UNESCAPED_UNICODE); ?>
            });

            try {
                const response = await fetch(
                    './ajax.delivery_product_search.php?' + params.toString(), {
                        method: 'GET',
                        credentials: 'same-origin',
                        signal: conditionSearchController.signal,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );

                const data = await response.json();

                if (!data.success) {
                    if (conditionProductList) conditionProductList.innerHTML = '';
                    if (conditionProductEmpty) {
                        conditionProductEmpty.style.display = 'block';
                        conditionProductEmpty.innerHTML = escapeHtml(data.message || '상품 검색 중 오류가 발생했습니다.');
                    }
                    if (conditionProductVisibleCount) conditionProductVisibleCount.textContent = '0';
                    return;
                }

                renderConditionProductResults(data.items || []);
            } catch (error) {
                if (error && error.name === 'AbortError') return;

                if (conditionProductList) conditionProductList.innerHTML = '';
                if (conditionProductEmpty) {
                    conditionProductEmpty.style.display = 'block';
                    conditionProductEmpty.innerHTML = '상품 검색 중 통신 오류가 발생했습니다.';
                }
                if (conditionProductVisibleCount) conditionProductVisibleCount.textContent = '0';
            }
        }

        function scheduleConditionProductSearch() {
            if (conditionSearchTimer) {
                clearTimeout(conditionSearchTimer);
            }

            conditionSearchTimer = setTimeout(searchConditionProducts, 250);
        }

        function openConditionProductPicker(button) {
            if (!conditionProductDrawer || !conditionProductConditionId) return;

            const targetId = String(button.dataset.conditionId || '');
            const targetName = button.dataset.conditionName || '배송조건';

            conditionProductConditionId.value = targetId;
            conditionSelectedIds.clear();
            syncConditionSelectedHidden();

            if (conditionProductTitle) conditionProductTitle.textContent = `‘${targetName}’ 적용 상품`;
            if (conditionProductSub) {
                conditionProductSub.textContent = '상품명 또는 상품코드를 검색한 뒤 체크하여 배송조건을 적용합니다.';
            }

            if (conditionProductSearch) conditionProductSearch.value = '';
            if (conditionProductFilter) conditionProductFilter.value = 'all';
            if (conditionProductList) conditionProductList.innerHTML = '';
            if (conditionProductEmpty) {
                conditionProductEmpty.style.display = 'block';
                conditionProductEmpty.innerHTML = '상품명 또는 상품코드를 입력하면 검색 결과가 이곳에 표시됩니다.';
            }
            if (conditionProductVisibleCount) conditionProductVisibleCount.textContent = '0';

            openDrawer(conditionProductDrawer);

            loadAppliedProductsAjax(targetId);

            setTimeout(() => {
                if (conditionProductSearch) conditionProductSearch.focus();
            }, 50);
        }

        document.querySelectorAll('.condition-products-btn').forEach(button => {
            button.addEventListener('click', () => openConditionProductPicker(button));
        });

        if (conditionProductSearch) {
            conditionProductSearch.addEventListener('input', scheduleConditionProductSearch);
            conditionProductSearch.addEventListener('keydown', event => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    if (conditionSearchTimer) clearTimeout(conditionSearchTimer);
                    searchConditionProducts();
                }
            });
        }

        if (conditionProductFilter) {
            conditionProductFilter.addEventListener('change', searchConditionProducts);
        }

        if (conditionProductList) {
            conditionProductList.addEventListener('change', event => {
                if (!event.target.matches('input[type="checkbox"]')) return;

                const itId = event.target.value;

                if (event.target.checked) {
                    conditionSelectedIds.add(itId);
                } else {
                    conditionSelectedIds.delete(itId);
                }

                refreshConditionProductPicker();
            });
        }

        if (conditionProductSelectAll) {
            conditionProductSelectAll.addEventListener('change', () => {
                conditionProductItems().forEach(item => {
                    const checkbox = item.querySelector('input[type="checkbox"]');
                    if (!checkbox) return;

                    checkbox.checked = conditionProductSelectAll.checked;

                    if (checkbox.checked) {
                        conditionSelectedIds.add(checkbox.value);
                    } else {
                        conditionSelectedIds.delete(checkbox.value);
                    }
                });

                refreshConditionProductPicker();
            });
        }

        const conditionProductForm = $('conditionProductForm');
        if (conditionProductForm) {
            conditionProductForm.addEventListener('submit', event => {
                syncConditionSelectedHidden();

                if (conditionSelectedIds.size === 0) {
                    event.preventDefault();
                    showToast('적용할 상품을 하나 이상 선택해 주세요.');
                    return false;
                }

                return true;
            });
        }

        function filterConditions() {
            if (!conditionSearch || !typeFilter) return;
            const q = conditionSearch.value.trim().toLowerCase();
            const t = typeFilter.value;
            let n = 0;
            document.querySelectorAll('#conditionRows tr').forEach(r => {
                const show = (r.dataset.name || '').toLowerCase().includes(q) && (t === 'all' || r.dataset.type === t);
                r.style.display = show ? '' : 'none';
                if (show) n++;
            });
            if (emptySearch) emptySearch.style.display = n ? 'none' : 'block';
        }

        if (conditionSearch) conditionSearch.addEventListener('input', filterConditions);
        if (typeFilter) typeFilter.addEventListener('change', filterConditions);

        if (openGroupCreate) openGroupCreate.addEventListener('click', () => openDrawer(groupDrawer));
        document.querySelectorAll('.choice-card').forEach(c => c.addEventListener('click', () => {
            c.parentNode.querySelectorAll('.choice-card').forEach(x => x.classList.remove('selected'));
            c.classList.add('selected');
            const radio = c.querySelector('input');
            if (radio) radio.checked = true;
        }));

        /* 그룹 미지정 상품 -> 배송조건/그룹 적용 */
        function openApply(ids, conditionId) {
            if (!applyIds || !applyDrawer) return;
            applyIds.innerHTML = '';
            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'it_ids[]';
                input.value = id;
                applyIds.appendChild(input);
            });
            if (conditionId && applyCondition) applyCondition.value = conditionId;
            openDrawer(applyDrawer);
        }

        document.querySelectorAll('.row-shipping-btn').forEach(b => b.addEventListener('click', e => {
            e.stopPropagation();
            const r = b.closest('.product-row');
            if (r) openApply([r.dataset.itId], r.dataset.conditionId);
        }));

        function selectedRows() {
            return [...document.querySelectorAll('#ungroupedProductList .row-check:checked')].map(x => x.value);
        }

        function refreshSelected() {
            const ids = selectedRows();
            if (openApplySecond) {
                openApplySecond.disabled = !ids.length;
                openApplySecond.textContent = '선택 상품 배송설정 (' + ids.length + ')';
            }
            const all = [...document.querySelectorAll('#ungroupedProductList .row-check')];
            if (selectAllUngrouped) {
                selectAllUngrouped.checked = all.length > 0 && ids.length === all.length;
                selectAllUngrouped.indeterminate = ids.length > 0 && ids.length < all.length;
            }
            all.forEach(c => {
                const row = c.closest('.product-row');
                if (row) row.classList.toggle('selected', c.checked);
            });
        }

        document.querySelectorAll('#ungroupedProductList .row-check').forEach(c => c.addEventListener('change', refreshSelected));

        if (selectAllUngrouped) selectAllUngrouped.addEventListener('change', () => {
            document.querySelectorAll('#ungroupedProductList .row-check').forEach(c => c.checked = selectAllUngrouped.checked);
            refreshSelected();
        });

        if (openApplySecond) openApplySecond.addEventListener('click', () => {
            const ids = selectedRows();
            if (!ids.length) {
                alert('배송비를 적용할 상품을 한 개 이상 선택해 주세요.');
                return;
            }
            openApply(ids, 0);
        });

        /* 묶음배송 그룹 -> 기존 상품 추가/이동 : AJAX 상품검색 */
        function escapeMoveHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function syncMoveSelectedInputs() {
            if (!moveSelectedProductIds) return;

            moveSelectedProductIds.innerHTML = '';

            moveSelectedIds.forEach(itId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'it_ids[]';
                input.value = itId;
                moveSelectedProductIds.appendChild(input);
            });

            if (moveSelectedCount) {
                moveSelectedCount.textContent = String(moveSelectedIds.size);
            }
        }

        function renderMoveProducts(items) {
            if (!productPickList || !emptyProductPick) return;

            productPickList.innerHTML = '';

            if (!Array.isArray(items) || items.length === 0) {
                emptyProductPick.style.display = 'block';
                emptyProductPick.textContent = '검색 결과가 없습니다.';
                if (productPickCount) productPickCount.textContent = '0';
                return;
            }

            emptyProductPick.style.display = 'none';
            if (productPickCount) productPickCount.textContent = String(items.length);

            items.forEach(item => {
                const itId = String(item.it_id || '');
                const groupId = String(item.group_id || '0');
                const groupName = item.dg_name || '그룹 미지정';
                const conditionName = item.dc_name || '배송조건 미설정';

                const label = document.createElement('label');
                label.className = 'product-pick';
                label.dataset.group = groupId;

                const checked = moveSelectedIds.has(itId);

                label.innerHTML = `
                <input type="checkbox"
                       value="${escapeMoveHtml(itId)}"
                       ${checked ? 'checked' : ''}>
                 <span class="product-thumb">${item.it_image || ''}</span>
                <span class="product-pick-copy">
                    <strong>${escapeMoveHtml(item.it_name || '')}</strong>
                    <small>
                        ${escapeMoveHtml(itId)} ·
                        ${escapeMoveHtml(conditionName)}
                    </small>
                </span>
                <span class="product-source ${groupId === '0' ? 'ungrouped' : ''}">
                    ${escapeMoveHtml(groupName)}
                </span>
            `;

                label.classList.toggle('selected', checked);
                productPickList.appendChild(label);
            });
        }


        function renderGroupAppliedProducts(items) {
            if (!groupAppliedList || !groupAppliedEmpty) return;

            groupAppliedList.innerHTML = '';

            if (!Array.isArray(items) || items.length === 0) {
                groupAppliedEmpty.style.display = 'block';
                groupAppliedEmpty.textContent = '현재 이 묶음배송 그룹에 적용된 상품이 없습니다.';
                if (groupAppliedCount) groupAppliedCount.textContent = '0';
                return;
            }

            groupAppliedEmpty.style.display = 'none';
            if (groupAppliedCount) {
                groupAppliedCount.textContent = String(items.length);
            }

            items.forEach(item => {
                const row = document.createElement('div');
                row.className = 'product-pick';

                const price = Number(item.it_price || 0).toLocaleString();

                row.dataset.itId = String(item.it_id || '');

                row.innerHTML = `
                 <span class="product-thumb">${item.it_image || ''}</span>
                <span class="product-pick-copy">
                    <strong>${escapeMoveHtml(item.it_name || '(상품명 없음)')}</strong>
                    <small>
                        ${escapeMoveHtml(item.it_id || '')} ·
                        ${price}원 ·
                        ${escapeMoveHtml(item.dc_name || '배송조건 미설정')}
                    </small>
                </span>
                <button
                    type="button"
                    class="btn btn-small group-applied-remove"
                    data-it-id="${escapeMoveHtml(item.it_id || '')}"
                >적용 해제</button>
            `;

                groupAppliedList.appendChild(row);
            });
        }

        async function loadGroupAppliedProducts(groupId) {
            if (!groupAppliedList || !groupAppliedEmpty) return;

            groupAppliedList.innerHTML = '';
            groupAppliedEmpty.style.display = 'block';
            groupAppliedEmpty.textContent = '현재 적용 상품을 불러오는 중입니다.';
            if (groupAppliedCount) groupAppliedCount.textContent = '0';

            const params = new URLSearchParams({
                group_id: String(groupId || ''),
                brand_id: <?php echo json_encode($manage_brand_id, JSON_UNESCAPED_UNICODE); ?>
            });

            try {
                const response = await fetch(
                    './ajax.delivery_group_applied_products.php?' + params.toString(), {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    }
                );

                const raw = await response.text();
                let data;

                try {
                    data = JSON.parse(raw);
                } catch (e) {
                    console.error('묶음배송 현재 적용상품 응답:', raw);
                    throw new Error('JSON_PARSE_ERROR');
                }

                if (!response.ok || !data.success) {
                    groupAppliedList.innerHTML = '';
                    groupAppliedEmpty.style.display = 'block';
                    groupAppliedEmpty.textContent =
                        data.message || '현재 적용 상품을 불러오지 못했습니다.';
                    if (groupAppliedCount) groupAppliedCount.textContent = '0';
                    return;
                }

                renderGroupAppliedProducts(data.items || []);
            } catch (e) {
                console.error(e);

                groupAppliedList.innerHTML = '';
                groupAppliedEmpty.style.display = 'block';
                groupAppliedEmpty.textContent =
                    '현재 적용 상품 조회 중 오류가 발생했습니다.';
                if (groupAppliedCount) groupAppliedCount.textContent = '0';
            }
        }

        /*
         * 묶음배송 현재 적용상품 -> 적용 해제
         *
         * 상품의 배송조건(condition_id)은 유지하고
         * group_id만 NULL로 변경합니다.
         */
        if (groupAppliedList) {
            groupAppliedList.addEventListener('click', event => {
                const button = event.target.closest('.group-applied-remove');
                if (!button) return;

                event.preventDefault();
                event.stopPropagation();

                const itId = String(button.dataset.itId || '').trim();
                const groupId = moveGroupId ? String(moveGroupId.value || '').trim() : '';

                if (!itId || !groupId) {
                    alert('해제할 상품 또는 묶음배송 그룹 정보가 없습니다.');
                    return;
                }

                if (!confirm('이 상품을 현재 묶음배송 그룹에서 해제하시겠습니까?\\n배송조건은 유지되고 묶음배송 그룹만 해제됩니다.')) {
                    return;
                }

                const form = document.createElement('form');
                form.method = 'post';
                form.action = './deliverymanage_update.php';
                form.style.display = 'none';

                const fields = {
                    token: <?php echo json_encode($admin_token, JSON_UNESCAPED_UNICODE); ?>,
                    brand_id: <?php echo json_encode($manage_brand_id, JSON_UNESCAPED_UNICODE); ?>,
                    action: 'remove_group_product',
                    group_id: groupId,
                    it_id: itId
                };

                Object.keys(fields).forEach(name => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = name;
                    input.value = fields[name];
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            });
        }

        async function searchMoveProducts() {
            if (!productPickSearch || !productSourceFilter || !moveGroupId) return;

            const keyword = productPickSearch.value.trim();
            const source = productSourceFilter.value || 'all';
            const targetGroupId = String(moveGroupId.value || '');

            if (keyword === '') {
                if (moveSearchController) {
                    moveSearchController.abort();
                    moveSearchController = null;
                }

                if (productPickList) productPickList.innerHTML = '';
                if (emptyProductPick) {
                    emptyProductPick.style.display = 'block';
                    emptyProductPick.textContent = '상품명 또는 상품코드를 입력하면 검색 결과가 표시됩니다.';
                }
                if (productPickCount) productPickCount.textContent = '0';
                return;
            }

            if (moveSearchController) {
                moveSearchController.abort();
            }

            moveSearchController = new AbortController();

            if (emptyProductPick) {
                emptyProductPick.style.display = 'block';
                emptyProductPick.textContent = '상품을 검색하고 있습니다...';
            }

            const params = new URLSearchParams({
                keyword: keyword,
                source_group: source,
                target_group_id: targetGroupId,
                brand_id: <?php echo json_encode($manage_brand_id, JSON_UNESCAPED_UNICODE); ?>
            });

            try {
                const response = await fetch(
                    './ajax.delivery_group_product_search.php?' + params.toString(), {
                        method: 'GET',
                        credentials: 'same-origin',
                        signal: moveSearchController.signal,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    }
                );

                const raw = await response.text();
                let data;

                try {
                    data = JSON.parse(raw);
                } catch (e) {
                    console.error('묶음배송 상품검색 응답:', raw);
                    throw new Error('JSON_PARSE_ERROR');
                }

                if (!response.ok || !data.success) {
                    if (productPickList) productPickList.innerHTML = '';
                    if (emptyProductPick) {
                        emptyProductPick.style.display = 'block';
                        emptyProductPick.textContent = data.message || '상품 검색 중 오류가 발생했습니다.';
                    }
                    if (productPickCount) productPickCount.textContent = '0';
                    return;
                }

                renderMoveProducts(data.items || []);
            } catch (e) {
                if (e && e.name === 'AbortError') return;

                console.error(e);

                if (productPickList) productPickList.innerHTML = '';
                if (emptyProductPick) {
                    emptyProductPick.style.display = 'block';
                    emptyProductPick.textContent = '상품 검색 중 통신 오류가 발생했습니다.';
                }
                if (productPickCount) productPickCount.textContent = '0';
            }
        }

        function scheduleMoveProductSearch() {
            if (moveSearchTimer) {
                clearTimeout(moveSearchTimer);
            }

            moveSearchTimer = setTimeout(searchMoveProducts, 250);
        }

        if (productPickList) {
            productPickList.addEventListener('change', event => {
                const checkbox = event.target.closest('input[type="checkbox"]');
                if (!checkbox) return;

                const itId = String(checkbox.value || '');
                const row = checkbox.closest('.product-pick');

                if (checkbox.checked) {
                    moveSelectedIds.add(itId);
                } else {
                    moveSelectedIds.delete(itId);
                }

                if (row) {
                    row.classList.toggle('selected', checkbox.checked);
                }

                syncMoveSelectedInputs();
            });

            productPickList.addEventListener('click', event => {
                if (event.target.matches('input[type="checkbox"]')) return;

                const row = event.target.closest('.product-pick');
                if (!row) return;

                const checkbox = row.querySelector('input[type="checkbox"]');
                if (!checkbox) return;

                event.preventDefault();
                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
            });
        }

        document.querySelectorAll('.group-products').forEach(button => {
            button.addEventListener('click', () => {
                const card = button.closest('.group-card');
                if (!card || !moveGroupId) return;

                moveGroupId.value = card.dataset.groupId || '';

                if (productGroupTitle) {
                    productGroupTitle.textContent =
                        '‘' + (card.dataset.groupName || '') + '’으로 상품 이동';
                }

                moveSelectedIds.clear();
                syncMoveSelectedInputs();

                if (productPickSearch) productPickSearch.value = '';
                if (productSourceFilter) productSourceFilter.value = 'all';
                if (productPickList) productPickList.innerHTML = '';
                if (productPickCount) productPickCount.textContent = '0';

                if (emptyProductPick) {
                    emptyProductPick.style.display = 'block';
                    emptyProductPick.textContent =
                        '상품명 또는 상품코드를 입력하면 검색 결과가 표시됩니다.';
                }

                openDrawer(productGroupDrawer);

                // 선택한 묶음배송 그룹에 현재 적용된 상품 목록 표시
                loadGroupAppliedProducts(moveGroupId.value);

                setTimeout(() => {
                    if (productPickSearch) productPickSearch.focus();
                }, 50);
            });
        });

        if (productPickSearch) {
            productPickSearch.addEventListener('input', scheduleMoveProductSearch);

            productPickSearch.addEventListener('keydown', event => {
                if (event.key === 'Enter') {
                    event.preventDefault();

                    if (moveSearchTimer) {
                        clearTimeout(moveSearchTimer);
                    }

                    searchMoveProducts();
                }
            });
        }

        if (productSourceFilter) {
            productSourceFilter.addEventListener('change', () => {
                if (productPickSearch && productPickSearch.value.trim() !== '') {
                    searchMoveProducts();
                }
            });
        }

        if (moveForm) {
            moveForm.addEventListener('submit', event => {
                syncMoveSelectedInputs();

                if (moveSelectedIds.size === 0) {
                    event.preventDefault();
                    alert('이동할 상품을 한 개 이상 선택해 주세요.');
                    return false;
                }

                return true;
            });
        }

        const applyForm = $('applyForm');
        if (applyForm) applyForm.addEventListener('submit', e => {
            if (!applyIds || !applyIds.querySelector('input[name="it_ids[]"]')) {
                e.preventDefault();
                alert('배송비를 적용할 상품을 한 개 이상 선택해 주세요.');
            }
        });

        refreshSelected();
        updatePreview();
    })();


 </script>
<?php include_once(G5_ADMIN_PATH . '/admin.tail.php'); ?>
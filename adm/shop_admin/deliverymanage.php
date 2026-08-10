<?php
$sub_menu = '400760';
include_once('./_common.php');
include_once(G5_LIB_PATH . '/donuts_delivery.lib.php');

auth_check_menu($auth, $sub_menu, 'r');
donuts_delivery_install();

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
    $row['ranges_json'] = json_encode(array_map(function($r){
        return array('min'=>(int)$r['min_amount'],'max'=>($r['max_amount']===''||$r['max_amount']===null)?null:(int)$r['max_amount'],'fee'=>(int)$r['dr_price']);
    }, $ranges), JSON_UNESCAPED_UNICODE);
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

$ungrouped = array_values(array_filter($products, function($p){ return empty($p['group_id']); }));
$applied_count = count($products);
$default_count = 0;
foreach ($conditions as $c) if (!empty($c['is_default'])) $default_count++;

function delivery_type_label($type) {
    $map = array('paid'=>'유료','conditional'=>'조건부 무료','free'=>'무료','quantity'=>'수량별','amount_range'=>'금액 구간별');
    return isset($map[$type]) ? $map[$type] : $type;
}
function delivery_condition_main($row) {
    if ($row['dc_type'] === 'free') return '0원';
    if ($row['dc_type'] === 'amount_range') {
        $r = donuts_delivery_condition_ranges($row['dc_id']);
        return '구간 '.count($r).'개';
    }
    return number_format((int)$row['dc_price']).'원';
}
function delivery_condition_sub($row) {
    if ($row['dc_type'] === 'conditional') return number_format((int)$row['dc_minimum']).'원 이상 무료';
    if ($row['dc_type'] === 'quantity') return max(1,(int)$row['dc_qty']).'개마다 반복';
    if ($row['dc_type'] === 'paid') return '주문 1건당 부과';
    if ($row['dc_type'] === 'amount_range') {
        $r = donuts_delivery_condition_ranges($row['dc_id']);
        if ($r) {
            $last = end($r);
            return number_format((int)$last['min_amount']).'원 이상 '.((int)$last['dr_price']===0?'무료':number_format((int)$last['dr_price']).'원');
        }
    }
    return '';
}
function delivery_product_fee_text($p) {
    if ($p['dc_type']==='free') return '무료';
    if ($p['dc_type']==='conditional') return number_format((int)$p['dc_price']).'원 / '.number_format((int)$p['dc_minimum']).'원 이상 무료';
    if ($p['dc_type']==='quantity') return number_format((int)$p['dc_price']).'원 / '.max(1,(int)$p['dc_qty']).'개마다';
    if ($p['dc_type']==='amount_range') return '금액 구간별';
    return number_format((int)$p['dc_price']).'원';
}

$g5['title'] = '배송관리';
include_once(G5_ADMIN_PATH . '/admin.head.php');
$admin_token = get_admin_token();
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'conditions';
?>
<style>
    :root {
        --bg: #f5f6f8;
        --surface: #ffffff;
        --surface-2: #fafafa;
        --line: #e7e9ed;
        --line-strong: #d8dce2;
        --text: #1c2028;
        --muted: #717782;
        --muted-2: #9aa0aa;
        --brand: #ff6a3d;
        --brand-dark: #e84d21;
        --brand-soft: #fff1eb;
        --green: #178a5b;
        --green-soft: #eaf8f1;
        --blue: #4169e1;
        --blue-soft: #eef2ff;
        --yellow: #9b6600;
        --yellow-soft: #fff8df;
        --red: #dc3f4b;
        --shadow: 0 12px 32px rgba(24, 29, 39, .08);
        --radius: 14px;
    }

    * {
        box-sizing: border-box;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        margin: 0;
        min-width: 320px;
        color: var(--text);
        background: var(--bg);
        font-family: Pretendard, "Noto Sans KR", "Apple SD Gothic Neo", Arial, sans-serif;
        font-size: 14px;
        line-height: 1.55;
        word-break: keep-all;
    }

    button,
    input,
    select {
        font: inherit;
    }

    button {
        cursor: pointer;
    }

    a {
        color: inherit;
        text-decoration: none;
    }

    .app-shell {
        min-height: 100vh;
    }

    .subnav {
        margin: 4px 0 14px 21px;
        padding-left: 19px;
        border-left: 1px solid var(--line);
    }

    .subnav a {
        display: block;
        padding: 7px 0;
        color: var(--muted);
        font-size: 13px;
    }

    .subnav a.active {
        color: var(--brand-dark);
        font-weight: 750;
    }

    /* .main {
      margin-left: 232px;
      padding: 104px 32px 64px;
    } */

    /* .content {
        width: min(1180px, 100%);
        margin: 0 auto;
    } */

    .page-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 16px;
    }

    /* h1,
    h2,
    h3,
    p {
        margin-top: 0;
    } */

    /* h1 {
        margin-bottom: 7px;
        font-size: 27px;
        line-height: 1.25;
        letter-spacing: -.04em;
    } */

    .page-head p {
        margin-bottom: 0;
        color: var(--muted);
    }

    .btn {
        min-height: 40px;
        padding: 0 15px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        border: 1px solid var(--line-strong);
        border-radius: 9px;
        color: #343943;
        background: #fff;
        font-weight: 720;
        white-space: nowrap;
        transition: .18s ease;
    }

    .btn:hover {
        border-color: #bcc1c9;
        background: #fafafa;
    }

    .btn:focus-visible,
    .field:focus-visible,
    select:focus-visible {
        outline: 3px solid rgba(255, 106, 61, .16);
        outline-offset: 1px;
        border-color: var(--brand);
    }

    .btn-primary {
        color: #fff;
        border-color: #252932;
        background: #252932;
    }

    .btn-primary:hover {
        color: #fff;
        border-color: #11141a;
        background: #11141a;
        transform: translateY(-1px);
    }

    .btn-brand {
        color: #fff;
        border-color: var(--brand);
        background: var(--brand);
    }

    .btn-brand:hover {
        color: #fff;
        border-color: var(--brand-dark);
        background: var(--brand-dark);
    }

    .btn-ghost {
        border-color: transparent;
        background: transparent;
    }

    .btn-small {
        min-height: 32px;
        padding: 0 10px;
        border-radius: 7px;
        font-size: 12px;
    }

    .btn-icon {
        width: 40px;
        padding: 0;
    }

    .plus {
        font-size: 18px;
        font-weight: 500;
        line-height: 1;
    }

    .tabs {
        display: flex;
        align-items: center;
        gap: 28px;
        margin-bottom: 22px;
        border-bottom: 1px solid var(--line);
    }

    .tab {
        position: relative;
        padding: 0 1px 13px;
        border: 0;
        color: var(--muted);
        background: transparent;
        font-weight: 720;
    }

    .tab span {
        margin-left: 4px;
        color: var(--muted-2);
        font-size: 12px;
    }

    .tab.active {
        color: var(--text);
    }

    .tab.active::after {
        content: "";
        position: absolute;
        height: 2px;
        inset: auto 0 -1px;
        border-radius: 3px;
        background: var(--brand);
    }

    .panel {
        display: none;
    }

    .panel.active {
        display: block;
    }

    .guide {
        margin-bottom: 18px;
        padding: 21px 22px;
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 28px;
        border: 1px solid #f2d8cd;
        border-radius: var(--radius);
        background: linear-gradient(110deg, #fff9f6, #fff);
    }

    .guide h2 {
        margin-bottom: 6px;
        font-size: 16px;
        letter-spacing: -.025em;
    }

    .guide p {
        margin-bottom: 0;
        color: var(--muted);
        font-size: 13px;
    }

    .flow {
        display: grid;
        grid-template-columns: 1fr 32px 1fr 32px 1fr;
        align-items: center;
        gap: 7px;
    }

    .flow-step {
        min-height: 64px;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        border: 1px solid var(--line);
        border-radius: 10px;
        background: #fff;
    }

    .step-no {
        width: 26px;
        height: 26px;
        display: grid;
        place-items: center;
        flex: none;
        border-radius: 50%;
        color: var(--brand-dark);
        background: var(--brand-soft);
        font-size: 12px;
        font-weight: 850;
    }

    .flow-step strong {
        display: block;
        font-size: 13px;
    }

    .flow-step small {
        display: block;
        margin-top: 2px;
        color: var(--muted);
        font-size: 11px;
    }

    .flow-arrow {
        color: #bbc0c8;
        text-align: center;
        font-size: 20px;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 18px;
    }

    .summary-card {
        padding: 18px 19px;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: var(--surface);
    }

    .summary-card .label {
        color: var(--muted);
        font-size: 12px;
        font-weight: 650;
    }

    .summary-card .value {
        margin: 5px 0 1px;
        font-size: 23px;
        font-weight: 820;
        letter-spacing: -.04em;
    }

    .summary-card .sub {
        color: var(--muted-2);
        font-size: 11px;
    }

    .card {
        border: 1px solid var(--line);
        border-radius: var(--radius);
        background: var(--surface);
    }

    .card-head {
        min-height: 68px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        border-bottom: 1px solid var(--line);
    }

    .card-head h2 {
        margin-bottom: 2px;
        font-size: 16px;
        letter-spacing: -.02em;
    }

    .card-head p {
        margin-bottom: 0;
        color: var(--muted);
        font-size: 12px;
    }

    .filters {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .search-wrap {
        position: relative;
    }

    .search-wrap::before {
        content: "⌕";
        position: absolute;
        left: 12px;
        top: 50%;
        color: var(--muted);
        transform: translateY(-52%) rotate(-15deg);
        font-size: 17px;
    }

    .field,
    .select {
        height: 38px;
        padding: 0 12px;
        border: 1px solid var(--line-strong);
        border-radius: 8px;
        color: var(--text);
        background: #fff;
    }

    .search-field {
        width: 205px;
        padding-left: 34px;
    }

    .select {
        padding-right: 30px;
    }

    .table-wrap {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 880px;
    }

    th,
    td {
        padding: 15px 16px;
        border-bottom: 1px solid #eef0f2;
        text-align: left;
        vertical-align: middle;
    }

    th {
        color: var(--muted);
        background: #fbfbfc;
        font-size: 11px;
        font-weight: 760;
    }

    tbody tr:last-child td {
        border-bottom: 0;
    }

    tbody tr:hover td {
        background: #fdfdfd;
    }

    .condition-name {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .condition-name strong {
        font-size: 13px;
    }

    .condition-desc {
        display: block;
        margin-top: 3px;
        color: var(--muted);
        font-size: 11px;
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

    .badge-bundle {
        color: var(--green);
        background: var(--green-soft);
    }

    .badge-individual {
        color: var(--blue);
        background: var(--blue-soft);
    }

    .badge-warning {
        color: var(--yellow);
        background: var(--yellow-soft);
    }

    .badge-off {
        color: #777;
        background: #efefef;
    }

    .usage {
        color: var(--text);
        border: 0;
        border-bottom: 1px solid #aeb4bd;
        background: none;
        font-weight: 750;
    }

    .row-actions {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .text-btn {
        padding: 4px 6px;
        border: 0;
        color: #59606a;
        background: transparent;
        font-size: 12px;
        font-weight: 700;
    }

    .text-btn:hover {
        color: var(--brand-dark);
    }

    .kebab {
        color: #8c929b;
        font-size: 18px;
        letter-spacing: 1px;
    }

    .btn:disabled {
        color: #a4a9b1;
        border-color: #e1e3e6;
        background: #f2f3f4;
        cursor: not-allowed;
    }

    .table-foot {
        min-height: 52px;
        padding: 0 17px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid var(--line);
        color: var(--muted);
        font-size: 12px;
    }

    .pagination {
        display: flex;
        gap: 4px;
    }

    .page-btn {
        width: 30px;
        height: 30px;
        border: 1px solid transparent;
        border-radius: 7px;
        color: var(--muted);
        background: transparent;
    }

    .page-btn.active {
        color: var(--text);
        border-color: var(--line);
        background: #fff;
        font-weight: 750;
    }

    .rule-note {
        margin-top: 14px;
        padding: 13px 15px;
        display: flex;
        align-items: flex-start;
        gap: 9px;
        border: 1px solid #f0e2b0;
        border-radius: 10px;
        color: #69531a;
        background: var(--yellow-soft);
        font-size: 12px;
    }

    .info-dot {
        width: 18px;
        height: 18px;
        display: grid;
        place-items: center;
        flex: none;
        border: 1px solid currentColor;
        border-radius: 50%;
        font-size: 11px;
        font-weight: 800;
    }

    .empty-search {
        display: none;
        padding: 48px 20px;
        color: var(--muted);
        text-align: center;
    }

    .group-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }

    .group-card {
        padding: 19px;
        border: 1px solid var(--line);
        border-radius: 12px;
        background: #fff;
    }

    .group-top {
        display: flex;
        justify-content: space-between;
        gap: 14px;
    }

    .group-card h3 {
        margin-bottom: 5px;
        font-size: 15px;
    }

    .group-card p {
        margin-bottom: 15px;
        color: var(--muted);
        font-size: 12px;
    }

    .group-meta {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        padding-top: 14px;
        border-top: 1px solid var(--line);
    }

    .group-meta span {
        display: block;
        color: var(--muted-2);
        font-size: 10px;
    }

    .group-meta strong {
        display: block;
        margin-top: 2px;
        font-size: 12px;
    }

    .product-list-toolbar {
        margin-bottom: 10px;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        border-radius: 9px;
        background: #f6f7f8;
        color: var(--muted);
        font-size: 11px;
    }

    .product-list-toolbar label {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #535a64;
        font-weight: 750;
        cursor: pointer;
    }

    .product-list {
        display: grid;
        gap: 8px;
    }

    .product-row {
        padding: 13px 14px;
        display: flex;
        align-items: center;
        gap: 13px;
        border: 1px solid var(--line);
        border-radius: 10px;
        background: #fff;
        cursor: pointer;
        transition: border-color .16s ease, box-shadow .16s ease, background .16s ease;
    }

    .product-row:hover {
        border-color: #c7cbd1;
        box-shadow: 0 4px 12px rgba(28, 33, 41, .05);
    }

    .product-row:focus-visible {
        outline: 2px solid var(--brand);
        outline-offset: 2px;
    }

    .product-row.selected {
        border-color: #f0a082;
        background: #fffaf8;
    }

    .row-check {
        width: 17px;
        height: 17px;
        flex: none;
        accent-color: var(--brand);
        cursor: pointer;
    }

    .product-info {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        min-width: 0;
    }

    .product-thumb {
        width: 44px;
        height: 44px;
        display: grid;
        place-items: center;
        border-radius: 9px;
        color: #a55b35;
        background: #f7e3d5;
        font-size: 19px;
    }

    .product-info strong {
        display: block;
        font-size: 13px;
    }

    .product-info small {
        display: block;
        margin-top: 2px;
        color: var(--muted);
    }

    .product-actions {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .product-fee {
        min-width: 82px;
        text-align: right;
    }

    .product-fee strong {
        display: block;
        font-size: 13px;
    }

    .product-fee small {
        color: var(--muted);
    }

    .empty-products {
        display: none;
        padding: 42px 16px;
        border: 1px dashed var(--line-strong);
        border-radius: 10px;
        color: var(--muted);
        text-align: center;
        font-size: 12px;
    }

    .overlay {
        position: fixed;
        z-index: 1000;
        inset: 0;
        visibility: hidden;
        opacity: 0;
        background: rgba(20, 24, 31, .42);
        transition: .2s ease;
    }

    .overlay.open {
        visibility: visible;
        opacity: 1;
    }

    .drawer {
        position: fixed;
        z-index: 1001;
        inset: 0 0 0 auto;
        width: min(560px, 100%);
        display: flex;
        flex-direction: column;
        background: #fff;
        /* box-shadow: -18px 0 50px rgba(20, 25, 33, .14); */
        transform: translateX(102%);
        transition: transform .24s ease;
    }

    .drawer.open {
        transform: translateX(0);
    }

    .drawer-head {
        min-height: 72px;
        padding: 0 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        border-bottom: 1px solid var(--line);
    }

    .drawer-head h2 {
        margin-bottom: 2px;
        font-size: 19px;
        letter-spacing: -.03em;
    }

    .drawer-head p {
        margin-bottom: 0;
        color: var(--muted);
        font-size: 11px;
    }

    .close-btn {
        width: 36px;
        height: 36px;
        border: 0;
        border-radius: 8px;
        color: #555c66;
        background: #f5f6f7;
        font-size: 20px;
    }

    .drawer-body {
        flex: 1;
        padding: 22px 24px 34px;
        overflow-y: auto;
    }

    .drawer-foot {
        min-height: 72px;
        padding: 14px 24px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        border-top: 1px solid var(--line);
        background: #fff;
    }

    .form-section {
        margin-bottom: 24px;
    }

    .section-title {
        margin-bottom: 11px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
        font-weight: 800;
    }

    .required {
        color: var(--red);
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        color: #505762;
        font-size: 12px;
        font-weight: 720;
    }

    .form-control {
        width: 100%;
        height: 44px;
        padding: 0 13px;
        border: 1px solid var(--line-strong);
        border-radius: 9px;
        color: var(--text);
        background: #fff;
    }

    .form-help {
        margin: 6px 0 0;
        color: var(--muted);
        font-size: 11px;
    }

    .choice-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 9px;
    }

    .choice-card {
        position: relative;
        padding: 14px;
        border: 1px solid var(--line-strong);
        border-radius: 10px;
        background: #fff;
        cursor: pointer;
    }

    .choice-card input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .choice-card.selected {
        border-color: var(--brand);
        box-shadow: 0 0 0 2px rgba(255, 106, 61, .1);
        background: #fffaf8;
    }

    .choice-card strong {
        display: block;
        margin-bottom: 3px;
        font-size: 13px;
    }

    .choice-card small {
        display: block;
        color: var(--muted);
        font-size: 11px;
    }

    .radio-mark {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 17px;
        height: 17px;
        border: 1.5px solid #bdc2c9;
        border-radius: 50%;
    }

    .choice-card.selected .radio-mark {
        border: 5px solid var(--brand);
    }

    .fee-types {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 6px;
    }

    .fee-type {
        height: 38px;
        border: 1px solid var(--line-strong);
        border-radius: 8px;
        color: #5a606a;
        background: #fff;
        font-size: 12px;
        font-weight: 700;
    }

    .fee-type.active {
        color: var(--brand-dark);
        border-color: var(--brand);
        background: var(--brand-soft);
    }

    .two-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .input-unit {
        position: relative;
    }

    .input-unit .form-control {
        padding-right: 37px;
    }

    .unit {
        position: absolute;
        right: 13px;
        bottom: 12px;
        color: var(--muted);
        font-size: 12px;
    }

    .amount-range-editor {
        padding: 15px;
        border: 1px solid var(--line);
        border-radius: 11px;
        background: #fafbfc;
    }

    .amount-range-head,
    .amount-range-row {
        display: grid;
        grid-template-columns: 1fr 1fr .86fr 34px;
        gap: 7px;
        align-items: center;
    }

    .amount-range-head {
        margin-bottom: 7px;
        padding: 0 2px;
        color: var(--muted);
        font-size: 10px;
        font-weight: 750;
    }

    .amount-range-row+.amount-range-row {
        margin-top: 8px;
    }

    .range-input {
        position: relative;
    }

    .range-input input {
        width: 100%;
        height: 40px;
        padding: 0 28px 0 9px;
        border: 1px solid var(--line-strong);
        border-radius: 8px;
        background: #fff;
        font-size: 12px;
    }

    .range-input span {
        position: absolute;
        right: 9px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        font-size: 10px;
    }

    .range-remove {
        width: 34px;
        height: 34px;
        border: 1px solid var(--line);
        border-radius: 8px;
        color: #7a8089;
        background: #fff;
        font-size: 17px;
    }

    .range-add {
        width: 100%;
        height: 38px;
        margin-top: 10px;
        border: 1px dashed var(--line-strong);
        border-radius: 8px;
        color: #555d68;
        background: #fff;
        font-size: 11px;
        font-weight: 750;
    }

    .range-help {
        margin: 9px 1px 0;
        color: var(--muted);
        font-size: 10px;
        line-height: 1.55;
    }

    .toggle-row {
        min-height: 48px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        border-top: 1px solid var(--line);
    }

    .toggle-row:first-of-type {
        border-top: 0;
    }

    .toggle-row strong {
        display: block;
        font-size: 12px;
    }

    .toggle-row small {
        color: var(--muted);
        font-size: 10px;
    }

    .switch {
        position: relative;
        width: 40px;
        height: 23px;
        flex: none;
        border: 0;
        border-radius: 999px;
        background: #cbd0d6;
    }

    .switch::after {
        content: "";
        position: absolute;
        top: 3px;
        left: 3px;
        width: 17px;
        height: 17px;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .18);
        transition: .18s ease;
    }

    .switch.on {
        background: var(--brand);
    }

    .switch.on::after {
        left: 20px;
    }

    .fee-preview {
        padding: 14px;
        border-radius: 10px;
        color: #474d56;
        background: #f5f6f8;
        font-size: 12px;
    }

    .fee-preview strong {
        color: var(--text);
    }

    .apply-product {
        margin-bottom: 18px;
        padding: 15px;
        display: flex;
        align-items: center;
        gap: 13px;
        border: 1px solid var(--line);
        border-radius: 11px;
        background: #fbfbfc;
    }

    .apply-product .product-thumb {
        width: 50px;
        height: 50px;
    }

    .apply-product strong {
        display: block;
    }

    .apply-product small {
        color: var(--muted);
    }

    .select-block {
        margin-bottom: 18px;
    }

    .select-block select {
        width: 100%;
        height: 46px;
        padding: 0 12px;
        border: 1px solid var(--line-strong);
        border-radius: 9px;
        background: #fff;
    }

    .select-block select:disabled {
        color: #a0a5ad;
        background: #f1f2f4;
        cursor: not-allowed;
    }

    .branch-box {
        margin-top: 8px;
        padding: 13px 14px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        border: 1px solid #cfe8db;
        border-radius: 10px;
        color: #286549;
        background: #f0faf5;
        font-size: 12px;
    }

    .branch-box.individual {
        color: #415f9b;
        border-color: #d8e0fa;
        background: #f2f5ff;
    }

    .apply-summary {
        padding: 16px;
        border: 1px solid var(--line);
        border-radius: 11px;
    }

    .apply-summary h3 {
        margin-bottom: 12px;
        font-size: 13px;
    }

    .summary-line {
        padding: 6px 0;
        display: flex;
        justify-content: space-between;
        gap: 20px;
        color: var(--muted);
        font-size: 12px;
    }

    .summary-line strong {
        color: var(--text);
        text-align: right;
    }

    .product-pick-list {
        display: grid;
        gap: 8px;
    }

    .product-pick {
        min-height: 66px;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        gap: 11px;
        border: 1px solid var(--line);
        border-radius: 10px;
        background: #fff;
        cursor: pointer;
    }

    .product-pick:hover {
        border-color: #c9cdd3;
        background: #fdfdfd;
    }

    .product-pick input {
        width: 17px;
        height: 17px;
        accent-color: var(--brand);
    }

    .product-pick .product-thumb {
        width: 40px;
        height: 40px;
    }

    .product-pick-copy {
        flex: 1;
        min-width: 0;
    }

    .product-pick strong {
        display: block;
        font-size: 12px;
    }

    .product-pick small {
        display: block;
        margin-top: 2px;
        color: var(--muted);
        font-size: 10px;
    }

    .product-source {
        flex: none;
        padding: 4px 7px;
        border-radius: 999px;
        color: #4f5d73;
        background: #eef2f7;
        font-size: 9px;
        font-weight: 800;
    }

    .product-source.ungrouped {
        color: #6c551a;
        background: var(--yellow-soft);
    }

    .picker-summary {
        margin: -5px 0 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        color: var(--muted);
        font-size: 10px;
    }

    .picker-summary strong {
        color: var(--text);
    }

    .empty-picker {
        display: none;
        padding: 34px 14px;
        border: 1px dashed var(--line-strong);
        border-radius: 10px;
        color: var(--muted);
        text-align: center;
        font-size: 11px;
    }

    .method-explain {
        margin-top: 10px;
        padding: 12px 13px;
        border: 1px solid var(--line);
        border-radius: 9px;
        color: var(--muted);
        background: #fafafa;
        font-size: 11px;
    }

    .register-flow-map {
        margin-bottom: 18px;
        padding: 20px;
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 8px;
        border: 1px solid var(--line);
        border-radius: var(--radius);
        background: #fff;
    }

    .register-flow-node {
        position: relative;
        min-height: 90px;
        padding: 14px;
        border: 1px solid var(--line);
        border-radius: 11px;
        background: #fbfbfc;
    }

    .register-flow-node:not(:last-child)::after {
        content: "›";
        position: absolute;
        z-index: 2;
        top: 50%;
        right: -14px;
        width: 20px;
        height: 20px;
        display: grid;
        place-items: center;
        border: 1px solid var(--line);
        border-radius: 50%;
        color: #a5abb4;
        background: #fff;
        transform: translateY(-50%);
    }

    .register-flow-node strong {
        display: block;
        margin: 7px 0 3px;
        font-size: 12px;
    }

    .register-flow-node small {
        display: block;
        color: var(--muted);
        font-size: 10px;
        line-height: 1.45;
    }

    .flow-kicker {
        color: var(--brand-dark);
        font-size: 10px;
        font-weight: 850;
    }

    .register-flow-node.optional {
        border-style: dashed;
        background: #fffdf8;
    }

    .register-flow-node.result {
        border-color: #cfe8db;
        background: #f3fbf7;
    }

    .register-shell {
        display: grid;
        grid-template-columns: 218px minmax(0, 1fr);
        min-height: 620px;
        border: 1px solid var(--line);
        border-radius: var(--radius);
        background: #fff;
        overflow: hidden;
    }

    .register-sidebar {
        padding: 25px 20px;
        border-right: 1px solid var(--line);
        background: #fafafa;
    }

    .register-sidebar h2 {
        margin-bottom: 5px;
        font-size: 16px;
    }

    .register-sidebar>p {
        margin-bottom: 24px;
        color: var(--muted);
        font-size: 11px;
    }

    .register-steps {
        display: grid;
        gap: 0;
    }

    .register-step {
        position: relative;
        min-height: 64px;
        padding-left: 40px;
        color: var(--muted-2);
    }

    .register-step:not(:last-child)::after {
        content: "";
        position: absolute;
        top: 31px;
        bottom: -1px;
        left: 14px;
        width: 1px;
        background: var(--line-strong);
    }

    .register-step .step-circle {
        position: absolute;
        z-index: 1;
        top: 0;
        left: 0;
        width: 29px;
        height: 29px;
        display: grid;
        place-items: center;
        border: 1px solid var(--line-strong);
        border-radius: 50%;
        color: var(--muted);
        background: #fff;
        font-size: 11px;
        font-weight: 800;
    }

    .register-step strong {
        display: block;
        padding-top: 2px;
        color: inherit;
        font-size: 12px;
    }

    .register-step small {
        display: block;
        margin-top: 2px;
        color: inherit;
        font-size: 10px;
    }

    .register-step.active {
        color: var(--text);
    }

    .register-step.active .step-circle {
        color: #fff;
        border-color: var(--brand);
        background: var(--brand);
    }

    .register-step.done {
        color: #5e6670;
    }

    .register-step.done .step-circle {
        color: var(--green);
        border-color: #b8decb;
        background: var(--green-soft);
    }

    .register-step.done:not(:last-child)::after {
        background: #b8decb;
    }

    .register-main {
        min-width: 0;
        display: flex;
        flex-direction: column;
    }

    .register-screen {
        display: none;
        flex: 1;
        padding: 28px 30px 22px;
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

    .register-form-grid {
        display: grid;
        grid-template-columns: 160px minmax(0, 1fr);
        gap: 22px;
    }

    .upload-box {
        width: 160px;
        height: 160px;
        display: grid;
        place-items: center;
        border: 1px dashed #c6cbd2;
        border-radius: 11px;
        color: var(--muted);
        background: #fafafa;
        text-align: center;
        font-size: 11px;
        cursor: pointer;
    }

    .upload-plus {
        display: block;
        margin-bottom: 6px;
        color: #9ba1aa;
        font-size: 25px;
        line-height: 1;
    }

    .form-stack {
        display: grid;
        gap: 16px;
    }

    .form-row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .condition-select-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
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

    .register-condition strong {
        display: block;
        margin-bottom: 4px;
        font-size: 13px;
    }

    .register-condition small {
        display: block;
        color: var(--muted);
        font-size: 11px;
    }

    .register-condition .radio-mark {
        top: 14px;
        right: 14px;
    }

    .independent-box {
        margin-top: 18px;
        padding: 16px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        border: 1px solid #dce2f6;
        border-radius: 11px;
        background: #f6f8ff;
    }

    .independent-part {
        padding-right: 10px;
    }

    .independent-part+.independent-part {
        padding-left: 14px;
        padding-right: 0;
        border-left: 1px solid #dce2f6;
    }

    .independent-part span {
        display: block;
        color: #7380a1;
        font-size: 10px;
        font-weight: 750;
    }

    .independent-part strong {
        display: block;
        margin: 3px 0;
        font-size: 12px;
    }

    .independent-part small {
        color: #6f7890;
        font-size: 10px;
    }

    .group-select-box {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--line);
    }

    .group-select-head {
        margin-bottom: 10px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
    }

    .group-select-head strong {
        display: block;
        font-size: 13px;
    }

    .group-select-head small {
        color: var(--muted);
        font-size: 10px;
    }

    .calc-result {
        margin-top: 10px;
        padding: 13px 14px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        border: 1px solid #f0e2b0;
        border-radius: 9px;
        color: #69531a;
        background: var(--yellow-soft);
        font-size: 11px;
    }

    .calc-result.grouped {
        color: #286549;
        border-color: #cfe8db;
        background: #f0faf5;
    }

    .confirm-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .confirm-card {
        padding: 17px;
        border: 1px solid var(--line);
        border-radius: 11px;
        background: #fff;
    }

    .confirm-card h3 {
        margin-bottom: 12px;
        font-size: 13px;
    }

    .confirm-line {
        min-height: 30px;
        display: flex;
        justify-content: space-between;
        gap: 18px;
        color: var(--muted);
        font-size: 11px;
    }

    .confirm-line strong {
        color: var(--text);
        text-align: right;
        font-size: 11px;
    }

    .register-foot {
        min-height: 70px;
        padding: 13px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        border-top: 1px solid var(--line);
        background: #fff;
    }

    .step-hint {
        color: var(--muted);
        font-size: 11px;
    }

    .register-actions {
        display: flex;
        gap: 8px;
    }

    .completion {
        min-height: 420px;
        display: grid;
        place-items: center;
        text-align: center;
    }

    .complete-icon {
        width: 58px;
        height: 58px;
        margin: 0 auto 15px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        color: var(--green);
        background: var(--green-soft);
        font-size: 24px;
        font-weight: 900;
    }

    .completion h2 {
        margin-bottom: 7px;
        font-size: 21px;
    }

    .completion p {
        margin-bottom: 18px;
        color: var(--muted);
        font-size: 12px;
    }

    .complete-number {
        margin-bottom: 20px;
        padding: 9px 13px;
        display: inline-block;
        border-radius: 8px;
        color: #59606a;
        background: #f4f5f6;
        font-size: 11px;
    }

    .toast {
        position: fixed;
        z-index: 100;
        left: 50%;
        bottom: 28px;
        min-width: 280px;
        max-width: calc(100% - 32px);
        padding: 13px 16px;
        display: flex;
        align-items: center;
        gap: 9px;
        border-radius: 10px;
        color: #fff;
        background: #252932;
        box-shadow: var(--shadow);
        opacity: 0;
        pointer-events: none;
        transform: translate(-50%, 12px);
        transition: .22s ease;
    }

    .toast.show {
        opacity: 1;
        transform: translate(-50%, 0);
    }

    .toast-check {
        width: 18px;
        height: 18px;
        display: grid;
        place-items: center;
        border-radius: 50%;
        color: #252932;
        background: #fff;
        font-size: 11px;
        font-weight: 900;
    }

    @media (max-width: 980px) {
        .sidebar {
            width: 190px;
        }

        .main {
            margin-left: 190px;
            padding-inline: 20px;
        }

        .guide {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .filters {
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .search-field {
            width: 180px;
        }

        .register-flow-map {
            grid-template-columns: 1fr 1fr;
        }

        .register-flow-node:not(:last-child)::after {
            display: none;
        }
    }

    @media (max-width: 760px) {
        .sidebar {
            display: none;
        }

        .main {
            margin-left: 0;
            padding: 82px 14px 46px;
        }

        .page-head {
            align-items: flex-start;
        }

        .page-head .btn {
            min-width: 40px;
            padding-inline: 11px;
        }

        .page-head .btn-label {
            display: none;
        }

        h1 {
            font-size: 23px;
        }

        .tabs {
            gap: 20px;
            overflow-x: auto;
        }

        .tab {
            flex: none;
        }

        .summary-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .summary-card {
            padding: 14px 16px;
        }

        .summary-card .value {
            font-size: 20px;
        }

        .flow {
            grid-template-columns: 1fr;
        }

        .flow-arrow {
            transform: rotate(90deg);
        }

        .card-head {
            align-items: flex-start;
            flex-direction: column;
        }

        .filters {
            width: 100%;
            justify-content: stretch;
        }

        .search-wrap {
            flex: 1;
        }

        .search-field {
            width: 100%;
        }

        .group-grid {
            grid-template-columns: 1fr;
        }

        .register-shell {
            grid-template-columns: 1fr;
        }

        .register-sidebar {
            border-right: 0;
            border-bottom: 1px solid var(--line);
        }

        .register-sidebar>p {
            margin-bottom: 15px;
        }

        .register-steps {
            grid-template-columns: repeat(4, 1fr);
        }

        .register-step {
            min-height: 50px;
            padding: 36px 4px 0;
            text-align: center;
        }

        .register-step .step-circle {
            left: 50%;
            transform: translateX(-50%);
        }

        .register-step:not(:last-child)::after {
            top: 14px;
            right: -50%;
            bottom: auto;
            left: 50%;
            width: 100%;
            height: 1px;
        }

        .register-step small {
            display: none;
        }

        .register-screen {
            padding: 22px 18px;
        }

        .register-foot {
            padding-inline: 18px;
        }

        .condition-select-grid,
        .confirm-grid {
            grid-template-columns: 1fr;
        }

        .product-row {
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .product-info {
            min-width: calc(100% - 34px);
        }

        .product-actions {
            width: 100%;
            padding-left: 30px;
            justify-content: space-between;
        }

        .product-fee {
            min-width: 80px;
        }

        .fee-types {
            grid-template-columns: repeat(2, 1fr);
        }

        .drawer-body {
            padding-inline: 18px;
        }

        .drawer-head,
        .drawer-foot {
            padding-inline: 18px;
        }
    }

    @media (max-width: 430px) {
        .guide {
            padding: 17px;
        }

        .choice-grid,
        .two-col {
            grid-template-columns: 1fr;
        }

        .register-flow-map {
            grid-template-columns: 1fr;
        }

        .register-form-grid {
            grid-template-columns: 1fr;
        }

        .upload-box {
            width: 100%;
            height: 110px;
        }

        .form-row-2,
        .independent-box {
            grid-template-columns: 1fr;
        }

        .independent-part+.independent-part {
            padding: 12px 0 0;
            border-left: 0;
            border-top: 1px solid #dce2f6;
        }

        .product-list-toolbar {
            align-items: flex-start;
            flex-direction: column;
        }

        .amount-range-head {
            display: none;
        }

        .amount-range-row {
            grid-template-columns: 1fr 1fr 34px;
        }

        .amount-range-row .range-input:nth-child(3) {
            grid-column: 1 / 3;
        }

        .amount-range-row .range-remove {
            grid-column: 3;
            grid-row: 1;
        }

        .step-hint {
            display: none;
        }

        .page-head p {
            font-size: 12px;
        }

        .hide-mobile {
            display: none;
        }
    }
</style>
<div class="app-shell">
<main class="main"><div class="content">
    <div class="page-head">
        <div>
            <h1>배송관리</h1>
            <p><strong><?php echo get_text($manage_brand_id); ?></strong> 브랜드의 배송조건과 묶음배송 그룹을 별도로 관리합니다.</p>
        </div>
        <div style="display:flex;gap:8px;align-items:center">
            <?php if ($is_admin === 'super') { ?>
            <form method="get" style="display:flex;gap:8px;align-items:center">
                <select class="select" name="brand_id" onchange="this.form.submit()">
                    <?php foreach ($brand_rows as $bid) { ?><option value="<?php echo get_text($bid); ?>" <?php echo $bid===$manage_brand_id?'selected':''; ?>><?php echo get_text($bid); ?></option><?php } ?>
                </select>
            </form>
            <?php } ?>
            <button class="btn btn-primary" id="openCreate" type="button"><span class="plus">＋</span><span class="btn-label">배송조건 추가</span></button>
        </div>
    </div>

    <nav class="tabs" aria-label="배송 관리 탭">
        <button class="tab <?php echo $active_tab==='conditions'?'active':''; ?>" type="button" data-tab="conditions">배송조건 <span><?php echo count($conditions); ?></span></button>
        <button class="tab <?php echo $active_tab==='groups'?'active':''; ?>" type="button" data-tab="groups">묶음배송 그룹 <span><?php echo count($groups); ?></span></button>
        <button class="tab <?php echo $active_tab==='individual'?'active':''; ?>" type="button" data-tab="individual">그룹 미지정 상품 <span><?php echo count($ungrouped); ?></span></button>
    </nav>

    <section class="panel <?php echo $active_tab==='conditions'?'active':''; ?>" id="panel-conditions">
        <div class="guide"><div><h2>배송조건과 배송그룹은 별개의 설정입니다</h2><p>배송조건은 금액을 정하고, 배송그룹은 함께 묶어 한 번만 부과할 상품 범위를 정합니다.</p></div><div class="flow"><div class="flow-step"><span class="step-no">1</span><div><strong>배송조건</strong><small>상품별 금액 규칙</small></div></div><div class="flow-arrow">›</div><div class="flow-step"><span class="step-no">2</span><div><strong>배송그룹</strong><small>선택 시 묶음배송</small></div></div><div class="flow-arrow">›</div><div class="flow-step"><span class="step-no">3</span><div><strong>주문 계산</strong><small>개별합산 또는 MIN/MAX</small></div></div></div></div>
        <div class="summary-grid">
            <div class="summary-card"><div class="label">사용 중인 배송조건</div><div class="value"><?php echo count($conditions); ?>개</div><div class="sub">브랜드별 독립 저장</div></div>
            <div class="summary-card"><div class="label">배송조건 적용 상품</div><div class="value"><?php echo number_format($applied_count); ?>개</div><div class="sub">미설정 상품은 기본조건 자동 적용</div></div>
            <div class="summary-card"><div class="label">자동 생성 기본조건</div><div class="value"><?php echo number_format($default_count); ?>개</div><div class="sub">기본조건은 삭제 불가</div></div>
        </div>
        <div class="card">
            <div class="card-head"><div><h2>배송조건 목록</h2><p>현재 브랜드에만 적용됩니다.</p></div><div class="filters"><div class="search-wrap"><input class="field search-field" id="conditionSearch" type="search" placeholder="조건명 검색"></div><select class="select" id="typeFilter"><option value="all">전체 유형</option><option value="paid">유료</option><option value="conditional">조건부 무료</option><option value="free">무료</option><option value="quantity">수량별</option><option value="amount_range">금액 구간별</option></select></div></div>
            <div class="table-wrap"><table><thead><tr><th>배송조건명</th><th>배송비 유형</th><th>배송비 설정</th><th>적용 상품</th><th>지역 추가비</th><th>관리</th></tr></thead><tbody id="conditionRows">
            <?php foreach ($conditions as $c) { ?>
                <tr data-type="<?php echo get_text($c['dc_type']); ?>" data-name="<?php echo get_text($c['dc_name']); ?>">
                    <td><div class="condition-name"><strong><?php echo get_text($c['dc_name']); ?></strong><?php if ($c['is_default']) { ?><span class="badge badge-base">기본</span><?php } ?></div><span class="condition-desc"><?php echo $c['is_default']?'브랜드 기본 배송조건':'직접 추가한 배송조건'; ?></span></td>
                    <td><?php echo delivery_type_label($c['dc_type']); ?></td>
                    <td><strong><?php echo delivery_condition_main($c); ?></strong><?php $sub=delivery_condition_sub($c); if($sub){ ?><span class="condition-desc"><?php echo get_text($sub); ?></span><?php } ?></td>
                    <td><button class="usage condition-products-btn"
                            type="button"
                            data-condition-id="<?php echo (int)$c['dc_id']; ?>"
                            data-condition-name="<?php echo get_text($c['dc_name']); ?>"
                            title="이 배송조건을 적용할 상품 선택"><?php echo number_format((int)$c['product_count']); ?>개</button></td>
                    <td><span class="condition-desc">제주 <?php echo $c['dc_jeju_use']?'+'.number_format((int)$c['dc_jeju_price']).'원':'미사용'; ?><br>도서산간 <?php echo $c['dc_island_use']?'+'.number_format((int)$c['dc_island_price']).'원':'미사용'; ?></span></td>
                    <td><div class="row-actions">
                        <button class="text-btn edit-condition" type="button"
                            data-id="<?php echo (int)$c['dc_id']; ?>" data-name="<?php echo get_text($c['dc_name']); ?>" data-type="<?php echo get_text($c['dc_type']); ?>" data-price="<?php echo (int)$c['dc_price']; ?>" data-minimum="<?php echo (int)$c['dc_minimum']; ?>" data-qty="<?php echo (int)$c['dc_qty']; ?>" data-jeju-use="<?php echo (int)$c['dc_jeju_use']; ?>" data-jeju-price="<?php echo (int)$c['dc_jeju_price']; ?>" data-island-use="<?php echo (int)$c['dc_island_use']; ?>" data-island-price="<?php echo (int)$c['dc_island_price']; ?>" data-ranges='<?php echo htmlspecialchars($c['ranges_json'], ENT_QUOTES); ?>'>수정</button>
                        <form method="post" action="./deliverymanage_update.php" style="display:inline"><input type="hidden" name="token" value="<?php echo get_text($admin_token); ?>"><input type="hidden" name="brand_id" value="<?php echo get_text($manage_brand_id); ?>"><input type="hidden" name="action" value="clone_condition"><input type="hidden" name="dc_id" value="<?php echo (int)$c['dc_id']; ?>"><button class="text-btn" type="submit">복제</button></form>
                        <?php if (!$c['is_default']) { ?><form method="post" action="./deliverymanage_update.php" style="display:inline" onsubmit="return confirm('이 배송조건을 삭제하시겠습니까?');"><input type="hidden" name="token" value="<?php echo get_text($admin_token); ?>"><input type="hidden" name="brand_id" value="<?php echo get_text($manage_brand_id); ?>"><input type="hidden" name="action" value="delete_condition"><input type="hidden" name="dc_id" value="<?php echo (int)$c['dc_id']; ?>"><button class="text-btn" type="submit">삭제</button></form><?php } ?>
                    </div></td>
                </tr>
            <?php } ?>
            </tbody></table></div>
            <div class="empty-search" id="emptySearch">조건에 맞는 배송조건이 없습니다.</div>
        </div>
    </section>

    <section class="panel <?php echo $active_tab==='groups'?'active':''; ?>" id="panel-groups">
        <div class="guide"><div><h2>묶음배송 그룹</h2><p>같은 그룹 상품은 주문 시 배송비를 한 번만 계산합니다.</p></div><div class="flow"><div class="flow-step"><span class="step-no">1</span><div><strong>그룹 생성</strong><small>합포장 가능한 범위</small></div></div><div class="flow-arrow">›</div><div class="flow-step"><span class="step-no">2</span><div><strong>MIN / MAX</strong><small>대표 배송비 기준</small></div></div><div class="flow-arrow">›</div><div class="flow-step"><span class="step-no">3</span><div><strong>상품 이동</strong><small>실제 DB에 저장</small></div></div></div></div>
        <div class="card" style="padding:20px"><div class="card-head" style="padding:0 0 17px;min-height:auto;margin-bottom:15px"><div><h2>묶음배송 그룹</h2><p>브랜드별로 독립 관리됩니다.</p></div><button class="btn btn-small" id="openGroupCreate" type="button">＋ 그룹 추가</button></div>
        <div class="group-grid" id="groupGrid">
        <?php foreach ($groups as $g) { ?>
            <article class="group-card" data-group-id="<?php echo (int)$g['dg_id']; ?>" data-group-name="<?php echo get_text($g['dg_name']); ?>">
                <div class="group-top"><div><h3><?php echo get_text($g['dg_name']); ?></h3><p>묶음배송 그룹</p></div><span class="badge badge-bundle">사용 중</span></div>
                <div class="group-meta"><div><span>상품 수</span><strong><?php echo number_format((int)$g['product_count']); ?>개</strong></div><div><span>계산 방식</span><strong><?php echo get_text($g['calc_method']); ?></strong></div><div><span>적용 방식</span><strong>1회 부과</strong></div></div>
                <div style="display:flex;gap:8px;margin-top:14px"><button class="btn btn-small group-products" type="button" style="flex:1">기존 상품 추가·이동</button><form method="post" action="./deliverymanage_update.php" onsubmit="return confirm('그룹을 삭제하시겠습니까? 상품이 포함되어 있으면 삭제되지 않습니다.');"><input type="hidden" name="token" value="<?php echo get_text($admin_token); ?>"><input type="hidden" name="brand_id" value="<?php echo get_text($manage_brand_id); ?>"><input type="hidden" name="action" value="delete_group"><input type="hidden" name="dg_id" value="<?php echo (int)$g['dg_id']; ?>"><button class="btn btn-small" type="submit">삭제</button></form></div>
            </article>
        <?php } ?>
        <?php if (!$groups) { ?><div class="empty-products" style="display:block">등록된 묶음배송 그룹이 없습니다.</div><?php } ?>
        </div></div>
    </section>

    <section class="panel <?php echo $active_tab==='individual'?'active':''; ?>" id="panel-individual">
        <div class="card" style="padding:20px"><div class="card-head" style="padding:0 0 17px;min-height:auto;margin-bottom:15px"><div><h2>그룹 미지정 상품</h2><p>이 상품들은 주문 시 배송비가 상품별로 합산됩니다.</p></div><button class="btn btn-small" id="openApplySecond" type="button" disabled>선택 상품 배송설정 (0)</button></div>
        <div class="product-list-toolbar"><label><input id="selectAllUngrouped" type="checkbox"> 전체 선택</label><span>상품 행을 선택한 뒤 배송조건 또는 묶음그룹을 변경하세요.</span></div>
        <div class="product-list" id="ungroupedProductList">
        <?php foreach ($ungrouped as $p) { ?>
            <div class="product-row" tabindex="0" data-it-id="<?php echo get_text($p['it_id']); ?>" data-condition-id="<?php echo (int)$p['condition_id']; ?>">
                <input class="row-check" type="checkbox" value="<?php echo get_text($p['it_id']); ?>">
                <div class="product-info"><div class="product-thumb">▣</div><div><strong><?php echo get_text($p['it_name']); ?></strong><small><?php echo get_text($p['it_id']); ?> · <?php echo get_text($p['dc_name']); ?> · 그룹 미지정</small></div></div>
                <div class="product-actions"><div class="product-fee"><strong><?php echo delivery_product_fee_text($p); ?></strong><small>개별 부과</small></div><button class="btn btn-small row-shipping-btn" type="button">배송설정</button></div>
            </div>
        <?php } ?>
        </div><?php if (!$ungrouped) { ?><div class="empty-products" style="display:block">그룹 미지정 상품이 없습니다.</div><?php } ?></div>
    </section>
</div></main></div>

<div class="overlay" id="overlay"></div>

<aside class="drawer" id="conditionDrawer"><div class="drawer-head"><div><h2 id="drawerTitle">배송조건 추가</h2><p>실제 브랜드 배송비 규칙으로 저장됩니다.</p></div><button class="close-btn" type="button" data-close>×</button></div>
<form id="fdeliverycondition" method="post" action="./deliverymanage_update.php"><input type="hidden" name="token" value="<?php echo get_text($admin_token); ?>"><input type="hidden" name="brand_id" value="<?php echo get_text($manage_brand_id); ?>"><input type="hidden" name="action" value="save_condition"><input type="hidden" name="dc_id" id="dc_id" value=""><input type="hidden" name="dc_type" id="dc_type" value="conditional">
<div class="drawer-body"><div class="form-section"><label class="form-label">배송조건명</label><input class="form-control" id="conditionName" name="dc_name" maxlength="100" required></div>
<div class="form-section"><div class="section-title">배송비 유형</div><div class="fee-types" id="feeTypes"><button class="fee-type" type="button" data-fee="paid">유료</button><button class="fee-type active" type="button" data-fee="conditional">조건부 무료</button><button class="fee-type" type="button" data-fee="free">무료</button><button class="fee-type" type="button" data-fee="quantity">수량별</button><button class="fee-type" type="button" data-fee="amount_range">금액 구간별</button></div></div>
<div class="form-section" id="feeFields"><div class="two-col"><div class="input-unit"><label class="form-label">기본 배송비</label><input class="form-control" id="baseFee" name="dc_price" type="number" min="0" value="3000"><span class="unit">원</span></div><div class="input-unit" id="thresholdWrap"><label class="form-label">무료배송 기준</label><input class="form-control" id="freeThreshold" name="dc_minimum" type="number" min="0" value="50000"><span class="unit">원</span></div><div class="input-unit" id="quantityWrap" style="display:none"><label class="form-label">반복 부과 수량</label><input class="form-control" id="repeatQuantity" name="dc_qty" type="number" min="1" value="1"><span class="unit">개</span></div></div></div>
<div class="form-section" id="amountRangeFields" style="display:none"><div class="section-title">주문금액별 배송비 구간</div><div class="amount-range-editor"><div class="amount-range-head"><span>시작 금액</span><span>종료 금액</span><span>배송비</span><span></span></div><div id="amountRangeRows"></div><button class="range-add" id="addAmountRange" type="button">＋ 구간 추가</button></div></div>
<div class="form-section"><div class="section-title">지역별 추가 배송비</div><div class="two-col"><div><label class="form-label"><input type="checkbox" id="jejuUse" name="dc_jeju_use" value="1"> 제주 추가비 사용</label><div class="input-unit"><input class="form-control" id="jejuPrice" name="dc_jeju_price" type="number" min="0" value="3000"><span class="unit">원</span></div></div><div><label class="form-label"><input type="checkbox" id="islandUse" name="dc_island_use" value="1"> 도서산간 추가비 사용</label><div class="input-unit"><input class="form-control" id="islandPrice" name="dc_island_price" type="number" min="0" value="5000"><span class="unit">원</span></div></div></div></div>
<div class="fee-preview" id="feePreview"></div></div><div class="drawer-foot"><button class="btn" type="button" data-close>취소</button><button class="btn btn-brand" type="submit">저장</button></div></form></aside>

<aside class="drawer" id="groupDrawer"><div class="drawer-head"><div><h2>묶음배송 그룹 추가</h2><p>같이 주문된 상품의 배송비를 MIN 또는 MAX 한 번만 부과합니다.</p></div><button class="close-btn" type="button" data-close>×</button></div><form method="post" action="./deliverymanage_update.php"><input type="hidden" name="token" value="<?php echo get_text($admin_token); ?>"><input type="hidden" name="brand_id" value="<?php echo get_text($manage_brand_id); ?>"><input type="hidden" name="action" value="save_group"><div class="drawer-body"><div class="form-section"><label class="form-label">그룹명</label><input class="form-control" name="dg_name" required maxlength="100"></div><div class="form-section"><div class="section-title">계산 방식</div><div class="choice-grid"><label class="choice-card"><input type="radio" name="calc_method" value="MIN"><span class="radio-mark"></span><strong>MIN · 최저 배송비</strong><small>그룹 상품 중 가장 낮은 배송비 1회</small></label><label class="choice-card selected"><input type="radio" name="calc_method" value="MAX" checked><span class="radio-mark"></span><strong>MAX · 최고 배송비</strong><small>그룹 상품 중 가장 높은 배송비 1회</small></label></div></div></div><div class="drawer-foot"><button class="btn" type="button" data-close>취소</button><button class="btn btn-brand" type="submit">그룹 저장</button></div></form></aside>


<aside class="drawer" id="conditionProductDrawer">
    <div class="drawer-head">
        <div>
            <h2 id="conditionProductTitle">배송조건 적용 상품</h2>
            <p id="conditionProductSub">이 배송조건을 사용할 상품을 선택합니다.</p>
        </div>
        <button class="close-btn" type="button" data-close>×</button>
    </div>

    <form method="post" action="./deliverymanage_update.php" id="conditionProductForm">
        <input type="hidden" name="token" value="<?php echo get_text($admin_token); ?>">
        <input type="hidden" name="brand_id" value="<?php echo get_text($manage_brand_id); ?>">
        <input type="hidden" name="action" value="sync_condition_products">
        <input type="hidden" name="condition_id" id="conditionProductConditionId" value="">

        <div class="drawer-body">
            <div class="rule-note" style="margin:0 0 16px">
                <span class="info-dot">i</span>
                <span>
                    <strong>체크한 상품에 선택한 배송조건이 적용됩니다.</strong><br>
                    다른 배송조건을 사용 중인 상품을 체크하면 해당 조건으로 변경됩니다.
                    검색 결과에서 체크한 상품만 저장되며 기존 다른 상품의 설정은 유지됩니다.
                </span>
            </div>

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

        <div class="drawer-foot">
            <button class="btn" type="button" data-close>취소</button>
            <button class="btn btn-brand" type="submit">선택 상품 적용</button>
        </div>
    </form>
</aside>

<aside class="drawer" id="applyDrawer"><div class="drawer-head"><div><h2>상품 배송설정</h2><p>선택 상품의 배송조건과 묶음배송 그룹을 저장합니다.</p></div><button class="close-btn" type="button" data-close>×</button></div><form method="post" action="./deliverymanage_update.php" id="applyForm"><input type="hidden" name="token" value="<?php echo get_text($admin_token); ?>"><input type="hidden" name="brand_id" value="<?php echo get_text($manage_brand_id); ?>"><input type="hidden" name="action" value="apply_products"><div id="applyProductIds"></div><div class="drawer-body"><div class="select-block"><label class="form-label">배송조건</label><select name="condition_id" id="applyCondition" required><?php foreach($conditions as $c){ ?><option value="<?php echo (int)$c['dc_id']; ?>"><?php echo get_text($c['dc_name']); ?> — <?php echo delivery_type_label($c['dc_type']); ?></option><?php } ?></select></div><div class="select-block"><label class="form-label">묶음배송 그룹</label><select name="group_id" id="applyGroup"><option value="0">선택 안 함 · 개별배송</option><?php foreach($groups as $g){ ?><option value="<?php echo (int)$g['dg_id']; ?>"><?php echo get_text($g['dg_name']); ?> · <?php echo get_text($g['calc_method']); ?></option><?php } ?></select></div><div class="branch-box individual"><span class="info-dot">i</span><span>그룹을 선택하지 않으면 상품별 배송비가 각각 합산됩니다.</span></div></div><div class="drawer-foot"><button class="btn" type="button" data-close>취소</button><button class="btn btn-brand" type="submit">상품에 적용</button></div></form></aside>

<aside class="drawer" id="productGroupDrawer"><div class="drawer-head"><div><h2 id="productGroupTitle">기존 상품 추가·이동</h2><p>선택 상품을 대상 그룹으로 이동합니다.</p></div><button class="close-btn" type="button" data-close>×</button></div><form method="post" action="./deliverymanage_update.php" id="moveForm"><input type="hidden" name="token" value="<?php echo get_text($admin_token); ?>"><input type="hidden" name="brand_id" value="<?php echo get_text($manage_brand_id); ?>"><input type="hidden" name="action" value="move_products"><input type="hidden" name="group_id" id="moveGroupId"><div class="drawer-body"><div class="two-col select-block"><div><label class="form-label">상품 검색</label><input class="form-control" id="productPickSearch" type="search"></div><div><label class="form-label">현재 소속</label><select class="form-control" id="productSourceFilter"><option value="all">전체</option><option value="none">그룹 미지정</option><?php foreach($groups as $g){ ?><option value="<?php echo (int)$g['dg_id']; ?>"><?php echo get_text($g['dg_name']); ?></option><?php } ?></select></div></div><div class="product-pick-list" id="productPickList"><?php foreach($products as $p){ ?><label class="product-pick" data-name="<?php echo get_text($p['it_name']); ?>" data-no="<?php echo get_text($p['it_id']); ?>" data-group="<?php echo (int)$p['group_id']; ?>"><input type="checkbox" name="it_ids[]" value="<?php echo get_text($p['it_id']); ?>"><span class="product-thumb">▣</span><span class="product-pick-copy"><strong><?php echo get_text($p['it_name']); ?></strong><small><?php echo get_text($p['it_id']); ?> · <?php echo get_text($p['dc_name']); ?></small></span><span class="product-source <?php echo empty($p['group_id'])?'ungrouped':''; ?>"><?php echo empty($p['group_id'])?'그룹 미지정':get_text($p['dg_name']); ?></span></label><?php } ?></div></div><div class="drawer-foot"><button class="btn" type="button" data-close>취소</button><button class="btn btn-brand" type="submit">선택 상품 이동</button></div></form></aside>

<div class="toast" id="toast"><span class="toast-check">✓</span><span id="toastText"></span></div>

<script>
(function () {
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
        try { ranges = JSON.parse(b.dataset.ranges || '[]'); } catch (e) {}
        (ranges.length ? ranges : [{min: 0, max: null, fee: 0}]).forEach(r => addRange(r.min, r.max ?? '', r.fee));
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

            const checked = conditionSelectedIds.has(itId) ? ' checked' : '';
            const conditionName = item.dc_name ? escapeHtml(item.dc_name) : '미설정';
            const price = Number(item.it_price || 0).toLocaleString();

            label.innerHTML = `
                <input type="checkbox" value="${escapeHtml(itId)}"${checked}>
                <span class="product-thumb">▣</span>
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
                './ajax.delivery_product_search.php?' + params.toString(),
                {
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

    /* 묶음배송 그룹 -> 기존 상품 추가/이동 */
    function refreshProductPickSelected() {
        if (!productPickList) return;
        productPickList.querySelectorAll('.product-pick').forEach(item => {
            const cb = item.querySelector('input[type="checkbox"]');
            item.classList.toggle('selected', !!(cb && cb.checked));
        });
    }

    if (productPickList) {
        productPickList.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            cb.addEventListener('change', refreshProductPickSelected);
            cb.addEventListener('click', e => e.stopPropagation());
        });
        productPickList.querySelectorAll('.product-pick').forEach(item => {
            item.addEventListener('click', e => {
                if (e.target.matches('input[type="checkbox"]')) return;
                e.preventDefault();
                const cb = item.querySelector('input[type="checkbox"]');
                if (!cb || item.style.display === 'none') return;
                cb.checked = !cb.checked;
                cb.dispatchEvent(new Event('change', {bubbles: true}));
            });
        });
    }

    function filterProducts() {
        if (!productPickList || !productPickSearch || !productSourceFilter || !moveGroupId) return;
        const q = productPickSearch.value.trim().toLowerCase();
        const g = productSourceFilter.value;
        const target = String(moveGroupId.value || '');

        productPickList.querySelectorAll('.product-pick').forEach(r => {
            const rowGroup = String(r.dataset.group || '0');
            const own = rowGroup === target;
            const groupOk = g === 'all' || (g === 'none' && rowGroup === '0') || rowGroup === g;
            const text = ((r.dataset.name || '') + ' ' + (r.dataset.no || '')).toLowerCase();
            const show = !own && groupOk && text.includes(q);
            r.style.display = show ? 'flex' : 'none';

            if (!show) {
                const cb = r.querySelector('input[type="checkbox"]');
                if (cb) cb.checked = false;
                r.classList.remove('selected');
            }
        });
    }

    document.querySelectorAll('.group-products').forEach(b => b.addEventListener('click', () => {
        const card = b.closest('.group-card');
        if (!card || !moveGroupId) return;

        moveGroupId.value = card.dataset.groupId;
        if (productGroupTitle) productGroupTitle.textContent = '‘' + card.dataset.groupName + '’으로 상품 이동';
        if (productPickSearch) productPickSearch.value = '';
        if (productSourceFilter) productSourceFilter.value = 'all';

        if (productPickList) {
            productPickList.querySelectorAll('input[type="checkbox"]').forEach(x => x.checked = false);
        }
        refreshProductPickSelected();
        filterProducts();
        openDrawer(productGroupDrawer);
    }));

    if (productPickSearch) productPickSearch.addEventListener('input', filterProducts);
    if (productSourceFilter) productSourceFilter.addEventListener('change', filterProducts);

    if (moveForm) moveForm.addEventListener('submit', e => {
        const checked = [...moveForm.querySelectorAll('input[name="it_ids[]"]:checked')]
            .filter(cb => cb.closest('.product-pick')?.style.display !== 'none');
        if (!checked.length) {
            e.preventDefault();
            alert('이동할 상품을 한 개 이상 선택해 주세요.');
        }
    });

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

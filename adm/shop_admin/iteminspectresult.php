<?php
$sub_menu = '400300';
include_once('./_common.php');
include_once(G5_LIB_PATH . '/donuts_item_inspection.lib.php');

auth_check_menu($auth, $sub_menu, 'r');

if (!donuts_item_inspection_table_exists()) {
    alert('상품검수 테이블이 없습니다. install.sql을 먼저 실행해 주세요.');
}

$brand_id = trim((string)$member['mb_id']);

if (!donuts_item_inspection_is_brand($brand_id)) {
    alert('브랜드 회원만 접근할 수 있습니다.');
}

$brand_sql = sql_real_escape_string($brand_id);

/*
 * 검수 row가 누락된 브랜드 상품을 공통 규칙으로 복구합니다.
 */
donuts_item_inspection_sync_untracked_brand_products(30);

/*
 * 판매 비노출 상품(it_use = 0) 동기화
 * 검수 데이터가 없는 현재 브랜드의 모든 판매 비노출 상품을 draft로 생성합니다.
 * 기간 제한을 두지 않습니다.
 */
$hidden_products = sql_query("
    SELECT i.it_id
    FROM {$g5['g5_shop_item_table']} i
    LEFT JOIN donuts_item_inspections r
      ON r.it_id COLLATE utf8mb4_general_ci = i.it_id COLLATE utf8mb4_general_ci
     AND r.brand_id = '{$brand_sql}'
    WHERE TRIM(i.it_brand) = '{$brand_sql}'
      AND i.it_use = 0
      AND r.inspection_id IS NULL
    ORDER BY i.it_time DESC, i.it_id DESC
", false);

if ($hidden_products) {
    while ($hidden = sql_fetch_array($hidden_products)) {
        $hidden_it_id = trim((string)$hidden['it_id']);
        if ($hidden_it_id === '') continue;

        donuts_item_inspection_ensure_draft(
            $hidden_it_id,
            $brand_id,
            'new',
            $brand_id
        );
    }
}

$status = isset($_GET['status']) ? trim($_GET['status']) : 'draft';

$allowed = array('draft', 'pending', 'revision', 'rejected', 'approved', 'all');

if (!in_array($status, $allowed, true)) {
    $status = 'draft';
}

$status_where = '';

if ($status !== 'all') {
    $status_sql = sql_real_escape_string($status);
    $status_where = " AND r.status = '{$status_sql}' ";
}

$rows = array();

if ($status === 'draft') {
    /*
     * 임시저장 = 현재 브랜드의 판매 비노출(it_use=0) 상품 중
     * 아직 플랫폼 검수중(pending)이 아닌 상품.
     * 검수 row가 없더라도 위 동기화에서 draft가 생성됩니다.
     */
    $result = sql_query("
        SELECT
            r.*,
            i.it_id AS product_it_id,
            i.it_name,
            i.it_price,
            i.it_use,
            i.it_time
        FROM {$g5['g5_shop_item_table']} i
        LEFT JOIN donuts_item_inspections r
          ON r.it_id COLLATE utf8mb4_general_ci = i.it_id COLLATE utf8mb4_general_ci
         AND r.brand_id = '{$brand_sql}'
        WHERE TRIM(i.it_brand) = '{$brand_sql}'
          AND i.it_use = 0
          AND (r.inspection_id IS NULL OR r.status IN ('draft','revision','rejected'))
        ORDER BY i.it_time DESC, i.it_id DESC
    ", false);
} else {
    $status_where = '';
    if ($status !== 'all') {
        $status_sql = sql_real_escape_string($status);
        $status_where = " AND r.status = '{$status_sql}' ";
    }

    $result = sql_query("
        SELECT
            r.*,
            i.it_id AS product_it_id,
            i.it_name,
            i.it_price,
            i.it_use,
            i.it_time
        FROM donuts_item_inspections r
        INNER JOIN {$g5['g5_shop_item_table']} i
            ON i.it_id COLLATE utf8mb4_general_ci = r.it_id COLLATE utf8mb4_general_ci
        WHERE r.brand_id = '{$brand_sql}'
          AND TRIM(i.it_brand) = '{$brand_sql}'
          {$status_where}
        ORDER BY COALESCE(r.requested_at, r.updated_at, r.created_at) DESC, r.inspection_id DESC
    ", false);
}

if ($result) {
    while ($row = sql_fetch_array($result)) {
        if (empty($row['it_id']) && !empty($row['product_it_id'])) {
            $row['it_id'] = $row['product_it_id'];
        }
        if (empty($row['status'])) {
            $row['status'] = 'draft';
        }
        $rows[] = $row;
    }
}

/*
 * 상세 모달용 실제 데이터
 *
 * 프론트에 하드코딩되어 있던 상품정보/보완요청/처리이력을
 * 실제 상품 + 검수 DB 기준으로 구성합니다.
 */
$inspection_details = array();

foreach ($rows as $detail_row) {
    $detail_it_id = !empty($detail_row['it_id'])
        ? trim((string)$detail_row['it_id'])
        : '';

    if ($detail_it_id === '') {
        continue;
    }

    $detail_it_sql = sql_real_escape_string($detail_it_id);

    $detail_item = sql_fetch("
        SELECT
            it_id,
            it_name,
            it_basic,
            it_brand,
            it_model,
            it_price,
            it_stock_qty,
            it_explan,
            it_use,
            it_time,
            it_img1,
            ca_id
        FROM {$g5['g5_shop_item_table']}
        WHERE it_id = '{$detail_it_sql}'
        LIMIT 1
    ");

    if (empty($detail_item['it_id'])) {
        continue;
    }

    $category_name = '';

    if (!empty($detail_item['ca_id'])) {
        $detail_ca_sql = sql_real_escape_string($detail_item['ca_id']);

        $category = sql_fetch("
            SELECT ca_name
            FROM {$g5['g5_shop_category_table']}
            WHERE ca_id = '{$detail_ca_sql}'
            LIMIT 1
        ");

        if (!empty($category['ca_name'])) {
            $category_name = $category['ca_name'];
        }
    }

    $review_fields = array();

    if (!empty($detail_row['review_fields'])) {
        $decoded_fields = json_decode($detail_row['review_fields'], true);

        if (is_array($decoded_fields)) {
            foreach ($decoded_fields as $field_name) {
                $field_name = trim((string)$field_name);

                if ($field_name !== '') {
                    $review_fields[] = $field_name;
                }
            }
        }
    }

    $detail_logs = array();

    if (!empty($detail_row['inspection_id'])) {
        $detail_inspection_id = (int)$detail_row['inspection_id'];

        $log_result = sql_query("
            SELECT
                from_status,
                to_status,
                action_by,
                message,
                review_fields,
                created_at
            FROM donuts_item_inspection_logs
            WHERE inspection_id = '{$detail_inspection_id}'
            ORDER BY log_id ASC
        ", false);

        if ($log_result) {
            while ($log = sql_fetch_array($log_result)) {
                $detail_logs[] = array(
                    'from_status' => (string)$log['from_status'],
                    'to_status' => (string)$log['to_status'],
                    'to_status_label' => donuts_item_inspection_status_label($log['to_status']),
                    'action_by' => (string)$log['action_by'],
                    'message' => (string)$log['message'],
                    'created_at' => (string)$log['created_at']
                );
            }
        }
    }

    $delivery_label = donuts_item_inspection_delivery_label(
        $brand_id,
        $detail_it_id
    );

    $image_html = '';

    if (function_exists('get_it_image')) {
        $image_html = get_it_image(
            $detail_it_id,
            360,
            360,
            false,
            'inspection-detail-image',
            'class="max-h-full max-w-full object-contain" alt="' . get_text($detail_item['it_name']) . '"',
            true
        );
    }

    $inspection_details[$detail_it_id] = array(
        'it_id' => $detail_it_id,
        'it_name' => (string)$detail_item['it_name'],
        'it_basic' => (string)$detail_item['it_basic'],
        'it_brand' => (string)$detail_item['it_brand'],
        'it_model' => (string)$detail_item['it_model'],
        'it_price' => (int)$detail_item['it_price'],
        'it_stock_qty' => (int)$detail_item['it_stock_qty'],
        'it_explan' => conv_content((string) $detail_item['it_explan'], 1),
        'it_use' => (int)$detail_item['it_use'],
        'it_time' => (string)$detail_item['it_time'],
        'ca_id' => (string)$detail_item['ca_id'],
        'category_name' => $category_name,
        'inspect_no' => !empty($detail_row['inspect_no'])
            ? (string)$detail_row['inspect_no']
            : '',
        'request_type' => !empty($detail_row['request_type'])
            ? (string)$detail_row['request_type']
            : 'new',
        'status' => !empty($detail_row['status'])
            ? (string)$detail_row['status']
            : 'draft',
        'status_label' => donuts_item_inspection_status_label(
            !empty($detail_row['status']) ? $detail_row['status'] : 'draft'
        ),
        'requested_at' => !empty($detail_row['requested_at'])
            ? (string)$detail_row['requested_at']
            : '',
        'reviewed_at' => !empty($detail_row['reviewed_at'])
            ? (string)$detail_row['reviewed_at']
            : '',
        'admin_message' => !empty($detail_row['admin_message'])
            ? (string)$detail_row['admin_message']
            : '',
        'brand_message' => !empty($detail_row['brand_message'])
            ? (string)$detail_row['brand_message']
            : '',
        'review_fields' => $review_fields,
        'delivery_label' => (string)$delivery_label,
        'image_html' => $image_html,
        'logs' => $detail_logs
    );
}

$counts = array(
    'draft' => 0,
    'pending' => 0,
    'revision' => 0,
    'rejected' => 0,
    'approved' => 0
);

/* 임시저장 건수는 it_use=0 기준 */
$draft_count = sql_fetch("
    SELECT COUNT(*) AS cnt
    FROM {$g5['g5_shop_item_table']} i
    LEFT JOIN donuts_item_inspections r
      ON r.it_id COLLATE utf8mb4_general_ci = i.it_id COLLATE utf8mb4_general_ci
     AND r.brand_id = '{$brand_sql}'
    WHERE TRIM(i.it_brand) = '{$brand_sql}'
      AND i.it_use = 0
      AND (r.inspection_id IS NULL OR r.status IN ('draft','revision','rejected'))
");
$counts['draft'] = isset($draft_count['cnt']) ? (int)$draft_count['cnt'] : 0;

$count_result = sql_query("
    SELECT r.status, COUNT(*) AS cnt
    FROM donuts_item_inspections r
    INNER JOIN {$g5['g5_shop_item_table']} i ON i.it_id COLLATE utf8mb4_general_ci = r.it_id COLLATE utf8mb4_general_ci
    WHERE r.brand_id = '{$brand_sql}'
      AND TRIM(i.it_brand) = '{$brand_sql}'
      AND r.status IN ('pending','revision','rejected','approved')
    GROUP BY r.status
", false);

if ($count_result) {
    while ($c = sql_fetch_array($count_result)) {
        if (isset($counts[$c['status']]) && $c['status'] !== 'draft') {
            $counts[$c['status']] = (int)$c['cnt'];
        }
    }
}

$token = get_admin_token();

add_stylesheet(
    '<link rel="stylesheet" href="' .
        G5_ADMIN_URL .
        '/shop_admin/css/brand.css?ver=' .
        G5_CSS_VER .
        '">',
    100
);

$g5['title'] = '상품검수(브랜드)';
include_once(G5_ADMIN_PATH . '/admin.head.php');
?>

<section id="view" class="content relative">
    <div class="head flex items-center justify-between mb-4">
        <div>
            <h2>상품 검수</h2>
            <p>상품 등록 후 임시저장에서 검수 요청을 진행해 주세요.</p>
        </div>
        <div class="actions">
            <a class="btn primary" href="./itemform.php">+ 신규 상품 등록</a>
        </div>
    </div>

    <div id="inspection-status-summary" class="grid grid-cols-2 gap-3 pc:grid-cols-5">
        <a href="./iteminspectresult.php?status=draft" class="flex flex-col justify-between min-h-32 border rounded-xl text-xs text-gray-500 p-4 <?php echo $status === 'draft' ? 'border-gray-500 bg-gray-50' : 'border-gray-300 bg-white'; ?>">
            <span>임시저장</span>
            <span class="text-2xl font-black text-gray-900"><?php echo number_format($counts['draft']); ?>건</span>
            <span>아직 플랫폼 미제출</span>
        </a>

        <a href="./iteminspectresult.php?status=pending" class="flex flex-col justify-between min-h-32 border rounded-xl text-xs text-gray-500 p-4 <?php echo $status === 'pending' ? 'border-gray-500 bg-gray-50' : 'border-gray-300 bg-white'; ?>">
            <span>검수</span>
            <span class="text-2xl font-black text-gray-900"><?php echo number_format($counts['pending']); ?>건</span>
            <span>플랫폼 심사중</span>
        </a>

        <a href="./iteminspectresult.php?status=revision" class="flex flex-col justify-between min-h-32 border rounded-xl text-xs text-gray-500 p-4 <?php echo $status === 'revision' ? 'border-gray-500 bg-gray-50' : 'border-gray-300 bg-white'; ?>">
            <span>보완 요청</span>
            <span class="text-2xl font-black text-gray-900"><?php echo number_format($counts['revision']); ?>건</span>
            <span>수정 후 재신청</span>
        </a>

        <a href="./iteminspectresult.php?status=rejected" class="flex flex-col justify-between min-h-32 border rounded-xl text-xs text-gray-500 p-4 <?php echo $status === 'rejected' ? 'border-gray-500 bg-gray-50' : 'border-gray-300 bg-white'; ?>">
            <span>거절</span>
            <span class="text-2xl font-black text-gray-900"><?php echo number_format($counts['rejected']); ?>건</span>
            <span>등록 불가</span>
        </a>

        <a href="./iteminspectresult.php?status=approved" class="flex flex-col justify-between min-h-32 border rounded-xl text-xs text-gray-500 p-4 <?php echo $status === 'approved' ? 'border-gray-500 bg-gray-50' : 'border-gray-300 bg-white'; ?>">
            <span>승인</span>
            <span class="text-2xl font-black text-gray-900"><?php echo number_format($counts['approved']); ?>건</span>
            <span>판매 가능</span>
        </a>
    </div>

    <div class="toolbar mt-4">
        <a class="btn <?php echo $status === 'all' ? 'primary' : ''; ?>" href="./iteminspectresult.php?status=all">전체</a>

        <div role="search">
            <label for="inspection-search-input" class="sound_only">상품명 검색</label>
            <input
                type="search"
                id="inspection-search-input"
                placeholder="상품 검색"
                autocomplete="off">
        </div>
    </div>

    <div class="card mt-4">
        <div class="tw">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>상품</th>
                        <th>검수번호</th>
                        <th>등록일</th>
                        <th>상태</th>
                        <th>판매상태</th>
                        <th>관리</th>
                    </tr>
                </thead>
                <tbody id="inspection-list-body">
                    <?php if (!$rows) { ?>
                        <tr>
                            <td colspan="6" style="text-align:center;padding:38px 10px;">
                                <?php echo $status === 'draft'
                                    ? '임시저장된 상품이 없습니다.'
                                    : '해당 상태의 상품이 없습니다.'; ?>
                            </td>
                        </tr>
                    <?php } ?>

                    <?php foreach ($rows as $row) { ?>
                        <tr class="js-inspection-search-row">
                            <td class="name">
                                <?php echo get_text($row['it_name']); ?>
                                <span class="sub">
                                    <?php echo get_text($row['it_id']); ?> ·
                                    <?php echo number_format((int)$row['it_price']); ?>원
                                </span>
                            </td>
                            <td><?php echo get_text($row['inspect_no']); ?></td>
                            <td><?php echo get_text($row['it_time']); ?></td>
                            <td>
                                <span class="badge <?php echo donuts_item_inspection_status_badge($row['status']); ?>">
                                    <?php echo get_text(donuts_item_inspection_status_label($row['status'])); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?php echo $row['it_use'] ? 'green' : 'yellow'; ?>">
                                    <?php echo $row['it_use'] ? '판매중' : '판매대기'; ?>
                                </span>
                            </td>
                            <td>
                                <button
                                    type="button"
                                    class="btn sm js-inspection-detail"
                                    data-it-id="<?php echo get_text($row['it_id']); ?>">상세</button>

                                <?php if (in_array($row['status'], array('draft', 'revision', 'rejected'), true)) { ?>
                                    <a class="btn sm" href="./itemform.php?w=u&amp;it_id=<?php echo urlencode($row['it_id']); ?>">수정</a>

                                    <form method="post" action="./iteminspect_request.php" style="display:inline-block;"
                                        onsubmit="return confirm('이 상품을 플랫폼 검수 요청하시겠습니까?');">
                                        <input type="hidden" name="token" value="<?php echo get_text($token); ?>">
                                        <input type="hidden" name="it_id" value="<?php echo get_text($row['it_id']); ?>">
                                        <input type="hidden" name="brand_message" value="">
                                        <button type="submit" class="btn sm primary">검수 요청</button>
                                    </form>
                                <?php } elseif ($row['status'] === 'pending') { ?>
                                    <span class="badge blue">플랫폼 검수 중</span>
                                <?php } elseif ($row['status'] === 'approved') { ?>
                                    <span class="badge green">승인 완료</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>

                    <tr id="inspection-search-empty" hidden>
                        <td colspan="6" class="text-center text-xs text-gray-500">
                            검색 결과가 없습니다.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="inspection-detail-modal" class="modal-bg" hidden>
        <section class="modal" role="dialog" aria-modal="true" aria-labelledby="inspection-detail-title">
            <div class="modal-head">
                <h3 id="inspection-detail-title">상품 검수 상세</h3>
                <button type="button" data-action="close-inspection-detail" aria-label="상세 모달 닫기">×</button>
            </div>

            <div class="modal-body">
                <span id="inspection-detail-code" class="text-xs text-gray-500">상품코드</span>
                <h3 id="inspection-detail-name" class="my-2 text-base font-bold">상품명</h3>

                <div
                    id="inspection-detail-image"
                    class="mb-4 flex aspect-square w-40 items-center justify-center overflow-hidden rounded-xl border border-gray-200 bg-gray-50 text-xs text-gray-400">
                    상품 이미지
                </div>

                <dl class="grid grid-cols-[115px_1fr] overflow-hidden rounded-xl border border-gray-200 text-xs [&>dt]:border-b [&>dt]:border-gray-200 [&>dt]:bg-gray-50 [&>dt]:p-3 [&>dt]:text-gray-500 [&>dt:last-of-type]:border-b-0 [&>dd]:m-0 [&>dd]:border-b [&>dd]:border-gray-200 [&>dd]:p-3 [&>dd]:font-bold [&>dd:last-child]:border-b-0">
                    <dt>상품 ID</dt>
                    <dd id="inspection-detail-id">-</dd>
                    <dt>브랜드</dt>
                    <dd id="inspection-detail-brand">-</dd>
                    <dt>판매가</dt>
                    <dd id="inspection-detail-price">0원</dd>
                    <dt>요청 유형</dt>
                    <dd id="inspection-detail-request-type">-</dd>
                    <dt>배송그룹</dt>
                    <dd id="inspection-detail-delivery">-</dd>
                    <dt>판매 상태</dt>
                    <dd id="inspection-detail-sale-status">-</dd>
                    <dt>검수 상태</dt>
                    <dd id="inspection-detail-status">-</dd>
                    <dt>요청일</dt>
                    <dd id="inspection-detail-requested-at">-</dd>
                </dl>

                <section class="mt-4">
                    <h2 class="h2_frm">상품 정보</h2>
                    <div class="tbl_frm01 tbl_wrap mt-4 overflow-hidden rounded-xl border border-gray-200 text-xs">
                        <table>
                            <colgroup>
                                <col class="grid_4">
                                <col>
                                <col class="grid_2">
                            </colgroup>
                            <tbody id="inspection-detail-fields"></tbody>
                        </table>
                    </div>
                </section>

                <section id="inspection-review-section" class="mt-4">
                    <h3 class="text-sm font-semibold">보완 요청 사항</h3>
                    <div class="mt-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-gray-700">
                        <div id="inspection-review-fields" class="mb-3"></div>
                        <p id="inspection-review-message" class="whitespace-pre-line text-xs text-red-500"></p>
                    </div>
                </section>

                <section id="inspection-brand-message-section" class="mt-4" hidden>
                    <h3 class="text-sm font-semibold">브랜드 검수 요청 메모</h3>
                    <div class="mt-3 rounded-xl border border-gray-200 p-4">
                        <p id="inspection-brand-message" class="whitespace-pre-line text-xs text-gray-600"></p>
                    </div>
                </section>

                <section class="mt-4">
                    <h3 class="text-sm font-semibold">처리 이력</h3>
                    <div id="inspection-detail-timeline" class="timeline mt-3"></div>
                </section>
            </div>

            <div class="modal-foot">
                <a
                    id="inspection-detail-edit"
                    class="btn primary"
                    href="#"
                    hidden>상품 수정</a>
                <button type="button" class="btn" data-action="close-inspection-detail">닫기</button>
            </div>
        </section>
    </div>

    <script>
        $(function() {
            const inspectionDetails = <?php echo json_encode(
                                            $inspection_details,
                                            JSON_UNESCAPED_UNICODE |
                                                JSON_UNESCAPED_SLASHES |
                                                JSON_HEX_TAG |
                                                JSON_HEX_AMP |
                                                JSON_HEX_APOS |
                                                JSON_HEX_QUOT
                                        ); ?>;

            const fieldLabels = {
                ca_id: '기본분류',
                it_name: '상품명',
                it_basic: '기본설명',
                it_brand: '브랜드',
                it_model: '모델',
                it_price: '판매가격',
                it_stock_qty: '재고수량',
                it_explan: '상품설명'
            };

            const $modal = $('#inspection-detail-modal');

            function escapeHtml(value) {
                return $('<div>').text(value == null ? '' : String(value)).html();
            }

            function formatNumber(value) {
                return Number(value || 0).toLocaleString('ko-KR');
            }

            function fieldValue(detail, field) {
                switch (field) {
                    case 'ca_id':
                        if (detail.category_name) {
                            return detail.category_name + ' (' + detail.ca_id + ')';
                        }
                        return detail.ca_id || '-';

                    case 'it_price':
                        return formatNumber(detail.it_price) + '원';

                    case 'it_stock_qty':
                        return formatNumber(detail.it_stock_qty) + '개';

                    case 'it_basic':
                    case 'it_model':
                    case 'it_explan':
                        return detail[field] || '-';

                    default:
                        return detail[field] || '-';
                }
            }

            function renderDetailFields(detail) {
                const reviewFields = Array.isArray(detail.review_fields) ?
                    detail.review_fields :
                    [];

                let html = '';

                Object.keys(fieldLabels).forEach(field => {
                    const needsRevision = reviewFields.includes(field);
                    const isHtmlField = field === 'it_explan';
                    const value = isHtmlField ?
                        fieldValue(detail, field) :
                        escapeHtml(fieldValue(detail, field));

                    html += '<tr>';

                    html += '<th>' + escapeHtml(fieldLabels[field]) + '</th>';

                    if (isHtmlField) {
                        html += '<td' +
                            (needsRevision ? ' class="bg-red-100"' : '') +
                            '><div class="max-w-full overflow-x-auto break-words [&_img]:h-auto [&_img]:max-w-full">' +
                            value +
                            '</div></td>';
                    } else {
                        html += '<td' +
                            (needsRevision ? ' class="bg-red-100"' : '') +
                            '><span class="block whitespace-pre-line">' +
                            value +
                            '</span></td>';
                    }

                    if (needsRevision) {
                        html += '<td class="text-center font-semibold text-red-500">보완 요청</td>';
                    } else {
                        html += '<td class="text-center text-gray-400">-</td>';
                    }

                    html += '</tr>';
                });

                $('#inspection-detail-fields').html(html);
            }

            function renderReview(detail) {
                const reviewFields = Array.isArray(detail.review_fields) ?
                    detail.review_fields :
                    [];

                const labels = reviewFields
                    .map(field => fieldLabels[field] || field);

                if (labels.length) {
                    $('#inspection-review-fields').html(
                        '<div class="flex flex-wrap gap-2">' +
                        labels.map(label =>
                            '<span class="badge red">' +
                            escapeHtml(label) +
                            '</span>'
                        ).join('') +
                        '</div>'
                    );
                } else {
                    $('#inspection-review-fields').html(
                        '<span class="text-xs text-gray-500">지정된 보완 항목이 없습니다.</span>'
                    );
                }

                const adminMessage = detail.admin_message || '';

                $('#inspection-review-message').text(
                    adminMessage || '플랫폼 관리자가 별도 검수 의견을 입력하지 않았습니다.'
                );

                /*
                 * 보완요청/거절일 때는 항상 보이고,
                 * 다른 상태에서는 실제 의견이 있을 때만 표시합니다.
                 */
                const showReview = (
                    detail.status === 'revision' ||
                    detail.status === 'rejected' ||
                    labels.length > 0 ||
                    adminMessage !== ''
                );

                $('#inspection-review-section').prop('hidden', !showReview);
            }

            function renderTimeline(detail) {
                const logs = Array.isArray(detail.logs) ?
                    detail.logs :
                    [];

                if (!logs.length) {
                    $('#inspection-detail-timeline').html(
                        '<div class="timeline-item">' +
                        '<b>처리 이력이 없습니다.</b>' +
                        '</div>'
                    );
                    return;
                }

                const html = logs.map(log => {
                    let meta = escapeHtml(log.created_at || '-');

                    if (log.action_by) {
                        meta += ' · ' + escapeHtml(log.action_by);
                    }

                    if (log.message) {
                        meta += ' · ' + escapeHtml(log.message);
                    }

                    return (
                        '<div class="timeline-item">' +
                        '<b>' + escapeHtml(log.to_status_label || log.to_status || '-') + '</b>' +
                        '<small>' + meta + '</small>' +
                        '</div>'
                    );
                }).join('');

                $('#inspection-detail-timeline').html(html);
            }

            function openInspectionDetail(itId) {
                const detail = inspectionDetails[itId];

                if (!detail) {
                    alert('상품 검수 상세 정보를 불러올 수 없습니다.');
                    return;
                }

                $('#inspection-detail-code').text(
                    (detail.inspect_no ? detail.inspect_no + ' · ' : '') +
                    detail.it_id
                );

                $('#inspection-detail-name').text(detail.it_name || '(상품명 없음)');
                $('#inspection-detail-id').text(detail.it_id || '-');
                $('#inspection-detail-brand').text(detail.it_brand || '-');
                $('#inspection-detail-price').text(formatNumber(detail.it_price) + '원');
                $('#inspection-detail-request-type').text(
                    detail.request_type === 'update' ? '정보 변경' : '신규'
                );
                $('#inspection-detail-delivery').text(detail.delivery_label || '미지정');
                $('#inspection-detail-sale-status').text(
                    Number(detail.it_use) === 1 ? '판매중' : '판매 대기'
                );
                $('#inspection-detail-status').text(detail.status_label || detail.status || '-');
                $('#inspection-detail-requested-at').text(detail.requested_at || '-');

                if (detail.image_html) {
                    $('#inspection-detail-image').html(detail.image_html);
                } else {
                    $('#inspection-detail-image').text('상품 이미지 없음');
                }

                renderDetailFields(detail);
                renderReview(detail);
                renderTimeline(detail);

                if (detail.brand_message) {
                    $('#inspection-brand-message').text(detail.brand_message);
                    $('#inspection-brand-message-section').prop('hidden', false);
                } else {
                    $('#inspection-brand-message').text('');
                    $('#inspection-brand-message-section').prop('hidden', true);
                }

                /*
                 * 보완 요청/거절/임시저장 상태에서는 상세창에서 바로 수정 가능.
                 */
                const canEdit = ['draft', 'revision', 'rejected'].includes(detail.status);

                $('#inspection-detail-edit')
                    .prop('hidden', !canEdit)
                    .attr(
                        'href',
                        canEdit ?
                        './itemform.php?w=u&it_id=' + encodeURIComponent(detail.it_id) :
                        '#'
                    );

                $modal.removeAttr('hidden');
            }

            $('.js-inspection-detail').on('click', function() {
                const itId = String($(this).data('it-id') || '');
                openInspectionDetail(itId);
            });

            $modal.on('click', function(event) {
                if (event.target === this) {
                    $modal.attr('hidden', true);
                }
            });

            $modal.find('[data-action="close-inspection-detail"]').on('click', function() {
                $modal.attr('hidden', true);
            });

            $(document).on('keydown', function(event) {
                if (event.key === 'Escape' && !$modal.is('[hidden]')) {
                    $modal.attr('hidden', true);
                }
            });

            // 상품 목록 검색
            const $searchInput = $('#inspection-search-input');
            const $searchRows = $('#inspection-list-body .js-inspection-search-row');
            const $searchEmpty = $('#inspection-search-empty');

            $searchInput.on('input', function() {
                const keyword = $.trim($(this).val()).toLowerCase();
                let matchedCount = 0;

                if (!$searchRows.length) {
                    return;
                }

                $searchRows.each(function() {
                    const searchText = $(this).text().toLowerCase();
                    const isMatched = searchText.includes(keyword);

                    $(this).toggle(isMatched);

                    if (isMatched) {
                        matchedCount += 1;
                    }
                });

                $searchEmpty.prop('hidden', matchedCount !== 0);
            });
        });
    </script>

</section>

<?php include_once(G5_ADMIN_PATH . '/admin.tail.php'); ?>
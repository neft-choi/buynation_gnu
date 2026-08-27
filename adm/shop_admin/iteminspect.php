<?php
$sub_menu = '400310';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);
include_once(G5_LIB_PATH . '/iteminfo.lib.php');
include_once(G5_LIB_PATH . '/donuts_item_inspection.lib.php');

if ($is_admin !== 'super') {
    auth_check_menu($auth, $sub_menu, 'r');
}

if (!donuts_item_inspection_table_exists()) {
    alert('상품검수 테이블이 없습니다. install.sql을 먼저 실행해 주세요.');
}

add_stylesheet('<link rel="stylesheet" href="' . G5_ADMIN_URL . '/shop_admin/css/platform.css?ver=' . G5_CSS_VER . '">', 100);

$g5['title'] = '상품검수';
include_once(G5_ADMIN_PATH . '/admin.head.php');

/*
 * 누락된 브랜드 상품 검수 row 복구.
 * - 모든 it_use=0 브랜드 상품
 * - 최근 7일 브랜드 신규상품
 */
donuts_item_inspection_sync_untracked_brand_products(7);

$status = isset($_GET['status']) ? trim($_GET['status']) : 'all';
$allowed_status = array('all', 'draft', 'pending', 'revision', 'approved', 'rejected');
if (!in_array($status, $allowed_status, true)) $status = 'all';

/*
 * 검수 누락 상품을 먼저 draft로 동기화합니다.
 * JOIN collation 비교는 공통 라이브러리에서 제거했습니다.
 */
donuts_item_inspection_sync_untracked_brand_products(30);

/*
 * 목록은 g5_shop_item을 기준으로 한 번만 조회한 뒤 PHP에서 상태를 분류합니다.
 * 이렇게 하면 상단 숫자와 실제 리스트가 서로 다른 SQL 조건을 쓰는 문제가 없습니다.
 */
$all_rows = array();

$base_result = sql_query("
    SELECT
        i.it_id,
        i.it_name,
        i.it_brand,
        i.it_price,
        i.it_use,
        i.it_time,
        i.it_img1,
        r.inspection_id,
        r.inspect_no,
        r.brand_id,
        r.request_type,
        r.status AS inspection_status,
        r.requested_at,
        r.updated_at,
        r.created_at,
        r.admin_message
    FROM {$g5['g5_shop_item_table']} i
    LEFT JOIN donuts_item_inspections r
      ON r.it_id COLLATE utf8mb4_general_ci = i.it_id COLLATE utf8mb4_general_ci
    WHERE r.inspection_id IS NOT NULL
       OR i.it_use = 0
    ORDER BY
        COALESCE(r.requested_at, r.updated_at, r.created_at, i.it_time) DESC,
        i.it_id DESC
", false);

if ($base_result) {
    while ($row = sql_fetch_array($base_result)) {
        /*
         * 검수 row가 없고 판매 비노출이면 임시저장.
         */
        if (empty($row['inspection_id'])) {
            $row['status'] = 'draft';
            $row['brand_id'] = $row['it_brand'];
            $row['inspect_no'] = '';
            $row['request_type'] = 'new';
        } else {
            $row['status'] = trim((string)$row['inspection_status']);

            if (empty($row['brand_id'])) {
                $row['brand_id'] = $row['it_brand'];
            }
        }

        /*
         * 승인 row가 있는데 현재 it_use=0인 특이 케이스도
         * 검수 상태 자체는 approved로 유지합니다.
         */
        if ($row['status'] === '') {
            $row['status'] = ((int)$row['it_use'] === 0) ? 'draft' : 'approved';
        }

        $all_rows[] = $row;
    }
}

$counts = array(
    'draft' => 0,
    'pending' => 0,
    'revision' => 0,
    'approved' => 0,
    'rejected' => 0
);

foreach ($all_rows as $tmp_row) {
    $tmp_status = $tmp_row['status'];

    if (isset($counts[$tmp_status])) {
        $counts[$tmp_status]++;
    }
}

$all_count = count($all_rows);

$rows = array();

foreach ($all_rows as $tmp_row) {
    if ($status === 'all' || $tmp_row['status'] === $status) {
        $rows[] = $tmp_row;
    }
}

$selected_it_id = isset($_GET['it_id'])
    ? preg_replace('/[^A-Za-z0-9_\-]/', '', $_GET['it_id'])
    : '';

$selected = array();
$it = array();
$review_fields = array();
$logs = array();

if ($selected_it_id !== '') {
    $selected = donuts_item_inspection_get($selected_it_id);

    if (!empty($selected['inspection_id'])) {
        $it = get_shop_item($selected_it_id);

        if (!empty($selected['review_fields'])) {
            $decoded = json_decode($selected['review_fields'], true);
            if (is_array($decoded)) $review_fields = $decoded;
        }

        $lr = sql_query("
            SELECT *
            FROM donuts_item_inspection_logs
            WHERE inspection_id = '" . (int)$selected['inspection_id'] . "'
            ORDER BY log_id DESC
            LIMIT 20
        ");
        while ($log = sql_fetch_array($lr)) {
            $logs[] = $log;
        }
    }
}

$token = get_admin_token();

function inspect_status_sub($status)
{
    if ($status === 'approved') return '판매중';
    if ($status === 'pending') return '판매 대기';
    if ($status === 'revision') return '보완 대기';
    if ($status === 'rejected') return '등록 반려';
    return '검수 요청 전';
}
?>

<section class="content" id="content">
    <div class="content-head">
        <div>
            <h2>상품 검수</h2>
            <p>브랜드 검수 요청 이후 플랫폼 승인 전에는 판매되지 않습니다.</p>
        </div>
    </div>

    <div id="inspection-status-summary" class="grid grid-cols-2 gap-3 pc:grid-cols-5">
        <a href="./iteminspect.php?status=draft" class="flex flex-col justify-between min-h-32 border rounded-xl text-xs text-gray-500 p-4 <?php echo $status === 'draft' ? 'border-gray-500 bg-gray-50' : 'border-gray-300 bg-white'; ?>">
            <span>임시저장</span>
            <span class="text-2xl font-black text-gray-900"><?php echo number_format($counts['draft']); ?>건</span>
            <span>검수 전 임시저장</span>
        </a>

        <a href="./iteminspect.php?status=pending" class="flex flex-col justify-between min-h-32 border rounded-xl text-xs text-gray-500 p-4 <?php echo $status === 'pending' ? 'border-gray-500 bg-gray-50' : 'border-gray-300 bg-white'; ?>">
            <span>검수</span>
            <span class="text-2xl font-black text-gray-900"><?php echo number_format($counts['pending']); ?>건</span>
            <span>플랫폼 심사중</span>
        </a>

        <a href="./iteminspect.php?status=revision" class="flex flex-col justify-between min-h-32 border rounded-xl text-xs text-gray-500 p-4 <?php echo $status === 'revision' ? 'border-gray-500 bg-gray-50' : 'border-gray-300 bg-white'; ?>">
            <span>보완 요청</span>
            <span class="text-2xl font-black text-gray-900"><?php echo number_format($counts['revision']); ?>건</span>
            <span>수정 후 재신청</span>
        </a>

        <a href="./iteminspect.php?status=rejected" class="flex flex-col justify-between min-h-32 border rounded-xl text-xs text-gray-500 p-4 <?php echo $status === 'rejected' ? 'border-gray-500 bg-gray-50' : 'border-gray-300 bg-white'; ?>">
            <span>거절</span>
            <span class="text-2xl font-black text-gray-900"><?php echo number_format($counts['rejected']); ?>건</span>
            <span>등록 불가</span>
        </a>

        <a href="./iteminspect.php?status=approved" class="flex flex-col justify-between min-h-32 border rounded-xl text-xs text-gray-500 p-4 <?php echo $status === 'approved' ? 'border-gray-500 bg-gray-50' : 'border-gray-300 bg-white'; ?>">
            <span>승인</span>
            <span class="text-2xl font-black text-gray-900"><?php echo number_format($counts['approved']); ?>건</span>
            <span>판매 최종 승인</span>
        </a>
    </div>

    <div class="status-rail" style="display:flex;gap:8px;flex-wrap:wrap;margin:16px 0;">
        <a
            class="btn <?php echo $status === 'all' ? 'primary' : ''; ?>"
            href="./iteminspect.php?status=all">
            전체 <?php echo number_format($all_count); ?>
        </a>

        <div role="search">
            <label for="inspection-search-input" class="sound_only">상품 검색</label>
            <input
                type="search"
                id="inspection-search-input"
                class="border border-[#ddd] rounded-lg p-3 bg-white"
                placeholder="상품 검색"
                autocomplete="off">
        </div>
    </div>

    <article class="card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>검수번호 / 상품</th>
                        <th>브랜드</th>
                        <th>구분</th>
                        <th>배송그룹</th>
                        <th>요청일</th>
                        <th>상태</th>
                        <th>검수</th>
                    </tr>
                </thead>
                <tbody id="inspection-list-body">
                    <?php if (!$rows) { ?>
                        <tr>
                            <td colspan="7" style="text-align:center;padding:32px;">
                                <?php
                                if ($status === 'draft') {
                                    echo '임시저장 상품이 없습니다.';
                                } elseif ($status === 'pending') {
                                    echo '검수 요청된 상품이 없습니다.';
                                } elseif ($status === 'approved') {
                                    echo '승인된 상품이 없습니다.';
                                } elseif ($status === 'revision') {
                                    echo '보완 요청 상품이 없습니다.';
                                } elseif ($status === 'rejected') {
                                    echo '거절된 상품이 없습니다.';
                                } else {
                                    echo '검수 상품이 없습니다.';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>

                    <?php foreach ($rows as $row) {
                        $row_brand_id = !empty($row['brand_id']) ? $row['brand_id'] : $row['it_brand'];
                        $delivery_label = donuts_item_inspection_delivery_label($row_brand_id, $row['it_id']);
                        $badge = donuts_item_inspection_status_badge($row['status']);
                    ?>
                        <tr class="js-inspection-search-row">
                            <td class="name">
                                <?php echo get_text($row['it_name'] ?: '(삭제된 상품)'); ?>
                                <span class="sub">
                                    <?php echo get_text($row['inspect_no']); ?> ·
                                    <?php echo get_text($row['it_id']); ?>
                                </span>
                            </td>
                            <td><?php echo get_text($row['brand_id']); ?></td>
                            <td><?php echo $row['request_type'] === 'update' ? '정보 변경' : '신규'; ?></td>
                            <td><?php echo get_text($delivery_label); ?></td>
                            <td><?php echo $row['requested_at'] ? get_text($row['requested_at']) : '-'; ?></td>
                            <td>
                                <span class="badge <?php echo $badge; ?>">
                                    <?php echo get_text(donuts_item_inspection_status_label($row['status'])); ?>
                                </span>
                                <span class="sub"><?php echo get_text(inspect_status_sub($row['status'])); ?></span>
                            </td>
                            <td>
                                <a class="btn small" href="./iteminspect.php?status=<?php echo urlencode($status); ?>&amp;it_id=<?php echo urlencode($row['it_id']); ?>">검수</a>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr id="inspection-search-empty" hidden>
                        <td colspan="7" class="text-center text-xs text-gray-500">
                            검색 결과가 없습니다.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </article>
</section>

<?php if (!empty($selected['inspection_id']) && !empty($it)) { ?>
    <div id="modal-root">
        <div class="modal-backdrop" data-action="close-modal">
            <section class="modal">
                <div class="modal-head">
                    <h3>상품 검수</h3>
                    <a href="./iteminspect.php?status=<?php echo urlencode($status); ?>">×</a>
                </div>

                <div class="modal-body">
                    <span class="eyebrow"><?php echo get_text($selected['inspect_no']); ?></span>
                    <h3 style="margin:6px 0 14px"><?php echo get_text($it['it_name']); ?></h3>

                    <button type="button" id="inspect-image-toggle" class="mb-4 flex w-40 aspect-square items-center justify-center overflow-hidden rounded-xl border border-gray-200 bg-gray-50 transition-all duration-400">
                        <?php echo get_it_image($it['it_id'], 800, 800, false, 'inspect-item-image', ' alt="' . get_text($it['it_name']) . '"', true); ?>
                    </button>

                    <dl class="detail text-xs">
                        <dt>상품 ID</dt>
                        <dd><?php echo get_text($it['it_id']); ?></dd>
                        <dt>브랜드</dt>
                        <dd><?php echo get_text($selected['brand_id']); ?></dd>
                        <dt>판매가</dt>
                        <dd><?php echo number_format((int)$it['it_price']); ?>원</dd>
                        <dt>요청 유형</dt>
                        <dd><?php echo $selected['request_type'] === 'update' ? '정보 변경' : '신규'; ?></dd>
                        <dt>배송그룹</dt>
                        <dd><?php echo get_text(donuts_item_inspection_delivery_label($selected['brand_id'], $it['it_id'])); ?></dd>
                        <dt>판매 상태</dt>
                        <dd><?php echo $it['it_use'] ? '판매중' : '판매 대기'; ?></dd>
                        <dt>검수 상태</dt>
                        <dd><?php echo get_text(donuts_item_inspection_status_label($selected['status'])); ?></dd>
                        <dt>요청일</dt>
                        <dd><?php echo $selected['requested_at'] ?: '-'; ?></dd>
                    </dl>

                    <?php if ($selected['brand_message']) { ?>
                        <div class="warning text-xs">
                            <b>브랜드 요청 메모</b><br>
                            <?php echo nl2br(get_text($selected['brand_message'])); ?>
                        </div>
                    <?php } ?>

                    <form id="inspection-decision-form" method="post" action="./iteminspect_update.php">
                        <input type="hidden" name="token" value="<?php echo get_text($token); ?>">
                        <input type="hidden" name="it_id" value="<?php echo get_text($it['it_id']); ?>">
                        <input type="hidden" name="decision" id="inspection-decision" value="">

                        <section>
                            <h2 class="h2_frm">상품 정보</h2>
                            <div class="tbl_frm01 tbl_wrap text-xs border border-gray-200 rounded-xl overflow-hidden mt-4">
                                <table>
                                    <colgroup>
                                        <col class="grid_4">
                                        <col>
                                        <col class="grid_3">
                                    </colgroup>
                                    <tbody>
                                        <?php
                                        $field_rows = array(
                                            'ca_id' => array('기본분류', function () use ($it) {
                                                return get_shop_category_path($it['ca_id'], ' > ');
                                            }),
                                            'it_name' => array('상품명', function () use ($it) {
                                                return $it['it_name'];
                                            }),
                                            'it_basic' => array('기본설명', function () use ($it) {
                                                return $it['it_basic'];
                                            }),
                                            'it_brand' => array('브랜드', function () use ($it) {
                                                return $it['it_brand'];
                                            }),
                                            'it_model' => array('모델', function () use ($it) {
                                                return $it['it_model'];
                                            }),
                                            'it_price' => array('판매가격', function () use ($it) {
                                                return number_format((int)$it['it_price']) . '원';
                                            }),
                                            'it_stock_qty' => array('재고수량', function () use ($it) {
                                                return number_format((int)$it['it_stock_qty']) . '개';
                                            }),
                                            'it_explan' => array('상품설명', function () use ($it) {
                                                return $it['it_explan'];
                                            }),
                                        );
                                        foreach ($field_rows as $field => $meta) {
                                            $checked = in_array($field, $review_fields, true);
                                        ?>
                                            <tr>
                                                <th><?php echo get_text($meta[0]); ?></th>
                                                <td>
                                                    <?php if ($field === 'it_explan') { ?>
                                                        <div class="max-w-full overflow-x-auto break-words [&_img]:h-auto [&_img]:max-w-full">
                                                            <?php echo conv_content((string) $meta[1](), 1); ?>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class="block whitespace-pre-wrap text-gray-900"><?php echo nl2br(get_text((string) $meta[1]())); ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-center">
                                                    <label>
                                                        <input type="checkbox" name="review_fields[]" value="<?php echo get_text($field); ?>" <?php echo $checked ? 'checked' : ''; ?>>
                                                        <span>보완 요청</span>
                                                    </label>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </section>

                        <section style="margin-top:18px;">
                            <h2 class="h2_frm">플랫폼 검수 의견</h2>
                            <textarea name="admin_message" rows="5" style="width:100%;" placeholder="보완 요청 또는 거절 사유를 입력하세요."><?php echo get_text($selected['admin_message']); ?></textarea>
                        </section>
                    </form>

                    <?php if ($logs) { ?>
                        <section style="margin-top:18px;">
                            <h2 class="h2_frm">처리 이력</h2>
                            <div class="timeline">
                                <?php foreach ($logs as $log) { ?>
                                    <div class="timeline-item">
                                        <b><?php echo get_text(donuts_item_inspection_status_label($log['to_status'])); ?></b>
                                        <small>
                                            <?php echo get_text($log['created_at']); ?> ·
                                            <?php echo get_text($log['action_by']); ?>
                                            <?php if ($log['message']) { ?> · <?php echo get_text($log['message']); ?><?php } ?>
                                        </small>
                                    </div>
                                <?php } ?>
                            </div>
                        </section>
                    <?php } ?>
                </div>

                <div class="modal-foot">
                    <a class="btn" href="./iteminspect.php?status=<?php echo urlencode($status); ?>">닫기</a>

                    <?php if ($selected['status'] === 'pending') { ?>
                        <button type="button" class="btn danger" data-decision="rejected">거절</button>
                        <button type="button" class="btn danger" data-decision="revision">보완 요청</button>
                        <button type="button" class="btn primary" data-decision="approved">승인·판매</button>
                    <?php } ?>
                </div>
            </section>
        </div>
    </div>
<?php } ?>

<script>
    $(function() {
        $('#modal-root .modal').on('click', function(e) {
            e.stopPropagation();
        });

        $('#modal-root [data-action="close-modal"]').on('click', function() {
            location.href = './iteminspect.php?status=<?php echo addslashes($status); ?>';
        });

        $('#inspect-image-toggle').on('click', function() {
            $(this).toggleClass('w-40 w-200');
        });

        $('[data-decision]').on('click', function() {
            var decision = $(this).data('decision');
            var message = $.trim($('textarea[name="admin_message"]').val());

            if (decision === 'revision') {
                var checked = $('input[name="review_fields[]"]:checked').length;
                if (!checked && !message) {
                    alert('보완 요청 항목 또는 보완 내용을 입력해 주세요.');
                    return;
                }
            }

            var confirmText = decision === 'approved' ?
                '이 상품을 승인하고 쇼핑몰 판매를 활성화하시겠습니까?' :
                (decision === 'revision' ? '보완 요청 처리하시겠습니까?' : '상품 검수를 거절하시겠습니까?');

            if (!confirm(confirmText)) return;

            $('#inspection-decision').val(decision);
            $('#inspection-decision-form').submit();
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

<?php include_once(G5_ADMIN_PATH . '/admin.tail.php'); ?>
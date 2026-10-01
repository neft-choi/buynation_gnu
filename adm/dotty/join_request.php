<?php
$sub_menu = '730300';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '가입 신청 관리';

function jr_e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function jr_json($v) {
    return json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

$dotty_mb_id = trim((string)$member['mb_id']);
if ($is_admin === 'super' && !empty($_REQUEST['mb_id'])) {
    $dotty_mb_id = trim((string)$_REQUEST['mb_id']);
}
if ($dotty_mb_id === '') {
    alert('도넛 관리 계정을 확인할 수 없습니다.');
}
$dotty_mb_id_sql = sql_real_escape_string($dotty_mb_id);

$required_tables = array(
    'donuts_dotty_join_requests',
    'donuts_dotty_join_request_answers',
    'donuts_dotty_members'
);
foreach ($required_tables as $table) {
    $table_sql = sql_real_escape_string($table);
    $ck = sql_fetch("SELECT COUNT(*) AS cnt FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='{$table_sql}'");
    if (empty($ck['cnt'])) {
        alert('가입 신청 관리 DB 마이그레이션이 필요합니다. migration_join_request.sql을 먼저 실행해 주세요.');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_admin_token();

    $action = isset($_POST['action']) ? trim((string)$_POST['action']) : '';
    $request_id = isset($_POST['request_id']) ? (int)$_POST['request_id'] : 0;

    if ($request_id <= 0) {
        alert('가입 신청번호가 올바르지 않습니다.');
    }

    $request = sql_fetch("
        SELECT *
          FROM donuts_dotty_join_requests
         WHERE request_id = '{$request_id}'
           AND dotty_mb_id = '{$dotty_mb_id_sql}'
         LIMIT 1
    ");

    if (empty($request['request_id'])) {
        alert('가입 신청을 찾을 수 없거나 처리 권한이 없습니다.');
    }

    if ($action === 'approve') {
        if ($request['status'] !== 'pending') {
            alert('이미 처리된 가입 신청입니다.');
        }

        $applicant_mb_id = trim((string)$request['applicant_mb_id']);
        $applicant_mb_id_sql = sql_real_escape_string($applicant_mb_id);

        sql_query("
            INSERT INTO donuts_dotty_members
                (dotty_mb_id, mb_id, member_status, joined_at, created_at, updated_at)
            VALUES
                ('{$dotty_mb_id_sql}', '{$applicant_mb_id_sql}', 'active', NOW(), NOW(), NOW())
            ON DUPLICATE KEY UPDATE
                member_status = 'active',
                joined_at = NOW(),
                left_at = NULL,
                updated_at = NOW()
        ");

        $processor_sql = sql_real_escape_string((string)$member['mb_id']);
        sql_query("
            UPDATE donuts_dotty_join_requests
               SET status = 'approved',
                   processed_by = '{$processor_sql}',
                   processed_at = NOW(),
                   reject_category = '',
                   reject_reason = '',
                   updated_at = NOW()
             WHERE request_id = '{$request_id}'
               AND dotty_mb_id = '{$dotty_mb_id_sql}'
               AND status = 'pending'
        ");

        alert('가입 신청을 승인했습니다.', './join_request.php?status=pending&mb_id='.urlencode($dotty_mb_id));
    }

    if ($action === 'reject') {
        if ($request['status'] !== 'pending') {
            alert('이미 처리된 가입 신청입니다.');
        }

        $reject_category = isset($_POST['reject_category']) ? trim((string)$_POST['reject_category']) : '';
        $reject_reason = isset($_POST['reject_reason']) ? trim((string)$_POST['reject_reason']) : '';

        if ($reject_category === '') {
            alert('거절 사유 카테고리를 선택해 주세요.');
        }
        if ($reject_reason === '') {
            alert('상세 거절 사유를 입력해 주세요.');
        }

        $category_sql = sql_real_escape_string($reject_category);
        $reason_sql = sql_real_escape_string($reject_reason);
        $processor_sql = sql_real_escape_string((string)$member['mb_id']);

        sql_query("
            UPDATE donuts_dotty_join_requests
               SET status = 'rejected',
                   processed_by = '{$processor_sql}',
                   processed_at = NOW(),
                   reject_category = '{$category_sql}',
                   reject_reason = '{$reason_sql}',
                   updated_at = NOW()
             WHERE request_id = '{$request_id}'
               AND dotty_mb_id = '{$dotty_mb_id_sql}'
               AND status = 'pending'
        ");

        alert('가입 신청을 거절했습니다.', './join_request.php?status=rejected&mb_id='.urlencode($dotty_mb_id));
    }

    alert('올바르지 않은 처리 요청입니다.');
}

$status = isset($_GET['status']) ? trim((string)$_GET['status']) : 'all';
if (!in_array($status, array('all', 'pending', 'rejected', 'approved'), true)) {
    $status = 'all';
}
$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';

$where = array("r.dotty_mb_id = '{$dotty_mb_id_sql}'");
if ($status !== 'all') {
    $status_sql = sql_real_escape_string($status);
    $where[] = "r.status = '{$status_sql}'";
}
if ($q !== '') {
    $q_sql = sql_real_escape_string($q);
    $where[] = "(
        r.request_no LIKE '%{$q_sql}%'
        OR r.applicant_mb_id LIKE '%{$q_sql}%'
        OR m.mb_name LIKE '%{$q_sql}%'
        OR m.mb_nick LIKE '%{$q_sql}%'
    )";
}
$sql_where = implode(' AND ', $where);

$stats = sql_fetch("
    SELECT
        SUM(CASE WHEN status='pending' THEN 1 ELSE 0 END) AS pending_count,
        SUM(CASE WHEN status='approved' AND DATE(processed_at)=CURDATE() THEN 1 ELSE 0 END) AS today_approved,
        SUM(CASE WHEN status='rejected' AND DATE(processed_at)=CURDATE() THEN 1 ELSE 0 END) AS today_rejected
    FROM donuts_dotty_join_requests
    WHERE dotty_mb_id = '{$dotty_mb_id_sql}'
");
$pending_count = (int)$stats['pending_count'];
$today_approved = (int)$stats['today_approved'];
$today_rejected = (int)$stats['today_rejected'];
$today_processed = $today_approved + $today_rejected;
$approval_rate = $today_processed > 0 ? round(($today_approved / $today_processed) * 100, 1) : 0;

$count_row = sql_fetch("
    SELECT COUNT(*) AS cnt
      FROM donuts_dotty_join_requests r
      LEFT JOIN {$g5['member_table']} m ON m.mb_id = r.applicant_mb_id
     WHERE {$sql_where}
");
$total_count = (int)$count_row['cnt'];

$rows = array();
$res = sql_query("
    SELECT r.*, m.mb_name, m.mb_nick
      FROM donuts_dotty_join_requests r
      LEFT JOIN {$g5['member_table']} m ON m.mb_id = r.applicant_mb_id
     WHERE {$sql_where}
     ORDER BY CASE WHEN r.status='pending' THEN 0 ELSE 1 END,
              r.created_at DESC,
              r.request_id DESC
     LIMIT 200
");
while ($row = sql_fetch_array($res)) {
    $answers = array();
    $ares = sql_query("
        SELECT question_text, answer_text, sort_order
          FROM donuts_dotty_join_request_answers
         WHERE request_id = '".(int)$row['request_id']."'
         ORDER BY sort_order ASC, answer_id ASC
    ");
    while ($a = sql_fetch_array($ares)) {
        $answers[] = array(
            'question' => (string)$a['question_text'],
            'answer' => (string)$a['answer_text']
        );
    }

    $row['answers_json'] = jr_json($answers);
    $rows[] = $row;
}

$admin_token = get_admin_token();
require_once '../admin.head.php';

function jr_status_label($status) {
    if ($status === 'approved') return '가입 승인';
    if ($status === 'rejected') return '승인 거절';
    return '승인 대기';
}
function jr_status_class($status) {
    if ($status === 'approved') return 'bg-emerald-100 text-emerald-700';
    if ($status === 'rejected') return 'bg-red-100 text-red-600';
    return 'bg-amber-100 text-amber-700';
}
function jr_notice($row) {
    if ($row['status'] === 'pending') {
        $created = strtotime($row['created_at']);
        $seconds = max(0, time() - $created);
        if ($seconds < 3600) return '대기 '.max(1, (int)ceil($seconds / 60)).'분';
        if ($seconds < 86400) return '대기 '.(int)floor($seconds / 3600).'시간';
        return '대기 '.(int)floor($seconds / 86400).'일';
    }
    if ($row['status'] === 'rejected') return '재신청 가능';
    return !empty($row['processed_at']) ? date('Y.m.d H:i', strtotime($row['processed_at'])).' 승인' : '승인 완료';
}
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="mt-1 text-gray-400">신청 답변을 확인한 뒤 승인하거나 거절할 수 있습니다.</p>
        <button type="button" id="join-request-policy-modal-open" class="shrink-0 border border-gray-300 rounded-lg bg-white px-3 py-2 text-gray-900 font-bold">재신청 정책 보기</button>
    </div>

    <section>
        <h3 class="sound_only">가입 신청 현황</h3>
        <div class="mt-4 grid grid-cols-1 gap-4 pc:grid-cols-4">
            <div class="rounded-lg border border-gray-300 bg-white p-4">
                <p class="text-xs text-gray-500">승인 대기</p>
                <p class="mt-3 text-2xl font-bold text-gray-900"><?php echo number_format($pending_count); ?><span class="ml-1 text-base">건</span></p>
                <span class="mt-3 block text-2xs text-amber-600">검토가 필요합니다.</span>
            </div>
            <div class="rounded-lg border border-gray-300 bg-white p-4">
                <p class="text-xs text-gray-500">오늘 승인</p>
                <p class="mt-3 text-2xl font-bold text-gray-900"><?php echo number_format($today_approved); ?><span class="ml-1 text-base">건</span></p>
                <span class="mt-3 block text-2xs text-emerald-600">처리 결과 즉시 반영</span>
            </div>
            <div class="rounded-lg border border-gray-300 bg-white p-4">
                <p class="text-xs text-gray-500">오늘 거절</p>
                <p class="mt-3 text-2xl font-bold text-gray-900"><?php echo number_format($today_rejected); ?><span class="ml-1 text-base">건</span></p>
                <span class="mt-3 block text-2xs text-red-600">사유 입력 완료</span>
            </div>
            <div class="rounded-lg border border-gray-300 bg-white p-4">
                <p class="text-xs text-gray-500">오늘 승인율</p>
                <p class="mt-3 text-2xl font-bold text-gray-900"><?php echo number_format($approval_rate, 1); ?><span class="ml-1 text-base">%</span></p>
                <span class="mt-3 block text-2xs text-blue-600">승인 ÷ 전체 처리</span>
            </div>
        </div>
    </section>

    <section class="mt-4">
        <h3 class="sound_only">가입 신청 목록</h3>
        <form id="join-request-search-form" method="get" class="flex flex-col gap-3 pc:flex-row pc:items-center">
            <input type="hidden" name="mb_id" value="<?php echo jr_e($dotty_mb_id); ?>">
            <div class="flex shrink-0 rounded-lg bg-gray-100 p-1">
                <?php
                $tabs = array('all'=>'전체', 'pending'=>'승인 대기 '.$pending_count, 'rejected'=>'승인 거절', 'approved'=>'가입 승인');
                foreach ($tabs as $key => $label) {
                    $active = $status === $key;
                ?>
                <button type="submit" name="status" value="<?php echo jr_e($key); ?>" aria-pressed="<?php echo $active ? 'true' : 'false'; ?>" class="rounded-md px-3 py-2 text-xs font-bold <?php echo $active ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500'; ?>"><?php echo jr_e($label); ?></button>
                <?php } ?>
            </div>

            <div class="flex min-w-0 flex-1 items-center rounded-lg border border-gray-300 bg-white">
                <label for="join-request-search" class="sound_only">신청자명 또는 신청번호 검색</label>
                <input type="search" id="join-request-search" name="q" value="<?php echo jr_e($q); ?>" class="min-w-0 flex-1 rounded-lg px-3 py-2 text-sm outline-none" placeholder="신청자명 또는 신청번호 검색">
                <button type="submit" aria-label="가입 신청 검색" class="shrink-0 px-3 py-2 text-gray-900">검색</button>
            </div>
            <p class="shrink-0 text-2xs text-gray-500">검색 결과 <?php echo number_format($total_count); ?>건</p>
        </form>

        <div class="mt-4 overflow-x-auto rounded-lg border border-gray-300 bg-white">
            <table class="border-collapse w-full min-w-225 text-left text-xs text-gray-900">
                <caption class="sound_only">가입 신청 목록</caption>
                <colgroup>
                    <col class="w-[14%]"><col class="w-[18%]"><col class="w-[18%]"><col class="w-[14%]"><col class="w-[22%]"><col class="w-[14%]">
                </colgroup>
                <thead class="border-b border-gray-300 bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-3 py-3 font-bold">신청자</th>
                        <th class="px-3 py-3 font-bold">신청번호</th>
                        <th class="px-3 py-3 font-bold">신청일</th>
                        <th class="px-3 py-3 font-bold">처리 상태</th>
                        <th class="px-3 py-3 font-bold">안내</th>
                        <th class="px-3 py-3 text-center font-bold">검토</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!$rows) { ?>
                    <tr><td colspan="6" class="p-6 text-center text-xs text-gray-500">검색 결과가 없습니다.</td></tr>
                <?php } ?>
                <?php foreach ($rows as $row) {
                    $nick = $row['mb_nick'] !== '' ? $row['mb_nick'] : $row['applicant_mb_id'];
                    $name = $row['mb_name'] !== '' ? $row['mb_name'] : $row['applicant_mb_id'];
                ?>
                    <tr class="border-b border-gray-200">
                        <td class="px-3 py-3"><p class="font-bold"><?php echo jr_e($nick); ?></p><span class="mt-1 block text-2xs text-gray-400"><?php echo jr_e($name); ?></span></td>
                        <td class="px-3 py-3"><?php echo jr_e($row['request_no']); ?></td>
                        <td class="px-3 py-3"><?php echo jr_e(date('Y.m.d H:i', strtotime($row['created_at']))); ?></td>
                        <td class="px-3 py-3"><span class="rounded-full px-2 py-1 text-2xs font-bold <?php echo jr_status_class($row['status']); ?>">● <?php echo jr_e(jr_status_label($row['status'])); ?></span></td>
                        <td class="px-3 py-3"><?php echo jr_e(jr_notice($row)); ?></td>
                        <td class="px-3 py-3 text-center">
                            <button type="button"
                                class="join-request-modal-open rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900"
                                data-request-id="<?php echo (int)$row['request_id']; ?>"
                                data-applicant="<?php echo jr_e($nick.' ('.$name.')'); ?>"
                                data-request-no="<?php echo jr_e($row['request_no']); ?>"
                                data-created-at="<?php echo jr_e(date('Y.m.d H:i', strtotime($row['created_at']))); ?>"
                                data-status="<?php echo jr_e($row['status']); ?>"
                                data-status-label="<?php echo jr_e(jr_status_label($row['status'])); ?>"
                                data-reject-category="<?php echo jr_e($row['reject_category']); ?>"
                                data-reject-reason="<?php echo jr_e($row['reject_reason']); ?>"
                                data-answers="<?php echo jr_e($row['answers_json']); ?>">
                                <?php echo $row['status'] === 'pending' ? '신청 검토' : ($row['status'] === 'rejected' ? '거절 사유' : '상세 보기'); ?>
                            </button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</section>

<div id="join-request-policy-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="join-request-policy-modal-backdrop" class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 w-full max-w-160 overflow-auto rounded-lg bg-white">
        <div class="flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 class="text-base font-bold text-gray-900">가입 재신청 정책</h3>
            <button type="button" class="join-request-policy-modal-close flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">×</button>
        </div>
        <div class="space-y-3 p-4">
            <div class="rounded-lg bg-gray-100 p-3"><p class="text-2xs text-gray-400">승인 거절</p><p class="mt-1 text-xs text-gray-700">신청자에게 선택한 거절 사유 카테고리와 상세 사유를 표시합니다.</p></div>
            <div class="rounded-lg bg-gray-100 p-3"><p class="text-2xs text-gray-400">거절 후 재신청</p><p class="mt-1 text-xs text-gray-700">대기 기간 없이 즉시 다시 신청할 수 있습니다.</p></div>
            <div class="rounded-lg bg-gray-100 p-3"><p class="text-2xs text-gray-400">도넛 탈퇴 후 재가입</p><p class="mt-1 text-xs text-gray-700">탈퇴 후 72시간이 지난 뒤 재가입할 수 있습니다.</p></div>
        </div>
        <div class="flex justify-end border-t border-gray-300 bg-white p-4"><button type="button" class="join-request-policy-modal-close rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-bold">닫기</button></div>
    </div>
</div>

<div id="join-request-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="join-request-modal-backdrop" class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 max-h-[90vh] w-full max-w-160 overflow-y-auto rounded-lg bg-white">
        <div class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 class="text-base font-bold text-gray-900">가입 신청 상세</h3>
            <button type="button" class="join-request-modal-close flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">×</button>
        </div>

        <div class="p-4">
            <div class="overflow-hidden rounded-lg border border-gray-300">
                <dl class="text-xs text-gray-900">
                    <div class="flex border-b border-gray-300"><dt class="w-32 shrink-0 bg-gray-50 p-3 text-gray-500">신청자</dt><dd id="jr-applicant" class="flex-1 p-3 font-bold"></dd></div>
                    <div class="flex border-b border-gray-300"><dt class="w-32 shrink-0 bg-gray-50 p-3 text-gray-500">신청번호</dt><dd id="jr-number" class="flex-1 p-3 font-bold"></dd></div>
                    <div class="flex border-b border-gray-300"><dt class="w-32 shrink-0 bg-gray-50 p-3 text-gray-500">신청일</dt><dd id="jr-date" class="flex-1 p-3 font-bold"></dd></div>
                    <div class="flex"><dt class="w-32 shrink-0 bg-gray-50 p-3 text-gray-500">상태</dt><dd id="jr-status" class="flex-1 p-3 font-bold"></dd></div>
                </dl>
            </div>

            <div id="jr-answers" class="mt-4 space-y-3"></div>

            <div id="jr-rejected-box" class="mt-4 rounded-lg bg-red-50 p-3 text-xs text-red-700" hidden>
                <span class="block font-bold">거절 사유</span>
                <p id="jr-reject-category" class="mt-2 font-bold"></p>
                <p id="jr-reject-reason" class="mt-1"></p>
            </div>

            <form id="jr-reject-form" method="post" class="mt-4 rounded-lg border border-red-200 bg-red-50 p-3" hidden>
                <input type="hidden" name="token" value="<?php echo jr_e($admin_token); ?>">
                <input type="hidden" name="mb_id" value="<?php echo jr_e($dotty_mb_id); ?>">
                <input type="hidden" name="action" value="reject">
                <input type="hidden" name="request_id" class="jr-request-id" value="">
                <label class="block text-xs font-bold text-red-700">거절 사유 카테고리</label>
                <select name="reject_category" class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs" required>
                    <option value="">선택해 주세요</option>
                    <option value="운영 기준 미충족">운영 기준 미충족</option>
                    <option value="홍보·광고 목적">홍보·광고 목적</option>
                    <option value="신청 정보 부족">신청 정보 부족</option>
                    <option value="기타">기타</option>
                </select>
                <label class="mt-3 block text-xs font-bold text-red-700">상세 사유</label>
                <textarea name="reject_reason" rows="4" class="mt-2 w-full rounded-lg border border-gray-300 bg-white p-3 text-xs" required></textarea>
                <div class="mt-3 flex justify-end gap-2">
                    <button type="button" id="jr-reject-cancel" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-bold">취소</button>
                    <button type="submit" class="rounded-lg bg-red-600 px-3 py-2 text-xs font-bold text-white">거절 확정</button>
                </div>
            </form>
        </div>

        <div class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="jr-reject-open" class="rounded-lg border border-red-300 bg-white px-3 py-2 text-sm font-bold text-red-600">가입 거절</button>
            <form id="jr-approve-form" method="post">
                <input type="hidden" name="token" value="<?php echo jr_e($admin_token); ?>">
                <input type="hidden" name="mb_id" value="<?php echo jr_e($dotty_mb_id); ?>">
                <input type="hidden" name="action" value="approve">
                <input type="hidden" name="request_id" class="jr-request-id" value="">
                <button type="submit" class="rounded-lg bg-amber-300 px-3 py-2 text-sm font-bold text-gray-900">가입 승인</button>
            </form>
            <button type="button" class="join-request-modal-close rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-bold text-gray-900">닫기</button>
        </div>
    </div>
</div>

<script>
const $policyModal = $('#join-request-policy-modal');
$('#join-request-policy-modal-open').on('click', function(){ $policyModal.prop('hidden', false); });
$('.join-request-policy-modal-close, #join-request-policy-modal-backdrop').on('click', function(){ $policyModal.prop('hidden', true); });

const $requestModal = $('#join-request-modal');

$('.join-request-modal-open').on('click', function() {
    const $btn = $(this);
    const requestId = $btn.data('request-id');
    const status = String($btn.data('status') || '');
    let answers = [];

    try {
        answers = JSON.parse($btn.attr('data-answers') || '[]');
    } catch (e) {
        answers = [];
    }

    $('.jr-request-id').val(requestId);
    $('#jr-applicant').text($btn.attr('data-applicant') || '');
    $('#jr-number').text($btn.attr('data-request-no') || '');
    $('#jr-date').text($btn.attr('data-created-at') || '');
    $('#jr-status').text($btn.attr('data-status-label') || '');

    const $answers = $('#jr-answers').empty();
    if (!answers.length) {
        $answers.append('<div class="rounded-lg bg-gray-100 p-3 text-xs text-gray-500">등록된 가입 질문 답변이 없습니다.</div>');
    } else {
        answers.forEach(function(item, index) {
            const $box = $('<div class="rounded-lg bg-gray-100 p-3"></div>');
            $('<span class="block text-2xs text-gray-400"></span>').text('Q' + (index + 1) + '. ' + (item.question || '가입 질문')).appendTo($box);
            $('<p class="mt-2 text-xs text-gray-900 whitespace-pre-wrap"></p>').text(item.answer || '').appendTo($box);
            $answers.append($box);
        });
    }

    const rejected = status === 'rejected';
    $('#jr-rejected-box').prop('hidden', !rejected);
    $('#jr-reject-category').text($btn.attr('data-reject-category') || '');
    $('#jr-reject-reason').text($btn.attr('data-reject-reason') || '');

    const pending = status === 'pending';
    $('#jr-reject-open, #jr-approve-form').prop('hidden', !pending);
    $('#jr-reject-form').prop('hidden', true);
    $requestModal.prop('hidden', false);
});

$('.join-request-modal-close, #join-request-modal-backdrop').on('click', function(){
    $requestModal.prop('hidden', true);
    $('#jr-reject-form').prop('hidden', true);
});

$('#jr-reject-open').on('click', function(){
    $('#jr-reject-form').prop('hidden', false);
    $(this).prop('hidden', true);
});

$('#jr-reject-cancel').on('click', function(){
    $('#jr-reject-form').prop('hidden', true);
    $('#jr-reject-open').prop('hidden', false);
});

$('#jr-approve-form').on('submit', function(e){
    if (!confirm('이 가입 신청을 승인하시겠습니까?')) e.preventDefault();
});
$('#jr-reject-form').on('submit', function(e){
    if (!confirm('이 가입 신청을 거절하시겠습니까?')) e.preventDefault();
});
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

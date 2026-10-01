<?php
$sub_menu = '730400';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '가입 도트';

function ma_e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

$dotty_mb_id = trim((string)$member['mb_id']);
if ($is_admin === 'super' && !empty($_REQUEST['mb_id'])) {
    $dotty_mb_id = trim((string)$_REQUEST['mb_id']);
}
if ($dotty_mb_id === '') {
    alert('도넛 관리 계정을 확인할 수 없습니다.');
}
$dotty_mb_id_sql = sql_real_escape_string($dotty_mb_id);

$check = sql_fetch("
    SELECT COUNT(*) AS cnt
    FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'donuts_dotty_members'
");
if (empty($check['cnt'])) {
    alert('가입 도트 DB 마이그레이션이 필요합니다. migration_member_activity.sql을 먼저 실행해 주세요.');
}

/* 기존 join_request 기능에서 생성한 테이블도 ALTER 누락 여부까지 확인 */
$required_columns = array('dot_id','role_type','member_status','restricted_reason','last_active_at','post_count','comment_count');
foreach ($required_columns as $col) {
    $col_sql = sql_real_escape_string($col);
    $ck = sql_fetch("
        SELECT COUNT(*) AS cnt
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'donuts_dotty_members'
          AND COLUMN_NAME = '{$col_sql}'
    ");
    if (empty($ck['cnt'])) {
        alert('가입 도트 DB 컬럼 확장이 필요합니다. migration_member_activity.sql을 먼저 실행해 주세요.');
    }
}

/* CSV 내려받기 */
if (isset($_GET['download']) && $_GET['download'] === 'csv') {
    auth_check_menu($auth, $sub_menu, 'r');

    $filename = 'dot_members_'.date('Ymd_His').'.csv';
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    echo "\xEF\xBB\xBF";

    $fp = fopen('php://output', 'w');
    fputcsv($fp, array('닉네임','이름','도트 ID','회원 ID','가입일','작성 글','작성 댓글','권한','상태','최근 활동'));

    $csv = sql_query("
        SELECT d.*, m.mb_name, m.mb_nick
        FROM donuts_dotty_members d
        LEFT JOIN {$g5['member_table']} m ON m.mb_id = d.mb_id
        WHERE d.dotty_mb_id = '{$dotty_mb_id_sql}'
        ORDER BY d.joined_at DESC, d.id DESC
    ");
    while ($r = sql_fetch_array($csv)) {
        $role = $r['role_type'] === 'operator' ? '지정 운영자' : '도트';
        $status_label = $r['member_status'] === 'restricted' ? '활동 제한' : ($r['member_status'] === 'withdrawn' ? '탈퇴' : '정상');
        fputcsv($fp, array(
            $r['mb_nick'], $r['mb_name'], $r['dot_id'], $r['mb_id'],
            $r['joined_at'], (int)$r['post_count'], (int)$r['comment_count'],
            $role, $status_label, $r['last_active_at']
        ));
    }
    fclose($fp);
    exit;
}

/* 상태/권한 변경 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_admin_token();

    $action = isset($_POST['action']) ? trim((string)$_POST['action']) : '';
    $member_id = isset($_POST['member_id']) ? (int)$_POST['member_id'] : 0;

    $target = sql_fetch("
        SELECT *
        FROM donuts_dotty_members
        WHERE id = '{$member_id}'
          AND dotty_mb_id = '{$dotty_mb_id_sql}'
        LIMIT 1
    ");
    if (empty($target['id'])) {
        alert('가입 도트를 찾을 수 없거나 처리 권한이 없습니다.');
    }

    if ($action === 'restrict') {
        $reason = isset($_POST['restricted_reason']) ? trim((string)$_POST['restricted_reason']) : '';
        if ($reason === '') alert('활동 제한 사유를 입력해 주세요.');
        $reason_sql = sql_real_escape_string($reason);

        sql_query("
            UPDATE donuts_dotty_members
            SET member_status='restricted',
                restricted_reason='{$reason_sql}',
                restricted_at=NOW(),
                updated_at=NOW()
            WHERE id='{$member_id}'
              AND dotty_mb_id='{$dotty_mb_id_sql}'
        ");
        alert('활동 제한 처리했습니다.', './member_activity.php?mb_id='.urlencode($dotty_mb_id));
    }

    if ($action === 'restore') {
        sql_query("
            UPDATE donuts_dotty_members
            SET member_status='active',
                restricted_reason='',
                restricted_at=NULL,
                updated_at=NOW()
            WHERE id='{$member_id}'
              AND dotty_mb_id='{$dotty_mb_id_sql}'
        ");
        alert('활동 제한을 해제했습니다.', './member_activity.php?mb_id='.urlencode($dotty_mb_id));
    }

    if ($action === 'role') {
        $role = isset($_POST['role_type']) && $_POST['role_type'] === 'operator' ? 'operator' : 'member';
        $role_sql = sql_real_escape_string($role);
        sql_query("
            UPDATE donuts_dotty_members
            SET role_type='{$role_sql}', updated_at=NOW()
            WHERE id='{$member_id}'
              AND dotty_mb_id='{$dotty_mb_id_sql}'
              AND member_status <> 'withdrawn'
        ");
        alert('권한을 변경했습니다.', './member_activity.php?mb_id='.urlencode($dotty_mb_id));
    }

    alert('올바르지 않은 요청입니다.');
}

$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$status = isset($_GET['status']) ? trim((string)$_GET['status']) : 'all';
if (!in_array($status, array('all','normal','restricted','withdrawn'), true)) $status = 'all';

$where = array("d.dotty_mb_id = '{$dotty_mb_id_sql}'");
if ($status === 'normal') $where[] = "d.member_status = 'active'";
if ($status === 'restricted') $where[] = "d.member_status = 'restricted'";
if ($status === 'withdrawn') $where[] = "d.member_status = 'withdrawn'";
if ($q !== '') {
    $q_sql = sql_real_escape_string($q);
    $where[] = "(d.dot_id LIKE '%{$q_sql}%' OR d.mb_id LIKE '%{$q_sql}%' OR m.mb_name LIKE '%{$q_sql}%' OR m.mb_nick LIKE '%{$q_sql}%')";
}
$sql_where = implode(' AND ', $where);

$stats = sql_fetch("
    SELECT
        SUM(CASE WHEN member_status <> 'withdrawn' THEN 1 ELSE 0 END) AS total_members,
        SUM(CASE WHEN member_status <> 'withdrawn' AND joined_at >= DATE_FORMAT(CURDATE(), '%Y-%m-01') THEN 1 ELSE 0 END) AS month_joined,
        SUM(CASE WHEN member_status <> 'withdrawn' AND last_active_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) AS active_7d,
        SUM(CASE WHEN member_status <> 'withdrawn' AND role_type='operator' THEN 1 ELSE 0 END) AS operators,
        SUM(CASE WHEN member_status='restricted' THEN 1 ELSE 0 END) AS restricted_count
    FROM donuts_dotty_members
    WHERE dotty_mb_id='{$dotty_mb_id_sql}'
");
$total_members = (int)$stats['total_members'];
$month_joined = (int)$stats['month_joined'];
$active_7d = (int)$stats['active_7d'];
$operators = (int)$stats['operators'];
$restricted_count = (int)$stats['restricted_count'];
$activity_rate = $total_members > 0 ? round(($active_7d / $total_members) * 100, 1) : 0;

$count = sql_fetch("
    SELECT COUNT(*) AS cnt
    FROM donuts_dotty_members d
    LEFT JOIN {$g5['member_table']} m ON m.mb_id=d.mb_id
    WHERE {$sql_where}
");
$result_count = (int)$count['cnt'];

$members = array();
$res = sql_query("
    SELECT d.*, m.mb_name, m.mb_nick, m.mb_certify
    FROM donuts_dotty_members d
    LEFT JOIN {$g5['member_table']} m ON m.mb_id=d.mb_id
    WHERE {$sql_where}
    ORDER BY CASE d.member_status WHEN 'restricted' THEN 0 WHEN 'active' THEN 1 ELSE 2 END,
             d.joined_at DESC, d.id DESC
    LIMIT 500
");
while ($r = sql_fetch_array($res)) {
    $members[] = $r;
}

$admin_token = get_admin_token();

function ma_status_label($s) {
    if ($s === 'restricted') return '활동 제한';
    if ($s === 'withdrawn') return '탈퇴';
    return '정상';
}
function ma_status_class($s) {
    if ($s === 'restricted') return 'bg-red-50 text-red-600';
    if ($s === 'withdrawn') return 'bg-gray-100 text-gray-600';
    return 'bg-emerald-50 text-emerald-700';
}

require_once '../admin.head.php';
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="mt-1 text-gray-400">가입 완료된 도트의 커뮤니티 활동 상태를 확인합니다.</p>
        <a href="./member_activity.php?download=csv&amp;mb_id=<?php echo urlencode($dotty_mb_id); ?>" class="shrink-0 border border-gray-300 rounded-lg bg-white px-3 py-2 text-gray-900 font-bold">목록 내려받기</a>
    </div>

    <section>
        <h3 class="sound_only">가입 도트 현황</h3>
        <div class="grid grid-cols-1 pc:grid-cols-4 gap-4 mt-4">
            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">전체 가입 도트</p>
                <p class="mt-3 text-2xl font-bold text-gray-900"><?php echo number_format($total_members); ?><span class="ml-1 text-sm">명</span></p>
                <span class="mt-3 block text-2xs text-green-600">이번 달 +<?php echo number_format($month_joined); ?>명</span>
            </div>
            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">최근 7일 활동</p>
                <p class="mt-3 text-2xl font-bold text-gray-900"><?php echo number_format($active_7d); ?><span class="ml-1 text-sm">명</span></p>
                <span class="mt-3 block text-2xs text-blue-600">활동률 <?php echo number_format($activity_rate, 1); ?>%</span>
            </div>
            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">지정 운영자</p>
                <p class="mt-3 text-2xl font-bold text-gray-900"><?php echo number_format($operators); ?><span class="ml-1 text-sm">명</span></p>
                <span class="mt-3 block text-2xs text-gray-600">대표 도티 제외</span>
            </div>
            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">활동 제한</p>
                <p class="mt-3 text-2xl font-bold text-gray-900"><?php echo number_format($restricted_count); ?><span class="ml-1 text-sm">명</span></p>
                <span class="mt-3 block text-2xs text-emerald-600">상태 확인 필요</span>
            </div>
        </div>
    </section>

    <section class="mt-4">
        <h3 class="sound_only">가입 도트 목록</h3>
        <form method="get" class="flex flex-col gap-3 pc:flex-row pc:items-center">
            <input type="hidden" name="mb_id" value="<?php echo ma_e($dotty_mb_id); ?>">
            <div class="flex-1 min-w-0 flex items-center border border-gray-300 rounded-lg bg-white">
                <label for="member-activity-search" class="sound_only">닉네임, 이름 또는 도트 ID 검색</label>
                <input type="search" id="member-activity-search" name="q" value="<?php echo ma_e($q); ?>" class="flex-1 min-w-0 outline-none p-3" placeholder="닉네임, 이름 또는 도트 ID 검색">
                <button type="submit" class="shrink-0 p-3 text-gray-900">검색</button>
            </div>
            <select name="status" onchange="this.form.submit()" class="shrink-0 rounded-lg border border-gray-300 bg-white text-gray-900">
                <option value="all"<?php echo $status==='all'?' selected':''; ?>>전체 상태</option>
                <option value="normal"<?php echo $status==='normal'?' selected':''; ?>>정상</option>
                <option value="restricted"<?php echo $status==='restricted'?' selected':''; ?>>활동 제한</option>
                <option value="withdrawn"<?php echo $status==='withdrawn'?' selected':''; ?>>탈퇴</option>
            </select>
            <span class="shrink-0 text-2xs text-gray-500">검색 결과 <?php echo number_format($result_count); ?>명</span>
        </form>

        <div class="mt-4 overflow-x-auto rounded-lg border border-gray-300 bg-white">
            <table class="border-collapse w-full min-w-225 text-left text-xs text-gray-900">
                <thead class="border-b border-gray-300 bg-gray-50 text-gray-500 font-bold [&_th]:p-3">
                    <tr><th>도트</th><th>도트 ID</th><th>가입일</th><th>작성 글</th><th>작성 댓글</th><th>권한</th><th>상태</th><th class="th_center">상세</th></tr>
                </thead>
                <tbody class="[&_td]:p-3">
                <?php if (!$members) { ?><tr><td colspan="8" class="p-6 text-center text-xs text-gray-500">검색 결과가 없습니다.</td></tr><?php } ?>
                <?php foreach ($members as $r) {
                    $nick = $r['mb_nick'] !== '' ? $r['mb_nick'] : $r['mb_id'];
                    $name = $r['mb_name'] !== '' ? $r['mb_name'] : $r['mb_id'];
                    $cert = !empty($r['mb_certify']) ? strtoupper($r['mb_certify']).' 본인인증 완료' : '본인인증 정보 없음';
                ?>
                    <tr class="border-b border-gray-200">
                        <td><p class="font-bold"><?php echo ma_e($nick); ?></p><span class="mt-1 block text-2xs text-gray-400"><?php echo ma_e($name); ?></span></td>
                        <td><?php echo ma_e($r['dot_id']); ?></td>
                        <td><?php echo !empty($r['joined_at']) ? ma_e(date('Y.m.d', strtotime($r['joined_at']))) : '-'; ?></td>
                        <td><?php echo number_format((int)$r['post_count']); ?></td>
                        <td><?php echo number_format((int)$r['comment_count']); ?></td>
                        <td><?php echo $r['role_type']==='operator' ? '<span class="inline-flex rounded-full bg-blue-50 px-2 py-1 text-2xs font-bold text-blue-700">• 지정 운영자</span>' : '도트'; ?></td>
                        <td><span class="inline-flex items-center rounded-full px-2 py-1 text-2xs font-bold <?php echo ma_status_class($r['member_status']); ?>">• <?php echo ma_e(ma_status_label($r['member_status'])); ?></span></td>
                        <td class="td_center">
                            <button type="button" class="member-activity-modal-open rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900"
                                data-id="<?php echo (int)$r['id']; ?>"
                                data-nick="<?php echo ma_e($nick); ?>"
                                data-name="<?php echo ma_e($name); ?>"
                                data-dot-id="<?php echo ma_e($r['dot_id']); ?>"
                                data-joined="<?php echo !empty($r['joined_at']) ? ma_e(date('Y.m.d', strtotime($r['joined_at']))) : '-'; ?>"
                                data-posts="<?php echo (int)$r['post_count']; ?>"
                                data-comments="<?php echo (int)$r['comment_count']; ?>"
                                data-role="<?php echo ma_e($r['role_type']); ?>"
                                data-status="<?php echo ma_e($r['member_status']); ?>"
                                data-status-label="<?php echo ma_e(ma_status_label($r['member_status'])); ?>"
                                data-reason="<?php echo ma_e($r['restricted_reason']); ?>"
                                data-cert="<?php echo ma_e($cert); ?>">보기</button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</section>

<div id="member-activity-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="member-activity-modal-backdrop" class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 max-h-[90vh] w-full max-w-160 overflow-y-auto rounded-lg bg-white">
        <div class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 class="text-base font-bold text-gray-900">도트 상세</h3>
            <button type="button" class="member-activity-modal-close flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">×</button>
        </div>
        <div class="space-y-3 p-4 text-xs text-gray-900">
            <div class="flex items-center gap-4">
                <div id="ma-avatar" class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-amber-300 text-base font-bold"></div>
                <div>
                    <p id="ma-nick" class="font-bold"></p>
                    <span id="ma-sub" class="mt-1 block text-2xs text-gray-400"></span>
                    <div class="mt-2 flex gap-3 text-2xs text-gray-500">
                        <span>가입 <b id="ma-joined" class="text-gray-900"></b></span>
                        <span>글 <b id="ma-posts" class="text-gray-900"></b></span>
                        <span>댓글 <b id="ma-comments" class="text-gray-900"></b></span>
                    </div>
                </div>
            </div>
            <div class="rounded-lg bg-gray-100 p-3"><span class="block text-2xs text-gray-400">현재 상태</span><p id="ma-status" class="mt-2"></p><p id="ma-reason" class="mt-1 text-red-600"></p></div>
            <div class="rounded-lg bg-gray-100 p-3"><span class="block text-2xs text-gray-400">본인인증</span><p id="ma-cert" class="mt-2"></p></div>

            <form method="post" class="rounded-lg border border-gray-200 p-3">
                <input type="hidden" name="token" value="<?php echo ma_e($admin_token); ?>">
                <input type="hidden" name="mb_id" value="<?php echo ma_e($dotty_mb_id); ?>">
                <input type="hidden" name="action" value="role">
                <input type="hidden" name="member_id" class="ma-member-id">
                <label class="font-bold">권한</label>
                <div class="mt-2 flex gap-2">
                    <select name="role_type" id="ma-role" class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2">
                        <option value="member">도트</option>
                        <option value="operator">지정 운영자</option>
                    </select>
                    <button type="submit" class="rounded-lg border border-gray-300 bg-white px-3 py-2 font-bold">권한 저장</button>
                </div>
            </form>

            <form id="ma-restrict-form" method="post" class="rounded-lg border border-red-200 bg-red-50 p-3">
                <input type="hidden" name="token" value="<?php echo ma_e($admin_token); ?>">
                <input type="hidden" name="mb_id" value="<?php echo ma_e($dotty_mb_id); ?>">
                <input type="hidden" name="action" value="restrict">
                <input type="hidden" name="member_id" class="ma-member-id">
                <label class="font-bold text-red-700">활동 제한 사유</label>
                <textarea name="restricted_reason" id="ma-restrict-reason" rows="3" class="mt-2 w-full rounded-lg border border-gray-300 bg-white p-3" required></textarea>
                <div class="mt-2 flex justify-end"><button type="submit" class="rounded-lg bg-red-600 px-3 py-2 font-bold text-white">활동 제한</button></div>
            </form>

            <form id="ma-restore-form" method="post">
                <input type="hidden" name="token" value="<?php echo ma_e($admin_token); ?>">
                <input type="hidden" name="mb_id" value="<?php echo ma_e($dotty_mb_id); ?>">
                <input type="hidden" name="action" value="restore">
                <input type="hidden" name="member_id" class="ma-member-id">
                <button type="submit" class="w-full rounded-lg border border-emerald-300 bg-white px-3 py-2 font-bold text-emerald-700">활동 제한 해제</button>
            </form>

            <div class="rounded-lg bg-amber-50 p-3 text-2xs text-amber-800">주문 · 배송 및 상품 문의 처리는 쇼핑 운영 영역에서 담당하며 도티 관리 범위에 포함되지 않습니다.</div>
        </div>
        <div class="sticky bottom-0 z-10 flex justify-end border-t border-gray-300 bg-white p-4">
            <button type="button" class="member-activity-modal-close rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-bold">닫기</button>
        </div>
    </div>
</div>

<script>
const $maModal = $('#member-activity-modal');
$('.member-activity-modal-open').on('click', function() {
    const $b = $(this), status = String($b.data('status') || '');
    $('.ma-member-id').val($b.data('id'));
    $('#ma-avatar').text(String($b.data('nick') || '?').substring(0, 1));
    $('#ma-nick').text($b.data('nick'));
    $('#ma-sub').text($b.data('name') + ' · ' + $b.data('dot-id'));
    $('#ma-joined').text($b.data('joined'));
    $('#ma-posts').text($b.data('posts'));
    $('#ma-comments').text($b.data('comments'));
    $('#ma-status').text($b.data('status-label'));
    $('#ma-reason').text(status === 'restricted' ? ($b.data('reason') || '') : '');
    $('#ma-cert').text($b.data('cert'));
    $('#ma-role').val($b.data('role'));
    $('#ma-restrict-reason').val('');
    $('#ma-restrict-form').prop('hidden', status === 'restricted' || status === 'withdrawn');
    $('#ma-restore-form').prop('hidden', status !== 'restricted');
    $maModal.prop('hidden', false);
});
$('.member-activity-modal-close, #member-activity-modal-backdrop').on('click', function(){ $maModal.prop('hidden', true); });
$('#ma-restrict-form').on('submit', function(e){ if (!confirm('이 도트의 커뮤니티 활동을 제한하시겠습니까?')) e.preventDefault(); });
$('#ma-restore-form').on('submit', function(e){ if (!confirm('활동 제한을 해제하시겠습니까?')) e.preventDefault(); });
</script>

<?php include_once(G5_ADMIN_PATH . '/admin.tail.php'); ?>

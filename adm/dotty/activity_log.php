<?php
$sub_menu = '730900';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '활동 로그';

function alog_e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
function alog_csv($v) {
    $v = (string)$v;
    if ($v !== '' && in_array($v[0], array('=', '+', '-', '@'), true)) {
        $v = "'" . $v;
    }
    return $v;
}

$dotty_mb_id = trim((string)$member['mb_id']);
if ($is_admin === 'super' && !empty($_REQUEST['mb_id'])) {
    $dotty_mb_id = trim((string)$_REQUEST['mb_id']);
}
if ($dotty_mb_id === '') {
    alert('도넛 관리 계정을 확인할 수 없습니다.');
}
$dotty_sql = sql_real_escape_string($dotty_mb_id);

$table_check = sql_fetch("
    SELECT COUNT(*) AS cnt
    FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'donuts_dotty_activity_logs'
");
if (empty($table_check['cnt'])) {
    alert('활동 로그 DB 마이그레이션이 필요합니다. migration_activity_log.sql을 먼저 실행해 주세요.');
}

$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$type = isset($_GET['type']) ? trim((string)$_GET['type']) : '';
$from_date = isset($_GET['from_date']) ? trim((string)$_GET['from_date']) : '';
$to_date = isset($_GET['to_date']) ? trim((string)$_GET['to_date']) : '';

$allowed_types = array(
    '', 'login', 'invite', 'product', 'topping', 'join',
    'content', 'notice', 'operator', 'ownership', 'etc'
);
if (!in_array($type, $allowed_types, true)) $type = '';

if ($from_date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $from_date)) $from_date = '';
if ($to_date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $to_date)) $to_date = '';

$where = array("l.dotty_mb_id = '{$dotty_sql}'");

if ($q !== '') {
    $q_sql = sql_real_escape_string($q);
    $where[] = "(
        l.actor_mb_id LIKE '%{$q_sql}%'
        OR l.actor_name LIKE '%{$q_sql}%'
        OR l.action_label LIKE '%{$q_sql}%'
        OR l.action_detail LIKE '%{$q_sql}%'
        OR l.target_id LIKE '%{$q_sql}%'
        OR l.ip_address LIKE '%{$q_sql}%'
    )";
}
if ($type !== '') {
    $type_sql = sql_real_escape_string($type);
    $where[] = "l.action_group = '{$type_sql}'";
}
if ($from_date !== '') {
    $from_sql = sql_real_escape_string($from_date);
    $where[] = "l.created_at >= '{$from_sql} 00:00:00'";
}
if ($to_date !== '') {
    $to_sql = sql_real_escape_string($to_date);
    $where[] = "l.created_at <= '{$to_sql} 23:59:59'";
}

$sql_where = implode(' AND ', $where);

/* CSV 다운로드 */
if (isset($_GET['download']) && $_GET['download'] === 'csv') {
    $filename = 'activity_log_' . date('Ymd_His') . '.csv';

    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $fp = fopen('php://output', 'w');
    fwrite($fp, "\xEF\xBB\xBF");
    fputcsv($fp, array('처리 일시', '운영자', '계정 ID', '처리 유형', '처리 내용', '대상 ID', 'IP 주소'));

    $csv_res = sql_query("
        SELECT l.*
        FROM donuts_dotty_activity_logs l
        WHERE {$sql_where}
        ORDER BY l.log_id DESC
        LIMIT 10000
    ");
    while ($row = sql_fetch_array($csv_res)) {
        fputcsv($fp, array(
            alog_csv($row['created_at']),
            alog_csv($row['actor_name']),
            alog_csv($row['actor_mb_id']),
            alog_csv($row['action_label']),
            alog_csv($row['action_detail']),
            alog_csv($row['target_id']),
            alog_csv($row['ip_address'])
        ));
    }
    fclose($fp);
    exit;
}

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$rows_per_page = 30;

$count_row = sql_fetch("
    SELECT COUNT(*) AS cnt
    FROM donuts_dotty_activity_logs l
    WHERE {$sql_where}
");
$total_count = (int)$count_row['cnt'];
$total_page = max(1, (int)ceil($total_count / $rows_per_page));
if ($page > $total_page) $page = $total_page;
$offset = ($page - 1) * $rows_per_page;

$logs = array();
$res = sql_query("
    SELECT l.*
    FROM donuts_dotty_activity_logs l
    WHERE {$sql_where}
    ORDER BY l.log_id DESC
    LIMIT {$offset}, {$rows_per_page}
");
while ($row = sql_fetch_array($res)) {
    $logs[] = $row;
}

$query_base = array(
    'mb_id' => $dotty_mb_id,
    'q' => $q,
    'type' => $type,
    'from_date' => $from_date,
    'to_date' => $to_date
);
$download_query = http_build_query(array_merge($query_base, array('download' => 'csv')));

require_once '../admin.head.php';
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="mt-1 text-gray-400">중요한 변경과 처리 이력을 확인합니다.</p>

        <a href="./activity_log.php?<?php echo alog_e($download_query); ?>"
           class="shrink-0 border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
            <span>로그 내려받기</span>
        </a>
    </div>

    <form method="get" class="mt-4 space-y-3">
        <input type="hidden" name="mb_id" value="<?php echo alog_e($dotty_mb_id); ?>">

        <div class="flex flex-col gap-2 pc:flex-row pc:items-center">
            <div role="search" class="min-w-0 flex-1">
                <label for="activity-log-search" class="sound_only">운영자, 작업 내용 또는 대상 ID 검색</label>
                <input type="search" id="activity-log-search" name="q"
                       value="<?php echo alog_e($q); ?>"
                       class="w-full rounded-lg border border-gray-300 text-gray-900 p-3"
                       placeholder="운영자, 작업 내용 또는 대상 ID 검색" autocomplete="off">
            </div>

            <select name="type" class="rounded-lg border border-gray-300 bg-white p-3 text-gray-900">
                <option value="">전체 유형</option>
                <?php
                $types = array(
                    'login' => '로그인',
                    'invite' => '초대 링크',
                    'product' => '추천상품',
                    'topping' => '토핑',
                    'join' => '가입 관리',
                    'content' => '콘텐츠',
                    'notice' => '공지·핀',
                    'operator' => '운영자',
                    'ownership' => '운영권 승계',
                    'etc' => '기타'
                );
                foreach ($types as $key => $label) {
                ?>
                    <option value="<?php echo alog_e($key); ?>" <?php echo $type === $key ? 'selected' : ''; ?>><?php echo alog_e($label); ?></option>
                <?php } ?>
            </select>

            <input type="date" name="from_date" value="<?php echo alog_e($from_date); ?>"
                   class="rounded-lg border border-gray-300 bg-white p-3 text-gray-900">
            <span class="hidden pc:inline text-gray-400">~</span>
            <input type="date" name="to_date" value="<?php echo alog_e($to_date); ?>"
                   class="rounded-lg border border-gray-300 bg-white p-3 text-gray-900">

            <button type="submit" class="rounded-lg bg-gray-900 px-4 py-3 font-bold text-white">검색</button>
        </div>

        <p id="activity-log-result-count" class="text-2xs text-gray-400">검색 결과 <?php echo number_format($total_count); ?>건</p>
    </form>

    <section class="mt-4">
        <h3 class="sr-only">관리자 활동 로그 목록</h3>

        <div class="overflow-x-auto rounded-lg border border-gray-300">
            <table class="w-full min-w-180 border-collapse text-left">
                <caption class="sr-only">도티와 지정 운영자의 주요 처리 이력</caption>

                <thead class="sr-only">
                    <tr>
                        <th>처리 일시</th>
                        <th>운영자</th>
                        <th>처리 유형</th>
                        <th>처리 내용</th>
                        <th>IP 주소</th>
                    </tr>
                </thead>

                <tbody class="text-gray-900 [&_td]:p-3">
                <?php if (!$logs) { ?>
                    <tr>
                        <td colspan="5" class="text-center text-xs text-gray-500 p-6">검색 결과가 없습니다.</td>
                    </tr>
                <?php } ?>

                <?php foreach ($logs as $row) { ?>
                    <tr class="border-b border-gray-200">
                        <td class="text-2xs"><?php echo alog_e(date('Y.m.d H:i:s', strtotime($row['created_at']))); ?></td>
                        <td>
                            <?php echo alog_e($row['actor_name'] ?: $row['actor_mb_id']); ?>
                            <?php if ($row['actor_mb_id'] !== '') { ?>
                                <span class="mt-1 block text-2xs text-gray-400"><?php echo alog_e($row['actor_mb_id']); ?></span>
                            <?php } ?>
                        </td>
                        <td>
                            <span class="flex items-center gap-2 font-bold">
                                <span aria-hidden="true" class="w-2 h-2 rounded-full bg-amber-300"></span>
                                <?php echo alog_e($row['action_label']); ?>
                            </span>
                        </td>
                        <td>
                            <?php echo alog_e($row['action_detail']); ?>
                            <?php if ($row['target_id'] !== '') { ?>
                                <span class="mt-1 block text-2xs text-gray-400">대상 <?php echo alog_e($row['target_id']); ?></span>
                            <?php } ?>
                        </td>
                        <td class="text-2xs text-gray-400"><?php echo alog_e($row['ip_address']); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_page > 1) { ?>
        <div class="mt-4 flex flex-wrap justify-center gap-1">
            <?php
            $start_page = max(1, $page - 5);
            $end_page = min($total_page, $page + 5);
            for ($p = $start_page; $p <= $end_page; $p++) {
                $page_query = http_build_query(array_merge($query_base, array('page' => $p)));
            ?>
                <a href="./activity_log.php?<?php echo alog_e($page_query); ?>"
                   class="rounded-lg border px-3 py-2 text-xs font-bold <?php echo $p === $page ? 'border-gray-900 bg-gray-900 text-white' : 'border-gray-300 bg-white text-gray-700'; ?>">
                    <?php echo $p; ?>
                </a>
            <?php } ?>
        </div>
        <?php } ?>
    </section>
</section>

<?php include_once(G5_ADMIN_PATH . '/admin.tail.php'); ?>

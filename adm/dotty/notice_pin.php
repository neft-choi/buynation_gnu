<?php
$sub_menu = '730600';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '공지사항 및 핀';

function np_e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
function np_code($id) {
    return 'NTC-' . str_pad((string)(int)$id, 4, '0', STR_PAD_LEFT);
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
      AND TABLE_NAME = 'donuts_dotty_notices'
");
if (empty($table_check['cnt'])) {
    alert('공지사항 DB 마이그레이션이 필요합니다. migration_notice_pin.sql을 먼저 실행해 주세요.');
}

/* 등록 / 수정 / 핀 변경 / 삭제 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    auth_check_menu($auth, $sub_menu, 'w');
    check_admin_token();

    $action = isset($_POST['action']) ? trim((string)$_POST['action']) : '';

    if ($action === 'write' || $action === 'edit') {
        $notice_id = isset($_POST['notice_id']) ? (int)$_POST['notice_id'] : 0;
        $subject = isset($_POST['notice_subject']) ? trim((string)$_POST['notice_subject']) : '';
        $content = isset($_POST['notice_content']) ? trim((string)$_POST['notice_content']) : '';
        $want_pin = !empty($_POST['is_pinned']) ? 'Y' : 'N';

        if ($subject === '') alert('공지 제목을 입력해 주세요.');
        if ($content === '') alert('공지 내용을 입력해 주세요.');
        if (mb_strlen($subject, 'UTF-8') > 255) alert('공지 제목은 255자 이하로 입력해 주세요.');

        $subject = mb_substr(clean_xss_tags($subject, 1, 1), 0, 255, 'UTF-8');
        $content = clean_xss_tags($content, 1, 1);

        $subject_sql = sql_real_escape_string($subject);
        $content_sql = sql_real_escape_string($content);

        if ($action === 'edit') {
            $old = sql_fetch("
                SELECT notice_id, is_pinned
                FROM donuts_dotty_notices
                WHERE notice_id = '{$notice_id}'
                  AND dotty_mb_id = '{$dotty_sql}'
                LIMIT 1
            ");
            if (empty($old['notice_id'])) alert('수정 권한이 없는 공지입니다.');

            if ($want_pin === 'Y' && $old['is_pinned'] !== 'Y') {
                $pin_cnt = sql_fetch("
                    SELECT COUNT(*) AS cnt
                    FROM donuts_dotty_notices
                    WHERE dotty_mb_id = '{$dotty_sql}'
                      AND is_pinned = 'Y'
                      AND use_yn = 'Y'
                ");
                if ((int)$pin_cnt['cnt'] >= 3) {
                    alert('고정 공지는 최대 3개까지 등록할 수 있습니다.');
                }
            }

            sql_query("
                UPDATE donuts_dotty_notices
                SET notice_subject = '{$subject_sql}',
                    notice_content = '{$content_sql}',
                    is_pinned = '{$want_pin}',
                    pinned_at = ".($want_pin === 'Y' ? "IF(pinned_at IS NULL, NOW(), pinned_at)" : "NULL").",
                    updated_at = NOW()
                WHERE notice_id = '{$notice_id}'
                  AND dotty_mb_id = '{$dotty_sql}'
            ");
            alert('공지를 수정했습니다.', './notice_pin.php?mb_id='.urlencode($dotty_mb_id));
        }

        if ($want_pin === 'Y') {
            $pin_cnt = sql_fetch("
                SELECT COUNT(*) AS cnt
                FROM donuts_dotty_notices
                WHERE dotty_mb_id = '{$dotty_sql}'
                  AND is_pinned = 'Y'
                  AND use_yn = 'Y'
            ");
            if ((int)$pin_cnt['cnt'] >= 3) {
                alert('고정 공지는 최대 3개까지 등록할 수 있습니다.');
            }
        }

        $writer_id_sql = sql_real_escape_string((string)$member['mb_id']);
        $writer_name_sql = sql_real_escape_string((string)$member['mb_name']);
        $writer_nick_sql = sql_real_escape_string((string)$member['mb_nick']);

        sql_query("
            INSERT INTO donuts_dotty_notices
            SET dotty_mb_id = '{$dotty_sql}',
                writer_mb_id = '{$writer_id_sql}',
                writer_name = '{$writer_name_sql}',
                writer_nick = '{$writer_nick_sql}',
                notice_subject = '{$subject_sql}',
                notice_content = '{$content_sql}',
                view_count = 0,
                is_pinned = '{$want_pin}',
                pinned_at = ".($want_pin === 'Y' ? "NOW()" : "NULL").",
                use_yn = 'Y',
                created_at = NOW(),
                updated_at = NOW()
        ");
        alert('공지를 등록했습니다.', './notice_pin.php?mb_id='.urlencode($dotty_mb_id));
    }

    if ($action === 'toggle_pin') {
        $notice_id = isset($_POST['notice_id']) ? (int)$_POST['notice_id'] : 0;
        $notice = sql_fetch("
            SELECT notice_id, is_pinned
            FROM donuts_dotty_notices
            WHERE notice_id = '{$notice_id}'
              AND dotty_mb_id = '{$dotty_sql}'
              AND use_yn = 'Y'
            LIMIT 1
        ");
        if (empty($notice['notice_id'])) alert('처리 권한이 없는 공지입니다.');

        if ($notice['is_pinned'] === 'Y') {
            sql_query("
                UPDATE donuts_dotty_notices
                SET is_pinned = 'N', pinned_at = NULL, updated_at = NOW()
                WHERE notice_id = '{$notice_id}'
                  AND dotty_mb_id = '{$dotty_sql}'
            ");
            alert('핀 고정을 해제했습니다.', './notice_pin.php?mb_id='.urlencode($dotty_mb_id));
        }

        $pin_cnt = sql_fetch("
            SELECT COUNT(*) AS cnt
            FROM donuts_dotty_notices
            WHERE dotty_mb_id = '{$dotty_sql}'
              AND is_pinned = 'Y'
              AND use_yn = 'Y'
        ");
        if ((int)$pin_cnt['cnt'] >= 3) {
            alert('고정 공지는 최대 3개까지 등록할 수 있습니다.');
        }

        sql_query("
            UPDATE donuts_dotty_notices
            SET is_pinned = 'Y', pinned_at = NOW(), updated_at = NOW()
            WHERE notice_id = '{$notice_id}'
              AND dotty_mb_id = '{$dotty_sql}'
        ");
        alert('공지를 상단에 고정했습니다.', './notice_pin.php?mb_id='.urlencode($dotty_mb_id));
    }

    if ($action === 'delete') {
        auth_check_menu($auth, $sub_menu, 'd');

        $notice_id = isset($_POST['notice_id']) ? (int)$_POST['notice_id'] : 0;
        $notice = sql_fetch("
            SELECT notice_id
            FROM donuts_dotty_notices
            WHERE notice_id = '{$notice_id}'
              AND dotty_mb_id = '{$dotty_sql}'
            LIMIT 1
        ");
        if (empty($notice['notice_id'])) alert('삭제 권한이 없는 공지입니다.');

        sql_query("
            DELETE FROM donuts_dotty_notices
            WHERE notice_id = '{$notice_id}'
              AND dotty_mb_id = '{$dotty_sql}'
        ");
        alert('공지를 삭제했습니다.', './notice_pin.php?mb_id='.urlencode($dotty_mb_id));
    }

    alert('올바르지 않은 요청입니다.');
}

/* 상세 모달 조회수는 별도 GET 액션으로 증가 */
if (isset($_GET['view_notice_id']) && (int)$_GET['view_notice_id'] > 0) {
    $view_id = (int)$_GET['view_notice_id'];
    sql_query("
        UPDATE donuts_dotty_notices
        SET view_count = view_count + 1
        WHERE notice_id = '{$view_id}'
          AND dotty_mb_id = '{$dotty_sql}'
          AND use_yn = 'Y'
    ");
    goto_url('./notice_pin.php?mb_id='.urlencode($dotty_mb_id));
}

$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$q_sql = sql_real_escape_string($q);

$where = "dotty_mb_id = '{$dotty_sql}' AND use_yn = 'Y'";
if ($q !== '') {
    $where .= " AND (
        notice_subject LIKE '%{$q_sql}%'
        OR writer_name LIKE '%{$q_sql}%'
        OR writer_nick LIKE '%{$q_sql}%'
        OR writer_mb_id LIKE '%{$q_sql}%'
    )";
}

$count = sql_fetch("SELECT COUNT(*) AS cnt FROM donuts_dotty_notices WHERE {$where}");
$result_count = (int)$count['cnt'];

$pin_count_row = sql_fetch("
    SELECT COUNT(*) AS cnt
    FROM donuts_dotty_notices
    WHERE dotty_mb_id = '{$dotty_sql}'
      AND use_yn = 'Y'
      AND is_pinned = 'Y'
");
$pin_count = (int)$pin_count_row['cnt'];

$pinned = array();
$pin_res = sql_query("
    SELECT *
    FROM donuts_dotty_notices
    WHERE dotty_mb_id = '{$dotty_sql}'
      AND use_yn = 'Y'
      AND is_pinned = 'Y'
    ORDER BY pinned_at ASC, notice_id ASC
    LIMIT 3
");
while ($r = sql_fetch_array($pin_res)) $pinned[] = $r;

$notices = array();
$res = sql_query("
    SELECT *
    FROM donuts_dotty_notices
    WHERE {$where}
    ORDER BY is_pinned DESC,
             CASE WHEN is_pinned='Y' THEN pinned_at END ASC,
             notice_id DESC
    LIMIT 500
");
while ($r = sql_fetch_array($res)) $notices[] = $r;

$admin_token = get_admin_token();

require_once '../admin.head.php';
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="mt-1 text-gray-400">고정된 공지는 커뮤니티 상세 상단에서 한 건과 +N 형태로 안내됩니다.</p>
        <button type="button" id="notice-write-modal-open" class="shrink-0 rounded-lg bg-amber-300 px-3 py-2">
            <span class="text-gray-900 font-bold">+ 공지 작성</span>
        </button>
    </div>

    <div class="mt-4 grid grid-cols-1 gap-4 pc:grid-cols-2">
        <div class="rounded-lg border border-gray-300 bg-white p-4">
            <h3 class="text-base font-bold text-gray-900">커뮤니티 노출 미리보기</h3>
            <div class="mt-4 flex items-center justify-between rounded-lg border border-gray-300 text-2xs text-gray-900 p-3">
                <?php if (!empty($pinned)) { ?>
                    <span>[공지] <?php echo np_e($pinned[0]['notice_subject']); ?></span>
                    <?php if ($pin_count > 1) { ?><span class="font-bold text-amber-700">+<?php echo $pin_count - 1; ?></span><?php } ?>
                <?php } else { ?>
                    <span class="text-gray-400">고정된 공지가 없습니다.</span>
                <?php } ?>
            </div>
            <p class="mt-2 text-2xs text-gray-400">
                <?php echo $pin_count > 0 ? '고정 공지가 총 '.number_format($pin_count).'개입니다.' : '공지 목록에서 핀을 추가할 수 있습니다.'; ?>
            </p>
        </div>

        <div class="rounded-lg border border-gray-300 bg-white p-4">
            <h3 class="text-base font-bold text-gray-900">핀 운영 상태</h3>
            <div class="mt-6 flex items-baseline gap-2">
                <span class="text-3xl font-bold text-gray-900"><?php echo number_format($pin_count); ?></span>
                <span class="font-bold text-gray-900">/ 3개</span>
                <?php if ($pin_count >= 3) { ?>
                    <span class="rounded-full bg-amber-100 px-2 py-1 text-2xs font-bold text-amber-700">● 고정 한도 도달</span>
                <?php } else { ?>
                    <span class="rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">● <?php echo 3-$pin_count; ?>개 추가 가능</span>
                <?php } ?>
            </div>
            <p class="mt-4 text-2xs text-gray-400">고정 공지는 최대 3개까지 운영할 수 있습니다.</p>
        </div>
    </div>

    <div class="mt-4 flex items-center gap-2">
        <form method="get" class="flex min-w-0 flex-1 items-center rounded-lg border border-gray-300 bg-white p-3">
            <input type="hidden" name="mb_id" value="<?php echo np_e($dotty_mb_id); ?>">
            <input type="search" name="q" value="<?php echo np_e($q); ?>" aria-label="공지 검색" class="min-w-0 flex-1 bg-transparent outline-none" placeholder="공지 제목 또는 작성자 검색">
            <button type="submit" class="shrink-0 text-gray-900">검색</button>
        </form>
        <span class="shrink-0 text-2xs text-gray-400">검색 결과 <?php echo number_format($result_count); ?>건</span>
    </div>

    <div class="mt-4 overflow-x-auto rounded-lg border border-gray-300 bg-white">
        <table class="border-collapse text-left min-w-225 w-full">
            <caption class="sound_only">공지 사항 목록</caption>
            <thead class="border-b border-gray-300 bg-gray-50 text-gray-500 font-bold [&_th]:p-3">
                <tr>
                    <th class="th_center">구분</th><th>제목</th><th>작성자</th><th>등록일</th><th>조회</th><th class="th_center">핀 상태</th><th class="th_center">관리</th>
                </tr>
            </thead>
            <tbody class="text-gray-900 [&_td]:p-3">
            <?php if (!$notices) { ?>
                <tr><td colspan="7" class="p-6 text-center text-xs text-gray-500">등록된 공지가 없습니다.</td></tr>
            <?php } ?>
            <?php
            $pin_no = 0;
            foreach ($notices as $r) {
                if ($r['is_pinned'] === 'Y') $pin_no++;
                $writer = $r['writer_nick'] ?: $r['writer_name'] ?: $r['writer_mb_id'];
            ?>
                <tr class="border-b border-gray-200">
                    <td class="td_center">
                        <?php if ($r['is_pinned'] === 'Y') { ?>
                            <span class="rounded-full bg-amber-100 px-2 py-1 text-2xs font-bold text-amber-700">● 고정 <?php echo $pin_no; ?></span>
                        <?php } else { ?>
                            <span class="rounded-full bg-gray-100 px-2 py-1 text-2xs font-bold text-gray-700">● 공지</span>
                        <?php } ?>
                    </td>
                    <td><p class="font-bold"><?php echo np_e($r['notice_subject']); ?></p><span class="mt-1 block text-2xs text-gray-400"><?php echo np_code($r['notice_id']); ?></span></td>
                    <td><?php echo np_e($writer); ?></td>
                    <td><?php echo np_e(date('Y.m.d', strtotime($r['created_at']))); ?></td>
                    <td><?php echo number_format((int)$r['view_count']); ?></td>
                    <td class="td_center">
                        <form method="post">
                            <input type="hidden" name="token" value="<?php echo np_e($admin_token); ?>">
                            <input type="hidden" name="mb_id" value="<?php echo np_e($dotty_mb_id); ?>">
                            <input type="hidden" name="action" value="toggle_pin">
                            <input type="hidden" name="notice_id" value="<?php echo (int)$r['notice_id']; ?>">
                            <button type="submit" class="rounded-lg border <?php echo $r['is_pinned']==='Y'?'border-red-300 text-red-600':'border-gray-300 text-gray-900'; ?> bg-white px-3 py-2 text-2xs font-bold">
                                <?php echo $r['is_pinned']==='Y'?'📌 고정 해제':'핀 추가'; ?>
                            </button>
                        </form>
                    </td>
                    <td class="td_center">
                        <button type="button" class="notice-detail-modal-open rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900"
                            data-id="<?php echo (int)$r['notice_id']; ?>"
                            data-code="<?php echo np_e(np_code($r['notice_id'])); ?>"
                            data-subject="<?php echo np_e($r['notice_subject']); ?>"
                            data-content="<?php echo np_e($r['notice_content']); ?>"
                            data-writer="<?php echo np_e($writer); ?>"
                            data-date="<?php echo np_e(date('Y.m.d', strtotime($r['created_at']))); ?>"
                            data-view="<?php echo (int)$r['view_count']; ?>"
                            data-pin="<?php echo np_e($r['is_pinned']); ?>">상세</button>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</section>

<!-- 공지 작성/수정 모달 -->
<div id="notice-write-modal" class="fixed inset-0 z-1000 flex items-center justify-center" hidden>
    <div id="notice-write-modal-backdrop" class="absolute inset-0 z-10 bg-black/40"></div>
    <div class="relative z-20 max-h-[90vh] w-full max-w-160 overflow-y-auto rounded-lg bg-white">
        <div class="flex items-center justify-between border-b border-gray-300 p-4">
            <h3 id="notice-write-modal-title" class="text-base font-bold text-gray-900">공지사항 작성</h3>
            <button type="button" class="notice-write-modal-close text-xl">×</button>
        </div>
        <form method="post" id="notice-write-form">
            <input type="hidden" name="token" value="<?php echo np_e($admin_token); ?>">
            <input type="hidden" name="mb_id" value="<?php echo np_e($dotty_mb_id); ?>">
            <input type="hidden" name="action" id="notice-action" value="write">
            <input type="hidden" name="notice_id" id="notice-id" value="">
            <div class="p-4">
                <label class="block font-bold text-gray-900">공지 제목</label>
                <input type="text" name="notice_subject" id="notice-subject" maxlength="255" required class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-3 text-sm" placeholder="공지 제목을 입력하세요">
                <label class="mt-4 block font-bold text-gray-900">공지 내용</label>
                <textarea name="notice_content" id="notice-content" required class="mt-2 h-40 w-full rounded-lg border border-gray-300 p-3 text-sm" placeholder="중요한 소식을 입력하세요."></textarea>
                <div class="mt-4 flex items-center gap-2">
                    <input type="checkbox" name="is_pinned" value="1" id="notice-pin" class="h-4 w-4">
                    <label for="notice-pin" class="text-sm text-gray-900">등록 후 상단에 고정하기</label>
                </div>
                <span class="mt-2 block text-2xs text-gray-400">고정 공지는 최대 3개까지 운영할 수 있습니다.</span>
            </div>
            <div class="flex justify-end gap-2 border-t border-gray-300 p-4">
                <button type="button" class="notice-write-modal-close rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-bold">취소</button>
                <button type="submit" id="notice-submit" class="rounded-lg bg-amber-300 px-4 py-3 text-sm font-bold text-gray-900">공지 등록</button>
            </div>
        </form>
    </div>
</div>

<!-- 상세 모달 -->
<div id="notice-detail-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="notice-detail-modal-backdrop" class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 max-h-[90vh] w-full max-w-160 overflow-y-auto rounded-lg bg-white">
        <div class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 class="text-lg font-bold text-gray-900">공지 상세</h3>
            <button type="button" class="notice-detail-modal-close text-xl">×</button>
        </div>
        <div class="p-4">
            <dl class="overflow-hidden rounded-lg border border-gray-300">
                <div class="grid grid-cols-[110px_1fr] border-b"><dt class="bg-gray-50 p-3 text-gray-500">공지 ID</dt><dd id="detail-code" class="p-3 font-bold"></dd></div>
                <div class="grid grid-cols-[110px_1fr] border-b"><dt class="bg-gray-50 p-3 text-gray-500">제목</dt><dd id="detail-subject" class="p-3 font-bold"></dd></div>
                <div class="grid grid-cols-[110px_1fr] border-b"><dt class="bg-gray-50 p-3 text-gray-500">작성자</dt><dd id="detail-writer" class="p-3 font-bold"></dd></div>
                <div class="grid grid-cols-[110px_1fr] border-b"><dt class="bg-gray-50 p-3 text-gray-500">등록일</dt><dd id="detail-date" class="p-3 font-bold"></dd></div>
                <div class="grid grid-cols-[110px_1fr]"><dt class="bg-gray-50 p-3 text-gray-500">조회</dt><dd id="detail-view" class="p-3 font-bold"></dd></div>
            </dl>
            <div class="mt-4 rounded-lg bg-gray-100 p-3"><span class="block text-2xs text-gray-500">공지 내용</span><p id="detail-content" class="mt-2 whitespace-pre-wrap text-2xs text-gray-900"></p></div>
        </div>
        <div class="sticky bottom-0 z-10 flex flex-wrap justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="notice-edit-button" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-bold">수정</button>
            <form method="post" onsubmit="return confirm('이 공지를 삭제하시겠습니까?');">
                <input type="hidden" name="token" value="<?php echo np_e($admin_token); ?>">
                <input type="hidden" name="mb_id" value="<?php echo np_e($dotty_mb_id); ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="notice_id" id="detail-delete-id">
                <button type="submit" class="rounded-lg border border-red-300 bg-white px-4 py-3 text-sm font-bold text-red-600">삭제</button>
            </form>
            <button type="button" class="notice-detail-modal-close rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-bold">닫기</button>
        </div>
    </div>
</div>

<script>
let currentNotice = null;

$('#notice-write-modal-open').on('click', function() {
    $('#notice-action').val('write');
    $('#notice-id').val('');
    $('#notice-subject').val('');
    $('#notice-content').val('');
    $('#notice-pin').prop('checked', false);
    $('#notice-write-modal-title').text('공지사항 작성');
    $('#notice-submit').text('공지 등록');
    $('#notice-write-modal').prop('hidden', false);
});
$('.notice-write-modal-close, #notice-write-modal-backdrop').on('click', function() {
    $('#notice-write-modal').prop('hidden', true);
});

$('.notice-detail-modal-open').on('click', function() {
    const $b = $(this);
    currentNotice = {
        id: $b.data('id'),
        subject: $b.attr('data-subject') || '',
        content: $b.attr('data-content') || '',
        pin: $b.data('pin')
    };
    $('#detail-code').text($b.data('code'));
    $('#detail-subject').text(currentNotice.subject);
    $('#detail-writer').text($b.data('writer'));
    $('#detail-date').text($b.data('date'));
    $('#detail-view').text(Number($b.data('view') || 0).toLocaleString());
    $('#detail-content').text(currentNotice.content);
    $('#detail-delete-id').val(currentNotice.id);
    $('#notice-detail-modal').prop('hidden', false);
});
$('.notice-detail-modal-close, #notice-detail-modal-backdrop').on('click', function() {
    $('#notice-detail-modal').prop('hidden', true);
});

$('#notice-edit-button').on('click', function() {
    if (!currentNotice) return;
    $('#notice-detail-modal').prop('hidden', true);
    $('#notice-action').val('edit');
    $('#notice-id').val(currentNotice.id);
    $('#notice-subject').val(currentNotice.subject);
    $('#notice-content').val(currentNotice.content);
    $('#notice-pin').prop('checked', currentNotice.pin === 'Y');
    $('#notice-write-modal-title').text('공지사항 수정');
    $('#notice-submit').text('수정 저장');
    $('#notice-write-modal').prop('hidden', false);
});
</script>

<?php include_once(G5_ADMIN_PATH . '/admin.tail.php'); ?>

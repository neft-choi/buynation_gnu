<?php
$sub_menu = '730700';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '운영자 권한';

function al_e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
function al_perm_labels($csv) {
    $map = array(
        'join' => '가입 심사',
        'member' => '회원 조회',
        'content' => '콘텐츠 관리',
        'notice' => '공지·핀 관리',
        'product' => '추천 상품'
    );
    $out = array();
    foreach (array_filter(array_map('trim', explode(',', (string)$csv))) as $p) {
        if (isset($map[$p])) $out[] = $map[$p];
    }
    return $out;
}

$dotty_mb_id = trim((string)$member['mb_id']);
if ($is_admin === 'super' && !empty($_REQUEST['mb_id'])) {
    $dotty_mb_id = trim((string)$_REQUEST['mb_id']);
}
if ($dotty_mb_id === '') {
    alert('도넛 관리 계정을 확인할 수 없습니다.');
}
$dotty_sql = sql_real_escape_string($dotty_mb_id);

/* 기능용 테이블 확인 */
foreach (array('donuts_dotty_admins', 'donuts_dotty_admin_logs', 'donuts_dotty_members') as $table) {
    $table_sql = sql_real_escape_string($table);
    $ck = sql_fetch("SELECT COUNT(*) AS cnt
        FROM information_schema.TABLES
        WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='{$table_sql}'");
    if (empty($ck['cnt'])) {
        alert('운영자 권한 DB 마이그레이션이 필요합니다. migration_admin_list.sql을 먼저 실행해 주세요.');
    }
}

/* 등록/수정/해제 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    auth_check_menu($auth, $sub_menu, 'w');
    check_admin_token();

    $action = isset($_POST['action']) ? trim((string)$_POST['action']) : '';
    $operator_mb_id = isset($_POST['operator_mb_id']) ? trim((string)$_POST['operator_mb_id']) : '';
    if ($operator_mb_id === '') alert('운영자를 선택해 주세요.');

    $operator_sql = sql_real_escape_string($operator_mb_id);

    /* 현재 도넛에 정상 가입한 회원만 지정 가능 */
    $joined = sql_fetch("SELECT id, mb_id, member_status
        FROM donuts_dotty_members
        WHERE dotty_mb_id='{$dotty_sql}'
          AND mb_id='{$operator_sql}'
          AND member_status='active'
        LIMIT 1");
    if (empty($joined['id'])) {
        alert('현재 도넛에 정상 가입한 도트만 운영자로 지정할 수 있습니다.');
    }
    if (strcasecmp($operator_mb_id, $dotty_mb_id) === 0) {
        alert('대표 도티는 별도 운영자로 지정할 수 없습니다.');
    }

    if ($action === 'save') {
        $allowed = array('join','member','content','notice','product');
        $posted = isset($_POST['permissions']) && is_array($_POST['permissions']) ? $_POST['permissions'] : array();
        $permissions = array();
        foreach ($posted as $p) {
            $p = trim((string)$p);
            if (in_array($p, $allowed, true)) $permissions[] = $p;
        }
        $permissions = array_values(array_unique($permissions));
        if (!$permissions) alert('운영자에게 부여할 권한을 하나 이상 선택해 주세요.');

        $permission_csv = implode(',', $permissions);
        $permission_sql = sql_real_escape_string($permission_csv);

        $existing = sql_fetch("SELECT admin_id
            FROM donuts_dotty_admins
            WHERE dotty_mb_id='{$dotty_sql}' AND operator_mb_id='{$operator_sql}'
            LIMIT 1");

        if (!empty($existing['admin_id'])) {
            sql_query("UPDATE donuts_dotty_admins
                SET permissions='{$permission_sql}', use_yn='Y', updated_at=NOW()
                WHERE admin_id='".(int)$existing['admin_id']."'
                  AND dotty_mb_id='{$dotty_sql}'");
            $log_action = 'permission_update';
        } else {
            sql_query("INSERT INTO donuts_dotty_admins
                SET dotty_mb_id='{$dotty_sql}',
                    operator_mb_id='{$operator_sql}',
                    permissions='{$permission_sql}',
                    use_yn='Y',
                    created_at=NOW(),
                    updated_at=NOW()");
            $log_action = 'operator_add';
        }

        /* member_activity와 역할 상태 동기화 */
        sql_query("UPDATE donuts_dotty_members
            SET role_type='operator', updated_at=NOW()
            WHERE dotty_mb_id='{$dotty_sql}' AND mb_id='{$operator_sql}'");

        $actor_sql = sql_real_escape_string((string)$member['mb_id']);
        $detail_sql = sql_real_escape_string('permissions='.$permission_csv);
        sql_query("INSERT INTO donuts_dotty_admin_logs
            SET dotty_mb_id='{$dotty_sql}',
                operator_mb_id='{$operator_sql}',
                action_type='{$log_action}',
                action_detail='{$detail_sql}',
                actor_mb_id='{$actor_sql}',
                created_at=NOW()");

        alert('운영자 권한을 저장했습니다.', './admin_list.php?mb_id='.urlencode($dotty_mb_id));
    }

    if ($action === 'remove') {
        $existing = sql_fetch("SELECT admin_id FROM donuts_dotty_admins
            WHERE dotty_mb_id='{$dotty_sql}' AND operator_mb_id='{$operator_sql}' AND use_yn='Y'
            LIMIT 1");
        if (empty($existing['admin_id'])) alert('지정된 운영자를 찾을 수 없습니다.');

        sql_query("UPDATE donuts_dotty_admins
            SET use_yn='N', updated_at=NOW()
            WHERE admin_id='".(int)$existing['admin_id']."' AND dotty_mb_id='{$dotty_sql}'");

        sql_query("UPDATE donuts_dotty_members
            SET role_type='member', updated_at=NOW()
            WHERE dotty_mb_id='{$dotty_sql}' AND mb_id='{$operator_sql}'");

        $actor_sql = sql_real_escape_string((string)$member['mb_id']);
        sql_query("INSERT INTO donuts_dotty_admin_logs
            SET dotty_mb_id='{$dotty_sql}',
                operator_mb_id='{$operator_sql}',
                action_type='operator_remove',
                action_detail='',
                actor_mb_id='{$actor_sql}',
                created_at=NOW()");

        alert('운영자 지정을 해제했습니다.', './admin_list.php?mb_id='.urlencode($dotty_mb_id));
    }

    alert('올바르지 않은 요청입니다.');
}

/* 대표 도티 */
$owner = sql_fetch("SELECT mb_id, mb_name, mb_nick, mb_email, mb_today_login
    FROM {$g5['member_table']}
    WHERE mb_id='{$dotty_sql}'
    LIMIT 1");

/* 지정 운영자 */
$operators = array();
$res = sql_query("SELECT a.*, m.mb_name, m.mb_nick, m.mb_email, m.mb_today_login,
                         dm.dot_id
    FROM donuts_dotty_admins a
    LEFT JOIN {$g5['member_table']} m ON m.mb_id=a.operator_mb_id
    LEFT JOIN donuts_dotty_members dm
      ON dm.dotty_mb_id=a.dotty_mb_id AND dm.mb_id=a.operator_mb_id
    WHERE a.dotty_mb_id='{$dotty_sql}' AND a.use_yn='Y'
    ORDER BY a.admin_id ASC");
while ($r = sql_fetch_array($res)) $operators[] = $r;

/* 운영자로 추가 가능한 정상 가입 도트 */
$candidates = array();
$cres = sql_query("SELECT dm.mb_id, dm.dot_id, m.mb_name, m.mb_nick
    FROM donuts_dotty_members dm
    LEFT JOIN {$g5['member_table']} m ON m.mb_id=dm.mb_id
    LEFT JOIN donuts_dotty_admins a
      ON a.dotty_mb_id=dm.dotty_mb_id AND a.operator_mb_id=dm.mb_id AND a.use_yn='Y'
    WHERE dm.dotty_mb_id='{$dotty_sql}'
      AND dm.member_status='active'
      AND dm.mb_id<>'{$dotty_sql}'
      AND a.admin_id IS NULL
    ORDER BY dm.joined_at DESC, dm.id DESC");

while ($r = sql_fetch_array($cres)) $candidates[] = $r;

$operator_count = 1 + count($operators);
$admin_token = get_admin_token();

require_once '../admin.head.php';
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="mt-1 text-gray-400">도티가 지정한 운영자와 메뉴별 처리 권한을 관리합니다.</p>

        <button type="button" id="admin-add-open" class="shrink-0 rounded-lg bg-amber-300 px-3 py-2 text-gray-900 font-bold">
            <span>+ 운영자 추가</span>
        </button>
    </div>

    <div class="mt-4 rounded-lg bg-blue-50 p-3">
        <span class="text-blue-600 font-bold">권한 원칙</span>
        <span class="ml-2 text-gray-600">대표 도티는 전체 권한을 가지며, 지정 운영자는 부여된 메뉴만 조회·처리합니다. 토핑 배분·사업자 서류·정산·승계·운영자 관리 권한은 부여할 수 없습니다.</span>
    </div>

    <div class="mt-4 grid grid-cols-1 pc:grid-cols-2 gap-4">
        <section class="border border-gray-300 rounded-lg p-4">
            <h3 class="text-lg font-bold">운영자 <?php echo number_format($operator_count); ?>명</h3>

            <ul class="mt-4 space-y-3">
                <li class="flex items-center justify-between border border-gray-300 rounded-lg p-3">
                    <div class="flex items-center gap-3">
                        <div class="flex w-10 h-10 items-center justify-center rounded-full bg-gray-100 font-bold">
                            <?php echo al_e(mb_substr(($owner['mb_nick'] ?: $owner['mb_name'] ?: $owner['mb_id']), 0, 1, 'UTF-8')); ?>
                        </div>
                        <div class="space-y-2">
                            <p class="text-sm font-bold"><?php echo al_e($owner['mb_nick'] ?: $owner['mb_name'] ?: $owner['mb_id']); ?> · 대표 도티</p>
                            <p class="text-2xs text-gray-600">
                                <?php echo al_e($owner['mb_email'] ?: $owner['mb_id']); ?>
                                · 최근 접속 <?php echo !empty($owner['mb_today_login']) ? al_e($owner['mb_today_login']) : '-'; ?>
                            </p>
                            <span class="block w-fit rounded-lg text-2xs text-gray-600 font-normal bg-gray-100 px-2 py-1">전체 권한</span>
                        </div>
                    </div>
                    <button type="button" id="owner-view-open" class="border border-gray-300 rounded-lg px-3 py-2">
                        <span class="font-bold">보기</span>
                    </button>
                </li>

                <?php foreach ($operators as $op) {
                    $name = $op['mb_nick'] ?: $op['mb_name'] ?: $op['operator_mb_id'];
                    $labels = al_perm_labels($op['permissions']);
                ?>
                <li class="flex items-center justify-between border border-gray-300 rounded-lg p-3">
                    <div class="flex items-center gap-3">
                        <div class="flex w-10 h-10 items-center justify-center rounded-full bg-gray-100 font-bold"><?php echo al_e(mb_substr($name,0,1,'UTF-8')); ?></div>
                        <div class="space-y-2">
                            <p class="text-sm font-bold"><?php echo al_e($name); ?> · 지정 운영자</p>
                            <p class="text-2xs text-gray-600">
                                <?php echo al_e($op['mb_email'] ?: $op['operator_mb_id']); ?>
                                · 최근 접속 <?php echo !empty($op['mb_today_login']) ? al_e($op['mb_today_login']) : '-'; ?>
                            </p>
                            <div class="flex flex-wrap items-center gap-2">
                                <?php foreach ($labels as $label) { ?>
                                    <span class="block w-fit rounded-lg text-2xs text-gray-600 font-normal bg-gray-100 px-2 py-1"><?php echo al_e($label); ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <button type="button"
                        class="admin-edit-open border border-gray-300 rounded-lg px-3 py-2"
                        data-mb-id="<?php echo al_e($op['operator_mb_id']); ?>"
                        data-name="<?php echo al_e($name); ?>"
                        data-dot-id="<?php echo al_e($op['dot_id']); ?>"
                        data-permissions="<?php echo al_e($op['permissions']); ?>">
                        <span class="font-bold">권한 수정</span>
                    </button>
                </li>
                <?php } ?>

                <?php if (!$operators) { ?>
                    <li class="rounded-lg border border-dashed border-gray-300 p-5 text-center text-xs text-gray-500">지정 운영자가 없습니다.</li>
                <?php } ?>
            </ul>
        </section>

        <section class="border border-gray-300 rounded-lg p-4">
            <h3 class="text-lg font-bold">권한 구성 안내</h3>

            <div class="mt-4 space-y-3">
                <div class="rounded-lg bg-gray-100 p-3">
                    <span class="block text-2xs text-gray-600">가입 관리</span>
                    <p class="mt-2">가입 신청 답변 확인, 승인 및 거절 처리</p>
                </div>
                <div class="rounded-lg bg-gray-100 p-3">
                    <span class="block text-2xs text-gray-600">회원 조회</span>
                    <p class="mt-2">가입 도트 목록과 활동 상태 조회</p>
                </div>
                <div class="rounded-lg bg-gray-100 p-3">
                    <span class="block text-2xs text-gray-600">콘텐츠·공지</span>
                    <p class="mt-2">게시글 작성·관리, 공지 작성, 핀 추가·해제</p>
                </div>
                <div class="rounded-lg bg-gray-100 p-3">
                    <span class="block text-2xs text-gray-600">추천상품</span>
                    <p class="mt-2">추천상품 등록·해제</p>
                </div>
                <div class="rounded-lg text-red-600 font-normal bg-red-100 p-3">
                    <p>운영자 변경 이력은 활동 로그에 기록됩니다.</p>
                </div>
            </div>
        </section>
    </div>
</section>

<!-- 운영자 추가/수정 모달 -->
<div id="admin-add-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="admin-add-modal-backdrop" class="absolute inset-0 bg-black/40"></div>
    <div role="dialog" aria-modal="true" class="relative z-10 max-h-[90vh] w-full max-w-160 overflow-y-auto rounded-lg bg-white">
        <div class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="admin-add-modal-title" class="text-lg font-bold text-gray-900">지정 운영자 추가</h3>
            <button type="button" class="admin-modal-close flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">×</button>
        </div>

        <form id="admin-add-modal-form" method="post" class="p-4">
            <input type="hidden" name="token" value="<?php echo al_e($admin_token); ?>">
            <input type="hidden" name="mb_id" value="<?php echo al_e($dotty_mb_id); ?>">
            <input type="hidden" name="action" value="save">

            <div>
                <label for="admin-add-dotty" class="mb-2 block font-bold text-gray-900">가입 도트 선택</label>
                <select id="admin-add-dotty" name="operator_mb_id" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-3 text-gray-900">
                    <option value="" selected disabled>도트를 선택하세요</option>
                    <?php foreach ($candidates as $c) {
                        $cname = $c['mb_nick'] ?: $c['mb_name'] ?: $c['mb_id']; ?>
                        <option value="<?php echo al_e($c['mb_id']); ?>"><?php echo al_e($cname); ?> · <?php echo al_e($c['mb_name']); ?> (<?php echo al_e($c['dot_id'] ?: $c['mb_id']); ?>)</option>
                    <?php } ?>
                </select>
                <div id="admin-edit-selected" class="mt-2 rounded-lg bg-gray-100 p-3 text-xs text-gray-700" hidden></div>
            </div>

            <fieldset class="mt-4">
                <span class="font-bold text-gray-900">부여할 권한</span>
                <div class="mt-2 space-y-2">
                    <?php
                    $permission_items = array(
                        'join' => '가입 신청 검토 및 승인·거절',
                        'member' => '가입 도트 조회',
                        'content' => '콘텐츠 관리',
                        'notice' => '공지 작성과 핀 관리',
                        'product' => '추천상품 등록·해제'
                    );
                    foreach ($permission_items as $key => $label) { ?>
                    <label class="flex cursor-pointer items-center gap-3 rounded-lg bg-gray-100 p-4">
                        <input type="checkbox" name="permissions[]" value="<?php echo al_e($key); ?>" id="admin-add-permission-<?php echo al_e($key); ?>" class="h-4 w-4">
                        <span><?php echo al_e($label); ?></span>
                    </label>
                    <?php } ?>
                </div>
            </fieldset>

            <div class="mt-4 rounded-lg bg-red-100 p-3 text-2xs text-red-600">
                <p>현재 도넛에 정상 가입한 도트만 지정 운영자로 추가할 수 있습니다. 토핑 배분·정산·승계·운영자 관리 권한은 부여할 수 없습니다.</p>
            </div>
        </form>

        <div class="sticky bottom-0 z-10 flex justify-between gap-2 border-t border-gray-300 bg-white p-4">
            <form id="admin-remove-form" method="post" hidden onsubmit="return confirm('이 운영자의 지정을 해제하시겠습니까?');">
                <input type="hidden" name="token" value="<?php echo al_e($admin_token); ?>">
                <input type="hidden" name="mb_id" value="<?php echo al_e($dotty_mb_id); ?>">
                <input type="hidden" name="action" value="remove">
                <input type="hidden" name="operator_mb_id" id="admin-remove-mb-id">
                <button type="submit" class="rounded-lg border border-red-300 bg-white px-4 py-3 text-sm font-bold text-red-600">운영자 해제</button>
            </form>
            <div class="ml-auto flex gap-2">
                <button type="button" class="admin-modal-close rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-bold text-gray-900">취소</button>
                <button type="submit" form="admin-add-modal-form" class="rounded-lg bg-amber-300 px-4 py-3 text-sm font-bold text-gray-900">권한 저장</button>
            </div>
        </div>
    </div>
</div>

<!-- 대표 도티 권한 보기 -->
<div id="owner-view-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div class="owner-view-close absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 w-full max-w-120 rounded-lg bg-white">
        <div class="flex items-center justify-between border-b border-gray-300 p-4">
            <h3 class="text-lg font-bold">대표 도티 권한</h3>
            <button type="button" class="owner-view-close text-xl">×</button>
        </div>
        <div class="space-y-3 p-4">
            <p class="font-bold"><?php echo al_e($owner['mb_nick'] ?: $owner['mb_name'] ?: $owner['mb_id']); ?></p>
            <div class="rounded-lg bg-blue-50 p-3 text-sm text-blue-700">대표 도티는 도넛 운영 전체 권한을 가지며 지정 운영자 권한 제한의 대상이 아닙니다.</div>
            <div class="flex flex-wrap gap-2">
                <?php foreach (array('가입 관리','회원 조회','콘텐츠 관리','공지·핀 관리','추천 상품','운영자 관리') as $label) { ?>
                    <span class="rounded-lg bg-gray-100 px-2 py-1 text-xs"><?php echo al_e($label); ?></span>
                <?php } ?>
            </div>
        </div>
        <div class="flex justify-end border-t border-gray-300 p-4"><button type="button" class="owner-view-close rounded-lg border border-gray-300 px-4 py-2 font-bold">닫기</button></div>
    </div>
</div>

<script>
function resetAdminModal() {
    $('#admin-add-modal-form')[0].reset();
    $('#admin-add-dotty').prop('disabled', false).prop('hidden', false);
    $('#admin-edit-selected').prop('hidden', true).text('');
    $('#admin-remove-form').prop('hidden', true);
    $('#admin-add-modal-title').text('지정 운영자 추가');
}

$('#admin-add-open').on('click', function() {
    resetAdminModal();
    $('#admin-add-modal').prop('hidden', false);
});

$('.admin-edit-open').on('click', function() {
    resetAdminModal();

    const $b = $(this);
    const mbId = String($b.attr('data-mb-id') || '');
    const permissions = String($b.attr('data-permissions') || '').split(',');

    $('#admin-add-modal-title').text('운영자 권한 수정');
    $('#admin-add-dotty').append($('<option>', {value: mbId, text: $b.attr('data-name') + ' (' + ($b.attr('data-dot-id') || mbId) + ')'}));
    $('#admin-add-dotty').val(mbId).prop('disabled', false).prop('hidden', true);
    $('#admin-edit-selected').text($b.attr('data-name') + ' · ' + ($b.attr('data-dot-id') || mbId)).prop('hidden', false);

    permissions.forEach(function(p) {
        if (p) $('#admin-add-permission-' + p).prop('checked', true);
    });

    $('#admin-remove-mb-id').val(mbId);
    $('#admin-remove-form').prop('hidden', false);
    $('#admin-add-modal').prop('hidden', false);
});

$('.admin-modal-close, #admin-add-modal-backdrop').on('click', function() {
    $('#admin-add-modal').prop('hidden', true);
});

$('#owner-view-open').on('click', function() {
    $('#owner-view-modal').prop('hidden', false);
});
$('.owner-view-close').on('click', function() {
    $('#owner-view-modal').prop('hidden', true);
});
</script>

<?php include_once(G5_ADMIN_PATH . '/admin.tail.php'); ?>

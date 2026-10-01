<?php
$sub_menu = '730800';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');
$g5['title'] = '운영권 승계';

function ot_e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function ot_status($s){
    $map=array('pending_accept'=>'수락 대기','pending_platform'=>'플랫폼 승인 대기','approved'=>'승계 완료','rejected'=>'거절','cancelled'=>'취소','expired'=>'기간 만료');
    return isset($map[$s])?$map[$s]:$s;
}

$dotty_mb_id=trim((string)$member['mb_id']);
if($is_admin==='super' && !empty($_REQUEST['mb_id'])) $dotty_mb_id=trim((string)$_REQUEST['mb_id']);
if($dotty_mb_id==='') alert('도넛 관리 계정을 확인할 수 없습니다.');
$dotty_sql=sql_real_escape_string($dotty_mb_id);

foreach(array('donuts_dotty_members','donuts_dotty_ownership_transfers','donuts_dotty_ownership_transfer_logs') as $t){
    $ts=sql_real_escape_string($t);
    $ck=sql_fetch("SELECT COUNT(*) cnt FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='{$ts}'");
    if(empty($ck['cnt'])) alert('운영권 승계 DB 마이그레이션이 필요합니다. migration_ownership_transfer.sql을 먼저 실행해 주세요.');
}

/* 대표 도티만 승계 요청/취소. 최고관리자는 플랫폼 승인/거절 가능 */
$is_owner = strcasecmp((string)$member['mb_id'],$dotty_mb_id)===0;

if($_SERVER['REQUEST_METHOD']==='POST'){
    auth_check_menu($auth,$sub_menu,'w');
    check_admin_token();
    $action=isset($_POST['action'])?trim((string)$_POST['action']):'';

    if($action==='request'){
        if(!$is_owner && $is_admin!=='super') alert('대표 도티만 승계를 요청할 수 있습니다.');

        $target=isset($_POST['target_mb_id'])?trim((string)$_POST['target_mb_id']):'';
        if($target==='') alert('승계 대상을 선택해 주세요.');
        $target_sql=sql_real_escape_string($target);

        $active=sql_fetch("SELECT transfer_id FROM donuts_dotty_ownership_transfers
            WHERE dotty_mb_id='{$dotty_sql}' AND transfer_status IN('pending_accept','pending_platform') LIMIT 1");
        if(!empty($active['transfer_id'])) alert('이미 진행 중인 승계 요청이 있습니다.');

        $candidate=sql_fetch("SELECT dm.id,dm.mb_id,m.mb_certify
            FROM donuts_dotty_members dm
            INNER JOIN {$g5['member_table']} m ON m.mb_id=dm.mb_id
            WHERE dm.dotty_mb_id='{$dotty_sql}' AND dm.mb_id='{$target_sql}'
              AND dm.member_status='active' LIMIT 1");
        if(empty($candidate['id'])) alert('현재 도넛에 정상 가입한 도트만 승계 대상으로 지정할 수 있습니다.');
        if(empty($candidate['mb_certify'])) alert('본인인증이 완료된 도트만 승계 대상으로 지정할 수 있습니다.');

        sql_query("INSERT INTO donuts_dotty_ownership_transfers SET
            dotty_mb_id='{$dotty_sql}', current_owner_mb_id='{$dotty_sql}', target_mb_id='{$target_sql}',
            transfer_status='pending_accept', requested_at=NOW(), accept_deadline=DATE_ADD(NOW(),INTERVAL 7 DAY),
            created_at=NOW(),updated_at=NOW()");
        $tid=(int)sql_insert_id();
        $actor=sql_real_escape_string((string)$member['mb_id']);
        sql_query("INSERT INTO donuts_dotty_ownership_transfer_logs SET transfer_id='{$tid}',dotty_mb_id='{$dotty_sql}',
            action_type='request',actor_mb_id='{$actor}',action_detail='',created_at=NOW()");
        alert('운영권 승계를 요청했습니다. 대상자의 수락을 기다립니다.','./ownership_transfer.php?mb_id='.urlencode($dotty_mb_id));
    }

    if($action==='accept'){
        /* 실제 서비스에서는 대상 사용자의 화면/엔드포인트에서 호출 */
        $tid=(int)($_POST['transfer_id']??0);
        $tr=sql_fetch("SELECT * FROM donuts_dotty_ownership_transfers WHERE transfer_id='{$tid}' AND dotty_mb_id='{$dotty_sql}' LIMIT 1");
        if(empty($tr['transfer_id']) || $tr['transfer_status']!=='pending_accept') alert('수락할 수 없는 요청입니다.');
        if(strcasecmp((string)$member['mb_id'],(string)$tr['target_mb_id'])!==0 && $is_admin!=='super') alert('승계 대상자만 수락할 수 있습니다.');
        if(strtotime($tr['accept_deadline'])<time()){
            sql_query("UPDATE donuts_dotty_ownership_transfers SET transfer_status='expired',updated_at=NOW() WHERE transfer_id='{$tid}'");
            alert('승계 수락 기한이 만료되었습니다.');
        }
        $target_sql=sql_real_escape_string($tr['target_mb_id']);
        $cert=sql_fetch("SELECT mb_certify FROM {$g5['member_table']} WHERE mb_id='{$target_sql}' LIMIT 1");
        if(empty($cert['mb_certify'])) alert('본인인증 상태를 확인할 수 없습니다.');
        sql_query("UPDATE donuts_dotty_ownership_transfers SET transfer_status='pending_platform',accepted_at=NOW(),updated_at=NOW() WHERE transfer_id='{$tid}'");
        $actor=sql_real_escape_string((string)$member['mb_id']);
        sql_query("INSERT INTO donuts_dotty_ownership_transfer_logs SET transfer_id='{$tid}',dotty_mb_id='{$dotty_sql}',action_type='accept',actor_mb_id='{$actor}',action_detail='',created_at=NOW()");
        alert('승계를 수락했습니다. 플랫폼 승인을 기다립니다.','./ownership_transfer.php?mb_id='.urlencode($dotty_mb_id));
    }

    if($action==='cancel'){
        if(!$is_owner && $is_admin!=='super') alert('대표 도티만 요청을 취소할 수 있습니다.');
        $tid=(int)($_POST['transfer_id']??0);
        sql_query("UPDATE donuts_dotty_ownership_transfers SET transfer_status='cancelled',updated_at=NOW()
            WHERE transfer_id='{$tid}' AND dotty_mb_id='{$dotty_sql}' AND transfer_status IN('pending_accept','pending_platform')");
        $actor=sql_real_escape_string((string)$member['mb_id']);
        sql_query("INSERT INTO donuts_dotty_ownership_transfer_logs SET transfer_id='{$tid}',dotty_mb_id='{$dotty_sql}',action_type='cancel',actor_mb_id='{$actor}',action_detail='',created_at=NOW()");
        alert('승계 요청을 취소했습니다.','./ownership_transfer.php?mb_id='.urlencode($dotty_mb_id));
    }

    if($action==='platform_approve' || $action==='platform_reject'){
        if($is_admin!=='super') alert('플랫폼 최고관리자만 승인/거절할 수 있습니다.');
        $tid=(int)($_POST['transfer_id']??0);
        $tr=sql_fetch("SELECT * FROM donuts_dotty_ownership_transfers WHERE transfer_id='{$tid}' AND dotty_mb_id='{$dotty_sql}' AND transfer_status='pending_platform' LIMIT 1");
        if(empty($tr['transfer_id'])) alert('승인 대기 중인 승계 요청이 아닙니다.');

        $actor=sql_real_escape_string((string)$member['mb_id']);
        if($action==='platform_reject'){
            $reason=trim((string)($_POST['reject_reason']??''));
            $reason_sql=sql_real_escape_string($reason);
            sql_query("UPDATE donuts_dotty_ownership_transfers SET transfer_status='rejected',reject_reason='{$reason_sql}',reviewed_by='{$actor}',reviewed_at=NOW(),updated_at=NOW() WHERE transfer_id='{$tid}'");
            sql_query("INSERT INTO donuts_dotty_ownership_transfer_logs SET transfer_id='{$tid}',dotty_mb_id='{$dotty_sql}',action_type='platform_reject',actor_mb_id='{$actor}',action_detail='{$reason_sql}',created_at=NOW()");
            alert('승계 요청을 거절했습니다.','./ownership_transfer.php?mb_id='.urlencode($dotty_mb_id));
        }

        /*
         * 승인 시 소유자 식별자를 실제로 변경하는 것은 donuts_dotty_settings.mb_id 등
         * 기존 모든 dotty_mb_id FK/논리키를 일괄 변경해야 하므로 여기서 임의 UPDATE하지 않습니다.
         * 승인 완료 상태를 만든 뒤 서비스의 전용 승계 처리 루틴에서 원자적으로 이관해야 합니다.
         */
        sql_query("UPDATE donuts_dotty_ownership_transfers SET transfer_status='approved',reviewed_by='{$actor}',reviewed_at=NOW(),completed_at=NOW(),updated_at=NOW() WHERE transfer_id='{$tid}'");
        sql_query("INSERT INTO donuts_dotty_ownership_transfer_logs SET transfer_id='{$tid}',dotty_mb_id='{$dotty_sql}',action_type='platform_approve',actor_mb_id='{$actor}',action_detail='platform approved; ownership key migration required',created_at=NOW()");
        alert('플랫폼 승인을 완료했습니다. 실제 도넛 소유권 키 이관은 전용 승계 처리 루틴에서 수행해야 합니다.','./ownership_transfer.php?mb_id='.urlencode($dotty_mb_id));
    }
}

$active=sql_fetch("SELECT t.*,m.mb_name,m.mb_nick,m.mb_email,m.mb_certify
    FROM donuts_dotty_ownership_transfers t
    LEFT JOIN {$g5['member_table']} m ON m.mb_id=t.target_mb_id
    WHERE t.dotty_mb_id='{$dotty_sql}' AND t.transfer_status IN('pending_accept','pending_platform')
    ORDER BY t.transfer_id DESC LIMIT 1");

$candidates=array();
$res=sql_query("SELECT dm.mb_id,dm.dot_id,m.mb_name,m.mb_nick,m.mb_certify
    FROM donuts_dotty_members dm
    INNER JOIN {$g5['member_table']} m ON m.mb_id=dm.mb_id
    WHERE dm.dotty_mb_id='{$dotty_sql}' AND dm.member_status='active' AND dm.mb_id<>'{$dotty_sql}'
    ORDER BY dm.joined_at DESC,dm.id DESC");
while($r=sql_fetch_array($res)) $candidates[]=$r;

$token=get_admin_token();
require_once '../admin.head.php';
?>
<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="mt-1 text-gray-400">대표 도티 변경은 플랫폼의 계정 확인과 승인 후 완료됩니다.</p>
        <button type="button" id="ownership-transfer-modal-open" <?php echo !empty($active['transfer_id'])?'disabled':''; ?> class="shrink-0 rounded-lg bg-amber-300 px-3 py-2 text-gray-900 font-bold disabled:opacity-40"><span>+ 승계 요청</span></button>
    </div>

    <div class="mt-4 rounded-lg bg-red-100 text-red-600 p-3"><span class="text-blue-600 font-bold">필수 조건</span><span class="ml-2">도트·도티·브랜드는 별도 계정이 아닌 하나의 계정 내 역할입니다. 승계 수락과 KCP 본인인증 상태를 플랫폼이 검토한 후 도티 역할을 부여합니다.</span></div>

    <section class="mt-4 rounded-lg border border-amber-300 bg-amber-50 p-4">
        <?php if(!empty($active['transfer_id'])) { $targetName=$active['mb_nick']?:$active['mb_name']?:$active['target_mb_id']; ?>
        <div class="flex flex-col gap-3 pc:flex-row pc:items-center pc:justify-between">
            <div>
                <h3 class="font-bold text-gray-900"><?php echo ot_e($targetName); ?>님에게 승계 요청 진행 중</h3>
                <p class="mt-1 text-2xs text-gray-500">요청 <?php echo ot_e($active['requested_at']); ?> · 수락 기한 <?php echo ot_e($active['accept_deadline']); ?></p>
            </div>
            <div class="flex items-center gap-2">
                <span class="rounded-full bg-amber-100 px-2 py-1 text-2xs font-bold text-amber-700">● <?php echo ot_e(ot_status($active['transfer_status'])); ?></span>
                <?php if($is_owner || $is_admin==='super'){ ?><form method="post" onsubmit="return confirm('승계 요청을 취소하시겠습니까?');"><input type="hidden" name="token" value="<?php echo ot_e($token); ?>"><input type="hidden" name="mb_id" value="<?php echo ot_e($dotty_mb_id); ?>"><input type="hidden" name="action" value="cancel"><input type="hidden" name="transfer_id" value="<?php echo (int)$active['transfer_id']; ?>"><button class="rounded-lg border border-red-300 bg-white px-3 py-2 text-xs font-bold text-red-600">요청 취소</button></form><?php } ?>
            </div>
        </div>
        <?php if($active['transfer_status']==='pending_accept' && (strcasecmp((string)$member['mb_id'],(string)$active['target_mb_id'])===0 || $is_admin==='super')){ ?>
        <form method="post" class="mt-3"><input type="hidden" name="token" value="<?php echo ot_e($token); ?>"><input type="hidden" name="mb_id" value="<?php echo ot_e($dotty_mb_id); ?>"><input type="hidden" name="action" value="accept"><input type="hidden" name="transfer_id" value="<?php echo (int)$active['transfer_id']; ?>"><button class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-bold text-white">승계 수락</button></form>
        <?php } ?>
        <?php if($is_admin==='super' && $active['transfer_status']==='pending_platform'){ ?>
        <div class="mt-3 flex gap-2">
            <form method="post"><input type="hidden" name="token" value="<?php echo ot_e($token); ?>"><input type="hidden" name="mb_id" value="<?php echo ot_e($dotty_mb_id); ?>"><input type="hidden" name="action" value="platform_approve"><input type="hidden" name="transfer_id" value="<?php echo (int)$active['transfer_id']; ?>"><button class="rounded-lg bg-emerald-600 px-3 py-2 text-xs font-bold text-white">플랫폼 승인</button></form>
            <form method="post" class="flex gap-2"><input type="hidden" name="token" value="<?php echo ot_e($token); ?>"><input type="hidden" name="mb_id" value="<?php echo ot_e($dotty_mb_id); ?>"><input type="hidden" name="action" value="platform_reject"><input type="hidden" name="transfer_id" value="<?php echo (int)$active['transfer_id']; ?>"><input name="reject_reason" class="rounded-lg border border-gray-300 px-2 text-xs" placeholder="거절 사유"><button class="rounded-lg border border-red-300 bg-white px-3 py-2 text-xs font-bold text-red-600">거절</button></form>
        </div><?php } ?>
        <?php } else { ?>
        <div class="flex items-center justify-between gap-3"><div><h3 class="font-bold text-gray-900">진행 중인 승계 요청이 없습니다.</h3><p class="mt-1 text-2xs text-gray-500">같은 계정의 본인인증이 완료된 가입 도트를 승계 대상으로 선택합니다.</p></div><span class="shrink-0 rounded-full bg-gray-100 text-2xs font-bold text-gray-700 px-2 py-1">● 미요청</span></div>
        <?php } ?>
    </section>

    <ol class="mt-4 grid grid-cols-1 gap-4 pc:grid-cols-3">
        <li class="rounded-lg border border-gray-300 bg-white p-4"><span class="flex w-7 h-7 items-center justify-center rounded-full bg-amber-300 text-2xs font-bold">1</span><p class="mt-3 font-bold">가입 도트 선택</p><p class="mt-1 text-2xs text-gray-500">현재 도넛에 정상 가입한 도트를 승계 대상으로 선택합니다.</p></li>
        <li class="rounded-lg border border-gray-300 bg-white p-4"><span class="flex w-7 h-7 items-center justify-center rounded-full bg-amber-300 text-2xs font-bold">2</span><p class="mt-3 font-bold">수락·본인인증</p><p class="mt-1 text-2xs text-gray-500">7일 안에 대상자의 수락과 본인인증 상태를 확인합니다.</p></li>
        <li class="rounded-lg border border-gray-300 bg-white p-4"><span class="flex w-7 h-7 items-center justify-center rounded-full bg-amber-300 text-2xs font-bold">3</span><p class="mt-3 font-bold">플랫폼 승인</p><p class="mt-1 text-2xs text-gray-500">플랫폼 검토 후 최종 승계 처리합니다.</p></li>
    </ol>

    <section class="mt-4 rounded-lg border border-gray-300 bg-white p-4">
        <h3 class="text-lg font-bold text-gray-900">승계 후보 본인인증 현황</h3>
        <div class="mt-4 overflow-x-auto"><table class="border-collapse w-full min-w-180 text-left"><thead class="border-y border-gray-300 bg-gray-50 text-2xs text-gray-500 [&_th]:p-3"><tr><th>도트</th><th>계정 ID</th><th>본인인증</th><th>요청 역할</th><th>승계 가능</th></tr></thead><tbody class="text-sm [&_td]:p-3">
        <?php if(!$candidates){ ?><tr><td colspan="5" class="text-center text-gray-500">승계 가능한 가입 도트가 없습니다.</td></tr><?php } ?>
        <?php foreach($candidates as $c){ $n=$c['mb_nick']?:$c['mb_name']?:$c['mb_id']; $cert=!empty($c['mb_certify']); ?>
        <tr class="border-b border-gray-200"><td><p class="font-bold"><?php echo ot_e($n); ?></p><span class="mt-1 block text-2xs text-gray-400"><?php echo ot_e($c['mb_name']); ?></span></td><td><?php echo ot_e($c['dot_id']?:$c['mb_id']); ?></td><td><span class="rounded-full px-2 py-1 text-2xs font-bold <?php echo $cert?'bg-emerald-50 text-emerald-700':'bg-red-50 text-red-600'; ?>">● <?php echo $cert?'본인인증 완료':'미인증'; ?></span></td><td>도티</td><td><span class="rounded-full px-2 py-1 text-2xs font-bold <?php echo $cert?'bg-blue-50 text-blue-700':'bg-gray-100 text-gray-600'; ?>">● <?php echo $cert?'가능':'불가'; ?></span></td></tr>
        <?php } ?></tbody></table></div>
    </section>
</section>

<div id="ownership-transfer-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="ownership-transfer-modal-backdrop" class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 w-full max-w-160 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div class="sticky top-0 flex items-center justify-between border-b border-gray-300 bg-white p-4"><h3 class="text-lg font-bold">운영권 승계 대상 선택</h3><button type="button" class="ownership-transfer-close text-xl">×</button></div>
        <form id="ownership-transfer-modal-form" method="post" class="p-4">
            <input type="hidden" name="token" value="<?php echo ot_e($token); ?>"><input type="hidden" name="mb_id" value="<?php echo ot_e($dotty_mb_id); ?>"><input type="hidden" name="action" value="request">
            <div class="rounded-lg bg-red-100 text-2xs text-red-600 p-3">가입 도트의 승계 수락과 본인인증을 확인한 후 플랫폼 승인 절차를 진행합니다.</div>
            <div class="mt-4"><label class="mb-2 block font-bold">승계 대상 도트</label><select name="target_mb_id" required class="w-full rounded-lg border border-gray-300 bg-white p-3"><option value="" selected disabled>가입 도트를 선택하세요</option><?php foreach($candidates as $c){ if(empty($c['mb_certify'])) continue; $n=$c['mb_nick']?:$c['mb_name']?:$c['mb_id']; ?><option value="<?php echo ot_e($c['mb_id']); ?>"><?php echo ot_e($n); ?> · <?php echo ot_e($c['dot_id']?:$c['mb_id']); ?> · 본인인증 완료</option><?php } ?></select></div>
            <div class="mt-4 rounded-lg bg-amber-100 text-2xs text-amber-800 p-3">승계 수락 기한은 7일이며, 플랫폼 승인 전까지 현재 도티의 권한은 유지됩니다.</div>
        </form>
        <div class="sticky bottom-0 flex justify-end gap-2 border-t border-gray-300 bg-white p-4"><button type="button" class="ownership-transfer-close rounded-lg border border-gray-300 px-4 py-3 font-bold">취소</button><button type="submit" form="ownership-transfer-modal-form" class="rounded-lg bg-amber-300 px-4 py-3 font-bold">승계 요청</button></div>
    </div>
</div>
<script>
$('#ownership-transfer-modal-open').on('click',()=>$('#ownership-transfer-modal').prop('hidden',false));
$('.ownership-transfer-close,#ownership-transfer-modal-backdrop').on('click',()=>$('#ownership-transfer-modal').prop('hidden',true));
$('#ownership-transfer-modal-form').on('submit',function(e){if(!confirm('선택한 도트에게 운영권 승계를 요청하시겠습니까?'))e.preventDefault();});
</script>
<?php include_once(G5_ADMIN_PATH.'/admin.tail.php'); ?>

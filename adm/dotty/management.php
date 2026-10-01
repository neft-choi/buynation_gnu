<?php
$sub_menu='730100';
include_once('./_common.php');
auth_check_menu($auth,$sub_menu,'r');
$g5['title']='도티 통합 관리';

function mg_e($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function mg_table($name){
    $n=sql_real_escape_string($name);
    $r=sql_fetch("SELECT COUNT(*) cnt FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='{$n}'");
    return !empty($r['cnt']);
}
function mg_col($table,$col){
    $t=sql_real_escape_string($table); $c=sql_real_escape_string($col);
    $r=sql_fetch("SELECT COUNT(*) cnt FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='{$t}' AND COLUMN_NAME='{$c}'");
    return !empty($r['cnt']);
}
function mg_count($sql){$r=sql_fetch($sql);return (int)($r['cnt']??0);}

$owner=trim((string)$member['mb_id']);
if($owner==='') alert('로그인 계정을 확인할 수 없습니다.');
$owner_sql=sql_real_escape_string($owner);

/* 관리 도넛: settings의 mb_id를 소유자 키로 사용 */
$donuts=array();
if(mg_table('donuts_dotty_settings')){
    $cols=array();
    foreach(array('mb_id','dotty_name','title','dotty_category','short_intro','business_status','settlement_available','available_topping','contribution_topping') as $c)
        $cols[$c]=mg_col('donuts_dotty_settings',$c);
    $nameExpr=$cols['dotty_name']?'s.dotty_name':($cols['title']?'s.title':'s.mb_id');
    $catExpr=$cols['dotty_category']?'s.dotty_category':"''";
    $where=$is_admin==='super' ? '1=1' : "s.mb_id='{$owner_sql}'";
    $r=sql_query("SELECT s.mb_id,{$nameExpr} donut_name,{$catExpr} category FROM donuts_dotty_settings s WHERE {$where} ORDER BY s.mb_id");
    while($d=sql_fetch_array($r)) $donuts[$d['mb_id']]=$d;
}
/* settings가 아직 없으면 현재 계정을 최소 1개 도넛으로 노출 */
if(!$donuts) $donuts[$owner]=array('mb_id'=>$owner,'donut_name'=>$member['mb_nick']?:$member['mb_name']?:$owner,'category'=>'');

$ids=array_keys($donuts);
foreach($donuts as $id=>&$d){
    $id_sql=sql_real_escape_string($id);
    $d['members']=mg_table('donuts_dotty_members')?mg_count("SELECT COUNT(*) cnt FROM donuts_dotty_members WHERE dotty_mb_id='{$id_sql}' AND member_status='active'"):0;
    $d['join_pending']=mg_table('donuts_dotty_join_requests')?mg_count("SELECT COUNT(*) cnt FROM donuts_dotty_join_requests WHERE dotty_mb_id='{$id_sql}' AND request_status='pending'"):0;
    $d['restricted']=mg_table('donuts_dotty_members')?mg_count("SELECT COUNT(*) cnt FROM donuts_dotty_members WHERE dotty_mb_id='{$id_sql}' AND member_status='restricted'"):0;
    $d['ownership']=mg_table('donuts_dotty_ownership_transfers')?mg_count("SELECT COUNT(*) cnt FROM donuts_dotty_ownership_transfers WHERE dotty_mb_id='{$id_sql}' AND transfer_status IN('pending_accept','pending_platform')"):0;
    $d['pending']=$d['join_pending'];
    $d['progress']=$d['ownership'];
    $d['warning']=$d['restricted'];
}
unset($d);

$sum_members=$sum_pending=$sum_progress=$sum_warning=0;
foreach($donuts as $d){$sum_members+=$d['members'];$sum_pending+=$d['pending'];$sum_progress+=$d['progress'];$sum_warning+=$d['warning'];}

/* 쪽지 */
if(!mg_table('donuts_dotty_management_messages')){
    alert('통합 관리 DB 마이그레이션이 필요합니다. migration_management.sql을 먼저 실행해 주세요.');
}
$allowed_ids=array_keys($donuts);
if($_SERVER['REQUEST_METHOD']==='POST'){
    auth_check_menu($auth,$sub_menu,'w');
    check_admin_token();
    $action=trim((string)($_POST['action']??''));
    if($action==='send_message'){
        $dotty=trim((string)($_POST['dotty_mb_id']??''));
        $partner=trim((string)($_POST['partner_mb_id']??''));
        $body=trim((string)($_POST['message_content']??''));
        if(!isset($donuts[$dotty])) alert('관리 권한이 없는 도넛입니다.');
        if($partner===''||$body==='') alert('상대방과 메시지 내용을 입력해 주세요.');
        if(mb_strlen($body)>2000) alert('메시지는 2,000자 이하로 입력해 주세요.');
        $ds=sql_real_escape_string($dotty);$ps=sql_real_escape_string($partner);$bs=sql_real_escape_string($body);$as=sql_real_escape_string($owner);
        sql_query("INSERT INTO donuts_dotty_management_messages SET dotty_mb_id='{$ds}',partner_mb_id='{$ps}',sender_mb_id='{$as}',message_content='{$bs}',is_read=0,created_at=NOW()");
        alert('쪽지를 전송했습니다.','./management.php?tab=message&conversation='.urlencode($dotty.'|'.$partner));
    }
    if($action==='read_conversation'){
        $dotty=trim((string)($_POST['dotty_mb_id']??''));$partner=trim((string)($_POST['partner_mb_id']??''));
        if(isset($donuts[$dotty])){
            $ds=sql_real_escape_string($dotty);$ps=sql_real_escape_string($partner);$as=sql_real_escape_string($owner);
            sql_query("UPDATE donuts_dotty_management_messages SET is_read=1,read_at=NOW() WHERE dotty_mb_id='{$ds}' AND partner_mb_id='{$ps}' AND sender_mb_id<>'{$as}' AND is_read=0");
        }
        exit;
    }
}

/* 최근 활동 */
$logs=array();
if(mg_table('donuts_dotty_activity_logs')){
    $escaped=array_map('sql_real_escape_string',$ids);
    $in="'".implode("','",$escaped)."'";
    $r=sql_query("SELECT * FROM donuts_dotty_activity_logs WHERE dotty_mb_id IN({$in}) ORDER BY log_id DESC LIMIT 10");
    while($x=sql_fetch_array($r)) $logs[]=$x;
}

/* 대화 목록 */
$convs=array(); $unread=0;
$escaped=array_map('sql_real_escape_string',$ids);$in="'".implode("','",$escaped)."'";
$r=sql_query("SELECT m.dotty_mb_id,m.partner_mb_id,
 MAX(m.message_id) last_id,
 SUM(CASE WHEN m.sender_mb_id<>'{$owner_sql}' AND m.is_read=0 THEN 1 ELSE 0 END) unread_count
 FROM donuts_dotty_management_messages m
 WHERE m.dotty_mb_id IN({$in})
 GROUP BY m.dotty_mb_id,m.partner_mb_id ORDER BY last_id DESC");
while($c=sql_fetch_array($r)){
    $last=sql_fetch("SELECT * FROM donuts_dotty_management_messages WHERE message_id=".(int)$c['last_id']);
    $p=sql_fetch("SELECT mb_nick,mb_name FROM {$g5['member_table']} WHERE mb_id='".sql_real_escape_string($c['partner_mb_id'])."' LIMIT 1");
    $c['partner_name']=$p['mb_nick']?:$p['mb_name']?:$c['partner_mb_id'];
    $c['last']=$last;$unread+=(int)$c['unread_count'];$convs[]=$c;
}
$selected_key=trim((string)($_GET['conversation']??''));
if($selected_key==='' && $convs) $selected_key=$convs[0]['dotty_mb_id'].'|'.$convs[0]['partner_mb_id'];
$selected=null;$messages=array();
foreach($convs as $c) if($selected_key===$c['dotty_mb_id'].'|'.$c['partner_mb_id']) {$selected=$c;break;}
if($selected){
    $ds=sql_real_escape_string($selected['dotty_mb_id']);$ps=sql_real_escape_string($selected['partner_mb_id']);
    $r=sql_query("SELECT * FROM donuts_dotty_management_messages WHERE dotty_mb_id='{$ds}' AND partner_mb_id='{$ps}' ORDER BY message_id ASC LIMIT 500");
    while($m=sql_fetch_array($r)) $messages[]=$m;
}

$token=get_admin_token();
require_once '../admin.head.php';
$tab=trim((string)($_GET['tab']??'home'));
?>
<div role="tablist" class="fixed top-13 right-4 z-100 flex items-center gap-1 w-fit rounded-lg bg-gray-100 p-1">
<?php foreach(array('home'=>'통합 홈','task'=>'통합 업무함','message'=>'통합 쪽지','donut'=>'도넛 목록') as $k=>$v){ ?>
<button type="button" data-tab="<?php echo $k;?>" class="management-tab rounded-md px-3 py-2 text-2xs font-bold <?php echo $tab===$k?'bg-gray-900 text-white':'text-gray-500';?>"><?php echo $v;?></button>
<?php } ?>
</div>

<section id="panel-home" class="management-panel" <?php echo $tab==='home'?'':'hidden';?>>
<p class="text-gray-600">관리 중인 모든 도넛의 실제 운영 상태와 도티가 해야 할 일을 한곳에서 확인합니다.</p>
<div class="grid grid-cols-2 pc:grid-cols-4 gap-4 mt-4">
<?php foreach(array(array('처리 필요',$sum_pending,'도티가 직접 처리'),array('진행 확인',$sum_progress,'상대방·플랫폼 결과 대기'),array('주의·예외',$sum_warning,'제한·보완 상태 확인'),array('읽지 않은 쪽지',$unread,'열면 읽지 않음 해제')) as $s){?>
<div class="border border-gray-300 rounded-lg p-3"><p class="text-gray-500"><?php echo $s[0];?></p><p class="text-2xl font-bold mt-3"><?php echo number_format($s[1]);?><span class="text-xs ml-0.5">건</span></p><p class="text-gray-500 mt-2"><?php echo $s[2];?></p></div>
<?php }?>
</div>
<div class="grid grid-cols-2 pc:grid-cols-3 gap-4 mt-4">
<div class="border rounded-lg p-3"><p class="text-gray-500">운영 도넛</p><p class="text-xl font-bold mt-2"><?php echo count($donuts);?>개</p></div>
<div class="border rounded-lg p-3"><p class="text-gray-500">가입 도트 합계</p><p class="text-xl font-bold mt-2"><?php echo number_format($sum_members);?>명</p></div>
<div class="border rounded-lg p-3"><p class="text-gray-500">전체 운영 업무</p><p class="text-xl font-bold mt-2"><?php echo number_format($sum_pending+$sum_progress+$sum_warning);?>건</p></div>
</div>
<h3 class="text-xl font-bold mt-6">최근 관리자 활동</h3>
<div class="border rounded-lg overflow-x-auto mt-3"><table class="w-full text-xs"><tbody class="divide-y">
<?php if(!$logs){?><tr><td class="p-4 text-center text-gray-500">기록된 활동이 없습니다.</td></tr><?php }?>
<?php foreach($logs as $l){?><tr><td class="p-3"><?php echo mg_e($l['created_at']);?></td><td class="p-3 font-bold"><?php echo mg_e($donuts[$l['dotty_mb_id']]['donut_name']??$l['dotty_mb_id']);?></td><td class="p-3"><?php echo mg_e($l['action_label']);?></td><td class="p-3 text-gray-500"><?php echo mg_e($l['action_detail']);?></td></tr><?php }?>
</tbody></table></div>
</section>

<section id="panel-task" class="management-panel" <?php echo $tab==='task'?'':'hidden';?>>
<h3 class="text-xl font-bold">통합 업무함</h3><p class="mt-1 text-gray-500">실제 가입 승인, 활동 제한, 운영권 승계 대기 건을 도넛별로 집계합니다.</p>
<div class="grid grid-cols-1 pc:grid-cols-3 gap-4 mt-4">
<?php foreach(array(array('가입 승인 대기','pending','join_request.php'),array('활동 제한 확인','warning','member_activity.php'),array('운영권 승계','progress','ownership_transfer.php')) as $t){$sum=0;foreach($donuts as $d)$sum+=$d[$t[1]];?>
<article class="border rounded-lg overflow-hidden"><div class="bg-gray-50 p-3 flex justify-between"><b><?php echo $t[0];?></b><span class="font-bold"><?php echo $sum;?>건</span></div><ul class="divide-y">
<?php foreach($donuts as $id=>$d){if(!$d[$t[1]])continue;?><li><a class="flex justify-between p-3" href="./<?php echo $t[2];?>?mb_id=<?php echo urlencode($id);?>"><span><?php echo mg_e($d['donut_name']);?></span><b><?php echo $d[$t[1]];?>건 ›</b></a></li><?php } if(!$sum){?><li class="p-3 text-gray-500">현재 해당 업무가 없습니다.</li><?php }?>
</ul></article><?php }?>
</div></section>

<section id="panel-message" class="management-panel" <?php echo $tab==='message'?'':'hidden';?>>
<h3 class="text-xl font-bold">통합 쪽지</h3><p class="mt-1 text-gray-500">모든 도넛의 대화를 한곳에서 확인합니다.</p>
<div class="flex overflow-hidden rounded-lg border mt-4 min-h-120">
<aside class="w-80 border-r"><div class="p-4 border-b font-bold">전체 대화 <?php echo count($convs);?>건</div><ul class="divide-y">
<?php foreach($convs as $c){$key=$c['dotty_mb_id'].'|'.$c['partner_mb_id'];?><li><a href="?tab=message&conversation=<?php echo urlencode($key);?>" class="block p-4 <?php echo $selected_key===$key?'bg-amber-50':'';?>"><b><?php echo mg_e($donuts[$c['dotty_mb_id']]['donut_name']??$c['dotty_mb_id']);?> · <?php echo mg_e($c['partner_name']);?></b><p class="truncate text-2xs text-gray-500 mt-1"><?php echo mg_e($c['last']['message_content']);?></p><?php if($c['unread_count']){?><span class="text-red-600 text-2xs font-bold">읽지 않음 <?php echo (int)$c['unread_count'];?></span><?php }?></a></li><?php }?>
</ul></aside>
<article class="flex-1 flex flex-col">
<?php if($selected){?><div class="p-4 border-b"><b><?php echo mg_e($selected['partner_name']);?></b> ↔ <?php echo mg_e($donuts[$selected['dotty_mb_id']]['donut_name']);?></div>
<ol class="flex-1 p-4 space-y-2 overflow-y-auto"><?php foreach($messages as $m){$mine=$m['sender_mb_id']===$owner;?><li class="<?php echo $mine?'text-right':'';?>"><div class="inline-block max-w-[75%] rounded-2xl border p-3 <?php echo $mine?'bg-amber-50':'bg-white';?>"><?php echo nl2br(mg_e($m['message_content']));?></div><time class="block text-2xs text-gray-400 mt-1"><?php echo mg_e($m['created_at']);?></time></li><?php }?></ol>
<form method="post" class="flex gap-2 border-t p-4"><input type="hidden" name="token" value="<?php echo mg_e($token);?>"><input type="hidden" name="action" value="send_message"><input type="hidden" name="dotty_mb_id" value="<?php echo mg_e($selected['dotty_mb_id']);?>"><input type="hidden" name="partner_mb_id" value="<?php echo mg_e($selected['partner_mb_id']);?>"><textarea name="message_content" required maxlength="2000" class="flex-1 border rounded-lg p-3" placeholder="메시지를 입력해 주세요."></textarea><button class="bg-gray-900 text-white font-bold rounded-lg px-4">전송</button></form>
<script>$.post('./management.php',{action:'read_conversation',token:<?php echo json_encode($token);?>,dotty_mb_id:<?php echo json_encode($selected['dotty_mb_id']);?>,partner_mb_id:<?php echo json_encode($selected['partner_mb_id']);?>});</script>
<?php }else{?><div class="m-auto text-gray-500">대화가 없습니다.</div><?php }?>
</article></div></section>

<section id="panel-donut" class="management-panel" <?php echo $tab==='donut'?'':'hidden';?>>
<h3 class="text-xl font-bold">관리 중인 도넛</h3>
<div class="mt-4 flex gap-2"><input id="donut-search" class="flex-1 border rounded-lg p-3" placeholder="도넛 이름·카테고리 검색"><select id="donut-filter" class="border rounded-lg p-3"><option value="all">전체</option><option value="pending">처리 필요</option><option value="progress">진행 확인</option><option value="warning">주의·예외</option></select></div>
<div class="border rounded-lg overflow-x-auto mt-4"><table class="w-full min-w-180 text-xs"><thead class="bg-gray-50"><tr><th class="p-3 text-left">도넛</th><th>운영 업무</th><th>가입 도트</th><th>관리</th></tr></thead><tbody id="donut-body" class="divide-y">
<?php foreach($donuts as $id=>$d){?><tr class="donut-row" data-pending="<?php echo $d['pending'];?>" data-progress="<?php echo $d['progress'];?>" data-warning="<?php echo $d['warning'];?>"><td class="p-3"><b><?php echo mg_e($d['donut_name']);?></b><p class="text-2xs text-gray-500"><?php echo mg_e($d['category']);?></p></td><td class="p-3 text-center">처리 <?php echo $d['pending'];?> · 진행 <?php echo $d['progress'];?> · 주의 <?php echo $d['warning'];?></td><td class="p-3 text-center"><?php echo number_format($d['members']);?>명</td><td class="p-3 text-center"><a href="./dashboard.php?mb_id=<?php echo urlencode($id);?>" class="border rounded-lg px-3 py-2 font-bold">진입</a></td></tr><?php }?>
<tr id="donut-empty" hidden><td colspan="4" class="p-4 text-center text-gray-500">검색 결과가 없습니다.</td></tr></tbody></table></div>
</section>

<script>
$('.management-tab').on('click',function(){location.href='./management.php?tab='+$(this).data('tab');});
function filterDonuts(){const q=$.trim($('#donut-search').val()).toLowerCase(),f=$('#donut-filter').val();let n=0;$('.donut-row').each(function(){const text=$(this).children('td').first().text().toLowerCase();const okq=text.includes(q);const okf=f==='all'||parseInt($(this).data(f),10)>0;$(this).toggle(okq&&okf);if(okq&&okf)n++;});$('#donut-empty').prop('hidden',n!==0);}
$('#donut-search').on('input',filterDonuts);$('#donut-filter').on('change',filterDonuts);
</script>
<?php include_once(G5_ADMIN_PATH.'/admin.tail.php'); ?>

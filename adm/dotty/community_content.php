<?php
$sub_menu = '730500';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '커뮤니티 콘텐츠';

function cc_e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function cc_status_text($yn) { return $yn === 'Y' ? '게시 중' : '노출 중지'; }

$dotty_mb_id = trim((string)$member['mb_id']);
if ($is_admin === 'super' && !empty($_REQUEST['mb_id'])) {
    $dotty_mb_id = trim((string)$_REQUEST['mb_id']);
}
if ($dotty_mb_id === '') alert('도넛 관리 계정을 확인할 수 없습니다.');
$dotty_sql = sql_real_escape_string($dotty_mb_id);

/* 필요한 확장 컬럼/테이블 확인 */
$need_cols = array('community_area','post_category','writer_mb_id','writer_name','writer_nick','like_count');
foreach ($need_cols as $col) {
    $col_sql = sql_real_escape_string($col);
    $ck = sql_fetch("SELECT COUNT(*) cnt FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='donuts_dotty_posts' AND COLUMN_NAME='{$col_sql}'");
    if (empty($ck['cnt'])) {
        alert('커뮤니티 콘텐츠 DB 마이그레이션이 필요합니다. migration_community_content.sql을 먼저 실행해 주세요.');
    }
}
$report_ck = sql_fetch("SELECT COUNT(*) cnt FROM information_schema.TABLES
    WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='donuts_dotty_content_reports'");
if (empty($report_ck['cnt'])) {
    alert('커뮤니티 콘텐츠 DB 마이그레이션이 필요합니다. migration_community_content.sql을 먼저 실행해 주세요.');
}

/* 쓰기/노출변경/삭제/댓글삭제 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    auth_check_menu($auth, $sub_menu, 'w');
    check_admin_token();

    $action = isset($_POST['action']) ? trim((string)$_POST['action']) : '';

    if ($action === 'write') {
        $area = isset($_POST['community_area']) && $_POST['community_area'] === 'community_02' ? 'community_02' : 'community_01';
        $category = isset($_POST['gallery']) ? trim((string)$_POST['gallery']) : '';
        $title = isset($_POST['title']) ? trim((string)$_POST['title']) : '';
        $content = isset($_POST['content']) ? trim((string)$_POST['content']) : '';

        $allowed_categories = array('', '안내', '모임', '후기', '질문', '팁');
        if (!in_array($category, $allowed_categories, true)) $category = '';
        if ($title === '') alert('제목을 입력해 주세요.');
        if ($content === '') alert('내용을 입력해 주세요.');
        if (mb_strlen($title, 'UTF-8') > 255) alert('제목은 255자 이하로 입력해 주세요.');

        $title = mb_substr(clean_xss_tags($title, 1, 1), 0, 255, 'UTF-8');
        $content = clean_xss_tags($content, 1, 1);

        $area_sql = sql_real_escape_string($area);
        $category_sql = sql_real_escape_string($category);
        $title_sql = sql_real_escape_string($title);
        $content_sql = sql_real_escape_string($content);
        $writer_id_sql = sql_real_escape_string((string)$member['mb_id']);
        $writer_name_sql = sql_real_escape_string((string)$member['mb_name']);
        $writer_nick_sql = sql_real_escape_string((string)$member['mb_nick']);

        sql_query("INSERT INTO donuts_dotty_posts SET
            dotty_mb_id='{$dotty_sql}',
            community_area='{$area_sql}',
            post_category='{$category_sql}',
            writer_mb_id='{$writer_id_sql}',
            writer_name='{$writer_name_sql}',
            writer_nick='{$writer_nick_sql}',
            post_subject='{$title_sql}',
            post_content='{$content_sql}',
            view_count=0, comment_count=0, like_count=0,
            use_yn='Y', created_at=NOW(), updated_at=NOW()");
        alert('게시글이 등록되었습니다.', './community_content.php?status='.$area.'&mb_id='.urlencode($dotty_mb_id));
    }

    if ($action === 'toggle_post') {
        $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
        $row = sql_fetch("SELECT post_id,use_yn FROM donuts_dotty_posts
            WHERE post_id='{$post_id}' AND dotty_mb_id='{$dotty_sql}' LIMIT 1");
        if (empty($row['post_id'])) alert('처리 권한이 없는 게시글입니다.');
        $new = $row['use_yn'] === 'Y' ? 'N' : 'Y';
        sql_query("UPDATE donuts_dotty_posts SET use_yn='{$new}',updated_at=NOW()
            WHERE post_id='{$post_id}' AND dotty_mb_id='{$dotty_sql}'");
        alert($new === 'Y' ? '게시글을 다시 노출했습니다.' : '게시글 노출을 중지했습니다.',
            './community_content.php?mb_id='.urlencode($dotty_mb_id));
    }

    if ($action === 'delete_comment') {
        auth_check_menu($auth, $sub_menu, 'd');
        $comment_id = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : 0;
        $c = sql_fetch("SELECT c.comment_id,c.post_id FROM donuts_dotty_post_comments c
            INNER JOIN donuts_dotty_posts p ON p.post_id=c.post_id
            WHERE c.comment_id='{$comment_id}' AND p.dotty_mb_id='{$dotty_sql}' LIMIT 1");
        if (empty($c['comment_id'])) alert('삭제 권한이 없는 댓글입니다.');
        sql_query("DELETE FROM donuts_dotty_post_comments WHERE comment_id='{$comment_id}'");
        $pid = (int)$c['post_id'];
        sql_query("UPDATE donuts_dotty_posts SET comment_count=(
            SELECT COUNT(*) FROM donuts_dotty_post_comments WHERE post_id='{$pid}'
        ),updated_at=NOW() WHERE post_id='{$pid}' AND dotty_mb_id='{$dotty_sql}'");
        alert('댓글을 삭제했습니다.', './community_content.php?status=comment&mb_id='.urlencode($dotty_mb_id));
    }

    if ($action === 'report_status') {
        $report_id = isset($_POST['report_id']) ? (int)$_POST['report_id'] : 0;
        $new_status = isset($_POST['report_status']) && $_POST['report_status'] === 'done' ? 'done' : 'pending';
        $new_sql = sql_real_escape_string($new_status);
        sql_query("UPDATE donuts_dotty_content_reports SET report_status='{$new_sql}',
            processed_by='".sql_real_escape_string((string)$member['mb_id'])."',
            processed_at=".($new_status === 'done' ? "NOW()" : "NULL")."
            WHERE report_id='{$report_id}' AND dotty_mb_id='{$dotty_sql}'");
        alert('신고 상태를 변경했습니다.', './community_content.php?status=report&mb_id='.urlencode($dotty_mb_id));
    }

    alert('올바르지 않은 요청입니다.');
}

$status = isset($_GET['status']) ? trim((string)$_GET['status']) : 'community_01';
if (!in_array($status, array('community_01','community_02','comment','report'), true)) $status='community_01';
$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$q_sql = sql_real_escape_string($q);

$rows = array();

if ($status === 'comment') {
    $where = "p.dotty_mb_id='{$dotty_sql}'";
    if ($q !== '') $where .= " AND (c.comment_content LIKE '%{$q_sql}%' OR c.comment_name LIKE '%{$q_sql}%' OR c.comment_nick LIKE '%{$q_sql}%')";
    $cnt = sql_fetch("SELECT COUNT(*) cnt FROM donuts_dotty_post_comments c
        INNER JOIN donuts_dotty_posts p ON p.post_id=c.post_id WHERE {$where}");
    $result_count = (int)$cnt['cnt'];
    $res = sql_query("SELECT c.*,p.post_subject,p.community_area FROM donuts_dotty_post_comments c
        INNER JOIN donuts_dotty_posts p ON p.post_id=c.post_id
        WHERE {$where} ORDER BY c.comment_id DESC LIMIT 500");
    while ($r=sql_fetch_array($res)) $rows[]=$r;
} elseif ($status === 'report') {
    $where = "r.dotty_mb_id='{$dotty_sql}'";
    if ($q !== '') $where .= " AND (r.report_content LIKE '%{$q_sql}%' OR r.reporter_mb_id LIKE '%{$q_sql}%')";
    $cnt=sql_fetch("SELECT COUNT(*) cnt FROM donuts_dotty_content_reports r WHERE {$where}");
    $result_count=(int)$cnt['cnt'];
    $res=sql_query("SELECT r.*,p.post_subject,c.comment_content
        FROM donuts_dotty_content_reports r
        LEFT JOIN donuts_dotty_posts p ON p.post_id=r.post_id
        LEFT JOIN donuts_dotty_post_comments c ON c.comment_id=r.comment_id
        WHERE {$where} ORDER BY r.report_id DESC LIMIT 500");
    while($r=sql_fetch_array($res)) $rows[]=$r;
} else {
    $where="p.dotty_mb_id='{$dotty_sql}' AND p.community_area='".sql_real_escape_string($status)."'";
    if ($q !== '') $where.=" AND (p.post_subject LIKE '%{$q_sql}%' OR p.writer_name LIKE '%{$q_sql}%' OR p.writer_nick LIKE '%{$q_sql}%')";
    $cnt=sql_fetch("SELECT COUNT(*) cnt FROM donuts_dotty_posts p WHERE {$where}");
    $result_count=(int)$cnt['cnt'];
    $res=sql_query("SELECT p.* FROM donuts_dotty_posts p WHERE {$where} ORDER BY p.post_id DESC LIMIT 500");
    while($r=sql_fetch_array($res)) $rows[]=$r;
}

$admin_token = get_admin_token();
require_once '../admin.head.php';
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="mt-1 text-gray-400">커뮤니티 01은 도티와 지정 운영자가, 커뮤니티 02는 가입 도트도 작성할 수 있습니다.</p>
        <button type="button" id="community-content-write-modal-open" class="shrink-0 rounded-lg bg-amber-400 px-3 py-2 text-gray-900 font-bold">+ 게시글 작성</button>
    </div>

    <div class="mt-4 rounded-lg text-amber-700 bg-amber-100 p-3">
        <p><span class="text-blue-700 font-bold mr-2">작성 권한</span>커뮤니티 01: 도티·지정 운영자 작성 / 가입 도트 댓글 참여 · 커뮤니티 02: 가입 도트와 도티 모두 글·댓글 작성</p>
    </div>

    <section class="mt-4">
        <form method="get" class="flex flex-col gap-3 pc:flex-row pc:items-center">
            <input type="hidden" name="mb_id" value="<?php echo cc_e($dotty_mb_id); ?>">
            <div class="flex shrink-0 rounded-lg bg-gray-100 p-1">
                <?php foreach(array('community_01'=>'커뮤니티 01','community_02'=>'커뮤니티 02','comment'=>'댓글','report'=>'신고 접수') as $k=>$label) {
                    $active=$status===$k; ?>
                    <button type="submit" name="status" value="<?php echo cc_e($k); ?>" aria-pressed="<?php echo $active?'true':'false'; ?>"
                        class="rounded-md px-3 py-2 text-xs font-bold <?php echo $active?'bg-white text-gray-900 shadow-sm':'text-gray-500'; ?>"><?php echo cc_e($label); ?></button>
                <?php } ?>
            </div>
            <div class="flex min-w-0 flex-1 items-center rounded-lg border border-gray-300 bg-white">
                <input type="search" name="q" value="<?php echo cc_e($q); ?>" class="min-w-0 flex-1 rounded-lg px-3 py-2 text-sm outline-none" placeholder="제목 또는 작성자 검색">
                <input type="hidden" name="status" value="<?php echo cc_e($status); ?>">
                <button type="submit" class="shrink-0 p-3 text-gray-900">검색</button>
            </div>
            <p class="shrink-0 text-2xs text-gray-500">검색 결과 <?php echo number_format($result_count); ?>건</p>
        </form>

        <div class="mt-4 overflow-x-auto rounded-lg border border-gray-300 bg-white">
            <table class="border-collapse w-full min-w-225 text-left text-xs text-gray-900">
                <thead class="border-b border-gray-300 bg-gray-50 text-gray-500 font-bold [&_th]:p-3">
                <?php if ($status==='comment') { ?>
                    <tr><th>구분</th><th>댓글 내용</th><th>작성자</th><th>등록일</th><th>원문</th><th>관리</th></tr>
                <?php } elseif ($status==='report') { ?>
                    <tr><th>대상</th><th>신고 내용</th><th>신고자</th><th>접수일</th><th>상태</th><th>관리</th></tr>
                <?php } else { ?>
                    <tr><th>구분</th><th>제목/접수 내용</th><th>작성자</th><th>등록일</th><th>댓글</th><th>좋아요</th><th>상태</th><th>관리</th></tr>
                <?php } ?>
                </thead>
                <tbody class="[&_td]:p-3">
                <?php if (!$rows) { ?><tr><td colspan="8" class="p-6 text-center text-gray-500">검색 결과가 없습니다.</td></tr><?php } ?>

                <?php foreach($rows as $r) {
                    if ($status==='comment') { ?>
                    <tr class="border-b border-gray-200">
                        <td><span class="rounded-full bg-blue-50 px-2 py-1 font-bold text-blue-700">댓글</span></td>
                        <td><p class="max-w-120 truncate font-bold"><?php echo cc_e($r['comment_content']); ?></p><span class="text-2xs text-gray-400"><?php echo cc_e($r['post_subject']); ?></span></td>
                        <td><?php echo cc_e($r['comment_nick'] ?: $r['comment_name'] ?: $r['mb_id']); ?></td>
                        <td><?php echo cc_e($r['created_at']); ?></td>
                        <td>POST-<?php echo str_pad((int)$r['post_id'],5,'0',STR_PAD_LEFT); ?></td>
                        <td>
                            <form method="post" onsubmit="return confirm('이 댓글을 삭제하시겠습니까?');">
                                <input type="hidden" name="token" value="<?php echo cc_e($admin_token); ?>">
                                <input type="hidden" name="mb_id" value="<?php echo cc_e($dotty_mb_id); ?>">
                                <input type="hidden" name="action" value="delete_comment">
                                <input type="hidden" name="comment_id" value="<?php echo (int)$r['comment_id']; ?>">
                                <button class="rounded-lg border border-red-300 px-3 py-2 font-bold text-red-600">삭제</button>
                            </form>
                        </td>
                    </tr>
                    <?php } elseif ($status==='report') {
                        $target = !empty($r['comment_id']) ? '댓글' : '게시글';
                        $target_text = !empty($r['comment_id']) ? $r['comment_content'] : $r['post_subject']; ?>
                    <tr class="border-b border-gray-200">
                        <td><span class="rounded-full bg-red-50 px-2 py-1 font-bold text-red-600"><?php echo $target; ?></span></td>
                        <td><p class="font-bold"><?php echo cc_e($r['report_content']); ?></p><span class="text-2xs text-gray-400"><?php echo cc_e($target_text); ?></span></td>
                        <td><?php echo cc_e($r['reporter_mb_id']); ?></td>
                        <td><?php echo cc_e($r['created_at']); ?></td>
                        <td><?php echo $r['report_status']==='done'?'처리 완료':'접수'; ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="token" value="<?php echo cc_e($admin_token); ?>">
                                <input type="hidden" name="mb_id" value="<?php echo cc_e($dotty_mb_id); ?>">
                                <input type="hidden" name="action" value="report_status">
                                <input type="hidden" name="report_id" value="<?php echo (int)$r['report_id']; ?>">
                                <input type="hidden" name="report_status" value="<?php echo $r['report_status']==='done'?'pending':'done'; ?>">
                                <button class="rounded-lg border border-gray-300 px-3 py-2 font-bold"><?php echo $r['report_status']==='done'?'접수로 변경':'처리 완료'; ?></button>
                            </form>
                        </td>
                    </tr>
                    <?php } else {
                        $writer=$r['writer_nick'] ?: $r['writer_name'] ?: $r['writer_mb_id'] ?: $r['dotty_mb_id']; ?>
                    <tr class="border-b border-gray-200">
                        <td><span class="rounded-full bg-gray-900 px-2 py-1 text-2xs font-bold text-white">● <?php echo $r['community_area']==='community_02'?'커뮤니티 02':'커뮤니티 01'; ?></span></td>
                        <td><p class="font-bold"><?php echo cc_e($r['post_subject']); ?></p><span class="block text-2xs text-gray-400">POST-<?php echo str_pad((int)$r['post_id'],5,'0',STR_PAD_LEFT); ?><?php echo $r['post_category'] ? ' · '.cc_e($r['post_category']) : ''; ?></span></td>
                        <td><?php echo cc_e($writer); ?></td>
                        <td><?php echo cc_e($r['created_at']); ?></td>
                        <td><?php echo number_format((int)$r['comment_count']); ?></td>
                        <td><?php echo number_format((int)$r['like_count']); ?></td>
                        <td><span class="rounded-full px-2 py-1 font-bold <?php echo $r['use_yn']==='Y'?'bg-amber-100 text-amber-700':'bg-gray-100 text-gray-600'; ?>">● <?php echo cc_status_text($r['use_yn']); ?></span></td>
                        <td><button type="button" class="community-content-modal-open rounded-lg border border-gray-300 bg-white px-3 py-2 font-bold"
                            data-id="<?php echo (int)$r['post_id']; ?>"
                            data-code="POST-<?php echo str_pad((int)$r['post_id'],5,'0',STR_PAD_LEFT); ?>"
                            data-writer="<?php echo cc_e($writer); ?>"
                            data-date="<?php echo cc_e($r['created_at']); ?>"
                            data-status="<?php echo cc_e(cc_status_text($r['use_yn'])); ?>"
                            data-use="<?php echo cc_e($r['use_yn']); ?>"
                            data-title="<?php echo cc_e($r['post_subject']); ?>"
                            data-content="<?php echo cc_e($r['post_content']); ?>">상세</button></td>
                    </tr>
                    <?php }
                } ?>
                </tbody>
            </table>
        </div>
    </section>
</section>

<div id="community-content-write-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="community-content-write-modal-backdrop" class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 max-h-[90vh] w-full max-w-160 overflow-y-auto rounded-lg bg-white">
        <div class="sticky top-0 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 class="text-base font-bold">커뮤니티 게시글 작성</h3>
            <button type="button" class="community-write-close text-xl">×</button>
        </div>
        <form method="post">
            <input type="hidden" name="token" value="<?php echo cc_e($admin_token); ?>">
            <input type="hidden" name="mb_id" value="<?php echo cc_e($dotty_mb_id); ?>">
            <input type="hidden" name="action" value="write">
            <div class="grid grid-cols-1 gap-4 p-4 pc:grid-cols-2">
                <div><label class="mb-2 block font-bold">게시 영역</label><select name="community_area" class="w-full rounded-lg border border-gray-300 px-3 py-3"><option value="community_01">커뮤니티 01</option><option value="community_02">커뮤니티 02</option></select></div>
                <div><label class="mb-2 block font-bold">말머리</label><select name="gallery" class="w-full rounded-lg border border-gray-300 px-3 py-3"><option value="">선택하지 않음</option><option value="안내">안내</option><option value="모임">모임</option><option value="후기">후기</option><option value="질문">질문</option><option value="팁">팁</option></select></div>
                <div class="pc:col-span-2"><label class="mb-2 block font-bold">제목</label><input type="text" name="title" maxlength="255" required class="w-full rounded-lg border border-gray-300 px-3 py-3"></div>
                <div class="pc:col-span-2"><label class="mb-2 block font-bold">내용</label><textarea name="content" required class="h-40 w-full rounded-lg border border-gray-300 p-3"></textarea></div>
            </div>
            <div class="sticky bottom-0 flex justify-end gap-2 border-t border-gray-300 bg-white p-4"><button type="button" class="community-write-close rounded-lg border border-gray-300 px-4 py-3 font-bold">취소</button><button class="rounded-lg bg-amber-400 px-4 py-3 font-bold">게시하기</button></div>
        </form>
    </div>
</div>

<div id="community-content-detail-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="community-content-detail-modal-backdrop" class="absolute inset-0 bg-black/40"></div>
    <div class="relative z-10 max-h-[90vh] w-full max-w-160 overflow-y-auto rounded-lg bg-white">
        <div class="sticky top-0 flex items-center justify-between border-b border-gray-300 bg-white p-4"><h3 class="text-base font-bold">콘텐츠 상세</h3><button type="button" class="community-detail-close text-xl">×</button></div>
        <div class="p-4">
            <div class="overflow-hidden rounded-lg border border-gray-300 text-xs">
                <div class="flex border-b"><p class="w-32 bg-gray-100 p-3 text-gray-500">콘텐츠 ID</p><p id="cc-code" class="flex-1 p-3 font-bold"></p></div>
                <div class="flex border-b"><p class="w-32 bg-gray-100 p-3 text-gray-500">작성자</p><p id="cc-writer" class="flex-1 p-3 font-bold"></p></div>
                <div class="flex border-b"><p class="w-32 bg-gray-100 p-3 text-gray-500">등록일</p><p id="cc-date" class="flex-1 p-3 font-bold"></p></div>
                <div class="flex"><p class="w-32 bg-gray-100 p-3 text-gray-500">상태</p><p id="cc-status" class="flex-1 p-3 font-bold"></p></div>
            </div>
            <div class="mt-4 rounded-lg bg-gray-100 p-3"><span id="cc-title" class="block text-2xs text-gray-400"></span><p id="cc-content" class="mt-2 whitespace-pre-wrap text-gray-900"></p></div>
        </div>
        <div class="sticky bottom-0 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" class="community-detail-close rounded-lg border border-gray-300 px-4 py-3 font-bold">닫기</button>
            <form method="post" id="cc-toggle-form">
                <input type="hidden" name="token" value="<?php echo cc_e($admin_token); ?>"><input type="hidden" name="mb_id" value="<?php echo cc_e($dotty_mb_id); ?>"><input type="hidden" name="action" value="toggle_post"><input type="hidden" name="post_id" id="cc-post-id">
                <button id="cc-toggle-button" class="rounded-lg border border-red-400 bg-white px-4 py-3 font-bold text-red-500">노출 중지</button>
            </form>
        </div>
    </div>
</div>

<script>
$('#community-content-write-modal-open').on('click',()=>$('#community-content-write-modal').prop('hidden',false));
$('.community-write-close,#community-content-write-modal-backdrop').on('click',()=>$('#community-content-write-modal').prop('hidden',true));
$('.community-content-modal-open').on('click',function(){
    const $b=$(this);
    $('#cc-post-id').val($b.data('id')); $('#cc-code').text($b.data('code')); $('#cc-writer').text($b.data('writer'));
    $('#cc-date').text($b.data('date')); $('#cc-status').text($b.data('status')); $('#cc-title').text($b.data('title')); $('#cc-content').text($b.attr('data-content')||'');
    $('#cc-toggle-button').text($b.data('use')==='Y'?'노출 중지':'다시 노출');
    $('#community-content-detail-modal').prop('hidden',false);
});
$('.community-detail-close,#community-content-detail-modal-backdrop').on('click',()=>$('#community-content-detail-modal').prop('hidden',true));
$('#cc-toggle-form').on('submit',function(e){ if(!confirm('게시글 노출 상태를 변경하시겠습니까?')) e.preventDefault(); });
</script>
<?php include_once(G5_ADMIN_PATH . '/admin.tail.php'); ?>

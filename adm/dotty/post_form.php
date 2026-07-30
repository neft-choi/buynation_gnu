<?php
$sub_menu = '710500';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check_menu($auth, $sub_menu, "w");

$w = isset($_GET['w']) ? trim($_GET['w']) : '';
$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;

$dotty_mb_id = $member['mb_id'];
$dotty_mb_id_sql = sql_real_escape_string($dotty_mb_id);

$post_subject = '';
$post_content = '';
$use_yn = 'Y';

if ($w === 'u') {
    $row = sql_fetch("
        SELECT *
        FROM donuts_dotty_posts
        WHERE post_id = '{$post_id}'
          AND dotty_mb_id = '{$dotty_mb_id_sql}'
        LIMIT 1
    ");

    if (empty($row['post_id'])) {
        alert('수정 권한이 없는 게시글입니다.');
    }

    $post_subject = $row['post_subject'];
    $post_content = $row['post_content'];
    $use_yn = $row['use_yn'];
}

$g5['title'] = $w === 'u' ? '게시글 수정' : '게시글 등록';

require_once '../admin.head.php';
?>

<section>
    <h2><?php echo $g5['title']; ?></h2>

    <div class="text-lg">
        안녕하세요 <?php echo get_text($member['mb_name'] ? $member['mb_name'] : $member['mb_id']); ?> 도티님
    </div>

    <form name="fpostform"
          method="post"
          action="./post_form_update.php"
          onsubmit="return fpostform_submit(this);">

        <input type="hidden" name="token" value="<?php echo get_admin_token(); ?>">
        <input type="hidden" name="w" value="<?php echo get_text($w); ?>">
        <input type="hidden" name="post_id" value="<?php echo (int)$post_id; ?>">

        <div class="mt-8 space-y-4">
            <div>
                <label for="post_subject">제목</label>
                <input type="text"
                       id="post_subject"
                       name="post_subject"
                       value="<?php echo get_text($post_subject); ?>"
                       maxlength="255"
                       required
                       class="frm_input required w-full">
            </div>

            <div>
                <label for="post_content">내용</label>
                <?php echo editor_html('post_content', $post_content); ?>
            </div>

            <div class="flex items-center gap-2">
                <label for="use_yn">개인 페이지 노출</label>
                <select name="use_yn" id="use_yn" class="frm_input">
                    <option value="Y" <?php echo get_selected($use_yn, 'Y'); ?>>노출</option>
                    <option value="N" <?php echo get_selected($use_yn, 'N'); ?>>숨김</option>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 mt-4">
            <button type="submit" class="btn btn_04">
                <?php echo $w === 'u' ? '수정완료' : '게시글 등록'; ?>
            </button>
            <a href="./post_list.php" class="btn btn_01">취소</a>
        </div>
    </form>
</section>

<script>
function fpostform_submit(f)
{
    <?php echo get_editor_js('post_content'); ?>

    if (!f.post_subject.value.trim()) {
        alert("제목을 입력해 주세요.");
        f.post_subject.focus();
        return false;
    }

    return true;
}
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

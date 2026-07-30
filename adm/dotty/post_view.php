<?php
$sub_menu = '710500';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "r");

$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;
$mb_id_sql = sql_real_escape_string($member['mb_id']);

$post = sql_fetch("
    SELECT *
    FROM donuts_dotty_posts
    WHERE post_id = '{$post_id}'
      AND dotty_mb_id = '{$mb_id_sql}'
    LIMIT 1
");

if (empty($post['post_id'])) {
    alert('열람 권한이 없는 게시글입니다.');
}


// 댓글 목록
$comment_result = sql_query("
    SELECT
        comment_id,
        mb_id,
        comment_name,
        comment_nick,
        comment_content,
        created_at
    FROM donuts_dotty_post_comments
    WHERE post_id = '{$post_id}'
    ORDER BY comment_id ASC
");

/**
 * 파일명을 기준으로 /data/editor 하위의 실제 업로드 파일을 찾습니다.
 *
 * 예)
 * /data/editor/2607/파일명.png
 * /data/editor/2026/07/파일명.png
 * /data/editor/파일명.png
 */
function dotty_find_editor_image_url($filename)
{
    $filename = basename(rawurldecode((string)$filename));

    // 이미지 파일명만 허용
    if (
        $filename === '' ||
        !preg_match('/\.(jpe?g|png|gif|webp)$/i', $filename)
    ) {
        return '';
    }

    $editor_root = G5_DATA_PATH . '/editor';

    if (!is_dir($editor_root)) {
        return '';
    }

    // 1. editor 바로 아래
    $direct = $editor_root . '/' . $filename;

    if (is_file($direct)) {
        return G5_DATA_URL . '/editor/' . rawurlencode($filename);
    }

    // 2. 일반적으로 많이 사용하는 1단계 날짜 디렉터리
    $matches = glob($editor_root . '/*/' . $filename);

    if (is_array($matches) && !empty($matches)) {
        $real = $matches[0];
        $relative = str_replace('\\', '/', substr($real, strlen(G5_DATA_PATH)));

        return G5_DATA_URL . implode(
            '/',
            array_map(
                'rawurlencode',
                array_filter(explode('/', ltrim($relative, '/')), 'strlen')
            )
        );
    }

    // 3. 하위 폴더가 2단계 이상인 경우까지 검색
    try {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $editor_root,
                FilesystemIterator::SKIP_DOTS
            )
        );

        foreach ($iterator as $file) {
            if (!$file->isFile()) {
                continue;
            }

            if ($file->getFilename() !== $filename) {
                continue;
            }

            $real = $file->getPathname();
            $relative = str_replace('\\', '/', substr($real, strlen(G5_DATA_PATH)));

            return G5_DATA_URL . implode(
                '/',
                array_map(
                    'rawurlencode',
                    array_filter(explode('/', ltrim($relative, '/')), 'strlen')
                )
            );
        }
    } catch (Exception $e) {
        return '';
    }

    return '';
}

/**
 * 에디터에서 깨진 IMG 태그를 복구합니다.
 *
 * 현재 문제 예:
 * <img src="%5C"
 *      title="\&quot;8eb...png\&quot;"
 *      alt="8eb...png\&quot;">
 *
 * src가 "\" 로 깨졌더라도 title 또는 alt에서 실제 파일명을 추출한 후
 * /data/editor 하위의 실제 파일을 찾아 src를 다시 구성합니다.
 */
function dotty_fix_post_images($html)
{
    if (!$html) {
        return '';
    }

    // 잘못된 /dataeditor 경로를 정상 /data/editor 경로로 보정
    $html = str_replace(
        array('/dataeditor/', 'dataeditor/', '/dataeditor'),
        array('/data/editor/', 'data/editor/', '/data/editor'),
        $html
    );

    // DB에 백슬래시가 엔티티와 섞여 저장된 경우 정리
    $html = str_replace(
        array('\&quot;', '\\&quot;', '\&#039;', '\\&#039;'),
        array('&quot;', '&quot;', '&#039;', '&#039;'),
        $html
    );

    return preg_replace_callback(
        '/<img\b[^>]*>/i',
        function ($matches) {
            $tag = $matches[0];

            $src = '';
            $alt = '';
            $title = '';

            if (preg_match('/\bsrc\s*=\s*(["\'])(.*?)\1/i', $tag, $m)) {
                $src = html_entity_decode(rawurldecode($m[2]), ENT_QUOTES, 'UTF-8');
                $src = trim($src, " \t\n\r\0\x0B\\\"");
            }

            if (preg_match('/\balt\s*=\s*(["\'])(.*?)\1/i', $tag, $m)) {
                $alt = html_entity_decode(rawurldecode($m[2]), ENT_QUOTES, 'UTF-8');
                $alt = trim($alt, " \t\n\r\0\x0B\\\"");
            }

            if (preg_match('/\btitle\s*=\s*(["\'])(.*?)\1/i', $tag, $m)) {
                $title = html_entity_decode(rawurldecode($m[2]), ENT_QUOTES, 'UTF-8');
                $title = trim($title, " \t\n\r\0\x0B\\\"");
            }

            // 정상 외부 URL / data URI
            if (
                $src !== '' &&
                (
                    preg_match('#^https?://#i', $src) ||
                    strpos($src, '//') === 0 ||
                    preg_match('#^(data:|blob:)#i', $src)
                )
            ) {
                return $tag;
            }

            // 정상 /data/... URL
            if (strpos($src, '/data/') === 0) {
                $new_src = G5_URL . $src;

                return preg_replace(
                    '/\bsrc\s*=\s*(["\'])(.*?)\1/i',
                    'src="' . htmlspecialchars($new_src, ENT_QUOTES, 'UTF-8') . '"',
                    $tag,
                    1
                );
            }

            // data/... 상대경로
            if (
                strpos($src, 'data/') === 0 ||
                strpos($src, './data/') === 0
            ) {
                $src = preg_replace('#^\./#', '', $src);
                $new_src = G5_URL . '/' . ltrim($src, '/');

                return preg_replace(
                    '/\bsrc\s*=\s*(["\'])(.*?)\1/i',
                    'src="' . htmlspecialchars($new_src, ENT_QUOTES, 'UTF-8') . '"',
                    $tag,
                    1
                );
            }

            // ../data/... 상대경로
            if (preg_match('#^(?:\.\./)+data/(.+)$#i', $src, $m)) {
                $new_src = G5_URL . '/data/' . ltrim($m[1], '/');

                return preg_replace(
                    '/\bsrc\s*=\s*(["\'])(.*?)\1/i',
                    'src="' . htmlspecialchars($new_src, ENT_QUOTES, 'UTF-8') . '"',
                    $tag,
                    1
                );
            }

            /*
             * src가 %5C, "\", 빈 문자열 등으로 깨진 경우
             * alt → title 순으로 이미지 파일명을 복구합니다.
             */
            $filename = '';

            foreach (array($alt, $title, $src) as $candidate) {
                if (!$candidate) {
                    continue;
                }

                // 값 뒤에 붙은 따옴표/엔티티/백슬래시 제거
                $candidate = html_entity_decode($candidate, ENT_QUOTES, 'UTF-8');
                $candidate = trim($candidate, " \t\n\r\0\x0B\\\"'");
                $candidate = basename(str_replace('\\', '/', $candidate));

                if (preg_match('/^[^\/\\\\]+\.(jpe?g|png|gif|webp)$/i', $candidate)) {
                    $filename = $candidate;
                    break;
                }
            }

            if ($filename === '') {
                return $tag;
            }

            $new_src = dotty_find_editor_image_url($filename);

            if ($new_src === '') {
                // 실제 파일을 못 찾으면 태그는 그대로 두되 alt/title만 정리
                return $tag;
            }

            // 기존 src 교체
            if (preg_match('/\bsrc\s*=/i', $tag)) {
                $tag = preg_replace(
                    '/\bsrc\s*=\s*(["\'])(.*?)\1/i',
                    'src="' . htmlspecialchars($new_src, ENT_QUOTES, 'UTF-8') . '"',
                    $tag,
                    1
                );
            } else {
                $tag = preg_replace(
                    '/<img\b/i',
                    '<img src="' . htmlspecialchars($new_src, ENT_QUOTES, 'UTF-8') . '"',
                    $tag,
                    1
                );
            }

            // 깨진 title/alt도 보기 좋게 정리
            $clean_filename = htmlspecialchars($filename, ENT_QUOTES, 'UTF-8');

            if (preg_match('/\balt\s*=/i', $tag)) {
                $tag = preg_replace(
                    '/\balt\s*=\s*(["\'])(.*?)\1/i',
                    'alt="' . $clean_filename . '"',
                    $tag,
                    1
                );
            }

            if (preg_match('/\btitle\s*=/i', $tag)) {
                $tag = preg_replace(
                    '/\btitle\s*=\s*(["\'])(.*?)\1/i',
                    'title="' . $clean_filename . '"',
                    $tag,
                    1
                );
            }

            return $tag;
        },
        $html
    );
}

$g5['title'] = '게시글 보기';
require_once '../admin.head.php';

$post_content_html = conv_content($post['post_content'], 1);
$post_content_html = dotty_fix_post_images($post_content_html);
?>

<section>
    <h2>게시글 보기</h2>

    <div class="tbl_frm01">
        <table>
            <tbody>
            <tr>
                <th>제목</th>
                <td><?php echo get_text($post['post_subject']); ?></td>
            </tr>
            <tr>
                <th>노출상태</th>
                <td><?php echo $post['use_yn'] === 'Y' ? '노출' : '숨김'; ?></td>
            </tr>
            <tr>
                <th>조회수</th>
                <td><?php echo number_format($post['view_count']); ?></td>
            </tr>
            <tr>
                <th>댓글수</th>
                <td><?php echo number_format($post['comment_count']); ?></td>
            </tr>
            <tr>
                <th>등록일</th>
                <td><?php echo get_text($post['created_at']); ?></td>
            </tr>
            <tr>
                <th>내용</th>
                <td class="dotty_post_content">
                    <?php echo $post_content_html; ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <style>
    .dotty_post_content img {
        max-width: 100%;
        height: auto;
    }
    </style>


    <div style="margin-top:30px;">
        <h3>댓글 테스트</h3>

        <div class="tbl_head01 tbl_wrap" style="margin-top:10px;">
            <table>
                <thead>
                <tr>
                    <th scope="col">회원아이디</th>
                    <th scope="col">이름</th>
                    <th scope="col">닉네임</th>
                    <th scope="col">댓글</th>
                    <th scope="col">등록일</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $comment_list_count = 0;
                while ($comment = sql_fetch_array($comment_result)) {
                    $comment_list_count++;
                ?>
                    <tr>
                        <td><?php echo get_text($comment['mb_id']); ?></td>
                        <td><?php echo get_text($comment['comment_name']); ?></td>
                        <td><?php echo get_text($comment['comment_nick']); ?></td>
                        <td class="td_left"><?php echo nl2br(get_text($comment['comment_content'])); ?></td>
                        <td><?php echo get_text($comment['created_at']); ?></td>
                    </tr>
                <?php
                }

                if (!$comment_list_count) {
                    echo '<tr><td colspan="5" class="empty_table">등록된 댓글이 없습니다.</td></tr>';
                }
                ?>
                </tbody>
            </table>
        </div>

        <form name="fcommenttest"
              method="post"
              action="./comment_write.php"
              onsubmit="return fcommenttest_submit(this);"
              style="margin-top:15px;">

            <input type="hidden" name="token" value="<?php echo get_admin_token(); ?>">
            <input type="hidden" name="post_id" value="<?php echo (int)$post_id; ?>">

            <div style="display:flex; gap:8px; align-items:flex-start;">
                <textarea name="comment_content"
                          id="comment_content"
                          rows="4"
                          required
                          class="frm_input required"
                          style="width:100%; resize:vertical;"
                          placeholder="댓글 테스트 내용을 입력하세요."></textarea>

                <button type="submit"
                        class="btn btn_04"
                        style="height:38px; white-space:nowrap;">
                    댓글 등록
                </button>
            </div>

            <p style="margin-top:6px; color:#777;">
                현재 로그인 계정
                <strong><?php echo get_text($member['mb_id']); ?></strong>
                정보로 테스트 댓글이 등록됩니다.
            </p>
        </form>
    </div>

    <script>
    function fcommenttest_submit(f)
    {
        if (!f.comment_content.value.trim()) {
            alert('댓글 내용을 입력해 주세요.');
            f.comment_content.focus();
            return false;
        }

        return confirm('테스트 댓글을 등록하시겠습니까?');
    }
    </script>

    <div class="btn_confirm01 btn_confirm">
        <a href="./post_form.php?w=u&amp;post_id=<?php echo (int)$post_id; ?>" class="btn btn_03">수정</a>
        <a href="./post_list.php" class="btn btn_04">목록</a>
    </div>
</section>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

<?php
$sub_menu = '710500';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "w");
check_demo();
check_admin_token();

$w = isset($_POST['w']) ? trim($_POST['w']) : '';
$post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;

$dotty_mb_id = $member['mb_id'];
$dotty_mb_id_sql = sql_real_escape_string($dotty_mb_id);

$post_subject = isset($_POST['post_subject']) ? trim($_POST['post_subject']) : '';
$post_content = isset($_POST['post_content']) ? $_POST['post_content'] : '';

/*
 * 에디터 이미지 경로 보정
 *
 * 잘못된 예:
 *   /dataeditor/2607/test.png
 *   dataeditor/2607/test.png
 *
 * 정상:
 *   /data/editor/2607/test.png
 */
$post_content = str_replace(
    array(
        '/dataeditor/',
        'dataeditor/',
        '/dataeditor',
        'dataeditor'
    ),
    array(
        '/data/editor/',
        '/data/editor/',
        '/data/editor',
        '/data/editor'
    ),
    $post_content
);

/*
 * 일부 에디터/escape 처리 과정에서
 * src="\\", src="%5C" 형태로 깨지고 실제 파일명이
 * title/alt에 남는 경우 복구한다.
 *
 * 파일명이 YYYYMMDD 등 editor 하위 경로 없이 넘어온 경우에는
 * 저장 시점에 임의의 날짜 폴더를 추측하지 않고,
 * /data/editor/파일명 형태로 최소한 정상화한다.
 */
$post_content = preg_replace_callback(
    '/<img\b[^>]*>/i',
    function ($matches) {
        $tag = $matches[0];

        $src = '';
        $alt = '';
        $title = '';

        if (preg_match('/\bsrc\s*=\s*(["\'])(.*?)\1/i', $tag, $m)) {
            $src = html_entity_decode(rawurldecode($m[2]), ENT_QUOTES, 'UTF-8');
            $src = trim($src, " \t\n\r\0\x0B\\\\\"'");
        }

        if (preg_match('/\balt\s*=\s*(["\'])(.*?)\1/i', $tag, $m)) {
            $alt = html_entity_decode(rawurldecode($m[2]), ENT_QUOTES, 'UTF-8');
            $alt = trim($alt, " \t\n\r\0\x0B\\\\\"'");
        }

        if (preg_match('/\btitle\s*=\s*(["\'])(.*?)\1/i', $tag, $m)) {
            $title = html_entity_decode(rawurldecode($m[2]), ENT_QUOTES, 'UTF-8');
            $title = trim($title, " \t\n\r\0\x0B\\\\\"'");
        }

        // 이미 정상적인 URL이면 유지
        if (
            preg_match('#^(https?:)?//#i', $src) ||
            preg_match('#^/(?:data/)?editor/#i', $src) ||
            preg_match('#^/data/editor/#i', $src) ||
            preg_match('#^(data:|blob:)#i', $src)
        ) {
            return $tag;
        }

        // /dataeditor가 아직 남아 있으면 한 번 더 강제 보정
        if (preg_match('#^/?dataeditor/(.+)$#i', $src, $m)) {
            $new_src = '/data/editor/' . ltrim($m[1], '/');

            return preg_replace(
                '/\bsrc\s*=\s*(["\'])(.*?)\1/i',
                'src="' . htmlspecialchars($new_src, ENT_QUOTES, 'UTF-8') . '"',
                $tag,
                1
            );
        }

        // src가 \ 또는 %5C 등으로 깨졌다면 alt/title에서 파일명 복구
        $broken_src = (
            $src === '' ||
            $src === '\\' ||
            $src === '/' ||
            strtolower($src) === '%5c'
        );

        if (!$broken_src) {
            return $tag;
        }

        $filename = '';

        foreach (array($alt, $title) as $candidate) {
            if ($candidate === '') {
                continue;
            }

            $candidate = html_entity_decode($candidate, ENT_QUOTES, 'UTF-8');
            $candidate = trim($candidate, " \t\n\r\0\x0B\\\\\"'");
            $candidate = basename(str_replace('\\', '/', $candidate));

            if (preg_match('/^[^\/\\\\]+\.(jpe?g|png|gif|webp)$/i', $candidate)) {
                $filename = $candidate;
                break;
            }
        }

        if ($filename === '') {
            return $tag;
        }

        $new_src = '/data/editor/' . rawurlencode($filename);

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

        return $tag;
    },
    $post_content
);

$use_yn = isset($_POST['use_yn']) && $_POST['use_yn'] === 'N' ? 'N' : 'Y';

if ($post_subject === '') {
    alert('제목을 입력해 주세요.');
}

$post_subject = mb_substr(clean_xss_tags($post_subject, 1, 1), 0, 255, 'UTF-8');

$post_subject_sql = sql_real_escape_string($post_subject);
$post_content_sql = sql_real_escape_string($post_content);

if ($w === 'u') {
    $row = sql_fetch("
        SELECT post_id
        FROM donuts_dotty_posts
        WHERE post_id = '{$post_id}'
          AND dotty_mb_id = '{$dotty_mb_id_sql}'
        LIMIT 1
    ");

    if (empty($row['post_id'])) {
        alert('수정 권한이 없는 게시글입니다.');
    }

    sql_query("
        UPDATE donuts_dotty_posts
        SET
            post_subject = '{$post_subject_sql}',
            post_content = '{$post_content_sql}',
            use_yn = '{$use_yn}',
            updated_at = NOW()
        WHERE post_id = '{$post_id}'
          AND dotty_mb_id = '{$dotty_mb_id_sql}'
    ");

    alert('게시글이 수정되었습니다.', './post_list.php');
}

sql_query("
    INSERT INTO donuts_dotty_posts
    SET
        dotty_mb_id = '{$dotty_mb_id_sql}',
        post_subject = '{$post_subject_sql}',
        post_content = '{$post_content_sql}',
        view_count = 0,
        comment_count = 0,
        use_yn = '{$use_yn}',
        created_at = NOW(),
        updated_at = NOW()
");

alert('게시글이 등록되었습니다.', './post_list.php');

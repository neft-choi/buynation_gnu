<?php
$sub_menu = '710800';
require_once './_common.php';

auth_check_menu($auth, $sub_menu, "w");
check_demo();
check_admin_token();

if (!$is_member) {
    alert('로그인 후 이용해 주세요.', G5_URL);
}

$mb_id = trim($member['mb_id']);

if ($mb_id === '') {
    alert('로그인 회원정보를 확인할 수 없습니다.');
}

$mb_id_sql = sql_real_escape_string($mb_id);

$dot_auto_join = isset($_POST['dot_auto_join']) ? 1 : 0;

$contribution_rate = isset($_POST['contribution_rate'])
    ? (float)$_POST['contribution_rate']
    : 0;

$discount_rate = isset($_POST['discount_rate'])
    ? (float)$_POST['discount_rate']
    : 0;

if ($contribution_rate < 0 || $contribution_rate > 100) {
    alert('기여금은 0~100 사이로 입력해 주세요.');
}

if ($discount_rate < 0 || $discount_rate > 100) {
    alert('할인율은 0~100 사이로 입력해 주세요.');
}

$allowed_group_types = array(
    'club',
    'organization',
    'company',
    'etc'
);

$group_type = isset($_POST['group_type'])
    ? trim($_POST['group_type'])
    : 'club';

if (!in_array($group_type, $allowed_group_types, true)) {
    $group_type = 'club';
}

$dotty_title = isset($_POST['dotty_title'])
    ? trim(clean_xss_tags($_POST['dotty_title'], 1, 1))
    : '';

if ($dotty_title === '') {
    alert('도티 제목을 입력해 주세요.');
}

$dotty_title = mb_substr($dotty_title, 0, 255, 'UTF-8');

$dotty_info = isset($_POST['dotty_info'])
    ? $_POST['dotty_info']
    : '';

$dotty_info_sql = sql_real_escape_string($dotty_info);
$dotty_title_sql = sql_real_escape_string($dotty_title);
$group_type_sql = sql_real_escape_string($group_type);

// 설정 테이블 존재 여부 확인
$table_check = sql_query("SHOW TABLES LIKE 'donuts_dotty_settings'", false);

if (!$table_check || sql_num_rows($table_check) < 1) {
    alert('donuts_dotty_settings 테이블이 없습니다. install.sql을 먼저 실행해 주세요.');
}

// 기존 설정 조회
$current = sql_fetch("
    SELECT *
    FROM donuts_dotty_settings
    WHERE mb_id = '{$mb_id_sql}'
    LIMIT 1
");

if (!is_array($current)) {
    $current = array();
}

$current_business_license_image = !empty($current['business_license_image'])
    ? $current['business_license_image']
    : '';

$current_top_image = !empty($current['top_image'])
    ? $current['top_image']
    : '';

$current_info_banner = !empty($current['info_banner'])
    ? $current['info_banner']
    : '';

// 판매자별 업로드 디렉터리
$upload_root = G5_DATA_PATH . '/dotty';
$upload_dir = $upload_root . '/' . $mb_id;

if (!is_dir($upload_root)) {
    if (!@mkdir($upload_root, G5_DIR_PERMISSION, true) && !is_dir($upload_root)) {
        alert('도티 업로드 상위 디렉터리를 생성할 수 없습니다.');
    }

    @chmod($upload_root, G5_DIR_PERMISSION);
}

if (!is_dir($upload_dir)) {
    if (!@mkdir($upload_dir, G5_DIR_PERMISSION, true) && !is_dir($upload_dir)) {
        alert('도티 업로드 디렉터리를 생성할 수 없습니다.');
    }

    @chmod($upload_dir, G5_DIR_PERMISSION);
}

if (!is_writable($upload_dir)) {
    alert('도티 업로드 디렉터리에 쓰기 권한이 없습니다. data/dotty 디렉터리 권한을 확인해 주세요.');
}

function dotty_settings_delete_image($upload_dir, $filename)
{
    if (!$filename) {
        return;
    }

    $basename = basename($filename);
    $path = $upload_dir . '/' . $basename;

    if (is_file($path)) {
        @unlink($path);
    }
}

function dotty_settings_upload_image($field_name, $upload_dir, $old_filename = '')
{
    if (!isset($_FILES[$field_name])) {
        return $old_filename;
    }

    $file = $_FILES[$field_name];

    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return $old_filename;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        alert('이미지 업로드 중 오류가 발생했습니다. 오류코드: ' . (int)$file['error']);
    }

    if (
        !isset($file['tmp_name']) ||
        !is_uploaded_file($file['tmp_name'])
    ) {
        alert('정상적인 업로드 파일이 아닙니다.');
    }

    if (!isset($file['size']) || $file['size'] <= 0) {
        alert('빈 이미지 파일은 업로드할 수 없습니다.');
    }

    if ($file['size'] > 10 * 1024 * 1024) {
        alert('이미지 파일은 10MB 이하만 업로드할 수 있습니다.');
    }

    $tmp_name = $file['tmp_name'];

    $image_info = @getimagesize($tmp_name);

    if (!$image_info) {
        alert('정상적인 이미지 파일이 아닙니다.');
    }

    $allowed_mime = array(
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp'
    );

    $mime = isset($image_info['mime'])
        ? strtolower($image_info['mime'])
        : '';

    if (!isset($allowed_mime[$mime])) {
        alert('JPG, PNG, GIF, WEBP 이미지만 업로드할 수 있습니다.');
    }

    $ext = $allowed_mime[$mime];

    try {
        $random = bin2hex(random_bytes(8));
    } catch (Exception $e) {
        $random = md5(uniqid((string)mt_rand(), true));
    }

    $new_filename = $field_name . '_' . date('YmdHis') . '_' . $random . '.' . $ext;
    $dest = $upload_dir . '/' . $new_filename;

    if (!move_uploaded_file($tmp_name, $dest)) {
        alert('이미지 파일을 서버에 저장하지 못했습니다.');
    }

    @chmod($dest, G5_FILE_PERMISSION);

    if ($old_filename && $old_filename !== $new_filename) {
        dotty_settings_delete_image($upload_dir, $old_filename);
    }

    return $new_filename;
}

// 삭제 체크 처리
if (!empty($_POST['business_license_image_del'])) {
    dotty_settings_delete_image($upload_dir, $current_business_license_image);
    $current_business_license_image = '';
}

if (!empty($_POST['top_image_del'])) {
    dotty_settings_delete_image($upload_dir, $current_top_image);
    $current_top_image = '';
}

if (!empty($_POST['info_banner_del'])) {
    dotty_settings_delete_image($upload_dir, $current_info_banner);
    $current_info_banner = '';
}

// 신규 업로드
$business_license_image = dotty_settings_upload_image(
    'business_license_image',
    $upload_dir,
    $current_business_license_image
);

$top_image = dotty_settings_upload_image(
    'top_image',
    $upload_dir,
    $current_top_image
);

$info_banner = dotty_settings_upload_image(
    'info_banner',
    $upload_dir,
    $current_info_banner
);

$business_license_image_sql = sql_real_escape_string($business_license_image);
$top_image_sql = sql_real_escape_string($top_image);
$info_banner_sql = sql_real_escape_string($info_banner);

// UNIQUE KEY(mb_id)가 있으므로 INSERT ... ON DUPLICATE KEY UPDATE 방식으로 처리.
// 기존 행 조회 여부와 관계없이 현재 로그인 회원 기준으로 확실하게 저장됩니다.
$sql = "
    INSERT INTO donuts_dotty_settings
    SET
        mb_id = '{$mb_id_sql}',
        dot_auto_join = '{$dot_auto_join}',
        business_license_image = '{$business_license_image_sql}',
        contribution_rate = '{$contribution_rate}',
        discount_rate = '{$discount_rate}',
        group_type = '{$group_type_sql}',
        dotty_title = '{$dotty_title_sql}',
        top_image = '{$top_image_sql}',
        dotty_info = '{$dotty_info_sql}',
        info_banner = '{$info_banner_sql}',
        created_at = NOW(),
        updated_at = NOW()
    ON DUPLICATE KEY UPDATE
        dot_auto_join = VALUES(dot_auto_join),
        business_license_image = VALUES(business_license_image),
        contribution_rate = VALUES(contribution_rate),
        discount_rate = VALUES(discount_rate),
        group_type = VALUES(group_type),
        dotty_title = VALUES(dotty_title),
        top_image = VALUES(top_image),
        dotty_info = VALUES(dotty_info),
        info_banner = VALUES(info_banner),
        updated_at = NOW()
";

$result = sql_query($sql, false);

if (!$result) {
    alert('도티 설정 DB 저장에 실패했습니다. MySQL 오류 로그와 donuts_dotty_settings 테이블 구조를 확인해 주세요.');
}

// 저장 직후 실제 DB에 들어갔는지 다시 확인
$saved = sql_fetch("
    SELECT
        id,
        mb_id,
        dot_auto_join,
        contribution_rate,
        discount_rate,
        group_type,
        dotty_title,
        business_license_image,
        top_image,
        info_banner
    FROM donuts_dotty_settings
    WHERE mb_id = '{$mb_id_sql}'
    LIMIT 1
");

if (empty($saved['id']) || $saved['mb_id'] !== $mb_id) {
    alert('SQL 실행 후 저장된 설정을 확인하지 못했습니다. DB 연결 대상 또는 테이블을 확인해 주세요.');
}

alert('도티 설정이 저장되었습니다.', './settings.php');

<?php
$sub_menu = '730200';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '도넛 정보 관리';

function donuts_info_e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function donuts_info_token() {
    try {
        return bin2hex(random_bytes(18));
    } catch (Exception $e) {
        return sha1(uniqid((string)mt_rand(), true));
    }
}

$target_mb_id = trim((string)$member['mb_id']);

if ($is_admin === 'super' && !empty($_REQUEST['mb_id'])) {
    $target_mb_id = trim((string)$_REQUEST['mb_id']);
}

if ($target_mb_id === '') {
    alert('도넛 관리 계정을 확인할 수 없습니다.');
}

$target_mb_id_sql = sql_real_escape_string($target_mb_id);

/*
 * 기존 donuts_dotty_settings를 확장해서 사용합니다.
 * migration_donuts_info.sql을 최초 1회 실행해 주세요.
 */
$required_columns = array(
    'dotty_category',
    'short_intro',
    'activity_rules',
    'visibility',
    'invite_token',
    'invite_version',
    'invite_issued_at'
);

foreach ($required_columns as $required_column) {
    $required_column_sql = sql_real_escape_string($required_column);
    $check = sql_fetch("
        SELECT COUNT(*) AS cnt
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'donuts_dotty_settings'
          AND COLUMN_NAME = '{$required_column_sql}'
    ");

    if (empty($check['cnt'])) {
        alert('도넛 정보 관리 DB 마이그레이션이 필요합니다. migration_donuts_info.sql을 먼저 실행해 주세요.');
    }
}

$question_table = sql_fetch("
    SELECT COUNT(*) AS cnt
    FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'donuts_dotty_join_questions'
");

if (empty($question_table['cnt'])) {
    alert('가입 질문 테이블이 없습니다. migration_donuts_info.sql을 먼저 실행해 주세요.');
}

$settings = sql_fetch("
    SELECT *
    FROM donuts_dotty_settings
    WHERE mb_id = '{$target_mb_id_sql}'
    LIMIT 1
");

if (empty($settings['id'])) {
    $new_token = donuts_info_token();
    $new_token_sql = sql_real_escape_string($new_token);

    sql_query("
        INSERT INTO donuts_dotty_settings
            (mb_id, dot_auto_join, group_type, dotty_title, top_image, dotty_info,
             dotty_category, short_intro, activity_rules, visibility,
             invite_token, invite_version, invite_issued_at, created_at, updated_at)
        VALUES
            ('{$target_mb_id_sql}', 1, 'club', '', '', '',
             '', '', '', 'public',
             '{$new_token_sql}', 1, NOW(), NOW(), NOW())
    ");

    $settings = sql_fetch("
        SELECT *
        FROM donuts_dotty_settings
        WHERE mb_id = '{$target_mb_id_sql}'
        LIMIT 1
    ");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_admin_token();

    $action = isset($_POST['action']) ? trim((string)$_POST['action']) : 'save';

    if ($action === 'regenerate_invite') {
        $new_token = donuts_info_token();
        $new_token_sql = sql_real_escape_string($new_token);

        sql_query("
            UPDATE donuts_dotty_settings
               SET invite_token = '{$new_token_sql}',
                   invite_version = invite_version + 1,
                   invite_issued_at = NOW(),
                   updated_at = NOW()
             WHERE mb_id = '{$target_mb_id_sql}'
        ");

        alert('초대 링크와 QR을 재발급했습니다.', './donuts_info.php?mb_id='.urlencode($target_mb_id));
    }

    $dotty_title = isset($_POST['dotty_title']) ? trim((string)$_POST['dotty_title']) : '';
    $dotty_category = isset($_POST['dotty_category']) ? trim((string)$_POST['dotty_category']) : '';
    $short_intro = isset($_POST['short_intro']) ? trim((string)$_POST['short_intro']) : '';
    $dotty_info = isset($_POST['dotty_info']) ? trim((string)$_POST['dotty_info']) : '';
    $activity_rules = isset($_POST['activity_rules']) ? trim((string)$_POST['activity_rules']) : '';
    $join_type = isset($_POST['join_type']) && $_POST['join_type'] === 'approval' ? 'approval' : 'instant';
    $visibility = isset($_POST['visibility']) && $_POST['visibility'] === 'private' ? 'private' : 'public';

    if ($dotty_title === '') {
        alert('도넛 이름을 입력해 주세요.');
    }

    if (mb_strlen($short_intro, 'UTF-8') > 255) {
        alert('한 줄 소개는 255자 이하로 입력해 주세요.');
    }

    if (mb_strlen($dotty_info, 'UTF-8') > 500) {
        alert('커뮤니티 소개는 500자 이하로 입력해 주세요.');
    }

    $top_image = isset($settings['top_image']) ? trim((string)$settings['top_image']) : '';

    if (isset($_FILES['top_image']) && is_uploaded_file($_FILES['top_image']['tmp_name'])) {
        if ((int)$_FILES['top_image']['error'] !== UPLOAD_ERR_OK) {
            alert('대표 이미지 업로드에 실패했습니다.');
        }

        $image_info = @getimagesize($_FILES['top_image']['tmp_name']);
        $allowed_types = array(IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png');

        if (!$image_info || !isset($allowed_types[$image_info[2]])) {
            alert('대표 이미지는 JPG 또는 PNG만 등록할 수 있습니다.');
        }

        $upload_dir = G5_DATA_PATH.'/donuts_info';
        $upload_url = G5_DATA_URL.'/donuts_info';

        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, G5_DIR_PERMISSION, true);
        }

        $filename = $target_mb_id.'_'.date('YmdHis').'_'.substr(donuts_info_token(), 0, 10).'.'.$allowed_types[$image_info[2]];
        $save_path = $upload_dir.'/'.$filename;

        if (!move_uploaded_file($_FILES['top_image']['tmp_name'], $save_path)) {
            alert('대표 이미지 저장에 실패했습니다. data/donuts_info 디렉터리 권한을 확인해 주세요.');
        }

        @chmod($save_path, G5_FILE_PERMISSION);

        if ($top_image !== '' && strpos($top_image, $upload_url.'/') === 0) {
            $old_path = G5_DATA_PATH.'/donuts_info/'.basename($top_image);
            if (is_file($old_path)) {
                @unlink($old_path);
            }
        }

        $top_image = $upload_url.'/'.$filename;
    }

    $dotty_title_sql = sql_real_escape_string($dotty_title);
    $dotty_category_sql = sql_real_escape_string($dotty_category);
    $short_intro_sql = sql_real_escape_string($short_intro);
    $dotty_info_sql = sql_real_escape_string($dotty_info);
    $activity_rules_sql = sql_real_escape_string($activity_rules);
    $visibility_sql = sql_real_escape_string($visibility);
    $top_image_sql = sql_real_escape_string($top_image);
    $dot_auto_join = ($join_type === 'instant') ? 1 : 0;

    sql_query("
        UPDATE donuts_dotty_settings
           SET dotty_title = '{$dotty_title_sql}',
               dotty_category = '{$dotty_category_sql}',
               short_intro = '{$short_intro_sql}',
               dotty_info = '{$dotty_info_sql}',
               activity_rules = '{$activity_rules_sql}',
               dot_auto_join = '{$dot_auto_join}',
               visibility = '{$visibility_sql}',
               top_image = '{$top_image_sql}',
               updated_at = NOW()
         WHERE mb_id = '{$target_mb_id_sql}'
    ");

    sql_query("
        DELETE FROM donuts_dotty_join_questions
        WHERE dotty_mb_id = '{$target_mb_id_sql}'
    ");

    $questions = isset($_POST['questions']) && is_array($_POST['questions'])
        ? $_POST['questions']
        : array();

    $sort_order = 1;
    foreach ($questions as $question) {
        $question = trim((string)$question);
        if ($question === '') {
            continue;
        }

        if (mb_strlen($question, 'UTF-8') > 255) {
            $question = mb_substr($question, 0, 255, 'UTF-8');
        }

        $question_sql = sql_real_escape_string($question);
        sql_query("
            INSERT INTO donuts_dotty_join_questions
                (dotty_mb_id, question_text, sort_order, use_yn, created_at, updated_at)
            VALUES
                ('{$target_mb_id_sql}', '{$question_sql}', '{$sort_order}', 'Y', NOW(), NOW())
        ");
        $sort_order++;
    }

    alert('변경사항을 저장했습니다.', './donuts_info.php?mb_id='.urlencode($target_mb_id));
}

$settings = sql_fetch("
    SELECT *
    FROM donuts_dotty_settings
    WHERE mb_id = '{$target_mb_id_sql}'
    LIMIT 1
");

$questions = array();
$qres = sql_query("
    SELECT id, question_text, sort_order
    FROM donuts_dotty_join_questions
    WHERE dotty_mb_id = '{$target_mb_id_sql}'
      AND use_yn = 'Y'
    ORDER BY sort_order ASC, id ASC
");

while ($qrow = sql_fetch_array($qres)) {
    $questions[] = $qrow;
}

$join_type = !empty($settings['dot_auto_join']) ? 'instant' : 'approval';
$visibility = isset($settings['visibility']) && $settings['visibility'] === 'private' ? 'private' : 'public';

if (empty($settings['invite_token'])) {
    $new_token = donuts_info_token();
    $new_token_sql = sql_real_escape_string($new_token);
    sql_query("
        UPDATE donuts_dotty_settings
           SET invite_token = '{$new_token_sql}',
               invite_version = IF(invite_version < 1, 1, invite_version),
               invite_issued_at = NOW()
         WHERE mb_id = '{$target_mb_id_sql}'
    ");
    $settings['invite_token'] = $new_token;
    $settings['invite_version'] = max(1, (int)$settings['invite_version']);
    $settings['invite_issued_at'] = date('Y-m-d H:i:s');
}

$invite_path = '/join/'.rawurlencode($target_mb_id).'?invite='.rawurlencode($settings['invite_token']);
$invite_url = (defined('G5_URL') ? G5_URL : '').$invite_path;
$qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=320x320&data='.rawurlencode($invite_url);

$issued_at = !empty($settings['invite_issued_at'])
    ? date('Y.m.d H:i', strtotime($settings['invite_issued_at']))
    : '-';

$admin_token = get_admin_token();

require_once '../admin.head.php';
?>

<form id="donuts-info-form" method="post" enctype="multipart/form-data">
<input type="hidden" name="token" value="<?php echo $admin_token; ?>">
<input type="hidden" name="action" id="donuts-info-action" value="save">
<input type="hidden" name="mb_id" value="<?php echo donuts_info_e($target_mb_id); ?>">
<input type="hidden" name="join_type" id="join_type" value="<?php echo donuts_info_e($join_type); ?>">
<input type="hidden" name="visibility" id="visibility" value="<?php echo donuts_info_e($visibility); ?>">
<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="mt-1 text-gray-400">도트가 확인하는 한 줄 소개와 더보기 콘텐츠, 가입·공개 조건을 관리합니다.</p>
        <div class="flex items-center gap-2">
            <button type="button" class="donuts-preview-modal-open shrink-0 border border-gray-300 rounded-lg bg-white px-3 py-2 text-gray-900 font-bold">
                도넛 미리보기
            </button>
            <button type="submit" class="shrink-0 rounded-lg bg-amber-300 px-3 py-2 text-gray-900 font-bold">
                변경사항 저장
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 pc:grid-cols-2 gap-4 mt-4">
        <div class="pc:col-span-2 grid grid-cols-1 pc:grid-cols-[6fr_4fr] gap-4">
            <div class="grid grid-cols-1 pc:grid-cols-2 gap-3 border border-gray-300 rounded-lg bg-white p-3">
                <div>
                    <label class="block font-bold mb-2">도넛 이름</label>
                    <input type="text" id="dotty-title" name="dotty_title" maxlength="255" required class="w-full border border-gray-300 rounded-lg p-3" value="<?php echo donuts_info_e($settings['dotty_title']); ?>">
                </div>

                <div>
                    <label class="block font-bold mb-2">카테고리</label>
                    <input type="text" id="dotty-category" name="dotty_category" maxlength="100" class="w-full border border-gray-300 rounded-lg p-3" value="<?php echo donuts_info_e($settings['dotty_category']); ?>">
                </div>

                <div class="pc:col-span-2">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block font-bold">한 줄 소개</label>
                        <span class="block text-2xs text-gray-400">가입 안내 팝업에 표시됩니다.</span>
                    </div>

                    <textarea id="short-intro" name="short_intro" maxlength="255" class="w-full border border-gray-300 rounded-lg p-3"><?php echo donuts_info_e($settings['short_intro']); ?></textarea>
                </div>

                <div class="pc:col-span-2">
                    <label class="block font-bold mb-2">대표 이미지</label>
                    <div class="flex items-center justify-between rounded-lg bg-gray-100 px-3 py-2">
                        <input type="file" id="donuts-cover-image" name="top_image" class="hidden" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                        <input type="text" id="donuts-cover-image-view" readonly class="min-w-0 flex-1 bg-transparent text-gray-500 outline-none" value="<?php echo donuts_info_e($settings['top_image']); ?>" placeholder="선택된 파일이 없습니다.">
                        <label for="donuts-cover-image" class="shrink-0 cursor-pointer rounded-lg border border-gray-300 bg-white px-3 py-2 font-bold text-gray-900 hover:bg-gray-50">
                            이미지 변경
                        </label>
                    </div>
                    <span class="mt-1 block text-2xs text-gra y-400">권장 비율 16:7 · JPG, PNG 형식을 사용합니다.</span>
                </div>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white p-3">
                <div class="border border-gray-300 rounded-lg bg-blue-50">
                    <div class="px-3 pt-3">
                        <div id="inline-cover-preview" class="h-40 rounded-lg bg-green-800 bg-cover bg-center"<?php if (!empty($settings['top_image'])) { ?> style="background-image:url('<?php echo donuts_info_e($settings['top_image']); ?>')"<?php } ?>></div>
                    </div>

                    <div class="p-3">
                        <div class="flex items-end gap-2 justify-between">
                            <div>
                                <h3 id="inline-title-preview" class="text-base font-bold text-gray-900"><?php echo donuts_info_e($settings['dotty_title']); ?></h3>

                                <p class="mt-1 truncate text-2xs text-gray-400">
                                    <span id="inline-intro-preview"><?php echo donuts_info_e($settings['short_intro']); ?></span>
                                </p>
                            </div>
                            <button type="button" class="donuts-preview-modal-open shrink-0 rounded-full border border-gray-300 px-2 py-1 text-2xs font-bold text-gray-900">
                                더보기
                            </button>
                        </div>

                        <div class="mt-3 flex items-center justify-between gap-3 rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs text-gray-900">
                            <span class="min-w-0 flex-1 truncate">[공지] 오프라인 모임 신청 안내</span>
                            <span class="shrink-0 font-bold">+2</span>
                        </div>
                    </div>

                    <ul id="preview-tabs" class="grid grid-cols-4 border-t border-gray-300">
                        <li>
                            <button type="button" aria-pressed="false" class="w-full border-b-2 border-transparent px-1 py-3 text-2xs text-gray-700">
                                추천상품
                            </button>
                        </li>
                        <li>
                            <button type="button" aria-pressed="false" class="w-full border-b-2 border-transparent px-1 py-3 text-2xs text-gray-700">
                                전체공지
                            </button>
                        </li>
                        <li>
                            <button type="button" aria-pressed="true" class="w-full border-b-2 border-gray-900 px-1 py-3 text-2xs font-bold text-gray-900">
                                커뮤니티 01
                            </button>
                        </li>
                        <li>
                            <button type="button" aria-pressed="false" class="w-full border-b-2 border-transparent px-1 py-3 text-2xs text-gray-700">
                                커뮤니티 02
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="pc:col-span-2 mt-4 border border-gray-300 rounded-lg bg-white p-3">
            <div class="flex items-center justify-between gap-3 border-b border-gray-300 pb-3">
                <div>
                    <h3 class="text-base font-bold text-gray-900">커뮤니티 소개 · 활동 규칙</h3>
                    <p class="mt-1 text-2xs text-gray-400">
                        도넛의 한 줄 소개 옆 ‘더 보기’를 눌렀을 때 팝업으로 표시되는 내용입니다.
                    </p>
                </div>

                <button type="button" class="donuts-preview-modal-open shrink-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                    도넛 미리보기
                </button>
            </div>

            <div class="mt-4">
                <div class="flex items-center justify-between gap-3">
                    <label class="block font-bold">커뮤니티 소개</label>
                    <span class="text-2xs text-gray-400">커뮤니티의 목적과 주요 활동을 설명해 주세요.</span>
                </div>

                <textarea id="dotty-info" name="dotty_info" maxlength="500" class="mt-2 h-30 w-full rounded-lg border border-gray-300 p-3"><?php echo donuts_info_e($settings['dotty_info']); ?></textarea>

                <span class="mt-1 block text-2xs text-gray-400">
                    최대 500자 · 첫 화면의 한 줄 소개와 별도로 표시됩니다.
                </span>
            </div>

            <div class="mt-4">
                <div class="flex items-center justify-between gap-3">
                    <label class="block font-bold">활동 규칙</label>
                    <span class="text-2xs text-gray-400">한 줄에 한 항목씩 입력해 주세요.</span>
                </div>

                <textarea id="activity-rules" name="activity_rules" class="mt-2 h-40 w-full rounded-lg border border-gray-300 p-3"><?php echo donuts_info_e($settings['activity_rules']); ?></textarea>

                <span class="mt-1 block text-2xs text-gray-400">
                    팝업에서는 입력 순서대로 번호가 붙어 표시됩니다.
                </span>
            </div>

            <div class="mt-4 rounded-lg bg-gray-100 text-2xs text-gray-500 p-3">
                <span class="font-bold text-gray-900">노출 방식</span>
                별도 탭을 만들지 않고, 도넛의 한 줄 소개 옆 ‘더 보기’를 누르면 커뮤니티 소개와 활동 규칙만 팝업으로 표시됩니다.
            </div>
        </div>

        <div class="border border-gray-300 rounded-lg bg-white p-3">
            <h3 class="text-base font-bold text-gray-900">가입 · 공개 설정</h3>
            <p class="mt-1 text-2xs text-gray-400">
                가입 절차와 도넛이 노출되는 범위를 각각 설정합니다.
            </p>

            <div class="mt-4">
                <p class="font-bold text-gray-900">가입 방식</p>

                <div class="donuts-setting-options mt-2 grid grid-cols-1 gap-3 pc:grid-cols-2" data-setting="join_type">
                    <button type="button" data-value="approval" aria-pressed="<?php echo $join_type === 'approval' ? 'true' : 'false'; ?>" class="rounded-lg <?php echo $join_type === 'approval' ? 'border-2 border-amber-400 bg-amber-50' : 'border border-gray-300'; ?> p-3 text-left">
                        <span class="block font-bold text-gray-900">승인형</span>
                        <span class="mt-1 block text-2xs text-gray-400">
                            가입 신청 내용을 도티 또는 권한을 가진 운영자가 승인합니다.
                        </span>
                    </button>

                    <button type="button" data-value="instant" aria-pressed="<?php echo $join_type === 'instant' ? 'true' : 'false'; ?>" class="rounded-lg <?php echo $join_type === 'instant' ? 'border-2 border-amber-400 bg-amber-50' : 'border border-gray-300'; ?> p-3 text-left">
                        <span class="block font-bold text-gray-900">즉시 가입형</span>
                        <span class="mt-1 block text-2xs text-gray-400">
                            도트가 가입 버튼을 누르면 즉시 가입됩니다.
                        </span>
                    </button>
                </div>
            </div>

            <div class="mt-4 border-t border-gray-300 pt-4">
                <p class="font-bold text-gray-900">공개 범위</p>

                <div class="donuts-setting-options mt-2 grid grid-cols-1 gap-3 pc:grid-cols-2" data-setting="visibility">
                    <button type="button" data-value="public" aria-pressed="<?php echo $visibility === 'public' ? 'true' : 'false'; ?>" class="rounded-lg <?php echo $visibility === 'public' ? 'border-2 border-amber-400 bg-amber-50' : 'border border-gray-300'; ?> p-3 text-left">
                        <span class="block font-bold text-gray-900">공개</span>
                        <span class="mt-1 block text-2xs text-gray-400">
                            검색과 도넛 목록 등 일반 탐색 영역에 노출됩니다.
                        </span>
                    </button>

                    <button type="button" data-value="private" aria-pressed="<?php echo $visibility === 'private' ? 'true' : 'false'; ?>" class="rounded-lg <?php echo $visibility === 'private' ? 'border-2 border-amber-400 bg-amber-50' : 'border border-gray-300'; ?> p-3 text-left">
                        <span class="block font-bold text-gray-900">비공개</span>
                        <span class="mt-1 block text-2xs text-gray-400">
                            일반 탐색에서는 숨기고 유효한 초대 링크·QR로만 진입합니다.
                        </span>
                    </button>
                </div>
            </div>

            <div class="mt-4 rounded-lg bg-gray-100 p-3 text-2xs text-gray-500">
                <span class="font-bold text-gray-900">설정 변경 시</span>
                새 가입자부터 변경한 방식이 적용됩니다. 승인형에서 즉시 가입형으로 바꿔도 이미 접수된 승인 대기 신청은 자동 처리하지 않고 목록에 보관합니다.
            </div>
        </div>

        <div class="border border-gray-300 rounded-lg bg-white p-3">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-base font-bold text-gray-900">초대 링크 · QR</h3>
                    <p class="mt-1 text-2xs text-gray-400">
                        이 도넛 전용 초대 수단입니다. 공개 여부와 관계없이 발급할 수 있습니다.
                    </p>
                </div>

                <div class="flex shrink-0 gap-1">
                    <span class="rounded-full bg-gray-100 px-2 py-1 text-2xs font-bold text-gray-700">● 공개</span>
                    <span class="rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">● 사용 중</span>
                </div>
            </div>

            <div class="mt-4 flex flex-col pc:flex-row gap-4">
                <div class="w-40 h-40 border border-gray-300 rounded-lg bg-gray-100 overflow-hidden">
                    <img id="invite-qr-image" src="<?php echo donuts_info_e($qr_url); ?>" alt="초대 QR" class="w-full h-full object-contain">
                </div>

                <div class="flex-1 min-w-0">
                    <p class="font-bold text-gray-900">현재 초대 링크</p>

                    <div class="mt-2 flex gap-2">
                        <input type="text" readonly class="min-w-0 flex-1 rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-2xs text-gray-500" id="invite-url" value="<?php echo donuts_info_e($invite_url); ?>">
                        <button type="button" id="invite-copy-button" class="shrink-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                            복사
                        </button>
                    </div>

                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <div class="rounded-lg bg-gray-100 p-2">
                            <span class="block text-2xs text-gray-400">발급 일시</span>
                            <span class="mt-1 block text-2xs font-bold text-gray-900"><?php echo donuts_info_e($issued_at); ?></span>
                        </div>

                        <div class="rounded-lg bg-gray-100 p-2">
                            <span class="block text-2xs text-gray-400">발급 버전</span>
                            <span class="mt-1 block text-2xs font-bold text-gray-900">v<?php echo (int)$settings['invite_version']; ?> · 현재 유효</span>
                        </div>
                    </div>

                    <div class="mt-3 grid grid-cols-1 gap-2 pc:grid-cols-3">
                        <button type="button" id="qr-download-button" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                            QR 저장
                        </button>
                        <button type="button" class="donuts-invite-modal-open rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                            가입 화면 확인
                        </button>
                        <button type="button" id="invite-regenerate-button" class="rounded-lg border border-red-300 bg-white px-3 py-2 text-2xs font-bold text-red-600">
                            링크·QR 재발급
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-4 rounded-lg bg-amber-50 p-3 text-2xs text-amber-800">
                <span class="block font-bold">재발급 정책</span>
                링크와 QR은 다음 재발급 전까지 계속 유효합니다. 재발급을 완료하는 즉시 이전 링크와 이전 QR은 무효화되고, 가장 최근에 발급한 1개만 사용할 수 있습니다.
            </div>
        </div>

        <div class="pc:col-span-2 border border-gray-300 rounded-lg bg-white p-3">
            <h3 class="text-base font-bold text-gray-900">가입 신청 질문</h3>
            <p class="mt-1 text-2xs text-gray-400">
                승인형 가입 시 도트에게 받을 질문입니다.
            </p>

            <ul id="join-question-list" class="mt-3 space-y-2">
                <?php foreach ($questions as $index => $question) { ?>
                <li class="join-question-row flex items-center gap-3 rounded-lg border border-gray-300 px-3 py-2">
                    <span class="question-number flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-2xs font-bold text-amber-700">Q<?php echo $index + 1; ?></span>
                    <input type="text" name="questions[]" maxlength="255" class="min-w-0 flex-1 border-0 bg-transparent text-sm text-gray-900 outline-none" value="<?php echo donuts_info_e($question['question_text']); ?>">
                    <button type="button" class="join-question-delete shrink-0 text-2xs text-red-500">삭제</button>
                </li>
                <?php } ?>
            </ul>

            <div class="mt-3 flex gap-2">
                <input type="text" id="new-question" maxlength="255" class="min-w-0 flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm" placeholder="새 가입 질문을 입력하세요">
                <button type="button" id="add-question-button" class="shrink-0 rounded-lg bg-amber-300 px-3 py-2 font-bold text-gray-900">
                    질문 추가
                </button>
            </div>
        </div>
    </div>
</section>
</form>

<!-- 도넛 미리보기 모달 -->
<div id="donuts-preview-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="donuts-preview-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="donuts-preview-modal-container" class="relative z-10 w-full max-w-240 max-h-[80vh] overflow-y-auto rounded-lg bg-white">
        <div id="donuts-preview-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="donuts-preview-modal-title" class="text-base font-bold text-gray-900">
                도넛 미리보기
            </h3>

            <button type="button" aria-label="도넛 미리보기 모달 닫기" class="donuts-preview-modal-close flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="donuts-preview-modal-body" class="p-4">
            <div class="overflow-hidden rounded-lg border border-gray-300 bg-white">
                <div class="bg-amber-100 text-2xs text-amber-800 px-3 py-2">
                    <span class="font-bold mr-2">편집 중 미리보기</span>
                    아직 저장하지 않은 도넛 이름·카테고리·소개·가입 방식·공개 범위까지 반영합니다.
                </div>

                <div class="flex flex-col pc:flex-row">
                    <div id="modal-cover-preview" class="flex w-90 h-64 items-center justify-center bg-green-800 bg-cover bg-center"<?php if (!empty($settings['top_image'])) { ?> style="background-image:url('<?php echo donuts_info_e($settings['top_image']); ?>')"<?php } ?>></div>

                    <div class="p-4">
                        <div class="flex gap-2">
                            <span id="modal-visibility-preview" class="rounded-full bg-gray-100 px-2 py-1 text-2xs font-bold text-gray-700">● <?php echo $visibility === 'public' ? '공개 도넛' : '비공개 도넛'; ?></span>
                            <span id="modal-category-preview" class="rounded-full bg-gray-100 px-2 py-1 text-2xs font-bold text-gray-700"><?php echo donuts_info_e($settings['dotty_category']); ?></span>
                        </div>

                        <h3 id="modal-title-preview" class="mt-4 text-2xl font-bold text-gray-900"><?php echo donuts_info_e($settings['dotty_title']); ?></h3>

                        <p class="mt-2 text-sm text-gray-500">
                            <span id="modal-intro-preview"><?php echo donuts_info_e($settings['short_intro']); ?></span>
                            <span class="font-bold text-gray-900">더 보기 ›</span>
                        </p>

                        <button type="button" class="mt-4 rounded-lg bg-gray-900 px-4 py-3 text-sm font-bold text-white">
                            가입 신청하기
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-gray-300 px-3 py-3 text-2xs text-gray-900">
                    <span>[공지] 오프라인 모임 신청 안내</span>
                    <span class="font-bold text-amber-700">+2</span>
                </div>

                <ul id="donuts-preview-modal-tabs" role="tablist" aria-label="도넛 미리보기 탭 리스트" class="grid grid-cols-4 border-t border-gray-300">
                    <li>
                        <button type="button" id="donuts-preview-modal-products-tab" role="tab" aria-selected="true" aria-controls="donuts-preview-modal-products-panel" class="w-full border-b-2 border-gray-900 px-1 py-3 text-2xs font-bold text-gray-900">
                            추천상품
                        </button>
                    </li>
                    <li>
                        <button type="button" id="donuts-preview-modal-notice-tab" role="tab" aria-selected="false" aria-controls="donuts-preview-modal-notice-panel" class="w-full border-b-2 border-transparent px-1 py-3 text-2xs text-gray-700">
                            전체공지
                        </button>
                    </li>
                    <li>
                        <button type="button" id="donuts-preview-modal-community-01-tab" role="tab" aria-selected="false" aria-controls="donuts-preview-modal-community-01-panel" class="w-full border-b-2 border-transparent px-1 py-3 text-2xs text-gray-700">
                            커뮤니티 01
                        </button>
                    </li>
                    <li>
                        <button type="button" id="donuts-preview-modal-community-02-tab" role="tab" aria-selected="false" aria-controls="donuts-preview-modal-community-02-panel" class="w-full border-b-2 border-transparent px-1 py-3 text-2xs text-gray-700">
                            커뮤니티 02
                        </button>
                    </li>
                </ul>

                <!-- 도넛 미리보기 모달 탭 내용 -->
                <section id="donuts-preview-modal-products-panel" role="tabpanel" aria-labelledby="donuts-preview-modal-products-tab" class="bg-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-bold text-gray-900">도티 추천상품</h3>
                        <span class="text-2xs text-gray-400">5개 노출</span>
                    </div>

                    <div class="mt-3 grid grid-cols-1 gap-3 pc:grid-cols-2">
                        <div class="flex gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-3xl">🥣</div>

                            <div class="min-w-0 flex-1">
                                <span class="text-2xs text-gray-400">그린테이블 · 일반 상품</span>
                                <p class="mt-1 text-sm font-bold text-gray-900">유기농 그래놀라 500g</p>
                                <p class="mt-1 font-bold text-gray-900">18,900원</p>
                                <span class="mt-2 block text-2xs font-bold text-amber-700">도티의 추천 이유</span>
                                <p class="mt-1 text-2xs text-gray-500">활동 전후 편하게 활용할 수 있어 테니스 커뮤니티 도트들에게 추천합니다.</p>
                            </div>
                        </div>

                        <div class="flex gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-3xl">🍫</div>

                            <div class="min-w-0 flex-1">
                                <span class="text-2xs text-gray-400">그린테이블 · 핫딜 상품</span>
                                <p class="mt-1 text-sm font-bold text-gray-900">저당 단백질바 12개입</p>
                                <p class="mt-1 font-bold text-gray-900">21,900원</p>
                                <span class="mt-2 block text-2xs font-bold text-amber-700">도티의 추천 이유</span>
                                <p class="mt-1 text-2xs text-gray-500">활동 전후 편하게 활용할 수 있어 테니스 커뮤니티 도트들에게 추천합니다.</p>
                            </div>
                        </div>

                        <div class="flex gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-3xl">🎁</div>

                            <div class="min-w-0 flex-1">
                                <span class="text-2xs text-gray-400">그린테이블 · 일반 상품</span>
                                <p class="mt-1 text-sm font-bold text-gray-900">선물용 대형 패키지</p>
                                <p class="mt-1 font-bold text-gray-900">48,000원</p>
                                <span class="mt-2 block text-2xs font-bold text-amber-700">도티의 추천 이유</span>
                                <p class="mt-1 text-2xs text-gray-500">활동 전후 편하게 활용할 수 있어 테니스 커뮤니티 도트들에게 추천합니다.</p>
                            </div>
                        </div>

                        <div class="flex gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-3xl">🧃</div>

                            <div class="min-w-0 flex-1">
                                <span class="text-2xs text-gray-400">그린테이블 · 일반 상품</span>
                                <p class="mt-1 text-sm font-bold text-gray-900">콜드프레스 주스 12병</p>
                                <p class="mt-1 font-bold text-gray-900">39,900원</p>
                                <span class="mt-2 block text-2xs font-bold text-amber-700">도티의 추천 이유</span>
                                <p class="mt-1 text-2xs text-gray-500">활동 전후 편하게 활용할 수 있어 테니스 커뮤니티 도트들에게 추천합니다.</p>
                            </div>
                        </div>

                        <div class="flex gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-3xl">📦</div>

                            <div class="min-w-0 flex-1">
                                <span class="text-2xs text-gray-400">그린테이블 · 일반 상품</span>
                                <p class="mt-1 text-sm font-bold text-gray-900">정기배송 혼합박스 24개입</p>
                                <p class="mt-1 font-bold text-gray-900">69,000원</p>
                                <span class="mt-2 block text-2xs font-bold text-amber-700">도티의 추천 이유</span>
                                <p class="mt-1 text-2xs text-gray-500">활동 전후 편하게 활용할 수 있어 테니스 커뮤니티 도트들에게 추천합니다.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="donuts-preview-modal-notice-panel" role="tabpanel" aria-labelledby="donuts-preview-modal-notice-tab" class="bg-gray-100 p-4" hidden>
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-bold text-gray-900">전체공지</h3>
                        <span class="text-2xs text-gray-400">6건</span>
                    </div>

                    <div class="mt-3 space-y-2">
                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">오프라인 모임 신청 안내</p>
                                <span class="mt-1 block text-2xs text-gray-400">2026.08.03 · 조회 1,204</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">2026 여름 테니스 캠프 모집 안내</p>
                                <span class="mt-1 block text-2xs text-gray-400">2026.08.01 · 조회 987</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">커뮤니티 운영 가이드라인 안내</p>
                                <span class="mt-1 block text-2xs text-gray-400">2026.07.29 · 조회 2,101</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">코트 이용 매너 및 안전 수칙</p>
                                <span class="mt-1 block text-2xs text-gray-400">2026.07.24 · 조회 742</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">회원 등급 운영 기준 안내</p>
                                <span class="mt-1 block text-2xs text-gray-400">2026.07.18 · 조회 681</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">7월 정기 모임 사진 공유</p>
                                <span class="mt-1 block text-2xs text-gray-400">2026.07.12 · 조회 524</span>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="donuts-preview-modal-community-01-panel" role="tabpanel" aria-labelledby="donuts-preview-modal-community-01-tab" class="bg-gray-100 p-4" hidden>
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-bold text-gray-900">커뮤니티 01</h3>
                        <span class="text-2xs text-gray-400">도티·운영자 콘텐츠</span>
                    </div>

                    <div class="mt-3 space-y-2">
                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">8월 정기 모임 참가 신청 안내</p>
                                <span class="mt-1 block text-2xs text-gray-400">도티 김도윤 · 댓글 38 · 좋아요 126</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">여름철 코트 이용 매너를 안내드립니다</p>
                                <span class="mt-1 block text-2xs text-gray-400">운영자 김도현 · 댓글 12 · 좋아요 74</span>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="donuts-preview-modal-community-02-panel" role="tabpanel" aria-labelledby="donuts-preview-modal-community-02-tab" class="bg-gray-100 p-4" hidden>
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-bold text-gray-900">커뮤니티 02</h3>
                        <span class="text-2xs text-gray-400">도트 자유게시판</span>
                    </div>

                    <div class="mt-3 space-y-2">
                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">새 라켓 사용 후기 남겨봐요</p>
                                <span class="mt-1 block text-2xs text-gray-400">최서진 · 댓글 21 · 좋아요 57</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">주말 번개 복식 멤버 모집합니다</p>
                                <span class="mt-1 block text-2xs text-gray-400">이유진 · 댓글 8 · 좋아요 31</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 rounded-lg border border-gray-300 bg-white p-3">
                            <span class="shrink-0 block w-10 h-10 rounded-lg bg-gray-100"></span>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900">초보자 포핸드 팁 공유</p>
                                <span class="mt-1 block text-2xs text-gray-400">박성훈 · 댓글 14 · 좋아요 83</span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <div id="donuts-preview-modal-footer" class="sticky bottom-0 z-10 flex justify-end border-t border-gray-300 bg-white p-4">
            <button type="button" class="donuts-preview-modal-close rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-bold text-gray-900">
                닫기
            </button>
        </div>
    </div>
</div>

<!-- 초대 링크 가입 화면 모달 -->
<div id="donuts-invite-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="donuts-invite-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="donuts-invite-modal-container" class="relative z-10 w-full max-w-160 max-h-[80vh] overflow-y-auto rounded-lg bg-white">
        <div id="donuts-invite-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="donuts-invite-modal-title" class="text-base font-bold text-gray-900">
                초대 링크 가입 화면
            </h3>

            <button type="button" aria-label="초대 링크 가입 화면 모달 닫기" class="donuts-invite-modal-close flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="donuts-invite-modal-body" class="p-4">
            <div class="rounded-lg border border-gray-300 bg-white">
                <div class="p-4">
                    <div id="invite-cover-preview" class="flex w-full h-40 items-center justify-center rounded-lg bg-green-800 bg-cover bg-center"<?php if (!empty($settings['top_image'])) { ?> style="background-image:url('<?php echo donuts_info_e($settings['top_image']); ?>')"<?php } ?>></div>
                </div>

                <div class="px-4 pb-4">
                    <div class="flex gap-2">
                        <span class="rounded-full bg-gray-100 px-2 py-1 text-2xs font-bold text-gray-700">● <?php echo $visibility === 'public' ? '공개 도넛' : '비공개 도넛'; ?></span>
                        <span class="rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">● 유효한 초대</span>
                    </div>

                    <h3 id="invite-title-preview" class="mt-4 text-sm font-bold text-gray-900"><?php echo donuts_info_e($settings['dotty_title']); ?></h3>

                    <p class="mt-1 text-xs text-gray-500">
                        <span id="invite-intro-preview"><?php echo donuts_info_e($settings['short_intro']); ?></span>
                    </p>

                    <div class="mt-2 rounded-lg bg-gray-100 p-3">
                        <span class="block text-2xs text-gray-500">가입 방식</span>
                        <p id="invite-join-type-preview" class="mt-1 text-xs text-gray-500"><?php echo $join_type === 'instant' ? '즉시 가입형 · 가입 버튼을 누르면 바로 가입됩니다.' : '승인형 · 질문 작성 후 운영자 승인을 기다립니다.'; ?></p>
                    </div>

                    <button type="button" class="mt-4 w-full rounded-lg bg-gray-900 px-4 py-3 text-sm font-bold text-white">
                        가입 신청하기
                    </button>
                </div>
            </div>

            <div class="mt-4 rounded-lg bg-amber-50 p-3 text-2xs text-amber-800">
                <span class="block text-green-600">현재 발급 버전 v<?php echo (int)$settings['invite_version']; ?>의 초대 링크로 진입한 화면입니다. 재발급된 이전 주소로는 이 화면에 들어올 수 없습니다.</span>
            </div>
        </div>

        <div id="donuts-invite-modal-footer" class="sticky bottom-0 z-10 flex justify-end border-t border-gray-300 bg-white p-4">
            <button type="button" class="donuts-invite-modal-close rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-bold text-gray-900">
                닫기
            </button>
        </div>
    </div>
</div>

<script>
    // 대표 이미지 file input 표시
    $('#donuts-cover-image').on('change', function() {
        const file = this.files[0];

        $('#donuts-cover-image-view').val(file ? file.name : '');
    });

    // 미리보기 탭 선택
    const $tabs = $('#preview-tabs button');

    $tabs.on('click', function() {
        $tabs
            .attr('aria-pressed', 'false')
            .removeClass('border-gray-900 font-bold text-gray-900')
            .addClass('border-transparent text-gray-700');

        $(this)
            .attr('aria-pressed', 'true')
            .removeClass('border-transparent text-gray-700')
            .addClass('border-gray-900 font-bold text-gray-900');
    });
    // 도넛 미리보기 모달 열기
    const $donutsPreviewModal = $('#donuts-preview-modal');

    $('.donuts-preview-modal-open').on('click', function() {
        $donutsPreviewModal.prop('hidden', false);
    });

    // 도넛 미리보기 모달 닫기
    $('.donuts-preview-modal-close, #donuts-preview-modal-backdrop').on('click', function() {
        $donutsPreviewModal.prop('hidden', true);
    });

    // 도넛 미리보기 모달 내부 탭 선택
    const $modalTabs = $('#donuts-preview-modal-tabs [role="tab"]');
    const $modalPanels = $('#donuts-preview-modal-body [role="tabpanel"]');

    $modalTabs.on('click', function() {
        const panelId = $(this).attr('aria-controls');

        // 전체 초기화 후
        $modalTabs
            .attr('aria-selected', 'false')
            .removeClass('border-gray-900 font-bold text-gray-900')
            .addClass('border-transparent text-gray-700');

        // 클릭한 탭만 적용
        $(this)
            .attr('aria-selected', 'true')
            .removeClass('border-transparent text-gray-700')
            .addClass('border-gray-900 font-bold text-gray-900');

        // 전체 패널 hidden 후 클릭한 탭의 aria-controls 안의 panelId 만 hidden 제거
        $modalPanels.prop('hidden', true);
        $('#' + panelId).prop('hidden', false);
    });

    // 가입 공개 설정 UI
    $('.donuts-setting-options > button').on('click', function() {
        const $options = $(this).closest('.donuts-setting-options').find('button');

        $options
            .attr('aria-pressed', 'false')
            .removeClass('border-2 border-amber-400 bg-amber-50')
            .addClass('border border-gray-300');

        $(this)
            .attr('aria-pressed', 'true')
            .removeClass('border border-gray-300')
            .addClass('border-2 border-amber-400 bg-amber-50');
    });

    // 초대 링크 가입 화면 모달 열기
    const $donutsInviteModal = $('#donuts-invite-modal');

    $('.donuts-invite-modal-open').on('click', function() {
        $donutsInviteModal.prop('hidden', false);
    });

    // 초대 링크 가입 화면 모달 닫기
    $('.donuts-invite-modal-close, #donuts-invite-modal-backdrop').on('click', function() {
        $donutsInviteModal.prop('hidden', true);
    });
    function updateQuestionNumbers() {
        $('#join-question-list .join-question-row').each(function(index) {
            $(this).find('.question-number').text('Q' + (index + 1));
        });
    }

    $('#add-question-button').on('click', function() {
        const text = $.trim($('#new-question').val());
        if (!text) {
            alert('가입 질문을 입력해 주세요.');
            $('#new-question').focus();
            return;
        }

        const $row = $('<li class="join-question-row flex items-center gap-3 rounded-lg border border-gray-300 px-3 py-2"></li>');
        $row.append('<span class="question-number flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-2xs font-bold text-amber-700"></span>');
        $('<input>', {
            type: 'text',
            name: 'questions[]',
            maxlength: 255,
            class: 'min-w-0 flex-1 border-0 bg-transparent text-sm text-gray-900 outline-none',
            value: text
        }).appendTo($row);
        $row.append('<button type="button" class="join-question-delete shrink-0 text-2xs text-red-500">삭제</button>');
        $('#join-question-list').append($row);
        $('#new-question').val('').focus();
        updateQuestionNumbers();
    });

    $('#new-question').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $('#add-question-button').trigger('click');
        }
    });

    $(document).on('click', '.join-question-delete', function() {
        $(this).closest('.join-question-row').remove();
        updateQuestionNumbers();
    });

    $('.donuts-setting-options > button').on('click', function() {
        const setting = $(this).closest('.donuts-setting-options').data('setting');
        const value = $(this).data('value');

        if (setting && value) {
            $('#' + setting).val(value);
        }

        updateLivePreview();
    });

    function updateLivePreview() {
        const title = $('#dotty-title').val() || '';
        const category = $('#dotty-category').val() || '';
        const intro = $('#short-intro').val() || '';
        const visibility = $('#visibility').val();
        const joinType = $('#join_type').val();

        $('#inline-title-preview, #modal-title-preview, #invite-title-preview').text(title);
        $('#inline-intro-preview, #modal-intro-preview, #invite-intro-preview').text(intro);
        $('#modal-category-preview').text(category);
        $('#modal-visibility-preview').text('● ' + (visibility === 'private' ? '비공개 도넛' : '공개 도넛'));
        $('#invite-join-type-preview').text(
            joinType === 'instant'
                ? '즉시 가입형 · 가입 버튼을 누르면 바로 가입됩니다.'
                : '승인형 · 질문 작성 후 운영자 승인을 기다립니다.'
        );
    }

    $('#dotty-title, #dotty-category, #short-intro').on('input', updateLivePreview);

    $('#donuts-cover-image').on('change', function() {
        const file = this.files[0];
        if (!file) return;

        if (!/^image\/(jpeg|png)$/.test(file.type)) {
            alert('JPG 또는 PNG 이미지만 등록할 수 있습니다.');
            this.value = '';
            $('#donuts-cover-image-view').val('');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            $('#inline-cover-preview, #modal-cover-preview, #invite-cover-preview')
                .css('background-image', 'url("' + e.target.result + '")');
        };
        reader.readAsDataURL(file);
    });

    $('#invite-copy-button').on('click', async function() {
        const value = $('#invite-url').val();

        try {
            await navigator.clipboard.writeText(value);
        } catch (e) {
            $('#invite-url').prop('readonly', false).select();
            document.execCommand('copy');
            $('#invite-url').prop('readonly', true);
        }

        alert('초대 링크를 복사했습니다.');
    });

    $('#invite-regenerate-button').on('click', function() {
        if (!confirm('재발급하면 기존 초대 링크와 QR은 즉시 사용할 수 없게 됩니다. 재발급할까요?')) {
            return;
        }

        $('#donuts-info-action').val('regenerate_invite');
        $('#donuts-info-form').trigger('submit');
    });

    $('#qr-download-button').on('click', function() {
        const src = $('#invite-qr-image').attr('src');
        const a = document.createElement('a');
        a.href = src;
        a.download = 'donuts-invite-qr.png';
        a.target = '_blank';
        document.body.appendChild(a);
        a.click();
        a.remove();
    });

    $('#donuts-info-form').on('submit', function() {
        if ($('#donuts-info-action').val() !== 'regenerate_invite') {
            $('#donuts-info-action').val('save');
        }
    });

    updateQuestionNumbers();
    updateLivePreview();

</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

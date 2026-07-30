<?php
$sub_menu = '710800';
require_once './_common.php';
require_once(G5_EDITOR_LIB);

auth_check_menu($auth, $sub_menu, "w");

if (!$is_member) {
    alert('로그인 후 이용해 주세요.', G5_URL);
}

$g5['title'] = '도티 설정';

// 현재 로그인한 도티 회원 기준으로 설정 조회
$dotty_mb_id = trim($member['mb_id']);
$dotty_mb_id_sql = sql_real_escape_string($dotty_mb_id);

$dotty = sql_fetch("
    SELECT *
    FROM donuts_dotty_settings
    WHERE mb_id = '{$dotty_mb_id_sql}'
    LIMIT 1
");

if (!is_array($dotty)) {
    $dotty = array();
}

$dot_auto_join          = isset($dotty['dot_auto_join']) ? (int)$dotty['dot_auto_join'] : 1;
$contribution_rate      = isset($dotty['contribution_rate']) ? $dotty['contribution_rate'] : '0.00';
$discount_rate          = isset($dotty['discount_rate']) ? $dotty['discount_rate'] : '0.00';
$group_type             = isset($dotty['group_type']) ? $dotty['group_type'] : 'club';
$dotty_title            = isset($dotty['dotty_title']) ? $dotty['dotty_title'] : '';
$dotty_info             = isset($dotty['dotty_info']) ? $dotty['dotty_info'] : '';
$business_license_image = isset($dotty['business_license_image']) ? $dotty['business_license_image'] : '';
$top_image              = isset($dotty['top_image']) ? $dotty['top_image'] : '';
$info_banner            = isset($dotty['info_banner']) ? $dotty['info_banner'] : '';

$upload_url = G5_DATA_URL . '/dotty/' . rawurlencode($dotty_mb_id);

function dotty_settings_h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

require_once '../admin.head.php';
?>

<form name="fdottysettings"
      id="fdottysettings"
      method="post"
      action="./settings_update.php"
      enctype="multipart/form-data"
      onsubmit="return fdottysettings_submit(this);">

    <input type="hidden" name="token" value="<?php echo get_admin_token(); ?>">

    <section class="text-sm">
        <h2>도티 설정</h2>

        <div class="text-lg">
            안녕하세요 <?php echo dotty_settings_h($member['mb_name'] ?: $member['mb_id']); ?> 도티님
        </div>

        <div class="mt-8 space-y-4">

            <div class="flex items-center gap-2">
                <label for="dot_auto_join">도트 자동가입 여부</label>
                <input type="checkbox"
                       id="dot_auto_join"
                       name="dot_auto_join"
                       value="1"
                       <?php echo $dot_auto_join ? 'checked' : ''; ?>>
            </div>

            <div class="flex items-center gap-2">
                <p>도티 사업자등록증(매출 증가시 필)</p>

                <label for="business_license_image" class="btn btn_04 cursor-pointer">
                    업로드
                </label>

                <input type="file"
                       id="business_license_image"
                       name="business_license_image"
                       accept="image/jpeg,image/png,image/gif,image/webp"
                       class="hidden"
                       data-preview="business_license_preview">
            </div>

            <div id="business_license_preview"
                 class="flex w-full h-48 max-w-120 items-center justify-center rounded border border-dashed border-gray-300 bg-gray-50 overflow-hidden">
                <?php if ($business_license_image) { ?>
                    <img src="<?php echo dotty_settings_h($upload_url . '/' . rawurlencode($business_license_image)); ?>"
                         alt="사업자등록증"
                         style="max-width:100%; max-height:100%; object-fit:contain;">
                <?php } else { ?>
                    <span class="text-gray-400">등록된 이미지가 없습니다.</span>
                <?php } ?>
            </div>

            <?php if ($business_license_image) { ?>
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="business_license_image_del" value="1">
                    기존 사업자등록증 삭제
                </label>
            <?php } ?>

            <div class="flex items-center gap-2">
                <p class="mr-12 whitespace-nowrap">정책 설정</p>

                <div class="inline-flex items-center gap-2">
                    <label for="contribution_rate" class="whitespace-nowrap">기여금</label>
                    <input type="number"
                           id="contribution_rate"
                           name="contribution_rate"
                           min="0"
                           max="100"
                           step="0.1"
                           value="<?php echo dotty_settings_h($contribution_rate); ?>"
                           class="frm_input w-24">
                    <span>%</span>
                </div>

                <span> : </span>

                <div class="inline-flex items-center gap-2">
                    <label for="discount_rate" class="whitespace-nowrap">할인율</label>
                    <input type="number"
                           id="discount_rate"
                           name="discount_rate"
                           min="0"
                           max="100"
                           step="0.1"
                           value="<?php echo dotty_settings_h($discount_rate); ?>"
                           class="frm_input w-24">
                    <span>%</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <label for="group_type" class="pr-12">그룹</label>

                <select id="group_type" name="group_type" class="frm_input w-50">
                    <option value="club" <?php echo get_selected($group_type, 'club'); ?>>동호회</option>
                    <option value="organization" <?php echo get_selected($group_type, 'organization'); ?>>단체</option>
                    <option value="company" <?php echo get_selected($group_type, 'company'); ?>>기업</option>
                    <option value="etc" <?php echo get_selected($group_type, 'etc'); ?>>기타</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label for="dotty_title" class="pr-12">도티 제목</label>

                <input type="text"
                       id="dotty_title"
                       name="dotty_title"
                       value="<?php echo dotty_settings_h($dotty_title); ?>"
                       maxlength="255"
                       required
                       class="frm_input required w-100">
            </div>

            <div>
                <div class="flex items-center gap-2">
                    <label for="top_image">도티 이미지</label>

                    <input type="file"
                           id="top_image"
                           name="top_image"
                           accept="image/jpeg,image/png,image/gif,image/webp"
                           class="hidden"
                           data-preview="top_image_preview">

                    <label for="top_image" class="btn btn_04 cursor-pointer">
                        업로드
                    </label>
                </div>

                <div id="top_image_preview"
                     class="mt-2 flex h-48 w-full max-w-120 items-center justify-center rounded border border-dashed border-gray-300 bg-gray-50 overflow-hidden">
                    <?php if ($top_image) { ?>
                        <img src="<?php echo dotty_settings_h($upload_url . '/' . rawurlencode($top_image)); ?>"
                             alt="도티 이미지"
                             style="max-width:100%; max-height:100%; object-fit:contain;">
                    <?php } else { ?>
                        <span class="text-gray-400">등록된 이미지가 없습니다.</span>
                    <?php } ?>
                </div>

                <?php if ($top_image) { ?>
                    <label class="mt-2 inline-flex items-center gap-2">
                        <input type="checkbox" name="top_image_del" value="1">
                        기존 도티 이미지 삭제
                    </label>
                <?php } ?>
            </div>

            <div>
                <label for="dotty_info">도티 정보</label>
                <?php echo editor_html('dotty_info', $dotty_info); ?>
            </div>

            <div>
                <div class="flex items-center gap-2">
                    <label for="info_banner">정보 배너</label>

                    <input type="file"
                           id="info_banner"
                           name="info_banner"
                           accept="image/jpeg,image/png,image/gif,image/webp"
                           class="hidden"
                           data-preview="info_banner_preview">

                    <label for="info_banner" class="btn btn_04 cursor-pointer">
                        업로드
                    </label>
                </div>

                <div id="info_banner_preview"
                     class="mt-2 flex h-48 w-full max-w-120 items-center justify-center rounded border border-dashed border-gray-300 bg-gray-50 overflow-hidden">
                    <?php if ($info_banner) { ?>
                        <img src="<?php echo dotty_settings_h($upload_url . '/' . rawurlencode($info_banner)); ?>"
                             alt="정보 배너"
                             style="max-width:100%; max-height:100%; object-fit:contain;">
                    <?php } else { ?>
                        <span class="text-gray-400">등록된 이미지가 없습니다.</span>
                    <?php } ?>
                </div>

                <?php if ($info_banner) { ?>
                    <label class="mt-2 inline-flex items-center gap-2">
                        <input type="checkbox" name="info_banner_del" value="1">
                        기존 정보 배너 삭제
                    </label>
                <?php } ?>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 mt-4">
            <button type="submit" class="btn btn_04">설정 완료</button>
        </div>
    </section>
</form>

<script>
(function () {
    function setPreview(input) {
        var previewId = input.getAttribute('data-preview');

        if (!previewId || !input.files || !input.files[0]) {
            return;
        }

        var file = input.files[0];

        if (!file.type.match(/^image\//)) {
            alert('이미지 파일만 업로드할 수 있습니다.');
            input.value = '';
            return;
        }

        if (file.size > 10 * 1024 * 1024) {
            alert('이미지 파일은 10MB 이하만 업로드할 수 있습니다.');
            input.value = '';
            return;
        }

        var reader = new FileReader();

        reader.onload = function (e) {
            var preview = document.getElementById(previewId);

            if (!preview) {
                return;
            }

            preview.innerHTML = '';

            var img = document.createElement('img');
            img.src = e.target.result;
            img.alt = '미리보기';
            img.style.maxWidth = '100%';
            img.style.maxHeight = '100%';
            img.style.objectFit = 'contain';

            preview.appendChild(img);
        };

        reader.readAsDataURL(file);
    }

    var fileInputs = document.querySelectorAll('input[type="file"][data-preview]');

    for (var i = 0; i < fileInputs.length; i++) {
        fileInputs[i].addEventListener('change', function () {
            setPreview(this);
        });
    }
})();

function fdottysettings_submit(f)
{
    <?php echo get_editor_js('dotty_info'); ?>

    var contribution = parseFloat(f.contribution_rate.value || 0);
    var discount = parseFloat(f.discount_rate.value || 0);

    if (isNaN(contribution) || contribution < 0 || contribution > 100) {
        alert('기여금은 0~100 사이로 입력해 주세요.');
        f.contribution_rate.focus();
        return false;
    }

    if (isNaN(discount) || discount < 0 || discount > 100) {
        alert('할인율은 0~100 사이로 입력해 주세요.');
        f.discount_rate.focus();
        return false;
    }

    if (!f.dotty_title.value.trim()) {
        alert('도티 제목을 입력해 주세요.');
        f.dotty_title.focus();
        return false;
    }

    return true;
}
</script>

<?php
require_once '../admin.tail.php';

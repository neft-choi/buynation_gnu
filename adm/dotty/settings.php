<?php
$sub_menu = '710800';
require_once './_common.php';
require_once(G5_EDITOR_LIB);

$g5['title'] = '도티 설정';
require_once '../admin.head.php';
?>

<section class="text-sm">
    <h2>도티 설정</h2>
    <div class="text-lg">안녕하세요 김우빈(임시) 도티님</div>

    <div class="mt-8 space-y-4">
        <div class="flex items-center gap-2">
            <label for="dot_auto_join">도트 자동가입 여부(임시)</label>
            <input type="checkbox" id="dot_auto_join" name="dot_auto_join" value="1" checked>
        </div>

        <div class="flex items-center gap-2">
            <p>도티 사업자등록증(매출 증가시 필)(임시)</p>
            <label for="business_license_image" class="btn btn_04 cursor-pointer">
                업로드
            </label>
            <input type="file" id="business_license_image" name="business_license_image" accept="image/*"
                class="hidden">
        </div>

        <div id="business_license_preview"
            class="flex w-full h-48 max-w-120 items-center justify-center rounded border border-dashed border-gray-300 bg-gray-50">
            <span class="text-gray-400">등록된 이미지가 없습니다.</span>
        </div>

        <div class="flex items-center gap-2">
            <p class="mr-12 whitespace-nowrap">정책 설정(임시)</p>
            <div class="inline-flex items-center gap-2">
                <label for="contribution_rate" class="whitespace-nowrap">기여금</label>
                <input type="number" id="contribution_rate" name="contribution_rate" min="0" max="100" step="0.1"
                    class="frm_input w-24">
                <span>%</span>
            </div>
            <span> : </span>
            <div class="inline-flex items-center gap-2">
                <label for="discount_rate" class="whitespace-nowrap">할인율</label>
                <input type="number" id="discount_rate" name="discount_rate" min="0" max="100" step="0.1"
                    class="frm_input w-24">
                <span>%</span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <label for="group_type" class="pr-12">그룹(임시)</label>
            <select id="group_type" name="group_type" class="frm_input w-50">
                <option value="">동호회</option>
            </select>
        </div>

        <div class="flex items-center gap-2">
            <label for="dotty_title" class="pr-12">도티 제목(임시)</label>
            <input type="text" id="dotty_title" name="dotty_title" class="frm_input w-100">
        </div>

        <div>
            <div class="flex items-center gap-2">
                <label>도티 이미지(임시)</label>
                <input type="file" id="top_image" name="top_image" accept="image/*" class="hidden">
                <label for="top_image" class="btn btn_04 cursor-pointer">
                    업로드
                </label>
            </div>

            <div
                class="mt-2 flex h-48 w-full max-w-120 items-center justify-center rounded border border-dashed border-gray-300 bg-gray-50">
                <span class="text-gray-400">등록된 이미지가 없습니다.</span>
            </div>
        </div>

        <div>
            <label for="dotty_info">도티 정보(임시)</label>
            <?php echo editor_html('dotty_info', ''); ?>
        </div>

        <div>
            <div class="flex items-center gap-2">
                <label for="info_banner">정보 배너(임시)</label>

                <input type="file" id="info_banner" name="info_banner" accept="image/*" class="hidden">

                <label for="info_banner" class="btn btn_04 cursor-pointer">
                    업로드
                </label>
            </div>

            <div
                class="mt-2 flex h-48 w-full max-w-120 items-center justify-center rounded border border-dashed border-gray-300 bg-gray-50">
                <span class="text-gray-400">등록된 이미지가 없습니다.</span>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-2 mt-4">
        <button type="button" class="btn btn_04">설정 완료</button>
    </div>
</section>

<?php
require_once '../admin.tail.php';

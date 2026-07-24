<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

function get_file_upload_field($input_name, $input_id)
{
    // HTML 속성 값에 사용하기 전에 출력해도 안전한 값으로 바꿔준다
    $input_name = htmlspecialchars($input_name, ENT_QUOTES, 'UTF-8');
    $input_id = htmlspecialchars($input_id, ENT_QUOTES, 'UTF-8');

    ob_start();
    ?>

    <div data-file-upload>
        <input type="file" name="<?php echo $input_name; ?>" id="<?php echo $input_id; ?>" class="sr-only" data-file-input>

        <label for="<?php echo $input_id; ?>"
            class="flex min-h-36 cursor-pointer items-center justify-center rounded-lg border border-dashed border-[var(--color-semantic-border-normal-strong)] bg-white p-4">
            <span class="text-sm font-medium text-gray-900">파일을 선택하세요</span>
        </label>

        <div hidden class="mt-2 flex items-center justify-between gap-3 rounded-lg bg-gray-50 px-4 py-3" data-file-selected>
            <div class="min-w-0">
                <p class="truncate text-sm font-medium text-gray-900" data-file-name></p>
                <p class="text-xs text-gray-500" data-file-size></p>
            </div>
            <button type="button" class="shrink-0 text-sm text-gray-600" data-file-remove>선택 취소</button>
        </div>
    </div>

    <?php
    return ob_get_clean();
}

// 스크립트는 파일 업로드 UI 전체에서 한 번만 필요하기 때문에 분리
function get_file_upload_script()
{
    ob_start();
    ?>
    <script>
        // 파일 크기를 사람이 읽기 쉬운 문자열로 바꾸기
        // toFixed(n)는 소수점 n자리까지 보이게 하는 함수
        function formatFileSize(bytes) {
            if (bytes < 1024) {
                return bytes + ' B';
            }

            if (bytes < 1024 * 1024) {
                return (bytes / 1024).toFixed(1) + ' KB';
            }

            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }

        // 페이지에 모든 업로드 영역을 찾은 후 파일이 선택된 것을 감지해서 가져온다
        $('[data-file-upload]').each(function () {
            const $fileUpload = $(this);
            const $input = $fileUpload.find('[data-file-input]');
            const $fileName = $fileUpload.find('[data-file-name]');
            const $fileSize = $fileUpload.find('[data-file-size]');
            const $selected = $fileUpload.find('[data-file-selected]');
            const $remove = $fileUpload.find('[data-file-remove]');

            $input.on('change', function () {
                const file = this.files[0];

                $fileName.text(file.name);
                $fileSize.text(formatFileSize(file.size));
                $selected.removeAttr('hidden');
            });

            $remove.on('click', () => {
                $input.val('');
                $fileName.text('');
                $fileSize.text('');
                $selected.attr('hidden', '');
            });
        });
    </script>
    <?php
    return ob_get_clean();
}
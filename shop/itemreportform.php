<?php
include_once('./_common.php');

$g5['title'] = '상품 신고';

$it_id = isset($_GET['it_id']) && is_string($_GET['it_id'])
    ? safe_replace_regex(trim($_GET['it_id']), 'it_id')
    : '';

// 상품 ID 없이 접근한 경우.
if ($it_id === '') {
    alert('신고할 상품을 확인해 주세요.', G5_SHOP_URL);
}

// 기존 영카트 함수로 상품 조회.
$report_product = get_shop_item($it_id, true);

// 존재하지 않는 상품인 경우.
if (empty($report_product['it_id'])) {
    alert('상품 정보가 존재하지 않습니다.', G5_SHOP_URL);
}

// 상품에 연결된 판매자 표시명 조회.
$report_seller = array();

if (!empty($report_product['it_seller'])) {
    $report_seller_id_sql = sql_real_escape_string(
        trim((string) $report_product['it_seller'])
    );

    $report_seller = sql_fetch("
        SELECT kr_id
        FROM donuts_brand
        WHERE brand_id = '{$report_seller_id_sql}'
        LIMIT 1
    ");
}

// 신고 화면에서 사용할 상품 데이터.
$report_item = array(
    'it_id'     => $report_product['it_id'],
    'name'      => $report_product['it_name'],
    'seller'    => !empty($report_seller['kr_id']) ? $report_seller['kr_id'] : '판매자 정보 없음',
    'price'     => (int) $report_product['it_price'],
    'image_url' => get_it_imageurl($it_id),
);

$report_escape = static function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};

$return_url = $it_id !== ''
    ? G5_SHOP_URL . '/item.php?it_id=' . rawurlencode($it_id)
    : G5_SHOP_URL . '/';

include_once(G5_PATH . '/head.sub.php');
?>

<header class="flex sm:hidden items-center justify-between p-4 border-b border-gray-300">
    <div class="h-6 w-6 shrink-0" aria-hidden="true"></div>
    <h1 class="text-lg font-semibold leading-none text-zinc-900">상품 신고</h1>
    <button type="button" onclick="window.close();" class="inline-flex h-6 w-6 shrink-0 items-center justify-center text-zinc-700" aria-label="확대창 닫기">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6" aria-hidden="true">
            <path d="M18 6 6 18" />
            <path d="m6 6 12 12" />
        </svg>
    </button>
</header>

<main id="item-report" class="mx-auto w-full max-w-180 bg-white px-5 py-6 sm:px-8">
    <h1 class="text-2xl font-bold text-gray-900">상품 신고</h1>

    <!-- 신고 대상 상품 -->
    <section aria-label="신고 대상 상품" class="flex items-center gap-4 py-8">
        <div class="flex h-22 w-22 shrink-0 items-center justify-center overflow-hidden border border-gray-300 rounded-lg bg-gray-100">
            <?php if ($report_item['image_url'] !== '') { ?>
                <img src="<?php echo $report_escape($report_item['image_url']); ?>" alt="<?php echo $report_escape($report_item['name']); ?>" class="h-full w-full object-cover">
            <?php } else { ?>
                <span class="text-xs text-gray-400">상품 이미지</span>
            <?php } ?>
        </div>

        <div class="min-w-0">
            <p class="text-xs text-gray-500">
                상품 ID: <?php echo $report_escape($report_item['it_id']); ?>
            </p>

            <p class="mt-1 wrap-break-word text-base font-bold text-gray-900">
                <?php echo $report_escape($report_item['name']); ?>
            </p>

            <p class="mt-1 text-base font-bold text-gray-900">
                <?php echo display_price($report_item['price'], $report_product['it_tel_inq']); ?>
            </p>

            <p class="mt-1 text-xs text-gray-500">
                판매자: <?php echo $report_escape($report_item['seller']); ?>
            </p>
        </div>
    </section>

    <!-- 실제 접수 주소는 추후 연결 -->
    <form id="item-report-form" method="post" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="it_id" value="<?php echo $report_escape($it_id); ?>">

        <!-- 신고 사유 -->
        <div class="flex flex-col gap-3 border-b border-gray-200 pb-4 sm:flex-row sm:gap-6">
            <label for="report-reason" class="shrink-0 text-sm font-bold sm:w-32 sm:pt-3">
                신고 사유 <span class="text-yellow-600">*</span>
            </label>

            <div class="min-w-0 flex-1">
                <select id="report-reason" name="reason_code" required aria-invalid="false" aria-describedby="report-reason-guide report-reason-error" class="h-12 w-full rounded-md border border-gray-300 bg-white px-4 text-sm aria-invalid:border-red-500">
                    <option value="">사유를 선택해 주세요</option>
                    <option value="false_info">허위·과장 정보</option>
                    <option value="prohibited">금지·부적절 제품</option>
                    <option value="counterfeit">위조 의심</option>
                    <option value="rights_infringement">권리 침해</option>
                    <option value="other">기타</option>
                </select>

                <p id="report-reason-guide" class="mt-2 text-xs text-gray-500" hidden></p>

                <p id="report-reason-error" class="mt-2 text-xs text-red-600" role="alert" hidden></p>
            </div>
        </div>

        <!-- 상세 내용 -->
        <div class="flex flex-col gap-3 border-b border-gray-200 py-4 sm:flex-row sm:gap-6">
            <label for="report-content" class="shrink-0 text-sm font-bold sm:w-32 sm:pt-3">
                상세 내용 <span class="text-yellow-600">*</span>
            </label>

            <div class="min-w-0 flex-1">
                <textarea id="report-content" name="report_content" required maxlength="1000" rows="6" placeholder="신고 내용을 입력해 주세요" aria-invalid="false" aria-describedby="report-content-guide report-content-error" class="block w-full resize-y rounded-md border border-gray-300 p-3 text-sm aria-invalid:border-red-500 focus-visible:outline-0 focus-visible:ring-2 focus-visible:ring-yellow-400"></textarea>

                <p id="report-content-error" class="mt-2 text-xs text-red-600" role="alert" hidden></p>

                <p id="report-content-guide" class="mt-3 text-xs text-gray-500">
                    최대 1,000자
                </p>
            </div>
        </div>

        <!-- 사진 첨부 -->
        <div class="flex flex-col gap-3 border-b border-gray-200 py-4 sm:flex-row sm:gap-6">
            <span id="report-images-label" class="shrink-0 text-sm font-bold sm:w-32 sm:pt-3">
                사진 첨부
            </span>

            <div class="min-w-0 flex-1">
                <label class="flex min-h-28 cursor-pointer flex-col items-center justify-center gap-2 rounded-md border border-dashed border-gray-300 bg-gray-50 text-gray-400 focus-within:ring-2 focus-within:ring-yellow-400">
                    <input type="file" id="report-images" name="report_images[]" accept="image/jpeg,image/png" multiple aria-labelledby="report-images-label" aria-describedby="report-images-guide report-images-error" aria-invalid="false" class="sr-only">

                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-images preview-icon w-6 h-6">
                        <path d="m22 11-1.296-1.296a2.4 2.4 0 0 0-3.408 0L11 16" />
                        <path d="M4 8a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2" />
                        <circle cx="13" cy="7" r="1" fill="currentColor" />
                        <rect x="8" y="2" width="14" height="14" rx="2" />
                    </svg>
                    <span class="text-sm">사진 추가</span>
                </label>

                <p id="report-images-guide" class="mt-2 text-xs text-gray-500">
                    JPG, PNG, WEBP 확장자 가능
                </p>

                <p id="report-images-error" class="mt-2 whitespace-pre-line text-xs text-red-600" role="alert" hidden></p>

                <p id="report-images-status" class="mt-2 text-xs text-gray-500" aria-live="polite"></p>

                <ul id="report-images-preview" class="mt-3 grid grid-cols-3 gap-3" aria-label="첨부 사진 목록"></ul>
            </div>
        </div>

        <!-- 접수 결과 안내 -->
        <p id="report-status" class="mt-4 text-sm text-gray-600" role="status"></p>

        <!-- 주문 문의·반품 안내 -->
        <p class="mt-4 text-center text-sm text-gray-500">
            주문 문의나 반품을 원하시나요?
            <a href="<?php echo G5_SHOP_URL; ?>/orderinquiry.php" id="report-order-history" class="font-semibold text-gray-900 underline underline-offset-2" aria-label="주문내역으로 이동">주문내역</a>에서 신청해주세요.
        </p>

        <!-- 하단 버튼 -->
        <div class="flex justify-end gap-3 pt-6">
            <a href="<?php echo $report_escape($return_url); ?>" id="report-cancel" class="flex h-12 flex-1 items-center justify-center rounded-md border border-gray-300 text-sm font-bold sm:flex-none sm:w-32">
                취소
            </a>

            <button type="submit" id="report-submit" class="h-12 flex-1 rounded-md bg-yellow-400 px-6 text-sm font-bold text-gray-900 disabled:cursor-not-allowed disabled:opacity-50 sm:flex-none sm:w-32">
                신고 접수
            </button>
        </div>
    </form>
</main>

<script>
    $(function() {
        const $form = $('#item-report-form');
        const $content = $('#report-content');
        const $status = $('#report-status');
        const $submit = $('#report-submit');

        let isSubmitting = false;

        // 신고 창을 열었던 원래 창에서 주문내역으로 이동.
        $('#report-order-history').on('click', function(event) {
            if (window.opener && !window.opener.closed) {
                event.preventDefault();

                window.opener.location.href = this.href;
                window.opener.focus();
                window.close();
            }
        });

        // 새 창으로 열렸다면 취소 시 창 닫기.
        $('#report-cancel').on('click', function(event) {
            if (window.opener) {
                event.preventDefault();
                window.close();
            }
        });

        // 처리 중 입력창·사진 선택·삭제·접수 버튼 잠금.
        function setReportSubmitting(value) {
            isSubmitting = value;

            $form.attr('aria-busy', value ? 'true' : 'false');
            $form.find(':input').prop('disabled', value);

            $submit.text(value ? '접수 중…' : '신고 접수');
        }

        // 실제 서버 연결 전 사용하는 테스트 요청.
        function requestReport(formData) {
            const deferred = $.Deferred();

            // false: 완료 테스트 / true: 실패 테스트
            const shouldFail = false;

            console.log('상품 신고 전송 데이터:', Array.from(formData.entries()));

            window.setTimeout(function() {
                if (shouldFail) {
                    deferred.reject();
                } else {
                    deferred.resolve();
                }
            }, 1500);

            return deferred.promise();
        }

        const reasonMessages = {
            false_info: '허위 또는 과장으로 의심되는 상품 정보와 그 이유를 작성해 주세요.',
            prohibited: '판매가 금지되었거나 부적절하다고 판단한 제품의 특징과 이유를 작성해 주세요.',
            counterfeit: '위조가 의심되는 부분과 정품 여부를 확인할 수 있는 정보를 작성해 주세요.',
            rights_infringement: '상표·저작물·이미지 등 권리 침해가 의심되는 대상과 내용을 작성해 주세요.',
            other: '위 항목에 해당하지 않는 신고 사유를 구체적으로 작성해 주세요.'
        };

        // 입력값이 바뀌면 이전 안내 초기화.
        $form.on('input change', function() {
            $status.text('');
        });

        // 사유·본문의 오류 문구와 테두리 표시.
        function setReportFieldError($field, message) {
            const hasError = message !== '';
            const errorId = $field.attr('id') + '-error';

            $field.attr('aria-invalid', hasError ? 'true' : 'false');

            $('#' + errorId)
                .text(message)
                .prop('hidden', !hasError);
        }

        // 본문 검사 결과를 오류 문구로 반환.
        function getReportContentError() {
            const value = $content.val();
            const maxLength = $content[0].maxLength;

            if (!$.trim(value)) {
                return '신고 상세 내용을 입력해 주세요.';
            }

            if (maxLength > 0 && value.length > maxLength) {
                return '상세 내용은 최대 ' + maxLength + '자까지 입력해 주세요.';
            }

            return '';
        }

        // 신고 사유별 안내 및 기존 오류 재검사.
        $('#report-reason').on('change', function() {
            const $field = $(this);
            const message = reasonMessages[this.value] || '';

            $('#report-reason-guide')
                .text(message)
                .prop('hidden', !message);

            if ($field.attr('aria-invalid') === 'true') {
                setReportFieldError(
                    $field,
                    this.value ? '' : '신고 사유를 선택해 주세요.'
                );
            }
        });

        // 본문 수정 시 기존 오류 재검사.
        $content.on('input', function() {
            if ($content.attr('aria-invalid') === 'true') {
                setReportFieldError($content, getReportContentError());
            }
        });

        const reportImages = [];
        const $imagesPreview = $('#report-images-preview');
        const $imagesStatus = $('#report-images-status');
        const $imagesError = $('#report-images-error');

        // 사진 첨부 제한값.
        const imageRules = {
            maxCount: 3,
            maxSizeMB: 10,
            types: ['image/jpeg', 'image/png']
        };

        // 제한값에 맞춰 안내 문구 표시.
        $('#report-images-guide').text(
            'JPG, PNG · 최대 ' + imageRules.maxCount +
            '장 · 사진당 ' + imageRules.maxSizeMB +
            'MB 이하'
        );

        // 오류 표시 또는 초기화.
        function setReportImageError(message) {
            $imagesError
                .text(message)
                .prop('hidden', !message);

            $('#report-images').attr(
                'aria-invalid',
                message ? 'true' : 'false'
            );
        }

        // 현재 사진 목록으로 미리보기 표시.
        function renderReportImages() {
            $imagesPreview.empty();

            $.each(reportImages, function(index, image) {
                const $item = $('<li class="min-w-0"></li>');

                $('<img class="h-24 w-full rounded-md border border-gray-200 object-cover">')
                    .attr({
                        src: image.url,
                        alt: image.file.name
                    })
                    .appendTo($item);

                $('<p class="mt-1 truncate text-xs text-gray-500"></p>')
                    .text(image.file.name)
                    .attr('title', image.file.name)
                    .appendTo($item);

                $('<button type="button" class="report-image-remove mt-1 min-h-11 w-full rounded-md border border-gray-300 text-xs text-gray-600">삭제</button>')
                    .attr({
                        'data-index': index,
                        'aria-label': image.file.name + ' 삭제'
                    })
                    .appendTo($item);

                $imagesPreview.append($item);
            });

            $imagesStatus.text(
                reportImages.length ?
                '사진 ' + reportImages.length + '개 선택됨' :
                ''
            );
        }

        // 새로 선택한 사진을 검사한 뒤 기존 목록에 추가.
        $('#report-images').on('change', function() {
            const files = Array.from(this.files);
            const errors = [];
            const maxSize = imageRules.maxSizeMB * 1024 * 1024;

            // 파일 목록을 확보한 뒤 입력창 초기화.
            $(this).val('');

            if (!files.length) {
                return;
            }

            // 기존 사진과 새로 선택한 사진의 합계 검사.
            if (reportImages.length + files.length > imageRules.maxCount) {
                errors.push(
                    '사진은 기존 첨부를 포함해 최대 ' +
                    imageRules.maxCount + '장까지 가능합니다.'
                );
            }

            // 파일별 형식과 용량 검사.
            $.each(files, function(index, file) {
                if (!imageRules.types.includes(file.type)) {
                    errors.push(
                        file.name + ': JPG, PNG, WEBP 형식만 가능합니다.'
                    );
                }

                if (file.size > maxSize) {
                    errors.push(
                        file.name + ': 사진당 ' +
                        imageRules.maxSizeMB + 'MB 이하여야 합니다.'
                    );
                }
            });

            // 오류가 있으면 기존 목록을 유지하고 추가 중단.
            if (errors.length) {
                setReportImageError(
                    '이번에 선택한 사진은 추가되지 않았습니다.\n' +
                    errors.join('\n')
                );

                return;
            }

            // 검사를 통과한 경우에만 사진 추가.
            $.each(files, function(index, file) {
                reportImages.push({
                    file: file,
                    url: URL.createObjectURL(file)
                });
            });

            setReportImageError('');
            renderReportImages();
        });

        // 선택한 사진만 삭제.
        $imagesPreview.on('click', '.report-image-remove', function() {
            const index = Number($(this).attr('data-index'));

            URL.revokeObjectURL(reportImages[index].url);
            reportImages.splice(index, 1);

            setReportImageError('');
            renderReportImages();
            $status.text('');
        });

        // 입력 검사 및 전송 데이터 준비.
        $form.on('submit', function(event) {
            event.preventDefault();

            // 처리 중에는 추가 제출 중단.
            if (isSubmitting) {
                return;
            }

            const $reason = $('#report-reason');

            const reasonError = $reason.val() ?
                '' :
                '신고 사유를 선택해 주세요.';

            const contentError = getReportContentError();

            // 두 항목의 오류를 함께 표시.
            setReportFieldError($reason, reasonError);
            setReportFieldError($content, contentError);

            // 첫 번째 오류 항목으로 이동하고 제출 중단.
            if (reasonError || contentError) {
                const $firstError = reasonError ? $reason : $content;

                $firstError.trigger('focus');

                return;
            }

            const content = $.trim($content.val());

            const formData = new FormData(this);
            formData.set('report_content', content);

            // 파일 입력창에서 생성된 항목 제거.
            formData.delete('report_images[]');

            // 현재 목록에 남아 있는 사진만 전송 데이터에 추가.
            $.each(reportImages, function(index, image) {
                formData.append('report_images[]', image.file, image.file.name);
            });

            // 전송 데이터를 모두 만든 다음 입력창 잠금.
            setReportSubmitting(true);

            $status
                .removeClass('text-red-600')
                .addClass('text-gray-600')
                .text('전송 동작을 테스트하고 있습니다. 실제 접수는 진행되지 않습니다.');

            requestReport(formData)
                .done(function() {
                    $status.text(
                        '전송 동작 테스트가 완료되었습니다. 실제 접수는 아직 연결되지 않았습니다.'
                    );
                })
                .fail(function() {
                    $status
                        .removeClass('text-gray-600')
                        .addClass('text-red-600')
                        .text(
                            '전송 동작 테스트에 실패했습니다. 작성한 내용은 유지됩니다. 다시 시도해 주세요.'
                        );
                })
                .always(function() {
                    setReportSubmitting(false);
                });
        });
    });
</script>

<?php
include_once(G5_PATH . '/tail.sub.php');
?>
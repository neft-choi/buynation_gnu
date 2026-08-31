<?php
$sub_menu = '400770';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '입점 사업자 서류';
include_once(G5_ADMIN_PATH . '/admin.head.php');
?>

<style>
    .timeline {
        margin: 12px 0 0;
        border-left: 2px solid #e4e4e4;
        padding-left: 14px;
    }

    .timeline-item {
        position: relative;
        padding: 0 0 12px;
    }

    .timeline-item:before {
        content: "";
        position: absolute;
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: yellow;
        left: -18px;
        top: 3px;
    }
</style>

<section class="space-y-1">
    <h2 id="seller-documents-heading" class="text-lg font-bold text-gray-900">
        입점 사업자 서류
    </h2>
    <p class="text-xs text-gray-500">
        브랜드 판매자 인증에 필요한 서류와 플랫폼 심사 상태를 관리합니다.
    </p>

    <div class="text-2xs text-blue-800 bg-blue-50 rounded-lg p-3 mt-4">
        <span class="font-bold">권한 구분: </span>
        <span>브랜드 담당자는 서류 등록·보완·재제출만 할 수 있습니다. 최종 승인·보완요청·반려 처리는 플랫폼 관리자에서 진행되고, 결과가 이 화면에 반영됩니다.</span>
    </div>


    <div class="grid grid-cols-1 pc:grid-cols-2 items-stretch gap-4 mt-4">
        <div class="border border-gray-300 rounded-lg bg-white p-4">
            <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3">
                <div class="space-y-1">
                    <p class="font-bold">최초 입점 심사 · 승인</p>
                    <span class="text-2xs text-gray-400">승인일 2026-07-21·판매 권한 유지 중</span>
                </div>
                <button type="button" class="text-2xs text-green-600 bg-green-50 rounded-full font-semibold px-2 py-1">승인</button>
            </div>

            <div class="grid grid-cols-4  items-stretch gap-2 mt-3">
                <div class="border border-green-400 rounded-lg bg-green-50 space-y-1 p-3">
                    <div class="inline-flex items-center justify-center w-5 aspect-square rounded-full text-white text-2xs font-semibold bg-green-700">1</div>
                    <p class="font-semibold">기본정보 입력</p>
                    <p class="text-2xs text-gray-400">브랜드·사업자·담당자 정보</p>
                </div>

                <div class="border border-green-400 rounded-lg bg-green-50 space-y-1 p-3">
                    <div class="inline-flex items-center justify-center w-5 aspect-square rounded-full text-white text-2xs font-semibold bg-green-700">2</div>
                    <p class="font-semibold">서류 제출</p>
                    <p class="text-2xs text-gray-400">필수·조건부 서류 등록</p>
                </div>

                <div class="border border-green-400 rounded-lg bg-green-50 space-y-1 p-3">
                    <div class="inline-flex items-center justify-center w-5 aspect-square rounded-full text-white text-2xs font-semibold bg-green-700">3</div>
                    <p class="font-semibold">플랫폼 심사</p>
                    <p class="text-2xs text-gray-400">일치 여부 및 보완 확인</p>
                </div>

                <div class="border border-green-400 rounded-lg bg-green-50 space-y-1 p-3">
                    <div class="inline-flex items-center justify-center w-5 aspect-square rounded-full text-white text-2xs font-semibold bg-green-700">4</div>
                    <p class="font-semibold">입점 승인</p>
                    <p class="text-2xs text-gray-400">상품 검수·판매 기능 활성화</p>
                </div>
            </div>
        </div>

        <div class="border border-gray-300 rounded-lg bg-white p-4">
            <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3">
                <div class="space-y-1">
                    <p class="font-bold">서류 변경 심사 · 보완요청</p>
                    <span class="text-2xs text-gray-400">접수번호 BR-VRF-2026-0718 · 승인된 기존 판매권한과 별도 표시</span>
                </div>
                <button type="button" class="text-2xs text-red-600 bg-red-50 rounded-full font-semibold px-2 py-1">보완요청</button>
            </div>

            <dl class="text-2xs [&>div]:flex [&>div]:items-center [&>div]:justify-between [&>div]:border-b [&>div]:border-gray-100 [&>div]:py-2 [&>div>dd]:font-semibold">
                <div>
                    <dt>등록 서류</dt>
                    <dd>4건</dd>
                </div>
                <div>
                    <dt>승인</dt>
                    <dd>3건</dd>
                </div>
                <div>
                    <dt>보완·제출대기</dt>
                    <dd>1건</dd>
                </div>
                <div>
                    <dt>플랫폼 심사중</dt>
                    <dd>0건</dd>
                </div>
            </dl>
        </div>
    </div>
</section>

<section class="mt-4">
    <h3 class="text-sm font-bold">제출 서류</h3>
    <p class="text-2xs text-gray-400">아래 항목은 DONUTS 브랜드 입점 프로토타입의 제안 구성입니다.</p>

    <div class="grid grid-cols-1 pc:grid-cols-2 items-stretch gap-4 mt-3">

        <!-- 사업자 등록증 -->
        <div class="border border-gray-300 rounded-lg bg-white p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-bold">사업자등록증 · 필수</p>
                    <span class="text-2xs text-gray-400">공통 제출 · 사업자명·대표자·사업장 소재지 확인용</span>
                </div>
                <button type="button" class="text-2xs text-green-600 bg-green-50 rounded-full font-semibold px-2 py-1">승인</button>
            </div>

            <div class="text-2xs text-gray-400 bg-gray-50 rounded-lg mt-3 p-3">
                <p id="seller-document-business-registration-file">그린테이블_사업자등록증.pdf · 823KB</p>
                <p>제출 2026-07-18 10:24</p>
            </div>

            <div class="flex items-center gap-2 mt-3">
                <input
                    type="file"
                    id="seller-document-business-registration-input"
                    class="seller-document-file-input hidden"
                    data-file-display="#seller-document-business-registration-file">
                <button
                    type="button"
                    class="seller-document-file-select border border-gray-400 rounded-lg text-2xs font-bold bg-white px-2 py-1"
                    data-file-input="#seller-document-business-registration-input">파일 교체</button>
                <button
                    type="button"
                    class="seller-document-modal-open border border-gray-400 rounded-lg text-2xs font-bold bg-white px-2 py-1"
                    data-document-title="사업자등록증"
                    data-document-description="공통 제출 · 필수"
                    data-document-status="승인"
                    data-document-status-tone="approved"
                    data-document-file="그린테이블_사업자등록증.pdf"
                    data-document-submitted-at="2026-07-18 10:24"
                    data-document-processed-at="2026-07-20 14:10"
                    data-document-size="823KB">상세·이력</button>
            </div>
        </div>

        <!-- 통신판매업 신고증 -->
        <div class="border border-gray-300 rounded-lg bg-white p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-bold">통신판매업 신고증 · 필수</p>
                    <span class="text-2xs text-gray-400">온라인 판매 확인 · 신고번호와 사업자 정보 확인용</span>
                </div>
                <button type="button" class="text-2xs text-green-600 bg-green-50 rounded-full font-semibold px-2 py-1">승인</button>
            </div>

            <div class="text-2xs text-gray-400 bg-gray-50 rounded-lg mt-3 p-3">
                <p id="seller-document-mail-order-registration-file">통신판매업신고증.pdf · 505KB</p>
                <p>제출 2026-07-18 10:26</p>
            </div>

            <div class="flex items-center gap-2 mt-3">
                <input
                    type="file"
                    id="seller-document-mail-order-registration-input"
                    class="seller-document-file-input hidden"
                    data-file-display="#seller-document-mail-order-registration-file">
                <button
                    type="button"
                    class="seller-document-file-select border border-gray-400 rounded-lg text-2xs font-bold bg-white px-2 py-1"
                    data-file-input="#seller-document-mail-order-registration-input">파일 교체</button>
                <button
                    type="button"
                    class="seller-document-modal-open border border-gray-400 rounded-lg text-2xs font-bold bg-white px-2 py-1"
                    data-document-title="통신판매업 신고증"
                    data-document-description="온라인 판매 확인 · 필수"
                    data-document-status="승인"
                    data-document-status-tone="approved"
                    data-document-file="통신판매업신고증.pdf"
                    data-document-submitted-at="2026-07-18 10:26"
                    data-document-processed-at="2026-07-20 14:12"
                    data-document-size="505KB">상세·이력</button>
            </div>
        </div>

        <!-- 정산계좌 확인서류 -->
        <div class="border border-gray-300 rounded-lg bg-white p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-bold">정산계좌 확인서류 · 필수</p>
                    <span class="text-2xs text-gray-400">정산계좌 등록 · 예금주와 사업자 정보 일치 여부 확인용</span>
                </div>
                <button type="button" class="text-2xs text-green-600 bg-green-50 rounded-full font-semibold px-2 py-1">승인</button>
            </div>

            <div class="text-2xs text-gray-400 bg-gray-50 rounded-lg mt-3 p-3">
                <p id="seller-document-settlement-account-file">그린테이블_정산계좌.pdf · 383KB</p>
                <p>제출 2026-07-18 10:28</p>
            </div>

            <div class="flex items-center gap-2 mt-3">
                <input
                    type="file"
                    id="seller-document-settlement-account-input"
                    class="seller-document-file-input hidden"
                    data-file-display="#seller-document-settlement-account-file">
                <button
                    type="button"
                    class="seller-document-file-select border border-gray-400 rounded-lg text-2xs font-bold bg-white px-2 py-1"
                    data-file-input="#seller-document-settlement-account-input">파일 교체</button>
                <button
                    type="button"
                    class="seller-document-modal-open border border-gray-400 rounded-lg text-2xs font-bold bg-white px-2 py-1"
                    data-document-title="정산계좌 확인서류"
                    data-document-description="정산계좌 등록 · 필수"
                    data-document-status="승인"
                    data-document-status-tone="approved"
                    data-document-file="그린테이블_정산계좌.pdf"
                    data-document-submitted-at="2026-07-18 10:28"
                    data-document-processed-at="2026-07-20 14:15"
                    data-document-size="383KB">상세·이력</button>
            </div>
        </div>

        <!-- 법인 등기사항증명서 -->
        <div class="border border-amber-400 rounded-lg bg-amber-50/50 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-bold">법인 등기사항증명서 · 필수</p>
                    <span class="text-2xs text-gray-400">법인사업자 · 법인 기본정보와 대표자 확인용</span>
                </div>
                <button type="button" class="text-2xs text-red-600 bg-red-50 rounded-full font-semibold px-2 py-1">보완요청</button>
            </div>

            <div class="text-2xs text-gray-400 bg-gray-50 rounded-lg mt-3 p-3">
                <p id="seller-document-corporate-registration-file">법인등기사항증명서_기존본.pdf · 1,212KB</p>
                <p>제출 2026-08-08 11:05</p>
            </div>

            <div class="text-2xs text-red-400 bg-red-50 rounded-lg mt-3 p-3">
                <p class="font-bold">보완 사유</p>
                <p>플랫폼 보완 예시: 최근 발급본으로 다시 제출해 주세요.</p>
            </div>

            <div class="flex items-center gap-2 mt-3">
                <input
                    type="file"
                    id="seller-document-corporate-registration-input"
                    class="seller-document-file-input hidden"
                    data-file-display="#seller-document-corporate-registration-file">
                <button
                    type="button"
                    class="seller-document-file-select border border-gray-400 rounded-lg text-2xs font-bold bg-white px-2 py-1"
                    data-file-input="#seller-document-corporate-registration-input">보완파일 선택</button>
                <button
                    type="button"
                    class="seller-document-modal-open border border-gray-400 rounded-lg text-2xs font-bold bg-white px-2 py-1"
                    data-document-title="법인 등기사항증명서"
                    data-document-description="법인사업자 · 필수"
                    data-document-status="보완요청"
                    data-document-status-tone="requested"
                    data-document-file="법인등기사항증명서_기존본.pdf"
                    data-document-submitted-at="2026-08-08 11:05"
                    data-document-processed-at="2026-08-12 16:20"
                    data-document-size="1,212KB">상세·이력</button>
            </div>
        </div>
    </div>
</section>

<div class="border-l-4 border-amber-400 text-2xs bg-amber-50 mt-4 p-4">
    <span class="font-bold">프로토타입 범위: </span>
    <span>이 단일 HTML에서는 선택한 파일의 이름·크기·상태만 저장합니다. 실제 원본파일 업로드, 암호화 보관, 접근권한, 보존기간, 플랫폼 관리자 심사는 백엔드와 파일 저장소 연동이 필요합니다. 최종 제출 서류 목록도 DONUTS 입점 정책 확정 후 조정해야 합니다.</span>
</div>

<section class="mt-4">
    <h3 class="text-sm font-bold">심사 이력</h3>
    <p class="text-2xs text-gray-400">브랜드 제출과 플랫폼 처리 결과를 시간순으로 확인합니다.</p>

    <div class="border border-gray-300 rounded-lg bg-white p-4 mt-3">
        <div class="timeline">
            <div class="timeline-item">
                <p>보완요청 · 2026-08-12 16:20</p>
                <p>법인 등기사항증명서 보완 요청</p>
            </div>
            <div class="timeline-item">
                <p>심사중 · 2026-08-08 11:05</p>
                <p>사업자 서류 변경 심사 접수</p>
            </div>
            <div class="timeline-item">
                <p>승인 · 2026-07-21 09:30</p>
                <p>최초 입점 및 판매 권한 승인</p>
            </div>
            <div class="timeline-item">
                <p>심사중 · 2026-07-18 10:30</p>
                <p>최초 입점 서류 제출 완료</p>
            </div>
        </div>
    </div>
</section>

<div id="seller-document-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4" hidden>
    <div id="seller-document-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="seller-document-modal-container" class="relative z-10 w-full max-w-160 rounded-lg bg-white">
        <div id="seller-document-modal-header" class="flex items-center justify-between border-b border-gray-300 p-4">
            <h3 class="text-sm font-bold text-gray-900">사업자 서류 상세</h3>

            <button type="button" id="seller-document-modal-close" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100" aria-label="모달 닫기">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-5 w-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="seller-document-modal-body" class="space-y-4 p-4">
            <div id="seller-document-modal-summary" class="flex items-center justify-between rounded-lg bg-gray-50 p-3">
                <div class="space-y-1">
                    <p id="seller-document-modal-title" class="text-xs font-bold text-gray-900">사업자등록증</p>
                    <p id="seller-document-modal-description" class="text-2xs text-gray-400">공통 제출 · 필수</p>
                </div>

                <span id="seller-document-modal-status" class="rounded-full bg-green-50 px-2 py-1 text-2xs font-semibold text-green-600">승인</span>
            </div>

            <dl id="seller-document-modal-metadata" class="text-2xs">
                <div class="flex items-center justify-between border-b border-gray-200 py-3">
                    <dt class="text-gray-500">파일</dt>
                    <dd id="seller-document-modal-file" class="font-semibold text-gray-900">그린테이블_사업자등록증.pdf</dd>
                </div>

                <div class="flex items-center justify-between border-b border-gray-200 py-3">
                    <dt class="text-gray-500">제출일</dt>
                    <dd id="seller-document-modal-submitted-at" class="font-semibold text-gray-900">2026-07-18 10:24</dd>
                </div>

                <div class="flex items-center justify-between border-b border-gray-200 py-3">
                    <dt class="text-gray-500">플랫폼 처리일</dt>
                    <dd id="seller-document-modal-processed-at" class="font-semibold text-gray-900">2026-07-20 14:10</dd>
                </div>

                <div class="flex items-center justify-between border-b border-gray-200 py-3">
                    <dt class="text-gray-500">파일 크기</dt>
                    <dd id="seller-document-modal-size" class="font-semibold text-gray-900">823KB</dd>
                </div>
            </dl>

            <div id="seller-document-modal-reason" class="my-3 rounded-lg bg-red-50 p-3 text-2xs text-red-400" hidden>
                <p class="font-bold">보완 사유</p>
                <p>플랫폼 보완 예시: 최근 발급본으로 다시 제출해 주세요.</p>
            </div>

            <div id="seller-document-modal-notice" class="border-l-4 border-amber-400 bg-amber-50 p-3 text-2xs text-gray-600">
                브랜드는 파일 교체와 재제출만 할 수 있으며, 승인 상태는 플랫폼 관리자 처리 결과로만 변경됩니다.
            </div>
        </div>

        <div id="seller-document-modal-footer" class="flex items-center justify-end border-t border-gray-300 p-4">
            <button type="button" id="seller-document-modal-cancel" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-bold text-gray-900">
                닫기
            </button>
        </div>
    </div>
</div>

<script>
    $(function() {
        // 파일 교체 버튼 클릭 및 선택 파일 정보 표시
        $(".seller-document-file-select").on("click", function() {
            const input_selector = $(this).data("fileInput");

            $(input_selector).trigger("click");
        });

        $(".seller-document-file-input").on("change", function() {
            const file = this.files[0];

            if (!file) {
                return;
            }

            const file_display_selector = $(this).data("fileDisplay");
            const file_size = `${Math.ceil(file.size / 1024)}KB`;

            $(file_display_selector).text(`${file.name} · ${file_size}`);
        });

        const $seller_document_modal = $("#seller-document-modal");

        // 상세 이력 클릭 시 모달에 data-* 연동 후 열기
        $(".seller-document-modal-open").on("click", function() {
            const $button = $(this);
            const status_tone = $button.data("documentStatusTone");
            const $status = $("#seller-document-modal-status");
            const $reason = $("#seller-document-modal-reason");

            $("#seller-document-modal-title").text($button.data("documentTitle"));
            $("#seller-document-modal-description").text($button.data("documentDescription"));
            $status.text($button.data("documentStatus"));
            $("#seller-document-modal-file").text($button.data("documentFile"));
            $("#seller-document-modal-submitted-at").text($button.data("documentSubmittedAt"));
            $("#seller-document-modal-processed-at").text($button.data("documentProcessedAt"));
            $("#seller-document-modal-size").text($button.data("documentSize"));

            $status.removeClass("bg-green-50 text-green-600 bg-red-50 text-red-600");

            // 승인 / 보완요청 뱃지 색상
            // 보완 요청 이유 메세지
            if (status_tone === "requested") {
                $status.addClass("bg-red-50 text-red-600");
                $reason.prop("hidden", false);
            } else {
                $status.addClass("bg-green-50 text-green-600");
                $reason.prop("hidden", true);
            }

            $seller_document_modal.prop("hidden", false);
        });

        // 배경 클릭 시 모달 닫기
        $("#seller-document-modal-backdrop").on("click", function() {
            $seller_document_modal.prop("hidden", true);
        });

        // X 버튼 클릭 시 모달 닫기
        $("#seller-document-modal-close").on("click", function() {
            $seller_document_modal.prop("hidden", true);
        });

        // 하단 닫기 버튼 클릭 시 모달 닫기
        $("#seller-document-modal-cancel").on("click", function() {
            $seller_document_modal.prop("hidden", true);
        });
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

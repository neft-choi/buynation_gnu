<?php
$sub_menu = '950300';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '클레임·분쟁';
require_once '../../admin.head.php';
?>

<section>
    <p class="text-gray-600 font-normal">브랜드의 주문·반품 처리와 플랫폼 개입 업무를 SLA 기준으로 구분합니다.</p>

    <section class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <div class="overflow-x-auto">
            <table class="border-collapse min-w-250 w-full table-fixed text-left">
                <caption class="sr-only">클레임·분쟁 목록</caption>

                <colgroup>
                    <col class="w-[12%]">
                    <col class="w-[13%]">
                    <col class="w-[13%]">
                    <col class="w-[13%]">
                    <col class="w-[13%]">
                    <col class="w-[12%]">
                    <col class="w-[12%]">
                    <col class="w-[12%]">
                </colgroup>

                <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">분쟁번호</th>
                        <th scope="col">주문번호</th>
                        <th scope="col">유형</th>
                        <th scope="col">브랜드 / 도트</th>
                        <th scope="col">담당</th>
                        <th scope="col">SLA</th>
                        <th scope="col">상태</th>
                        <th scope="col">처리</th>
                    </tr>
                </thead>

                <tbody class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:whitespace-nowrap [&_td]:px-4 [&_td]:py-3">
                    <tr>
                        <td>
                            <span class="font-bold">ORD-1038</span>
                        </td>
                        <td>
                            <span>20260809007112</span>
                        </td>
                        <td>
                            <span>반품비 이견</span>
                        </td>
                        <td>
                            <span>그린테이블</span>
                            <span class="block text-gray-400 font-normal">DOT-38904</span>
                        </td>
                        <td>
                            <span>플랫폼 CS</span>
                        </td>
                        <td>
                            <span>2시간 남음</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">● 심사 대기</span>
                        </td>
                        <td>
                            <button type="button" class="disputes-detail-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<!-- 클레임·분쟁 상세 모달 -->
<div id="disputes-detail-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="disputes-detail-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="disputes-detail-modal-container" role="dialog" aria-modal="true" aria-labelledby="disputes-detail-modal-title" class="relative z-10 w-full max-w-150 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div id="disputes-detail-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="disputes-detail-modal-title" class="text-sm font-bold text-gray-900">
                클레임·분쟁 상세
            </h3>

            <button type="button" id="disputes-detail-modal-close" aria-label="클레임·분쟁 상세 모달 닫기" class="flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="disputes-detail-modal-body" class="p-4">
            <div class="overflow-hidden rounded-lg border border-gray-300">
                <dl class="text-left text-2xs [&>div+div]:border-t [&>div+div]:border-gray-200">
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">분쟁번호</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">DSP-0182</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">주문번호</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">20260809007112</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">유형</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">반품비 이견</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">당사자</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">그린테이블 / DOT-38904</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">담당</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">플랫폼 CS</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">SLA</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">2시간 남음</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">증빙</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">도트 사진 3장 · 브랜드 답변 1건</dd>
                    </div>
                </dl>
            </div>

            <div class="mt-3">
                <label class="block text-2xs font-bold">플랫폼 처리 메모</label>
                <textarea class="mt-1 rounded-lg"></textarea>
            </div>
        </div>

        <div id="disputes-detail-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="disputes-detail-modal-cancel" class="rounded-lg border border-gray-300 bg-white font-bold text-gray-900 px-4 py-3">
                닫기
            </button>
            <button type="button" class="rounded-lg border border-transparent bg-amber-300 font-bold px-4 py-3">
                처리 완료
            </button>
        </div>
    </div>
</div>

<script>
    // 거래 상세 모달
    $('.disputes-detail-modal-open').on('click', function() {
        $('#disputes-detail-modal').prop('hidden', false);
    });

    $('#disputes-detail-modal-close, #disputes-detail-modal-cancel, #disputes-detail-modal-backdrop').on('click', function() {
        $('#disputes-detail-modal').prop('hidden', true);
    });
</script>

<?php
require_once '../../admin.tail.php';

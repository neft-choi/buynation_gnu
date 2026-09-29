<?php
$sub_menu = '940600';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '도티 승계 심사';
require_once '../../admin.head.php';
?>

<section>
    <p class="text-gray-600 font-normal">동일 계정의 도티 역할과 본인확인을 검증한 뒤 운영권을 이전합니다.</p>

    <div class="mt-4 flex items-center gap-3 bg-amber-50 rounded-lg text-2xs p-3">
        <span class="text-amber-600 font-bold">승계 원칙</span>
        <p class="text-amber-700 font-normal">승계 대상은 도트 계정 자체가 아니라 해당 도트가 보유한 동일 계정의 도티 역할입니다. 플랫폼 승인 전까지 기존 도티 권한이 유지됩니다.</p>
    </div>

    <section class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <div class="overflow-x-auto">
            <table class="border-collapse min-w-250 w-full table-fixed text-left">
                <caption class="sr-only">도티 승계 심사 목록</caption>

                <colgroup>
                    <col class="w-[20%]">
                    <col class="w-[12%]">
                    <col class="w-[20%]">
                    <col class="w-[12%]">
                    <col class="w-[12%]">
                    <col class="w-[12%]">
                    <col class="w-[12%]">
                </colgroup>

                <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">요청번호 / 도넛</th>
                        <th scope="col">현재 도티</th>
                        <th scope="col">승계 도티</th>
                        <th scope="col">가입 도트</th>
                        <th scope="col">본인확인</th>
                        <th scope="col">상태</th>
                        <th scope="col">심사</th>
                    </tr>
                </thead>

                <tbody class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:whitespace-nowrap [&_td]:px-4 [&_td]:py-3">
                    <tr>
                        <td>
                            <span class="block font-bold">반려생활 연구소</span>
                            <span class="block text-zinc-400 font-medium">SUC-PET-001 · DONUT-PET</span>
                        </td>
                        <td>
                            <span>김도윤 · 도티 역할</span>
                        </td>
                        <td>
                            <span>김도현 · 도티 역할 요청</span>
                        </td>
                        <td>
                            <span>DOT-41092</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 완료</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">● 심사 대기</span>
                        </td>
                        <td>
                            <button type="button" class="transfer_review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<!-- 도티 승계 심사 모달 -->
<div id="transfer_review-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="transfer_review-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="transfer_review-modal-container" role="dialog" aria-modal="true" aria-labelledby="transfer_review-modal-title" class="relative z-10 w-full max-w-150 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div id="transfer_review-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="transfer_review-modal-title" class="text-sm font-bold text-gray-900">
                도티 승계 심사
            </h3>

            <button type="button" id="transfer_review-modal-close" aria-label="상품 검수 모달 닫기" class="flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="transfer_review-modal-body" class="p-4">
            <div class="overflow-hidden rounded-lg border border-gray-300">
                <dl class="text-left text-2xs [&>div+div]:border-t [&>div+div]:border-gray-200">
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">도넛</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">반려생활 연구소 · DONUT-PET</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">현재 도티</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">김도윤 · 도티 역할</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">승계 도티</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">김도현 · 도티 역할 요청</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">가입 도트</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">DOT-41092</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">KCP 본인확인</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">완료</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">상태</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">● 심사 대기</span>
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="mt-3 rounded-lg bg-amber-50 p-3">
                <p class="mt-1 text-2xs text-amber-600">승인 시 동일 계정의 도티 역할이 변경되고 승계 이력이 보관됩니다.</p>
            </div>
        </div>

        <div id="transfer_review-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="transfer_review-modal-cancel" class="rounded-lg border border-gray-300 bg-white font-bold text-gray-900 px-4 py-3">
                닫기
            </button>
            <button type="button" class="rounded-lg border border-red-400 bg-white font-bold text-red-600 px-4 py-3">
                보완
            </button>
            <button type="button" class="rounded-lg border border-transparent bg-amber-300 font-bold px-4 py-3">
                승인
            </button>
        </div>
    </div>
</div>

<script>
    $('.transfer_review-modal-open').on('click', function() {
        $('#transfer_review-modal').prop('hidden', false);
    });

    $('#transfer_review-modal-close, #transfer_review-modal-cancel, #transfer_review-modal-backdrop').on('click', function() {
        $('#transfer_review-modal').prop('hidden', true);
    });
</script>

<?php
require_once '../../admin.tail.php';

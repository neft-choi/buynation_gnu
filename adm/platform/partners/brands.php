<?php
$sub_menu = '930100';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '브랜드 서류 심사';
require_once '../../admin.head.php';
?>

<section>
    <h2 class="sr-only">브랜드 서류 심사</h2>

    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="text-gray-600 font-normal">사업자등록증·통신판매업 신고증·정산계좌 서류와 법인 해당 서류를 개별 확인합니다.</p>

        <button type="button" class="shrink-0 border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
            <span>심사 목록</span>
        </button>
    </div>

    <div class="mt-4 flex items-center gap-3 bg-amber-50 rounded-lg text-2xs p-3">
        <span class="text-amber-600 font-bold">심사 경계</span>
        <p class="text-gray-600">브랜드가 관리자에서 서류를 제출하면 플랫폼이 승인·보완·최종 반려합니다. 상품은 브랜드 서류 승인과 별도의 상품 검수를 통과해야 판매됩니다.</p>
    </div>

    <div id="brand-review-filters" role="group" aria-label="서류 심사 상태 필터" class="mt-4 flex flex-wrap gap-2">
        <button type="button" data-status="all" aria-pressed="true" class="rounded-full bg-gray-900 text-2xs font-bold text-white px-3 py-2">
            전체
        </button>
        <button type="button" data-status="pending" aria-pressed="false" class="rounded-full border border-gray-300 bg-white text-2xs text-gray-700 px-3 py-2">
            심사 대기
        </button>
        <button type="button" data-status="supplement" aria-pressed="false" class="rounded-full border border-gray-300 bg-white text-2xs text-gray-700 px-3 py-2">
            보완 요청
        </button>
        <button type="button" data-status="approved" aria-pressed="false" class="rounded-full border border-gray-300 bg-white text-2xs text-gray-700 px-3 py-2">
            승인
        </button>
        <button type="button" data-status="rejected" aria-pressed="false" class="rounded-full border border-gray-300 bg-white text-2xs text-gray-700 px-3 py-2">
            최종 거절
        </button>
    </div>

    <section class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <div class="overflow-x-auto">
            <table class="border-collapse min-w-250 w-full table-fixed text-left">
                <caption class="sr-only">브랜드 서류 심사 요청 목록</caption>

                <colgroup>
                    <col class="w-[30%]">
                    <col class="w-[12%]">
                    <col class="w-[15%]">
                    <col class="w-[8%]">
                    <col class="w-[8%]">
                    <col class="w-[15%]">
                    <col class="w-[12%]">
                </colgroup>

                <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">요청번호 / 브랜드</th>
                        <th scope="col">유형</th>
                        <th scope="col">제출</th>
                        <th scope="col">서류</th>
                        <th scope="col">SLA</th>
                        <th scope="col">상태</th>
                        <th scope="col">심사</th>
                    </tr>
                </thead>

                <tbody id="brand-review-list" class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:whitespace-nowrap [&_td]:px-4 [&_td]:py-3">
                    <tr data-status="pending">
                        <td>
                            <span class="block font-bold">노르딕홈</span>
                            <span class="block text-zinc-400">BR-VRF-2026-0811 · BRD-00182</span>
                        </td>
                        <td>신규 입점</td>
                        <td>2026.08.08 09:20</td>
                        <td>0/4</td>
                        <td>D-2</td>
                        <td><span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-700 px-2 py-1">● 심사 대기</span></td>
                        <td>
                            <button type="button" class="brand-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>

                    <tr data-status="pending">
                        <td>
                            <span class="block font-bold">그린리프</span>
                            <span class="block text-zinc-400">BR-VRF-2026-0809 · BRD-00179</span>
                        </td>
                        <td>신규 입점</td>
                        <td>2026.08.07 15:10</td>
                        <td>0/3</td>
                        <td>D-1</td>
                        <td><span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-700 px-2 py-1">● 심사 대기</span></td>
                        <td>
                            <button type="button" class="brand-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>

                    <tr data-status="supplement">
                        <td>
                            <span class="block font-bold">그린테이블</span>
                            <span class="block text-zinc-400">BR-VRF-2026-0718 · BRD-00204</span>
                        </td>
                        <td>서류 변경</td>
                        <td>2026.08.08 11:05</td>
                        <td>3/4</td>
                        <td>D-1</td>
                        <td><span class="rounded-full bg-red-100 text-2xs font-bold text-red-600 px-2 py-1">● 보완 요청</span></td>
                        <td>
                            <button type="button" class="brand-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>

                    <tr data-status="approved">
                        <td>
                            <span class="block font-bold">몬스테리</span>
                            <span class="block text-zinc-400">BR-VRF-2026-0602 · BRD-00168</span>
                        </td>
                        <td>신규 입점</td>
                        <td>2026.06.10 11:22</td>
                        <td>3/3</td>
                        <td>완료</td>
                        <td><span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">● 승인</span></td>
                        <td>
                            <button type="button" class="brand-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>

                    <tr data-status="rejected">
                        <td>
                            <span class="block font-bold">하루마켓</span>
                            <span class="block text-zinc-400">BR-VRF-2026-0521 · BRD-00155</span>
                        </td>
                        <td>신규 입점</td>
                        <td>2026.07.14 09:05</td>
                        <td>0/3</td>
                        <td>종료</td>
                        <td><span class="rounded-full bg-red-100 text-2xs font-bold text-red-600 px-2 py-1">● 최종 거절</span></td>
                        <td>
                            <button type="button" class="brand-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<!-- 브랜드 서류 심사 모달 -->
<div id="brand-review-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="brand-review-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="brand-review-modal-container" role="dialog" aria-modal="true" aria-labelledby="brand-review-modal-title" class="relative z-10 w-full max-w-150 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div id="brand-review-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="brand-review-modal-title" class="text-sm font-bold text-gray-900">
                브랜드 서류 심사
            </h3>

            <button type="button" id="brand-review-modal-close" aria-label="브랜드 서류 심사 모달 닫기" class="flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="brand-review-modal-body" class="p-4">
            <span class="text-2xs font-bold text-amber-700">BR-VRF-2026-0811</span>

            <h3 class="mt-2 text-xl font-bold text-gray-900">
                노르딕홈 · 신규 입점
            </h3>

            <div class="mt-4 overflow-hidden rounded-lg border border-gray-300">
                <dl class="text-left text-2xs [&>div+div]:border-t [&>div+div]:border-gray-200">
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">브랜드 ID</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">BRD-00182</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">사업자번호</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">214-81-00931</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">담당자</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">이서연 · 010-2234-8891</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">정산계좌</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">국민 123-45-******</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">제출일 / SLA</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">2026.08.08 09:20 · D-2</dd>
                    </div>
                </dl>
            </div>

            <div class="mt-4 space-y-2">
                <div class="flex items-center justify-between rounded-lg border border-gray-300 p-3">
                    <div>
                        <span class="block text-2xs font-bold text-gray-900">사업자등록증</span>
                        <p class="mt-1 text-2xs text-gray-400">nordic_business.pdf</p>
                    </div>
                    <span class="rounded-full bg-amber-50 text-2xs font-bold text-amber-700 px-2 py-1">● 심사 대기</span>
                </div>

                <div class="flex items-center justify-between rounded-lg border border-gray-300 p-3">
                    <div>
                        <span class="block text-2xs font-bold text-gray-900">통신판매업 신고증</span>
                        <p class="mt-1 text-2xs text-gray-400">nordic_sales.pdf</p>
                    </div>
                    <span class="rounded-full bg-amber-50 text-2xs font-bold text-amber-700 px-2 py-1">● 심사 대기</span>
                </div>

                <div class="flex items-center justify-between rounded-lg border border-gray-300 p-3">
                    <div>
                        <span class="block text-2xs font-bold text-gray-900">정산계좌 확인서류</span>
                        <p class="mt-1 text-2xs text-gray-400">nordic_bank.pdf</p>
                    </div>
                    <span class="rounded-full bg-amber-50 text-2xs font-bold text-amber-700 px-2 py-1">● 심사 대기</span>
                </div>

                <div class="flex items-center justify-between rounded-lg border border-gray-300 p-3">
                    <div>
                        <span class="block text-2xs font-bold text-gray-900">법인 등기사항증명서</span>
                        <p class="mt-1 text-2xs text-gray-400">nordic_corp.pdf</p>
                    </div>
                    <span class="rounded-full bg-amber-50 text-2xs font-bold text-amber-700 px-2 py-1">● 심사 대기</span>
                </div>
            </div>
        </div>

        <div id="brand-review-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="brand-review-modal-cancel" class="rounded-lg border border-gray-300 bg-white font-bold text-gray-900 px-4 py-3">
                닫기
            </button>

            <button type="button" class="rounded-lg border border-red-400 bg-white font-bold text-red-600 px-4 py-3">
                보완 요청
            </button>

            <button type="button" class="rounded-lg border border-red-400 bg-white font-bold text-red-600 px-4 py-3">
                최종 반려
            </button>

            <button type="button" class="rounded-lg bg-amber-300 font-bold text-gray-900 px-4 py-3">
                전체 승인
            </button>
        </div>
    </div>
</div>

<script>
    $('#brand-review-filters button').on('click', function() {
        const status = $(this).data('status');

        $('#brand-review-filters button')
            .attr('aria-pressed', 'false')
            .removeClass('bg-gray-900 font-bold text-white')
            .addClass('border border-gray-300 bg-white text-gray-700');

        $(this)
            .attr('aria-pressed', 'true')
            .removeClass('border border-gray-300 bg-white text-gray-700')
            .addClass('bg-gray-900 font-bold text-white');

        $('#brand-review-list tr').prop('hidden', false);

        if (status !== 'all') {
            $('#brand-review-list tr').not('[data-status="' + status + '"]').prop('hidden', true);
        }
    });

    // 브랜드 서류 심사 모달
    $('.brand-review-modal-open').on('click', function() {
        $('#brand-review-modal').prop('hidden', false);
    });

    $('#brand-review-modal-close, #brand-review-modal-cancel, #brand-review-modal-backdrop').on('click', function() {
        $('#brand-review-modal').prop('hidden', true);
    });
</script>

<?php
require_once '../../admin.tail.php';

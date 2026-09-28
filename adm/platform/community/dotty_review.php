<?php
$sub_menu = '940400';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '도티 사업자 심사';
require_once '../../admin.head.php';
?>

<section>
    <h2 class="sr-only">도티 사업자 심사</h2>

    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="text-gray-600 font-normal">최초 인증과 전환 신청은 승인 전 토핑의 현금화 규칙이 다릅니다.</p>

        <button type="button" class="shrink-0 w-fit border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
            <span>14일 기한 점검</span>
        </button>
    </div>

    <section class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
        <div class="border border-gray-300 rounded-lg bg-gray-50 p-3">
            <span class="block text-2xs text-gray-900 font-bold">최초 사업자 인증</span>
            <p class="mt-1 text-2xs text-gray-400 font-normal">개설 후 14일 이내 정상 제출하고 승인되면 개설 후 심사 중 적립분까지 현금화할 수 있습니다. 미제출 또는 최종 거절이면 비사업자로 시작합니다.</p>
        </div>
        <div class="border border-gray-300 rounded-lg bg-gray-50 p-3">
            <span class="block text-2xs text-gray-900 font-bold">비사업자 → 사업자 전환</span>
            <p class="mt-1 text-2xs text-gray-400 font-normal">기존 비사업자 적립분과 승인 전까지 적립된 토핑은 쇼핑 전용으로 남으며, 승인 이후 새로 적립된 토핑만 현금화할 수 있습니다.</p>
        </div>
    </section>

    <section class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <div class="overflow-x-auto">
            <table class="border-collapse min-w-250 w-full table-fixed text-left">
                <caption class="sr-only">도넛 사업자 심사 목록</caption>

                <colgroup>
                    <col class="w-[28%]">
                    <col class="w-[12%]">
                    <col class="w-[12%]">
                    <col class="w-[12%]">
                    <col class="w-[12%]">
                    <col class="w-[12%]">
                    <col class="w-[12%]">
                </colgroup>

                <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">신청번호 / 도넛</th>
                        <th scope="col">신청 유형</th>
                        <th scope="col">개설·기한</th>
                        <th scope="col">제출</th>
                        <th scope="col">심사중 적립</th>
                        <th scope="col">상태</th>
                        <th scope="col">심사</th>

                    </tr>
                </thead>

                <tbody class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:whitespace-nowrap [&_td]:px-4 [&_td]:py-3">
                    <tr>
                        <td>
                            <span class="block font-bold">테니스 커뮤니티</span>
                            <span class="block text-zinc-400 font-medium">DTV-TENNIS-260704 · DONUT-TENNIS</span>
                        </td>
                        <td>
                            <span>최초 사업자 인증</span>
                        </td>
                        <td>
                            <span class="block">2026.07.01</span>
                            <span class="block text-zinc-400">기한 2026.07.15</span>
                        </td>
                        <td>
                            <span>2026.07.04</span>
                        </td>
                        <td>
                            <span>680,000T</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 승인</span>
                        </td>
                        <td>
                            <button type="button" class="dotty-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">러닝 메이트</span>
                            <span class="block text-zinc-400 font-medium">DTV-RUNNING-260811 · DONUT-RUNNING</span>
                        </td>
                        <td>
                            <span>최초 사업자 인증</span>
                        </td>
                        <td>
                            <span class="block">2026.08.01</span>
                            <span class="block text-zinc-400">기한 2026.08.15</span>
                        </td>
                        <td>
                            <span>미제출</span>
                        </td>
                        <td>
                            <span>1,286,400T</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-gray-100 text-2xs font-bold text-gray-600 px-2 py-1">● 미제출</span>
                        </td>
                        <td>
                            <button type="button" class="dotty-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">사진 산책회</span>
                            <span class="block text-zinc-400 font-medium">DTV-PHOTO-260808 · DONUT-PHOTO</span>
                        </td>
                        <td>
                            <span>최초 사업자 인증</span>
                        </td>
                        <td>
                            <span class="block">2026.08.02</span>
                            <span class="block text-zinc-400">기한 2026.08.16</span>
                        </td>
                        <td>
                            <span>2026.08.08</span>
                        </td>
                        <td>
                            <span>742,000T</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">● 심사 대기</span>
                        </td>
                        <td>
                            <button type="button" class="dotty-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">독서 라운지</span>
                            <span class="block text-zinc-400 font-medium">DTV-BOOK-260807 · DONUT-BOOK</span>
                        </td>
                        <td>
                            <span>최초 사업자 인증</span>
                        </td>
                        <td>
                            <span class="block">2026.08.03</span>
                            <span class="block text-zinc-400">기한 2026.08.17</span>
                        </td>
                        <td>
                            <span>2026.08.07</span>
                        </td>
                        <td>
                            <span>384,000T</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-red-100 text-2xs font-bold text-red-600 px-2 py-1">● 보완 요청</span>
                        </td>
                        <td>
                            <button type="button" class="dotty-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">홈베이킹 살롱</span>
                            <span class="block text-zinc-400 font-medium">DTV-BAKING-260724 · DONUT-BAKING</span>
                        </td>
                        <td>
                            <span>최초 사업자 인증</span>
                        </td>
                        <td>
                            <span class="block">2026.07.18</span>
                            <span class="block text-zinc-400">기한 2026.08.01</span>
                        </td>
                        <td>
                            <span>2026.07.24</span>
                        </td>
                        <td>
                            <span>918,000T</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-red-100 text-2xs font-bold text-red-600 px-2 py-1">● 최종 거절</span>
                        </td>
                        <td>
                            <button type="button" class="dotty-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<!-- 도티 사업자 심사 모달 -->
<div id="dotty-review-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="dotty-review-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="dotty-review-modal-container" role="dialog" aria-modal="true" aria-labelledby="dotty-review-modal-title" class="relative z-10 w-full max-w-150 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div id="dotty-review-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="dotty-review-modal-title" class="text-sm font-bold text-gray-900">
                도티 사업자 심사
            </h3>

            <button type="button" id="dotty-review-modal-close" aria-label="상품 검수 모달 닫기" class="flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="dotty-review-modal-body" class="p-4">
            <span class="text-2xs font-bold text-amber-700">DTV-TENNIS-260704</span>

            <h3 class="mt-2 text-xl font-bold text-gray-900">
                테니스 커뮤니티
            </h3>

            <div class="mt-4 overflow-hidden rounded-lg border border-gray-300">
                <dl class="text-left text-2xs [&>div+div]:border-t [&>div+div]:border-gray-200">
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">신청 유형</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">최초 사업자 인증</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">도티</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">김도윤 · DOTI-0001</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">개설 / 기한</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">2026.07.01 / 2026.07.15</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">제출일</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">2026.07.04</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">사업자</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">테니스 커뮤니티 주식회사 · 120-88-01984</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">계좌</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">신한 110-***-123456</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">심사 중 적립</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">680,000T</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">현재 상태</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 승인</span>
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="mt-3 flex items-center justify-between rounded-lg border border-gray-300 p-3">
                <div>
                    <span class="block text-2xs font-bold text-gray-900">사업자등록증</span>
                    <p class="mt-1 text-2xs text-gray-400">도티 관리자 제출 항목</p>
                </div>
                <span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">● 승인</span>
            </div>

            <div class="mt-3 flex items-center justify-between rounded-lg border border-gray-300 p-3">
                <div>
                    <span class="block text-2xs font-bold text-gray-900">대표자 확인서류</span>
                    <p class="mt-1 text-2xs text-gray-400">도티 관리자 제출 항목</p>
                </div>
                <span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">● 승인</span>
            </div>

            <div class="mt-3 flex items-center justify-between rounded-lg border border-gray-300 p-3">
                <div>
                    <span class="block text-2xs font-bold text-gray-900">정산계좌 확인서류</span>
                    <p class="mt-1 text-2xs text-gray-400">도티 관리자 제출 항목</p>
                </div>
                <span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">● 승인</span>
            </div>

            <div class="mt-3 rounded-lg bg-amber-50 p-3">
                <span class="block text-2xs text-amber-700 font-bold">승인 결과의 토핑 영향</span>
                <p class="mt-1 text-2xs text-amber-600">개설 후 심사 기간 적립분까지 현금화 가능</p>
            </div>

            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 items-center gap-2 md:gap-3">
                <div class="border border-gray-300 rounded-lg bg-white p-3">
                    <span class="block text-2xs text-gray-900 font-bold">현재 현금화 가능</span>
                    <p class="mt-1 text-2xs text-gray-400 font-normal">680,000T</p>
                </div>
                <div class="border border-gray-300 rounded-lg bg-white p-3">
                    <span class="block text-2xs text-gray-900 font-bold">쇼핑 전용·잠정</span>
                    <p class="mt-1 text-2xs text-gray-400 font-normal">0T</p>
                </div>
            </div>
        </div>

        <div id="dotty-review-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="dotty-review-modal-cancel" class="rounded-lg border border-gray-300 bg-white font-bold text-gray-900 px-4 py-3">
                닫기
            </button>
        </div>
    </div>
</div>

<script>
    $('.dotty-review-modal-open').on('click', function() {
        $('#dotty-review-modal').prop('hidden', false);
    });

    $('#dotty-review-modal-close, #dotty-review-modal-cancel, #dotty-review-modal-backdrop').on('click', function() {
        $('#dotty-review-modal').prop('hidden', true);
    });
</script>

<?php
require_once '../../admin.tail.php';

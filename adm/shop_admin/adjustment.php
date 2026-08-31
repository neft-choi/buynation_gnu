<?php
$sub_menu = '400450';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '정산 관리';
include_once(G5_ADMIN_PATH . '/admin.head.php');
?>

<section class="space-y-4">
    <header class="grid grid-cols-1 gap-3 pc:grid-cols-[1fr_auto] pc:items-center">
        <div>
            <h2 class="text-xl font-bold text-slate-900">정산 관리</h2>
            <p class="mt-1 text-xs text-slate-500">
                매출·배송비·환불·수수료·기여 부담액과 지급 상태를 회차별로 확인합니다.
            </p>
        </div>

        <button type="button" class="btn btn_04">정산 내보내기</button>
    </header>

    <div class="grid grid-cols-1 gap-4 pc:grid-cols-4">
        <article class="rounded-xl border border-gray-300 bg-white p-5">
            <p class="text-sm text-slate-500">상품 매출</p>
            <strong class="mt-4 block text-2xl font-bold text-slate-900">337,400원</strong>
            <p class="mt-3 text-xs text-slate-500">현재 샘플 주문</p>
        </article>

        <article class="rounded-xl border border-gray-300 bg-white p-5">
            <p class="text-sm text-slate-500">배송비 수입</p>
            <strong class="mt-4 block text-2xl font-bold text-slate-900">22,000원</strong>
            <p class="mt-3 text-xs text-slate-500">주문 배송비 합계</p>
        </article>

        <article class="rounded-xl border border-amber-300 bg-amber-50 p-5">
            <p class="text-sm text-slate-500">환불 확정</p>
            <strong class="mt-4 block text-2xl font-bold text-slate-900">0원</strong>
            <p class="mt-3 text-xs text-slate-500">처리완료 클레임</p>
        </article>

        <article class="rounded-xl border border-gray-300 bg-white p-5">
            <p class="text-sm text-slate-500">예상 정산액</p>
            <strong class="mt-4 block text-2xl font-bold text-slate-900">331,092원</strong>
            <p class="mt-3 text-xs text-slate-500">상품 주문 기록 기준</p>
        </article>
    </div>

    <div class="grid grid-cols-1 gap-4 pc:grid-cols-2">
        <section class="overflow-hidden rounded-xl border border-gray-300 bg-white">
            <header class="border-b border-gray-300 px-5 py-4">
                <h3 class="text-gray-900 font-bold">회차별 정산</h3>
            </header>

            <div class="overflow-x-auto">
                <table class="w-full min-w-180 table-fixed border-collapse text-left text-xs">
                    <colgroup>
                        <col class="w-[12%]">
                        <col class="w-[16%]">
                        <col class="w-[16%]">
                        <col class="w-[16%]">
                        <col class="w-[16%]">
                        <col class="w-[12%]">
                        <col class="w-[12%]">
                    </colgroup>

                    <thead class="border-b border-gray-200 bg-gray-50 text-2xs text-gray-500 [&_th]:px-3 [&_th]:py-3 [&_th]:font-semibold">
                        <tr>
                            <th scope="col">회차</th>
                            <th scope="col">상품매출</th>
                            <th scope="col">배송비</th>
                            <th scope="col">차감</th>
                            <th scope="col">정산액</th>
                            <th scope="col">지급</th>
                            <th scope="col">상세</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 text-gray-700 [&_td]:px-3 [&_td]:py-4 [&_td]:align-middle">
                        <tr>
                            <td>
                                <span class="block font-bold text-gray-900">2026-08</span>
                            </td>

                            <td>
                                <span>2,315,000원</span>
                            </td>

                            <td>
                                <span>151,000원</span>
                            </td>

                            <td>
                                <span>246,360원</span>
                            </td>

                            <td>
                                <span class="block font-bold text-gray-900">2,219,640원</span>
                            </td>

                            <td>
                                <span class="block w-fit text-2xs text-amber-600 font-bold bg-amber-100 rounded-lg px-2 py-1">집계중</span>
                                <span class="mt-1 block text-2xs text-gray-300">미확정</span>
                            </td>

                            <td>
                                <button type="button" class="adjustment-detail-modal-open whitespace-nowrap rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                    내역
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <span class="block font-bold text-gray-900">2026-07</span>
                            </td>

                            <td>
                                <span>4,820,000원</span>
                            </td>

                            <td>
                                <span>318,000원</span>
                            </td>

                            <td>
                                <span>571,600원</span>
                            </td>

                            <td>
                                <span class="block font-bold text-gray-900">4,566,400원</span>
                            </td>

                            <td>
                                <span class="block w-fit text-2xs text-green-600 font-bold bg-green-100 rounded-lg px-2 py-1">지급완료</span>
                                <span class="mt-1 block text-2xs text-gray-300">2026-08-12</span>
                            </td>

                            <td>
                                <button type="button" class="adjustment-detail-modal-open whitespace-nowrap rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                    내역
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-xl border border-gray-300 bg-white p-5">
            <h3 class="font-bold text-slate-900">현재 예상액 계산</h3>

            <div class="mt-4 rounded-lg bg-slate-50 p-4 text-xs text-slate-600">
                <p>상품 매출 337,400원</p>
                <p>+ 배송비 수입 22,000원</p>
                <p>− 환불 확정 0원</p>
                <p>− 플랫폼 수수료 기록 26,992원</p>
                <p>− 추가토핑 부담(1회) 1,316원</p>
                <p class="font-bold">= 예상 정산액 331,092원</p>
            </div>

            <p class="mt-4 border-l-4 border-amber-400 bg-amber-50 p-3 text-xs text-slate-600">
                기본 토핑 3.2%는 기본 판매수수료에 이미 포함되므로 다시 차감하지 않습니다. 정산에서는 실제 적용된 추가토핑만 1회 차감하며, 월말 마감 후 익월 12일 지급을 기준으로 담당자가 검토합니다.
            </p>
        </section>
    </div>
</section>

<div id="adjustment-detail-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4" hidden>
    <div id="adjustment-detail-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="adjustment-detail-modal-container" class="relative z-10 w-full max-w-200 rounded-lg bg-white">
        <div id="adjustment-detail-modal-header" class="flex items-center justify-between border-b border-gray-300 px-5 py-4">
            <h3 id="adjustment-detail-modal-title" class="text-sm font-bold text-gray-900">2026-07 정산 상세</h3>

            <button type="button" id="adjustment-detail-modal-close" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100" aria-label="모달 닫기">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-5 w-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="adjustment-detail-modal-body" class="space-y-4 p-5">
            <div id="adjustment-detail-modal-summary" class="grid grid-cols-1 gap-8 pc:grid-cols-2">
                <div id="adjustment-detail-modal-calculation" class="rounded-lg bg-slate-50 p-4 text-xs text-slate-600">
                    <p>상품 매출 4,820,000원</p>
                    <p>+ 배송비 수입 318,000원</p>
                    <p>− 환불 138,000원</p>
                    <p>− 플랫폼 수수료 385,600원</p>
                    <p>− 추가토핑 부담 48,000원</p>
                    <p class="text-gray-900 font-bold">= 정산액 4,566,400원</p>
                </div>

                <dl id="adjustment-detail-modal-payment" class="text-2xs">
                    <div class="flex items-center justify-between border-b border-gray-200 py-3">
                        <dt class="text-gray-500">상태</dt>
                        <dd>
                            <span class="rounded-full bg-green-50 px-2 py-1 text-2xs font-semibold text-green-600">지급완료</span>
                        </dd>
                    </div>

                    <div class="flex items-center justify-between border-b border-gray-200 py-3">
                        <dt class="text-gray-500">지급일</dt>
                        <dd class="font-semibold text-gray-900">2026-08-12</dd>
                    </div>

                    <div class="flex items-center justify-between border-b border-gray-200 py-3">
                        <dt class="text-gray-500">정산계좌</dt>
                        <dd class="font-semibold text-gray-900">○○은행 123-****-567890</dd>
                    </div>

                    <div class="flex items-center justify-between border-b border-gray-200 py-3">
                        <dt class="text-gray-500">예금주</dt>
                        <dd class="font-semibold text-gray-900">주식회사 그린테이블</dd>
                    </div>
                </dl>
            </div>

            <div id="adjustment-detail-modal-notice" class="border-l-4 border-amber-400 bg-amber-50 p-3 text-2xs text-gray-600">
                정산액 = 상품 매출 + 배송비 수입 − 환불 − 플랫폼 수수료 − 추가토핑 부담액으로 구성한 프로토타입 예시입니다.
            </div>
        </div>
        <div id="adjustment-detail-modal-footer" class="flex items-center justify-end border-t border-gray-300 p-4">
            <button type="button" id="adjustment-detail-modal-cancel" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-bold text-gray-900">
                닫기
            </button>
        </div>
    </div>
</div>

<script>
    $(function() {
        const $adjustment_detail_modal = $("#adjustment-detail-modal");

        // 모달 열기
        $(".adjustment-detail-modal-open").on("click", function() {
            $adjustment_detail_modal.prop("hidden", false);
        });

        // 배경 클릭 시 모달 닫기
        $("#adjustment-detail-modal-backdrop").on("click", function() {
            $adjustment_detail_modal.prop("hidden", true);
        });

        // X 버튼 클릭 시 모달 닫기
        $("#adjustment-detail-modal-close").on("click", function() {
            $adjustment_detail_modal.prop("hidden", true);
        });

        // 하단 닫기 버튼 클릭 시 모달 닫기
        $("#adjustment-detail-modal-cancel").on("click", function() {
            $adjustment_detail_modal.prop("hidden", true);
        });
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

<?php
$sub_menu = '970100';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '정책 파라미터';
require_once '../../admin.head.php';
?>

<section>
    <header class="flex items-center justify-between">
        <p class="text-gray-400 font-normal">확정 정책, 조정 가능한 값, 결정이 필요한 항목을 구분합니다.</p>

        <div class="inline-flex items-center gap-2">
            <button type="button" class="w-fit border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
                정책 업무 보기
            </button>
            <button type="button" class="w-fit border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
                변경 이력
            </button>
            <button type="button" class="w-fit border border-transparent rounded-lg bg-amber-300 text-gray-900 font-bold px-3 py-2">
                변경 예약
            </button>
        </div>
    </header>

    <div class="mt-4 flex items-center gap-3 bg-amber-50 rounded-lg text-2xs p-3">
        <strong class="text-amber-600 font-bold">표시 기준</strong>
        <p class="text-amber-700 font-normal">초록색은 확정·고정 정책, 노란색 결정 항목은 처리 업무함에 각각 하나의 업무로 생성됩니다. 변경은 소급하지 않고 시행 시점 이후 신규 건부터 적용합니다.</p>
    </div>

    <div role="tablist" aria-label="정책 파라미터 분류" class="mt-4 flex w-fit rounded-lg bg-gray-100 p-1">
        <button type="button" role="tab" id="policy-settlement-tab" aria-selected="true" aria-controls="policy-settlement-panel" class="rounded-lg bg-white px-3 py-2 text-2xs font-bold text-gray-900">
            정산·환불
        </button>

        <button type="button" role="tab" id="policy-community-tab" aria-selected="false" aria-controls="policy-community-panel" tabindex="-1" class="rounded-lg px-3 py-2 text-2xs font-bold text-gray-600">
            가입·커뮤니티
        </button>

        <button type="button" role="tab" id="policy-shopping-tab" aria-selected="false" aria-controls="policy-shopping-panel" tabindex="-1" class="rounded-lg px-3 py-2 text-2xs font-bold text-gray-600">
            쇼핑·기여
        </button>

        <button type="button" role="tab" id="policy-suggestion-tab" aria-selected="false" aria-controls="policy-suggestion-panel" tabindex="-1" class="rounded-lg px-3 py-2 text-2xs font-bold text-gray-600">
            제안·좋아요
        </button>

        <button type="button" role="tab" id="policy-review-tab" aria-selected="false" aria-controls="policy-review-panel" tabindex="-1" class="rounded-lg px-3 py-2 text-2xs font-bold text-gray-600">
            검토 통제
        </button>
    </div>

    <section role="tabpanel" id="policy-settlement-panel" aria-labelledby="policy-settlement-tab" class="mt-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <section class="rounded-lg border border-gray-300 bg-white p-4">
                <h3 class="text-xl text-gray-900 font-bold">정산·철회 파라미터</h3>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <label for="policy-donut-min-settlement" class="text-2xs font-bold text-gray-900">
                                도넛 최소 정산액
                            </label>

                            <span class="text-2xs text-gray-400">원</span>
                        </div>

                        <input type="number" id="policy-donut-min-settlement" name="donut_min_settlement"
                            value="50000" min="0" step="1000" class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-3 text-2xs text-gray-900">
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <label for="policy-monthly-payment-day" class="text-2xs font-bold text-gray-900">
                                익월 지급일
                            </label>

                            <span class="text-2xs text-gray-400">일</span>
                        </div>

                        <input type="number" id="policy-monthly-payment-day" name="monthly_payment_day"
                            value="12" min="1" max="31" step="1" class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-3 text-2xs text-gray-900">
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <label for="policy-general-withdrawal" class="text-2xs font-bold text-gray-900">
                                일반 청약철회
                            </label>

                            <span class="text-2xs text-gray-400">일</span>
                        </div>

                        <input type="number" id="policy-general-withdrawal" name="general_withdrawal_days"
                            value="7" min="0" step="1" class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-3 text-2xs text-gray-900">
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-2xs font-bold text-gray-900">
                                불일치 청약철회
                            </span>

                            <span class="text-2xs text-gray-400"></span>
                        </div>

                        <div class="mt-2 flex h-10 items-center rounded-lg border border-gray-300 bg-gray-100 px-3 text-2xs text-gray-500">
                            공급 3개월 · 안 날 30일
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-2xs font-bold text-gray-900">
                                자동 구매확정
                            </span>

                            <span class="text-2xs text-gray-400">배송완료 후 일</span>
                        </div>

                        <div class="mt-2 flex h-10 items-center rounded-lg border border-gray-300 bg-gray-100 px-3 text-2xs text-gray-500">
                            7
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-2xs font-bold text-gray-900">
                                환급 처리
                            </span>

                            <span class="text-2xs text-gray-400">영업일</span>
                        </div>

                        <div class="mt-2 flex h-10 items-center rounded-lg border border-gray-300 bg-gray-100 px-3 text-2xs text-gray-500">
                            3
                        </div>
                    </div>
                </div>
            </section>

            <section class="border border-gray-300 rounded-lg p-4">
                <h3 class="text-xl font-bold">고정 적용 원칙</h3>
                <ul class="mt-4 space-y-2">
                    <li>
                        <div class="flex items-center justify-between rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">도넛 현금 정산 신청</span>
                                <p class="mt-1 text-2xs text-gray-400">최소 50,000원 · 신청 후 취소 불가</p>
                            </div>
                            <span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">확정</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center justify-between rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">구매확정과 철회</span>
                                <p class="mt-1 text-2xs text-gray-400">구매확정 후에도 법정 청약철회권은 소멸하지 않음</p>
                            </div>
                            <span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">확정</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center justify-between rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">브랜드 정산 차감</span>
                                <p class="mt-1 text-2xs text-gray-400">기본 토핑 3.2%는 기본 판매수수료에 포함 · 추가토핑만 1회 차감</p>
                            </div>
                            <span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">확정</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center justify-between rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">실제 지급 실행</span>
                                <p class="mt-1 text-2xs text-gray-400">월말 마감·익월 12일 기준, 담당자가 증빙 확인 후 외부 절차로 실행</p>
                            </div>
                            <span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">담당자</span>
                        </div>
                    </li>
                </ul>
            </section>
        </div>
    </section>

    <section role="tabpanel" id="policy-community-panel" aria-labelledby="policy-community-tab" class="mt-4" hidden>

    </section>

    <section role="tabpanel" id="policy-shopping-panel" aria-labelledby="policy-shopping-tab" class="mt-4" hidden>

    </section>

    <section role="tabpanel" id="policy-suggestion-panel" aria-labelledby="policy-suggestion-tab" class="mt-4" hidden>

    </section>

    <section role="tabpanel" id="policy-review-panel" aria-labelledby="policy-review-tab" class="mt-4" hidden>

    </section>
</section>

<?php
require_once '../../admin.tail.php';

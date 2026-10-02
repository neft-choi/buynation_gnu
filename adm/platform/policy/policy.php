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
            <a href="<?php echo G5_ADMIN_URL . '/platform/work_queue.php'; ?>" class="shrink-0 w-fit border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
                <span>정책 업무 보기</span>
            </a>
            <button type="button" class="policy-history-modal-open w-fit border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
                변경 이력
            </button>
            <button type="button" class="policy-schedule-modal-open w-fit border border-transparent rounded-lg bg-amber-300 text-gray-900 font-bold px-3 py-2">
                변경 예약
            </button>
        </div>
    </header>

    <div class="mt-4 flex items-center gap-3 bg-amber-50 rounded-lg text-2xs p-3">
        <strong class="text-amber-600 font-bold">표시 기준</strong>
        <p class="text-amber-700 font-normal">초록색은 확정·고정 정책, 노란색 결정 항목은 처리 업무함에 각각 하나의 업무로 생성됩니다. 변경은 소급하지 않고 시행 시점 이후 신규 건부터 적용합니다.</p>
    </div>

    <div id="policy-tabs" role="tablist" aria-label="정책 파라미터 분류" class="mt-4 flex w-fit rounded-lg bg-gray-100 p-1">
        <button type="button" role="tab" id="policy-settlement-tab" aria-selected="true" aria-controls="policy-settlement-panel" class="rounded-lg px-3 py-2 text-2xs font-bold text-gray-600 aria-selected:bg-white aria-selected:text-gray-900">
            정산·환불
        </button>

        <button type="button" role="tab" id="policy-community-tab" aria-selected="false" aria-controls="policy-community-panel" tabindex="-1" class="rounded-lg px-3 py-2 text-2xs font-bold text-gray-600 aria-selected:bg-white aria-selected:text-gray-900">
            가입·커뮤니티
        </button>

        <button type="button" role="tab" id="policy-shopping-tab" aria-selected="false" aria-controls="policy-shopping-panel" tabindex="-1" class="rounded-lg px-3 py-2 text-2xs font-bold text-gray-600 aria-selected:bg-white aria-selected:text-gray-900">
            쇼핑·기여
        </button>

        <button type="button" role="tab" id="policy-suggestion-tab" aria-selected="false" aria-controls="policy-suggestion-panel" tabindex="-1" class="rounded-lg px-3 py-2 text-2xs font-bold text-gray-600 aria-selected:bg-white aria-selected:text-gray-900">
            제안·좋아요
        </button>

        <button type="button" role="tab" id="policy-review-tab" aria-selected="false" aria-controls="policy-review-panel" tabindex="-1" class="rounded-lg px-3 py-2 text-2xs font-bold text-gray-600 aria-selected:bg-white aria-selected:text-gray-900">
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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <section class="rounded-lg border border-gray-300 bg-white p-4">
                <h3 class="text-xl font-bold text-gray-900">
                    회원·도넛 파라미터
                </h3>

                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">

                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <label
                                for="policy-signup-reapply-hours"
                                class="text-2xs font-bold text-gray-900">
                                가입 거절 재신청
                            </label>

                            <span class="text-2xs text-gray-400">시간</span>
                        </div>

                        <input
                            type="number"
                            id="policy-signup-reapply-hours"
                            name="signup_reapply_hours"
                            value="0"
                            min="0"
                            step="1"
                            class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-3 text-2xs text-gray-900">
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <label
                                for="policy-donut-rejoin-hours"
                                class="text-2xs font-bold text-gray-900">
                                도넛 탈퇴 후 재가입
                            </label>

                            <span class="text-2xs text-gray-400">시간</span>
                        </div>

                        <input
                            type="number"
                            id="policy-donut-rejoin-hours"
                            name="donut_rejoin_hours"
                            value="72"
                            min="0"
                            step="1"
                            class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-3 text-2xs text-gray-900">
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <label
                                for="policy-account-rejoin-days"
                                class="text-2xs font-bold text-gray-900">
                                계정 탈퇴 후 재가입
                            </label>

                            <span class="text-2xs text-gray-400">일</span>
                        </div>

                        <input
                            type="number"
                            id="policy-account-rejoin-days"
                            name="account_rejoin_days"
                            value="30"
                            min="0"
                            step="1"
                            readonly
                            class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-3 text-2xs text-gray-900
                       read-only:bg-gray-100 read-only:text-gray-500">
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <label
                                for="policy-member-dormancy-months"
                                class="text-2xs font-bold text-gray-900">
                                회원 휴면
                            </label>

                            <span class="text-2xs text-gray-400">개월</span>
                        </div>

                        <input
                            type="number"
                            id="policy-member-dormancy-months"
                            name="member_dormancy_months"
                            value="12"
                            min="0"
                            step="1"
                            readonly
                            class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-3 text-2xs text-gray-900
                       read-only:bg-gray-100 read-only:text-gray-500">
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <label
                                for="policy-doti-inactive-days"
                                class="text-2xs font-bold text-gray-900">
                                도티 미접속 휴면
                            </label>

                            <span class="text-2xs text-gray-400">일</span>
                        </div>

                        <input
                            type="number"
                            id="policy-doti-inactive-days"
                            name="doti_inactive_days"
                            value="90"
                            min="0"
                            step="1"
                            readonly
                            class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-3 text-2xs text-gray-900
                       read-only:bg-gray-100 read-only:text-gray-500">
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <label
                                for="policy-dormant-donut-close-months"
                                class="text-2xs font-bold text-gray-900">
                                휴면 도넛 폐쇄
                            </label>

                            <span class="text-2xs text-gray-400">개월</span>
                        </div>

                        <input
                            type="number"
                            id="policy-dormant-donut-close-months"
                            name="dormant_donut_close_months"
                            value="12"
                            min="0"
                            step="1"
                            readonly
                            class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-3 text-2xs text-gray-900
                       read-only:bg-gray-100 read-only:text-gray-500">
                    </div>

                </div>

                <div class="mt-4 rounded-lg bg-amber-50 p-3 text-2xs">
                    <strong class="text-amber-600 font-bold">
                        도넛 카테고리 9종
                    </strong>

                    <p class="text-amber-700 font-normal">
                        인플루언서 · 챌린지 · 취미 · 게임 · 가치관/이념 · 회사/동아리 · 학교/학술 · 종교 · 기타
                    </p>
                </div>
            </section>

            <section class="border border-gray-300 rounded-lg p-4">
                <h3 class="text-xl font-bold">고정 원칙</h3>
                <ul class="mt-4 space-y-2">
                    <li>
                        <div class="flex items-center justify-between rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">폐쇄 도넛 기여포인트</span>
                                <p class="mt-1 text-2xs text-gray-400">90일 유예 · 소멸 7일 전 재안내</p>
                            </div>
                            <span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">확정</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center justify-between rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">가입·본인인증</span>
                                <p class="mt-1 text-2xs text-gray-400">국내 휴대전화 KCP 본인인증 · 만 14세 미만 가입 차단</p>
                            </div>
                            <span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">확정</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center justify-between rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">가입 거절 후 재신청</span>
                                <p class="mt-1 text-2xs text-gray-400">대기 없이 즉시 재신청 가능</p>
                            </div>
                            <span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">0시간</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center justify-between rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">휴면·폐쇄 사전 안내</span>
                                <p class="mt-1 text-2xs text-gray-400">회원 휴면과 도넛 폐쇄 모두 30일 전 안내</p>
                            </div>
                            <span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">30일</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center justify-between rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">지정 운영자 제한</span>
                                <p class="mt-1 text-2xs text-gray-400">토핑 배분·사업자 서류·정산·승계·운영자 관리 권한 부여 불가</p>
                            </div>
                            <span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">확정</span>
                        </div>
                    </li>
                </ul>
            </section>
        </div>
    </section>

    <!-- 쇼핑·기여 탭 패널 -->
    <section role="tabpanel" id="policy-shopping-panel" aria-labelledby="policy-shopping-tab" class="mt-4" hidden>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <section class="rounded-lg border border-gray-300 bg-white p-4">
                <h3 class="text-xl font-bold text-gray-900">
                    쇼핑·토핑 파라미터
                </h3>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <label
                                for="policy-base-topping-rate"
                                class="text-2xs font-bold text-gray-900">
                                기본 토핑율
                            </label>

                            <span class="text-2xs text-gray-400">%</span>
                        </div>

                        <input
                            type="number"
                            id="policy-base-topping-rate"
                            name="base_topping_rate"
                            value="3.2"
                            min="0"
                            max="100"
                            step="0.1"
                            class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-3 text-2xs text-gray-900">
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <label
                                for="policy-extra-topping-range"
                                class="text-2xs font-bold text-gray-900">
                                추가토핑율 범위
                            </label>
                        </div>

                        <input
                            type="text"
                            id="policy-extra-topping-range"
                            name="extra_topping_range"
                            value="3% ~ 50%"
                            readonly
                            class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-3 text-2xs text-gray-900
                               read-only:bg-gray-100 read-only:text-gray-500">
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <label
                                for="policy-usable-topping-valid-years"
                                class="text-2xs font-bold text-gray-900">
                                사용가능토핑 유효기간
                            </label>

                            <span class="text-2xs text-gray-400">년</span>
                        </div>

                        <input
                            type="number"
                            id="policy-usable-topping-valid-years"
                            name="usable_topping_valid_years"
                            value="1"
                            readonly
                            class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-3 text-2xs text-gray-900
                               read-only:bg-gray-100 read-only:text-gray-500">
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <label
                                for="policy-donut-contribution-valid-years"
                                class="text-2xs font-bold text-gray-900">
                                도넛 기여포인트 유효기간
                            </label>

                            <span class="text-2xs text-gray-400">년</span>
                        </div>

                        <input
                            type="number"
                            id="policy-donut-contribution-valid-years"
                            name="donut_contribution_valid_years"
                            value="2"
                            readonly
                            class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-3 text-2xs text-gray-900
                               read-only:bg-gray-100 read-only:text-gray-500">
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <label
                                for="policy-contribution-target"
                                class="text-2xs font-bold text-gray-900">
                                기여 대상
                            </label>
                        </div>

                        <input
                            type="text"
                            id="policy-contribution-target"
                            name="contribution_target"
                            value="가입한 도넛만"
                            readonly
                            class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-3 text-2xs text-gray-900
                               read-only:bg-gray-100 read-only:text-gray-500">
                    </div>

                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <label
                                for="policy-contribution-selection-point"
                                class="text-2xs font-bold text-gray-900">
                                선택 시점
                            </label>
                        </div>

                        <input
                            type="text"
                            id="policy-contribution-selection-point"
                            name="contribution_selection_point"
                            value="결제 단계 · 주문상품별"
                            readonly
                            class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-3 py-3 text-2xs text-gray-900
                               read-only:bg-gray-100 read-only:text-gray-500">
                    </div>

                </div>
            </section>

            <section class="rounded-lg border border-gray-300 bg-white p-4">
                <h3 class="text-xl font-bold text-gray-900">
                    노출·배분 원칙
                </h3>

                <ul class="mt-4 space-y-2">

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    결제·상품 범위
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    토스페이먼츠 · 국내 카드/수단 · 해외카드 미지원 · 유형 상품만 판매
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                확정
                            </span>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    추가토핑 상품
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    일반 목록에 상품 노출 · 혜택율·대상은 자격 있는 도티 추천 흐름에서만 노출
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                확정
                            </span>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    장바구니
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    기여 선택·금액을 표시하지 않고 결제에서 상품별 선택
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                확정
                            </span>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    도티 배분
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    개별·전체 균등·활동 조건 · 원천 주문 철회 기간까지 홀딩
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                확정
                            </span>
                        </div>
                    </li>

                </ul>

                <div class="mt-3 rounded-lg bg-amber-50 p-3 text-2xs">
                    <p class="text-amber-700 font-normal">
                        도트의 활동은 자동 보상이 아닙니다. 도티가 도넛 기여포인트를 배분한 경우에만 개인의 사용가능토핑이 됩니다.
                    </p>
                </div>
            </section>
        </div>
    </section>

    <section role="tabpanel" id="policy-suggestion-panel" aria-labelledby="policy-suggestion-tab" class="mt-4" hidden>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <section class="rounded-lg border border-gray-300 bg-white p-4">
                <h3 class="text-xl font-bold text-gray-900">
                    도넛·브랜드 제안
                </h3>

                <ul class="mt-4 space-y-2">

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    양방향 제안
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    브랜드와 도넛 모두 먼저 쪽지를 보내 협업 제안 가능
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                사용
                            </span>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    좋아요 목록에서 연락
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    좋아요한 브랜드·도넛을 쪽지 화면에서 확인하고 즉시 연락
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                사용
                            </span>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    서로 좋아요 표시
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    양쪽 관심이 일치하면 대화 우선순위 판단에 활용
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                사용
                            </span>
                        </div>
                    </li>
                </ul>
            </section>

            <section class="rounded-lg border border-gray-300 bg-white p-4">
                <h3 class="text-xl font-bold text-gray-900">
                    권한·노출 원칙
                </h3>

                <ul class="mt-4 space-y-2">

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    대화 당사자
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    해당 브랜드와 해당 도넛 관리자만 협의 내용 확인
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                고정
                            </span>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    프로토타입 탐색 목록
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    현재 5개 예시는 전체 목록 구조를 설명하기 위한 샘플
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-amber-50 px-2 py-1 text-2xs font-bold text-amber-700">
                                예시 데이터
                            </span>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    쪽지 보관 기간
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    협업 종료 후 대화·첨부파일을 얼마나 보관할지 결정 필요
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-amber-50 px-2 py-1 text-2xs font-bold text-amber-700">
                                결정 필요
                            </span>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    반복 제안·차단 기준
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    동일 상대 재문의 간격, 신고 누적, 수신 거부 규칙 결정 필요
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-amber-50 px-2 py-1 text-2xs font-bold text-amber-700">
                                결정 필요
                            </span>
                        </div>
                    </li>
                </ul>
            </section>
        </div>
    </section>

    <!-- 검토 통제 탭 패널 -->
    <section role="tabpanel" id="policy-review-panel" aria-labelledby="policy-review-tab" class="mt-4" hidden>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <section class="rounded-lg border border-gray-300 bg-white p-4">
                <h3 class="text-xl font-bold text-gray-900">
                    자동 기록
                </h3>

                <ul class="mt-4 space-y-2">

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    주문·결제·토핑 발생 기록
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    발생 사실과 로그 근거를 변경 불가능한 이력으로 저장
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                자동
                            </span>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    규칙 위반·불일치 표시
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    이상 여부를 알려주되 금액과 지급 상태는 바꾸지 않음
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                자동
                            </span>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    감사 로그
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    누가 언제 무엇을 확인·승인했는지 기록
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                자동
                            </span>
                        </div>
                    </li>
                </ul>
            </section>

            <section class="rounded-lg border border-gray-300 bg-white p-4">
                <h3 class="text-xl font-bold text-gray-900">
                    담당자 판단
                </h3>

                <ul class="mt-4 space-y-2">

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    로그 정정 승인
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    로그 화면에서 직접 수정하지 않고 근거와 승인 기록을 별도 보관
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                담당자
                            </span>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    정산 확정·실제 지급
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    사업자·계좌·최소금액·차감을 확인한 담당자가 수행
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                담당자
                            </span>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    정책 시행
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    사유·적용 시점 기록 후 신규 건부터 적용
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                담당자
                            </span>
                        </div>
                    </li>
                </ul>
            </section>

            <section class="rounded-lg border border-gray-300 bg-white p-4">
                <h3 class="text-xl font-bold text-gray-900">
                    확정 SLA·추가 통제값
                </h3>

                <ul class="mt-4 space-y-2">

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    고액 정산 2인 확인 기준
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    금액 임계값과 작성자·확인자 분리 여부 결정 필요
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-amber-50 px-2 py-1 text-2xs font-bold text-amber-700">
                                결정 필요
                            </span>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    감사 로그·증빙 보관 기간
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    업무 유형별 보관 연수와 파기 방식 결정 필요
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-amber-50 px-2 py-1 text-2xs font-bold text-amber-700">
                                결정 필요
                            </span>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    상품·주문·배송·환불·결제·계정
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    각 3영업일 · 토핑 적립/사용 오류는 5영업일
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                확정
                            </span>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    커뮤니티 분쟁
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    도티 48시간 → 플랫폼 5영업일
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                확정
                            </span>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    토핑 부여 형평성 이의
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    도티 48시간 · 플랫폼 불관여
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                확정
                            </span>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    내려받기 개인정보 마스킹
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    계좌·연락처·IP의 역할별 노출 범위 결정 필요
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-amber-50 px-2 py-1 text-2xs font-bold text-amber-700">
                                결정 필요
                            </span>
                        </div>
                    </li>
                </ul>
            </section>

            <section class="rounded-lg border border-gray-300 bg-white p-4">
                <h3 class="text-xl font-bold text-gray-900">
                    변경 적용 원칙
                </h3>

                <ul class="mt-4 space-y-2">

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    정책 변경 사전 공지
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    서비스정책 7일 · 회원 불리 변경/토핑 유효기간 30일
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                확정
                            </span>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    소급 적용 금지
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    변경 사유와 시행 시점을 남기고 신규 건부터 적용
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                확정
                            </span>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                            <div>
                                <span class="block text-2xs font-bold text-gray-900">
                                    담당자 검토 기록
                                </span>

                                <p class="mt-1 text-2xs text-gray-400">
                                    확인 결과가 원본 거래·로그·정산 상태를 직접 변경하지 않음
                                </p>
                            </div>

                            <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                확정
                            </span>
                        </div>
                    </li>
                </ul>
            </section>
        </div>
    </section>
</section>

<!-- 정책 변경 이력 모달 -->
<div id="policy-history-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="policy-history-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="policy-history-modal-container" role="dialog" aria-modal="true" aria-labelledby="policy-history-modal-title" class="relative z-10 w-full max-w-120 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div id="policy-history-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="policy-history-modal-title" class="text-sm font-bold text-gray-900">
                정책 변경 이력
            </h3>

            <button type="button" id="policy-history-modal-close" aria-label="정책 변경 이력 모달 닫기" class="flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="policy-history-modal-body" class="p-4">
            <div class="rounded-lg bg-gray-50 p-3">
                <span class="block text-2xs text-gray-400 font-normal">2026.08.19 · 운영정책팀</span>
                <p class="text-2xs text-gray-900 font-normal">로그 정정·정산 지급·분쟁 판단을 검토 통제로 구분하고 자동 확정 동작을 제외</p>
            </div>

            <div class="mt-3 rounded-lg bg-gray-50 p-3">
                <span class="block text-2xs text-gray-400 font-normal">2026.08.01 · 정산팀</span>
                <p class="text-2xs text-gray-900 font-normal">도넛 정산일 익월 12일, 최소 50,000원 기준 확인</p>
            </div>

            <div class="mt-3 rounded-lg bg-gray-50 p-3">
                <span class="block text-2xs text-gray-400 font-normal">2026.07.15 · 커뮤니티팀</span>
                <p class="text-2xs text-gray-900 font-normal">도티의 도트 운영자 권한 감사 범위 추가</p>
            </div>

            <div class="mt-3 rounded-lg bg-gray-50 p-3">
                <span class="block text-2xs text-gray-400 font-normal">2026.07.01 · 입점팀</span>
                <p class="text-2xs text-gray-900 font-normal">상품 검수 시 배송그룹 필수 검증 추가</p>
            </div>
        </div>

        <div id="policy-history-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="policy-history-modal-cancel" class="rounded-lg border border-gray-300 bg-white font-bold text-gray-900 px-4 py-3">
                닫기
            </button>
        </div>
    </div>
</div>

<!-- 정책 변경 예약 모달 -->
<div id="policy-schedule-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="policy-schedule-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="policy-schedule-modal-container" role="dialog" aria-modal="true" aria-labelledby="policy-schedule-modal-title" class="relative z-10 w-full max-w-120 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div id="policy-schedule-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="policy-schedule-modal-title" class="text-sm font-bold text-gray-900">
                정책 변경 예약
            </h3>

            <button type="button" id="policy-schedule-modal-close" aria-label="정책 변경 예약 모달 닫기" class="flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="policy-schedule-modal-body" class="p-4">
            <div class="bg-red-50 rounded-lg text-2xs p-3">
                <span class="text-red-600 font-bold">영향 범위 확인</span>
                <p class="mt-1 text-red-700 font-normal">진행 중 신청과 완료 건을 소급 변경하지 않고 시행 시점 이후 신규 건부터 적용합니다.</p>
            </div>

            <div class="mt-3">
                <label class="block text-2xs font-bold">적용 시점</label>
                <select class="mt-1 w-full rounded-lg">
                    <option>다음 달 1일 00:00</option>
                    <option>다음 날 00:00</option>
                </select>
            </div>

            <div class="mt-3">
                <label class="block text-2xs font-bold">변경 사유</label>
                <textarea class="mt-1 rounded-lg"></textarea>
            </div>
        </div>

        <div id="policy-schedule-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="policy-schedule-modal-cancel" class="rounded-lg border border-gray-300 bg-white font-bold text-gray-900 px-4 py-3">
                취소
            </button>
            <button type="button" class="rounded-lg border border-transparent bg-amber-300 font-bold px-4 py-3">
                변경 예약
            </button>
        </div>
    </div>
</div>

<script>
    // 탭 전환
    const $tabs = $('#policy-tabs [role="tab"]');
    const $panels = $('[role="tabpanel"]');

    $tabs.on('click', function() {
        const panelId = $(this).attr('aria-controls');

        $tabs
            .attr('aria-selected', 'false')
            .attr('tabindex', '-1')

        $(this)
            .attr('aria-selected', 'true')
            .attr('tabindex', '0')

        $panels.prop('hidden', true);

        $('#' + panelId).prop('hidden', false);
    });

    // 변경 이력 모달 열기 닫기
    $('.policy-history-modal-open').on('click', function() {
        $('#policy-history-modal').prop('hidden', false);
    });

    $('#policy-history-modal-close, #policy-history-modal-cancel, #policy-history-modal-backdrop').on('click', function() {
        $('#policy-history-modal').prop('hidden', true);
    });

    // 변경 예약 모달 열기 닫기
    $('.policy-schedule-modal-open').on('click', function() {
        $('#policy-schedule-modal').prop('hidden', false);
    });

    $('#policy-schedule-modal-close, #policy-schedule-modal-cancel, #policy-schedule-modal-backdrop').on('click', function() {
        $('#policy-schedule-modal').prop('hidden', true);
    });
</script>

<?php
require_once '../../admin.tail.php';

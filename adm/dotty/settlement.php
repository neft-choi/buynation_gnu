<?php
$sub_menu = '760200';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '정산 관리';
require_once '../admin.head.php';
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="text-gray-400">테니스 커뮤니티의 정산 가능 금액과 전용 입금 계좌를 관리합니다.</p>

        <button type="button" class="settlement-request-modal-open shrink-0 border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
            <span>정산하기</span>
        </button>
    </div>

    <div class="mt-4 rounded-lg bg-gray-900 p-6 text-white">
        <div class="flex flex-col gap-4 pc:flex-row pc:items-center pc:justify-between">
            <div>
                <h3 class="text-2xs text-gray-300">
                    현재 정산 가능한 금액
                </h3>

                <div class="mt-3 flex items-end gap-2">
                    <p class="text-3xl font-bold">
                        680,000
                    </p>

                    <span class="mb-1 font-bold text-gray-200">
                        원
                    </span>
                </div>

                <p class="mt-3 text-2xs text-gray-300">
                    사업자 인증 완료 · 정산 신청 가능
                </p>
            </div>

            <button type="button" class="settlement-request-modal-open shrink-0 rounded-lg bg-amber-300 px-4 py-3 text-sm font-bold text-gray-900">
                정산 신청
            </button>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 gap-3 pc:grid-cols-4">
        <div class="rounded-lg border border-gray-300 bg-white p-5">
            <h3 class="text-sm text-gray-500">
                정산 가능
            </h3>

            <div class="mt-4 flex items-end gap-1">
                <p class="text-2xl font-bold text-gray-900">
                    680,000
                </p>

                <span class="mb-1 font-bold text-gray-700">
                    원
                </span>
            </div>

            <p class="mt-3 text-2xs text-teal-600">
                최소 신청 금액 50,000원
            </p>
        </div>

        <div class="rounded-lg border border-gray-300 bg-white p-5">
            <h3 class="text-sm text-gray-500">
                확정 대기
            </h3>

            <div class="mt-4 flex items-end gap-1">
                <p class="text-2xl font-bold text-gray-900">
                    214,600
                </p>

                <span class="mb-1 font-bold text-gray-700">
                    원
                </span>
            </div>

            <p class="mt-3 text-2xs text-blue-600">
                철회 가능 기간 이후 반영
            </p>
        </div>

        <div class="rounded-lg border border-gray-300 bg-white p-5">
            <h3 class="text-sm text-gray-500">
                담당자 검토 중
            </h3>

            <div class="mt-4 flex items-end gap-1">
                <p class="text-2xl font-bold text-gray-900">
                    0
                </p>

                <span class="mb-1 font-bold text-gray-700">
                    원
                </span>
            </div>

            <p class="mt-3 text-2xs text-blue-600">
                익월 12일 기준 · 담당자 확인
            </p>
        </div>

        <div class="rounded-lg border border-gray-300 bg-white p-5">
            <h3 class="text-sm text-gray-500">
                현금화 불가 토핑
            </h3>

            <div class="mt-4 flex items-end gap-1">
                <p class="text-2xl font-bold text-gray-900">
                    0
                </p>

                <span class="mb-1 font-bold text-gray-700">
                    T
                </span>
            </div>

            <p class="mt-3 text-2xs text-gray-500">
                쇼핑 포인트로 사용 가능
            </p>
        </div>
    </div>


    <div class="mt-4 flex flex-col pc:flex-row pc:items-center pc:justify-between gap-3 rounded-lg bg-blue-50 px-4 py-3">
        <p class="text-gray-700">
            <span class="font-bold text-blue-600">입금 계좌</span>
            <span class="ml-2">신한은행 · 110-***-123456 · 테니스 커뮤니티 주식회사</span>
        </p>

        <button type="button" id="settlement-account-modal-open" class="shrink-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
            계좌 변경
        </button>
    </div>


    <div class="mt-4 grid grid-cols-1 gap-4 pc:grid-cols-5">
        <section aria-labelledby="settlement-list-title" class="overflow-hidden rounded-lg border border-gray-300 bg-white pc:col-span-3">
            <header class="flex items-center justify-between gap-3 border-b border-gray-300 p-4">
                <div>
                    <h3 id="settlement-list-title" class="text-lg font-bold text-gray-900">
                        정산 내역
                    </h3>

                    <p class="mt-2 text-2xs text-gray-400">
                        도넛별 신청·지급 내역이며 다른 도넛과 합산되지 않습니다.
                    </p>
                </div>

                <button type="button" class="shrink-0 text-2xs text-gray-500">
                    내역 내려받기
                </button>
            </header>

            <div class="overflow-x-auto">
                <table class="border-collapse min-w-180 w-full table-fixed text-left">
                    <colgroup>
                        <col class="w-[20%]">
                        <col class="w-[17%]">
                        <col class="w-[17%]">
                        <col class="w-[17%]">
                        <col class="w-[16%]">
                        <col class="w-[13%]">
                    </colgroup>

                    <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                        <tr>
                            <th scope="col">정산번호</th>
                            <th scope="col">대상 월</th>
                            <th scope="col">신청일</th>
                            <th scope="col">기준일</th>
                            <th scope="col">상태</th>
                            <th scope="col" class="text-right">정산 금액</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-900 font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:whitespace-nowrap [&_td]:px-4 [&_td]:py-3">
                        <tr>
                            <td>SET-260712-001</td>
                            <td>2026.06</td>
                            <td>2026.07.01</td>
                            <td>2026.07.12</td>
                            <td>
                                <span class="rounded-lg bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                                    지급 완료
                                </span>
                            </td>
                            <td class="text-right font-bold">530,000원</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section aria-labelledby="settlement-guide-title" class="rounded-lg border border-gray-300 bg-white p-5 pc:col-span-2">
            <h3 id="settlement-guide-title" class="text-xl font-bold text-gray-900">
                정산 기준
            </h3>

            <div class="mt-4 grid grid-cols-1 gap-2 pc:grid-cols-3">
                <div class="rounded-lg bg-gray-50 p-3">
                    <span class="font-bold text-gray-900">월말 마감</span>
                    <p class="mt-2 text-2xs text-gray-500">
                        매월 말일 23:59 기준으로 정산 신청 가능 금액을 집계합니다.
                    </p>
                </div>

                <div class="rounded-lg bg-gray-50 p-3">
                    <span class="font-bold text-gray-900">익월 12일 기준</span>
                    <p class="mt-2 text-2xs text-gray-500">
                        담당자가 사업자·계좌·증빙을 확인한 뒤 외부 절차에서 직접 지급합니다.
                    </p>
                </div>

                <div class="rounded-lg bg-gray-50 p-3">
                    <span class="font-bold text-gray-900">최소 5만원</span>
                    <p class="mt-2 text-2xs text-gray-500">
                        정산 가능 금액이 50,000원 이상일 때 신청할 수 있습니다.
                    </p>
                </div>
            </div>

            <div class="mt-3 rounded-lg bg-amber-50 p-3">
                <p class="text-2xs text-amber-700">
                    이 화면의 신청은 검토 자료를 전달하는 기능입니다. 자동 확정이나 자동 지급을 실행하지 않습니다.
                </p>
            </div>
        </section>
    </div>
</section>

<!-- 정산 신청 모달 -->
<div id="settlement-request-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="settlement-request-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="settlement-request-modal-container" role="dialog" aria-modal="true" aria-labelledby="settlement-request-modal-title" class="relative z-10 max-h-[90vh] w-full max-w-160 overflow-y-auto rounded-lg bg-white">
        <div id="settlement-request-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="settlement-request-modal-title" class="text-lg font-bold text-gray-900">
                도넛 정산 신청
            </h3>

            <button type="button" id="settlement-request-modal-close" aria-label="정산 신청 모달 닫기" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="settlement-request-modal-body" class="p-4">
            <div class="rounded-lg bg-gray-900 p-6 text-white">
                <span class="text-2xs text-gray-300">신청 금액</span>

                <div class="mt-3 flex items-end gap-2">
                    <p class="text-3xl font-bold">680,000</p>
                    <span class="mb-1 font-bold text-gray-200">원</span>
                </div>
            </div>

            <dl class="mt-4 overflow-hidden rounded-lg border border-gray-300 text-2xs [&>div]:grid [&>div]:grid-cols-[120px_1fr]">
                <div class="border-b border-gray-300">
                    <dt class="bg-gray-50 p-3 text-gray-500">정산 도넛</dt>
                    <dd class="p-3 font-bold text-gray-900">테니스 커뮤니티</dd>
                </div>

                <div class="border-b border-gray-300">
                    <dt class="bg-gray-50 p-3 text-gray-500">대상 기간</dt>
                    <dd class="p-3 font-bold text-gray-900">2026.07</dd>
                </div>

                <div class="border-b border-gray-300">
                    <dt class="bg-gray-50 p-3 text-gray-500">입금 계좌</dt>
                    <dd class="p-3 font-bold text-gray-900">신한은행 · 110-***-123456</dd>
                </div>

                <div class="border-b border-gray-300">
                    <dt class="bg-gray-50 p-3 text-gray-500">예금주</dt>
                    <dd class="p-3 font-bold text-gray-900">테니스 커뮤니티 주식회사</dd>
                </div>

                <div>
                    <dt class="bg-gray-50 p-3 text-gray-500">담당자 검토 기준일</dt>
                    <dd class="p-3 font-bold text-gray-900">2026.10.12</dd>
                </div>
            </dl>

            <div class="mt-4 rounded-lg bg-amber-50 p-3">
                <p class="text-2xs text-gray-700">
                    신청 자료는 플랫폼 검토함에 전달됩니다. 정산 확정과 실제 지급은 담당자가 증빙 확인 후 외부 절차에서 직접 진행합니다.
                </p>
            </div>
        </div>

        <div id="settlement-request-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="settlement-request-modal-cancel" class="rounded-lg border border-gray-300 bg-white px-4 py-3 font-bold text-gray-900">
                취소
            </button>

            <button type="button" class="rounded-lg bg-amber-300 px-4 py-3 font-bold text-gray-900">
                680,000원 정산 신청
            </button>
        </div>
    </div>
</div>

<!-- 정산 입금 계좌 변경 모달 -->
<div id="settlement-account-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="settlement-account-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="settlement-account-modal-container" role="dialog" aria-modal="true" aria-labelledby="settlement-account-modal-title" class="relative z-10 w-full max-w-160 rounded-lg bg-white">
        <div id="settlement-account-modal-header" class="flex items-center justify-between border-b border-gray-300 p-4">
            <h3 id="settlement-account-modal-title" class="text-lg font-bold text-gray-900">
                정산 입금 계좌 변경
            </h3>

            <button type="button" id="settlement-account-modal-close" aria-label="정산 입금 계좌 변경 모달 닫기" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <form id="settlement-account-modal-form" class="p-4">
            <div class="rounded-lg bg-amber-50 p-3">
                <p class="text-2xs text-gray-700">
                    계좌는 현재 선택한 테니스 커뮤니티 도넛에만 적용됩니다.
                </p>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-4 pc:grid-cols-2">
                <div>
                    <label for="settlement-account-modal-bank" class="font-bold text-gray-900">
                        은행
                    </label>

                    <input type="text" id="settlement-account-modal-bank" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3" value="신한은행">
                </div>

                <div>
                    <label for="settlement-account-modal-number" class="font-bold text-gray-900">
                        계좌번호
                    </label>

                    <input type="text" id="settlement-account-modal-number" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3" value="110-***-123456">
                </div>
            </div>

            <div class="mt-4">
                <label for="settlement-account-modal-holder" class="font-bold text-gray-900">
                    예금주
                </label>

                <input type="text" id="settlement-account-modal-holder" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3" value="테니스 커뮤니티 주식회사">
            </div>
        </form>

        <div id="settlement-account-modal-footer" class="flex justify-end gap-2 border-t border-gray-300 p-4">
            <button type="button" id="settlement-account-modal-cancel" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-bold text-gray-900">
                취소
            </button>

            <button type="submit" form="settlement-account-modal-form" class="rounded-lg bg-amber-300 px-4 py-3 text-sm font-bold text-gray-900">
                계좌 저장
            </button>
        </div>
    </div>
</div>

<script>
    // 정산 신청 모달 열기 닫기
    $('.settlement-request-modal-open').on('click', function() {
        $('#settlement-request-modal').prop('hidden', false);
    });

    $('#settlement-request-modal-close, #settlement-request-modal-cancel, #settlement-request-modal-backdrop').on('click', function() {
        $('#settlement-request-modal').prop('hidden', true);
    });

    // 정산 입금 계좌 변경 모달 열기 닫기
    $('#settlement-account-modal-open').on('click', function() {
        $('#settlement-account-modal').prop('hidden', false);
    });

    $('#settlement-account-modal-close, #settlement-account-modal-cancel, #settlement-account-modal-backdrop').on('click', function() {
        $('#settlement-account-modal').prop('hidden', true);
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

<?php
$sub_menu = '950400';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '결제 대사';
require_once '../../admin.head.php';
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="text-gray-600 font-normal">PG 승인·취소·환불과 주문 상태를 대조하고 불일치 판단 근거를 기록합니다.</p>

        <a class="shrink-0 w-fit border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
            <span>검토 기록</span>
        </a>
    </div>

    <div class="mt-4 flex items-center gap-3 bg-amber-50 rounded-lg text-2xs p-3">
        <span class="text-amber-600 font-bold">자동 변경 없음</span>
        <p class="text-amber-700 font-normal">시스템은 비교 결과만 표시합니다. 불일치 해소와 환불 판단은 결제사 증빙을 확인한 뒤 담당자 검토 기록으로 남깁니다.</p>
    </div>

    <section class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <div class="overflow-x-auto">
            <table class="border-collapse min-w-250 w-full table-fixed text-left">
                <caption class="sr-only">결제 대사 목록</caption>

                <colgroup>
                    <col class="w-[20%]">
                    <col class="w-[20%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                </colgroup>

                <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">결제번호</th>
                        <th scope="col">주문번호</th>
                        <th scope="col">구분</th>
                        <th scope="col">금액</th>
                        <th scope="col">PG</th>
                        <th scope="col">최근 확인</th>
                        <th scope="col">대사 상태</th>
                        <th scope="col">검토</th>
                    </tr>
                </thead>

                <tbody class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:whitespace-nowrap [&_td]:px-4 [&_td]:py-3">
                    <tr>
                        <td>
                            <span class="font-bold">PAY-260811-0881</span>
                        </td>
                        <td>
                            <span>20260811001031</span>
                        </td>
                        <td>
                            <span>결제</span>
                        </td>
                        <td>
                            <span>89,000원</span>
                        </td>
                        <td>
                            <span>토스페이먼츠</span>
                        </td>
                        <td>
                            <span>11:42</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 대사 일치</span>
                        </td>
                        <td>
                            <span>-</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="font-bold">PAY-260810-0732</span>
                        </td>
                        <td>
                            <span>20260810009871</span>
                        </td>
                        <td>
                            <span>부분취소</span>
                        </td>
                        <td>
                            <span>42,000원</span>
                        </td>
                        <td>
                            <span>KG이니시스</span>
                        </td>
                        <td>
                            <span>18:48</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-red-100 text-2xs font-bold text-red-600 px-2 py-1">● 불일치</span>
                        </td>
                        <td>
                            <span>
                                <button type="button" class="payment-audit-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                    기록
                                </button>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="font-bold">PAY-260809-0614</span>
                        </td>
                        <td>
                            <span>20260809007112</span>
                        </td>
                        <td>
                            <span>환불</span>
                        </td>
                        <td>
                            <span>36,000원</span>
                        </td>
                        <td>
                            <span>토스페이먼츠</span>
                        </td>
                        <td>
                            <span>09:22</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">● 심사 대기</span>
                        </td>
                        <td>
                            <span>
                                <button type="button" class="payment-audit-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                    기록
                                </button>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<!-- 담당자 검토 기록 모달 -->
<div id="payment-audit-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="payment-audit-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="payment-audit-modal-container" role="dialog" aria-modal="true" aria-labelledby="payment-audit-modal-title" class="relative z-10 w-full max-w-150 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div id="payment-audit-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="payment-audit-modal-title" class="text-sm font-bold text-gray-900">
                담당자 검토 기록
            </h3>

            <button type="button" id="payment-audit-modal-close" aria-label="클레임·분쟁 상세 모달 닫기" class="flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="payment-audit-modal-body" class="p-4">
            <div class="bg-amber-50 rounded-lg text-2xs p-3">
                <span class="text-amber-600 font-bold">상태·금액 변경 없음</span>
                <p class="mt-1 text-amber-700 font-normal">이 기록은 담당자의 확인 근거만 남기며 로그 잔액, 결제 대사, 정산 지급 상태를 자동으로 바꾸지 않습니다.</p>
            </div>

            <div class="mt-4 overflow-hidden rounded-lg border border-gray-300">
                <dl class="text-left text-2xs [&>div+div]:border-t [&>div+div]:border-gray-200">
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">구분</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">결제</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">대상</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">PAY-260810-0732</dd>
                    </div>
                </dl>
            </div>

            <div class="mt-3">
                <label class="block text-2xs font-bold">검토 결과</label>
                <select class="mt-1 w-full rounded-lg">
                    <option>확인 완료</option>
                    <option>추가 확인</option>
                    <option>보류</option>
                </select>
            </div>

            <div class="mt-3">
                <label class="block text-2xs font-bold">검토 메모</label>
                <textarea class="mt-1 rounded-lg"></textarea>
            </div>
        </div>

        <div id="payment-audit-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="payment-audit-modal-cancel" class="rounded-lg border border-gray-300 bg-white font-bold text-gray-900 px-4 py-3">
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
    $('.payment-audit-modal-open').on('click', function() {
        $('#payment-audit-modal').prop('hidden', false);
    });

    $('#payment-audit-modal-close, #payment-audit-modal-cancel, #payment-audit-modal-backdrop').on('click', function() {
        $('#payment-audit-modal').prop('hidden', true);
    });
</script>

<?php
require_once '../../admin.tail.php';

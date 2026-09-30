<?php
$sub_menu = '960200';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '토핑 발생 로그';
require_once '../../admin.head.php';
?>

<section>
    <h2 class="sr-only">토핑 발생 로그</h2>

    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="text-gray-600 font-normal">발생 근거와 잔액을 대조하는 읽기 전용 화면입니다.</p>

        <div class="flex items-center gap-2">
            <a href="<?php echo G5_ADMIN_URL . '/platform/money/manual_review.php'; ?>" class="shrink-0 w-fit border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
                <span>검토 기록</span>
            </a>
            <button type="button" class="shrink-0 w-fit border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
                <span>로그 내려받기</span>
            </button>
        </div>
    </div>

    <section class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
        <div class="border border-gray-300 rounded-lg bg-gray-50 p-3">
            <span class="block text-2xs text-gray-900 font-bold">로그 값은 자동 생성·변경 불가</span>
            <p class="mt-1 text-2xs text-gray-400 font-normal">주문과 지급 같은 발생 사실을 기록하되 관리자가 금액이나 잔액을 직접 수정할 수 없습니다.</p>
        </div>
        <div class="border border-gray-300 rounded-lg bg-gray-50 p-3">
            <span class="block text-2xs text-gray-900 font-bold">담당자 검토는 로그만 생성</span>
            <p class="mt-1 text-2xs text-gray-400 font-normal">오류 의심 건은 근거를 대조하고 검토 메모를 남기며 실제 정정은 별도 승인 절차로 처리합니다.</p>
        </div>
    </section>

    <section class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <div class="overflow-x-auto">
            <table class="border-collapse min-w-300 w-full table-fixed text-left">
                <caption class="sr-only">토핑 발생 로그 목록</caption>

                <colgroup>
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[22%]">
                    <col class="w-[8%]">
                </colgroup>

                <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">로그 번호</th>
                        <th scope="col">일시</th>
                        <th scope="col">소유자</th>
                        <th scope="col">발생 근거</th>
                        <th scope="col">버킷</th>
                        <th scope="col">변동</th>
                        <th scope="col">잔액</th>
                        <th scope="col">정책 메모</th>
                        <th scope="col">검토</th>
                    </tr>
                </thead>

                <tbody class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:px-4 [&_td]:py-3">
                    <tr>
                        <td>
                            <span class="block font-bold">LED-260811-1841</span>
                        </td>
                        <td>
                            <span>2026.08.11 14:22</span>
                        </td>
                        <td>
                            <span class="block">DN-00203</span>
                        </td>
                        <td>
                            <span>주문 20260811001234</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-blue-100 text-2xs font-bold text-blue-600 px-2 py-1">● 최초 심사 잠정</span>
                        </td>
                        <td>
                            <span>+2,304T</span>
                        </td>
                        <td>
                            <span>412,000T</span>
                        </td>
                        <td>
                            <span>최초 사업자 심사 중 · 승인 시 현금화 가능</span>
                        </td>
                        <td>
                            <button type="button" class="payment-audit-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                기록
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">LED-260811-1839</span>
                        </td>
                        <td>
                            <span>2026.08.11 13:08</span>
                        </td>
                        <td>
                            <span class="block">DN-00417</span>
                        </td>
                        <td>
                            <span>주문 20260811001187</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 현금화 가능</span>
                        </td>
                        <td>
                            <span>+1,152T</span>
                        </td>
                        <td>
                            <span>1,284,000T</span>
                        </td>
                        <td>
                            <span>사업자 승인 도넛</span>
                        </td>
                        <td>
                            <button type="button" class="payment-audit-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                기록
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">LED-260810-1722</span>
                        </td>
                        <td>
                            <span>2026.08.10 18:31</span>
                        </td>
                        <td>
                            <span class="block">DN-00688</span>
                        </td>
                        <td>
                            <span>주문 20260810009871</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">● 전환 심사 잠금</span>
                        </td>
                        <td>
                            <span>+5,888T</span>
                        </td>
                        <td>
                            <span>184,000T</span>
                        </td>
                        <td>
                            <span>비사업자→사업자 전환 심사 중 · 현금화 불가</span>
                        </td>
                        <td>
                            <button type="button" class="payment-audit-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                기록
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">LED-260809-1603</span>
                        </td>
                        <td>
                            <span>2026.08.09 10:04</span>
                        </td>
                        <td>
                            <span class="block">DN-00512</span>
                        </td>
                        <td>
                            <span>주문 20260809006102</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">● 쇼핑 전용</span>
                        </td>
                        <td>
                            <span>+3,200T</span>
                        </td>
                        <td>
                            <span>211,000T</span>
                        </td>
                        <td>
                            <span>비사업자 · 쇼핑 전용</span>
                        </td>
                        <td>
                            <button type="button" class="payment-audit-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                기록
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">LED-260808-1511</span>
                        </td>
                        <td>
                            <span>2026.08.08 15:44</span>
                        </td>
                        <td>
                            <span class="block">DOT-48102</span>
                        </td>
                        <td>
                            <span>주문 적립</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-blue-100 text-2xs font-bold text-blue-600 px-2 py-1">● 도트 토핑</span>
                        </td>
                        <td>
                            <span>+720T</span>
                        </td>
                        <td>
                            <span>12,400T</span>
                        </td>
                        <td>
                            <span>도트 사용 가능 토핑</span>
                        </td>
                        <td>
                            <button type="button" class="payment-audit-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                기록
                            </button>
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
                        <dd class="flex-1 font-bold text-gray-900 p-3">로그</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">대상</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">LED-260811-1841</dd>
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
                기록 저장
            </button>
        </div>
    </div>
</div>

<script>
    $('.payment-audit-modal-open').on('click', function() {
        $('#payment-audit-modal').prop('hidden', false);
    });

    $('#payment-audit-modal-close, #payment-audit-modal-cancel, #payment-audit-modal-backdrop').on('click', function() {
        $('#payment-audit-modal').prop('hidden', true);
    });
</script>

<?php
require_once '../../admin.tail.php';

<?php
$sub_menu = '960400';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '정산 검토';
require_once '../../admin.head.php';
?>

<section>
    <header class="flex items-center justify-between">
        <p class="text-gray-400 font-normal">집계 결과를 조회하고 사업자·계좌·현금화 자격·최소금액을 사람이 확인합니다.</p>

        <div class="inline-flex items-center gap-2">
            <button type="button" class="w-fit border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
                정산 기준
            </button>
            <button type="button" class="w-fit border border-transparent rounded-lg bg-amber-300 text-gray-900 font-bold px-3 py-2">
                검토 기록
            </button>
        </div>
    </header>

    <div class="mt-4 flex items-center gap-3 bg-amber-50 rounded-lg text-2xs p-3">
        <strong class="text-amber-600 font-bold">자동 확정·지급 없음</strong>
        <p class="text-amber-700 font-normal">이 화면은 집계와 증빙 확인을 돕고 검토 기록만 남깁니다. 실제 지급 실행과 완료 처리는 별도 금융 절차에서 직접 진행합니다.</p>
    </div>

    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-2">
        <div class="border border-gray-300 rounded-lg p-3">
            <h3 class="text-2xs text-gray-900 font-bold">브랜드 정산</h3>
            <p class="mt-1 text-2xs text-gray-400 font-normal">판매완료·취소·환불·클레임을 반영한 집계값과 브랜드별 계약 지급일을 대조합니다.</p>
        </div>

        <div class="border border-gray-300 rounded-lg p-3">
            <h3 class="text-2xs text-gray-900 font-bold">도넛 정산</h3>
            <p class="mt-1 text-2xs text-gray-400 font-normal">월말 마감 · 익월 12일 지급 · 최소 50,000원. 도넛별 사업자와 계좌를 검증합니다.</p>
        </div>
    </div>

    <form class="mt-4">
        <fieldset class="flex items-center gap-2">
            <legend class="sr-only">정산 구분 필터</legend>

            <div>
                <input
                    type="radio"
                    id="filter-all"
                    name="settlement-filter"
                    value="all"
                    class="peer sr-only"
                    checked>
                <label
                    for="filter-all"
                    class="block cursor-pointer rounded-full border border-gray-300 px-3 py-2
                       peer-checked:border-gray-900
                       peer-checked:bg-gray-900
                       peer-checked:text-white">
                    전체
                </label>
            </div>

            <div>
                <input
                    type="radio"
                    id="filter-brand"
                    name="settlement-filter"
                    value="brand"
                    class="peer sr-only">
                <label
                    for="filter-brand"
                    class="block cursor-pointer rounded-full border border-gray-300 px-3 py-2
                       peer-checked:border-gray-900
                       peer-checked:bg-gray-900
                       peer-checked:text-white">
                    브랜드
                </label>
            </div>

            <div>
                <input
                    type="radio"
                    id="filter-donut"
                    name="settlement-filter"
                    value="donut"
                    class="peer sr-only">
                <label
                    for="filter-donut"
                    class="block cursor-pointer rounded-full border border-gray-300 px-3 py-2
                       peer-checked:border-gray-900
                       peer-checked:bg-gray-900
                       peer-checked:text-white">
                    도넛
                </label>
            </div>
        </fieldset>
    </form>

    <div class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <div class="overflow-x-auto">
            <table class="border-collapse min-w-300 w-full table-fixed text-left">
                <caption class="sr-only">정산 검토 대상 목록</caption>

                <colgroup>
                    <col class="w-[14%]">
                    <col class="w-[8%]">
                    <col class="w-[8%]">
                    <col class="w-[8%]">
                    <col class="w-[8%]">
                    <col class="w-[8%]">
                    <col class="w-[14%]">
                    <col class="w-[14%]">
                    <col class="w-[10%]">
                    <col class="w-[8%]">
                </colgroup>

                <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">정산번호 / 대상</th>
                        <th scope="col">구분</th>
                        <th scope="col">기간</th>
                        <th scope="col">집계액</th>
                        <th scope="col">차감</th>
                        <th scope="col">검토 금액</th>
                        <th scope="col">계좌</th>
                        <th scope="col">기준</th>
                        <th scope="col">상태</th>
                        <th scope="col">확인</th>
                    </tr>
                </thead>

                <tbody id="settlement-list" class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_th]:px-4 [&_th]:py-3 [&_td]:px-4 [&_td]:py-3">
                    <tr data-type="brand">
                        <th scope="row">
                            <span class="block font-bold">그린테이블</span>
                            <span class="block text-gray-400 font-bold">SET-B-GT-2607</span>
                        </th>
                        <td>브랜드</td>
                        <td>2026.07</td>
                        <td>5,138,000원/T</td>
                        <td>571,600</td>
                        <td>4,566,400</td>
                        <td>○○ 123-****-567890</td>
                        <td>2026.08.12</td>
                        <td>
                            <span class="rounded-full bg-blue-100 text-2xs font-bold text-blue-600 px-2 py-1">
                                <span aria-hidden="true">●</span>
                                결제 완료
                            </span>
                        </td>
                        <td>
                            <button type="button" aria-label="그린테이블 정산 검토" class="settlement-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>
                    <tr data-type="donut">
                        <th scope="row">
                            <span class="block font-bold">테니스 커뮤니티</span>
                            <span class="block text-gray-400 font-bold">SET-D-TENNIS-2607 · DONUT-TENNIS</span>
                        </th>
                        <td>
                            <span>도넛</span>
                            <span class="block text-gray-400">사업자 승인</span>
                        </td>
                        <td>2026.07</td>
                        <td>680,000원/T</td>
                        <td>0</td>
                        <td>680,000</td>
                        <td>신한 110-***-123456</td>
                        <td>월말 마감 · 익월 12일</td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">
                                <span aria-hidden="true">●</span>
                                검토 중
                            </span>
                        </td>
                        <td>
                            <button type="button" aria-label="테니스 커뮤니티 정산 검토" class="settlement-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>
                    <tr data-type="donut">
                        <th scope="row">
                            <span class="block font-bold">켐핑 위켄드</span>
                            <span class="block text-gray-400 font-bold">SET-CAMP-2608 · DONUT-CAMPING</span>
                        </th>
                        <td>
                            <span>도넛</span>
                            <span class="block text-gray-400">사업자 승인</span>
                        </td>
                        <td>2026.07</td>
                        <td>860,000원/T</td>
                        <td>0</td>
                        <td>860,000</td>
                        <td>카카오뱅크 3333-**-5502101</td>
                        <td>2026.08.12</td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">
                                <span aria-hidden="true">●</span>
                                검토 중
                            </span>
                        </td>
                        <td>
                            <button type="button" aria-label="켐핑 위켄드 정산 검토" class="settlement-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>
                    <tr data-type="donut">
                        <th scope="row">
                            <span class="block font-bold">홈베이킹 살롱</span>
                            <span class="block text-gray-400 font-bold">SET-D-BAKING-2607 · DONUT-BAKING</span>
                        </th>
                        <td>
                            <span>도넛</span>
                            <span class="block text-gray-400">비사업자</span>
                        </td>
                        <td>2026.07</td>
                        <td>918,000원/T</td>
                        <td>0</td>
                        <td>0</td>
                        <td>정산 불가</td>
                        <td>정산 불가</td>
                        <td>
                            <span class="rounded-full bg-gray-100 text-2xs font-bold text-gray-600 px-2 py-1">
                                <span aria-hidden="true">●</span>
                                정산 불가
                            </span>
                        </td>
                        <td>
                            <button type="button" aria-label="홈베이킹 살롱 정산 검토" class="settlement-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- 정산 상세 모달 -->
<div id="settlement-review-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="settlement-review-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="settlement-review-modal-container" role="dialog" aria-modal="true" aria-labelledby="settlement-review-modal-title" class="relative z-10 w-full max-w-150 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <header id="settlement-review-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="settlement-review-modal-title" class="text-sm font-bold text-gray-900">
                정산 상세
            </h3>

            <button type="button" aria-label="정산 상세 모달 닫기" class="settlement-review-modal-close flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </header>

        <div id="settlement-review-modal-body" class="p-4">
            <div class="mt-4 overflow-hidden rounded-lg border border-gray-300">
                <dl class="text-left text-2xs [&>div+div]:border-t [&>div+div]:border-gray-200">
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">정산번호</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">SET-B-GT-2607</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">대상</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">그린테이블</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">구분</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">브랜드</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">기간</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">2026.07</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">집계액</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">5,138,000</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">차감</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">571,600</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">검토 금액</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">4,566,400</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">제외 토핑</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">0</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">계좌</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">○○ 123-****-567890</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">기준일</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">2026.08.12</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">상태</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">
                            <span class="rounded-full bg-blue-100 text-2xs font-bold text-blue-600 px-2 py-1">
                                <span aria-hidden="true">●</span>
                                결제 완료
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="mt-3 border border-transparent rounded-lg bg-amber-50 text-2xs p-3">
                <span class="block text-amber-700 font-bold">확인 자료</span>
                <p class="mt-1 text-amber-700 font-normal">기본 토핑 3.2% 수수료 포함 · 추가토핑만 1회 차감</p>
            </div>

            <div class="mt-3 border border-transparent rounded-lg bg-red-50 text-2xs p-3">
                <span class="block text-red-700 font-bold">이 화면에서 지급하지 않습니다.</span>
                <p class="mt-1 text-red-700 font-normal">검토 기록은 금액과 상태를 바꾸지 않으며, 실제 지급은 별도 금융 절차에서 수행합니다.</p>
            </div>
        </div>

        <footer id="settlement-review-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" class="settlement-review-modal-close rounded-lg border border-gray-300 bg-white text-gray-900 font-bold px-4 py-3">
                닫기
            </button>
            <button type="button" class="rounded-lg border border-transparent bg-amber-300 text-gray-900 font-bold px-4 py-3">
                검토 기록
            </button>
        </footer>
    </div>
</div>

<script>
    // 정산 검토 구분 필터
    $('input[name="settlement-filter"]').on('change', function() {
        const filter = $(this).val();

        const $rows = $('#settlement-list tr');

        if (filter === 'all') {
            $rows.prop('hidden', false);
            return;
        }

        $rows.each(function() {
            const type = $(this).data('type');

            $(this).prop('hidden', type !== filter);
        });
    });

    // 도넛 통합 상세 모달
    $('.settlement-review-modal-open').on('click', function() {
        $('#settlement-review-modal').prop('hidden', false);
    });

    $('.settlement-review-modal-close, #settlement-review-modal-backdrop').on('click', function() {
        $('#settlement-review-modal').prop('hidden', true);
    });
</script>

<?php
require_once '../../admin.tail.php';

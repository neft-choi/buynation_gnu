<?php
$sub_menu = '930300';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '상품 검수';
require_once '../../admin.head.php';
?>

<section>
    <h2 class="sr-only">상품 검수</h2>

    <p class="text-gray-600 font-normal">브랜드 검수 요청 이후 플랫폼 승인 전에는 판매되지 않습니다.</p>

    <ol class="mt-4 grid grid-cols-1 gap-3 pc:grid-cols-5">
        <li class="rounded-lg border border-emerald-300 bg-emerald-50 text-center text-2xs font-bold text-emerald-700 p-3">
            상품 등록
        </li>
        <li class="rounded-lg border border-emerald-300 bg-emerald-50 text-center text-2xs font-bold text-emerald-700 p-3">
            접수 요청
        </li>
        <li class="rounded-lg border border-amber-400 bg-amber-50 text-center text-2xs font-bold text-amber-700 p-3">
            플랫폼 심사
        </li>
        <li class="rounded-lg border border-gray-300 bg-white text-center text-2xs text-gray-500 p-3">
            보완·반려
        </li>
        <li class="rounded-lg border border-gray-300 bg-white text-center text-2xs text-gray-500 p-3">
            승인 후 판매
        </li>
    </ol>

    <div id="product-review-filters" role="group" aria-label="상품 검수 상태 필터" class="mt-4 flex flex-wrap gap-2">
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
            반려
        </button>
    </div>

    <section class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <div class="overflow-x-auto">
            <table class="border-collapse min-w-250 w-full table-fixed text-left">
                <caption class="sr-only">상품 검수 요청 목록</caption>

                <colgroup>
                    <col class="w-[26%]">
                    <col class="w-[11%]">
                    <col class="w-[9%]">
                    <col class="w-[20%]">
                    <col class="w-[14%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                </colgroup>

                <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">접수번호 / 상품</th>
                        <th scope="col">브랜드</th>
                        <th scope="col">구분</th>
                        <th scope="col">배송그룹</th>
                        <th scope="col">요청일</th>
                        <th scope="col">상태</th>
                        <th scope="col">검수</th>
                    </tr>
                </thead>

                <tbody id="product-review-list" class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:whitespace-nowrap [&_td]:px-4 [&_td]:py-3">
                    <tr data-status="pending">
                        <td>
                            <span class="block font-bold">신제품 수프 체험팩</span>
                            <span class="block text-zinc-400">PRD-RV-260811-07 · P-807 · GT-SP-001</span>
                        </td>
                        <td>그린테이블</td>
                        <td>신규</td>
                        <td>기본 배송그룹 · 묶음배송</td>
                        <td>2026.08.11 10:40</td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-700 px-2 py-1">● 심사 대기</span>
                            <span class="mt-1 block text-zinc-400">판매 대기</span>
                        </td>
                        <td>
                            <button type="button" class="product-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검수
                            </button>
                        </td>
                    </tr>

                    <tr data-status="supplement">
                        <td>
                            <span class="block font-bold">저염 버섯스프 3팩</span>
                            <span class="block text-zinc-400">PRD-RV-260810-03 · P-809 · GT-MS-003</span>
                        </td>
                        <td>그린테이블</td>
                        <td>신규</td>
                        <td>묶음배송 · 상온</td>
                        <td>2026.08.10 09:15</td>
                        <td>
                            <span class="rounded-full bg-red-100 text-2xs font-bold text-red-600 px-2 py-1">● 보완 요청</span>
                            <span class="mt-1 block text-zinc-400">판매 대기</span>
                        </td>
                        <td>
                            <button type="button" class="product-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검수
                            </button>
                        </td>
                    </tr>

                    <tr data-status="rejected">
                        <td>
                            <span class="block font-bold">상온 크림 샐러드</span>
                            <span class="block text-zinc-400">PRD-RV-260809-02 · P-810 · GT-CR-001</span>
                        </td>
                        <td>그린테이블</td>
                        <td>신규</td>
                        <td><span class="rounded-full bg-red-100 text-2xs font-bold text-red-600 px-2 py-1">● 미지정</span></td>
                        <td>2026.08.09 13:20</td>
                        <td>
                            <span class="rounded-full bg-red-100 text-2xs font-bold text-red-600 px-2 py-1">● 최종 거절</span>
                            <span class="mt-1 block text-zinc-400">등록 반려</span>
                        </td>
                        <td>
                            <button type="button" class="product-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검수
                            </button>
                        </td>
                    </tr>

                    <tr data-status="pending">
                        <td>
                            <span class="block font-bold">대나무 욕실 정리 선반</span>
                            <span class="block text-zinc-400">PRD-RV-260811-028 · P51218 · ML-BT-811</span>
                        </td>
                        <td>몬스앤리빙</td>
                        <td>신규</td>
                        <td>개별배송 · 대형</td>
                        <td>2026.08.11 08:35</td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-700 px-2 py-1">● 심사 대기</span>
                            <span class="mt-1 block text-zinc-400">판매 대기</span>
                        </td>
                        <td>
                            <button type="button" class="product-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검수
                            </button>
                        </td>
                    </tr>

                    <tr data-status="approved">
                        <td>
                            <span class="block font-bold">패브릭 수납 박스</span>
                            <span class="block text-zinc-400">PRD-RV-260806-03 · P51188 · ML-FB-204</span>
                        </td>
                        <td>몬스앤리빙</td>
                        <td>정보 변경</td>
                        <td>묶음배송 · 상온</td>
                        <td>2026.08.06 13:18</td>
                        <td>
                            <span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">● 승인</span>
                            <span class="mt-1 block text-zinc-400">판매중</span>
                        </td>
                        <td>
                            <button type="button" class="product-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검수
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<!-- 상품 검수 모달 -->
<div id="product-review-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="product-review-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="product-review-modal-container" role="dialog" aria-modal="true" aria-labelledby="product-review-modal-title" class="relative z-10 w-full max-w-150 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div id="product-review-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="product-review-modal-title" class="text-sm font-bold text-gray-900">
                상품 검수
            </h3>

            <button type="button" id="product-review-modal-close" aria-label="상품 검수 모달 닫기" class="flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="product-review-modal-body" class="p-4">
            <span class="text-2xs font-bold text-amber-700">PRD-RV-260811-07</span>

            <h3 class="mt-2 text-xl font-bold text-gray-900">
                신제품 수프 체험팩
            </h3>

            <div class="mt-4 overflow-hidden rounded-lg border border-gray-300">
                <dl class="text-left text-2xs [&>div+div]:border-t [&>div+div]:border-gray-200">
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">상품 / SKU</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">P-807 · GT-SP-001</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">브랜드</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">그린테이블</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">판매가</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">7,900원</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">요청 유형</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">신규</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">배송그룹</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">기본 배송그룹 · 묶음배송</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">판매 상태</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">판매 대기</dd>
                    </div>
                </dl>
            </div>

            <div class="mt-4 space-y-2">
                <div class="flex items-center justify-between rounded-lg border border-gray-300 p-3">
                    <div>
                        <span class="block text-2xs font-bold text-gray-900">필수 고시정보 완료</span>
                        <p class="mt-1 text-2xs text-gray-400">검수 체크 1</p>
                    </div>
                    <span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">● 확인</span>
                </div>

                <div class="flex items-center justify-between rounded-lg border border-gray-300 p-3">
                    <div>
                        <span class="block text-2xs font-bold text-gray-900">대표 이미지 확인</span>
                        <p class="mt-1 text-2xs text-gray-400">검수 체크 2</p>
                    </div>
                    <span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">● 확인</span>
                </div>

                <div class="flex items-center justify-between rounded-lg border border-gray-300 p-3">
                    <div>
                        <span class="block text-2xs font-bold text-gray-900">배송그룹 지정</span>
                        <p class="mt-1 text-2xs text-gray-400">검수 체크 3</p>
                    </div>
                    <span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">● 확인</span>
                </div>

                <div class="flex items-center justify-between rounded-lg border border-gray-300 p-3">
                    <div>
                        <span class="block text-2xs font-bold text-gray-900">금지표현 자동검사 통과</span>
                        <p class="mt-1 text-2xs text-gray-400">검수 체크 4</p>
                    </div>
                    <span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">● 확인</span>
                </div>
            </div>

            <div class="mt-4 bg-amber-50 rounded-lg text-2xs px-3 py-2">
                <p class="text-amber-600">플랫폼 승인 시 판매 상태가 ‘판매중’으로 바뀌고 브랜드 상품 목록에 승인 결과가 반영됩니다.</p>
            </div>
        </div>

        <div id="product-review-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="product-review-modal-cancel" class="rounded-lg border border-gray-300 bg-white font-bold text-gray-900 px-4 py-3">
                닫기
            </button>

            <button type="button" class="rounded-lg border border-red-400 bg-white font-bold text-red-600 px-4 py-3">
                보완 요청
            </button>

            <button type="button" class="rounded-lg border border-red-400 bg-white font-bold text-red-600 px-4 py-3">
                반려
            </button>

            <button type="button" class="rounded-lg bg-amber-300 font-bold text-gray-900 px-4 py-3">
                승인·판매
            </button>
        </div>
    </div>
</div>

<script>
    $('#product-review-filters button').on('click', function() {
        const status = $(this).data('status');

        $('#product-review-filters button')
            .attr('aria-pressed', 'false')
            .removeClass('bg-gray-900 font-bold text-white')
            .addClass('border border-gray-300 bg-white text-gray-700');

        $(this)
            .attr('aria-pressed', 'true')
            .removeClass('border border-gray-300 bg-white text-gray-700')
            .addClass('bg-gray-900 font-bold text-white');

        $('#product-review-list tr').prop('hidden', false);

        if (status !== 'all') {
            $('#product-review-list tr').not('[data-status="' + status + '"]').prop('hidden', true);
        }
    });

    $('.product-review-modal-open').on('click', function() {
        $('#product-review-modal').prop('hidden', false);
    });

    $('#product-review-modal-close, #product-review-modal-cancel, #product-review-modal-backdrop').on('click', function() {
        $('#product-review-modal').prop('hidden', true);
    });
</script>

<?php
require_once '../../admin.tail.php';

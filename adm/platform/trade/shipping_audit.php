<?php
$sub_menu = '950200';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '배송 정책·그룹 점검';
require_once '../../admin.head.php';
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="text-gray-600 font-normal">브랜드가 설정한 그룹을 플랫폼 공통 제약과 상품 검수 관점에서 확인합니다.</p>

        <button type="button" class="orders-example-modal-open shrink-0 w-fit rounded-lg bg-amber-300 text-gray-900 font-bold px-4 py-3">
            <span>복수 브랜드 계산</span>
        </button>
    </div>

    <section class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-2">
        <div class="rounded-lg bg-gray-50 p-3">
            <span class="block text-2xs text-gray-400 font-normal">브랜드</span>
            <span class="mt-1 block text-xs text-gray-900 font-bold">1개</span>
        </div>
        <div class="rounded-lg bg-gray-50 p-3">
            <span class="block text-2xs text-gray-400 font-normal">배송 그룹</span>
            <span class="mt-1 block text-xs text-gray-900 font-bold">4개</span>
        </div>
        <div class="rounded-lg bg-gray-50 p-3">
            <span class="block text-2xs text-gray-400 font-normal">미지정 상품</span>
            <span class="mt-1 block text-xs text-gray-900 font-bold">1개</span>
        </div>
        <div class="rounded-lg bg-gray-50 p-3">
            <span class="block text-2xs text-gray-400 font-normal">조치 필요</span>
            <span class="mt-1 block text-xs text-gray-900 font-bold">1개사</span>
        </div>
    </section>

    <div class="mt-4 flex items-center gap-3 bg-amber-50 rounded-lg text-2xs p-3">
        <span class="text-amber-600 font-bold">검수 연결</span>
        <p class="text-amber-700 font-normal">모든 브랜드에는 기본 배송그룹이 자동 생성되고, 상품은 하나의 배송그룹에 속해야 합니다. 냉장·대형 상품의 그룹 미지정은 상품 승인 차단 사유입니다.</p>
    </div>

    <section class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <div class="overflow-x-auto">
            <table class="border-collapse min-w-250 w-full table-fixed text-left">
                <caption class="sr-only">거래 조회 목록</caption>

                <colgroup>
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[30%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                </colgroup>

                <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">브랜드</th>
                        <th scope="col">기본 그룹</th>
                        <th scope="col">그룹 수</th>
                        <th scope="col">미지정</th>
                        <th scope="col">도서산간</th>
                        <th scope="col">최근 변경</th>
                        <th scope="col">상태</th>
                        <th scope="col">점검</th>
                    </tr>
                </thead>

                <tbody class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:whitespace-nowrap [&_td]:px-4 [&_td]:py-3">
                    <tr>
                        <td>
                            <span class="block font-bold">그린테이블</span>
                            <span class="block text-zinc-400 font-medium">BRD-00204</span>
                        </td>
                        <td>
                            <span>기본 배송그룹</span>
                        </td>
                        <td>
                            <span>4개</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-red-100 text-2xs font-bold text-red-600 px-2 py-1">● 1개</span>
                        </td>
                        <td>
                            <span>기본·정기 제주/도서산간 +3,000원 · 냉장 +4,000원 · 대형 +6,000원</span>
                        </td>
                        <td>
                            <span>2026.08.11 10:40</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-red-100 text-2xs font-bold text-red-600 px-2 py-1">● 조치 필요</span>
                        </td>
                        <td>
                            <button type="button" class="orders-detail-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<!-- 배송비 계산 예시 모달 -->
<div id="orders-example-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="orders-example-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="orders-example-modal-container" role="dialog" aria-modal="true" aria-labelledby="orders-example-modal-title" class="relative z-10 w-full max-w-150 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div id="orders-example-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="orders-example-modal-title" class="text-sm font-bold text-gray-900">
                복수 브랜드 배송비 계산
            </h3>

            <button type="button" id="orders-example-modal-close" aria-label="커뮤니티 신고 상세 모달 닫기" class="flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="orders-example-modal-body" class="p-4">
            <div class="rounded-lg bg-gray-100 p-3">
                <span class="block text-2xs text-gray-400 font-normal">그린테이블 · 상온 묶음배송</span>
                <p class="text-2xs text-gray-900 font-normal">상품 18,500원 → 배송비 4,000원</p>
            </div>

            <div class="mt-3 rounded-lg bg-gray-100 p-3">
                <span class="block text-2xs text-gray-400 font-normal">문스앤 리빙 · 가구 개별배송</span>
                <p class="text-2xs text-gray-900 font-normal">상품 72,000원 → 배송비 12,000원</p>
            </div>

            <div class="mt-3 flex items-center justify-between rounded-lg bg-amber-200 p-6">
                <div>
                    <span class="block text-2xs text-amber-600 font-normal">주문 총 배송비</span>
                    <span class="mt-2 block text-2xl text-gray-900 font-bold">16,000원</span>
                </div>
                <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-300 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-move-horizontal preview-icon w-6 h-6">
                        <path d="m18 8 4 4-4 4" />
                        <path d="M2 12h20" />
                        <path d="m6 8-4 4 4 4" />
                    </svg>
                </div>
            </div>

            <div class="mt-3 rounded-lg bg-amber-50 p-3">
                <p class="mt-1 text-2xs text-amber-600">브랜드 내부 묶음 조건을 먼저 계산한 뒤 브랜드별 배송비를 합산합니다.</p>
            </div>
        </div>

        <div id="orders-example-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="orders-example-modal-cancel" class="rounded-lg border border-gray-300 bg-white font-bold text-gray-900 px-4 py-3">
                닫기
            </button>
        </div>
    </div>
</div>

<!-- 거래 상세 모달 -->
<div id="orders-detail-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="orders-detail-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="orders-detail-modal-container" role="dialog" aria-modal="true" aria-labelledby="orders-detail-modal-title" class="relative z-10 w-full max-w-150 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div id="orders-detail-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="orders-detail-modal-title" class="text-sm font-bold text-gray-900">
                거래 상세
            </h3>

            <button type="button" id="orders-detail-modal-close" aria-label="커뮤니티 신고 상세 모달 닫기" class="flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="orders-detail-modal-body" class="p-4">
            <div class="overflow-hidden rounded-lg border border-gray-300">
                <dl class="text-left text-2xs [&>div+div]:border-t [&>div+div]:border-gray-200">
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">주문번호</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">ORD-1038</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">구매 도트</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">DOT-48102</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">브랜드</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">그린테이블</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">주문 상품</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">P-801 × 1</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">상품액</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">37,800원</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">배송비</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">3,000원</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">기여 도넛</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">테니스 커뮤니티</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">기여 토핑</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">1,209T</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">토핑 로그</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">TOP-ORD-1038</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">배송 조회 ID</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">SHP-ORD-1038</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">상태</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">
                            <span class="rounded-full bg-blue-100 text-2xs font-bold text-blue-600 px-2 py-1">● 결제 완료</span>
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="mt-3 rounded-lg bg-amber-50 p-3">
                <p class="mt-1 text-2xs text-amber-600">주문 헤더와 상품별 기여 내역을 공통 주문번호로 추적합니다.</p>
            </div>
        </div>

        <div id="orders-detail-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="orders-detail-modal-cancel" class="rounded-lg border border-gray-300 bg-white font-bold text-gray-900 px-4 py-3">
                닫기
            </button>
        </div>
    </div>
</div>

<script>
    // 배송비 계산 예시 모달
    $('.orders-example-modal-open').on('click', function() {
        $('#orders-example-modal').prop('hidden', false);
    });

    $('#orders-example-modal-close, #orders-example-modal-cancel, #orders-example-modal-backdrop').on('click', function() {
        $('#orders-example-modal').prop('hidden', true);
    });

    // 거래 상세 모달
    $('.orders-detail-modal-open').on('click', function() {
        $('#orders-detail-modal').prop('hidden', false);
    });

    $('#orders-detail-modal-close, #orders-detail-modal-cancel, #orders-detail-modal-backdrop').on('click', function() {
        $('#orders-detail-modal').prop('hidden', true);
    });
</script>

<?php
require_once '../../admin.tail.php';

<?php
$sub_menu = '940300';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '도넛 관리';
require_once '../../admin.head.php';
?>

<section>
    <h2 class="sr-only">도넛 관리</h2>

    <p class="text-gray-600 font-normal">한 도티가 여러 도넛을 운영해도 사업자·계좌·토핑 자격은 도넛별로 분리합니다.</p>

    <div class="mt-4 flex items-center gap-3 bg-blue-50 rounded-lg text-2xs p-3">
        <span class="text-blue-600 font-bold">정산 단위</span>
        <p class="text-blue-700 font-normal">같은 도티가 운영하더라도 사업자와 입금계좌가 다를 수 있으므로 도넛별 정산계정으로 관리합니다.</p>
    </div>

    <section class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <div class="overflow-x-auto">
            <table class="border-collapse min-w-250 w-full table-fixed text-left">
                <caption class="sr-only">도넛 목록</caption>

                <colgroup>
                    <col class="w-[16%]">
                    <col class="w-[12%]">
                    <col class="w-[12%]">
                    <col class="w-[12%]">
                    <col class="w-[12%]">
                    <col class="w-[12%]">
                    <col class="w-[12%]">
                    <col class="w-[12%]">
                </colgroup>

                <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">도넛</th>
                        <th scope="col">도티</th>
                        <th scope="col">가입 도트</th>
                        <th scope="col">운영</th>
                        <th scope="col">사업자 상태</th>
                        <th scope="col">현금화 가능</th>
                        <th scope="col">쇼핑 전용·잠정</th>
                        <th scope="col">관리</th>
                    </tr>
                </thead>

                <tbody class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:whitespace-nowrap [&_td]:px-4 [&_td]:py-3">
                    <tr>
                        <td>
                            <span class="block font-bold">테니스 커뮤니티</span>
                            <span class="block text-zinc-400 font-medium">DONUT-TENNIS</span>
                        </td>
                        <td>
                            <span class="block">김도윤</span>
                            <span class="block text-zinc-400">DOTI-0001</span>
                        </td>
                        <td>
                            <span>8,720명</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 정상</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 사업자 승인</span>
                        </td>
                        <td>
                            <span>680,000T</span>
                        </td>
                        <td>
                            <span>0T</span>
                        </td>
                        <td>
                            <button type="button" class="donuts-detail-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">러닝 메이트</span>
                            <span class="block text-zinc-400 font-medium">DONUT-RUNNING</span>
                        </td>
                        <td>
                            <span class="block">김도윤</span>
                            <span class="block text-zinc-400">DOTI-0001</span>
                        </td>
                        <td>
                            <span>4,360명</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 정상</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-blue-100 text-2xs font-bold text-blue-600 px-2 py-1">● 최초 심사 중</span>
                        </td>
                        <td>
                            <span>0T</span>
                        </td>
                        <td>
                            <span>1,286,400T</span>
                        </td>
                        <td>
                            <button type="button" class="donuts-detail-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">반려생활 연구소</span>
                            <span class="block text-zinc-400 font-medium">DONUT-PET</span>
                        </td>
                        <td>
                            <span class="block">김도윤</span>
                            <span class="block text-zinc-400">DOTI-0001</span>
                        </td>
                        <td>
                            <span>5,180명</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 정상</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-gray-100 text-2xs font-bold text-gray-600 px-2 py-1">● 비사업자</span>
                        </td>
                        <td>
                            <span>0T</span>
                        </td>
                        <td>
                            <span>635,000T</span>
                        </td>
                        <td>
                            <button type="button" class="donuts-detail-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<!-- 도넛 통합 상세 모달 -->
<div id="donuts-detail-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="donuts-detail-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="donuts-detail-modal-container" role="dialog" aria-modal="true" aria-labelledby="donuts-detail-modal-title" class="relative z-10 w-full max-w-150 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div id="donuts-detail-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="donuts-detail-modal-title" class="text-sm font-bold text-gray-900">
                도넛 통합 상세
            </h3>

            <button type="button" id="donuts-detail-modal-close" aria-label="상품 검수 모달 닫기" class="flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="donuts-detail-modal-body" class="p-4">
            <h3 class="text-xl font-bold text-gray-900">
                테니스 커뮤니티
            </h3>

            <div class="mt-4 overflow-hidden rounded-lg border border-gray-300">
                <dl class="text-left text-2xs [&>div+div]:border-t [&>div+div]:border-gray-200">
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">도넛 ID</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">DONUT-TENNIS</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">도티</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">김도윤 · DOTI-0001</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">사업자 상태</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 사업자 승인</span>
                        </dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">정산 계좌</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">신한 110-***-123456 · 테니스 커뮤니티 주식회사</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">현금화 가능</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">680,000T</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">쇼핑 전용</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">0T</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">심사 잠정</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">0T</dd>
                    </div>
                </dl>
            </div>

            <div class="mt-3 rounded-r-lg bg-emerald-50 border-l-4 border-emerald-600 p-3">
                <span class="block text-2xs text-gray-900">현금화 가능 버킷 680,000T</span>
                <p class="mt-1 text-2xs text-gray-400">사업자 승인 후 발생했거나 최초 심사 승인으로 자격이 확정된 금액</p>
            </div>

            <div class="mt-3 rounded-r-lg bg-amber-50 border-l-4 border-amber-600 p-3">
                <span class="block text-2xs text-gray-900">쇼핑 전용·잠정 0T</span>
                <p class="mt-1 text-2xs text-gray-400">잠금 토핑 없음</p>
            </div>

            <div class="mt-3 rounded-lg bg-gray-50 p-3">
                <span class="block text-2xs text-gray-400">사업자 신청</span>
                <p class="mt-1 text-2xs text-gray-900">DTV-TENNIS-260704 · 최초 사업자 인증 · approved</p>
            </div>

            <div class="mt-3 rounded-lg bg-gray-50 p-3">
                <span class="block text-2xs text-gray-400">도트 운영자</span>
                <p class="mt-1 text-2xs text-gray-900">홍길동(1개 권한)</p>
            </div>
        </div>

        <div id="donuts-detail-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="donuts-detail-modal-cancel" class="rounded-lg border border-gray-300 bg-white font-bold text-gray-900 px-4 py-3">
                닫기
            </button>
            <button type="button" class="rounded-lg border border-gray-300 bg-white font-bold text-gray-900 px-4 py-3">
                사업자 신청 보기
            </button>
        </div>
    </div>
</div>

<script>
    $('.donuts-detail-modal-open').on('click', function() {
        $('#donuts-detail-modal').prop('hidden', false);
    });

    $('#donuts-detail-modal-close, #donuts-detail-modal-cancel, #donuts-detail-modal-backdrop').on('click', function() {
        $('#donuts-detail-modal').prop('hidden', true);
    });
</script>

<?php
require_once '../../admin.tail.php';

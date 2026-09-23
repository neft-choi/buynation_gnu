<?php
$sub_menu = '930200';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '입점사 관리';
require_once '../../admin.head.php';
?>

<section>
    <h2 class="sr-only">입점사 관리</h2>

    <p class="text-gray-600 font-normal">서류 승인 후에도 정산계좌·배송·상품검수 상태를 함께 감독합니다.</p>

    <section>
        <h3 class="sound_only">요약 정보</h3>

        <div class="grid grid-cols-1 pc:grid-cols-4 gap-4 mt-4">
            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">승인 입점사</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">1<span class="ml-1 text-sm">개</span></p>
                <span class="mt-3 block text-2xs text-gray-600">판매 가능</span>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">서류 변경 보완</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">1<span class="ml-1 text-sm">건</span></p>
                <span class="mt-3 block text-2xs text-green-600">기존 판매 유지 · 정산 검토</span>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">배송 조치 필요</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">1<span class="ml-1 text-sm">개사</span></p>
                <span class="mt-3 block text-2xs text-amber-600">미지정 상품 포함</span>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">상품 검수 대기</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">3<span class="ml-1 text-sm">건</span></p>
                <span class="mt-3 block text-2xs text-indigo-600">판매 전 플랫폼 승인</span>
            </div>
        </div>
    </section>

    <section class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <div class="overflow-x-auto">
            <table class="border-collapse min-w-250 w-full table-fixed text-left">
                <caption class="sr-only">브랜드 서류 심사 요청 목록</caption>

                <colgroup>
                    <col class="w-[20%]">
                    <col class="w-[20%]">
                    <col class="w-[10%]">
                    <col class="w-[15%]">
                    <col class="w-[15%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                </colgroup>

                <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">브랜드</th>
                        <th scope="col">사업자번호</th>
                        <th scope="col">담당자</th>
                        <th scope="col">서류</th>
                        <th scope="col">배송</th>
                        <th scope="col">상품</th>
                        <th scope="col">관리</th>
                    </tr>
                </thead>

                <tbody class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:whitespace-nowrap [&_td]:px-4 [&_td]:py-3">
                    <tr>
                        <td>
                            <span class="block font-bold">노르딕홈</span>
                            <span class="block text-zinc-400">BRD-00182</span>
                        </td>
                        <td>
                            <span>214-81-00931</span>
                        </td>
                        <td>
                            <span>이서연</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-700 px-2 py-1">● 심사 대기</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-gray-100 text-2xs font-bold text-gray-700 px-2 py-1">● 연동 전</span>
                        </td>
                        <td>
                            <span>0개</span>
                        </td>
                        <td>
                            <button type="button" class="brand-detail-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                통합 보기
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">그린리프</span>
                            <span class="block text-zinc-400">BRD-00179</span>
                        </td>
                        <td>
                            <span>128-86-01277</span>
                        </td>
                        <td>
                            <span>박준호</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-700 px-2 py-1">● 심사 대기</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-gray-100 text-2xs font-bold text-gray-700 px-2 py-1">● 연동 전</span>
                        </td>
                        <td>
                            <span>0개</span>
                        </td>
                        <td>
                            <button type="button" class="brand-detail-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                통합 보기
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">그린테이블</span>
                            <span class="block text-zinc-400">BRD-00204</span>
                        </td>
                        <td>
                            <span>123-45-67890</span>
                        </td>
                        <td>
                            <span>김브랜드</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-red-100 text-2xs font-bold text-red-700 px-2 py-1">● 보완 요청</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-red-100 text-2xs font-bold text-red-700 px-2 py-1">● 조치 필요</span>
                        </td>
                        <td>
                            <span>3개</span>
                        </td>
                        <td>
                            <button type="button" class="brand-detail-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                통합 보기
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">문스앤 리빙</span>
                            <span class="block text-zinc-400">BRD-00168</span>
                        </td>
                        <td>
                            <span>317-88-00654</span>
                        </td>
                        <td>
                            <span>김지현</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-700 px-2 py-1">● 승인</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-gray-100 text-2xs font-bold text-gray-700 px-2 py-1">● 연동 전</span>
                        </td>
                        <td>
                            <span>2개</span>
                        </td>
                        <td>
                            <button type="button" class="brand-detail-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                통합 보기
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<!-- 입점사 통합 상세 모달 -->
<div id="brand-detail-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="brand-detail-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="brand-detail-modal-container" role="dialog" aria-modal="true" aria-labelledby="brand-detail-modal-title" class="relative z-10 w-full max-w-150 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div id="brand-detail-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="brand-detail-modal-title" class="text-sm font-bold text-gray-900">
                입점사 통합 상세
            </h3>

            <button type="button" id="brand-detail-modal-close" aria-label="입점사 통합 상세 모달 닫기" class="flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="brand-detail-modal-body" class="p-4">
            <h3 class="text-xl font-bold text-gray-900">노르딕홈</h3>

            <div class="mt-4 grid grid-cols-2 gap-3 pc:grid-cols-4">
                <div class="rounded-lg bg-gray-100 p-3">
                    <span class="block text-2xs text-gray-400">서류</span>
                    <p class="mt-2 font-bold text-gray-900">pending</p>
                </div>

                <div class="rounded-lg bg-gray-100 p-3">
                    <span class="block text-2xs text-gray-400">상품</span>
                    <p class="mt-2 font-bold text-gray-900">0개</p>
                </div>

                <div class="rounded-lg bg-gray-100 p-3">
                    <span class="block text-2xs text-gray-400">배송그룹</span>
                    <p class="mt-2 font-bold text-gray-900">0개</p>
                </div>

                <div class="rounded-lg bg-gray-100 p-3">
                    <span class="block text-2xs text-gray-400">미지정</span>
                    <p class="mt-2 font-bold text-gray-900">0개</p>
                </div>
            </div>

            <div class="mt-3 rounded-lg bg-gray-100 p-3">
                <span class="block text-2xs text-gray-400">정산계좌</span>
                <p class="mt-2 text-2xs text-gray-900">국민 123-45-*****</p>
            </div>

            <div class="mt-3 rounded-lg bg-gray-100 p-3">
                <span class="block text-2xs text-gray-400">최근 서류 이력</span>
                <p class="mt-2 text-2xs text-gray-900">08.08 09:20 신규 입점 심사 요청</p>
            </div>
        </div>

        <div id="brand-detail-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="brand-detail-modal-cancel" class="rounded-lg border border-gray-300 bg-white font-bold text-gray-900 px-4 py-3">
                닫기
            </button>

            <button type="button" class="rounded-lg border border-gray-300 bg-white font-bold text-gray-900 px-4 py-3">
                서류 보기
            </button>
        </div>
    </div>
</div>

<script>
    $('.brand-detail-modal-open').on('click', function() {
        $('#brand-detail-modal').prop('hidden', false);
    });

    $('#brand-detail-modal-close, #brand-detail-modal-cancel, #brand-detail-modal-backdrop').on('click', function() {
        $('#brand-detail-modal').prop('hidden', true);
    });
</script>

<?php
require_once '../../admin.tail.php';

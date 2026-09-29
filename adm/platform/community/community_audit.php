<?php
$sub_menu = '940700';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '커뮤니티 모니터링';
require_once '../../admin.head.php';
?>

<section>
    <p class="text-gray-600 font-normal">도티·운영자 1차 처리와 플랫폼 직접 개입 건을 구분합니다.</p>

    <section class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <div class="overflow-x-auto">
            <table class="border-collapse min-w-250 w-full table-fixed text-left">
                <caption class="sr-only">커뮤니티 모니터링 목록</caption>

                <colgroup>
                    <col class="w-[16%]">
                    <col class="w-[14%]">
                    <col class="w-[14%]">
                    <col class="w-[14%]">
                    <col class="w-[14%]">
                    <col class="w-[14%]">
                    <col class="w-[14%]">
                </colgroup>

                <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">신고번호 / 도넛</th>
                        <th scope="col">유형</th>
                        <th scope="col">대상</th>
                        <th scope="col">신고</th>
                        <th scope="col">현재 담당</th>
                        <th scope="col">상태</th>
                        <th scope="col">확인</th>
                    </tr>
                </thead>

                <tbody class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:whitespace-nowrap [&_td]:px-4 [&_td]:py-3">
                    <tr>
                        <td>
                            <span class="block font-bold">전국 러닝크루</span>
                            <span class="block text-zinc-400 font-medium">RPT-0221</span>
                        </td>
                        <td>
                            <span>반복 홍보</span>
                        </td>
                        <td>
                            <span>게시글 #1841</span>
                        </td>
                        <td>
                            <span>7건</span>
                        </td>
                        <td>
                            <span>도티 확인 대기</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">● 심사 대기</span>
                        </td>
                        <td>
                            <button type="button" class="community-audit-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">건강한 식탁</span>
                            <span class="block text-zinc-400 font-medium">RPT-0218</span>
                        </td>
                        <td>
                            <span>욕설·비방</span>
                        </td>
                        <td>
                            <span>댓글 #9122</span>
                        </td>
                        <td>
                            <span>4건</span>
                        </td>
                        <td>
                            <span>운영자 조치 완료</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 완료</span>
                        </td>
                        <td>
                            <button type="button" class="community-audit-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">문장 수집소</span>
                            <span class="block text-zinc-400 font-medium">RPT-0214</span>
                        </td>
                        <td>
                            <span>상품 링크 오남용</span>
                        </td>
                        <td>
                            <span>게시글 #1784</span>
                        </td>
                        <td>
                            <span>3건</span>
                        </td>
                        <td>
                            <span>플랫폼 검토</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">● 검토 중</span>
                        </td>
                        <td>
                            <button type="button" class="community-audit-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<!-- 커뮤니티 신고 상세 모달 -->
<div id="community-audit-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="community-audit-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="community-audit-modal-container" role="dialog" aria-modal="true" aria-labelledby="community-audit-modal-title" class="relative z-10 w-full max-w-150 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div id="community-audit-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="community-audit-modal-title" class="text-sm font-bold text-gray-900">
                커뮤니티 신고 상세
            </h3>

            <button type="button" id="community-audit-modal-close" aria-label="커뮤니티 신고 상세 모달 닫기" class="flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="community-audit-modal-body" class="p-4">
            <div class="overflow-hidden rounded-lg border border-gray-300">
                <dl class="text-left text-2xs [&>div+div]:border-t [&>div+div]:border-gray-200">
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">신고번호</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">RPT-0221</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">도넛</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">전국 러닝크루</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">유형</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">반복 홍보</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">대상</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">게시글 #1841</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">신고 수</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">7건</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">현재 담당</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">도티 확인 대기</dd>
                    </div>
                </dl>
            </div>

            <div class="mt-3 rounded-lg bg-amber-50 p-3">
                <p class="mt-1 text-2xs text-amber-600">도티·운영자의 일상 moderation을 우선하며 반복 오남용·미처리·플랫폼 정책 위반 시 직접 개입합니다.</p>
            </div>
        </div>

        <div id="community-audit-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="community-audit-modal-cancel" class="rounded-lg border border-gray-300 bg-white font-bold text-gray-900 px-4 py-3">
                닫기
            </button>
        </div>
    </div>
</div>

<script>
    $('.community-audit-modal-open').on('click', function() {
        $('#community-audit-modal').prop('hidden', false);
    });

    $('#community-audit-modal-close, #community-audit-modal-cancel, #community-audit-modal-backdrop').on('click', function() {
        $('#community-audit-modal').prop('hidden', true);
    });
</script>

<?php
require_once '../../admin.tail.php';

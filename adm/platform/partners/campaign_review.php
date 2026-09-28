<?php
$sub_menu = '930400';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '추가 토핑 심사';
require_once '../../admin.head.php';
?>

<section>
    <h2 class="sr-only">추가 토핑 심사</h2>

    <p class="text-gray-600 font-normal">브랜드가 제안한 추가율·기간·상품·노출 범위를 검토합니다.</p>

    <div class="mt-4 flex items-center gap-3 bg-amber-50 rounded-lg text-2xs p-3">
        <span class="text-amber-600 font-bold">적용 원칙</span>
        <p class="text-amber-700 font-normal">승인 전에는 도티의 추천상품 검색과 도트의 상품 노출에 반영되지 않습니다.</p>
    </div>

    <section class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <div class="overflow-x-auto">
            <table class="border-collapse min-w-250 w-full table-fixed text-left">
                <caption class="sr-only">추가 토핑 심사 목록</caption>

                <colgroup>
                    <col class="w-[16%]">
                    <col class="w-[10%]">
                    <col class="w-[14%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[20%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                </colgroup>

                <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">요청번호 / 캠페인</th>
                        <th scope="col">브랜드</th>
                        <th scope="col">기간</th>
                        <th scope="col">상품</th>
                        <th scope="col">추가율</th>
                        <th scope="col">범위</th>
                        <th scope="col">상태</th>
                        <th scope="col">심사</th>
                    </tr>
                </thead>

                <tbody class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:whitespace-nowrap [&_td]:px-4 [&_td]:py-3">
                    <tr>
                        <td>
                            <span class="block font-bold">건강한 여름 추가 토핑</span>
                            <span class="block text-zinc-400">CMP-RV-260801-01</span>
                        </td>
                        <td>그린테이블</td>
                        <td>2026.08.01 ~ 2026.08.31</td>
                        <td>1개</td>
                        <td>+4%</td>
                        <td>
                            <span>DONUT-TENNIS · 테니스 커뮤니티</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 승인</span>
                        </td>
                        <td>
                            <button type="button" class="campaign-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">ECO KITCHEN WEEK</span>
                            <span class="block text-zinc-400">EVT-0191</span>
                        </td>
                        <td>그린리프</td>
                        <td>2026.08.15 ~ 2026.08.28</td>
                        <td>6개</td>
                        <td>+3%</td>
                        <td>
                            <span>음식 카테고리</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">● 심사 대기</span>
                        </td>
                        <td>
                            <button type="button" class="campaign-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<!-- 추가 토핑 심사 검토 모달 -->
<div id="campaign-review-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="campaign-review-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="campaign-review-modal-container" role="dialog" aria-modal="true" aria-labelledby="campaign-review-modal-title" class="relative z-10 w-full max-w-150 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div id="campaign-review-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="campaign-review-modal-title" class="text-sm font-bold text-gray-900">
                추가 토핑 심사
            </h3>

            <button type="button" id="campaign-review-modal-close" aria-label="상품 검수 모달 닫기" class="flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="campaign-review-modal-body" class="p-4">
            <div class="mt-4 overflow-hidden rounded-lg border border-gray-300">
                <dl class="text-left text-2xs [&>div+div]:border-t [&>div+div]:border-gray-200">
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">요청번호</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">CMP-RV-260801-01</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">브랜드</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">그린테이블</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">캠페인</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">건강한 여름 추가 토핑</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">기간</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">2026.08.01 ~ 2026.08.31</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">상품</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">1개</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">기본 기여율</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">3.2% · 확정 정책값</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">추가 토핑율</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">+4%</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">노출 범위</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">DONUT-TENNIS · 테니스 커뮤니티</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div id="campaign-review-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="campaign-review-modal-cancel" class="rounded-lg border border-gray-300 bg-white font-bold text-gray-900 px-4 py-3">
                닫기
            </button>
        </div>
    </div>
</div>

<script>
    $('.campaign-review-modal-open').on('click', function() {
        $('#campaign-review-modal').prop('hidden', false);
    });

    $('#campaign-review-modal-close, #campaign-review-modal-cancel, #campaign-review-modal-backdrop').on('click', function() {
        $('#campaign-review-modal').prop('hidden', true);
    });
</script>

<?php
require_once '../../admin.tail.php';

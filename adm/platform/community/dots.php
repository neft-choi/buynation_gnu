<?php
$sub_menu = '940100';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '도트 관리';
require_once '../../admin.head.php';
?>

<section>
    <h2 class="sr-only">도트 관리</h2>

    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="text-gray-600 font-normal">가입 도넛 수와 도티가 부여한 운영자 권한을 계정 상태와 함께 확인합니다.</p>

        <button type="button" class="shrink-0 w-fit border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
            <span>도트 목록</span>
        </button>
    </div>

    <section class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <div class="overflow-x-auto">
            <table class="border-collapse min-w-250 w-full table-fixed text-left">
                <caption class="sr-only">도트 목록</caption>

                <colgroup>
                    <col class="w-[12%]">
                    <col class="w-[11%]">
                    <col class="w-[11%]">
                    <col class="w-[11%]">
                    <col class="w-[11%]">
                    <col class="w-[11%]">
                    <col class="w-[11%]">
                    <col class="w-[11%]">
                    <col class="w-[11%]">
                </colgroup>

                <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">도트</th>
                        <th scope="col">가입일</th>
                        <th scope="col">가입 도넛</th>
                        <th scope="col">운영 권한</th>
                        <th scope="col">주문</th>
                        <th scope="col">토핑</th>
                        <th scope="col">위험</th>
                        <th scope="col">상태</th>
                        <th scope="col">관리</th>
                    </tr>
                </thead>

                <tbody class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:whitespace-nowrap [&_td]:px-4 [&_td]:py-3">
                    <tr>
                        <td>
                            <span class="block font-bold">홍길동</span>
                            <span class="block text-zinc-400 font-medium">DOT-48102</span>
                        </td>
                        <td>
                            <span>2025.11.01</span>
                        </td>
                        <td>
                            <span>7개</span>
                        </td>
                        <td>
                            <span>6건</span>
                        </td>
                        <td>
                            <span>1건</span>
                        </td>
                        <td>
                            <span>3,000T</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 낮음</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 정상</span>
                        </td>
                        <td>
                            <button type="button" class="dots-detail-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">리뷰왕</span>
                            <span class="block text-zinc-400 font-medium">DOT-41255</span>
                        </td>
                        <td>
                            <span>2026.03.22</span>
                        </td>
                        <td>
                            <span>1개</span>
                        </td>
                        <td>
                            <span>0건</span>
                        </td>
                        <td>
                            <span>122건</span>
                        </td>
                        <td>
                            <span>86,700T</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-red-100 text-2xs font-bold text-red-600 px-2 py-1">● 높음</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-red-100 text-2xs font-bold text-red-600 px-2 py-1">● 활동 제한</span>
                        </td>
                        <td>
                            <button type="button" class="dots-detail-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">계정공유의심</span>
                            <span class="block text-zinc-400 font-medium">DOT-39771</span>
                        </td>
                        <td>
                            <span>2026.02.18</span>
                        </td>
                        <td>
                            <span>0개</span>
                        </td>
                        <td>
                            <span>0건</span>
                        </td>
                        <td>
                            <span>7건</span>
                        </td>
                        <td>
                            <span>3,200T</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">● 주의</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">● 검토 중</span>
                        </td>
                        <td>
                            <button type="button" class="dots-detail-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<!-- 도트 통합 상세 모달 -->
<div id="dots-detail-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="dots-detail-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="dots-detail-modal-container" role="dialog" aria-modal="true" aria-labelledby="dots-detail-modal-title" class="relative z-10 w-full max-w-150 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div id="dots-detail-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="dots-detail-modal-title" class="text-sm font-bold text-gray-900">
                도트 통합 상세
            </h3>

            <button type="button" id="dots-detail-modal-close" aria-label="상품 검수 모달 닫기" class="flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="dots-detail-modal-body" class="p-4">
            <div class="mt-4 overflow-hidden rounded-lg border border-gray-300">
                <dl class="text-left text-2xs [&>div+div]:border-t [&>div+div]:border-gray-200">
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">도트</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">홍길동 · DOT-48102</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">가입일</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">2025.11.01</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">가입 도넛</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">7개</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">운영자 권한</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">7건</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">주문 / 토핑</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">1건 · 3,000T</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">상태</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 정상</span>
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="mt-3 rounded-lg bg-gray-50 p-3">
                <span class="block text-2xs text-gray-400">테니스 커뮤니티 · 도넛 운영자</span>
                <p class="mt-1 text-2xs text-gray-900">가입 심사</p>
            </div>

            <div class="mt-3 rounded-lg bg-gray-50 p-3">
                <span class="block text-2xs text-gray-400">러닝 메이트 · 도넛 운영자</span>
                <p class="mt-1 text-2xs text-gray-900">콘텐츠 · 공지</p>
            </div>

            <div class="mt-3 rounded-lg bg-gray-50 p-3">
                <span class="block text-2xs text-gray-400">사진 산책회 · 도넛 운영자</span>
                <p class="mt-1 text-2xs text-gray-900">가입 심사 · 회원 조회</p>
            </div>

            <div class="mt-3 rounded-lg bg-gray-50 p-3">
                <span class="block text-2xs text-gray-400">독서 라운지 · 도넛 운영자</span>
                <p class="mt-1 text-2xs text-gray-900">추천상품</p>
            </div>

            <div class="mt-3 rounded-lg bg-gray-50 p-3">
                <span class="block text-2xs text-gray-400">홈베이킹 살롱 · 초대 대기</span>
                <p class="mt-1 text-2xs text-gray-900">공지 · 추천상품</p>
            </div>

            <div class="mt-3 rounded-lg bg-gray-50 p-3">
                <span class="block text-2xs text-gray-400">반려생활 연구소 · 도넛 운영자</span>
                <p class="mt-1 text-2xs text-gray-900">콘텐츠</p>
            </div>

            <div class="mt-3 rounded-lg bg-gray-50 p-3">
                <span class="block text-2xs text-gray-400">캠핑 위켄드 · 도넛 운영자</span>
                <p class="mt-1 text-2xs text-gray-900">가입 심사 · 회원 조회 · 콘텐츠 · 공지 · 추천상품</p>
            </div>

            <div class="mt-3 bg-amber-50 rounded-lg text-2xs p-3">
                <p class="text-amber-700 font-normal">계정 제한 시 부여받은 도넛 운영자 권한도 함께 사용 중지되어야 합니다.</p>
            </div>
        </div>

        <div id="dots-detail-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="dots-detail-modal-cancel" class="rounded-lg border border-gray-300 bg-white font-bold text-gray-900 px-4 py-3">
                닫기
            </button>
            <button type="button" class="rounded-lg border border-red-400 bg-white font-bold text-red-600 px-4 py-3">
                계정 제한
            </button>
        </div>
    </div>
</div>

<script>
    $('.dots-detail-modal-open').on('click', function() {
        $('#dots-detail-modal').prop('hidden', false);
    });

    $('#dots-detail-modal-close, #dots-detail-modal-cancel, #dots-detail-modal-backdrop').on('click', function() {
        $('#dots-detail-modal').prop('hidden', true);
    });
</script>

<?php
require_once '../../admin.tail.php';

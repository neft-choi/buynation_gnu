<?php
$sub_menu = '940500';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '도넛 운영자 권한';
require_once '../../admin.head.php';
?>

<section>
    <p class="text-gray-600 font-normal">권한 부여·회수는 도티 업무이며, 플랫폼은 오남용·제재·감사 이력을 감독합니다.</p>

    <div class="mt-4 flex items-center gap-3 bg-amber-50 rounded-lg text-2xs p-3">
        <span class="text-amber-600 font-bold">권한 경계</span>
        <p class="text-amber-700 font-normal">도트 마이페이지에는 부여받은 도넛과 권한만 표시됩니다. 예를 들어 ‘문장 수집소’의 추천상품 현황은 쇼핑 권한이 있어야 보이고, 커뮤니티 기능도 부여 범위만 노출됩니다.</p>
    </div>

    <div id="delegate-audit-filters" role="group" aria-label="도티 운영자 권한 필터" class="mt-4 flex flex-wrap gap-2">
        <button type="button" data-status="all" aria-pressed="true" class="rounded-full bg-gray-900 text-2xs font-bold text-white px-3 py-2">
            전체
        </button>
        <button type="button" data-status="not-submitted" aria-pressed="false" class="rounded-full border border-gray-300 bg-white text-2xs text-gray-700 px-3 py-2">
            주의 필요
        </button>
        <button type="button" data-status="pending" aria-pressed="false" class="rounded-full border border-gray-300 bg-white text-2xs text-gray-700 px-3 py-2">
            사용 중
        </button>
        <button type="button" data-status="supplement" aria-pressed="false" class="rounded-full border border-gray-300 bg-white text-2xs text-gray-700 px-3 py-2">
            중지
        </button>
    </div>

    <section class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <div class="overflow-x-auto">
            <table class="border-collapse min-w-250 w-full table-fixed text-left">
                <caption class="sr-only">도넛 운영자 권한 목록</caption>

                <colgroup>
                    <col class="w-[20%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[20%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                </colgroup>

                <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">권한번호 / 도넛</th>
                        <th scope="col">도트</th>
                        <th scope="col">역할</th>
                        <th scope="col">부여 권한</th>
                        <th scope="col">최근 사용</th>
                        <th scope="col">위험</th>
                        <th scope="col">상태</th>
                        <th scope="col">감독</th>
                    </tr>
                </thead>

                <tbody class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:whitespace-nowrap [&_td]:px-4 [&_td]:py-3">
                    <tr>
                        <td>
                            <span class="block font-bold">테니스 커뮤니티</span>
                            <span class="block text-zinc-400 font-medium">DLG-TENNIS-001 · DONUT-TENNIS</span>
                        </td>
                        <td>
                            <span class="block">홍길동</span>
                            <span class="block text-zinc-400">DOT-48102</span>
                        </td>
                        <td>
                            <span>도넛 운영자</span>
                        </td>
                        <td>
                            <span class="block">1개</span>
                            <span class="block text-zinc-400">가입 심사</span>
                        </td>
                        <td>
                            <span>오늘 11:20</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 정상</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 정상</span>
                        </td>
                        <td>
                            <button type="button" class="delegate-audit-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">홈베이킹 살롱</span>
                            <span class="block text-zinc-400 font-medium">DLG-BAKING-001 · DONUT-BAKING</span>
                        </td>
                        <td>
                            <span class="block">홍길동</span>
                            <span class="block text-zinc-400">DOT-48102</span>
                        </td>
                        <td>
                            <span>초대 대기</span>
                        </td>
                        <td>
                            <span class="block">2개</span>
                            <span class="block text-zinc-400">공지 · 추천상품</span>
                        </td>
                        <td>
                            <span>-</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 정상</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">● 심사 대기</span>
                        </td>
                        <td>
                            <button type="button" class="delegate-audit-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">캠핑 위켄드</span>
                            <span class="block text-zinc-400 font-medium">DLG-CAMPING-001 · DONUT-CAMPING</span>
                        </td>
                        <td>
                            <span class="block">홍길동</span>
                            <span class="block text-zinc-400">DOT-48102</span>
                        </td>
                        <td>
                            <span>도넛 운영자</span>
                        </td>
                        <td>
                            <span class="block">5개</span>
                            <span class="block text-zinc-400">가입 심사 · 회원 조회 · 콘텐츠 · 공지 · 추천상품</span>
                        </td>
                        <td>
                            <span>오늘 11:20</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 정상</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 정상</span>
                        </td>
                        <td>
                            <button type="button" class="delegate-audit-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<!-- 도넛 운영자 권한 모달 -->
<div id="delegate-audit-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="delegate-audit-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="delegate-audit-modal-container" role="dialog" aria-modal="true" aria-labelledby="delegate-audit-modal-title" class="relative z-10 w-full max-w-150 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div id="delegate-audit-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="delegate-audit-modal-title" class="text-sm font-bold text-gray-900">
                도넛 운영자 권한 상세
            </h3>

            <button type="button" id="delegate-audit-modal-close" aria-label="상품 검수 모달 닫기" class="flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="delegate-audit-modal-body" class="p-4">
            <div class="mt-4 overflow-hidden rounded-lg border border-gray-300">
                <dl class="text-left text-2xs [&>div+div]:border-t [&>div+div]:border-gray-200">
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">도넛</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">테니스 커뮤니티 · DONUT-TENNIS</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">도티</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">김도윤</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">운영 도트</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">홍길동 · DOT-48102</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">역할</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">도넛 운영자</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">부여일</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">2026.08.01</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">최근 사용</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">오늘 11:20</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">상태</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">
                            <span class="rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 정상</span>
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="mt-3 flex items-center justify-between rounded-lg border border-gray-300 p-3">
                <div>
                    <span class="block text-2xs font-bold text-gray-900">가입 심사</span>
                    <p class="mt-1 text-2xs text-gray-400">도트 관리자 화면에 노출되는 기능</p>
                </div>
                <span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">● 허용</span>
            </div>

            <div class="mt-3 rounded-lg bg-amber-50 p-3">
                <p class="mt-1 text-2xs text-amber-600">플랫폼 중지는 도티의 부여 기록을 삭제하지 않고 사용만 차단하며, 사유를 감사 로그에 남깁니다.</p>
            </div>   
        </div>

        <div id="delegate-audit-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="delegate-audit-modal-cancel" class="rounded-lg border border-gray-300 bg-white font-bold text-gray-900 px-4 py-3">
                닫기
            </button>
        </div>
    </div>
</div>

<script>
    $('.delegate-audit-modal-open').on('click', function() {
        $('#delegate-audit-modal').prop('hidden', false);
    });

    $('#delegate-audit-modal-close, #delegate-audit-modal-cancel, #delegate-audit-modal-backdrop').on('click', function() {
        $('#delegate-audit-modal').prop('hidden', true);
    });
</script>

<?php
require_once '../../admin.tail.php';

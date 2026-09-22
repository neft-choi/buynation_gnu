<?php
$sub_menu = '710100';
require_once './_common.php';

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '대시보드';
$title_sub = '러닝 메이트 운영 현황을 한눈에 확인합니다.';

require_once '../admin.head.php';
?>

<section>
    <h3 class="sr-only">도티 관리 대시보드</h3>

    <div class="mt-4 flex items-center gap-3 bg-blue-50 rounded-lg p-3">
        <span class="text-blue-500 font-bold">● 운영 요약</span>
        <p class="text-gray-600">가입 신청 2건이 검토를 기다리고 있으며, 고정 공지 1개가 노출 중입니다.</p>
    </div>

    <section>
        <h3 class="sound_only">요약 정보</h3>

        <div class="grid grid-cols-1 pc:grid-cols-4 gap-4 mt-4">
            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">가입 도트</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">3,940<span class="ml-1 text-sm">명</span></p>
                <span class="mt-3 block text-2xs text-green-600">▲ 지난달 대비 116명</span>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">가입 승인 대기</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">2<span class="ml-1 text-sm">건</span></p>
                <span class="mt-3 block text-2xs text-orange-600">가장 오래된 대기 2시간</span>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">이번 달 기여토핑</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">1,052,000<span class="ml-1 text-sm">토핑</span></p>
                <span class="mt-3 block text-2xs text-blue-600">플랫폼 집계 기준</span>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">지급 가능 토핑</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">610,000<span class="ml-1 text-sm">토핑</span></p>
                <span class="mt-3 block text-2xs text-emerald-600">정상 사용 가능</span>
            </div>
        </div>
    </section>

    <div class="mt-4 grid grid-cols-1 gap-4 pc:grid-cols-5">
        <section class="overflow-hidden rounded-lg border border-gray-300 bg-white pc:col-span-3">
            <header class="flex items-center justify-between gap-3 border-b border-gray-300 px-4 py-3">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">
                        가입 신청 대기
                    </h3>

                    <p class="mt-1 text-2xs text-gray-400">
                        최근 접수된 승인형 가입 신청
                    </p>
                </div>

                <a href="<?php echo G5_ADMIN_URL . '/dotty/join_request.php'; ?>" class="shrink-0 inline-flex items-center gap-1 text-2xs text-gray-500">
                    <span>전체 보기</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right preview-icon w-3 h-3">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </a>
            </header>

            <div class="overflow-x-auto">
                <table class="border-collapse min-w-180 w-full table-fixed text-left">
                    <colgroup>
                        <col class="w-[20%]">
                        <col class="w-[20%]">
                        <col class="w-[20%]">
                        <col class="w-[20%]">
                        <col class="w-[20%]">
                    </colgroup>

                    <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                        <tr>
                            <th scope="col">신청자</th>
                            <th scope="col">신청일</th>
                            <th scope="col">대기</th>
                            <th scope="col">상태</th>
                            <th scope="col">처리</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-900 font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:whitespace-nowrap [&_td]:px-4 [&_td]:py-3">
                        <tr>
                            <td>
                                <span class="block font-bold">캠핑 위켄드새싹</span>
                                <span class="block text-2xs text-zinc-400 font-normal">APP-CAMPING-2608-001</span>
                            </td>
                            <td>2026.08.10 10:20</td>
                            <td>대기 1시간</td>
                            <td>
                                <span class="w-fit bg-orange-100 rounded-full text-2xs text-orange-700 font-bold px-2 py-1">● 승인 대기</span>
                            </td>
                            <td>
                                <button type="button" class="border border-gray-300 rounded-lg text-2xs font-bold px-3 py-2">검토</button>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <span class="block font-bold">캠핑 위켄드친구</span>
                                <span class="block text-2xs text-zinc-400 font-normal">APP-CAMPING-2608-002</span>
                            </td>
                            <td>2026.08.09 11:20</td>
                            <td>대기 2시간</td>
                            <td>
                                <span class="w-fit bg-orange-100 rounded-full text-2xs text-orange-700 font-bold px-2 py-1">● 승인 대기</span>
                            </td>
                            <td>
                                <button type="button" class="border border-gray-300 rounded-lg text-2xs font-bold px-3 py-2">검토</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-lg border border-gray-300 bg-white pc:col-span-2">
            <header class="flex items-center justify-between gap-3 border-b border-gray-300 px-4 py-3">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">
                        주간 신규 가입
                    </h3>

                    <p class="mt-1 text-2xs text-gray-400">
                        최근 7일 승인 완료 기준
                    </p>
                </div>

                <span class="w-fit bg-emerald-100 rounded-lg text-2xs text-emerald-700 font-bold px-2 py-1">● 총 116명</span>
            </header>

            <div class="grid h-48 grid-cols-7 gap-3 px-4 py-5">
                <div class="flex min-w-0 flex-col">
                    <div class="flex flex-1 items-end justify-center">
                        <div class="h-[55%] w-full max-w-8 rounded-t-lg bg-gray-300"></div>
                    </div>
                    <span class="mt-2 text-center text-2xs text-gray-400">월</span>
                </div>

                <div class="flex min-w-0 flex-col">
                    <div class="flex flex-1 items-end justify-center">
                        <div class="h-[70%] w-full max-w-8 rounded-t-lg bg-gray-300"></div>
                    </div>
                    <span class="mt-2 text-center text-2xs text-gray-400">화</span>
                </div>

                <div class="flex min-w-0 flex-col">
                    <div class="flex flex-1 items-end justify-center">
                        <div class="h-[50%] w-full max-w-8 rounded-t-lg bg-gray-300"></div>
                    </div>
                    <span class="mt-2 text-center text-2xs text-gray-400">수</span>
                </div>

                <div class="flex min-w-0 flex-col">
                    <div class="flex flex-1 items-end justify-center">
                        <div class="h-[88%] w-full max-w-8 rounded-t-lg bg-gray-300"></div>
                    </div>
                    <span class="mt-2 text-center text-2xs text-gray-400">목</span>
                </div>

                <div class="flex min-w-0 flex-col">
                    <div class="flex flex-1 items-end justify-center">
                        <div class="h-full w-full max-w-8 rounded-t-lg bg-amber-300"></div>
                    </div>
                    <span class="mt-2 text-center text-2xs text-gray-400">금</span>
                </div>

                <div class="flex min-w-0 flex-col">
                    <div class="flex flex-1 items-end justify-center">
                        <div class="h-[78%] w-full max-w-8 rounded-t-lg bg-gray-300"></div>
                    </div>
                    <span class="mt-2 text-center text-2xs text-gray-400">토</span>
                </div>

                <div class="flex min-w-0 flex-col">
                    <div class="flex flex-1 items-end justify-center">
                        <div class="h-[65%] w-full max-w-8 rounded-t-lg bg-gray-300"></div>
                    </div>
                    <span class="mt-2 text-center text-2xs text-gray-400">일</span>
                </div>
            </div>
        </section>
    </div>

    <div class="mt-4 grid grid-cols-1 gap-4 pc:grid-cols-3">
        <section class="overflow-hidden rounded-lg border border-gray-300 bg-white">
            <header class="flex items-center justify-between gap-3 border-b border-gray-300 px-4 py-3">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">
                        최근 콘텐츠
                    </h3>

                    <p class="mt-1 text-2xs text-gray-400">
                        새 게시글과 주요 반응
                    </p>
                </div>

                <button type="button" class="shrink-0 inline-flex items-center gap-1 text-2xs text-gray-500">
                    <span>관리</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right preview-icon w-3 h-3">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </button>
            </header>

            <ul class="divide-y divide-gray-200 px-4">
                <li class="flex items-center justify-between gap-3 py-4">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-sm text-gray-900">
                            01
                        </span>

                        <div class="min-w-0">
                            <span class="block truncate font-bold text-gray-900">캠핑 위켄드 운영 안내</span>
                            <span class="mt-1 block text-2xs text-gray-400">도티 김도윤 · 댓글 38</span>
                        </div>
                    </div>

                    <span class="shrink-0 text-2xs text-gray-400">08.03</span>
                </li>

                <li class="flex items-center justify-between gap-3 py-4">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-sm text-gray-900">
                            01
                        </span>

                        <div class="min-w-0">
                            <span class="block truncate font-bold text-gray-900">캠핑 위켄드 이벤트 모임</span>
                            <span class="mt-1 block text-2xs text-gray-400">운영자 김도현 · 댓글 12</span>
                        </div>
                    </div>

                    <span class="shrink-0 text-2xs text-gray-400">08.02</span>
                </li>

                <li class="flex items-center justify-between gap-3 py-4">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-sm text-gray-900">
                            02
                        </span>

                        <div class="min-w-0">
                            <span class="block truncate font-bold text-gray-900">캠핑 위켄드 활동 후기</span>
                            <span class="mt-1 block text-2xs text-gray-400">최서진 · 댓글 21</span>
                        </div>
                    </div>

                    <span class="shrink-0 text-2xs text-gray-400">08.01</span>
                </li>
            </ul>
        </section>

        <section class="overflow-hidden rounded-lg border border-gray-300 bg-white">
            <header class="flex items-center justify-between gap-3 border-b border-gray-300 px-4 py-3">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">
                        공지 고정 현황
                    </h3>

                    <p class="mt-1 text-2xs text-gray-400">
                        커뮤니티 상단 노출
                    </p>
                </div>

                <button type="button" class="shrink-0 inline-flex items-center gap-1 text-2xs text-gray-500">
                    <span>관리</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right preview-icon w-3 h-3">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </button>
            </header>

            <div class="flex items-center justify-between gap-3 p-4">
                <div class="flex min-w-0 items-center gap-3">
                    <div aria-hidden="true" class="h-8 w-8 shrink-0 rounded-lg bg-gray-100"></div>

                    <div class="min-w-0">
                        <span class="block truncate font-bold text-gray-900">캠핑 위켄드 운영 안내</span>
                        <span class="mt-1 block text-2xs text-gray-400">고정 순서 1 · 조회 482</span>
                    </div>
                </div>

                <span class="shrink-0 text-2xs text-gray-400">08.09</span>
            </div>
        </section>

        <section class="overflow-hidden rounded-lg border border-gray-300 bg-white">
            <header class="flex items-center justify-between gap-3 border-b border-gray-300 px-4 py-3">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">
                        추천상품 현황
                    </h3>

                    <p class="mt-1 text-2xs text-gray-400">
                        커뮤니티 설정 중인 상품
                    </p>
                </div>

                <button type="button" class="shrink-0 inline-flex items-center gap-1 text-2xs text-gray-500">
                    <span>관리</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right preview-icon w-3 h-3">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </button>
            </header>

            <div class="flex items-center justify-between gap-3 p-4">
                <div class="flex min-w-0 items-center gap-3">
                    <div aria-hidden="true" class="h-8 w-8 shrink-0 rounded-lg bg-gray-100"></div>

                    <div class="min-w-0">
                        <span class="block truncate font-bold text-gray-900">유기농 그래놀라 500g</span>
                        <span class="mt-1 block text-2xs text-gray-400">그린테이블 · 18,900원</span>
                    </div>
                </div>

                <span class="shrink-0 text-2xs text-gray-400">일반</span>
            </div>
        </section>
    </div>
</section>

<?php
require_once '../admin.tail.php';

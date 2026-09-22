<?php
$sub_menu = '910100';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '대시보드';
require_once '../admin.head.php';
?>

<section>
    <h2 class="sr-only">플랫폼 관리 대시보드</h2>

    <div class="mt-4 flex items-center gap-3 bg-blue-50 rounded-lg p-3">
        <span class="text-blue-500 font-bold">처리 중심 대시보드</span>
        <p class="text-gray-600">시스템은 검토할 사안을 개별 업무로 만들고 상태·근거를 수집합니다. 담당자의 판단이 필요한 업무는 자동 승인·확정하지 않습니다.</p>
    </div>

    <section>
        <h3 class="sound_only">플랫폼 관리 요약 정보</h3>

        <div class="grid grid-cols-1 pc:grid-cols-4 gap-4 mt-4">
            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">처리 업무</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">55<span class="ml-1 text-sm">건</span></p>
                <span class="mt-3 block text-2xs text-green-600">개별 사안 기준</span>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">검토·보류</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">29<span class="ml-1 text-sm">건</span></p>
                <span class="mt-3 block text-2xs text-indigo-600">승인·판단·정책</span>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">기한·주의</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">8<span class="ml-1 text-sm">건</span></p>
                <span class="mt-3 block text-2xs text-red-600">우선 확인</span>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">처리 완료</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">6<span class="ml-1 text-sm">건</span></p>
                <span class="mt-3 block text-2xs text-gray-600">완료함·처리 로그 보관</span>
            </div>
        </div>
    </section>


    <section class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <header class="flex items-center justify-between gap-3 border-b border-gray-300 px-4 py-3">
            <div>
                <h3 class="text-sm font-bold text-gray-900">
                    처리 업무함
                </h3>

                <p class="mt-1 text-2xs text-gray-400">
                    묶음 건수가 아니라 실제 사안 한 건씩 표시합니다.
                </p>
            </div>

            <a href="<?php echo G5_ADMIN_URL . '/dot/join_request.php'; ?>" class="shrink-0 inline-flex items-center gap-1 text-2xs text-gray-500">
                <span>전체 55건</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right preview-icon w-3 h-3">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>
        </header>

        <div class="overflow-x-auto">
            <table class="border-collapse min-w-250 w-full table-fixed text-left">
                <colgroup>
                    <col class="w-[13%]">
                    <col class="w-[18%]">
                    <col class="w-[13%]">
                    <col class="w-[24%]">
                    <col class="w-[8%]">
                    <col class="w-[8%]">
                    <col class="w-[8%]">
                    <col class="w-[8%]">
                </colgroup>

                <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">업무 ID / 유형</th>
                        <th scope="col">대상</th>
                        <th scope="col">전달 구분·대상</th>
                        <th scope="col">검토 사유·최근 처리</th>
                        <th scope="col">기한</th>
                        <th scope="col">담당자</th>
                        <th scope="col">상태</th>
                        <th scope="col">처리</th>
                    </tr>
                </thead>

                <tbody class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:whitespace-nowrap [&_td]:px-4 [&_td]:py-3">
                    <tr>
                        <td>
                            <span class="block font-bold">ESC-0104</span>
                            <span class="block text-zinc-400 font-normal">보류·에스크로 검토</span>
                        </td>
                        <td>
                            <span class="block font-bold">분쟁 DSP-0182</span>
                        </td>
                        <td>
                            <span class="w-fit bg-indigo-100 rounded-full text-indigo-700 font-bold px-2 py-1">전달 선택</span>
                            <span class="mt-1 block text-zinc-400 font-normal">관련 당사자 · 관리자 알림</span>
                        </td>
                        <td>
                            <span>반품비 분쟁 · 36,000원 보류 근거와 해제 조건 확인</span>
                        </td>
                        <td>
                            <span class="w-fit bg-red-100 rounded-full text-red-700 font-bold px-2 py-1">● 우선 확인</span>
                        </td>
                        <td>
                            <span>정산팀 김OO</span>
                        </td>
                        <td>
                            <span class="w-fit bg-amber-100 rounded-full text-amber-700 font-bold px-2 py-1">● 보류</span>
                        </td>
                        <td>
                            <span class="w-fit bg-amber-300 rounded-lg text-gray-900 font-bold p-2">처리</span>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span class="block font-bold">ESC-0112</span>
                            <span class="block text-zinc-400 font-normal">보류·에스크로 검토</span>
                        </td>
                        <td>
                            <span class="block font-bold">주문 20260810009871</span>
                        </td>
                        <td>
                            <span class="w-fit bg-indigo-100 rounded-full text-indigo-700 font-bold px-2 py-1">전달 선택</span>
                            <span class="mt-1 block text-zinc-400 font-normal">관련 당사자 · 관리자 알림</span>
                        </td>
                        <td>
                            <span>이상 거래 검토 · 184,000원 보류 근거와 해제 조건 확인</span>
                        </td>
                        <td>
                            <span class="w-fit bg-red-100 rounded-full text-red-700 font-bold px-2 py-1">● 우선 확인</span>
                        </td>
                        <td>
                            <span>정산팀 김OO</span>
                        </td>
                        <td>
                            <span class="w-fit bg-amber-100 rounded-full text-amber-700 font-bold px-2 py-1">● 보류</span>
                        </td>
                        <td>
                            <span class="w-fit bg-amber-300 rounded-lg text-gray-900 font-bold p-2">처리</span>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span class="block font-bold">RPT-DOTI-48H</span>
                            <span class="block text-zinc-400 font-normal">도티 신고 미처리</span>
                        </td>
                        <td>
                            <span class="block font-bold">RPT-0221 · 48시간 경과</span>
                        </td>
                        <td>
                            <span class="w-fit bg-amber-100 rounded-full text-amber-700 font-bold px-2 py-1">전달 필수</span>
                            <span class="mt-1 block text-zinc-400 font-normal">대상 도티 · 관리자 알림</span>
                        </td>
                        <td>
                            <span>도티 처리 기한 초과 · 플랫폼 5영업일 검토로 에스컬레이션</span>
                        </td>
                        <td>
                            <span class="w-fit bg-red-100 rounded-full text-red-700 font-bold px-2 py-1">● 즉시</span>
                        </td>
                        <td>
                            <span>커뮤니티 팀</span>
                        </td>
                        <td>
                            <span class="w-fit bg-amber-100 rounded-full text-amber-700 font-bold px-2 py-1">● 처리 필요</span>
                        </td>
                        <td>
                            <span class="w-fit bg-amber-300 rounded-lg text-gray-900 font-bold p-2">처리</span>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span class="block font-bold">SYS-SABANG-260821</span>
                            <span class="block text-zinc-400 font-normal">사방넷 연동 실패</span>
                        </td>
                        <td>
                            <span class="block font-bold">상품·주문 동기화 배치 #240821-04</span>
                        </td>
                        <td>
                            <span class="w-fit bg-zinc-100 rounded-full text-zinc-700 font-bold px-2 py-1">내부 전용</span>
                            <span class="mt-1 block text-zinc-400 font-normal">내부 전용 · 없음</span>
                        </td>
                        <td>
                            <span>재시도 3회 실패 · 중복 반영 없이 수동 재처리 여부 확인</span>
                        </td>
                        <td>
                            <span class="w-fit bg-red-100 rounded-full text-red-700 font-bold px-2 py-1">● 30분 내</span>
                        </td>
                        <td>
                            <span>연동 운영팀</span>
                        </td>
                        <td>
                            <span class="w-fit bg-amber-100 rounded-full text-amber-700 font-bold px-2 py-1">● 처리 필요</span>
                        </td>
                        <td>
                            <span class="w-fit bg-amber-300 rounded-lg text-gray-900 font-bold p-2">처리</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <div class="mt-4 grid grid-cols-1 gap-4 pc:grid-cols-5">
        <section class="overflow-hidden rounded-lg border border-gray-300 bg-white pc:col-span-3">
            <header class="flex items-center justify-between gap-3 border-b border-gray-300 px-4 py-3">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">
                        최근 처리 로그
                    </h3>

                    <p class="mt-1 text-2xs text-gray-400">
                        완료 결과와 민감 작업 이력
                    </p>
                </div>

                <button type="button" class="shrink-0 inline-flex items-center gap-1 text-2xs text-gray-500">
                    <span>전체</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right preview-icon w-3 h-3">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </button>
            </header>

            <ul class="divide-y divide-gray-200 px-4">
                <li class="flex items-center justify-between gap-3 py-4">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-sm text-gray-900">

                        </span>

                        <div class="min-w-0">
                            <span class="block truncate font-bold text-gray-900">상품 승인</span>
                            <span class="mt-1 block text-2xs text-gray-400">PRD-RV-260806-003 변경 승인 · 판매 반영</span>
                        </div>
                    </div>

                    <span class="shrink-0 text-2xs text-gray-400">16:04</span>
                </li>

                <li class="flex items-center justify-between gap-3 py-4">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-sm text-gray-900">

                        </span>

                        <div class="min-w-0">
                            <span class="block truncate font-bold text-gray-900">도티 심사</span>
                            <span class="mt-1 block text-2xs text-gray-400">DTV-260808-009 계좌 서류 보완 요청</span>
                        </div>
                    </div>

                    <span class="shrink-0 text-2xs text-gray-400">14:10</span>
                </li>

                <li class="flex items-center justify-between gap-3 py-4">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-sm text-gray-900">

                        </span>

                        <div class="min-w-0">
                            <span class="block truncate font-bold text-gray-900">정산 검토</span>
                            <span class="mt-1 block text-2xs text-gray-400">SET-D-2607-041 지급 자격 확인</span>
                        </div>
                    </div>

                    <span class="shrink-0 text-2xs text-gray-400">11:30</span>
                </li>

                <li class="flex items-center justify-between gap-3 py-4">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-sm text-gray-900">

                        </span>

                        <div class="min-w-0">
                            <span class="block truncate font-bold text-gray-900">기한 처리</span>
                            <span class="mt-1 block text-2xs text-gray-400">DN-00802 14일 미제출 · 비사업자 자동 전환</span>
                        </div>
                    </div>

                    <span class="shrink-0 text-2xs text-gray-400">09:18</span>
                </li>
            </ul>
        </section>

        <section class="overflow-hidden rounded-lg border border-gray-300 bg-white pc:col-span-2">
            <header class="flex items-center justify-between gap-3 border-b border-gray-300 px-4 py-3">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">
                        시스템 상태
                    </h3>

                    <p class="mt-1 text-2xs text-gray-400">
                        자동 수집·연동 상태만 표시
                    </p>
                </div>

                <button type="button" class="shrink-0 inline-flex items-center gap-1 text-2xs text-gray-500">
                    <span>상세</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right preview-icon w-3 h-3">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </button>
            </header>

            <ul class="divide-y divide-gray-200 px-4">
                <li class="flex items-center justify-between gap-3 py-4">
                    <div class="min-w-0">
                        <span class="block truncate font-bold text-gray-900">주문·결제 이벤트</span>
                        <span class="mt-1 block text-2xs text-gray-400">최근 수집 15:51</span>
                    </div>

                    <span class="shrink-0 w-fit bg-emerald-100 rounded-full text-emerald-700 text-2xs font-bold px-2 py-1">● 정상</span>
                </li>

                <li class="flex items-center justify-between gap-3 py-4">
                    <div class="min-w-0">
                        <span class="block truncate font-bold text-gray-900">토핑 발생 로그</span>
                        <span class="mt-1 block text-2xs text-gray-400">발생 근거 누락 0건</span>
                    </div>

                    <span class="shrink-0 w-fit bg-emerald-100 rounded-full text-emerald-700 text-2xs font-bold px-2 py-1">● 정상</span>
                </li>

                <li class="flex items-center justify-between gap-3 py-4">
                    <div class="min-w-0">
                        <span class="block truncate font-bold text-gray-900">사방넷·배송 연동</span>
                        <span class="mt-1 block text-2xs text-gray-400">확인 필요 1건</span>
                    </div>

                    <span class="shrink-0 w-fit bg-amber-100 rounded-full text-amber-700 text-2xs font-bold px-2 py-1">● 확인</span>
                </li>

                <li class="flex items-center justify-between gap-3 py-4">
                    <div class="min-w-0">
                        <span class="block truncate font-bold text-gray-900">알림 발송</span>
                        <span class="mt-1 block text-2xs text-gray-400">재시도 3건</span>
                    </div>

                    <span class="shrink-0 w-fit bg-amber-100 rounded-full text-amber-700 text-2xs font-bold px-2 py-1">● 확인</span>
                </li>
            </ul>
        </section>
    </div>
</section>

<?php
require_once '../admin.tail.php';

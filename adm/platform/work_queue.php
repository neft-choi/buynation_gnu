<?php
$sub_menu = '920100';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '처리 업무함';
require_once '../admin.head.php';
?>

<section>
    <h2 class="sr-only">처리 업무함</h2>

    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="text-gray-600 font-normal">브랜드 서류부터 정책 결정까지 각 사안을 한 줄씩 처리하고 내부 메모와 전달 사유를 분리합니다.</p>

        <button type="button" class="shrink-0 border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
            <span>업무 새로고침</span>
        </button>
    </div>

    <div class="mt-4 flex items-center gap-3 bg-amber-50 rounded-lg text-2xs p-3">
        <span class="text-amber-600 font-bold">처리 방식</span>
        <p class="text-gray-600">내부 메모는 플랫폼에만 남습니다. 상대방 전달 사유는 지정된 수신자에게만 전달되며, 내부 전용 업무에는 입력란이 열리지 않습니다.</p>
    </div>

    <section>
        <h3 class="sound_only">요약 정보</h3>

        <div class="grid grid-cols-2 pc:grid-cols-4 gap-4 mt-4">
            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">처리 중</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">55</p>
                <span class="mt-3 block text-2xs text-gray-500">현재 업무함에 남은 개별 사안</span>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">기한·주의</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">8</p>
                <span class="mt-3 block text-2xs text-gray-500">기한 초과 또는 영향도가 큰 사안</span>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">진행·보류</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">15</p>
                <span class="mt-3 block text-2xs text-gray-500">회신 대기 11 · 판단 보류 4</span>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">담당 미지정</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">1</p>
                <span class="mt-3 block text-2xs text-gray-500">담당자를 먼저 배정할 사안</span>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">상대방 전달 필수</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">37</p>
                <span class="mt-3 block text-2xs text-gray-500">결과 저장 전 전달 사유 필수</span>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">상대방 전달 선택</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">7</p>
                <span class="mt-3 block text-2xs text-gray-500">필요할 때만 안내 여부 선택</span>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">내부 전용</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">11</p>
                <span class="mt-3 block text-2xs text-gray-500">내부 메모만 기록·외부 미노출</span>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">완료 업무 항목</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">6</p>
                <span class="mt-3 block text-2xs text-gray-500">항목별 완료 이력 조회 가능</span>
            </div>
        </div>
    </section>

    <div class="mt-4 flex items-center gap-3 border border-gray-300 rounded-lg text-2xs p-3">
        <span class="font-bold">대표 사례 범위</span>
        <div class="flex items-center gap-2">
            <span class="block w-fit bg-gray-100 rounded-full px-2 py-1">서류·상품·기획전</span>
            <span class="block w-fit bg-gray-100 rounded-full px-2 py-1">도티·계정·승계</span>
            <span class="block w-fit bg-gray-100 rounded-full px-2 py-1">주문·배송·분쟁</span>
            <span class="block w-fit bg-gray-100 rounded-full px-2 py-1">결제·정산·에스크로</span>
            <span class="block w-fit bg-gray-100 rounded-full px-2 py-1">시스템·보안</span>
            <span class="block w-fit bg-gray-100 rounded-full px-2 py-1">정책 결정</span>
            <span class="block w-fit bg-gray-100 rounded-full px-2 py-1">전달 필수·선택·내부 전용</span>
            <span class="block w-fit bg-gray-100 rounded-full px-2 py-1">승인·보완·반려·보류·완료</span>
        </div>
    </div>

    <div id="work-queue-tabs" role="tablist" aria-label="업무 처리 상태" class="mt-4 flex w-fit rounded-lg bg-gray-100 p-1">
        <button type="button" role="tab" id="work-queue-processing-tab" aria-selected="true" aria-controls="work-queue-processing-panel" tabindex="0" class="rounded-lg px-3 py-2 text-2xs font-bold text-gray-600 aria-selected:bg-white aria-selected:text-gray-900">
            <span>처리 중 55</span>
        </button>

        <button type="button" role="tab" id="work-queue-completed-tab" aria-selected="false" aria-controls="work-queue-completed-panel" tabindex="-1" class="rounded-lg px-3 py-2 text-2xs font-bold text-gray-600 aria-selected:bg-white aria-selected:text-gray-900">
            <span>처리 완료 6</span>
        </button>
    </div>

    <section role="tabpanel" id="work-queue-processing-panel" class="mt-4">
        <form method="get" class="flex flex-col gap-3 pc:flex-row pc:items-center">
            <div class="flex-1 min-w-0 flex items-center border border-gray-300 rounded-lg bg-white">
                <label for="work-queue-search" class="sound_only">업무 ID·대상·메모·전달 사유 검색</label>

                <input type="search" id="work-queue-search" name="q" class="flex-1 min-w-0 outline-none p-3" placeholder="업무 ID·대상·메모·전달 사유 검색">

                <button type="submit" class="shrink-0 p-3 text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    <span class="sound_only">검색</span>
                </button>
            </div>

            <select id="work-queue-status" name="status" aria-label="상태 필터" class="shrink-0 rounded-lg border border-gray-300 bg-white text-gray-900">
                <option value="all">모든 상태</option>
                <option value="action">처리 필요</option>
                <option value="review">검토·정책</option>
                <option value="progress">진행 확인</option>
                <option value="hold">보류</option>
                <option value="urgent">기한·주의</option>
                <option value="unassigned">담당 미지정</option>
            </select>

            <select id="work-queue-communication" name="communication" aria-label="전달 구분 필터" class="shrink-0 rounded-lg border border-gray-300 bg-white text-gray-900">
                <option value="all">모든 전달 구분</option>
                <option value="required">전달 필수</option>
                <option value="optional">전달 선택</option>
                <option value="internal">내부 전용</option>
            </select>

            <div class="min-w-0 overflow-x-auto">
                <fieldset class="flex items-center gap-2 text-2xs font-bold text-nowrap">
                    <legend class="sr-only">업무 분류 필터</legend>
                    <div>
                        <input type="radio" id="work-queue-category-all" name="category" value="all" class="peer sr-only" checked>
                        <label for="work-queue-category-all" class="block cursor-pointer rounded-full border border-gray-300 bg-white text-gray-600 px-3 py-2 peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white peer-focus-visible:inset-ring-2 peer-focus-visible:inset-ring-blue-500">전체</label>
                    </div>
                    <div>
                        <input type="radio" id="work-queue-category-brand" name="category" value="brand" class="peer sr-only">
                        <label for="work-queue-category-brand" class="block cursor-pointer rounded-full border border-gray-300 bg-white text-gray-600 px-3 py-2 peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white peer-focus-visible:inset-ring-2 peer-focus-visible:inset-ring-blue-500">브랜드·상품</label>
                    </div>
                    <div>
                        <input type="radio" id="work-queue-category-community" name="category" value="community" class="peer sr-only">
                        <label for="work-queue-category-community" class="block cursor-pointer rounded-full border border-gray-300 bg-white text-gray-600 px-3 py-2 peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white peer-focus-visible:inset-ring-2 peer-focus-visible:inset-ring-blue-500">도티·도넛</label>
                    </div>
                    <div>
                        <input type="radio" id="work-queue-category-order" name="category" value="order" class="peer sr-only">
                        <label for="work-queue-category-order" class="block cursor-pointer rounded-full border border-gray-300 bg-white text-gray-600 px-3 py-2 peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white peer-focus-visible:inset-ring-2 peer-focus-visible:inset-ring-blue-500">주문·배송</label>
                    </div>
                    <div>
                        <input type="radio" id="work-queue-category-dispute" name="category" value="dispute" class="peer sr-only">
                        <label for="work-queue-category-dispute" class="block cursor-pointer rounded-full border border-gray-300 bg-white text-gray-600 px-3 py-2 peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white peer-focus-visible:inset-ring-2 peer-focus-visible:inset-ring-blue-500">신고·분쟁</label>
                    </div>
                    <div>
                        <input type="radio" id="work-queue-category-money" name="category" value="money" class="peer sr-only">
                        <label for="work-queue-category-money" class="block cursor-pointer rounded-full border border-gray-300 bg-white text-gray-600 px-3 py-2 peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white peer-focus-visible:inset-ring-2 peer-focus-visible:inset-ring-blue-500">결제·정산</label>
                    </div>
                    <div>
                        <input type="radio" id="work-queue-category-account" name="category" value="account" class="peer sr-only">
                        <label for="work-queue-category-account" class="block cursor-pointer rounded-full border border-gray-300 bg-white text-gray-600 px-3 py-2 peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white peer-focus-visible:inset-ring-2 peer-focus-visible:inset-ring-blue-500">계정·권한</label>
                    </div>
                    <div>
                        <input type="radio" id="work-queue-category-system" name="category" value="system" class="peer sr-only">
                        <label for="work-queue-category-system" class="block cursor-pointer rounded-full border border-gray-300 bg-white text-gray-600 px-3 py-2 peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white peer-focus-visible:inset-ring-2 peer-focus-visible:inset-ring-blue-500">시스템·보안</label>
                    </div>
                    <div>
                        <input type="radio" id="work-queue-category-policy" name="category" value="policy" class="peer sr-only">
                        <label for="work-queue-category-policy" class="block cursor-pointer rounded-full border border-gray-300 bg-white text-gray-600 px-3 py-2 peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white peer-focus-visible:inset-ring-2 peer-focus-visible:inset-ring-blue-500">정책</label>
                    </div>
                </fieldset>
            </div>
        </form>

        <section class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
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
                                <button type="button" id="work-queue-modal-open" aria-haspopup="dialog" aria-controls="work-queue-modal-container" class="w-fit bg-amber-300 rounded-lg text-gray-900 font-bold px-3 py-1">처리</button>
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
                                <button type="button" id="work-queue-modal-open" aria-haspopup="dialog" aria-controls="work-queue-modal-container" class="w-fit bg-amber-300 rounded-lg text-gray-900 font-bold px-3 py-1">처리</button>
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
                                <button type="button" id="work-queue-modal-open" aria-haspopup="dialog" aria-controls="work-queue-modal-container" class="w-fit bg-amber-300 rounded-lg text-gray-900 font-bold px-3 py-1">처리</button>
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
                                <button type="button" id="work-queue-modal-open" aria-haspopup="dialog" aria-controls="work-queue-modal-container" class="w-fit bg-amber-300 rounded-lg text-gray-900 font-bold px-3 py-1">처리</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </section>

    <section role="tabpanel" id="work-queue-completed-panel" class="mt-4" hidden>
        <form method="get" class="flex flex-col gap-3 pc:flex-row pc:items-center">
            <div class="flex min-w-0 flex-1 items-center rounded-lg border border-gray-300 bg-white">
                <label for="work-queue-completed-search" class="sound_only">완료 업무 검색</label>

                <input type="search" id="work-queue-completed-search" name="q" class="min-w-0 flex-1 outline-none p-3" placeholder="업무 ID·대상·메모·전달 사유 검색">

                <button type="submit" class="shrink-0 p-3 text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    <span class="sound_only">검색</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <ul class="flex items-center gap-2 text-2xs font-bold text-nowrap">
                    <li>
                        <button type="button" aria-pressed="true" class="rounded-full border border-transparent bg-gray-900 px-3 py-2 text-white">전체</button>
                    </li>
                    <li>
                        <button type="button" aria-pressed="false" class="rounded-full border border-gray-300 bg-white px-3 py-2 text-gray-600">브랜드·상품</button>
                    </li>
                    <li>
                        <button type="button" aria-pressed="false" class="rounded-full border border-gray-300 bg-white px-3 py-2 text-gray-600">도티·도넛</button>
                    </li>
                    <li>
                        <button type="button" aria-pressed="false" class="rounded-full border border-gray-300 bg-white px-3 py-2 text-gray-600">주문·배송</button>
                    </li>
                    <li>
                        <button type="button" aria-pressed="false" class="rounded-full border border-gray-300 bg-white px-3 py-2 text-gray-600">신고·분쟁</button>
                    </li>
                    <li>
                        <button type="button" aria-pressed="false" class="rounded-full border border-gray-300 bg-white px-3 py-2 text-gray-600">결제·정산</button>
                    </li>
                    <li>
                        <button type="button" aria-pressed="false" class="rounded-full border border-gray-300 bg-white px-3 py-2 text-gray-600">계정·권한</button>
                    </li>
                    <li>
                        <button type="button" aria-pressed="false" class="rounded-full border border-gray-300 bg-white px-3 py-2 text-gray-600">시스템·보안</button>
                    </li>
                    <li>
                        <button type="button" aria-pressed="false" class="rounded-full border border-gray-300 bg-white px-3 py-2 text-gray-600">정책</button>
                    </li>
                </ul>
            </div>
        </form>

        <div class="mt-4 flex items-center gap-3 bg-emerald-50 rounded-lg text-2xs p-3">
            <span class="text-emerald-700 font-bold">항목별 완료 업무</span>
            <p class="text-emerald-700 ">완료된 업무는 삭제하지 않고 업무 유형별로 묶어 내부 메모·상대방 전달 사유·전달 상태까지 보관합니다.</p>
        </div>

        <ul class="mt-4 grid grid-cols-1 gap-3 pc:grid-cols-4">
            <li>
                <button type="button" aria-pressed="true" class="flex w-full items-center justify-between rounded-lg border-2 border-gray-900 bg-white p-3 text-left">
                    <div>
                        <p class="font-bold text-gray-900">전체 항목</p>
                        <p class="mt-1 text-2xs text-gray-400">외부 전달 4건 · 내부 전용 2건</p>
                    </div>
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gray-900 text-2xs font-bold text-white">6</span>
                </button>
            </li>

            <li>
                <button type="button" aria-pressed="false" class="flex w-full items-center justify-between rounded-lg border border-gray-300 bg-white p-3 text-left">
                    <div>
                        <p class="font-bold text-gray-900">상품 검수</p>
                        <p class="mt-1 text-2xs text-gray-400">외부 전달 1건 · 내부 전용 0건</p>
                    </div>
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gray-900 text-2xs font-bold text-white">1</span>
                </button>
            </li>

            <li>
                <button type="button" aria-pressed="false" class="flex w-full items-center justify-between rounded-lg border border-gray-300 bg-white p-3 text-left">
                    <div>
                        <p class="font-bold text-gray-900">커뮤니티 신고</p>
                        <p class="mt-1 text-2xs text-gray-400">외부 전달 0건 · 내부 전용 1건</p>
                    </div>
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gray-900 text-2xs font-bold text-white">1</span>
                </button>
            </li>

            <li>
                <button type="button" aria-pressed="false" class="flex w-full items-center justify-between rounded-lg border border-gray-300 bg-white p-3 text-left">
                    <div>
                        <p class="font-bold text-gray-900">브랜드 사업자 서류</p>
                        <p class="mt-1 text-2xs text-gray-400">외부 전달 1건 · 내부 전용 0건</p>
                    </div>
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gray-900 text-2xs font-bold text-white">1</span>
                </button>
            </li>

            <li>
                <button type="button" aria-pressed="false" class="flex w-full items-center justify-between rounded-lg border border-gray-300 bg-white p-3 text-left">
                    <div>
                        <p class="font-bold text-gray-900">도티 역할 부여 예외</p>
                        <p class="mt-1 text-2xs text-gray-400">외부 전달 1건 · 내부 전용 0건</p>
                    </div>
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gray-900 text-2xs font-bold text-white">1</span>
                </button>
            </li>

            <li>
                <button type="button" aria-pressed="false" class="flex w-full items-center justify-between rounded-lg border border-gray-300 bg-white p-3 text-left">
                    <div>
                        <p class="font-bold text-gray-900">도넛 정산 검토</p>
                        <p class="mt-1 text-2xs text-gray-400">외부 전달 1건 · 내부 전용 0건</p>
                    </div>
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gray-900 text-2xs font-bold text-white">1</span>
                </button>
            </li>

            <li>
                <button type="button" aria-pressed="false" class="flex w-full items-center justify-between rounded-lg border border-gray-300 bg-white p-3 text-left">
                    <div>
                        <p class="font-bold text-gray-900">사방넷 연동 실패</p>
                        <p class="mt-1 text-2xs text-gray-400">외부 전달 0건 · 내부 전용 1건</p>
                    </div>
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gray-900 text-2xs font-bold text-white">1</span>
                </button>
            </li>
        </ul>

        <section class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
            <div class="overflow-x-auto">
                <table class="border-collapse min-w-400 w-full table-fixed text-left">
                    <colgroup>
                        <col class="w-[10%]">
                        <col class="w-[16%]">
                        <col class="w-[8%]">
                        <col class="w-[14%]">
                        <col class="w-[18%]">
                        <col class="w-[16%]">
                        <col class="w-[10%]">
                        <col class="w-[8%]">
                    </colgroup>

                    <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                        <tr>
                            <th scope="col">업무 ID / 유형</th>
                            <th scope="col">대상</th>
                            <th scope="col">처리 결과</th>
                            <th scope="col">전달 구분·대상</th>
                            <th scope="col">내부 처리 메모</th>
                            <th scope="col">상대방 전달 사유</th>
                            <th scope="col">완료</th>
                            <th scope="col">확인</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:whitespace-normal [&_td]:px-4 [&_td]:py-3">
                        <tr>
                            <td>
                                <span class="block font-bold">PRD-RV-260806-003</span>
                                <span class="block text-zinc-400 font-medium">상품 검수</span>
                            </td>
                            <td>
                                <span class="block font-bold">패브릭 수납 바스켓 · P51188</span>
                            </td>
                            <td>
                                <span class="w-fit bg-emerald-50 rounded-full text-emerald-700 font-bold px-2 py-1">● 승인</span>
                            </td>
                            <td>
                                <span class="w-fit bg-amber-50 rounded-full text-amber-700 font-bold px-2 py-1">전달 필수</span>
                                <span class="mt-1 block text-zinc-400 font-normal">브랜드 · 노르딕홈 · 관리자 알림</span>
                            </td>
                            <td>
                                <span>필수 정보·배송그룹·표시사항 체크리스트 확인</span>
                            </td>
                            <td>
                                <span class="block font-normal">상품 검수가 완료되어 판매중으로 전환되었습니다.</span>
                                <span class="block text-zinc-400 font-normal">전달 완료</span>
                            </td>
                            <td>
                                <span class="block font-normal">2026.08.11 09:18</span>
                                <span class="block text-zinc-400 font-normal">상품 운영팀 박OO</span>
                            </td>
                            <td>
                                <span class="w-fit border border-gray-300 rounded-lg text-gray-900 font-bold p-2">상세</span>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <span class="block font-bold">RPT-0218</span>
                                <span class="block text-zinc-400 font-medium">커뮤니티 신고</span>
                            </td>
                            <td>
                                <span class="block font-bold">건강한 식탁 · 댓글 #9122</span>
                            </td>
                            <td>
                                <span class="w-fit bg-emerald-50 rounded-full text-emerald-700 font-bold px-2 py-1">● 완료</span>
                            </td>
                            <td>
                                <span class="w-fit bg-gray-50 rounded-full text-gray-700 font-bold px-2 py-1">내부 전용</span>
                                <span class="mt-1 block text-zinc-400 font-normal">내부 전용 · 없음</span>
                            </td>
                            <td>
                                <span>도티의 선조치와 증빙을 확인하여 플랫폼 개입 없이 종결</span>
                            </td>
                            <td>
                                <span class="block text-zinc-400 font-normal">전달 없음</span>
                                <span class="block text-zinc-400 font-normal">전달 없음</span>
                            </td>
                            <td>
                                <span class="block font-normal">2026.08.11 08:42</span>
                                <span class="block text-zinc-400 font-normal">커뮤니티팀 이OO</span>
                            </td>
                            <td>
                                <span class="w-fit border border-gray-300 rounded-lg text-gray-900 font-bold p-2">상세</span>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <span class="block font-bold">BR-VRF-260805-REJ</span>
                                <span class="block text-zinc-400 font-normal">브랜드 사업자 서류</span>
                            </td>
                            <td>
                                <span class="block font-bold">샘플 브랜드 · BRD-00101</span>
                            </td>
                            <td>
                                <span class="w-fit bg-red-50 rounded-full text-red-700 font-bold px-2 py-1">● 반려</span>
                            </td>
                            <td>
                                <span class="w-fit bg-amber-50 rounded-full text-amber-700 font-bold px-2 py-1">전달 필수</span>
                                <span class="mt-1 block text-zinc-400 font-normal">브랜드 · 샘플 브랜드 · 관리자 알림·이메일</span>
                            </td>
                            <td>
                                <span>사업자등록증 대표자와 계좌 예금주 대조 완료</span>
                            </td>
                            <td>
                                <span class="block font-normal">등록 사업자와 정산계좌 예금주가 일치하지 않아 입점 신청이 반려되었습니다.</span>
                                <span class="block text-zinc-400 font-normal">전달 완료</span>
                            </td>
                            <td>
                                <span class="block font-normal">2026.08.10 17:20</span>
                                <span class="block text-zinc-400 font-normal">입점 심사팀 박OO</span>
                            </td>
                            <td>
                                <span class="w-fit border border-gray-300 rounded-lg text-gray-900 font-bold p-2">상세</span>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <span class="block font-bold">ROLE-DOTI-260810-02</span>
                                <span class="block text-zinc-400 font-normal">도티 역할 부여 예외</span>
                            </td>
                            <td>
                                <span class="block font-bold">이전 승계 사례</span>
                            </td>
                            <td>
                                <span class="w-fit bg-emerald-50 rounded-full text-emerald-700 font-bold px-2 py-1">● 승인</span>
                            </td>
                            <td>
                                <span class="w-fit bg-amber-50 rounded-full text-amber-700 font-bold px-2 py-1">전달 필수</span>
                                <span class="mt-1 block text-zinc-400 font-normal">승계 대상 도티 · 관리자 알림</span>
                            </td>
                            <td>
                                <span>KCP 본인인증·승계 수락·플랫폼 승인 이력 확인</span>
                            </td>
                            <td>
                                <span class="block font-normal">운영권 승계가 승인되어 동일 계정에 도티 역할이 부여되었습니다.</span>
                                <span class="block text-zinc-400 font-normal">전달 완료</span>
                            </td>
                            <td>
                                <span class="block font-normal">2026.08.10 15:05</span>
                                <span class="block text-zinc-400 font-normal">계정 운영팀 이OO</span>
                            </td>
                            <td>
                                <span class="w-fit border border-gray-300 rounded-lg text-gray-900 font-bold p-2">상세</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </section>
</section>

<!-- 처리 업무 상세 모달 -->
<div id="work-queue-modal" class="fixed inset-0 z-[1300] flex items-center justify-center p-4" hidden>
    <div id="work-queue-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="work-queue-modal-container" role="dialog" aria-modal="true" aria-labelledby="work-queue-modal-title" tabindex="-1" class="relative z-10 flex w-full max-w-[900px] max-h-[90vh] flex-col overflow-hidden rounded-2xl bg-white">
        <header class="flex shrink-0 items-center justify-between border-b border-gray-300 p-4">
            <h3 id="work-queue-modal-title" class="text-sm font-bold text-gray-900">처리 업무 상세</h3>
            <button type="button" aria-label="처리 업무 상세 모달 닫기" class="work-queue-modal-close flex h-8 w-8 items-center justify-center rounded-full bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4" aria-hidden="true">
                    <path d="M18 6 6 18M6 6l12 12" />
                </svg>
            </button>
        </header>

        <div id="work-queue-modal-body" class="min-h-0 flex-1 overflow-y-auto p-4">
            <dl class="overflow-hidden rounded-lg border border-gray-300 text-2xs [&>div+div]:border-t [&>div+div]:border-gray-200">
                <div class="flex">
                    <dt class="w-24 shrink-0 bg-gray-50 p-3 font-normal text-gray-500">업무 ID</dt>
                    <dd class="min-w-0 flex-1 p-3 font-bold text-gray-900">ESC-0104</dd>
                </div>
                <div class="flex">
                    <dt class="w-24 shrink-0 bg-gray-50 p-3 font-normal text-gray-500">유형</dt>
                    <dd class="min-w-0 flex-1 p-3 font-bold text-gray-900">보류·에스크로 검토</dd>
                </div>
                <div class="flex">
                    <dt class="w-24 shrink-0 bg-gray-50 p-3 font-normal text-gray-500">대상</dt>
                    <dd class="min-w-0 flex-1 p-3 font-bold text-gray-900">분쟁 DSP-0182</dd>
                </div>
                <div class="flex">
                    <dt class="w-24 shrink-0 bg-gray-50 p-3 font-normal text-gray-500">검토 사유</dt>
                    <dd class="min-w-0 flex-1 p-3 font-bold text-gray-900">반품비 분쟁 · 36,000원 보류 근거와 해제 조건 확인</dd>
                </div>
                <div class="flex">
                    <dt class="w-24 shrink-0 bg-gray-50 p-3 font-normal text-gray-500">접수</dt>
                    <dd class="min-w-0 flex-1 p-3 font-bold text-gray-900">2026.08.09</dd>
                </div>
                <div class="flex">
                    <dt class="w-24 shrink-0 bg-gray-50 p-3 font-normal text-gray-500">기한</dt>
                    <dd class="min-w-0 flex-1 p-3 font-bold text-gray-900">우선 확인</dd>
                </div>
                <div class="flex">
                    <dt class="w-24 shrink-0 bg-gray-50 p-3 font-normal text-gray-500">담당자</dt>
                    <dd class="min-w-0 flex-1 p-3 font-bold text-gray-900">정산팀 김OO</dd>
                </div>
                <div class="flex">
                    <dt class="w-24 shrink-0 bg-gray-50 p-3 font-normal text-gray-500">현재 상태</dt>
                    <dd class="min-w-0 flex-1 p-3 font-bold text-gray-900">
                        <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">
                            <span aria-hidden="true">●</span>
                            보류
                        </span>
                    </dd>
                </div>
                <div class="flex">
                    <dt class="w-24 shrink-0 bg-gray-50 p-3 font-normal text-gray-500">대상</dt>
                    <dd class="min-w-0 flex-1 p-3 font-bold text-gray-900">
                        <span class="rounded-full bg-blue-100 text-2xs font-bold text-blue-600 px-2 py-1">
                            전달 선택
                        </span>
                        <span> · 관련 당사자</span>
                    </dd>
                </div>
            </dl>

            <div class="mt-4">
                <label for="work-queue-result" class="block text-2xs font-bold text-gray-900">처리 결과</label>
                <select id="work-queue-result" name="result" class="mt-2 w-full rounded-lg border border-gray-300 bg-white">
                    <option value="approve">승인</option>
                    <option value="supplement">보완 요청</option>
                    <option value="reject">반려</option>
                    <option value="hold">보류</option>
                    <option value="complete">완료</option>
                </select>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-4 pc:grid-cols-2">
                <section class="rounded-lg border border-amber-300 bg-amber-50 p-4">
                    <h4 class="text-xs font-bold text-gray-900">
                        <label for="work-queue-internal-memo">내부 처리 메모 · 필수</label>
                    </h4>
                    <p id="work-queue-internal-memo-help" class="mt-1 text-2xs font-normal text-gray-500">플랫폼 담당자만 확인합니다. 확인 자료, 판단 과정과 내부 후속 조치를 기록하세요.</p>
                    <textarea id="work-queue-internal-memo" name="internal_memo" maxlength="2000" required aria-describedby="work-queue-internal-memo-help" class="mt-3 min-h-40 w-full rounded-lg border border-gray-300 bg-white p-3" placeholder="내부에서 확인한 자료와 판단 근거를 입력하세요."></textarea>
                    <div class="mt-2 flex items-center justify-between text-2xs text-gray-500">
                        <p class="font-normal">상대방에게 노출되지 않음</p>
                        <span class="font-bold">0 / 2000</span>
                    </div>
                </section>

                <section class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                    <h4 class="text-xs font-bold text-gray-900">
                        <label for="work-queue-external-reason">상대방 전달 사유 · 선택</label>
                    </h4>
                    <p id="work-queue-external-reason-help" class="mt-1 text-2xs font-normal text-gray-500">관련 당사자에게 관리자 알림으로 안내할 때만 사용합니다.</p>
                    <label for="work-queue-send-notice" class="mt-3 flex items-center gap-2 text-2xs font-bold text-gray-900">
                        <input type="checkbox" id="work-queue-send-notice" name="send_notice" value="1">
                        <span>상대방에게 안내하기</span>
                    </label>
                    <textarea id="work-queue-external-reason" name="external_reason" maxlength="1000" aria-describedby="work-queue-external-reason-help" class="mt-3 min-h-40 w-full rounded-lg border border-gray-300 bg-white p-3" placeholder="상대방에게 보낼 사유와 후속 조치를 입력하세요."></textarea>
                    <div class="mt-2 flex items-center justify-between text-2xs text-gray-500">
                        <p class="font-normal">수신자에게만 표시</p>
                        <span class="font-bold">0 / 1000</span>
                    </div>
                    <div class="mt-2 border border-dashed border-blue-200 rounded-lg bg-white text-2xs p-3">
                        <span class="block text-blue-500 font-bold">관련 당사자 · 관리자 알림</span>
                        <p class="mt-1 text-gray-500 font-normal">안내하기를 선택하면 전달 문구를 미리 확인할 수 있습니다.</p>
                    </div>
                </section>
            </div>

            <div class="mt-4 flex items-center gap-3 bg-amber-50 rounded-lg text-2xs p-3">
                <p class="text-amber-700 font-normal">내부 메모는 상대방 화면에 전달되지 않습니다. 승인·반려·완료는 항목별 완료함으로 이동하며, 보완 요청·보류는 두 기록을 분리해 유지합니다.</p>
            </div>
        </div>

        <footer class="flex shrink-0 justify-end gap-2 border-t border-gray-300 p-4">
            <button type="button" class="work-queue-modal-close rounded-lg border border-gray-300 bg-white px-4 py-3 font-bold text-gray-900">닫기</button>
            <button type="button" class="rounded-lg border border-gray-300 bg-white px-4 py-3 font-bold text-gray-900">전문 화면</button>
            <button type="button" id="work-queue-modal-save" class="rounded-lg border border-transparent bg-amber-300 px-4 py-3 font-bold text-gray-900">결과 저장·전달</button>
        </footer>
    </div>
</div>

<script>
    // 탭 선택
    const $workQueueTabs = $('#work-queue-tabs [role="tab"]');
    const $workQueuePanels = $('[role="tabpanel"]');

    $workQueueTabs.on('click', function() {
        const panelId = $(this).attr('aria-controls');

        $workQueueTabs
            .attr('aria-selected', 'false')
            .attr('tabindex', '-1');

        $(this)
            .attr('aria-selected', 'true')
            .attr('tabindex', '0');

        $workQueuePanels.prop('hidden', true);
        $('#' + panelId).prop('hidden', false);
    });

    // 업무 상세 모달 열기
    $('#work-queue-modal-open').on('click', function() {
        $('#work-queue-modal').prop('hidden', false);
        $('#work-queue-modal-container').trigger('focus');
    });

    // 업무 상세 모달 닫기
    $('.work-queue-modal-close, #work-queue-modal-backdrop').on('click', function() {
        $('#work-queue-modal').prop('hidden', true);
        $('#work-queue-modal-open').trigger('focus');
    });
</script>

<?php
require_once '../admin.tail.php';

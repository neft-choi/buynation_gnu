<?php
$sub_menu = '710110';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '도티 통합 관리';
require_once '../admin.head.php';
?>

<div role="tablist" aria-label="도티 통합 관리 메뉴" class="fixed top-13 right-4 z-100 flex items-center gap-1 w-fit rounded-lg bg-gray-100 p-1">
    <button type="button" id="management-tab-home" role="tab" aria-selected="true" aria-controls="management-panel-home" class="management-tab rounded-md bg-gray-900 px-3 py-2 text-2xs font-bold text-white">
        통합 홈
    </button>
    <button type="button" id="management-tab-task" role="tab" aria-selected="false" aria-controls="management-panel-task" class="management-tab rounded-md px-3 py-2 text-2xs font-bold text-gray-500">
        통합 업무함
    </button>
    <button type="button" id="management-tab-message" role="tab" aria-selected="false" aria-controls="management-panel-message" class="management-tab rounded-md px-3 py-2 text-2xs font-bold text-gray-500">
        통합 쪽지
    </button>
    <button type="button" id="management-tab-donut" role="tab" aria-selected="false" aria-controls="management-panel-donut" class="management-tab rounded-md px-3 py-2 text-2xs font-bold text-gray-500">
        도넛 목록
    </button>
</div>

<section id="management-panel-home" class="management-panel" role="tabpanel" aria-labelledby="management-tab-home" tabindex="0">
    <!-- 통합 홈 패널 -->
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-4">
        <div>
            <p class="text-gray-600">관리 중인 모든 도넛의 실제 운영 상태와 도티가 해야 할 일을 한곳에서 확인합니다.</p>
            <p class="text-gray-600">브랜드 쪽지는 업무 단계로 판별하지 않고 읽지 않음만 별도로 표시합니다.</p>
        </div>
        <div class="pc:w-100 border border-amber-400 rounded-lg bg-amber-50 text-2xs p-3">
            <p class="font-bold">자동화 범위</p>
            <p>상태와 로그는 자동 집계하지만 승인·제출·제재·정산 판단은 담당자가 해당 도넛 관리자에서 직접 처리합니다.</p>
        </div>
    </div>

    <div class="grid grid-cols-2 pc:grid-cols-4 gap-4 mt-4">
        <div class="font-normal border border-gray-300 rounded-lg p-3">
            <p class="text-gray-500">처리 필요</p>
            <p class="text-2xl font-bold mt-3">15<span class="text-xs ml-0.5">건</span></p>
            <p class="text-green-500 mt-2">도티가 직접 처리</p>
        </div>

        <div class="font-normal border border-gray-300 rounded-lg p-3">
            <p class="text-gray-500">진행 확인</p>
            <p class="text-2xl font-bold mt-3">4<span class="text-xs ml-0.5">건</span></p>
            <p class="text-indigo-500 mt-2">상대방·플랫폼 결과 대기</p>
        </div>

        <div class="font-normal border border-gray-300 rounded-lg p-3">
            <p class="text-gray-500">주의·예외</p>
            <p class="text-2xl font-bold mt-3">5<span class="text-xs ml-0.5">건</span></p>
            <p class="text-orange-500 mt-2">제한·보완 상태 확인</p>
        </div>

        <div class="font-normal border border-gray-300 rounded-lg p-3">
            <p class="text-gray-500">읽지 않은 쪽지</p>
            <p class="text-2xl font-bold mt-3">0<span class="text-xs ml-0.5">건</span></p>
            <p class="text-gray-500 mt-2">열면 읽지 않음 해제</p>
        </div>
    </div>

    <div class="mt-4">
        <h3 class="text-xl font-bold">통합 대시보드</h3>
        <p class="text-gray-500 mt-1">업무 건수와 분리해 전체 도넛의 운영 규모와 정산 가능 현황을 봅니다.</p>

        <div class="grid grid-cols-2 pc:grid-cols-6 gap-4 mt-4">
            <div class="font-normal border border-gray-300 rounded-lg p-3">
                <p class="text-gray-500">운영 도넛</p>
                <p class="text-xl font-bold mt-2">7개</p>
                <p class="text-2xs text-gray-500 mt-1">현재 도티가 관리하는 전체 도넛</p>
            </div>

            <div class="font-normal border border-gray-300 rounded-lg p-3">
                <p class="text-gray-500">가입 도트 합계</p>
                <p class="text-xl font-bold mt-2">30,040명</p>
                <p class="text-2xs text-gray-500 mt-1">도넛별 가입 인원 합산</p>
            </div>

            <div class="font-normal border border-gray-300 rounded-lg p-3">
                <p class="text-gray-500">이번 달 기여토핑</p>
                <p class="text-xl font-bold mt-2">7,202,000<span class="ml-1">T</span></p>
                <p class="text-2xs text-gray-500 mt-1">전체 도넛 발생 합계</p>
            </div>

            <div class="font-normal border border-gray-300 rounded-lg p-3">
                <p class="text-gray-500">지급 가능 토핑</p>
                <p class="text-xl font-bold mt-2">3,545,000<span class="ml-1">T</span></p>
                <p class="text-2xs text-gray-500 mt-1">운영 지급에 사용할 수 있는 잔액</p>
            </div>

            <div class="font-normal border border-gray-300 rounded-lg p-3">
                <p class="text-gray-500">정산 신청 가능</p>
                <p class="text-xl font-bold mt-2">680,000원</p>
                <p class="text-2xs text-gray-500 mt-1">선택 업무이므로 처리 필요에 포함하지 않음</p>
            </div>

            <div class="font-normal border border-gray-300 rounded-lg p-3">
                <p class="text-gray-500">사업자 인증 완료</p>
                <p class="text-xl font-bold mt-2">2/7</p>
                <p class="text-2xs text-gray-500 mt-1">현금 정산 자격이 확인된 도넛</p>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <h3 class="text-xl font-bold">운영 상태 시뮬레이션</h3>
        <div class="flex items-center justify-between mt-1">
            <p class="text-gray-500">도넛마다 서로 다른 사업자·정산 사례를 배치했습니다. 카드를 누르면 해당 전문 화면으로 이동합니다.</p>
            <span class="bg-gray-300 rounded-full text-2xs text-gray-600 font-bold px-2 py-1">● 대표 사례 7종</span>
        </div>

        <div class="grid grid-cols-2 pc:grid-cols-7 gap-4 mt-4">
            <a class="font-normal border border-gray-300 rounded-lg p-3">
                <div class="w-7 h-7 bg-gray-100 rounded-lg"></div>
                <p class="font-bold mt-1">승인 완료</p>
                <p class="text-2xs text-gray-500 font-bold mt-1">정상 운영·현금 정산 가능</p>
                <p class="w-fit text-2xs text-gray-700 font-bold bg-gray-50 rounded-full px-2 py-1 mt-1">사례 열기 ></p>
            </a>

            <a class="font-normal border border-gray-300 rounded-lg p-3">
                <div class="w-7 h-7 bg-gray-100 rounded-lg"></div>
                <p class="font-bold mt-1">서류 미제출</p>
                <p class="text-2xs text-gray-500 font-bold mt-1">제출 기한이 남은 최초 신청</p>
                <p class="w-fit text-2xs text-gray-700 font-bold bg-gray-50 rounded-full px-2 py-1 mt-1">사례 열기 ></p>
            </a>

            <a class="font-normal border border-gray-300 rounded-lg p-3">
                <div class="w-7 h-7 bg-gray-100 rounded-lg"></div>
                <p class="font-bold mt-1">심사 중</p>
                <p class="text-2xs text-gray-500 font-bold mt-1">서류 제출 후 플랫폼 결과 대기</p>
                <p class="w-fit text-2xs text-gray-700 font-bold bg-gray-50 rounded-full px-2 py-1 mt-1">사례 열기 ></p>
            </a>

            <a class="font-normal border border-gray-300 rounded-lg p-3">
                <div class="w-7 h-7 bg-gray-100 rounded-lg"></div>
                <p class="font-bold mt-1">보완 요청</p>
                <p class="text-2xs text-gray-500 font-bold mt-1">서류 수정·제제출 필요</p>
                <p class="w-fit text-2xs text-gray-700 font-bold bg-gray-50 rounded-full px-2 py-1 mt-1">사례 열기 ></p>
            </a>

            <a class="font-normal border border-gray-300 rounded-lg p-3">
                <div class="w-7 h-7 bg-gray-100 rounded-lg"></div>
                <p class="font-bold mt-1">최종 거절</p>
                <p class="text-2xs text-gray-500 font-bold mt-1">비사업자 전환·기여토핑 잠금</p>
                <p class="w-fit text-2xs text-gray-700 font-bold bg-gray-50 rounded-full px-2 py-1 mt-1">사례 열기 ></p>
            </a>

            <a class="font-normal border border-gray-300 rounded-lg p-3">
                <div class="w-7 h-7 bg-gray-100 rounded-lg"></div>
                <p class="font-bold mt-1">승계 검토</p>
                <p class="text-2xs text-gray-500 font-bold mt-1">운영권 이전 결과 대기</p>
                <p class="w-fit text-2xs text-gray-700 font-bold bg-gray-50 rounded-full px-2 py-1 mt-1">사례 열기 ></p>
            </a>

            <a class="font-normal border border-gray-300 rounded-lg p-3">
                <div class="w-7 h-7 bg-gray-100 rounded-lg"></div>
                <p class="font-bold mt-1">정산 검토</p>
                <p class="text-2xs text-gray-500 font-bold mt-1">신청 완료·플랫폼 확인 대기</p>
                <p class="w-fit text-2xs text-gray-700 font-bold bg-gray-50 rounded-full px-2 py-1 mt-1">사례 열기 ></p>
            </a>
        </div>
    </div>

    <div class="mt-4">
        <h3 class="text-xl font-bold">전체 운영 업무</h3>
        <p class="text-gray-500 mt-1">도티가 실제로 수행하거나 확인해야 하는 업무만 분류했습니다.</p>

        <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-4 mt-4">
            <div class="flex gap-4">
                <div class="w-full pc:max-w-80 border border-gray-300 rounded-lg p-3">
                    <p class="font-bold">처리 필요</p>
                    <p class="text-2xs text-gray-500">도티가 승인·제출·수정해야 완료되는 일입니다.</p>
                </div>
                <div class="w-full pc:max-w-80 border border-gray-300 rounded-lg p-3">
                    <p class="font-bold">진행 확인</p>
                    <p class="text-2xs text-gray-500">요청을 끝낸 뒤 플랫폼 또는 초대 대상의 결과를 기다리는 일입니다.</p>
                </div>
                <div class="w-full pc:max-w-80 border border-gray-300 rounded-lg p-3">
                    <p class="font-bold">주의·예외</p>
                    <p class="text-2xs text-gray-500">제한·최종 거절·기한 실패처럼 정상 흐름과 다른 일입니다.</p>
                </div>
            </div>

            <button type="button" class="management-task-view-trigger text-sm font-bold bg-white border border-gray-300 rounded-lg px-4 py-3">
                도넛별 대상 보기
            </button>
        </div>

        <div class="mt-4">
            <div class="flex items-center justify-between">
                <p class="text-lg font-bold">처리 필요<span class="text-xs text-gray-500 font-normal ml-1">도티 또는 지정 운영자가 승인·제출·수정해야 하는 업무</span></p>
                <span class="bg-green-100 rounded-full text-2xs text-green-600 font-bold px-2 py-1">● 15건</span>
            </div>

            <div class="grid grid-cols-1 pc:grid-cols-3 gap-4 mt-4">
                <button type="button" class="management-task-view-trigger w-full text-left font-normal border border-gray-300 rounded-lg p-3 hover:outline-1 hover:outline-amber-400">
                    <div class="w-7 h-7 bg-gray-100 rounded-lg"></div>
                    <p class="font-bold mt-2">가입 승인 대기</p>
                    <p class="text-2xs text-gray-500 mt-2">가입 신청을 검토하고 승인 또는 거절합니다.</p>
                    <p class="w-fit text-2xs text-white font-bold bg-gray-900 rounded-full px-2 py-1 mt-2">9건</p>
                </button>

                <button type="button" class="management-task-view-trigger w-full text-left font-normal border border-gray-300 rounded-lg p-3 hover:outline-1 hover:outline-amber-400">
                    <div class="w-7 h-7 bg-gray-100 rounded-lg"></div>
                    <p class="font-bold mt-2">신고 검토 대기</p>
                    <p class="text-2xs text-gray-500 mt-2">신고된 콘텐츠를 확인하고 처리합니다.</p>
                    <p class="w-fit text-2xs text-white font-bold bg-gray-900 rounded-full px-2 py-1 mt-2">5건</p>
                </button>

                <button type="button" class="management-task-view-trigger w-full text-left font-normal border border-gray-300 rounded-lg p-3 hover:outline-1 hover:outline-amber-400">
                    <div class="w-7 h-7 bg-gray-100 rounded-lg"></div>
                    <p class="font-bold mt-2">사업자 서류 처리</p>
                    <p class="text-2xs text-gray-500 mt-2">미제출 또는 보완이 필요한 서류를 등록·재제출합니다.</p>
                    <p class="w-fit text-2xs text-white font-bold bg-gray-900 rounded-full px-2 py-1 mt-2">1건</p>
                </button>
            </div>
        </div>

        <div class="mt-4">
            <div class="flex items-center justify-between">
                <p class="text-lg font-bold">진행 확인<span class="text-xs text-gray-500 font-normal ml-1">요청 또는 제출 후 플랫폼·상대방의 결과를 기다리는 업무</span></p>
                <span class="bg-indigo-100 rounded-full text-2xs text-indigo-600 font-bold px-2 py-1">● 4건</span>
            </div>

            <div class="grid grid-cols-1 pc:grid-cols-3 gap-4 mt-4">
                <button type="button" class="management-task-view-trigger w-full text-left font-normal border border-gray-300 rounded-lg p-3 hover:outline-1 hover:outline-amber-400">
                    <div class="w-7 h-7 bg-gray-100 rounded-lg"></div>
                    <p class="font-bold mt-2">사업자 승인 대기</p>
                    <p class="text-2xs text-gray-500 mt-2">제출한 서류를 플랫폼 관리자가 검토하고 있습니다.</p>
                    <p class="w-fit text-2xs text-white font-bold bg-gray-900 rounded-full px-2 py-1 mt-2">1건</p>
                </button>

                <button type="button" class="management-task-view-trigger w-full text-left font-normal border border-gray-300 rounded-lg p-3 hover:outline-1 hover:outline-amber-400">
                    <div class="w-7 h-7 bg-gray-100 rounded-lg"></div>
                    <p class="font-bold mt-2">정산 검토 중</p>
                    <p class="text-2xs text-gray-500 mt-2">신청 자료를 바탕으로 담당자가 검토하고 있습니다.</p>
                    <p class="w-fit text-2xs text-white font-bold bg-gray-900 rounded-full px-2 py-1 mt-2">1건</p>
                </button>

                <button type="button" class="management-task-view-trigger w-full text-left font-normal border border-gray-300 rounded-lg p-3 hover:outline-1 hover:outline-amber-400">
                    <div class="w-7 h-7 bg-gray-100 rounded-lg"></div>
                    <p class="font-bold mt-2">운영권 승계 심사</p>
                    <p class="text-2xs text-gray-500 mt-2">본인확인과 운영권 이전에 대한 플랫폼 결과를 기다립니다.</p>
                    <p class="w-fit text-2xs text-white font-bold bg-gray-900 rounded-full px-2 py-1 mt-2">1건</p>
                </button>

                <button type="button" class="management-task-view-trigger w-full text-left font-normal border border-gray-300 rounded-lg p-3 hover:outline-1 hover:outline-amber-400">
                    <div class="w-7 h-7 bg-gray-100 rounded-lg"></div>
                    <p class="font-bold mt-2">운영자 초대 응답 대기</p>
                    <p class="text-2xs text-gray-500 mt-2">지정 운영자가 초대를 수락할 때까지 상태를 확인합니다.</p>
                    <p class="w-fit text-2xs text-white font-bold bg-gray-900 rounded-full px-2 py-1 mt-2">1건</p>
                </button>
            </div>
        </div>

        <div class="mt-4">
            <div class="flex items-center justify-between">
                <p class="text-lg font-bold">주의·예외<span class="text-xs text-gray-500 font-normal ml-1">제한·최종 거절·기한 실패처럼 별도 판단이 필요한 업무</span></p>
                <span class="bg-red-100 rounded-full text-2xs text-red-600 font-bold px-2 py-1">● 5건</span>
            </div>

            <div class="grid grid-cols-1 pc:grid-cols-3 gap-4 mt-4">
                <button type="button" class="management-task-view-trigger w-full text-left font-normal border border-gray-300 rounded-lg p-3 hover:outline-1 hover:outline-amber-400">
                    <div class="w-7 h-7 bg-gray-100 rounded-lg"></div>
                    <p class="font-bold mt-2">활동 제한 확인</p>
                    <p class="text-2xs text-gray-500 mt-2">활동 제한 중인 도트의 사유와 해제 여부를 확인합니다.</p>
                    <p class="w-fit text-2xs text-white font-bold bg-gray-900 rounded-full px-2 py-1 mt-2">4건</p>
                </button>

                <button type="button" class="management-task-view-trigger w-full text-left font-normal border border-gray-300 rounded-lg p-3 hover:outline-1 hover:outline-amber-400">
                    <div class="w-7 h-7 bg-gray-100 rounded-lg"></div>
                    <p class="font-bold mt-2">사업자 최종 거절</p>
                    <p class="text-2xs text-gray-500 mt-2">정산 제한 상태와 사업자 전환 가능 여부를 확인합니다.</p>
                    <p class="w-fit text-2xs text-white font-bold bg-gray-900 rounded-full px-2 py-1 mt-2">1건</p>
                </button>

                <button type="button" class="management-task-view-trigger w-full text-left font-normal border border-gray-300 rounded-lg p-3 hover:outline-1 hover:outline-amber-400">
                    <div class="w-7 h-7 bg-gray-100/50 rounded-lg"></div>
                    <p class="text-gray-900/50 font-bold mt-2">기한·실패 예외</p>
                    <p class="text-2xs text-gray-500/50 mt-2">기한 초과, 오류 또는 중단 알림의 원인을 확인합니다.</p>
                    <p class="w-fit text-2xs text-gray-900/50  font-bold bg-gray-300/50 rounded-full px-2 py-1 mt-2">현재 없음</p>
                </button>
            </div>
        </div>
    </div>

    <div id="management-donut-list-home-slot" class="mt-4">
        <div id="management-donut-list-section">
            <h3 class="text-xl font-bold">관리 중인 도넛</h3>
            <div class="flex items-center justify-between mt-1">
                <p class="text-gray-500">전체 도넛을 얇은 목록으로 비교하며, 업무가 많은 도넛을 먼저 표시합니다.</p>
                <span class="bg-red-100 rounded-full text-2xs text-red-600 font-bold px-2 py-1">● 7개</span>
            </div>

            <div class="mt-4 flex flex-col gap-2 pc:flex-row">
                <div role="search" class="flex-1">
                    <label for="management-donut-search" class="sound_only">도넛 이름·카테고리 검색</label>
                    <input type="search" id="management-donut-search" class="w-full border border-gray-300 rounded-lg p-3" placeholder="도넛 이름·카테고리 검색" autocomplete="off">
                </div>

                <div role="group" aria-label="도넛 운영 업무 필터" class="flex shrink-0 items-center gap-1 rounded-lg bg-gray-100 p-1">
                    <button type="button" class="management-donut-filter rounded-md bg-gray-900 px-3 py-2 text-2xs font-bold text-white" data-management-donut-filter="all" aria-pressed="true">
                        전체
                    </button>
                    <button type="button" class="management-donut-filter rounded-md px-3 py-2 text-2xs font-bold text-gray-500" data-management-donut-filter="pending" aria-pressed="false">
                        처리 필요
                    </button>
                    <button type="button" class="management-donut-filter rounded-md px-3 py-2 text-2xs font-bold text-gray-500" data-management-donut-filter="progress" aria-pressed="false">
                        진행 확인
                    </button>
                    <button type="button" class="management-donut-filter rounded-md px-3 py-2 text-2xs font-bold text-gray-500" data-management-donut-filter="warning" aria-pressed="false">
                        주의·예외
                    </button>
                </div>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white overflow-x-auto mt-4">
                <table class="w-full min-w-240 table-fixed border-collapse text-left text-xs">
                    <colgroup>
                        <col class="w-[32%]">
                        <col class="w-[18%]">
                        <col class="w-[12%]">
                        <col class="w-[10%]">
                        <col class="w-[11%]">
                        <col class="w-[11%]">
                        <col class="w-[6%]">
                    </colgroup>

                    <thead class="border-b border-gray-200 bg-gray-50 text-2xs text-gray-500 [&_th]:px-3 [&_th]:py-3 [&_th]:font-semibold">
                        <tr>
                            <th scope="col">도넛</th>
                            <th scope="col">운영 업무</th>
                            <th scope="col" class="th_center">사업자 상태</th>
                            <th scope="col" class="th_center">가입 도트</th>
                            <th scope="col" class="th_center">기여토핑</th>
                            <th scope="col" class="th_center">지급가능 </th>
                            <th scope="col" class="th_center">관리</th>
                        </tr>
                    </thead>

                    <tbody id="management-donut-list-body" class="divide-y divide-gray-200 text-gray-700 [&_td]:px-3 [&_td]:py-4 [&_td]:align-middle">
                        <tr class="management-donut-search-row" data-management-donut-pending="3" data-management-donut-progress="1" data-management-donut-warning="1">
                            <td>
                                <div class="flex items-center gap-4 bg-gray-100 px-1">
                                    <div class="w-8 h-8 bg-white rounded-lg"></div>
                                    <div class="flex-1 min-w-0 text-center">
                                        <p class="font-bold">캠핑 위켄드</p>
                                        <p class="text-2xs text-gray-500">여행 ● 아웃도어 ● 운영 업무 5건</p>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="text-nowrap">
                                    <span class="bg-amber-100 rounded-full text-2xs text-amber-600 font-bold px-2 py-1">처리 3</span>
                                    <span class="bg-blue-100 rounded-full text-2xs text-blue-600 font-bold px-2 py-1">진행 1</span>
                                    <span class="bg-red-100 rounded-full text-2xs text-red-600 font-bold px-2 py-1">주의 1</span>
                                </div>
                            </td>

                            <td class="td_center">
                                <span class="text-nowrap bg-green-100 rounded-full text-2xs text-green-600 font-bold px-2 py-1">● 사업자 인증 완료</span>
                            </td>

                            <td class="td_center">
                                <span class="font-bold">3,940명</span>
                            </td>

                            <td class="td_center">
                                <span class="font-bold">1,052,000 T</span>
                            </td>

                            <td class="td_center">
                                <span class="font-bold">610,000 T</span>
                            </td>

                            <td class="td_center">
                                <a href="/adm/dotty/dashboard.php" class="inline-flex items-center justify-center whitespace-nowrap rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900 hover:bg-gray-50">
                                    진입
                                </a>
                            </td>
                        </tr>

                        <tr class="management-donut-search-row" data-management-donut-pending="4" data-management-donut-progress="0" data-management-donut-warning="0">
                            <td>
                                <div class="flex items-center gap-4 bg-gray-100 px-1">
                                    <div class="w-8 h-8 bg-white rounded-lg"></div>
                                    <div class="flex-1 min-w-0 text-center">
                                        <p class="font-bold">독서 라운지</p>
                                        <p class="text-2xs text-gray-500">취미 ● 독서 ● 운영 업무 4건</p>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="text-nowrap">
                                    <span class="bg-amber-100 rounded-full text-2xs text-amber-600 font-bold px-2 py-1">처리 4</span>
                                    <span class="bg-blue-100 rounded-full text-2xs text-blue-600 font-bold px-2 py-1">진행 0</span>
                                    <span class="bg-red-100 rounded-full text-2xs text-red-600 font-bold px-2 py-1">주의 0</span>
                                </div>
                            </td>

                            <td class="td_center">
                                <span class="bg-red-100 rounded-full text-2xs text-red-600 font-bold px-2 py-1">● 서류 보완 필요</span>
                            </td>

                            <td class="td_center">
                                <span class="font-bold">2,730명</span>
                            </td>

                            <td class="td_center">
                                <span class="font-bold">384,000 T</span>
                            </td>

                            <td class="td_center">
                                <span class="font-bold">175,000 T</span>
                            </td>

                            <td class="td_center">
                                <a href="/adm/dotty/dashboard.php" class="inline-flex items-center justify-center whitespace-nowrap rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900 hover:bg-gray-50">
                                    진입
                                </a>
                            </td>
                        </tr>

                        <tr class="management-donut-search-row" data-management-donut-pending="3" data-management-donut-progress="0" data-management-donut-warning="1">
                            <td>
                                <div class="flex items-center gap-4 bg-gray-100 px-1">
                                    <div class="w-8 h-8 bg-white rounded-lg"></div>
                                    <div class="flex-1 min-w-0 text-center">
                                        <p class="font-bold">러닝 메이트</p>
                                        <p class="text-2xs text-gray-500">취미 ● 스포츠 ● 운영 업무 4건</p>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="text-nowrap">
                                    <span class="bg-amber-100 rounded-full text-2xs text-amber-600 font-bold px-2 py-1">처리 3</span>
                                    <span class="bg-blue-100 rounded-full text-2xs text-blue-600 font-bold px-2 py-1">진행 0</span>
                                    <span class="bg-red-100 rounded-full text-2xs text-red-600 font-bold px-2 py-1">주의 1</span>
                                </div>
                            </td>

                            <td class="td_center">
                                <span class="bg-gray-100 rounded-full text-2xs text-gray-600 font-bold px-2 py-1">● 비사업자 도넛</span>
                            </td>

                            <td class="td_center">
                                <span class="font-bold">4,360명</span>
                            </td>

                            <td class="td_center">
                                <span class="font-bold">1,286,400 T</span>
                            </td>

                            <td class="td_center">
                                <span class="font-bold">420,000 T</span>
                            </td>

                            <td class="td_center">
                                <a href="/adm/dotty/dashboard.php" class="inline-flex items-center justify-center whitespace-nowrap rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900 hover:bg-gray-50">
                                    진입
                                </a>
                            </td>
                        </tr>

                        <tr id="management-donut-search-empty" hidden>
                            <td colspan="7" class="text-center text-xs text-gray-500">
                                검색 결과가 없습니다.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <h3 class="text-xl font-bold">최근 관리자 활동</h3>
        <p class="text-gray-500 mt-1">모든 도넛의 처리 로그를 시간순으로 모아 봅니다.</p>

        <div class="border border-gray-300 rounded-lg bg-white overflow-x-auto mt-4 px-3">
            <table class="w-full min-w-240 table-fixed border-collapse text-left text-xs">
                <colgroup>
                    <col class="w-[14%]">
                    <col class="w-[14%]">
                    <col class="w-[14%]">
                    <col class="w-[58%]">
                </colgroup>

                <tbody class="divide-y divide-gray-200 text-gray-700 [&_td]:px-3 [&_td]:py-4 [&_td]:align-middle">
                    <tr>
                        <td>
                            <span>2026.08.18 15:50:16</span>
                        </td>

                        <td>
                            <span class="font-bold">러닝 메이트</span>
                        </td>

                        <td>
                            <span>공지 등록</span>
                        </td>

                        <td>
                            <span class="text-gray-500">NTC-35816680 공지 등록</span>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span>2026.08.18 15:25:06</span>
                        </td>

                        <td>
                            <span class="font-bold">러닝 메이트</span>
                        </td>

                        <td>
                            <span>사업자 전환 신청</span>
                        </td>

                        <td>
                            <span class="text-gray-600">DONUT-RUNNING · 승인 이후 적립분부터 정산 적용</span>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span>2026.08.18 14:51:07</span>
                        </td>

                        <td>
                            <span class="font-bold">테니스 커뮤니티</span>
                        </td>

                        <td>
                            <span>추천상품 등록</span>
                        </td>

                        <td>
                            <span class="text-gray-600">
                                P-808 정기배송 혼합박스 24개입 · 일반 상품</span>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span>2026.08.18 14:51:04</span>
                        </td>

                        <td>
                            <span class="font-bold">테니스 커뮤니티</span>
                        </td>

                        <td>
                            <span>추천상품 등록</span>
                        </td>

                        <td>
                            <span class="text-gray-600">P-805 콜드프레스 주스 12병 · 일반 상품</span>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span>2026.08.18 14:51:01</span>
                        </td>

                        <td>
                            <span class="font-bold">테니스 커뮤니티</span>
                        </td>

                        <td>
                            <span>추천상품 등록</span>
                        </td>

                        <td>
                            <span class="text-gray-600">
                                P-804 선물용 대형 패키지 · 일반 상품</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section id="management-panel-task" class="management-panel" role="tabpanel" aria-labelledby="management-tab-task" tabindex="0" hidden>

    <!-- 통합 업무함 패널 -->
    <div>
        <div class="flex items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold">처리 필요</h3>
                <p class="mt-1 text-gray-500">도티 또는 지정 운영자가 승인·제출·수정해야 하는 업무입니다. 0건 항목도 업무 범위 확인을 위해 표시합니다.</p>
            </div>
            <span class="shrink-0 rounded-full bg-amber-100 px-2 py-1 text-2xs font-bold text-amber-700">● 15건</span>
        </div>

        <div class="grid grid-cols-1 pc:grid-cols-2 gap-4 mt-4">
            <article class="overflow-hidden border border-gray-300 rounded-lg">
                <div class="flex items-center justify-between gap-4 border-b border-amber-100 bg-amber-50 p-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-amber-300"></div>
                        <div>
                            <h3 class="text-sm font-bold">가입 승인 대기</h3>
                            <p class="mt-1 text-2xs text-gray-500">가입 신청을 검토하고 승인 또는 거절합니다.</p>
                        </div>
                    </div>
                    <span class="shrink-0 rounded-full bg-gray-900 font-bold text-white px-3 py-2">9건</span>
                </div>

                <ul class="divide-y divide-gray-200 px-3">
                    <li>
                        <button type="button" class="flex items-center gap-3 w-full rounded-lg py-3">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-gray-100"></div>
                            <div class="flex-1 min-w-0 text-left">
                                <p class="font-bold">테니스 커뮤니티</p>
                                <p class="mt-1 text-2xs text-gray-500">가입 승인 대기 관리로 이동</p>
                            </div>
                            <span class="shrink-0 text-sm font-bold">3건</span>
                            <span class="shrink-0 text-gray-400">›</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="flex items-center gap-3 w-full rounded-lg py-3">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-gray-100"></div>
                            <div class="flex-1 min-w-0 text-left">
                                <p class="font-bold">러닝 메이트</p>
                                <p class="mt-1 text-2xs text-gray-500">가입 승인 대기 관리로 이동</p>
                            </div>
                            <span class="shrink-0 text-sm font-bold">2건</span>
                            <span class="shrink-0 text-gray-400">›</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="flex items-center gap-3 w-full rounded-lg py-3">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-gray-100"></div>
                            <div class="flex-1 min-w-0 text-left">
                                <p class="font-bold">독서 라운지</p>
                                <p class="mt-1 text-2xs text-gray-500">가입 승인 대기 관리로 이동</p>
                            </div>
                            <span class="shrink-0 text-sm font-bold">1건</span>
                            <span class="shrink-0 text-gray-400">›</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="flex items-center gap-3 w-full rounded-lg py-3">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-gray-100"></div>
                            <div class="flex-1 min-w-0 text-left">
                                <p class="font-bold">반려생활 연구소</p>
                                <p class="mt-1 text-2xs text-gray-500">가입 승인 대기 관리로 이동</p>
                            </div>
                            <span class="shrink-0 text-sm font-bold">1건</span>
                            <span class="shrink-0 text-gray-400">›</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="flex items-center gap-3 w-full rounded-lg py-3">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-gray-100"></div>
                            <div class="flex-1 min-w-0 text-left">
                                <p class="font-bold">캠핑 위켄드</p>
                                <p class="mt-1 text-2xs text-gray-500">가입 승인 대기 관리로 이동</p>
                            </div>
                            <span class="shrink-0 text-sm font-bold">2건</span>
                            <span class="shrink-0 text-gray-400">›</span>
                        </button>
                    </li>
                </ul>
            </article>

            <article class="overflow-hidden border border-gray-300 rounded-lg">
                <div class="flex items-center justify-between gap-4 border-b border-amber-100 bg-amber-50 p-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-amber-300"></div>
                        <div>
                            <h3 class="text-sm font-bold">신고 검토 대기</h3>
                            <p class="mt-1 text-2xs text-gray-500">신고된 콘텐츠를 확인하고 처리합니다.</p>
                        </div>
                    </div>
                    <span class="shrink-0 rounded-full bg-gray-900 font-bold text-white px-3 py-2">5건</span>
                </div>

                <ul class="divide-y divide-gray-200 px-3">
                    <li>
                        <button type="button" class="flex items-center gap-3 w-full rounded-lg py-3">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-gray-100"></div>
                            <div class="flex-1 min-w-0 text-left">
                                <p class="font-bold">테니스 커뮤니티</p>
                                <p class="mt-1 text-2xs text-gray-500">신고 검토 대기 관리로 이동</p>
                            </div>
                            <span class="shrink-0 text-sm font-bold">1건</span>
                            <span class="shrink-0 text-gray-400">›</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="flex items-center gap-3 w-full rounded-lg py-3">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-gray-100"></div>
                            <div class="flex-1 min-w-0 text-left">
                                <p class="font-bold">러닝 메이트</p>
                                <p class="mt-1 text-2xs text-gray-500">신고 검토 대기 관리로 이동</p>
                            </div>
                            <span class="shrink-0 text-sm font-bold">1건</span>
                            <span class="shrink-0 text-gray-400">›</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="flex items-center gap-3 w-full rounded-lg py-3">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-gray-100"></div>
                            <div class="flex-1 min-w-0 text-left">
                                <p class="font-bold">독서 라운지</p>
                                <p class="mt-1 text-2xs text-gray-500">신고 검토 대기 관리로 이동</p>
                            </div>
                            <span class="shrink-0 text-sm font-bold">2건</span>
                            <span class="shrink-0 text-gray-400">›</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="flex items-center gap-3 w-full rounded-lg py-3">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-gray-100"></div>
                            <div class="flex-1 min-w-0 text-left">
                                <p class="font-bold">캠핑 위켄드</p>
                                <p class="mt-1 text-2xs text-gray-500">신고 검토 대기 관리로 이동</p>
                            </div>
                            <span class="shrink-0 text-sm font-bold">1건</span>
                            <span class="shrink-0 text-gray-400">›</span>
                        </button>
                    </li>
                </ul>
            </article>

            <article class="overflow-hidden border border-gray-300 rounded-lg">
                <div class="flex items-center justify-between gap-4 border-b border-amber-100 bg-amber-50 p-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-amber-300"></div>
                        <div>
                            <h3 class="text-sm font-bold">사업자 서류 처리</h3>
                            <p class="mt-1 text-2xs text-gray-500">미제출 또는 보완이 필요한 서류를 등록·재제출합니다.</p>
                        </div>
                    </div>
                    <span class="shrink-0 rounded-full bg-gray-900 font-bold text-white px-3 py-2">1건</span>
                </div>

                <ul class="divide-y divide-gray-200 px-3">
                    <li>
                        <button type="button" class="flex items-center gap-3 w-full rounded-lg py-3">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-gray-100"></div>
                            <div class="flex-1 min-w-0 text-left">
                                <p class="font-bold">독서 라운지</p>
                                <p class="mt-1 text-2xs text-gray-500">사업자 서류 처리 관리로 이동</p>
                            </div>
                            <span class="shrink-0 text-sm font-bold">1건</span>
                            <span class="shrink-0 text-gray-400">›</span>
                        </button>
                    </li>
                </ul>
            </article>
        </div>
    </div>

    <div class="mt-4">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold">진행 확인</h3>
                <p class="mt-1 text-gray-500">요청 또는 제출 후 플랫폼·상대방의 결과를 기다리는 업무. 0건 항목도 업무 범위 확인을 위해 표시합니다.</p>
            </div>
            <span class="shrink-0 rounded-full bg-indigo-100 px-2 py-1 text-2xs font-bold text-indigo-700">● 4건</span>
        </div>

        <div class="grid grid-cols-1 pc:grid-cols-2 gap-4 mt-4">
            <article class="overflow-hidden border border-gray-300 rounded-lg">
                <div class="flex items-center justify-between gap-4 border-b border-indigo-100 bg-indigo-50 p-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-indigo-200"></div>
                        <div>
                            <h3 class="text-sm font-bold">사업자 승인 대기</h3>
                            <p class="mt-1 text-2xs text-gray-500">제출한 서류를 플랫폼 관리자가 검토하고 있습니다.</p>
                        </div>
                    </div>
                    <span class="shrink-0 rounded-full bg-gray-900 font-bold text-white px-3 py-2">1건</span>
                </div>

                <ul class="divide-y divide-gray-200 px-3">
                    <li>
                        <button type="button" class="flex items-center gap-3 w-full rounded-lg py-3">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-gray-100"></div>
                            <div class="flex-1 min-w-0 text-left">
                                <p class="font-bold">사진 산책회</p>
                                <p class="mt-1 text-2xs text-gray-500">사업자 승인 대기 관리로 이동</p>
                            </div>
                            <span class="shrink-0 text-sm font-bold">1건</span>
                            <span class="shrink-0 text-gray-400">›</span>
                        </button>
                    </li>
                </ul>
            </article>

            <article class="overflow-hidden border border-gray-300 rounded-lg">
                <div class="flex items-center justify-between gap-4 border-b border-indigo-100 bg-indigo-50 p-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-indigo-200"></div>
                        <div>
                            <h3 class="text-sm font-bold">정산 검토 중</h3>
                            <p class="mt-1 text-2xs text-gray-500">신청 자료를 바탕으로 담당자가 검토하고 있습니다.</p>
                        </div>
                    </div>
                    <span class="shrink-0 rounded-full bg-gray-900 font-bold text-white px-3 py-2">1건</span>
                </div>

                <ul class="divide-y divide-gray-200 px-3">
                    <li>
                        <button type="button" class="flex items-center gap-3 w-full rounded-lg py-3">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-gray-100"></div>
                            <div class="flex-1 min-w-0 text-left">
                                <p class="font-bold">캠핑 위켄드</p>
                                <p class="mt-1 text-2xs text-gray-500">정산 검토 중 관리로 이동</p>
                            </div>
                            <span class="shrink-0 text-sm font-bold">1건</span>
                            <span class="shrink-0 text-gray-400">›</span>
                        </button>
                    </li>
                </ul>
            </article>

            <article class="overflow-hidden border border-gray-300 rounded-lg">
                <div class="flex items-center justify-between gap-4 border-b border-indigo-100 bg-indigo-50 p-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-indigo-200"></div>
                        <div>
                            <h3 class="text-sm font-bold">운영권 승계 심사</h3>
                            <p class="mt-1 text-2xs text-gray-500">본인확인과 운영권 이전에 대한 플랫폼 결과를 기다립니다.</p>
                        </div>
                    </div>
                    <span class="shrink-0 rounded-full bg-gray-900 font-bold text-white px-3 py-2">1건</span>
                </div>

                <ul class="divide-y divide-gray-200 px-3">
                    <li>
                        <button type="button" class="flex items-center gap-3 w-full rounded-lg py-3">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-gray-100"></div>
                            <div class="flex-1 min-w-0 text-left">
                                <p class="font-bold">반려생활 연구소</p>
                                <p class="mt-1 text-2xs text-gray-500">운영권 승계 심사 관리로 이동</p>
                            </div>
                            <span class="shrink-0 text-sm font-bold">1건</span>
                            <span class="shrink-0 text-gray-400">›</span>
                        </button>
                    </li>
                </ul>
            </article>

            <article class="overflow-hidden border border-gray-300 rounded-lg">
                <div class="flex items-center justify-between gap-4 border-b border-indigo-100 bg-indigo-50 p-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-indigo-200"></div>
                        <div>
                            <h3 class="text-sm font-bold">운영자 초대 응답 대기</h3>
                            <p class="mt-1 text-2xs text-gray-500">지정 운영자가 초대를 수락할 때까지 상태를 확인합니다.</p>
                        </div>
                    </div>
                    <span class="shrink-0 rounded-full bg-gray-900 font-bold text-white px-3 py-2">1건</span>
                </div>

                <ul class="divide-y divide-gray-200 px-3">
                    <li>
                        <button type="button" class="flex items-center gap-3 w-full rounded-lg py-3">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-gray-100"></div>
                            <div class="flex-1 min-w-0 text-left">
                                <p class="font-bold">홈베이킹 살롱</p>
                                <p class="mt-1 text-2xs text-gray-500">운영자 초대 응답 대기 관리로 이동</p>
                            </div>
                            <span class="shrink-0 text-sm font-bold">1건</span>
                            <span class="shrink-0 text-gray-400">›</span>
                        </button>
                    </li>
                </ul>
            </article>
        </div>
    </div>

    <div class="mt-4">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold">주의·예외</h3>
                <p class="mt-1 text-gray-500">제한·최종 거절·기한 실패처럼 별도 판단이 필요한 업무. 0건 항목도 업무 범위 확인을 위해 표시합니다.</p>
            </div>
            <span class="shrink-0 rounded-full bg-red-100 px-2 py-1 text-2xs font-bold text-red-700">● 5건</span>
        </div>

        <div class="grid grid-cols-1 pc:grid-cols-2 gap-4 mt-4">
            <article class="overflow-hidden border border-gray-300 rounded-lg">
                <div class="flex items-center justify-between gap-4 border-b border-red-100 bg-red-50 p-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-red-200"></div>
                        <div>
                            <h3 class="text-sm font-bold">활동 제한 확인</h3>
                            <p class="mt-1 text-2xs text-gray-500">활동 제한 중인 도트의 사유와 해제 여부를 확인합니다.</p>
                        </div>
                    </div>
                    <span class="shrink-0 rounded-full bg-gray-900 font-bold text-white px-3 py-2">4건</span>
                </div>

                <ul class="divide-y divide-gray-200 px-3">
                    <li>
                        <button type="button" class="flex items-center gap-3 w-full rounded-lg py-3">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-gray-100"></div>
                            <div class="flex-1 min-w-0 text-left">
                                <p class="font-bold">테니스 커뮤니티</p>
                                <p class="mt-1 text-2xs text-gray-500">활동 제한 확인 관리로 이동</p>
                            </div>
                            <span class="shrink-0 text-sm font-bold">1건</span>
                            <span class="shrink-0 text-gray-400">›</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="flex items-center gap-3 w-full rounded-lg py-3">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-gray-100"></div>
                            <div class="flex-1 min-w-0 text-left">
                                <p class="font-bold">러닝 메이트</p>
                                <p class="mt-1 text-2xs text-gray-500">활동 제한 확인 관리로 이동</p>
                            </div>
                            <span class="shrink-0 text-sm font-bold">1건</span>
                            <span class="shrink-0 text-gray-400">›</span>
                        </button>
                    </li>
                </ul>
            </article>

            <article class="overflow-hidden border border-gray-300 rounded-lg">
                <div class="flex items-center justify-between gap-4 border-b border-red-100 bg-red-50 p-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-red-200"></div>
                        <div>
                            <h3 class="text-sm font-bold">사업자 최종 거절</h3>
                            <p class="mt-1 text-2xs text-gray-500">정산 제한 상태와 사업자 전환 가능 여부를 확인합니다.</p>
                        </div>
                    </div>
                    <span class="shrink-0 rounded-full bg-gray-900 font-bold text-white px-3 py-2">1건</span>
                </div>

                <ul class="divide-y divide-gray-200 px-3">
                    <li>
                        <button type="button" class="flex items-center gap-3 w-full rounded-lg py-3">
                            <div class="shrink-0 h-9 w-9 rounded-lg bg-gray-100"></div>
                            <div class="flex-1 min-w-0 text-left">
                                <p class="font-bold">홈베이킹 살롱</p>
                                <p class="mt-1 text-2xs text-gray-500">사업자 최종 거절 관리로 이동</p>
                            </div>
                            <span class="shrink-0 text-sm font-bold">1건</span>
                            <span class="shrink-0 text-gray-400">›</span>
                        </button>
                    </li>
                </ul>
            </article>

            <article class="overflow-hidden border border-gray-300 rounded-lg">
                <div class="flex items-center justify-between gap-4 border-b border-red-100 bg-red-50 p-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-red-200"></div>
                        <div>
                            <h3 class="text-sm font-bold">기한·실패 예외</h3>
                            <p class="mt-1 text-2xs text-gray-500">기한 초과, 오류 또는 중단 알림의 원인을 확인합니다.</p>
                        </div>
                    </div>
                    <span class="shrink-0 rounded-full bg-gray-900 font-bold text-white px-3 py-2">0건</span>
                </div>

                <ul class="divide-y divide-gray-200 px-3">

                </ul>

                <div class="text-2xs text-gray-500 font-normal p-3">현재 해당 업무가 없습니다.</div>
            </article>
        </div>
    </div>
</section>

<section id="management-panel-message" class="management-panel" role="tabpanel" aria-labelledby="management-tab-message" tabindex="0" hidden>
    <!-- 통합 쪽지 패널 -->
    <h3 class="text-xl font-bold">통합 쪽지</h3>
    <p class="mt-1 text-gray-500">모든 도넛의 브랜드 대화를 한곳에서 확인합니다. 목록에는 읽지 않음만 표시됩니다.</p>

    <div class="flex overflow-hidden rounded-lg border border-gray-300 mt-4">
        <aside class="shrink-0 w-80 border-r border-gray-300" aria-labelledby="dotty-message-conversation-heading">
            <div class="flex flex-col items-start justify-center h-18 border-b border-gray-300 p-4">
                <h3 id="dotty-message-conversation-heading" class="text-sm text-gray-900 font-bold">전체 대화 2건</h3>
                <p class="text-2xs text-gray-400">대화를 열면 읽지 않음 표시가 사라집니다.</p>
            </div>

            <nav aria-label="도티 대화 목록">
                <ul class="divide-y divide-gray-200">
                    <li>
                        <button type="button" data-dotty-message-conversation="running" aria-current="true" class="dotty-message-conversation-item flex w-full items-start gap-3 border-l-4 border-amber-400 bg-amber-50 px-4 py-4 text-left">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-gray-900 font-bold text-amber-300" aria-hidden="true">러</span>

                            <span class="min-w-0 flex-1">
                                <span class="block truncate font-bold text-gray-900">러닝 메이트 · 그린테이블</span>
                                <time datetime="2026-08-19T14:10" class="shrink-0 text-2xs text-gray-400">2026.08.19 14:10</time>
                                <span class="block truncate text-2xs text-gray-500">정기런 참여 회원을 위한 회복식 기획전을 제안합니다.</span>
                            </span>
                        </button>
                    </li>

                    <li>
                        <button type="button" data-dotty-message-conversation="tennis" class="dotty-message-conversation-item flex w-full items-start gap-3 bg-gray-50 p-4 text-left">
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-gray-900 font-bold text-amber-300" aria-hidden="true">테</span>

                            <span class="min-w-0 flex-1">
                                <span class="block truncate font-bold text-gray-900">테니스 커뮤니티 · 그린테이블</span>
                                <time datetime="2026-08-19T14:10" class="shrink-0 text-2xs text-gray-400">2026.08.18 09:10</time>
                                <span class="block truncate text-2xs text-gray-500">조건 확인했습니다. 정기 모임 일정과 맞춰 노출 시작일을 9월 3일로 조정할 수 있을까요?</span>
                            </span>
                        </button>
                    </li>
                </ul>
            </nav>
        </aside>

        <article class="min-w-0 flex-1">
            <header class="flex flex-col items-start justify-center h-18 border-b border-gray-200 p-4">
                <h3 id="dotty-message-conversation-title" class="truncate text-sm font-bold text-gray-900">러닝 회원 전용 회복식 기획전 제안</h3>
                <p id="dotty-message-conversation-meta" class="mt-1 text-2xs text-gray-400">그린테이블 ↔ 러닝 메이트 · MSG-DOTI-GT-RUNNING</p>
            </header>

            <div class="dotty-message-chat">
                <ol id="dotty-message-list" class="space-y-2 p-4" aria-label="러닝 메이트와의 메시지">
                    <li class="flex justify-start">
                        <article class="flex max-w-[80%] flex-col items-start text-left">
                            <p id="dotty-message-received-sender" class="text-2xs font-semibold text-gray-900">러닝 메이트</p>
                            <p id="dotty-message-received-content" class="mt-1 w-fit max-w-full rounded-2xl rounded-tl-none border border-gray-200 bg-white p-3 text-xs text-gray-800">
                                정기런 참여 회원을 위한 회복식 기획전을 제안드립니다. 2주간 500세트 규모와 도넛 전용 구성을 협의할 수 있을까요?
                            </p>
                            <time id="dotty-message-received-time" datetime="2026-08-19T14:10" class="mt-1 block text-2xs text-gray-400">2026.08.19 14:10</time>
                        </article>
                    </li>

                    <li class="flex justify-end">
                        <article class="flex max-w-[80%] flex-col items-end text-right">
                            <p id="dotty-message-sent-sender" class="text-2xs font-semibold text-gray-900">그린 테이블</p>
                            <p id="dotty-message-sent-content" class="mt-1 max-w-full rounded-2xl rounded-tr-none border border-amber-200 bg-amber-50 p-3 text-xs text-gray-800">
                                가능할 것 같습니다.
                            </p>
                            <time id="dotty-message-sent-time" datetime="2026-08-19T14:13" class="mt-1 block text-2xs text-gray-400">2026.08.19 14:13</time>
                        </article>
                    </li>
                </ol>

                <form id="dotty-message-form" method="post" class="flex gap-2 border-t border-gray-300 p-4">
                    <label for="dotty-message-content" class="sr-only">메시지 내용</label>
                    <textarea id="dotty-message-content" name="message_content" rows="3" required placeholder="도넛에 보낼 메시지를 입력해 주세요." class="min-h-18 flex-1 rounded-lg border border-gray-300 p-3 text-xs text-gray-900"></textarea>

                    <button type="submit" class="shrink-0 rounded-lg bg-amber-400 px-5 py-3 text-xs font-bold text-gray-900">
                        보내기
                    </button>
                </form>
            </div>
        </article>
    </div>
</section>

<section id="management-panel-donut" class="management-panel" role="tabpanel" aria-labelledby="management-tab-donut" tabindex="0" hidden>
    <!-- 도넛 목록 패널 -->

</section>

<script>
    // 상단 탭 전환
    $('.management-tab').on('click', function() {
        const $selectedTab = $(this);
        const panelId = $selectedTab.attr('aria-controls');

        $('.management-panel').attr('hidden', true);
        $('#' + panelId).removeAttr('hidden');

        $('.management-tab')
            .attr('aria-selected', 'false')
            .removeClass('bg-gray-900 text-white')
            .addClass('text-gray-500');

        $selectedTab
            .attr('aria-selected', 'true')
            .removeClass('text-gray-500')
            .addClass('bg-gray-900 text-white');
    });

    // 관리 중인 도넛 검색 기능
    const $donutSearchInput = $('#management-donut-search');
    const $donutSearchRows = $('#management-donut-list-body .management-donut-search-row');
    const $donutSearchEmpty = $('#management-donut-search-empty');

    // on Input 으로 실시간 입력 감지
    $donutSearchInput.on('input', function() {
        const keyword = $.trim($(this).val()).toLowerCase();
        let matchedCount = 0;

        // 행 마다 반복 실행
        $donutSearchRows.each(function() {
            // 행의 td 중 첫번째 td 만 선택하고 그 안의 모든 글자를 가져온다
            const searchText = $(this).children('td').first().text().toLowerCase();

            // 검색 키워드가 첫번째 td 글자 안에 포함되면 true, 없으면 false
            const isMatched = searchText.includes(keyword);

            // true 이면 행을 보이기, false 이면 행을 숨기기
            $(this).toggle(isMatched);

            // 검색된 행 숫자 반복 증가
            if (isMatched) {
                matchedCount += 1;
            }
        });

        // 검색된 행이 있으면 빈 결과 UI 숨김
        $donutSearchEmpty.prop('hidden', matchedCount !== 0);
    });

    // 통합 쪽지 도넛별 하드코딩 대화 데이터
    const dottyMessageData = {
        running: {
            title: '러닝 회원 전용 회복식 기획전 제안',
            meta: '그린테이블 ↔ 러닝 메이트 · MSG-DOTI-GT-RUNNING',
            messageLabel: '러닝 메이트와의 메시지',
            receivedSender: '러닝 메이트',
            receivedContent: '정기런 참여 회원을 위한 회복식 기획전을 제안드립니다. 2주간 500세트 규모와 도넛 전용 구성을 협의할 수 있을까요?',
            receivedDatetime: '2026-08-19T14:10',
            receivedTime: '2026.08.19 14:10',
            sentSender: '그린 테이블',
            sentContent: '가능할 것 같습니다.',
            sentDatetime: '2026-08-19T14:13',
            sentTime: '2026.08.19 14:13'
        },
        tennis: {
            title: '회원 전용 고단백 간식 기획전 제안',
            meta: '그린테이블 ↔ 테니스 커뮤니티 · MSG-DOTI-GT-TENNIS',
            messageLabel: '테니스 커뮤니티와의 메시지',
            receivedSender: '테니스 커뮤니티',
            receivedContent: '제안 감사합니다. 정기 모임 일정과 맞춰 노출 시작일을 9월 3일로 조정할 수 있을까요?',
            receivedDatetime: '2026-08-18T09:10',
            receivedTime: '2026.08.18 09:10',
            sentSender: '그린 테이블',
            sentContent: '네, 9월 3일 노출 시작으로 준비하겠습니다.',
            sentDatetime: '2026-08-18T09:24',
            sentTime: '2026.08.18 09:24'
        }
    };

    // 통합 쪽지 대화 목록 선택
    const $dottyMessagePanel = $('#management-panel-message');
    const $dottyMessageItems = $dottyMessagePanel.find('.dotty-message-conversation-item');

    $dottyMessageItems.on('click', function() {
        const $selectedItem = $(this);
        const conversationKey = $selectedItem.attr('data-dotty-message-conversation');
        const conversation = dottyMessageData[conversationKey];

        if (!conversation) {
            return;
        }

        $dottyMessagePanel.find('#dotty-message-conversation-title').text(conversation.title);
        $dottyMessagePanel.find('#dotty-message-conversation-meta').text(conversation.meta);
        $dottyMessagePanel.find('#dotty-message-list').attr('aria-label', conversation.messageLabel);
        $dottyMessagePanel.find('#dotty-message-received-sender').text(conversation.receivedSender);
        $dottyMessagePanel.find('#dotty-message-received-content').text(conversation.receivedContent);
        $dottyMessagePanel.find('#dotty-message-received-time')
            .attr('datetime', conversation.receivedDatetime)
            .text(conversation.receivedTime);
        $dottyMessagePanel.find('#dotty-message-sent-sender').text(conversation.sentSender);
        $dottyMessagePanel.find('#dotty-message-sent-content').text(conversation.sentContent);
        $dottyMessagePanel.find('#dotty-message-sent-time')
            .attr('datetime', conversation.sentDatetime)
            .text(conversation.sentTime);

        $dottyMessageItems
            .removeAttr('aria-current')
            .removeClass('border-l-4 border-amber-400 bg-amber-50 px-4 py-4')
            .addClass('bg-gray-50 p-4');

        $selectedItem
            .attr('aria-current', 'true')
            .removeClass('bg-gray-50 p-4')
            .addClass('border-l-4 border-amber-400 bg-amber-50 px-4 py-4');
    });

    // 도넛 목록의 홈·도넛 목록 탭 위치 전환
    const $donutListHomeSlot = $('#management-donut-list-home-slot');
    const $donutListPanel = $('#management-panel-donut');
    const $donutListSection = $('#management-donut-list-section');

    $('#management-tab-home').on('click', function() {
        $donutListHomeSlot.append($donutListSection);
    });

    $('#management-tab-donut').on('click', function() {
        $donutListPanel.append($donutListSection);
    });

    // 도넛 목록 운영 업무 필터 선택 UI
    const $donutFilters = $('.management-donut-filter');

    $donutFilters.on('click', function() {
        const $selectedFilter = $(this);

        $donutFilters
            .attr('aria-pressed', 'false')
            .removeClass('bg-gray-900 text-white')
            .addClass('text-gray-500');

        $selectedFilter
            .attr('aria-pressed', 'true')
            .removeClass('text-gray-500')
            .addClass('bg-gray-900 text-white');
    });

    // 통합 홈 업무 카드에서 통합 업무함 탭으로 이동
    $('.management-task-view-trigger').on('click', function() {
        $('#management-tab-task').trigger('click');
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

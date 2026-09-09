<?php
$sub_menu = '710140';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '가입 도트';
require_once '../admin.head.php';
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="mt-1 text-gray-400">가입 완료된 도트의 커뮤니티 활동 상태를 확인합니다.</p>
        <button type="button" id="join-request-policy-modal-open" class="shrink-0 border border-gray-300 rounded-lg bg-white px-3 py-2 text-gray-900 font-bold">
            목록 내려받기
        </button>
    </div>

    <section>
        <h3 class="sound_only">가입 도트 현황</h3>

        <div class="grid grid-cols-1 pc:grid-cols-4 gap-4 mt-4">
            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">전체 가입 도트</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">8,720<span class="ml-1 text-sm">명</span></p>
                <span class="mt-3 block text-2xs text-green-600">이번 달 +214명</span>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">최근 7일 활동</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">2,843<span class="ml-1 text-sm">명</span></p>
                <span class="mt-3 block text-2xs text-blue-600">활동률 32.6%</span>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">지정 운영자</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">2<span class="ml-1 text-sm">명</span></p>
                <span class="mt-3 block text-2xs text-gray-600">수락 완료 · 대표 도티 제외</span>
            </div>

            <div class="border border-gray-300 rounded-lg bg-white font-normal p-4">
                <p class="text-xs text-gray-500">활동 제한</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">1<span class="ml-1 text-sm">명</span></p>
                <span class="mt-3 block text-2xs text-emerald-600">상태 확인 필요</span>
            </div>
        </div>
    </section>


    <section class="mt-4">
        <h3 class="sound_only">가입 도트 목록</h3>

        <form id="member-activity-search-form" method="get" class="flex flex-col gap-3 pc:flex-row pc:items-center">
            <div class="flex-1 min-w-0 flex items-center border border-gray-300 rounded-lg bg-white">
                <label for="member-activity-search" class="sound_only">닉네임, 이름 또는 도트 ID 검색</label>

                <input type="search" id="member-activity-search" name="q" class="flex-1 min-w-0 outline-none p-3" placeholder="닉네임, 이름 또는 도트 ID 검색">

                <button type="submit" class="shrink-0 p-3 text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    <span class="sound_only">검색</span>
                </button>
            </div>

            <label for="member-activity-status" class="sound_only">가입 도트 상태 선택</label>
            <select id="member-activity-status" name="status" class="shrink-0 rounded-lg border border-gray-300 bg-white text-gray-900">
                <option value="all">전체 상태</option>
                <option value="normal">정상</option>
                <option value="restricted">활동 제한</option>
                <option value="withdrawn">탈퇴</option>
            </select>

            <span class="shrink-0 text-2xs text-gray-500">검색 결과 6명</span>
        </form>

        <div class="mt-4 overflow-x-auto rounded-lg border border-gray-300 bg-white">
            <table class="border-collapse w-full min-w-225 text-left text-xs text-gray-900">
                <caption class="sound_only">가입 도트 목록</caption>

                <colgroup>
                    <col class="w-[15%]">
                    <col class="w-[15%]">
                    <col class="w-[15%]">
                    <col class="w-[11.5%]">
                    <col class="w-[11.5%]">
                    <col class="w-[11.5%]">
                    <col class="w-[11.5%]">
                    <col class="w-[9%]">
                </colgroup>

                <thead class="border-b border-gray-300 bg-gray-50 text-gray-500 font-bold [&_th]:p-3">
                    <tr>
                        <th scope="col">도트</th>
                        <th scope="col">도트 ID</th>
                        <th scope="col">가입일</th>
                        <th scope="col">작성 글</th>
                        <th scope="col">작성 댓글</th>
                        <th scope="col">권한</th>
                        <th scope="col">상태</th>
                        <th scope="col" class="th_center">상세</th>
                    </tr>
                </thead>

                <tbody id="member-activity-list-body" class="[&_td]:p-3">
                    <tr class="member-activity-search-row border-b border-gray-200">
                        <td>
                            <p class="font-bold">홍길동님</p>
                            <span class="mt-1 block text-2xs text-gray-400">홍길동</span>
                        </td>
                        <td>DOT-48102</td>
                        <td>2025.11.01</td>
                        <td>12</td>
                        <td>86</td>
                        <td>도트</td>
                        <td>
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">• 정상</span>
                        </td>
                        <td class="td_center">
                            <button type="button" class="member-activity-modal-open rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">보기</button>
                        </td>
                    </tr>

                    <tr class="member-activity-search-row border-b border-gray-200">
                        <td>
                            <p class="font-bold">스매시킬러</p>
                            <span class="mt-1 block text-2xs text-gray-400">김도현</span>
                        </td>
                        <td>DOT-41092</td>
                        <td>2026.01.14</td>
                        <td>34</td>
                        <td>151</td>
                        <td>
                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-2xs font-bold text-blue-700">• 직장 운영자</span>
                        </td>
                        <td>
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">• 정상</span>
                        </td>
                        <td class="td_center">
                            <button type="button" class="member-activity-modal-open rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">보기</button>
                        </td>
                    </tr>

                    <tr class="member-activity-search-row border-b border-gray-200">
                        <td>
                            <p class="font-bold">박도티</p>
                            <span class="mt-1 block text-2xs text-gray-400">박도티</span>
                        </td>
                        <td>DOT-39811</td>
                        <td>2025.08.20</td>
                        <td>8</td>
                        <td>49</td>
                        <td>도트</td>
                        <td>
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">• 정상</span>
                        </td>
                        <td class="td_center">
                            <button type="button" class="member-activity-modal-open rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">보기</button>
                        </td>
                    </tr>

                    <tr class="member-activity-search-row border-b border-gray-200">
                        <td>
                            <p class="font-bold">수빈딸리</p>
                            <span class="mt-1 block text-2xs text-gray-400">최수빈</span>
                        </td>
                        <td>DOT-35624</td>
                        <td>2026.03.02</td>
                        <td>5</td>
                        <td>22</td>
                        <td>도트</td>
                        <td>
                            <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-2xs font-bold text-red-600">• 활동 제한</span>
                        </td>
                        <td class="td_center">
                            <button type="button" class="member-activity-modal-open rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">보기</button>
                        </td>
                    </tr>

                    <tr class="member-activity-search-row border-b border-gray-200">
                        <td>
                            <p class="font-bold">테니스유진</p>
                            <span class="mt-1 block text-2xs text-gray-400">이유진</span>
                        </td>
                        <td>DOT-28770</td>
                        <td>2025.12.18</td>
                        <td>19</td>
                        <td>118</td>
                        <td>도트</td>
                        <td>
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">• 정상</span>
                        </td>
                        <td class="td_center">
                            <button type="button" class="member-activity-modal-open rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">보기</button>
                        </td>
                    </tr>

                    <tr class="member-activity-search-row">
                        <td>
                            <p class="font-bold">우석포핸드</p>
                            <span class="mt-1 block text-2xs text-gray-400">정우석</span>
                        </td>
                        <td>DOT-23105</td>
                        <td>2025.09.11</td>
                        <td>0</td>
                        <td>7</td>
                        <td>도트</td>
                        <td>
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-1 text-2xs font-bold text-gray-600">• 탈퇴</span>
                        </td>
                        <td class="td_center">
                            <button type="button" class="member-activity-modal-open rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">보기</button>
                        </td>
                    </tr>

                    <tr id="member-activity-search-empty" hidden>
                        <td colspan="8" class="p-4 text-center text-xs text-gray-500">
                            검색 결과가 없습니다.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<!-- 도트 상세 모달 -->
<div id="member-activity-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="member-activity-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="member-activity-modal-container" role="dialog" aria-modal="true" aria-labelledby="member-activity-modal-title" class="relative z-10 max-h-[90vh] w-full max-w-160 overflow-y-auto rounded-lg bg-white">
        <div id="member-activity-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="member-activity-modal-title" class="text-base font-bold text-gray-900">
                도트 상세
            </h3>

            <button type="button" id="member-activity-modal-close" aria-label="도트 상세 닫기" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5" aria-hidden="true">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="member-activity-modal-body" class="space-y-3 p-4 text-xs text-gray-900">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-amber-300 text-base font-bold text-gray-900">
                    홍
                </div>

                <div>
                    <p class="font-bold">홍길동님</p>
                    <span class="mt-1 block text-2xs text-gray-400">홍길동 · DOT-48102</span>

                    <div class="mt-2 flex gap-3 text-2xs text-gray-500">
                        <span>가입 <span class="font-bold text-gray-900">2025.11.01</span></span>
                        <span>글 <span class="font-bold text-gray-900">12</span></span>
                        <span>댓글 <span class="font-bold text-gray-900">86</span></span>
                    </div>
                </div>
            </div>

            <div class="rounded-lg bg-gray-100 p-3">
                <span class="block text-2xs text-gray-400">현재 상태</span>
                <p class="mt-2">정상 활동 중</p>
            </div>

            <div class="rounded-lg bg-gray-100 p-3">
                <span class="block text-2xs text-gray-400">계정 · 역할</span>
                <p class="mt-2">하나의 계정에 도트 역할을 보유합니다. 승계 승인 시 같은 계정에 도티 역할이 추가됩니다.</p>
            </div>

            <div class="rounded-lg bg-gray-100 p-3">
                <span class="block text-2xs text-gray-400">본인인증</span>
                <p class="mt-2">KCP 본인인증 완료</p>
            </div>

            <div class="rounded-lg bg-amber-50 p-3 text-2xs text-amber-800">
                주문 · 배송 및 상품 문의 처리는 쇼핑 운영 영역에서 담당하며 도티 관리 범위에 포함되지 않습니다.
            </div>
        </div>

        <div id="member-activity-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="member-activity-modal-footer-close" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-bold text-gray-900">
                닫기
            </button>
            <button type="button" id="member-activity-modal-restrict" class="rounded-lg border border-red-300 bg-white px-3 py-2 text-sm font-bold text-red-600">
                활동 제한
            </button>
        </div>
    </div>
</div>

<script>
    const $memberActivityModal = $('#member-activity-modal');

    $('.member-activity-modal-open').on('click', function() {
        $memberActivityModal.prop('hidden', false);
    });

    $('#member-activity-modal-close, #member-activity-modal-backdrop, #member-activity-modal-footer-close').on('click', function() {
        $memberActivityModal.prop('hidden', true);
    });

    // 도트 검색 기능
    const $memberActivitySearchForm = $('#member-activity-search-form');
    const $memberActivitySearch = $('#member-activity-search');
    const $memberActivityRows = $('#member-activity-list-body .member-activity-search-row');
    const $memberActivitySearchEmpty = $('#member-activity-search-empty');

    $memberActivitySearch.on('input', function() {
        const keyword = $.trim($(this).val()).toLowerCase();
        let matchedCount = 0;

        $memberActivityRows.each(function() {
            const $row = $(this);
            const isMatched = $row.text().toLowerCase().includes(keyword);

            $row.toggle(isMatched);

            if (isMatched) {
                matchedCount += 1;
            }
        });

        $memberActivitySearchEmpty.prop('hidden', matchedCount !== 0);
    });

    $memberActivitySearchForm.on('submit', function(event) {
        event.preventDefault();
        $memberActivitySearch.trigger('input');
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

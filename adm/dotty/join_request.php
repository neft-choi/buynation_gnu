<?php
$sub_menu = '710130';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '가입 신청 관리';
require_once '../admin.head.php';
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="mt-1 text-gray-400">신청 답변을 확인한 뒤 승인하거나 거절할 수 있습니다.</p>
        <button type="button" id="join-request-policy-modal-open" class="shrink-0 border border-gray-300 rounded-lg bg-white px-3 py-2 text-gray-900 font-bold">
            재신청 정책 보기
        </button>
    </div>

    <section>
        <h3 class="sound_only">가입 신청 현황</h3>

        <div class="mt-4 grid grid-cols-1 gap-4 pc:grid-cols-4">
            <div class="rounded-lg border border-gray-300 bg-white p-4">
                <p class="text-xs text-gray-500">승인 대기</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">3<span class="ml-1 text-base">건</span></p>
                <span class="mt-3 block text-2xs text-amber-600">검토가 필요합니다.</span>
            </div>

            <div class="rounded-lg border border-gray-300 bg-white p-4">
                <p class="text-xs text-gray-500">오늘 승인</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">11<span class="ml-1 text-base">건</span></p>
                <span class="mt-3 block text-2xs text-emerald-600">처리 결과 즉시 반영</span>
            </div>

            <div class="rounded-lg border border-gray-300 bg-white p-4">
                <p class="text-xs text-gray-500">오늘 거절</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">2<span class="ml-1 text-base">건</span></p>
                <span class="mt-3 block text-2xs text-red-600">사유 입력 완료</span>
            </div>

            <div class="rounded-lg border border-gray-300 bg-white p-4">
                <p class="text-xs text-gray-500">오늘 승인율</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">84.6<span class="ml-1 text-base">%</span></p>
                <span class="mt-3 block text-2xs text-blue-600">승인 ÷ 전체 처리</span>
            </div>
        </div>
    </section>

    <section class="mt-4">
        <h3 class="sound_only">가입 신청 목록</h3>

        <form id="join-request-search-form" method="get" class="flex flex-col gap-3 pc:flex-row pc:items-center">
            <div class="flex shrink-0 rounded-lg bg-gray-100 p-1">
                <button type="submit" name="status" value="all" aria-pressed="true" class="rounded-md bg-white px-3 py-2 text-xs font-bold text-gray-900 shadow-sm">
                    전체
                </button>
                <button type="submit" name="status" value="pending" aria-pressed="false" class="rounded-md px-3 py-2 text-xs font-bold text-gray-500">
                    승인 대기 3
                </button>
                <button type="submit" name="status" value="rejected" aria-pressed="false" class="rounded-md px-3 py-2 text-xs font-bold text-gray-500">
                    승인 거절 2
                </button>
            </div>

            <div class="flex min-w-0 flex-1 items-center rounded-lg border border-gray-300 bg-white">
                <label for="join-request-search" class="sound_only">신청자명 또는 신청번호 검색</label>

                <input type="search" id="join-request-search" name="q" class="min-w-0 flex-1 rounded-lg px-3 py-2 text-sm outline-none" placeholder="신청자명 또는 신청번호 검색">

                <button type="submit" aria-label="가입 신청 검색" class="shrink-0 px-3 py-2 text-gray-900">
                    검색
                </button>
            </div>

            <p class="shrink-0 text-2xs text-gray-500">검색 결과 5건</p>
        </form>

        <div class="mt-4 overflow-x-auto rounded-lg border border-gray-300 bg-white">
            <table class="border-collapse w-full min-w-225 text-left text-xs text-gray-900">
                <caption class="sound_only">가입 신청 목록</caption>

                <colgroup>
                    <col class="w-[14%]">
                    <col class="w-[18%]">
                    <col class="w-[18%]">
                    <col class="w-[14%]">
                    <col class="w-[22%]">
                    <col class="w-[14%]">
                </colgroup>

                <thead class="border-b border-gray-300 bg-gray-50 text-gray-500">
                    <tr>
                        <th scope="col" class="px-3 py-3 font-bold">신청자</th>
                        <th scope="col" class="px-3 py-3 font-bold">신청번호</th>
                        <th scope="col" class="px-3 py-3 font-bold">신청일</th>
                        <th scope="col" class="px-3 py-3 font-bold">처리 상태</th>
                        <th scope="col" class="px-3 py-3 font-bold">안내</th>
                        <th scope="col" class="px-3 py-3 text-center font-bold">검토</th>
                    </tr>
                </thead>

                <tbody id="join-request-list-body">
                    <tr class="join-request-search-row border-b border-gray-200">
                        <td class="px-3 py-3">
                            <p class="font-bold">서울의백핸드</p>
                            <span class="mt-1 block text-2xs text-gray-400">최서윤</span>
                        </td>
                        <td class="px-3 py-3">APP-240803-014</td>
                        <td class="px-3 py-3">2026.08.03 14:22</td>
                        <td class="px-3 py-3">
                            <span class="rounded-full bg-amber-100 px-2 py-1 text-2xs font-bold text-amber-700">● 승인 대기</span>
                        </td>
                        <td class="px-3 py-3">대기 1시간</td>
                        <td class="px-3 py-3 text-center">
                            <button type="button" class="join-request-modal-open rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                신청 검토
                            </button>
                        </td>
                    </tr>

                    <tr class="join-request-search-row border-b border-gray-200">
                        <td class="px-3 py-3">
                            <p class="font-bold">민재서브</p>
                            <span class="mt-1 block text-2xs text-gray-400">김민재</span>
                        </td>
                        <td class="px-3 py-3">APP-240803-013</td>
                        <td class="px-3 py-3">2026.08.03 13:08</td>
                        <td class="px-3 py-3">
                            <span class="rounded-full bg-amber-100 px-2 py-1 text-2xs font-bold text-amber-700">● 승인 대기</span>
                        </td>
                        <td class="px-3 py-3">대기 2시간</td>
                        <td class="px-3 py-3 text-center">
                            <button type="button" class="join-request-modal-open rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                신청 검토
                            </button>
                        </td>
                    </tr>

                    <tr class="join-request-search-row border-b border-gray-200">
                        <td class="px-3 py-3">
                            <p class="font-bold">하늘스매시</p>
                            <span class="mt-1 block text-2xs text-gray-400">박하늘</span>
                        </td>
                        <td class="px-3 py-3">APP-240802-041</td>
                        <td class="px-3 py-3">2026.08.02 19:31</td>
                        <td class="px-3 py-3">
                            <span class="rounded-full bg-amber-100 px-2 py-1 text-2xs font-bold text-amber-700">● 승인 대기</span>
                        </td>
                        <td class="px-3 py-3">대기 19시간</td>
                        <td class="px-3 py-3 text-center">
                            <button type="button" class="join-request-modal-open rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                신청 검토
                            </button>
                        </td>
                    </tr>

                    <tr class="join-request-search-row border-b border-gray-200">
                        <td class="px-3 py-3">
                            <p class="font-bold">지후테니스</p>
                            <span class="mt-1 block text-2xs text-gray-400">오지후</span>
                        </td>
                        <td class="px-3 py-3">APP-240802-028</td>
                        <td class="px-3 py-3">2026.08.02 11:17</td>
                        <td class="px-3 py-3">
                            <span class="rounded-full bg-red-100 px-2 py-1 text-2xs font-bold text-red-600">● 승인 거절</span>
                        </td>
                        <td class="px-3 py-3">재신청 가능 22시간 후</td>
                        <td class="px-3 py-3 text-center">
                            <button type="button" class="join-request-modal-rejected-open rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                거절 사유
                            </button>
                        </td>
                    </tr>

                    <tr class="join-request-search-row">
                        <td class="px-3 py-3">
                            <p class="font-bold">연우랠리</p>
                            <span class="mt-1 block text-2xs text-gray-400">이연우</span>
                        </td>
                        <td class="px-3 py-3">APP-240801-017</td>
                        <td class="px-3 py-3">2026.08.01 09:02</td>
                        <td class="px-3 py-3">
                            <span class="rounded-full bg-red-100 px-2 py-1 text-2xs font-bold text-red-600">● 승인 거절</span>
                        </td>
                        <td class="px-3 py-3">재신청 가능</td>
                        <td class="px-3 py-3 text-center">
                            <button type="button" class="join-request-modal-rejected-open rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                거절 사유
                            </button>
                        </td>
                    </tr>

                    <tr id="join-request-search-empty" hidden>
                        <td colspan="6" class="p-4 text-center text-xs text-gray-500">
                            검색 결과가 없습니다.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<!-- 가입 재신청 정책 모달 -->
<div id="join-request-policy-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="join-request-policy-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="join-request-policy-modal-container" role="dialog" aria-modal="true" aria-labelledby="join-request-policy-modal-title" class="relative z-10 w-full max-w-160 overflow-auto rounded-lg bg-white">
        <div id="join-request-policy-modal-header" class="flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="join-request-policy-modal-title" class="text-base font-bold text-gray-900">
                가입 재신청 정책
            </h3>

            <button type="button" id="join-request-policy-modal-close" aria-label="가입 재신청 정책 닫기" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="join-request-policy-modal-body" class="space-y-3 p-4">
            <div class="rounded-lg bg-gray-100 p-3">
                <p class="text-2xs text-gray-400">승인 거절</p>
                <p class="mt-1 text-xs text-gray-700">신청자에게 선택한 거절 사유 카테고리와 상세 사유를 표시합니다.</p>
            </div>

            <div class="rounded-lg bg-gray-100 p-3">
                <p class="text-2xs text-gray-400">거절 후 재신청</p>
                <p class="mt-1 text-xs text-gray-700">대기 기간 없이 즉시 다시 신청할 수 있습니다.</p>
            </div>

            <div class="rounded-lg bg-gray-100 p-3">
                <p class="text-2xs text-gray-400">도넛 탈퇴 후 재가입</p>
                <p class="mt-1 text-xs text-gray-700">탈퇴 후 72시간이 지난 뒤 재가입할 수 있습니다.</p>
            </div>
        </div>

        <div id="join-request-policy-modal-footer" class="flex justify-end border-t border-gray-300 bg-white p-4">
            <button type="button" id="join-request-policy-modal-footer-close" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-bold text-gray-900">
                닫기
            </button>
        </div>
    </div>
</div>

<!-- 가입 신청 상세 모달 -->
<div id="join-request-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="join-request-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="join-request-modal-container" role="dialog" aria-modal="true" aria-labelledby="join-request-modal-title" class="relative z-10 max-h-[90vh] w-full max-w-160 overflow-y-auto rounded-lg bg-white">
        <div id="join-request-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="join-request-modal-title" class="text-base font-bold text-gray-900">
                가입 신청 상세
            </h3>

            <button type="button" id="join-request-modal-close" aria-label="가입 신청 상세 닫기" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="join-request-modal-body" class="p-4">
            <div class="overflow-hidden rounded-lg border border-gray-300">
                <dl class="text-xs text-gray-900">
                    <div class="flex border-b border-gray-300">
                        <dt class="w-32 shrink-0 bg-gray-50 p-3 text-gray-500">신청자</dt>
                        <dd class="flex-1 p-3 font-bold">서울의백핸드 (최서윤)</dd>
                    </div>

                    <div class="flex border-b border-gray-300">
                        <dt class="w-32 shrink-0 bg-gray-50 p-3 text-gray-500">신청번호</dt>
                        <dd class="flex-1 p-3 font-bold">APP-240803-014</dd>
                    </div>

                    <div class="flex border-b border-gray-300">
                        <dt class="w-32 shrink-0 bg-gray-50 p-3 text-gray-500">신청일</dt>
                        <dd class="flex-1 p-3 font-bold">2026.08.03 14:22</dd>
                    </div>

                    <div class="flex">
                        <dt class="w-32 shrink-0 bg-gray-50 p-3 text-gray-500">상태</dt>
                        <dd id="join-request-modal-status" class="flex-1 p-3 font-bold">승인 대기</dd>
                    </div>
                </dl>
            </div>

            <div class="mt-4 rounded-lg bg-gray-100 p-3">
                <span class="block text-2xs text-gray-400">Q1. 가입 질문</span>
                <p class="mt-2 text-xs text-gray-900">테니스를 시작한 지 2년 되었습니다. 주 2회 정도 즐기고 있어요.</p>
            </div>

            <div class="mt-3 rounded-lg bg-gray-100 p-3">
                <span class="block text-2xs text-gray-400">Q2. 가입 질문</span>
                <p class="mt-2 text-xs text-gray-900">정기 모임과 라켓 정보 공유에 참여하고 싶습니다.</p>
            </div>

            <div id="join-request-modal-notice" class="mt-3 rounded-lg bg-amber-50 p-3 text-2xs text-amber-800">
                <span id="join-request-modal-notice-title" class="block font-bold" hidden>커뮤니티 운영 기준 미충족</span>
                <span id="join-request-modal-notice-message">신청 답변과 커뮤니티 운영 기준을 확인한 뒤 처리해 주세요.</span>
            </div>
        </div>

        <div id="join-request-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="join-request-modal-reject" class="rounded-lg border border-red-300 bg-white px-3 py-2 text-sm font-bold text-red-600">
                가입 거절
            </button>
            <button type="button" id="join-request-modal-approve" class="rounded-lg bg-amber-300 px-3 py-2 text-sm font-bold text-gray-900">
                가입 승인
            </button>
            <button type="button" id="join-request-modal-footer-close" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-bold text-gray-900" hidden>
                닫기
            </button>
        </div>
    </div>
</div>

<script>
    // 가입 재신청 정책 모달 열기 닫기
    const $joinRequestPolicyModal = $('#join-request-policy-modal');

    $('#join-request-policy-modal-open').on('click', function() {
        $joinRequestPolicyModal.prop('hidden', false);
    });

    $('#join-request-policy-modal-close, #join-request-policy-modal-backdrop, #join-request-policy-modal-footer-close').on('click', function() {
        $joinRequestPolicyModal.prop('hidden', true);
    });

    // 가입 신청 상세 모달 열기 닫기
    const $joinRequestModal = $('#join-request-modal');

    $('.join-request-modal-open').on('click', function() {
        $('#join-request-modal-status').text('승인 대기');

        $('#join-request-modal-notice')
            .removeClass('bg-red-50 text-red-700')
            .addClass('bg-amber-50 text-amber-800');

        $('#join-request-modal-notice-title').prop('hidden', true);
        $('#join-request-modal-notice-message').text('신청 답변과 커뮤니티 운영 기준을 확인한 뒤 처리해 주세요.');

        $('#join-request-modal-reject, #join-request-modal-approve').prop('hidden', false);
        $('#join-request-modal-footer-close').prop('hidden', true);

        $joinRequestModal.prop('hidden', false);
    });

    $('.join-request-modal-rejected-open').on('click', function() {
        $('#join-request-modal-status').text('승인 거절');

        $('#join-request-modal-notice')
            .removeClass('bg-amber-50 text-amber-800')
            .addClass('bg-red-50 text-red-700');

        $('#join-request-modal-notice-title').prop('hidden', false);
        $('#join-request-modal-notice-message').text('커뮤니티 활동 목적과 맞지 않는 홍보성 내용이 포함되어 있습니다.');

        $('#join-request-modal-reject, #join-request-modal-approve').prop('hidden', true);
        $('#join-request-modal-footer-close').prop('hidden', false);

        $joinRequestModal.prop('hidden', false);
    });

    $('#join-request-modal-close, #join-request-modal-backdrop, #join-request-modal-footer-close').on('click', function() {
        $joinRequestModal.prop('hidden', true);
    });

    // 가입 신청 검색 기능
    const $joinRequestSearchRows = $('.join-request-search-row');

    $('#join-request-search').on('input', function() {
        const keyword = $(this).val().toLowerCase();
        let hasResult = false;

        $joinRequestSearchRows.each(function() {
            const applicant = $(this).children('td').eq(0).text();
            const requestNumber = $(this).children('td').eq(1).text();
            const isMatched = (applicant + requestNumber).toLowerCase().includes(keyword);

            $(this).toggle(isMatched);

            if (isMatched) {
                hasResult = true;
            }
        });

        $('#join-request-search-empty').prop('hidden', hasResult);
    });

    $('#join-request-search-form').on('submit', function(event) {
        event.preventDefault();
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

<?php
$sub_menu = '730900';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '활동 로그';
require_once '../admin.head.php';
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="mt-1 text-gray-400">중요한 변경과 처리 이력을 확인합니다.</p>

        <button type="button" class="shrink-0 border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
            <span>로그 내려받기</span>
        </button>
    </div>

    <div class="mt-4 flex items-center gap-2">
        <div role="search" class="flex-1">
            <label for="activity-log-search" class="sound_only">운영자, 작업 내용 또는 대상 ID 검색</label>

            <input type="search" id="activity-log-search" class="w-full rounded-lg border border-gray-300 text-gray-900 p-3" placeholder="운영자, 작업 내용 또는 대상 ID 검색" autocomplete="off">
        </div>

        <p id="activity-log-result-count" class="shrink-0 text-2xs text-gray-400">검색 결과 10건</p>
    </div>

    <section class="mt-4">
        <h3 class="sr-only">관리자 활동 로그 목록</h3>

        <div class="overflow-x-auto rounded-lg border border-gray-300">
            <table class="w-full min-w-180 border-collapse text-left">
                <caption class="sr-only">도티와 지정 운영자의 주요 처리 이력</caption>

                <thead class="sr-only">
                    <tr>
                        <th scope="col">처리 일시</th>
                        <th scope="col">운영자</th>
                        <th scope="col">처리 유형</th>
                        <th scope="col">처리 내용</th>
                        <th scope="col">IP 주소</th>
                    </tr>
                </thead>

                <tbody id="activity-log-list-body" class="text-gray-900 [&_td]:p-3">
                    <tr class="activity-log-search-row border-b border-gray-200">
                        <td class="text-2xs">2026.09.08 15:29:56</td>
                        <td>김도윤</td>
                        <td>
                            <span class="flex items-center gap-2 font-bold">
                                <span aria-hidden="true" class="w-2 h-2 rounded-full bg-amber-300"></span>
                                초대 링크 재발급
                            </span>
                        </td>
                        <td>DONUT-TENNIS · v1 무효화 · v2 발급</td>
                        <td class="text-2xs text-gray-400">203.0.113.12</td>
                    </tr>

                    <tr class="activity-log-search-row border-b border-gray-200">
                        <td class="text-2xs">2026.08.18 14:51:07</td>
                        <td>김도윤</td>
                        <td>
                            <span class="flex items-center gap-2 font-bold">
                                <span aria-hidden="true" class="w-2 h-2 rounded-full bg-amber-300"></span>
                                추천상품 등록
                            </span>
                        </td>
                        <td>P-808 정기배송 혼합박스 24개입 · 일반 상품</td>
                        <td class="text-2xs text-gray-400">203.0.113.12</td>
                    </tr>

                    <tr class="activity-log-search-row border-b border-gray-200">
                        <td class="text-2xs">2026.08.18 14:51:04</td>
                        <td>김도윤</td>
                        <td>
                            <span class="flex items-center gap-2 font-bold">
                                <span aria-hidden="true" class="w-2 h-2 rounded-full bg-amber-300"></span>
                                추천상품 등록
                            </span>
                        </td>
                        <td>P-805 콜드프레스 주스 12병 · 일반 상품</td>
                        <td class="text-2xs text-gray-400">203.0.113.12</td>
                    </tr>

                    <tr class="activity-log-search-row border-b border-gray-200">
                        <td class="text-2xs">2026.08.18 14:51:01</td>
                        <td>김도윤</td>
                        <td>
                            <span class="flex items-center gap-2 font-bold">
                                <span aria-hidden="true" class="w-2 h-2 rounded-full bg-amber-300"></span>
                                추천상품 등록
                            </span>
                        </td>
                        <td>P-804 선물용 대형 패키지 · 일반 상품</td>
                        <td class="text-2xs text-gray-400">203.0.113.12</td>
                    </tr>

                    <tr class="activity-log-search-row border-b border-gray-200">
                        <td class="text-2xs">2026.08.03 15:41:22</td>
                        <td>김도윤</td>
                        <td>
                            <span class="flex items-center gap-2 font-bold">
                                <span aria-hidden="true" class="w-2 h-2 rounded-full bg-amber-300"></span>
                                로그인
                            </span>
                        </td>
                        <td>도티 관리자 로그인</td>
                        <td class="text-2xs text-gray-400">203.0.113.12</td>
                    </tr>

                    <tr class="activity-log-search-row border-b border-gray-200">
                        <td class="text-2xs">2026.08.03 14:05:09</td>
                        <td>김도윤</td>
                        <td>
                            <span class="flex items-center gap-2 font-bold">
                                <span aria-hidden="true" class="w-2 h-2 rounded-full bg-amber-300"></span>
                                토핑 지급
                            </span>
                        </td>
                        <td>DOT-39811에게 5,000 토핑 지급</td>
                        <td class="text-2xs text-gray-400">203.0.113.12</td>
                    </tr>

                    <tr class="activity-log-search-row border-b border-gray-200">
                        <td class="text-2xs">2026.08.03 13:44:51</td>
                        <td>김도현</td>
                        <td>
                            <span class="flex items-center gap-2 font-bold">
                                <span aria-hidden="true" class="w-2 h-2 rounded-full bg-amber-300"></span>
                                가입 승인
                            </span>
                        </td>
                        <td>APP-240803-011 가입 신청 승인</td>
                        <td class="text-2xs text-gray-400">203.0.113.41</td>
                    </tr>

                    <tr class="activity-log-search-row border-b border-gray-200">
                        <td class="text-2xs">2026.08.03 11:20:08</td>
                        <td>김도윤</td>
                        <td>
                            <span class="flex items-center gap-2 font-bold">
                                <span aria-hidden="true" class="w-2 h-2 rounded-full bg-amber-300"></span>
                                게시글 등록
                            </span>
                        </td>
                        <td>커뮤니티 01 게시글 POST-00871 등록</td>
                        <td class="text-2xs text-gray-400">203.0.113.12</td>
                    </tr>

                    <tr class="activity-log-search-row border-b border-gray-200">
                        <td class="text-2xs">2026.08.03 10:15:33</td>
                        <td>김도현</td>
                        <td>
                            <span class="flex items-center gap-2 font-bold">
                                <span aria-hidden="true" class="w-2 h-2 rounded-full bg-amber-300"></span>
                                공지 고정
                            </span>
                        </td>
                        <td>공지 NTC-0023 상단 고정</td>
                        <td class="text-2xs text-gray-400">203.0.113.41</td>
                    </tr>

                    <tr class="activity-log-search-row">
                        <td class="text-2xs">2026.08.02 18:30:21</td>
                        <td>김도현</td>
                        <td>
                            <span class="flex items-center gap-2 font-bold">
                                <span aria-hidden="true" class="w-2 h-2 rounded-full bg-amber-300"></span>
                                토핑 지급
                            </span>
                        </td>
                        <td>DOT-40277에게 3,000 토핑 지급</td>
                        <td class="text-2xs text-gray-400">203.0.113.41</td>
                    </tr>

                    <tr id="activity-log-search-empty" hidden>
                        <td colspan="5" class="text-center text-xs text-gray-500 p-3">
                            검색 결과가 없습니다.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<script>
    const $activityLogSearchInput = $('#activity-log-search');
    const $activityLogSearchRows = $('#activity-log-list-body .activity-log-search-row');
    const $activityLogSearchEmpty = $('#activity-log-search-empty');

    $activityLogSearchInput.on('input', function() {
        const keyword = $.trim($(this).val()).toLowerCase();
        let matchedCount = 0;

        $activityLogSearchRows.each(function() {
            const searchText = $(this).text().toLowerCase();
            const isMatched = searchText.includes(keyword);

            $(this).toggle(isMatched);

            if (isMatched) {
                matchedCount += 1;
            }
        });

        $activityLogSearchEmpty.prop('hidden', matchedCount !== 0);
        $('#activity-log-result-count').text(`검색 결과 ${matchedCount}건`);
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

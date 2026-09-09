<?php
$sub_menu = '710150';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '커뮤니티 콘텐츠';
require_once '../admin.head.php';
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="mt-1 text-gray-400">커뮤니티 01은 도티와 지정 운영자가, 커뮤니티 02는 가입 도트도 작성할 수 있습니다.</p>
        <button type="button" class="shrink-0 rounded-lg bg-amber-400 px-3 py-2 text-gray-900 font-bold">
            + 게시글 작성
        </button>
    </div>

    <div class="mt-4 rounded-lg text-amber-700 bg-amber-100 p-3">
        <p><span class="text-blue-700 font-bold mr-2">작성 권한</span>커뮤니티 01: 도티·지정 운영자 작성 / 가입 도트 댓글 참여 · 커뮤니티 02: 가입 도트와 도티 모두 글·댓글 작성</p>
    </div>

    <section class="mt-4">
        <h3 class="sound_only">커뮤니티 콘텐츠 목록</h3>

        <form id="community-content-search-form" method="get" class="flex flex-col gap-3 pc:flex-row pc:items-center">
            <div id="community-content-search-filter" class="flex shrink-0 rounded-lg bg-gray-100 p-1">
                <button type="submit" name="status" value="community_01" aria-pressed="true" class="rounded-md bg-white px-3 py-2 text-xs font-bold text-gray-900 shadow-sm">
                    커뮤니티 01
                </button>
                <button type="submit" name="status" value="community_02" aria-pressed="false" class="rounded-md px-3 py-2 text-xs font-bold text-gray-500">
                    커뮤니티 02
                </button>
                <button type="submit" name="status" value="comment" aria-pressed="false" class="rounded-md px-3 py-2 text-xs font-bold text-gray-500">
                    댓글
                </button>
                <button type="submit" name="status" value="report" aria-pressed="false" class="rounded-md px-3 py-2 text-xs font-bold text-gray-500">
                    신고 접수
                </button>
            </div>

            <div class="flex min-w-0 flex-1 items-center rounded-lg border border-gray-300 bg-white">
                <label for="community-content-search" class="sound_only">제목 또는 작성자 검색</label>

                <input type="search" id="community-content-search" name="q" class="min-w-0 flex-1 rounded-lg px-3 py-2 text-sm outline-none" placeholder="제목 또는 작성자 검색">

                <button type="submit" class="shrink-0 p-3 text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    <span class="sound_only">검색</span>
                </button>
            </div>

            <p class="shrink-0 text-2xs text-gray-500">검색 결과 2건</p>
        </form>

        <div class="mt-4 overflow-x-auto rounded-lg border border-gray-300 bg-white">
            <table class="border-collapse w-full min-w-225 text-left text-xs text-gray-900">
                <caption class="sound_only">가입 신청 목록</caption>

                <colgroup>
                    <col class="w-[12%]">
                    <col class="w-[22.5%]">
                    <col class="w-[12.5%]">
                    <col class="w-[13%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                </colgroup>

                <thead class="border-b border-gray-300 bg-gray-50 text-gray-500 font-bold [&_th]:p-3">
                    <tr>
                        <th scope="col">구분</th>
                        <th scope="col">제목/접수 내용</th>
                        <th scope="col">작성자</th>
                        <th scope="col">등록일</th>
                        <th scope="col">댓글</th>
                        <th scope="col">좋아요</th>
                        <th scope="col">상태</th>
                        <th scope="col">관리</th>
                    </tr>
                </thead>

                <tbody id="community-content-list-body" class="[&_td]:p-3">
                    <tr class="community-content-search-row border-b border-gray-200">
                        <td>
                            <span class="rounded-full bg-gray-900 px-2 py-1 text-2xs font-bold text-white">● 커뮤니티 01</span>
                        </td>
                        <td>
                            <p class="font-bold">8월 정기 모임 참가 신청 안내</p>
                            <span class="block text-2xs text-gray-400">POST-00871</span>
                        </td>
                        <td>도티 김도윤</td>
                        <td>2026.08.03 11:20</td>
                        <td>38</td>
                        <td>126</td>
                        <td>
                            <span class="rounded-full bg-amber-100 px-2 py-1 text-2xs font-bold text-amber-700">● 게시 중</span>
                        </td>
                        <td>
                            <button type="button" class="community-content-modal-open rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                상세
                            </button>
                        </td>
                    </tr>

                    <tr class="community-content-search-row border-b border-gray-200">
                        <td>
                            <span class="rounded-full bg-gray-900 px-2 py-1 text-2xs font-bold text-white">● 커뮤니티 01</span>
                        </td>
                        <td>
                            <p class="font-bold">여름철 코트 이용 매너를 안내드립니다</p>
                            <span class="block text-2xs text-gray-400">POST-00869</span>
                        </td>
                        <td>운영자 김도현</td>
                        <td>2026.08.02 16:40</td>
                        <td>12</td>
                        <td>74</td>
                        <td>
                            <span class="rounded-full bg-amber-100 px-2 py-1 text-2xs font-bold text-amber-700">● 게시 중</span>
                        </td>
                        <td>
                            <button type="button" class="community-content-modal-open rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                상세
                            </button>
                        </td>
                    </tr>

                    <tr id="community-content-search-empty" hidden>
                        <td colspan="8" class="p-4 text-center text-xs text-gray-500">
                            검색 결과가 없습니다.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<script>
    const $statusButtons = $('#community-content-search-filter > button');

    $statusButtons.on('click', function(event) {
        // 하드코딩 단계에서 제출 방지
        event.preventDefault();

        $statusButtons
            .attr('aria-pressed', 'false')
            .removeClass('bg-white text-gray-900 shadow-sm')
            .addClass('text-gray-500');

        $(this)
            .attr('aria-pressed', 'true')
            .removeClass('text-gray-500')
            .addClass('bg-white text-gray-900 shadow-sm');
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

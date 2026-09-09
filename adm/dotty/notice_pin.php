<?php
$sub_menu = '710160';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '공지사항 및 핀';
require_once '../admin.head.php';
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="mt-1 text-gray-400">고정된 공지는 커뮤니티 상세 상단에서 한 건과 +N 형태로 안내됩니다.</p>
        <button type="button" id="notice-write-modal-open" class="shrink-0 rounded-lg bg-amber-300 px-3 py-2">
            <span class="text-gray-900 font-bold">+ 공지 작성</span>
        </button>
    </div>

    <div class="mt-4 grid grid-cols-1 gap-4 pc:grid-cols-2">
        <div class="rounded-lg border border-gray-300 bg-white p-4">
            <h3 class="text-base font-bold text-gray-900">커뮤니티 노출 미리보기</h3>

            <div class="mt-4 flex items-center justify-between rounded-lg border border-gray-300 text-2xs text-gray-900 p-3">
                <span>[공지] 오프라인 모임 신청 안내</span>
                <span class="font-bold text-amber-700">+2</span>
            </div>

            <p class="mt-2 text-2xs text-gray-400">
                고정 공지가 총 3개이므로 우측에 +2로 표시됩니다.
            </p>
        </div>

        <div class="rounded-lg border border-gray-300 bg-white p-4">
            <h3 class="text-base font-bold text-gray-900">핀 운영 상태</h3>

            <div class="mt-6 flex items-baseline gap-2">
                <span class="text-3xl font-bold text-gray-900">3</span>
                <span class="font-bold text-gray-900">/ 3개</span>
                <span class="rounded-full bg-amber-100 px-2 py-1 text-2xs font-bold text-amber-700">● 고정 한도 도달</span>
            </div>

            <p class="mt-4 text-2xs text-gray-400">
                핀 추가·해제 버튼은 도티와 권한을 받은 지정 운영자에게만 보입니다.
            </p>
        </div>
    </div>

    <div class="mt-4 flex items-center gap-2">
        <form method="get" class="flex min-w-0 flex-1 items-center rounded-lg border border-gray-300 bg-white p-3">
            <input type="search" aria-label="공지 검색" class="min-w-0 flex-1 bg-transparent outline-none" placeholder="공지 제목 또는 작성자 검색">

            <button type="submit" class="shrink-0 text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.35-4.35" />
                </svg>
                <span class="sound_only">검색</span>
            </button>
        </form>

        <span class="shrink-0 text-2xs text-gray-400">검색 결과 6건</span>
    </div>

    <div class="mt-4 overflow-x-auto rounded-lg border border-gray-300 bg-white">
        <table class="border-collapse text-left min-w-225 w-full">
            <caption class="sound_only">공지 사항 목록</caption>

            <colgroup>
                <col class="w-[10%]">
                <col class="w-[25%]">
                <col class="w-[15%]">
                <col class="w-[15%]">
                <col class="w-[10%]">
                <col class="w-[15%]">
                <col class="w-[10%]">
            </colgroup>

            <thead class="border-b border-gray-300 bg-gray-50 text-gray-500 font-bold [&_th]:p-3">
                <tr>
                    <th scope="col" class="th_center">구분</th>
                    <th scope="col">제목</th>
                    <th scope="col">작성자</th>
                    <th scope="col">등록일</th>
                    <th scope="col">조회</th>
                    <th scope="col" class="th_center">핀 상태</th>
                    <th scope="col" class="th_center">관리</th>
                </tr>
            </thead>

            <tbody class="text-gray-900 [&_td]:p-3">
                <tr class="border-b border-gray-200">
                    <td class="td_center">
                        <span class="rounded-full bg-amber-100 px-2 py-1 text-2xs font-bold text-amber-700">● 고정 1</span>
                    </td>
                    <td>
                        <p class="font-bold">오프라인 모임 신청 안내</p>
                        <span class="mt-1 block text-2xs text-gray-400">NTC-0023</span>
                    </td>
                    <td>도티 김도윤</td>
                    <td>2026.08.03</td>
                    <td>1,204</td>
                    <td class="td_center">
                        <button type="button" class="rounded-lg border border-red-300 bg-white px-3 py-2 text-2xs font-bold text-red-600">
                            📌 고정 해제
                        </button>
                    </td>
                    <td class="td_center">
                        <button type="button" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                            상세
                        </button>
                    </td>
                </tr>

                <tr class="border-b border-gray-200">
                    <td class="td_center">
                        <span class="rounded-full bg-amber-100 px-2 py-1 text-2xs font-bold text-amber-700">● 고정 2</span>
                    </td>
                    <td>
                        <p class="font-bold">2026 여름 테니스 캠프 모집 안내</p>
                        <span class="mt-1 block text-2xs text-gray-400">NTC-0022</span>
                    </td>
                    <td>운영자 김도현</td>
                    <td>2026.08.01</td>
                    <td>987</td>
                    <td class="td_center">
                        <button type="button" class="rounded-lg border border-red-300 bg-white px-3 py-2 text-2xs font-bold text-red-600">
                            📌 고정 해제
                        </button>
                    </td>
                    <td class="td_center">
                        <button type="button" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                            상세
                        </button>
                    </td>
                </tr>

                <tr class="border-b border-gray-200">
                    <td class="td_center">
                        <span class="rounded-full bg-amber-100 px-2 py-1 text-2xs font-bold text-amber-700">● 고정 3</span>
                    </td>
                    <td>
                        <p class="font-bold">커뮤니티 운영 가이드라인 안내</p>
                        <span class="mt-1 block text-2xs text-gray-400">NTC-0021</span>
                    </td>
                    <td>도티 김도윤</td>
                    <td>2026.07.29</td>
                    <td>2,101</td>
                    <td class="td_center">
                        <button type="button" class="rounded-lg border border-red-300 bg-white px-3 py-2 text-2xs font-bold text-red-600">
                            📌 고정 해제
                        </button>
                    </td>
                    <td class="td_center">
                        <button type="button" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                            상세
                        </button>
                    </td>
                </tr>

                <tr class="border-b border-gray-200">
                    <td class="td_center">
                        <span class="rounded-full bg-gray-100 px-2 py-1 text-2xs font-bold text-gray-700">● 공지</span>
                    </td>
                    <td>
                        <p class="font-bold">코트 이용 매너 및 안전 수칙</p>
                        <span class="mt-1 block text-2xs text-gray-400">NTC-0020</span>
                    </td>
                    <td>운영자 김도현</td>
                    <td>2026.07.24</td>
                    <td>742</td>
                    <td class="td_center">
                        <button type="button" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                            핀 추가
                        </button>
                    </td>
                    <td class="td_center">
                        <button type="button" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                            상세
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<!-- 공지 작성 모달 -->
<div id="notice-write-modal" class="fixed inset-0 z-1000 flex items-center justify-center" hidden>
    <div id="notice-write-modal-backdrop" class="absolute inset-0 z-10 bg-black/40"></div>

    <div id="notice-write-modal-container" role="dialog" aria-modal="true" aria-labelledby="notice-write-modal-title" class="relative z-20 max-h-[90vh] w-full max-w-160 overflow-y-auto rounded-lg bg-white">
        <div id="notice-write-modal-header" class="flex items-center justify-between border-b border-gray-300 p-4">
            <h3 id="notice-write-modal-title" class="text-base font-bold text-gray-900">
                공지사항 작성
            </h3>

            <button type="button" id="notice-write-modal-close" aria-label="공지사항 작성 닫기" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <form id="notice-write-modal-form" method="post">
            <div class="p-4">
                <div>
                    <label for="notice-write-modal-subject" class="block font-bold text-gray-900">
                        공지 제목
                    </label>

                    <input type="text" id="notice-write-modal-subject" required class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-3 text-sm" placeholder="공지 제목을 입력하세요">
                </div>

                <div class="mt-4">
                    <label for="notice-write-modal-content" class="block font-bold text-gray-900">
                        공지 내용
                    </label>

                    <textarea id="notice-write-modal-content" required class="mt-2 h-40 w-full rounded-lg border border-gray-300 p-3 text-sm" placeholder="중요한 소식을 입력하세요."></textarea>
                </div>

                <div class="mt-4">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="notice-write-modal-pin" class="h-4 w-4">
                        <label for="notice-write-modal-pin" class="text-sm text-gray-900">
                            등록 후 상단에 고정하기
                        </label>
                    </div>

                    <span class="mt-2 block text-2xs text-gray-400">
                        고정 공지는 최대 3개까지 운영할 수 있습니다.
                    </span>
                </div>
            </div>

            <div id="notice-write-modal-footer" class="flex justify-end gap-2 border-t border-gray-300 p-4">
                <button type="button" id="notice-write-modal-cancel" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-bold text-gray-900">
                    취소
                </button>

                <button type="submit" id="notice-write-modal-submit" class="rounded-lg bg-amber-300 px-4 py-3 text-sm font-bold text-gray-900">
                    공지 등록
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $('#notice-write-modal-open').on('click', function() {
        $('#notice-write-modal').prop('hidden', false);
    });

    $('#notice-write-modal-close, #notice-write-modal-cancel, #notice-write-modal-backdrop').on('click', function() {
        $('#notice-write-modal').prop('hidden', true);
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

<?php
$sub_menu = '710170';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '운영자 관리';
require_once '../admin.head.php';
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="mt-1 text-gray-400">도티가 지정한 운영자와 메뉴별 처리 권한을 관리합니다.</p>

        <button type="button" class="admin-modal-open shrink-0 rounded-lg bg-amber-300 px-3 py-2 text-gray-900 font-bold">
            <span>+ 운영자 추가</span>
        </button>
    </div>

    <div class="mt-4 rounded-lg bg-blue-50 p-3">
        <span class="text-blue-600 font-bold">권한 원칙</span>
        <span class="ml-2 text-gray-600">대표 도티는 전체 권한을 가지며, 지정 운영자는 부여된 메뉴만 조회·처리합니다. 토핑 배분·사업자 서류·정산·승계·운영자 관리 권한은 부여할 수 없습니다.</span>
    </div>

    <div class="mt-4 grid grid-cols-1 pc:grid-cols-2 gap-4">
        <section class="border border-gray-300 rounded-lg p-4">
            <h3 class="text-lg font-bold">운영자 3명</h3>

            <ul class="mt-4 space-y-3">
                <li class="flex items-center justify-between border border-gray-300 rounded-lg p-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-100"></div>
                        <div class="space-y-2">
                            <p class="text-sm font-bold">김도윤 · 대표 도티</p>
                            <p class="text-2xs text-gray-600">doyun@sample.kr · 최근 접속 2026.08.03 15:41</p>
                            <span class="block w-fit rounded-lg text-2xs text-gray-600 font-normal bg-gray-100 px-2 py-1">전체 권한</span>
                        </div>
                    </div>

                    <button type="button" class="border border-gray-300 rounded-lg px-3 py-2">
                        <span class="font-bold">보기</span>
                    </button>
                </li>

                <li class="flex items-center justify-between border border-gray-300 rounded-lg p-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-100"></div>
                        <div class="space-y-2">
                            <p class="text-sm font-bold">홍길동 · 지정 운영자</p>
                            <p class="text-2xs text-gray-600">dot48102@sample.kr · 최근 접속 2026.08.03 13:18</p>
                            <span class="block w-fit rounded-lg text-2xs text-gray-600 font-normal bg-gray-100 px-2 py-1">가입 심사</span>
                        </div>
                    </div>

                    <button type="button" data-permissions="join" class="admin-modal-open border border-gray-300 rounded-lg px-3 py-2">
                        <span class="font-bold">권한 수정</span>
                    </button>
                </li>

                <li class="flex items-center justify-between border border-gray-300 rounded-lg p-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-100"></div>
                        <div class="space-y-2">
                            <p class="text-sm font-bold">윤서진 · 지정 운영자</p>
                            <p class="text-2xs text-gray-600">seojin@sample.kr · 최근 접속 2026.08.02 19:02</p>
                            <div class="flex items-center gap-2">
                                <span class="block w-fit rounded-lg text-2xs text-gray-600 font-normal bg-gray-100 px-2 py-1">회원 조회</span>
                                <span class="block w-fit rounded-lg text-2xs text-gray-600 font-normal bg-gray-100 px-2 py-1">추천 상품</span>
                            </div>
                        </div>
                    </div>

                    <button type="button" data-permissions="product" class="admin-modal-open border border-gray-300 rounded-lg px-3 py-2">
                        <span class="font-bold">권한 수정</span>
                    </button>
                </li>
            </ul>
        </section>

        <section class="border border-gray-300 rounded-lg p-4">
            <h3 class="text-lg font-bold">권한 구성 안내</h3>

            <div class="mt-4 space-y-3">
                <div class="rounded-lg bg-gray-100 p-3">
                    <span class="block text-2xs text-gray-600">가입 관리</span>
                    <p class="mt-2">가입 신청 답변 확인, 승인 및 거절 처리</p>
                </div>

                <div class="rounded-lg bg-gray-100 p-3">
                    <span class="block text-2xs text-gray-600">콘텐츠·공지</span>
                    <p class="mt-2">게시글 작성·관리, 공지 작성, 핀 추가·해제</p>
                </div>

                <div class="rounded-lg bg-gray-100 p-3">
                    <span class="block text-2xs text-gray-600">추천상품</span>
                    <p class="mt-2">추천상품 등록·해제</p>
                </div>

                <div class="rounded-lg text-red-600 font-normal bg-red-100 p-3">
                    <p>운영자 변경 이력은 활동 로그에 기록됩니다.</p>
                </div>
            </div>
        </section>
    </div>
</section>

<!-- 운영자 추가 모달 -->
<div id="admin-add-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="admin-add-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="admin-add-modal-container" role="dialog" aria-modal="true" aria-labelledby="admin-add-modal-title" class="relative z-10 max-h-[90vh] w-full max-w-160 overflow-y-auto rounded-lg bg-white">
        <div id="admin-add-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="admin-add-modal-title" class="text-lg font-bold text-gray-900">
                지정 운영자 추가
            </h3>

            <button type="button" id="admin-add-modal-close" aria-label="지정 운영자 추가 모달 닫기" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <form id="admin-add-modal-form" class="p-4">
            <div>
                <label for="admin-add-dotty" class="mb-2 block font-bold text-gray-900">
                    가입 도트 선택
                </label>

                <select id="admin-add-dotty" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-3 text-gray-900">
                    <option value="" selected disabled>도트를 선택하세요</option>
                    <option value="dotty-01">홍길동님 · 홍길동 (DOT-48102)</option>
                    <option value="dotty-02">박도티 · 박도티 (DOT-39811)</option>
                    <option value="dotty-03">테니스유진 · 이유진 (DOT-28770)</option>
                </select>
            </div>

            <fieldset class="mt-4">
                <span class="font-bold text-gray-900">부여할 권한</span>

                <div class="mt-2 space-y-2">
                    <label for="admin-add-permission-join" class="flex cursor-pointer items-center gap-3 rounded-lg bg-gray-100 p-4">
                        <input type="checkbox" id="admin-add-permission-join" class="h-4 w-4">
                        <span>가입 신청 검토 및 승인·거절</span>
                    </label>

                    <label for="admin-add-permission-content" class="flex cursor-pointer items-center gap-3 rounded-lg bg-gray-100 p-4">
                        <input type="checkbox" id="admin-add-permission-content" class="h-4 w-4">
                        <span>콘텐츠 관리</span>
                    </label>

                    <label for="admin-add-permission-notice" class="flex cursor-pointer items-center gap-3 rounded-lg bg-gray-100 p-4">
                        <input type="checkbox" id="admin-add-permission-notice" class="h-4 w-4">
                        <span>공지 작성과 핀 관리</span>
                    </label>

                    <label for="admin-add-permission-product" class="flex cursor-pointer items-center gap-3 rounded-lg bg-gray-100 p-4">
                        <input type="checkbox" id="admin-add-permission-product" class="h-4 w-4">
                        <span>추천상품 등록·해제</span>
                    </label>
                </div>
            </fieldset>

            <div class="mt-4 rounded-lg bg-red-100 p-3 text-2xs text-red-600">
                <p>현재 도넛에 정상 가입한 도트만 지정 운영자로 추가할 수 있습니다. 토핑 배분·정산·승계 권한은 부여할 수 없습니다.</p>
            </div>
        </form>

        <div id="admin-add-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="admin-add-modal-cancel" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-bold text-gray-900">
                취소
            </button>

            <button type="submit" form="admin-add-modal-form" class="rounded-lg bg-amber-300 px-4 py-3 text-sm font-bold text-gray-900">
                운영자 지정
            </button>
        </div>
    </div>
</div>

<script>
    $('.admin-modal-open').on('click', function() {
        const permissions = ($(this).attr('data-permissions') || '').split(',');

        $('#admin-add-modal input[type="checkbox"]').prop('checked', false);

        permissions.forEach(function(permission) {
            if (permission) {
                $('#admin-add-permission-' + permission).prop('checked', true);
            }
        });

        $('#admin-add-modal').prop('hidden', false);
    });

    $('#admin-add-modal-close, #admin-add-modal-cancel, #admin-add-modal-backdrop').on('click', function() {
        $('#admin-add-modal').prop('hidden', true);
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

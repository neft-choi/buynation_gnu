<?php
$sub_menu = '970200';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '플랫폼 관리자·권한';
require_once '../../admin.head.php';
?>

<section>
    <header class="flex items-center justify-between">
        <p class="text-gray-400 font-normal">업무별 최소 권한과 최근 접근을 확인합니다.</p>

        <button type="button" class="platform-admin-add-modal-open w-fit border border-transparent rounded-lg bg-amber-300 text-gray-900 font-bold px-3 py-2">
            관리자 추가
        </button>
    </header>

    <ul class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
        <li class="flex items-center justify-between gap-4 rounded-lg border border-gray-300 bg-white p-4">
            <div class="flex min-w-0 items-center gap-3">
                <div
                    aria-hidden="true"
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-300 font-bold text-gray-900">
                    김
                </div>

                <div class="min-w-0">
                    <h3 class="font-bold text-gray-900">
                        김○○ · 최고 관리자
                    </h3>

                    <p class="mt-1 text-2xs text-gray-400">
                        finance@donuts.example · 최근 방금
                    </p>

                    <ul class="mt-2 flex flex-wrap gap-1" aria-label="보유 권한">
                        <li class="rounded bg-gray-100 px-2 py-1 text-2xs text-gray-600">
                            전체 권한
                        </li>
                    </ul>
                </div>
            </div>

            <button
                type="button"
                aria-label="김○○ 관리자 권한 설정"
                class="admin-detail-modal-open shrink-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                권한
            </button>
        </li>

        <li class="flex items-center justify-between gap-4 rounded-lg border border-gray-300 bg-white p-4">
            <div class="flex min-w-0 items-center gap-3">
                <div
                    aria-hidden="true"
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-300 font-bold text-gray-900">
                    박
                </div>

                <div class="min-w-0">
                    <h3 class="font-bold text-gray-900">
                        박○○ · 입점 심사자
                    </h3>

                    <p class="mt-1 text-2xs text-gray-400 font-medium">
                        partner@donuts.example · 최근 오늘 15:31
                    </p>

                    <ul class="mt-2 flex flex-wrap gap-1" aria-label="보유 권한">
                        <li class="rounded bg-gray-100 px-2 py-1 text-2xs text-gray-600">
                            브랜드 서류
                        </li>
                        <li class="rounded bg-gray-100 px-2 py-1 text-2xs text-gray-600">
                            상품 심사
                        </li>
                        <li class="rounded bg-gray-100 px-2 py-1 text-2xs text-gray-600">
                            배송 점검
                        </li>
                    </ul>
                </div>
            </div>

            <button
                type="button"
                aria-label="박○○ 관리자 권한 설정"
                class="admin-detail-modal-open shrink-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                권한
            </button>
        </li>

        <li class="flex items-center justify-between gap-4 rounded-lg border border-gray-300 bg-white p-4">
            <div class="flex min-w-0 items-center gap-3">
                <div
                    aria-hidden="true"
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-300 font-bold text-gray-900">
                    이
                </div>

                <div class="min-w-0">
                    <h3 class="font-bold text-gray-900">
                        이OO · 커뮤니티 운영자
                    </h3>

                    <p class="mt-1 text-2xs text-gray-400 font-medium">
                        community@donuts.example · 최근 오늘 13:54
                    </p>

                    <ul class="mt-2 flex flex-wrap gap-1" aria-label="보유 권한">
                        <li class="rounded bg-gray-100 px-2 py-1 text-2xs text-gray-600">
                            도트·도넛
                        </li>
                        <li class="rounded bg-gray-100 px-2 py-1 text-2xs text-gray-600">
                            도티 심사
                        </li>
                        <li class="rounded bg-gray-100 px-2 py-1 text-2xs text-gray-600">
                            권한 감독
                        </li>
                    </ul>
                </div>
            </div>

            <button
                type="button"
                aria-label="이OO 관리자 권한 설정"
                class="admin-detail-modal-open shrink-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                권한
            </button>
        </li>

        <li class="flex items-center justify-between gap-4 rounded-lg border border-gray-300 bg-white p-4">
            <div class="flex min-w-0 items-center gap-3">
                <div
                    aria-hidden="true"
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-300 font-bold text-gray-900">
                    최
                </div>

                <div class="min-w-0">
                    <h3 class="font-bold text-gray-900">
                        최OO · CS 운영자
                    </h3>

                    <p class="mt-1 text-2xs text-gray-400 font-medium">
                        cs@donuts.example · 최근 어제 18:02
                    </p>

                    <ul class="mt-2 flex flex-wrap gap-1" aria-label="보유 권한">
                        <li class="rounded bg-gray-100 px-2 py-1 text-2xs text-gray-600">
                            주문·클레임
                        </li>
                    </ul>
                </div>
            </div>

            <button
                type="button"
                aria-label="박○○ 관리자 권한 설정"
                class="admin-detail-modal-open shrink-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                권한
            </button>
        </li>
    </ul>
</section>

<!-- 플랫폼 관리자 추가 모달 -->
<div id="platform-admin-add-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="platform-admin-add-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="platform-admin-add-modal-container" role="dialog" aria-modal="true" aria-labelledby="platform-admin-add-modal-title"
        class="relative z-10 flex max-h-[90vh] w-full max-w-120 flex-col overflow-hidden rounded-lg bg-white">

        <!-- 모달 헤더 -->
        <div id="platform-admin-add-modal-header" class="flex shrink-0 items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="platform-admin-add-modal-title" class="text-sm font-bold text-gray-900">
                플랫폼 관리자 추가
            </h3>

            <button type="button" id="platform-admin-add-modal-close" aria-label="플랫폼 관리자 추가 모달 닫기"
                class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <!-- 모달 바디 -->
        <div id="platform-admin-add-modal-body" class="min-h-0 flex-1 overflow-y-auto p-4">
            <form id="platform-admin-add-form">
                <div>
                    <label for="platform-admin-add-name" class="block text-2xs font-bold text-gray-900">
                        이름
                    </label>

                    <input type="text" id="platform-admin-add-name" name="name"
                        class="mt-2 h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-2xs text-gray-900">
                </div>

                <div class="mt-4">
                    <label for="platform-admin-add-email" class="block text-2xs font-bold text-gray-900">
                        이메일
                    </label>

                    <input type="email" id="platform-admin-add-email" name="email"
                        class="mt-2 h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-2xs text-gray-900">
                </div>

                <fieldset class="mt-4">
                    <legend class="text-2xs font-bold text-gray-900">
                        권한
                    </legend>

                    <div class="mt-2 space-y-2">
                        <label class="flex cursor-pointer items-center gap-3 rounded-lg bg-gray-50 p-4">
                            <input type="checkbox" name="permissions[]" value="brand_product"
                                class="h-4 w-4 shrink-0 rounded border-gray-300">
                            <span class="text-xs font-normal text-gray-900">브랜드 · 상품</span>
                        </label>

                        <label class="flex cursor-pointer items-center gap-3 rounded-lg bg-gray-50 p-4">
                            <input type="checkbox" name="permissions[]" value="dot_donut"
                                class="h-4 w-4 shrink-0 rounded border-gray-300">
                            <span class="text-xs font-normal text-gray-900">도티 · 도넛</span>
                        </label>

                        <label class="flex cursor-pointer items-center gap-3 rounded-lg bg-gray-50 p-4">
                            <input type="checkbox" name="permissions[]" value="order_cs"
                                class="h-4 w-4 shrink-0 rounded border-gray-300">
                            <span class="text-xs font-normal text-gray-900">주문 · CS</span>
                        </label>

                        <label class="flex cursor-pointer items-center gap-3 rounded-lg bg-gray-50 p-4">
                            <input type="checkbox" name="permissions[]" value="topping_settlement"
                                class="h-4 w-4 shrink-0 rounded border-gray-300">
                            <span class="text-xs font-normal text-gray-900">토핑 · 정산</span>
                        </label>
                    </div>
                </fieldset>
            </form>
        </div>

        <!-- 모달 푸터 -->
        <div id="platform-admin-add-modal-footer" class="flex shrink-0 justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="platform-admin-add-modal-cancel"
                class="rounded-lg border border-gray-300 bg-white px-4 py-3 font-bold text-gray-900">
                취소
            </button>

            <button type="submit" form="platform-admin-add-form"
                class="rounded-lg border border-transparent bg-amber-300 px-4 py-3 font-bold text-gray-900">
                추가
            </button>
        </div>
    </div>
</div>

<!-- 관리자 권한 모달 -->
<div id="admin-detail-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="admin-detail-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="admin-detail-modal-container" role="dialog" aria-modal="true" aria-labelledby="admin-detail-modal-title"
        class="relative z-10 flex max-h-[90vh] w-full max-w-120 flex-col overflow-hidden rounded-lg bg-white">

        <!-- 모달 헤더 -->
        <div id="admin-detail-modal-header" class="flex shrink-0 items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="admin-detail-modal-title" class="text-sm font-bold text-gray-900">
                관리자 권한
            </h3>

            <button type="button" id="admin-detail-modal-close" aria-label="관리자 권한 모달 닫기"
                class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <!-- 모달 바디 -->
        <div id="admin-detail-modal-body" class="min-h-0 flex-1 overflow-y-auto p-4">
            <div class="rounded-lg border border-gray-300 bg-white p-4">
                <div class="flex items-center gap-3">
                    <div
                        aria-hidden="true"
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-300 font-bold text-gray-900">
                        김
                    </div>

                    <div class="min-w-0">
                        <h3 class="font-bold text-gray-900">
                            김○○ · 최고 관리자
                        </h3>

                        <p class="mt-1 text-2xs text-gray-400">
                            finance@donuts.example
                        </p>

                        <ul class="mt-2 flex flex-wrap gap-1" aria-label="보유 권한">
                            <li class="rounded bg-gray-100 px-2 py-1 text-2xs text-gray-600">
                                전체 권한
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mt-3 flex items-center gap-3 bg-red-50 rounded-lg text-2xs p-3">
                <p class="text-red-700 font-normal">권한 변경은 변경 전후 값과 처리자를 감사 로그에 기록해야 합니다.</p>
            </div>
        </div>

        <!-- 모달 푸터 -->
        <div id="admin-detail-modal-footer" class="flex shrink-0 justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="admin-detail-modal-cancel"
                class="rounded-lg border border-gray-300 bg-white px-4 py-3 font-bold text-gray-900">
                닫기
            </button>
        </div>
    </div>
</div>

<script>
    $('.platform-admin-add-modal-open').on('click', function() {
        $('#platform-admin-add-modal').prop('hidden', false);
    });

    $('#platform-admin-add-modal-cancel, #platform-admin-add-modal-close, #platform-admin-add-modal-backdrop').on('click', function() {
        $('#platform-admin-add-modal').prop('hidden', true);
    });

    $('.admin-detail-modal-open').on('click', function() {
        $('#admin-detail-modal').prop('hidden', false);
    });

    $('#admin-detail-modal-cancel, #admin-detail-modal-close, #admin-detail-modal-backdrop').on('click', function() {
        $('#admin-detail-modal').prop('hidden', true);
    });
</script>

<?php
require_once '../../admin.tail.php';

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

        <form>
            
        </form>
    </section>
</section>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

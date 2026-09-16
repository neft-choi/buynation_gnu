<?php
$sub_menu = '730800';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '운영권 승계';
require_once '../admin.head.php';
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="mt-1 text-gray-400">대표 도티 변경은 플랫폼의 계정 확인과 승인 후 완료됩니다.</p>

        <button type="button" id="ownership-transfer-modal-open" class="shrink-0 rounded-lg bg-amber-300 px-3 py-2 text-gray-900 font-bold">
            <span>+ 승계 요청</span>
        </button>
    </div>

    <div class="mt-4 rounded-lg bg-red-100 text-red-600 p-3">
        <span class="text-blue-600 font-bold">필수 조건</span>
        <span class="ml-2">도트·도티·브랜드는 별도 계정이 아닌 하나의 계정 내 역할입니다. 승계 수락과 KCP 본인인증 상태를 플랫폼이 검토한 후 도티 역할을 부여합니다.</span>
    </div>

    <section class="mt-4 rounded-lg border border-amber-300 bg-amber-50 p-4">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h3 class="font-bold text-gray-900">진행 중인 승계 요청이 없습니다.</h3>
                <p class="mt-1 text-2xs text-gray-500">같은 계정의 KCP 본인인증이 완료된 가입 도트를 승계 대상으로 선택합니다.</p>
            </div>

            <span class="shrink-0 rounded-full bg-gray-100 text-2xs font-bold text-gray-700 px-2 py-1">● 미요청</span>
        </div>
    </section>

    <ol class="mt-4 grid grid-cols-1 gap-4 pc:grid-cols-3">
        <li class="rounded-lg border border-gray-300 bg-white p-4">
            <span class="flex w-7 h-7 items-center justify-center rounded-full bg-amber-300 text-2xs font-bold text-gray-900">1</span>
            <p class="mt-3 font-bold text-gray-900">가입 도트 선택</p>
            <p class="mt-1 text-2xs text-gray-500">현재 도넛에 정상 가입한 도트를 승계 대상으로 선택합니다.</p>
        </li>

        <li class="rounded-lg border border-gray-300 bg-white p-4">
            <span class="flex w-7 h-7 items-center justify-center rounded-full bg-amber-300 text-2xs font-bold text-gray-900">2</span>
            <p class="mt-3 font-bold text-gray-900">수락·본인인증</p>
            <p class="mt-1 text-2xs text-gray-500">승계 수락 기간 안에 대상과 KCP 본인인증 상태를 확인합니다.</p>
        </li>

        <li class="rounded-lg border border-gray-300 bg-white p-4">
            <span class="flex w-7 h-7 items-center justify-center rounded-full bg-amber-300 text-2xs font-bold text-gray-900">3</span>
            <p class="mt-3 font-bold text-gray-900">플랫폼 승인</p>
            <p class="mt-1 text-2xs text-gray-500">승인 완료 시 같은 계정에 도티 역할과 운영권이 부여됩니다.</p>
        </li>
    </ol>

    <section class="mt-4 rounded-lg border border-gray-300 bg-white p-4">
        <h3 class="text-lg font-bold text-gray-900">승계 후보 본인인증 현황</h3>

        <div class="mt-4 overflow-x-auto">
            <table class="border-collapse w-full min-w-180 text-left">
                <caption class="sr-only">승계 후보의 KCP 본인인증과 승계 가능 여부</caption>

                <thead class="border-y border-gray-300 bg-gray-50 text-2xs text-gray-500 [&_th]:font-normal [&_th]:p-3">
                    <tr>
                        <th scope="col">도트</th>
                        <th scope="col">계정 ID</th>
                        <th scope="col">본인인증</th>
                        <th scope="col">요청 역할</th>
                        <th scope="col">승계 가능</th>
                    </tr>
                </thead>

                <tbody class="text-sm text-gray-900 [&_td]:p-3">
                    <tr class="border-b border-gray-200">
                        <td>
                            <p class="font-bold">홍길동님</p>
                            <span class="mt-1 block text-2xs text-gray-400">홍길동</span>
                        </td>
                        <td>DOT-48102</td>
                        <td><span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">● KCP 완료</span></td>
                        <td>도티</td>
                        <td><span class="rounded-full bg-blue-50 text-2xs font-bold text-blue-700 px-2 py-1">● 가능</span></td>
                    </tr>

                    <tr class="border-b border-gray-200">
                        <td>
                            <p class="font-bold">스매시릴리</p>
                            <span class="mt-1 block text-2xs text-gray-400">김도현</span>
                        </td>
                        <td>DOT-41092</td>
                        <td><span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">● KCP 완료</span></td>
                        <td>도티</td>
                        <td><span class="rounded-full bg-blue-50 text-2xs font-bold text-blue-700 px-2 py-1">● 가능</span></td>
                    </tr>

                    <tr class="border-b border-gray-200">
                        <td>
                            <p class="font-bold">박도티</p>
                            <span class="mt-1 block text-2xs text-gray-400">박도티</span>
                        </td>
                        <td>DOT-39811</td>
                        <td><span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">● KCP 완료</span></td>
                        <td>도티</td>
                        <td><span class="rounded-full bg-blue-50 text-2xs font-bold text-blue-700 px-2 py-1">● 가능</span></td>
                    </tr>

                    <tr>
                        <td>
                            <p class="font-bold">테니스유진</p>
                            <span class="mt-1 block text-2xs text-gray-400">이유진</span>
                        </td>
                        <td>DOT-28770</td>
                        <td><span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">● KCP 완료</span></td>
                        <td>도티</td>
                        <td><span class="rounded-full bg-blue-50 text-2xs font-bold text-blue-700 px-2 py-1">● 가능</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<!-- 운영권 승계 대상 선택 모달 -->
<div id="ownership-transfer-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="ownership-transfer-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="ownership-transfer-modal-container" role="dialog" aria-modal="true" aria-labelledby="ownership-transfer-modal-title" class="relative z-10 w-full max-w-160 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div id="ownership-transfer-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="ownership-transfer-modal-title" class="text-lg font-bold text-gray-900">
                운영권 승계 대상 선택
            </h3>

            <button type="button" id="ownership-transfer-modal-close" aria-label="운영권 승계 대상 선택 모달 닫기" class="flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <form id="ownership-transfer-modal-form" class="p-4">
            <div class="rounded-lg bg-red-100 text-2xs text-red-600 p-3">
                <p>가입 도트의 승계 수락과 KCP 본인인증을 확인한 후 같은 계정에 도티 역할을 부여합니다.</p>
            </div>

            <div class="mt-4">
                <label for="ownership-transfer-target" class="mb-2 block font-bold text-gray-900">
                    승계 대상 도트
                </label>

                <select id="ownership-transfer-target" name="ownership_transfer_target" class="w-full rounded-lg border border-gray-300 bg-white text-gray-900 p-3">
                    <option value="" selected disabled>가입 도트를 선택하세요</option>
                    <option value="DOT-48102">홍길동님 · DOT-48102 · KCP 본인인증 완료</option>
                    <option value="DOT-41092">스매시릴리 · DOT-41092 · KCP 본인인증 완료</option>
                    <option value="DOT-39811">박도티 · DOT-39811 · KCP 본인인증 완료</option>
                    <option value="DOT-28770">테니스유진 · DOT-28770 · KCP 본인인증 완료</option>
                </select>
            </div>

            <div class="mt-4 rounded-lg bg-amber-100 text-2xs text-amber-800 p-3">
                <p>승계 수락 기한은 7일이며, 플랫폼 승인 전까지 현재 도티의 권한은 유지됩니다.</p>
            </div>
        </form>

        <div id="ownership-transfer-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="ownership-transfer-modal-cancel" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-bold text-gray-900">
                취소
            </button>

            <button type="submit" form="ownership-transfer-modal-form" class="rounded-lg bg-amber-300 px-4 py-3 text-sm font-bold text-gray-900">
                승계 요청
            </button>
        </div>
    </div>
</div>

<script>
    $('#ownership-transfer-modal-open').on('click', function() {
        $('#ownership-transfer-modal').prop('hidden', false);
    });

    $('#ownership-transfer-modal-close, #ownership-transfer-modal-cancel, #ownership-transfer-modal-backdrop').on('click', function() {
        $('#ownership-transfer-modal').prop('hidden', true);
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

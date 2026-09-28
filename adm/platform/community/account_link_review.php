<?php
$sub_menu = '940200';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '도티 역할 부여 예외';
require_once '../../admin.head.php';
?>

<section>
    <h2 class="sr-only">도티 역할 부여 예외</h2>

    <p class="text-gray-600 font-normal">하나의 계정에 도티 역할을 부여하기 전 KCP 본인인증·승계 수락·승인 근거를 확인합니다.</p>

    <div class="mt-4 flex items-center gap-3 bg-amber-50 rounded-lg text-2xs p-3">
        <span class="text-amber-600 font-bold">역할 원칙</span>
        <p class="text-amber-700 font-normal">도트·도티·브랜드는 하나의 계정 안에서 부여되는 역할입니다. 플랫폼은 KCP 본인인증, 승계 수락, 역할 부여 조건을 검토합니다.</p>
    </div>

    <section class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <div class="overflow-x-auto">
            <table class="border-collapse min-w-250 w-full table-fixed text-left">
                <caption class="sr-only">도트 목록</caption>

                <colgroup>
                    <col class="w-[20%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[20%]">
                    <col class="w-[14%]">
                    <col class="w-[13%]">
                    <col class="w-[13%]">
                </colgroup>

                <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">요청번호</th>
                        <th scope="col">계정</th>
                        <th scope="col">요청 역할</th>
                        <th scope="col">본인인증·수락</th>
                        <th scope="col">요청일</th>
                        <th scope="col">상태</th>
                        <th scope="col">심사</th>
                    </tr>
                </thead>

                <tbody class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:whitespace-nowrap [&_td]:px-4 [&_td]:py-3">
                    <tr>
                        <td>
                            <span class="block font-bold">ROLE-DOTI-260811-01</span>
                        </td>
                        <td>
                            <span class="block">김도현</span>
                            <span class="block text-zinc-400">DOT-41092</span>
                        </td>
                        <td>
                            <span>도티 역할</span>
                        </td>
                        <td>
                            <span>KCP 본인인증·승계 수락 완료</span>
                        </td>
                        <td>
                            <span>2026.08.11 10:20</span>
                        </td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">● 심사 대기</span>
                        </td>
                        <td>
                            <button type="button" class="account-review-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<!-- 도티 역할 부여 예외 심사 모달 -->
<div id="account-review-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="account-review-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="account-review-modal-container" role="dialog" aria-modal="true" aria-labelledby="account-review-modal-title" class="relative z-10 w-full max-w-150 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div id="account-review-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="account-review-modal-title" class="text-sm font-bold text-gray-900">
                도티 역할 부여 예외 심사
            </h3>

            <button type="button" id="account-review-modal-close" aria-label="상품 검수 모달 닫기" class="flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="account-review-modal-body" class="p-4">
            <div class="mt-4 overflow-hidden rounded-lg border border-gray-300">
                <dl class="text-left text-2xs [&>div+div]:border-t [&>div+div]:border-gray-200">
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">요청번호</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">ROLE-DOTI-260811-01</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">도트</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">김도현 · DOT-41092</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">요청 역할</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">도티 역할</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">본인확인</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">KCP 본인인증·승계 수락 완료</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">현재 연결</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">없음</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">관련 승계</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">SUC-PET-001 · 반려생활 연구소</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">상태</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">● 심사 대기</span>
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="mt-3 bg-amber-50 rounded-lg text-2xs p-3">
                <p class="text-amber-700 font-normal">도티 역할 부여 승인만으로 도넛 운영권은 이전되지 않습니다. 관련 승계 요청은 ‘도티 승계 심사’에서 별도 승인해야 합니다.</p>
            </div>
        </div>

        <div id="account-review-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="account-review-modal-cancel" class="rounded-lg border border-gray-300 bg-white font-bold text-gray-900 px-4 py-3">
                닫기
            </button>
            <button type="button" class="rounded-lg border border-red-400 bg-white font-bold text-red-600 px-4 py-3">
                거절
            </button>
            <button type="button" class="rounded-lg border border-transparent bg-amber-300 font-bold px-4 py-3">
                연결 승인
            </button>
        </div>
    </div>
</div>

<script>
    $('.account-review-modal-open').on('click', function() {
        $('#account-review-modal').prop('hidden', false);
    });

    $('#account-review-modal-close, #account-review-modal-cancel, #account-review-modal-backdrop').on('click', function() {
        $('#account-review-modal').prop('hidden', true);
    });
</script>

<?php
require_once '../../admin.tail.php';

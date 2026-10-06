<?php
$sub_menu = '400780';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '담당자·권한';
include_once(G5_ADMIN_PATH . '/admin.head.php');
?>

<section>
    <div class="flex items-end justify-between gap-3">
        <div>
            <h3 class="text-sm font-bold text-gray-900">담당자·권한</h3>
            <p class="mt-1 text-2xs text-gray-400">
                업무 역할별로 상품·주문·배송·CS·정산 접근권한을 관리합니다.
            </p>
        </div>

        <button type="button" class="add-admin-modal-open shrink-0 rounded-lg bg-gray-900 px-3 py-2 text-2xs font-bold text-white">
            + 담당자 추가
        </button>
    </div>

    <div class="mt-3 overflow-hidden rounded-lg border border-gray-200 bg-white">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] table-fixed border-collapse text-left text-xs">
                <colgroup>
                    <col class="w-[22%]">
                    <col class="w-[17%]">
                    <col class="w-[27%]">
                    <col class="w-[12%]">
                    <col class="w-[14%]">
                    <col class="w-[8%]">
                </colgroup>

                <thead class="border-b border-gray-200 bg-gray-50 text-2xs text-gray-500 [&_th]:px-3 [&_th]:py-3 [&_th]:font-semibold">
                    <tr>
                        <th>담당자</th>
                        <th>역할</th>
                        <th>권한</th>
                        <th>상태</th>
                        <th>최근 접속</th>
                        <th>관리</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 text-gray-700 [&_td]:px-3 [&_td]:py-4 [&_td]:align-middle">
                    <tr>
                        <td>
                            <span class="block font-bold text-gray-900">김브랜드</span>
                            <span class="mt-1 block text-2xs text-gray-400">brand@greentable.co.kr</span>
                        </td>
                        <td>최고 관리자</td>
                        <td>
                            <div class="flex flex-wrap gap-1">
                                <span class="rounded-full bg-blue-50 px-2 py-1 text-2xs font-semibold text-blue-600">전체</span>
                            </div>
                        </td>
                        <td>
                            <span class="inline-flex rounded-full bg-green-50 px-2 py-1 text-2xs font-semibold text-green-600">
                                사용중
                            </span>
                        </td>
                        <td class="text-gray-500">2026-08-11 10:32</td>
                        <td>
                            <button type="button" data-staff="ST-1" class="whitespace-nowrap rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                권한 설정
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span class="block font-bold text-gray-900">이상품</span>
                            <span class="mt-1 block text-2xs text-gray-400">product@greentable.co.kr</span>
                        </td>
                        <td>상품 운영자</td>
                        <td>
                            <div class="flex flex-wrap gap-1">
                                <span class="rounded-full bg-blue-50 px-2 py-1 text-2xs font-semibold text-blue-600">상품</span>
                                <span class="rounded-full bg-blue-50 px-2 py-1 text-2xs font-semibold text-blue-600">재고</span>
                                <span class="rounded-full bg-blue-50 px-2 py-1 text-2xs font-semibold text-blue-600">프로모션</span>
                            </div>
                        </td>
                        <td>
                            <span class="inline-flex rounded-full bg-green-50 px-2 py-1 text-2xs font-semibold text-green-600">
                                사용중
                            </span>
                        </td>
                        <td class="text-gray-500">2026-08-11 09:18</td>
                        <td>
                            <button type="button" data-staff="ST-2" class="whitespace-nowrap rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                권한 설정
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span class="block font-bold text-gray-900">박물류</span>
                            <span class="mt-1 block text-2xs text-gray-400">logistics@greentable.co.kr</span>
                        </td>
                        <td>주문·배송 운영자</td>
                        <td>
                            <div class="flex flex-wrap gap-1">
                                <span class="rounded-full bg-blue-50 px-2 py-1 text-2xs font-semibold text-blue-600">주문</span>
                                <span class="rounded-full bg-blue-50 px-2 py-1 text-2xs font-semibold text-blue-600">배송</span>
                                <span class="rounded-full bg-blue-50 px-2 py-1 text-2xs font-semibold text-blue-600">클레임</span>
                            </div>
                        </td>
                        <td>
                            <span class="inline-flex rounded-full bg-green-50 px-2 py-1 text-2xs font-semibold text-green-600">
                                사용중
                            </span>
                        </td>
                        <td class="text-gray-500">2026-08-12 18:02</td>
                        <td>
                            <button type="button" data-staff="ST-3" class="whitespace-nowrap rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                권한 설정
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span class="block font-bold text-gray-900">최CS</span>
                            <span class="mt-1 block text-2xs text-gray-400">cs@greentable.co.kr</span>
                        </td>
                        <td>CS 담당자</td>
                        <td>
                            <div class="flex flex-wrap gap-1">
                                <span class="rounded-full bg-blue-50 px-2 py-1 text-2xs font-semibold text-blue-600">문의</span>
                                <span class="rounded-full bg-blue-50 px-2 py-1 text-2xs font-semibold text-blue-600">리뷰</span>
                                <span class="rounded-full bg-blue-50 px-2 py-1 text-2xs font-semibold text-blue-600">클레임</span>
                            </div>
                        </td>
                        <td>
                            <span class="inline-flex rounded-full bg-amber-50 px-2 py-1 text-2xs font-semibold text-amber-600">
                                초대대기
                            </span>
                        </td>
                        <td class="text-gray-400">-</td>
                        <td>
                            <button type="button" data-staff="ST-4" class="whitespace-nowrap rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                권한 설정
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 rounded-lg bg-blue-50 p-4 text-2xs text-blue-800">
        <span>최고 관리자는 전체 권한을 가지며, 다른 담당자의 권한을 변경할 수 있습니다. 주요 권한 변경은 활동 로그에 기록됩니다.</span>
    </div>
</section>

<!-- 관리자 추가 모달 -->
<div id="add-admin-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="add-admin-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="add-admin-modal-container" role="dialog" aria-modal="true" aria-labelledby="add-admin-modal-title"
        class="relative z-10 flex max-h-[90vh] w-full max-w-120 flex-col overflow-hidden rounded-lg bg-white">

        <div id="add-admin-modal-header" class="flex shrink-0 items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="add-admin-modal-title" class="text-sm font-bold text-gray-900">
                관리자 추가
            </h3>

            <button type="button" id="add-admin-modal-close" aria-label="플랫폼 관리자 추가 모달 닫기"
                class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="add-admin-modal-body" class="min-h-0 flex-1 overflow-y-auto p-4">
            <form id="add-admin-form">
                <div>
                    <label for="add-admin-name" class="block text-2xs font-bold text-gray-900">
                        이름
                    </label>

                    <input type="text" id="add-admin-name" name="name"
                        class="mt-2 h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-2xs text-gray-900">
                </div>

                <div class="mt-4">
                    <label for="add-admin-email" class="block text-2xs font-bold text-gray-900">
                        이메일
                    </label>

                    <input type="email" id="add-admin-email" name="email"
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

        <div id="add-admin-modal-footer" class="flex shrink-0 justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="add-admin-modal-cancel"
                class="rounded-lg border border-gray-300 bg-white px-4 py-3 font-bold text-gray-900">
                취소
            </button>

            <button type="submit" form="add-admin-form"
                class="rounded-lg border border-transparent bg-amber-300 px-4 py-3 font-bold text-gray-900">
                추가
            </button>
        </div>
    </div>
</div>

<script>
    $('.add-admin-modal-open').on('click', function() {
        $('#add-admin-modal').prop('hidden', false);
    });

    $('#add-admin-modal-cancel, #add-admin-modal-close, #add-admin-modal-backdrop').on('click', function() {
        $('#add-admin-modal').prop('hidden', true);
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

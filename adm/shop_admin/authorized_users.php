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

        <button type="button" class="shrink-0 rounded-lg bg-gray-900 px-3 py-2 text-2xs font-bold text-white">
            + 담당자 초대
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

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

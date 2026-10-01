<?php
$sub_menu = '960500';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '보류·에스크로';
require_once '../../admin.head.php';
?>

<section>
    <header>
        <p class="text-gray-600 font-normal">금액 보류와 사업자 심사 중 잠정 토핑을 구분합니다.</p>
    </header>

    <div class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <div class="overflow-x-auto">
            <table class="border-collapse min-w-240 w-full table-fixed text-left">
                <caption class="sr-only">정산 검토 대상 목록</caption>

                <colgroup>
                    <col class="w-[10%]">
                    <col class="w-[16%]">
                    <col class="w-[10%]">
                    <col class="w-[24%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                    <col class="w-[10%]">
                </colgroup>

                <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">번호</th>
                        <th scope="col">대상</th>
                        <th scope="col">유형</th>
                        <th scope="col">사유</th>
                        <th scope="col">금액·토핑</th>
                        <th scope="col">시작일</th>
                        <th scope="col">상태</th>
                        <th scope="col">상세</th>
                    </tr>
                </thead>

                <tbody class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:px-4 [&_td]:py-3">
                    <tr>
                        <td>
                            <span class="font-bold">ESC-0112</span>
                        </td>
                        <td>주문 20260810009871</td>
                        <td>금액</td>
                        <td>이상 거래 검토</td>
                        <td>184,000</td>
                        <td>2026.08.10</td>
                        <td>
                            <span class="rounded-full bg-red-100 text-2xs font-bold text-red-600 px-2 py-1">
                                <span aria-hidden="true">●</span>
                                보류
                            </span>
                        </td>
                        <td>
                            <button type="button" class="rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="font-bold">ESC-0108</span>
                        </td>
                        <td>DN-00203</td>
                        <td>잠정 토핑</td>
                        <td>최초 사업자 심사</td>
                        <td>412,000</td>
                        <td>2026.08.01</td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">
                                <span aria-hidden="true">●</span>
                                검토 중
                            </span>
                        </td>
                        <td>
                            <button type="button" class="rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="font-bold">ESC-0107</span>
                        </td>
                        <td>DN-00861</td>
                        <td>잠정 토핑</td>
                        <td>기한 내 제출 후 보완 심사</td>
                        <td>376,000</td>
                        <td>2026.07.26</td>
                        <td>
                            <span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">
                                <span aria-hidden="true">●</span>
                                검토 중
                            </span>
                        </td>
                        <td>
                            <button type="button" class="rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="font-bold">ESC-0104</span>
                        </td>
                        <td>분쟁 DSP-0182</td>
                        <td>금액</td>
                        <td>반품비 분쟁</td>
                        <td>36,000</td>
                        <td>2026.08.09</td>
                        <td>
                            <span class="rounded-full bg-red-100 text-2xs font-bold text-red-600 px-2 py-1">
                                <span aria-hidden="true">●</span>
                                보류
                            </span>
                        </td>
                        <td>
                            <button type="button" class="rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php
require_once '../../admin.tail.php';

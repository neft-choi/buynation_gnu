<?php
$sub_menu = '970400';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '관리자 처리 로그';
require_once '../../admin.head.php';
?>

<section>
    <header class="flex items-center justify-between">
        <p class="text-gray-400 font-normal">관리자 처리와 상태 연동 이력을 확인합니다.</p>

        <button type="button" class="w-fit border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
            로그 내려받기
        </button>
    </header>

    <div class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <div class="overflow-x-auto">
            <table class="border-collapse min-w-240 w-full table-fixed text-left">
                <caption class="sr-only">정산 검토 대상 목록</caption>

                <colgroup>
                    <col class="w-[15%]">
                    <col class="w-[15%]">
                    <col class="w-[15%]">
                    <col class="w-[40%]">
                    <col class="w-[15%]">
                </colgroup>

                <tbody class="text-gray-900 text-2xs font-normal divide-y divide-gray-200 [&_td]:px-4 [&_td]:py-3">
                    <tr>
                        <td>2026.08.11 16:04:12</td>
                        <td>
                            <span class="font-bold">입점팀 박OO</span>
                        </td>
                        <td>
                            <span class="font-bold"><span aria-hidden="true" class="text-emerald-400 mr-2">●</span>상품 승인</span>
                        </td>
                        <td>PRD-RV-260806-003 변경 승인 · 판매 반영</td>
                        <td class="td_right">
                            <span class="text-gray-600">203.0.113.24</span>
                        </td>
                    </tr>
                    <tr>
                        <td>2026.08.11 14:10:32</td>
                        <td>
                            <span class="font-bold">커뮤니티 이OO</span>
                        </td>
                        <td>
                            <span class="font-bold"><span aria-hidden="true" class="text-emerald-400 mr-2">●</span>도티 심사</span>
                        </td>
                        <td>DTV-260808-009 계좌 서류 보완 요청</td>
                        <td class="td_right">
                            <span class="text-gray-600">203.0.113.51</span>
                        </td>
                    </tr>
                    <tr>
                        <td>2026.08.11 11:30:08</td>
                        <td>
                            <span class="font-bold">정산팀 최OO</span>
                        </td>
                        <td>
                            <span class="font-bold"><span aria-hidden="true" class="text-emerald-400 mr-2">●</span>정산 검토</span>
                        </td>
                        <td>SET-D-2607-041 지급 자격 확인</td>
                        <td class="td_right">
                            <span class="text-gray-600">203.0.113.77</span>
                        </td>
                    </tr>
                    <tr>
                        <td>2026.08.11 09:18:45</td>
                        <td>
                            <span class="font-bold">시스템</span>
                        </td>
                        <td>
                            <span class="font-bold"><span aria-hidden="true" class="text-emerald-400 mr-2">●</span>기한 처리</span>
                        </td>
                        <td>DN-00802 14일 미제출 · 비사업자 자동 전환</td>
                        <td class="td_right">
                            <span class="text-gray-600">system</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php
require_once '../../admin.tail.php';

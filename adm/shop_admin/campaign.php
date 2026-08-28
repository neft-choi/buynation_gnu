<?php
$sub_menu = '400320';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '핫딜·추가 토핑';
include_once(G5_ADMIN_PATH . '/admin.head.php');
?>

<section>
    <div class="flex items-center justify-between gap-3">
        <p class="mt-1 text-2xs text-gray-400">가격 프로모션과 추가 기여 상품을 DONUTS 정책에 맞게 분리해 관리합니다.</p>
        <button type="button" class="shrink-0 rounded-lg bg-gray-900 px-3 py-2 text-2xs font-bold text-white">
            + 캠페인 등록
        </button>
    </div>

    <div class="mt-3 rounded-lg bg-blue-50 p-3 text-2xs text-blue-800">
        <span class="font-semibold">구분: </span><span>핫딜은 판매가격을 일정 기간 낮추는 기능입니다. 추가토핑 상품 자체는 일반 쇼핑 목록에도 노출되지만, 혜택율·대상은 자격이 맞는 도티 추천 흐름에서만 노출됩니다.</span>
    </div>

    <div class="mt-3 overflow-hidden rounded-lg border border-gray-200 bg-white">
        <div class="overflow-x-auto">
            <table class="w-full min-w-280 table-fixed border-collapse text-left text-xs">
                <colgroup>
                    <col class="w-[16%]">
                    <col class="w-[18%]">
                    <col class="w-[7.5%]">
                    <col class="w-[15%]">
                    <col class="w-[15%]">
                    <col class="w-[10%]">
                    <col class="w-[7.5%]">
                    <col class="w-[11%]">
                </colgroup>

                <thead class="border-b border-gray-200 bg-gray-50 text-2xs text-gray-500 [&_th]:px-3 [&_th]:py-3 [&_th]:font-semibold">
                    <tr>
                        <th scope="col">캠페인</th>
                        <th scope="col">대상 상품</th>
                        <th scope="col">운영 값</th>
                        <th scope="col">추천 자격</th>
                        <th scope="col">기간</th>
                        <th scope="col">플랫폼 심사</th>
                        <th scope="col">운영 상태</th>
                        <th scope="col">관리</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 text-gray-700 [&_td]:px-3 [&_td]:py-4 [&_td]:align-middle">
                    <tr>
                        <td>
                            <span class="block font-bold text-gray-900">건강한 여름 추가 토핑</span>
                            <span class="mt-1 block text-2xs text-gray-400">CMP-2608-HEALTH · 추가 토핑</span>
                        </td>

                        <td>
                            <span>프리미엄 냉장 샐러드 6팩</span>
                        </td>

                        <td>
                            <span>기본 +4%</span>
                        </td>

                        <td>
                            <span>지정 도넛: 테니스 커뮤니티</span>
                        </td>

                        <td>
                            <span>2026-08-01 ~ 2026-08-31</span>
                        </td>

                        <td>
                            <span class="block w-fit text-2xs text-green-600 font-bold bg-green-100 rounded-lg px-2 py-1">승인</span>
                            <span class="mt-1 block text-2xs text-gray-300">CMP-RV-260801-01</span>
                        </td>

                        <td>
                            <span class="w-fit text-2xs text-green-600 font-bold bg-green-100 rounded-lg px-2 py-1">진행중</span>
                        </td>

                        <td>
                            <button type="button" class="whitespace-nowrap rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                수정
                            </button>
                            <button type="button" class="whitespace-nowrap rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                중지
                            </button>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <span class="block font-bold text-gray-900">단백질바 주말 핫딜</span>
                            <span class="mt-1 block text-2xs text-gray-400">CMP-25 · 핫딜</span>
                        </td>

                        <td>
                            <span>저당 단백질바 12개입</span>
                        </td>

                        <td>
                            <span>21,900원</span>
                        </td>

                        <td>
                            <span>전체</span>
                        </td>

                        <td>
                            <span>2026-08-08 ~ 2026-08-16</span>
                        </td>

                        <td>
                            <span class="block w-fit text-2xs text-green-600 font-bold bg-green-100 rounded-lg px-2 py-1">승인</span>
                            <span class="mt-1 block text-2xs text-gray-300">CMP-RV-260808-02</span>
                        </td>

                        <td>
                            <span class="w-fit text-2xs text-green-600 font-bold bg-green-100 rounded-lg px-2 py-1">진행중</span>
                        </td>

                        <td>
                            <button type="button" class="whitespace-nowrap rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                수정
                            </button>
                            <button type="button" class="whitespace-nowrap rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                중지
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

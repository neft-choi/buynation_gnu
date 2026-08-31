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
        <button type="button" id="campaign-modal-open" class="shrink-0 rounded-lg bg-gray-900 px-3 py-2 text-2xs font-bold text-white">
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

<div id="campaign-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4" hidden>
    <div id="campaign-modal-backdrop" class="absolute inset-0 bg-black/40"></div>
    <div id="campaign-modal-container" class="relative z-10 w-full max-w-180 bg-white rounded-lg">
        <div id="campaign-modal-header" class="flex items-center justify-between border-b border-gray-300 p-4">
            <h3 class="text-sm font-bold">캠페인 등록</h3>
            <button type="button" id="campaign-modal-close" class="flex items-center justify-center w-8 h-8 rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <form id="campaign-modal-form" class="grid grid-cols-1 pc:grid-cols-2 gap-4 p-4">
            <div class="col-span-full">
                <label for="campaign-name" class="mb-2 block text-xs font-bold">캠페인명</label>
                <input type="text" id="campaign-name" class="w-full border border-gray-300 rounded-lg text-xs px-3 py-2">
            </div>

            <div>
                <label for="campaign-type" class="mb-2 block text-xs font-bold">유형</label>
                <select id="campaign-type" class="w-full border border-gray-300 rounded-lg text-xs px-3 py-2">
                    <option value="">핫딜</option>
                    <option value="">추가토핑</option>
                </select>
            </div>

            <div>
                <label for="campaign-product" class="mb-2 block text-xs font-bold">대상 상품</label>
                <select id="campaign-product" class="w-full border border-gray-300 rounded-lg text-xs px-3 py-2">
                    <option value="">유기농 그래놀라 500g</option>
                    <option value="">저당 단백질바 12개입</option>
                    <option value="">프리미엄 냉장 샐러드 6팩</option>
                    <option value="">선물용 대형 패키지</option>
                </select>
            </div>

            <div>
                <label for="campaign-sale-price" class="mb-2 block text-xs font-bold">핫딜 판매가</label>
                <input type="number" id="campaign-sale-price" value="0" min="0" step="1" class="w-full border border-gray-300 rounded-lg text-xs px-3 py-2">
            </div>

            <div>
                <label for="campaign-contribution-rate" class="mb-2 block text-xs font-bold">추가 기여율(%)</label>
                <input type="number" id="campaign-contribution-rate" value="0" min="0" step="1" class="w-full border border-gray-300 rounded-lg text-xs px-3 py-2">
            </div>

            <div>
                <label for="campaign-start-date" class="mb-2 block text-xs font-bold">시작일</label>
                <input type="date" id="campaign-start-date" class="w-full border border-gray-300 rounded-lg text-xs px-3 py-2">
            </div>

            <div>
                <label for="campaign-end-date" class="mb-2 block text-xs font-bold">종료일</label>
                <input type="date" id="campaign-end-date" class="w-full border border-gray-300 rounded-lg text-xs px-3 py-2">
            </div>

            <div class="col-span-full">
                <label for="campaign-dotty-recommend" class="mb-2 block text-xs font-bold">도티 추천 자격</label>
                <select id="campaign-dotty-recommend" class="w-full border border-gray-300 rounded-lg text-xs px-3 py-2">
                    <option value="">전체</option>
                </select>
            </div>
        </form>

        <div id="campaign-modal-footer" class="flex items-center justify-end gap-2 border-t border-gray-300 p-4">
            <button type="button" id="campaign-modal-cancel" class="border border-gray-300 rounded-lg bg-white px-3 py-2 text-xs font-bold text-gray-900">
                닫기
            </button>

            <button type="button" id="campaign-modal-save" class="rounded-lg bg-amber-400 px-3 py-2 text-xs font-bold text-gray-900">
                저장
            </button>
        </div>
    </div>
</div>

<script>
    $(function() {
        const $campaign_modal = $("#campaign-modal");

        // 캠페인 등록 버튼 클릭 시 모달 열기
        $("#campaign-modal-open").on("click", function() {
            $campaign_modal.prop("hidden", false);
        });

        // 배경 클릭 시 모달 닫기
        $("#campaign-modal-backdrop").on("click", function() {
            $campaign_modal.prop("hidden", true);
        });

        // X 버튼 클릭 시 모달 닫기
        $("#campaign-modal-close").on("click", function() {
            $campaign_modal.prop("hidden", true);
        });

        // 닫기 버튼 클릭 시 모달 닫기
        $("#campaign-modal-cancel").on("click", function() {
            $campaign_modal.prop("hidden", true);
        });
    });
</script>
<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

<?php
$sub_menu = '710300';
include_once('./_common.php');

$g5['title'] = '상품 리스트';
require_once '../admin.head.php';
?>
<section>
    <h2>상품 리스트</h2>
    <div class="text-lg">안녕하세요 김우빈(임시) 도티님</div>

    <div class="mt-8 space-y-2">
        <div class="flex items-center gap-2">
            <label for="item_type" class="mr-12">타입(임시)</label>
            <select id="item_type" name="item_type" class="frm_input w-72">
                <option value="all">전체(기본값)</option>
                <option value="event">기획전(브랜드가 생성한 기획전)</option>
            </select>
        </div>

        <div class="flex items-center gap-2">
            <label for="item_search" class="mr-12">상품검색(임시)</label>

            <div class="relative">
                <button type="button" id="item_search"
                    class="w-72 rounded border border-gray-300 bg-white px-4 py-2 text-left" aria-expanded="false"
                    aria-controls="item_search_list" aria-haspopup="listbox">
                    문스앤 항균 세탁망 (기존 상품에서 선택)
                </button>
                <ul id="item_search_list"
                    class="hidden absolute left-0 top-full !mt-0.5 z-10 w-72 rounded border border-gray-300 bg-white">
                    <li>
                        <button type="button" class="block w-full text-left">
                            <span class="block font-medium bg-blue-200 px-4 py-2">문스앤 항균 세탁망</span>
                            <span class="block px-4 py-2 space-y-2">
                                <span class="block text-xs text-gray-500">브랜드 수수료율 10% (플랫폼 수수료 5% 포함)</span>
                                <span class="block text-xs text-gray-500">정책 기여금 50% 할인율 50%</span>
                            </span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="space-y-2 pt-2">
            <p>상품리스트(임시)</p>
            <div class="min-h-40 border border-gray-400 rounded">
                <ul class="!p-2">
                    <li class="flex items-center justify-between gap-2">
                        <p>문스앤 항균 세탁망</p>
                        <div class="inline-flex items-center gap-1">
                            <button type="button" class="btn_xs btn_04">보기</button>
                            <button type="button" aria-activedescendant=""class="btn_xs btn_01">삭제</button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</section>
<script>
    // 상품 검색 리스트 hidden 토글
    $(function () {
        const $itemSearchButton = $("#item_search");
        const $itemSearchList = $("#item_search_list");

        $itemSearchButton.on("click", function () {
            const isHidden = $itemSearchList.hasClass("hidden");

            if (isHidden) {
                $itemSearchList.removeClass("hidden");
                $(this).attr("aria-expanded", "true");
            } else {
                $itemSearchList.addClass("hidden");
                $(this).attr("aria-expanded", "false");
            }
        });
    });
</script>
<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

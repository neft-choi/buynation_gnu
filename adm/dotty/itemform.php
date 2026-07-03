<?php
$sub_menu = '710200';
include_once('./_common.php');

$g5['title'] = '상품 등록';
require_once '../admin.head.php';
?>
<section>
    <h2>상품 등록</h2>
    <div class="text-lg">안녕하세요 김우빈(임시) 도티님</div>

    <div class="mt-8 space-y-2">
        <div class="flex items-center justify-between gap-2">
            <label for="item_type">타입(임시)</label>
            <select id="item_type" name="item_type" class="frm_input w-72">
                <option value="all">전체(쇼핑몰 모든 상품)</option>
                <option value="event">기획전(브랜드가 생성한 기획전)</option>
            </select>
        </div>

        <!-- 상품 검색 입력 -->
        <div class="flex items-center justify-between gap-2">
            <label for="item_search_input">상품검색(임시)</label>
            <div>
                <div class="flex items-center gap-1 w-72">
                    <input type="text" id="item_search_input" name="item_search" class="frm_input w-full"
                        placeholder="상품명을 입력해주세요.">
                    <button type="button" id="item_search_button" class="btn btn_04 shrink-0"
                        aria-controls="item_search_list" aria-expanded="false">검색</button>
                </div>
            </div>
        </div>

        <!-- 상품 검색 결과 -->
        <ul id="item_search_list" class="hidden divide-y divide-gray-200 rounded border border-gray-300 bg-white">
            <li>
                <button type="button" class="group flex w-full flex-col items-center text-left">
                    <span class="w-full bg-white px-4 py-2 font-medium group-hover:bg-blue-200 group-focus:bg-blue-200">
                        문스앤 항균 세탁망
                    </span>

                    <span class="flex w-full items-center justify-start gap-4 px-4 py-2">
                        <img src="<?php echo G5_SHOP_URL; ?>/img/no_image.gif" alt="문스앤 항균 세탁망"
                            class="h-[50px] w-[50px] shrink-0 rounded border border-gray-200 object-cover">

                        <span class="block space-y-2">
                            <span class="block text-xs text-gray-500">
                                브랜드 수수료율 10% (플랫폼 수수료 5% 포함)
                            </span>
                            <span class="block text-xs text-gray-500">
                                정책 기여금 50% 할인율 50%
                            </span>
                        </span>
                    </span>
                </button>
            </li>

            <li>
                <button type="button" class="group flex w-full flex-col items-center text-left">
                    <span class="w-full bg-white px-4 py-2 font-medium group-hover:bg-blue-200 group-focus:bg-blue-200">
                        문스앤 항균 세탁망 2
                    </span>

                    <span class="flex w-full items-center justify-start gap-4 px-4 py-2">
                        <img src="<?php echo G5_SHOP_URL; ?>/img/no_image.gif" alt="문스앤 항균 세탁망 2"
                            class="h-[50px] w-[50px] shrink-0 rounded border border-gray-200 object-cover">

                        <span class="block space-y-2">
                            <span class="block text-xs text-gray-500">
                                브랜드 수수료율 10% (플랫폼 수수료 5% 포함)
                            </span>
                            <span class="block text-xs text-gray-500">
                                정책 기여금 50% 할인율 50%
                            </span>
                        </span>
                    </span>
                </button>
            </li>
        </ul>

        <div class="space-y-2 pt-2">
            <p>상품정보(임시)</p>
            <div class="min-h-40 border border-gray-400 rounded p-2">
                <p>상품명: 문스앤 항균 세탁망</p>
                <p>상품 가격: 10,000원 </p>
                <p>기여금: 250원</p>
                <p>할인금: 250원</p>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-2 mt-4">
        <button type="button" class="btn btn_04">상품등록</button>
        <a href="./item_list.php" class="btn btn_01">취소</a>
    </div>
</section>
<script>
    $(function () {
        // 상품 검색 버튼 클릭 시 검색 결과 보이기
        $("#item_search_button").on("click", function () {
            const $itemSearchList = $("#item_search_list");

            $itemSearchList.removeClass("hidden");
            $(this).attr("aria-expanded", "true");
        });
    });
</script>
<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

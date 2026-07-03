<?php
$sub_menu = '710600';
include_once('./_common.php');

$g5['title'] = '주문목록';
require_once '../admin.head.php';
?>
<section class="relative">
    <h2>주문목록</h2>
    <div class="text-lg">안녕하세요 김우빈(임시) 도티님</div>

    <div class="mt-8 space-y-2">
        <p>총 주문 수 10개(임시)</p>

        <div class="flex items-center gap-2">
            <label for="order_search_type" class="pr-12">타입(임시)</label>
            <select id="order_search_type" name="order_search_type" class="frm_input w-50">
                <option value="od_id">주문번호(기본값)</option>
            </select>
        </div>

        <div class="flex items-center gap-2">
            <input type="text" id="order_search_input" name="order_search"
                class="w-full border border-gray-300 rounded px-4 py-2" placeholder="주문 검색(임시)">
            <button type="button" class="btn btn_04 shrink-0">검색</button>
        </div>

        <div>
            <p>주문 리스트(임시)</p>
            <div class="tbl_head01 tbl_wrap !mt-2">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">브랜드</th>
                            <th scope="col">주문번호</th>
                            <th scope="col">상품</th>
                            <th scope="col">금액</th>
                            <th scope="col">상태</th>
                            <th scope="col">기여금</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>CJ</td>
                            <td>12345</td>
                            <td>세탁망</td>
                            <td>10000</td>
                            <td>입금완료</td>
                            <td>250원</td>
                        </tr>
                        <tr>
                            <td>CJ</td>
                            <td>12345</td>
                            <td>세탁망</td>
                            <td>10000</td>
                            <td>입금완료</td>
                            <td>250원</td>
                        </tr>
                        <tr>
                            <td>CJ</td>
                            <td>12345</td>
                            <td>세탁망</td>
                            <td>10000</td>
                            <td>입금완료</td>
                            <td>250원</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
    $(function () {
        // // 주문 정보 모달 보기
        // $(".order_modal_view").on("click", function () {
        //     const $orderInfoModalOverlay = $("#order_info_modal_overlay");

        //     $orderInfoModalOverlay.removeClass("hidden");
        // });

        // // 주문 정보 모달의 흰색 상자 바깥 클릭 시 닫기
        // $("#order_info_modal_overlay").on("click", function (event) {
        //     if (event.target !== event.currentTarget) {
        //         return;
        //     }

        //     $(this).addClass("hidden");
        // });
    });
</script>
<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
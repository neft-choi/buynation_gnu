<?php
$sub_menu = '710200';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "w");

$g5['title'] = '상품 등록';

$dotty_mb_id = $member['mb_id'];
$dotty_mb_id_sql = sql_real_escape_string($dotty_mb_id);

// 도티 설정값 조회
$dotty_setting = sql_fetch("
    SELECT contribution_rate, discount_rate, dotty_title
    FROM donuts_dotty_settings
    WHERE mb_id = '{$dotty_mb_id_sql}'
    LIMIT 1
");

$contribution_rate = isset($dotty_setting['contribution_rate']) ? (float)$dotty_setting['contribution_rate'] : 0;
$discount_rate = isset($dotty_setting['discount_rate']) ? (float)$dotty_setting['discount_rate'] : 0;

require_once '../admin.head.php';
?>
<section>
    <h2>상품 등록</h2>

    <div class="text-lg">
        안녕하세요 <?php echo get_text($member['mb_name'] ? $member['mb_name'] : $member['mb_id']); ?> 도티님
    </div>

    <form id="fdottyitem" method="post" action="./itemform_update.php" onsubmit="return fdottyitem_submit(this);">
        <input type="hidden" name="token" value="<?php echo get_admin_token(); ?>">
        <input type="hidden" name="it_id" id="selected_it_id" value="">

        <div class="mt-8 space-y-2">
            <div class="flex items-center justify-between gap-2">
                <label for="item_type">타입</label>
                <select id="item_type" name="item_type" class="frm_input w-72">
                    <option value="all">전체(쇼핑몰 모든 상품)</option>
                    <option value="event" disabled>기획전(추후 연동)</option>
                </select>
            </div>

            <div class="flex items-center justify-between gap-2">
                <label for="item_search_input">상품검색</label>
                <div>
                    <div class="flex items-center gap-1 w-72">
                        <input type="text"
                               id="item_search_input"
                               name="item_search"
                               class="frm_input w-full"
                               placeholder="상품명 또는 상품코드를 입력해주세요.">
                        <button type="button"
                                id="item_search_button"
                                class="btn btn_04 shrink-0"
                                aria-controls="item_search_list"
                                aria-expanded="false">검색</button>
                    </div>
                </div>
            </div>

            <ul id="item_search_list"
                class="hidden divide-y divide-gray-200 rounded border border-gray-300 bg-white">
            </ul>

            <div class="space-y-2 pt-2">
                <p>상품정보</p>
                <div id="selected_item_info" class="min-h-40 border border-gray-400 rounded p-2">
                    <p class="text-gray-400">검색 결과에서 등록할 상품을 선택해 주세요.</p>
                </div>
            </div>

            <div class="space-y-1 pt-2">
                <p>현재 도티 정책</p>
                <div class="border border-gray-300 rounded p-2">
                    <p>기여금율: <?php echo number_format($contribution_rate, 1); ?>%</p>
                    <p>할인율: <?php echo number_format($discount_rate, 1); ?>%</p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 mt-4">
            <button type="submit" class="btn btn_04">상품등록</button>
            <a href="./item_list.php" class="btn btn_01">취소</a>
        </div>
    </form>
</section>

<script>
$(function () {
    function searchItems() {
        const keyword = $.trim($("#item_search_input").val());

        if (!keyword) {
            alert("상품명 또는 상품코드를 입력해 주세요.");
            $("#item_search_input").focus();
            return;
        }

        $.ajax({
            url: "./ajax.item_search.php",
            type: "GET",
            dataType: "json",
            data: {
                keyword: keyword
            },
            success: function (res) {
                const $list = $("#item_search_list");
                $list.empty();

                if (!res.success) {
                    alert(res.message || "상품 검색 중 오류가 발생했습니다.");
                    return;
                }

                if (!res.items.length) {
                    $list.append(
                        $("<li>").addClass("px-4 py-4 text-gray-500").text("검색된 상품이 없습니다.")
                    );
                } else {
                    $.each(res.items, function (_, item) {
                        const $button = $("<button>", {
                            type: "button",
                            class: "dotty-item-select group flex w-full flex-col items-center text-left"
                        }).data("item", item);

                        $("<span>", {
                            class: "w-full bg-white px-4 py-2 font-medium group-hover:bg-blue-200 group-focus:bg-blue-200"
                        }).text(item.it_name + " (" + item.it_id + ")").appendTo($button);

                        const $detail = $("<span>", {
                            class: "flex w-full items-center justify-start gap-4 px-4 py-2"
                        });

                        $("<img>", {
                            src: item.image_url,
                            alt: item.it_name,
                            class: "h-[50px] w-[50px] shrink-0 rounded border border-gray-200 object-cover"
                        }).appendTo($detail);

                        const $meta = $("<span>", { class: "block space-y-2" });
                        $("<span>", { class: "block text-xs text-gray-500" })
                            .text("판매가 " + Number(item.it_price).toLocaleString() + "원")
                            .appendTo($meta);
                        $("<span>", { class: "block text-xs text-gray-500" })
                            .text("정책 기여금 " + item.contribution_rate + "% / 할인율 " + item.discount_rate + "%")
                            .appendTo($meta);

                        $detail.append($meta);
                        $button.append($detail);

                        $("<li>").append($button).appendTo($list);
                    });
                }

                $list.removeClass("hidden");
                $("#item_search_button").attr("aria-expanded", "true");
            },
            error: function () {
                alert("상품 검색 요청에 실패했습니다.");
            }
        });
    }

    $("#item_search_button").on("click", searchItems);

    $("#item_search_input").on("keydown", function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            searchItems();
        }
    });

    $("#item_search_list").on("click", ".dotty-item-select", function () {
        const item = $(this).data("item");

        $("#selected_it_id").val(item.it_id);

        const contributionAmount = Math.floor(Number(item.it_price) * Number(item.contribution_rate) / 100);
        const discountAmount = Math.floor(Number(item.it_price) * Number(item.discount_rate) / 100);
        const expectedPrice = Math.max(0, Number(item.it_price) - discountAmount);

        $("#selected_item_info").html(
            "<p><strong>상품코드:</strong> " + $("<div>").text(item.it_id).html() + "</p>" +
            "<p><strong>상품명:</strong> " + $("<div>").text(item.it_name).html() + "</p>" +
            "<p><strong>상품 가격:</strong> " + Number(item.it_price).toLocaleString() + "원</p>" +
            "<p><strong>기여금:</strong> " + contributionAmount.toLocaleString() + "원</p>" +
            "<p><strong>할인금:</strong> " + discountAmount.toLocaleString() + "원</p>" +
            "<p><strong>예상 노출가격:</strong> " + expectedPrice.toLocaleString() + "원</p>"
        );

        $("#item_search_list").addClass("hidden");
        $("#item_search_button").attr("aria-expanded", "false");
    });
});

function fdottyitem_submit(f)
{
    if (!f.it_id.value) {
        alert("등록할 상품을 선택해 주세요.");
        return false;
    }

    return confirm("선택한 상품을 내 도티 페이지에 등록하시겠습니까?");
}
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

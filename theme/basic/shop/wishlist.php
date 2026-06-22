<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

$g5['title'] = '위시리스트';
include_once(G5_SHOP_PATH . '/_head.php');

?>
<?php add_javascript('<script src="' . G5_THEME_JS_URL . '/theme.shop.list.js"></script>', 10); ?>

<!-- 모바일 헤더 -->
<div class="flex pc:hidden items-center justify-between h-[var(--mobile-header-height)] px-4">
    <button type="button" class="inline-flex items-center justify-center text-zinc-700" aria-label="뒤로가기" onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '<?php echo G5_URL ?>'; }">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left w-6 h-6">
            <path d="m15 18-6-6 6-6" />
        </svg>
    </button>
    <h1 class="text-lg font-semibold text-zinc-900 leading-0">위시리스트</h1>
    <div class="w-6 h-6" aria-hidden="true"></div>
</div>

<!-- 회원 요약 정보 (PC) -->
<?php include_once(G5_THEME_SHOP_PATH . '/_mypage_summary_pc.php'); ?>

<div class="block pc:flex gap-6 pc:px-5 pc:py-12">

    <!-- 마이페이지 메뉴 (PC) -->
    <?php
    include_once(G5_THEME_SHOP_PATH . '/_mypage_menu_pc.php');
    ?>

    <!-- 위시리스트 시작 { -->
    <div id="sod_ws" class="flex-1 min-w-0">

        <!-- PC 너비 타이틀 -->
        <div class="hidden pc:block px-4">
            <h2 class="text-2xl font-bold pb-4 border-b-2 border-gray-900">위시리스트</h2>
        </div>

        <?php
        $sql  = " select a.wi_id, a.wi_time, b.* from {$g5['g5_shop_wish_table']} a left join {$g5['g5_shop_item_table']} b on ( a.it_id = b.it_id ) ";
        $sql .= " where a.mb_id = '{$member['mb_id']}' order by a.wi_id desc ";
        $result = sql_query($sql);
        $wishlist_total_count = sql_num_rows($result);
        ?>

        <section class="mx-auto w-full max-w-full p-4">
            <div class="flex items-center justify-between">
                <span class="text-sm text-zinc-500 font-semibold text-zinc-900">총 <?php echo number_format($wishlist_total_count); ?>건</span>
                <div class="inline-flex items-center gap-2 text-sm text-zinc-500">
                    <button type="button" id="wishlist-check-all" class="cursor-pointer">전체선택</button>
                    <span>|</span>
                    <button type="button" id="wishlist-delete-selected" class="cursor-pointer">선택삭제</button>
                </div>
            </div>

            <form name="fwishlist" method="post" action="./cartupdate.php">
                <input type="hidden" name="act" value="multi">
                <input type="hidden" name="sw_direct" value="">
                <input type="hidden" name="prog" value="wish">

                <div class="list_02 pt-4">
                    <ul class="grid grid-cols-2 pc:grid-cols-4 gap-4">
                        <?php
                        // 위시리스트 목록 조회
                        $sql  = " select a.wi_id, a.wi_time, b.* ";
                        $sql .= " from {$g5['g5_shop_wish_table']} a ";
                        $sql .= " left join {$g5['g5_shop_item_table']} b on ( a.it_id = b.it_id ) ";
                        $sql .= " where a.mb_id = '{$member['mb_id']}' ";
                        $sql .= " order by a.wi_id desc ";
                        $result = sql_query($sql);

                        // 위시리스트 목록 출력 시작
                        for ($i = 0; $row = sql_fetch_array($result); $i++) {

                            // 현재 상품 옵션 여부 확인
                            $out_cd = '';
                            $sql = " select count(*) as cnt from {$g5['g5_shop_item_option_table']} where it_id = '{$row['it_id']}' and io_type = '0' ";
                            $tmp = sql_fetch($sql);
                            if (isset($tmp['cnt']) && $tmp['cnt'])
                                $out_cd = 'no';

                            // 현재 상품 출력용 값 준비
                            $it_price = get_price($row);
                            $it_cust_price = (int) $row['it_cust_price'];
                            $discount_percent = 0;

                            if ($it_cust_price > 0 && $it_cust_price > $it_price) {
                                $discount_percent = (int) round((($it_cust_price - $it_price) / $it_cust_price) * 100);
                            }

                            $star_avg = $row['it_use_avg'] ? number_format((float) $row['it_use_avg'], 1) : '0.0';
                            $use_cnt = isset($row['it_use_cnt']) ? (int) $row['it_use_cnt'] : 0;

                            if ($row['it_tel_inq']) $out_cd = 'tel_inq';

                            $image = get_it_image($row['it_id'], 220, 280);
                        ?>

                            <li>
                                <div class="wish_chk">
                                    <?php
                                    // 품절검사
                                    if (is_soldout($row['it_id'])) {
                                    ?>
                                        품절
                                    <?php } else { //품절이 아니면 체크할수 있도록한다 
                                    ?>
                                        <div class="wishlist-check-box relative h-6 w-6">
                                            <input
                                                type="checkbox"
                                                name="chk_it_id[<?php echo $i; ?>]"
                                                value="1"
                                                id="chk_it_id_<?php echo $i; ?>"
                                                onclick="out_cd_check(this, '<?php echo $out_cd; ?>');"
                                                class="wishlist-check-input peer h-full w-full cursor-pointer appearance-none rounded-full border border-gray-300 checked:border-gray-900 checked:bg-gray-900"
                                                data-wi-id="<?php echo $row['wi_id']; ?>">

                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                class="pointer-events-none absolute left-1/2 top-1/2 h-4 w-4 -translate-x-1/2 -translate-y-1/2 text-gray-300 peer-checked:text-white"
                                                aria-hidden="true">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg>

                                            <label for="chk_it_id_<?php echo $i; ?>" class="sound_only">
                                                <?php echo $row['it_name']; ?>
                                            </label>
                                        </div>
                                    <?php } ?>
                                    <input type="hidden" name="it_id[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">
                                    <input type="hidden" name="io_type[<?php echo $row['it_id']; ?>][0]" value="0">
                                    <input type="hidden" name="io_id[<?php echo $row['it_id']; ?>][0]" value="">
                                    <input type="hidden" name="io_value[<?php echo $row['it_id']; ?>][0]" value="<?php echo $row['it_name']; ?>">
                                    <input type="hidden" name="ct_qty[<?php echo $row['it_id']; ?>][0]" value="1">
                                </div>

                                <div class="sod_ws_img pt-3">
                                    <a href="<?php echo shop_item_url($row['it_id']); ?>"><?php echo $image; ?></a>
                                </div>

                                <div class="flex w-full gap-1 pt-2">
                                    <button type="button"
                                        class="wishlist-cart-btn flex items-center justify-center w-full border border-gray-300 rounded-lg bg-white px-4 py-2"
                                        data-index="<?php echo $i; ?>"
                                        data-out_cd="<?php echo $out_cd; ?>"
                                        aria-label="장바구니">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart-icon lucide-shopping-cart w-6 h-6">
                                            <circle cx="8" cy="21" r="1" />
                                            <circle cx="19" cy="21" r="1" />
                                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="wish_info pt-3">
                                    <a href="<?php echo shop_item_url($row['it_id']); ?>" class="block text-base font-medium text-zinc-900 line-clamp-2">
                                        <?php echo stripslashes($row['it_name']); ?>
                                    </a>

                                    <?php if ($it_cust_price > 0 && $it_cust_price > $it_price) { ?>
                                        <div class="mt-1 text-sm text-zinc-400 line-through">
                                            <?php echo display_price($it_cust_price, $row['it_tel_inq']); ?>
                                        </div>
                                    <?php } ?>

                                    <div class="mt-1 flex items-center gap-1 text-lg font-bold">
                                        <?php if ($discount_percent > 0) { ?>
                                            <span class="text-red-500"><?php echo $discount_percent; ?>%</span>
                                        <?php } ?>
                                        <span class="text-zinc-900"><?php echo display_price($it_price, $row['it_tel_inq']); ?></span>
                                    </div>

                                    <div class="mt-2 flex items-center gap-2 text-xs text-zinc-500">
                                        <span class="flex items-center gap-1 text-zinc-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 fill-current text-zinc-900" viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M12 2.25l2.97 6.02 6.64.97-4.8 4.68 1.13 6.61L12 17.4l-5.94 3.13 1.13-6.61-4.8-4.68 6.64-.97L12 2.25z" />
                                            </svg>
                                            <?php echo $star_avg; ?>
                                        </span>
                                        <span class="text-zinc-300">|</span>
                                        <span><?php echo number_format($use_cnt); ?>건</span>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>

                        <?php
                        // 위시리스트 목록 없을 때
                        if ($i == 0) { ?>
                            <li class="col-span-2 pc:col-span-4">
                                <div class="flex flex-col items-center justify-center gap-3 text-gray-400 min-h-[calc(100dvh-var(--mobile-header-height)-var(--bottom-nav-height))] pc:min-h-0 pc:h-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart w-12 h-12">
                                        <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                    </svg>
                                    <span>찜한 상품이 없습니다</span>
                                    <a href="<?php echo G5_SHOP_URL; ?>/" class="text-sm font-semibold px-4 py-2 mt-3 bg-gray-800 text-white rounded">쇼핑하러 가기</a>
                                </div>

                            </li>
                        <?php } ?>
                    </ul>
                </div>

                <div id="sod_ws_act" class="!hidden">
                    <button type="submit" class="btn01" onclick="return fwishlist_check(document.fwishlist,'');">장바구니 담기</button>
                    <button type="submit" class="btn02" onclick="return fwishlist_check(document.fwishlist,'direct_buy');">주문하기</button>
                </div>
            </form>

            <?php if ($i == 0) { ?>
                <section id="wishlist_recommend">
                    <div class="py-5 flex items-center">
                        <h3 class="text-xl font-semibold">이런 상품은 어때요?</h3>
                    </div>

                    <?php
                    $list = new item_list();
                    $list->set_type(2);
                    $list->set_list_skin(G5_SHOP_SKIN_PATH . '/main.10.skin.php');
                    $list->set_view('it_id', false);
                    $list->set_view('it_name', true);
                    $list->set_view('it_basic', true);
                    $list->set_view('it_cust_price', true);
                    $list->set_view('it_price', true);
                    $list->set_view('it_icon', true);
                    $list->set_view('sns', true);
                    $list->set_view('star', true);
                    echo $list->run();
                    ?>
                </section>
            <?php } ?>
        </section>
    </div>
</div>

<script>
    function out_cd_check(fld, out_cd) {
        if (out_cd == 'no') {
            alert("옵션이 있는 상품입니다.\n\n상품을 클릭하여 상품페이지에서 옵션을 선택한 후 주문하십시오.");
            fld.checked = false;
            return;
        }

        if (out_cd == 'tel_inq') {
            alert("이 상품은 전화로 문의해 주십시오.\n\n장바구니에 담아 구입하실 수 없습니다.");
            fld.checked = false;
            return;
        }
    }

    function fwishlist_check(f, act) {
        var k = 0;
        var length = f.elements.length;

        for (i = 0; i < length; i++) {
            if (f.elements[i].checked) {
                k++;
            }
        }

        if (k == 0) {
            alert("상품을 하나 이상 체크 하십시오");
            return false;
        }

        if (act == "direct_buy") {
            f.sw_direct.value = 1;
        } else {
            f.sw_direct.value = 0;
        }

        return true;
    }

    // 위시리스트 체크박스 전체 선택 토글
    // 부분 체크 감지해서 중간에 클릭해도 전체 선택 가능
    $(function() {
        $("#wishlist-check-all").on("click", function() {
            const $checkAllButton = $(this);
            const $wishlistChecks = $("form[name=\"fwishlist\"] .wishlist-check-input");
            const hasUnchecked = $wishlistChecks.filter(":not(:checked)").length > 0;

            $wishlistChecks.prop("checked", hasUnchecked);
            $checkAllButton.text(hasUnchecked ? "전체해제" : "전체선택");
        });

        $("form[name=\"fwishlist\"]").on("change", ".wishlist-check-input", function() {
            const $wishlistChecks = $("form[name=\"fwishlist\"] .wishlist-check-input");
            const isAllChecked = $wishlistChecks.length > 0 && $wishlistChecks.filter(":checked").length === $wishlistChecks.length;

            $("#wishlist-check-all").text(isAllChecked ? "전체해제" : "전체선택");
        });
    });

    // 위시리스트 선택 삭제
    $(function() {
        $("#wishlist-delete-selected").on("click", function() {
            const $checkedItems = $("form[name=\"fwishlist\"] .wishlist-check-input:checked");

            if ($checkedItems.length === 0) {
                alert("삭제할 상품을 선택해 주세요.");
                return;
            }

            if (!confirm("선택한 상품을 위시리스트에서 삭제하시겠습니까?")) {
                return;
            }

            const wiIds = [];

            $checkedItems.each(function() {
                wiIds.push($(this).data("wi-id"));
            });

            const deleteRequests = wiIds.map(function(wiId) {
                return $.ajax({
                    url: "./wishupdate.php",
                    type: "GET",
                    data: {
                        w: "d",
                        wi_id: wiId
                    }
                });
            });

            $.when.apply($, deleteRequests)
                .done(function() {
                    alert("선택한 상품을 위시리스트에서 삭제했습니다.");
                    location.reload();
                })
                .fail(function() {
                    alert("삭제 중 오류가 발생했습니다.");
                });
        });
    });

    // 위시리스트 전용 단일 상품 장바구니 담기
    $(function() {
        $(document).on('click', '.wishlist-cart-btn', function() {
            var $btn = $(this);
            var realIndex = $btn.data('index');
            var outCd = $btn.data('out_cd');
            var $form = $('form[name="fwishlist"]');

            if (outCd === 'no') {
                alert("옵션이 있는 상품입니다.\n\n상품을 클릭하여 상품페이지에서 옵션을 선택한 후 주문하십시오.");
                return;
            }

            if (outCd === 'tel_inq') {
                alert("이 상품은 전화로 문의해 주십시오.\n\n장바구니에 담아 구입하실 수 없습니다.");
                return;
            }

            var itId = $form.find('[name="it_id[' + realIndex + ']"]').val();

            var payload = [{
                    name: 'act',
                    value: 'multi'
                },
                {
                    name: 'sw_direct',
                    value: ''
                },
                {
                    name: 'prog',
                    value: 'wish'
                },
                {
                    name: 'chk_it_id[0]',
                    value: '1'
                },
                {
                    name: 'it_id[0]',
                    value: itId
                }
            ];

            function pushField(sourceName, submitName) {
                var $field = $form.find('[name="' + sourceName + '"]');

                if ($field.length) {
                    payload.push({
                        name: submitName,
                        value: $field.val()
                    });
                }
            }

            pushField('io_type[' + itId + '][0]', 'io_type[' + itId + '][0]');
            pushField('io_id[' + itId + '][0]', 'io_id[' + itId + '][0]');
            pushField('io_value[' + itId + '][0]', 'io_value[' + itId + '][0]');
            pushField('ct_qty[' + itId + '][0]', 'ct_qty[' + itId + '][0]');

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: $.param(payload),
                success: function() {
                    alert('해당 상품을 장바구니에 담았습니다.');
                },
                error: function(request) {
                    alert('false ajax :' + request.responseText);
                }
            });
        });
    });

    // Owl Carousel 초기화
    $(function() {
        const $wishlistSlider = $("#wishlist_recommend .js-shop-slider");
        const isPc = matchesBreakpoint("min-width", "--breakpoint-pc");

        if (
            $wishlistSlider.length &&
            $.fn.owlCarousel &&
            !$wishlistSlider.hasClass("owl-loaded")
        ) {
            const sliderItems = parseFloat(
                isPc ?
                $wishlistSlider.attr("data-items-pc") :
                $wishlistSlider.attr("data-items-mobile")
            ) || 2.15;

            $wishlistSlider.owlCarousel({
                loop: true,
                nav: false,
                autoplay: false,
                autoplayHoverPause: false,
                margin: parseInt($wishlistSlider.attr("data-margin"), 10) || 12,
                stagePadding: 0,
                items: sliderItems
            });
        }
    });

    // 반응형 쇼핑몰 헤더 숨기기
    syncWithPcBreakpoint(function(isPc) {
        if (isPc) {
            $('#hd').css('display', '');
        } else {
            $('#hd').css('display', 'none');
        }
    });
</script>
<!-- } 위시리스트 끝 -->


<?php
include_once(G5_SHOP_PATH . '/_tail.php');

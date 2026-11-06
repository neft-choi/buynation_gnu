<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

$g5['title'] = '찜 목록';
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
    <h1 class="text-lg font-semibold text-zinc-900 leading-0">찜 목록</h1>
    <div class="w-6 h-6" aria-hidden="true"></div>
</div>

<!-- 회원 요약 정보 (PC) -->
<?php include_once(G5_THEME_SHOP_PATH . '/_mypage_summary_pc.php'); ?>

<div class="block pc:flex gap-6 pc:px-5 pc:py-12">

    <!-- 마이페이지 메뉴 (PC) -->
    <?php
    include_once(G5_THEME_SHOP_PATH . '/_mypage_menu_pc.php');
    ?>

    <!-- 찜 목록 시작 { -->
    <div id="sod_ws" class="flex-1 min-w-0">

        <!-- PC 너비 타이틀 -->
        <div class="hidden pc:block px-4">
            <h2 class="text-2xl font-bold pb-4 border-b-2 border-gray-900">찜 목록</h2>
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
                    <ul class="grid grid-cols-2 pc:grid-cols-3 gap-4">
                        <?php
                        // 찜 목록 목록 조회
                        $sql  = " select a.wi_id, a.wi_time, b.* ";
                        $sql .= " from {$g5['g5_shop_wish_table']} a ";
                        $sql .= " left join {$g5['g5_shop_item_table']} b on ( a.it_id = b.it_id ) ";
                        $sql .= " where a.mb_id = '{$member['mb_id']}' ";
                        $sql .= " order by a.wi_id desc ";
                        $result = sql_query($sql);

                        // 찜 목록 목록 출력 시작
                        for ($i = 0; $row = sql_fetch_array($result); $i++) {

                            // 현재 상품 옵션 여부 확인
                            $out_cd = '';
                            $sql = " select count(*) as cnt from {$g5['g5_shop_item_option_table']} where it_id = '{$row['it_id']}' and io_type = '0' ";
                            $tmp = sql_fetch($sql);
                            if (isset($tmp['cnt']) && $tmp['cnt'])
                                $out_cd = 'no';

                            // 상품 카드 출력과 찜 해제에 사용할 값
                            $item_link_href = shop_item_url($row['it_id']);
                            $item_name = htmlspecialchars(stripslashes($row['it_name']), ENT_QUOTES, 'UTF-8');
                            $wish_id = (int) $row['wi_id'];
                            $is_soldout = is_soldout($row['it_id']);

                            $it_price = (int) $row['it_price'];
                            $it_cust_price = (int) $row['it_cust_price'];
                            $discount_percent = 0;

                            if ($it_cust_price > 0 && $it_cust_price > $it_price) {
                                $discount_percent = (int) round((($it_cust_price - $it_price) / $it_cust_price) * 100);
                            }

                            $star_avg = $row['it_use_avg'] ? number_format((float) $row['it_use_avg'], 1) : '0.0';
                            $use_cnt = isset($row['it_use_cnt']) ? (int) $row['it_use_cnt'] : 0;

                            $item_type_labels = array();

                            if ($row['it_type1']) {
                                $item_type_labels[] = '히트 상품';
                            }
                            if ($row['it_type2']) {
                                $item_type_labels[] = '추천 상품';
                            }
                            if ($row['it_type3']) {
                                $item_type_labels[] = '신상품';
                            }
                            if ($row['it_type4']) {
                                $item_type_labels[] = '인기 상품';
                            }
                            if ($row['it_type5']) {
                                $item_type_labels[] = '할인 상품';
                            }

                            $item_type_text = $item_type_labels ? implode(' · ', $item_type_labels) : '일반 상품';

                            if ($row['it_tel_inq']) $out_cd = 'tel_inq';

                            $image = get_it_image($row['it_id'], 500, 500, false, '', $item_name);
                        ?>

                            <li class="min-w-0">
                                <article class="space-y-2" aria-labelledby="wishlist-item-title-<?php echo $wish_id; ?>">
                                    <div class="relative z-0 flex flex-col items-center justify-center bg-[#EEEEEE] rounded-lg overflow-hidden" style="aspect-ratio: 220 / 280;">
                                        <a href="<?php echo htmlspecialchars($item_link_href, ENT_QUOTES, 'UTF-8'); ?>" class="absolute inset-0 flex items-center justify-center [&>img]:w-full [&>img]:h-full [&>img]:object-cover">
                                            <?php echo $image; ?>
                                        </a>

                                        <?php if ($is_soldout) { ?>
                                            <span class="pointer-events-none absolute inset-0 z-10 flex items-center justify-center bg-black/50 text-lg font-bold text-white">
                                                SOLD OUT
                                            </span>
                                        <?php } ?>

                                        <div class="absolute top-2 left-2 z-20 h-6 w-6">
                                            <input
                                                type="checkbox"
                                                name="chk_it_id[<?php echo $i; ?>]"
                                                value="1"
                                                id="chk_it_id_<?php echo $i; ?>"
                                                class="wishlist-check-input peer h-full w-full cursor-pointer appearance-none rounded-full border border-gray-300 bg-white checked:border-gray-900 checked:bg-gray-900"
                                                data-wi-id="<?php echo $wish_id; ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute left-1/2 top-1/2 h-4 w-4 -translate-x-1/2 -translate-y-1/2 text-gray-300 peer-checked:text-white" aria-hidden="true">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg>
                                            <label for="chk_it_id_<?php echo $i; ?>" class="sound_only"><?php echo $item_name; ?> 선택</label>
                                        </div>

                                        <button
                                            type="button"
                                            class="wishlist-remove-btn absolute top-2 right-2 z-20 flex items-center justify-center w-8 h-8 border border-gray-300 rounded-full bg-white/90 shadow-sm cursor-pointer"
                                            data-wi-id="<?php echo $wish_id; ?>"
                                            aria-label="<?php echo $item_name; ?> 찜 해제">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart w-5 h-5 fill-red-500 stroke-red-500" aria-hidden="true">
                                                <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                            </svg>
                                        </button>

                                        <?php if ($row['it_type1']) { ?>
                                            <p class="pointer-events-none absolute inset-x-0 bottom-0 z-10 bg-amber-100/60 py-2 text-center text-xs font-semibold text-amber-700">
                                                마감까지 <span class="ml-1">01:23:45</span>
                                            </p>
                                        <?php } ?>
                                    </div>

                                    <div class="space-y-1">
                                        <h3 id="wishlist-item-title-<?php echo $wish_id; ?>" class="text-base font-semibold line-clamp-2">
                                            <a href="<?php echo htmlspecialchars($item_link_href, ENT_QUOTES, 'UTF-8'); ?>">
                                                <?php echo $item_name; ?>
                                            </a>
                                        </h3>

                                        <div class="flex flex-col gap-1">
                                            <?php if ($it_cust_price > 0 && $it_cust_price > $it_price) { ?>
                                                <span class="text-sm text-gray-400 line-through">
                                                    <?php echo display_price($it_cust_price, $row['it_tel_inq']); ?>
                                                </span>
                                            <?php } ?>

                                            <div class="flex gap-1 items-center">
                                                <?php if ($discount_percent > 0) { ?>
                                                    <span class="text-lg text-red-500 font-bold"><?php echo $discount_percent; ?>%</span>
                                                <?php } ?>
                                                <span class="text-lg font-bold"><?php echo display_price($it_price, $row['it_tel_inq']); ?></span>
                                            </div>

                                            <p class="text-sm font-semibold text-orange-500">예상 기여금 1,000원</p>

                                            <div class="flex gap-1 text-gray-900 text-sm">
                                                <span class="flex gap-1 items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star-icon lucide-star" aria-hidden="true">
                                                        <path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z" />
                                                    </svg>
                                                    <span class="sound_only">평점 </span><?php echo $star_avg; ?>
                                                </span>
                                                <span class="text-gray-300" aria-hidden="true">|</span>
                                                <span class="text-gray-400"><span class="sound_only">리뷰 </span><?php echo $use_cnt; ?>건</span>
                                            </div>
                                        </div>

                                        <div>
                                            <span class="inline-flex rounded-md px-2 py-1 text-xs font-semibold <?php echo $item_type_labels ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-500'; ?>">
                                                <?php echo $item_type_text; ?>
                                            </span>
                                        </div>
                                    </div>
                                </article>
                            </li>
                        <?php } ?>

                        <?php
                        // 찜 목록 목록 없을 때
                        if ($i == 0) { ?>
                            <li class="col-span-2 pc:col-span-3">
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

    // 찜 목록 체크박스 전체 선택 토글
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

    // 찜 목록 선택 삭제
    $(function() {
        $("#wishlist-delete-selected").on("click", function() {
            const $checkedItems = $("form[name=\"fwishlist\"] .wishlist-check-input:checked");

            if ($checkedItems.length === 0) {
                alert("삭제할 상품을 선택해 주세요.");
                return;
            }

            if (!confirm("선택한 상품을 찜 목록에서 삭제하시겠습니까?")) {
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
                    alert("선택한 상품을 찜 목록에서 삭제했습니다.");
                    location.reload();
                })
                .fail(function() {
                    alert("삭제 중 오류가 발생했습니다.");
                });
        });
    });

    // 찜 목록에서 개별 상품 찜 해제
    $(function() {
        $('form[name="fwishlist"]').on('click', '.wishlist-remove-btn', function() {
            const $button = $(this);
            const wishId = $button.data('wi-id');

            if (!confirm('이 상품을 찜 목록에서 삭제하시겠습니까?')) {
                return;
            }

            $button.prop('disabled', true);
            window.location.href = './wishupdate.php?w=d&wi_id=' + encodeURIComponent(wishId);
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
<!-- } 찜 목록 끝 -->


<?php
include_once(G5_SHOP_PATH . '/_tail.php');

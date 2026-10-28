<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가

function get_mshop_category($ca_id, $len)
{
    global $g5;

    $sql = " select ca_id, ca_name from {$g5['g5_shop_category_table']}
                where ca_use = '1' ";
    if ($ca_id)
        $sql .= " and ca_id like '$ca_id%' ";
    $sql .= " and length(ca_id) = '$len' order by ca_order, ca_id ";

    return $sql;
}

$mshop_categories = get_shop_category_array(true);
?>
<div id="category_clip" class="fixed inset-x-0 top-0 z-50 h-dvh overflow-hidden pointer-events-none">
    <div id="category" class="absolute inset-0 overflow-hidden bg-white pointer-events-auto -translate-x-full transition-transform duration-250 ease-[ease] [&.is-open]:translate-x-0">
        <div class="cate-top">
            <header class="grid h-14 grid-cols-[36px_1fr_36px] items-center border-b border-gray-200 px-2">
                <button type="button" class="close_btn inline-flex h-9 w-9 items-center justify-center text-gray-900"
                    aria-label="카테고리 화면 닫기">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                </button>
                <h2 class="text-center text-base font-semibold text-gray-900">카테고리</h2>
                <a href="<?php echo G5_SHOP_URL; ?>/cart.php"
                    class="relative inline-flex h-9 w-9 items-center justify-center text-gray-700" aria-label="장바구니">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-shopping-cart-icon lucide-shopping-cart" aria-hidden="true">
                        <circle cx="8" cy="21" r="1" />
                        <circle cx="19" cy="21" r="1" />
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                    </svg>
                    <span
                        class="absolute -top-1 -right-1 inline-flex min-w-4 h-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-semibold leading-none text-white">
                        <?php echo get_boxcart_datas_count(); ?>
                    </span>
                </a>
            </header>
            <div class="px-5 py-6">
                <h3 class="text-2xl font-bold text-gray-900">카테고리</h3>
                <p class="mt-2 text-sm text-gray-500">원하는 상품을 찾아보세요.</p>
                <p class="mt-4 h-5 truncate text-sm text-orange-500" aria-live="polite">
                    <span class="cate-path-primary"></span><span class="cate-path-secondary"></span>
                </p>
            </div>
        </div>
        <?php if (!empty($mshop_categories)) { ?>
            <div class="cate-shell grid grid-cols-[1fr_2fr] grid-rows-1">
                <div>
                    <ul class="cate-lv1 h-full overflow-y-auto bg-gray-100 divide-y divide-gray-200">
                        <?php
                        $cate_idx = 0;
                        foreach ($mshop_categories as $cate1) {
                            if (empty($cate1) || empty($cate1['text']))
                                continue;
                            $mshop_ca_row1 = $cate1['text'];
                            $panel_id = 'cate-panel-' . $cate_idx;
                            $is_active = ($cate_idx === 0) ? ' is-active' : '';
                        ?>
                            <li class="cate1-btn flex items-stretch text-sm text-black [&.is-active]:font-semibold [&.is-active]:bg-amber-100 [&.is-active>*]:text-amber-700<?php echo $is_active; ?>">
                                <a href="<?php echo $mshop_ca_row1['url']; ?>"
                                    class="min-w-0 flex-1 px-2 py-4 text-left">
                                    <?php echo get_text($mshop_ca_row1['ca_name']); ?>
                                </a>
                                <?php if (count($cate1) > 1) { ?>
                                    <button type="button" class="cate1-toggle flex w-8 shrink-0 items-center justify-center"
                                        data-target="<?php echo $panel_id; ?>"
                                        aria-controls="<?php echo $panel_id; ?>"
                                        aria-expanded="false"
                                        aria-label="<?php echo get_text($mshop_ca_row1['ca_name']); ?> 하위 분류 펼치기">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right preview-icon w-4 h-4">
                                            <path d="m9 18 6-6-6-6" />
                                        </svg>
                                    </button>
                                <?php } ?>
                            </li>
                        <?php
                            $cate_idx++;
                        }
                        ?>
                    </ul>
                </div>

                <div class="cate-lv2-wrap col-start-2 row-start-1 overflow-y-auto">
                    <?php
                    $cate_idx = 0;
                    foreach ($mshop_categories as $cate1) {
                        if (empty($cate1) || empty($cate1['text']))
                            continue;
                        $mshop_ca_row1 = $cate1['text'];
                        $panel_id = 'cate-panel-' . $cate_idx;
                        $is_active = ($cate_idx === 0) ? ' is-active' : '';
                    ?>
                        <div id="<?php echo $panel_id; ?>" class="cate2-panel hidden [&.is-active]:block<?php echo $is_active; ?>">
                            <div class="cate-lv2-list">
                                <?php
                                foreach ($cate1 as $key => $cate2) {
                                    if (empty($cate2) || $key === 'text')
                                        continue;
                                    $mshop_ca_row2 = $cate2['text'];
                                ?>
                                    <div class="cate2-item flex items-stretch [&.is-active>*]:text-[var(--color-primary)]">
                                        <a href="<?php echo $mshop_ca_row2['url']; ?>"
                                            class="min-w-0 flex-1 px-2 py-4 text-left text-sm text-black">
                                            <?php echo get_text($mshop_ca_row2['ca_name']); ?>
                                        </a>
                                        <?php if (count($cate2) > 1) { ?>
                                            <button type="button" class="cate2-toggle flex w-8 shrink-0 items-center justify-center"
                                                data-target="cate-third-<?php echo get_text($mshop_ca_row2['ca_id']); ?>"
                                                aria-controls="cate-third-<?php echo get_text($mshop_ca_row2['ca_id']); ?>"
                                                aria-expanded="false"
                                                aria-label="<?php echo get_text($mshop_ca_row2['ca_name']); ?> 하위 분류 펼치기">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right preview-icon w-4 h-4">
                                                    <path d="m9 18 6-6-6-6" />
                                                </svg>
                                            </button>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php
                        $cate_idx++;
                    }
                    ?>
                </div>
                <div class="cate-lv3-wrap hidden z-10 col-start-2 row-start-1 min-h-0 min-w-0 overflow-y-auto bg-white">
                    <div class="flex items-center justify-end border-b border-gray-200 p-2">
                        <button type="button" class="cate3-close inline-flex items-center justify-center text-gray-700"
                            aria-label="3차 분류 닫고 2차 분류로 돌아가기">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                class="h-5 w-5" aria-hidden="true">
                                <path d="m6 6 12 12M6 18 18 6" />
                            </svg>
                        </button>
                    </div>
                    <?php
                    foreach ($mshop_categories as $cate1) {
                        if (empty($cate1) || empty($cate1['text']))
                            continue;
                        foreach ($cate1 as $key => $cate2) {
                            if (empty($cate2) || $key === 'text' || empty($cate2['text']))
                                continue;
                            $mshop_ca_row2 = $cate2['text'];
                    ?>
                            <div id="cate-third-<?php echo get_text($mshop_ca_row2['ca_id']); ?>" class="cate3-panel hidden">
                                <ul>
                                    <?php
                                    foreach ($cate2 as $sub_key => $cate3) {
                                        if (empty($cate3) || $sub_key === 'text' || empty($cate3['text']))
                                            continue;
                                        $mshop_ca_row3 = $cate3['text'];
                                    ?>
                                        <li>
                                            <a href="<?php echo $mshop_ca_row3['url']; ?>" class="block px-2 py-4 text-sm text-black">
                                                <?php echo get_text($mshop_ca_row3['ca_name']); ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        <?php } else { ?>
            <p class="no-cate p-4 text-center text-sm text-gray-500">등록된 분류가 없습니다.</p>
        <?php } ?>
    </div>
</div>

<div id="pc-category-modal" class="fixed left-0 top-[142px] z-40 w-full" style="display:none;">
    <div class="pc-category-backdrop hidden fixed top-[142px] inset-0 bg-black/20"></div>

    <div class="pc-category-panel relative">


        <div class="pc-category-body mx-auto flex w-full max-w-[var(--breakpoint-pc)] items-start px-4">
            <div class="pc-category-primary w-50 shrink-0 border border-gray-200 bg-white p-3 shadow-md">
                <div class="flex flex-col gap-1">
                    <?php
                    $pc_primary_index = 0;
                    foreach ($mshop_categories as $cate1) {
                        if (empty($cate1) || empty($cate1['text']))
                            continue;
                        $mshop_ca_row1 = $cate1['text'];
                        $is_active = ($pc_primary_index === 0) ? ' text-[var(--color-primary)] font-semibold' : ' text-gray-700';
                    ?>
                        <a href="<?php echo $mshop_ca_row1['url']; ?>"
                            class="pc-category-primary-button flex w-full items-center gap-3 p-2 text-left text-base<?php echo $is_active; ?>"
                            data-target="pc-category-panel-<?php echo $pc_primary_index; ?>">
                            <span><?php echo get_text($mshop_ca_row1['ca_name']); ?></span>
                        </a>
                    <?php
                        $pc_primary_index++;
                    }
                    ?>
                </div>
            </div>

            <div class="pc-category-secondary hidden w-50 shrink-0 border border-l-0 border-gray-200 bg-white p-3 shadow-md">
                <?php
                $pc_panel_index = 0;
                foreach ($mshop_categories as $cate1) {
                    if (empty($cate1) || empty($cate1['text']))
                        continue;
                    $mshop_ca_row1 = $cate1['text'];
                    $panel_hidden = ($pc_panel_index === 0) ? '' : ' hidden';
                ?>
                    <div id="pc-category-panel-<?php echo $pc_panel_index; ?>"
                        class="pc-category-secondary-panel<?php echo $panel_hidden; ?>">
                        <div class="flex flex-col gap-1">
                            <?php
                            foreach ($cate1 as $key => $cate2) {
                                if (empty($cate2) || $key === 'text' || empty($cate2['text']))
                                    continue;
                                $mshop_ca_row2 = $cate2['text'];
                            ?>
                                <a href="<?php echo $mshop_ca_row2['url']; ?>"
                                    class="pc-category-secondary-link block p-2 text-base text-gray-900 hover:text-[var(--color-primary)]"
                                    data-tertiary-target="pc-category-tertiary-<?php echo get_text($mshop_ca_row2['ca_id']); ?>">
                                    <?php echo get_text($mshop_ca_row2['ca_name']); ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                <?php
                    $pc_panel_index++;
                }
                ?>
            </div>

            <div class="pc-category-tertiary hidden w-50 shrink-0 border border-l-0 border-gray-200 bg-white p-3 shadow-md">
                <?php
                foreach ($mshop_categories as $cate1) {
                    if (empty($cate1) || empty($cate1['text']))
                        continue;
                    foreach ($cate1 as $key => $cate2) {
                        if (empty($cate2) || $key === 'text' || empty($cate2['text']))
                            continue;
                        $mshop_ca_row2 = $cate2['text'];
                ?>
                        <div id="pc-category-tertiary-<?php echo get_text($mshop_ca_row2['ca_id']); ?>"
                            class="pc-category-tertiary-panel hidden">
                            <div class="flex flex-col gap-3">
                                <?php
                                foreach ($cate2 as $sub_key => $cate3) {
                                    if (empty($cate3) || $sub_key === 'text' || empty($cate3['text']))
                                        continue;
                                    $mshop_ca_row3 = $cate3['text'];
                                ?>
                                    <a href="<?php echo $mshop_ca_row3['url']; ?>" class="text-base text-gray-700 hover:text-[var(--color-primary)]">
                                        <?php echo get_text($mshop_ca_row3['ca_name']); ?>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div id="category_all_bg"></div>

<script>
    $(function() {
        var $category = $("#category");
        var $categoryBg = $("#category_all_bg");
        var closeTimer = null;

        function openCategory() {
            if (closeTimer) {
                clearTimeout(closeTimer);
                closeTimer = null;
            }

            $category.show();
            $categoryBg.show();

            $category.find(".cate1-btn, .cate2-panel").removeClass("is-active");
            $category.find(".cate1-toggle, .cate2-toggle").attr("aria-expanded", "false");
            $category.find(".cate2-item").removeClass("is-active font-semibold");
            $category.find(".cate-lv3-wrap, .cate3-panel").addClass("hidden");
            $category.find(".cate-path-primary, .cate-path-secondary").text("");
            const categoryTopHeight = $category.find(".cate-top").outerHeight() || 0;
            const bottomNavHeight = $("#shop-bottom-nav-wrap").outerHeight() || 0;
            $category.find(".cate-shell").css("height", `calc(100dvh - ${categoryTopHeight}px - ${bottomNavHeight}px)`);

            requestAnimationFrame(function() {
                $category.addClass("is-open");
            });
        }

        function closeCategory() {
            $category.removeClass("is-open");
            $categoryBg.hide();

            closeTimer = setTimeout(function() {
                $category.hide();
            }, 250);
        }

        $("#menu_open").on("click", function(e) {
            e.preventDefault();
            openCategory();
        });

        $("#category .close_btn, #category_all_bg").on("click", function(e) {
            e.preventDefault();
            closeCategory();
        });

        $("#category").on("click", ".cate1-toggle", function() {
            const target = $(this).data("target");
            if (!target) return;

            const categoryName = $(this).closest(".cate1-btn").children("a").text().trim();
            $("#category .cate-path-primary").text(categoryName);
            $("#category .cate-path-secondary").text("");

            $("#category .cate1-btn").removeClass("is-active");
            $(this).closest(".cate1-btn").addClass("is-active");
            $("#category .cate1-toggle").attr("aria-expanded", "false");
            $(this).attr("aria-expanded", "true");

            $("#category .cate2-panel").removeClass("is-active");
            $("#" + target).addClass("is-active");
            $("#category .cate2-item").removeClass("is-active font-semibold");
            $("#category .cate2-toggle").attr("aria-expanded", "false");
            $("#category .cate-lv3-wrap, #category .cate3-panel").addClass("hidden");
        });

        $("#category").on("click", ".cate2-toggle", function() {
            const target = $(this).data("target");
            if (!target) return;

            const categoryName = $(this).closest(".cate2-item").children("a").text().trim();
            $("#category .cate-path-secondary").text(" / " + categoryName);

            $("#category .cate2-item").removeClass("is-active font-semibold");
            $(this).closest(".cate2-item").addClass("is-active font-semibold");
            $("#category .cate2-toggle").attr("aria-expanded", "false");
            $(this).attr("aria-expanded", "true");

            $("#category .cate3-panel").addClass("hidden");
            $("#" + target).removeClass("hidden");
            $("#category .cate-lv3-wrap").removeClass("hidden");
        });

        $("#category").on("click", ".cate3-close", function() {
            $("#category .cate-lv3-wrap, #category .cate3-panel").addClass("hidden");
            $("#category .cate2-toggle").attr("aria-expanded", "false");
            $("#category .cate2-item").removeClass("is-active font-semibold");
            $("#category .cate-path-secondary").text("");
        });

        var $pcCategoryModal = $("#pc-category-modal");

        // PC 카테고리 버튼 호버
        const $pcCategoryButton = $("#category_open");
        const $pcCategoryHoverAreas = $pcCategoryButton.add(
            $pcCategoryModal.find(".pc-category-primary, .pc-category-secondary, .pc-category-tertiary")
        );
        let pcCategoryCloseTimer = null;

        function closePcCategory() {
            clearTimeout(pcCategoryCloseTimer);
            pcCategoryCloseTimer = null;
            $("#category_open_icon").removeClass("bg-[var(--color-primary)]");
            $pcCategoryModal.css("display", "none");
        }

        $pcCategoryHoverAreas.on("mouseenter", function() {
            clearTimeout(pcCategoryCloseTimer);
            pcCategoryCloseTimer = null;
        });

        // pc 카테고리 마우스 이벤트 발생 시
        // mouseenter는 마우스가 요소 바깥에서 안으로 들어왔을 때 발생 (mousehover라는 이벤트 이름은 없음)
        // jQuery에 .hover() 라는 메서드는 따로 존재함
        $pcCategoryButton.on("mouseenter", function() {
            if ($pcCategoryModal.is(":visible")) return;

            $pcCategoryModal.find(".pc-category-secondary, .pc-category-secondary-panel, .pc-category-tertiary, .pc-category-tertiary-panel").addClass("hidden");
            $pcCategoryModal.find(".pc-category-primary-button").removeClass("text-[var(--color-primary)] font-semibold").addClass("text-gray-700");
            $pcCategoryModal.find(".pc-category-secondary-link").removeClass("text-[var(--color-primary)]").addClass("text-gray-900");
            $pcCategoryModal.find(".pc-category-tertiary-panel a").removeClass("text-[var(--color-primary)]").addClass("text-gray-700");
            $("#category_open_icon").addClass("bg-[var(--color-primary)]");
            $pcCategoryModal.css({
                display: "block",
                // getBoundingClientRect()는 요소의 크기와 화면에서의 위치를 가져온다
                top: (this.getBoundingClientRect().bottom + 12) + "px"
            });
        });

        $pcCategoryHoverAreas.on("mouseleave", function() {
            clearTimeout(pcCategoryCloseTimer);
            pcCategoryCloseTimer = setTimeout(closePcCategory, 200);
        });
    });

    $("#pc-category-modal").on("mouseenter", ".pc-category-primary-button", function() {
        const $modal = $(this).closest("#pc-category-modal");
        const $secondary = $modal.find(".pc-category-secondary");
        const target = $(this).attr("data-target");

        $secondary.addClass("hidden");
        $secondary.find(".pc-category-secondary-panel").addClass("hidden");
        $modal.find(".pc-category-tertiary, .pc-category-tertiary-panel").addClass("hidden");
        $modal.find(".pc-category-secondary-link").removeClass("text-[var(--color-primary)]").addClass("text-gray-900");
        $modal.find(".pc-category-tertiary-panel a").removeClass("text-[var(--color-primary)]").addClass("text-gray-700");

        $modal.find(".pc-category-primary-button").removeClass("text-[var(--color-primary)] font-semibold").addClass("text-gray-700");
        $(this).removeClass("text-gray-700").addClass("text-[var(--color-primary)] font-semibold");

        if (!target) return;

        const $target = $secondary.find("#" + target);
        if (!$target.find(".pc-category-secondary-link").length) return;

        $target.removeClass("hidden");
        $secondary.removeClass("hidden");
    });

    $("#pc-category-modal").on("mouseenter", ".pc-category-secondary-link", function() {
        const $modal = $(this).closest("#pc-category-modal");
        const $tertiary = $modal.find(".pc-category-tertiary");
        const target = $(this).attr("data-tertiary-target");

        $tertiary.addClass("hidden");
        $tertiary.find(".pc-category-tertiary-panel").addClass("hidden");

        $modal.find(".pc-category-secondary-link").removeClass("text-[var(--color-primary)]").addClass("text-gray-900");
        $(this).removeClass("text-gray-900").addClass("text-[var(--color-primary)]");
        $tertiary.find("a").removeClass("text-[var(--color-primary)]").addClass("text-gray-700");

        if (!target) return;

        const $target = $tertiary.find("#" + target);
        if (!$target.find("a").length) return;

        $target.removeClass("hidden");
        $tertiary.removeClass("hidden");
    });

    $("#pc-category-modal").on("mouseenter", ".pc-category-tertiary-panel a", function() {
        const $modal = $(this).closest("#pc-category-modal");
        $modal.find(".pc-category-tertiary-panel a").removeClass("text-[var(--color-primary)]").addClass("text-gray-700");
        $(this).removeClass("text-gray-700").addClass("text-[var(--color-primary)]");
    });
</script>
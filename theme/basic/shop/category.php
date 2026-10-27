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
<style>
    /* 카테고리 클래핑 래퍼 */
    #category_clip {
        position: fixed;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        /* max-width: 375px; */
        height: 100dvh;
        overflow: hidden;
        pointer-events: none;
    }

    /* 카테고리 메뉴 */
    #category {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        max-width: none;
        height: 100dvh;
        overflow: hidden;
        background: #fff;
        pointer-events: auto;
        /* 카테고리 메뉴 초기 위치를 왼쪽으로 */
        transform: translateX(-100%);
        transition: transform 0.25s ease;
    }

    /* 카테고리 메뉴 실행 시 왼쪽에서 등장 */
    #category.is-open {
        transform: translateX(0);
    }

    /* 카테고리 1차 메뉴 2차 메뉴 Grid */
    #category .cate-shell {
        display: grid;
        grid-template-columns: 134px minmax(0, 1fr);
        height: calc(100dvh - 56px);
    }

    #category .cate-lv1 {
        height: 100%;
        overflow-y: auto;
        background: rgba(141, 141, 141, 0.08);
    }

    #category .cate1-btn {
        width: 100%;
        padding: 16px;
        text-align: left;
        font-size: 16px;
        color: #000;
    }

    #category .cate1-btn.is-active {
        background: #fff;
        font-weight: 600;
    }

    #category .cate-lv2-wrap {
        height: 100%;
        overflow-y: auto;
        background: #fff;
    }

    #category .cate2-panel {
        display: none;
    }

    #category .cate2-panel.is-active {
        display: block;
    }

    #category .cate2-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px;
        font-size: 16px;
        font-weight: 500;
        color: #F1B10F;
    }

    #category .cate-lv2-list a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px;
        font-size: 16px;
        color: #000;
    }
</style>
<div id="category_clip" class="z-50">
    <div id="category">
        <div class="flex h-14 items-center border-b border-gray-200 px-2">

            <form class="ml-1 mr-2 flex min-w-0 flex-1 items-center" action="<?php echo G5_SHOP_URL; ?>/search.php"
                method="get"
                onsubmit="if (this.q.value.length < 2) { alert('검색어는 두글자 이상 입력하십시오.'); this.q.focus(); return false; } return true;">
                <label for="category_search_input" class="sound_only">검색어</label>
                <div
                    class="flex h-9 w-full items-center gap-1 rounded-full border-2 border-[var(--color-primary)] bg-white pl-2 pr-1">
                    <input type="search" name="q" id="category_search_input"
                        value="<?php echo isset($q) ? stripslashes(get_text(get_search_string($q))) : ''; ?>"
                        class="w-full px-2 border-0 bg-transparent text-sm text-gray-900 outline-none" placeholder=""
                        required>
                    <button type="submit"
                        class="inline-flex h-7 w-7 items-center justify-center text-[var(--color-primary)]"
                        aria-label="검색">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"
                            aria-hidden="true">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                    </button>
                </div>
            </form>

            <div class="flex items-center gap-1">
                <button type="button" class="inline-flex h-9 w-9 items-center justify-center text-gray-700"
                    aria-label="알림">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-bell-icon lucide-bell">
                        <path d="M10.268 21a2 2 0 0 0 3.464 0" />
                        <path
                            d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />
                    </svg>
                </button>
                <a href="<?php echo G5_SHOP_URL; ?>/cart.php"
                    class="relative inline-flex h-9 w-9 items-center justify-center text-gray-700" aria-label="장바구니">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-shopping-cart-icon lucide-shopping-cart">
                        <circle cx="8" cy="21" r="1" />
                        <circle cx="19" cy="21" r="1" />
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                    </svg>
                    <span
                        class="absolute -top-1 -right-1 inline-flex min-w-4 h-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-semibold leading-none text-white">
                        <?php echo get_boxcart_datas_count(); ?>
                    </span>
                </a>
            </div>
        </div>
        <?php if (!empty($mshop_categories)) { ?>
            <div class="cate-shell">
                <div>
                    <ul class="cate-lv1">
                        <?php
                        $cate_idx = 0;
                        foreach ($mshop_categories as $cate1) {
                            if (empty($cate1) || empty($cate1['text']))
                                continue;
                            $mshop_ca_row1 = $cate1['text'];
                            $panel_id = 'cate-panel-' . $cate_idx;
                            $is_active = ($cate_idx === 0) ? ' is-active' : '';
                        ?>
                            <li>
                                <button type="button" class="cate1-btn<?php echo $is_active; ?>"
                                    data-target="<?php echo $panel_id; ?>">
                                    <?php echo get_text($mshop_ca_row1['ca_name']); ?>
                                </button>
                            </li>
                        <?php
                            $cate_idx++;
                        }
                        ?>
                    </ul>
                </div>

                <div class="cate-lv2-wrap">
                    <?php
                    $cate_idx = 0;
                    foreach ($mshop_categories as $cate1) {
                        if (empty($cate1) || empty($cate1['text']))
                            continue;
                        $mshop_ca_row1 = $cate1['text'];
                        $panel_id = 'cate-panel-' . $cate_idx;
                        $is_active = ($cate_idx === 0) ? ' is-active' : '';
                    ?>
                        <div id="<?php echo $panel_id; ?>" class="cate2-panel<?php echo $is_active; ?>">
                            <a href="<?php echo $mshop_ca_row1['url']; ?>" class="cate2-title">
                                <span><?php echo get_text($mshop_ca_row1['ca_name']); ?></span>
                                <span aria-hidden="true">&rsaquo;</span>
                            </a>
                            <div class="cate-lv2-list">
                                <?php
                                foreach ($cate1 as $key => $cate2) {
                                    if (empty($cate2) || $key === 'text')
                                        continue;
                                    $mshop_ca_row2 = $cate2['text'];
                                ?>
                                    <a href="<?php echo $mshop_ca_row2['url']; ?>">
                                        <span><?php echo get_text($mshop_ca_row2['ca_name']); ?></span>
                                        <span aria-hidden="true">&rsaquo;</span>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php
                        $cate_idx++;
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

            const bottomNavHeight = $("#shop-bottom-nav-wrap").outerHeight() || 0;
            $category.find(".cate-shell").css("height", `calc(100dvh - 56px - ${bottomNavHeight}px)`);

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

        $("#category").on("click", ".cate1-btn", function() {
            var target = $(this).data("target");
            if (!target) return;

            $("#category .cate1-btn").removeClass("is-active");
            $(this).addClass("is-active");

            $("#category .cate2-panel").removeClass("is-active");
            $("#" + target).addClass("is-active");
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
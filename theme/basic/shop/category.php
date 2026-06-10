<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

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
        z-index: 30;
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
<div id="category_clip">
    <div id="category">
        <div class="flex h-14 items-center border-b border-gray-200 px-2">

            <form
                class="ml-1 mr-2 flex min-w-0 flex-1 items-center"
                action="<?php echo G5_SHOP_URL; ?>/search.php"
                method="get"
                onsubmit="if (this.q.value.length < 2) { alert('검색어는 두글자 이상 입력하십시오.'); this.q.focus(); return false; } return true;">
                <label for="category_search_input" class="sound_only">검색어</label>
                <div class="flex h-9 w-full items-center gap-1 rounded-full border-2 border-[var(--color-primary)] bg-white pl-2 pr-1">
                    <input
                        type="search"
                        name="q"
                        id="category_search_input"
                        value="<?php echo isset($q) ? stripslashes(get_text(get_search_string($q))) : ''; ?>"
                        class="w-full px-2 border-0 bg-transparent text-sm text-gray-900 outline-none"
                        placeholder=""
                        required>
                    <button type="submit" class="inline-flex h-7 w-7 items-center justify-center text-[var(--color-primary)]" aria-label="검색">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                    </button>
                </div>
            </form>

            <div class="flex items-center gap-1">
                <button type="button" class="inline-flex h-9 w-9 items-center justify-center text-gray-700" aria-label="알림">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell-icon lucide-bell">
                        <path d="M10.268 21a2 2 0 0 0 3.464 0" />
                        <path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />
                    </svg>
                </button>
                <a href="<?php echo G5_SHOP_URL; ?>/cart.php" class="relative inline-flex h-9 w-9 items-center justify-center text-gray-700" aria-label="장바구니">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart-icon lucide-shopping-cart">
                        <circle cx="8" cy="21" r="1" />
                        <circle cx="19" cy="21" r="1" />
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                    </svg>
                    <span class="absolute -top-1 -right-1 inline-flex min-w-4 h-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-semibold leading-none text-white">
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
                            if (empty($cate1) || empty($cate1['text'])) continue;
                            $mshop_ca_row1 = $cate1['text'];
                            $panel_id = 'cate-panel-' . $cate_idx;
                            $is_active = ($cate_idx === 0) ? ' is-active' : '';
                        ?>
                            <li>
                                <button type="button" class="cate1-btn<?php echo $is_active; ?>" data-target="<?php echo $panel_id; ?>">
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
                        if (empty($cate1) || empty($cate1['text'])) continue;
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
                                    if (empty($cate2) || $key === 'text') continue;
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

<div id="pc-category-modal" class="fixed left-0 top-[132px] z-[60] w-full" style="display:none;">
    <div class="pc-category-backdrop fixed top-[132px] inset-0 bg-black/20"></div>

    <div class="pc-category-panel relative bg-white">


        <div class="pc-category-body mx-auto flex w-full max-w-[var(--breakpoint-pc)] gap-5 px-4 py-10">
            <div class="pc-category-primary w-[220px] shrink-0 border-r border-gray-200 pr-6">
                <div class="flex flex-col gap-1">
                    <?php
                    $pc_primary_index = 0;
                    foreach ($mshop_categories as $cate1) {
                        if (empty($cate1) || empty($cate1['text'])) continue;
                        $mshop_ca_row1 = $cate1['text'];
                        $is_active = ($pc_primary_index === 0) ? ' text-[var(--color-primary)] font-semibold' : ' text-gray-700';
                    ?>
                        <button
                            type="button"
                            class="pc-category-primary-button flex w-full items-center gap-3 p-2 text-left text-base<?php echo $is_active; ?>"
                            data-target="pc-category-panel-<?php echo $pc_primary_index; ?>">
                            <span><?php echo get_text($mshop_ca_row1['ca_name']); ?></span>
                        </button>
                    <?php
                        $pc_primary_index++;
                    }
                    ?>
                </div>
            </div>

            <div class="pc-category-secondary min-w-0 flex-1">
                <?php
                $pc_panel_index = 0;
                foreach ($mshop_categories as $cate1) {
                    if (empty($cate1) || empty($cate1['text'])) continue;
                    $mshop_ca_row1 = $cate1['text'];
                    $panel_hidden = ($pc_panel_index === 0) ? '' : ' hidden';
                ?>
                    <div
                        id="pc-category-panel-<?php echo $pc_panel_index; ?>"
                        class="pc-category-secondary-panel<?php echo $panel_hidden; ?>">
                        <div class="grid grid-cols-4 gap-8">
                            <div>
                                <a href="<?php echo $mshop_ca_row1['url']; ?>" class="mb-4 inline-flex text-base font-semibold text-[var(--color-primary)]">
                                    <?php echo get_text($mshop_ca_row1['ca_name']); ?> 전체
                                </a>
                            </div>

                            <?php
                            foreach ($cate1 as $key => $cate2) {
                                if (empty($cate2) || $key === 'text' || empty($cate2['text'])) continue;
                                $mshop_ca_row2 = $cate2['text'];
                            ?>
                                <div class="min-w-0 border-l border-gray-200 pl-5 [&:nth-child(4n+1)]:border-l-0 [&:nth-child(4n+1)]:pl-0">
                                    <a href="<?php echo $mshop_ca_row2['url']; ?>" class="mb-4 inline-flex text-base font-semibold text-gray-900">
                                        <?php echo get_text($mshop_ca_row2['ca_name']); ?>
                                    </a>

                                    <div class="flex flex-col gap-3">
                                        <?php
                                        foreach ($cate2 as $sub_key => $cate3) {
                                            if (empty($cate3) || $sub_key === 'text' || empty($cate3['text'])) continue;
                                            $mshop_ca_row3 = $cate3['text'];
                                        ?>
                                            <a href="<?php echo $mshop_ca_row3['url']; ?>" class="text-[15px] text-gray-700">
                                                <?php echo get_text($mshop_ca_row3['ca_name']); ?>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php
                    $pc_panel_index++;
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

        // PC 카테고리 모달이 열리면 배경 스크롤을 막습니다.
        function lockPcCategoryScroll() {
            $("body").css("overflow", "scroll");
            $("html").css("overflow", "hidden");
        }

        // PC 카테고리 모달이 닫히면 배경 스크롤을 원래대로 돌립니다.
        function unlockPcCategoryScroll() {
            $("html, body").css("overflow", "");
        }

        // PC 카테고리 버튼 토글
        $("#category_open").on("click", function(e) {
            e.preventDefault();

            if ($pcCategoryModal.is(":visible")) {
                $("#category_open_icon").removeClass("bg-[var(--color-primary)]");
                $pcCategoryModal.css("display", "none");
                unlockPcCategoryScroll();
                return;
            }

            $("#category_open_icon").addClass("bg-[var(--color-primary)]");
            $pcCategoryModal.css("display", "block");
            lockPcCategoryScroll();
        });

        $("#pc-category-modal .pc-category-close, #pc-category-modal .pc-category-backdrop").on("click", function(e) {
            e.preventDefault();
            $pcCategoryModal.css("display", "none");
            unlockPcCategoryScroll();
        });

    });

    $("#pc-category-modal").on("click", ".pc-category-primary-button", function() {
        var target = $(this).data("target");
        if (!target) return;

        $("#pc-category-modal .pc-category-primary-button").removeClass("text-[var(--color-primary)] font-semibold").addClass("text-gray-700");


        $(this).removeClass("text-gray-700").addClass("text-[var(--color-primary)] font-semibold");


        $("#pc-category-modal .pc-category-secondary-panel").addClass("hidden");
        $("#" + target).removeClass("hidden");
    });

    // 바깥 클릭 닫기 로직
    $(document).mouseup(function(e) {
        const $pcCategoryModal = $("#pc-category-modal");
        const container = $("#pc-category-modal .pc-category-panel");
        const $categoryOpen = $("#category_open");

        if (!$pcCategoryModal.is(":visible")) {
            return;
        }

        if ($categoryOpen.is(e.target) || $categoryOpen.has(e.target).length > 0) {
            return;
        }

        if (container.has(e.target).length === 0 && !container.is(e.target)) {
            $pcCategoryModal.css("display", "none");
            unlockPcCategoryScroll();
        }
    });
</script>
<?php
if (!defined("_GNUBOARD_"))
    exit; // 개별 페이지 접근 불가

// listtype 에서만 넘겨주는 url
$sct_sort_href = isset($sct_sort_href) ? $sct_sort_href : $_SERVER['SCRIPT_NAME'] . '?';

if (!isset($sct_sort_href)) {
    $sct_sort_href = $_SERVER['SCRIPT_NAME'] . '?';

    if ($ca_id) {
        $shop_category_url = shop_category_url($ca_id);
        $sct_sort_href = (strpos($shop_category_url, '?') === false) ? $shop_category_url . '?1=1' : $shop_category_url;
    } else if ($ev_id) {
        $sct_sort_href .= 'ev_id=' . $ev_id;
    }

    if ($skin)
        $sct_sort_href .= '&amp;skin=' . $skin;
}

// &sort= 를 붙여줌
$sct_sort_href .= '&amp;sort=';

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_SHOP_CSS_URL . '/style.css">', 0);
?>

<style>
    #ssch_sort {
        display: none !important;
    }

    #ssch_sort li {
        float: left;
        position: relative;
        margin-left: -1px;
        height: fit-content !important;
        padding: 4px 0 !important;
        line-height: 15px;
    }
</style>
<!-- 상품 정렬 선택 시작 { -->
<section id="sct_sort">
    <h2 class="sound_only">상품 정렬</h2>

    <ul id="ssch_sort">
        <li><a href="<?php echo $sct_sort_href; ?>it_sum_qty&amp;sortodr=desc">판매많은순</a></li>
        <li><a href="<?php echo $sct_sort_href; ?>it_price&amp;sortodr=asc">낮은가격순</a></li>
        <li><a href="<?php echo $sct_sort_href; ?>it_price&amp;sortodr=desc">높은가격순</a></li>
        <li><a href="<?php echo $sct_sort_href; ?>it_use_avg&amp;sortodr=desc">평점높은순</a></li>
        <li><a href="<?php echo $sct_sort_href; ?>it_use_cnt&amp;sortodr=desc">후기많은순</a></li>
        <li><a href="<?php echo $sct_sort_href; ?>it_update_time&amp;sortodr=desc">최근등록순</a></li>
    </ul>

    <?php
    $current_sort_key = (isset($sort) ? $sort : '') . '|' . (isset($sortodr) ? strtolower($sortodr) : '');
    $sort_options = array(
        array('label' => '판매많은순', 'sort' => 'it_sum_qty', 'sortodr' => 'desc'),
        array('label' => '낮은가격순', 'sort' => 'it_price', 'sortodr' => 'asc'),
        array('label' => '높은가격순', 'sort' => 'it_price', 'sortodr' => 'desc'),
        array('label' => '평점높은순', 'sort' => 'it_use_avg', 'sortodr' => 'desc'),
        array('label' => '후기많은순', 'sort' => 'it_use_cnt', 'sortodr' => 'desc'),
        array('label' => '최근등록순', 'sort' => 'it_update_time', 'sortodr' => 'desc'),
    );
    ?>
    <div class="flex justify-end">
        <label for="ssch_sort_select" class="sr-only">상품 정렬 선택</label>

        <div class="relative">
            <select id="ssch_sort_select"
                class="block w-fit appearance-none rounded-xs border border-gray-300 bg-white pl-3 pr-8 py-2 text-sm text-gray-900">
                <option value="">정렬 선택</option>
                <?php foreach ($sort_options as $option) { ?>
                    <?php
                    $option_key = $option['sort'] . '|' . $option['sortodr'];
                    ?>

                    <option value="<?php echo $option['sort']; ?>" data-sortodr="<?php echo $option['sortodr']; ?>" <?php echo ($current_sort_key === $option_key) ? 'selected' : ''; ?>>
                        <?php echo $option['label']; ?>
                    </option>
                <?php } ?>
            </select>

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-chevron-down-icon lucide-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-900 w-4 h-4">
                <path d="m6 9 6 6 6-6" />
            </svg>
        </div>
    </div>
</section>

<script>
    $(function () {
        $('#ssch_sort_select').on('change', function () {
            const sort = $(this).val();
            const sortodr = $(this)
                .find('option:selected')
                .data('sortodr');

            if (!sort) {
                return;
            }

            const url = new URL(window.location.href);

            url.searchParams.set('sort', sort);
            url.searchParams.set('sortodr', sortodr);
            url.searchParams.delete('page');

            window.location.href = url.toString();
        });
    });
</script>
<!-- } 상품 정렬 선택 끝 -->
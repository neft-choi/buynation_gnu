<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$sct_sort_href = $_SERVER['SCRIPT_NAME'] . '?';

if ($ca_id) {
    $shop_category_url = shop_category_url($ca_id);
    $sct_sort_href = (strpos($shop_category_url, '?') === false) ? $shop_category_url . '?1=1' : $shop_category_url;
} else if ($ev_id) {
    $sct_sort_href .= 'ev_id=' . $ev_id;
}

if ($skin)
    $sct_sort_href .= '&amp;skin=' . $skin;
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
<section id="sct_sort" class="!w-full !float-none !text-xs">
    <h2>상품 정렬</h2>

    <!-- 기타 정렬 옵션 
    <ul>
        <li><a href="<?php echo $sct_sort_href; ?>it_name&amp;sortodr=asc">상품명순</a></li>
        <li><a href="<?php echo $sct_sort_href; ?>it_type1&amp;sortodr=desc">히트상품</a></li>
        <li><a href="<?php echo $sct_sort_href; ?>it_type2&amp;sortodr=desc">추천상품</a></li>
        <li><a href="<?php echo $sct_sort_href; ?>it_type3&amp;sortodr=desc">최신상품</a></li>
        <li><a href="<?php echo $sct_sort_href; ?>it_type4&amp;sortodr=desc">인기상품</a></li>
        <li><a href="<?php echo $sct_sort_href; ?>it_type5&amp;sortodr=desc">할인상품</a></li>
    </ul>
	-->

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
    <div class="flex justify-end p-4">
        <label for="ssch_sort_select" class="sr-only">상품 정렬 선택</label>
        <select
            id="ssch_sort_select"
            class="block w-fit rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900"
            onchange="if(this.value){window.location.href=this.value;}"
        >
            <option value="">정렬 선택</option>
            <?php foreach ($sort_options as $option) { ?>
                <?php
                $option_key = $option['sort'] . '|' . $option['sortodr'];
                $option_url = $sct_sort_href . $option['sort'] . '&amp;sortodr=' . $option['sortodr'];
                ?>
                <option value="<?php echo $option_url; ?>" <?php echo ($current_sort_key === $option_key) ? 'selected' : ''; ?>>
                    <?php echo $option['label']; ?>
                </option>
            <?php } ?>
        </select>
    </div>
</section>
<!-- } 상품 정렬 선택 끝 -->

<?php
include_once('./_common.php');

// 상품 리스트에서 다른 필드로 정렬을 하려면 아래의 배열 코드에서 해당 필드를 추가하세요.
$sort = (isset($_REQUEST['sort']) && in_array($_REQUEST['sort'], array('it_name', 'it_sum_qty', 'it_price', 'it_use_avg', 'it_use_cnt', 'it_update_time'))) ? $_REQUEST['sort'] : 'it_sum_qty';
$type = isset($_REQUEST['type']) ? (int) preg_replace("/[^0-9]/", "", $_REQUEST['type']) : 1;
$sortodr = (isset($_REQUEST['sortodr']) && in_array(strtolower($_REQUEST['sortodr']), array('asc', 'desc')))
    ? strtolower($_REQUEST['sortodr'])
    : 'desc';
$ca_id = isset($_REQUEST['ca_id']) ? preg_replace('/[^0-9A-Za-z]/', '', $_REQUEST['ca_id']) : '';
if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH . '/listtype.php');
    return;
}

if ($type === 1)      $g5['title'] = '히트상품';
else if ($type === 2) $g5['title'] = '추천상품';
else if ($type === 3) $g5['title'] = '최신상품';
else if ($type === 4) $g5['title'] = '인기상품';
else if ($type === 5) $g5['title'] = '할인상품';
else
    alert('상품유형이 아닙니다.');

include_once('./_head.php');

?>
<?php
$sct_sort_href = $_SERVER['SCRIPT_NAME'] . '?type=' . $type;
if ($skin) $sct_sort_href .= '&skin=' . $skin;
if ($ca_id) $sct_sort_href .= '&ca_id=' . $ca_id;
$sct_sort_href .= '&sort=';

$sort_link_base_class = 'text-sm';
$sort_link_active_class = 'font-semibold text-gray-900';
$sort_link_inactive_class = 'font-medium text-gray-500';

$is_active_sum_qty = ($sort === 'it_sum_qty' && $sortodr === 'desc');
$is_active_price_asc = ($sort === 'it_price' && $sortodr === 'asc');
$is_active_price_desc = ($sort === 'it_price' && $sortodr === 'desc');
?>

<!-- <section id="sct_sort">
    <ul id="ssch_sort">
        <li><a href="<?php echo $sct_sort_href; ?>it_sum_qty&sortodr=desc">판매많은순</a></li>
        <li><a href="<?php echo $sct_sort_href; ?>it_price&sortodr=asc">낮은가격순</a></li>
        <li><a href="<?php echo $sct_sort_href; ?>it_price&sortodr=desc">높은가격순</a></li>
        <li><a href="<?php echo $sct_sort_href; ?>it_use_avg&sortodr=desc">평점높은순</a></li>
        <li><a href="<?php echo $sct_sort_href; ?>it_use_cnt&sortodr=desc">후기많은순</a></li>
        <li><a href="<?php echo $sct_sort_href; ?>it_update_time&sortodr=desc">최근등록순</a></li>
    </ul>
</section> -->
<section class="flex items-center justify-between gap-4 p-4" aria-label="유형별 상품 필터">
    <div class="text-sm text-gray-500">방금 전 업데이트</div>
    <div class="inline-flex items-center gap-3" role="tablist" aria-label="집계 기간">
        <a class="<?php echo $sort_link_base_class . ' ' . ($is_active_sum_qty ? $sort_link_active_class : $sort_link_inactive_class); ?>" href="<?php echo $sct_sort_href; ?>it_sum_qty&sortodr=desc">판매많은순</a>
        <a class="<?php echo $sort_link_base_class . ' ' . ($is_active_price_asc ? $sort_link_active_class : $sort_link_inactive_class); ?>" href="<?php echo $sct_sort_href; ?>it_price&sortodr=asc">낮은가격순</a>
        <a class="<?php echo $sort_link_base_class . ' ' . ($is_active_price_desc ? $sort_link_active_class : $sort_link_inactive_class); ?>" href="<?php echo $sct_sort_href; ?>it_price&sortodr=desc">높은가격순</a>
        <!-- <a href="<?php echo $sct_sort_href; ?>it_use_avg&sortodr=desc">평점높은순</a> -->
        <!-- <a href="<?php echo $sct_sort_href; ?>it_use_cnt&sortodr=desc">후기많은순</a> -->
        <!-- <a href="<?php echo $sct_sort_href; ?>it_update_time&sortodr=desc">최근등록순</a> -->
        <!-- <button type="button" class="text-sm font-semibold text-gray-900" role="tab" aria-selected="true" data-period="realtime">실시간</button>
        <button type="button" class="text-sm font-medium text-gray-500" role="tab" aria-selected="false" data-period="daily">일간</button>
        <button type="button" class="text-sm font-medium text-gray-500" role="tab" aria-selected="false" data-period="weekly">주간</button> -->
    </div>
</section>

<?php

// 한페이지에 출력하는 이미지수 = $list_mod * $list_row
$list_mod   = $default['de_listtype_list_mod'];   // 한줄에 이미지 몇개씩 출력?
$list_row   = $default['de_listtype_list_row'];   // 한 페이지에 몇라인씩 출력?

$img_width  = $default['de_listtype_img_width'];  // 출력이미지 폭
$img_height = $default['de_listtype_img_height']; // 출력이미지 높이

// 상품 출력순서가 있다면
$order_by = ' it_order, it_id desc ';
if ($sort != '')
    $order_by = $sort . ' ' . $sortodr . ' , it_order, it_id desc';
else
    $order_by = 'it_order, it_id desc';

$skin = isset($skin) ? strip_tags($skin) : '';
if (!$skin || preg_match('#\.+[\\\/]#', $skin))
    $skin = $default['de_listtype_list_skin'];
else
    $skin = preg_replace('#\.+[\\\/]#', '', $skin);

define('G5_SHOP_CSS_URL', G5_SHOP_SKIN_URL);

// 리스트 유형별로 출력
$list_file = G5_SHOP_SKIN_PATH . '/' . $skin;
if (file_exists($list_file)) {
    // 총몇개 = 한줄에 몇개 * 몇줄
    $items = $list_mod * $list_row;
    // 페이지가 없으면 첫 페이지 (1 페이지)
    if ($page < 1) $page = 1;
    // 시작 레코드 구함
    $from_record = ($page - 1) * $items;

    $list = new item_list();
    if ($ca_id) {
        $list->set_category($ca_id);
    }
    $list->set_type($type);
    $list->set_list_skin($list_file);
    $list->set_list_mod($list_mod);
    $list->set_list_row($list_row);
    $list->set_img_size($img_width, $img_height);
    $list->set_is_page(true);
    $list->set_order_by($order_by);
    $list->set_from_record($from_record);
    $list->set_view('it_img', true);
    $list->set_view('it_id', false);
    $list->set_view('it_name', true);
    $list->set_view('it_cust_price', true);
    $list->set_view('it_price', true);
    $list->set_view('it_icon', true);
    $list->set_view('sns', true);
    echo $list->run();

    // where 된 전체 상품수
    $total_count = $list->total_count;
    // 전체 페이지 계산
    $total_page  = ceil($total_count / $items);
} else {
    echo '<div align="center">' . get_text($skin) . ' 파일을 찾을 수 없습니다.<br>관리자에게 알려주시면 감사하겠습니다.</div>';
}

$qstr .= '&amp;type=' . $type . '&amp;sort=' . $sort;
echo get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page=");

include_once('./_tail.php');

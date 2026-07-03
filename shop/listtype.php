<?php
include_once('./_common.php');

define('G5_SHOP_CSS_URL', G5_SHOP_SKIN_URL);

// 상품 리스트에서 다른 필드로 정렬을 하려면 아래의 배열 코드에서 해당 필드를 추가하세요.
$sort = (isset($_REQUEST['sort']) && in_array($_REQUEST['sort'], array('it_name', 'it_sum_qty', 'it_price', 'it_use_avg', 'it_use_cnt', 'it_update_time'))) ? $_REQUEST['sort'] : 'it_sum_qty';
$type = isset($_REQUEST['type']) ? (int) preg_replace("/[^0-9]/", "", $_REQUEST['type']) : 1;
$sortodr = (isset($_REQUEST['sortodr']) && in_array(strtolower($_REQUEST['sortodr']), array('asc', 'desc')))
    ? strtolower($_REQUEST['sortodr'])
    : 'desc';
$ca_id = isset($_REQUEST['ca_id']) ? preg_replace('/[^0-9A-Za-z]/', '', $_REQUEST['ca_id']) : '';

// 상품 종류 필터
$it_type1 = isset($_GET['it_type1']) && $_GET['it_type1'] === '1' ? 1 : 0;
$it_type2 = isset($_GET['it_type2']) && $_GET['it_type2'] === '1' ? 1 : 0;
$it_type3 = isset($_GET['it_type3']) && $_GET['it_type3'] === '1' ? 1 : 0;
$it_type4 = isset($_GET['it_type4']) && $_GET['it_type4'] === '1' ? 1 : 0;

// 최소 가격 최대 가격 필터
$price_range = isset($_REQUEST['price_range']) ? preg_replace('/[^0-9_a-z]/', '', $_REQUEST['price_range']) : '';
$price_min = isset($_REQUEST['price_min']) ? preg_replace('/[^0-9]/', '', $_REQUEST['price_min']) : '';
$price_max = isset($_REQUEST['price_max']) ? preg_replace('/[^0-9]/', '', $_REQUEST['price_max']) : '';

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH . '/listtype.php');
    return;
}

// type 분기
if ($type === 1)
    $g5['title'] = '히트상품';
else if ($type === 2)
    $g5['title'] = '추천상품';
else if ($type === 3)
    $g5['title'] = '최신상품';
else if ($type === 4)
    $g5['title'] = '인기상품';
else if ($type === 5)
    $g5['title'] = '할인상품';
else
    alert('상품유형이 아닙니다.');

include_once('./_head.php');

$total_count = 0;

$count_where = array();
$count_where[] = " it_use = '1' ";
$count_where[] = " it_type{$type} = '1' ";

if ($ca_id) {
    $count_where[] = " ca_id like '{$ca_id}%' ";
}

// 상품 종류 필터 SQL
$item_type_conditions = array();

if ($it_type1)
    $item_type_conditions[] = " it_type1 = '1' ";

if ($it_type2)
    $item_type_conditions[] = " it_type2 = '1' ";

if ($it_type3)
    $item_type_conditions[] = " it_type3 = '1' ";

if ($it_type4)
    $item_type_conditions[] = " it_type4 = '1' ";

$item_type_where = '';

if ($item_type_conditions) {
    $item_type_where = ' (' . implode(' or ', $item_type_conditions) . ') ';
    $count_where[] = $item_type_where;
}

// 가격 필터 SQL
$price_where = '';

if ($price_range === '0_30000') {
    $price_where = " it_price between 0 and 30000 ";
} else if ($price_range === '30000_50000') {
    $price_where = " it_price between 30000 and 50000 ";
} else if ($price_range === '50000_100000') {
    $price_where = " it_price between 50000 and 100000 ";
} else if ($price_range === '100000_up') {
    $price_where = " it_price >= 100000 ";
} else if ($price_min && $price_max) {
    $price_where = " it_price between '{$price_min}' and '{$price_max}' ";
} else if ($price_min) {
    $price_where = " it_price >= '{$price_min}' ";
} else if ($price_max) {
    $price_where = " it_price <= '{$price_max}' ";
}

if ($price_where) {
    $count_where[] = $price_where;
}

$count_sql = " select count(*) as cnt
                from `{$g5['g5_shop_item_table']}`
                where " . implode(' and ', $count_where);

$count_row = sql_fetch($count_sql);
$total_count = isset($count_row['cnt']) ? (int) $count_row['cnt'] : 0;

?>

<?php
// type 분기만 추가된 정렬 전 링크
$sct_sort_href = $_SERVER['SCRIPT_NAME'] . '?type=' . $type;

// 스킨 값이나 카테고리 값이 있으면 먼저 붙임
if ($skin)
    $sct_sort_href .= '&skin=' . $skin;
if ($ca_id)
    $sct_sort_href .= '&ca_id=' . $ca_id;

// &sort= 는 미리 붙이지 않고 list.sort.skin.php 에서 붙임
// $sct_sort_href .= '&sort=';

$sort_link_base_class = 'text-sm';
$sort_link_active_class = 'font-semibold text-gray-900';
$sort_link_inactive_class = 'font-medium text-gray-500';

$is_active_sum_qty = ($sort === 'it_sum_qty' && $sortodr === 'desc');
$is_active_price_asc = ($sort === 'it_price' && $sortodr === 'asc');
$is_active_price_desc = ($sort === 'it_price' && $sortodr === 'desc');
?>
<?php
$type_category_sql = " select ca_id, ca_name
                       from {$g5['g5_shop_category_table']}
                       where length(ca_id) = 2
                         and ca_use = '1'
                       order by ca_order, ca_id ";
$type_category_result = sql_query($type_category_sql);
$type_category_items = array();

$type_category_items[] = array(
    'ca_id' => '',
    'ca_name' => '전체',
    'href' => $_SERVER['SCRIPT_NAME'] . '?type=' . $type,
    'active' => !$ca_id,
);

while ($type_category_row = sql_fetch_array($type_category_result)) {
    $type_category_items[] = array(
        'ca_id' => $type_category_row['ca_id'],
        'ca_name' => $type_category_row['ca_name'],
        'href' => $_SERVER['SCRIPT_NAME'] . '?type=' . $type . '&ca_id=' . $type_category_row['ca_id'],
        'active' => (substr($ca_id, 0, 2) === $type_category_row['ca_id']),
    );
}
?>

<!-- 카테고리 -->
<section>
    <div class="flex flex-col items-center px-4 pc:px-5 pc:py-7">
        <h2 class="hidden pc:block text-4xl font-bold"><?php echo $g5['title']; ?></h2>

        <div class="flex items-center justify-between gap-4 w-full overflow-x-auto my-2 mb-4 pc:my-7">
            <ul class="flex items-center gap-2 text-sm pc:text-base text-nowrap pc:px-2">
                <?php foreach ($type_category_items as $category_item) { ?>
                    <?php
                    $category_class = $category_item['active']
                        ? 'bg-[var(--color-primary)] text-black font-semibold'
                        : 'bg-gray-100 text-gray-700 font-medium';
                    ?>
                    <li>
                        <a href="<?php echo $category_item['href']; ?>"
                            class="block rounded px-4 py-2 pc:px-6 pc:py-3 <?php echo $category_class; ?>">
                            <?php echo get_text($category_item['ca_name']); ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>

            <button type="button"
                class="inline-flex items-center justify-center w-11 h-11 shrink-0 border border-gray-300 rounded-full cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-chevron-down-icon lucide-chevron-down w-5 h-5">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>
        </div>
    </div>
</section>

<!-- 상품 리스트 정렬 -->
<section class="px-4 pt-4">
    <div class="flex items-center justify-between">
        <div>
            <span class="text-red-500 font-semibold"><?php echo number_format($total_count); ?>개</span>
            <span>의 상품</span>
        </div>
        <div class="flex items-center gap-1 text-sm">
            <?php
            // 정렬 드롭다운
            include G5_THEME_PATH . '/skin/shop/basic/list.sort.skin.php'; ?>

            <button type="button" id="filterDrawerOpen"
                class="inline-flex pc:hidden items-center gap-1 border border-gray-300 rounded-xs p-2 ml-0.5">
                <span class="text-nowrap">필터</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-list-filter-icon lucide-list-filter w-4 h-4">
                    <path d="M2 5h20" />
                    <path d="M6 12h12" />
                    <path d="M9 19h6" />
                </svg>
            </button>
        </div>
    </div>
</section>


<?php

// 한페이지에 출력하는 이미지수 = $list_mod * $list_row
$list_mod = $default['de_listtype_list_mod'];   // 한줄에 이미지 몇개씩 출력?
$list_row = $default['de_listtype_list_row'];   // 한 페이지에 몇라인씩 출력?

$img_width = $default['de_listtype_img_width'];  // 출력이미지 폭
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



// 리스트 유형별로 출력
$list_file = G5_SHOP_SKIN_PATH . '/' . $skin;
if (file_exists($list_file)) {
    // 총몇개 = 한줄에 몇개 * 몇줄
    $items = $list_mod * $list_row;
    // 페이지가 없으면 첫 페이지 (1 페이지)
    if ($page < 1)
        $page = 1;
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

    // 가격 필터 또는 상품 종류 필터가 있으면 item_list 대신 직접 만든 SQL을 사용
    if ($price_where || $item_type_where) {
        $list_where = array();
        $list_where[] = " it_use = '1' ";
        $list_where[] = " it_type{$type} = '1' ";

        if ($ca_id) {
            $list_where[] = " ca_id like '{$ca_id}%' ";
        }

        if ($item_type_where) {
            $list_where[] = $item_type_where;
        }

        if ($price_where) {
            $list_where[] = $price_where;
        }

        $list_sql_where = " where " . implode(' and ', $list_where);
        $list_sql = " select *
                  from `{$g5['g5_shop_item_table']}`
                  {$list_sql_where}
                  order by {$order_by}
                  limit {$from_record}, {$items} ";

        $list->set_query($list_sql);
    } else {
        if ($ca_id) {
            $list->set_category($ca_id);
        }

        $list->set_type($type);
    }

    echo $list->run();

    // 직접 만든 SQL을 사용하지 않았을 때만 item_list에서 계산한 개수 사용
    if (!$price_where && !$item_type_where) {
        $total_count = $list->total_count;
    }

    // 전체 페이지 계산
    $total_page = ceil($total_count / $items);

} else {
    echo '<div align="center">' . get_text($skin) . ' 파일을 찾을 수 없습니다.<br>관리자에게 알려주시면 감사하겠습니다.</div>';
}

// 페이징에 현재 상품 유형 유지
$qstr .= '&amp;type=' . $type;

// 선택된 카테고리가 있으면 유지
if ($ca_id)
    $qstr .= '&amp;ca_id=' . $ca_id;

// 정렬 조건 유지
$qstr .= '&amp;sort=' . $sort;
$qstr .= '&amp;sortodr=' . $sortodr;

// 상품 종류 필터 유지
if ($it_type1)
    $qstr .= '&amp;it_type1=1';

if ($it_type2)
    $qstr .= '&amp;it_type2=1';

if ($it_type3)
    $qstr .= '&amp;it_type3=1';

if ($it_type4)
    $qstr .= '&amp;it_type4=1';

// 가격 필터 유지
if ($price_range)
    $qstr .= '&amp;price_range=' . $price_range;
if ($price_min)
    $qstr .= '&amp;price_min=' . $price_min;
if ($price_max)
    $qstr .= '&amp;price_max=' . $price_max;

echo get_paging(
    $config['cf_write_pages'],
    $page,
    $total_page,
    "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="
);

include_once('./_tail.php');

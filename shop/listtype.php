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
if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH . '/listtype.php');
    return;
}

// type 분기
if ($type === 1)      $g5['title'] = '히트상품';
else if ($type === 2) $g5['title'] = '추천상품';
else if ($type === 3) $g5['title'] = '최신상품';
else if ($type === 4) $g5['title'] = '인기상품';
else if ($type === 5) $g5['title'] = '할인상품';
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

$count_sql = " select count(*) as cnt
                from `{$g5['g5_shop_item_table']}`
                where " . implode(' and ', $count_where);

$count_row = sql_fetch($count_sql);
$total_count = isset($count_row['cnt']) ? (int) $count_row['cnt'] : 0;

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

<section>

</section>
<!-- 상품 리스트 정렬, 필터 -->
<section class="px-4 pt-4">
    <div class="flex items-center justify-between">
        <div>
            <span class="text-red-500 font-semibold"><?php echo number_format($total_count); ?>개</span>
            <span>의 상품</span>
        </div>
        <div class="flex items-center gap-1 text-sm">
            <?php include G5_THEME_PATH . '/skin/shop/basic/list.sort.skin.php'; ?>

            <button type="button" id="filterDrawerOpen" class="inline-flex pc:hidden items-center gap-1 border border-gray-300 rounded-xs p-2 ml-0.5">
                <span class="text-nowrap">필터</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-filter-icon lucide-list-filter w-4 h-4">
                    <path d="M2 5h20" />
                    <path d="M6 12h12" />
                    <path d="M9 19h6" />
                </svg>
            </button>
        </div>
    </div>
</section>

<div id="filterDrawer" class="hidden fixed inset-0 z-50" aria-hidden="true">
    <div id="filterDrawerBackdrop" class="absolute inset-0 bg-black/40"></div>
    <div class="fixed left-0 right-0 bottom-[var(--bottom-nav-height)] w-full bg-white rounded-t-xl">
        <div class="px-4 pt-5">
            <div class="flex items-center justify-between">
                <p class="text-[20px] font-bold">필터</p>
                <button type="button" id="filterDrawerClose">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x w-6 h-6">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex items-center gap-8 text-sm border-b border-gray-200 mt-3">
                <button type="button" id="filterTabDelivery" class="pb-2 font-semibold text-gray-900 border-b-2 border-gray-900">
                    배송유형
                </button>
                <button type="button" id="filterTabBenefit" class="pb-2 text-gray-400">
                    혜택
                </button>
                <button type="button" id="filterTabPrice" class="pb-2 text-gray-400">
                    가격대
                </button>
            </div>
        </div>

        <div id="filterPanelDelivery" class="filterDrawerPanel mt-6 px-4">
            <ul class="space-y-3">
                <li>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <div class="relative w-5 h-5">
                            <input type="checkbox" name="delivery_type[]" value="today_start" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900 checked:bg-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-gray-300 peer-checked:text-white pointer-events-none">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                        </div>
                        <span>오늘출발</span>
                    </label>
                </li>
                <li>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <div class="relative w-5 h-5">
                            <input type="checkbox" name="delivery_type[]" value="gift_wrap" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900 checked:bg-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-gray-300 peer-checked:text-white pointer-events-none">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                        </div>
                        <span>선물포장</span>
                    </label>
                </li>
                <li>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <div class="relative w-5 h-5">
                            <input type="checkbox" name="delivery_type[]" value="quick_delivery" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900 checked:bg-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-gray-300 peer-checked:text-white pointer-events-none">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                        </div>
                        <span>퀵배송</span>
                    </label>
                </li>
            </ul>
        </div>

        <div id="filterPanelBenefit" class="filterDrawerPanel mt-6 px-4 hidden">
            <ul class="space-y-3">
                <li>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <div class="relative w-5 h-5">
                            <input type="checkbox" name="benefit_type[]" value="free_shipping" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900 checked:bg-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-gray-300 peer-checked:text-white pointer-events-none">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                        </div>
                        <span>무료배송</span>
                    </label>
                </li>
                <li>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <div class="relative w-5 h-5">
                            <input type="checkbox" name="benefit_type[]" value="special_price" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900 checked:bg-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-gray-300 peer-checked:text-white pointer-events-none">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                        </div>
                        <span>특가상품</span>
                    </label>
                </li>
                <li>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <div class="relative w-5 h-5">
                            <input type="checkbox" name="benefit_type[]" value="coupon" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900 checked:bg-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-gray-300 peer-checked:text-white pointer-events-none">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                        </div>
                        <span>쿠폰</span>
                    </label>
                </li>
                <li>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <div class="relative w-5 h-5">
                            <input type="checkbox" name="benefit_type[]" value="event" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900 checked:bg-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 text-gray-300 peer-checked:text-white pointer-events-none">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                        </div>
                        <span>이벤트</span>
                    </label>
                </li>
            </ul>
        </div>

        <div id="filterPanelPrice" class="filterDrawerPanel mt-6 px-4 hidden">
            <div>
                <ul class="space-y-3 mb-3">
                    <li>
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <div class="relative w-5 h-5">
                                <input type="radio" name="price_range" value="0_30000" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900">
                                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 bg-gray-900 rounded-full opacity-0 peer-checked:opacity-100 pointer-events-none"></div>
                            </div>
                            <span>~3만원</span>
                        </label>
                    </li>
                    <li>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <div class="relative w-5 h-5">
                                <input type="radio" name="price_range" value="30000_50000" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900">
                                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 bg-gray-900 rounded-full opacity-0 peer-checked:opacity-100 pointer-events-none"></div>
                            </div>
                            <span>3만원~5만원</span>
                        </label>
                    </li>
                    <li>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <div class="relative w-5 h-5">
                                <input type="radio" name="price_range" value="50000_100000" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900">
                                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 bg-gray-900 rounded-full opacity-0 peer-checked:opacity-100 pointer-events-none"></div>
                            </div>
                            <span>5만원~10만원</span>
                        </label>
                    </li>
                    <li>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <div class="relative w-5 h-5">
                                <input type="radio" name="price_range" value="100000_up" class="peer appearance-none w-full h-full border border-gray-300 rounded-full text-black checked:border-gray-900">
                                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 bg-gray-900 rounded-full opacity-0 peer-checked:opacity-100 pointer-events-none"></div>
                            </div>
                            <span>10만원 이상</span>
                        </label>
                    </li>
                </ul>

                <div class="flex items-center gap-1">
                    <input type="text" name="price_min" class="w-full border border-gray-300 !px-2 !py-1 text-sm placeholder:text-right" placeholder="원">
                    <span>~</span>
                    <input type="text" name="price_max" class="w-full border border-gray-300 !px-2 !py-1 text-sm placeholder:text-right" placeholder="원">
                    <button type="button" id="btn_price_apply" class="border border-gray-300 rounded p-2 bg-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search w-3.5 h-3.5">
                            <path d="m21 21-4.34-4.34" />
                            <circle cx="11" cy="11" r="8" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 text-base px-4 pt-5 pb-4">
            <button type="button" id="filterDrawerReset" class="flex items-center justify-center gap-2 w-full bg-white border border-gray-400 rounded px-5 py-4 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw-icon lucide-rotate-ccw w-5 h-5">
                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                    <path d="M3 3v5h5" />
                </svg>
                <span>초기화</span>
            </button>
            <button type="button" class="w-full bg-[var(--color-primary)] rounded px-5 py-4 cursor-pointer">
                <span>적용하기</span>
            </button>
        </div>
    </div>
</div>

<script>
    // 필터 버튼 누르면 Drawer 열림
    $('#filterDrawerOpen').on('click', function() {
        $('#hd').css('z-index', 4);
        $('#filterDrawer').removeClass('hidden');
    })

    // 필터 Drawer 안 X 버튼과 뒷배경 누르면 Drawer hidden 처리
    $('#filterDrawerClose, #filterDrawerBackdrop').on('click', function() {
        $('#filterDrawer').addClass('hidden');
        $('#hd').css('z-index', '');
    });

    function setActiveFilterTab($activeTab) {
        $('#filterTabDelivery, #filterTabBenefit, #filterTabPrice')
            .removeClass('font-semibold text-gray-900 border-b-2 border-gray-900')
            .addClass('text-gray-400');

        $activeTab
            .removeClass('text-gray-400')
            .addClass('font-semibold text-gray-900 border-b-2 border-gray-900');
    }

    // 필터 탭 선택 시 탭 밑줄 스타일 적용
    $('#filterTabDelivery').on('click', function() {
        setActiveFilterTab($(this));
    });

    $('#filterTabBenefit').on('click', function() {
        setActiveFilterTab($(this));
    });

    $('#filterTabPrice').on('click', function() {
        setActiveFilterTab($(this));
    });

    // 필터 탭 선택 시 패널 전환
    $('#filterTabDelivery').on('click', function() {
        $('.filterDrawerPanel').addClass('hidden');
        $('#filterPanelDelivery').removeClass('hidden');
    });

    $('#filterTabBenefit').on('click', function() {
        $('.filterDrawerPanel').addClass('hidden');
        $('#filterPanelBenefit').removeClass('hidden');
    });

    $('#filterTabPrice').on('click', function() {
        $('.filterDrawerPanel').addClass('hidden');
        $('#filterPanelPrice').removeClass('hidden');
    });

    // 필터 초기화 버튼
    $('#filterDrawerReset').on('click', function() {
        $('input[name="delivery_type[]"]').prop('checked', false);
        $('input[name="benefit_type[]"]').prop('checked', false);
        $('input[name="price_range"]').prop('checked', false);
        $('input[name="price_min"]').val('');
        $('input[name="price_max"]').val('');
    });
</script>

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

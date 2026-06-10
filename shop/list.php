<?php
include_once('./_common.php');

$ca_id = isset($_REQUEST['ca_id']) ? safe_replace_regex($_REQUEST['ca_id'], 'ca_id') : '';
$skin = isset($_REQUEST['skin']) ? safe_replace_regex($_REQUEST['skin'], 'skin') : '';

// 상품 리스트에서 다른 필드로 정렬을 하려면 아래의 배열 코드에서 해당 필드를 추가하세요.
if (isset($sort) && ! in_array($sort, array('it_name', 'it_sum_qty', 'it_price', 'it_use_avg', 'it_use_cnt', 'it_update_time'))) {
    $sort = '';
}

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH . '/list.php');
    return;
}

// 테마에 list.php 있으면 include
if (defined('G5_THEME_SHOP_PATH')) {
    $theme_list_file = G5_THEME_SHOP_PATH . '/list.php';
    if (is_file($theme_list_file)) {
        include_once($theme_list_file);
        return;
    }
    unset($theme_list_file);
}

$sql = " select * from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' and ca_use = '1'  ";
$ca = sql_fetch($sql);
if (! (isset($ca['ca_id']) && $ca['ca_id']))
    alert('등록된 분류가 없습니다.');

// 테마미리보기 스킨 등의 변수 재설정
if (defined('_THEME_PREVIEW_') && _THEME_PREVIEW_ === true) {
    $ca['ca_skin']       = (isset($tconfig['ca_skin']) && $tconfig['ca_skin']) ? $tconfig['ca_skin'] : $ca['ca_skin'];
    $ca['ca_img_width']  = (isset($tconfig['ca_img_width']) && $tconfig['ca_img_width']) ? $tconfig['ca_img_width'] : $ca['ca_img_width'];
    $ca['ca_img_height'] = (isset($tconfig['ca_img_height']) && $tconfig['ca_img_height']) ? $tconfig['ca_img_height'] : $ca['ca_img_height'];
    $ca['ca_list_mod']   = (isset($tconfig['ca_list_mod']) && $tconfig['ca_list_mod']) ? $tconfig['ca_list_mod'] : $ca['ca_list_mod'];
    $ca['ca_list_row']   = (isset($tconfig['ca_list_row']) && $tconfig['ca_list_row']) ? $tconfig['ca_list_row'] : $ca['ca_list_row'];
}

// 본인인증, 성인인증체크
if (!$is_admin && $config['cf_cert_use']) {
    $msg = shop_member_cert_check($ca_id, 'list');
    if ($msg)
        alert($msg, G5_SHOP_URL);
}

$g5['title'] = $ca['ca_name'] . ' 상품리스트';

// // list 페이지 헤더
// $shop_header = array(
//     'layout' => 'side_actions',
//     'title' => $ca['ca_name'],
//     'show_back' => true,
//     'show_title' => true,
//     'show_search_button' => false,
//     'show_searchbar' => false,
//     'show_notice' => false,
//     'show_cart' => true,
//     'back_mode' => 'history',
// );

if ($ca['ca_include_head'] && is_include_path_check($ca['ca_include_head']))
    @include_once($ca['ca_include_head']);
else
    include_once(G5_SHOP_PATH . '/_head.php');

// 스킨경로
$skin_dir = G5_SHOP_SKIN_PATH;

if ($ca['ca_skin_dir']) {
    if (preg_match('#^theme/(.+)$#', $ca['ca_skin_dir'], $match))
        $skin_dir = G5_THEME_PATH . '/' . G5_SKIN_DIR . '/shop/' . $match[1];
    else
        $skin_dir = G5_PATH . '/' . G5_SKIN_DIR . '/shop/' . $ca['ca_skin_dir'];

    if (is_dir($skin_dir)) {
        $skin_file = $skin_dir . '/' . $ca['ca_skin'];

        if (!is_file($skin_file))
            $skin_dir = G5_SHOP_SKIN_PATH;
    } else {
        $skin_dir = G5_SHOP_SKIN_PATH;
    }
}

define('G5_SHOP_CSS_URL', str_replace(G5_PATH, G5_URL, $skin_dir));

if ($is_admin)
    echo '<div class="sct_admin"><a href="' . G5_ADMIN_URL . '/shop_admin/categoryform.php?w=u&amp;ca_id=' . $ca_id . '" class="btn_admin btn"><span class="sound_only">분류 관리</span><i class="fa fa-cog fa-spin fa-fw"></i></a></div>';
?>

<script>
    var itemlist_ca_id = "<?php echo $ca_id; ?>";
</script>
<script src="<?php echo G5_JS_URL; ?>/shop.list.js"></script>

<style>

</style>
<!-- 상품 목록 시작 { -->
<div id="sct">

    <?php
    $nav_skin = $skin_dir . '/navigation.skin.php';
    if (!is_file($nav_skin))
        $nav_skin = G5_SHOP_SKIN_PATH . '/navigation.skin.php';
    include $nav_skin;

    // 상단 HTML
    echo '<div id="sct_hhtml">' . conv_content($ca['ca_head_html'], 1) . '</div>';

    $cate_skin = $skin_dir . '/listcategory.skin.php';
    if (!is_file($cate_skin))
        $cate_skin = G5_SHOP_SKIN_PATH . '/listcategory.skin.php';
    include $cate_skin;

    // 상품 출력순서가 있다면
    if ($sort != "")
        $order_by = $sort . ' ' . $sortodr . ' , it_order, it_id desc';
    else
        $order_by = 'it_order, it_id desc';

    $error = '<p class="sct_noitem">등록된 상품이 없습니다.</p>';

    // 검색된 총 상품의 수 구하기
    $total_count = 0;

    $count_sql = " select count(*) as cnt
                from {$g5['g5_shop_item_table']}
                where it_use = '1'
                  and (
                        ca_id like '{$ca['ca_id']}%'
                        or ca_id2 like '{$ca['ca_id']}%'
                        or ca_id3 like '{$ca['ca_id']}%'
                  ) ";

    $count_row = sql_fetch($count_sql);
    $total_count = isset($count_row['cnt']) ? (int) $count_row['cnt'] : 0;

    // 리스트 스킨
    $skin_file = is_include_path_check($skin_dir . '/' . $ca['ca_skin']) ? $skin_dir . '/' . $ca['ca_skin'] : $skin_dir . '/list.10.skin.php';

    if (file_exists($skin_file)) {

        echo '<div id="sct_sortlst" class="!border-none">';
    ?>

        <!-- 상품 리스트 정렬, 필터 -->
        <section class="px-4 pt-4">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-red-500 font-semibold"><?php echo number_format($total_count); ?>개</span>
                    <span>의 상품</span>
                </div>
                <div class="flex items-center gap-1 text-sm">
                    <?php
                    $sort_skin = $skin_dir . '/list.sort.skin.php';
                    if (!is_file($sort_skin))
                        $sort_skin = G5_SHOP_SKIN_PATH . '/list.sort.skin.php';
                    include $sort_skin;
                    ?>

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
            <div class="absolute bottom-[var(--bottom-nav-height)] w-full bg-white rounded-t-xl">
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
                $('#filterDrawer').removeClass('hidden');
            })

            // 필터 Drawer 안 X 버튼과 뒷배경 누르면 Drawer hidden 처리
            $('#filterDrawerClose, #filterDrawerBackdrop').on('click', function() {
                $('#filterDrawer').addClass('hidden');
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
        // 상품 보기 타입 변경 버튼
        $sub_skin = $skin_dir . '/list.sub.skin.php';
        if (!is_file($sub_skin))
            $sub_skin = G5_SHOP_SKIN_PATH . '/list.sub.skin.php';
        include $sub_skin;
        echo '</div>';

        // 총몇개 = 한줄에 몇개 * 몇줄
        $items = $ca['ca_list_mod'] * $ca['ca_list_row'];
        // 페이지가 없으면 첫 페이지 (1 페이지)
        if ($page < 1) $page = 1;
        // 시작 레코드 구함
        $from_record = ($page - 1) * $items;

        $list = new item_list($skin_file, $ca['ca_list_mod'], $ca['ca_list_row'], $ca['ca_img_width'], $ca['ca_img_height']);
        $list->set_category($ca['ca_id'], 1);
        $list->set_category($ca['ca_id'], 2);
        $list->set_category($ca['ca_id'], 3);
        $list->set_is_page(true);
        $list->set_order_by($order_by);
        $list->set_from_record($from_record);
        $list->set_view('it_img', true);
        $list->set_view('it_id', false);
        $list->set_view('it_name', true);
        $list->set_view('it_basic', true);
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
        echo '<div class="sct_nofile">' . str_replace(G5_PATH . '/', '', $skin_file) . ' 파일을 찾을 수 없습니다.<br>관리자에게 알려주시면 감사하겠습니다.</div>';
    }

    $qstr1 = 'ca_id=' . $ca_id;
    $qstr1 .= '&amp;sort=' . $sort . '&amp;sortodr=' . $sortodr;
    echo get_paging($config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr1 . '&amp;page=');

    // 하단 HTML
    echo '<div id="sct_thtml">' . conv_content($ca['ca_tail_html'], 1) . '</div>';

    ?>
</div>
<!-- } 상품 목록 끝 -->

<?php
if ($ca['ca_include_tail'] && is_include_path_check($ca['ca_include_tail']))
    @include_once($ca['ca_include_tail']);
else
    include_once(G5_SHOP_PATH . '/_tail.php');

echo "\n<!-- {$ca['ca_skin']} -->\n";

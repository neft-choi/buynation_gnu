<?php
include_once('./_common.php');

$ca_id = isset($_REQUEST['ca_id']) ? safe_replace_regex($_REQUEST['ca_id'], 'ca_id') : '';
$skin = isset($_REQUEST['skin']) ? safe_replace_regex($_REQUEST['skin'], 'skin') : '';

// 상품 종류 필터
$it_type1 = isset($_GET['it_type1']) && $_GET['it_type1'] === '1' ? 1 : 0;
$it_type2 = isset($_GET['it_type2']) && $_GET['it_type2'] === '1' ? 1 : 0;
$it_type3 = isset($_GET['it_type3']) && $_GET['it_type3'] === '1' ? 1 : 0;
$it_type4 = isset($_GET['it_type4']) && $_GET['it_type4'] === '1' ? 1 : 0;

// 가격 필터 값
$price_range = isset($_REQUEST['price_range']) ? preg_replace('/[^0-9_a-z]/', '', $_REQUEST['price_range']) : '';
$price_min = isset($_REQUEST['price_min']) ? preg_replace('/[^0-9]/', '', $_REQUEST['price_min']) : '';
$price_max = isset($_REQUEST['price_max']) ? preg_replace('/[^0-9]/', '', $_REQUEST['price_max']) : '';

// 가격 필터 SQL 조건
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

// 상품 리스트에서 다른 필드로 정렬을 하려면 아래의 배열 코드에서 해당 필드를 추가하세요.
if (isset($sort) && !in_array($sort, array('it_name', 'it_sum_qty', 'it_price', 'it_use_avg', 'it_use_cnt', 'it_update_time'))) {
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
if (!(isset($ca['ca_id']) && $ca['ca_id']))
    alert('등록된 분류가 없습니다.');

// 테마미리보기 스킨 등의 변수 재설정
if (defined('_THEME_PREVIEW_') && _THEME_PREVIEW_ === true) {
    $ca['ca_skin'] = (isset($tconfig['ca_skin']) && $tconfig['ca_skin']) ? $tconfig['ca_skin'] : $ca['ca_skin'];
    $ca['ca_img_width'] = (isset($tconfig['ca_img_width']) && $tconfig['ca_img_width']) ? $tconfig['ca_img_width'] : $ca['ca_img_width'];
    $ca['ca_img_height'] = (isset($tconfig['ca_img_height']) && $tconfig['ca_img_height']) ? $tconfig['ca_img_height'] : $ca['ca_img_height'];
    $ca['ca_list_mod'] = (isset($tconfig['ca_list_mod']) && $tconfig['ca_list_mod']) ? $tconfig['ca_list_mod'] : $ca['ca_list_mod'];
    $ca['ca_list_row'] = (isset($tconfig['ca_list_row']) && $tconfig['ca_list_row']) ? $tconfig['ca_list_row'] : $ca['ca_list_row'];
}

// 본인인증, 성인인증체크
if (!$is_admin && $config['cf_cert_use']) {
    $msg = shop_member_cert_check($ca_id, 'list');
    if ($msg)
        alert($msg, G5_SHOP_URL);
}

$g5['title'] = $ca['ca_name'] . ' 상품리스트';

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

    // 상품 개수 SQL에 사용할 조건
    $count_where = array();

    // 판매 중인 상품만 조회
    $count_where[] = " it_use = '1' ";

    // 현재 카테고리에 포함된 상품 조회
    $count_where[] = " (
    ca_id like '{$ca['ca_id']}%'
    or ca_id2 like '{$ca['ca_id']}%'
    or ca_id3 like '{$ca['ca_id']}%'
    ) ";

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

    // 가격 조건이 있으면 개수 SQL에도 추가
    if ($price_where) {
        $count_where[] = $price_where;
    }

    // 배열에 담긴 조건을 and로 연결
    $count_sql = " select count(*) as cnt
               from {$g5['g5_shop_item_table']}
               where " . implode(' and ', $count_where);

    // SQL 실행
    $count_row = sql_fetch($count_sql);

    // 조회 결과가 있으면 실제 개수를 넣고, 없으면 0 유지
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
        // 상품 보기 타입 변경 버튼
        $sub_skin = $skin_dir . '/list.sub.skin.php';
        if (!is_file($sub_skin))
            $sub_skin = G5_SHOP_SKIN_PATH . '/list.sub.skin.php';
        include $sub_skin;
        echo '</div>';

        // 총몇개 = 한줄에 몇개 * 몇줄
        $items = $ca['ca_list_mod'] * $ca['ca_list_row'];
        // 페이지가 없으면 첫 페이지 (1 페이지)
        if ($page < 1)
            $page = 1;
        // 시작 레코드 구함
        $from_record = ($page - 1) * $items;

        $list = new item_list(
            $skin_file,
            $ca['ca_list_mod'],
            $ca['ca_list_row'],
            $ca['ca_img_width'],
            $ca['ca_img_height']
        );

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

        // 가격 필터 또는 상품 종류 필터가 있으면 직접 만든 상품 SQL 사용
        if ($price_where || $item_type_where) {
            $list_where = array();

            $list_where[] = " it_use = '1' ";

            $list_where[] = " (
        ca_id like '{$ca['ca_id']}%'
        or ca_id2 like '{$ca['ca_id']}%'
        or ca_id3 like '{$ca['ca_id']}%'
        ) ";

            if ($item_type_where) {
                $list_where[] = $item_type_where;
            }

            if ($price_where) {
                $list_where[] = $price_where;
            }

            $list_sql = " select *
                  from {$g5['g5_shop_item_table']}
                  where " . implode(' and ', $list_where) . "
                  order by {$order_by}
                  limit {$from_record}, {$items} ";

            $list->set_query($list_sql);
        }

        echo $list->run();

        // 직접 만든 SQL을 사용하지 않았을 때만 item_list에서 계산한 개수 사용
        if (!$price_where && !$item_type_where) {
            $total_count = $list->total_count;
        }

        // 전체 페이지 계산
        $total_page = ceil($total_count / $items);
    } else {
        echo '<div class="sct_nofile">' . str_replace(G5_PATH . '/', '', $skin_file) . ' 파일을 찾을 수 없습니다.<br>관리자에게 알려주시면 감사하겠습니다.</div>';
    }

    $qstr1 = 'ca_id=' . $ca_id;
    $qstr1 .= '&amp;sort=' . $sort . '&amp;sortodr=' . $sortodr;

    // 상품 종류 필터 유지
    if ($it_type1)
        $qstr1 .= '&amp;it_type1=1';
    if ($it_type2)
        $qstr1 .= '&amp;it_type2=1';
    if ($it_type3)
        $qstr1 .= '&amp;it_type3=1';
    if ($it_type4)
        $qstr1 .= '&amp;it_type4=1';

    if ($price_range)
        $qstr1 .= '&amp;price_range=' . $price_range;
    if ($price_min)
        $qstr1 .= '&amp;price_min=' . $price_min;
    if ($price_max)
        $qstr1 .= '&amp;price_max=' . $price_max;

    echo get_paging(
        $config['cf_write_pages'],
        $page,
        $total_page,
        $_SERVER['SCRIPT_NAME'] . '?' . $qstr1 . '&amp;page='
    );

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

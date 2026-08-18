<?php
include_once('./_common.php');

$brand_id = isset($_GET['brand_id']) ? trim($_GET['brand_id']) : '';
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

// URL로 전달된 브랜드 ID가 donuts_brand 테이블에 실제로 등록되어 있는지 확인
$sql = " select b.brand_id, c.brand_name
            from donuts_brand as b
            left join donuts_brand_config as c on c.brand_id collate utf8mb4_general_ci = b.brand_id
            where b.brand_id = '" . sql_real_escape_string($brand_id) . "'
            limit 1 ";

$brand = sql_fetch($sql);

if (!isset($brand['brand_id']) || $brand['brand_id'] === '')
    alert('등록된 브랜드가 없습니다.');

// 페이지 제목 용도로만 사용
$g5['title'] = !empty($brand['brand_name']) ? $brand['brand_name'] . ' 상품리스트' : $brand['brand_id'] . ' 상품리스트';

include_once(G5_SHOP_PATH . '/_head.php');

// 스킨경로
$skin_dir = G5_SHOP_SKIN_PATH;

// 브랜드 상품 목록 출력 설정
$list_mod = 2;
$list_row = 4;
$list_img_width = 400;
$list_img_height = 600;

define('G5_SHOP_CSS_URL', str_replace(G5_PATH, G5_URL, $skin_dir));
?>

<script>
    // 브랜드별 상품 목록 보기 방식을 구분하는 쿠키 키로 사용
    // shop.list.js 호환을 위해 기존 변수명 유지
    const itemlist_ca_id = <?php echo json_encode($brand['brand_id'], JSON_UNESCAPED_UNICODE); ?>;
</script>

<script src="<?php echo G5_JS_URL; ?>/shop.list.js"></script>

<!-- 상품 목록 시작 { -->
<div id="sct">

    <?php
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

    // 현재 브랜드에 등록된 상품 조회
    $count_where[] = " it_brand = '" . sql_real_escape_string($brand['brand_id']) . "' ";

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
    $skin_file = $skin_dir . '/brand.10.skin.php';

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

        // 페이지 당 상품 수 
        $items =  $list_mod * $list_row;

        // 페이지가 없으면 첫 페이지 (1 페이지)
        if ($page < 1)
            $page = 1;

        // 시작 레코드
        $from_record = ($page - 1) * $items;

        $list = new item_list(
            $skin_file,
            $list_mod,
            $list_row,
            $list_img_width,
            $list_img_height
        );

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

        // 현재 브랜드 상품을 조회하는 SQL 조건
        $list_where = array();

        $list_where[] = " it_use = '1' ";

        $list_where[] = " it_brand = '" . sql_real_escape_string($brand['brand_id']) . "' ";

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

        echo $list->run();

        // 전체 페이지 계산
        $total_page = ceil($total_count / $items);
    } else {
        echo '<div class="sct_nofile">' . str_replace(G5_PATH . '/', '', $skin_file) . ' 파일을 찾을 수 없습니다.<br>관리자에게 알려주시면 감사하겠습니다.</div>';
    }

    $qstr1 = 'brand_id=' . urlencode($brand['brand_id']);
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
    ?>
</div>
<!-- } 상품 목록 끝 -->

<?php
include_once(G5_SHOP_PATH . '/_tail.php');

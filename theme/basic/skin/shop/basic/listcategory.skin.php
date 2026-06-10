<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$str = '';
$exists = false;

// strlen()은 문자열의 길이를 세는 PHP 함수
// 그누보드 상품 분류는 2자리씩 깊어지는 구조를 사용하기 때문
$ca_id_len = strlen($ca_id);
$len2 = $ca_id_len + 2;
$len4 = $ca_id_len + 4;

// 1차 카테고리 코드만 추출
$depth1_ca_id = substr($ca_id, 0, 2);
$root_cate_str = '';

// 1차 카테고리만 코드와 이름 가져오기
$root_cate_sql = " select ca_id, ca_name
                   from {$g5['g5_shop_category_table']}
                   where length(ca_id) = 2
                     and ca_use = '1'
                   order by ca_order, ca_id ";

$root_cate_result = sql_query($root_cate_sql);

// 1차 카테고리 출력
while ($root_row = sql_fetch_array($root_cate_result)) {
    $is_active = ($depth1_ca_id === $root_row['ca_id']);
    $active_class = $is_active ? 'border-[#f2b705] bg-white text-[#111111]' : 'border-transparent bg-[#f5f5f5] text-[#444444]';

    $root_cate_str .= '
    <li class="shrink-0">
        <a href="' . shop_category_url($root_row['ca_id']) . '" class="flex flex-col items-center gap-3">
            <span class="flex w-14 h-14 pc:w-19 pc:h-19 items-center justify-center rounded-full border-[1.5px] pc:border-2 ' . $active_class . '"></span>
            <span class="text-sm">' . $root_row['ca_name'] . '</span>
        </a>
    </li>
';
}

$depth2_str = '';
$depth2_exists = false;
$current_depth2_ca_id = substr($ca_id, 0, 4);

// 2차 카테고리 SQL
// 1차 카테고리 ca_id 가 포함되고 ca_id 길이가 4 이고 사용 중인 카테고리만 가져온다.
$depth2_sql = " select ca_id, ca_name
                from {$g5['g5_shop_category_table']}
                where ca_id like '{$depth1_ca_id}%'
                  and length(ca_id) = 4
                  and ca_use = '1'
                order by ca_order, ca_id ";
$depth2_result = sql_query($depth2_sql);

$is_depth2_all_active = ($ca_id === $depth1_ca_id);

$depth2_all_active_class = $is_depth2_all_active
    ? 'text-white bg-gray-700 pc:bg-gray-900 font-semibold'
    : 'text-gray-400 bg-gray-700 pc:text-black pc:bg-white';

$depth2_str .= '<li class="pc:w-50 pc:border border-gray-200 rounded ' . $depth2_all_active_class . '"><a href="' . shop_category_url($depth1_ca_id) . '" class="flex items-center justify-center px-5 py-3">전체</a></li>';

while ($depth2_row = sql_fetch_array($depth2_result)) {
    $is_depth2_active = ($current_depth2_ca_id === $depth2_row['ca_id']);
    $depth2_active_class = $is_depth2_active
        ? 'text-white bg-gray-700 pc:bg-gray-900 font-semibold'
        : 'text-gray-400 bg-gray-700 pc:text-black pc:bg-white';

    $depth2_str .= '<li class="pc:w-50 pc:border border-gray-200 pc:rounded mt-[-1px] ml-[-1px] ' . $depth2_active_class . '"><a href="' . shop_category_url($depth2_row['ca_id']) . '" class="flex items-center justify-center px-5 py-3">' . $depth2_row['ca_name'] . '</a></li>';
    $depth2_exists = true;
}

$depth3_str = '';
$depth3_exists = false;
$current_depth3_ca_id = substr($ca_id, 0, 6);

// 3차 카테고리 SQL
$depth3_sql = " select ca_id, ca_name
                from {$g5['g5_shop_category_table']}
                where ca_id like '{$current_depth2_ca_id}%'
                  and length(ca_id) = 6
                  and ca_use = '1'
                order by ca_order, ca_id ";
$depth3_result = sql_query($depth3_sql);

$is_depth3_all_active = ($ca_id === $current_depth2_ca_id);

$depth3_all_active_class = $is_depth3_all_active
    ? 'text-white pc:text-red-500 bg-gray-900 pc:bg-transparent font-semibold'
    : 'bg-gray-200 text-gray-600';

$depth3_str .= '<li class="px-[10px] py-[6px] pc:p-0 rounded pc:rounded-none ' . $depth3_all_active_class . '"><a href="' . shop_category_url($current_depth2_ca_id) . '">전체</a></li>';

while ($depth3_row = sql_fetch_array($depth3_result)) {
    $is_depth3_active = ($current_depth3_ca_id === $depth3_row['ca_id']);
    $depth3_active_class = $is_depth3_active
        ? 'text-white pc:text-red-500 bg-gray-900 pc:bg-transparent font-semibold'
        : 'bg-gray-200 text-gray-600';

    $depth3_str .= '<li class="px-[10px] py-[6px] pc:p-0 rounded pc:rounded-none ' . $depth3_active_class . '"><a href="' . shop_category_url($depth3_row['ca_id']) . '">' . $depth3_row['ca_name'] . '</a></li>';
    $depth3_exists = true;
}

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_SHOP_SKIN_URL . '/style.css">', 0);
?>

<!-- 1차 카테고리 -->
<section class="relative px-5 py-7 bg-white">
    <button type="button" class="list-cate-nav list-cate-prev hidden pc:inline-flex absolute left-4 top-1/2 z-10 -translate-y-1/2 items-center justify-center w-12 h-12 border border-gray-300 rounded-full">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left w-6 h-6 text-gray-300">
            <path d="m15 18-6-6 6-6" />
        </svg>
    </button>

    <div class="list-cate-scroller overflow-x-auto">
        <ul class="flex items-center gap-[30px] min-w-max pc:ml-16">
            <?php echo $root_cate_str; ?>
        </ul>
    </div>

    <button type="button" class="list-cate-nav list-cate-next hidden pc:inline-flex absolute right-4 top-1/2 z-10 -translate-y-1/2 items-center justify-center w-12 h-12 border border-gray-300 rounded-full">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right w-6 h-6 text-gray-300">
            <path d="m9 18 6-6-6-6" />
        </svg>
    </button>
</section>

<!-- 2차 카테고리 -->
<aside id="sct_ct_2" class="sct_ct !text-sm overflow-x-auto">
    <h2 class="sound_only">2차 카테고리</h2>
    <ul class="flex items-center pc:flex-wrap text-nowrap w-full bg-gray-700 pc:bg-white pc:[&>li:nth-child(6n+7)]:ml-0">
        <?php echo $depth2_str; ?>
    </ul>
</aside>

<!-- 3차 카테고리 -->
<aside id="sct_ct_3" class="sct_ct text-zinc-500 !text-sm overflow-x-auto pc:mt-4">
    <h2 class="sound_only">3차 카테고리</h2>
    <ul class="flex items-center gap-2 pc:gap-6 px-4 pc:px-6 py-[14px] pc:py-3 bg-white pc:bg-zinc-200 text-nowrap">
        <?php echo $depth3_str; ?>
    </ul>
</aside>
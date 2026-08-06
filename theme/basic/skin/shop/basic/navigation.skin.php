<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$navi_datas = $ca_ids = array();
$is_item_view = (isset($it_id) && isset($it) && isset($it['it_id']) && $it_id === $it['it_id']) ? true : false;

if (!$is_item_view && $ca_id) {
    $navi_datas = get_shop_navigation_data(true, $ca_id);
    $ca_ids = array(
        'ca_id' => substr($ca_id, 0, 2),
        'ca_id2' => substr($ca_id, 0, 4),
        'ca_id3' => substr($ca_id, 0, 6),
    );
} else if ($is_item_view && isset($it) && is_array($it)) {
    $navi_datas = get_shop_navigation_data(true, $it['ca_id']);
    $ca_ids = array(
        'ca_id' => substr($it['ca_id'], 0, 2),
        'ca_id2' => substr($it['ca_id'], 0, 4),
        'ca_id3' => substr($it['ca_id'], 0, 6)
    );
}

// $location_class = array();
// if ($is_item_view) {
//     $location_class[] = 'view_location';    // view_location는 리스트 말고 상품보기에서만 표시
// } else {
//     $location_class[] = 'is_list is_right';    // view_location는 리스트 말고 상품보기에서만 표시
// }

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_SHOP_CSS_URL . '/style.css">', 0);
// add_javascript('<script src="' . G5_JS_URL . '/shop.category.navigation.js"></script>', 10);
?>

<nav id="sct_location" class="<?php //echo implode(' ', $location_class); ?> flex items-center gap-2 px-4 pc:px-6 py-3 text-sm text-gray-500 font-medium bg-zinc-100"> <!-- class="view_location" --> <!-- view_location는 리스트 말고 상품보기에서만 표시 -->
    <a href='<?php echo G5_SHOP_URL; ?>/' class="go_home">
        <span>도너츠 홈</span>
    </a>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-4">
        <path d="m9 18 6-6-6-6" />
    </svg>
    <?php if (is_array($navi_datas) && $navi_datas) { ?>

        <?php if (isset($navi_datas[0]) && $navi_datas[0]) { ?>
            <?php foreach ((array) $navi_datas[0] as $data) { ?>
                <?php if ($ca_ids['ca_id'] === $data['ca_id']) { ?>
                    <a href="<?php echo $data['url']; ?>" class="last:text-red-400 last:font-semibold"><?php echo get_text($data['ca_name']); ?></a>
                <?php } ?>
            <?php } ?>
        <?php } ?>

        <?php if (isset($navi_datas[1]) && $navi_datas[1]) { ?>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-4" aria-hidden="true">
                <path d="m9 18 6-6-6-6" />
            </svg>
            <?php foreach ((array) $navi_datas[1] as $data) { ?>
                <?php if ($ca_ids['ca_id2'] === $data['ca_id']) { ?>
                    <a href="<?php echo $data['url']; ?>" class="last:text-red-400 last:font-semibold"><?php echo get_text($data['ca_name']); ?></a>
                <?php } ?>
            <?php } ?>
        <?php } ?>

        <?php if (isset($navi_datas[2]) && $navi_datas[2]) { ?>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-4" aria-hidden="true">
                <path d="m9 18 6-6-6-6" />
            </svg>
            <?php foreach ((array) $navi_datas[2] as $data) { ?>
                <?php if ($ca_ids['ca_id3'] === $data['ca_id']) { ?>
                    <a href="<?php echo $data['url']; ?>" class="last:text-red-400 last:font-semibold"><?php echo get_text($data['ca_name']); ?></a>
                <?php } ?>
            <?php } ?>

        <?php } ?>
    <?php } else { ?>
        <?php echo get_text($g5['title']); ?>
    <?php } ?>
</nav>
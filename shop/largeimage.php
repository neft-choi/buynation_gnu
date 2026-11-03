<?php
include_once('./_common.php');

$it_id = isset($_GET['it_id']) ? get_search_string(trim($_GET['it_id'])) : '';
$no = (isset($_GET['no']) && $_GET['no']) ? (int) $_GET['no'] : 1;
$is_id = isset($_GET['is_id']) ? (int) $_GET['is_id'] : 0;

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH . '/largeimage.php');
    return;
}

$review_image_sources = array();

if ($is_id > 0) {
    $review = sql_fetch(" select it_id, is_content from `{$g5['g5_shop_item_use_table']}` where is_id = '{$is_id}' and is_confirm = '1' ");
    if (empty($review['it_id'])) alert_close('리뷰정보가 존재하지 않습니다.');

    $it_id = $review['it_id'];
    $review_image_sources = get_editor_image(conv_content($review['is_content'], 1), false);
    if (empty($review_image_sources[1])) alert_close('리뷰 이미지가 존재하지 않습니다.');
}

$row = get_shop_item($it_id, true);

if (! (isset($row['it_id']) && $row['it_id']))
    alert_close('상품정보가 존재하지 않습니다.');

if ($is_id > 0) {
    $size = array();
} else {
    $imagefile = G5_DATA_PATH . '/item/' . $row['it_img' . $no];
    $imagefileurl = run_replace('get_item_image_url', G5_DATA_URL . '/item/' . $row['it_img' . $no], $row, $no);
    $size = file_exists($imagefile) ? @getimagesize($imagefile) : array();
}

$g5['title'] = "{$row['it_name']} ($it_id)";
include_once(G5_PATH . '/head.sub.php');

$skin = G5_SHOP_SKIN_PATH . '/largeimage.skin.php';

if (is_file($skin))
    include_once($skin);
else
    echo '<p>' . str_replace(G5_PATH . '/', '', $skin) . '파일이 존재하지 않습니다.</p>';

include_once(G5_PATH . '/tail.sub.php');

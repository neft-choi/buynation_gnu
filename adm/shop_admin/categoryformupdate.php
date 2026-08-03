<?php
$sub_menu = '400200';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "w");

$ca_include_head = isset($_POST['ca_include_head']) ? trim($_POST['ca_include_head']) : '';
$ca_include_tail = isset($_POST['ca_include_tail']) ? trim($_POST['ca_include_tail']) : '';
$ca_id = isset($_REQUEST['ca_id']) ? preg_replace('/[^0-9a-z]/i', '', $_REQUEST['ca_id']) : '';

// 카테고리 아이콘 저장 경로
// 첫 번째는 실제 서버 경로, 두 번째는 ca_1에 저장할 웹 주소
$ca_icon_dir = G5_DATA_PATH . '/adm/icons';
$ca_icon_url = G5_DATA_URL . '/adm/icons';

if (!$ca_id) {
    alert('', G5_SHOP_URL);
}

if ($file = $ca_include_head) {
    $file_ext = pathinfo($file, PATHINFO_EXTENSION);

    if (! $file_ext || ! in_array($file_ext, array('php', 'htm', 'html')) || !preg_match("/\.(php|htm[l]?)$/i", $file)) {
        alert("상단 파일 경로가 php, html 파일이 아닙니다.");
    }
}

if ($file = $ca_include_tail) {
    $file_ext = pathinfo($file, PATHINFO_EXTENSION);

    if (! $file_ext || ! in_array($file_ext, array('php', 'htm', 'html')) || !preg_match("/\.(php|htm[l]?)$/i", $file)) {
        alert("하단 파일 경로가 php, html 파일이 아닙니다.");
    }
}

if ($ca_id) {
    $sql = " select * from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' ";
    $ca = sql_fetch($sql);

    if ($ca && ($ca['ca_include_head'] !== $ca_include_head || $ca['ca_include_tail'] !== $ca_include_tail) && function_exists('get_admin_captcha_by') && get_admin_captcha_by()) {
        include_once(G5_CAPTCHA_PATH . '/captcha.lib.php');

        if (!chk_captcha()) {
            alert('자동등록방지 숫자가 틀렸습니다.');
        }
    }
}

$check_str_keys = array(
    'ca_order' => 'int',
    'ca_img_width' => 'int',
    'ca_img_height' => 'int',
    'ca_name' => 'str',
    'ca_mb_id' => 'str',
    'ca_nocoupon' => 'str',
    'ca_mobile_skin_dir' => 'str',
    'ca_skin' => 'str',
    'ca_mobile_skin' => 'str',
    'ca_list_mod' => 'int',
    'ca_list_row' => 'int',
    'ca_mobile_img_width' => 'int',
    'ca_mobile_img_height' => 'int',
    'ca_mobile_list_mod' => 'int',
    'ca_mobile_list_row' => 'int',
    'ca_sell_email' => 'str',
    'ca_use' => 'int',
    'ca_stock_qty' => 'int',
    'ca_explan_html' => 'int',
    'ca_cert_use' => 'int',
    'ca_adult_use' => 'int',
    'ca_skin_dir' => 'str'
);

for ($i = 0; $i <= 10; $i++) {
    $check_str_keys['ca_' . $i . '_subj'] = 'str';
    $check_str_keys['ca_' . $i] = 'str';
}

foreach ($check_str_keys as $key => $val) {
    if ($val === 'int') {
        $value = isset($_POST[$key]) ? (int) $_POST[$key] : 0;
    } else {
        $value = isset($_POST[$key]) ? clean_xss_tags($_POST[$key], 1, 1) : '';
    }
    $$key = $_POST[$key] = $value;
}

// 카테고리 아이콘을 업로드 했는지 확인
if ($_FILES['ca_icon']['name']) {

    // 아이콘 저장 폴더가 없으면 data/adm/icons 폴더를 생성한다
    if (!is_dir($ca_icon_dir)) {
        @mkdir($ca_icon_dir, G5_DIR_PERMISSION, true);
        @chmod($ca_icon_dir, G5_DIR_PERMISSION);
    }

    // 업로드한 파일 이름에서 확장자를 가져온다
    // strtolower()는 영문 대문자를 소문자로 바꿔준다
    $ca_icon_ext = strtolower(pathinfo($_FILES['ca_icon']['name'], PATHINFO_EXTENSION));

    // 이미지 확장자
    if (!in_array($ca_icon_ext, array('jpg', 'jpeg', 'png', 'gif', 'webp'))) {
        alert('아이콘은 JPG, JPEG, PNG, GIF, WEBP 파일만 업로드할 수 있습니다.');
    }

    // 저장할 파일 이름을 분류 코드와 확장자로 새로 정해준다.
    $ca_icon_name = strtolower($ca_id) . '.' . $ca_icon_ext;

    // 새 아이콘 파일이 저장될 실제 서버 경로
    $ca_icon_path = $ca_icon_dir . '/' . $ca_icon_name;

    // $_FILES['ca_icon']['tmp_name']은 PHP가 잠시 보관한 업로드 파일
    upload_file($_FILES['ca_icon']['tmp_name'], $ca_icon_name, $ca_icon_dir);

    // 새 아이콘 파일이 실제로 저장되지 않았다면 DB 주소를 바꾸지 않고 중단
    if (!is_file($ca_icon_path)) {
        alert('아이콘 파일 저장에 실패했습니다.');
    }

    // DB에 저장된 기존 아이콘 주소를 가져온다
    $old_ca_icon_url = isset($ca['ca_1']) ? $ca['ca_1'] : '';

    if ($old_ca_icon_url && strpos($old_ca_icon_url, $ca_icon_url) === 0) {
        $old_ca_icon_path = $ca_icon_dir . '/' . basename($old_ca_icon_url);

        // 기존 파일과 새 파일의 경로가 다를 때만 이전 아이콘을 삭제한다
        if ($old_ca_icon_path !== $ca_icon_path && is_file($old_ca_icon_path)) {
            if (!@unlink($old_ca_icon_path)) {
                alert('기존 아이콘 파일 삭제에 실패했습니다.');
            }
        }
    }

    // DB에 저장할 주소
    $ca_1 = $ca_icon_url . '/' . $ca_icon_name;
}

$ca_head_html = isset($_POST['ca_head_html']) ? $_POST['ca_head_html'] : '';
$ca_tail_html = isset($_POST['ca_tail_html']) ? $_POST['ca_tail_html'] : '';
$ca_mobile_head_html = isset($_POST['ca_mobile_head_html']) ? $_POST['ca_mobile_head_html'] : '';
$ca_mobile_tail_html = isset($_POST['ca_mobile_tail_html']) ? $_POST['ca_mobile_tail_html'] : '';

if (!is_include_path_check($ca_include_head, 1)) {
    alert('상단 파일 경로에 포함시킬수 없는 문자열이 있습니다.');
}

if (!is_include_path_check($ca_include_tail, 1)) {
    alert('하단 파일 경로에 포함시킬수 없는 문자열이 있습니다.');
}

$check_keys = array('ca_skin_dir', 'ca_mobile_skin_dir', 'ca_skin', 'ca_mobile_skin');

foreach ($check_keys as $key) {
    if (isset($$key) && preg_match('#\.+(\/|\\\)#', $$key)) {
        alert('스킨명 또는 경로에 포함시킬수 없는 문자열이 있습니다.');
    }
}

if (function_exists('filter_input_include_path')) {
    $ca_include_head = filter_input_include_path($ca_include_head);
    $ca_include_tail = filter_input_include_path($ca_include_tail);
}

if ($w == "u" || $w == "d")
    check_demo();

auth_check_menu($auth, $sub_menu, "d");

check_admin_token();

if ($w == 'd' && $is_admin != 'super')
    alert("최고관리자만 분류를 삭제할 수 있습니다.");

if ($w == "" || $w == "u") {
    if ($ca_mb_id) {
        $sql = " select mb_id from {$g5['member_table']} where mb_id = '$ca_mb_id' ";
        $row = sql_fetch($sql);
        if (!$row['mb_id'])
            alert("\'$ca_mb_id\' 은(는) 존재하는 회원아이디가 아닙니다.");
    }
}

if ($ca_skin && ! is_include_path_check($ca_skin)) {
    alert('오류 : 데이터폴더가 포함된 path 를 포함할수 없습니다.');
}

$sql_common = " ca_order                = '$ca_order',
                ca_skin_dir             = '$ca_skin_dir',
                ca_mobile_skin_dir      = '$ca_mobile_skin_dir',
                ca_skin                 = '$ca_skin',
                ca_mobile_skin          = '$ca_mobile_skin',
                ca_img_width            = '$ca_img_width',
                ca_img_height           = '$ca_img_height',
				ca_list_mod             = '$ca_list_mod',
				ca_list_row             = '$ca_list_row',
                ca_mobile_img_width     = '$ca_mobile_img_width',
                ca_mobile_img_height    = '$ca_mobile_img_height',
				ca_mobile_list_mod      = '$ca_mobile_list_mod',
                ca_mobile_list_row      = '$ca_mobile_list_row',
                ca_sell_email           = '$ca_sell_email',
                ca_use                  = '$ca_use',
                ca_stock_qty            = '$ca_stock_qty',
                ca_explan_html          = '$ca_explan_html',
                ca_head_html            = '$ca_head_html',
                ca_tail_html            = '$ca_tail_html',
                ca_mobile_head_html     = '$ca_mobile_head_html',
                ca_mobile_tail_html     = '$ca_mobile_tail_html',
                ca_include_head         = '$ca_include_head',
                ca_include_tail         = '$ca_include_tail',
                ca_mb_id                = '$ca_mb_id',
                ca_cert_use             = '$ca_cert_use',
                ca_adult_use            = '$ca_adult_use',
                ca_nocoupon             = '$ca_nocoupon',
                ca_1_subj               = '$ca_1_subj',
                ca_2_subj               = '$ca_2_subj',
                ca_3_subj               = '$ca_3_subj',
                ca_4_subj               = '$ca_4_subj',
                ca_5_subj               = '$ca_5_subj',
                ca_6_subj               = '$ca_6_subj',
                ca_7_subj               = '$ca_7_subj',
                ca_8_subj               = '$ca_8_subj',
                ca_9_subj               = '$ca_9_subj',
                ca_10_subj              = '$ca_10_subj',
                ca_1                    = '$ca_1',
                ca_2                    = '$ca_2',
                ca_3                    = '$ca_3',
                ca_4                    = '$ca_4',
                ca_5                    = '$ca_5',
                ca_6                    = '$ca_6',
                ca_7                    = '$ca_7',
                ca_8                    = '$ca_8',
                ca_9                    = '$ca_9',
                ca_10                   = '$ca_10' ";


if ($w == "") {
    if (!trim($ca_id))
        alert("분류 코드가 없으므로 분류를 추가하실 수 없습니다.");

    // 소문자로 변환
    $ca_id = strtolower($ca_id);

    $sql = " insert {$g5['g5_shop_category_table']}
                set ca_id   = '$ca_id',
                    ca_name = '$ca_name',
                    $sql_common ";
    sql_query($sql);
    run_event('shop_admin_category_created', $ca_id);
} else if ($w == "u") {
    $sql = " update {$g5['g5_shop_category_table']}
                set ca_name = '$ca_name',
                    $sql_common
              where ca_id = '$ca_id' ";
    sql_query($sql);

    // 하위분류를 똑같은 설정으로 반영
    if (isset($_POST['sub_category']) && $_POST['sub_category']) {
        $len = strlen($ca_id);
        $sql = " update {$g5['g5_shop_category_table']}
                    set $sql_common
                  where SUBSTRING(ca_id,1,$len) = '$ca_id' ";
        if ($is_admin != 'super')
            $sql .= " and ca_mb_id = '{$member['mb_id']}' ";
        sql_query($sql);
    }
    run_event('shop_admin_category_updated', $ca_id);
} else if ($w == "d") {
    // 분류의 길이
    $len = strlen($ca_id);

    $sql = " select COUNT(*) as cnt from {$g5['g5_shop_category_table']}
              where SUBSTRING(ca_id,1,$len) = '$ca_id'
                and ca_id <> '$ca_id' ";
    $row = sql_fetch($sql);
    if ($row['cnt'] > 0)
        alert("이 분류에 속한 하위 분류가 있으므로 삭제 할 수 없습니다.\\n\\n하위분류를 우선 삭제하여 주십시오.");

    $str = $comma = "";
    $sql = " select it_id from {$g5['g5_shop_item_table']} where ca_id = '$ca_id' ";
    $result = sql_query($sql);
    $i = 0;
    while ($row = sql_fetch_array($result)) {
        $i++;
        if ($i % 10 == 0) $str .= "\\n";
        $str .= "$comma{$row['it_id']}";
        $comma = " , ";
    }

    if ($str)
        alert("이 분류와 관련된 상품이 총 {$i} 건 존재하므로 상품을 삭제한 후 분류를 삭제하여 주십시오.\\n\\n$str");

    // 분류 삭제
    $sql = " delete from {$g5['g5_shop_category_table']} where ca_id = '$ca_id' ";
    sql_query($sql);
    run_event('shop_admin_category_deleted', $ca_id);
}

if (function_exists('get_admin_captcha_by'))
    get_admin_captcha_by('remove');

if ($w == "" || $w == "u") {
    goto_url("./categoryform.php?w=u&amp;ca_id=$ca_id&amp;$qstr");
} else {
    goto_url("./categorylist.php?$qstr");
}

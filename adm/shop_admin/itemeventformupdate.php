<?php
$sub_menu = '500300';
include_once('./_common.php');

if ($w == "u" || $w == "d")
    check_demo();

if ($w == 'd')
    auth_check_menu($auth, $sub_menu, "d");
else
    auth_check_menu($auth, $sub_menu, "w");

check_admin_token();

/* 브랜드별 기획전 소유자 컬럼 */
if (!sql_query(" select ev_seller from {$g5['g5_shop_event_table']} limit 1 ", false)) {
    sql_query(" ALTER TABLE `{$g5['g5_shop_event_table']}` ADD `ev_seller` varchar(20) NOT NULL DEFAULT '' AFTER `ev_id` ", true);
}

$event_member_id = isset($member['mb_id']) ? trim((string)$member['mb_id']) : '';
$event_member_sql = sql_real_escape_string($event_member_id);

/* 수정/삭제는 최고관리자 외에는 본인 기획전만 허용 */
if ($is_admin != 'super' && ($w == 'u' || $w == 'd')) {
    $check_ev_id = preg_replace('/[^0-9]/', '', (string)$ev_id);
    $event_owner = sql_fetch(" select ev_id from {$g5['g5_shop_event_table']}
        where ev_id = '{$check_ev_id}'
          and LOWER(TRIM(ev_seller)) = LOWER('{$event_member_sql}')
        limit 1 ");
    if (empty($event_owner['ev_id'])) {
        alert('수정 또는 삭제 권한이 없는 기획전입니다.');
    }
}

@mkdir(G5_DATA_PATH."/event", G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH."/event", G5_DIR_PERMISSION);

$ev_mimg_del = isset($_POST['ev_mimg_del']) ? (int) $_POST['ev_mimg_del'] : 0;
$ev_himg_del = isset($_POST['ev_himg_del']) ? (int) $_POST['ev_himg_del'] : 0;
$ev_timg_del = isset($_POST['ev_timg_del']) ? (int) $_POST['ev_timg_del'] : 0;

$ev_skin = isset($_POST['ev_skin']) ? clean_xss_tags($_POST['ev_skin'], 1, 1) : '';
$ev_mobile_skin = isset($_POST['ev_mobile_skin']) ? clean_xss_tags($_POST['ev_mobile_skin'], 1, 1) : '';

$ev_img_width = isset($_POST['ev_img_width']) ? (int) $_POST['ev_img_width'] : 0;
$ev_img_height = isset($_POST['ev_img_height']) ? (int) $_POST['ev_img_height'] : 0;
$ev_list_mod = isset($_POST['ev_list_mod']) ? (int) $_POST['ev_list_mod'] : 0;
$ev_list_row = isset($_POST['ev_list_row']) ? (int) $_POST['ev_list_row'] : 0;
$ev_mobile_img_width = isset($_POST['ev_mobile_img_width']) ? (int) $_POST['ev_mobile_img_width'] : 0;
$ev_mobile_img_height = isset($_POST['ev_mobile_img_height']) ? (int) $_POST['ev_mobile_img_height'] : 0;
$ev_mobile_list_mod = isset($_POST['ev_mobile_list_mod']) ? (int) $_POST['ev_mobile_list_mod'] : 0;
$ev_mobile_list_row = isset($_POST['ev_mobile_list_row']) ? (int) $_POST['ev_mobile_list_row'] : 0;
$ev_use = isset($_POST['ev_use']) ? (int) $_POST['ev_use'] : 0;
$ev_subject_strong = isset($_POST['ev_subject_strong']) ? (int) $_POST['ev_subject_strong'] : 0;

$ev_subject = isset($_POST['ev_subject']) ? clean_xss_tags($_POST['ev_subject'], 1, 1) : '';
$ev_head_html = isset($_POST['ev_head_html']) ? $_POST['ev_head_html'] : '';
$ev_tail_html = isset($_POST['ev_tail_html']) ? $_POST['ev_tail_html'] : '';

if ($ev_mimg_del)  @unlink(G5_DATA_PATH."/event/{$ev_id}_m");
if ($ev_himg_del)  @unlink(G5_DATA_PATH."/event/{$ev_id}_h");
if ($ev_timg_del)  @unlink(G5_DATA_PATH."/event/{$ev_id}_t");

$ev_skin = preg_replace(array('#\.+(\/|\\\)#', '#[\'\"]#'), array('', ''), $ev_skin);
$ev_mobile_skin = preg_replace(array('#\.+(\/|\\\)#', '#[\'\"]#'), array('', ''), $ev_mobile_skin);

$skin_regex_patten = "^list.[0-9]+\.skin\.php";

$ev_skin = (preg_match("/$skin_regex_patten/", $ev_skin) && file_exists(G5_SHOP_SKIN_PATH.'/'.$ev_skin)) ? $ev_skin : ''; 
$ev_mobile_skin = (preg_match("/$skin_regex_patten/", $ev_mobile_skin) && file_exists(G5_MSHOP_SKIN_PATH.'/'.$ev_mobile_skin)) ? $ev_mobile_skin : ''; 
$ev_subject = strip_tags($ev_subject);

$sql_common = " set ev_skin             = '$ev_skin',
                    ev_mobile_skin      = '$ev_mobile_skin',
                    ev_img_width        = '$ev_img_width',
                    ev_img_height       = '$ev_img_height',
                    ev_list_mod         = '$ev_list_mod',
                    ev_list_row         = '$ev_list_row',
                    ev_mobile_img_width = '$ev_mobile_img_width',
                    ev_mobile_img_height= '$ev_mobile_img_height',
                    ev_mobile_list_mod  = '$ev_mobile_list_mod',
                    ev_mobile_list_row  = '$ev_mobile_list_row',
                    ev_subject          = '$ev_subject',
                    ev_head_html        = '$ev_head_html',
                    ev_tail_html        = '$ev_tail_html',
                    ev_use              = '$ev_use',
                    ev_subject_strong   = '$ev_subject_strong'
                    ";

if ($w == "")
{
    $ev_id = G5_SERVER_TIME;

    $event_seller = ($is_admin == 'super' && !empty($_POST['ev_seller']))
        ? trim((string)$_POST['ev_seller'])
        : $event_member_id;
    $event_seller_sql = sql_real_escape_string($event_seller);

    $sql = " insert {$g5['g5_shop_event_table']}
                    $sql_common
                  , ev_id = '$ev_id'
                  , ev_seller = '$event_seller_sql' ";
    sql_query($sql);
    run_event('shop_admin_event_created', $ev_id);
}
else if ($w == "u")
{
    $sql = " update {$g5['g5_shop_event_table']}
                $sql_common
              where ev_id = '$ev_id' ";
    if ($is_admin != 'super') {
        $sql .= " and LOWER(TRIM(ev_seller)) = LOWER('{$event_member_sql}') ";
    }
    sql_query($sql);
    run_event('shop_admin_event_updated', $ev_id);
}
else if ($w == "d")
{
    @unlink(G5_DATA_PATH."/event/{$ev_id}_m");
    @unlink(G5_DATA_PATH."/event/{$ev_id}_h");
    @unlink(G5_DATA_PATH."/event/{$ev_id}_t");

    // 이벤트상품삭제
    $sql = " delete from {$g5['g5_shop_event_item_table']} where ev_id = '$ev_id' ";
    sql_query($sql);
    run_event('shop_admin_event_deleted', $ev_id);

    $sql = " delete from {$g5['g5_shop_event_table']} where ev_id = '$ev_id' ";
    if ($is_admin != 'super') {
        $sql .= " and LOWER(TRIM(ev_seller)) = LOWER('{$event_member_sql}') ";
    }
    sql_query($sql);
}

if ($w == "" || $w == "u")
{
    if ($_FILES['ev_mimg']['name']) upload_file($_FILES['ev_mimg']['tmp_name'], $ev_id."_m", G5_DATA_PATH."/event");
    if ($_FILES['ev_himg']['name']) upload_file($_FILES['ev_himg']['tmp_name'], $ev_id."_h", G5_DATA_PATH."/event");
    if ($_FILES['ev_timg']['name']) upload_file($_FILES['ev_timg']['tmp_name'], $ev_id."_t", G5_DATA_PATH."/event");

    // 등록된 이벤트 상품 먼저 삭제
    $sql = " delete from {$g5['g5_shop_event_item_table']} where ev_id = '$ev_id' ";
    sql_query($sql);

    // 이벤트 상품등록
    $item = explode(',', $ev_item);
    $count = count($item);

    for($i=0; $i<$count; $i++) {
        $it_id = isset($item[$i]) ? $item[$i] : '';
        if($it_id) {
            $it_id = preg_replace('/[^A-Za-z0-9_\-]/', '', (string)$it_id);
            if ($it_id === '') {
                continue;
            }

            /* POST 변조 방지: 브랜드 회원은 자기 it_seller 상품만 등록 */
            if ($is_admin != 'super') {
                $it_id_sql = sql_real_escape_string($it_id);
                $owned_item = sql_fetch(" select it_id from {$g5['g5_shop_item_table']}
                    where it_id = '{$it_id_sql}'
                      and LOWER(TRIM(it_seller)) = LOWER('{$event_member_sql}')
                    limit 1 ");
                if (empty($owned_item['it_id'])) {
                    continue;
                }
            }

            $sql = " insert into {$g5['g5_shop_event_item_table']}
                        set ev_id = '$ev_id',
                            it_id = '$it_id' ";
            sql_query($sql);
        }
    }

    goto_url("./itemeventform.php?w=u&amp;ev_id=$ev_id");
}
else
{
    goto_url("./itemevent.php");
}
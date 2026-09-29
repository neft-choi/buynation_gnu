<?php
$sub_menu = '500300';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "w");

$event_member_id = isset($member['mb_id']) ? trim((string)$member['mb_id']) : '';
$event_member_sql = sql_real_escape_string($event_member_id);

/* 수정 중인 기획전도 본인 소유인지 확인 */
if ($is_admin != 'super' && $w == 'u' && !empty($ev_id)) {
    $event_ev_id = preg_replace('/[^0-9]/', '', (string)$ev_id);
    $event_owner = sql_fetch(" select ev_id from {$g5['g5_shop_event_table']}
        where ev_id = '{$event_ev_id}'
          and LOWER(TRIM(ev_seller)) = LOWER('{$event_member_sql}')
        limit 1 ");
    if (empty($event_owner['ev_id'])) {
        die('<p>기획전 상품을 수정할 권한이 없습니다.</p>');
    }
}

$ca_id = trim($ca_id);
$it_name = trim(strip_tags($it_name));

if(!$ca_id && !$it_name)
    die('<p>상품의 분류를 선택하시거나 상품명을 입력하신 후 검색하여 주십시오.</p>');

$sql = " select it_id, it_name
           from {$g5['g5_shop_item_table']}
          where (1) ";
if ($is_admin != 'super') {
    $sql .= " and LOWER(TRIM(it_seller)) = LOWER('{$event_member_sql}') ";
}
if($ca_id)
    $sql .= " and ( ca_id like '$ca_id%' or ca_id2 like '$ca_id%' or ca_id3 like '$ca_id%' ) ";

if($it_name)
    $sql .= " and it_name like '%$it_name%' ";

$sql .= " order by ca_id, it_name ";
$result = sql_query($sql);

$list = '';
for($i=0;$row=sql_fetch_array($result);$i++) {
    if($w == 'u') {
        $sql2 = " select count(*) as cnt from {$g5['g5_shop_event_item_table']} where ev_id = '$ev_id' and it_id = '{$row['it_id']}' ";
        $row2 = sql_fetch($sql2);
        if ($row2['cnt'])
            continue;
    }

    $it_name = get_it_image($row['it_id'], 50, 50).' '.$row['it_name'];

    $list .= '<li>';
    $list .= '<input type="hidden" name="it_id[]" value="'.$row['it_id'].'">';
    $list .= '<div class="list_item">'.$it_name.'</div>';
    $list .= '<div class="list_item_btn"><button type="button" class="add_item btn btn_04">추가</button></div>';
    $list .= '</li>'.PHP_EOL;
}

if($list)
    $list = '<ul>'.$list.'</ul>';
else
    $list = '<p>등록된 상품이 없습니다.</p>';

echo $list;
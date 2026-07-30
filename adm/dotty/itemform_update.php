<?php
$sub_menu = '710200';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "w");
check_demo();
check_admin_token();

if (!$is_member) {
    alert('로그인 후 이용해 주세요.', G5_URL);
}

$dotty_mb_id = $member['mb_id'];
$dotty_mb_id_sql = sql_real_escape_string($dotty_mb_id);

$it_id = isset($_POST['it_id'])
    ? preg_replace('/[^A-Za-z0-9_\-]/', '', $_POST['it_id'])
    : '';

$item_type = isset($_POST['item_type'])
    ? trim($_POST['item_type'])
    : 'all';

if ($item_type !== 'all') {
    alert('현재는 전체 상품 등록만 지원합니다.');
}

if ($it_id === '') {
    alert('등록할 상품이 없습니다.');
}

// 실제 상품 존재/판매여부 확인
$item = sql_fetch("
    SELECT it_id, it_name, it_price, it_use
    FROM {$g5['g5_shop_item_table']}
    WHERE it_id = '" . sql_real_escape_string($it_id) . "'
    LIMIT 1
");

if (empty($item['it_id'])) {
    alert('존재하지 않는 상품입니다.');
}

if (!(int)$item['it_use']) {
    alert('현재 판매중인 상품만 등록할 수 있습니다.');
}

// 도티 설정값 스냅샷
$setting = sql_fetch("
    SELECT contribution_rate, discount_rate
    FROM donuts_dotty_settings
    WHERE mb_id = '{$dotty_mb_id_sql}'
    LIMIT 1
");

$contribution_rate = isset($setting['contribution_rate'])
    ? (float)$setting['contribution_rate']
    : 0;

$discount_rate = isset($setting['discount_rate'])
    ? (float)$setting['discount_rate']
    : 0;

// 중복 등록 방지
$exists = sql_fetch("
    SELECT id
    FROM donuts_dotty_items
    WHERE dotty_mb_id = '{$dotty_mb_id_sql}'
      AND it_id = '" . sql_real_escape_string($it_id) . "'
    LIMIT 1
");

if (!empty($exists['id'])) {
    alert('이미 등록된 상품입니다.', './item_list.php');
}

// 현재 최대 정렬순서 뒤에 추가
$row = sql_fetch("
    SELECT COALESCE(MAX(sort_order), 0) AS max_order
    FROM donuts_dotty_items
    WHERE dotty_mb_id = '{$dotty_mb_id_sql}'
");

$sort_order = (int)$row['max_order'] + 10;

$sql = "
    INSERT INTO donuts_dotty_items
    SET
        dotty_mb_id = '{$dotty_mb_id_sql}',
        it_id = '" . sql_real_escape_string($it_id) . "',
        item_type = 'all',
        contribution_rate = '{$contribution_rate}',
        discount_rate = '{$discount_rate}',
        sort_order = '{$sort_order}',
        use_yn = 'Y',
        created_at = NOW(),
        updated_at = NOW()
";

sql_query($sql);

alert('상품이 내 도티 페이지에 등록되었습니다.', './item_list.php');

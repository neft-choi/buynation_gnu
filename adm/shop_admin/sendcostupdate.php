<?php
$sub_menu = '400750';
include_once('./_common.php');

check_demo();

auth_check_menu($auth, $sub_menu, "w");

check_admin_token();

// ---------------------------------------------------------
// 브랜드별 추가배송비 관리 대상 결정
// ---------------------------------------------------------
sql_query("
    CREATE TABLE IF NOT EXISTS donuts_brand_sendcost (
        sc_id INT NOT NULL AUTO_INCREMENT,
        brand_id VARCHAR(255) NOT NULL DEFAULT '',
        sc_name VARCHAR(255) NOT NULL DEFAULT '',
        sc_zip1 VARCHAR(10) NOT NULL DEFAULT '',
        sc_zip2 VARCHAR(10) NOT NULL DEFAULT '',
        sc_price INT NOT NULL DEFAULT 0,
        reg_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        update_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (sc_id),
        KEY idx_brand_id (brand_id),
        KEY idx_brand_zip (brand_id, sc_zip1, sc_zip2)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
", false);

$current_mb_id = isset($member['mb_id']) ? trim((string)$member['mb_id']) : '';
$current_mb_id_sql = sql_real_escape_string($current_mb_id);

$brand = sql_fetch("
    SELECT brand_id
    FROM donuts_brand
    WHERE TRIM(brand_id) = '{$current_mb_id_sql}'
    LIMIT 1
");

$is_brand = !empty($brand['brand_id']);
$requested_brand_id = isset($_POST['brand_id']) ? trim((string)$_POST['brand_id']) : '';

if ($is_brand) {
    $manage_brand_id = trim((string)$brand['brand_id']);
} elseif ($is_admin === 'super') {
    $manage_brand_id = $requested_brand_id;
} else {
    alert('브랜드 계정만 추가배송비를 관리할 수 있습니다.');
}

if ($manage_brand_id === '') {
    alert('브랜드 정보가 올바르지 않습니다.');
}

$brand_exists = sql_fetch("
    SELECT brand_id
    FROM donuts_brand
    WHERE TRIM(brand_id) = '" . sql_real_escape_string($manage_brand_id) . "'
    LIMIT 1
");

if (empty($brand_exists['brand_id'])) {
    alert('등록되지 않은 브랜드입니다.');
}

$brand_id = sql_real_escape_string($manage_brand_id);

$w = isset($_POST['w']) ? trim($_POST['w']) : '';
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
if ($page < 1) $page = 1;

if ($w === 'd') {

    $chk = isset($_POST['chk']) && is_array($_POST['chk'])
        ? $_POST['chk']
        : array();

    if (!$chk) {
        alert('삭제하실 항목을 하나이상 선택해 주십시오.');
    }

    foreach ($chk as $k) {

        $k = (int)$k;

        if (!isset($_POST['sc_id'][$k])) {
            continue;
        }

        $sc_id = (int)$_POST['sc_id'][$k];

        if ($sc_id < 1) {
            continue;
        }
        sql_query("
            DELETE FROM donuts_brand_sendcost
            WHERE sc_id = '{$sc_id}'
              AND brand_id = '{$brand_id}'
        ");
    }

} else {

    $sc_name = isset($_POST['sc_name'])
        ? trim(strip_tags(clean_xss_attributes($_POST['sc_name'])))
        : '';

    $sc_zip1 = isset($_POST['sc_zip1'])
        ? preg_replace('/[^0-9]/', '', $_POST['sc_zip1'])
        : '';

    $sc_zip2 = isset($_POST['sc_zip2'])
        ? preg_replace('/[^0-9]/', '', $_POST['sc_zip2'])
        : '';

    $sc_price = isset($_POST['sc_price'])
        ? preg_replace('/[^0-9]/', '', $_POST['sc_price'])
        : '';

    if (!$sc_name) {
        alert('지역명을 입력해 주십시오.');
    }

    if (!$sc_zip1) {
        alert('우편번호 시작을 입력해 주십시오.');
    }

    if (!$sc_zip2) {
        alert('우편번호 끝을 입력해 주십시오.');
    }

    if ($sc_price === '') {
        alert('추가배송비를 입력해 주십시오.');
    }

    if (strlen($sc_zip1) !== 5 || strlen($sc_zip2) !== 5) {
        alert('우편번호는 5자리 숫자로 입력해 주십시오.');
    }

    if ((int)$sc_zip1 > (int)$sc_zip2) {
        alert('우편번호 시작값은 끝값보다 클 수 없습니다.');
    }

    $sc_name_sql = sql_real_escape_string($sc_name);
    $sc_zip1_sql = sql_real_escape_string($sc_zip1);
    $sc_zip2_sql = sql_real_escape_string($sc_zip2);
    $sc_price_int = (int)$sc_price;

    // 같은 브랜드 안에서 동일한 우편번호 범위가 중복 등록되는지 검사
    $dup = sql_fetch("
        SELECT sc_id
        FROM donuts_brand_sendcost
        WHERE brand_id = '{$brand_id}'
          AND sc_zip1 = '{$sc_zip1_sql}'
          AND sc_zip2 = '{$sc_zip2_sql}'
        LIMIT 1
    ");

    if (!empty($dup['sc_id'])) {
        alert('같은 우편번호 범위가 이미 등록되어 있습니다.');
    }

    sql_query("
        INSERT INTO donuts_brand_sendcost
        SET
            brand_id = '{$brand_id}',
            sc_name = '{$sc_name_sql}',
            sc_zip1 = '{$sc_zip1_sql}',
            sc_zip2 = '{$sc_zip2_sql}',
            sc_price = '{$sc_price_int}',
            reg_date = NOW(),
            update_date = NOW()
    ");
}

goto_url('./sendcostlist.php?brand_id=' . urlencode($manage_brand_id) . '&page=' . $page);

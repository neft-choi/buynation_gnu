<?php
$sub_menu = '400750';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "r");

/*
 * 브랜드별 추가배송비 관리
 * - donuts_brand에 등록된 계정은 자기 브랜드 데이터만 조회
 * - 최고관리자는 brand_id 파라미터로 브랜드를 선택해서 조회
 */
sql_query("
    CREATE TABLE IF NOT EXISTS donuts_brand_sendcost (
        sc_id INT NOT NULL AUTO_INCREMENT,
        brand_id VARCHAR(255) NOT NULL DEFAULT '',
        sc_name VARCHAR(255) NOT NULL DEFAULT '',
        sc_zip1 VARCHAR(10) NOT NULL DEFAULT '',
        sc_zip2 VARCHAR(10) NOT NULL DEFAULT '',
        sc_price INT NOT NULL DEFAULT 0,
        reg_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        update_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (sc_id),
        KEY idx_brand_id (brand_id),
        KEY idx_brand_zip (brand_id, sc_zip1, sc_zip2)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
", false);

$brand_rows = array();
$brand_result = sql_query("SELECT brand_id FROM donuts_brand ORDER BY brand_id");
while ($br = sql_fetch_array($brand_result)) {
    $brand_rows[] = trim((string)$br['brand_id']);
}

$current_mb_id = isset($member['mb_id']) ? trim((string)$member['mb_id']) : '';
$current_brand = '';

if ($current_mb_id !== '') {
    $current_mb_id_sql = sql_real_escape_string($current_mb_id);
    $current_brand_row = sql_fetch("
        SELECT brand_id
        FROM donuts_brand
        WHERE TRIM(brand_id) = '{$current_mb_id_sql}'
        LIMIT 1
    ");

    if (!empty($current_brand_row['brand_id'])) {
        $current_brand = trim((string)$current_brand_row['brand_id']);
    }
}

if ($current_brand !== '') {
    $manage_brand_id = $current_brand;
} elseif ($is_admin === 'super') {
    $manage_brand_id = isset($_GET['brand_id']) ? trim((string)$_GET['brand_id']) : '';

    if ($manage_brand_id === '' || !in_array($manage_brand_id, $brand_rows, true)) {
        $manage_brand_id = !empty($brand_rows[0]) ? $brand_rows[0] : '';
    }
} else {
    alert('브랜드 계정만 접근할 수 있습니다.');
}

if ($manage_brand_id === '') {
    alert('등록된 브랜드가 없습니다.');
}

$brand_id_sql = sql_real_escape_string($manage_brand_id);

$sql_common = " from donuts_brand_sendcost ";
$sql_search = " where brand_id = '{$brand_id_sql}' ";
$sql_order = " order by sc_id desc ";

$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search}
            {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
            {$sql_common}
            {$sql_search}
            {$sql_order}
            limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$g5['title'] = '추가배송비관리';
include_once(G5_ADMIN_PATH . '/admin.head.php');
?>
    <section id="scp_list">
        <h2>추가배송비 내역</h2>

        <?php if ($is_admin === 'super') { ?>
        <div class="local_sch01 local_sch" style="margin-bottom:15px;">
            <form method="get">
                <label for="brand_id"><strong>브랜드 선택</strong></label>
                <select name="brand_id" id="brand_id" onchange="this.form.submit()">
                    <?php foreach ($brand_rows as $bid) { ?>
                    <option value="<?php echo get_text($bid); ?>" <?php echo $bid === $manage_brand_id ? 'selected' : ''; ?>>
                        <?php echo get_text($bid); ?>
                    </option>
                    <?php } ?>
                </select>
            </form>
        </div>
        <?php } ?>

        <div class="local_desc02 local_desc" style="margin-bottom:15px;">
            현재 <strong><?php echo get_text($manage_brand_id); ?></strong> 브랜드의 추가배송비만 관리합니다.
        </div>

        <form name="fsendcost" id="fsendcost" method="post" action="./sendcostupdate.php" onsubmit="return fsendcost_submit(this);">
            <input type="hidden" name="w" value="d">
            <input type="hidden" name="brand_id" value="<?php echo get_text($manage_brand_id); ?>">
            <input type="hidden" name="page" value="<?php echo $page; ?>">
            <input type="hidden" name="token" value="">
            <div class="tbl_head01 tbl_wrap">
                <table>
                    <caption>추가배송비 내역</caption>
                    <thead>
                        <tr>
                            <th scope="col">
                                <label for="chkall" class="sound_only">내역 전체</label>
                                <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
                            </th>
                            <th scope="col">지역명</th>
                            <th scope="col">우편번호</th>
                            <th scope="col">추가배송비</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $row = sql_fetch_array($result); $i++) {
                            $bg = 'bg' . ($i % 2);
                        ?>
                            <tr class="<?php echo $bg; ?>">
                                <td class="td_chk">
                                    <input type="hidden" id="sc_id_<?php echo $i; ?>" name="sc_id[<?php echo $i; ?>]" value="<?php echo $row['sc_id']; ?>">
                                    <input type="checkbox" id="chk_<?php echo $i; ?>" name="chk[]" value="<?php echo $i; ?>" title="내역선택">
                                </td>
                                <td class="td_left"><?php echo $row['sc_name']; ?></td>
                                <td class="td_postalbig"><?php echo $row['sc_zip1'] . ' ~ ' . $row['sc_zip2']; ?></td>
                                <td class="td_sendcost_add"><?php echo number_format($row['sc_price']); ?></td>
                            </tr>
                        <?php
                        }

                        if ($i == 0)
                            echo '<tr><td colspan="4" class="empty_table">자료가 없습니다.</td></tr>';
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="btn_list01 btn_list">
                <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_04">
            </div>

        </form>
    </section>

    <?php
    $sendcost_qstr = 'brand_id=' . urlencode($manage_brand_id);
    echo get_paging(
        G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'],
        $page,
        $total_page,
        "{$_SERVER['SCRIPT_NAME']}?{$sendcost_qstr}&amp;page="
    );
?>

    <section id="sendcost_postal">
        <h2 class="h2_frm">추가배송비 등록</h2>

        <form name="fsendcost2" method="post" id="fsendcost2" action="./sendcostupdate.php" autocomplete="off">
            <input type="hidden" name="brand_id" value="<?php echo get_text($manage_brand_id); ?>">
            <input type="hidden" name="token" value="">

            <div class="tbl_frm01 tbl_wrap">
                <table>
                    <caption>추가배송비 등록</caption>
                    <colgroup>
                        <col class="grid_4">
                        <col>
                    </colgroup>
                    <tbody>
                        <tr>
                            <th scope="row"><label for="sc_name">지역명<strong class="sound_only">필수</strong></label></th>
                            <td><input type="text" name="sc_name" value="" id="sc_name" class="required frm_input" size="30" required></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="sc_zip1">우편번호 시작<strong class="sound_only">필수</strong></label></th>
                            <td>
                                <input type="text" name="sc_zip1" id="sc_zip1" required class="required frm_input" size="10"> (입력 예 : 01234)
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="sc_zip2">우편번호 끝<strong class="sound_only">필수</strong></label></th>
                            <td>
                                <input type="text" name="sc_zip2" id="sc_zip2" required class="required frm_input" size="10"> (입력 예 : 01234)
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="sc_price">추가배송비<strong class="sound_only">필수</strong></label></th>
                            <td><input type="text" name="sc_price" id="sc_price" size="8" required class="required frm_input"> 원</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="btn_confirm01 btn_confirm">
                <input type="submit" value="확인" class="btn btn_04">
            </div>

        </form>

    </section>
<script>
    function fsendcost_submit(f) {
        if (!is_checked("chk[]")) {
            alert(document.pressed + " 하실 항목을 하나 이상 선택하세요.");
            return false;
        }

        if (document.pressed == "선택삭제") {
            if (!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
                return false;
            }
        }

        return true;
    }
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

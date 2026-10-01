<?php
$sub_menu = "100200";
require_once './_common.php';

if ($is_admin != 'super') {
    alert('최고관리자만 접근 가능합니다.');
}

$sql_common = " from {$g5['auth_table']} a left join {$g5['member_table']} b on (a.mb_id=b.mb_id) ";

$sql_search = " where (1) ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        default:
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst = "a.mb_id, au_menu";
    $sod = "";
}

$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search}
            {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page = ceil($total_count / $rows);

if ($page < 1) {
    $page = 1;
}

$from_record = ($page - 1) * $rows;

$sql = " select *
            {$sql_common}
            {$sql_search}
            {$sql_order}
            limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="' . $_SERVER['SCRIPT_NAME'] . '" class="btn btn_04">전체목록</a>';

$g5['title'] = "관리권한설정";
require_once './admin.head.php';

$colspan = 5;

?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <span class="btn_ov01">
        <span class="ov_txt">설정된 관리권한</span>
        <span class="ov_num"><?php echo number_format($total_count) ?>건</span>
    </span>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
    <input type="hidden" name="sfl" value="a.mb_id" id="sfl">

    <div class="flex_gap">
        <label for="stx" class="sound_only">
            회원아이디<strong class="sound_only"> 필수</strong>
        </label>

        <input
            type="text"
            name="stx"
            value="<?php echo $stx ?>"
            id="stx"
            required
            class="required frm_input"
        >

        <input
            type="submit"
            value="검색"
            id="fsearch_submit"
            class="btn btn_04"
        >
    </div>
</form>

<form
    name="fauthlist"
    id="fauthlist"
    method="post"
    action="./auth_list_delete.php"
    onsubmit="return fauthlist_submit(this);"
>
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="">

    <div class="tbl_head01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?> 목록</caption>

            <thead>
                <tr>
                    <th scope="col">
                        <label for="chkall" class="sound_only">
                            현재 페이지 회원 전체
                        </label>

                        <input
                            type="checkbox"
                            name="chkall"
                            value="1"
                            id="chkall"
                            onclick="check_all(this.form)"
                        >
                    </th>

                    <th scope="col">
                        <?php echo subject_sort_link('a.mb_id') ?>회원아이디</a>
                    </th>

                    <th scope="col">
                        <?php echo subject_sort_link('mb_nick') ?>닉네임</a>
                    </th>

                    <th scope="col">메뉴</th>
                    <th scope="col">권한</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $count = 0;

                for ($i = 0; $row = sql_fetch_array($result); $i++) {
                    $is_continue = false;

                    // 회원아이디가 없는 메뉴는 삭제함
                    if ($row['mb_id'] == '' && $row['mb_nick'] == '') {
                        sql_query("
                            delete from {$g5['auth_table']}
                            where au_menu = '{$row['au_menu']}'
                        ");

                        $is_continue = true;
                    }

                    // 메뉴번호가 바뀌는 경우에 현재 없는 저장된 메뉴는 삭제함
                    if (!isset($auth_menu[$row['au_menu']])) {
                        sql_query("
                            delete from {$g5['auth_table']}
                            where au_menu = '{$row['au_menu']}'
                        ");

                        $is_continue = true;
                    }

                    if ($is_continue) {
                        continue;
                    }

                    $mb_nick = get_sideview(
                        $row['mb_id'],
                        $row['mb_nick'],
                        $row['mb_email'],
                        $row['mb_homepage']
                    );

                    $bg = 'bg' . ($i % 2);
                ?>

                    <tr class="<?php echo $bg; ?>">
                        <td class="td_chk">
                            <input
                                type="hidden"
                                name="au_menu[<?php echo $i ?>]"
                                value="<?php echo $row['au_menu'] ?>"
                            >

                            <input
                                type="hidden"
                                name="mb_id[<?php echo $i ?>]"
                                value="<?php echo $row['mb_id'] ?>"
                            >

                            <label
                                for="chk_<?php echo $i; ?>"
                                class="sound_only"
                            >
                                <?php echo $row['mb_nick'] ?>님 권한
                            </label>

                            <input
                                type="checkbox"
                                name="chk[]"
                                value="<?php echo $i ?>"
                                id="chk_<?php echo $i ?>"
                            >
                        </td>

                        <td class="td_mbid">
                            <a href="?sfl=a.mb_id&amp;stx=<?php echo $row['mb_id'] ?>">
                                <?php echo $row['mb_id'] ?>
                            </a>
                        </td>

                        <td class="td_auth_mbnick">
                            <?php echo $mb_nick ?>
                        </td>

                        <td class="td_menu">
                            <?php echo $row['au_menu'] ?>
                            <?php echo $auth_menu[$row['au_menu']] ?>
                        </td>

                        <td class="td_auth">
                            <?php echo $row['au_auth'] ?>
                        </td>
                    </tr>

                <?php
                    $count++;
                }

                if ($count == 0) {
                    echo '<tr><td colspan="' . $colspan . '" class="empty_table">자료가 없습니다.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="btn_list01 btn_list">
        <input
            type="submit"
            name="act_button"
            value="선택삭제"
            onclick="document.pressed=this.value"
            class="btn btn_05"
        >
    </div>
</form>

<?php
if (strstr($sfl, 'mb_id')) {
    $mb_id = $stx;
} else {
    $mb_id = '';
}
?>

<?php
$pagelist = get_paging(
    G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'],
    $page,
    $total_page,
    $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page='
);

echo $pagelist;
?>
<?php 


$existing_auth_menu = array();

if ($mb_id) {
    $sql = "
        select au_menu
        from {$g5['auth_table']}
        where mb_id = '{$mb_id}'
    ";

    $result_auth = sql_query($sql);

    while ($row_auth = sql_fetch_array($result_auth)) {
        $existing_auth_menu[$row_auth['au_menu']] = true;
    }
}

$existing_auth = array(
    'r' => false,
    'w' => false,
    'd' => false
);

if ($mb_id) {
    $sql = "
        select au_auth
        from {$g5['auth_table']}
        where mb_id = '{$mb_id}'
        limit 1
    ";

    $auth_row = sql_fetch($sql);

    if ($auth_row['au_auth']) {
        $auth_arr = explode(',', $auth_row['au_auth']);

        $existing_auth['r'] = isset($auth_arr[0]) && $auth_arr[0] === 'r';
        $existing_auth['w'] = isset($auth_arr[1]) && $auth_arr[1] === 'w';
        $existing_auth['d'] = isset($auth_arr[2]) && $auth_arr[2] === 'd';
    }
}
?>
<form
    name="fauthlist2"
    id="fauthlist2"
    action="./auth_update.php"
    method="post"
    autocomplete="off"
    onsubmit="return fauth_add_submit(this);"
>
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="">

    <section id="add_admin">
        <h2 class="h2_frm">관리권한 추가</h2>

        <div class="local_desc01 local_desc">
            <p>
                다음 양식에서 회원에게 관리권한을 부여하실 수 있습니다.<br>
                여러 메뉴를 선택하면 한 번에 관리권한이 부여됩니다.<br>
                권한 <strong>r</strong>은 읽기권한,
                <strong>w</strong>는 쓰기권한,
                <strong>d</strong>는 삭제권한입니다.
            </p>
        </div>

        <div class="tbl_frm01 tbl_wrap">
            <table>
                <colgroup>
                    <col class="grid_4">
                    <col>
                </colgroup>

                <tbody>
                    <tr>
                        <th scope="row">
                            <label for="mb_id">
                                회원아이디<strong class="sound_only">필수</strong>
                            </label>
                        </th>

                        <td>
                            <strong
                                id="msg_mb_id"
                                class="msg_sound_only"
                            ></strong>

                            <input
                                type="text"
                                name="mb_id"
                                value="<?php echo $mb_id ?>"
                                id="mb_id"
                                required
                                class="required frm_input"
                            >
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            접근가능메뉴<strong class="sound_only">필수</strong>
                        </th>

                        <td>
                            <div style="margin-bottom:10px;">
                                <input
                                    type="checkbox"
                                    id="au_menu_all"
                                    onclick="check_auth_menu_all(this)"
                                >

                                <label for="au_menu_all">
                                    전체선택
                                </label>
                            </div>

                            <div
                                id="auth_menu_list"
                                style="
                                    display:grid;
                                    grid-template-columns:repeat(3, minmax(180px, 1fr));
                                    gap:8px 20px;
                                "
                            >
                                <?php
                                foreach ($auth_menu as $key => $value) {
                                    if (
                                        !(substr($key, -3) == '000'
                                        || $key == '-'
                                        || !$key)
                                    ) {
                                ?>

                                    <label
                                        style="
                                            display:flex;
                                            align-items:center;
                                            gap:5px;
                                            cursor:pointer;
                                        "
                                    >
                                       <input
                                            type="checkbox"
                                            name="au_menu[]"
                                            value="<?php echo $key ?>"
                                            class="au_menu_chk"
                                            <?php echo isset($existing_auth_menu[$key]) ? 'checked' : ''; ?>
                                        >

                                        <span>
                                            <?php echo $key ?>
                                            <?php echo $value ?>
                                        </span>
                                    </label>

                                <?php
                                    }
                                }
                                ?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">권한지정</th>

                        <td>
                            <input
    type="checkbox"
    name="r"
    value="r"
    id="r"
    <?php echo $existing_auth['r'] ? 'checked' : ''; ?>
>
<label for="r">r (읽기)</label>

<input
    type="checkbox"
    name="w"
    value="w"
    id="w"
    <?php echo $existing_auth['w'] ? 'checked' : ''; ?>
>
<label for="w">w (쓰기)</label>

<input
    type="checkbox"
    name="d"
    value="d"
    id="d"
    <?php echo $existing_auth['d'] ? 'checked' : ''; ?>
>
<label for="d">d (삭제)</label>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">자동등록방지</th>

                        <td>
                            <?php
                            require_once G5_CAPTCHA_PATH . '/captcha.lib.php';

                            $captcha_html = captcha_html();
                            $captcha_js = chk_captcha_js();

                            echo $captcha_html;
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="btn_confirm01 btn_confirm">
            <input
                type="submit"
                value="저장"
                class="btn btn_04"
            >
        </div>
    </section>
</form>

<script>
function check_auth_menu_all(checkbox) {
    var checkboxes = document.querySelectorAll('.au_menu_chk');

    checkboxes.forEach(function(item) {
        item.checked = checkbox.checked;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    var checkboxes = document.querySelectorAll('.au_menu_chk');
    var allCheckbox = document.getElementById('au_menu_all');

    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            var checkedCount = document.querySelectorAll(
                '.au_menu_chk:checked'
            ).length;

            allCheckbox.checked =
                checkedCount === checkboxes.length;
        });
    });
});

function fauth_add_submit(f) {

    <?php echo $captcha_js; ?>

    var menuChecked = document.querySelectorAll(
        '#fauthlist2 .au_menu_chk:checked'
    );

    if (menuChecked.length === 0) {
        alert('접근가능메뉴를 하나 이상 선택하세요.');
        return false;
    }

    return true;
}

function fauthlist_submit(f) {
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
require_once './admin.tail.php';
?>
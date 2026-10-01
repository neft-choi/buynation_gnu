<?php

$sub_menu = "100200";

require_once './_common.php';
require_once G5_LIB_PATH . '/mailer.lib.php';

if ($is_admin != 'super') {
    alert('최고관리자만 접근 가능합니다.');
}

/*
 * 회원아이디
 */
$mb_id = isset($_POST['mb_id'])
    ? preg_replace('/[^a-z0-9_]/i', '', $_POST['mb_id'])
    : '';

/*
 * 메뉴
 *
 * 기존:
 * au_menu = 하나의 값
 *
 * 변경:
 * au_menu[] = 여러 개의 값
 */
$au_menu = isset($_POST['au_menu']) && is_array($_POST['au_menu'])
    ? $_POST['au_menu']
    : array();

/*
 * 메뉴값 검증
 */
$au_menu = array_map(function ($menu) {
    return preg_replace('/[^0-9a-z_]/i', '', $menu);
}, $au_menu);

/*
 * 빈 메뉴 제거 + 중복 제거
 */
$au_menu = array_filter($au_menu);
$au_menu = array_unique($au_menu);

/*
 * 권한
 */
$post_r = isset($_POST['r'])
    ? preg_replace('/[^0-9a-z_]/i', '', $_POST['r'])
    : '';

$post_w = isset($_POST['w'])
    ? preg_replace('/[^0-9a-z_]/i', '', $_POST['w'])
    : '';

$post_d = isset($_POST['d'])
    ? preg_replace('/[^0-9a-z_]/i', '', $_POST['d'])
    : '';


/*
 * 회원 확인
 */
$mb = get_member($mb_id);

if (!(isset($mb['mb_id']) && $mb['mb_id'])) {
    alert('존재하는 회원아이디가 아닙니다.');
}


/*
 * 메뉴가 하나도 없으면 중단
 */
if (empty($au_menu)) {
    alert('접근가능메뉴를 하나 이상 선택하세요.');
}


/*
 * 관리자 토큰 확인
 */
check_admin_token();


/*
 * 캡챠 확인
 */
require_once G5_CAPTCHA_PATH . '/captcha.lib.php';

if (!chk_captcha()) {
    alert('자동등록방지 숫자가 틀렸습니다.');
}


/*
 * 권한 문자열
 *
 * 기존 auth_update.php와 동일하게
 * r,w,d 값을 쉼표로 저장
 *
 * 예:
 * r,w,d
 * r,,
 * r,w,
 */
$au_auth = "{$post_r},{$post_w},{$post_d}";


// 선택된 메뉴 정리
$selected_menus = array();

foreach ($au_menu as $menu) {
    $menu = preg_replace('/[^0-9a-z_]/i', '', $menu);

    if ($menu != '') {
        $selected_menus[] = $menu;
    }
}

$selected_menus = array_unique($selected_menus);


/*
 * 현재 회원에게 부여되어 있는 권한 조회
 */
$existing_menus = array();

$sql = " select au_menu
           from {$g5['auth_table']}
          where mb_id = '$mb_id' ";

$result = sql_query($sql);

while ($row = sql_fetch_array($result)) {
    $existing_menus[] = $row['au_menu'];
}


/*
 * 현재 체크되지 않은 기존 권한 삭제
 */
foreach ($existing_menus as $menu) {

    if (!in_array($menu, $selected_menus)) {

        $sql = " delete from {$g5['auth_table']}
                  where mb_id = '$mb_id'
                    and au_menu = '$menu' ";

        sql_query($sql);
    }
}


/*
 * 현재 체크된 메뉴 추가 / 권한 수정
 */
$au_auth = "{$post_r},{$post_w},{$post_d}";

foreach ($selected_menus as $menu) {

    $sql = " insert into {$g5['auth_table']}
                set mb_id   = '$mb_id',
                    au_menu = '$menu',
                    au_auth = '$au_auth' ";

    $result = sql_query($sql, false);

    /*
     * 이미 존재하면 UPDATE
     */
    if (!$result) {

        $sql = " update {$g5['auth_table']}
                    set au_auth = '$au_auth'
                  where mb_id   = '$mb_id'
                    and au_menu = '$menu' ";

        sql_query($sql);
    }
}


/*
 * 세션을 체크하여 하루에 한번만
 * 메일알림이 가게 합니다.
 */
if (str_replace('-', '', G5_TIME_YMD) !== get_session('adm_auth_update')) {

    $site_url = preg_replace(
        '/^www\./',
        '',
        strtolower($_SERVER['SERVER_NAME'])
    );

    $to_email = 'gnuboard@' . $site_url;

    mailer(
        $config['cf_admin_email_name'],
        $to_email,
        $config['cf_admin_email'],
        '[' . $config['cf_title'] . '] 관리권한설정 알림',
        '<p><b>[' . $config['cf_title'] . '] 관리권한설정 변경 안내</b></p>
        <p style="padding-top:1em">
            회원 아이디 ' . $mb['mb_id'] . '에 관리권한이 추가 되었습니다.
        </p>
        <p style="padding-top:1em">' . G5_TIME_YMDHIS . '</p>
        <p style="padding-top:1em">
            <a href="' . G5_URL . '" target="_blank">
                ' . $config['cf_title'] . '
            </a>
        </p>',
        1
    );

    set_session(
        'adm_auth_update',
        str_replace('-', '', G5_TIME_YMD)
    );
}


/*
 * 이벤트
 */
run_event('adm_auth_update', $mb);


/*
 * 기존 검색조건 유지
 */
goto_url('./auth_list.php?' . $qstr);
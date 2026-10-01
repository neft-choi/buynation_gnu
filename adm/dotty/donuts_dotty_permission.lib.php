<?php
/*
 * 지정 운영자 권한 체크용 공통 함수.
 * _common.php 또는 도넛 관리자 공통 include 파일에서 include_once 하여 사용하세요.
 *
 * 예)
 * donuts_dotty_permission_check('join');    // 가입 심사
 * donuts_dotty_permission_check('member');  // 회원 조회
 * donuts_dotty_permission_check('content'); // 콘텐츠
 * donuts_dotty_permission_check('notice');  // 공지/핀
 * donuts_dotty_permission_check('product'); // 추천상품
 */

if (!defined('_GNUBOARD_')) exit;

function donuts_dotty_has_permission($dotty_mb_id, $login_mb_id, $permission)
{
    global $is_admin;

    $dotty_mb_id = trim((string)$dotty_mb_id);
    $login_mb_id = trim((string)$login_mb_id);
    $permission = trim((string)$permission);

    if ($is_admin === 'super') return true;
    if ($dotty_mb_id !== '' && strcasecmp($dotty_mb_id, $login_mb_id) === 0) return true;

    $allowed = array('join','member','content','notice','product');
    if (!in_array($permission, $allowed, true)) return false;

    $dotty_sql = sql_real_escape_string($dotty_mb_id);
    $login_sql = sql_real_escape_string($login_mb_id);

    $row = sql_fetch("SELECT permissions
        FROM donuts_dotty_admins
        WHERE dotty_mb_id='{$dotty_sql}'
          AND operator_mb_id='{$login_sql}'
          AND use_yn='Y'
        LIMIT 1");

    if (empty($row)) return false;

    $permissions = array_filter(array_map('trim', explode(',', (string)$row['permissions'])));
    return in_array($permission, $permissions, true);
}

function donuts_dotty_permission_check($permission, $dotty_mb_id = '')
{
    global $member;

    if ($dotty_mb_id === '') {
        $dotty_mb_id = isset($_REQUEST['mb_id']) ? trim((string)$_REQUEST['mb_id']) : trim((string)$member['mb_id']);
    }

    if (!donuts_dotty_has_permission($dotty_mb_id, (string)$member['mb_id'], $permission)) {
        alert('해당 메뉴의 운영 권한이 없습니다.');
    }
}

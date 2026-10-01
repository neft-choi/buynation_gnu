<?php
if (!defined('_GNUBOARD_')) exit;

/*
 * 도넛 활동 로그 기록
 *
 * donuts_dotty_activity_log(
 *     $dotty_mb_id,
 *     'join',
 *     'join_approve',
 *     '가입 승인',
 *     'APP-240803-011 가입 신청 승인',
 *     'APP-240803-011'
 * );
 */
function donuts_dotty_activity_log($dotty_mb_id, $action_group, $action_code, $action_label, $action_detail = '', $target_id = '', $actor_mb_id = '')
{
    global $member;

    $dotty_mb_id = trim((string)$dotty_mb_id);
    if ($dotty_mb_id === '') return false;

    $allowed_groups = array('login','invite','product','topping','join','content','notice','operator','ownership','etc');
    if (!in_array($action_group, $allowed_groups, true)) {
        $action_group = 'etc';
    }

    if ($actor_mb_id === '') {
        $actor_mb_id = isset($member['mb_id']) ? (string)$member['mb_id'] : '';
    }

    $actor_name = '';
    if ($actor_mb_id !== '') {
        if (isset($member['mb_id']) && (string)$member['mb_id'] === $actor_mb_id) {
            $actor_name = !empty($member['mb_nick']) ? $member['mb_nick'] : (!empty($member['mb_name']) ? $member['mb_name'] : $actor_mb_id);
        } else {
            $actor_sql = sql_real_escape_string($actor_mb_id);
            $m = sql_fetch("SELECT mb_name,mb_nick FROM {$GLOBALS['g5']['member_table']} WHERE mb_id='{$actor_sql}' LIMIT 1");
            $actor_name = !empty($m['mb_nick']) ? $m['mb_nick'] : (!empty($m['mb_name']) ? $m['mb_name'] : $actor_mb_id);
        }
    }

    $ip = isset($_SERVER['REMOTE_ADDR']) ? trim((string)$_SERVER['REMOTE_ADDR']) : '';

    $dotty_sql = sql_real_escape_string($dotty_mb_id);
    $actor_sql = sql_real_escape_string($actor_mb_id);
    $actor_name_sql = sql_real_escape_string($actor_name);
    $group_sql = sql_real_escape_string($action_group);
    $code_sql = sql_real_escape_string((string)$action_code);
    $label_sql = sql_real_escape_string((string)$action_label);
    $detail_sql = sql_real_escape_string((string)$action_detail);
    $target_sql = sql_real_escape_string((string)$target_id);
    $ip_sql = sql_real_escape_string($ip);

    return sql_query("INSERT INTO donuts_dotty_activity_logs SET
        dotty_mb_id='{$dotty_sql}',
        actor_mb_id='{$actor_sql}',
        actor_name='{$actor_name_sql}',
        action_group='{$group_sql}',
        action_code='{$code_sql}',
        action_label='{$label_sql}',
        action_detail='{$detail_sql}',
        target_id='{$target_sql}',
        ip_address='{$ip_sql}',
        created_at=NOW()");
}

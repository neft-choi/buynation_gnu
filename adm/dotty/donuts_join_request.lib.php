<?php
// 가입 신청 생성 예시 helper.
// 실제 사용자 가입 화면에서 include 후 donuts_create_join_request()를 호출할 수 있습니다.

if (!defined('_GNUBOARD_')) exit;

function donuts_create_join_request($dotty_mb_id, $applicant_mb_id, $answers = array())
{
    $dotty_mb_id = trim((string)$dotty_mb_id);
    $applicant_mb_id = trim((string)$applicant_mb_id);

    if ($dotty_mb_id === '' || $applicant_mb_id === '') {
        return false;
    }

    $dotty_sql = sql_real_escape_string($dotty_mb_id);
    $applicant_sql = sql_real_escape_string($applicant_mb_id);

    $pending = sql_fetch("
        SELECT request_id
        FROM donuts_dotty_join_requests
        WHERE dotty_mb_id = '{$dotty_sql}'
          AND applicant_mb_id = '{$applicant_sql}'
          AND status = 'pending'
        LIMIT 1
    ");
    if (!empty($pending['request_id'])) {
        return (int)$pending['request_id'];
    }

    $request_no = 'APP-'.date('ymd').'-'.strtoupper(substr(md5(uniqid((string)mt_rand(), true)), 0, 8));
    $request_no_sql = sql_real_escape_string($request_no);

    sql_query("
        INSERT INTO donuts_dotty_join_requests
            (request_no, dotty_mb_id, applicant_mb_id, status, created_at, updated_at)
        VALUES
            ('{$request_no_sql}', '{$dotty_sql}', '{$applicant_sql}', 'pending', NOW(), NOW())
    ");

    $request_id = (int)sql_insert_id();

    $sort = 1;
    foreach ((array)$answers as $answer) {
        $question = isset($answer['question']) ? trim((string)$answer['question']) : '';
        $value = isset($answer['answer']) ? trim((string)$answer['answer']) : '';
        if ($question === '' && $value === '') continue;

        $question_sql = sql_real_escape_string($question);
        $value_sql = sql_real_escape_string($value);

        sql_query("
            INSERT INTO donuts_dotty_join_request_answers
                (request_id, question_text, answer_text, sort_order, created_at)
            VALUES
                ('{$request_id}', '{$question_sql}', '{$value_sql}', '{$sort}', NOW())
        ");
        $sort++;
    }

    return $request_id;
}

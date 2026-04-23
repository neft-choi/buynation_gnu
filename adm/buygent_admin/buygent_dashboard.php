<?php
$sub_menu = '920100';
require_once './_common.php';
require_once G5_ADMIN_PATH . '/data_table_component.php';
require_once G5_ADMIN_PATH . '/chart_component.php';

// 샘플 데이터 겸 sql 테스트
$dashboard_sql = " select mb_id, mb_name, mb_nick, mb_email, mb_datetime
                   from {$g5['member_table']}
                   order by mb_datetime desc
                   limit 10 ";
$dashboard_result = sql_query($dashboard_sql);

// 컴포넌트 rows 규격에 맞게 순차 누적
$dashboard_rows = array();
while ($dashboard_row = sql_fetch_array($dashboard_result)) {
    $dashboard_rows[] = $dashboard_row;
}

// 컴포넌트 columns 규격: key(데이터 키) + label(헤더명) + class(선택)
$dashboard_columns = array(
    array('key' => 'mb_id', 'label' => '아이디', 'th_class' => 'w-[140px]'),
    array('key' => 'mb_name', 'label' => '이름'),
    array('key' => 'mb_nick', 'label' => '닉네임'),
    array('key' => 'mb_email', 'label' => '이메일'),
    array('key' => 'mb_datetime', 'label' => '가입일', 'th_class' => 'w-[180px]')
);

// 최근 7일 가입 추이 차트 데이터 (SQL 공부)
$member_chart_sql = " select date(mb_datetime) as joined_date, count(*) as joined_cnt
                      from {$g5['member_table']}
                      where mb_datetime >= date_sub(curdate(), interval 6 day)
                      group by date(mb_datetime)
                      order by joined_date asc ";
$member_chart_result = sql_query($member_chart_sql);

$member_count_map = array();
while ($member_chart_row = sql_fetch_array($member_chart_result)) {
    $member_count_map[$member_chart_row['joined_date']] = (int) $member_chart_row['joined_cnt'];
}

$member_chart_labels = array();
$member_chart_data = array();
$weekday_map = array('일', '월', '화', '수', '목', '금', '토');

for ($offset = 6; $offset >= 0; $offset--) {
    $day_key = date('Y-m-d', strtotime("-{$offset} days"));
    $weekday_index = (int) date('w', strtotime($day_key));
    $member_chart_labels[] = substr($day_key, 5) . ' (' . $weekday_map[$weekday_index] . ')';
    $member_chart_data[] = isset($member_count_map[$day_key]) ? $member_count_map[$day_key] : 0;
}

$g5['title'] = '바이전트 대시보드';
require_once '../admin.head.php';
?>

<section>
    <h2>바이전트 대시보드</h2>
    <div>바이전트 대시보드 페이지 입니다</div>
</section>

<section class="mt-4">
    <?php
    echo render_data_table(array(
        'columns' => $dashboard_columns,
        'rows' => $dashboard_rows,
        'table_class' => 'w-full',
        'wrapper_class' => 'bg-white',

        // 버튼이나 input 등 추가 용도
        'render_cell' => function ($column, $row) {
            $column_key = isset($column['key']) ? $column['key'] : '';

            if ($column_key === 'mb_datetime') {
                return dt_escape(substr((string) $row['mb_datetime'], 0, 16));
            }

            return dt_escape(isset($row[$column_key]) ? $row[$column_key] : '');
        }
    ));
    ?>
</section>

<section class="mt-4">
    <?php
    echo render_admin_chart(array(
        'id' => 'chart_test',
        'title' => '차트 타이틀 테스트',
        'type' => 'bar',
        'labels' => $member_chart_labels,
        'datasets' => array(
            array(
                'label' => '주문',
                'data' => $member_chart_data,
                'backgroundColor' => '#3080FF',
                'hoverBackgroundColor' => '#3080FF',
                'borderWidth' => 0
            ),
            array(
                'label' => '취소',
                'data' => $member_chart_data,
                'backgroundColor' => '#FB2C36',
                'hoverBackgroundColor' => '#FB2C36',
                'borderWidth' => 0
            )
        ),
    ));
    ?>
</section>

<?php echo render_admin_chart_bootstrap(); ?>

<?php
require_once '../admin.tail.php';

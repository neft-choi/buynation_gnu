<?php
// 직접 접근 차단
if (!defined('_GNUBOARD_')) {
    exit;
}

// 컴포넌트 출력 시 XSS를 방지하기 위한 기본 이스케이프 함수
function dt_escape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// 데이터 테이블 렌더
function render_data_table($props = array())
{
    $columns = isset($props['columns']) && is_array($props['columns']) ? $props['columns'] : array();
    $rows = isset($props['rows']) && is_array($props['rows']) ? $props['rows'] : array();
    $table_class = isset($props['table_class']) ? (string) $props['table_class'] : '';
    $wrapper_class = isset($props['wrapper_class']) ? (string) $props['wrapper_class'] : '';
    $empty_text = isset($props['empty_text']) ? (string) $props['empty_text'] : '자료가 없습니다.';
    $render_cell = isset($props['render_cell']) && is_callable($props['render_cell']) ? $props['render_cell'] : null;

    // columns가 비어 있으면 렌더를 중단
    if (count($columns) === 0) {
        return '';
    }

    ob_start();
?>
    <div class="data_table tbl_head01 tbl_wrap <?php echo dt_escape($wrapper_class); ?>">
        <table class="<?php echo dt_escape($table_class); ?>">
            <thead>
                <tr>
                    <?php foreach ($columns as $column) { ?>
                        <th scope="col" class="<?php echo dt_escape(isset($column['th_class']) ? $column['th_class'] : ''); ?>">
                            <?php echo dt_escape(isset($column['label']) ? $column['label'] : ''); ?>
                        </th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php if (count($rows) === 0) { ?>
                    <tr>
                        <td colspan="<?php echo (int) count($columns); ?>" class="empty_table">
                            <?php echo dt_escape($empty_text); ?>
                        </td>
                    </tr>
                <?php } else { ?>
                    <?php foreach ($rows as $rowIndex => $row) { ?>
                        <tr>
                            <?php foreach ($columns as $column) { ?>
                                <?php
                                $key = isset($column['key']) ? (string) $column['key'] : '';
                                $value = isset($row[$key]) ? $row[$key] : '';

                                // 버튼이나 input 등 추가 용도
                                if ($render_cell) {
                                    $cell_html = call_user_func($render_cell, $column, $row, $rowIndex);
                                } else {
                                    $cell_html = dt_escape($value);
                                }
                                ?>
                                <td class="<?php echo dt_escape(isset($column['td_class']) ? $column['td_class'] : ''); ?>">
                                    <?php echo $cell_html; ?>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php
    return ob_get_clean();
}

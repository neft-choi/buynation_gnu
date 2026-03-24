<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 모바일 분기 false 처리
if (false && G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH . '/group.php');
    return;
}

$is_all_group = empty($gr_id);

if (!$is_all_group && !$is_admin && $group['gr_device'] == 'mobile')
    alert($group['gr_subject'] . ' 그룹은 모바일에서만 접근할 수 있습니다.');

$g5['title'] = $is_all_group ? '전체 카테고리' : $group['gr_subject'];
include_once(G5_THEME_PATH . '/head.php');
include_once(G5_LIB_PATH . '/latest.lib.php');
?>

<style>
    #container_title {
        display: none !important;
    }
</style>

<?php
$quick_nav_container_class = 'mx-auto w-full max-w-full px-4';
include G5_THEME_PATH . '/_quick_nav.php';
unset($quick_nav_container_class);
?>

<div class="latest_wr mx-auto grid w-full max-w-full grid-cols-1 gap-4 p-4 mt-4 !mb-0">

    <!-- 메인화면 최신글 시작 -->
    <?php if ($is_all_group) { ?>
        <?php
        // 전체 그룹 진입 시에도 gr_id 진입과 동일한 latest 카드 UI로 노출
        $sql = " select a.bo_table, a.bo_subject
                from {$g5['board_table']} a
                left join {$g5['group_table']} b on (a.gr_id = b.gr_id)
                where a.bo_list_level <= '{$member['mb_level']}'
                and a.bo_device <> 'mobile' 
                AND b.gr_id != 'community'";
        if (!$is_admin)
            $sql .= " and a.bo_use_cert = '' ";
        $sql .= " order by b.gr_order, a.bo_order, a.bo_table ";
        $result = sql_query($sql);
        $total_board_count = (int)sql_num_rows($result);
        ?>
        <div class="p-4">
            <p class="text-sm font-semibold text-gray-900">전체 (<?php echo number_format($total_board_count); ?>)</p>
        </div>
        <?php
        for ($i = 0; $row = sql_fetch_array($result); $i++) {
        ?>
            <div class="w-full">
                <?php
                echo latest('theme/basic', $row['bo_table'], 6, 25);
                ?>
            </div>
        <?php } ?>
    <?php } else { ?>
        <?php
        //  최신글
        $sql = " select bo_table, bo_subject
                from {$g5['board_table']}
                where gr_id = '{$gr_id}'
                and bo_list_level <= '{$member['mb_level']}'
                and bo_device <> 'mobile' ";
        if (!$is_admin)
            $sql .= " and bo_use_cert = '' ";
        $sql .= " order by bo_order ";
        $result = sql_query($sql);
        for ($i = 0; $row = sql_fetch_array($result); $i++) {
        ?>
            <div class="w-full">
                <?php
                // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
                // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
                echo latest('theme/basic', $row['bo_table'], 6, 25);
                ?>
            </div>
        <?php } ?>
    <?php } ?>
    <!-- 메인화면 최신글 끝 -->
</div>
<?php
include_once(G5_THEME_PATH . '/tail.php');

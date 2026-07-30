<?php
$sub_menu = '710300';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "r");

$g5['title'] = '등록 상품 관리';

$dotty_mb_id = $member['mb_id'];
$dotty_mb_id_sql = sql_real_escape_string($dotty_mb_id);

$rows = isset($config['cf_page_rows']) ? (int)$config['cf_page_rows'] : 20;
if ($rows < 1) $rows = 20;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$count = sql_fetch("
    SELECT COUNT(*) AS cnt
    FROM donuts_dotty_items
    WHERE dotty_mb_id = '{$dotty_mb_id_sql}'
");

$total_count = (int)$count['cnt'];
$total_page = $total_count ? (int)ceil($total_count / $rows) : 1;
$from_record = ($page - 1) * $rows;

$sql = "
    SELECT
        d.*,
        i.it_name,
        i.it_price,
        i.it_use,
        i.it_soldout,
        i.it_brand
    FROM donuts_dotty_items d
    INNER JOIN {$g5['g5_shop_item_table']} i
        ON d.it_id = i.it_id
    WHERE d.dotty_mb_id = '{$dotty_mb_id_sql}'
    ORDER BY d.sort_order ASC, d.id DESC
    LIMIT {$from_record}, {$rows}
";

$result = sql_query($sql);

require_once '../admin.head.php';

// admin.head.php 출력 이후 토큰을 한 번만 생성하여
// 숨김/노출/삭제 모든 요청에 동일한 토큰을 사용합니다.
$admin_token = get_admin_token();
?>

<section>
    <h2>등록 상품 관리</h2>

    <div class="local_ov01 local_ov">
        <span class="btn_ov01">
            <span class="ov_txt">등록 상품</span>
            <span class="ov_num"><?php echo number_format($total_count); ?>개</span>
        </span>
    </div>

    <div class="btn_fixed_top">
        <a href="./itemform.php" class="btn btn_01">상품 등록</a>
    </div>

    <div class="tbl_head01 tbl_wrap">
        <table>
            <thead>
            <tr>
                <th>상품코드</th>
                <th>상품명</th>
                <th>원 판매가</th>
                <th>기여금율</th>
                <th>할인율</th>
                <th>노출여부</th>
                <th>정렬</th>
                <th>관리</th>
            </tr>
            </thead>

            <tbody>
            <?php
            $i = 0;
            while ($row = sql_fetch_array($result)) {
                $i++;

                $discount_amount = floor((int)$row['it_price'] * (float)$row['discount_rate'] / 100);
                $display_price = max(0, (int)$row['it_price'] - $discount_amount);
            ?>
                <tr>
                    <td class="td_numbig"><?php echo get_text($row['it_id']); ?></td>
                    <td class="td_left">
                        <a href="<?php echo shop_item_url($row['it_id']); ?>" target="_blank">
                            <?php echo get_it_image($row['it_id'], 50, 50); ?>
                            <?php echo get_text($row['it_name']); ?>
                        </a>
                        <br>
                        <small>도티 노출가: <?php echo number_format($display_price); ?>원</small>
                    </td>
                    <td class="td_price"><?php echo number_format($row['it_price']); ?>원</td>
                    <td class="td_num"><?php echo number_format($row['contribution_rate'], 1); ?>%</td>
                    <td class="td_num"><?php echo number_format($row['discount_rate'], 1); ?>%</td>
                    <td class="td_mngsmall"><?php echo $row['use_yn'] === 'Y' ? '노출' : '숨김'; ?></td>
                    <td class="td_num"><?php echo number_format($row['sort_order']); ?></td>
                    <td class="td_mng">
                        <form method="post"
                              action="./item_toggle.php"
                              style="display:inline-block; margin:0;">
                            <input type="hidden" name="token" value="<?php echo get_text($admin_token); ?>">
                            <input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>">
                            <button type="submit" class="btn btn_03">
                                <?php echo $row['use_yn'] === 'Y' ? '숨김' : '노출'; ?>
                            </button>
                        </form>

                        <form method="post"
                              action="./item_delete.php"
                              style="display:inline-block; margin:0;"
                              onsubmit="return confirm('등록 상품에서 실제로 삭제하시겠습니까?');">
                            <input type="hidden" name="token" value="<?php echo get_text($admin_token); ?>">
                            <input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>">
                            <button type="submit" class="btn btn_02">삭제</button>
                        </form>
                    </td>
                </tr>
            <?php
            }

            if (!$i) {
                echo '<tr><td colspan="8" class="empty_table">등록된 상품이 없습니다.</td></tr>';
            }
            ?>
            </tbody>
        </table>
    </div>

    <?php
    if ($total_count) {
        echo get_paging(
            G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'],
            $page,
            $total_page,
            $_SERVER['SCRIPT_NAME'] . '?page='
        );
    }
    ?>
</section>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

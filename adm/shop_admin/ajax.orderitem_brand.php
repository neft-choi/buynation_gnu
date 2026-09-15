<?php
$sub_menu = '400400';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "r");

$od_id = isset($_POST['od_id'])
    ? preg_replace('/[^0-9A-Za-z_\-]/', '', (string)$_POST['od_id'])
    : '';

if ($od_id === '') {
    exit('주문번호가 올바르지 않습니다.');
}

$od_id_sql = sql_real_escape_string($od_id);

/*
 * 현재 로그인 계정이 브랜드 계정인지 확인합니다.
 *
 * 최고관리자:
 *   주문 전체 상품 표시
 *
 * 브랜드 계정:
 *   현재 로그인한 브랜드가 소유한 상품만 표시
 */
$modal_is_brand = false;
$modal_brand_id = '';
$modal_brand_sql = '';

if ($is_admin !== 'super' && !empty($member['mb_id'])) {
    $login_id = trim((string)$member['mb_id']);
    $login_id_sql = sql_real_escape_string($login_id);

    $brand = sql_fetch("
        SELECT brand_id
        FROM donuts_brand
        WHERE LOWER(TRIM(brand_id)) = LOWER('{$login_id_sql}')
        LIMIT 1
    ");

    if (!empty($brand['brand_id'])) {
        $modal_is_brand = true;
        $modal_brand_id = trim((string)$brand['brand_id']);
        $modal_brand_sql = sql_real_escape_string($modal_brand_id);
    }
}

/*
 * 소유자 필드 결정
 *
 * 신규 구조: it_seller = 판매자/브랜드 계정 ID
 * 구버전 구조: it_brand = 판매자/브랜드 계정 ID
 *
 * 서버에 it_seller 컬럼이 존재하면 it_seller를 우선 사용하고,
 * 값이 비어 있는 과거 데이터만 it_brand로 fallback 합니다.
 */
$has_it_seller = false;

$column_check = sql_query("
    SHOW COLUMNS
    FROM {$g5['g5_shop_item_table']}
    LIKE 'it_seller'
", false);

if ($column_check && sql_num_rows($column_check) > 0) {
    $has_it_seller = true;
}

$brand_join_where = '';

if ($modal_is_brand) {
    if ($has_it_seller) {
        $brand_join_where = "
            AND (
                LOWER(TRIM(i.it_seller)) = LOWER('{$modal_brand_sql}')
                OR (
                    TRIM(COALESCE(i.it_seller, '')) = ''
                    AND LOWER(TRIM(i.it_brand)) = LOWER('{$modal_brand_sql}')
                )
            )
        ";
    } else {
        $brand_join_where = "
            AND LOWER(TRIM(i.it_brand)) = LOWER('{$modal_brand_sql}')
        ";
    }
}

/*
 * 상품별 목록.
 * cart 테이블만 조회하면 다른 브랜드 상품도 같이 나오기 때문에
 * 상품 테이블을 INNER JOIN하여 브랜드 소유권을 서버에서 필터링합니다.
 */
$sql = "
    SELECT
        c.it_id,
        MAX(c.it_name) AS it_name,
        MAX(c.cp_price) AS cp_price,
        MAX(c.ct_notax) AS ct_notax,
        MAX(c.ct_send_cost) AS ct_send_cost,
        MAX(c.it_sc_type) AS it_sc_type,
        MIN(c.ct_id) AS first_ct_id
    FROM {$g5['g5_shop_cart_table']} c
    INNER JOIN {$g5['g5_shop_item_table']} i
        ON i.it_id = c.it_id
    WHERE c.od_id = '{$od_id_sql}'
      {$brand_join_where}
    GROUP BY c.it_id
    ORDER BY first_ct_id ASC
";

$result = sql_query($sql, false);

if (!$result) {
    exit('주문상품을 조회하지 못했습니다.');
}
?>

<h3>주문상품 목록</h3>

<div class="tbl_head01 tbl_wrap">
    <table>
        <caption>주문 상품 목록</caption>
        <thead>
            <tr>
                <th scope="col">상품명</th>
                <th scope="col">옵션항목</th>
                <th scope="col">상태</th>
                <th scope="col">수량</th>
                <th scope="col">판매가</th>
                <th scope="col">소계</th>
                <th scope="col">쿠폰</th>
                <th scope="col">포인트</th>
                <th scope="col">배송비</th>
            </tr>
        </thead>
        <tbody>
<?php
$item_count = 0;

while ($row = sql_fetch_array($result)) {
    $item_count++;

    $it_id = trim((string)$row['it_id']);
    $it_id_sql = sql_real_escape_string($it_id);

    $image = get_it_image($it_id, 50, 50);

    /*
     * 옵션행도 동일 주문번호 + 동일 상품 기준으로만 출력합니다.
     * 상품 자체가 위 브랜드 필터를 통과한 경우에만 여기까지 들어옵니다.
     */
    $opt_result = sql_query("
        SELECT
            ct_id,
            it_id,
            ct_price,
            ct_point,
            ct_qty,
            ct_option,
            ct_status,
            cp_price,
            ct_stock_use,
            ct_point_use,
            ct_send_cost,
            io_type,
            io_price
        FROM {$g5['g5_shop_cart_table']}
        WHERE od_id = '{$od_id_sql}'
          AND it_id = '{$it_id_sql}'
        ORDER BY io_type ASC, ct_id ASC
    ");

    $rowspan = sql_num_rows($opt_result);

    if ($rowspan < 1) {
        continue;
    }

    $sum = sql_fetch("
        SELECT
            SUM(
                IF(
                    io_type = 1,
                    io_price * ct_qty,
                    (ct_price + io_price) * ct_qty
                )
            ) AS price,
            SUM(ct_qty) AS qty
        FROM {$g5['g5_shop_cart_table']}
        WHERE od_id = '{$od_id_sql}'
          AND it_id = '{$it_id_sql}'
    ");

    switch ((int)$row['ct_send_cost']) {
        case 1:
            $ct_send_cost = '착불';
            break;
        case 2:
            $ct_send_cost = '무료';
            break;
        default:
            $ct_send_cost = '선불';
            break;
    }

    if ((int)$row['it_sc_type'] === 2 && function_exists('get_item_sendcost')) {
        $sendcost = get_item_sendcost(
            $it_id,
            isset($sum['price']) ? (int)$sum['price'] : 0,
            isset($sum['qty']) ? (int)$sum['qty'] : 0,
            $od_id
        );

        if ((int)$sendcost === 0) {
            $ct_send_cost = '무료';
        }
    }

    for ($k = 0; $opt = sql_fetch_array($opt_result); $k++) {
        if ((int)$opt['io_type'] === 1) {
            $opt_price = (int)$opt['io_price'];
        } else {
            $opt_price = (int)$opt['ct_price'] + (int)$opt['io_price'];
        }

        $stotal = $opt_price * (int)$opt['ct_qty'];
        $point_total = (int)$opt['ct_point'] * (int)$opt['ct_qty'];
?>
            <tr>
                <?php if ($k === 0) { ?>
                <td rowspan="<?php echo (int)$rowspan; ?>" class="td_left">
                    <a href="./itemform.php?w=u&amp;it_id=<?php echo urlencode($it_id); ?>">
                        <?php echo $image; ?>
                        <?php echo get_text(stripslashes($row['it_name'])); ?>
                    </a>
                </td>
                <?php } ?>

                <td class="td_left">
                    <?php echo get_text($opt['ct_option']); ?>
                </td>
                <td>
                    <?php echo get_text($opt['ct_status']); ?>
                </td>
                <td class="td_num">
                    <?php echo number_format((int)$opt['ct_qty']); ?>
                </td>
                <td class="td_num">
                    <?php echo number_format($opt_price); ?>
                </td>
                <td class="td_num">
                    <?php echo number_format($stotal); ?>
                </td>
                <td class="td_num">
                    <?php echo number_format((int)$opt['cp_price']); ?>
                </td>
                <td class="td_num">
                    <?php echo number_format($point_total); ?>
                </td>

                <?php if ($k === 0) { ?>
                <td rowspan="<?php echo (int)$rowspan; ?>">
                    <?php echo get_text($ct_send_cost); ?>
                </td>
                <?php } ?>
            </tr>
<?php
    }
}

if ($item_count === 0) {
?>
            <tr>
                <td colspan="9" class="empty_table">
                    현재 로그인한 브랜드의 주문상품이 없습니다.
                </td>
            </tr>
<?php
}
?>
        </tbody>
    </table>
</div>

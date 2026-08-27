<?php
$sub_menu = '500110';
include_once('./_common.php');

include_once(G5_LIB_PATH . '/donuts_delivery.lib.php');
donuts_delivery_install();

/*
 * 매출현황 브랜드 범위
 *
 * 최고관리자:
 *   기존처럼 전체 브랜드 매출 표시
 *
 * 브랜드 계정:
 *   현재 로그인 ID와 상품의 it_brand가 일치하는 상품만 매출 합산
 */
$sale_brand_id = '';
$sale_is_brand = false;

if ($is_admin !== 'super' && !empty($member['mb_id'])) {
    $sale_login_id = trim((string)$member['mb_id']);
    $sale_login_sql = sql_real_escape_string($sale_login_id);

    $sale_brand_row = sql_fetch("
        SELECT brand_id
        FROM donuts_brand
        WHERE LOWER(TRIM(brand_id)) = LOWER('{$sale_login_sql}')
        LIMIT 1
    ");

    if (!empty($sale_brand_row['brand_id'])) {
        $sale_is_brand = true;
        $sale_brand_id = $sale_login_id;
    }
}

/*
 * orderlist.php와 같은 기준으로 현재 브랜드의
 * 상품금액 + 배송관리 배송비를 계산합니다.
 */
if (!function_exists('sale_brand_order_calc')) {
    function sale_brand_order_calc($od_id, $brand_id)
    {
        global $g5;

        $result_data = array(
            'item_total' => 0,
            'shipping_total' => 0,
            'order_total' => 0
        );

        $od_id = trim((string)$od_id);
        $brand_id = trim((string)$brand_id);

        if ($od_id === '' || $brand_id === '') {
            return $result_data;
        }

        $od_id_sql = sql_real_escape_string($od_id);
        $brand_id_sql = sql_real_escape_string($brand_id);

        $default_condition = array();

        $default_result = sql_query("
            SELECT *
            FROM donuts_delivery_conditions
            WHERE LOWER(TRIM(brand_id)) = LOWER('{$brand_id_sql}')
              AND is_default = 1
              AND use_yn = 'Y'
            ORDER BY dc_id DESC
            LIMIT 1
        ", false);

        if ($default_result) {
            $default_condition = sql_fetch_array($default_result);
        }

        $sql = "
            SELECT
                c.it_id,
                c.ct_qty,
                c.ct_price,
                c.io_type,
                c.io_price,
                ps.condition_id,
                ps.group_id,
                dc.dc_id,
                dc.dc_type,
                dc.dc_price,
                dc.dc_minimum,
                dc.dc_qty,
                dg.calc_method
            FROM {$g5['g5_shop_cart_table']} c
            INNER JOIN {$g5['g5_shop_item_table']} i
                ON c.it_id = i.it_id
            LEFT JOIN donuts_delivery_product_settings ps
                ON LOWER(TRIM(ps.brand_id)) = LOWER('{$brand_id_sql}')
               AND ps.it_id = c.it_id
            LEFT JOIN donuts_delivery_conditions dc
                ON dc.dc_id = ps.condition_id
               AND LOWER(TRIM(dc.brand_id)) = LOWER('{$brand_id_sql}')
               AND dc.use_yn = 'Y'
            LEFT JOIN donuts_delivery_groups dg
                ON dg.dg_id = ps.group_id
               AND LOWER(TRIM(dg.brand_id)) = LOWER('{$brand_id_sql}')
               AND dg.use_yn = 'Y'
            WHERE c.od_id = '{$od_id_sql}'
              AND LOWER(TRIM(i.it_brand)) = LOWER('{$brand_id_sql}')
            ORDER BY c.ct_id ASC
        ";

        $query = sql_query($sql, false);

        if (!$query) {
            return $result_data;
        }

        $items = array();

        while ($row = sql_fetch_array($query)) {
            $it_id = trim((string)$row['it_id']);

            if ($it_id === '') {
                continue;
            }

            if (!isset($items[$it_id])) {
                $condition = !empty($row['dc_id'])
                    ? $row
                    : $default_condition;

                $items[$it_id] = array(
                    'amount' => 0,
                    'qty' => 0,
                    'condition' => $condition,
                    'group_id' => !empty($row['group_id'])
                        ? (int)$row['group_id']
                        : 0,
                    'calc_method' => !empty($row['calc_method'])
                        ? strtoupper($row['calc_method'])
                        : 'MAX'
                );
            }

            if ((int)$row['io_type'] === 1) {
                $line_price =
                    (int)$row['io_price'] *
                    (int)$row['ct_qty'];
            } else {
                $line_price =
                    ((int)$row['ct_price'] + (int)$row['io_price']) *
                    (int)$row['ct_qty'];
            }

            $items[$it_id]['amount'] += $line_price;
            $items[$it_id]['qty'] += (int)$row['ct_qty'];
            $result_data['item_total'] += $line_price;
        }

        $groups = array();

        foreach ($items as $it_id => $item) {
            $condition = $item['condition'];
            $fee = 0;

            if (!empty($condition)) {
                $type = !empty($condition['dc_type'])
                    ? trim((string)$condition['dc_type'])
                    : 'conditional';

                $price = isset($condition['dc_price'])
                    ? (int)$condition['dc_price']
                    : 0;

                $minimum = isset($condition['dc_minimum'])
                    ? (int)$condition['dc_minimum']
                    : 0;

                $qty_unit = max(
                    1,
                    isset($condition['dc_qty'])
                        ? (int)$condition['dc_qty']
                        : 1
                );

                switch ($type) {
                    case 'paid':
                        $fee = max(0, $price);
                        break;

                    case 'free':
                        $fee = 0;
                        break;

                    case 'quantity':
                        $fee =
                            max(0, $price) *
                            (int)ceil(
                                max(0, $item['qty']) /
                                $qty_unit
                            );
                        break;

                    case 'amount_range':
                        $dc_id = !empty($condition['dc_id'])
                            ? (int)$condition['dc_id']
                            : 0;

                        if ($dc_id > 0) {
                            $range_result = sql_query("
                                SELECT
                                    min_amount,
                                    max_amount,
                                    dr_price
                                FROM donuts_delivery_condition_ranges
                                WHERE dc_id = '{$dc_id}'
                                ORDER BY sort_order, dr_id
                            ", false);

                            if ($range_result) {
                                while ($range = sql_fetch_array($range_result)) {
                                    $min = (int)$range['min_amount'];

                                    $max = (
                                        $range['max_amount'] === null ||
                                        $range['max_amount'] === ''
                                    )
                                        ? null
                                        : (int)$range['max_amount'];

                                    if (
                                        $item['amount'] >= $min &&
                                        (
                                            $max === null ||
                                            $item['amount'] < $max
                                        )
                                    ) {
                                        $fee = max(
                                            0,
                                            (int)$range['dr_price']
                                        );
                                        break;
                                    }
                                }
                            }
                        }
                        break;

                    case 'conditional':
                    default:
                        $fee = (
                            $minimum > 0 &&
                            $item['amount'] >= $minimum
                        )
                            ? 0
                            : max(0, $price);
                        break;
                }
            }

            if ($item['group_id'] > 0) {
                $group_id = $item['group_id'];

                if (!isset($groups[$group_id])) {
                    $groups[$group_id] = array(
                        'method' =>
                            $item['calc_method'] === 'MIN'
                                ? 'MIN'
                                : 'MAX',
                        'fees' => array()
                    );
                }

                $groups[$group_id]['fees'][] = $fee;
            } else {
                $result_data['shipping_total'] += $fee;
            }
        }

        foreach ($groups as $group) {
            if (empty($group['fees'])) {
                continue;
            }

            if ($group['method'] === 'MIN') {
                $result_data['shipping_total'] += min(
                    $group['fees']
                );
            } else {
                $result_data['shipping_total'] += max(
                    $group['fees']
                );
            }
        }

        $result_data['item_total'] =
            max(0, (int)$result_data['item_total']);

        $result_data['shipping_total'] =
            max(0, (int)$result_data['shipping_total']);

        $result_data['order_total'] =
            $result_data['item_total'] +
            $result_data['shipping_total'];

        return $result_data;
    }
}

/*
 * 브랜드 계정일 때 해당 주문번호가 자기 브랜드 상품을 포함하는지 확인하는
 * SQL EXISTS 조건을 반환합니다.
 */
if (!function_exists('sale_brand_order_exists_sql')) {
    function sale_brand_order_exists_sql($order_alias, $brand_id)
    {
        global $g5;

        $brand_id_sql = sql_real_escape_string(
            trim((string)$brand_id)
        );

        if ($brand_id_sql === '') {
            return '';
        }

        return "
            AND EXISTS (
                SELECT 1
                FROM {$g5['g5_shop_cart_table']} sale_c
                INNER JOIN {$g5['g5_shop_item_table']} sale_i
                    ON sale_c.it_id = sale_i.it_id
                WHERE sale_c.od_id = {$order_alias}.od_id
                  AND LOWER(TRIM(sale_i.it_brand))
                      = LOWER('{$brand_id_sql}')
            )
        ";
    }
}


auth_check_menu($auth, $sub_menu, "r");

$fr_month = isset($_REQUEST['fr_month']) ? preg_replace('/[^0-9 :_\-]/i', '', $_REQUEST['fr_month']) : '';
$to_month = isset($_REQUEST['to_month']) ? preg_replace('/[^0-9 :_\-]/i', '', $_REQUEST['to_month']) : '';

$fr_month = preg_replace("/([0-9]{4})([0-9]{2})/", "\\1-\\2", $fr_month);
$to_month = preg_replace("/([0-9]{4})([0-9]{2})/", "\\1-\\2", $to_month);

$g5['title'] = "$fr_month ~ $to_month 월간 매출현황";
include_once (G5_ADMIN_PATH.'/admin.head.php');

function print_line($save)
{
    $date = preg_replace("/-/", "", $save['od_date']);

    ?>
    <tr>
        <td class="td_alignc"><a href="./sale1date.php?fr_date=<?php echo $date; ?>01&amp;to_date=<?php echo $date; ?>31"><?php echo $save['od_date']; ?></a></td>
        <td class="td_num"><?php echo number_format($save['ordercount']); ?></td>
        <td class="td_numsum"><?php echo number_format($save['orderprice']); ?></td>
        <td class="td_numcoupon"><?php echo number_format($save['ordercoupon']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receiptbank']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receiptvbank']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receiptiche']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receiptcard']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receipteasy']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receipthp']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receiptpoint']); ?></td>
        <td class="td_numcancel1"><?php echo number_format($save['ordercancel']); ?></td>
        <td class="td_numrdy"><?php echo number_format($save['misu']); ?></td>
    </tr>
    <?php
}

$sql = " select od_id,
            SUBSTRING(od_time,1,7) as od_date,
            od_send_cost,
            od_settle_case,
            od_receipt_price,
            od_receipt_point,
            od_cart_price,
            od_cancel_price,
            od_misu,
            (od_cart_price + od_send_cost + od_send_cost2) as orderprice,
            (od_cart_coupon + od_coupon + od_send_coupon) as couponprice
       from {$g5['g5_shop_order_table']} o
      where SUBSTRING(o.od_time,1,7) between '$fr_month' and '$to_month' ";

if ($sale_is_brand) {
    $sql .= sale_brand_order_exists_sql('o', $sale_brand_id);
}

$sql .= " order by o.od_time desc ";
$result = sql_query($sql);
?>

<?php if ($sale_is_brand) { ?>
<div class="local_desc01 local_desc">
    <p>
        매출합계는 현재 로그인 브랜드
        <strong><?php echo get_text($sale_brand_id); ?></strong>
        의 주문상품 금액과 해당 브랜드 배송비만 합산합니다.
        같은 주문번호에 포함된 다른 브랜드 상품금액은 제외됩니다.
    </p>
</div>
<?php } ?>

<div class="tbl_head01 tbl_wrap">

    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <thead>
    <tr>
        <th scope="col">주문월</th>
        <th scope="col">주문수</th>
        <th scope="col">주문합계</th>
        <th scope="col">쿠폰</th>
        <th scope="col">무통장</th>
        <th scope="col">가상계좌</th>
        <th scope="col">계좌이체</th>
        <th scope="col">카드입금</th>
        <th scope="col">간편결제</th>
        <th scope="col">휴대폰</th>
        <th scope="col">포인트입금</th>
        <th scope="col">주문취소</th>
        <th scope="col">미수금</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $save = array('ordercount'=>0, 'orderprice'=>0, 'ordercancel'=>0, 'ordercoupon'=>0, 'receiptbank'=>0, 'receiptvbank'=>0, 'receiptiche'=>0, 'receipthp'=>0, 'receiptcard'=>0, 'receiptpoint'=>0, 'misu'=>0, 'receipteasy'=>0);
    $tot = array('ordercount'=>0, 'orderprice'=>0, 'ordercancel'=>0, 'ordercoupon'=>0, 'receiptbank'=>0, 'receiptvbank'=>0, 'receiptiche'=>0, 'receipthp'=>0, 'receiptcard'=>0, 'receiptpoint'=>0, 'misu'=>0, 'receipteasy'=>0);

    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        /*
         * 브랜드 계정이면 동일 주문번호에 다른 브랜드 상품이 있어도
         * 현재 로그인 브랜드 상품금액 + 현재 브랜드 배송비만 매출로 계산.
         */
        if ($sale_is_brand) {
            $sale_calc = sale_brand_order_calc(
                $row['od_id'],
                $sale_brand_id
            );

            $row['orderprice'] =
                (int)$sale_calc['order_total'];
        }

        if ($i == 0)
            $save['od_date'] = $row['od_date'];

        if ($save['od_date'] != $row['od_date']) {
            print_line($save);
            $save = array('ordercount'=>0, 'orderprice'=>0, 'ordercancel'=>0, 'ordercoupon'=>0, 'receiptbank'=>0, 'receiptvbank'=>0, 'receiptiche'=>0, 'receipthp'=>0, 'receiptcard'=>0, 'receiptpoint'=>0, 'misu'=>0, 'receipteasy'=>0);
            $save['od_date'] = $row['od_date'];
        }

        $save['ordercount']++;
        $save['orderprice']    += $row['orderprice'];
        $save['ordercancel']   += $row['od_cancel_price'];
        $save['ordercoupon']   += $row['couponprice'];
        if($row['od_settle_case'] == '무통장')
            $save['receiptbank']   += $row['od_receipt_price'];
        if($row['od_settle_case'] == '가상계좌')
            $save['receiptvbank']   += $row['od_receipt_price'];
        if($row['od_settle_case'] == '계좌이체')
            $save['receiptiche']   += $row['od_receipt_price'];
        if($row['od_settle_case'] == '휴대폰')
            $save['receipthp']   += $row['od_receipt_price'];
        if($row['od_settle_case'] == '신용카드')
            $save['receiptcard']   += $row['od_receipt_price'];
        $save['receiptpoint']  += $row['od_receipt_point'];
        $save['misu']          += $row['od_misu'];

        $tot['ordercount']++;
        $tot['orderprice']    += $row['orderprice'];
        $tot['ordercancel']   += $row['od_cancel_price'];
        $tot['ordercoupon']   += $row['couponprice'];
        if($row['od_settle_case'] == '무통장')
            $tot['receiptbank']   += $row['od_receipt_price'];
        if($row['od_settle_case'] == '가상계좌')
            $tot['receiptvbank']   += $row['od_receipt_price'];
        if($row['od_settle_case'] == '계좌이체')
            $tot['receiptiche']   += $row['od_receipt_price'];
        if($row['od_settle_case'] == '휴대폰')
            $tot['receipthp']   += $row['od_receipt_price'];
        if($row['od_settle_case'] == '신용카드')
            $tot['receiptcard']   += $row['od_receipt_price'];
        $tot['receiptpoint']  += $row['od_receipt_point'];
        $tot['misu']          += $row['od_misu'];

        if(in_array($row['od_settle_case'], array('간편결제', 'KAKAOPAY', 'lpay', 'inicis_payco', 'inicis_kakaopay', '삼성페이'))) {
            $save['receipteasy'] += $row['od_receipt_price'];
            $tot['receipteasy'] += $row['od_receipt_price'];
        }
    }

    if ($i == 0) {
        echo '<tr><td colspan="12" class="empty_table">자료가 없습니다.</td></tr>';
    } else {
        print_line($save);
    }
    ?>
    </tbody>
    <tfoot>
    <tr>
        <td>합 계</td>
        <td class="td_num_right"><?php echo number_format($tot['ordercount']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['orderprice']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['ordercoupon']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['receiptbank']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['receiptvbank']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['receiptiche']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['receiptcard']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['receipteasy']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['receipthp']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['receiptpoint']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['ordercancel']); ?></td>
        <td class="td_num_right"><?php echo number_format($tot['misu']); ?></td>
    </tr>
    </tfoot>
    </table>
</div>


<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
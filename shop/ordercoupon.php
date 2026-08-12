<?php
include_once('./_common.php');

if ($is_guest)
    exit;

$price = isset($_POST['price']) ? preg_replace('#[^0-9]#', '', $_POST['price']) : 0;

if ($price <= 0)
    die('상품금액이 0원이므로 쿠폰을 사용할 수 없습니다.');

// 쿠폰정보
$sql = " select *
            from {$g5['g5_shop_coupon_table']}
            where mb_id IN ( '{$member['mb_id']}', '전체회원' )
              and cp_method = '2'
              and cp_start <= '" . G5_TIME_YMD . "'
              and cp_end >= '" . G5_TIME_YMD . "'
              and cp_minimum <= '$price' ";
$result = sql_query($sql);
$count = sql_num_rows($result);
?>

<style>
    .tbl_head02 td {
        padding: 8px;
        /* border-bottom: 1px solid #e9e9e9;
        line-height: 1.4em;
        word-break: break-all; */
    }

    .tbl_head02 thead th {
        padding: 8px;
        /* background: #f3f3f3;
        border-top: 1px solid #d3d3d3;
        border-bottom: 1px solid #d3d3d3; */
    }

    /* 할인금액 */
    .td_numbig {
        width: 80px;
        /* text-align: center; */
    }

    /* 적용 */
    .td_mngsmall {
        width: 80px;
        /* text-align: center; */
    }
</style>

<!-- 쿠폰 선택 시작 { -->
<div class="od_coupon_wrap">
    <div id="od_coupon_frm" class="od_coupon pc:!max-w-100 rounded-lg text-sm p-4">
        <h3 class="text-lg font-semibold">쿠폰 선택</h3>
        <div class="tbl_head02 tbl_wrap !my-2">
            <table>
                <caption>쿠폰 선택</caption>
                <thead>
                    <tr>
                        <th scope="col" class="th_left">쿠폰명</th>
                        <th scope="col">할인금액</th>
                        <th scope="col">적용</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $coupon_count = 0;

                    for ($i = 0; $row = sql_fetch_array($result); $i++) {
                        // 사용한 쿠폰인지 체크
                        if (is_used_coupon($member['mb_id'], $row['cp_id']))
                            continue;

                        $coupon_count++;

                        $dc = 0;
                        if ($row['cp_type']) {
                            $dc = floor(($price * ($row['cp_price'] / 100)) / $row['cp_trunc']) * $row['cp_trunc'];
                        } else {
                            $dc = $row['cp_price'];
                        }

                        if ($row['cp_maximum'] && $dc > $row['cp_maximum'])
                            $dc = $row['cp_maximum'];
                    ?>
                        <tr>
                            <td>
                                <input type="hidden" name="o_cp_id[]" value="<?php echo $row['cp_id']; ?>">
                                <input type="hidden" name="o_cp_prc[]" value="<?php echo $dc; ?>">
                                <input type="hidden" name="o_cp_subj[]" value="<?php echo $row['cp_subject']; ?>">
                                <?php echo get_text($row['cp_subject']); ?>
                            </td>
                            <td class="td_numbig"><?php echo number_format($dc); ?></td>
                            <td class="td_mngsmall"><button type="button" class="od_cp_apply text-white bg-gray-700 rounded px-4 py-2">적용</button></td>
                        </tr>
                    <?php
                    }

                    if ($coupon_count === 0) {
                    ?>
                        <tr>
                            <td colspan="3" class="!py-8 text-center text-gray-600">사용할 수 있는 쿠폰이 없습니다.</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="btn_confirm">
            <button type="button" id="od_coupon_close" class="btn_close px-2 py-1">닫기</button>
        </div>
    </div>
</div>
<!-- } 쿠폰 선택 끝 -->
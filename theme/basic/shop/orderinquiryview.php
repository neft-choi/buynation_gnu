<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$g5['title'] = '주문상세내역';
include_once('./_head.php');

// LG 현금영수증 JS
if ($od['od_pg'] == 'lg') {
    if ($default['de_card_test']) {
        echo '<script language="JavaScript" src="' . SHOP_TOSSPAYMENTS_CASHRECEIPT_TEST_JS . '"></script>' . PHP_EOL;
    } else {
        echo '<script language="JavaScript" src="' . SHOP_TOSSPAYMENTS_CASHRECEIPT_REAL_JS . '"></script>' . PHP_EOL;
    }
}
?>

<!-- 주문상세내역 시작 { -->
<div id="sod_fin" class="mx-auto w-full p-4 text-gray-900">
    <div id="sod_fin_no" class="mb-4 rounded border border-gray-200 bg-white p-4 !text-sm">
        <span class="mr-2 text-gray-500">주문번호</span>
        <span class="font-semibold"><?php echo $od_id; ?></span>
    </div>

    <section id="sod_fin_list" class="mb-6">
        <h2 class="mb-3 text-lg font-semibold">주문하신 상품</h2>

        <?php
        $st_count1 = $st_count2 = 0;
        $custom_cancel = false;

        $sql = " select it_id, it_name, ct_send_cost, it_sc_type
                    from {$g5['g5_shop_cart_table']}
                    where od_id = '$od_id'
                    group by it_id
                    order by ct_id ";
        $result = sql_query($sql);
        ?>

        <div class="tbl_head03 tbl_wrap space-y-3">
            <?php
            for ($i = 0; $row = sql_fetch_array($result); $i++) {
                $image = get_it_image($row['it_id'], 55, 55);

                $sql = " select ct_id, it_name, ct_option, ct_qty, ct_price, ct_point, ct_status, io_type, io_price
	                            from {$g5['g5_shop_cart_table']}
	                            where od_id = '$od_id'
	                              and it_id = '{$row['it_id']}'
	                            order by io_type asc, ct_id asc ";
                $res = sql_query($sql);
                $rowspan = sql_num_rows($res) + 1;

                // 합계금액 계산
                $sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((ct_price + io_price) * ct_qty))) as price,
	                                SUM(ct_qty) as qty
	                            from {$g5['g5_shop_cart_table']}
	                            where it_id = '{$row['it_id']}'
	                              and od_id = '$od_id' ";
                $sum = sql_fetch($sql);

                // 배송비
                switch ($row['ct_send_cost']) {
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

                // 조건부무료
                if ($row['it_sc_type'] == 2) {
                    $sendcost = get_item_sendcost($row['it_id'], $sum['price'], $sum['qty'], $od_id);

                    if ($sendcost == 0)
                        $ct_send_cost = '무료';
                }

                for ($k = 0; $opt = sql_fetch_array($res); $k++) {
                    if ($opt['io_type'])
                        $opt_price = $opt['io_price'];
                    else
                        $opt_price = $opt['ct_price'] + $opt['io_price'];

                    $sell_price = $opt_price * $opt['ct_qty'];
                    $point = $opt['ct_point'] * $opt['ct_qty'];

                    if ($k == 0) {
            ?>
                    <?php } ?>
                    <div class="rounded border border-gray-200 bg-white p-4 !space-y-4">
                        <div class="flex gap-4">
                            <div class="sod_img !static border border-gray-100 bg-gray-50"><?php echo $image; ?></div>
                            <div class="sod_name flex-1">
                                <a href="<?php echo shop_item_url($row['it_id']); ?>" class="block text-base font-medium text-gray-900"><?php echo $row['it_name']; ?></a>
                                <div class="sod_opt mt-1 text-sm text-gray-500"><?php echo get_text($opt['ct_option']); ?></div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-x-3 gap-y-2 text-sm">
                            <div class="text-gray-500">총수량</div>
                            <div class="text-right text-gray-900"><?php echo number_format($opt['ct_qty']); ?></div>
                            <div class="text-gray-500">판매가</div>
                            <div class="text-right text-gray-900"><?php echo number_format($opt_price); ?></div>
                            <div class="text-gray-500">포인트</div>
                            <div class="text-right text-gray-900"><?php echo number_format($point); ?></div>
                            <div class="text-gray-500">배송비</div>
                            <div class="text-right text-gray-900"><?php echo $ct_send_cost; ?></div>
                            <div class="text-gray-500">소계</div>
                            <div class="text-right text-gray-900"><?php echo number_format($sell_price); ?></div>
                            <div class="text-gray-500">상태</div>
                            <div class="text-right text-gray-900"><?php echo $opt['ct_status']; ?></div>
                        </div>
                    </div>
            <?php
                    $tot_point       += $point;

                    $st_count1++;
                    if ($opt['ct_status'] == '주문')
                        $st_count2++;
                }
            }

            // 주문 상품의 상태가 모두 주문이면 고객 취소 가능
            if ($st_count1 > 0 && $st_count1 == $st_count2)
                $custom_cancel = true;
            ?>
        </div>

        <div id="sod_sts_wrap" class="mt-4 bg-white p-4">
            <span class="sound_only">상품 상태 설명</span>
            <button type="button" id="sod_sts_explan_open" class="btn_frmline inline-flex items-center gap-2 text-xs text-gray-700">상태설명보기</button>
            <div id="sod_sts_explan" class="mt-3" style="display:none;">
                <dl id="sod_fin_legend" class="grid grid-cols-1 gap-2 text-sm">
                    <div class="grid grid-cols-4 gap-2 rounded bg-gray-100 p-2">
                        <dt class="font-medium text-gray-900">주문</dt>
                        <dd class="col-span-3 text-gray-600">주문이 접수되었습니다.</dd>
                    </div>
                    <div class="grid grid-cols-4 gap-2 rounded bg-gray-100 p-2">
                        <dt class="font-medium text-gray-900">입금</dt>
                        <dd class="col-span-3 text-gray-600">입금(결제)이 완료 되었습니다.</dd>
                    </div>
                    <div class="grid grid-cols-4 gap-2 rounded bg-gray-100 p-2">
                        <dt class="font-medium text-gray-900">준비</dt>
                        <dd class="col-span-3 text-gray-600">상품 준비 중입니다.</dd>
                    </div>
                    <div class="grid grid-cols-4 gap-2 rounded bg-gray-100 p-2">
                        <dt class="font-medium text-gray-900">배송</dt>
                        <dd class="col-span-3 text-gray-600">상품 배송 중입니다.</dd>
                    </div>
                    <div class="grid grid-cols-4 gap-2 rounded bg-gray-100 p-2">
                        <dt class="font-medium text-gray-900">완료</dt>
                        <dd class="col-span-3 text-gray-600">상품 배송이 완료 되었습니다.</dd>
                    </div>
                </dl>
                <button type="button" id="sod_sts_explan_close" class="btn_frmline mt-3 rounded-md border border-gray-300 px-3 py-2 text-xs text-gray-700">상태설명닫기</button>
            </div>
        </div>
    </section>

    <div class="sod_left !w-full">
        <h2 class="mb-3 text-lg font-semibold">결제/배송 정보</h2>
        <?php
        // 총계 = 주문상품금액합계 + 배송비 - 상품할인 - 결제할인 - 배송비할인
        $tot_price = $od['od_cart_price'] + $od['od_send_cost'] + $od['od_send_cost2']
            - $od['od_cart_coupon'] - $od['od_coupon'] - $od['od_send_coupon']
            - $od['od_cancel_price'];

        $receipt_price  = $od['od_receipt_price']
            + $od['od_receipt_point'];
        $cancel_price   = $od['od_cancel_price'];

        $misu = true;
        $misu_price = $tot_price - $receipt_price;

        if ($misu_price == 0 && ($od['od_cart_price'] > $od['od_cancel_price'])) {
            $wanbul = " (완불)";
            $misu = false; // 미수금 없음
        } else {
            $wanbul = display_price($receipt_price);
        }

        // 결제정보처리
        if ($od['od_receipt_price'] > 0)
            $od_receipt_price = display_price($od['od_receipt_price']);
        else
            $od_receipt_price = '아직 입금되지 않았거나 입금정보를 입력하지 못하였습니다.';

        $app_no_subj = '';
        $disp_bank = true;
        $disp_receipt = false;
        if ($od['od_settle_case'] == '신용카드' || $od['od_settle_case'] == 'KAKAOPAY' || is_inicis_order_pay($od['od_settle_case'])) {
            $app_no_subj = '승인번호';
            $app_no = $od['od_app_no'];
            $disp_bank = false;
            $disp_receipt = true;
        } else if ($od['od_settle_case'] == '간편결제') {
            $app_no_subj = '승인번호';
            $app_no = $od['od_app_no'];
            $disp_bank = false;
            $disp_receipt = true;
        } else if ($od['od_settle_case'] == '휴대폰') {
            $app_no_subj = '휴대폰번호';
            $app_no = $od['od_bank_account'];
            $disp_bank = false;
            $disp_receipt = true;
        } else if ($od['od_settle_case'] == '가상계좌' || $od['od_settle_case'] == '계좌이체') {
            $app_no_subj = '거래번호';
            $app_no = $od['od_tno'];

            if (function_exists('shop_is_taxsave') && $misu_price == 0 && shop_is_taxsave($od, true) === 2) {
                $disp_receipt = true;
            }
        }
        ?>

        <section id="sod_fin_orderer">
            <h3 class="text-base font-semibold">주문하신 분</h3>

            <div class="border border-gray-200 bg-white p-4">
                <div class="grid grid-cols-3 gap-2 text-sm">
                    <div class="text-gray-500">이 름</div>
                    <div class="col-span-2 text-right"><?php echo get_text($od['od_name']); ?></div>
                    <div class="text-gray-500">전화번호</div>
                    <div class="col-span-2 text-right"><?php echo get_text($od['od_tel']); ?></div>
                    <div class="text-gray-500">핸드폰</div>
                    <div class="col-span-2 text-right"><?php echo get_text($od['od_hp']); ?></div>
                    <div class="text-gray-500">주 소</div>
                    <div class="col-span-2 text-right"><?php echo get_text(sprintf("(%s%s)", $od['od_zip1'], $od['od_zip2']) . ' ' . print_address($od['od_addr1'], $od['od_addr2'], $od['od_addr3'], $od['od_addr_jibeon'])); ?></div>
                    <div class="text-gray-500">E-mail</div>
                    <div class="col-span-2 text-right break-all"><?php echo get_text($od['od_email']); ?></div>
                </div>
            </div>
        </section>

        <section id="sod_fin_receiver" class="mt-4">
            <h3 class="text-base font-semibold">받으시는 분</h3>

            <div class="border border-gray-200 bg-white p-4">
                <div class="grid grid-cols-3 gap-2 text-sm">
                    <div class="text-gray-500">이 름</div>
                    <div class="col-span-2 text-right"><?php echo get_text($od['od_b_name']); ?></div>
                    <div class="text-gray-500">전화번호</div>
                    <div class="col-span-2 text-right"><?php echo get_text($od['od_b_tel']); ?></div>
                    <div class="text-gray-500">핸드폰</div>
                    <div class="col-span-2 text-right"><?php echo get_text($od['od_b_hp']); ?></div>
                    <div class="text-gray-500">주 소</div>
                    <div class="col-span-2 text-right"><?php echo get_text(sprintf("(%s%s)", $od['od_b_zip1'], $od['od_b_zip2']) . ' ' . print_address($od['od_b_addr1'], $od['od_b_addr2'], $od['od_b_addr3'], $od['od_b_addr_jibeon'])); ?></div>
                    <?php
                    // 희망배송일을 사용한다면
                    if ($default['de_hope_date_use']) {
                    ?>
                        <div class="text-gray-500">희망배송일</div>
                        <div class="col-span-2 text-right"><?php echo substr($od['od_hope_date'], 0, 10) . ' (' . get_yoil($od['od_hope_date']) . ')'; ?></div>
                    <?php }
                    if ($od['od_memo']) {
                    ?>
                        <div class="text-gray-500">전하실 말씀</div>
                        <div class="col-span-2 text-right"><?php echo conv_content($od['od_memo'], 0); ?></div>
                    <?php } ?>
                </div>
            </div>
        </section>

        <section id="sod_fin_dvr" class="mt-4">
            <h3 class="text-base font-semibold">배송정보</h3>

            <div class="border border-gray-200 bg-white p-4">
                <div class="grid grid-cols-3 gap-2 text-sm">
                    <?php if ($od['od_invoice'] && $od['od_delivery_company']) { ?>
                        <div class="text-gray-500">배송회사</div>
                        <div class="col-span-2 text-right"><?php echo $od['od_delivery_company']; ?> <?php echo get_delivery_inquiry($od['od_delivery_company'], $od['od_invoice'], 'dvr_link'); ?></div>
                        <div class="text-gray-500">운송장번호</div>
                        <div class="col-span-2 text-right"><?php echo $od['od_invoice']; ?></div>
                        <div class="text-gray-500">배송일시</div>
                        <div class="col-span-2 text-right"><?php echo $od['od_invoice_time']; ?></div>
                    <?php } else { ?>
                        <div class="col-span-3 rounded-md bg-gray-50 p-3 text-center text-gray-600">아직 배송하지 않았거나 배송정보를 입력하지 못하였습니다.</div>
                    <?php } ?>
                </div>
            </div>
        </section>
    </div>

    <div class="sod_right mt-6 !w-full !clear-both !pb-20">
        <div id="sod_bsk_tot2" class="space-y-2 rounded border !border-gray-200 bg-white p-3">
            <div class="sod_bsk_dvr grid grid-cols-2 items-center text-sm">
                <span class="text-gray-500">주문총액</span>
                <span class="text-right font-semibold"><?php echo number_format($od['od_cart_price']); ?> 원</span>
            </div>
            <?php if ($od['od_cart_coupon'] > 0) { ?>
                <div class="sod_bsk_dvr grid grid-cols-2 items-center text-sm">
                    <span class="text-gray-500">개별상품 쿠폰할인</span>
                    <span class="text-right font-semibold"><?php echo number_format($od['od_cart_coupon']); ?> 원</span>
                </div>
            <?php } ?>
            <?php if ($od['od_coupon'] > 0) { ?>
                <div class="sod_bsk_dvr grid grid-cols-2 items-center text-sm">
                    <span class="text-gray-500">주문금액 쿠폰할인</span>
                    <span class="text-right font-semibold"><?php echo number_format($od['od_coupon']); ?> 원</span>
                </div>
            <?php } ?>
            <?php if ($od['od_send_cost'] > 0) { ?>
                <div class="sod_bsk_dvr grid grid-cols-2 items-center text-sm">
                    <span class="text-gray-500">배송비</span>
                    <span class="text-right font-semibold"><?php echo number_format($od['od_send_cost']); ?> 원</span>
                </div>
            <?php } ?>
            <?php if ($od['od_send_coupon'] > 0) { ?>
                <div class="sod_bsk_dvr grid grid-cols-2 items-center text-sm">
                    <span class="text-gray-500">배송비 쿠폰할인</span>
                    <span class="text-right font-semibold"><?php echo number_format($od['od_send_coupon']); ?> 원</span>
                </div>
            <?php } ?>
            <?php if ($od['od_send_cost2'] > 0) { ?>
                <div class="sod_bsk_dvr grid grid-cols-2 items-center text-sm">
                    <span class="text-gray-500">추가배송비</span>
                    <span class="text-right font-semibold"><?php echo number_format($od['od_send_cost2']); ?> 원</span>
                </div>
            <?php } ?>
            <?php if ($od['od_cancel_price'] > 0) { ?>
                <div class="sod_bsk_dvr grid grid-cols-2 items-center text-sm">
                    <span class="text-gray-500">취소금액</span>
                    <span class="text-right font-semibold"><?php echo number_format($od['od_cancel_price']); ?> 원</span>
                </div>
            <?php } ?>
            <div class="sod_bsk_cnt grid grid-cols-2 items-center border-t border-gray-200 pt-2 text-sm">
                <span class="text-gray-500">총계</span>
                <span class="text-right font-semibold"><?php echo number_format($tot_price); ?> 원</span>
            </div>
            <div class="sod_bsk_point grid grid-cols-2 items-center text-sm">
                <span class="text-gray-500">적립포인트</span>
                <span class="text-right font-semibold"><?php echo number_format($tot_point); ?> 점</span>
            </div>

            <div class="sod_fin_tot grid grid-cols-2 items-center text-sm"><span class="text-gray-500">총 구매액</span><span class="text-right font-semibold"><?php echo display_price($tot_price); ?></span></div>
            <?php
            if ($misu_price > 0) {
                echo '<div class="sod_fin_tot grid grid-cols-2 items-center text-sm">';
                echo '<span class="text-gray-500">미결제액</span>' . PHP_EOL;
                echo '<span class="text-right font-semibold">' . display_price($misu_price) . '</span>';
                echo '</div>';
            }
            ?>
            <div id="alrdy" class="sod_fin_tot rounded-md bg-gray-50 p-2">
                <div class="grid grid-cols-2 items-center text-sm">
                    <span class="text-gray-500">결제액</span>
                    <span class="text-right font-semibold"><?php echo $wanbul; ?></span>
                </div>
                <?php if ($od['od_receipt_point']) {    //포인트로 결제한 내용이 있으면 
                ?>
                    <div class="mt-2 space-y-1 border-t border-gray-200 pt-2 text-xs">
                        <p class="grid grid-cols-2"><span class="title text-gray-500">포인트 결제</span><span class="text-right"><?php echo number_format($od['od_receipt_point']); ?>점</span></p>
                        <p class="grid grid-cols-2"><span class="title text-gray-500">실결제</span><span class="text-right"><?php echo number_format($od['od_receipt_price']); ?>원</span></p>
                    </div>
                <?php } ?>
            </div>
        </div>

        <section id="sod_fin_pay">
            <h3 class="my-2 text-base font-semibold">결제정보</h3>
            <div class="space-y-2 bg-white p-4 text-sm">
                <div class="grid grid-cols-3 gap-2">
                    <div class="text-gray-500">주문번호</div>
                    <div class="col-span-2 text-right"><?php echo $od_id; ?></div>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div class="text-gray-500">주문일시</div>
                    <div class="col-span-2 text-right"><?php echo $od['od_time']; ?></div>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div class="text-gray-500">결제방식</div>
                    <div class="col-span-2 text-right"><?php echo check_pay_name_replace($od['od_settle_case'], $od, 1); ?></div>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div class="text-gray-500">결제금액</div>
                    <div class="col-span-2 text-right"><?php echo $od_receipt_price; ?></div>
                </div>
                <?php
                if ($od['od_receipt_price'] > 0) {
                ?>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="text-gray-500">결제일시</div>
                        <div class="col-span-2 text-right"><?php echo $od['od_receipt_time']; ?></div>
                    </div>
                <?php
                }

                // 승인번호, 휴대폰번호, 거래번호
                if ($app_no_subj && $app_no) {
                ?>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="text-gray-500"><?php echo $app_no_subj; ?></div>
                        <div class="col-span-2 text-right"><?php echo $app_no; ?></div>
                    </div>
                <?php
                }

                // 계좌정보
                if ($disp_bank) {
                ?>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="text-gray-500">입금자명</div>
                        <div class="col-span-2 text-right"><?php echo get_text($od['od_deposit_name']); ?></div>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="text-gray-500">입금계좌</div>
                        <div class="col-span-2 text-right"><?php echo get_text($od['od_bank_account']); ?></div>
                    </div>
                <?php
                }

                if ($disp_receipt) {
                ?>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="text-gray-500">영수증</div>
                        <div class="col-span-2 text-right">
                            <?php
                            if ($od['od_settle_case'] == '휴대폰') {
                                if ($od['od_pg'] == 'lg') {
                                    require_once G5_SHOP_PATH . '/settle_lg.inc.php';
                                    $LGD_TID      = $od['od_tno'];
                                    $LGD_MERTKEY  = $config['cf_lg_mert_key'];
                                    $LGD_HASHDATA = md5($LGD_MID . $LGD_TID . $LGD_MERTKEY);

                                    $hp_receipt_script = 'showReceiptByTID(\'' . $LGD_MID . '\', \'' . $LGD_TID . '\', \'' . $LGD_HASHDATA . '\');';
                                } else if ($od['od_pg'] == 'toss') {
                                    $hp_receipt_script = 'window.open(\'https://dashboard.tosspayments.com/receipt/phone?transactionId=' . $od['od_tno'] . '&ref=PX\',\'receipt\',\'width=430,height=700\');';
                                } else if ($od['od_pg'] == 'inicis') {
                                    $hp_receipt_script = 'window.open(\'https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid=' . $od['od_tno'] . '&noMethod=1\',\'receipt\',\'width=430,height=700\');';
                                } else if ($od['od_pg'] == 'nicepay') {
                                    $hp_receipt_script = 'window.open(\'https://npg.nicepay.co.kr/issue/IssueLoader.do?type=0&TID=' . $od['od_tno'] . '&noMethod=1\',\'receipt\',\'width=430,height=700\');';
                                } else {
                                    $hp_receipt_script = 'window.open(\'' . G5_BILL_RECEIPT_URL . 'mcash_bill&tno=' . $od['od_tno'] . '&order_no=' . $od['od_id'] . '&trade_mony=' . $od['od_receipt_price'] . '\', \'winreceipt\', \'width=500,height=690,scrollbars=yes,resizable=yes\');';
                                }
                            ?>
                                <a href="javascript:;" onclick="<?php echo $hp_receipt_script; ?>" class="inline-block rounded-md border border-gray-300 px-3 py-1 text-xs">영수증 출력</a>
                            <?php
                            }

                            if ($od['od_settle_case'] == '신용카드' || $od['od_settle_case'] == '간편결제' || is_inicis_order_pay($od['od_settle_case']) || (shop_is_taxsave($od, true) && $misu_price == 0)) {
                                if ($od['od_pg'] == 'lg') {
                                    require_once G5_SHOP_PATH . '/settle_lg.inc.php';
                                    $LGD_TID      = $od['od_tno'];
                                    $LGD_MERTKEY  = $config['cf_lg_mert_key'];
                                    $LGD_HASHDATA = md5($LGD_MID . $LGD_TID . $LGD_MERTKEY);

                                    $card_receipt_script = 'showReceiptByTID(\'' . $LGD_MID . '\', \'' . $LGD_TID . '\', \'' . $LGD_HASHDATA . '\');';
                                } else if ($od['od_pg'] == 'toss') {
                                    $card_receipt_script = 'window.open(\'https://dashboard.tosspayments.com/receipt/redirection?transactionId=' . $od['od_tno'] . '&ref=PX\',\'receipt\',\'width=430,height=700\');';
                                } else if ($od['od_pg'] == 'inicis') {
                                    $card_receipt_script = 'window.open(\'https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid=' . $od['od_tno'] . '&noMethod=1\',\'receipt\',\'width=430,height=700\');';
                                } else if ($od['od_pg'] == 'nicepay') {
                                    $card_receipt_script = 'window.open(\'https://npg.nicepay.co.kr/issue/IssueLoader.do?type=0&TID=' . $od['od_tno'] . '&noMethod=1\',\'receipt\',\'width=430,height=700\');';
                                } else {
                                    $card_receipt_script = 'window.open(\'' . G5_BILL_RECEIPT_URL . 'card_bill&tno=' . $od['od_tno'] . '&order_no=' . $od['od_id'] . '&trade_mony=' . $od['od_receipt_price'] . '\', \'winreceipt\', \'width=470,height=815,scrollbars=yes,resizable=yes\');';
                                }
                            ?>
                                <a href="javascript:;" onclick="<?php echo $card_receipt_script; ?>" class="inline-block rounded-md border border-gray-300 px-3 py-1 text-xs">영수증 출력</a>
                            <?php
                            }

                            if ($od['od_settle_case'] == 'KAKAOPAY') {
                                //$card_receipt_script = 'window.open(\'https://mms.cnspay.co.kr/trans/retrieveIssueLoader.do?TID='.$od['od_tno'].'&type=0\', \'popupIssue\', \'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=420,height=540\');';
                                $card_receipt_script = 'window.open(\'https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid=' . $od['od_tno'] . '&noMethod=1\',\'receipt\',\'width=430,height=700\');';
                            ?>
                                <a href="javascript:;" onclick="<?php echo $card_receipt_script; ?>" class="inline-block rounded-md border border-gray-300 px-3 py-1 text-xs">영수증 출력</a>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                <?php
                }

                if ($od['od_receipt_point'] > 0) {
                ?>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="text-gray-500">포인트사용</div>
                        <div class="col-span-2 text-right"><?php echo display_point($od['od_receipt_point']); ?></div>
                    </div>

                <?php
                }

                if ($od['od_refund_price'] > 0) {
                ?>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="text-gray-500">환불 금액</div>
                        <div class="col-span-2 text-right"><?php echo display_price($od['od_refund_price']); ?></div>
                    </div>
                    <?php
                }

                // 현금영수증 발급을 사용하는 경우 또는 현금영수증 발급을 한 주문건이면
                if ((function_exists('shop_is_taxsave') && shop_is_taxsave($od)) || (function_exists('is_order_cashreceipt') && is_order_cashreceipt($od))) {
                    // 미수금이 없고 현금일 경우에만 현금영수증을 발급 할 수 있습니다.
                    if ($misu_price == 0) {
                    ?>
                        <div class="grid grid-cols-3 gap-2">
                            <div class="text-gray-500">현금영수증</div>
                            <div class="col-span-2 text-right">
                                <?php
                                if ($od['od_cash'] && is_order_cashreceipt($od)) {
                                    if ($od['od_pg'] == 'lg') {
                                        require_once G5_SHOP_PATH . '/settle_lg.inc.php';

                                        switch ($od['od_settle_case']) {
                                            case '계좌이체':
                                                $trade_type = 'BANK';
                                                break;
                                            case '가상계좌':
                                                $trade_type = 'CAS';
                                                break;
                                            default:
                                                $trade_type = 'CR';
                                                break;
                                        }
                                        $cash_receipt_script = 'javascript:showCashReceipts(\'' . $LGD_MID . '\',\'' . $od['od_id'] . '\',\'' . $od['od_casseqno'] . '\',\'' . $trade_type . '\',\'' . $CST_PLATFORM . '\');';
                                    } else if ($od['od_pg'] == 'toss') {
                                        $cash_receipt_script = 'window.open(\'https://dashboard.tosspayments.com/receipt/mids/si_' . $config['cf_lg_mid'] . '/orders/' . $od['od_id'] . '/cash-receipt?ref=dashboard\',\'receipt\',\'width=430,height=700\');';
                                    } else if ($od['od_pg'] == 'inicis') {
                                        $cash = unserialize($od['od_cash_info']);
                                        $cash_receipt_script = 'window.open(\'https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/Cash_mCmReceipt.jsp?noTid=' . $cash['TID'] . '&clpaymethod=22\',\'showreceipt\',\'width=380,height=540,scrollbars=no,resizable=no\');';
                                    } else if ($od['od_pg'] == 'nicepay') {
                                        $cash_receipt_script = 'window.open(\'https://npg.nicepay.co.kr/issue/IssueLoader.do?type=1&TID=' . $od['od_tno'] . '&noMethod=1\',\'receipt\',\'width=430,height=700\');';
                                    } else {
                                        require_once G5_SHOP_PATH . '/settle_kcp.inc.php';

                                        $cash = unserialize($od['od_cash_info']);
                                        $cash_receipt_script = 'window.open(\'' . G5_CASH_RECEIPT_URL . $default['de_kcp_mid'] . '&orderid=' . $od_id . '&bill_yn=Y&authno=' . $cash['receipt_no'] . '\', \'taxsave_receipt\', \'width=360,height=647,scrollbars=0,menus=0\');';
                                    }
                                ?>
                                    <a href="javascript:;" onclick="<?php echo $cash_receipt_script; ?>" class="btn_frmline inline-block rounded-md border border-gray-300 px-3 py-1 text-xs">현금영수증 확인하기</a>
                                <?php
                                } else if (shop_is_taxsave($od)) {
                                ?>
                                    <a href="javascript:;" onclick="window.open('<?php echo G5_SHOP_URL; ?>/taxsave.php?od_id=<?php echo $od_id; ?>', 'taxsave', 'width=550,height=400,scrollbars=1,menus=0');" class="btn_frmline is-long-text inline-block rounded-md border border-gray-300 px-3 py-1 text-xs">현금영수증을 발급하시려면 클릭하십시오.</a>
                                <?php } ?>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </section>

        <section id="sod_fin_cancel">
            <?php
            // 취소한 내역이 없다면
            if ($cancel_price == 0) {
                if ($custom_cancel) {
            ?>
                    <button type="button" class="sod_fin_c_btn w-full rounded-md bg-gray-900 px-4 py-3 text-sm text-white">주문 취소하기</button>
                    <div id="sod_cancel_pop" class="fixed inset-0 z-50 hidden">
                        <div id="sod_fin_cancelfrm" class="absolute left-1/2 top-1/2 z-10 w-11/12 -translate-x-1/2 -translate-y-1/2 rounded-xl bg-white p-4">
                            <h2 class="mb-3 text-base font-semibold">주문취소</h2>
                            <form method="post" action="./orderinquirycancel.php" onsubmit="return fcancel_check(this);">
                                <input type="hidden" name="od_id" value="<?php echo $od['od_id']; ?>">
                                <input type="hidden" name="token" value="<?php echo $token; ?>">

                                <label for="cancel_memo" class="sound_only">취소사유</label>
                                <input type="text" name="cancel_memo" id="cancel_memo" required class="frm_input required mb-2 w-full rounded-md border border-gray-300 px-3 py-2 text-sm" size="40" maxlength="100" placeholder="취소사유">
                                <input type="submit" value="확인" class="btn_frmline w-full rounded-md bg-gray-900 px-3 py-2 text-sm text-white">
                            </form>
                            <button class="sod_cls_btn absolute right-3 top-3 text-gray-500">
                                <span class="sound_only">닫기</span>
                                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 6L6 18"></path>
                                    <path d="M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="sod_fin_bg absolute inset-0 bg-black/40"></div>
                    </div>
                    <script>
                        $(function() {
                            $(".sod_fin_c_btn").on("click", function() {
                                $("#sod_cancel_pop").show();
                            });
                            $(".sod_cls_btn").on("click", function() {
                                $("#sod_cancel_pop").hide();
                            });
                        });
                    </script>

                <?php
                }
            } else {
                ?>
                <p class="mt-4 rounded-md bg-gray-100 p-3 text-sm text-gray-700">주문 취소, 반품, 품절된 내역이 있습니다.</p>
            <?php } ?>
        </section>
    </div>

    <?php if ($od['od_settle_case'] == '가상계좌' && $od['od_misu'] > 0 && $default['de_card_test'] && $is_admin && $od['od_pg'] == 'kcp') {
        preg_match("/\s{1}([^\s]+)\s?/", $od['od_bank_account'], $matchs);
        $deposit_no = trim($matchs[1]);
    ?>
        <p class="mt-6 mb-2 text-sm text-gray-600">관리자가 가상계좌 테스트를 한 경우에만 보입니다.</p>
        <div class="tbl_frm01 tbl_wrap rounded-xl border border-gray-200 bg-white p-3">
            <form method="post" action="http://devadmin.kcp.co.kr/Modules/Noti/TEST_Vcnt_Noti_Proc.jsp" target="_blank">
                <div class="mb-3 text-sm font-medium">모의입금처리</div>
                <div class="grid grid-cols-1 gap-3">
                    <div class="grid grid-cols-3 items-center gap-2 text-sm">
                        <label for="e_trade_no" class="text-gray-500">KCP 거래번호</label>
                        <input type="text" id="e_trade_no" name="e_trade_no" value="<?php echo $od['od_tno']; ?>" class="col-span-2 rounded-md border border-gray-300 px-3 py-2">
                    </div>
                    <div class="grid grid-cols-3 items-center gap-2 text-sm">
                        <label for="deposit_no" class="text-gray-500">입금계좌</label>
                        <input type="text" id="deposit_no" name="deposit_no" value="<?php echo $deposit_no; ?>" class="col-span-2 rounded-md border border-gray-300 px-3 py-2">
                    </div>
                    <div class="grid grid-cols-3 items-center gap-2 text-sm">
                        <label for="req_name" class="text-gray-500">입금자명</label>
                        <input type="text" id="req_name" name="req_name" value="<?php echo $od['od_deposit_name']; ?>" class="col-span-2 rounded-md border border-gray-300 px-3 py-2">
                    </div>
                    <div class="grid grid-cols-3 items-center gap-2 text-sm">
                        <label for="noti_url" class="text-gray-500">입금통보 URL</label>
                        <input type="text" id="noti_url" name="noti_url" value="<?php echo G5_SHOP_URL; ?>/settle_kcp_common.php" class="col-span-2 rounded-md border border-gray-300 px-3 py-2">
                    </div>
                </div>
                <div id="sod_fin_test" class="btn_confirm mt-3">
                    <input type="submit" value="입금통보 테스트" class="btn_submit w-full rounded-md bg-gray-900 px-3 py-2 text-sm text-white">
                </div>
            </form>
        </div>
    <?php } ?>

</div>
<!-- } 주문상세내역 끝 -->

<script>
    $(function() {
        $("#sod_sts_explan_open").on("click", function() {
            var $explan = $("#sod_sts_explan");
            if ($explan.is(":animated"))
                return false;

            if ($explan.is(":visible")) {
                $explan.slideUp(200);
                $("#sod_sts_explan_open").text("상태설명보기");
            } else {
                $explan.slideDown(200);
                $("#sod_sts_explan_open").text("상태설명닫기");
            }
        });

        $("#sod_sts_explan_close").on("click", function() {
            var $explan = $("#sod_sts_explan");
            if ($explan.is(":animated"))
                return false;

            $explan.slideUp(200);
            $("#sod_sts_explan_open").text("상태설명보기");
        });
    });

    function fcancel_check(f) {
        if (!confirm("주문을 정말 취소하시겠습니까?"))
            return false;

        var memo = f.cancel_memo.value;
        if (memo == "") {
            alert("취소사유를 입력해 주십시오.");
            return false;
        }

        return true;
    }
</script>

<?php
include_once('./_tail.php');

<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

require_once(G5_SHOP_PATH . '/settle_' . $default['de_pg_service'] . '.inc.php');
require_once(G5_SHOP_PATH . '/settle_kakaopay.inc.php');

if ($default['de_inicis_lpay_use'] || $default['de_inicis_kakaopay_use']) {   //이니시스 Lpay 또는 이니시스 카카오페이 사용시
    require_once(G5_SHOP_PATH . '/inicis/lpay_common.php');
}

if (function_exists('is_use_easypay') && is_use_easypay('global_nhnkcp')) {  // 타 PG 사용시 NHN KCP 네이버페이 사용이 설정되어 있다면
    require_once(G5_SHOP_PATH . '/kcp/global_nhn_kcp.php');
}

// 결제대행사별 코드 include (스크립트 등)
require_once(G5_SHOP_PATH . '/' . $default['de_pg_service'] . '/orderform.1.php');

if ($default['de_inicis_lpay_use'] || $default['de_inicis_kakaopay_use']) {   //이니시스 L.pay 사용시
    require_once(G5_SHOP_PATH . '/inicis/lpay_form.1.php');
}

if (function_exists('is_use_easypay') && is_use_easypay('global_nhnkcp')) {  // 타 PG 사용시 NHN KCP 네이버페이 사용이 설정되어 있다면
    require_once(G5_SHOP_PATH . '/kcp/global_nhn_kcp_form.1.php');
}

if ($is_kakaopay_use) {
    require_once(G5_SHOP_PATH . '/kakaopay/orderform.1.php');
}
?>

<style>
    #wrapper_title {
        display: none;
    }

    #sod_frm section h2 {
        font-size: 18px;
        font-weight: bold;
        border: none;
        padding: 0;
    }

    .sod_left,
    .sod_right {
        width: 100%;
        float: none;
        margin: 0;
    }

    /* 배송요청사항 textarea */
    #sod_frm #od_memo {
        width: 100%;
        border: 1px solid #d0d3db;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    }

    /* 쿠폰 입력 창 모달 */
    #sod_coupon_apply #od_coupon_frm {
        z-index: 10000;
        position: fixed;
        top: 50%;
        left: 50%;
        right: auto;
        width: 100%;
        max-width: 350px;
        height: auto !important;
        max-height: fit-content;
        transform: translate(-50%, -50%);
        border: 1px solid #000;
        background: #fff;
        overflow-y: auto;
    }

    /* 기존 주문하기/취소 버튼 */
    #display_pay_button {
        display: none;
    }
</style>
<form name="forderform" id="forderform" method="post" action="<?php echo $order_action_url; ?>" autocomplete="off">
    <?php
    $sql = " select count(distinct it_id) as cnt
                from {$g5['g5_shop_cart_table']}
                where od_id = '$s_cart_id'
                  and ct_select = '1' ";
    $row = sql_fetch($sql);
    $cart_item_kind_count = (int)$row['cnt'];
    ?>
    <div id="sod_frm" class="sod_frm_pc">

        <div class="flex flex-col-reverse">
            <!-- 주문상품 시작 -->
            <section id="sod_order_products" class="space-y-4 p-4 mb-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <h2>주문상품</h2>
                        <span class="text-sm text-[#8D8D8D]">내일 11/10(월) 도착예정</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-base font-semibold"><?php echo number_format($cart_item_kind_count); ?>건</span>
                        <button type="button" id="order_products_accordion_btn" class="inline-flex h-8 w-8 items-center justify-center text-[#222]" aria-expanded="true" aria-controls="sod_order_products_body">
                            <svg class="accordion-chevron h-5 w-5 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="sod_order_products_body" class="pt-4 border-t border-[#e5e5e5] space-y-4">
                    <ul id="sod_list" class="space-y-4">
                        <?php
                        $tot_point = 0;
                        $tot_sell_price = 0;
                        $tot_cust_price = 0;

                        $goods = $goods_it_id = "";
                        $goods_count = -1;

                        // $s_cart_id 로 현재 장바구니 자료 쿼리
                        $sql = " select a.ct_id,
                        a.it_id,
                        a.it_name,
                        a.ct_price,
                        a.ct_point,
                        a.ct_qty,
                        a.ct_status,
                        a.ct_send_cost,
                        a.it_sc_type,
                        b.ca_id,
                        b.ca_id2,
                        b.ca_id3,
                        b.it_cust_price,
                        b.it_notax
                   from {$g5['g5_shop_cart_table']} a left join {$g5['g5_shop_item_table']} b on ( a.it_id = b.it_id )
                  where a.od_id = '$s_cart_id'
                    and a.ct_select = '1' ";
                        $sql .= " group by a.it_id ";
                        $sql .= " order by a.ct_id ";
                        $result = sql_query($sql);

                        $good_info = '';
                        $it_send_cost = 0;
                        $it_cp_count = 0;

                        $comm_tax_mny = 0; // 과세금액
                        $comm_vat_mny = 0; // 부가세
                        $comm_free_mny = 0; // 면세금액
                        $tot_tax_mny = 0;

                        // 토스페이먼츠 escrowProducts 배열 생성
                        $escrow_products = array();

                        for ($i = 0; $row = sql_fetch_array($result); $i++) {
                            // 합계금액 계산
                            $sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((ct_price + io_price) * ct_qty))) as price,
                            SUM(ct_point * ct_qty) as point,
                            SUM(ct_qty) as qty
                        from {$g5['g5_shop_cart_table']}
                        where it_id = '{$row['it_id']}'
                          and od_id = '$s_cart_id'
                          and ct_select = '1' ";
                            $sum = sql_fetch($sql);

                            if (!$goods) {
                                //$goods = addslashes($row[it_name]);
                                //$goods = get_text($row[it_name]);
                                $goods = preg_replace("/\'|\"|\||\,|\&|\;/", "", $row['it_name']);
                                $goods_it_id = $row['it_id'];
                            }
                            $goods_count++;

                            // 에스크로 상품정보
                            if ($default['de_escrow_use']) {
                                if ($i > 0)
                                    $good_info .= chr(30);
                                $good_info .= "seq=" . ($i + 1) . chr(31);
                                $good_info .= "ordr_numb={$od_id}_" . sprintf("%04d", $i) . chr(31);
                                $good_info .= "good_name=" . addslashes($row['it_name']) . chr(31);
                                $good_info .= "good_cntx=" . $row['ct_qty'] . chr(31);
                                $good_info .= "good_amtx=" . $row['ct_price'] . chr(31);
                            }

                            $image = get_it_image($row['it_id'], 80, 80);

                            $it_name = '<span class="font-medium">' . stripslashes($row['it_name']) . '</span>';
                            $it_options = '';
                            $sql_opt = " select ct_option, ct_qty, io_price
                                            from {$g5['g5_shop_cart_table']}
                                           where it_id = '{$row['it_id']}'
                                             and od_id = '$s_cart_id'
                                             and ct_select = '1'
                                        order by io_type asc, ct_id asc ";
                            $result_opt = sql_query($sql_opt);
                            for ($opt_i = 0; $opt_row = sql_fetch_array($result_opt); $opt_i++) {
                                if ($opt_i === 0) {
                                    $it_options .= '<ul>' . PHP_EOL;
                                }
                                $price_plus = ($opt_row['io_price'] >= 0) ? '+' : '';
                                $it_options .= '<li>' . get_text($opt_row['ct_option']) . ' ' . $opt_row['ct_qty'] . '개 (' . $price_plus . display_price($opt_row['io_price']) . ')</li>' . PHP_EOL;
                            }
                            if ($it_options) {
                                $it_options .= '</ul>';
                            }
                            if ($it_options) {
                                $it_name .= '<div class="sod_opt mt-1 text-sm text-[#7a7a7a]">' . $it_options . '</div>';
                            }

                            // 복합과세금액
                            if ($default['de_tax_flag_use']) {
                                if ($row['it_notax']) {
                                    $comm_free_mny += $sum['price'];
                                } else {
                                    $tot_tax_mny += $sum['price'];
                                }
                            }

                            $point      = $sum['point'];
                            $sell_price = $sum['price'];
                            $cust_price = ((int)$row['it_cust_price'] > 0 ? (int)$row['it_cust_price'] : (int)$row['ct_price']) * (int)$sum['qty'];

                            // 토스페이먼츠 escrowProducts 배열에 상품 정보 추가
                            $escrow_products[] = array(
                                'id'        => $row['ct_id'],
                                'name'      => $row['it_name'],
                                'code'      => $row['it_id'],
                                'unitPrice' => (int) $row['ct_price'],
                                'quantity'  => (int) $row['ct_qty']
                            );

                            // 쿠폰
                            $cp_button = '';
                            if ($is_member) {
                                $cp_count = 0;

                                $sql = " select cp_id
                            from {$g5['g5_shop_coupon_table']}
                            where mb_id IN ( '{$member['mb_id']}', '전체회원' )
                              and cp_start <= '" . G5_TIME_YMD . "'
                              and cp_end >= '" . G5_TIME_YMD . "'
                              and cp_minimum <= '$sell_price'
                              and (
                                    ( cp_method = '0' and cp_target = '{$row['it_id']}' )
                                    OR
                                    ( cp_method = '1' and ( cp_target IN ( '{$row['ca_id']}', '{$row['ca_id2']}', '{$row['ca_id3']}' ) ) )
                                  ) ";
                                $res = sql_query($sql);

                                for ($k = 0; $cp = sql_fetch_array($res); $k++) {
                                    if (is_used_coupon($member['mb_id'], $cp['cp_id']))
                                        continue;

                                    $cp_count++;
                                }

                                if ($cp_count) {
                                    $cp_button = '<button type="button" class="cp_btn mt-1 rounded border border-[#d8d8d8] px-2 py-1 text-xs">쿠폰적용</button>';
                                    $it_cp_count++;
                                }
                            }

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
                                $sendcost = get_item_sendcost($row['it_id'], $sum['price'], $sum['qty'], $s_cart_id);

                                if ($sendcost == 0)
                                    $ct_send_cost = '무료';
                            }
                        ?>
                            <li class="sod_item flex gap-4">
                                <div class="sod_img overflow-hidden rounded-lg"><?php echo $image; ?></div>
                                <div class="min-w-0 flex-1 py-1">
                                    <input type="hidden" name="it_id[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">
                                    <input type="hidden" name="it_name[<?php echo $i; ?>]" value="<?php echo get_text($row['it_name']); ?>">
                                    <input type="hidden" name="it_price[<?php echo $i; ?>]" value="<?php echo $sell_price; ?>">
                                    <input type="hidden" name="cp_id[<?php echo $i; ?>]" value="">
                                    <input type="hidden" name="cp_price[<?php echo $i; ?>]" value="0">
                                    <?php if ($default['de_tax_flag_use']) { ?>
                                        <input type="hidden" name="it_notax[<?php echo $i; ?>]" value="<?php echo $row['it_notax']; ?>">
                                    <?php } ?>

                                    <div class="sod_name">
                                        <?php echo $it_name; ?>
                                        <div class=" font-semibold"><span class="total_price"><?php echo number_format($sell_price); ?></span>원 <span class="ml-2"><?php echo number_format($sum['qty']); ?>건</span></div>
                                        <?php if ($cp_button) {
                                            echo $cp_button;
                                        } ?>
                                        <div class="hidden">
                                            <span class="td_num"><?php echo number_format($sum['qty']); ?></span>
                                            <span class="td_numbig"><?php echo number_format($row['ct_price']); ?></span>
                                            <span class="td_dvr"><?php echo $ct_send_cost; ?></span>
                                            <span class="td_point"><?php echo number_format($point); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </li>

                        <?php
                            $tot_point      += $point;
                            $tot_sell_price += $sell_price;
                            $tot_cust_price += $cust_price;
                        } // for 끝

                        if ($i == 0) {
                            alert('장바구니가 비어 있습니다.', G5_SHOP_URL . '/cart.php');
                        } else {
                            // 배송비 계산
                            $send_cost = get_sendcost($s_cart_id);
                        }

                        // 복합과세처리
                        if ($default['de_tax_flag_use']) {
                            $comm_tax_mny = round(($tot_tax_mny + $send_cost) / 1.1);
                            $comm_vat_mny = ($tot_tax_mny + $send_cost) - $comm_tax_mny;
                        }
                        $tot_item_discount = max(0, $tot_cust_price - $tot_sell_price);
                        ?>
                    </ul>
                </div>

                <div class="rounded-lg bg-[#f2f2f2] p-4 text-[#666]">
                    <p class="mb-2 text-base font-semibold text-[#333]">확인해 주세요</p>
                    <ul class="list-disc space-y-1 pl-5 text-sm">
                        <li>동시 주문이 많은 상품은 결제 후에도 품절될 수 있습니다.</li>
                        <li>품절 상품만 부분 취소되며, 나머지 상품은 정상 배송됩니다.</li>
                        <li>품절로 인한 전체 주문 취소는 불가합니다.</li>
                    </ul>
                </div>
            </section>

            <?php if ($goods_count) $goods .= ' 외 ' . $goods_count . '건'; ?>
            <!-- 주문상품 끝 -->

            <div class="sod_left bg-[#F4F4F4]">
                <input type="hidden" name="od_price" value="<?php echo $tot_sell_price; ?>">
                <input type="hidden" name="org_od_price" value="<?php echo $tot_sell_price; ?>">
                <input type="hidden" name="od_send_cost" value="<?php echo $send_cost; ?>">
                <input type="hidden" name="od_send_cost2" value="0">
                <input type="hidden" name="item_coupon" value="0">
                <input type="hidden" name="od_coupon" value="0">
                <input type="hidden" name="od_send_coupon" value="0">
                <input type="hidden" name="od_goods_name" value="<?php echo $goods; ?>">

                <?php
                // 결제대행사별 코드 include (결제대행사 정보 필드)
                require_once(G5_SHOP_PATH . '/' . $default['de_pg_service'] . '/orderform.2.php');

                if ($is_kakaopay_use) {
                    require_once(G5_SHOP_PATH . '/kakaopay/orderform.2.php');
                }
                ?>

                <!-- 주문자 정보 시작 -->
                <section id="sod_frm_orderer" class="!border-none p-4 !mb-3">
                    <div class="flex items-center justify-between">
                        <h2>주문자 정보</h2>
                        <button type="button" id="orderer_accordion_btn" class="inline-flex h-8 w-8 items-center justify-center text-[#222]" aria-expanded="true" aria-controls="sod_frm_orderer_body">
                            <svg class="accordion-chevron h-5 w-5 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>
                        </button>
                    </div>

                    <div id="sod_frm_orderer_body" class="mt-4">
                        <div class="flex flex-col gap-2 text-sm">
                            <div class="text-sm text-[#666]"><label for="od_name">보내는 분<strong class="sound_only"> 필수</strong></label></div>
                            <div><input type="text" name="od_name" value="<?php echo isset($member['mb_name']) ? get_text($member['mb_name']) : ''; ?>" id="od_name" required class="frm_input required !w-full !h-8 " maxlength="20"></div>

                            <?php if (!$is_member) { // 비회원이면 
                            ?>
                                <div class="text-sm text-[#666]"><label for="od_pwd">비밀번호</label></div>
                                <div>
                                    <span class="frm_info">영,숫자 3~20자 (주문서 조회시 필요)</span>
                                    <input type="password" name="od_pwd" id="od_pwd" required class="frm_input required mt-1 !w-full !h-8" maxlength="20">
                                </div>
                            <?php } ?>

                            <div class="text-sm text-[#666]"><label for="od_tel">전화번호<strong class="sound_only"> 필수</strong></label></div>
                            <div><input type="text" name="od_tel" value="<?php echo get_text($member['mb_tel']); ?>" id="od_tel" required class="frm_input required !w-full !h-8" maxlength="20"></div>

                            <div class="text-sm text-[#666]"><label for="od_hp">핸드폰</label></div>
                            <div><input type="text" name="od_hp" value="<?php echo get_text($member['mb_hp']); ?>" id="od_hp" class="frm_input !w-full !h-8" maxlength="20"></div>

                            <div class="self-start pt-2 text-sm text-[#666]">주소</div>
                            <div class="space-y-1">
                                <label for="od_zip" class="sound_only">우편번호<strong class="sound_only"> 필수</strong></label>
                                <div class="flex items-center gap-2">
                                    <input type="text" name="od_zip" value="<?php echo $member['mb_zip1'] . $member['mb_zip2']; ?>" id="od_zip" required class="frm_input required !w-full !h-8" size="8" maxlength="6" placeholder="우편번호">
                                    <button type="button" class="btn_address shrink-0" onclick="win_zip('forderform', 'od_zip', 'od_addr1', 'od_addr2', 'od_addr3', 'od_addr_jibeon');">주소 검색</button>
                                </div>
                                <input type="text" name="od_addr1" value="<?php echo get_text($member['mb_addr1']) ?>" id="od_addr1" required class="frm_input frm_address required !w-full !h-8" size="60" placeholder="기본주소">
                                <label for="od_addr1" class="sound_only">기본주소<strong class="sound_only"> 필수</strong></label>
                                <input type="text" name="od_addr2" value="<?php echo get_text($member['mb_addr2']) ?>" id="od_addr2" class="frm_input frm_address !w-full !h-8" size="60" placeholder="상세주소">
                                <label for="od_addr2" class="sound_only">상세주소</label>
                                <input type="text" name="od_addr3" value="<?php echo get_text($member['mb_addr3']) ?>" id="od_addr3" class="frm_input frm_address !w-full !h-8" size="60" readonly="readonly" placeholder="참고항목">
                                <label for="od_addr3" class="sound_only">참고항목</label>
                                <input type="hidden" name="od_addr_jibeon" value="<?php echo get_text($member['mb_addr_jibeon']); ?>">
                            </div>

                            <div class="text-sm text-[#666]"><label for="od_email">E-mail<strong class="sound_only"> 필수</strong></label></div>
                            <div><input type="text" name="od_email" value="<?php echo $member['mb_email']; ?>" id="od_email" required class="frm_input required !w-full !h-8" size="35" maxlength="100"></div>

                            <?php if ($default['de_hope_date_use']) { // 배송희망일 사용 
                            ?>
                                <div class="text-sm text-[#666]"><label for="od_hope_date">희망배송일</label></div>
                                <div><input type="text" name="od_hope_date" value="" id="od_hope_date" required class="frm_input required inline-block w-auto max-w-full !h-8" size="11" maxlength="10" readonly="readonly"> 이후로 배송 바랍니다.</div>
                            <?php } ?>
                        </div>
                    </div>
                </section>
                <!-- 주문자 정보 끝 -->

                <!-- 배송지 시작 -->
                <section id="sod_frm_taker" class="!border-none p-4 space-y-4 !mb-3">
                    <div class="flex items-center justify-between">
                        <h2>배송지</h2>
                        <?php if ($is_member) { ?>
                            <a href="<?php echo G5_SHOP_URL; ?>/orderaddress.php" id="order_address" class="flex items-center gap-1 border border-gray-400 rounded-full px-2 py-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right-left-icon lucide-arrow-right-left">
                                    <path d="m16 3 4 4-4 4" />
                                    <path d="M20 7H4" />
                                    <path d="m8 21-4-4 4-4" />
                                    <path d="M4 17h16" />
                                </svg>
                                <span class="text-sm">변경</span>
                            </a>
                        <?php } ?>
                    </div>

                    <div>
                        <?php
                        $addr_list = '';
                        if ($is_member) {
                            // 배송지 이력
                            $sep = chr(30);

                            // 주문자와 동일
                            $addr_list .= '<input type="radio" name="ad_sel_addr" value="same" id="ad_sel_addr_same">' . PHP_EOL;
                            $addr_list .= '<label for="ad_sel_addr_same">주문자와 동일</label>' . PHP_EOL;

                            // 기본배송지
                            $sql = " select *
                                from {$g5['g5_shop_order_address_table']}
                                where mb_id = '{$member['mb_id']}'
                                  and ad_default = '1' ";
                            $row = sql_fetch($sql);
                            if (isset($row['ad_id']) && $row['ad_id']) {
                                $val1 = $row['ad_name'] . $sep . $row['ad_tel'] . $sep . $row['ad_hp'] . $sep . $row['ad_zip1'] . $sep . $row['ad_zip2'] . $sep . $row['ad_addr1'] . $sep . $row['ad_addr2'] . $sep . $row['ad_addr3'] . $sep . $row['ad_jibeon'] . $sep . $row['ad_subject'];
                                $addr_list .= '<input type="radio" name="ad_sel_addr" value="' . get_text($val1) . '" id="ad_sel_addr_def">' . PHP_EOL;
                                $addr_list .= '<label for="ad_sel_addr_def">기본배송지</label>' . PHP_EOL;
                            }

                            // 최근배송지
                            $sql = " select *
                                from {$g5['g5_shop_order_address_table']}
                                where mb_id = '{$member['mb_id']}'
                                  and ad_default = '0'
                                order by ad_id desc
                                limit 1 ";
                            $result = sql_query($sql);
                            for ($i = 0; $row = sql_fetch_array($result); $i++) {
                                $val1 = $row['ad_name'] . $sep . $row['ad_tel'] . $sep . $row['ad_hp'] . $sep . $row['ad_zip1'] . $sep . $row['ad_zip2'] . $sep . $row['ad_addr1'] . $sep . $row['ad_addr2'] . $sep . $row['ad_addr3'] . $sep . $row['ad_jibeon'] . $sep . $row['ad_subject'];
                                $val2 = '<label for="ad_sel_addr_' . ($i + 1) . '">최근배송지(' . ($row['ad_subject'] ? get_text($row['ad_subject']) : get_text($row['ad_name'])) . ')</label>';
                                $addr_list .= '<input type="radio" name="ad_sel_addr" value="' . get_text($val1) . '" id="ad_sel_addr_' . ($i + 1) . '"> ' . PHP_EOL . $val2 . PHP_EOL;
                            }

                            $addr_list .= '<input type="radio" name="ad_sel_addr" value="new" id="od_sel_addr_new">' . PHP_EOL;
                            $addr_list .= '<label for="od_sel_addr_new">신규배송지</label>' . PHP_EOL;
                        } else {
                            // 주문자와 동일
                            $addr_list .= '<input type="checkbox" name="ad_sel_addr" value="same" id="ad_sel_addr_same">' . PHP_EOL;
                            $addr_list .= '<label for="ad_sel_addr_same">주문자와 동일</label>' . PHP_EOL;
                        }
                        ?>

                        <div class="flex flex-col gap-2 text-sm">
                            <div class="text-sm text-[#666]">배송지선택</div>
                            <div>
                                <div class="order_choice_place"><?php echo $addr_list; ?></div>
                            </div>

                            <?php if ($is_member) { ?>
                                <div class="text-sm text-[#666]"><label for="ad_subject">배송지명</label></div>
                                <div>
                                    <input type="text" name="ad_subject" id="ad_subject" class="frm_input !w-full !h-8" maxlength="20">
                                    <input type="checkbox" name="ad_default" id="ad_default" value="1">
                                    <label for="ad_default">기본배송지로 설정</label>
                                </div>
                            <?php } ?>

                            <div class="text-sm text-[#666]"><label for="od_b_name">이름<strong class="sound_only"> 필수</strong></label></div>
                            <div><input type="text" name="od_b_name" id="od_b_name" required class="frm_input required !w-full !h-8" maxlength="20"></div>

                            <div class="text-sm text-[#666]"><label for="od_b_tel">전화번호<strong class="sound_only"> 필수</strong></label></div>
                            <div><input type="text" name="od_b_tel" id="od_b_tel" required class="frm_input required !w-full !h-8" maxlength="20"></div>

                            <div class="text-sm text-[#666]"><label for="od_b_hp">핸드폰</label></div>
                            <div><input type="text" name="od_b_hp" id="od_b_hp" class="frm_input !w-full !h-8" maxlength="20"></div>

                            <div class="self-start pt-2 text-sm text-[#666]">주소</div>
                            <div id="sod_frm_addr" class="space-y-1">
                                <label for="od_b_zip" class="sound_only">우편번호<strong class="sound_only"> 필수</strong></label>
                                <div class="flex items-center gap-2">
                                    <input type="text" name="od_b_zip" id="od_b_zip" required class="frm_input required !w-full !h-8" size="8" maxlength="6" placeholder="우편번호">
                                    <button type="button" class="btn_address shrink-0" onclick="win_zip('forderform', 'od_b_zip', 'od_b_addr1', 'od_b_addr2', 'od_b_addr3', 'od_b_addr_jibeon');">주소 검색</button>
                                </div>
                                <input type="text" name="od_b_addr1" id="od_b_addr1" required class="frm_input frm_address required !w-full !h-8" size="60" placeholder="기본주소">
                                <label for="od_b_addr1" class="sound_only">기본주소<strong> 필수</strong></label>
                                <input type="text" name="od_b_addr2" id="od_b_addr2" class="frm_input frm_address !w-full !h-8" size="60" placeholder="상세주소">
                                <label for="od_b_addr2" class="sound_only">상세주소</label>
                                <input type="text" name="od_b_addr3" id="od_b_addr3" readonly="readonly" class="frm_input frm_address !w-full !h-8" size="60" placeholder="참고항목">
                                <label for="od_b_addr3" class="sound_only">참고항목</label>
                                <input type="hidden" name="od_b_addr_jibeon" value="">
                            </div>

                            <div class="text-sm text-[#666]"><label for="od_memo">배송요청사항</label></div>
                            <div><textarea name="od_memo" id="od_memo"></textarea></div>
                        </div>
                    </div>
                </section>
                <!-- 배송지 끝 -->
            </div>
        </div>

        <?php
        $oc_cnt = $sc_cnt = 0;
        if ($is_member) {
            // 주문쿠폰
            $sql = " select cp_id
                    from {$g5['g5_shop_coupon_table']}
                    where mb_id IN ( '{$member['mb_id']}', '전체회원' )
                      and cp_method = '2'
                      and cp_start <= '" . G5_TIME_YMD . "'
                      and cp_end >= '" . G5_TIME_YMD . "'
                      and cp_minimum <= '$tot_sell_price' ";
            $res = sql_query($sql);

            for ($k = 0; $cp = sql_fetch_array($res); $k++) {
                if (is_used_coupon($member['mb_id'], $cp['cp_id']))
                    continue;

                $oc_cnt++;
            }

            if ($send_cost > 0) {
                // 배송비쿠폰
                $sql = " select cp_id
                        from {$g5['g5_shop_coupon_table']}
                        where mb_id IN ( '{$member['mb_id']}', '전체회원' )
                          and cp_method = '3'
                          and cp_start <= '" . G5_TIME_YMD . "'
                          and cp_end >= '" . G5_TIME_YMD . "'
                          and cp_minimum <= '$tot_sell_price' ";
                $res = sql_query($sql);

                for ($k = 0; $cp = sql_fetch_array($res); $k++) {
                    if (is_used_coupon($member['mb_id'], $cp['cp_id']))
                        continue;

                    $sc_cnt++;
                }
            }
        }
        $buycle_arr = [
            [
                '바이클' => '조인성',
                '기여금' => 5,
                '할인율' => 10
            ],
            [
                '바이클' => '김우빈',
                '기여금' => 5,
                '할인율' => 10
            ]
        ];
        $coupon_total_count = (int)$it_cp_count + (int)$oc_cnt + (int)$sc_cnt;
        $point_unit = (int)$default['de_settle_point_unit'];
        if ($point_unit < 1) {
            $point_unit = 1;
        }
        $temp_point = 0;
        if ($is_member && $config['cf_use_point'] && (int)$member['mb_point'] >= (int)$default['de_settle_min_point']) {
            $temp_point = (int)$default['de_settle_max_point'];
            if ($temp_point > (int)$tot_sell_price) {
                $temp_point = (int)$tot_sell_price;
            }
            if ($temp_point > (int)$member['mb_point']) {
                $temp_point = (int)$member['mb_point'];
            }
            $temp_point = (int)((int)($temp_point / $point_unit) * $point_unit);
        }
        ?>

        <div class="sod_right bg-[#F4F4F4]">

            <!-- 쿠폰 적용 섹션 시작 -->
            <section id="sod_coupon_apply" class="space-y-4 p-4 mb-3 bg-white">
                <div class="flex items-center justify-between ">
                    <h2 class="!m-0 !p-0 text-lg font-bold">쿠폰 적용</h2>
                    <div class="flex items-center gap-2 font-semibold">
                        <span>보유 <?php echo number_format($coupon_total_count); ?>개</span>
                        <button type="button" id="coupon_accordion_btn" class="inline-flex h-8 w-8 items-center justify-center text-[#222]" aria-expanded="true" aria-controls="sod_coupon_apply_body">
                            <svg class="accordion-chevron h-5 w-5 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="sod_coupon_apply_body" class="space-y-4">
                    <button type="button" id="od_coupon_btn" class="flex w-full items-center justify-between rounded-lg border border-[#dfdfdf] bg-white px-4 py-4 text-left">
                        <?php if ((int)$coupon_total_count > 0) { ?>
                            <span id="od_coupon_btn_label" class="text-sm">쿠폰 선택</span>
                            <span class="flex items-center gap-2">
                                <span id="od_coupon_btn_badge" class="hidden rounded-full bg-[#ffe7dc] px-2 py-1 text-xs text-[#ef5a29]">최대적용중</span>
                                <span id="od_coupon_btn_amount" class="text-sm text-[#666]">할인 없음</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-4">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </span>
                        <?php } else { ?>
                            <span class="text-sm">사용 가능한 쿠폰이 없습니다.</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-4">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                        <?php } ?>
                    </button>

                    <div class="flex items-center justify-end gap-3">
                        <span class="text-sm">최대 할인 적용</span>
                        <button type="button" id="max_coupon_toggle" aria-pressed="false" class="relative h-5 w-9 rounded-full bg-gray-300 transition-colors duration-200">
                            <span id="max_coupon_toggle_thumb" class="absolute left-0.5 top-0.5 h-4 w-4 translate-x-0 rounded-full bg-white transition-transform duration-200"></span>
                        </button>
                    </div>
                </div>
            </section>
            <!-- 쿠폰 적용 섹션 끝 -->

            <!-- 포인트 섹션 시작 -->
            <section id="sod_point_apply" class="space-y-4 p-4 mb-3 bg-white">
                <div class="flex items-center justify-between">
                    <h2 class="!m-0 !p-0 text-lg font-bold">포인트</h2>
                    <button type="button" id="point_accordion_btn" class="inline-flex h-8 w-8 items-center justify-center text-[#222]" aria-expanded="true" aria-controls="sod_point_apply_body">
                        <svg class="accordion-chevron h-5 w-5 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>
                    </button>
                </div>

                <div id="sod_point_apply_body" class="space-y-4">
                    <div class="flex items-center justify-between text-sm">
                        <span>바이네이션 포인트</span>
                        <span>보유 <?php echo number_format((int)$member['mb_point']); ?>P</span>
                    </div>

                    <?php if ($temp_point > 0) { ?>
                        <div class="flex items-center gap-2">
                            <label class="flex h-10 flex-1 items-center gap-2 rounded-md border border-[#dfdfdf] bg-white px-4 text-[#8a8a8a]">
                                <input type="hidden" name="max_temp_point" value="<?php echo $temp_point; ?>">
                                <input type="text" name="od_temp_point" value="0" id="od_temp_point" class="w-full flex-1 bg-transparent text-sm text-[#222]" inputmode="numeric">
                                <span>P</span>
                            </label>
                            <button type="button" id="use_all_point_btn" class="h-11 shrink-0 rounded-md bg-gray-900 px-4 text-sm text-white transition-colors hover:bg-gray-700 cursor-pointer disabled:bg-gray-200 disabled:text-gray-400 disabled:hover:bg-gray-200 disabled:cursor-not-allowed">전액사용</button>
                        </div>
                        <div class="text-right text-xs text-[#666]">
                            최대 사용 가능 <em id="use_max_point" class="not-italic"><?php echo number_format($temp_point); ?>점</em>
                        </div>
                    <?php } else { ?>
                        <div class="flex items-center gap-2">
                            <div class="flex h-10 flex-1 items-center justify-between rounded-md border border-[#dfdfdf] bg-[#f7f7f7] px-4 text-[#b0b0b0]">
                                <span>0</span>
                                <span>P</span>
                            </div>
                            <input type="hidden" name="max_temp_point" value="0">
                            <button type="button" id="use_all_point_btn" disabled class="h-11 shrink-0 rounded-md bg-gray-200 px-4 text-sm text-gray-400 transition-colors hover:bg-gray-700 cursor-pointer disabled:bg-gray-200 disabled:text-gray-400 disabled:hover:bg-gray-200">전액사용</button>
                        </div>
                    <?php } ?>
                </div>
            </section>
            <!-- 포인트 섹션 끝 -->
            <!-- 기여할 바이클 선택 섹션 -->
            <section id="sod_buycle_apply" class="space-y-4 p-4 mb-3 bg-white">
                <div class="flex items-center justify-between ">
                    <h2 class="!m-0 !p-0 text-lg font-bold">기여할 바이클</h2>
                    <div class="flex items-center gap-2 font-semibold">
                        <span>구독한 바이클 <?php echo number_format(count($buycle_arr)); ?>개</span>
                        <button type="button" id="buycle_accordion_btn" class="inline-flex h-8 w-8 items-center justify-center text-[#222]" aria-expanded="true" aria-controls="sod_buycle_apply_body">
                            <svg class="accordion-chevron h-5 w-5 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m18 15-6-6-6 6" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="sod_buycle_apply_body" class="space-y-4">
                    <button type="button" id="od_buycle_btn" class="flex w-full items-center justify-between rounded-lg border border-[#dfdfdf] bg-white px-4 py-4 text-left">
                        <?php

                        if ((int)$buycle_arr > 0) {

                        ?>
                            <!-- <span id="od_buycle_btn_label" class="text-sm">바이클 선택</span> -->
                            <span class="items-center w-full">
                                <select class="w-full">
                                    <?php foreach ($buycle_arr as $item): ?>
                                        <option class="item">
                                            이름: <?= $item['바이클'] ?>
                                            기여금: <?= $item['기여금'] ?>%
                                            할인율: <?= $item['할인율'] ?>%
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </span>
                        <?php } else { ?>
                            <span class="text-sm">아직 구독한 바이클이 없습니다1.</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-4">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                        <?php } ?>
                    </button>

                    <div class="flex items-center justify-end gap-3">
                        <span class="text-sm">최대 할인 적용</span>
                        <button type="button" id="max_coupon_toggle" aria-pressed="false" class="relative h-5 w-9 rounded-full bg-gray-300 transition-colors duration-200">
                            <span id="max_coupon_toggle_thumb" class="absolute left-0.5 top-0.5 h-4 w-4 translate-x-0 rounded-full bg-white transition-transform duration-200"></span>
                        </button>
                    </div>
                </div>
            </section>
            <!-- 기여할 바이클 선택 섹션 -->
            <!-- 결제수단 섹션 시작 -->
            <section id="sod_settle_ui" class="space-y-4 p-4 mb-3 bg-white">
                <h2>결제수단</h2>

                <div id="sod_settle_tabs" class="grid grid-cols-4 gap-2">
                    <button type="button" data-settle-tab="cash" class="settle-tab-btn h-12 rounded-md border border-gray-300 bg-white text-sm text-gray-900">현금결제</button>
                    <button type="button" data-settle-tab="easy" class="settle-tab-btn h-12 rounded-md border border-gray-800 bg-gray-800 text-sm text-white">간편결제</button>
                    <button type="button" data-settle-tab="card" class="settle-tab-btn h-12 rounded-md border border-gray-300 bg-white text-sm text-gray-900">카드결제</button>
                    <button type="button" data-settle-tab="gift" class="settle-tab-btn h-12 rounded-md border border-gray-300 bg-white text-sm text-gray-900">문화상품권</button>
                </div>

                <div id="sod_cash_pay_ui" class="hidden grid grid-cols-3 gap-2">
                    <button type="button" class="settle-cash-btn h-12 rounded-md border border-gray-300 bg-white px-2 text-sm text-gray-900" data-settle-target="#od_settle_bank">무통장입금</button>
                    <button type="button" class="settle-cash-btn h-12 rounded-md border border-gray-300 bg-white px-2 text-sm text-gray-900" data-settle-target="#od_settle_iche">계좌이체</button>
                    <button type="button" class="settle-cash-btn h-12 rounded-md border border-gray-300 bg-white px-2 text-sm text-gray-900" data-settle-target="#od_settle_vbank">가상계좌</button>
                </div>

                <div id="sod_cash_bank_ui" class="hidden space-y-3 rounded-md border border-gray-200 bg-gray-50 p-3">
                    <div class="space-y-2">
                        <label for="ui_od_bank_account" class="block text-sm text-gray-700">입금 계좌</label>
                        <select id="ui_od_bank_account" class="h-11 w-full rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-900">
                            <?php
                            if ($default['de_bank_use']) {
                                $ui_bank_accounts = array_map('trim', explode("\n", trim($default['de_bank_account'])));
                                $ui_bank_accounts = array_filter($ui_bank_accounts, 'strlen');
                                if (empty($ui_bank_accounts)) {
                                    echo '<option value="">등록된 계좌가 없습니다.</option>';
                                } else {
                                    foreach ($ui_bank_accounts as $ui_bank_account) {
                                        echo '<option value="' . $ui_bank_account . '">' . $ui_bank_account . '</option>' . PHP_EOL;
                                    }
                                }
                            } else {
                                echo '<option value="">무통장입금 비활성화</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label for="ui_od_deposit_name" class="block text-sm text-gray-700">입금자명</label>
                        <input type="text" id="ui_od_deposit_name" class="h-11 w-full rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-900" maxlength="20" placeholder="입금자명을 입력해 주세요">
                    </div>
                </div>

                <div id="sod_easy_pay_ui" class="grid grid-cols-3 gap-2">
                    <button type="button" class="rounded-md border border-[#2f3134] bg-white p-3 text-center">
                        <div class="mb-2 text-xl font-semibold leading-none text-[#1f57c3]">toss pay</div>
                        <div class="text-sm">토스페이</div>
                    </button>
                    <button type="button" class="rounded-md border border-[#dfdfdf] bg-white p-3 text-center">
                        <div class="mb-2 text-xl font-semibold leading-none text-[#16c15f]">N pay</div>
                        <div class="text-sm">네이버페이</div>
                    </button>
                    <button type="button" class="rounded-md border border-[#dfdfdf] bg-white p-3 text-center">
                        <div class="mx-auto mb-2 flex h-7 w-14 items-center justify-center rounded-full bg-[#f2da00] text-sm font-bold leading-none">pay</div>
                        <div class="text-sm">카카오페이</div>
                    </button>
                    <button type="button" class="rounded-md border border-[#dfdfdf] bg-white p-3 text-center">
                        <div class="mb-2 text-xl font-semibold leading-none text-[#f33446]">PAYCO</div>
                        <div class="text-sm">페이코</div>
                    </button>
                    <button type="button" class="rounded-md border border-[#dfdfdf] bg-white p-3 text-center">
                        <div class="mb-2 text-xl font-semibold leading-none text-[#2455c4]">SAMSUNG pay</div>
                        <div class="text-sm">삼성페이</div>
                    </button>
                    <button type="button" class="rounded-md border border-[#dfdfdf] bg-white p-3 text-center">
                        <div class="mb-2 text-xl font-semibold leading-none text-black">Pay</div>
                        <div class="text-sm">애플페이</div>
                    </button>
                </div>

                <label class="flex items-center gap-2 text-sm text-[#555]">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-[#3b3b3b] text-xs text-white">✓</span>
                    <span>이 결제 수단 다음에도 사용</span>
                </label>
            </section>
            <!-- 결제수단 섹션 끝 -->

            <!-- 결제 예정 금액 섹션 시작 -->
            <section id="sod_expected_price" class="space-y-4 p-4 mb-3 bg-white">
                <div class="flex items-center justify-between">
                    <h2>결제 예정 금액</h2>
                    <button type="button" id="expected_accordion_btn" class="inline-flex h-8 w-8 items-center justify-center text-[#222]" aria-expanded="true" aria-controls="sod_expected_price_body">
                        <svg class="accordion-chevron h-5 w-5 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>
                    </button>
                </div>

                <div id="sod_expected_price_body" class="space-y-3">
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-[#666]">상품수</span>
                            <span><?php echo number_format($cart_item_kind_count); ?>개</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[#666]">상품금액</span>
                            <span id="exp_item_price"><?php echo number_format($tot_cust_price); ?>원</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[#666]">배송비</span>
                            <span id="exp_send_price"><?php echo number_format($send_cost); ?>원</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[#666]">할인금액</span>
                            <span id="exp_discount_price">-<?php echo number_format($tot_item_discount); ?>원</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[#666]">쿠폰 할인금액</span>
                            <span id="exp_coupon_price" class="text-[#ff4c2e]">-0원</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[#666]">포인트</span>
                            <span id="exp_point_price" class="text-[#ff4c2e]">-0원</span>
                        </div>
                    </div>

                    <div class="border-t border-[#e5e5e5] pt-4">
                        <div class="flex items-end justify-between">
                            <span>총 결제 금액</span>
                            <span id="exp_total_price" class="text-xl font-bold"><?php echo number_format($tot_price); ?>원</span>
                        </div>
                    </div>
                </div>
            </section>
            <!-- 결제 예정 금액 섹션 끝 -->

            <!-- 결제 동의 섹션 시작 -->
            <section id="sod_agree_ui" class="space-y-4 mb-4">
                <div class="flex items-center justify-between bg-white p-4">
                    <h2>위 내용을 확인했으며, 결제에 동의합니다.</h2>
                    <button type="button" id="agree_accordion_btn" class="inline-flex h-8 w-8 items-center justify-center text-[#222]" aria-expanded="true" aria-controls="sod_agree_ui_body">
                        <svg class="accordion-chevron h-5 w-5 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 15-6-6-6 6" />
                        </svg>
                    </button>
                </div>

                <div id="sod_agree_ui_body" class="space-y-4 px-4 text-sm">
                    <div class="flex items-center justify-between">
                        <span>개인정보 수집, 이용 및 처리 동의</span>
                        <button type="button" class="text-[#8a8a8a] underline">보기</button>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>전자지급 결제대행 서비스 이용약관 동의</span>
                        <button type="button" class="text-[#8a8a8a] underline">보기</button>
                    </div>
                    <div class="flex items-center justify-between">
                        <span>선불전자지급수단 이용약관 동의</span>
                        <button type="button" class="text-[#8a8a8a] underline">보기</button>
                    </div>
                </div>
            </section>
            <!-- 결제 동의 섹션 끝 -->

            <!-- 결제 버튼 섹션 시작 -->
            <section id="sod_pay_button_ui" class="space-y-4 p-4 bg-white">
                <button type="button" onclick="forderform_check(this.form);" class="h-14 w-full rounded-md bg-[#f4cc3a] text-base font-semibold text-[#1f1f1f]">
                    <span id="pay_btn_amount"><?php echo number_format($tot_price); ?>원</span> 결제하기
                </button>
                <p class="text-center text-xs text-[#8a8a8a]">결제 전 이용약관 및 정보제공 동의를 확인해주세요.</p>
            </section>
            <!-- 결제 버튼 섹션 끝 -->

            <!-- 주문상품 합계 시작 { -->
            <div id="sod_bsk_tot" class="!hidden">
                <ul>
                    <li class="sod_bsk_sell">
                        <span>주문</span>
                        <strong><?php echo number_format($tot_sell_price); ?></strong>원
                    </li>
                    <li class="sod_bsk_coupon">
                        <span>쿠폰할인</span>
                        <strong id="ct_tot_coupon">0</strong>원
                    </li>
                    <li class="sod_bsk_dvr">
                        <span>배송비</span>
                        <strong><?php echo number_format($send_cost); ?></strong>원
                    </li>
                    <li class="sod_bsk_point">
                        <span>포인트</span>
                        <strong><?php echo number_format($tot_point); ?></strong>점
                    </li>
                    <li class="sod_bsk_cnt">
                        <span>총계</span>
                        <?php $tot_price = $tot_sell_price + $send_cost; // 총계 = 주문상품금액합계 + 배송비 
                        ?>
                        <strong id="ct_tot_price"><?php echo number_format($tot_price); ?></strong>원
                    </li>

                </ul>
            </div>
            <!-- } 주문상품 합계 끝 -->


            <!-- 결제정보 입력 시작 { -->
            <section id="sod_frm_pay" class="!hidden">
                <h2>결제정보</h2>

                <div class="pay_tbl">
                    <table>
                        <tbody>
                            <?php if ($oc_cnt > 0) { ?>
                                <tr>
                                    <th scope="row">주문할인</th>
                                    <td>
                                        <strong id="od_cp_price">0</strong>원
                                        <input type="hidden" name="od_cp_id" value="">
                                        <button type="button" class="btn_frmline">쿠폰적용</button>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if ($sc_cnt > 0) { ?>
                                <tr>
                                    <th scope="row">배송비할인</th>
                                    <td>
                                        <strong id="sc_cp_price">0</strong>원
                                        <input type="hidden" name="sc_cp_id" value="">
                                        <button type="button" id="sc_coupon_btn" class="btn_frmline">쿠폰적용</button>
                                    </td>
                                </tr>
                            <?php } ?>

                            <tr>
                                <th>추가배송비</th>
                                <td><strong id="od_send_cost2">0</strong>원<br>(지역에 따라 추가되는 도선료 등의 배송비입니다.)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="od_tot_price">
                    <span>총 주문금액</span>
                    <strong class="print_price"><?php echo number_format($tot_price); ?></strong>원
                </div>

                <div id="od_pay_sl">
                    <div class="od_pay_buttons_el">
                        <h3>결제수단</h3>
                        <?php
                        if (!$default['de_card_point'])
                            echo '<p id="sod_frm_pt_alert"><strong>무통장입금</strong> 이외의 결제 수단으로 결제하시는 경우 포인트를 적립해드리지 않습니다.</p>';

                        $multi_settle = 0;
                        $checked = '';

                        $escrow_title = "";
                        if ($default['de_escrow_use']) {
                            $escrow_title = "에스크로<br>";
                        }

                        if ($is_kakaopay_use || $default['de_bank_use'] || $default['de_vbank_use'] || $default['de_iche_use'] || $default['de_card_use'] || $default['de_hp_use'] || $default['de_easy_pay_use'] || $default['de_inicis_lpay_use'] || $default['de_inicis_kakaopay_use']) {
                            echo '<fieldset id="sod_frm_paysel">';
                            echo '<legend>결제방법 선택</legend>';
                        }

                        // 카카오페이
                        if ($is_kakaopay_use) {
                            $multi_settle++;
                            echo '<input type="radio" id="od_settle_kakaopay" name="od_settle_case" value="KAKAOPAY" ' . $checked . '> <label for="od_settle_kakaopay" class="kakaopay_icon lb_icon">KAKAOPAY</label>' . PHP_EOL;
                            $checked = '';
                        }

                        // 무통장입금 사용
                        if ($default['de_bank_use']) {
                            $multi_settle++;
                            echo '<input type="radio" id="od_settle_bank" name="od_settle_case" value="무통장" ' . $checked . '> <label for="od_settle_bank" class="lb_icon bank_icon">무통장입금</label>' . PHP_EOL;
                            $checked = '';
                        }

                        // 가상계좌 사용
                        if ($default['de_vbank_use']) {
                            $multi_settle++;
                            echo '<input type="radio" id="od_settle_vbank" name="od_settle_case" value="가상계좌" ' . $checked . '> <label for="od_settle_vbank" class="lb_icon vbank_icon">' . $escrow_title . '가상계좌</label>' . PHP_EOL;
                            $checked = '';
                        }

                        // 계좌이체 사용
                        if ($default['de_iche_use']) {
                            $multi_settle++;
                            // 토스페이먼츠 v2 - 퀵계좌이체 명칭 사용
                            echo '<input type="radio" id="od_settle_iche" name="od_settle_case" value="계좌이체" ' . $checked . '> <label for="od_settle_iche" class="lb_icon iche_icon">' . $escrow_title . ($default['de_pg_service'] == 'toss' ? '퀵계좌이체' : '계좌이체') . '</label>' . PHP_EOL;
                            $checked = '';
                        }

                        // 휴대폰 사용
                        if ($default['de_hp_use']) {
                            $multi_settle++;
                            echo '<input type="radio" id="od_settle_hp" name="od_settle_case" value="휴대폰" ' . $checked . '> <label for="od_settle_hp" class="lb_icon hp_icon">휴대폰</label>' . PHP_EOL;
                            $checked = '';
                        }

                        // 신용카드 사용
                        if ($default['de_card_use']) {
                            $multi_settle++;
                            echo '<input type="radio" id="od_settle_card" name="od_settle_case" value="신용카드" ' . $checked . '> <label for="od_settle_card" class="lb_icon card_icon">신용카드</label>' . PHP_EOL;
                            $checked = '';
                        }

                        $easypay_prints = array();

                        // PG 간편결제
                        if ($default['de_easy_pay_use']) {
                            switch ($default['de_pg_service']) {
                                case 'lg':
                                    $pg_easy_pay_name = 'PAYNOW';
                                    break;
                                case 'inicis':
                                    $pg_easy_pay_name = 'KPAY';
                                    break;
                                default:
                                    $pg_easy_pay_name = 'PAYCO';
                                    break;
                            }

                            $multi_settle++;

                            if (in_array($default['de_pg_service'], array('kcp', 'nicepay')) && isset($default['de_easy_pay_services']) && $default['de_easy_pay_services']) {
                                $de_easy_pay_service_array = explode(',', $default['de_easy_pay_services']);

                                if ($default['de_pg_service'] === 'kcp') {
                                    if (in_array('nhnkcp_payco', $de_easy_pay_service_array)) {
                                        $easypay_prints['nhnkcp_payco'] = '<input type="radio" id="od_settle_nhnkcp_payco" name="od_settle_case" data-pay="payco" value="간편결제"> <label for="od_settle_nhnkcp_payco" class="PAYCO nhnkcp_payco lb_icon" title="NHN_KCP - PAYCO">PAYCO</label>';
                                    }
                                    if (in_array('nhnkcp_naverpay', $de_easy_pay_service_array)) {

                                        if (isset($default['de_easy_pay_services']) && in_array('used_nhnkcp_naverpay_point', explode(',', $default['de_easy_pay_services']))) {
                                            $easypay_prints['nhnkcp_naverpay_card'] = '<input type="radio" id="od_settle_nhnkcp_naverpay" name="od_settle_case" data-pay="naverpay" value="간편결제" > <label for="od_settle_nhnkcp_naverpay" class="naverpay_icon nhnkcp_naverpay lb_icon nhnkcp_icon nhnkcp_card" title="NHN_KCP - 네이버페이 카드결제">네이버페이 카드결제</label>';

                                            $easypay_prints['nhnkcp_naverpay_money'] = '<input type="radio" id="od_settle_nhnkcp_naverpay_money" name="od_settle_case" data-pay="naverpay" data-money="1" value="간편결제" > <label for="od_settle_nhnkcp_naverpay_money" class="naverpay_icon nhnkcp_naverpay lb_icon nhnkcp_icon nhnkcp_money" title="NHN_KCP - 네이버페이 머니/포인트 결제">네이버페이 머니/포인트</label>';
                                        } else {
                                            $easypay_prints['nhnkcp_naverpay_card'] = '<input type="radio" id="od_settle_nhnkcp_naverpay" name="od_settle_case" data-pay="naverpay" value="간편결제" > <label for="od_settle_nhnkcp_naverpay" class="naverpay_icon nhnkcp_naverpay lb_icon" title="NHN_KCP - 네이버페이 카드결제">네이버페이 카드결제</label>';
                                        }
                                    }
                                    if (in_array('nhnkcp_kakaopay', $de_easy_pay_service_array)) {
                                        $easypay_prints['nhnkcp_kakaopay'] = '<input type="radio" id="od_settle_nhnkcp_kakaopay" name="od_settle_case" data-pay="kakaopay" value="간편결제" > <label for="od_settle_nhnkcp_kakaopay" class="kakaopay_icon nhnkcp_kakaopay lb_icon" title="NHN_KCP - 카카오페이">카카오페이</label>';
                                    }
                                } else if ($default['de_pg_service'] === 'nicepay') {
                                    if (in_array('nicepay_samsungpay', $de_easy_pay_service_array)) {
                                        $easypay_prints['nicepay_samsungpay'] = '<input type="radio" id="od_settle_nicepay_samsungpay" name="od_settle_case" data-pay="nice_samsungpay" value="간편결제"> <label for="od_settle_nicepay_samsungpay" class="samsungpay_icon nicepay_samsungpay lb_icon" title="NICEPAY - 삼성페이">삼성페이</label>';
                                    }
                                    if (in_array('nicepay_naverpay', $de_easy_pay_service_array)) {
                                        $easypay_prints['nicepay_naverpay'] = '<input type="radio" id="od_settle_nicepay_naverpay" name="od_settle_case" data-pay="nice_naverpay" value="간편결제" > <label for="od_settle_nicepay_naverpay" class="naverpay_icon nicepay_naverpay lb_icon" title="NICEPAY - 네이버페이">네이버페이</label>';
                                    }
                                    if (in_array('nicepay_kakaopay', $de_easy_pay_service_array)) {
                                        $easypay_prints['nicepay_kakaopay'] = '<input type="radio" id="od_settle_nicepay_kakaopay" name="od_settle_case" data-pay="nice_kakaopay" value="간편결제" > <label for="od_settle_nicepay_kakaopay" class="kakaopay_icon nicepay_kakaopay lb_icon" title="NICEPAY - 카카오페이">카카오페이</label>';
                                    }
                                    if (in_array('nicepay_paycopay', $de_easy_pay_service_array)) {
                                        $easypay_prints['nicepay_paycopay'] = '<input type="radio" id="od_settle_nicepay_paycopay" name="od_settle_case" data-pay="nice_paycopay" value="간편결제" > <label for="od_settle_nicepay_paycopay" class="paycopay_icon nicepay_paycopay lb_icon" title="NICEPAY - 페이코">페이코</label>';
                                    }
                                    if (in_array('nicepay_skpay', $de_easy_pay_service_array)) {
                                        $easypay_prints['nicepay_skpay'] = '<input type="radio" id="od_settle_nicepay_skpay" name="od_settle_case" data-pay="nice_skpay" value="간편결제" > <label for="od_settle_nicepay_skpay" class="skpay_icon nicepay_skpay lb_icon" title="NICEPAY - SK페이">SK페이</label>';
                                    }
                                    if (in_array('nicepay_ssgpay', $de_easy_pay_service_array)) {
                                        $easypay_prints['nicepay_ssgpay'] = '<input type="radio" id="od_settle_nicepay_ssgpay" name="od_settle_case" data-pay="nice_ssgpay" value="간편결제" > <label for="od_settle_nicepay_ssgpay" class="ssgpay_icon nicepay_ssgpay lb_icon" title="NICEPAY - SSGPAY">SSGPAY</label>';
                                    }
                                    if (in_array('nicepay_lpay', $de_easy_pay_service_array)) {
                                        $easypay_prints['nicepay_lpay'] = '<input type="radio" id="od_settle_nicepay_lpay" name="od_settle_case" data-pay="nice_lpay" value="간편결제" > <label for="od_settle_nicepay_lpay" class="lpay_icon nicepay_lpay lb_icon" title="NICEPAY - LPAY">LPAY</label>';
                                    }
                                }
                            } else {
                                $easypay_prints[strtolower($pg_easy_pay_name)] = '<input type="radio" id="od_settle_easy_pay" name="od_settle_case" value="간편결제"> <label for="od_settle_easy_pay" class="' . $pg_easy_pay_name . ' lb_icon">' . $pg_easy_pay_name . '</label>';
                            }
                        }

                        if (! isset($easypay_prints['nhnkcp_naverpay']) && function_exists('is_use_easypay') && is_use_easypay('global_nhnkcp')) {

                            if (isset($default['de_easy_pay_services']) && in_array('used_nhnkcp_naverpay_point', explode(',', $default['de_easy_pay_services']))) {
                                $easypay_prints['nhnkcp_naverpay_card'] = '<input type="radio" id="od_settle_nhnkcp_naverpay" name="od_settle_case" data-pay="naverpay" value="간편결제" > <label for="od_settle_nhnkcp_naverpay" class="naverpay_icon nhnkcp_naverpay lb_icon nhnkcp_icon nhnkcp_card" title="NHN_KCP - 네이버페이 카드결제">네이버페이 카드결제</label>';

                                $easypay_prints['nhnkcp_naverpay_money'] = '<input type="radio" id="od_settle_nhnkcp_naverpay_money" name="od_settle_case" data-pay="naverpay" data-money="1" value="간편결제" > <label for="od_settle_nhnkcp_naverpay_money" class="naverpay_icon nhnkcp_naverpay lb_icon nhnkcp_icon nhnkcp_money" title="NHN_KCP - 네이버페이 머니/포인트 결제">네이버페이 머니/포인트</label>';
                            } else {
                                $easypay_prints['nhnkcp_naverpay'] = '<input type="radio" id="od_settle_nhnkcp_naverpay" name="od_settle_case" data-pay="naverpay" value="간편결제" > <label for="od_settle_nhnkcp_naverpay" class="naverpay_icon nhnkcp_naverpay lb_icon" title="NHN_KCP - 네이버페이">네이버페이</label>';
                            }
                        }

                        if ($easypay_prints) {
                            $multi_settle++;
                            echo run_replace('shop_orderform_easypay_buttons', implode(PHP_EOL, $easypay_prints), $easypay_prints, $multi_settle);
                        }

                        //이니시스 Lpay
                        if ($default['de_inicis_lpay_use']) {
                            echo '<input type="radio" id="od_settle_inicislpay" data-case="lpay" name="od_settle_case" value="lpay" ' . $checked . '> <label for="od_settle_inicislpay" class="inicis_lpay lb_icon">L.pay</label>' . PHP_EOL;
                            $checked = '';
                        }

                        //이니시스 카카오페이 
                        if (isset($default['de_inicis_kakaopay_use']) && $default['de_inicis_kakaopay_use']) {
                            echo '<input type="radio" id="od_settle_inicis_kakaopay" data-case="inicis_kakaopay" name="od_settle_case" value="inicis_kakaopay" ' . $checked . ' title="KG 이니시스 카카오페이"> <label for="od_settle_inicis_kakaopay" class="inicis_kakaopay lb_icon">KG 이니시스 카카오페이<em></em></label>' . PHP_EOL;
                            $checked = '';
                        }

                        if ($temp_point > 0) {
                            $multi_settle++;
                        }

                        if ($default['de_bank_use']) {
                            // 은행계좌를 배열로 만든후
                            $str = explode("\n", trim($default['de_bank_account']));
                            if (count($str) <= 1) {
                                $bank_account = '<input type="hidden" name="od_bank_account" value="' . $str[0] . '">' . $str[0] . PHP_EOL;
                            } else {
                                $bank_account = '<select name="od_bank_account" id="od_bank_account">' . PHP_EOL;
                                $bank_account .= '<option value="">선택하십시오.</option>';
                                for ($i = 0; $i < count($str); $i++) {
                                    //$str[$i] = str_replace("\r", "", $str[$i]);
                                    $str[$i] = trim($str[$i]);
                                    $bank_account .= '<option value="' . $str[$i] . '">' . $str[$i] . '</option>' . PHP_EOL;
                                }
                                $bank_account .= '</select>' . PHP_EOL;
                            }
                            echo '<div id="settle_bank" style="display:none">';
                            echo '<label for="od_bank_account" class="sound_only">입금할 계좌</label>';
                            echo $bank_account;
                            echo '<br><label for="od_deposit_name">입금자명</label> ';
                            echo '<input type="text" name="od_deposit_name" id="od_deposit_name" size="10" maxlength="20">';
                            echo '</div>';
                        }

                        if ($is_kakaopay_use || $default['de_bank_use'] || $default['de_vbank_use'] || $default['de_iche_use'] || $default['de_card_use'] || $default['de_hp_use'] || $default['de_easy_pay_use'] || $default['de_inicis_lpay_use'] || $default['de_inicis_kakaopay_use']) {
                            echo '</fieldset>';
                        }

                        if ($multi_settle == 0)
                            echo '<p>결제할 방법이 없습니다.<br>운영자에게 알려주시면 감사하겠습니다.</p>';
                        ?>
                    </div>
            </section>
            <!-- } 결제 정보 입력 끝 -->

            <?php
            // 결제대행사별 코드 include (주문버튼)
            require_once(G5_SHOP_PATH . '/' . $default['de_pg_service'] . '/orderform.3.php');

            if ($is_kakaopay_use) {
                require_once(G5_SHOP_PATH . '/kakaopay/orderform.3.php');
            }
            ?>

            <?php
            if ($default['de_escrow_use']) {
                // 결제대행사별 코드 include (에스크로 안내)
                require_once(G5_SHOP_PATH . '/' . $default['de_pg_service'] . '/orderform.4.php');
            }
            ?>
        </div>

    </div>
</form>

<?php
if ($default['de_inicis_lpay_use'] || $default['de_inicis_kakaopay_use']) {   //이니시스 L.pay 또는 이니시스 카카오페이 사용시
    require_once(G5_SHOP_PATH . '/inicis/lpay_order.script.php');
}
if (function_exists('is_use_easypay') && is_use_easypay('global_nhnkcp')) {  // 타 PG 사용시 NHN KCP 네이버페이 사용이 설정되어 있다면
    require_once(G5_SHOP_PATH . '/kcp/global_nhn_kcp_order.script.php');
}
?>
<script>
    var zipcode = "";
    var form_action_url = "<?php echo $order_action_url; ?>";
    var auto_applied_od_cp_id = "";

    $(function() {
        var $cp_btn_el;
        var $cp_row_el;

        $(".cp_btn").click(function() {
            $cp_btn_el = $(this);
            $cp_row_el = $(this).closest(".sod_item");
            $("#cp_frm").remove();
            var it_id = $cp_btn_el.closest(".sod_item").find("input[name^=it_id]").val();

            $.post(
                "./orderitemcoupon.php", {
                    it_id: it_id,
                    sw_direct: "<?php echo $sw_direct; ?>"
                },
                function(data) {
                    $cp_btn_el.after(data);
                }
            );
        });

        $(document).on("click", ".cp_apply", function() {
            var $el = $(this).closest("tr");
            var cp_id = $el.find("input[name='f_cp_id[]']").val();
            var price = $el.find("input[name='f_cp_prc[]']").val();
            var subj = $el.find("input[name='f_cp_subj[]']").val();
            var sell_price;

            if (parseInt(price) == 0) {
                if (!confirm(subj + "쿠폰의 할인 금액은 " + price + "원입니다.\n쿠폰을 적용하시겠습니까?")) {
                    return false;
                }
            }

            // 이미 사용한 쿠폰이 있는지
            var cp_dup = false;
            var cp_dup_idx;
            var $cp_dup_el;
            $("input[name^=cp_id]").each(function(index) {
                var id = $(this).val();

                if (id == cp_id) {
                    cp_dup_idx = index;
                    cp_dup = true;
                    $cp_dup_el = $(this).closest(".sod_item");;

                    return false;
                }
            });

            if (cp_dup) {
                var it_name = $("input[name='it_name[" + cp_dup_idx + "]']").val();
                if (!confirm(subj + "쿠폰은 " + it_name + "에 사용되었습니다.\n" + it_name + "의 쿠폰을 취소한 후 적용하시겠습니까?")) {
                    return false;
                } else {
                    coupon_cancel($cp_dup_el);
                    $("#cp_frm").remove();
                    $cp_dup_el.find(".cp_btn").text("적용").focus();
                    $cp_dup_el.find(".cp_cancel").remove();
                }
            }

            var $s_el = $cp_row_el.find(".total_price");;
            sell_price = parseInt($cp_row_el.find("input[name^=it_price]").val());
            sell_price = sell_price - parseInt(price);
            if (sell_price < 0) {
                alert("쿠폰할인금액이 상품 주문금액보다 크므로 쿠폰을 적용할 수 없습니다.");
                return false;
            }
            $s_el.text(number_format(String(sell_price)));
            $cp_row_el.find("input[name^=cp_id]").val(cp_id);
            $cp_row_el.find("input[name^=cp_price]").val(price);

            calculate_total_price();
            $("#cp_frm").remove();
            $cp_btn_el.text("변경").focus();
            if (!$cp_row_el.find(".cp_cancel").length)
                $cp_btn_el.after("<button type=\"button\" class=\"cp_cancel\">취소</button>");
        });

        $(document).on("click", "#cp_close", function() {
            $("#cp_frm").remove();
            $cp_btn_el.focus();
        });

        $(document).on("click", ".cp_cancel", function() {
            coupon_cancel($(this).closest(".sod_item"));
            calculate_total_price();
            $("#cp_frm").remove();
            $(this).closest(".sod_item").find(".cp_btn").text("적용").focus();
            $(this).remove();
        });

        $("#od_coupon_btn").click(function() {
            if ($("#od_coupon_frm").parent(".od_coupon_wrap").length) {
                $("#od_coupon_frm").parent(".od_coupon_wrap").remove();
            }
            $("#od_coupon_frm").remove();
            var $this = $(this);
            var price = parseInt($("input[name=org_od_price]").val()) - parseInt($("input[name=item_coupon]").val());
            if (price <= 0) {
                alert('상품금액이 0원이므로 쿠폰을 사용할 수 없습니다.');
                return false;
            }
            $.post(
                "./ordercoupon.php", {
                    price: price
                },
                function(data) {
                    $this.after(data);
                }
            );
        });

        $(document).on("click", ".od_cp_apply", function() {
            var $el = $(this).closest("tr");
            var cp_id = $el.find("input[name='o_cp_id[]']").val();
            var price = parseInt($el.find("input[name='o_cp_prc[]']").val());
            var subj = $el.find("input[name='o_cp_subj[]']").val();
            var send_cost = $("input[name=od_send_cost]").val();
            var item_coupon = parseInt($("input[name=item_coupon]").val());
            var od_price = parseInt($("input[name=org_od_price]").val()) - item_coupon;

            if (price == 0) {
                if (!confirm(subj + "쿠폰의 할인 금액은 " + price + "원입니다.\n쿠폰을 적용하시겠습니까?")) {
                    return false;
                }
            }

            if (od_price - price <= 0) {
                alert("쿠폰할인금액이 주문금액보다 크므로 쿠폰을 적용할 수 없습니다.");
                return false;
            }

            // 자동 적용된 쿠폰과 다른 쿠폰을 수동 선택하면 자동모드를 해제한다.
            if (auto_applied_od_cp_id && cp_id !== auto_applied_od_cp_id) {
                set_max_coupon_toggle(false);
                auto_applied_od_cp_id = "";
            }

            $("input[name=sc_cp_id]").val("");
            $("#sc_coupon_btn").text("쿠폰적용");
            $("#sc_coupon_cancel").remove();

            $("input[name=od_price]").val(od_price - price);
            $("input[name=od_cp_id]").val(cp_id);
            $("input[name=od_coupon]").val(price);
            $("input[name=od_send_coupon]").val(0);
            $("#od_cp_price").text(number_format(String(price)));
            $("#sc_cp_price").text(0);
            calculate_order_price();
            if ($("#od_coupon_frm").parent(".od_coupon_wrap").length) {
                $("#od_coupon_frm").parent(".od_coupon_wrap").remove();
            }
            $("#od_coupon_frm").remove();
            update_coupon_button_ui();
            $("#od_coupon_btn").focus();
            if (!$("#od_coupon_cancel").length)
                $("#od_coupon_btn").after("<button type=\"button\" id=\"od_coupon_cancel\" class=\"cp_cancel\">취소</button>");
        });

        $(document).on("click", "#od_coupon_close", function() {
            if ($("#od_coupon_frm").parent(".od_coupon_wrap").length) {
                $("#od_coupon_frm").parent(".od_coupon_wrap").remove();
            }
            $("#od_coupon_frm").remove();
            $("#od_coupon_btn").focus();
        });

        $(document).on("click", "#od_coupon_cancel", function() {
            var org_price = $("input[name=org_od_price]").val();
            var item_coupon = parseInt($("input[name=item_coupon]").val());
            $("input[name=od_price]").val(org_price - item_coupon);
            $("input[name=sc_cp_id]").val("");
            $("input[name=od_coupon]").val(0);
            $("input[name=od_send_coupon]").val(0);
            $("#od_cp_price").text(0);
            $("#sc_cp_price").text(0);
            calculate_order_price();
            if ($("#od_coupon_frm").parent(".od_coupon_wrap").length) {
                $("#od_coupon_frm").parent(".od_coupon_wrap").remove();
            }
            $("#od_coupon_frm").remove();
            update_coupon_button_ui();
            $("#od_coupon_btn").focus();
            $(this).remove();
            $("#sc_coupon_btn").text("쿠폰적용");
            $("#sc_coupon_cancel").remove();
            set_max_coupon_toggle(false);
            auto_applied_od_cp_id = "";
        });

        $("#sc_coupon_btn").click(function() {
            $("#sc_coupon_frm").remove();
            var $this = $(this);
            var price = parseInt($("input[name=od_price]").val());
            var send_cost = parseInt($("input[name=od_send_cost]").val());
            $.post(
                "./ordersendcostcoupon.php", {
                    price: price,
                    send_cost: send_cost
                },
                function(data) {
                    $this.after(data);
                }
            );
        });

        $(document).on("click", ".sc_cp_apply", function() {
            var $el = $(this).closest("tr");
            var cp_id = $el.find("input[name='s_cp_id[]']").val();
            var price = parseInt($el.find("input[name='s_cp_prc[]']").val());
            var subj = $el.find("input[name='s_cp_subj[]']").val();
            var send_cost = parseInt($("input[name=od_send_cost]").val());

            if (parseInt(price) == 0) {
                if (!confirm(subj + "쿠폰의 할인 금액은 " + price + "원입니다.\n쿠폰을 적용하시겠습니까?")) {
                    return false;
                }
            }

            $("input[name=sc_cp_id]").val(cp_id);
            $("input[name=od_send_coupon]").val(price);
            $("#sc_cp_price").text(number_format(String(price)));
            calculate_order_price();
            $("#sc_coupon_frm").remove();
            $("#sc_coupon_btn").text("변경").focus();
            if (!$("#sc_coupon_cancel").length)
                $("#sc_coupon_btn").after("<button type=\"button\" id=\"sc_coupon_cancel\" class=\"cp_cancel\">취소</button>");
        });

        $(document).on("click", "#sc_coupon_close", function() {
            $("#sc_coupon_frm").remove();
            $("#sc_coupon_btn").focus();
        });

        $(document).on("click", "#sc_coupon_cancel", function() {
            $("input[name=od_send_coupon]").val(0);
            $("#sc_cp_price").text(0);
            calculate_order_price();
            $("#sc_coupon_frm").remove();
            $("#sc_coupon_btn").text("쿠폰적용").focus();
            $(this).remove();
        });

        $("#od_b_addr2").focus(function() {
            var zip = $("#od_b_zip").val().replace(/[^0-9]/g, "");
            if (zip == "")
                return false;

            var code = String(zip);

            if (zipcode == code)
                return false;

            zipcode = code;
            calculate_sendcost(code);
        });

        $("#od_settle_bank").on("click", function() {
            $("[name=od_deposit_name]").val($("[name=od_name]").val());
            $("#settle_bank").show();
            syncCashBankUi();
        });

        $("#od_settle_iche,#od_settle_card,#od_settle_vbank,#od_settle_hp,#od_settle_easy_pay,#od_settle_kakaopay,#od_settle_nhnkcp_payco,#od_settle_nhnkcp_naverpay,#od_settle_nhnkcp_naverpay_money,#od_settle_nhnkcp_kakaopay,#od_settle_inicislpay,#od_settle_inicis_kakaopay").bind("click", function() {
            $("#settle_bank").hide();
            syncCashBankUi();
        });

        var currentSettleTabKey = "easy";

        // 결제수단 탭 UI
        function setSettleTabUi(tabKey) {
            var activeClass = "border-gray-800 bg-gray-800 text-white";
            var inactiveClass = "border-gray-300 bg-white text-gray-900";
            currentSettleTabKey = tabKey;

            $(".settle-tab-btn").each(function() {
                var $btn = $(this);
                var isActive = $btn.data("settle-tab") === tabKey;
                $btn.toggleClass(activeClass, isActive);
                $btn.toggleClass(inactiveClass, !isActive);
            });

            $("#sod_cash_pay_ui").toggleClass("hidden", tabKey !== "cash");
            $("#sod_easy_pay_ui").toggleClass("hidden", tabKey !== "easy");
        }

        function syncCashSettleUi() {
            var activeClass = "border-gray-800 bg-gray-800 text-white";
            var inactiveClass = "border-gray-300 bg-white text-gray-900";
            var disabledClass = "cursor-not-allowed border-gray-200 bg-gray-100 text-gray-400";

            $(".settle-cash-btn").each(function() {
                var $btn = $(this);
                var targetSelector = $btn.data("settle-target");
                var $target = $(targetSelector);
                var exists = $target.length > 0;
                var isChecked = exists && $target.is(":checked");

                $btn.prop("disabled", !exists);
                $btn.toggleClass(activeClass, isChecked);
                $btn.toggleClass(inactiveClass, exists && !isChecked);
                $btn.toggleClass(disabledClass, !exists);
            });
        }

        function syncCashBankUi() {
            var isBankChecked = $("#od_settle_bank").length && $("#od_settle_bank").is(":checked");
            var isCashTabActive = currentSettleTabKey === "cash";
            var $uiWrap = $("#sod_cash_bank_ui");
            var $uiBankAccount = $("#ui_od_bank_account");
            var $uiDepositName = $("#ui_od_deposit_name");
            var $originBankAccount = $("#od_bank_account");
            var $originDepositName = $("#od_deposit_name");

            $uiWrap.toggleClass("hidden", !(isBankChecked && isCashTabActive));
            if (!(isBankChecked && isCashTabActive)) {
                return;
            }

            if ($originBankAccount.length && $uiBankAccount.length) {
                if ($originBankAccount.val()) {
                    $uiBankAccount.val($originBankAccount.val());
                }
                $originBankAccount.val($uiBankAccount.val());
            }

            if ($originDepositName.length && $uiDepositName.length) {
                if ($originDepositName.val() && !$uiDepositName.val()) {
                    $uiDepositName.val($originDepositName.val());
                }
                $originDepositName.val($uiDepositName.val());
            }
        }

        function selectFirstAvailableSettle(selectorList) {
            for (var i = 0; i < selectorList.length; i++) {
                var $target = $(selectorList[i]);
                if ($target.length) {
                    $target.prop("checked", true).trigger("click");
                    return true;
                }
            }

            return false;
        }

        function syncSettleTabByRadio() {
            var $selected = $("input[name='od_settle_case']:checked");
            if (!$selected.length) {
                setSettleTabUi("easy");
                return;
            }

            var selectedId = $selected.attr("id") || "";
            var tabKey = "easy";

            if (selectedId === "od_settle_card") {
                tabKey = "card";
            } else if (selectedId === "od_settle_bank" || selectedId === "od_settle_vbank" || selectedId === "od_settle_iche") {
                tabKey = "cash";
            }

            setSettleTabUi(tabKey);
        }

        $(document).on("click", ".settle-tab-btn", function() {
            var tabKey = $(this).data("settle-tab");
            setSettleTabUi(tabKey);

            if (tabKey === "cash") {
                selectFirstAvailableSettle(["#od_settle_bank", "#od_settle_vbank", "#od_settle_iche"]);
            } else if (tabKey === "easy") {
                selectFirstAvailableSettle([
                    "#od_settle_nhnkcp_payco",
                    "#od_settle_nhnkcp_naverpay",
                    "#od_settle_nhnkcp_naverpay_money",
                    "#od_settle_nhnkcp_kakaopay",
                    "#od_settle_nicepay_samsungpay",
                    "#od_settle_nicepay_naverpay",
                    "#od_settle_nicepay_kakaopay",
                    "#od_settle_nicepay_paycopay",
                    "#od_settle_nicepay_skpay",
                    "#od_settle_nicepay_ssgpay",
                    "#od_settle_nicepay_lpay",
                    "#od_settle_easy_pay",
                    "#od_settle_inicislpay",
                    "#od_settle_inicis_kakaopay",
                    "#od_settle_kakaopay"
                ]);
            } else if (tabKey === "card") {
                selectFirstAvailableSettle(["#od_settle_card"]);
            }

            syncCashSettleUi();
            syncCashBankUi();
        });

        $(document).on("click", ".settle-cash-btn", function() {
            var targetSelector = $(this).data("settle-target");
            var $target = $(targetSelector);

            if (!$target.length) {
                return;
            }

            $target.prop("checked", true).trigger("click");
        });

        $(document).on("change", "input[name='od_settle_case']", function() {
            syncSettleTabByRadio();
            syncCashSettleUi();
            syncCashBankUi();
        });

        $(document).on("change", "#ui_od_bank_account", function() {
            $("#od_bank_account").val($(this).val());
        });

        $(document).on("input", "#ui_od_deposit_name", function() {
            $("#od_deposit_name").val($(this).val());
        });

        syncSettleTabByRadio();
        syncCashSettleUi();
        syncCashBankUi();

        // 배송지선택
        $("input[name=ad_sel_addr]").on("click", function() {
            var addr = $(this).val().split(String.fromCharCode(30));

            if (addr[0] == "same") {
                gumae2baesong();
            } else {
                if (addr[0] == "new") {
                    for (i = 0; i < 10; i++) {
                        addr[i] = "";
                    }
                }

                var f = document.forderform;
                f.od_b_name.value = addr[0];
                f.od_b_tel.value = addr[1];
                f.od_b_hp.value = addr[2];
                f.od_b_zip.value = addr[3] + addr[4];
                f.od_b_addr1.value = addr[5];
                f.od_b_addr2.value = addr[6];
                f.od_b_addr3.value = addr[7];
                f.od_b_addr_jibeon.value = addr[8];
                f.ad_subject.value = addr[9];

                var zip1 = addr[3].replace(/[^0-9]/g, "");
                var zip2 = addr[4].replace(/[^0-9]/g, "");

                var code = String(zip1) + String(zip2);

                if (zipcode != code) {
                    calculate_sendcost(code);
                }
            }
        });

        // 배송지목록
        $("#order_address").on("click", function() {
            var url = this.href;
            window.open(url, "win_address", "left=100,top=100,width=800,height=600,scrollbars=1");
            return false;
        });

        $("#orderer_accordion_btn").on("click", function() {
            var $btn = $(this);
            var $body = $("#sod_frm_orderer_body");
            var isExpanded = $btn.attr("aria-expanded") === "true";

            $body.toggleClass("hidden", isExpanded);
            $btn.attr("aria-expanded", isExpanded ? "false" : "true");
            $btn.find(".accordion-chevron").toggleClass("rotate-180", isExpanded);
        });

        $("#order_products_accordion_btn").on("click", function() {
            var $btn = $(this);
            var $body = $("#sod_order_products_body");
            var isExpanded = $btn.attr("aria-expanded") === "true";

            $body.toggleClass("hidden", isExpanded);
            $btn.attr("aria-expanded", isExpanded ? "false" : "true");
            $btn.find(".accordion-chevron").toggleClass("rotate-180", isExpanded);
        });

        $("#coupon_accordion_btn").on("click", function() {
            var $btn = $(this);
            var $body = $("#sod_coupon_apply_body");
            var isExpanded = $btn.attr("aria-expanded") === "true";

            $body.toggleClass("hidden", isExpanded);
            $btn.attr("aria-expanded", isExpanded ? "false" : "true");
            $btn.find(".accordion-chevron").toggleClass("rotate-180", isExpanded);
        });

        $("#point_accordion_btn").on("click", function() {
            var $btn = $(this);
            var $body = $("#sod_point_apply_body");
            var isExpanded = $btn.attr("aria-expanded") === "true";

            $body.toggleClass("hidden", isExpanded);
            $btn.attr("aria-expanded", isExpanded ? "false" : "true");
            $btn.find(".accordion-chevron").toggleClass("rotate-180", isExpanded);
        });

        $("#expected_accordion_btn").on("click", function() {
            var $btn = $(this);
            var $body = $("#sod_expected_price_body");
            var isExpanded = $btn.attr("aria-expanded") === "true";

            $body.toggleClass("hidden", isExpanded);
            $btn.attr("aria-expanded", isExpanded ? "false" : "true");
            $btn.find(".accordion-chevron").toggleClass("rotate-180", isExpanded);
        });

        $("#agree_accordion_btn").on("click", function() {
            var $btn = $(this);
            var $body = $("#sod_agree_ui_body");
            var isExpanded = $btn.attr("aria-expanded") === "true";

            $body.toggleClass("hidden", isExpanded);
            $btn.attr("aria-expanded", isExpanded ? "false" : "true");
            $btn.find(".accordion-chevron").toggleClass("rotate-180", isExpanded);
        });

        // 최대 할인 적용 스위치의 UI를 토글하고 ON일 때 주문쿠폰 최대 할인을 자동 적용한다.
        $("#max_coupon_toggle").on("click", function() {
            var isOn = $(this).attr("aria-pressed") === "true";
            var nextOn = !isOn;

            set_max_coupon_toggle(nextOn);

            if (nextOn) {
                auto_apply_max_order_coupon();
            } else if ($("#od_coupon_cancel").length) {
                $("#od_coupon_cancel").trigger("click");
            }
        });

        $("#use_all_point_btn").on("click", function() {
            var maxPoint = parseInt($("input[name=max_temp_point]").val(), 10) || 0;
            $("#od_temp_point").val(maxPoint).trigger("input");
        });

        $(document).on("input", "#od_temp_point", function() {
            var val = this.value.replace(/[^0-9]/g, "");
            var maxPoint = parseInt($("input[name=max_temp_point]").val(), 10) || 0;

            if (val !== "") {
                var point = parseInt(val, 10) || 0;
                if (point > maxPoint) {
                    point = maxPoint;
                }
                this.value = String(point);
            } else {
                this.value = "";
            }

            calculate_order_price();
        });

        sync_point_apply_button_ui();
        update_expected_price();
    });

    function coupon_cancel($el) {
        var $dup_sell_el = $el.find(".total_price");
        var $dup_price_el = $el.find("input[name^=cp_price]");
        var org_sell_price = $el.find("input[name^=it_price]").val();

        $dup_sell_el.text(number_format(String(org_sell_price)));
        $dup_price_el.val(0);
        $el.find("input[name^=cp_id]").val("");
    }

    function update_expected_price() {
        var od_price = parseInt($("input[name=od_price]").val()) || 0;
        var send_cost = parseInt($("input[name=od_send_cost]").val()) || 0;
        var send_cost2 = parseInt($("input[name=od_send_cost2]").val()) || 0;
        var item_coupon = parseInt($("input[name=item_coupon]").val()) || 0;
        var od_coupon = parseInt($("input[name=od_coupon]").val()) || 0;
        var send_coupon = parseInt($("input[name=od_send_coupon]").val()) || 0;
        var temp_point = parseInt($("input[name=od_temp_point]").val()) || 0;
        var coupon_total = item_coupon + od_coupon + send_coupon;
        var total = od_price + send_cost + send_cost2 - send_coupon - temp_point;

        if (total < 0) {
            total = 0;
        }

        $("#exp_send_price").text(number_format(String(send_cost + send_cost2)) + "원");
        $("#exp_coupon_price").text("-" + number_format(String(coupon_total)) + "원");
        $("#exp_point_price").text("-" + number_format(String(temp_point)) + "원");
        $("#exp_total_price").text(number_format(String(total)) + "원");
        $("#pay_btn_amount").text(number_format(String(total)) + "원");
        update_coupon_button_ui();
    }

    // 최대 할인 적용 스위치의 시각/접근성 상태를 동기화한다.
    function set_max_coupon_toggle(isOn) {
        var $toggle = $("#max_coupon_toggle");
        var $thumb = $("#max_coupon_toggle_thumb");

        $toggle.attr("aria-pressed", isOn ? "true" : "false");
        $toggle.toggleClass("bg-yellow-400", isOn);
        $toggle.toggleClass("bg-gray-300", !isOn);
        $thumb.toggleClass("translate-x-4", isOn);
        $thumb.toggleClass("translate-x-0", !isOn);
    }

    // 주문쿠폰 목록에서 할인금액이 가장 큰 쿠폰 1장을 찾아 자동 적용한다.
    function auto_apply_max_order_coupon() {
        var basePrice = (parseInt($("input[name=org_od_price]").val(), 10) || 0) - (parseInt($("input[name=item_coupon]").val(), 10) || 0);

        if (basePrice <= 0) {
            alert("상품금액이 0원이므로 쿠폰을 사용할 수 없습니다.");
            set_max_coupon_toggle(false);
            return;
        }

        $.post(
            "./ordercoupon.php", {
                price: basePrice
            },
            function(data) {
                var $html = $("<div>").html(data);
                var maxCoupon = null;

                $html.find("tr").each(function() {
                    var $tr = $(this);
                    var cpId = $tr.find("input[name='o_cp_id[]']").val();
                    var cpPrice = parseInt($tr.find("input[name='o_cp_prc[]']").val(), 10);

                    if (!cpId || isNaN(cpPrice)) {
                        return;
                    }

                    if (!maxCoupon || cpPrice > maxCoupon.price) {
                        maxCoupon = {
                            id: cpId,
                            price: cpPrice
                        };
                    }
                });

                if (!maxCoupon) {
                    alert("적용 가능한 주문 쿠폰이 없습니다.");
                    set_max_coupon_toggle(false);
                    return;
                }

                if (basePrice - maxCoupon.price <= 0) {
                    alert("쿠폰할인금액이 주문금액보다 크므로 쿠폰을 적용할 수 없습니다.");
                    set_max_coupon_toggle(false);
                    return;
                }

                $("input[name=sc_cp_id]").val("");
                $("#sc_coupon_btn").text("쿠폰적용");
                $("#sc_coupon_cancel").remove();

                $("input[name=od_price]").val(basePrice - maxCoupon.price);
                $("input[name=od_cp_id]").val(maxCoupon.id);
                $("input[name=od_coupon]").val(maxCoupon.price);
                $("input[name=od_send_coupon]").val(0);
                auto_applied_od_cp_id = maxCoupon.id;
                $("#od_cp_price").text(number_format(String(maxCoupon.price)));
                $("#sc_cp_price").text(0);
                calculate_order_price();

                if (!$("#od_coupon_cancel").length) {
                    $("#od_coupon_btn").after("<button type=\"button\" id=\"od_coupon_cancel\" class=\"cp_cancel\">취소</button>");
                }
            }
        );
    }

    // 보유/적용 상태에 맞춰 쿠폰 버튼의 텍스트 UI를 갱신한다.
    function update_coupon_button_ui() {
        var totalCount = <?php echo (int)$coupon_total_count; ?>;
        var itemCoupon = parseInt($("input[name=item_coupon]").val(), 10) || 0;
        var odCoupon = parseInt($("input[name=od_coupon]").val(), 10) || 0;
        var sendCoupon = parseInt($("input[name=od_send_coupon]").val(), 10) || 0;
        var couponTotal = itemCoupon + odCoupon + sendCoupon;
        var isAutoOn = $("#max_coupon_toggle").attr("aria-pressed") === "true";
        var currentOdCpId = $("input[name=od_cp_id]").val() || "";
        var $label = $("#od_coupon_btn_label");
        var $badge = $("#od_coupon_btn_badge");
        var $amount = $("#od_coupon_btn_amount");

        if (!$label.length || !$amount.length || !$badge.length) {
            return;
        }

        if (totalCount <= 0) {
            $label.text("사용 가능한 쿠폰이 없습니다.");
            $badge.addClass("hidden");
            $amount.text("");
            return;
        }

        if (couponTotal > 0) {
            $label.text("쿠폰 할인");
            $badge.toggleClass("hidden", !(isAutoOn && auto_applied_od_cp_id && currentOdCpId === auto_applied_od_cp_id));
            $amount.removeClass("text-[#666]").addClass("font-semibold text-[#ef5a29]").text(number_format(String(couponTotal)) + "원");
        } else {
            $label.text("쿠폰 선택");
            $badge.addClass("hidden");
            $amount.removeClass("font-semibold text-[#ef5a29]").addClass("text-[#666]").text("할인 없음");
        }
    }

    function calculate_total_price() {
        var $it_prc = $("input[name^=it_price]");
        var $cp_prc = $("input[name^=cp_price]");
        var tot_sell_price = sell_price = tot_cp_price = 0;
        var it_price, cp_price, it_notax;
        var tot_mny = comm_tax_mny = comm_vat_mny = comm_free_mny = tax_mny = vat_mny = 0;
        var send_cost = parseInt($("input[name=od_send_cost]").val());

        $it_prc.each(function(index) {
            it_price = parseInt($(this).val());
            cp_price = parseInt($cp_prc.eq(index).val());
            sell_price += it_price;
            tot_cp_price += cp_price;
        });

        tot_sell_price = sell_price - tot_cp_price + send_cost;

        $("#ct_tot_coupon").text(number_format(String(tot_cp_price)));
        $("#ct_tot_price").text(number_format(String(tot_sell_price)));

        $("input[name=good_mny]").val(tot_sell_price);
        $("input[name=od_price]").val(sell_price - tot_cp_price);
        $("input[name=item_coupon]").val(tot_cp_price);
        $("input[name=od_coupon]").val(0);
        $("input[name=od_send_coupon]").val(0);
        <?php if ($oc_cnt > 0) { ?>
            $("input[name=od_cp_id]").val("");
            $("#od_cp_price").text(0);
            if ($("#od_coupon_cancel").length) {
                $("#od_coupon_cancel").remove();
            }
        <?php } ?>
        <?php if ($sc_cnt > 0) { ?>
            $("input[name=sc_cp_id]").val("");
            $("#sc_cp_price").text(0);
            if ($("#sc_coupon_cancel").length) {
                $("#sc_coupon_btn").text("쿠폰적용");
                $("#sc_coupon_cancel").remove();
            }
        <?php } ?>
        $("input[name=od_temp_point]").val(0);
        <?php if ($temp_point > 0 && $is_member) { ?>
            calculate_temp_point();
        <?php } ?>
        calculate_order_price();
        update_expected_price();
    }

    function calculate_order_price() {
        var sell_price = parseInt($("input[name=od_price]").val());
        var send_cost = parseInt($("input[name=od_send_cost]").val());
        var send_cost2 = parseInt($("input[name=od_send_cost2]").val());
        var send_coupon = parseInt($("input[name=od_send_coupon]").val());
        var tot_price = sell_price + send_cost + send_cost2 - send_coupon;

        $("input[name=good_mny]").val(tot_price);
        $("#od_tot_price .print_price").text(number_format(String(tot_price)));
        <?php if ($temp_point > 0 && $is_member) { ?>
            calculate_temp_point();
        <?php } ?>
        update_expected_price();
    }

    function calculate_temp_point() {
        var sell_price = parseInt($("input[name=od_price]").val());
        var mb_point = parseInt(<?php echo $member['mb_point']; ?>);
        var max_point = parseInt(<?php echo $default['de_settle_max_point']; ?>);
        var point_unit = parseInt(<?php echo $default['de_settle_point_unit']; ?>);
        var temp_point = max_point;

        if (temp_point > sell_price)
            temp_point = sell_price;

        if (temp_point > mb_point)
            temp_point = mb_point;

        temp_point = parseInt(temp_point / point_unit) * point_unit;

        $("#use_max_point").text(number_format(String(temp_point)) + "점");
        $("input[name=max_temp_point]").val(temp_point);
        sync_point_apply_button_ui();
    }

    function sync_point_apply_button_ui() {
        var $button = $("#use_all_point_btn");
        if (!$button.length) {
            return;
        }

        var maxPoint = parseInt($("input[name=max_temp_point]").val(), 10) || 0;
        var isUsable = maxPoint > 0;

        $button.prop("disabled", !isUsable);
        $button.toggleClass("bg-gray-900 text-white", isUsable);
        $button.toggleClass("bg-gray-200 text-gray-400", !isUsable);
    }

    function calculate_sendcost(code) {
        $.post(
            "./ordersendcost.php", {
                zipcode: code
            },
            function(data) {
                $("input[name=od_send_cost2]").val(data);
                $("#od_send_cost2").text(number_format(String(data)));

                zipcode = code;

                calculate_order_price();
            }
        );
    }

    function calculate_tax() {
        var $it_prc = $("input[name^=it_price]");
        var $cp_prc = $("input[name^=cp_price]");
        var sell_price = tot_cp_price = 0;
        var it_price, cp_price, it_notax;
        var tot_mny = comm_free_mny = tax_mny = vat_mny = 0;
        var send_cost = parseInt($("input[name=od_send_cost]").val());
        var send_cost2 = parseInt($("input[name=od_send_cost2]").val());
        var od_coupon = parseInt($("input[name=od_coupon]").val());
        var send_coupon = parseInt($("input[name=od_send_coupon]").val());
        var temp_point = 0;

        $it_prc.each(function(index) {
            it_price = parseInt($(this).val());
            cp_price = parseInt($cp_prc.eq(index).val());
            sell_price += it_price;
            tot_cp_price += cp_price;
            it_notax = $("input[name^=it_notax]").eq(index).val();
            if (it_notax == "1") {
                comm_free_mny += (it_price - cp_price);
            } else {
                tot_mny += (it_price - cp_price);
            }
        });

        if ($("input[name=od_temp_point]").length)
            temp_point = parseInt($("input[name=od_temp_point]").val());

        tot_mny += (send_cost + send_cost2 - od_coupon - send_coupon - temp_point);
        if (tot_mny < 0) {
            comm_free_mny = comm_free_mny + tot_mny;
            tot_mny = 0;
        }

        tax_mny = Math.round(tot_mny / 1.1);
        vat_mny = tot_mny - tax_mny;
        $("input[name=comm_tax_mny]").val(tax_mny);
        $("input[name=comm_vat_mny]").val(vat_mny);
        $("input[name=comm_free_mny]").val(comm_free_mny);
    }

    function forderform_check(f) {
        // 재고체크
        var stock_msg = order_stock_check();
        if (stock_msg != "") {
            alert(stock_msg);
            return false;
        }

        errmsg = "";
        errfld = "";
        var deffld = "";

        check_field(f.od_name, "주문하시는 분 이름을 입력하십시오.");
        if (typeof(f.od_pwd) != 'undefined') {
            clear_field(f.od_pwd);
            if ((f.od_pwd.value.length < 3) || (f.od_pwd.value.search(/([^A-Za-z0-9]+)/) != -1))
                error_field(f.od_pwd, "회원이 아니신 경우 주문서 조회시 필요한 비밀번호를 3자리 이상 입력해 주십시오.");
        }
        check_field(f.od_tel, "주문하시는 분 전화번호를 입력하십시오.");
        check_field(f.od_addr1, "주소검색을 이용하여 주문하시는 분 주소를 입력하십시오.");
        //check_field(f.od_addr2, " 주문하시는 분의 상세주소를 입력하십시오.");
        check_field(f.od_zip, "");

        clear_field(f.od_email);
        if (f.od_email.value == '' || f.od_email.value.search(/(\S+)@(\S+)\.(\S+)/) == -1)
            error_field(f.od_email, "E-mail을 바르게 입력해 주십시오.");

        if (typeof(f.od_hope_date) != "undefined") {
            clear_field(f.od_hope_date);
            if (!f.od_hope_date.value)
                error_field(f.od_hope_date, "희망배송일을 선택하여 주십시오.");
        }

        check_field(f.od_b_name, "받으시는 분 이름을 입력하십시오.");
        check_field(f.od_b_tel, "받으시는 분 전화번호를 입력하십시오.");
        check_field(f.od_b_addr1, "주소검색을 이용하여 받으시는 분 주소를 입력하십시오.");
        //check_field(f.od_b_addr2, "받으시는 분의 상세주소를 입력하십시오.");
        check_field(f.od_b_zip, "");

        var od_settle_bank = document.getElementById("od_settle_bank");
        if (od_settle_bank) {
            if (od_settle_bank.checked) {
                check_field(f.od_bank_account, "계좌번호를 선택하세요.");
                check_field(f.od_deposit_name, "입금자명을 입력하세요.");
            }
        }

        // 배송비를 받지 않거나 더 받는 경우 아래식에 + 또는 - 로 대입
        f.od_send_cost.value = parseInt(f.od_send_cost.value);

        if (errmsg) {
            alert(errmsg);
            errfld.focus();
            return false;
        }

        var settle_case = document.getElementsByName("od_settle_case");
        var settle_check = false;
        var settle_method = "";

        for (i = 0; i < settle_case.length; i++) {
            if (settle_case[i].checked) {
                settle_check = true;
                settle_method = settle_case[i].value;
                break;
            }
        }
        if (!settle_check) {
            alert("결제방식을 선택하십시오.");
            return false;
        }

        var od_price = parseInt(f.od_price.value);
        var send_cost = parseInt(f.od_send_cost.value);
        var send_cost2 = parseInt(f.od_send_cost2.value);
        var send_coupon = parseInt(f.od_send_coupon.value);

        var max_point = 0;
        if (typeof(f.max_temp_point) != "undefined")
            max_point = parseInt(f.max_temp_point.value);

        var temp_point = 0;
        if (typeof(f.od_temp_point) != "undefined") {
            var point_unit = parseInt(<?php echo $default['de_settle_point_unit']; ?>);
            temp_point = parseInt(f.od_temp_point.value) || 0;

            if (f.od_temp_point.value) {
                if (temp_point > od_price) {
                    alert("상품 주문금액(배송비 제외) 보다 많이 포인트결제할 수 없습니다.");
                    f.od_temp_point.select();
                    return false;
                }

                if (temp_point > <?php echo (int)$member['mb_point']; ?>) {
                    alert("회원님의 포인트보다 많이 결제할 수 없습니다.");
                    f.od_temp_point.select();
                    return false;
                }

                if (temp_point > max_point) {
                    alert(max_point + "점 이상 결제할 수 없습니다.");
                    f.od_temp_point.select();
                    return false;
                }

                if (parseInt(parseInt(temp_point / point_unit) * point_unit) != temp_point) {
                    alert("포인트를 " + String(point_unit) + "점 단위로 입력하세요.");
                    f.od_temp_point.select();
                    return false;
                }
            }

            // pg 결제 금액에서 포인트 금액 차감
            if (settle_method != "무통장") {
                f.good_mny.value = od_price + send_cost + send_cost2 - send_coupon - temp_point;
            }
        }

        var tot_price = od_price + send_cost + send_cost2 - send_coupon - temp_point;

        if (document.getElementById("od_settle_iche")) {
            if (document.getElementById("od_settle_iche").checked) {
                if (tot_price < 150) {
                    alert("계좌이체는 150원 이상 결제가 가능합니다.");
                    return false;
                }
            }
        }

        if (document.getElementById("od_settle_card")) {
            if (document.getElementById("od_settle_card").checked) {
                if (tot_price < 1000) {
                    alert("신용카드는 1000원 이상 결제가 가능합니다.");
                    return false;
                }
            }
        }

        if (document.getElementById("od_settle_hp")) {
            if (document.getElementById("od_settle_hp").checked) {
                if (tot_price < 350) {
                    alert("휴대폰은 350원 이상 결제가 가능합니다.");
                    return false;
                }
            }
        }

        <?php if ($default['de_tax_flag_use']) { ?>
            calculate_tax();
        <?php } ?>

        <?php if ($default['de_pg_service'] == 'inicis') { ?>
            if (f.action != form_action_url) {
                f.action = form_action_url;
                f.removeAttribute("target");
                f.removeAttribute("accept-charset");
            }
        <?php } ?>

        // 카카오페이 지불
        if (settle_method == "KAKAOPAY") {
            <?php if ($default['de_tax_flag_use']) { ?>
                f.SupplyAmt.value = parseInt(f.comm_tax_mny.value) + parseInt(f.comm_free_mny.value);
                f.GoodsVat.value = parseInt(f.comm_vat_mny.value);
            <?php } ?>
            getTxnId(f);
            return false;
        }

        var form_order_method = '';

        if (settle_method == "lpay" || settle_method == "inicis_kakaopay") { //이니시스 L.pay 또는 이니시스 카카오페이 이면 ( 이니시스의 삼성페이는 모바일에서만 단독실행 가능함 )
            form_order_method = 'samsungpay';
        } else if (settle_method == "간편결제") {
            if (jQuery("input[name='od_settle_case']:checked").attr("data-pay") === "naverpay") {
                form_order_method = 'nhnkcp_naverpay';
            }
        }

        if (jQuery(f).triggerHandler("form_sumbit_order_" + form_order_method) !== false) {

            // pay_method 설정
            <?php if ($default['de_pg_service'] == 'kcp') { ?>
                f.site_cd.value = f.def_site_cd.value;
                if (typeof f.payco_direct !== "undefined") f.payco_direct.value = "";
                if (typeof f.naverpay_direct !== "undefined") f.naverpay_direct.value = "A";
                if (typeof f.kakaopay_direct !== "undefined") f.kakaopay_direct.value = "A";
                switch (settle_method) {
                    case "계좌이체":
                        f.pay_method.value = "010000000000";
                        break;
                    case "가상계좌":
                        f.pay_method.value = "001000000000";
                        break;
                    case "휴대폰":
                        f.pay_method.value = "000010000000";
                        break;
                    case "신용카드":
                        f.pay_method.value = "100000000000";
                        break;
                    case "간편결제":
                        f.pay_method.value = "100000000000";

                        var nhnkcp_easy_pay = jQuery("input[name='od_settle_case']:checked").attr("data-pay");

                        if (nhnkcp_easy_pay === "naverpay") {
                            if (typeof f.naverpay_direct !== "undefined") f.naverpay_direct.value = "Y";

                            var is_money = jQuery("input[name='od_settle_case']:checked").attr("data-money");

                            if (is_money) { // 머니/포인트 결제
                                jQuery(f).find("input[name='naverpay_point_direct']").val("Y");
                            } else { // 카드 결제
                                jQuery(f).find("input[name='naverpay_point_direct']").val("");
                            }

                        } else if (nhnkcp_easy_pay === "kakaopay") {
                            if (typeof f.kakaopay_direct !== "undefined") f.kakaopay_direct.value = "Y";
                        } else {
                            if (typeof f.payco_direct !== "undefined") f.payco_direct.value = "Y";
                            <?php if ($default['de_card_test']) { ?>
                                f.site_cd.value = "S6729";
                            <?php } ?>
                        }

                        break;
                    default:
                        f.pay_method.value = "무통장";
                        break;
                }
            <?php } else if ($default['de_pg_service'] == 'lg') { ?>
                f.LGD_EASYPAY_ONLY.value = "";
                if (typeof f.LGD_CUSTOM_USABLEPAY === "undefined") {
                    var input = document.createElement("input");
                    input.setAttribute("type", "hidden");
                    input.setAttribute("name", "LGD_CUSTOM_USABLEPAY");
                    input.setAttribute("value", "");
                    f.LGD_EASYPAY_ONLY.parentNode.insertBefore(input, f.LGD_EASYPAY_ONLY);
                }

                switch (settle_method) {
                    case "계좌이체":
                        f.LGD_CUSTOM_FIRSTPAY.value = "SC0030";
                        f.LGD_CUSTOM_USABLEPAY.value = "SC0030";
                        break;
                    case "가상계좌":
                        f.LGD_CUSTOM_FIRSTPAY.value = "SC0040";
                        f.LGD_CUSTOM_USABLEPAY.value = "SC0040";
                        break;
                    case "휴대폰":
                        f.LGD_CUSTOM_FIRSTPAY.value = "SC0060";
                        f.LGD_CUSTOM_USABLEPAY.value = "SC0060";
                        break;
                    case "신용카드":
                        f.LGD_CUSTOM_FIRSTPAY.value = "SC0010";
                        f.LGD_CUSTOM_USABLEPAY.value = "SC0010";
                        break;
                    case "간편결제":
                        var elm = f.LGD_CUSTOM_USABLEPAY;
                        if (elm.parentNode)
                            elm.parentNode.removeChild(elm);
                        f.LGD_EASYPAY_ONLY.value = "PAYNOW";
                        break;
                    default:
                        f.LGD_CUSTOM_FIRSTPAY.value = "무통장";
                        break;
                }
            <?php } else if ($default['de_pg_service'] == 'toss') { ?>
                switch (settle_method) {
                    case "계좌이체":
                        f.method.value = "TRANSFER";
                        break;
                    case "가상계좌":
                        f.method.value = "VIRTUAL_ACCOUNT";
                        break;
                    case "휴대폰":
                        f.method.value = "MOBILE_PHONE";
                        break;
                    case "신용카드":
                        f.method.value = "CARD";
                        break;
                    case "간편결제":
                        f.method.value = "CARD";
                        break;
                    default:
                        f.method.value = "무통장";
                        break;
                }
            <?php } else if ($default['de_pg_service'] == 'inicis') { ?>
                switch (settle_method) {
                    case "계좌이체":
                        f.gopaymethod.value = "DirectBank";
                        break;
                    case "가상계좌":
                        f.gopaymethod.value = "VBank";
                        break;
                    case "휴대폰":
                        f.gopaymethod.value = "HPP";
                        break;
                    case "신용카드":
                        f.gopaymethod.value = "Card";
                        f.acceptmethod.value = f.acceptmethod.value.replace(":useescrow", "");
                        break;
                    case "간편결제":
                        f.gopaymethod.value = "Kpay";
                        break;
                    case "lpay":
                        f.gopaymethod.value = "onlylpay";
                        f.acceptmethod.value = f.acceptmethod.value + ":cardonly";
                        break;
                    case "inicis_kakaopay":
                        f.gopaymethod.value = "onlykakaopay";
                        f.acceptmethod.value = f.acceptmethod.value + ":cardonly";
                        break;
                    default:
                        f.gopaymethod.value = "무통장";
                        break;
                }
            <?php } else if ($default['de_pg_service'] == 'nicepay') { ?>
                f.DirectShowOpt.value = ""; // 간편결제 요청 값 초기화
                f.DirectEasyPay.value = ""; // 간편결제 요청 값 초기화
                f.NicepayReserved.value = ""; // 간편결제 요청 값 초기화
                f.EasyPayMethod.value = ""; // 간편결제 요청 값 초기화

                <?php if ($default['de_escrow_use']) {  // 간편결제시 에스크로값이 0이 되므로 기본설정값을 지정 
                ?>
                    f.TransType.value = "1";
                <?php } ?>
                switch (settle_method) {
                    case "계좌이체":
                        f.PayMethod.value = "BANK";
                        break;
                    case "가상계좌":
                        f.PayMethod.value = "VBANK";
                        break;
                    case "휴대폰":
                        f.PayMethod.value = "CELLPHONE";
                        break;
                    case "신용카드":
                        f.PayMethod.value = "CARD";
                        break;
                    case "간편결제":
                        f.PayMethod.value = "CARD";
                        f.DirectShowOpt.value = "CARD";
                        f.TransType.value = "0"; // 간편결제의 경우 에스크로를 사용할수 없다.

                        var nicepay_easy_pay = jQuery("input[name='od_settle_case']:checked").attr("data-pay");

                        if (nicepay_easy_pay === "nice_naverpay") {
                            if (typeof f.DirectEasyPay !== "undefined") f.DirectEasyPay.value = "E020";

                            <?php
                            // * 카드 선택 시 전액 카드로 결제, 포인트 선택 시 전액 포인트로 결제.
                            // (카드와 포인트를 같이 사용하는 복합결제 형태의 결제는 불가함.)
                            // - 카드: EasyPayMethod=”E020=CARD”, 포인트: EasyPayMethod=”E020=POINT”
                            ?>

                            if (typeof f.EasyPayMethod !== "undefined") f.EasyPayMethod.value = "E020=CARD";

                        } else if (nicepay_easy_pay === "nice_kakaopay") {
                            if (typeof f.NicepayReserved !== "undefined") f.NicepayReserved.value = "DirectKakao=Y";
                        } else if (nicepay_easy_pay === "nice_samsungpay") {
                            if (typeof f.DirectEasyPay !== "undefined") f.DirectEasyPay.value = "E021";
                        } else if (nicepay_easy_pay === "nice_paycopay") {
                            if (typeof f.NicepayReserved !== "undefined") f.NicepayReserved.value = "DirectPayco=Y";
                        } else if (nicepay_easy_pay === "nice_skpay") {
                            if (typeof f.NicepayReserved !== "undefined") f.NicepayReserved.value = "DirectPay11=Y";
                        } else if (nicepay_easy_pay === "nice_ssgpay") {
                            if (typeof f.DirectEasyPay !== "undefined") f.DirectEasyPay.value = "E007";
                        } else if (nicepay_easy_pay === "nice_lpay") {
                            if (typeof f.DirectEasyPay !== "undefined") f.DirectEasyPay.value = "E018";
                        }

                        break;
                    default:
                        f.PayMethod.value = "무통장";
                        break;
                }
            <?php } ?>
            // 결제정보설정
            <?php if ($default['de_pg_service'] == 'kcp') { ?>
                f.buyr_name.value = f.od_name.value;
                f.buyr_mail.value = f.od_email.value;
                f.buyr_tel1.value = f.od_tel.value;
                f.buyr_tel2.value = f.od_hp.value;
                f.rcvr_name.value = f.od_b_name.value;
                f.rcvr_tel1.value = f.od_b_tel.value;
                f.rcvr_tel2.value = f.od_b_hp.value;
                f.rcvr_mail.value = f.od_email.value;
                f.rcvr_zipx.value = f.od_b_zip.value;
                f.rcvr_add1.value = f.od_b_addr1.value;
                f.rcvr_add2.value = f.od_b_addr2.value;

                if (f.pay_method.value != "무통장") {
                    jsf__pay(f);
                } else {
                    f.submit();
                }
            <?php } ?>
            <?php if ($default['de_pg_service'] == 'lg') { ?>
                f.LGD_BUYER.value = f.od_name.value;
                f.LGD_BUYEREMAIL.value = f.od_email.value;
                f.LGD_BUYERPHONE.value = f.od_hp.value;
                f.LGD_AMOUNT.value = f.good_mny.value;
                f.LGD_RECEIVER.value = f.od_b_name.value;
                f.LGD_RECEIVERPHONE.value = f.od_b_hp.value;
                <?php if ($default['de_escrow_use']) { ?>
                    f.LGD_ESCROW_ZIPCODE.value = f.od_b_zip.value;
                    f.LGD_ESCROW_ADDRESS1.value = f.od_b_addr1.value;
                    f.LGD_ESCROW_ADDRESS2.value = f.od_b_addr2.value;
                    f.LGD_ESCROW_BUYERPHONE.value = f.od_hp.value;
                <?php } ?>
                <?php if ($default['de_tax_flag_use']) { ?>
                    f.LGD_TAXFREEAMOUNT.value = f.comm_free_mny.value;
                <?php } ?>

                if (f.LGD_CUSTOM_FIRSTPAY.value != "무통장") {
                    launchCrossPlatform(f);
                } else {
                    f.submit();
                }
            <?php } ?>
            <?php if ($default['de_pg_service'] == 'toss') { ?>

                f.orderId.value = '<?= $od_id ?>';
                f.orderName.value = '<?= $goods ?>';

                f.customerName.value = f.od_name.value;
                f.customerEmail.value = f.od_email.value;
                f.customerMobilePhone.value = f.od_hp.value.replace(/[^0-9]/g, '');
                if (f.customerMobilePhone.value == '') {
                    f.customerMobilePhone.value = f.od_tel.value.replace(/[^0-9]/g, '');
                }

                f.cardUseCardPoint.value = false;
                f.cardUseAppCardOnly.value = false;

                <?php if ($default['de_escrow_use']) { ?>
                    f.cardUseEscrow.value = 'true';
                    f.escrowProducts.value = JSON.stringify(<?php echo json_encode($escrow_products, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
                <?php } ?>

                if (settle_method == "간편결제") {
                    f.cardflowMode.value = 'DIRECT';
                }

                f.amountCurrency.value = 'KRW';
                f.amountValue.value = f.good_mny.value;
                <?php if ($default['de_tax_flag_use']) { ?>
                    f.taxFreeAmount.value = f.comm_free_mny.value;
                <?php } ?>
                f.windowTarget.value = 'iframe';

                if (f.method.value != "무통장") {
                    // 주문정보 임시저장
                    var order_data = $(f).serialize();
                    var save_result = "";
                    $.ajax({
                        type: "POST",
                        data: order_data,
                        url: g5_url + "/shop/ajax.orderdatasave.php",
                        cache: false,
                        async: false,
                        success: function(data) {
                            save_result = data;
                        }
                    });

                    if (save_result) {
                        alert(save_result);
                        return false;
                    }

                    launchCrossPlatform(f);
                } else {
                    f.submit();
                }
            <?php } ?>
            <?php if ($default['de_pg_service'] == 'inicis') { ?>
                f.price.value = f.good_mny.value;
                <?php if ($default['de_tax_flag_use']) { ?>
                    f.tax.value = f.comm_vat_mny.value;
                    f.taxfree.value = f.comm_free_mny.value;
                <?php } ?>
                f.buyername.value = f.od_name.value;
                f.buyeremail.value = f.od_email.value;
                f.buyertel.value = f.od_hp.value ? f.od_hp.value : f.od_tel.value;
                f.recvname.value = f.od_b_name.value;
                f.recvtel.value = f.od_b_hp.value ? f.od_b_hp.value : f.od_b_tel.value;
                f.recvpostnum.value = f.od_b_zip.value;
                f.recvaddr.value = f.od_b_addr1.value + " " + f.od_b_addr2.value;

                if (f.gopaymethod.value != "무통장") {
                    // 주문정보 임시저장
                    var order_data = $(f).serialize();
                    var save_result = "";
                    $.ajax({
                        type: "POST",
                        data: order_data,
                        url: g5_url + "/shop/ajax.orderdatasave.php",
                        cache: false,
                        async: false,
                        success: function(data) {
                            save_result = data;
                        }
                    });

                    if (save_result) {
                        alert(save_result);
                        return false;
                    }

                    if (!make_signature(f))
                        return false;

                    paybtn(f);
                } else {
                    f.submit();
                }
            <?php } ?>
            <?php if ($default['de_pg_service'] == 'nicepay') { ?>
                f.Amt.value = f.good_mny.value;
                <?php if ($default['de_tax_flag_use']) { ?>
                    f.SupplyAmt.value = f.comm_tax_mny.value;
                    f.GoodsVat.value = f.comm_vat_mny.value;
                    f.TaxFreeAmt.value = f.comm_free_mny.value;
                <?php } ?>
                f.BuyerName.value = f.od_name.value;
                f.BuyerEmail.value = f.od_email.value;
                f.BuyerTel.value = f.od_hp.value ? f.od_hp.value : f.od_tel.value;

                if (f.PayMethod.value != "무통장") {
                    // 주문정보 임시저장
                    var order_data = $(f).serialize();
                    var save_result = "";
                    $.ajax({
                        type: "POST",
                        data: order_data,
                        url: g5_url + "/shop/ajax.orderdatasave.php",
                        cache: false,
                        async: false,
                        success: function(data) {
                            save_result = data;
                        }
                    });

                    if (save_result) {
                        alert(save_result);
                        return false;
                    }

                    if (!nicepay_create_signdata(f))
                        return false;

                    nicepayStart(f);
                } else {
                    f.submit();
                }

            <?php } ?>
        }

    }

    // 구매자 정보와 동일합니다.
    function gumae2baesong() {
        var f = document.forderform;

        f.od_b_name.value = f.od_name.value;
        f.od_b_tel.value = f.od_tel.value;
        f.od_b_hp.value = f.od_hp.value;
        f.od_b_zip.value = f.od_zip.value;
        f.od_b_addr1.value = f.od_addr1.value;
        f.od_b_addr2.value = f.od_addr2.value;
        f.od_b_addr3.value = f.od_addr3.value;
        f.od_b_addr_jibeon.value = f.od_addr_jibeon.value;

        calculate_sendcost(String(f.od_b_zip.value));
    }

    <?php if ($default['de_hope_date_use']) { ?>
        $(function() {
            $("#od_hope_date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                showButtonPanel: true,
                yearRange: "c-99:c+99",
                minDate: "+<?php echo (int)$default['de_hope_date_after']; ?>d;",
                maxDate: "+<?php echo (int)$default['de_hope_date_after'] + 6; ?>d;"
            });
        });
    <?php } ?>
</script>
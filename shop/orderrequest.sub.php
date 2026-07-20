<?php
if (!defined('_GNUBOARD_'))
    exit;
if (!defined('_ORDERREQUEST_'))
    exit;

// 현재 페이지에 표시할 취소·반품 주문 목록 SQL
// select o.* 는 orderrequest.php 에 주문 테이블 별칭인 o 에서 모든 필드를 가져오겠다는 뜻
// order by o.od_id desc 는 주문번호 기준 내림차순, 주문번호가 큰 값이 최근 주문
$sql = " select o.*
           {$sql_common}
          order by o.od_id desc
          {$limit} ";

// 조회 결과
$result = sql_query($sql);

// 현재 페이지에서 사용할 주문 목록
$orders = array();

// 현재 페이지 주문의 주문번호 목록
$order_ids = array();

// 조회한 주문을 주문 목록과 주문번호 목록에 저장
for ($i = 0; $row = sql_fetch_array($result); $i++) {
    $orders[] = $row;
    $order_ids[] = "'" . sql_real_escape_string($row['od_id']) . "'";
}

// 주문별 취소 반품 상품 목록
$request_items = array();

// 주문별 상품별 장바구니 옵션 목록
$request_item_options = array();

// 현재 페이지 주문에서 취소 또는 반품된 상품 목록 SQL
if (!empty($order_ids)) {
    $item_sql = " select od_id, ct_id, it_id, it_name, ct_option, ct_qty,
                         ct_price, io_price, io_type, io_id, ct_status
                    from {$g5['g5_shop_cart_table']}
                   where od_id in (" . implode(',', $order_ids) . ")
                     and ct_status in ('취소', '반품')
                   order by od_id asc, ct_id asc ";

    // 취소 반품 상품 목록 조회 결과
    $item_result = sql_query($item_sql);

    // 취소 반품 상품을 주문번호별로 묶어서 저장
    for ($i = 0; $item = sql_fetch_array($item_result); $i++) {
        $od_id = $item['od_id'];

        if (!isset($request_items[$od_id])) {
            $request_items[$od_id] = array();
        }

        $request_items[$od_id][] = $item;

        if (!isset($request_item_options[$od_id])) {
            $request_item_options[$od_id] = array();
        }

        if (!isset($request_item_options[$od_id][$item['it_id']])) {
            $request_item_options[$od_id][$item['it_id']] = array();
        }

        $request_item_options[$od_id][$item['it_id']][] = array(
            'it_id' => $item['it_id'],
            'io_id' => $item['io_id'],
            'io_type' => (string) $item['io_type'],
            'ct_option' => $item['ct_option'],
            'ct_qty' => (int) $item['ct_qty']
        );
    }
}
?>

<!-- 모바일 헤더 -->
<div class="flex pc:hidden items-center justify-between p-4">
    <button type="button" class="inline-flex items-center justify-center text-zinc-700" aria-label="뒤로가기"
        onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '<?php echo G5_URL ?>'; }">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-chevron-left-icon lucide-chevron-left w-6 h-6">
            <path d="m15 18-6-6 6-6" />
        </svg>
    </button>
    <h1 class="text-lg font-semibold text-zinc-900 leading-0"><?php echo $g5['title']; ?></h1>
    <div class="w-6 h-6" aria-hidden="true"></div>
</div>

<!-- 주문 내역 시작 -->
<section class="min-w-0 flex-1">

    <!-- PC 너비 타이틀 -->
    <div class="hidden pc:block px-4">
        <h2 class="text-2xl font-bold pb-4 border-b-2 border-gray-900">
            <?php echo $g5['title']; ?>
        </h2>
    </div>

    <?php if (empty($orders)) { ?>
        <p class="p-4 text-sm text-zinc-500">취소 또는 반품 내역이 없습니다.</p>
    <?php } else { ?>
        <?php foreach ($orders as $order) { ?>
            <article class="border-b border-zinc-200 p-4">
                <?php
                $uid = md5($order['od_id'] . $order['od_time'] . $order['od_ip']);
                $od_date = str_replace('-', '.', substr($order['od_time'], 0, 10));
                $request_item_rows = isset($request_items[$order['od_id']]) ? $request_items[$order['od_id']] : array();
                ?>

                <div class="order-card-head flex items-center justify-between gap-2">
                    <p class="text-base text-[var(--color-semantic-label-solid-default)] font-semibold"><?php echo $od_date; ?></p>
                    <a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $order['od_id']; ?>&amp;uid=<?php echo $uid; ?>"
                        class="inline-flex items-center text-sm text-[var(--color-semantic-label-solid-default)] font-normal">
                        주문 상세보기
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-4">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </a>
                </div>

                <div class="divide-y divide-[var(--color-semantic-border-solid-subtle)]">
                    <?php foreach ($request_item_rows as $item) { ?>
                        <?php
                        $thumb_html = get_it_image($item['it_id'], 80, 102);
                        $item_url = shop_item_url($item['it_id']);
                        $item_name = get_text($item['it_name']);
                        $item_status = get_text($item['ct_status']);
                        $item_qty = (int) $item['ct_qty'];
                        $item_reorder_rows = isset($request_item_options[$order['od_id']][$item['it_id']])
                            ? $request_item_options[$order['od_id']][$item['it_id']]
                            : array();
                        $item_reorder_json = htmlspecialchars(json_encode($item_reorder_rows), ENT_QUOTES);

                        if ((string) $item['io_type'] === '1') {
                            $item_total_price = (int) $item['io_price'] * $item_qty;
                        } else {
                            $item_total_price = ((int) $item['ct_price'] + (int) $item['io_price']) * $item_qty;
                        }
                        ?>

                        <div class="order-card-items grid grid-cols-[80px_1fr_auto] gap-4 py-3">
                            <div class="overflow-hidden rounded bg-zinc-100">
                                <?php echo $thumb_html ? $thumb_html : '<div class="h-full w-full aspect-[80/102]"></div>'; ?>
                            </div>

                            <div class="flex flex-col justify-start gap-1.5 text-[var(--color-semantic-label-solid-default)]">
                                <a href="<?php echo $item_url; ?>" class="text-[15px] font-normal">
                                    <?php echo $item_name; ?>
                                </a>

                                <p class="text-sm text-zinc-600">
                                    <span class="font-semibold text-zinc-900"><?php echo $item_status; ?></span>
                                    <span class="mx-1">|</span>
                                    <?php echo number_format($item_qty); ?>개
                                </p>

                                <p class="font-bold">
                                    <?php echo display_price($item_total_price); ?>
                                </p>
                            </div>

                            <button type="button"
                                class="js-order-add-cart flex h-11 w-11 items-center justify-center rounded border border-[var(--color-semantic-border-normal-default)] text-[var(--color-semantic-label-solid-default)]"
                                data-it-id="<?php echo $item['it_id']; ?>" data-reorder-options="<?php echo $item_reorder_json; ?>"
                                aria-label="장바구니">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="lucide lucide-shopping-cart-icon lucide-shopping-cart w-5 h-5">
                                    <circle cx="8" cy="21" r="1" />
                                    <circle cx="19" cy="21" r="1" />
                                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 1.95 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                                </svg>
                            </button>
                        </div>
                    <?php } ?>
                </div>

                <div class="order-card-actions mt-2 grid grid-cols-1 gap-2">
                    <a href="<?php echo G5_BBS_URL; ?>/qalist.php"
                        class="flex items-center justify-center border border-[var(--color-semantic-border-normal-strong)] rounded px-5 py-2.5 text-sm text-[var(--color-semantic-label-solid-default)] font-medium bg-white">
                        문의하기
                    </a>
                </div>
            </article>
        <?php } ?>
    <?php } ?>
</section>

<script>
    $(function () {
        const $cartButtons = $('.js-order-add-cart');

        if (!$cartButtons.length) {
            return;
        }

        $cartButtons.on('click', function () {
            const $button = $(this);
            const itemId = $button.attr('data-it-id');

            if (!itemId) {
                alert('상품코드가 올바르지 않습니다.');
                return;
            }

            let reorderRows = [];

            try {
                reorderRows = JSON.parse($button.attr('data-reorder-options') || '[]');
            } catch (error) {
                reorderRows = [];
            }

            if (!Array.isArray(reorderRows) || !reorderRows.length) {
                reorderRows = [{
                    it_id: itemId,
                    io_id: '',
                    io_type: '0',
                    ct_option: '',
                    ct_qty: 1
                }];
            }

            const formData = new URLSearchParams();

            formData.append('action', 'cart_update');
            formData.append('it_id[]', itemId);
            formData.append('sw_direct', '0');

            for (let i = 0; i < reorderRows.length; i++) {
                const row = reorderRows[i] || {};
                const ioId = row.io_id ? String(row.io_id) : '';
                const ioType = row.io_type ? String(row.io_type) : '0';
                const optionText = row.ct_option ? String(row.ct_option) : '';
                const qty = Number(row.ct_qty) > 0 ? String(Number(row.ct_qty)) : '1';

                formData.append('io_id[' + itemId + '][]', ioId);
                formData.append('io_type[' + itemId + '][]', ioType);
                formData.append('io_value[' + itemId + '][]', optionText);
                formData.append('ct_qty[' + itemId + '][]', qty);
            }

            $.ajax({
                url: '<?php echo G5_SHOP_URL; ?>/ajax.action.php',
                type: 'POST',
                data: formData.toString(),
                dataType: 'json',
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).done(function (data) {
                if (data.error) {
                    alert(String(data.error).replace(/\\n/g, '\n'));
                    return;
                }

                alert('상품을 장바구니에 담았습니다.');
            }).fail(function () {
                alert('장바구니 처리 중 오류가 발생했습니다.');
            });
        });
    });

        // 반응형 쇼핑몰 헤더 숨기기
    syncWithPcBreakpoint(function(isPc) {
        if (isPc) {
            $('#hd').css('display', '');
        } else {
            $('#hd').css('display', 'none');
        }
    });
</script>
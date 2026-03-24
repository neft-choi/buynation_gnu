<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if (!defined("_ORDERINQUIRY_")) exit; // 개별 페이지 접근 불가

// 테마에 orderinquiry.sub.php 있으면 include
if (defined('G5_THEME_SHOP_PATH')) {
    $theme_inquiry_file = G5_THEME_SHOP_PATH . '/orderinquiry.sub.php';
    if (is_file($theme_inquiry_file)) {
        include_once($theme_inquiry_file);
        return;
        unset($theme_inquiry_file);
    }
}
?>

<?php
$allowed_periods = array(
    '1m' => 1,
    '3m' => 3,
    '6m' => 6,
    '1y' => 12
);

$current_period = isset($_GET['period']) ? (string) $_GET['period'] : '3m';
if (!isset($allowed_periods[$current_period])) {
    $current_period = '3m';
}

$period_month = $allowed_periods[$current_period];
$period_from_datetime = date('Y-m-d 00:00:00', strtotime('-' . $period_month . ' months'));
$period_where_sql = " and od_time >= '" . sql_real_escape_string($period_from_datetime) . "' ";

$sql = " select *
           from {$g5['g5_shop_order_table']}
          where mb_id = '{$member['mb_id']}'
            {$period_where_sql}
          order by od_id desc
          $limit ";
$result = sql_query($sql);

$orders = array();
$order_ids = array();
for ($i = 0; $row = sql_fetch_array($result); $i++) {
    $orders[] = $row;
    $order_ids[] = "'" . sql_real_escape_string($row['od_id']) . "'";
}

$first_items = array();
if (!empty($order_ids)) {
    $item_sql = " select od_id, it_id, it_name, ct_qty, ct_price, io_price, io_type
                    from {$g5['g5_shop_cart_table']}
                   where od_id in (" . implode(',', $order_ids) . ")
                   order by od_id asc, ct_id asc ";
    $item_result = sql_query($item_sql);
    for ($j = 0; $item_row = sql_fetch_array($item_result); $j++) {
        if (!isset($first_items[$item_row['od_id']])) {
            $first_items[$item_row['od_id']] = $item_row;
        }
    }
}
?>

<style>
    #wrapper_title {
        display: none;
    }

    .order-card-status span {
        width: 100%;
        padding: 16px 16px;
        background: #F4F4F4;
        color: #262626;
        font-size: 14px;
        font-weight: 600;
    }
</style>

<!-- 주문 내역 시작 -->
<div class="mx-auto w-full max-w-full px-4">
    <div id="order-filter-tabs" class="my-8">
        <div class="grid grid-flow-col auto-cols-max justify-start gap-2">
            <button
                type="button"
                class="js-order-filter-tab rounded px-2 py-1 text-sm <?php echo $current_period === '1m' ? 'bg-zinc-800 font-semibold text-white' : 'bg-zinc-100 font-medium text-zinc-500'; ?>"
                data-period="1m"
                aria-current="<?php echo $current_period === '1m' ? 'true' : 'false'; ?>">
                1개월
            </button>
            <button
                type="button"
                class="js-order-filter-tab rounded px-2 py-1 text-sm <?php echo $current_period === '3m' ? 'bg-zinc-800 font-semibold text-white' : 'bg-zinc-100 font-medium text-zinc-500'; ?>"
                data-period="3m"
                aria-current="<?php echo $current_period === '3m' ? 'true' : 'false'; ?>">
                3개월
            </button>
            <button
                type="button"
                class="js-order-filter-tab rounded px-2 py-1 text-sm <?php echo $current_period === '6m' ? 'bg-zinc-800 font-semibold text-white' : 'bg-zinc-100 font-medium text-zinc-500'; ?>"
                data-period="6m"
                aria-current="<?php echo $current_period === '6m' ? 'true' : 'false'; ?>">
                6개월
            </button>
            <button
                type="button"
                class="js-order-filter-tab rounded px-2 py-1 text-sm <?php echo $current_period === '1y' ? 'bg-zinc-800 font-semibold text-white' : 'bg-zinc-100 font-medium text-zinc-500'; ?>"
                data-period="1y"
                aria-current="<?php echo $current_period === '1y' ? 'true' : 'false'; ?>">
                1년
            </button>
            <button
                type="button"
                class="js-order-filter-tab flex items-center gap-2 rounded border border-zinc-300 bg-white px-2 py-1 text-sm font-medium text-zinc-800"
                data-tab-kind="custom"
                aria-current="false">
                <span>직접입력</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down-icon lucide-chevron-down">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>
        </div>
    </div>

    <section id="order-history-list" class="space-y-8">
        <?php
        for ($i = 0; $i < count($orders); $i++) {
            $row = $orders[$i];
            $uid = md5($row['od_id'] . $row['od_time'] . $row['od_ip']);
            $od_date = str_replace('-', '.', substr($row['od_time'], 0, 10));

            switch ($row['od_status']) {
                case '주문':
                    $od_status = '<span class="status_01">입금확인중</span>';
                    break;
                case '입금':
                    $od_status = '<span class="status_02">입금완료</span>';
                    break;
                case '준비':
                    $od_status = '<span class="status_03">상품준비중</span>';
                    break;
                case '배송':
                    $od_status = '<span class="status_04">상품배송</span>';
                    break;
                case '완료':
                    $od_status = '<span class="status_05">배송완료</span>';
                    break;
                default:
                    $od_status = '<span class="status_06">주문취소</span>';
                    break;
            }

            $thumb_html = '';
            $item_name = '';
            $item_qty = 0;
            $item_price = 0;
            if (isset($first_items[$row['od_id']])) {
                $item = $first_items[$row['od_id']];
                if (!empty($item['it_id'])) {
                    $thumb_html = get_it_image($item['it_id'], 80, 80);
                    $item_name = get_text($item['it_name']);
                    $item_qty = (int) $item['ct_qty'];
                    if ((string) $item['io_type'] === '1') {
                        $item_price = (int) $item['io_price'] * $item_qty;
                    } else {
                        $item_price = ((int) $item['ct_price'] + (int) $item['io_price']) * $item_qty;
                    }
                }
            }

            $action_buttons = array();
            switch ($row['od_status']) {
                case '주문':
                case '입금':
                case '준비':
                    $action_buttons[] = array(
                        'label' => '주문취소',
                        'href' => G5_SHOP_URL . '/orderinquiryview.php?od_id=' . $row['od_id'] . '&uid=' . $uid . '#sod_fin_cancel',
                        'disabled' => false
                    );
                    $action_buttons[] = array(
                        'label' => '문의하기',
                        'href' => G5_BBS_URL . '/qalist.php',
                        'disabled' => false
                    );
                    break;
                case '배송':
                    $action_buttons[] = array(
                        'label' => '배송조회',
                        'href' => G5_SHOP_URL . '/orderinquiryview.php?od_id=' . $row['od_id'] . '&uid=' . $uid . '#sod_fin_dvr',
                        'disabled' => false
                    );
                    $action_buttons[] = array(
                        'label' => '교환신청',
                        'href' => G5_BBS_URL . '/qalist.php',
                        'disabled' => false
                    );
                    $action_buttons[] = array(
                        'label' => '문의하기',
                        'href' => G5_BBS_URL . '/qalist.php',
                        'disabled' => false
                    );
                    break;
                case '완료':
                    $action_buttons[] = array(
                        'label' => '반품신청',
                        'href' => G5_BBS_URL . '/qalist.php',
                        'disabled' => false
                    );
                    $action_buttons[] = array(
                        'label' => '문의하기',
                        'href' => G5_BBS_URL . '/qalist.php',
                        'disabled' => false
                    );
                    break;
                default:
                    $action_buttons[] = array(
                        'label' => '취소완료',
                        'href' => '#',
                        'disabled' => true
                    );
                    $action_buttons[] = array(
                        'label' => '문의하기',
                        'href' => G5_BBS_URL . '/qalist.php',
                        'disabled' => false
                    );
                    break;
            }

            $action_cols = count($action_buttons) === 3 ? 'grid-cols-3' : 'grid-cols-2';
        ?>

            <div class="order-card flex flex-col gap-4">
                <div class="order-card-head flex items-center justify-between gap-2">
                    <p class="font-semibold"><?php echo $od_date; ?></p>
                    <a
                        href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>"
                        class="inline-flex items-center text-sm font-medium text-zinc-700">
                        주문 상세보기
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </a>
                </div>

                <div class="order-card-status"><?php echo $od_status; ?></div>

                <div class="order-card-items grid grid-cols-[80px_1fr_auto] gap-2">
                    <div class="h-20 w-20 overflow-hidden rounded bg-zinc-100">
                        <?php echo $thumb_html ? $thumb_html : '<div class="h-full w-full"></div>'; ?>
                    </div>
                    <div class="flex flex-col justify-start gap-2">
                        <p class="text-zinc-900"><?php echo $item_name; ?></p>
                        <p class="font-semibold text-zinc-900">
                            <?php echo display_price($item_price); ?> <span class="mx-1">|</span> <?php echo number_format($item_qty); ?>개</p>
                    </div>
                    <button
                        type="button"
                        class="flex h-10 w-10 items-center justify-center rounded border border-zinc-300 text-zinc-700"
                        aria-label="장바구니">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart-icon lucide-shopping-cart">
                            <circle cx="8" cy="21" r="1" />
                            <circle cx="19" cy="21" r="1" />
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 1.95 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                        </svg>
                    </button>
                </div>

                <div class="order-card-actions grid <?php echo $action_cols; ?> gap-2">
                    <?php for ($b = 0; $b < count($action_buttons); $b++) { ?>
                        <a
                            href="<?php echo $action_buttons[$b]['disabled'] ? '#' : $action_buttons[$b]['href']; ?>"
                            class="flex h-10 items-center justify-center rounded border border-zinc-300 bg-white px-2 text-sm font-medium text-zinc-800 <?php echo $action_buttons[$b]['disabled'] ? 'pointer-events-none opacity-40' : ''; ?>">
                            <?php echo $action_buttons[$b]['label']; ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <?php if (empty($orders)) { ?>
            <div class="py-10 text-center text-sm text-zinc-500">주문 내역이 없습니다.</div>
        <?php } ?>
    </section>
</div>
<!-- 주문 내역 끝 -->

<script>
    (function() {
        var tabWrap = document.getElementById('order-filter-tabs');
        if (!tabWrap) return;

        var tabs = tabWrap.querySelectorAll('.js-order-filter-tab');
        if (!tabs.length) return;

        function setActiveTab(activeTab) {
            for (var i = 0; i < tabs.length; i++) {
                var tab = tabs[i];
                var isActive = tab === activeTab;
                var isCustomTab = tab.getAttribute('data-tab-kind') === 'custom';

                tab.setAttribute('aria-current', isActive ? 'true' : 'false');
                tab.classList.toggle('bg-zinc-800', isActive);
                tab.classList.toggle('text-white', isActive);
                tab.classList.toggle('font-semibold', isActive);
                tab.classList.toggle('border-zinc-800', isActive);

                tab.classList.toggle('bg-zinc-100', !isActive && !isCustomTab);
                tab.classList.toggle('text-zinc-500', !isActive && !isCustomTab);
                tab.classList.toggle('font-medium', !isActive);
                tab.classList.toggle('border-zinc-300', !isActive && isCustomTab);
                tab.classList.toggle('bg-white', !isActive && isCustomTab);
                tab.classList.toggle('text-zinc-800', !isActive && isCustomTab);
            }
        }

        for (var i = 0; i < tabs.length; i++) {
            tabs[i].addEventListener('click', function() {
                if (this.getAttribute('data-tab-kind') === 'custom') return;
                var period = this.getAttribute('data-period');
                if (!period) return;

                setActiveTab(this);
                var url = new URL(window.location.href);
                url.searchParams.set('period', period);
                window.location.href = url.toString();
            });
        }
    })();
</script>

<?php
if (!defined("_GNUBOARD_"))
    exit; // 개별 페이지 접근 불가

if (!defined("_ORDERINQUIRY_"))
    exit; // 개별 페이지 접근 불가

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

$from_date = isset($_GET['from']) ? trim((string) $_GET['from']) : '';
$to_date = isset($_GET['to']) ? trim((string) $_GET['to']) : '';

$is_custom_range = false;
$period_where_sql = '';

$from_ok = preg_match('/^\d{4}-\d{2}-\d{2}$/', $from_date);
$to_ok = preg_match('/^\d{4}-\d{2}-\d{2}$/', $to_date);

if ($from_ok && $to_ok) {
    $from_ts = strtotime($from_date . ' 00:00:00');
    $to_ts = strtotime($to_date . ' 23:59:59');

    if ($from_ts !== false && $to_ts !== false && $from_ts <= $to_ts) {
        $is_custom_range = true;
        $period_where_sql = " and od_time >= '" . sql_real_escape_string(date('Y-m-d 00:00:00', $from_ts)) . "'";
        $period_where_sql .= " and od_time <= '" . sql_real_escape_string(date('Y-m-d 23:59:59', $to_ts)) . "'";
    }
}

if (!$is_custom_range) {
    $period_month = $allowed_periods[$current_period];
    $period_from_datetime = date('Y-m-d 00:00:00', strtotime('-' . $period_month . ' months'));
    $period_where_sql = " and od_time >= '" . sql_real_escape_string($period_from_datetime) . "' ";
}

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
$first_item_ids = array();
$first_item_options = array();
if (!empty($order_ids)) {
    $item_sql = " select od_id, it_id, it_name, ct_qty, ct_price, io_price, io_type, io_id, ct_option
                    from {$g5['g5_shop_cart_table']}
                   where od_id in (" . implode(',', $order_ids) . ")
                   order by od_id asc, ct_id asc ";
    $item_result = sql_query($item_sql);
    for ($j = 0; $item_row = sql_fetch_array($item_result); $j++) {
        $od_id = $item_row['od_id'];
        $it_id = $item_row['it_id'];

        if (!isset($first_items[$od_id])) {
            $first_items[$od_id] = $item_row;
        }

        if (!isset($first_item_ids[$od_id])) {
            $first_item_ids[$od_id] = $it_id;
        }

        if ($first_item_ids[$od_id] === $it_id) {
            if (!isset($first_item_options[$od_id])) {
                $first_item_options[$od_id] = array();
            }

            $first_item_options[$od_id][] = array(
                'it_id' => $it_id,
                'io_id' => isset($item_row['io_id']) ? $item_row['io_id'] : '',
                'io_type' => isset($item_row['io_type']) ? (string) $item_row['io_type'] : '0',
                'ct_option' => isset($item_row['ct_option']) ? $item_row['ct_option'] : '',
                'ct_qty' => isset($item_row['ct_qty']) ? (int) $item_row['ct_qty'] : 1
            );
        }
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
    <h1 class="text-lg font-semibold text-zinc-900 leading-0">주문내역</h1>
    <div class="w-6 h-6" aria-hidden="true"></div>
</div>

<!-- 주문 내역 시작 -->
<div class="mx-auto w-full max-w-full">

    <!-- PC 너비 타이틀 -->
    <div class="hidden pc:block px-4">
        <h2 class="text-2xl font-bold pb-4 border-b-2 border-gray-900">
            <?php echo (isset($is_mypage) && $is_mypage) ? '주문내역' : $g5['title']; ?>
        </h2>
    </div>

    <div id="order-filter-tabs" class="p-4">
        <div class="flex items-center justify-between pc:bg-[var(--color-semantic-fill-solid-subtle)] pc:px-5 pc:py-2">
            <div class="grid grid-flow-col auto-cols-max items-center justify-start gap-2">
                <span
                    class="hidden pc:inline text-sm text-[var(--color-semantic-label-solid-default)] font-medium">기간조회</span>
                <button type="button"
                    class="js-order-filter-tab rounded px-2 py-1 text-sm <?php echo (!$is_custom_range && $current_period === '1m') ? 'bg-zinc-800 font-semibold text-white' : 'bg-zinc-100 font-medium text-zinc-500'; ?>"
                    data-period="1m"
                    aria-current="<?php echo (!$is_custom_range && $current_period === '1m') ? 'true' : 'false'; ?>">
                    1개월
                </button>
                <button type="button"
                    class="js-order-filter-tab rounded px-2 py-1 text-sm <?php echo (!$is_custom_range && $current_period === '3m') ? 'bg-zinc-800 font-semibold text-white' : 'bg-zinc-100 font-medium text-zinc-500'; ?>"
                    data-period="3m"
                    aria-current="<?php echo (!$is_custom_range && $current_period === '3m') ? 'true' : 'false'; ?>">
                    3개월
                </button>
                <button type="button"
                    class="js-order-filter-tab rounded px-2 py-1 text-sm <?php echo (!$is_custom_range && $current_period === '6m') ? 'bg-zinc-800 font-semibold text-white' : 'bg-zinc-100 font-medium text-zinc-500'; ?>"
                    data-period="6m"
                    aria-current="<?php echo (!$is_custom_range && $current_period === '6m') ? 'true' : 'false'; ?>">
                    6개월
                </button>
                <button type="button"
                    class="js-order-filter-tab rounded px-2 py-1 text-sm <?php echo (!$is_custom_range && $current_period === '1y') ? 'bg-zinc-800 font-semibold text-white' : 'bg-zinc-100 font-medium text-zinc-500'; ?>"
                    data-period="1y"
                    aria-current="<?php echo (!$is_custom_range && $current_period === '1y') ? 'true' : 'false'; ?>">
                    1년
                </button>
                <button type="button"
                    class="js-order-filter-tab flex pc:hidden items-center gap-2 rounded border px-2 py-1 text-sm <?php echo $is_custom_range ? 'border-zinc-800 bg-zinc-800 font-semibold text-white' : 'border-zinc-300 bg-white font-medium text-zinc-800'; ?>"
                    data-tab-kind="custom" aria-current="<?php echo $is_custom_range ? 'true' : 'false'; ?>">
                    <span>직접입력</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-chevron-down-icon lucide-chevron-down">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>
            </div>

            <div id="order-filter-custom-pc-slot" class="hidden pc:block"></div>
        </div>

        <div id="order-filter-custom" class="<?php echo $is_custom_range ? '' : 'hidden '; ?> mt-4 pc:mt-0 flex flex-col pc:flex-row gap-2">
            <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-1">
                <div class="relative pc:bg-white">
                    <input type="date" id="order-filter-from"
                        class="h-10 w-full min-w-0 rounded border border-zinc-300 px-3 text-sm focus-visible:outline-0"
                        value="<?php echo htmlspecialchars($from_date, ENT_QUOTES); ?>">
                    <!-- 커스텀 달력 버튼: 시작일 입력의 네이티브 date picker를 연다 -->
                    <button type="button"
                        class="js-date-picker-trigger absolute right-2 top-1/2 -translate-y-1/2 text-gray-900"
                        data-target="order-filter-from" aria-label="시작일 선택">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-calendar-icon lucide-calendar w-4 h-4">
                            <path d="M8 2v4" />
                            <path d="M16 2v4" />
                            <rect width="18" height="18" x="3" y="4" rx="2" />
                            <path d="M3 10h18" />
                        </svg>
                    </button>
                </div>

                <span class="flex h-10 items-center justify-center text-sm text-zinc-500">~</span>

                <div class="relative pc:bg-white">
                    <input type="date" id="order-filter-to"
                        class="h-10 w-full min-w-0 rounded border border-zinc-300 px-3 text-sm focus-visible:outline-0"
                        value="<?php echo htmlspecialchars($to_date, ENT_QUOTES); ?>">
                    <!-- 커스텀 달력 버튼: 종료일 입력의 네이티브 date picker를 연다 -->
                    <button type="button"
                        class="js-date-picker-trigger absolute right-2 top-1/2 -translate-y-1/2 text-gray-900"
                        data-target="order-filter-to" aria-label="종료일 선택">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-calendar-icon lucide-calendar w-4 h-4">
                            <path d="M8 2v4" />
                            <path d="M16 2v4" />
                            <rect width="18" height="18" x="3" y="4" rx="2" />
                            <path d="M3 10h18" />
                        </svg>
                    </button>
                </div>
            </div>
            <button type="button" data-variant="secondary" id="order-filter-apply" class="h-10 px-2.5 py-1.5 pc:!text-white pc:!bg-[var(--color-semantic-fill-solid-strongest)]">
                적용
            </button>
        </div>
    </div>

    <section id="order-history-list" class="space-y-8 p-4">
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
            $item_id = '';
            $item_name = '';
            $item_url = '#';
            $item_qty = 0;
            $item_price = 0;
            $item_reorder_rows = isset($first_item_options[$row['od_id']]) ? $first_item_options[$row['od_id']] : array();
            $item_reorder_json = htmlspecialchars(json_encode($item_reorder_rows), ENT_QUOTES);
            if (isset($first_items[$row['od_id']])) {
                $item = $first_items[$row['od_id']];
                if (!empty($item['it_id'])) {
                    $item_id = $item['it_id'];
                    $item_url = shop_item_url($item['it_id']);
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
                        'label' => '리뷰쓰기',
                        'href' => G5_SHOP_URL . '/itemuseform.php?it_id=' . $item_id,
                        'disabled' => false
                    );
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
                    <a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>"
                        class="inline-flex items-center text-sm font-medium text-zinc-700">
                        주문 상세보기
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-chevron-right-icon lucide-chevron-right">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </a>
                </div>

                <div class="order-card-status p-4 text-sm font-semibold text-gray-900 bg-zinc-100"><?php echo $od_status; ?>
                </div>

                <div class="order-card-items grid grid-cols-[80px_1fr_auto] gap-2">
                    <div class="h-20 w-20 overflow-hidden rounded bg-zinc-100">
                        <?php echo $thumb_html ? $thumb_html : '<div class="h-full w-full"></div>'; ?>
                    </div>
                    <div class="flex flex-col justify-start gap-2">
                        <a href="<?php echo $item_url; ?>" class="text-zinc-900"><?php echo $item_name; ?></a>
                        <p class="font-semibold text-zinc-900">
                            <?php echo display_price($item_price); ?> <span class="mx-1">|</span>
                            <?php echo number_format($item_qty); ?>개
                        </p>
                    </div>
                    <button type="button"
                        class="js-order-add-cart flex h-10 w-10 items-center justify-center rounded border border-zinc-300 text-zinc-700"
                        data-it-id="<?php echo $item_id; ?>" data-reorder-options="<?php echo $item_reorder_json; ?>"
                        aria-label="장바구니">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-shopping-cart-icon lucide-shopping-cart">
                            <circle cx="8" cy="21" r="1" />
                            <circle cx="19" cy="21" r="1" />
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 1.95 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                        </svg>
                    </button>
                </div>

                <div class="order-card-actions grid <?php echo $action_cols; ?> gap-2">
                    <?php for ($b = 0; $b < count($action_buttons); $b++) { ?>
                        <a href="<?php echo $action_buttons[$b]['disabled'] ? '#' : $action_buttons[$b]['href']; ?>"
                            class="flex h-10 items-center justify-center rounded border border-zinc-300 bg-white px-2 text-sm font-medium text-zinc-800 <?php echo $action_buttons[$b]['disabled'] ? 'pointer-events-none opacity-40' : ''; ?>">
                            <?php echo $action_buttons[$b]['label']; ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <?php
        if (empty($orders)) { ?>
            <div
                class="flex flex-col items-center justify-center gap-3 text-gray-400 min-h-[calc(100dvh-var(--mobile-header-height)-var(--bottom-nav-height))] pc:min-h-0 pc:h-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-file-text-icon lucide-file-text w-12 h-12">
                    <path
                        d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z" />
                    <path d="M14 2v5a1 1 0 0 0 1 1h5" />
                    <path d="M10 9H8" />
                    <path d="M16 13H8" />
                    <path d="M16 17H8" />
                </svg>
                <span>주문 내역이 없습니다</span>
                <a href="<?php echo G5_SHOP_URL; ?>/"
                    class="text-sm font-semibold px-4 py-2 mt-3 bg-gray-800 text-white rounded">쇼핑하러 가기</a>
            </div>
        <?php } ?>
    </section>
</div>
<!-- 주문 내역 끝 -->

<script>
    (function () {
        const tabWrap = document.getElementById('order-filter-tabs');
        if (tabWrap) {
            const tabs = tabWrap.querySelectorAll('.js-order-filter-tab');
            if (tabs.length) {
                const customPanel = document.getElementById('order-filter-custom');
                const fromInput = document.getElementById('order-filter-from');
                const toInput = document.getElementById('order-filter-to');
                const applyButton = document.getElementById('order-filter-apply');
                const datePickerTriggers = tabWrap.querySelectorAll('.js-date-picker-trigger');

                // 커스텀 달력 아이콘 클릭 시 해당 input의 네이티브 picker를 연다.
                for (let i = 0; i < datePickerTriggers.length; i++) {
                    datePickerTriggers[i].addEventListener('click', function () {
                        const targetId = this.getAttribute('data-target');
                        if (!targetId) return;

                        const targetInput = document.getElementById(targetId);
                        if (!targetInput) return;

                        if (typeof targetInput.showPicker === 'function') {
                            targetInput.showPicker();
                            return;
                        }

                        targetInput.focus();
                    });
                }

                function setActiveTab(activeTab) {
                    for (let i = 0; i < tabs.length; i++) {
                        const tab = tabs[i];
                        const isActive = tab === activeTab;
                        const isCustomTab = tab.getAttribute('data-tab-kind') === 'custom';

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

                function showCustomPanel(show) {
                    if (!customPanel) return;
                    customPanel.classList.toggle('hidden', !show);
                }

                for (let i = 0; i < tabs.length; i++) {
                    tabs[i].addEventListener('click', function () {
                        const isCustomTab = this.getAttribute('data-tab-kind') === 'custom';
                        if (isCustomTab) {
                            setActiveTab(this);
                            showCustomPanel(true);
                            return;
                        }

                        const period = this.getAttribute('data-period');
                        if (!period) return;

                        setActiveTab(this);
                        showCustomPanel(false);
                        const url = new URL(window.location.href);
                        url.searchParams.set('period', period);
                        url.searchParams.delete('from');
                        url.searchParams.delete('to');
                        window.location.href = url.toString();
                    });
                }

                if (applyButton) {
                    applyButton.addEventListener('click', function () {
                        const fromValue = fromInput ? fromInput.value : '';
                        const toValue = toInput ? toInput.value : '';

                        if (!fromValue || !toValue) {
                            alert('시작일과 종료일을 모두 선택해 주세요.');
                            return;
                        }

                        if (new Date(fromValue) > new Date(toValue)) {
                            alert('시작일은 종료일보다 늦을 수 없습니다.');
                            return;
                        }

                        const url = new URL(window.location.href);
                        url.searchParams.set('from', fromValue);
                        url.searchParams.set('to', toValue);
                        url.searchParams.delete('period');
                        window.location.href = url.toString();
                    });
                }

            }
        }

        const cartButtons = document.querySelectorAll('.js-order-add-cart');
        if (!cartButtons.length) return;

        async function addOrderItemToCart(button) {
            const itemId = button.getAttribute('data-it-id');
            if (!itemId) {
                alert('상품코드가 올바르지 않습니다.');
                return;
            }

            let reorderRows = [];
            try {
                reorderRows = JSON.parse(button.getAttribute('data-reorder-options') || '[]');
            } catch (e) {
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

            const response = await fetch('<?php echo G5_SHOP_URL; ?>/ajax.action.php', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                body: formData.toString()
            });

            const data = await response.json();
            if (data.error) {
                alert(String(data.error).replace(/\\n/g, '\n'));
                return;
            }

            alert('상품을 장바구니에 담았습니다.');
        }

        for (let i = 0; i < cartButtons.length; i++) {
            cartButtons[i].addEventListener('click', function () {
                addOrderItemToCart(this).catch(function () {
                    alert('장바구니 처리 중 오류가 발생했습니다.');
                });
            });
        }
    })();

    // 기간 필터 이동
    syncWithPcBreakpoint(function (isPc) {
        const $customPanel = $('#order-filter-custom');

        if (!$customPanel.length) {
            return;
        }

        if (isPc) {
            $customPanel
                .appendTo('#order-filter-custom-pc-slot')
                .removeClass('hidden');
            return;
        }

        $customPanel.appendTo('#order-filter-custom-mobile-slot');
    });

    // 반응형 쇼핑몰 헤더 숨기기
    syncWithPcBreakpoint(function (isPc) {
        if (isPc) {
            $('#hd').css('display', '');
        } else {
            $('#hd').css('display', 'none');
        }
    });
</script>
<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH . '/coupon.php');
    return;
}

// 테마에 coupon.php 있으면 include
if (defined('G5_THEME_SHOP_PATH')) {
    $theme_coupon_file = G5_THEME_SHOP_PATH . '/coupon.php';
    if (is_file($theme_coupon_file)) {
        include_once($theme_coupon_file);
        return;
        unset($theme_coupon_file);
    }
}

if ($is_guest)
    alert_close('회원만 조회하실 수 있습니다.');

$g5['title'] = $member['mb_nick'] . ' 님의 쿠폰 내역';
include_once(G5_PATH . '/head.sub.php');

$sql = " select cp_id, cp_subject, cp_method, cp_target, cp_start, cp_end, cp_type, cp_price
            from {$g5['g5_shop_coupon_table']}
            where mb_id IN ( '{$member['mb_id']}', '전체회원' )
              and cp_start <= '" . G5_TIME_YMD . "'
              and cp_end >= '" . G5_TIME_YMD . "'
            order by cp_no ";
$result = sql_query($sql);

$coupon_list = array();
for ($i = 0; $row = sql_fetch_array($result); $i++) {
    if (is_used_coupon($member['mb_id'], $row['cp_id']))
        continue;

    if ($row['cp_method'] == 1) {
        $sql = " select ca_name from {$g5['g5_shop_category_table']} where ca_id = '{$row['cp_target']}' ";
        $ca = sql_fetch($sql);
        $cp_target = $ca['ca_name'] . '의 상품할인';
    } else if ($row['cp_method'] == 2) {
        $cp_target = '결제금액 할인';
    } else if ($row['cp_method'] == 3) {
        $cp_target = '배송비 할인';
    } else {
        $it = get_shop_item($row['cp_target'], true);
        $cp_target = $it['it_name'] . ' 상품할인';
    }

    if ($row['cp_type'])
        $cp_price = $row['cp_price'] . '%';
    else
        $cp_price = number_format($row['cp_price']) . '원';

    $is_unlimited = false;
    if (!$row['cp_end'] || $row['cp_end'] === '9999-12-31' || $row['cp_end'] === '9999-12-31 00:00:00')
        $is_unlimited = true;

    $expire_label = '기간제한 없음';
    if (!$is_unlimited) {
        $end_time = strtotime(substr($row['cp_end'], 0, 10));
        if ($end_time)
            $expire_label = date('Y.m.d', $end_time) . '까지';
    }

    $dday_label = '';
    if (!$is_unlimited) {
        $today_time = strtotime(G5_TIME_YMD);
        $end_time = strtotime(substr($row['cp_end'], 0, 10));
        if ($today_time && $end_time) {
            $diff_days = (int)(($end_time - $today_time) / 86400);
            if ($diff_days >= 0)
                $dday_label = 'D-' . $diff_days;
        }
    }

    $coupon_list[] = array(
        'cp_subject' => $row['cp_subject'],
        'cp_price' => $cp_price,
        'cp_target' => $cp_target,
        'expire_label' => $expire_label,
        'dday_label' => $dday_label
    );
}

$cp_count = count($coupon_list);
?>

<!-- 쿠폰 시작 { -->
<div id="coupon" class="mx-auto w-full max-w-full p-4">
    <div class="flex items-center justify-between">
        <button type="button" class="inline-flex items-center justify-center text-zinc-700" aria-label="뒤로가기" onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '<?php echo G5_URL ?>'; }">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left">
                <path d="m15 18-6-6 6-6" />
            </svg>
        </button>
        <h1 id="win_title_custom" class="text-lg font-semibold text-zinc-900">쿠폰</h1>
        <div class="h-8 w-8" aria-hidden="true"></div>
    </div>

    <div class="mt-6 flex items-center">
        <p class="text-base font-medium text-zinc-900">전체 <span class="text-orange-500"><?php echo number_format($cp_count); ?>개</span></p>
    </div>

    <ul class="mt-4 space-y-3">
        <?php
        for ($i = 0; $i < count($coupon_list); $i++) {
            $row = $coupon_list[$i];
        ?>
            <li class="!p-0 !border-none">
                <div class="grid grid-cols-[1fr_68px]">
                    <div class="px-6 py-5 border border-yellow-400 bg-white rounded-r-xl">
                        <p class="text-lg font-bold text-zinc-900"><?php echo $row['cp_subject']; ?></p>
                        <p class="mt-1 text-base text-zinc-800"><?php echo $row['cp_price']; ?> 쿠폰</p>
                        <p class="mt-1 text-sm text-zinc-500"><?php echo $row['cp_target']; ?></p>

                        <div class="mt-5 flex items-center justify-between gap-2">
                            <ul class="flex items-center gap-2">
                                <?php if ($row['dday_label']) { ?>
                                    <li class="rounded-lg bg-orange-500 !px-2 !py-1 text-xs font-semibold text-white whitespace-nowrap"><?php echo $row['dday_label']; ?></li>
                                <?php } ?>
                            </ul>
                            <p class="text-sm text-zinc-500"><?php echo $row['expire_label']; ?></p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center border-l-4 border-dashed border-yellow-500 rounded-l-xl bg-yellow-400 text-base font-bold text-zinc-900">
                        1장
                    </div>
                </div>
            </li>
        <?php
        }

        if (!$cp_count)
            echo '<li class="rounded-2xl bg-white px-4 py-6 text-center text-xs text-zinc-500">사용할 수 있는 쿠폰이 없습니다.</li>';
        ?>
    </ul>
</div>

<?php
include_once(G5_PATH . '/tail.sub.php');

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
include_once(G5_SHOP_PATH . '/_head.php');

$sql = " select cp_id, cp_subject, cp_method, cp_target, cp_start, cp_end, cp_type, cp_price, cp_minimum
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
        'cp_minimum' => number_format($row['cp_minimum']) . '원 이상',
        'expire_label' => $expire_label,
        'dday_label' => $dday_label
    );
}

$cp_count = count($coupon_list);
?>

<!-- 모바일 헤더 -->
<div class="flex pc:hidden items-center justify-between p-4">
    <button type="button" class="inline-flex items-center justify-center text-zinc-700" aria-label="뒤로가기" onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '<?php echo G5_URL ?>'; }">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left w-6 h-6">
            <path d="m15 18-6-6 6-6" />
        </svg>
    </button>
    <h1 class="text-lg font-semibold text-zinc-900 leading-0">쿠폰</h1>
    <div class="w-6 h-6" aria-hidden="true"></div>
</div>

<!-- 회원 요약 정보 (PC) -->
<?php include_once(G5_THEME_SHOP_PATH . '/_mypage_summary_pc.php'); ?>

<div class="block pc:flex gap-6 pc:px-5 pc:py-12">
    <!-- 마이페이지 메뉴 (PC) -->
    <?php
    include_once(G5_THEME_SHOP_PATH . '/_mypage_menu_pc.php');
    ?>

    <!-- 쿠폰 시작 { -->
    <div id="coupon" class="mx-auto w-full max-w-full px-4 py-4 pc:py-0">

        <!-- PC 너비 타이틀 -->
        <div class="hidden pc:flex items-center pb-4 border-b-2 border-gray-900">
            <h2 class="text-2xl font-bold">쿠폰</h2>
        </div>

        <div class="pc:mt-6 flex items-center">
            <p class="text-base font-medium text-zinc-900">전체 <span class="text-orange-500"><?php echo number_format($cp_count); ?>개</span></p>
        </div>

        <ul class="grid grid-cols-1 pc:grid-cols-2 pc:gap-5 mt-4 space-y-3">
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
                            <p class="mt-1 text-sm text-zinc-500">최소사용금액 <?php echo $row['cp_minimum']; ?></p>

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
            <?php } ?>

            <?php
            // 쿠폰이 없을 때
            if (!$cp_count) { ?>
                <li class="col-span-2">
                    <div class="flex flex-col items-center justify-center gap-3 text-gray-400 min-h-[calc(100dvh-var(--mobile-header-height)-var(--bottom-nav-height))] pc:min-h-0 pc:h-100">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 18H18.02M30 18L18 30M30 30H30.02M4 18C5.5913 18 7.11742 18.6321 8.24264 19.7574C9.36786 20.8826 10 22.4087 10 24C10 25.5913 9.36786 27.1174 8.24264 28.2426C7.11742 29.3679 5.5913 30 4 30V34C4 35.0609 4.42143 36.0783 5.17157 36.8284C5.92172 37.5786 6.93913 38 8 38H40C41.0609 38 42.0783 37.5786 42.8284 36.8284C43.5786 36.0783 44 35.0609 44 34V30C42.4087 30 40.8826 29.3679 39.7574 28.2426C38.6321 27.1174 38 25.5913 38 24C38 22.4087 38.6321 20.8826 39.7574 19.7574C40.8826 18.6321 42.4087 18 44 18V14C44 12.9391 43.5786 11.9217 42.8284 11.1716C42.0783 10.4214 41.0609 10 40 10H8C6.93913 10 5.92172 10.4214 5.17157 11.1716C4.42143 11.9217 4 12.9391 4 14V18Z" stroke="#393939" stroke-opacity="0.35" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span>사용할 수 있는 쿠폰이 없습니다</span>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>

<script>
    // 반응형 쇼핑몰 헤더 숨기기
    syncWithPcBreakpoint(function(isPc) {
        if (isPc) {
            $('#hd').css('display', '');
        } else {
            $('#hd').css('display', 'none');
        }
    });
</script>

<?php
include_once(G5_SHOP_PATH . '/_tail.php');

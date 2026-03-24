<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$sum_point1 = 0;
$sum_point2 = 0;
?>

<section class="mx-auto w-full max-w-full px-4 py-4">
    <div class="mb-4 flex items-center justify-between">
        <button type="button" onclick="history.back();" class="inline-flex h-8 w-8 items-center justify-center text-zinc-700" aria-label="뒤로가기">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="m15 18-6-6 6-6"></path>
            </svg>
        </button>
        <div class="text-lg font-semibold text-zinc-900">포인트</div>
        <div class="h-8 w-8" aria-hidden="true"></div>
    </div>

    <div class="mb-4 rounded border-4 border-[var(--color-primary)] p-4">
        <div class="grid grid-cols-1 gap-2">
            <div class="flex items-center gap-2">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12.0007 21.4343C17.2111 21.4343 21.435 17.2104 21.435 12C21.435 6.78956 17.2111 2.56567 12.0007 2.56567C6.79029 2.56567 2.56641 6.78956 2.56641 12C2.56641 17.2104 6.79029 21.4343 12.0007 21.4343Z" fill="white" />
                    <path d="M12.9436 7.28284H9.16992V12.9434H12.9436C13.6943 12.9434 14.4142 12.6452 14.945 12.1144C15.4757 11.5837 15.7739 10.8638 15.7739 10.1131C15.7739 9.36249 15.4757 8.64259 14.945 8.11181C14.4142 7.58103 13.6943 7.28284 12.9436 7.28284Z" fill="#FDE272" />
                    <path d="M20.8686 12C20.8686 7.1022 16.8978 3.13138 12 3.13138C7.1022 3.13138 3.13138 7.1022 3.13138 12C3.13138 16.8978 7.1022 20.8686 12 20.8686C16.8978 20.8686 20.8686 16.8978 20.8686 12ZM22 12C22 17.5231 17.5231 22 12 22C6.47695 22 2 17.5231 2 12C2 6.47695 6.47695 2 12 2C17.5231 2 22 6.47695 22 12Z" fill="#542C0D" />
                    <path d="M15.208 10.1131C15.208 9.51263 14.9693 8.93651 14.5447 8.51189C14.1201 8.08726 13.5439 7.84854 12.9434 7.84854H9.7354V12.3777H12.9434C13.5439 12.3777 14.1201 12.139 14.5447 11.7144C14.9693 11.2898 15.208 10.7137 15.208 10.1131ZM16.3394 10.1131C16.3394 11.0139 15.9823 11.8781 15.3453 12.515C14.7084 13.152 13.8442 13.5091 12.9434 13.5091H9.7354V16.7172C9.7354 17.0298 9.48233 17.2828 9.16971 17.2828C8.85708 17.2828 8.60402 17.0298 8.60402 16.7172V7.28285C8.60402 6.97022 8.85708 6.71715 9.16971 6.71715H12.9434C13.8442 6.71715 14.7084 7.07432 15.3453 7.71126C15.9823 8.3482 16.3394 9.21237 16.3394 10.1131Z" fill="#542C0D" />
                </svg>

                <span class="text-sm font-bold text-gray-900">보유 포인트</span>
            </div>

            <div class="text-xl font-bold text-red-500"><?php echo number_format($member['mb_point']); ?><span class="text-base text-gray-900 ml-1 font-medium">P</span></div>
        </div>
    </div>

    <div class="grid gap-3">
        <?php
        $i = 0;
        foreach ((array) $list as $row) {
            $point_value = (int) $row['po_point'];
            $is_plus = $point_value > 0;
            $display_point = $is_plus ? '+' . number_format($point_value) : number_format($point_value);

            // 포인트 증감에 따른 수치 색상
            $point_color_class = $is_plus ? 'text-gray-900' : 'text-rose-500';

            if ($is_plus) {
                $sum_point1 += $point_value;
            } else {
                $sum_point2 += $point_value;
            }

            $expire_text = '&nbsp;';
            if ($row['po_expired'] == 1) {
                $expire_text = '만료 ' . substr(str_replace('-', '', $row['po_expire_date']), 2);
            } else if ($row['po_expire_date'] != '9999-12-31') {
                $expire_text = $row['po_expire_date'];
            }
        ?>
            <div class="rounded border border-zinc-200 bg-white p-4">
                <div class="grid grid-cols-[1fr_auto] items-start gap-2">
                    <div class="text-sm font-bold text-zinc-900"><?php echo $row['po_content']; ?></div>
                    <div class="text-base font-semibold <?php echo $point_color_class; ?>"><?php echo $display_point; ?></div>
                </div>
                
                <div class="grid grid-cols-[1fr_auto] gap-2 text-xs text-gray-900">
                    <div class="inline-flex items-center gap-1">
                        <span><?php echo $row['po_datetime']; ?></span>
                    </div>
                    <div class="<?php echo ($row['po_expired'] == 1) ? 'text-rose-500' : 'text-zinc-500'; ?>"><?php echo $expire_text; ?></div>
                </div>
            </div>
        <?php
            $i++;
        }

        if ($i == 0) {
            echo '<div class="rounded-xl border border-dashed border-zinc-300 bg-white p-6 text-center text-sm text-zinc-500">자료가 없습니다.</div>';
        }
        ?>
    </div>

    <?php
    $sum_plus_text = $sum_point1 > 0 ? '+' . number_format($sum_point1) : '0';
    $sum_minus_text = number_format($sum_point2);
    ?>
    <div class="mt-4 rounded-xl border border-zinc-200 bg-zinc-900 p-4 text-white !hidden">
        <div class="mb-2 text-sm font-semibold">소계</div>
        <div class="grid grid-cols-2 gap-2 text-sm">
            <div class="rounded-lg bg-zinc-800 p-3">
                <div class="mb-1 text-zinc-300">적립</div>
                <div class="font-semibold text-blue-300"><?php echo $sum_plus_text; ?></div>
            </div>
            <div class="rounded-lg bg-zinc-800 p-3">
                <div class="mb-1 text-zinc-300">사용</div>
                <div class="font-semibold text-rose-300"><?php echo $sum_minus_text; ?></div>
            </div>
        </div>
    </div>

    <div class="mt-5 text-center">
        <?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page='); ?>
    </div>
</section>
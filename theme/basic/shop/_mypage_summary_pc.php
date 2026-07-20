<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// 유저 이름과 유저 포인트
$summary_mb_name = isset($member['mb_name']) ? $member['mb_name'] : '';
$summary_mb_point = isset($member['mb_point']) ? (int) $member['mb_point'] : 0;

// 유저 쿠폰 수
$summary_cp_count = 0;

$sql = " select cp_id
            from {$g5['g5_shop_coupon_table']}
            where mb_id IN ( '{$member['mb_id']}', '전체회원' )
              and cp_start <= '" . G5_TIME_YMD . "'
              and cp_end >= '" . G5_TIME_YMD . "' ";
$res = sql_query($sql);

for ($k = 0; $cp = sql_fetch_array($res); $k++) {
    if (!is_used_coupon($member['mb_id'], $cp['cp_id'])) {
        $summary_cp_count++;
    }
}
?>

<section id="mypage-summary-pc" class="hidden pc:block">
    <h2 class="sound_only">회원 정보</h2>

    <div class="max-w-[var(--breakpoint-pc)] mx-auto">
        <div class="flex items-center gap-1 p-4">
            <div class="flex flex-col w-full h-53 bg-[#8D8D8D0D] rounded-tl-lg rounded-bl-lg p-8">
                <div class="flex items-center justify-between">
                    <span class="text-2xl font-bold"><?php echo $summary_mb_name; ?>님</span>
                    <button type="button" class="bg-[var(--color-primary)] px-[10px] py-[6px] rounded">
                        <span class="text-xs">가입 쿠폰 받기(임시)</span>
                    </button>
                </div>

                <span class="text-gray-500 text-xs mt-auto">도너츠에서 구매와 기부를 함께해보세요.</span>
            </div>

            <div class="flex flex-col w-full h-53 bg-[#8D8D8D0D] p-8">
                <div class="flex items-center gap-1">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.78516 7.47822L8.17646 6.08691H22.4373V18.2608H8.17646L6.78516 16.5217V7.47822Z" fill="white" />
                        <path d="M1.56641 17.913V6.08691H5.39249L6.7838 7.47822V16.5217L5.39249 17.913H1.56641Z" fill="#FDE272" />
                        <circle cx="12.0007" cy="9.21748" r="1.3913" fill="#FDE272" />
                        <circle cx="16.8718" cy="14.7824" r="1.3913" fill="#FDE272" />
                        <path d="M5.50679 5.41895C5.67393 5.41895 5.8312 5.49902 5.92935 5.6343L6.77514 6.80074L7.62092 5.6343L7.66033 5.58606C7.75841 5.48015 7.89726 5.41895 8.04348 5.41895H22.4783C22.7664 5.41895 23 5.65254 23 5.94068V18.0589C23 18.347 22.7664 18.5806 22.4783 18.5806H8.04348C7.87631 18.5806 7.71907 18.5006 7.62092 18.3653L6.77514 17.1988L5.92935 18.3653C5.83121 18.5006 5.67397 18.5806 5.50679 18.5806H1.52174C1.23359 18.5806 1 18.347 1 18.0589V5.94068C1 5.65254 1.23359 5.41895 1.52174 5.41895H5.50679ZM18.0177 14.5732C18.0176 14.1217 17.6519 13.7553 17.2004 13.7552C16.7489 13.7552 16.3826 14.1216 16.3825 14.5732C16.3825 15.0248 16.7488 15.3911 17.2004 15.3911C17.652 15.391 18.0177 15.0248 18.0177 14.5732ZM16.5095 9.37954C16.7133 9.17579 17.0435 9.17579 17.2473 9.37954C17.4509 9.58331 17.451 9.9136 17.2473 10.1173L12.7439 14.6207C12.5402 14.8243 12.2098 14.8243 12.0061 14.6207C11.8024 14.417 11.8025 14.0867 12.0061 13.8829L16.5095 9.37954ZM6.2534 13.2335V10.7661C6.2534 10.4779 6.48699 10.2444 6.77514 10.2444C7.06328 10.2444 7.29688 10.4779 7.29688 10.7661V13.2335C7.29688 13.5216 7.06328 13.7552 6.77514 13.7552C6.48699 13.7552 6.2534 13.5216 6.2534 13.2335ZM12.8709 9.42642C12.8709 8.97474 12.5047 8.60848 12.053 8.60848C11.6014 8.6086 11.2351 8.97482 11.2351 9.42642C11.2351 9.87799 11.6015 10.2442 12.053 10.2444C12.5047 10.2444 12.8709 9.87807 12.8709 9.42642ZM6.2534 9.42642V7.85848L5.24117 6.46242H2.04348V17.5372H5.24117L6.2534 16.1404V14.5732C6.2535 14.2851 6.48705 14.0514 6.77514 14.0514C7.06322 14.0514 7.29677 14.2851 7.29688 14.5732V16.1404L8.3091 17.5372H21.9565V6.46242H8.3091L7.29688 7.85848V9.42642C7.29685 9.71455 7.06327 9.94815 6.77514 9.94816C6.487 9.94816 6.25342 9.71455 6.2534 9.42642ZM19.0611 14.5732C19.0611 15.6011 18.2283 16.4344 17.2004 16.4346C16.1725 16.4346 15.339 15.6011 15.339 14.5732C15.3391 13.5453 16.1725 12.7117 17.2004 12.7117C18.2282 12.7119 19.061 13.5454 19.0611 14.5732ZM13.9144 9.42642C13.9144 10.4544 13.0809 11.2878 12.053 11.2878C11.0252 11.2877 10.1916 10.4543 10.1916 9.42642C10.1916 8.3985 11.0252 7.56512 12.053 7.56501C13.081 7.56501 13.9144 8.39844 13.9144 9.42642Z" fill="#542C0D" />
                    </svg>
                    <h3>쿠폰</h3>
                </div>

                <div class="mt-2">
                    <p class="text-2xl font-bold <?= $summary_cp_count === 0 ? 'text-zinc-600 ' : 'text-red-600'; ?>">
                        <span><?php echo number_format($summary_cp_count); ?></span>
                        <span class="text-base text-black font-medium"> 장</span>
                    </p>
                </div>

                <div class="mt-auto">
                    <p class="flex items-center justify-between text-gray-700">
                        <span>다운로드 가능한 쿠폰</span>
                        <span class="font-semibold">0장(임시)</span>
                    </p>
                    <p class="flex items-center justify-between text-gray-700">
                        <span>만료예정 쿠폰</span>
                        <span class="font-semibold">0장(임시)</span>
                    </p>
                </div>
            </div>

            <div class="flex flex-col w-full h-53 bg-[#8D8D8D0D] rounded-tr-lg rounded-br-lg p-8">
                <div class="flex items-center gap-1">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.0007 21.4345C17.2111 21.4345 21.435 17.2107 21.435 12.0002C21.435 6.7898 17.2111 2.56592 12.0007 2.56592C6.79029 2.56592 2.56641 6.7898 2.56641 12.0002C2.56641 17.2107 6.79029 21.4345 12.0007 21.4345Z" fill="white" />
                        <path d="M12.9417 7.28271H9.16797V12.9433H12.9417C13.6923 12.9433 14.4122 12.6451 14.943 12.1143C15.4738 11.5835 15.772 10.8636 15.772 10.113C15.772 9.36237 15.4738 8.64247 14.943 8.11169C14.4122 7.58091 13.6923 7.28271 12.9417 7.28271Z" fill="#FDE272" />
                        <path d="M20.8686 12C20.8686 7.1022 16.8978 3.13138 12 3.13138C7.1022 3.13138 3.13138 7.1022 3.13138 12C3.13138 16.8978 7.1022 20.8686 12 20.8686C16.8978 20.8686 20.8686 16.8978 20.8686 12ZM22 12C22 17.5231 17.5231 22 12 22C6.47695 22 2 17.5231 2 12C2 6.47695 6.47695 2 12 2C17.5231 2 22 6.47695 22 12Z" fill="#542C0D" />
                        <path d="M15.208 10.1131C15.208 9.51263 14.9693 8.93651 14.5447 8.51189C14.1201 8.08726 13.5439 7.84854 12.9434 7.84854H9.7354V12.3777H12.9434C13.5439 12.3777 14.1201 12.139 14.5447 11.7144C14.9693 11.2898 15.208 10.7137 15.208 10.1131ZM16.3394 10.1131C16.3394 11.0139 15.9823 11.8781 15.3453 12.515C14.7084 13.152 13.8442 13.5091 12.9434 13.5091H9.7354V16.7172C9.7354 17.0298 9.48233 17.2828 9.16971 17.2828C8.85708 17.2828 8.60402 17.0298 8.60402 16.7172V7.28285C8.60402 6.97022 8.85708 6.71715 9.16971 6.71715H12.9434C13.8442 6.71715 14.7084 7.07432 15.3453 7.71126C15.9823 8.3482 16.3394 9.21237 16.3394 10.1131Z" fill="#542C0D" />
                    </svg>
                    <h3>포인트</h3>
                </div>

                <div class="mt-2">
                    <p class="text-2xl font-bold <?= number_format($summary_mb_point) == 0 ? 'text-zinc-600 ' : 'text-red-600'; ?>">
                        <span><?php echo number_format($summary_mb_point); ?></span>
                        <span class="text-base text-black font-medium"> P</span>
                    </p>
                </div>

                <div class="mt-auto">
                    <p class="flex items-center justify-between text-gray-700">
                        <span>적립 예정 포인트</span>
                        <span class="font-semibold">0P(임시)</span>
                    </p>
                    <p class="flex items-center justify-between text-gray-700">
                        <span>소멸 예정 포인트</span>
                        <span class="font-semibold">0P(임시)</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
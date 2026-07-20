<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH . '/tail.php');
    return;
}

if (G5_COMMUNITY_USE === false) {
    include_once(G5_THEME_SHOP_PATH . '/shop.tail.php');
    return;
}
?>

</div>
<div id="aside" class="!hidden">
    <?php echo outlogin('theme/basic'); // 외부 로그인, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정 
    ?>
    <?php echo poll('theme/basic'); // 설문조사, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정 
    ?>
</div>
</div>

</div>
<!-- } 콘텐츠 끝 -->

<!-- 하단 시작 { -->
<div id="ft" class="bg-white px-4 pb-12">
    <div id="ft_wr" class="relative">
        <div class="absolute top-0 right-0 z-10">
            <svg width="74" height="31" viewBox="0 0 74 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_755_5722)">
                    <path
                        d="M73.2301 0C74.7382 2.40687 72.6273 5.11407 65.4067 5.53754C61.9736 5.73631 59.9124 8.57098 58.6873 11.5029C58.4216 13.886 57.862 16.0811 56.9978 18.0904C56.0147 20.3893 54.6406 22.4397 52.8776 24.2437C48.8719 28.4288 43.909 30.5224 37.961 30.5224C34.8844 30.5224 32.0259 29.9606 29.3835 28.8393C26.7304 27.7287 24.4747 26.1839 22.6166 24.2437C20.7521 22.2884 19.2937 19.9377 18.2264 17.1981C17.9671 16.5413 17.7446 15.8651 17.5501 15.178C17.5501 15.178 17.5501 15.1672 17.5436 15.1413C17.4356 14.7675 17.3384 14.3872 17.2476 14.0005C17.2476 13.994 17.2476 13.9875 17.2412 13.9832C16.5346 11.8723 14.3049 7.5123 7.61582 5.5397C-1.39375 2.8714 0.0905596 0 0.0905596 0H73.228H73.2301Z"
                        fill="#FAC740" />
                    <path
                        d="M39.8279 14.8952C39.9251 15.174 40.0569 15.4159 40.2255 15.6169C40.394 15.8157 40.5971 15.9734 40.8369 16.0857C41.0767 16.2002 41.3338 16.2586 41.6126 16.2586C42.1505 16.2586 42.5999 16.0447 42.9629 15.6169C43.3237 15.1977 43.5031 14.6489 43.5031 13.9727C43.5031 13.2878 43.3216 12.7368 42.9629 12.3177C42.5935 11.8964 42.1376 11.689 41.5996 11.689C41.0616 11.689 40.6079 11.8964 40.2363 12.3177C39.8668 12.7541 39.6832 13.3051 39.6832 13.9727C39.6832 14.3054 39.7307 14.6122 39.8279 14.8952Z"
                        fill="black" />
                    <path
                        d="M31.1359 14.8952C31.2331 15.174 31.3649 15.4159 31.5335 15.6169C31.702 15.8157 31.9051 15.9734 32.1449 16.0857C32.3847 16.2002 32.6418 16.2586 32.9205 16.2586C33.4585 16.2586 33.9079 16.0447 34.2709 15.6169C34.6317 15.1977 34.811 14.6489 34.811 13.9727C34.811 13.2878 34.6317 12.7368 34.2709 12.3177C33.9014 11.8964 33.4456 11.689 32.9076 11.689C32.3696 11.689 31.9159 11.8964 31.5443 12.3177C31.1748 12.7541 30.9911 13.3051 30.9911 13.9727C30.9911 14.3054 31.0387 14.6122 31.1359 14.8952Z"
                        fill="black" />
                </g>
                <defs>
                    <clipPath id="clip0_755_5722">
                        <rect width="73.6884" height="30.5202" fill="white" />
                    </clipPath>
                </defs>
            </svg>
        </div>

        <div class="pt-4">
            <h2 class="text-[20px] font-bold">도움이 필요하세요?</h2>
            <p class="mt-1 text-[15px] font-normal">도너츠가 도와드릴게요.</p>
        </div>

        <div id="ft_link" class="ft_cnt flex items-center gap-2 my-6 text-[13px] font-normal">
            <a href="#ft"
                class="rounded-full bg-[#F5F5F5] px-[10px] py-[5px] shadow-[2px_0_6.2px_rgba(0,0,0,0.05)]">문의하기</a>
            <a href="#ft"
                class="rounded-full bg-[#F5F5F5] px-[10px] py-[5px] shadow-[2px_0_6.2px_rgba(0,0,0,0.05)]">제안하기</a>
            <a href="#ft" class="rounded-full bg-[#F5F5F5] px-[10px] py-[5px] shadow-[2px_0_6.2px_rgba(0,0,0,0.05)]">도너츠
                소식</a>
            <a href="#ft" class="rounded-full bg-[#F5F5F5] px-[10px] py-[5px] shadow-[2px_0_6.2px_rgba(0,0,0,0.05)]">자주
                묻는
                이야기</a>
        </div>

        <div id="ft_link_bak" class="!hidden">
            <a href="<?php echo get_pretty_url('content', 'company'); ?>">회사소개</a>
            <a href="<?php echo get_device_change_url(); ?>">모바일버전</a>
        </div>

        <div id="ft_company" class="ft_cnt text-[13px] text-[#757575] font-normal">
            <h2 class="sr-only">사이트 정보</h2>

            <div class="flex items-center gap-3 pb-4">
                <a href="<?php echo get_pretty_url('content', 'provision'); ?>">이용약관</a>
                <a href="<?php echo get_pretty_url('content', 'privacy'); ?>">개인정보처리방침</a>
            </div>

            <div class="ft_info">
                <span><?php echo $default['de_admin_company_name']; ?></span>
                <span>대표 <?php echo $default['de_admin_company_owner']; ?></span>
                <!-- <span><b>주소</b> <?php echo $default['de_admin_company_addr']; ?></span><br> -->
                <span class="block">사업자등록번호 <?php echo $default['de_admin_company_saupja_no']; ?></span>
                <!-- <span><b>전화</b> <?php echo $default['de_admin_company_tel']; ?></span> -->
                <!-- <span><b>팩스</b> <?php echo $default['de_admin_company_fax']; ?></span><br> -->
                <!-- <span><b>운영자</b> <?php echo $admin['mb_name']; ?></span><br> -->
                <!-- <span><b>통신판매업신고번호</b> <?php echo $default['de_admin_tongsin_no']; ?></span> -->
                <!-- <span><b>개인정보 보호책임자</b> <?php echo $default['de_admin_info_name']; ?></span><br> -->
                <!-- <?php if ($default['de_admin_buga_no'])
                    echo '<span><b>부가통신사업신고번호</b> ' . $default['de_admin_buga_no'] . '</span>'; ?> -->

                <div id="ft_copy">2026 &copy;DONUTS All rights reserved</div>
            </div>
        </div>
    </div>
    <!-- <div id="ft_catch"><img src="<?php echo G5_IMG_URL; ?>/ft_logo.png" alt="<?php echo G5_VERSION ?>"></div> -->

    <button type="button" id="top_btn" class="!hidden">
        <i class="fa fa-arrow-up" aria-hidden="true"></i><span class="sound_only">상단으로</span>
    </button>

    <script>
        $(function () {
            $("#top_btn").on("click", function () {
                $("html, body").animate({
                    scrollTop: 0
                }, '500');
                return false;
            });
        });
    </script>
</div>

<?php
if (G5_DEVICE_BUTTON_DISPLAY && !G5_IS_MOBILE) { ?>
<?php
}

if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

<!-- } 하단 끝 -->

<script>
    $(function () {
        // 폰트 리사이즈 쿠키있으면 실행
        font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
    });
</script>

<!-- 커뮤니티 하단 고정 네비게이션 -->
<nav class="fixed bottom-8 left-1/2 z-50 w-full max-w-full -translate-x-1/2 px-6">
    <ul
        class="h-[var(--bottom-nav-height)] grid grid-cols-5 items-center overflow-hidden rounded-full bg-white/60 backdrop-blur shadow-[0_8px_30px_rgba(0,0,0,0.12)]">
        <li>
            <a href="<?php echo G5_URL; ?>" class="h-full text-gray-900">
                <span
                    class="flex flex-col items-center justify-center gap-1 h-[var(--bottom-nav-height)] rounded-full text-center hover:bg-[var(--donuts-yellow)]">
                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0 10.1057V24H25V10.1057L12.474 0L0 10.1057ZM16.3299 13.3383C16.3299 15.4513 14.6137 17.1566 12.5037 17.1566C10.3938 17.1566 8.67756 15.4439 8.67756 13.3383C8.67756 11.2326 10.3938 9.51993 12.5037 9.51993C14.6137 9.51993 16.3299 11.2326 16.3299 13.3383Z"
                            fill="black" />
                    </svg>
                    <span class="text-[10px] leading-none">홈</span>
                </span>
            </a>
        </li>
        <li>
            <button type="button" class="w-full h-full text-gray-900">
                <span
                    class="flex flex-col items-center justify-center gap-1 h-[var(--bottom-nav-height)] rounded-full text-center hover:bg-[var(--donuts-yellow)]">
                    <svg width="26" height="25" viewBox="0 0 26 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M5.8863 21.7266C7.88797 23.1574 10.3412 23.9999 13.0034 23.9999C15.6655 23.9999 18.0918 23.1641 20.0867 21.7534C19.3319 17.7818 16.6158 14.499 12.9831 12.9211C11.4802 12.2725 9.82899 11.9048 8.08342 11.9048C5.44148 11.9048 2.99499 12.7405 1.00006 14.1513C1.59315 17.2603 3.38589 19.9481 5.87956 21.7266H5.8863Z"
                            stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M22.7095 18.6367C23.8573 17.3003 24.6667 15.6354 24.9999 13.798C23.4289 12.6064 21.5034 11.9048 19.4139 11.9048C18.9696 11.9048 18.5358 11.9382 18.1126 12.005"
                            stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M7.47693 8.47502C9.55764 8.47502 11.2444 6.80168 11.2444 4.73751C11.2444 2.67334 9.55764 1 7.47693 1C5.39622 1 3.70947 2.67334 3.70947 4.73751C3.70947 6.80168 5.39622 8.47502 7.47693 8.47502Z"
                            stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M20.0933 8.47502C22.174 8.47502 23.8607 6.80168 23.8607 4.73751C23.8607 2.67334 22.174 1 20.0933 1C18.0126 1 16.3258 2.67334 16.3258 4.73751C16.3258 6.80168 18.0126 8.47502 20.0933 8.47502Z"
                            stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="text-[10px] leading-none">커뮤니티</span>
                </span>
            </button>
        </li>
        <li>
            <a href="<?php echo G5_SHOP_URL; ?>" class="h-full text-gray-900">
                <span
                    class="flex flex-col items-center justify-center gap-1 h-[var(--bottom-nav-height)] rounded-full text-center hover:bg-[var(--donuts-yellow)]">
                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.79275 24.0001L1 9.17212L23 9.22645L20.3783 24.0001H3.79275Z" stroke="black"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M17.8004 6.32803C17.8004 3.38544 15.2545 1 12.1139 1C8.9734 1 6.42749 3.38544 6.42749 6.32803"
                            stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="text-[10px] leading-none">쇼핑</span>
                </span>
            </a>
        </li>
        <li>
            <a href="<?php echo G5_BBS_URL; ?>/group.php" class="h-full text-gray-900">
                <span
                    class="flex flex-col items-center justify-center gap-1 h-[var(--bottom-nav-height)] rounded-full text-center hover:bg-[var(--donuts-yellow)]">
                    <svg width="25" height="23" viewBox="0 0 25 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M2.6709 2.73145C4.87358 0.421964 8.42556 0.423485 10.6191 2.73047V2.73145L11.7676 3.93848C11.9563 4.13683 12.2184 4.24898 12.4922 4.24902C12.766 4.24902 13.028 4.13683 13.2168 3.93848L14.2695 2.83105C14.3057 2.80235 14.3409 2.77212 14.373 2.73828C16.5749 0.422751 20.1262 0.422906 22.3281 2.73828L22.3291 2.74023C24.556 5.07562 24.5574 8.88672 22.3281 11.2314L21.1797 12.4385L12.5 21.5508L3.82031 12.4385H3.81934L2.67188 11.2314H2.6709C0.44285 8.88743 0.442831 5.07545 2.6709 2.73145Z"
                            stroke="black" stroke-width="2" stroke-linejoin="round" />
                    </svg>
                    <span class="text-[10px] leading-none">좋아요</span>
                </span>
            </a>
        </li>
        <?php
        $uri = $_SERVER['REQUEST_URI'];
        $is_shop = strpos($uri, '/shop/') !== false;

        if ($is_member) {
            $mypage_url = $is_shop
                ? G5_SHOP_URL . '/mypage.php'
                : G5_BBS_URL . '/mypage.php';
        } else {
            $mypage_url = G5_BBS_URL . '/login.php?url=' . login_url($uri);
        }
        ?>
        <li>
            <?= $is_shop ?>
            <a href="<?= $mypage_url ?>" class="h-full text-gray-900">
                <span
                    class="flex flex-col items-center justify-center gap-1 h-[var(--bottom-nav-height)] rounded-full text-center hover:bg-[var(--donuts-yellow)]">
                    <svg width="25" height="28" viewBox="0 0 25 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12.3626 10.7064C15.1377 10.7064 17.3895 8.53241 17.3895 5.85319C17.3895 3.17396 15.1377 1 12.3626 1C9.58748 1 7.33569 3.17396 7.33569 5.85319C7.33569 8.53241 9.58748 10.7064 12.3626 10.7064Z"
                            stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M12.3762 26.147C12.3762 26.1488 12.3777 26.1503 12.3796 26.1503C16.7171 26.1494 20.5902 24.4209 23.1299 21.7179C23.96 20.8344 23.9611 19.4722 23.1316 18.5881C20.5957 15.8851 16.7209 14.157 12.3762 14.157C8.03162 14.157 4.16228 15.8804 1.62342 18.5818C0.792769 19.4656 0.792164 20.8288 1.62201 21.7133C4.1602 24.419 8.02883 26.1428 12.3729 26.1437C12.3747 26.1437 12.3762 26.1452 12.3762 26.147Z"
                            stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="text-[10px] leading-none">마이도너츠</span>
                </span>
            </a>
        </li>
    </ul>
</nav>

</div><!-- /#app-shell -->

<?php
include_once(G5_THEME_PATH . "/tail.sub.php");

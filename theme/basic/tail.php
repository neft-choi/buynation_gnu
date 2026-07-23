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
<?php
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
$page = basename($path);

$is_home = basename($_SERVER['SCRIPT_NAME']) === 'newindex.php';
$is_community = $page === 'community.php';
$is_likes = $page === 'likes.php';
$is_mypage = $page === 'mypage.php';
$is_shop = strpos($path, '/shop/') !== false;
$is_shop_active = $is_shop && !$is_mypage;
?>
<nav class="fixed bottom-8 left-1/2 z-50 w-full max-w-full -translate-x-1/2 px-6">
    <ul
        class="h-[var(--bottom-nav-height)] grid grid-cols-5 items-center overflow-hidden rounded-full font-semibold bg-white/60 backdrop-blur shadow-[0_8px_30px_0px_rgba(0,0,0,0.12)] p-1">
        <li>
            <a href="<?php echo G5_URL; ?>" class="h-full text-gray-900">
                <span
                    class="flex flex-col items-center justify-center rounded-full text-center <?= $is_home ? 'bg-[var(--donuts-yellow)] fill-gray-900' : 'fill-transparent' ?> p-1">
                    <svg viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg" class="w-9 h-9">
                        <path
                            d="M8.1748 22.4451V41.1851H41.8248V22.4451L24.9648 8.81506L8.1748 22.4451ZM30.1448 26.8051C30.1448 29.6551 27.8348 31.9551 24.9948 31.9551C22.1548 31.9551 19.8448 29.6451 19.8448 26.8051C19.8448 23.9651 22.1548 21.6551 24.9948 21.6551C27.8348 21.6551 30.1448 23.9651 30.1448 26.8051Z"
                            stroke="currentColor" stroke-width="3" />
                    </svg>
                    <span class="text-[10px] leading-none">홈</span>
                </span>
            </a>
        </li>
        <li>
            <a href="<?php echo G5_URL; ?>/community.php" class="h-full text-gray-900">
                <span
                    class="flex flex-col items-center justify-center rounded-full text-center <?= $is_community ? 'bg-[var(--donuts-yellow)] fill-gray-900' : 'fill-transparent' ?> p-1">
                    <svg viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg" class="w-9 h-9">
                        <path
                            d="M14.4449 38.6904C17.4149 40.8134 21.0549 42.0634 25.005 42.0634C28.955 42.0634 32.555 40.8234 35.5151 38.7301C34.3951 32.8373 30.365 27.9663 24.975 25.625C22.745 24.6627 20.2949 24.1171 17.7049 24.1171C13.7849 24.1171 10.1548 25.3571 7.19482 27.4504C8.07483 32.0635 10.7349 36.0515 14.4349 38.6904H14.4449Z"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M40.3556 34.1056C42.0588 32.1228 43.2597 29.6525 43.7542 26.9261C41.423 25.1581 38.566 24.1171 35.4657 24.1171C34.8064 24.1171 34.1628 24.1666 33.5349 24.2658"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M16.8044 19.0279C19.8917 19.0279 22.3945 16.545 22.3945 13.4823C22.3945 10.4195 19.8917 7.93665 16.8044 7.93665C13.7171 7.93665 11.2144 10.4195 11.2144 13.4823C11.2144 16.545 13.7171 19.0279 16.8044 19.0279Z"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M35.5254 19.0279C38.6126 19.0279 41.1154 16.545 41.1154 13.4823C41.1154 10.4195 38.6126 7.93665 35.5254 7.93665C32.4381 7.93665 29.9353 10.4195 29.9353 13.4823C29.9353 16.545 32.4381 19.0279 35.5254 19.0279Z"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="text-[10px] leading-none">커뮤니티</span>
                </span>
            </a>
        </li>
        <li>
            <a href="<?php echo G5_SHOP_URL; ?>" class="h-full text-gray-900">
                <span
                    class="flex flex-col items-center justify-center rounded-full text-center <?= $is_shop_active ? 'bg-[var(--donuts-yellow)] fill-gray-900' : 'fill-transparent' ?> p-1">
                    <svg viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg" class="w-9 h-9">
                        <path d="M12.8348 42.0458L8.69531 20.0671L41.3047 20.1477L37.4186 42.0458H12.8348Z"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M33.5979 15.8517C33.5979 11.49 29.8242 7.95422 25.1692 7.95422C20.5141 7.95422 16.7405 11.49 16.7405 15.8517"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="text-[10px] leading-none">쇼핑</span>
                </span>
            </a>
        </li>
        <li>
            <a href="<?php echo G5_URL; ?>/likes.php" class="h-full text-gray-900">
                <span
                    class="flex flex-col items-center justify-center rounded-full text-center <?= $is_likes ? 'bg-[var(--donuts-yellow)] fill-gray-900' : 'fill-transparent' ?> p-1">
                    <svg viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg" class="w-9 h-9">
                        <path
                            d="M38.77 13.0188C35.38 9.56877 29.88 9.56877 26.49 13.0188L25 14.5488L23.5 13.0188C20.11 9.56877 14.62 9.56877 11.23 13.0188C7.84 16.4788 7.84 22.0788 11.23 25.5388L12.72 27.0588L25 39.5688L37.27 27.0588L38.77 25.5388C42.16 22.0788 42.16 16.4788 38.77 13.0188Z"
                            stroke="currentColor" stroke-width="3" />
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
                    class="flex flex-col items-center justify-center rounded-full text-center <?= $is_mypage ? 'bg-[var(--donuts-yellow)] fill-gray-900' : 'fill-transparent' ?> p-1">
                    <svg viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg" class="w-9 h-9">
                        <path
                            d="M24.9803 19.6063C28.7796 19.6063 31.8624 16.6301 31.8624 12.962C31.8624 9.29403 28.7796 6.31775 24.9803 6.31775C21.181 6.31775 18.0981 9.29403 18.0981 12.962C18.0981 16.6301 21.181 19.6063 24.9803 19.6063Z"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M24.9995 42.2116C24.9995 42.2141 25.0016 42.2161 25.0041 42.2161C31.2313 42.2148 36.7593 39.6126 40.2163 35.5984C41.0073 34.6798 41.0084 33.3321 40.2182 32.4128C36.7675 28.3984 31.2372 25.7965 24.9995 25.7965C18.7618 25.7965 13.2397 28.3913 9.78356 32.4041C8.99202 33.3231 8.99143 34.6717 9.78214 35.5914C13.2374 39.6105 18.7583 42.2058 24.9949 42.207C24.9975 42.207 24.9995 42.2091 24.9995 42.2116Z"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="text-[10px] leading-none">마이도너츠</span>
                </span>
            </a>
        </li>
    </ul>
</nav>

<script>
    $(fuction() {});
</script>

</div><!-- /#app-shell -->

<?php
include_once(G5_THEME_PATH . "/tail.sub.php");

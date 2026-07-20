<?php
if (!defined("_GNUBOARD_"))
    exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MSHOP_PATH . '/shop.tail.php');
    return;
}

$admin = get_admin("super");

// 사용자 화면 우측과 하단을 담당하는 페이지입니다.
// 우측, 하단 화면을 꾸미려면 이 파일을 수정합니다.
?>
</div> <!-- } .shop-content 끝 -->
</div> <!-- } #container 끝 -->
</main>
<!-- } 전체 콘텐츠 끝 -->

<!-- 하단 시작 { -->
<footer id="ft" class="bg-[#393939]">
    <div id="ft_wr" class="w-full max-w-[var(--breakpoint-pc)] mx-auto px-4 pc:px-5 py-8 pc:py-15">
        <div class="flex flex-col pc:flex-row items-start gap-8 pc:gap-0">
            <div class="w-full space-y-2 text-white">
                <p class="text-base font-medium">고객센터</p>
                <p class="text-[28px] pc:text-4xl font-bold"><?php echo $default['de_admin_company_tel']; ?></p>
                <p class="text-sm text-[#8D8D8D] font-normal">평일 09:00 ~ 18:00</p>
            </div>

            <ul id="ft_link" class="ft_cnt w-full space-y-4 text-sm text-white/60 font-medium">
                <li><a href="<?php echo get_pretty_url('content', 'company'); ?>">회사소개</a></li>
                <li><a href="<?php echo get_pretty_url('content', 'provision'); ?>">이용약관</a></li>
                <li><a href="<?php echo get_pretty_url('content', 'privacy'); ?>">개인정보처리방침</a></li>
                <li><a href="">이용안내</a></li>
            </ul>

            <div class="flex items-center gap-3">
                <a class="flex items-center justify-center w-11 h-11 rounded-full text-white bg-[#8D8D8D]/16">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.61">
                            <path
                                d="M12.0001 8.66679C10.1983 8.66679 8.66679 10.1983 8.66679 12.0001C8.66679 13.8019 10.1983 15.3335 12.0001 15.3335C13.8019 15.3335 15.3335 13.8019 15.3335 12.0001C15.3335 10.1983 13.8019 8.66679 12.0001 8.66679Z"
                                fill="white" />
                            <path
                                d="M16.2727 2H7.81818C4.54545 2 2 4.54545 2 7.72727V16.1818C2 19.4545 4.54545 22 7.81818 22H16.2727C19.4545 22 22 19.4545 22 16.1818V7.72727C22 4.54545 19.4545 2 16.2727 2ZM12 17.2727C9.09091 17.2727 6.81818 14.9091 6.81818 12.0909C6.81818 9.27273 9.09091 6.81818 12 6.81818C14.9091 6.81818 17.1818 9.18182 17.1818 12C17.1818 14.8182 14.9091 17.2727 12 17.2727ZM17.3636 7.90909C16.7273 7.90909 16.1818 7.36364 16.1818 6.72727C16.1818 6.09091 16.7273 5.54545 17.3636 5.54545C18 5.54545 18.5455 6.09091 18.5455 6.72727C18.5455 7.36364 18 7.90909 17.3636 7.90909Z"
                                fill="white" />
                        </g>
                    </svg>
                </a>

                <a class="flex items-center justify-center w-11 h-11 rounded-full text-white bg-[#8D8D8D]/16">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.61">
                            <path
                                d="M12.0037 4C12.0453 4 18.8852 4.00149 20.6004 4.48068C21.5473 4.74108 22.2926 5.51478 22.545 6.50021C23.003 8.28326 23 12.0022 23 12.0022C23 12.0282 22.9981 15.7262 22.5418 17.5031C22.2895 18.4847 21.5442 19.2581 20.5972 19.5226C18.8816 19.9994 11.9995 20 11.9995 20C11.9579 20 5.11383 19.9979 3.40277 19.5226C2.4557 19.2622 1.71047 18.4888 1.45818 17.5031C1.00184 15.7262 1.00001 12.0282 1 12.0022C1 12.0022 0.999558 8.28408 1.46137 6.49691C1.71365 5.5153 2.45899 4.74189 3.40597 4.47738C5.12157 4.00057 12.0037 4 12.0037 4ZM9.75226 15.3802L15.5051 12.0022L9.75226 8.62423V15.3802Z"
                                fill="white" />
                        </g>
                    </svg>
                </a>

                <a class="flex items-center justify-center w-11 h-11 rounded-full text-white bg-[#8D8D8D]/16">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.61">
                            <path
                                d="M9.69935 7.7396C8.33109 7.7396 7.06311 8.1802 6.02197 8.9306V3.4036H3V20.5696H6.02197V19.7172C7.06311 20.4674 8.33109 20.9082 9.69935 20.9082C13.2397 20.9082 16.1095 17.9604 16.1095 14.324C16.1095 10.6876 13.2395 7.7396 9.69935 7.7396ZM9.41156 18.0324C7.48136 18.0324 5.91663 16.3708 5.91663 14.3212C5.91663 12.2716 7.48136 10.61 9.41156 10.61C11.3418 10.61 12.9065 12.2716 12.9065 14.3212C12.9065 16.3708 11.3418 18.0324 9.41156 18.0324ZM19.7912 2H21V22H19.7912V2Z"
                                fill="white" />
                        </g>
                    </svg>
                </a>
            </div>
        </div>

        <hr class="pc:hidden text-[#8D8D8D] my-8">

        <div id="ft_info">
            <div id="ft_info_pc" class="hidden pc:flex items-start pt-10">
                <div class="w-full space-y-5 text-[#8D8D8D]">
                    <p class="text-[18px] font-medium">Donuts</p>
                    <div id="ft_copy" class="text-[13px] font-normal">Copyright &copy;
                        <?php echo $default['de_admin_company_name']; ?>. All Rights Reserved.
                    </div>
                </div>

                <div id="ft_company_pc" class="ft_cnt w-full">
                    <h2 class="sr-only">사이트 정보</h2>
                    <div class="ft_info text-sm text-[#8D8D8D]/88 font-normal">
                        <span>법인명(상호) : <?php echo $default['de_admin_company_name']; ?></span>
                        <!-- <span>주소 : <?php echo $default['de_admin_company_addr']; ?></span>--><br>
                        <span>대표자 : <?php echo $default['de_admin_company_owner']; ?></span>
                        <!-- <span><b>전화</b> <?php echo $default['de_admin_company_tel']; ?></span> -->
                        <!-- <span><b>팩스</b> <?php echo $default['de_admin_company_fax']; ?></span>--><br>
                        <!-- <span><b>운영자</b> <?php echo $admin['mb_name']; ?></span><br> -->
                        <!-- <span><b>통신판매업신고번호</b> <?php echo $default['de_admin_tongsin_no']; ?></span> -->
                        <!-- <span><b>개인정보 보호책임자</b> <?php echo $default['de_admin_info_name']; ?></span><br> -->
                        <span>사업자 등록번호 : <?php echo $default['de_admin_company_saupja_no']; ?></span>
                        <!-- <?php if ($default['de_admin_buga_no'])
                            echo '<span><b>부가통신사업신고번호</b> ' . $default['de_admin_buga_no'] . '</span>'; ?> -->
                    </div>
                </div>

                <div class="shrink-0 min-w-0 w-[156px] h-full"></div>
            </div>

            <div id="ft_info_mobile" class="flex pc:hidden flex-col">
                <div class="w-full space-y-5 text-[#8D8D8D]">
                    <p class="text-[18px] font-medium">Donuts</p>
                </div>

                <div id="ft_company_mobile" class="ft_cnt w-full mt-5">
                    <h2 class="sr-only">사이트 정보</h2>
                    <div class="ft_info text-sm text-[#8D8D8D]/88 font-normal space-y-2">
                        <div class="flex gap-3">
                            <span class="w-[120px] shrink-0">법인명(상호)</span>
                            <span class="flex-1 min-w-0"><?php echo $default['de_admin_company_name']; ?></span>
                        </div>

                        <div class="hidden flex gap-3">
                            <span class="w-[120px] shrink-0">주소</span>
                            <span class="flex-1 min-w-0"><?php echo $default['de_admin_company_addr']; ?></span>
                        </div>

                        <div class="flex gap-3">
                            <span class="w-[120px] shrink-0">대표자</span>
                            <span class="flex-1 min-w-0"><?php echo $default['de_admin_company_owner']; ?></span>
                        </div>

                        <div class="hidden flex gap-3">
                            <span class="w-[120px] shrink-0">전화</span>
                            <span class="flex-1 min-w-0"><?php echo $default['de_admin_company_tel']; ?></span>
                        </div>

                        <div class="hidden flex gap-3">
                            <span class="w-[120px] shrink-0">팩스</span>
                            <span class="flex-1 min-w-0"><?php echo $default['de_admin_company_fax']; ?></span>
                        </div>

                        <div class="hidden flex gap-3">
                            <span class="w-[120px] shrink-0">운영자</span>
                            <span class="flex-1 min-w-0"><?php echo $admin['mb_name']; ?></span>
                        </div>

                        <div class="hidden flex gap-3">
                            <span class="w-[120px] shrink-0">통신판매업신고번호</span>
                            <span class="flex-1 min-w-0"><?php echo $default['de_admin_tongsin_no']; ?></span>
                        </div>

                        <div class="hidden flex gap-3">
                            <span class="w-[120px] shrink-0">개인정보 보호책임자</span>
                            <span class="flex-1 min-w-0"><?php echo $default['de_admin_info_name']; ?></span>
                        </div>

                        <div class="flex gap-3">
                            <span class="w-[120px] shrink-0">사업자 등록번호</span>
                            <span class="flex-1 min-w-0"><?php echo $default['de_admin_company_saupja_no']; ?></span>
                        </div>

                        <?php if ($default['de_admin_buga_no']) { ?>
                            <div class="hidden flex gap-3">
                                <span class="w-[120px] shrink-0">부가통신사업신고번호</span>
                                <span class="flex-1 min-w-0"><?php echo $default['de_admin_buga_no']; ?></span>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div id="ft_copy_mobile" class="text-center text-[13px] text-[#8D8D8D] font-normal mt-8">Copyright
                    &copy;
                    <?php echo $default['de_admin_company_name']; ?>. All Rights Reserved.
                </div>
            </div>
        </div>
    </div>


</footer>
</div><!-- #app-shell 끝 -->

<?php
$sec = get_microtime() - $begin_time;
$file = $_SERVER['SCRIPT_NAME'];

if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

<script src="<?php echo G5_JS_URL; ?>/sns.js"></script>
<!-- } 하단 끝 -->

<?php
// 하단 네비게이션 고정 노출
$current_shop_page = basename($_SERVER['SCRIPT_NAME']);

// 제외할 페이지 블랙리스트 추가
$bottom_nav_blacklist = array(
    'orderform.php',
    'cart.php',
    'item.php',
);

// 카테고리 레이어를 공통으로 로드
// 기존 shop.head.php와 중복되지 않도록 include_once 사용
include_once(G5_THEME_SHOP_PATH . '/category.php');

$show_bottom_nav = !in_array($current_shop_page, $bottom_nav_blacklist, true);

if ($show_bottom_nav) {
    ?>
    <style>
        /* 본문 하단 네비게이션 만큼 padding */
        body {
            padding-bottom: calc(var(--bottom-nav-icon) + env(safe-area-inset-bottom));
        }
    </style>

    <nav id="shop-bottom-nav-wrap"
        class="fixed bottom-0 left-1/2 z-50 w-full h-[var(--bottom-nav-height)] max-w-full -translate-x-1/2 border-t border-gray-200 bg-white pc:hidden">
        <ul class="h-full grid grid-cols-5 items-center text-xs">
            <li>
                <a href="#" id="bottom_menu_open"
                    class="h-full flex flex-col items-center justify-center gap-1 text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-menu-icon lucide-menu">
                        <path d="M4 5h16" />
                        <path d="M4 12h16" />
                        <path d="M4 19h16" />
                    </svg>
                    <span>카테고리</span>
                </a>
            </li>
            <li>
                <button type="button" id="shop-bottom-search-trigger"
                    class="w-full h-full flex flex-col items-center justify-center gap-1 text-gray-900" aria-label="검색 열기">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-icon lucide-search">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                    <span>검색</span>
                </button>
            </li>
            <li>
                <a href="<?php echo G5_SHOP_URL; ?>"
                    class="flex mx-auto -mt-[calc(var(--bottom-nav-icon)-var(--bottom-nav-height))] h-[var(--bottom-nav-icon)] w-[var(--bottom-nav-icon)] items-center justify-center rounded-full bg-yellow-400 text-4xl font-bold text-black">
                    D
                </a>
            </li>
            <li>
                <a href="<?php echo G5_URL; ?>/index.php"
                    class="h-full flex flex-col items-center justify-center gap-1 text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-message-circle-more-icon lucide-message-circle-more">
                        <path
                            d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719" />
                        <path d="M8 12h.01" />
                        <path d="M12 12h.01" />
                        <path d="M16 12h.01" />
                    </svg>
                    <span>커뮤니티</span>
                </a>
            </li>
            <li>
                <a href="<?php echo $is_member ? G5_SHOP_URL . '/mypage.php' : G5_BBS_URL . '/login.php?url=' . login_url($_SERVER['REQUEST_URI']); ?>"
                    class="h-full flex flex-col items-center justify-center gap-1 text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-user-icon lucide-user">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    <span>마이</span>
                </a>
            </li>
        </ul>
    </nav>
    <script>
        $(function () {
            $("#bottom_menu_open").on("click", function (e) {
                e.preventDefault();

                const $category = $("#category");
                const $categoryBg = $("#category_all_bg");

                if ($category.hasClass("is-open")) {
                    $category.removeClass("is-open");
                    $categoryBg.hide();

                    window.setTimeout(function () {
                        if (!$category.hasClass("is-open")) {
                            $category.hide();
                        }
                    }, 250);
                    return;
                }

                if ($("#menu_open").length) {
                    $("#menu_open").trigger("click");
                    return;
                }

                $category.css("display", "block").addClass("is-open");
                $categoryBg.css("display", "block");
            });

            // 하단 검색 버튼 클릭 시 상단 검색창으로 이동 및 포커스
            $("#shop-bottom-search-trigger").on("click", function () {
                const $input = $("#shop_searchbar_input, #sch_stx_top, #ssch_q, #sch_str").filter(":visible").first();

                // 검색 input 없으면 종료
                if (!$input.length) {
                    return;
                }

                const input = $input.get(0);
                const form = input.closest("form");
                const target = form || input;

                target.scrollIntoView({
                    behavior: "smooth",
                    block: "center"
                });

                // 스크롤 후 짧게 대기한 뒤 포커스/선택 처리
                window.setTimeout(function () {
                    try {
                        // focus로 인한 추가 스크롤 방지
                        input.focus({
                            preventScroll: true
                        });
                    } catch (e) {
                        // focus 옵션 미지원 브라우저 폴백
                        input.focus();
                    }
                    // 즉시 재입력 가능하도록 기존 텍스트 선택
                    input.select();
                }, 220);
            });
        });
    </script>
<?php } ?>

<?php
include_once(G5_THEME_PATH . '/tail.sub.php');

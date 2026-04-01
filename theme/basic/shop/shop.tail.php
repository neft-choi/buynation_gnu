<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

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
<footer id="ft" class="!hidden">
    <div id="ft_wr">
        <ul id="ft_link" class="ft_cnt">
            <li><a href="<?php echo get_pretty_url('content', 'company'); ?>">회사소개</a></li>
            <li><a href="<?php echo get_pretty_url('content', 'provision'); ?>">서비스이용약관</a></li>
            <li><a href="<?php echo get_pretty_url('content', 'privacy'); ?>">개인정보처리방침</a></li>
            <li><a href="<?php echo get_device_change_url(); ?>">모바일버전</a></li>
        </ul>
        <div id="ft_company" class="ft_cnt">
            <h2>사이트 정보</h2>
            <p class="ft_info">
                <span><b>회사명</b> <?php echo $default['de_admin_company_name']; ?></span>
                <span><b>주소</b> <?php echo $default['de_admin_company_addr']; ?></span><br>
                <span><b>사업자 등록번호</b> <?php echo $default['de_admin_company_saupja_no']; ?></span>
                <span><b>대표</b> <?php echo $default['de_admin_company_owner']; ?></span>
                <span><b>전화</b> <?php echo $default['de_admin_company_tel']; ?></span>
                <span><b>팩스</b> <?php echo $default['de_admin_company_fax']; ?></span><br>
                <!-- <span><b>운영자</b> <?php echo $admin['mb_name']; ?></span><br> -->
                <span><b>통신판매업신고번호</b> <?php echo $default['de_admin_tongsin_no']; ?></span>
                <span><b>개인정보 보호책임자</b> <?php echo $default['de_admin_info_name']; ?></span><br>
                <?php if ($default['de_admin_buga_no']) echo '<span><b>부가통신사업신고번호</b> ' . $default['de_admin_buga_no'] . '</span>'; ?>
            </p>
        </div>

        <!-- 커뮤니티 최신글 시작 { -->
        <section id="sidx_lat">
            <?php echo latest('theme/notice', 'notice', 5, 30); ?>
        </section>
        <!-- } 커뮤니티 최신글 끝 -->

        <?php echo visit('theme/shop_basic'); // 접속자 
        ?>
    </div>

    <div id="ft_copy">Copyright &copy; 2001-2013 <?php echo $default['de_admin_company_name']; ?>. All Rights Reserved.</div>
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
        /* 쇼핑 하단 고정 네비 */
        body {
            padding-bottom: 72px;
        }

        #shop-bottom-nav-wrap {
            position: fixed;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: 100%;
            /* max-width: 375px; */
            z-index: 9999;
            padding-bottom: env(safe-area-inset-bottom);
        }
    </style>

    <nav id="shop-bottom-nav-wrap" class="bg-white border-t border-gray-200">
        <ul class="flex items-end justify-between py-2 text-xs">
            <li class="flex-1">
                <a href="#" id="bottom_menu_open" class="flex flex-col items-center gap-1 text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu-icon lucide-menu">
                        <path d="M4 5h16" />
                        <path d="M4 12h16" />
                        <path d="M4 19h16" />
                    </svg>
                    <span>카테고리</span>
                </a>
            </li>
            <li class="flex-1">
                <button type="button" id="shop-bottom-search-trigger" class="flex w-full flex-col items-center gap-1 !p-0 !text-xs !font-normal !text-gray-800" aria-label="검색 열기">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                    <span>검색</span>
                </button>
            </li>
            <li class="w-18 shrink-0">
                <a href="<?php echo G5_SHOP_URL; ?>" class="mx-auto -mt-6 flex h-18 w-18 items-center justify-center rounded-full bg-yellow-400 text-4xl font-bold text-black">
                    B
                </a>
            </li>
            <li class="flex-1">
                <a href="<?php echo G5_URL; ?>/index.php" class="flex flex-col items-center gap-1 text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-more-icon lucide-message-circle-more">
                        <path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719" />
                        <path d="M8 12h.01" />
                        <path d="M12 12h.01" />
                        <path d="M16 12h.01" />
                    </svg>
                    <span>커뮤니티</span>
                </a>
            </li>
            <li class="flex-1">
                <a href="<?php echo $is_member ? G5_SHOP_URL . '/mypage.php' : G5_BBS_URL . '/login.php?url=' . login_url($_SERVER['REQUEST_URI']); ?>" class="flex flex-col items-center gap-1 text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-icon lucide-user">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    <span>마이</span>
                </a>
            </li>
        </ul>
    </nav>
    <script>
        $(function() {
            $("#bottom_menu_open").on("click", function(e) {
                e.preventDefault();

                var $category = $("#category");
                var $categoryBg = $("#category_all_bg");

                if ($category.hasClass("is-open")) {
                    $category.removeClass("is-open");
                    $categoryBg.hide();

                    window.setTimeout(function() {
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
            $("#shop-bottom-search-trigger").on("click", function() {
                var $input = $("#shop_searchbar_input, #sch_stx_top, #ssch_q, #sch_str").filter(":visible").first();

                // 검색 input 없으면 종료
                if (!$input.length) {
                    return;
                }

                var input = $input.get(0);
                var form = input.closest("form");
                var target = form || input;

                target.scrollIntoView({
                    behavior: "smooth",
                    block: "center"
                });

                // 스크롤 후 짧게 대기한 뒤 포커스/선택 처리
                window.setTimeout(function() {
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

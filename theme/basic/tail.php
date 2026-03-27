<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

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
<div id="ft" class="!hidden">

    <div id="ft_wr">
        <div id="ft_link" class="ft_cnt">
            <a href="<?php echo get_pretty_url('content', 'company'); ?>">회사소개</a>
            <a href="<?php echo get_pretty_url('content', 'privacy'); ?>">개인정보처리방침</a>
            <a href="<?php echo get_pretty_url('content', 'provision'); ?>">서비스이용약관</a>
            <a href="<?php echo get_device_change_url(); ?>">모바일버전</a>
        </div>
        <div id="ft_company" class="ft_cnt">
            <h2>사이트 정보</h2>
            <p class="ft_info">
                회사명 : 회사명 / 대표 : 대표자명<br>
                주소 : OO도 OO시 OO구 OO동 123-45<br>
                사업자 등록번호 : 123-45-67890<br>
                전화 : 02-123-4567 팩스 : 02-123-4568<br>
                통신판매업신고번호 : 제 OO구 - 123호<br>
                개인정보관리책임자 : 정보책임자명<br>
            </p>
        </div>
        <?php
        //공지사항
        // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
        // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
        // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
        echo latest('theme/notice', 'notice', 4, 13);
        ?>

        <?php echo visit('theme/basic'); // 접속자집계, 테마의 스킨을 사용하려면 스킨을 theme/basic 과 같이 지정 
        ?>
    </div>
    <!-- <div id="ft_catch"><img src="<?php echo G5_IMG_URL; ?>/ft_logo.png" alt="<?php echo G5_VERSION ?>"></div> -->
    <div id="ft_copy">Copyright &copy; <b>소유하신 도메인.</b> All rights reserved.</div>


    <button type="button" id="top_btn">
        <i class="fa fa-arrow-up" aria-hidden="true"></i><span class="sound_only">상단으로</span>
    </button>
    <script>
        $(function() {
            $("#top_btn").on("click", function() {
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
    $(function() {
        // 폰트 리사이즈 쿠키있으면 실행
        font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
    });
</script>

<!-- 커뮤니티 하단 고정 네비게이션 -->
<nav class="fixed bottom-0 left-1/2 z-50 w-full h-[var(--bottom-nav-height)] max-w-full -translate-x-1/2 border-t border-gray-200 bg-white">
    <ul class="grid grid-cols-5">
        <li>
            <a href="<?php echo G5_URL; ?>" class="flex flex-col items-center justify-center gap-1 py-2 text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house-icon lucide-house">
                    <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                    <path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                </svg>
                <span class="text-xs">홈</span>
            </a>
        </li>
        <li>
            <button type="button" id="bottom-search-trigger" class="flex w-full flex-col items-center justify-center gap-1 py-2 text-gray-900" aria-label="검색 열기">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search">
                    <path d="m21 21-4.34-4.34" />
                    <circle cx="11" cy="11" r="8" />
                </svg>
                <span class="text-xs">검색</span>
            </button>
        </li>
        <li>
            <a href="<?php echo G5_BBS_URL; ?>/group.php" class="flex flex-col items-center justify-center gap-1 py-2 text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart">
                    <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                </svg>
                <span class="text-xs">바이클</span>
            </a>
        </li>
        <li>
            <a href="<?php echo G5_SHOP_URL; ?>" class="flex flex-col items-center justify-center gap-1 py-2 text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-handbag-icon lucide-handbag">
                    <path d="M2.048 18.566A2 2 0 0 0 4 21h16a2 2 0 0 0 1.952-2.434l-2-9A2 2 0 0 0 18 8H6a2 2 0 0 0-1.952 1.566z" />
                    <path d="M8 11V6a4 4 0 0 1 8 0v5" />
                </svg>
                <span class="text-xs">쇼핑</span>
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
            <a href="<?= $mypage_url ?>" class="flex flex-col items-center justify-center gap-1 py-2 text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-icon lucide-user">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <span class="text-xs">마이</span>
            </a>
        </li>
    </ul>
</nav>

<script>
    $(function() {
        // 하단 검색 버튼 클릭 시 상단 검색창으로 이동 및 포커스
        $("#bottom-search-trigger").on("click", function() {
            var $input = $("#sch_stx_top");

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

</div><!-- /#app-shell -->

<?php
include_once(G5_THEME_PATH . "/tail.sub.php");

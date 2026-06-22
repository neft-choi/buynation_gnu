<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$current_menu_path = basename($_SERVER['SCRIPT_NAME']);
$current_menu_url = isset($_GET['url']) ? $_GET['url'] : '';

$link_base_class = 'block text-zinc-700';
$link_active_class = 'font-bold !text-red-500';
?>

<section id="mypage-menu-pc" class="hidden pc:block">
    <h2 class="sound_only">마이페이지 메뉴</h2>

    <div class="w-55 p-6 border border-zinc-200 rounded-lg bg-white space-y-4">
        <section class="pb-4 border-b border-zinc-200">
            <button
                type="button"
                class="mypage-menu-group-toggle flex w-full items-center justify-between text-left"
                aria-expanded="true"
                aria-controls="mypage-menu-group-order">
                <h3 class="font-bold">주문관리</h3>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mypage-menu-group-icon lucide lucide-chevron-down-icon lucide-chevron-down w-4 h-4 transition-transform duration-200">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>

            <div id="mypage-menu-group-order" class="mt-4 space-y-3 text-sm">
                <a href="#" class="block text-zinc-700">
                    <span>주문/배송 조회(임시)</span>
                </a>
                <a href="<?php echo G5_SHOP_URL; ?>/orderinquiry.php" class="<?php echo $link_base_class; ?><?php echo $current_menu_path === 'orderinquiry.php' ? ' ' . $link_active_class : ''; ?>">
                    <span>주문내역</span>
                </a>
                <a href="#" class="block text-zinc-700">
                    <span>취소/교환/반품 내역(임시)</span>
                </a>
                <a href="#" class="block text-zinc-700">
                    <span>자주구매 상품(임시)</span>
                </a>
            </div>
        </section>

        <section class="pb-4 border-b border-zinc-200">
            <button
                type="button"
                class="mypage-menu-group-toggle flex w-full items-center justify-between text-left"
                aria-expanded="true"
                aria-controls="mypage-menu-group-activity">
                <h3 class="font-bold">나의 활동 관리</h3>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mypage-menu-group-icon lucide lucide-chevron-down-icon lucide-chevron-down w-4 h-4 transition-transform duration-200">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>

            <div id="mypage-menu-group-activity" class="mt-4 space-y-3 text-sm">
                <a href="#" class="block text-zinc-700">
                    <span>상품 리뷰(임시)</span>
                </a>
                <a href="<?php echo G5_SHOP_URL; ?>/wishlist.php" class="<?php echo $link_base_class; ?><?php echo $current_menu_path === 'wishlist.php' ? ' ' . $link_active_class : ''; ?>">
                    <span>위시리스트</span>
                </a>
                <a href="#" class="block text-zinc-700">
                    <span>이벤트 참여 내역(임시)</span>
                </a>
                <a href="#" class="block text-zinc-700">
                    <span>입고알림 내역(임시)</span>
                </a>
            </div>
        </section>

        <section class="pb-4 border-b border-zinc-200">
            <button
                type="button"
                class="mypage-menu-group-toggle flex w-full items-center justify-between text-left"
                aria-expanded="true"
                aria-controls="mypage-menu-group-benefit">
                <h3 class="font-bold">나의 혜택 관리</h3>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mypage-menu-group-icon lucide lucide-chevron-down-icon lucide-chevron-down w-4 h-4 transition-transform duration-200">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>

            <div id="mypage-menu-group-benefit" class="mt-4 space-y-3 text-sm">
                <a href="<?php echo G5_SHOP_URL; ?>/coupon.php" class="<?php echo $link_base_class; ?><?php echo $current_menu_path === 'coupon.php' ? ' ' . $link_active_class : ''; ?>">
                    <span>쿠폰</span>
                </a>
                <a href="<?php echo G5_BBS_URL; ?>/point.php" class="<?php echo $link_base_class; ?><?php echo $current_menu_path === 'point.php' ? ' ' . $link_active_class : ''; ?>">
                    <span>포인트</span>
                </a>
                <a href="#" class="block text-zinc-700">
                    <span>기부 포인트(임시)</span>
                </a>
            </div>
        </section>

        <section class="pb-4 border-b border-zinc-200">
            <button
                type="button"
                class="mypage-menu-group-toggle flex w-full items-center justify-between text-left"
                aria-expanded="true"
                aria-controls="mypage-menu-group-profile">
                <h3 class="font-bold">나의 정보 관리</h3>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mypage-menu-group-icon lucide lucide-chevron-down-icon lucide-chevron-down w-4 h-4 transition-transform duration-200">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>

            <div id="mypage-menu-group-profile" class="mt-4 space-y-3 text-sm">
                <a href="<?php echo G5_BBS_URL; ?>/member_confirm.php?url=register_form.php" class="block text-zinc-700">
                    <span>회원정보 설정</span>
                </a>
                <a href="<?php echo G5_SHOP_URL; ?>/orderaddress.php" class="<?php echo $link_base_class; ?><?php echo $current_menu_path === 'orderaddress.php' ? ' ' . $link_active_class : ''; ?>">
                    <span>배송지 관리</span>
                </a>
                <a href="#" target="_blank" class="win_memo block text-zinc-700">
                    <span>알림 목록(임시)</span>
                </a>
                <a href="<?php echo G5_BBS_URL; ?>/member_confirm.php?url=member_leave.php" class="block text-zinc-700" onclick="return member_leave();">
                    <span>회원 탈퇴</span>
                </a>
            </div>
        </section>

        <section class="pb-4 border-b border-zinc-200">
            <button
                type="button"
                class="mypage-menu-group-toggle flex w-full items-center justify-between text-left"
                aria-expanded="true"
                aria-controls="mypage-menu-group-cs">
                <h3 class="font-bold">고객센터</h3>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mypage-menu-group-icon lucide lucide-chevron-down-icon lucide-chevron-down w-4 h-4 transition-transform duration-200">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>

            <div id="mypage-menu-group-cs" class="mt-4 space-y-3 text-sm">
                <a href="<?php echo G5_BBS_URL; ?>/faq.php" class="<?php echo $link_base_class; ?><?php echo $current_menu_path === 'faq.php' ? ' ' . $link_active_class : ''; ?>">
                    <span>FAQ</span>
                </a>
                <a href="<?php echo G5_BBS_URL; ?>/qalist.php" class="<?php echo $link_base_class; ?><?php echo $current_menu_path === 'qalist.php' ? ' ' . $link_active_class : ''; ?>">
                    <span>문의내역(임시)</span>
                </a>
                <a href="<?php echo G5_BBS_URL; ?>/qawrite.php" class="<?php echo $link_base_class; ?><?php echo $current_menu_path === 'qawrite.php' ? ' ' . $link_active_class : ''; ?>">
                    <span>문의하기</span>
                </a>
                <a href="#" class="block text-zinc-700">
                    <span>공지사항(임시)</span>
                </a>
            </div>
        </section>

        <section>
            <a href="<?php echo G5_BBS_URL; ?>/logout.php?url=shop" class="text-sm text-zinc-500">
                <span>로그아웃</span>
            </a>
        </section>
    </div>
</section>
<script>
    $(function() {
        $('.mypage-menu-group-toggle').on('click', function() {
            const $button = $(this);
            const targetId = $button.attr('aria-controls');
            const $panel = $('#' + targetId);
            const isExpanded = $button.attr('aria-expanded') === 'true';

            $button.attr('aria-expanded', isExpanded ? 'false' : 'true');
            $panel.stop(true, true).slideToggle(200);

            $button.find('.mypage-menu-group-icon').toggleClass('rotate-180');
        });
    });

    function member_leave() {
        return confirm('정말 회원에서 탈퇴 하시겠습니까?')
    }
</script>
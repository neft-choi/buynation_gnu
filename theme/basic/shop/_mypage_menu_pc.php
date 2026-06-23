<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$current_menu_path = basename($_SERVER['SCRIPT_NAME']);
$current_menu_url = isset($_GET['url']) ? $_GET['url'] : '';

$link_base_class = 'block text-zinc-700';
$link_active_class = 'font-bold !text-red-500';
?>

<?php
// 마이페이지 메뉴 배열
$mypage_menu_groups = array(
    array(
        'title' => '주문관리',
        'items' => array(
            array(
                'label' => '주문/배송 조회(임시)',
                'href' => '#',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-truck-icon lucide-truck w-5 h-5"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2" /><path d="M15 18H9" /><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14" /><circle cx="17" cy="18" r="2" /><circle cx="7" cy="18" r="2" /></svg>',
            ),
            array(
                'label' => '주문내역',
                'href' => G5_SHOP_URL . '/orderinquiry.php',
                'icon' => '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"><path d="M20 5C20 4.44772 19.5523 4 19 4H5C4.44772 4 4 4.44772 4 5V19C4 19.5523 4.44772 20 5 20H19C19.5523 20 20 19.5523 20 19V5ZM22 19C22 20.6569 20.6569 22 19 22H5C3.34315 22 2 20.6569 2 19V5C2 3.34315 3.34315 2 5 2H19C20.6569 2 22 3.34315 22 5V19Z" fill="#262626" /><path d="M16 7C16.5523 7 17 7.44772 17 8C17 8.55228 16.5523 9 16 9H8C7.44772 9 7 8.55228 7 8C7 7.44772 7.44772 7 8 7H16Z" fill="#262626" /><path d="M16 11C16.5523 11 17 11.4477 17 12C17 12.5523 16.5523 13 16 13H8C7.44772 13 7 12.5523 7 12C7 11.4477 7.44772 11 8 11H16Z" fill="#262626" /><path d="M13 15C13.5523 15 14 15.4477 14 16C14 16.5523 13.5523 17 13 17H8C7.44772 17 7 16.5523 7 16C7 15.4477 7.44772 15 8 15H13Z" fill="#262626" /></svg>',
            ),
            array(
                'label' => '취소/교환/반품 내역(임시)',
                'href' => '#',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-archive-restore-icon lucide-archive-restore w-5 h-5"><rect width="20" height="5" x="2" y="3" rx="1" /><path d="M4 8v11a2 2 0 0 0 2 2h2" /><path d="M20 8v11a2 2 0 0 1-2 2h-2" /><path d="m9 15 3-3 3 3" /><path d="M12 12v9" /></svg>',
            ),
            array(
                'label' => '자주구매 상품(임시)',
                'href' => '#',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag-icon lucide-shopping-bag w-5 h-5"><path d="M16 10a4 4 0 0 1-8 0" /><path d="M3.103 6.034h17.794" /><path d="M3.4 5.467a2 2 0 0 0-.4 1.2V20a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6.667a2 2 0 0 0-.4-1.2l-2-2.667A2 2 0 0 0 17 2H7a2 2 0 0 0-1.6.8z" /></svg>',
            ),
        ),
    ),
    array(
        'title' => '나의 활동 관리',
        'items' => array(
            array(
                'label' => '상품 리뷰(임시)',
                'href' => '#',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square-heart-icon lucide-message-square-heart w-5 h-5"><path d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z" /><path d="M7.5 9.5c0 .687.265 1.383.697 1.844l3.009 3.264a1.14 1.14 0 0 0 .407.314 1 1 0 0 0 .783-.004 1.14 1.14 0 0 0 .398-.31l3.008-3.264A2.77 2.77 0 0 0 16.5 9.5 2.5 2.5 0 0 0 12 8a2.5 2.5 0 0 0-4.5 1.5" /></svg>',
            ),
            array(
                'label' => '위시리스트',
                'href' => G5_SHOP_URL . '/wishlist.php',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart w-5 h-5"><path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" /></svg>',
            ),
            array(
                'label' => '이벤트 참여 내역(임시)',
                'href' => '#',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-party-popper-icon lucide-party-popper w-5 h-5"><path d="M5.8 11.3 2 22l10.7-3.79" /><path d="M4 3h.01" /><path d="M22 8h.01" /><path d="M15 2h.01" /><path d="M22 20h.01" /><path d="m22 2-2.24.75a2.9 2.9 0 0 0-1.96 3.12c.1.86-.57 1.63-1.45 1.63h-.38c-.86 0-1.6.6-1.76 1.44L14 10" /><path d="m22 13-.82-.33c-.86-.34-1.82.2-1.98 1.11c-.11.7-.72 1.22-1.43 1.22H17" /><path d="m11 2 .33.82c.34.86-.2 1.82-1.11 1.98C9.52 4.9 9 5.52 9 6.23V7" /><path d="M11 13c1.93 1.93 2.83 4.17 2 5-.83.83-3.07-.07-5-2-1.93-1.93-2.83-4.17-2-5 .83-.83 3.07.07 5 2Z" /></svg>',
            ),
            array(
                'label' => '입고알림 내역(임시)',
                'href' => '#',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell-plus-icon lucide-bell-plus w-5 h-5"><path d="M10.268 21a2 2 0 0 0 3.464 0" /><path d="M15 8h6" /><path d="M18 5v6" /><path d="M20.002 14.464a9 9 0 0 0 .738.863A1 1 0 0 1 20 17H4a1 1 0 0 1-.74-1.673C4.59 13.956 6 12.499 6 8a6 6 0 0 1 8.75-5.332" /></svg>',
            ),
        ),
    ),
    array(
        'title' => '나의 혜택 관리',
        'items' => array(
            array(
                'label' => '쿠폰',
                'href' => G5_SHOP_URL . '/coupon.php',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket-percent-icon lucide-ticket-percent w-5 h-5"><path d="M2 9a3 3 0 1 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 1 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" /><path d="M9 9h.01" /><path d="m15 9-6 6" /><path d="M15 15h.01" /></svg>',
            ),
            array(
                'label' => '포인트',
                'href' => G5_BBS_URL . '/point.php',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-parking-icon lucide-circle-parking w-5 h-5"><circle cx="12" cy="12" r="10" /><path d="M9 17V7h4a3 3 0 0 1 0 6H9" /></svg>',
            ),
            array(
                'label' => '기부 포인트(임시)',
                'href' => '#',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-handshake-icon lucide-heart-handshake w-5 h-5"><path d="M19.414 14.414C21 12.828 22 11.5 22 9.5a5.5 5.5 0 0 0-9.591-3.676.6.6 0 0 1-.818.001A5.5 5.5 0 0 0 2 9.5c0 2.3 1.5 4 3 5.5l5.535 5.362a2 2 0 0 0 2.879.052 2.12 2.12 0 0 0-.004-3 2.124 2.124 0 1 0 3-3 2.124 2.124 0 0 0 3.004 0 2 2 0 0 0 0-2.828l-1.881-1.882a2.41 2.41 0 0 0-3.409 0l-1.71 1.71a2 2 0 0 1-2.828 0 2 2 0 0 1 0-2.828l2.823-2.762" /></svg>',
            ),
        ),
    ),
    array(
        'title' => '나의 정보 관리',
        'items' => array(
            array(
                'label' => '회원정보 설정',
                'href' => G5_BBS_URL . '/member_confirm.php?url=register_form.php',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-icon lucide-user w-5 h-5"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" /><circle cx="12" cy="7" r="4" /></svg>',
            ),
            array(
                'label' => '배송지 관리',
                'href' => G5_SHOP_URL . '/orderaddress.php',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-icon lucide-map-pin w-5 h-5"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" /><circle cx="12" cy="10" r="3" /></svg>',
            ),
            array(
                'label' => '알림 목록(임시)',
                'href' => '#',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell-dot-icon lucide-bell-dot w-5 h-5"><path d="M10.268 21a2 2 0 0 0 3.464 0" /><path d="M11.68 2.009A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673c-.824-.85-1.678-1.731-2.21-3.348" /><circle cx="18" cy="5" r="3" /></svg>',
            ),
            array(
                'label' => '회원 탈퇴',
                'href' => G5_BBS_URL . '/member_confirm.php?url=member_leave.php',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-x-icon lucide-user-x w-5 h-5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><line x1="17" x2="22" y1="8" y2="13" /><line x1="22" x2="17" y1="8" y2="13" /></svg>',
            ),
        ),
    ),
    array(
        'title' => '고객센터',
        'items' => array(
            array(
                'label' => 'FAQ',
                'href' => G5_BBS_URL . '/faq.php',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square-warning-icon lucide-message-square-warning w-5 h-5"><path d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z" /><path d="M12 15h.01" /><path d="M12 7v4" /></svg>',
            ),
            array(
                'label' => '문의내역',
                'href' => G5_BBS_URL . '/qalist.php',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-check-icon lucide-list-check w-5 h-5"><path d="M16 5H3" /><path d="M16 12H3" /><path d="M11 19H3" /><path d="m15 18 2 2 4-4" /></svg>',
            ),
            array(
                'label' => '문의하기',
                'href' => G5_BBS_URL . '/qawrite.php',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen w-5 h-5"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" /><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z" /></svg>',
            ),
            array(
                'label' => '공지사항(임시)',
                'href' => '#',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-megaphone-icon lucide-megaphone w-5 h-5"><path d="M11 6a13 13 0 0 0 8.4-2.8A1 1 0 0 1 21 4v12a1 1 0 0 1-1.6.8A13 13 0 0 0 11 14H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z" /><path d="M6 14a12 12 0 0 0 2.4 7.2 2 2 0 0 0 3.2-2.4A8 8 0 0 1 10 14" /><path d="M8 6v8" /></svg>',
            ),
        ),
    ),
);
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
                <h3 class="font-bold"><?php echo $mypage_menu_groups[0]['title']; ?></h3>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mypage-menu-group-icon lucide lucide-chevron-down-icon lucide-chevron-down w-4 h-4 transition-transform duration-200">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>

            <div id="mypage-menu-group-order" class="mt-4 space-y-3 text-sm">
                <?php foreach ($mypage_menu_groups[0]['items'] as $menu_item) { ?>
                    <a href="<?php echo $menu_item['href']; ?>" class="<?php echo $link_base_class; ?><?php echo basename($menu_item['href']) === $current_menu_path ? ' ' . $link_active_class : ''; ?>">
                        <span><?php echo $menu_item['label']; ?></span>
                    </a>
                <?php } ?>
            </div>
        </section>

        <section class="pb-4 border-b border-zinc-200">
            <button
                type="button"
                class="mypage-menu-group-toggle flex w-full items-center justify-between text-left"
                aria-expanded="true"
                aria-controls="mypage-menu-group-activity">
                <h3 class="font-bold"><?php echo $mypage_menu_groups[1]['title']; ?></h3>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mypage-menu-group-icon lucide lucide-chevron-down-icon lucide-chevron-down w-4 h-4 transition-transform duration-200">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>

            <div id="mypage-menu-group-activity" class="mt-4 space-y-3 text-sm">
                <?php foreach ($mypage_menu_groups[1]['items'] as $menu_item) { ?>
                    <a href="<?php echo $menu_item['href']; ?>" class="<?php echo $link_base_class; ?><?php echo basename($menu_item['href']) === $current_menu_path ? ' ' . $link_active_class : ''; ?>">
                        <span><?php echo $menu_item['label']; ?></span>
                    </a>
                <?php } ?>
            </div>
        </section>

        <section class="pb-4 border-b border-zinc-200">
            <button
                type="button"
                class="mypage-menu-group-toggle flex w-full items-center justify-between text-left"
                aria-expanded="true"
                aria-controls="mypage-menu-group-benefit">
                <h3 class="font-bold"><?php echo $mypage_menu_groups[2]['title']; ?></h3>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mypage-menu-group-icon lucide lucide-chevron-down-icon lucide-chevron-down w-4 h-4 transition-transform duration-200">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>

            <div id="mypage-menu-group-benefit" class="mt-4 space-y-3 text-sm">
                <?php foreach ($mypage_menu_groups[2]['items'] as $menu_item) { ?>
                    <a href="<?php echo $menu_item['href']; ?>" class="<?php echo $link_base_class; ?><?php echo basename($menu_item['href']) === $current_menu_path ? ' ' . $link_active_class : ''; ?>">
                        <span><?php echo $menu_item['label']; ?></span>
                    </a>
                <?php } ?>
            </div>
        </section>

        <section class="pb-4 border-b border-zinc-200">
            <button
                type="button"
                class="mypage-menu-group-toggle flex w-full items-center justify-between text-left"
                aria-expanded="true"
                aria-controls="mypage-menu-group-profile">
                <h3 class="font-bold"><?php echo $mypage_menu_groups[3]['title']; ?></h3>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mypage-menu-group-icon lucide lucide-chevron-down-icon lucide-chevron-down w-4 h-4 transition-transform duration-200">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>

            <div id="mypage-menu-group-profile" class="mt-4 space-y-3 text-sm">
                <?php foreach ($mypage_menu_groups[3]['items'] as $menu_item) { ?>
                    <a href="<?php echo $menu_item['href']; ?>" class="<?php echo $link_base_class; ?><?php echo basename($menu_item['href']) === $current_menu_path ? ' ' . $link_active_class : ''; ?>">
                        <span><?php echo $menu_item['label']; ?></span>
                    </a>
                <?php } ?>
            </div>
        </section>

        <section class="pb-4 border-b border-zinc-200">
            <button
                type="button"
                class="mypage-menu-group-toggle flex w-full items-center justify-between text-left"
                aria-expanded="true"
                aria-controls="mypage-menu-group-cs">
                <h3 class="font-bold"><?php echo $mypage_menu_groups[4]['title']; ?></h3>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mypage-menu-group-icon lucide lucide-chevron-down-icon lucide-chevron-down w-4 h-4 transition-transform duration-200">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>

            <div id="mypage-menu-group-cs" class="mt-4 space-y-3 text-sm">
                <?php foreach ($mypage_menu_groups[4]['items'] as $menu_item) { ?>
                    <a href="<?php echo $menu_item['href']; ?>" class="<?php echo $link_base_class; ?><?php echo basename($menu_item['href']) === $current_menu_path ? ' ' . $link_active_class : ''; ?>">
                        <span><?php echo $menu_item['label']; ?></span>
                    </a>
                <?php } ?>
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
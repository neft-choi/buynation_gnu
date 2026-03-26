<?php
include_once('./_common.php');

$g5['title'] = '내정보';

include_once(G5_THEME_PATH . '/head.php');
?>

<main class="w-full max-w-full mx-auto bg-zinc-100">
    <header class="flex items-center justify-between gap-2 p-4 bg-white">
        <div></div>
        <h1 class="text-lg font-bold">내정보</h1>
        <div></div>
    </header>

    <section class="p-4 mb-4 bg-white">
        <?php if ($is_guest) { ?>
            <p class="text-lg font-medium text-zinc-700">
                로그인이 필요합니다.
            </p>
        <?php } else { ?>
            <div class="flex items-center gap-2">
                <div class="h-10 w-10 overflow-hidden rounded-full bg-zinc-200">
                    <?php echo get_member_profile_img($member['mb_id']); ?>
                </div>
                <p class="text-2xl font-bold text-zinc-900">
                    <?php echo get_text($member['mb_nick']); ?>님
                </p>
            </div>
        <?php } ?>
    </section>

    <nav aria-label="마이페이지 메뉴">
        <ul class="space-y-4">
            <li class="bg-white p-4">
                <button
                    type="button"
                    class="flex w-full items-center justify-between"
                    aria-expanded="false"
                    aria-controls="acc-buycle"
                    data-acc-toggle>
                    <span class="text-lg font-bold">바이클관리</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down-icon lucide-chevron-down">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>

                <ul id="acc-buycle" class="hidden pl-4 pt-4 space-y-4">
                    <li class="border-b border-gray-900 py-2">
                        <a href="#" class="text-sm font-medium">바이클 리스트</a>
                    </li>
                    <li class="border-b border-gray-900 py-2">
                        <a href="#" class="text-sm font-medium">바이클 코드 가입</a>
                    </li>
                </ul>
            </li>

            <li class="bg-white p-4">
                <button
                    type="button"
                    class="flex w-full items-center justify-between"
                    aria-expanded="false"
                    aria-controls="acc-shoping"
                    data-acc-toggle>
                    <span class="text-lg font-bold">쇼핑관리</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down-icon lucide-chevron-down">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>

                <ul id="acc-shoping" class="hidden pl-4 pt-4 space-y-4">
                    <li class="border-b border-gray-900 py-2">
                        <a href="<?php echo G5_SHOP_URL; ?>/orderinquiry.php" class="text-sm font-medium">주문 내역 조회</a>
                    </li>
                    <li class="border-b border-gray-900 py-2">
                        <a href="<?php echo G5_SHOP_URL; ?>/orderinquiry.php" class="text-sm font-medium">배송 조회</a>
                    </li>
                    <li class="border-b border-gray-900 py-2">
                        <a href="<?php echo G5_SHOP_URL; ?>/orderinquiry.php" class="text-sm font-medium">환불/취소 신청</a>
                    </li>
                    <li class="border-b border-gray-900 py-2">
                        <a href="<?php echo G5_SHOP_URL; ?>/cart.php" class="text-sm font-medium">장바구니 보기</a>
                    </li>
                    <li class="border-b border-gray-900 py-2">
                        <a href="<?php echo G5_SHOP_URL; ?>/orderinquiry.php" class="text-sm font-medium">구매한 상품 목록</a>
                    </li>
                </ul>
            </li>

            <li class="bg-white p-4">
                <button
                    type="button"
                    class="flex w-full items-center justify-between"
                    aria-expanded="false"
                    aria-controls="acc-donation"
                    data-acc-toggle>
                    <span class="text-lg font-bold">기여금/후원금 내역 관리</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down-icon lucide-chevron-down">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>

                <ul id="acc-donation" class="hidden pl-4 pt-4 space-y-4">
                    <li class="border-b border-gray-900 py-2">
                        <a href="#" class="text-sm font-medium">바이클 별 누적금액 확인</a>
                    </li>
                </ul>
            </li>

            <li class="bg-white p-4">
                <button
                    type="button"
                    class="flex w-full items-center justify-between"
                    aria-expanded="false"
                    aria-controls="acc-alert"
                    data-acc-toggle>
                    <span class="text-lg font-bold">알람 관리</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down-icon lucide-chevron-down">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>

                <ul id="acc-alert" class="hidden pl-4 pt-4 space-y-4">
                    <li class="border-b border-gray-900 py-2">
                        <a href="#" class="text-sm font-medium">내가 작성한 댓글에 답글</a>
                    </li>
                    <li class="border-b border-gray-900 py-2">
                        <a href="#" class="text-sm font-medium">내 댓글에 좋아요</a>
                    </li>
                </ul>
            </li>

            <li class="bg-white p-4">
                <button
                    type="button"
                    class="flex w-full items-center justify-between"
                    aria-expanded="false"
                    aria-controls="acc-profile"
                    data-acc-toggle>
                    <span class="text-lg font-bold">개인정보 관리</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down-icon lucide-chevron-down">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </button>

                <ul id="acc-profile" class="hidden pl-4 pt-4 space-y-4">
                    <li class="border-b border-gray-900 py-2">
                        <a href="<?php echo G5_BBS_URL; ?>/member_confirm.php?url=<?php echo urlencode(G5_BBS_URL . '/register_form.php'); ?>" class="text-sm font-medium">기본정보 수정</a>
                    </li>
                    <li class="border-b border-gray-900 py-2">
                        <a href="<?php echo G5_BBS_URL; ?>/member_confirm.php?url=<?php echo urlencode(G5_BBS_URL . '/register_form.php'); ?>" class="text-sm font-medium">비밀번호 변경</a>
                    </li>
                    <li class="border-b border-gray-900 py-2">
                        <a href="<?php echo G5_SHOP_URL; ?>/orderaddress.php" class="text-sm font-medium">주소 관리</a>
                    </li>
                    <li class="border-b border-gray-900 py-2">
                        <a href="#" class="text-sm font-medium">결제수단 관리</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</main>

<script>
    document.querySelectorAll('[data-acc-toggle]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var panelId = btn.getAttribute('aria-controls');
            var panel = document.getElementById(panelId);
            var expanded = btn.getAttribute('aria-expanded') === 'true';

            btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
            panel.classList.toggle('hidden');
        });
    });
</script>

<?php include_once(G5_THEME_PATH . '/tail.php'); ?>
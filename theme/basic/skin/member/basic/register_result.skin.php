<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . $member_skin_url . '/style.css">', 0);
?>

<!-- 모바일 헤더 -->
<header class="flex pc:hidden items-center justify-between h-[var(--mobile-header-height)] mx-auto p-4">
    <button type="button" class="w-6 h-6"></button>
    <h1 class="text-lg font-semibold text-zinc-900 leading-0"></h1>
    <button type="button" class="inline-flex items-center justify-center text-zinc-700" aria-label="닫기"
        onclick="window.location.href = '<?php echo G5_URL ?>'">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-x-icon lucide-x">
            <path d="M18 6 6 18" />
            <path d="m6 6 12 12" />
        </svg>
    </button>
</header>

<!-- 회원가입결과 시작 { -->
<div id="reg_result_custom" class="register w-full max-w-[460px] mx-auto px-4 pt-8 pc:px-5 pc:pt-21">

    <div class="flex flex-col items-center">
        <p class="flex flex-col items-center text-[28px] text-gray-900 font-bold">
            <span><?php echo get_text($mb['mb_name']); ?>님</span>
            <span>환영합니다!</span>
        </p>

        <?php if (is_use_email_certify()) { ?>
            <p class="result_txt">
                회원 가입 시 입력하신 이메일 주소로 인증메일이 발송되었습니다.<br>
                발송된 인증메일을 확인하신 후 인증처리를 하시면 사이트를 원활하게 이용하실 수 있습니다.
            </p>
            <div id="result_email">
                <span>아이디</span>
                <strong><?php echo $mb['mb_id'] ?></strong><br>
                <span>이메일 주소</span>
                <strong><?php echo $mb['mb_email'] ?></strong>
            </div>
            <p>
                이메일 주소를 잘못 입력하셨다면, 사이트 관리자에게 문의해주시기 바랍니다.
            </p>
        <?php } ?>

        <p class="mt-2 text-[15px] text-zinc-500">
            지금부터 도너츠 서비스를 이용해보세요!
        </p>
    </div>

    <div class="mt-10">
        <button type="button" onclick="window.location.href='<?php echo G5_SHOP_URL ?>/';" data-variant="primary"
            class="text-base text-gray-900 font-semibold py-4">이어서 쇼핑하기</button>
    </div>
</div>
<!-- } 회원가입결과 끝 -->

<script>
    // 반응형 쇼핑몰 헤더 숨기기
    syncWithPcBreakpoint(function (isPc) {
        if (isPc) {
            $('#hd').css('display', '');
        } else {
            $('#hd').css('display', 'none');
        }
    });
</script>
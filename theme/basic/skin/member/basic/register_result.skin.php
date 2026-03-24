<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . $member_skin_url . '/style.css">', 0);
?>

<!-- 회원가입결과 시작 { -->
<div id="reg_result_custom" class="register p-4">
    <header class="flex items-center justify-between">
        <button type="button" class="inline-flex h-10 w-10 items-center justify-center text-zinc-700" aria-label="뒤로가기" onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '<?php echo G5_URL ?>'; }">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left">
                <path d="m15 18-6-6 6-6" />
            </svg>
        </button>
        <h1 class="text-base font-semibold text-zinc-900">회원가입</h1>
        <a href="<?php echo G5_URL ?>" class="inline-flex h-10 w-10 items-center justify-center text-zinc-700" aria-label="닫기">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
            </svg>
        </a>
    </header>

    <div class="flex flex-col justify-center items-center gap-4 mt-5">
        <p class="reg_result_p_custom text-2xl text-zinc-900 font-bold">
            <?php echo get_text($mb['mb_name']); ?>님<br>환영합니다!
        </p>

        <?php if (is_use_email_certify()) {  ?>
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
        <?php }  ?>

        <p class="result_txt text-zinc-600">
            지금부터 바이네이션 서비스를 이용해보세요!
        </p>
    </div>
</div>
<!-- } 회원가입결과 끝 -->
<div class="btn_confirm_reg flex !p-4">
    <a href="<?php echo G5_URL ?>/" class="reg_btn_submit_custom w-full p-4 bg-yellow-400 text-zinc-900 font-semibold">메인으로</a>
</div>
<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . $member_skin_url . '/style.css">', 0);
?>

<!-- 모바일 헤더 -->
<div class="flex pc:hidden items-center justify-between h-[var(--mobile-header-height)] px-4">
    <button type="button" class="inline-flex items-center justify-center text-zinc-700" aria-label="뒤로가기"
        onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '<?php echo G5_URL ?>'; }">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-chevron-left-icon lucide-chevron-left w-6 h-6">
            <path d="m15 18-6-6 6-6" />
        </svg>
    </button>
    <h1 class="text-lg font-semibold text-zinc-900 leading-0"><?php echo $g5['title'] ?></h1>
    <div class="w-6 h-6" aria-hidden="true"></div>
</div>

<!-- 회원 비밀번호 확인 시작 { -->
<div id="mb_confirm" class="relative w-full max-w-[460px] mx-auto px-4 py-12 pc:pt-16">
    <h1 class="sr-only"><?php echo $g5['title'] ?></h1>

    <p class="text-base pc:text-[20px] font-medium">비밀번호 재확인</p>

    <p class="text-sm pc:text-base font-normal mt-2">
        <?php if ($url == 'member_leave.php') { ?>
            비밀번호를 입력하시면 회원탈퇴가 완료됩니다.
        <?php } else { ?>
            회원님의 정보를 안전하게 보호하기 위해 비밀번호를 한 번 더 확인합니다.
        <?php } ?>
    </p>

    <form name="fmemberconfirm" action="<?php echo $url ?>" onsubmit="return fmemberconfirm_submit(this);"
        method="post">
        <input type="hidden" name="mb_id" value="<?php echo $member['mb_id'] ?>">
        <input type="hidden" name="w" value="u">

        <fieldset>
            <label for="confirm_mb_id" class="block text-sm pc:text-base font-medium mt-4 mb-1">아이디</label>
            <input type="text" id="confirm_mb_id" value="<?php echo get_text($member['mb_id']) ?>" readonly
                class="!p-[10px]">
            <label for="confirm_mb_password" class="block text-sm pc:text-base font-medium mt-4 mb-1">비밀번호</label>
            <input type="password" name="mb_password" id="confirm_mb_password" required class="required !p-[10px]"
                size="15" maxLength="20" placeholder="비밀번호">
            <input type="submit" value="확인" id="btn_submit"
                class="btn_submit w-full !bg-[var(--color-primary)] !text-gray-900 font-semibold rounded p-4 mt-6">
        </fieldset>

    </form>

</div>

<script>
    function fmemberconfirm_submit(f) {
        document.getElementById("btn_submit").disabled = true;

        return true;
    }

    // 반응형 쇼핑몰 헤더 숨기기
    syncWithPcBreakpoint(function (isPc) {
        if (isPc) {
            $('#hd').css('display', '');
        } else {
            $('#hd').css('display', 'none');
        }
    });
</script>
<!-- } 회원 비밀번호 확인 끝 -->
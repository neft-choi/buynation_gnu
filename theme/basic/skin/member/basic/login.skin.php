<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . $member_skin_url . '/style.css">', 0);
?>

<!-- 로그인 시작 { -->
<div id="mb_login" class="w-full max-w-full mx-auto pt-12 p-4">
    <div class="mbskin_box space-y-8">
        <h1 class="sound_only"><?php echo $g5['title'] ?></h1>

        <div class="text-center space-y-2">
            <p class="text-base text-zinc-500">새로운 가치 소비 생태계</p>
            <a href="/shop" class="text-lg font-semibold tracking-tight text-gray-900">BUYNATION</a>
        </div>

        <form name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">
            <input type="hidden" name="url" value="<?php echo $login_url ?>">

            <fieldset id="login_fs" class="!p-0 space-y-2">
                <label for="login_id" class="sound_only">회원아이디<strong class="sound_only"> 필수</strong></label>
                <input type="text" name="mb_id" id="login_id" required class="w-full rounded border border-zinc-300 bg-white p-4 text-base text-zinc-900 placeholder:text-zinc-400 focus:border-zinc-400 focus:outline-none" size="20" maxLength="20" placeholder="아이디를 입력해주세요">
                <label for="login_pw" class="sound_only">비밀번호<strong class="sound_only"> 필수</strong></label>
                <input type="password" name="mb_password" id="login_pw" required class="w-full rounded border border-zinc-300 bg-white p-4 text-base text-zinc-900 placeholder:text-zinc-400 focus:border-zinc-400 focus:outline-none" size="20" maxLength="20" placeholder="비밀번호를 입력해주세요">

                <!-- <div id="login_info">
                    <div class="login_if_auto chk_box">
                        <input type="checkbox" name="auto_login" id="login_auto_login" class="selec_chk">
                        <label for="login_auto_login"><span></span> 자동로그인</label>
                    </div>
                </div> -->

                <div class="text-base text-zinc-700">
                    <label for="remember_id_ui" class="inline-flex cursor-pointer items-center gap-2">
                        <input type="checkbox" id="remember_id_ui" class="peer sr-only">
                        <span class="peer-checked:hidden text-zinc-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-icon lucide-circle-check">
                                <circle cx="12" cy="12" r="10" />
                                <path d="m9 12 2 2 4-4" />
                            </svg>
                        </span>
                        <span class="hidden peer-checked:inline-flex text-zinc-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-icon lucide-circle-check">
                                <circle cx="12" cy="12" r="10" fill="currentColor" stroke="currentColor" />
                                <path d="m9 12 2 2 4-4" stroke="#ffffff" />
                            </svg>
                        </span>
                        <span>아이디 저장</span>
                    </label>
                </div>

                <button type="submit" class="my-2 p-4 w-full rounded bg-yellow-400 text-base font-semibold text-zinc-900">로그인</button>

                <div class="flex items-center justify-center my-2 gap-3 text-sm text-zinc-600">
                    <a href="<?php echo G5_BBS_URL ?>/password_lost.php">아이디/비밀번호 찾기</a>
                    <span class="text-zinc-300">|</span>
                    <a href="<?php echo G5_BBS_URL ?>/register.php" class="font-bold">회원가입</a>
                </div>
            </fieldset>
        </form>
        <?php // @include_once(get_social_skin_path().'/social_login.skin.php'); // 소셜로그인 사용시 소셜로그인 버튼 
        ?>

        <!-- 소셜 로그인 하드 코딩 (기능 사용 시 제거) -->
        <section class="space-y-4">
            <h2 class="text-center text-sm text-zinc-500">SNS 간편 로그인</h2>
            <div class="grid grid-cols-3 justify-self-center w-55 gap-4">
                <a href="#" class="flex h-15 w-15 items-center justify-center justify-self-center rounded-full border border-zinc-200 bg-zinc-50 text-zinc-900" aria-label="카카오 로그인">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6">
                        <path d="M12 3C6.477 3 2 6.548 2 10.926c0 2.752 1.78 5.174 4.49 6.606L5.5 21l3.748-2.054c.896.152 1.82.232 2.752.232 5.523 0 10-3.548 10-7.926S17.523 3 12 3z" />
                    </svg>
                </a>
                <a href="#" class="flex h-15 w-15 items-center justify-center justify-self-center rounded-full border border-zinc-200 bg-zinc-50 text-3xl font-bold text-green-600" aria-label="네이버 로그인">N</a>
                <a href="#" class="flex h-15 w-15 items-center justify-center justify-self-center rounded-full border border-zinc-200 bg-zinc-50 text-2xl font-semibold text-blue-500" aria-label="구글 로그인">G</a>
            </div>
        </section>

        <section class="rounded bg-[#523919] p-6 text-white">
            <div>프로모션 배너</div>
        </section>
    </div>

    <?php // 쇼핑몰 사용시 여기부터 
    ?>
    <?php if (isset($default['de_level_sell']) && $default['de_level_sell'] == 1) { // 상품구입 권한 
    ?>

        <!-- 주문하기, 신청하기 -->
        <?php if (preg_match("/orderform.php/", $url)) { ?>
            <section id="mb_login_notmb" class="!p-4 space-y-2">
                <h2>비회원 구매</h2>
                <p class="text-sm">비회원으로 주문하시는 경우 포인트는 지급하지 않습니다.</p>

                <div id="guest_privacy">
                    <?php echo conv_content($default['de_guest_privacy'], $config['cf_editor']); ?>
                </div>

                <div class="chk_box">
                    <input type="checkbox" id="agree" value="1" class="selec_chk">
                    <label for="agree" class="flex items-center gap-2 text-sm"><span></span> 개인정보수집에 대한 내용을 읽었으며 이에 동의합니다.</label>
                </div>

                <div class="btn_confirm">
                    <a href="javascript:guest_submit(document.flogin);" class="btn_submit">비회원으로 구매하기</a>
                </div>

                <script>
                    function guest_submit(f) {
                        if (document.getElementById('agree')) {
                            if (!document.getElementById('agree').checked) {
                                alert("개인정보수집에 대한 내용을 읽고 이에 동의하셔야 합니다.");
                                return;
                            }
                        }

                        f.url.value = "<?php echo $url; ?>";
                        f.action = "<?php echo $url; ?>";
                        f.submit();
                    }
                </script>
            </section>

        <?php } else if (preg_match("/orderinquiry.php$/", $url)) { ?>
            <div id="mb_login_od_wr">
                <h2>비회원 주문조회 </h2>

                <fieldset id="mb_login_od">
                    <legend>비회원 주문조회</legend>

                    <form name="forderinquiry" method="post" action="<?php echo urldecode($url); ?>" autocomplete="off">

                        <label for="od_id" class="od_id sound_only">주문서번호<strong class="sound_only"> 필수</strong></label>
                        <input type="text" name="od_id" value="<?php echo get_text($od_id); ?>" id="od_id" required class="frm_input required" size="20" placeholder="주문서번호">
                        <label for="od_pwd" class="od_pwd sound_only">비밀번호 <strong>필수</strong></label>
                        <input type="password" name="od_pwd" size="20" id="od_pwd" required class="frm_input required" placeholder="비밀번호">
                        <button type="submit" class="btn_submit">확인</button>

                    </form>
                </fieldset>

                <section id="mb_login_odinfo">
                    <p>메일로 발송해드린 주문서의 <strong>주문번호</strong> 및 주문 시 입력하신 <strong>비밀번호</strong>를 정확히 입력해주십시오.</p>
                </section>

            </div>
        <?php } ?>

    <?php } ?>
    <?php // 쇼핑몰 사용시 여기까지 반드시 복사해 넣으세요 
    ?>

</div>

<script>
    jQuery(function($) {
        $("#login_auto_login").click(function() {
            if (this.checked) {
                this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
            }
        });
    });

    function flogin_submit(f) {
        if ($(document.body).triggerHandler('login_sumit', [f, 'flogin']) !== false) {
            return true;
        }
        return false;
    }
</script>
<!-- } 로그인 끝 -->
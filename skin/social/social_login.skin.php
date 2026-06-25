<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가

if (!$config['cf_social_login_use']) {     //소셜 로그인을 사용하지 않으면
    return;
}

$social_pop_once = false;

$self_url = G5_BBS_URL . "/login.php";

//새창을 사용한다면
if (G5_SOCIAL_USE_POPUP) {
    $self_url = G5_SOCIAL_LOGIN_URL . '/popup.php';
}

add_stylesheet('<link rel="stylesheet" href="' . get_social_skin_url() . '/style.css?ver=' . G5_CSS_VER . '">', 10);
?>

<div id="sns_login" class="">
    <h3 class="sound_only">소셜계정으로 로그인</h3>
    <div class="sns-wrap flex items-center gap-5">
        <?php if (social_service_check('kakao')) {     //카카오 로그인을 사용한다면 ?>
            <a href="<?php echo $self_url; ?>?provider=kakao&amp;url=<?php echo $urlencode; ?>"
                class="social_link flex items-center justify-center w-15 h-15 border border-zinc-200 rounded-full bg-zinc-50"
                title="카카오">
                <svg viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-7 h-7">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M14.002 4.66602C8.20595 4.66602 3.50195 8.12821 3.50195 12.3863C3.50195 15.0426 5.31845 17.3607 8.09045 18.7734L6.92495 22.8226C6.90296 22.9032 6.90749 22.9881 6.93792 23.0662C6.96836 23.1442 7.02325 23.2116 7.09523 23.2592C7.16721 23.3068 7.25285 23.3325 7.34065 23.3327C7.42845 23.3329 7.51422 23.3077 7.58645 23.2604L12.6895 20.0469C13.12 20.0469 13.561 20.1165 14.002 20.1165C19.798 20.1165 24.502 16.6543 24.502 12.3863C24.502 8.11826 19.798 4.66602 14.002 4.66602Z"
                        fill="black" />
                </svg>
            </a>
        <?php }     //end if ?>
        <?php if (social_service_check('naver')) {     //네이버 로그인을 사용한다면 ?>
            <a href="<?php echo $self_url; ?>?provider=naver&amp;url=<?php echo $urlencode; ?>"
                class="social_link flex items-center justify-center w-15 h-15 border border-zinc-200 rounded-full bg-zinc-50"
                title="네이버">
                <svg viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-7 h-7">
                    <path
                        d="M17.3229 14.6738L10.4026 4.68311H4.66602V23.3498H10.6758V13.3592L17.5962 23.3498H23.3327V4.68311H17.3229V14.6738Z"
                        fill="#03CF5D" />
                </svg>

            </a>
        <?php }     //end if ?>
        <?php if (social_service_check('facebook')) {     //페이스북 로그인을 사용한다면 ?>
            <a href="<?php echo $self_url; ?>?provider=facebook&amp;url=<?php echo $urlencode; ?>"
                class="social_link flex items-center justify-center w-15 h-15 border border-zinc-200 rounded-full bg-zinc-50"
                title="페이스북">
            </a>
        <?php }     //end if ?>
        <?php if (social_service_check('google')) {     //구글 로그인을 사용한다면 ?>
            <a href="<?php echo $self_url; ?>?provider=google&amp;url=<?php echo $urlencode; ?>"
                class="social_link flex items-center justify-center w-15 h-15 border border-zinc-200 rounded-full bg-zinc-50"
                title="구글">
                <svg viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-7 h-7">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M24.0785 14.2392C24.0785 13.4949 24.012 12.7785 23.8871 12.0913H13.998V16.1527H19.6496C19.4058 17.4652 18.6661 18.5783 17.5542 19.3226V21.9571H20.947C22.9328 20.1288 24.0785 17.4372 24.0785 14.2392Z"
                        fill="#3D82F0" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M13.9991 24.501C16.8343 24.501 19.2109 23.5606 20.9481 21.9575L17.5553 19.3219C16.6149 19.9519 15.412 20.3241 13.9991 20.3241C11.2644 20.3241 8.94959 18.4772 8.12355 15.9956H4.61523V18.7164C6.34314 22.1477 9.89463 24.501 13.9991 24.501Z"
                        fill="#31A752" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M8.12291 15.9956C7.91291 15.3656 7.7939 14.6924 7.7939 14.0005C7.7939 13.3087 7.91291 12.6355 8.12291 12.0055V9.28467H4.6146C3.90406 10.7022 3.49805 12.3065 3.49805 14.0005C3.49805 15.6946 3.90406 17.2989 4.6146 18.7164L8.12291 15.9956Z"
                        fill="#F9BA00" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M13.9991 7.67636C15.5404 7.67636 16.9253 8.20605 18.0126 9.24676L21.0251 6.23546C19.2062 4.54022 16.8296 3.49951 13.9991 3.49951C9.89463 3.49951 6.34314 5.85278 4.61523 9.28526L8.12355 12.0049C8.94959 9.52327 11.2644 7.67636 13.9991 7.67636Z"
                        fill="#E64234" />
                </svg>

            </a>
        <?php }     //end if ?>
        <?php if (social_service_check('twitter')) {     //트위터 로그인을 사용한다면 ?>
            <a href="<?php echo $self_url; ?>?provider=twitter&amp;url=<?php echo $urlencode; ?>"
                class="social_link flex items-center justify-center w-15 h-15 border border-zinc-200 rounded-full bg-zinc-50"
                title="트위터">
            </a>
        <?php }     //end if ?>
        <?php if (social_service_check('payco')) {     //페이코 로그인을 사용한다면 ?>
            <a href="<?php echo $self_url; ?>?provider=payco&amp;url=<?php echo $urlencode; ?>"
                class="social_link flex items-center justify-center w-15 h-15 border border-zinc-200 rounded-full bg-zinc-50"
                title="페이코">
            </a>
        <?php }     //end if ?>

        <?php if (G5_SOCIAL_USE_POPUP && !$social_pop_once) {
            $social_pop_once = true;
            ?>
            <script>
                jQuery(function ($) {
                    $(".sns-wrap").on("click", "a.social_link", function (e) {
                        e.preventDefault();

                        var pop_url = $(this).attr("href");
                        var newWin = window.open(
                            pop_url,
                            "social_sing_on",
                            "location=0,status=0,scrollbars=1,width=600,height=500"
                        );

                        if (!newWin || newWin.closed || typeof newWin.closed == 'undefined')
                            alert('브라우저에서 팝업이 차단되어 있습니다. 팝업 활성화 후 다시 시도해 주세요.');

                        return false;
                    });
                });
            </script>
        <?php } ?>

    </div>
</div>
<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . $member_skin_url . '/style.css">', 0);
?>

<!-- 회원가입약관 동의 시작 { -->
<div class="register w-full max-w-full bg-white p-4">

    <div class="flex items-center justify-between">
        <button type="button" class="inline-flex items-center justify-center text-zinc-700" aria-label="뒤로가기" onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '<?php echo G5_URL ?>'; }">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left">
                <path d="m15 18-6-6 6-6" />
            </svg>
        </button>
        <h1 class="text-base font-semibold text-zinc-900">회원가입</h1>
        <a href="<?php echo G5_URL ?>" class="inline-flex items-center justify-center text-zinc-700" aria-label="닫기">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
            </svg>
        </a>
    </div>

    <form name="fregister" id="fregister_custom" action="<?php echo $register_action_url ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off" class="my-8 space-y-4">
        <div class="space-y-1">
            <h2 class="text-2xl font-bold text-zinc-900">약관동의</h2>
            <p class="text-sm text-zinc-500">바이네이션 회원 서비스 이용을 위해</p>
            <p class="text-sm text-zinc-500">아래 약관 동의가 필요합니다.</p>
        </div>

        <?php
        // 소셜로그인 사용시 소셜로그인 버튼
        @include_once(get_social_skin_path() . '/social_register.skin.php');
        ?>

        <div id="fregister_chkall_custom" class="rounded bg-zinc-100 p-4">
            <label for="chk_all" class="flex cursor-pointer items-center gap-3 text-base font-medium text-zinc-900">
                <input type="checkbox" name="chk_all" id="chk_all" class="selec_chk sr-only peer">
                <span class="inline-flex h-5 w-5 items-center justify-center rounded-full border border-zinc-300 bg-white text-transparent peer-checked:border-zinc-800 peer-checked:bg-zinc-800 peer-checked:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                        <path d="m5 12 5 5L20 7"></path>
                    </svg>
                </span>
                전체 동의
            </label>
        </div>

        <div class="space-y-1 px-4">
            <div class="grid grid-cols-[1fr_auto] items-center gap-4 py-2">
                <label for="agree11" class="flex cursor-pointer items-center gap-2 text-sm text-zinc-800">
                    <input type="checkbox" name="agree" value="1" id="agree11" class="selec_chk sr-only peer">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full border border-zinc-300 bg-white text-transparent peer-checked:border-zinc-800 peer-checked:bg-zinc-800 peer-checked:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                            <path d="m5 12 5 5L20 7"></path>
                        </svg>
                    </span>
                    바이네이션 이용약관 <span class="text-zinc-500">(필수)</span>
                </label>
                <button type="button" class="text-zinc-400" data-modal-target="termsModal" aria-label="회원가입약관 보기">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-[1fr_auto] items-center gap-3 py-2">
                <label for="agree3" class="flex cursor-pointer items-center gap-2 text-sm text-zinc-800">
                    <input type="checkbox" name="agree3" value="1" id="agree3" class="selec_chk sr-only peer">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full border border-zinc-300 bg-white text-transparent peer-checked:border-zinc-800 peer-checked:bg-zinc-800 peer-checked:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                            <path d="m5 12 5 5L20 7"></path>
                        </svg>
                    </span>
                    전자금융서비스 이용약관 동의 <span class="text-zinc-500">(필수)</span>
                </label>
                <button type="button" class="text-zinc-400" data-modal-target="financeModal" aria-label="전자금융서비스 약관 보기">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-[1fr_auto] items-center gap-3 py-2">
                <label for="agree21" class="flex cursor-pointer items-center gap-2 text-sm text-zinc-800">
                    <input type="checkbox" name="agree2" value="1" id="agree21" class="selec_chk sr-only peer">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full border border-zinc-300 bg-white text-transparent peer-checked:border-zinc-800 peer-checked:bg-zinc-800 peer-checked:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                            <path d="m5 12 5 5L20 7"></path>
                        </svg>
                    </span>
                    개인정보 수집 및 이용동의 <span class="text-zinc-500">(필수)</span>
                </label>
                <button type="button" class="text-zinc-400" data-modal-target="privacyModal" aria-label="개인정보 처리방침 보기">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-[1fr_auto] items-center gap-3 py-2">
                <label for="agree4" class="flex cursor-pointer items-center gap-2 text-sm text-zinc-800">
                    <input type="checkbox" name="agree4" value="1" id="agree4" class="selec_chk sr-only peer">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full border border-zinc-300 bg-white text-transparent peer-checked:border-zinc-800 peer-checked:bg-zinc-800 peer-checked:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                            <path d="m5 12 5 5L20 7"></path>
                        </svg>
                    </span>
                    만 14세 이상입니다 <span class="text-zinc-500">(필수)</span>
                </label>
                <span></span>
            </div>

            <div class="grid grid-cols-[1fr_auto] items-center gap-3 py-2">
                <label for="agree5" class="flex cursor-pointer items-center gap-2 text-sm text-zinc-800">
                    <input type="checkbox" name="agree5" value="1" id="agree5" class="selec_chk sr-only peer">
                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full border border-zinc-300 bg-white text-transparent peer-checked:border-zinc-800 peer-checked:bg-zinc-800 peer-checked:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                            <path d="m5 12 5 5L20 7"></path>
                        </svg>
                    </span>
                    혜택 알림 이메일, 문자, 앱 푸시 수신 <span class="text-zinc-400">(선택)</span>
                </label>
                <span></span>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-2 gap-3">
            <a href="<?php echo G5_URL ?>" class="inline-flex h-12 items-center justify-center rounded border border-zinc-300 text-base font-semibold text-zinc-700">처음으로</a>
            <button type="submit" data-variant="primary" class="h-12 !text-base">가입하기</button>
        </div>

    </form>

    <div id="termsModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40" data-modal-close="termsModal"></div>
        <div class="relative mx-auto mt-10 w-full rounded-t-2xl bg-white px-5 pb-6 pt-4" style="max-width: 375px;">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-base font-semibold text-zinc-900">바이네이션 이용약관</h3>
                <button type="button" class="inline-flex h-8 w-8 items-center justify-center text-zinc-500" data-modal-close="termsModal" aria-label="모달 닫기">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="max-h-[70vh] overflow-y-auto rounded border border-zinc-200 bg-zinc-50 p-4 text-sm text-zinc-700 whitespace-pre-wrap"><?php echo get_text($config['cf_stipulation']); ?></div>
        </div>
    </div>

    <div id="financeModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40" data-modal-close="financeModal"></div>
        <div class="relative mx-auto mt-10 w-full rounded-t-2xl bg-white px-5 pb-6 pt-4" style="max-width: 375px;">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-base font-semibold text-zinc-900">전자금융서비스 이용약관</h3>
                <button type="button" class="inline-flex h-8 w-8 items-center justify-center text-zinc-500" data-modal-close="financeModal" aria-label="모달 닫기">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="max-h-[70vh] overflow-y-auto rounded border border-zinc-200 bg-zinc-50 p-4 text-sm text-zinc-700 whitespace-pre-wrap">[전자금융서비스 이용약관 동의]</div>
        </div>
    </div>

    <div id="privacyModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40" data-modal-close="privacyModal"></div>
        <div class="relative mx-auto mt-10 w-full rounded-t-2xl bg-white px-5 pb-6 pt-4" style="max-width: 375px;">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-base font-semibold text-zinc-900">개인정보 수집 및 이용동의</h3>
                <button type="button" class="inline-flex h-8 w-8 items-center justify-center text-zinc-500" data-modal-close="privacyModal" aria-label="모달 닫기">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="max-h-[70vh] overflow-y-auto rounded border border-zinc-200 bg-zinc-50 p-4 text-sm text-zinc-700 whitespace-pre-wrap"><?php echo get_text($config['cf_privacy']); ?></div>
        </div>
    </div>

    <script>
        // 폼 제출 시 필수 약관(회원가입약관/개인정보) 체크 여부를 검증한다.
        function fregister_submit(f) {
            // 기존 그누보드 회원가입약관 필수 동의
            if (!f.agree.checked) {
                alert("회원가입약관의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
                f.agree.focus();
                return false;
            }

            // 기존 그누보드 개인정보 수집 및 이용 필수 동의
            if (!f.agree2.checked) {
                alert("개인정보 수집 및 이용의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
                f.agree2.focus();
                return false;
            }

            return true;
        }

        jQuery(function($) {
            // "전체 동의" 체크박스: name이 agree로 시작하는 항목 일괄 토글
            $("input[name=chk_all]").click(function() {
                if ($(this).prop('checked')) {
                    $("input[name^=agree]").prop('checked', true);
                } else {
                    $("input[name^=agree]").prop("checked", false);
                }
            });

            // 약관 상세 보기 버튼: data-modal-target 값(모달 id)에 해당하는 모달을 연다.
            $("[data-modal-target]").on("click", function() {
                var modalId = $(this).data("modal-target");
                var $modal = $("#" + modalId);
                if ($modal.length) {
                    $modal.removeClass("hidden");
                    // 모달 오픈 시 배경 스크롤 방지
                    $("body").addClass("overflow-hidden");
                }
            });

            // 닫기 버튼/X/배경 클릭: data-modal-close 값(모달 id)에 해당하는 모달을 닫는다.
            $("[data-modal-close]").on("click", function() {
                var modalId = $(this).data("modal-close");
                $("#" + modalId).addClass("hidden");
                // 모달이 닫히면 배경 스크롤 복구
                $("body").removeClass("overflow-hidden");
            });
        });
    </script>
</div>
<!-- } 회원가입 약관 동의 끝 -->
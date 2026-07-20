<?php
include_once('./_common.php');

// bbs 페이지를 쇼핑몰 페이지로 사용하게 만드는 상수
if (!defined('_SHOP_')) {
    define('_SHOP_', true);
}

// include_once('./_head.sub.php');
include_once(G5_SHOP_PATH . '/_head.php');
?>
<!-- 모바일 헤더 -->
<header class="flex pc:hidden items-center justify-between h-[var(--mobile-header-height)] mx-auto p-4">
    <button type="button" class="inline-flex items-center justify-center text-zinc-700" aria-label="뒤로가기"
        onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '<?php echo G5_URL ?>'; }">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-chevron-left-icon lucide-chevron-left w-6 h-6">
            <path d="m15 18-6-6 6-6" />
        </svg>
    </button>
    <h1 class="text-lg font-semibold text-zinc-900 leading-0">회원가입</h1>
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

<!-- 회원 타입 선택 시작 { -->
<div class="w-full max-w-[460px] mx-auto px-4 py-12 pc:pt-16">
    <h1 class="sound_only">회원가입</h1>

    <div class="space-y-8">
        <div class="text-center space-y-2">
            <p class="text-[15px] text-zinc-500">새로운 가치 소비 생태계</p>
            <a href="<?php echo G5_SHOP_URL; ?>/" class="flex items-center justify-center">
                <img src="<?php echo G5_DATA_URL; ?>/common/logo_img" alt="<?php echo $config['cf_title']; ?>">
            </a>
        </div>

        <form action="<?php echo G5_BBS_URL ?>/register.php" method="get" class="space-y-10">
            <div class="space-y-2">
                <!-- 항목 1: 도트(일반 회원) -->
                <label
                    class="flex items-center justify-between p-4 border border-zinc-200 rounded cursor-pointer hover:bg-zinc-50 hover:border-zinc-400 has-[:checked]:border-gray-900 transition-all group">
                    <div class="flex items-center gap-3">
                        <input type="radio" name="type" value="dots" checked
                            class="w-5 h-5 appearance-none border border-zinc-300 rounded-full accent-white checked:border-[6px] checked:border-[var(--color-primary)]">
                        <span class="text-sm font-semibold text-zinc-900">일반회원 가입하기</span>
                    </div>
                </label>

                <!-- 항목 2: 도넛(모임) -->
                <label
                    class="flex items-center justify-between p-4 border border-zinc-200 rounded cursor-pointer hover:bg-zinc-50 hover:border-zinc-400 has-[:checked]:border-gray-900 transition-all group">
                    <div class="flex items-center gap-3">
                        <input type="radio" name="type" value="donuts"
                            class="w-5 h-5 appearance-none border border-zinc-300 rounded-full accent-white checked:border-[6px] checked:border-[var(--color-primary)]">
                        <span class="text-sm font-semibold text-zinc-900">모임 가입하기</span>
                    </div>
                </label>
            </div>

            <div class="space-y-3">
                <p class="text-center text-[15px] text-zinc-500">도너츠에서 판매를 원하시나요?</p>

                <!-- 항목 3: 서포터(판매회원) -->
                <label
                    class="flex items-center justify-between p-4 border border-zinc-200 rounded cursor-pointer hover:bg-zinc-50 hover:border-zinc-400 has-[:checked]:border-gray-900 transition-all group">
                    <div class="flex items-center gap-3">
                        <input type="radio" name="type" value="brand"
                            class="w-5 h-5 appearance-none border border-zinc-300 rounded-full accent-white checked:border-[6px] checked:border-[var(--color-primary)]">
                        <span class="text-sm font-semibold text-zinc-900">판매회원 가입하기</span>
                    </div>
                </label>
            </div>

            <button type="submit" data-variant="primary" class="h-14 !text-base cursor-pointer">가입하기</button>
        </form>


        <div class="text-center text-sm text-zinc-600">
            <p>이미 회원이신가요? <a href="/bbs/login.php" class="font-bold underline">로그인</a></p>
        </div>
    </div>
</div>
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
<?php
// include_once('./_tail.sub.php'); 
include_once(G5_SHOP_PATH . '/_tail.php');
?>
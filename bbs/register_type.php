<?php
include_once('./_common.php');
include_once('./_head.sub.php');
?>

<!-- 회원 타입 선택 시작 { -->
<div class="w-full max-w-[460px] mx-auto pt-12 p-4">
    <h1 class="sound_only"><?php echo $g5['title'] ?></h1>
    <div class="space-y-8">


        <div class="text-center space-y-2">
            <p class="text-base text-zinc-500">새로운 가치 소비 생태계</p>
            <a href="/shop" class="text-4xl font-semibold tracking-tight text-gray-900">Buynation</a>
        </div>

        <form action="<?php echo G5_BBS_URL ?>/register.php" method="post" class="space-y-10">
            <div class="space-y-2">
                <!-- 항목 1: 일반 회원 -->
                <label class="flex items-center justify-between p-4 border border-zinc-200 rounded cursor-pointer hover:bg-zinc-50 hover:border-zinc-400 has-[:checked]:border-gray-900 transition-all group">
                    <div class="flex items-center gap-3">
                        <input type="radio" name="mb_type" value="general" checked class="w-5 h-5 appearance-none border border-zinc-300 rounded-full accent-white checked:border-[6px] checked:border-[var(--color-primary)]">
                        <span class="text-sm font-semibold text-zinc-900">일반회원 가입하기</span>
                    </div>
                </label>

                <!-- 항목 2: 바이클 -->
                <label class="flex items-center justify-between p-4 border border-zinc-200 rounded cursor-pointer hover:bg-zinc-50 hover:border-zinc-400 has-[:checked]:border-gray-900 transition-all group">
                    <div class="flex items-center gap-3">
                        <input type="radio" name="mb_type" value="buycle" class="w-5 h-5 appearance-none border border-zinc-300 rounded-full accent-white checked:border-[6px] checked:border-[var(--color-primary)]">
                        <span class="text-sm font-semibold text-zinc-900">바이클 가입하기</span>
                    </div>
                </label>
            </div>

            <div class="space-y-3">
                <p class="text-center text-sm text-zinc-600">바이네이션에서 판매를 원하시나요?</p>

                <!-- 항목 4: 판매회원 가입하기 -->
                <label class="flex items-center justify-between p-4 border border-zinc-200 rounded cursor-pointer hover:bg-zinc-50 hover:border-zinc-400 has-[:checked]:border-gray-900 transition-all group">
                    <div class="flex items-center gap-3">
                        <input type="radio" name="mb_type" value="seller" class="w-5 h-5 appearance-none border border-zinc-300 rounded-full accent-white checked:border-[6px] checked:border-[var(--color-primary)]">
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

<?php include_once('./_tail.sub.php'); ?>
<?php
if (!defined("_GNUBOARD_")) exit;
?>

<!-- 최근 본 상품 -->
<div id="recent-viewed-backdrop" class="hidden pointer-events-auto absolute inset-0 z-40 bg-black/40"></div>

<div id="recent-viewed-panel"
    class="pointer-events-auto absolute top-0 right-0 z-50 flex flex-col h-full w-full max-w-110 translate-x-full bg-white">
    <div class="flex items-center justify-between border-b border-zinc-200 p-5">
        <p class="text-lg font-semibold text-gray-900">최근 본 상품</p>

        <button type="button" id="recent-viewed-close"
            class="inline-flex items-center justify-center w-9 h-9 rounded text-gray-700 hover:bg-gray-300 cursor-pointer"
            aria-label="최근 본 상품 닫기">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-x-icon lucide-x w-6 h-6">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
            </svg>
        </button>
    </div>

    <div id="recent-viewed-list" class="min-h-0 flex-1 overflow-y-auto p-5">
        <?php include(G5_SHOP_SKIN_PATH . '/boxtodayview.skin.php'); // 최근 본 상품
        ?>
    </div>
</div>

<script>
    // 최근 본 상품 패널 열기/닫기 제어
    $(function() {
        $('html').addClass('gutter-stable');

        $(document).on('click', '#recent-viewed-open', function() {
            $('#recent-viewed-backdrop').removeClass('hidden');
            $('#recent-viewed-panel').removeClass('translate-x-full');
            $('html').addClass('!overflow-hidden');
        });

        $(document).on('click', '#recent-viewed-close, #recent-viewed-backdrop', function() {
            $('#recent-viewed-backdrop').addClass('hidden');
            $('#recent-viewed-panel').addClass('translate-x-full');
            $('html').removeClass('!overflow-hidden');
        });
    });
</script>
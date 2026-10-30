<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_JS_URL . '/owlcarousel/owl.carousel.css">', 0);
add_javascript('<script src="' . G5_JS_URL . '/owlcarousel/owl.carousel.min.js"></script>', 10);

$images = get_item_images_info($row, $size, 60, 60);
$image_numbers = array_keys($images);
$start_position = array_search($no, $image_numbers, true);
$start_position = $start_position === false ? 0 : $start_position;
?>

<div id="sit_pvi_nw" class="flex min-h-dvh flex-col bg-white text-zinc-900">
    <header class="hidden pc:flex items-center justify-between px-4 py-4">
        <h1 id="win_title" class="text-2xl font-bold">상품 이미지 확대</h1>
        <button type="button" onclick="window.close();" class="inline-flex h-6 w-6 shrink-0 items-center justify-center text-zinc-700" aria-label="확대창 닫기">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6" aria-hidden="true">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
            </svg>
        </button>
    </header>

    <header class="flex pc:hidden items-center justify-between p-4">
        <div class="h-6 w-6 shrink-0" aria-hidden="true"></div>
        <h1 class="text-lg font-semibold leading-none text-zinc-900">상품 이미지 확대</h1>
        <button type="button" onclick="window.close();" class="inline-flex h-6 w-6 shrink-0 items-center justify-center text-zinc-700" aria-label="확대창 닫기">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6" aria-hidden="true">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
            </svg>
        </button>
    </header>

    <main class="flex min-w-0 flex-1 flex-col justify-center gap-4">
        <div id="sit_pvi_nwbig" class="owl-carousel">
            <?php foreach ((array) $images as $imgs) { ?>
                <div class="h-[60dvh] max-h-[480px] px-4 [&>img]:h-full [&>img]:w-full [&>img]:object-contain">
                    <?php echo $imgs['imagehtml']; ?>
                </div>
            <?php } ?>
        </div>
        <div class="flex items-center justify-center gap-3 text-zinc-500">
            <button type="button" id="sit_pvi_nwprev" aria-label="이전 상품 이미지" class="flex h-8 w-8 items-center justify-center text-xl">‹</button>
            <span id="sit_pvi_nwcount" class="text-sm"></span>
            <button type="button" id="sit_pvi_nwnext" aria-label="다음 상품 이미지" class="flex h-8 w-8 items-center justify-center text-xl">›</button>
        </div>
    </main>

    <footer class="flex justify-end p-4">
        <button type="button" onclick="window.close();" class="h-12 w-32 rounded-md bg-yellow-400 font-semibold">닫기</button>
    </footer>
</div>

<script>
    jQuery(function($) {
        const $slider = $("#sit_pvi_nwbig");
        if (!$slider.children().length) return;

        $slider.on("initialized.owl.carousel translated.owl.carousel refreshed.owl.carousel", function(event) {
            const current = event.relatedTarget.relative(event.item.index) + 1;
            $("#sit_pvi_nwcount").text(current + " / " + event.item.count);
        });

        $slider.owlCarousel({
            items: 1,
            nav: false,
            dots: false,
            loop: true,
            rewind: $slider.children().length === 1,
            startPosition: <?php echo (int) $start_position; ?>,
            autoplay: false
        });

        $("#sit_pvi_nwprev").on("click", function() {
            $slider.trigger("prev.owl.carousel");
        });
        $("#sit_pvi_nwnext").on("click", function() {
            $slider.trigger("next.owl.carousel");
        });
    });
</script>
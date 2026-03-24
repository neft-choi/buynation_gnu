<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MSHOP_PATH . '/index.php');
    return;
}

if (! defined('_INDEX_')) define('_INDEX_', TRUE);

include_once(G5_THEME_SHOP_PATH . '/shop.head.php');
?>

<!-- 메인이미지 시작 { -->
<?php echo display_banner('메인', 'mainbanner.10.skin.php'); ?>
<!-- } 메인이미지 끝 -->

<?php if ($default['de_type1_list_use']) { ?>
    <!-- 히트상품 시작 { -->
    <section id="idx_hit" class="sct_wrap idx_product">
        <header class="flex flex-col gap-2">
            <p class="text-base text-[var(--color-primary-strong)] font-semibold">BUYNATION BEST ITEM</p>
            <h2>
                <a href="<?php echo shop_type_url('1'); ?>" class="flex items-center gap-2 justify-between">
                    지금 인기있는 상품을 만나보세요!
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </a>
            </h2>
        </header>
        <?php
        $list = new item_list();
        $list->set_type(1);
        $list->set_view('it_img', true);
        $list->set_view('it_id', false);
        $list->set_view('it_name', true);
        $list->set_view('it_basic', true);
        $list->set_view('it_cust_price', true);
        $list->set_view('it_price', true);
        $list->set_view('it_icon', true);
        $list->set_view('sns', true);
        $list->set_view('star', true);
        echo $list->run();
        ?>
    </section>
    <!-- } 히트상품 끝 -->
<?php } ?>

<?php if ($default['de_type2_list_use']) { ?>
    <!-- 추천상품 시작 { -->
    <section id="idx_recommend" class="sct_wrap idx_product">
        <header class="flex flex-col gap-2">
            <p class="text-base text-[var(--color-primary-strong)] font-semibold">BUYCLE RECOMMEND</p>
            <h2>
                <a href="<?php echo shop_type_url('2'); ?>" class="flex items-center gap-2 justify-between">
                    <div>
                        바이클의 추천 제품
                        <span class="text-[var(--color-primary-strong)]">Pick</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </a>
            </h2>
        </header>
        <?php
        $list = new item_list();
        $list->set_type(2);
        $list->set_list_skin(G5_SHOP_SKIN_PATH . '/main.10.skin.php');
        $list->set_view('it_id', false);
        $list->set_view('it_name', true);
        $list->set_view('it_basic', true);
        $list->set_view('it_cust_price', true);
        $list->set_view('it_price', true);
        $list->set_view('it_icon', true);
        $list->set_view('sns', true);
        $list->set_view('star', true);
        echo $list->run();
        ?>
    </section>
    <!-- } 추천상품 끝 -->
<?php } ?>

<!-- New 신상품 시작 { -->
<?php if ($default['de_type3_list_use']) { ?>
    <section id="idx_new" class="sct_wrap idx_product">
        <header class="flex flex-col gap-2">
            <h2>
                <a href="<?php echo shop_type_url('3'); ?>" class="flex items-center gap-2 justify-between">
                    지금 올라온 따끈따끈한 신상
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </a>
            </h2>
            <p class="text-sm text-gray-400">새롭게 업데이트된 상품들을 만나보세요</p>
        </header>
        <?php
        $list = new item_list();
        $list->set_type(3);
        $list->set_list_skin(G5_SHOP_SKIN_PATH . '/main.10.skin.php');
        $list->set_view('it_id', false);
        $list->set_view('it_name', true);
        $list->set_view('it_basic', true);
        $list->set_view('it_cust_price', true);
        $list->set_view('it_price', true);
        $list->set_view('it_icon', true);
        $list->set_view('sns', true);
        $list->set_view('star', true);
        echo $list->run();
        ?>
    </section>
<?php } ?>
<!-- } New 신상품 끝 -->

<!-- 프로모션 단일 배너 -->
<?php
$promo_banner = array(
    'href' => '#',
    'image' => 'https://placehold.co/375x88',
    'alt' => '프로모션 배너', // 실제 배너 내용이 있으면 더 좋다 (예시 - 2월 한정 30% 할인 프로모션 )
);
?>
<section class="my-4 hidden">
    <div class="w-full">
        <h2 class="sound_only">프로모션 배너</h2>
        <a href="<?php echo htmlspecialchars($promo_banner['href'], ENT_QUOTES, 'UTF-8'); ?>" aria-label="프로모션 상세 보기" class="block">
            <img width="375" height="88" class="w-full" src="<?php echo htmlspecialchars($promo_banner['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($promo_banner['alt'], ENT_QUOTES, 'UTF-8'); ?>" loading="lazy" />
        </a>
    </div>
</section>
<!-- 프로모션 단일 배너 끝 -->

<!-- 가치소비 Pick 시작 { -->
<?php if ($default['de_type4_list_use']) { ?>
    <section id="idx_value_pick" class="sct_wrap idx_product bg-[#FAFAFA]">
        <header class="flex flex-col gap-2">
            <h2>
                <a href="<?php echo shop_type_url('2'); ?>" class="flex items-center gap-2 justify-between">
                    <div>
                        인기상품
                        <span class="text-[var(--color-primary-strong)]">Pick</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </a>
            </h2>
            <!-- <p class="text-sm text-gray-400">인기상품</p> -->
        </header>
        <?php
        $list = new item_list();
        $list->set_type(4);
        $list->set_list_skin(G5_SHOP_SKIN_PATH . '/main.60.skin.php');
        $list->set_view('it_id', false);
        $list->set_view('it_name', true);
        $list->set_view('it_basic', true);
        $list->set_view('it_cust_price', true);
        $list->set_view('it_price', true);
        $list->set_view('it_icon', true);
        $list->set_view('sns', true);
        $list->set_view('star', true);
        echo $list->run();
        ?>
    </section>
<?php } ?>
<!-- } 가치소비 Pick 끝 -->

<!-- 특가 혜택 상품 시작 { -->
<?php if ($default['de_type5_list_use']) { ?>
    <section id="idx_timedeal" class="sct_wrap idx_product">
        <header class="flex flex-col gap-2">
            <h2>
                <a href="<?php echo shop_type_url('5'); ?>">
                    특가 혜택 상품
                </a>
            </h2>
            <p class="text-sm text-gray-400">늦기 전에 서두르세요!</p>
            <p class="flex items-center gap-2 text-2xl text-[var(--color-primary)]">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alarm-clock-icon lucide-alarm-clock">
                    <circle cx="12" cy="13" r="8" />
                    <path d="M12 9v4l2 2" />
                    <path d="M5 3 2 6" />
                    <path d="m22 6-3-3" />
                    <path d="M6.38 18.7 4 21" />
                    <path d="M17.64 18.67 20 21" />
                </svg>
                <span class="js-today-countdown text-gray-900 font-semibold">00:00:00</span>
            </p>
        </header>
        <?php
        $list = new item_list();
        $list->set_type(5);
        $list->set_list_skin(G5_SHOP_SKIN_PATH . '/main.70.skin.php');
        $list->set_list_mod(1);
        $list->set_list_row(2);
        $list->set_view('it_id', false);
        $list->set_view('it_name', true);
        $list->set_view('it_basic', true);
        $list->set_view('it_cust_price', true);
        $list->set_view('it_price', true);
        $list->set_view('it_icon', true);
        $list->set_view('sns', true);
        $list->set_view('star', true);
        echo $list->run();
        ?>
    </section>
<?php } ?>
<!-- 특가 혜택 상품 끝 } -->

<!-- 카테고리 별 BEST 상품 시작 { -->
<?php if ($default['de_type3_list_use']) { ?>
    <section id="idx_category_best" class="sct_wrap idx_product">
        <header class="flex flex-col gap-2">
            <h2>
                <a href="<?php echo shop_type_url('2'); ?>">
                    BEST 상품
                </a>
            </h2>
            <p class="text-sm text-gray-400">최대 50% 까지 할인!</p>
        </header>
        <div class="px-2">
            <!-- <div class="scrollbar-hidden overflow-x-auto">
                <div class="flex gap-2 min-w-max px-1">
                    <button type="button" class="px-4 py-2 rounded bg-[var(--color-primary)] text-black text-sm font-semibold">전체</button>
                    <button type="button" class="px-4 py-2 rounded bg-gray-100 text-gray-700 text-sm font-semibold">식품</button>
                    <button type="button" class="px-4 py-2 rounded bg-gray-100 text-gray-700 text-sm font-semibold">스포츠/레저/취미</button>
                    <button type="button" class="px-4 py-2 rounded bg-gray-100 text-gray-700 text-sm font-semibold">패션/잡화</button>
                    <button type="button" class="px-4 py-2 rounded bg-gray-100 text-gray-700 text-sm font-semibold">가전/디지털</button>
                </div>
            </div> -->

            <?php
            $list = new item_list();
            $list->set_type(2);
            $list->set_list_skin(G5_SHOP_SKIN_PATH . '/main.80.skin.php');
            $list->set_view('it_id', false);
            $list->set_view('it_name', true);
            $list->set_view('it_basic', true);
            $list->set_view('it_cust_price', true);
            $list->set_view('it_price', true);
            $list->set_view('it_icon', true);
            $list->set_view('sns', true);
            $list->set_view('star', true);
            echo $list->run();
            ?>
        </div>
    </section>
<?php } ?>
<!-- 카테고리 별 BEST 상품 끝 } -->

<!-- 프로모션 단일 배너 (중간) 시작 { -->
<?php
$promo_banner = array(
    'href' => '#',
    'image' => 'https://placehold.co/375x88',
    'alt' => '프로모션 배너', // 실제 배너 내용이 있으면 더 좋다 (예시 - 2월 한정 30% 할인 프로모션 )
);
?>
<section class="my-4 hidden">
    <div class="w-full">
        <h2 class="sound_only">프로모션 배너</h2>
        <a href="<?php echo htmlspecialchars($promo_banner['href'], ENT_QUOTES, 'UTF-8'); ?>" aria-label="프로모션 상세 보기" class="block">
            <img width="375" height="88" class="w-full" src="<?php echo htmlspecialchars($promo_banner['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($promo_banner['alt'], ENT_QUOTES, 'UTF-8'); ?>" loading="lazy" />
        </a>
    </div>
</section>
<!-- 프로모션 단일 배너 끝 (중간) 끝 } -->

<?php if ($default['de_type2_list_use']) { ?>
    <!-- 추천상품 시작 { -->
    <section id="idx_buycle" class="sct_wrap idx_product">
        <header class="flex flex-col gap-2">
            <h2>
                <a href="<?php echo shop_type_url('2'); ?>" class="flex items-center gap-2 justify-between">
                    바이클 추천 상품
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </a>
            </h2>
            <p class="text-sm text-gray-400">바이머를 위한 바이클 추천 상품!</p>
        </header>
        <?php
        $list = new item_list();
        $list->set_type(2);
        $list->set_list_skin(G5_SHOP_SKIN_PATH . '/main.10.skin.php');
        $list->set_view('it_id', false);
        $list->set_view('it_name', true);
        $list->set_view('it_basic', true);
        $list->set_view('it_cust_price', true);
        $list->set_view('it_price', true);
        $list->set_view('it_icon', true);
        $list->set_view('sns', true);
        $list->set_view('star', true);
        echo $list->run();
        ?>
    </section>
    <!-- } 추천상품 끝 -->
<?php } ?>

<script>
    $(function() {

        // 상품 슬라이더 생성 및 설정
        function initSectionSlider(sectionSelector) {
            $(sectionSelector).each(function() {
                var $section = $(this);
                var $sectionSlider = $section.find('.js-shop-slider');
                var sliderItems = parseFloat($sectionSlider.attr('data-items'));
                var sliderMargin = parseInt($sectionSlider.attr('data-margin'), 10);
                var sliderStagePadding = parseInt($sectionSlider.attr('data-stage-padding'), 10);

                if (!$sectionSlider.length || $sectionSlider.hasClass('owl-loaded')) {
                    return;
                }

                if (!sliderItems) {
                    sliderItems = 2.15;
                }
                if (isNaN(sliderMargin)) {
                    sliderMargin = 12;
                }
                if (isNaN(sliderStagePadding)) {
                    sliderStagePadding = 12;
                }

                $sectionSlider.owlCarousel({
                    loop: true,
                    nav: false,
                    autoplay: false,
                    autoplayHoverPause: false,
                    margin: sliderMargin,
                    stagePadding: sliderStagePadding,
                    items: sliderItems
                });
            });
        }

        initSectionSlider('#idx_hit'); // 인기 상품
        initSectionSlider('#idx_recommend'); // 바이클 추천 상품
        initSectionSlider('#idx_new'); // New 신상품
        initSectionSlider('#idx_value_pick'); // 가치소비 Pick
        initSectionSlider('#idx_timedeal'); // 특가 혜택 상품
        initSectionSlider('#idx_category_best'); // 카테고리 별 Best 상품
        initSectionSlider('#idx_buycle') // 바이클 추천 상품

        function formatTwoDigits(num) {
            return (num < 10 ? '0' : '') + num;
        }

        function getRemainingTodayText() {
            var now = new Date();
            var endOfDay = new Date(now);
            endOfDay.setHours(24, 0, 0, 0);

            var diffMs = endOfDay - now;
            if (diffMs < 0) {
                diffMs = 0;
            }

            var totalSec = Math.floor(diffMs / 1000);
            var hours = Math.floor(totalSec / 3600);
            var minutes = Math.floor((totalSec % 3600) / 60);
            var seconds = totalSec % 60;

            return formatTwoDigits(hours) + ':' + formatTwoDigits(minutes) + ':' + formatTwoDigits(seconds);
        }

        function renderTodayCountdown() {
            $('.js-today-countdown').text(getRemainingTodayText());
        }

        renderTodayCountdown();
        setInterval(renderTodayCountdown, 1000);
    });
</script>

<?php
include_once(G5_THEME_SHOP_PATH . '/shop.tail.php');

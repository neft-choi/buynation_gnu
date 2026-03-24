<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_SHOP_SKIN_URL . '/style.css">', 0);
add_javascript('<script src="' . G5_JS_URL . '/owlcarousel/owl.carousel.min.js"></script>', 10);
add_stylesheet('<link rel="stylesheet" href="' . G5_JS_URL . '/owlcarousel/owl.carousel.min.css">', 10);

$max_width = $max_height = 0;
$bn_first_class = ' class="bn_first"';
$bn_slide_btn = '';
$bn_sl = ' class="bn_sl"';
$main_banners = array();

for ($i = 0; $row = sql_fetch_array($result); $i++) {
    $main_banners[] = $row;

    // 테두리 있는지
    $bn_border  = ($row['bn_border']) ? ' class="sbn_border"' : '';;
    // 새창 띄우기인지
    $bn_new_win = ($row['bn_new_win']) ? ' target="_blank"' : '';

    $bimg = G5_DATA_PATH . '/banner/' . $row['bn_id'];
    $item_exists = false;
    $link_href = '';
    $link_target = '';
    $img_src = '';
    $img_width = 0;
    $img_alt = '';

    if (file_exists($bimg)) {
        $size = getimagesize($bimg);

        if ($size[2] < 1 || $size[2] > 16)
            continue;

        if ($max_width < $size[0])
            $max_width = $size[0];

        if ($max_height < $size[1])
            $max_height = $size[1];

        if ($row['bn_url'][0] == '#')
            $link_href = $row['bn_url'];
        else if ($row['bn_url'] && $row['bn_url'] != 'http://') {
            $link_href = G5_SHOP_URL . '/bannerhit.php?bn_id=' . $row['bn_id'];
            $link_target = $bn_new_win;
        }

        $img_src = G5_DATA_URL . '/banner/' . $row['bn_id'] . '?' . preg_replace('/[^0-9]/i', '', $row['bn_time']);
        $img_width = $size[0];
        $img_alt = get_text($row['bn_alt']);
        $item_exists = true;
    }

    $banner_style = $max_height ? 'style="min-height:' . ($max_height + 25) . 'px"' : '';
    if ($i == 0) {
?>

        <style>
            #main_bn .btn_wr {
                padding: 6px 12px;
            }

            #main_bn .pager-prev {
                display: none;
            }
        </style>

        <div id="main_bn" class="!mt-0">
            <div class="main_image_area">
                <div class="main_banner_owl owl-carousel">
                <?php
            }

            if ($item_exists) {
                ?>
                    <div class="item aspect-[16/9]">
                        <?php if ($link_href) { ?><a href="<?php echo $link_href; ?>" <?php echo $link_target; ?>><?php } ?>
                            <img src="<?php echo $img_src; ?>" width="<?php echo $img_width; ?>" alt="<?php echo $img_alt; ?>" <?php echo $bn_border; ?>>
                            <?php if ($link_href) { ?></a><?php } ?>
                    </div>
                <?php
            }
        }

        if ($i > 0) {
                ?>
                </div>
                <div class="main_bn_controls absolute right-4 bottom-4 z-10 flex items-center gap-1">
                    <button type="button" class="main_bn_pause inline-flex h-9 w-9 items-center justify-center rounded-full bg-black/50 text-white/80" aria-label="배너 일시정지">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="0" stroke-linecap="round" stroke-linejoin="round" class="pause-icon lucide lucide-pause">
                            <rect x="14" y="4" width="4" height="16" rx="1"></rect>
                            <rect x="6" y="4" width="4" height="16" rx="1"></rect>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="0" stroke-linecap="round" stroke-linejoin="round" class="play-icon lucide lucide-play hidden">
                            <polygon points="6 3 20 12 6 21 6 3"></polygon>
                        </svg>
                    </button>
                    <div class="btn_wr !static !inline-flex !p-2 items-center text-sm text-white/80"><a href="#" class="pager-prev"><i class="fa fa-angle-left"></i></a>
                        <div id="slide-counter" class="!p-0"></div><a href="#" class="pager-next !p-0"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right">
                                <path d="m9 18 6-6-6-6" />
                            </svg></a>
                    </div>
                </div>
            </div>
            <div class="main_owl_pager">
                <div class="owl_pager">
                    <ul class="carousel-custom-dots owl-dots">
                        <?php
                        $k = 0;
                        foreach ($main_banners as $row) {
                            $alt_title = $row['bn_alt'] ? cut_str(get_text($row['bn_alt']), 12, '') : '&nbsp;';
                        ?>
                            <li class="owl-dot"><a data-slide-index="<?php echo $k; ?>" href="#"><?php echo $alt_title; ?></a></li>
                        <?php
                            $k++;
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <script>
            jQuery(function($) {

                function owl_show_page(event) {

                    if (event.item) {
                        var count = event.item.count,
                            item_index = event.item.index,
                            index = 1;

                        if (is_loop) {
                            index = (1 + (event.property.value - Math.ceil(event.item.count / 2)) % event.item.count || 0) || 1;
                        } else {
                            index = event.item.index ? event.item.index + 1 : 1;
                        }

                        $(event.target).closest("#main_bn").find(".slide-index").text(index);
                    }
                }

                var is_loop = true,
                    item_totals = $('.main_banner_owl .item').length,
                    is_autoplay = true;

                if (item_totals) {
                    $('#slide-counter').prepend('<span class="slide-index current-index"></span> / ')
                        .append('<span class="total-slides">' + item_totals + '</span>');
                }

                var owl = $('.main_banner_owl').owlCarousel({
                    items: 1,
                    loop: is_loop,
                    margin: 0,
                    nav: false,
                    autoHeight: false,
                    autoplay: true,
                    autoplayTimeout: 5000, // 5000은 5초
                    autoplayHoverPause: true,
                    dotsContainer: '.carousel-custom-dots',
                    onChanged: function(event) {
                        owl_show_page(event);
                    },
                });

                // Custom Navigation Events
                $(document).on("click", ".carousel-custom-dots a", function(e) {
                    e.preventDefault();
                    owl.trigger('to.owl.carousel', [$(this).parent().index(), 300]);
                });

                $(document).on("click", ".btn_wr .pager-next", function(e) {
                    e.preventDefault();
                    owl.trigger('next.owl.carousel');
                });

                $(document).on("click", ".btn_wr .pager-prev", function(e) {
                    e.preventDefault();
                    owl.trigger('prev.owl.carousel');
                });

                $(document).on("click", ".main_bn_pause", function() {
                    var $button = $(this);
                    var $pauseIcon = $button.find(".pause-icon");
                    var $playIcon = $button.find(".play-icon");

                    if (is_autoplay) {
                        owl.trigger('stop.owl.autoplay');
                        $button.attr("aria-label", "배너 재생");
                        $pauseIcon.addClass("hidden");
                        $playIcon.removeClass("hidden");
                    } else {
                        owl.trigger('play.owl.autoplay', [5000]);
                        $button.attr("aria-label", "배너 일시정지");
                        $pauseIcon.removeClass("hidden");
                        $playIcon.addClass("hidden");
                    }

                    is_autoplay = !is_autoplay;
                });
            });
        </script>
    <?php
        }

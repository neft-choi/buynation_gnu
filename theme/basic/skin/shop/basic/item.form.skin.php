<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_SHOP_CSS_URL . '/style.css">', 0);
add_javascript('<script src="' . G5_JS_URL . '/owlcarousel/owl.carousel.min.js"></script>', 10);
add_stylesheet('<link rel="stylesheet" href="' . G5_JS_URL . '/owlcarousel/owl.carousel.css">', 0);

// 사용할 변수 선언
$it_price = (int) get_price($it);
$it_cust_price = (int) $it['it_cust_price'];
$is_discount = ($it_cust_price > 0 && $it_cust_price > $it_price);
$discount_percent = $is_discount
	? (int) round((($it_cust_price - $it_price) / $it_cust_price) * 100)
	: 0;
$star_avg = isset($it['it_use_avg']) ? number_format((float) $it['it_use_avg'], 1) : '0.0';
$use_cnt = isset($item_use_count) ? (int) $item_use_count : (int) $it['it_use_cnt'];
?>

<style>
	#sit_tot_price {
		display: none;
	}

	#sit_tot_price strong {
		font-size: 16px;
		font-weight: 600;
		margin-left: 16px;
	}
</style>
<div id="sit_ov_from">
	<form name="fitem" method="post" action="<?php echo $action_url; ?>" onsubmit="return fitem_submit(this);">
		<input type="hidden" name="it_id[]" value="<?php echo $it_id; ?>">
		<input type="hidden" name="sw_direct">
		<input type="hidden" name="url">
		<input type="hidden" id="it_price" value="<?php echo $it_price; ?>">

		<div id="sit_ov_wrap">
			<!-- 상품이미지 미리보기 시작 { -->
			<div id="sit_pvi">
				<div id="sit_pvi_big">
					<?php
					$big_img_count = 0;
					$first_img_no = 1;
					echo '<div class="owl-carousel sit-pvi-slider">';
					for ($i = 1; $i <= 10; $i++) {
						if (!$it['it_img' . $i])
							continue;

						$img = get_it_thumbnail($it['it_img' . $i], $default['de_mimg_width'], $default['de_mimg_height']);

						if ($img) {
							if ($big_img_count === 0) {
								$first_img_no = $i;
							}
							$big_img_count++;

							echo '<div class="sit-pvi-slide">';
							echo '<a href="' . G5_SHOP_URL . '/largeimage.php?it_id=' . $it['it_id'] . '&amp;no=' . $i . '" target="_blank" class="popup_item_image">' . $img . '</a>';
							echo '</div>';
						}
					}
					echo '</div>';

					if ($big_img_count == 0) {
						echo '<img src="' . G5_SHOP_URL . '/img/no_image.gif" alt="" class="sit-pvi-empty">';
					}
					?>
					<?php if ($big_img_count > 0) { ?>
						<a href="<?php echo G5_SHOP_URL; ?>/largeimage.php?it_id=<?php echo $it['it_id']; ?>&amp;no=<?php echo $first_img_no; ?>" target="_blank" id="popup_item_image" class="popup_item_image"><i class="fa fa-search-plus" aria-hidden="true"></i><span class="sound_only">확대보기</span></a>
					<?php } ?>
				</div>
			</div>
			<!-- } 상품이미지 미리보기 끝 -->

			<!-- 상품 요약정보 및 구매 시작 { -->
			<section id="sit_ov" class="2017_renewal_itemform">
				<p class="flex items-center gap-1 text-sm text-zinc-500 font-semibold">
					<?php echo $it["it_maker"]; ?>
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right">
						<path d="m9 18 6-6-6-6" />
					</svg>
				</p>
				<div class="flex items-baseline justify-between">

					<h2 id="sit_title"><?php echo stripslashes($it['it_name']); ?> <span class="sound_only">요약정보 및 구매</span></h2>

					<div class="flex gap-2 items-center text-sm ml-4">
						<span class="flex gap-1 items-center">
							<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-star-icon lucide-star">
								<path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z" />
							</svg>
							<?php echo $star_avg; ?>
						</span>
						<span class="text-gray-300">|</span>
						<span class="text-gray-400 underline text-nowrap"><?php echo $it['it_use_cnt']; ?>건</span>
					</div>
				</div>

				<?php if ($is_orderable) { ?>
					<p id="sit_opt_info">
						상품 선택옵션 <?php echo $option_count; ?> 개, 추가옵션 <?php echo $supply_count; ?> 개
					</p>
				<?php } ?>

				<div id="sit_star_sns">
					<?php if ($star_score) { ?>
						<span class="sound_only">고객평점</span>
						<img src="<?php echo G5_SHOP_URL; ?>/img/s_star<?php echo $star_score ?>.png" alt="" class="sit_star" width="100">
						<span class="sound_only">별<?php echo $star_score ?>개</span>
					<?php } ?>

					<span class="">사용후기 <?php echo $it['it_use_cnt']; ?> 개</span>

					<div id="sit_btn_opt">
						<span id="btn_wish"><i class="fa fa-heart-o" aria-hidden="true"></i><span class="sound_only">위시리스트</span><span class="btn_wish_num"><?php echo get_wishlist_count_by_item($it['it_id']); ?></span></span>
						<button type="button" class="btn_sns_share"><i class="fa fa-share-alt" aria-hidden="true"></i><span class="sound_only">sns 공유</span></button>
						<div class="sns_area">
							<?php echo $sns_share_links; ?>
							<a href="javascript:popup_item_recommend('<?php echo $it['it_id']; ?>');" id="sit_btn_rec"><i class="fa fa-envelope-o" aria-hidden="true"></i><span class="sound_only">추천하기</span></a>
						</div>
					</div>
				</div>
				<script>
					$(".btn_sns_share").click(function() {
						$(".sns_area").show();
					});
					$(document).mouseup(function(e) {
						const container = $(".sns_area");
						if (container.has(e.target).length === 0)
							container.hide();
					});
				</script>

				<div class="sit_info">
					<div class="flex flex-col">
						<div class="flex items-center gap-2">
							<?php if ($is_discount) { ?>
								<span class="text-2xl font-bold text-black"><?php echo $discount_percent; ?>%</span>
								<span class="text-2xl font-bold text-black"><?php echo display_price($it_price); ?></span>
								<span class="text-base text-gray-400 line-through"><?php echo display_price($it_cust_price); ?></span>
							<?php } else { ?>
								<span class="text-2xl font-bold text-black"><?php echo display_price($it_price); ?></span>
							<?php } ?>
						</div>

						<div class="space-y-2">
							<div class="flex items-center gap-2">
								<span class="text-2xl font-bold text-red-500">92%</span>
								<span class="text-2xl font-bold text-red-500">800원</span>
								<span class="text-base font-medium text-red-500">가입 축하 할인가</span>
							</div>

							<div class="flex items-center justify-center gap-1 px-4 py-2 bg-yellow-50">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#FFDB62" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ticket-percent-icon lucide-ticket-percent">
									<path d="M2 9a3 3 0 1 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 1 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z" />
									<path d="M9 9h.01" />
									<path d="m15 9-6 6" />
									<path d="M15 15h.01" />
								</svg>
								<span class="text-sm whitespace-nowrap font-semibold">가입 축하 쿠폰 받고 6,500원에 구매하기</span>
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right">
									<path d="m9 18 6-6-6-6" />
								</svg>
							</div>
						</div>


						<div class="mt-4">
							<div class="grid grid-cols-[72px_1fr] gap-x-4 py-4 border-t border-gray-200">
								<div class="text-sm text-gray-500">배송정보</div>
								<div class="text-sm">
									<div class="font-medium text-gray-900">택배배송</div>
									<div class="mt-1 font-semibold text-gray-900">배송비 2,500원 (50,000원 이상 무료)</div>
									<div class="mt-1 text-gray-400">11/12(수) 도착예정</div>
								</div>
							</div>

							<div class="grid grid-cols-[72px_1fr] gap-x-4 py-4 border-t border-gray-200">
								<div class="text-sm text-gray-500">무이자</div>
								<div class="flex items-center gap-2 text-sm font-medium text-gray-900">
									카드사별 무이자 혜택
									<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
										<path d="m6 9 6 6 6-6" />
									</svg>
								</div>
							</div>

							<div class="grid grid-cols-[72px_1fr] gap-x-4 py-4 border-t border-gray-200">
								<div class="text-sm text-gray-500">포인트</div>
								<div class="text-sm font-medium text-gray-900">0.1% 바이네이션 포인트 적립</div>
							</div>
						</div>

					</div>
				</div>
				<?php if (!$is_orderable) { ?>
					<?php if ($is_soldout) { ?>
						<p id="sit_ov_soldout">상품의 재고가 부족하여 구매할 수 없습니다.</p>
					<?php } ?>
					<div id="sit_ov_btn">
						<a href="javascript:item_wish(document.fitem, '<?php echo $it['it_id']; ?>');" class="sit_btn_wish"><i class="fa fa-heart-o" aria-hidden="true"></i><span class="sound_only">위시리스트</span></a>
						<?php if ($it['it_soldout'] && $it['it_stock_sms']) { ?>
							<a href="javascript:popup_stocksms('<?php echo $it['it_id']; ?>');" id="sit_btn_alm">재입고알림</a>
						<?php } ?>
						<?php if ($naverpay_button_js) { ?>
							<div class="itemform-naverpay"><?php echo $naverpay_request_js . $naverpay_button_js; ?></div>
						<?php } ?>
					</div>
				<?php } ?>

				<script>
					// 상품보관
					function item_wish(f, it_id) {
						f.url.value = "<?php echo G5_SHOP_URL; ?>/wishupdate.php?it_id=" + it_id;
						f.action = "<?php echo G5_SHOP_URL; ?>/wishupdate.php";
						f.submit();
					}

					// 추천메일
					function popup_item_recommend(it_id) {
						if (!g5_is_member) {
							if (confirm("회원만 추천하실 수 있습니다."))
								document.location.href = "<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo urlencode(shop_item_url($it_id)); ?>";
						} else {
							url = "./itemrecommend.php?it_id=" + it_id;
							opt = "scrollbars=yes,width=616,height=420,top=10,left=10";
							popup_window(url, "itemrecommend", opt);
						}
					}

					// 재입고SMS 알림
					function popup_stocksms(it_id) {
						url = "<?php echo G5_SHOP_URL; ?>/itemstocksms.php?it_id=" + it_id;
						opt = "scrollbars=yes,width=616,height=420,top=10,left=10";
						popup_window(url, "itemstocksms", opt);
					}
				</script>
			</section>
			<!-- } 상품 요약정보 및 구매 끝 -->
		</div>
		<!-- 다른 상품 보기 시작 { -->
		<div id="sit_siblings">
			<?php
			if ($prev_href || $next_href) {
				echo $prev_href . $prev_title . $prev_href2;
				echo $next_href . $next_title . $next_href2;
			} else {
				echo '<span class="sound_only">이 분류에 등록된 다른 상품이 없습니다.</span>';
			}
			?>
		</div>
		<!-- } 다른 상품 보기 끝 -->
		<?php if ($is_orderable) { ?>
			<div id="sit_bottom_fab" class="fixed inset-x-0 bottom-0 z-[60] border-t border-gray-200 bg-white/95 px-4 py-2 pb-[calc(0.5rem+env(safe-area-inset-bottom))] backdrop-blur">
				<div class="mx-auto grid w-full max-w-full grid-cols-[48px_48px_1fr] gap-2">
					<button type="button"
						class="btn_wish flex h-12 w-12 items-center justify-center rounded border border-gray-300 bg-white text-gray-700"
						data-it_id="<?php echo $it['it_id']; ?>"
						aria-label="위시리스트">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart">
							<path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
						</svg>
					</button>

					<button type="button"
						id="sit_copy_link_btn"
						class="flex h-12 w-12 items-center justify-center rounded border border-gray-300 bg-white text-gray-700"
						aria-label="공유하기">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-share-icon lucide-share">
							<path d="M12 2v13" />
							<path d="m16 6-4-4-4 4" />
							<path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8" />
						</svg>
					</button>

					<button type="button"
						id="sit_open_drawer"
						aria-controls="sit_purchase_drawer"
						aria-expanded="false"
						data-variant="primary"
						class="h-12">
						장바구니 담기
					</button>
				</div>
			</div>

			<div id="sit_drawer_overlay" class="fixed inset-0 z-[70] hidden bg-black/40" aria-hidden="true"></div>

			<div id="sit_purchase_drawer" class="fixed inset-x-0 bottom-0 z-[80] hidden translate-y-full transition-transform duration-300 ease-out" role="dialog" aria-modal="true" aria-labelledby="sit_drawer_title" aria-hidden="true">
				<div class="mx-auto w-full max-w-full rounded-t-2xl bg-white shadow-2xl">
					<div class="flex items-center justify-between border-b border-gray-200 px-4 py-3">
						<strong id="sit_drawer_title" class="text-sm font-semibold text-gray-900">옵션 선택</strong>
						<button type="button" id="sit_close_drawer" class="inline-flex h-8 w-8 items-center justify-center rounded text-gray-500" aria-label="닫기">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x">
								<path d="M18 6 6 18" />
								<path d="m6 6 12 12" />
							</svg>
						</button>
					</div>
					<div class="max-h-[70vh] overflow-y-auto px-4 py-4">
						<?php
						if ($option_item) {
						?>
							<!-- 선택옵션 시작 { -->
							<section class="sit_option">
								<h3>선택옵션</h3>
								<?php echo $option_item; ?>
							</section>
							<!-- } 선택옵션 끝 -->
						<?php
						}
						?>

						<?php
						if ($supply_item) {
						?>
							<!-- 추가옵션 시작 { -->
							<section class="sit_option">
								<h3>추가옵션</h3>
								<?php echo $supply_item; ?>
							</section>
							<!-- } 추가옵션 끝 -->
						<?php
						}
						?>

						<!-- 선택된 옵션 시작 { -->
						<section id="sit_sel_option" class="mb-4">
							<h3>선택된 옵션</h3>
							<?php
							if (!$option_item) {
								if (!$it['it_buy_min_qty'])
									$it['it_buy_min_qty'] = 1;
							?>
								<ul id="sit_opt_added" class="space-y-2">
									<li class="sit_opt_list space-y-2">
										<input type="hidden" name="io_type[<?php echo $it_id; ?>][]" value="0">
										<input type="hidden" name="io_id[<?php echo $it_id; ?>][]" value="">
										<input type="hidden" name="io_value[<?php echo $it_id; ?>][]" value="<?php echo $it['it_name']; ?>">
										<input type="hidden" class="io_price" value="0">
										<input type="hidden" class="io_stock" value="<?php echo $it['it_stock_qty']; ?>">
										<div class="opt_name">
											<span class="sit_opt_subj"><?php echo $it['it_name']; ?></span>
										</div>
										<div class="opt_count flex items-center justify-between gap-2">
											<label for="ct_qty_<?php echo $i; ?>" class="sound_only">수량</label>
											<div class="flex">
												<button type="button" class="sit_qty_minus flex items-center justify-center p-1 border border-gray-600">
													<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-minus-icon lucide-minus">
														<path d="M5 12h14" />
													</svg>
													<span class="sound_only">감소</span>
												</button>

												<input type="text" name="ct_qty[<?php echo $it_id; ?>][]" value="<?php echo $it['it_buy_min_qty']; ?>" id="ct_qty_<?php echo $i; ?>" class="num_input !p-0 !w-fit !rounded-none !border-gray-900 !border-l-0 !border-r-0 text-center" size="5">

												<button type="button" class="sit_qty_plus flex items-center justify-center p-1 border border-gray-600">
													<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus">
														<path d="M5 12h14" />
														<path d="M12 5v14" />
													</svg>
													<span class="sound_only">증가</span>
												</button>
											</div>
											<span class="sit_opt_prc">+0원</span>
											<span class="opt_prc_custom whitespace-nowrap text-sm font-semibold text-gray-900">+0원</span>
										</div>
									</li>
								</ul>
								<script>
									$(function() {
										price_calculate();
									});
								</script>
							<?php } ?>
						</section>
						<!-- } 선택된 옵션 끝 -->

						<div id="sit_tot_price"></div>

						<div id="sit_ov_btn" class="gap-2 items-center">
							<button type="submit" data-variant="secondary" onclick="document.pressed=this.value;" value="장바구니" class="h-12">장바구니</button>
							
							<button type="submit" data-variant="primary" onclick="document.pressed=this.value;" value="바로구매" class="h-12">바로구매</button>
							
							<a href="javascript:item_wish(document.fitem, '<?php echo $it['it_id']; ?>');" class="flex !w-12 !h-12 shrink-0 p-2 items-center justify-center rounded border border-gray-300 bg-white text-gray-700">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart">
									<path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
								</svg>
								<span class="sound_only">위시리스트</span>
							</a>
							
							<?php if ($naverpay_button_js) { ?>
								<div class="itemform-naverpay"><?php echo $naverpay_request_js . $naverpay_button_js; ?></div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>

		<?php } ?>
	</form>
</div>

<script>
	$(function() {
		const $drawer = $("#sit_purchase_drawer");
		const $overlay = $("#sit_drawer_overlay");
		const $openDrawerBtn = $("#sit_open_drawer");
		const $closeDrawerBtn = $("#sit_close_drawer");
		let lastFocusedElement = null;

		function resetDrawerOptionSelects() {
			const $optionSelects = $drawer.find("select.it_option");
			if (!$optionSelects.length) return;

			$optionSelects.each(function(index) {
				const $sel = $(this);
				$sel.val("");
				if (index === 0) {
					$sel.prop("selectedIndex", 0).attr("disabled", false).trigger("change");
				} else {
					$sel.prop("selectedIndex", 0).attr("disabled", true);
				}
			});
		}

		function openPurchaseDrawer() {
			if (!$drawer.length || !$overlay.length) return;
			lastFocusedElement = document.activeElement;
			resetDrawerOptionSelects();
			$overlay.removeClass("hidden");
			$drawer.removeClass("hidden");
			requestAnimationFrame(function() {
				$drawer.removeClass("translate-y-full");
			});
			$("body").addClass("overflow-hidden");
			$openDrawerBtn.attr("aria-expanded", "true");
			$overlay.attr("aria-hidden", "false");
			$drawer.attr("aria-hidden", "false");

			setTimeout(function() {
				const $firstFocusable = $drawer.find("select, input, button, textarea, a[href]").filter(":visible").first();
				if ($firstFocusable.length) {
					$firstFocusable.focus();
				}
			}, 50);
		}

		function closePurchaseDrawer() {
			if (!$drawer.length || !$overlay.length) return;
			$drawer.addClass("translate-y-full");
			$("body").removeClass("overflow-hidden");
			$openDrawerBtn.attr("aria-expanded", "false");
			$overlay.attr("aria-hidden", "true");
			$drawer.attr("aria-hidden", "true");
			setTimeout(function() {
				$overlay.addClass("hidden");
				$drawer.addClass("hidden");
				if (lastFocusedElement && typeof lastFocusedElement.focus === "function") {
					lastFocusedElement.focus();
				}
			}, 300);
		}

		$openDrawerBtn.on("click", openPurchaseDrawer);
		$closeDrawerBtn.on("click", closePurchaseDrawer);
		$overlay.on("click", closePurchaseDrawer);
		$(document).on("keydown", function(e) {
			if (e.key === "Escape") closePurchaseDrawer();
		});

		const $slider = $("#sit_pvi_big .sit-pvi-slider");
		const $zoomButton = $("#popup_item_image");

		if ($slider.length && $.fn.owlCarousel) {
			const slideCount = $slider.find(".sit-pvi-slide").length;

			$slider.owlCarousel({
				items: 1,
				margin: 0,
				nav: false,
				dots: true,
				autoplay: false,
				loop: slideCount > 1,
				mouseDrag: slideCount > 1,
				touchDrag: slideCount > 1,
				pullDrag: slideCount > 1
			});

			if ($zoomButton.length) {
				const syncZoomButtonHref = function(e) {
					let index = 0;
					if (e && e.item && typeof e.item.index !== "undefined") {
						index = e.item.index;
					}

					const href = $slider.find(".owl-item").eq(index).find(".popup_item_image").attr("href");
					if (href) {
						$zoomButton.attr("href", href);
					}
				};

				syncZoomButtonHref();
				$slider.on("changed.owl.carousel", syncZoomButtonHref);
			}
		}

		// 상품이미지 크게보기
		$(".popup_item_image").click(function() {
			const url = $(this).attr("href");
			const top = 10;
			const left = 10;
			const opt = 'scrollbars=yes,top=' + top + ',left=' + left;
			popup_window(url, "largeimage", opt);

			return false;
		});

		// btn_wish 클래스가 있
		$(document).on("click", ".btn_wish", function(e) {
			e.preventDefault();

			const itId = $(this).data("it_id");
			if (!itId) {
				alert("상품코드가 올바르지 않습니다.");
				return false;
			}

			if (typeof g5_is_member === "undefined" || !g5_is_member) {
				if (confirm("회원 전용 서비스입니다.\n로그인 하시겠습니까?")) {
					location.href = g5_bbs_url + "/login.php?url=" + encodeURIComponent(location.href);
				}
				return false;
			}

			$.post(
				g5_shop_url + "/ajax.action.php", {
					action: "wish_update",
					it_id: itId
				},
				function(res) {
					if (res !== "OK") {
						alert(String(res).replace(/\\n/g, "\n"));
						return;
					}

					if (typeof mainCart !== "undefined" && typeof mainCart.update_wish_side === "function") {
						mainCart.update_wish_side();
					}

					alert("상품을 위시리스트에 담았습니다.");
				}
			);
		});

		$("#sit_copy_link_btn").on("click", function(e) {
			e.preventDefault();

			const pageUrl = window.location.href;

			if (navigator.clipboard && window.isSecureContext) {
				navigator.clipboard.writeText(pageUrl).then(function() {
					alert("링크가 복사되었습니다.");
				}).catch(function() {
					copyWithFallback(pageUrl);
				});
			} else {
				copyWithFallback(pageUrl);
			}
		});

		function copyWithFallback(text) {
			const $temp = $("<input>").val(text).appendTo("body");
			$temp.trigger("select");

			try {
				document.execCommand("copy");
				alert("링크가 복사되었습니다.");
			} catch (err) {
				alert("복사에 실패했습니다. 링크를 직접 복사해 주세요.\n" + text);
			}

			$temp.remove();
		}

		function syncOptionPriceLabels() {
			const itemPrice = parseInt($("input#it_price").val(), 10);
			const safeItemPrice = isNaN(itemPrice) ? 0 : itemPrice;

			$("#sit_sel_option li").each(function() {
				const $row = $(this);
				const $legacyPrice = $row.find(".sit_opt_prc").first();
				if (!$legacyPrice.length) return;

				$legacyPrice.addClass("sound_only");

				let $customPrice = $row.find(".opt_prc_custom").first();
				if (!$customPrice.length) {
					$legacyPrice.after('<span class="opt_prc_custom whitespace-nowrap text-sm font-semibold text-gray-900">+0원</span>');
					$customPrice = $row.find(".opt_prc_custom").first();
				}

				const qty = parseInt($row.find("input[name^=ct_qty]").val(), 10);
				const ioType = String($row.find("input[name^=io_type]").val());
				const ioPrice = parseInt($row.find("input.io_price").val(), 10);

				const safeQty = isNaN(qty) || qty < 1 ? 1 : qty;
				const safeIoPrice = isNaN(ioPrice) ? 0 : ioPrice;

				let unitPrice = 0;
				if (ioType === "0") {
					unitPrice = safeItemPrice + safeIoPrice;
				} else {
					unitPrice = safeIoPrice;
				}

				let extraPrice = unitPrice * (safeQty - 1);
				if (extraPrice < 0) {
					extraPrice = 0;
				}

				const formattedPrice = "+" + number_format(String(extraPrice)) + "원";
				$legacyPrice.text(formattedPrice);
				$customPrice.text(formattedPrice);
			});
		}

		$("#sit_tot_price").on("price_calculate", function() {
			syncOptionPriceLabels();
		});

		$("#sit_sel_option").on("add_sit_sel_option", function() {
			syncOptionPriceLabels();
		});

		syncOptionPriceLabels();
	});

	function fsubmit_check(f) {
		// 판매가격이 0 보다 작다면
		if (document.getElementById("it_price").value < 0) {
			alert("전화로 문의해 주시면 감사하겠습니다.");
			return false;
		}

		if ($(".sit_opt_list").length < 1) {
			alert("상품의 선택옵션을 선택해 주십시오.");
			return false;
		}

		let val;
		let io_type;
		let result = true;
		let sum_qty = 0;
		const min_qty = parseInt(<?php echo $it['it_buy_min_qty']; ?>);
		const max_qty = parseInt(<?php echo $it['it_buy_max_qty']; ?>);
		const $el_type = $("input[name^=io_type]");

		$("input[name^=ct_qty]").each(function(index) {
			val = $(this).val();

			if (val.length < 1) {
				alert("수량을 입력해 주십시오.");
				result = false;
				return false;
			}

			if (val.replace(/[0-9]/g, "").length > 0) {
				alert("수량은 숫자로 입력해 주십시오.");
				result = false;
				return false;
			}

			if (parseInt(val.replace(/[^0-9]/g, "")) < 1) {
				alert("수량은 1이상 입력해 주십시오.");
				result = false;
				return false;
			}

			io_type = $el_type.eq(index).val();
			if (io_type == "0")
				sum_qty += parseInt(val);
		});

		if (!result) {
			return false;
		}

		if (min_qty > 0 && sum_qty < min_qty) {
			alert("선택옵션 개수 총합 " + number_format(String(min_qty)) + "개 이상 주문해 주십시오.");
			return false;
		}

		if (max_qty > 0 && sum_qty > max_qty) {
			alert("선택옵션 개수 총합 " + number_format(String(max_qty)) + "개 이하로 주문해 주십시오.");
			return false;
		}

		return true;
	}

	// 바로구매, 장바구니 폼 전송
	function fitem_submit(f) {
		f.action = "<?php echo $action_url; ?>";
		f.target = "";

		if (document.pressed == "장바구니") {
			f.sw_direct.value = 0;
		} else { // 바로구매
			f.sw_direct.value = 1;
		}

		// 판매가격이 0 보다 작다면
		if (document.getElementById("it_price").value < 0) {
			alert("전화로 문의해 주시면 감사하겠습니다.");
			return false;
		}

		if ($(".sit_opt_list").length < 1) {
			alert("상품의 선택옵션을 선택해 주십시오.");
			return false;
		}

		let val;
		let io_type;
		let result = true;
		let sum_qty = 0;
		const min_qty = parseInt(<?php echo $it['it_buy_min_qty']; ?>);
		const max_qty = parseInt(<?php echo $it['it_buy_max_qty']; ?>);
		const $el_type = $("input[name^=io_type]");

		$("input[name^=ct_qty]").each(function(index) {
			val = $(this).val();

			if (val.length < 1) {
				alert("수량을 입력해 주십시오.");
				result = false;
				return false;
			}

			if (val.replace(/[0-9]/g, "").length > 0) {
				alert("수량은 숫자로 입력해 주십시오.");
				result = false;
				return false;
			}

			if (parseInt(val.replace(/[^0-9]/g, "")) < 1) {
				alert("수량은 1이상 입력해 주십시오.");
				result = false;
				return false;
			}

			io_type = $el_type.eq(index).val();
			if (io_type == "0")
				sum_qty += parseInt(val);
		});

		if (!result) {
			return false;
		}

		if (min_qty > 0 && sum_qty < min_qty) {
			alert("선택옵션 개수 총합 " + number_format(String(min_qty)) + "개 이상 주문해 주십시오.");
			return false;
		}

		if (max_qty > 0 && sum_qty > max_qty) {
			alert("선택옵션 개수 총합 " + number_format(String(max_qty)) + "개 이하로 주문해 주십시오.");
			return false;
		}

		return true;
	}
</script>
<?php /* 2017 리뉴얼한 테마 적용 스크립트입니다. 기존 스크립트를 오버라이드 합니다. */ ?>
<script src="<?php echo G5_JS_URL; ?>/shop.override.js"></script>
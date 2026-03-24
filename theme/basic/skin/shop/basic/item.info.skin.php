<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_SHOP_CSS_URL . '/style.css">', 0);
?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<?php if (/*$default['de_rel_list_use']*/false) { ?>
	<!-- 관련상품 시작 { -->
	<section id="sit_rel">
		<h2>관련상품</h2>
		<?php
		$rel_skin_file = $skin_dir . '/' . $default['de_rel_list_skin'];
		if (!is_file($rel_skin_file))
			$rel_skin_file = G5_SHOP_SKIN_PATH . '/' . $default['de_rel_list_skin'];

		$sql = " select b.* from {$g5['g5_shop_item_relation_table']} a left join {$g5['g5_shop_item_table']} b on (a.it_id2=b.it_id) where a.it_id = '{$it['it_id']}' and b.it_use='1' ";
		$list = new item_list($rel_skin_file, $default['de_rel_list_mod'], 0, $default['de_rel_img_width'], $default['de_rel_img_height']);
		$list->set_query($sql);
		echo $list->run();
		?>
	</section>
	<!-- } 관련상품 끝 -->
<?php } ?>

<section id="sit_info">
	<div id="sit_tab">
		<ul class="tab_tit">
			<li><button type="button" id="btn_sit_inf" rel="#sit_inf" class="selected">상품설명</button></li>
			<li><button type="button" id="btn_sit_dvex" rel="#sit_dex">상세정보</button></li>
			<li><button type="button" id="btn_sit_use" rel="#sit_use">리뷰 <span class="item_use_count"><?php echo $item_use_count; ?></span></button></li>
			<li><button type="button" id="btn_sit_qa" rel="#sit_qa">문의 <span class="item_qa_count"><?php echo $item_qa_count; ?></span></button></li>
		</ul>
		<ul class="tab_con">

			<!-- 상품 정보 시작 { -->
			<li id="sit_inf">
				<h2 class="contents_tit"><span>상품 정보</span></h2>

				<?php if ($it['it_explan']) { // 상품 상세설명 
				?>
					<h3>상품 상세설명</h3>
					<div id="sit_inf_explan">
						<?php echo conv_content($it['it_explan'], 1); ?>
					</div>
				<?php } ?>

			</li>
			<!-- 리뷰 시작 { -->
			<li id="sit_use">
				<h2>리뷰</h2>
				<div id="itemuse"><?php include_once(G5_SHOP_PATH . '/itemuse.php'); ?></div>
			</li>
			<!-- } 리뷰 끝 -->

			<!-- 상품문의 시작 { -->
			<li id="sit_qa">
				<h2>상품문의</h2>
				<div id="itemqa"><?php include_once(G5_SHOP_PATH . '/itemqa.php'); ?></div>
			</li>
			<!-- } 상품문의 끝 -->

			<!-- 상세정보 시작 { -->
			<li id="sit_dex">

				<?php
				// 상품 필수 정보
				if ($it['it_info_value']) {
					$info_data = unserialize(stripslashes($it['it_info_value']));
					if (is_array($info_data)) {
						$gubun = $it['it_info_gubun'];
						$info_array = $item_info[$gubun]['article'];
				?>
						<button
							type="button"
							id="sit_inf_required_toggle"
							class="flex w-full items-center justify-between border-b border-gray-300 py-4 text-left"
							aria-expanded="true"
							aria-controls="sit_inf_open">
							<span class="text-lg font-bold text-gray-900">상품필수정보</span>
							<span class="inline-flex items-center text-gray-700" aria-hidden="true">
								<svg id="sit_inf_required_icon_down" class="hidden" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="m6 9 6 6 6-6" />
								</svg>
								<svg id="sit_inf_required_icon_up" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="m18 15-6-6-6 6" />
								</svg>
							</span>
						</button>
						<div id="sit_inf_open" class="space-y-2">
							<?php
							foreach ($info_data as $key => $val) {
								$ii_title = $info_array[$key][0];
								$ii_value = $val;
							?>
								<div class="grid grid-cols-[72px_1fr] gap-x-4 py-4 border-b border-gray-200">
									<div class="text-sm text-gray-500"><?php echo $ii_title; ?></div>
									<div class="text-sm font-medium text-gray-900"><?php echo $ii_value; ?></div>
								</div>
							<?php } ?>
							<div class="flex items-center justify-between gap-2 px-4 py-2 rounded bg-red-50">
								<div class="flex items-center gap-2">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info-icon lucide-info">
										<circle cx="12" cy="12" r="10" />
										<path d="M12 16v-4" />
										<path d="M12 8h.01" />
									</svg>
									<span class="whitespace-nowrap text-sm">상품 정보에 문제가 있나요?</span>
								</div>
								<button class="flex items-center gap-1 text-sm text-gray-400">
									신고하기
									<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right">
										<path d="m9 18 6-6-6-6" />
									</svg>
								</button>
							</div>
						</div>
				<?php
					}
				}
				?>

				<?php if ($default['de_baesong_content']) { // 배송정보 내용이 있다면 
				?>
					<!-- 배송 시작 { -->
					<button
						type="button"
						id="sit_ship_toggle"
						class="mt-6 flex w-full items-center justify-between border-b border-gray-300 py-4 text-left"
						aria-expanded="true"
						aria-controls="sit_ship_open">
						<span class="text-lg font-bold text-gray-900">배송안내</span>
						<span class="inline-flex items-center text-gray-700" aria-hidden="true">
							<svg id="sit_ship_icon_down" class="hidden" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="m6 9 6 6 6-6" />
							</svg>
							<svg id="sit_ship_icon_up" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="m18 15-6-6-6 6" />
							</svg>
						</span>
					</button>
					<div id="sit_ship_open" class="mt-4 border-t border-gray-200">
						<div id="sit_dvr">
							<?php echo conv_content($default['de_baesong_content'], 1); ?>
						</div>
					</div>
					<!-- } 배송 끝 -->
				<?php } ?>

				<?php if ($default['de_change_content']) { // 교환/반품 내용이 있다면 
				?>
					<!-- 교환 시작 { -->
					<button
						type="button"
						id="sit_change_toggle"
						class="mt-6 flex w-full items-center justify-between border-b border-gray-300 py-4 text-left"
						aria-expanded="true"
						aria-controls="sit_change_open">
						<span class="text-lg font-bold text-gray-900">교환/반품안내</span>
						<span class="inline-flex items-center text-gray-700" aria-hidden="true">
							<svg id="sit_change_icon_down" class="hidden" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="m6 9 6 6 6-6" />
							</svg>
							<svg id="sit_change_icon_up" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<path d="m18 15-6-6-6 6" />
							</svg>
						</span>
					</button>
					<div id="sit_change_open">
						<div id="sit_ex">
							<?php echo conv_content($default['de_change_content'], 1); ?>
						</div>
					</div>
					<!-- } 교환 끝 -->
				<?php } ?>

			</li>
			<!-- } 상세정보  끝 -->
		</ul>
	</div>
	<script>
		$(function() {
			$(".tab_con>li").hide();
			$(".tab_con>li:first").show();
			$(".tab_tit li button").click(function() {
				$(".tab_tit li button").removeClass("selected");
				$(this).addClass("selected");
				$(".tab_con>li").hide();
				$($(this).attr("rel")).show();
			});
		});
	</script>
	<script>
		(function() {
			function bindAccordion(toggleId, panelId, downIconId, upIconId) {
				var toggleButton = document.getElementById(toggleId);
				var panel = document.getElementById(panelId);
				var iconDown = document.getElementById(downIconId);
				var iconUp = document.getElementById(upIconId);

				if (!toggleButton || !panel || !iconDown || !iconUp) {
					return;
				}

				toggleButton.addEventListener("click", function() {
					var isExpanded = toggleButton.getAttribute("aria-expanded") === "true";
					toggleButton.setAttribute("aria-expanded", isExpanded ? "false" : "true");
					panel.classList.toggle("hidden", isExpanded);
					iconDown.classList.toggle("hidden", !isExpanded);
					iconUp.classList.toggle("hidden", isExpanded);
				});
			}

			function initAccordions() {
				bindAccordion("sit_inf_required_toggle", "sit_inf_open", "sit_inf_required_icon_down", "sit_inf_required_icon_up");
				bindAccordion("sit_ship_toggle", "sit_ship_open", "sit_ship_icon_down", "sit_ship_icon_up");
				bindAccordion("sit_change_toggle", "sit_change_open", "sit_change_icon_down", "sit_change_icon_up");
			}

			if (document.readyState === "loading") {
				document.addEventListener("DOMContentLoaded", initAccordions);
			} else {
				initAccordions();
			}
		})();
	</script>
	<div id="sit_buy" class="fix" style="display:none !important;" aria-hidden="true">
		<div class="sit_buy_inner">
			<?php if ($option_item) {    // 선택옵션이 있다면 
			?>
				<!-- 선택옵션 시작 { -->
				<section class="sit_side_option">
					<h3>선택옵션</h3>
					<?php // 선택옵션
					echo str_replace(array('class="get_item_options"', 'id="it_option_', 'class="it_option"'), array('class="get_side_item_options"', 'id="it_side_option_', 'class="it_side_option"'), $option_item);
					?>
				</section>
				<!-- } 선택옵션 끝 -->
			<?php } // end if
			?>

			<?php if ($supply_item) {    // 추가옵션이 있다면 
			?>
				<!-- 추가옵션 시작 { -->
				<section class="sit_side_option">
					<h3>추가옵션</h3>
					<?php // 추가옵션
					echo str_replace(array('id="it_supply_', 'class="it_supply"'), array('id="it_side_supply_', 'class="it_side_supply"'), $supply_item);
					?>
				</section>
				<!-- } 추가옵션 끝 -->
			<?php } // end if
			?>

			<?php if ($is_orderable) { ?>
				<!-- 선택된 옵션 시작 { -->
				<section class="sit_sel_option">
					<h3>선택된 옵션</h3>
					<ul class="sit_opt_added">
						<?php if (!$option_item) { ?>
							<li>
								<div class="opt_name">
									<span class="sit_opt_subj"><?php echo $it['it_name']; ?></span>
								</div>
								<div class="opt_count">
									<label for="ct_qty_<?php echo $i; ?>" class="sound_only">수량</label>
									<button type="button" class="sit_qty_minus"><i class="fa fa-minus" aria-hidden="true"></i><span class="sound_only">감소</span></button>
									<input type="text" name="ct_copy_qty[<?php echo $it_id; ?>][]" value="<?php echo $it['it_buy_min_qty']; ?>" id="ct_qty_<?php echo $i; ?>" class="num_input" size="5">
									<button type="button" class="sit_qty_plus"><i class="fa fa-plus" aria-hidden="true"></i><span class="sound_only">증가</span></button>
									<span class="sit_opt_prc">+0원</span>
								</div>
							</li>
						<?php } ?>
					</ul>
				</section>
				<!-- } 선택된 옵션 끝 -->

				<div class="sum_section">
					<div class="sit_tot_price"></div>

					<div class="sit_order_btn">
						<button type="submit" onclick="document.pressed=this.value;" value="장바구니" class="sit_btn_cart">장바구니</button>
						<button type="submit" onclick="document.pressed=this.value;" value="바로구매" class="sit_btn_buy">바로구매</button>
					</div>
				</div>
			<?php } ?>

		</div>
	</div>
</section>

<script>
	jQuery(function($) {
		var change_name = "ct_copy_qty";

		$(document).on("select_it_option_change", "select.it_option", function(e, $othis) {
			var value = $othis.val(),
				change_id = $othis.attr("id").replace("it_option_", "it_side_option_");

			if ($("#" + change_id).length) {
				$("#" + change_id).val(value).attr("selected", "selected");
			}
		});

		$(document).on("select_it_option_post", "select.it_option", function(e, $othis, idx, sel_count, data) {
			var value = $othis.val(),
				change_id = $othis.attr("id").replace("it_option_", "it_side_option_");

			$("select.it_side_option").eq(idx + 1).empty().html(data).attr("disabled", false);

			// select의 옵션이 변경됐을 경우 하위 옵션 disabled
			if ((idx + 1) < sel_count) {
				$("select.it_side_option:gt(" + (idx + 1) + ")").val("").attr("disabled", true);
			}
		});

		$(document).on("add_sit_sel_option", "#sit_sel_option", function(e, opt) {

			opt = opt.replace('name="ct_qty[', 'name="' + change_name + '[');

			var $opt = $(opt);
			$opt.removeClass("sit_opt_list");
			$("input[type=hidden]", $opt).remove();

			$(".sit_sel_option .sit_opt_added").append($opt);

		});

		$(document).on("price_calculate", "#sit_tot_price", function(e, total) {

			$(".sum_section .sit_tot_price").empty().html("<span>총 금액 </span><strong>" + number_format(String(total)) + "</strong> 원");

		});

		$(".sit_side_option").on("change", "select.it_side_option", function(e) {
			var idx = $("select.it_side_option").index($(this)),
				value = $(this).val();

			if (value) {
				if (typeof(option_add) != "undefined") {
					option_add = true;
				}

				$("select.it_option").eq(idx).val(value).attr("selected", "selected").trigger("change");
			}
		});

		$(".sit_side_option").on("change", "select.it_side_supply", function(e) {
			var value = $(this).val();

			if (value) {
				if (typeof(supply_add) != "undefined") {
					supply_add = true;
				}

				$("select.it_supply").val(value).attr("selected", "selected").trigger("change");
			}
		});

		$(".sit_opt_added").on("click", "button", function(e) {
			e.preventDefault();

			var $this = $(this),
				mode = $this.text(),
				$sit_sel_el = $("#sit_sel_option"),
				li_parent_index = $this.closest('li').index();

			if (!$sit_sel_el.length) {
				alert("el 에러");
				return false;
			}

			switch (mode) {
				case "증가":
					$sit_sel_el.find("li").eq(li_parent_index).find(".sit_qty_plus").trigger("click");
					break;
				case "감소":
					$sit_sel_el.find("li").eq(li_parent_index).find(".sit_qty_minus").trigger("click");
					break;
				case "삭제":
					$sit_sel_el.find("li").eq(li_parent_index).find(".sit_opt_del").trigger("click");
					break;
			}

		});

		$(document).on("sit_sel_option_success", "#sit_sel_option li button", function(e, $othis, mode, this_qty) {
			var ori_index = $othis.closest('li').index();

			switch (mode) {
				case "증가":
				case "감소":
					$(".sit_opt_added li").eq(ori_index).find("input[name^=ct_copy_qty]").val(this_qty);
					break;
				case "삭제":
					$(".sit_opt_added li").eq(ori_index).remove();
					break;
			}
		});

		$(document).on("change_option_qty", "input[name^=ct_qty]", function(e, $othis, val, force_val) {
			var $this = $(this),
				ori_index = $othis.closest('li').index(),
				this_val = force_val ? force_val : val;

			$(".sit_opt_added").find("li").eq(ori_index).find("input[name^=" + change_name + "]").val(this_val);
		});

		$(".sit_opt_added").on("keyup paste", "input[name^=" + change_name + "]", function(e) {
			var $this = $(this),
				val = $this.val(),
				this_index = $("input[name^=" + change_name + "]").index(this);

			$("input[name^=ct_qty]").eq(this_index).val(val).trigger("keyup");
		});

		$(".sit_order_btn").on("click", "button", function(e) {
			e.preventDefault();

			var $this = $(this);

			if ($this.hasClass("sit_btn_cart")) {
				$("#sit_ov_btn .sit_btn_cart").trigger("click");
			} else if ($this.hasClass("sit_btn_buy")) {
				$("#sit_ov_btn .sit_btn_buy").trigger("click");
			}
		});

		if (window.location.href.split("#").length > 1) {
			let id = window.location.href.split("#")[1];
			$("#btn_" + id).trigger("click");
		};
	});
</script>
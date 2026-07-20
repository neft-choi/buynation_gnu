<?php
if (!defined("_GNUBOARD_"))
	exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_SHOP_SKIN_URL . '/style.css">', 0);
?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<!-- 모바일 헤더 -->
<div class="flex pc:hidden items-center justify-between p-4">
	<button type="button" class="inline-flex items-center justify-center text-zinc-700" aria-label="뒤로가기"
		onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '<?php echo G5_URL ?>'; }">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
			stroke-linecap="round" stroke-linejoin="round"
			class="lucide lucide-chevron-left-icon lucide-chevron-left w-6 h-6">
			<path d="m15 18-6-6 6-6" />
		</svg>
	</button>
	<h1 class="text-lg font-semibold text-zinc-900 leading-0">상품 리뷰</h1>
	<div class="w-6 h-6" aria-hidden="true"></div>
</div>

<section class="min-w-0 flex-1">

	<!-- PC 너비 타이틀 -->
	<div class="hidden pc:block px-4">
		<h2 class="text-2xl font-bold pb-4 border-b-2 border-gray-900">
			<span>상품 리뷰</span>
		</h2>
	</div>

	<!-- 전체 상품 사용후기 목록 시작 { -->
	<section id="sps_page" class="mx-auto w-full max-w-full p-4">
		<form method="get" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
			<div id="sps_sch_custom" class="flex flex-col gap-1">
				<label for="sfl" class="sound_only">검색항목 필수</label>
				<div class="relative w-full">
					<select name="sfl" id="sfl"
						class="w-full h-10 appearance-none px-2 border border-zinc-300 rounded focus-visible:outline-0"
						required>
						<option value="">선택</option>
						<option value="b.it_name" <?php echo get_selected($sfl, "b.it_name"); ?>>상품명</option>
						<option value="a.it_id" <?php echo get_selected($sfl, "a.it_id"); ?>>상품코드</option>
						<option value="a.is_subject" <?php echo get_selected($sfl, "a.is_subject"); ?>>후기제목</option>
						<option value="a.is_content" <?php echo get_selected($sfl, "a.is_content"); ?>>후기내용</option>
						<option value="a.is_name" <?php echo get_selected($sfl, "a.is_name"); ?>>작성자명</option>
						<option value="a.mb_id" <?php echo get_selected($sfl, "a.mb_id"); ?>>작성자아이디</option>
					</select>

					<span class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2" aria-hidden="true">
						<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
							class="lucide lucide-chevron-down-icon lucide-chevron-down">
							<path d="m6 9 6 6 6-6" />
						</svg>
					</span>
				</div>

				<div class="relative w-full">
					<label for="stx" class="sound_only">검색어<span class="sound_only"> 필수</span></label>
					<input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" required
						class="h-10 w-full rounded border border-zinc-300 bg-white pl-3 pr-10 text-sm text-zinc-900 focus-visible:outline-0">
					<button type="submit" value="검색"
						class="absolute right-0 top-0 inline-flex h-10 w-10 items-center justify-center text-gray-900">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
							stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
							class="lucide lucide-search-icon lucide-search w-5 h-5">
							<path d="m21 21-4.34-4.34" />
							<circle cx="11" cy="11" r="8" />
						</svg>
						<span class="sound_only">검색</span>
					</button>
				</div>
				<a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>"
					class="inline-flex h-10 w-fit items-center justify-center rounded border border-zinc-300 px-2 text-sm font-semibold bg-gray-900 text-white">전체보기</a>
			</div>
		</form>

		<div id="sps" class="my-4">
			<!-- <p><?php echo $config['cf_title']; ?> 전체 사용후기 목록입니다.</p> -->
			<?php
			$thumbnail_width = 500;

			for ($i = 0; $row = sql_fetch_array($result); $i++) {
				$num = $total_count - ($page - 1) * $rows - $i;
				$star = get_star($row['is_score']);

				$is_content = get_view_thumbnail(conv_content($row['is_content'], 1), $thumbnail_width);

				$row2 = get_shop_item($row['it_id'], true);
				$it_href = shop_item_url($row['it_id']);
				$thumb_html = get_it_image($row['it_id'], 80, 102);
				$review_thumb_html = get_itemuse_thumb($row['is_content'], 60, 60);

				if ($i == 0)
					echo '<ol class="divide-y divide-[var(--color-semantic-border-solid-subtle)]">';
				?>
				<li class="flex items-start gap-3 py-3">
					<div class="sps_img">
						<div class="sps_img_inner">
							<a href="<?php echo $it_href; ?>" class="inline-block overflow-hidden rounded bg-zinc-100 w-20">
								<?php echo $thumb_html ? $thumb_html : '<div class="h-full w-full aspect-[80/102]"></div>'; ?>
							</a>
							<button class="prd_detail !hidden"
								data-url="<?php echo G5_SHOP_URL . '/largeimage.php?it_id=' . $row['it_id']; ?>"><i
									class="fa fa-camera" aria-hidden="true"></i><span class="sound_only">상품
									이미지보기</span></button>
						</div>
					</div>

					<div class="sps_section flex flex-1 min-w-0 flex-col gap-1">
						<p class="leading-none">
							<span class="sound_only">평가점수</span>
							<img src="<?php echo G5_URL; ?>/shop/img/s_star<?php echo $star; ?>.png"
								alt="별<?php echo $star; ?>개" width="80" class="h-4 w-auto">
						</p>

						<p class="sps_pd_name text-xs text-[var(--color-semantic-label-solid-subtle)] pt-1">
							<?php echo get_text($row2['it_name']); ?>
						</p>

						<p class="sps_rv_tit text-[15px] font-medium text-[var(--color-semantic-label-solid-default)]">
							<?php echo get_text($row['is_subject']); ?>
						</p>

						<div id="sps_content_<?php echo $i; ?>" class="review_content hidden my-4">
							<?php echo $is_content; ?>
						</div>

						<?php if (!empty($review_thumb_html)) { ?>
							<p class="sps_rv_thum mt-1 w-15 overflow-hidden rounded bg-zinc-100">
								<?php echo $review_thumb_html; ?>
							</p>
						<?php } ?>

						<div class="sps_con_btn flex items-center justify-between gap-3">
							<time datetime="<?php echo substr($row['is_time'], 0, 10); ?>"
								class="text-sm text-[var(--color-semantic-label-solid-subtle)]">
								<?php echo str_replace('-', '.', substr($row['is_time'], 0, 10)); ?>
							</time>

							<button type="button"
								class="sps_con_<?php echo $i; ?> review_detail inline-flex items-center border border-[var(--color-semantic-border-normal-default)] rounded text-sm font-normal text-[var(--color-semantic-label-solid-default)] px-2 py-1"
								aria-expanded="false" aria-controls="sps_content_<?php echo $i; ?>">
								내용 보기
							</button>

						</div>
					</div>
				</li>
			<?php }
			if ($i > 0)
				echo '</ol>';
			if ($i == 0)
				echo '<p id="sps_empty" class="text-center py-50">자료가 없습니다.</p>';
			?>
		</div>

		<?php echo get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
	</section>
</section>

<script>
	jQuery(function ($) {
		$(".review_detail").on("click", function () {
			const $button = $(this);
			const contentId = $button.attr("aria-controls");
			const $content = $("#" + contentId);
			const isExpanded = $button.attr("aria-expanded") === "true";

			$content.toggleClass("hidden", isExpanded);

			// if (isExpanded) {
			// 	$content.stop(true, true).slideUp(200, function () {
			// 		$content.addClass("hidden");
			// 	});
			// } else {
			// 	$content.removeClass("hidden").hide().stop(true, true).slideDown(200);
			// }

			$button.attr("aria-expanded", isExpanded ? "false" : "true");
			$button.text(isExpanded ? "내용 보기" : "내용 닫기");
		});

		// 상품이미지 크게보기
		$(".prd_detail").click(function () {
			var url = $(this).attr("data-url");
			var top = 10;
			var left = 10;
			var opt = 'scrollbars=yes,top=' + top + ',left=' + left;
			popup_window(url, "largeimage", opt);

			return false;
		});
	});

	// 반응형 쇼핑몰 헤더 숨기기
	syncWithPcBreakpoint(function (isPc) {
		if (isPc) {
			$('#hd').css('display', '');
		} else {
			$('#hd').css('display', 'none');
		}
	});
</script>
<!-- } 전체 상품 사용후기 목록 끝 -->
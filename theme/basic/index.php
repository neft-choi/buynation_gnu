<?php
if (!defined('_INDEX_')) define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH . '/index.php');
    return;
}

if (G5_COMMUNITY_USE === false) {
    include_once(G5_THEME_SHOP_PATH . '/index.php');
    return;
}

include_once(G5_THEME_PATH . '/head.php');
?>

<main class="space-y-6">
    <section aria-label="메인 배너" class="px-4">
        <?php echo display_banner('커뮤니티', 'mainbanner.10.skin.php'); ?>
        <!-- <div id="hero-carousel" class="relative overflow-hidden rounded-xl">
            <div class="hero_owl owl-carousel">
                <a href="#" class="relative block h-[350px] overflow-hidden bg-gradient-to-br from-amber-100 via-orange-100 to-yellow-200 p-4">
                    <img
                        src="https://placehold.co/350"
                        alt="메인 배너 1"
                        class="absolute inset-0 h-full w-full object-cover">

                    <div class="relative z-10 flex w-60 h-72 flex-col justify-end">
                        <p class="text-xl font-bold leading-tight text-gray-900">내 취향에 딱 맞는 모임을 발견해보세요.</p>
                        <p class="mt-2 text-sm text-gray-700">나의 관심사로 연결되는 모임 리스트</p>
                    </div>
                </a>

                <a href="#" class="relative block h-[350px] overflow-hidden bg-gradient-to-br from-sky-100 via-cyan-100 to-emerald-100 p-4">
                    <div class="relative z-10 flex w-60 h-72 flex-col justify-end">
                        <p class="text-xl font-bold leading-tight text-gray-900">새로운 사람과 취미를 함께하세요.</p>
                        <p class="mt-2 text-sm text-gray-700">지금 참여 가능한 오프라인 모임 안내</p>
                    </div>
                </a>

                <a href="#" class="relative block h-[350px] overflow-hidden bg-gradient-to-br from-rose-100 via-orange-100 to-amber-100 p-4">
                    <div class="relative z-10 flex w-60 h-72 flex-col justify-end">
                        <p class="text-xl font-bold leading-tight text-gray-900">이번 주 인기 바이블을 확인해보세요.</p>
                        <p class="mt-2 text-sm text-gray-700">실시간 참여 데이터를 기반으로 추천해요</p>
                    </div>
                </a>
            </div>

            <div class="absolute bottom-4 right-4 inline-flex items-center p-1 rounded-full bg-black/70 text-xs text-white z-10">
                <div class="px-2 py-1">
                    <span id="hero-current">1</span>
                    <span class="mx-1">/</span>
                    <span id="hero-total">3</span>
                </div>
                <button type="button" id="hero-next" class="inline-flex items-center justify-center" aria-label="다음 배너">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </button>
            </div>
        </div> -->
    </section>
    <script>
        $(function() {
            const $heroOwl = $(".hero_owl");
            const $pickOwl = $(".pick_owl");

            // if (!$heroOwl.length || !$.fn.owlCarousel) {
            //     return;
            // }

            function updateHeroCounter(event) {
                if (!event || !event.item || !event.relatedTarget) {
                    return;
                }

                const total = event.item.count;
                const current = event.relatedTarget.relative(event.item.index) + 1;

                $("#hero-total").text(total);
                $("#hero-current").text(current);
            }

            $heroOwl.on("initialized.owl.carousel changed.owl.carousel", updateHeroCounter);

            $heroOwl.owlCarousel({
                items: 1,
                loop: true,
                nav: false,
                dots: false,
                autoplay: true,
                autoplayTimeout: 4000,
                autoplayHoverPause: true,
                smartSpeed: 450,
                mouseDrag: true,
                touchDrag: true
            });

            $("#hero-next").on("click", function(e) {
                e.preventDefault();
                $heroOwl.trigger("next.owl.carousel");
            });

            if ($pickOwl.length) {
                $pickOwl.owlCarousel({
                    loop: false,
                    nav: false,
                    dots: false,
                    margin: 12,
                    autoWidth: true,
                    mouseDrag: true,
                    touchDrag: true,
                    smartSpeed: 400
                });
            }
        });
    </script>

    <?php include G5_THEME_PATH . '/_quick_nav.php'; ?>

    <!-- <section aria-label="프로모션 배너" class="px-4">
        <a href="#" class="flex items-center justify-between w-full h-20 rounded bg-gray-900 px-4 py-2 !text-white">
            <div class="flex items-center gap-4">
                <span class="inline-flex h-9 w-14 items-center justify-center rounded bg-amber-100/20 text-amber-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" class="lucide lucide-heart">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5A4.5 4.5 0 0 1 6.5 4 5 5 0 0 1 12 7a5 5 0 0 1 5.5-3A4.5 4.5 0 0 1 22 8.5c0 3.78-3.4 6.86-8.55 11.54z" />
                    </svg>
                </span>
                <div class="w-40">
                    <p class="text-sm font-semibold">바이클과 함께하는 온오프라인 커뮤니티</p>
                </div>
            </div>

            <div class="flex items-center gap-1">
                <span class="text-sm">상세보기</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </div>
        </a>
    </section> -->

    <?php
    // [추천 바이클] 섹션 데이터 준비
    // - 현재는 그룹(gr_id) 제한 없이 전체 게시판을 조회
    // - 로그인 회원 레벨로 볼 수 있는 게시판만 노출
    // - mobile 전용 게시판 제외
    // - 추천과 관련된 DB 값 추가 시 수정 필요

    $sql_pick_board = " select b.bo_table, b.bo_subject, b.bo_content_head, b.gr_id, g.gr_subject ,b.bo_content_tail
                        from {$g5['board_table']} b
                        left join {$g5['group_table']} g on b.gr_id = g.gr_id
                        where b.bo_list_level <= '{$member['mb_level']}'
                        and b.bo_device <> 'mobile' 
                        AND b.gr_id !='community'";
    if (!$is_admin) {
        $sql_pick_board .= " and b.bo_use_cert = '' ";
    }
    $sql_pick_board .= " order by b.bo_order, b.gr_id, b.bo_table ";

    // 결과 리소스
    $result_pick_board = sql_query($sql_pick_board);
    ?>

    <section aria-label="놓치면 아쉬운 추천 바이클" class="bg-gray-100 py-8 space-y-4">
        <div class="px-4">
            <h3 class="!text-base font-bold text-gray-900">놓치면 아쉬운 추천 바이클</h3>
            <p class="mt-1 text-sm text-gray-500">꼼꼼하게 선정한 이달의 바이클을 만나보세요</p>
        </div>

        <div class="px-4">
            <div class="pick_owl owl-carousel">
                <?php
                // 조회된 게시판 수만큼 카드 생성
                // 각 카드는 board.php?bo_table=... 상세로 이동
                for ($p = 0; $pick_row = sql_fetch_array($result_pick_board); $p++) { ?>
                    <?php
                    // 게시판 데이터
                    $pick_card = board_card($pick_row);
                    $pick_row_image = $pick_card['thumbnail'];
                    $pick_row_title = $pick_card['title'];
                    $pick_row_desc = $pick_card['desc'];
                    $pick_row_group = $pick_card['group'];
                    $pick_row_group_badge_class = $pick_card['group_badge_class'];
                    $pick_row_url = G5_BBS_URL . '/board.php?bo_table=' . $pick_row['bo_table'];

                    ?>
                    <div class="w-60">
                        <a href="<?php echo $pick_row_url; ?>" class="block h-71 overflow-hidden rounded-lg border border-gray-100 bg-white">
                            <div class="relative w-full aspect-[2/1] overflow-hidden rounded-t-lg border border-gray-200 bg-gray-100">
                                <?php if ($pick_row_image) { ?>
                                    <img
                                        src="<?php echo $pick_row_image; ?>"
                                        alt="<?php echo $pick_row_title; ?> 썸네일"
                                        class="h-full w-full object-cover"
                                        loading="lazy"
                                        decoding="async">
                                <?php } else { ?>
                                    <div class="flex h-full w-full items-center justify-center">
                                        <p class="text-xs font-medium text-gray-500">사진이 없습니다</p>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="p-4 space-y-2">
                                <p class="text-base font-semibold text-gray-900 line-clamp-2 leading-normal"><?php echo $pick_row_title; ?></p>

                                <p class="text-sm text-gray-500 line-clamp-1 leading-normal">
                                    <?= $pick_row_desc ?>
                                </p>

                                <div class="flex items-center gap-1 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart">
                                        <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                    </svg>
                                    <span class="text-xs">392</span>
                                </div>

                                <span class="rounded px-2 py-1 text-xs <?php echo $pick_row_group_badge_class; ?>"><?php echo $pick_row_group; ?></span>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <?php
    // [오프라인 모임] 섹션 데이터 준비
    // - 현재는 club 그룹 게시판만 전체 조회
    // - 로그인 회원 레벨로 볼 수 있는 게시판만 노출
    // - mobile 전용 게시판 제외
    // - 위치와 관련된 DB 값 추가 시 수정 필요
    $sql_club_board = " select bo_table, bo_subject, bo_content_head, bo_content_tail
                        from {$g5['board_table']}
                        where gr_id = 'club'
                        and bo_list_level <= '{$member['mb_level']}'
                        and bo_device <> 'mobile' ";
    if (!$is_admin) {
        $sql_club_board .= " and bo_use_cert = '' ";
    }
    $sql_club_board .= " order by bo_order, bo_table ";
    $result_club_board = sql_query($sql_club_board);
    ?>

    <section aria-label="오프라인 모임" class="px-4 space-y-4">
        <div>
            <button type="button" class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-4 py-2 text-sm text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-icon lucide-map-pin">
                    <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                    <circle cx="12" cy="10" r="3" />
                </svg>
                <span>경기도 성남시 분당구</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>
        </div>

        <div>
            <h3 class="text-base font-bold text-gray-900">우리 동네 활력 충전소<br>오프라인 모임</h3>
        </div>

        <div class="space-y-6">
            <?php for ($c = 0; $club_row = sql_fetch_array($result_club_board); $c++) { ?>
                <?php
                $club_card = board_card($club_row);
                $club_card_url = G5_BBS_URL . '/board.php?bo_table=' . $club_row['bo_table'];
                $club_card_title = $club_card['title'];
                $club_card_image = $club_card['thumbnail'];
                $club_card_desc = $club_card['desc'];
                ?>

                <a href="<?php echo $club_card_url; ?>" class="block overflow-hidden bg-white space-y-3">
                    <div class="relative w-full aspect-[2/1] border border-gray-200 overflow-hidden rounded bg-gray-100">
                        <?php if ($club_card_image) { ?>
                            <img
                                src="<?php echo $club_card_image; ?>"
                                alt="<?php echo $club_card_title; ?> 썸네일"
                                class="h-full w-full object-cover"
                                loading="lazy"
                                decoding="async">
                        <?php } else { ?>
                            <div class="flex h-full w-full items-center justify-center">
                                <p class="text-xs font-medium text-gray-500">사진이 없습니다</p>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="space-y-1">
                        <p class="text-base font-semibold text-gray-900"><?php echo $club_card_title; ?></p>

                        <p class="text-sm text-gray-500 line-clamp-1"><?php echo $club_card_desc ? $club_card_desc : '설명이 없습니다.'; ?></p>

                        <div class="flex items-center text-xs text-gray-500">
                            <div class="flex items-center gap-2 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart">
                                    <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                                </svg>
                                <span class="text-xs">92</span>
                            </div>
                            <span class="mx-2">|</span>
                            <div class="flex items-center gap-2 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="transparent" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin-icon lucide-map-pin">
                                    <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                                <span>경기 성남시 분당구</span>
                            </div>
                        </div>

                        <span class="rounded px-2 py-1 text-xs bg-emerald-100 text-emerald-600">동호회</span>
                    </div>
                </a>
            <?php } ?>

        </div>

        <!-- <div class="mt-4 text-center">
            <button type="button" class="inline-flex items-center gap-1 rounded-full border border-gray-200 bg-white px-4 py-2 text-xs text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-cw-icon lucide-rotate-cw">
                    <path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8" />
                    <path d="M21 3v5h-5" />
                </svg>
                <span>모임 더보기</span>
            </button>
        </div> -->
    </section>
    <!-- 
    <section aria-label="프로모션 슬라이드 배너" class="mx-4">
        <a href="#" class="flex items-center justify-between rounded-lg bg-rose-100 px-4 py-4">
            <div>
                <p class="text-base font-semibold text-gray-900">올겨울, 아이들의 산타가 되어주세요</p>
                <p class="mt-1 text-xs text-rose-700">판매 수익금 일부는 저소득층 아동의 난방비로 지원됩니다.</p>
            </div>
            <span class="inline-flex h-12 w-12 items-center justify-center rounded bg-white/70 text-rose-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-gift">
                    <rect x="3" y="8" width="18" height="4" rx="1" />
                    <path d="M12 8v13" />
                    <path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7" />
                    <path d="M7.5 8a2.5 2.5 0 1 1 0-5C11 3 12 8 12 8s1-5 4.5-5a2.5 2.5 0 1 1 0 5" />
                </svg>
            </span>
        </a>
    </section> -->

    <?php
    $rank_gr_id = isset($_GET['rank_gr_id']) ? substr(preg_replace('#[^a-z0-9_]#i', '', $_GET['rank_gr_id']), 0, 10) : '';

    $rank_tabs = array(
        array('gr_id' => '', 'gr_subject' => '전체')
    );
    $rank_valid_ids = array('');

    $sql_rank_group = " select gr_id, gr_subject
                        from {$g5['group_table']}
                        WHERE gr_id != 'community'
                        order by gr_order, gr_id ";
    $result_rank_group = sql_query($sql_rank_group);

    for ($rg = 0; $rank_group = sql_fetch_array($result_rank_group); $rg++) {
        $rank_tabs[] = $rank_group;
        $rank_valid_ids[] = $rank_group['gr_id'];
    }

    if (!in_array($rank_gr_id, $rank_valid_ids, true)) {
        $rank_gr_id = '';
    }

    // 현재는 좋아요 집계 전 단계이므로 최근 게시물 시각 기준으로 정렬
    $sql_rank_board = " select b.bo_table, b.bo_subject, b.bo_content_head, b.gr_id ,b.bo_content_tail, g.gr_subject, max(n.bn_datetime) as rank_latest_datetime
                        from {$g5['board_table']} b
                        left join {$g5['group_table']} g on b.gr_id = g.gr_id
                        left join {$g5['board_new_table']} n on b.bo_table = n.bo_table
                        where b.bo_list_level <= '{$member['mb_level']}'
                        and b.bo_device <> 'mobile' 
                        AND b.gr_id != 'community'";
    if (!$is_admin) {
        $sql_rank_board .= " and b.bo_use_cert = '' ";
    }
    $sql_rank_board .= " group by b.bo_table, b.bo_subject, b.bo_content_head, b.bo_content_tail, b.gr_id, g.gr_subject
                         order by rank_latest_datetime desc, b.bo_order, b.bo_table
                         limit 20 ";
    $result_rank_board = sql_query($sql_rank_board);
    ?>

    <section id="rank-section" aria-label="인기 바이클 랭킹" class="mx-4 space-y-2">
        <div>
            <!-- <p class="text-sm text-gray-500">25분 전 업데이트</p> -->
            <h3 class="mt-1 text-base font-bold text-gray-900">인기 바이클 랭킹</h3>
        </div>

        <div class="-mx-4 mb-4 overflow-x-auto px-4 scrollbar-hidden">
            <div class="grid w-max grid-flow-col gap-2" role="tablist" aria-label="인기 바이클 랭킹 카테고리">
                <?php for ($rt = 0; $rt < count($rank_tabs); $rt++) { ?>
                    <?php
                    $rank_tab = $rank_tabs[$rt];
                    $rank_tab_id_suffix = $rank_tab['gr_id'] !== '' ? $rank_tab['gr_id'] : 'all';
                    $rank_tab_is_active = ($rank_tab['gr_id'] === $rank_gr_id || ($rank_tab['gr_id'] === '' && $rank_gr_id === ''));
                    $rank_tab_class = 'rounded-full bg-gray-100 px-4 py-2 text-xs text-gray-600';

                    if ($rank_tab_is_active) {
                        $rank_tab_class = 'rounded-full bg-gray-900 px-4 py-2 text-xs text-white';
                    }
                    ?>
                    <button
                        type="button"
                        id="rank-tab-<?php echo $rank_tab_id_suffix; ?>"
                        class="<?php echo $rank_tab_class; ?>"
                        data-rank-tab="1"
                        data-rank-gr-id="<?php echo $rank_tab['gr_id']; ?>"
                        role="tab"
                        aria-controls="rank-panel"
                        aria-selected="<?php echo $rank_tab_is_active ? 'true' : 'false'; ?>"
                        tabindex="<?php echo $rank_tab_is_active ? '0' : '-1'; ?>">
                        <?php echo $rank_tab['gr_subject']; ?>
                    </button>
                <?php } ?>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const tabs = document.querySelectorAll("[data-rank-tab='1']");
                const cards = document.querySelectorAll("[data-rank-card='1']");
                const emptyState = document.getElementById("rank-empty-state");
                let defaultGrId = "<?php echo $rank_gr_id; ?>";
                const activeClass = "rounded-full bg-gray-900 px-4 py-2 text-xs text-white";
                const inactiveClass = "rounded-full bg-gray-100 px-4 py-2 text-xs text-gray-600";

                if (!tabs.length) {
                    return;
                }

                function setTabActive(tab, isActive) {
                    tab.className = isActive ? activeClass : inactiveClass;
                    tab.setAttribute("aria-selected", isActive ? "true" : "false");
                    tab.setAttribute("tabindex", isActive ? "0" : "-1");
                }

                function filterRank(selectedGrId, updateUrl) {
                    let visibleCount = 0;

                    for (let i = 0; i < cards.length; i++) {
                        const cardGrId = cards[i].getAttribute("data-rank-gr-id") || "";
                        const visible = selectedGrId === "" || selectedGrId === cardGrId;
                        const rankBadge = cards[i].querySelector("[data-rank-no='1']");
                        cards[i].classList.toggle("hidden", !visible);

                        if (visible) {
                            visibleCount++;

                            if (rankBadge) {
                                rankBadge.textContent = visibleCount;
                                rankBadge.classList.remove("bg-amber-300", "text-gray-900", "bg-gray-900", "text-white");

                                if (visibleCount <= 3) {
                                    rankBadge.classList.add("bg-amber-300", "text-gray-900");
                                } else {
                                    rankBadge.classList.add("bg-gray-900", "text-white");
                                }
                            }
                        }
                    }

                    for (let t = 0; t < tabs.length; t++) {
                        const tabGrId = tabs[t].getAttribute("data-rank-gr-id") || "";
                        setTabActive(tabs[t], tabGrId === selectedGrId);
                    }

                    if (emptyState) {
                        emptyState.classList.toggle("hidden", visibleCount > 0);
                    }

                    if (updateUrl && window.history && window.history.replaceState) {
                        let nextUrl = "<?php echo G5_URL; ?>/";
                        if (selectedGrId !== "") {
                            nextUrl += "?rank_gr_id=" + encodeURIComponent(selectedGrId);
                        }
                        window.history.replaceState(null, "", nextUrl + "#rank-section");
                    }
                }

                let isValidDefault = false;
                for (let d = 0; d < tabs.length; d++) {
                    if ((tabs[d].getAttribute("data-rank-gr-id") || "") === defaultGrId) {
                        isValidDefault = true;
                        break;
                    }
                }
                if (!isValidDefault) {
                    defaultGrId = "";
                }

                filterRank(defaultGrId, false);

                for (let j = 0; j < tabs.length; j++) {
                    tabs[j].addEventListener("click", function() {
                        const selectedGrId = this.getAttribute("data-rank-gr-id") || "";
                        filterRank(selectedGrId, true);
                    });

                    tabs[j].addEventListener("keydown", function(e) {
                        let currentIndex = -1;
                        for (let k = 0; k < tabs.length; k++) {
                            if (tabs[k] === this) {
                                currentIndex = k;
                                break;
                            }
                        }
                        if (currentIndex < 0) {
                            return;
                        }

                        let nextIndex = currentIndex;

                        if (e.key === "ArrowRight") {
                            e.preventDefault();
                            nextIndex = (currentIndex + 1) % tabs.length;
                        } else if (e.key === "ArrowLeft") {
                            e.preventDefault();
                            nextIndex = (currentIndex - 1 + tabs.length) % tabs.length;
                        } else if (e.key === "Home") {
                            e.preventDefault();
                            nextIndex = 0;
                        } else if (e.key === "End") {
                            e.preventDefault();
                            nextIndex = tabs.length - 1;
                        } else if (e.key === "Enter" || e.key === " ") {
                            e.preventDefault();
                            const selectedByKey = this.getAttribute("data-rank-gr-id") || "";
                            filterRank(selectedByKey, true);
                            return;
                        } else {
                            return;
                        }

                        tabs[nextIndex].focus();
                        const selectedGrIdByArrow = tabs[nextIndex].getAttribute("data-rank-gr-id") || "";
                        filterRank(selectedGrIdByArrow, true);
                    });
                }
            });
        </script>

        <div id="rank-panel" class="space-y-4" role="tabpanel" aria-label="인기 바이클 랭킹 목록">
            <?php for ($r = 0; $rank_row = sql_fetch_array($result_rank_board); $r++) { ?>
                <?php
                $rank_card = board_card($rank_row);
                $rank_row_url = G5_BBS_URL . '/board.php?bo_table=' . $rank_row['bo_table'];
                $rank_row_title = $rank_card['title'];
                $rank_row_group = $rank_card['group'];
                $rank_row_image = $rank_card['thumbnail'];
                $rank_row_desc = $rank_card['desc'];
                $rank_row_badge_class = $rank_card['group_badge_class'];

                $rank_no = $r + 1;
                $rank_no_class = ($rank_no <= 3) ? 'bg-amber-300 text-gray-900' : 'bg-gray-900 text-white';
                ?>
                <a href="<?php echo $rank_row_url; ?>" class="grid grid-cols-[132px_1fr] gap-4 bg-white" data-rank-card="1" data-rank-gr-id="<?php echo $rank_row['gr_id']; ?>">
                    <div class="relative h-33 overflow-hidden rounded bg-gray-100">
                        <?php if ($rank_row_image) { ?>
                            <img src="<?php echo $rank_row_image; ?>" alt="<?php echo $rank_row_title; ?>" class="absolute inset-0 h-full w-full object-cover">
                        <?php } else { ?>
                            <div class="flex h-full w-full items-center justify-center">
                                <p class="text-xs font-medium text-gray-500">사진이 없습니다</p>
                            </div>
                        <?php } ?>
                        <span data-rank-no="1" class="absolute left-0 top-0 flex h-6 w-6 items-center justify-center rounded-tl rounded-br text-xs font-semibold <?php echo $rank_no_class; ?>"><?php echo $rank_no; ?></span>
                    </div>
                    <div class="space-y-1">
                        <p class="line-clamp-1 text-base font-semibold text-gray-900"><?php echo $rank_row_title; ?></p>
                        <p class="text-sm text-gray-500 line-clamp-1"><?php echo $rank_row_desc ? $rank_row_desc : '게시판 설명이 없습니다.'; ?></p>
                        <div class="flex items-center gap-1 my-2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart">
                                <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                            </svg>
                            <span class="text-xs">0</span>
                        </div>
                        <span class="rounded px-2 py-1 text-xs <?php echo $rank_row_badge_class; ?>"><?php echo $rank_row_group; ?></span>
                    </div>
                </a>
            <?php } ?>
            <div id="rank-empty-state" class="hidden rounded border border-gray-200 bg-gray-50 p-4 text-center text-sm text-gray-500">표시할 게시판이 없습니다.</div>
            <?php if (sql_num_rows($result_rank_board) < 1) { ?>
                <div class="rounded border border-gray-200 bg-gray-50 p-4 text-center text-sm text-gray-500">표시할 게시판이 없습니다.</div>
            <?php } ?>
        </div>

        <!-- <div class="mt-4 text-center">
            <button type="button" class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-xs text-gray-600">
                <span>랭킹 더보기</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down-icon lucide-chevron-down">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>
        </div> -->
    </section>

    <div class="hidden" aria-hidden="true">
        <div class="latest_top_wr">
            <?php
            // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
            // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
            // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
            // echo latest('theme/pic_list', 'free', 4, 23);		// 최소설치시 자동생성되는 자유게시판
            // echo latest('theme/pic_list', 'qa', 4, 23);			// 최소설치시 자동생성되는 질문답변게시판
            // echo latest('theme/pic_list', 'notice', 4, 23);		// 최소설치시 자동생성되는 공지사항게시판
            ?>
        </div>
        <div class="latest_wr">
            <!-- 사진 최신글2 { -->
            <?php
            // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
            // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
            // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
            // echo latest('theme/pic_block', 'gallery', 4, 23);		// 최소설치시 자동생성되는 갤러리게시판
            ?>
            <!-- } 사진 최신글2 끝 -->
        </div>

        <div class="latest_wr">
            <!-- 최신글 시작 { -->
            <?php
            //  최신글
            $sql = " select bo_table
                        from `{$g5['board_table']}` a left join `{$g5['group_table']}` b on (a.gr_id=b.gr_id)
                        where a.bo_device <> 'mobile' ";
            if (!$is_admin)
                $sql .= " and a.bo_use_cert = '' ";
            $sql .= " and a.bo_table not in ('notice', 'gallery') ";     //공지사항과 갤러리 게시판은 제외
            $sql .= " order by b.gr_order, a.bo_order ";
            $result = sql_query($sql);
            for ($i = 0; $row = sql_fetch_array($result); $i++) {
                $lt_style = '';
                if ($i % 3 !== 0) $lt_style = "margin-left:2%";
            ?>
                <div style="float:left;<?php echo $lt_style ?>" class="lt_wr">
                    <?php
                    // 이 함수가 바로 최신글을 추출하는 역할을 합니다.
                    // 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
                    // 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
                    echo latest('theme/basic', $row['bo_table'], 6, 24);
                    ?>
                </div>
            <?php
            }
            ?>
            <!-- } 최신글 끝 -->
        </div>
    </div>
</main>

<?php
include_once(G5_THEME_PATH . '/tail.php');

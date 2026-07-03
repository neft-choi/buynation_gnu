<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가
?>
<?php if ($is_admin) {  //관리자이면 
        ?>
    <div class="sit_admin"><a href="<?php echo G5_ADMIN_URL; ?>/shop_admin/configform.php#anc_scf_etc"
            class="btn_admin btn"><i class="fa fa-cog fa-spin fa-fw"></i><span class="sound_only">검색 설정</span></a></div>
<?php } ?>


<!-- 검색 시작 { -->
<div id="ssch">
    <!-- <h2><strong><?php echo $q; ?></strong> 검색 결과<span class="ssch_result_total">총 <?php echo $total_count; ?>건</span></h2> -->
    <!-- 상세검색 항목 시작 { -->
    <div id="ssch_frm" class="!hidden">
        <div class="ssch_frm_inner">
            <form name="frmdetailsearch">
                <?php
                /*
                <input type="hidden" name="qsort" id="qsort" value="<?php echo $qsort ?>">
                <input type="hidden" name="qorder" id="qorder" value="<?php echo $qorder ?>">
                */
                ?>
                <input type="hidden" name="qcaid" id="qcaid" value="<?php echo $qcaid ?>">

                <?php if ($it_type1) { ?>
                    <input type="hidden" name="it_type1" value="1">
                <?php } ?>

                <?php if ($it_type2) { ?>
                    <input type="hidden" name="it_type2" value="1">
                <?php } ?>

                <?php if ($it_type3) { ?>
                    <input type="hidden" name="it_type3" value="1">
                <?php } ?>

                <?php if ($it_type4) { ?>
                    <input type="hidden" name="it_type4" value="1">
                <?php } ?>

                <?php if ($price_range) { ?>
                    <input type="hidden" name="price_range" value="<?php echo get_text($price_range); ?>">
                <?php } ?>

                <?php if ($price_min) { ?>
                    <input type="hidden" name="price_min" value="<?php echo $price_min; ?>">
                <?php } ?>

                <?php if ($price_max) { ?>
                    <input type="hidden" name="price_max" value="<?php echo $price_max; ?>">
                <?php } ?>

                <div class="ssch_scharea">
                    <label for="ssch_q" class="sound_only">검색어</label>
                    <input type="text" name="q" value="<?php echo $q; ?>" id="ssch_q" class="ssch_input" size="40"
                        maxlength="30" placeholder="검색어">
                    <button type="submit" class="btn_submit"><i class="fa fa-search" aria-hidden="true"></i> 검색</button>
                    <button type="button" class="tooltip_icon"><i class="fa fa-question-circle-o"
                            aria-hidden="true"></i><span class="sound_only">설명보기</span></button>
                    <span class="tooltip">
                        상세검색을 선택하지 않거나, 상품가격을 입력하지 않으면 전체에서 검색합니다.<br>
                        검색어는 최대 30글자까지, 여러개의 검색어를 공백으로 구분하여 입력 할수 있습니다.
                    </span>
                </div>
                <div class="ssch_option chk_box">
                    <strong class="sound_only">검색범위</strong>
                    <input type="checkbox" name="qname" id="ssch_qname" value="1" <?php echo $qname_check ? 'checked="checked"' : ''; ?>> <label for="ssch_qname"><span></span>상품명</label>
                    <input type="checkbox" name="qexplan" id="ssch_qexplan" value="1" <?php echo $qexplan_check ? 'checked="checked"' : ''; ?>> <label for="ssch_qexplan"><span></span>상품설명</label>
                    <input type="checkbox" name="qbasic" id="ssch_qbasic" value="1" <?php echo $qbasic_check ? 'checked="checked"' : ''; ?>> <label for="ssch_qbasic"><span></span>기본설명</label>
                    <input type="checkbox" name="qid" id="ssch_qid" value="1" <?php echo $qid_check ? 'checked="checked"' : ''; ?>> <label for="ssch_qid"><span></span>상품코드</label>
                    <strong class="sound_only">상품가격 (원)</strong>
                    <label for="ssch_qfrom" class="sound_only">최소 가격</label>
                    <input type="text" name="qfrom" value="<?php echo $qfrom; ?>" id="ssch_qfrom" class="ssch_input"
                        size="10"> 원 ~
                    <label for="ssch_qto" class="sound_only">최대 가격</label>
                    <input type="text" name="qto" value="<?php echo $qto; ?>" id="ssch_qto" class="ssch_input"
                        size="10"> 원
                </div>
            </form>
        </div>
        <!-- 검색된 분류 시작 { -->
        <div id="ssch_cate">
            <ul>
                <?php
                echo '<li><a href="#" onclick="set_ca_id(\'\'); return false;">전체분류 <span>(' . $total_count . ')</span></a></li>' . PHP_EOL;
                $total_cnt = 0;
                foreach ((array) $categorys as $row) {
                    if (empty($row))
                        continue;
                    echo "<li><a href=\"#\" onclick=\"set_ca_id('{$row['ca_id']}'); return false;\">{$row['ca_name']} (" . $row['cnt'] . ")</a></li>\n";
                    $total_cnt += $row['cnt'];
                }
                ?>
            </ul>
        </div>
        <!-- } 검색된 분류 끝 -->

        <ul id="ssch_sort_all" class="flex !hidden">
            <li><a href="#" onclick="set_sort('it_sum_qty', 'desc'); return false;">판매많은순</a></li>
            <li><a href="#" onclick="set_sort('it_price', 'asc'); return false;">낮은가격순</a></li>
            <li><a href="#" onclick="set_sort('it_price', 'desc'); return false;">높은가격순</a></li>
            <li><a href="#" onclick="set_sort('it_use_avg', 'desc'); return false;">평점높은순</a></li>
            <li><a href="#" onclick="set_sort('it_use_cnt', 'desc'); return false;">후기많은순</a></li>
            <li><a href="#" onclick="set_sort('it_update_time', 'desc'); return false;">최근등록순</a></li>
        </ul>
        <!-- } 상세검색 항목 끝 -->
    </div>

    <?php
    // $sort_label = '추천순';
    // if ($qsort === 'it_price' && $qorder === 'asc') {
    //     $sort_label = '낮은가격순';
    // } else if ($qsort === 'it_price' && $qorder === 'desc') {
    //     $sort_label = '높은가격순';
    // } else if ($qsort === 'it_use_avg' && $qorder === 'desc') {
    //     $sort_label = '평점높은순';
    // } else if ($qsort === 'it_use_cnt' && $qorder === 'desc') {
    //     $sort_label = '후기많은순';
    // } else if ($qsort === 'it_update_time' && $qorder === 'desc') {
    //     $sort_label = '최근등록순';
    // }
    ?>

    <!-- 검색 결과 수 표시 + 정렬 시작 -->
    <div id="searchResultToolbar" class="flex items-center justify-between gap-3 mb-5">
        <div>
            <span class="text-red-500 font-semibold">
                <?php echo number_format((int) $total_count); ?>
            </span>
            <span class="text-zinc-900">개의 상품이 있습니다</span>
        </div>
        <!--
        <div class="relative shrink-0">
            <button type="button" id="sortBtn"
                class="px-2 py-1 border border-zinc-300 bg-white text-sm text-zinc-800 flex items-center gap-1">
                <span id="sortBtnLabel"
                    class="<?php /* echo $sort_label === '추천순' ? 'pr-4' : ''; */ ?>"><?php /* echo $sort_label; */ ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-zinc-600" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>

            <div id="sortMenu"
                class="hidden absolute right-0 mt-1 min-w-max border border-zinc-200 rounded bg-white z-20">
                <button type="button" class="block w-full text-left p-2 pr-6 text-sm hover:bg-zinc-100"
                    onclick="set_sort('it_sum_qty', 'desc')">추천순</button>
                <button type="button" class="block w-full text-left p-2 pr-6 text-sm hover:bg-zinc-100"
                    onclick="set_sort('it_price', 'asc')">낮은가격순</button>
                <button type="button" class="block w-full text-left p-2 pr-6 text-sm hover:bg-zinc-100"
                    onclick="set_sort('it_price', 'desc')">높은가격순</button>
                <button type="button" class="block w-full text-left p-2 pr-6 text-sm hover:bg-zinc-100"
                    onclick="set_sort('it_use_avg', 'desc')">평점높은순</button>
                <button type="button" class="block w-full text-left p-2 pr-6 text-sm hover:bg-zinc-100"
                    onclick="set_sort('it_use_cnt', 'desc')">후기많은순</button>
                <button type="button" class="block w-full text-left p-2 pr-6 text-sm hover:bg-zinc-100"
                    onclick="set_sort('it_update_time', 'desc')">최근등록순</button>
            </div>
        </div>
-->
        <div class="flex items-center gap-1 text-sm">
            <?php
            include G5_THEME_PATH . '/skin/shop/basic/list.sort.skin.php';
            ?>

            <button type="button" id="filterDrawerOpen"
                class="inline-flex pc:hidden items-center gap-1 border border-gray-300 rounded-xs p-2 ml-0.5">
                <span class="text-nowrap">필터</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-list-filter-icon lucide-list-filter w-4 h-4">
                    <path d="M2 5h20" />
                    <path d="M6 12h12" />
                    <path d="M9 19h6" />
                </svg>
            </button>
        </div>
    </div>
    <!-- 검색 결과 수 표시 + 정렬 끝 -->

    <!-- 검색결과 시작 { -->
    <div>
        <?php
        // 리스트 유형별로 출력
        if (isset($list) && is_object($list) && method_exists($list, 'run')) {
            $list->set_is_page(true);
            $list->set_view('it_img', true);
            $list->set_view('it_name', true);
            $list->set_view('it_basic', true);
            $list->set_view('it_cust_price', false);
            $list->set_view('it_price', true);
            $list->set_view('it_icon', true);
            $list->set_view('sns', true);
            $list->set_view('star', true);
            echo $list->run();
        } else {
            $i = 0;
            $error = '<p class="sct_nofile">' . $list_file . ' 파일을 찾을 수 없습니다.<br>관리자에게 알려주시면 감사하겠습니다.</p>';
        }

        if ($i == 0) {
            echo '<div>' . $error . '</div>';
        }

        // $query_string = 'qname=' . $qname . '&amp;qexplan=' . $qexplan . '&amp;qid=' . $qid;
        
        $query_string = 'qname=' . $qname;
        $query_string .= '&amp;qexplan=' . $qexplan;
        $query_string .= '&amp;qid=' . $qid;
        $query_string .= '&amp;qbasic=' . $qbasic;

        if ($qfrom && $qto)
            $query_string .= '&amp;qfrom=' . $qfrom . '&amp;qto=' . $qto;

        $query_string .= '&amp;qcaid=' . $qcaid;
        $query_string .= '&amp;q=' . urlencode($q);
        $query_string .= '&amp;sort=' . $sort;
        $query_string .= '&amp;sortodr=' . $sortodr;

        // if ($qfrom && $qto)
        //     $query_string .= '&amp;qfrom=' . $qfrom . '&amp;qto=' . $qto;
        
        // $query_string .= '&amp;qcaid=' . $qcaid . '&amp;q=' . urlencode($q);
        // $query_string .= '&amp;qsort=' . $qsort . '&amp;qorder=' . $qorder;
        
        if ($it_type1)
            $query_string .= '&amp;it_type1=1';

        if ($it_type2)
            $query_string .= '&amp;it_type2=1';

        if ($it_type3)
            $query_string .= '&amp;it_type3=1';

        if ($it_type4)
            $query_string .= '&amp;it_type4=1';

        if ($price_range)
            $query_string .= '&amp;price_range=' . urlencode($price_range);

        if ($price_min)
            $query_string .= '&amp;price_min=' . $price_min;

        if ($price_max)
            $query_string .= '&amp;price_max=' . $price_max;

        echo get_paging(
            $config['cf_write_pages'],
            $page,
            $total_page,
            $_SERVER['SCRIPT_NAME'] . '?' . $query_string . '&amp;page='
        );
        ?>
    </div>
    <!-- } 검색결과 끝 -->
</div>
<!-- } 검색 끝 -->

<script>
    // (function () {
    //     var btn = document.getElementById('sortBtn');
    //     var menu = document.getElementById('sortMenu');

    //     if (!btn || !menu) return;

    //     btn.addEventListener('click', function () {
    //         menu.classList.toggle('hidden');
    //     });

    //     document.addEventListener('click', function (e) {
    //         if (!btn.contains(e.target) && !menu.contains(e.target)) {
    //             menu.classList.add('hidden');
    //         }
    //     });
    // })();

    // function set_sort(qsort, qorder) {
    //     var f = document.frmdetailsearch;
    //     f.qsort.value = qsort;
    //     f.qorder.value = qorder;
    //     f.submit();
    // }

    $(function () {
        const $toolbar = $('#searchResultToolbar');
        const $productList = $('.sct.sct_10').first();

        if (!$toolbar.length || !$productList.length) {
            return;
        }

        $toolbar.insertBefore($productList);
    });

    function set_ca_id(qcaid) {
        var f = document.frmdetailsearch;
        f.qcaid.value = qcaid;
        f.submit();
    }

    $(function () {
        //tooltip
        $(".tooltip_icon").click(function () {
            $(this).next(".tooltip").fadeIn(400);
        }).mouseout(function () {
            $(this).next(".tooltip").fadeOut();
        });
    });

    // 검색옵션
    $("#ssch_sort_all li a").click(function () {
        $(this).parent().addClass('active');
    });
</script>
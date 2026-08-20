<?php
$sub_menu = '400310';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);
include_once(G5_LIB_PATH . '/iteminfo.lib.php');

auth_check_menu($auth, $sub_menu, 'r');

add_stylesheet('<link rel="stylesheet" href="' . G5_ADMIN_URL . '/shop_admin/css/platform.css?ver=' . G5_CSS_VER . '">', 100);

$g5['title'] = '상품검수';
include_once(G5_ADMIN_PATH . '/admin.head.php');

// 단일 상품 코드 (임시)
$inspect_item_id = '1784857141';

$inspect_item_id = sql_real_escape_string($inspect_item_id);

// $inspect_item = sql_fetch("
//     select it_id, it_name, it_brand, it_price, it_time, it_use
//     from {$g5['g5_shop_item_table']}
//     where it_id = '{$inspect_item_id}'
// ");

$it = get_shop_item($inspect_item_id);

$inspect_item = $it;
?>

<section class="content" id="content">
    <div class="content-head">
        <div>
            <h2>상품 검수</h2>
            <p>브랜드 검수 요청 이후 플랫폼 승인 전에는 판매되지 않습니다.</p>
        </div>
    </div>
    <div class="flow">
        <div class="flow-step done">임시저장</div>
        <div class="flow-step done">검수 요청</div>
        <div class="flow-step on">플랫폼 심사</div>
        <div class="flow-step">보완·반려</div>
        <div class="flow-step">승인 후 판매</div>
    </div>

    <div class="status-rail">
        <button class="active" data-filter-key="product" data-filter-value="all">전체</button>
        <button class="" data-filter-key="product" data-filter-value="pending">심사 중</button>
        <button class="" data-filter-key="product" data-filter-value="revision">보완 요청</button>
        <button class="" data-filter-key="product" data-filter-value="approved">승인</button>
        <button class="" data-filter-key="product" data-filter-value="rejected">거절</button>
    </div>

    <article class="card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>검수번호 / 상품</th>
                        <th>브랜드</th>
                        <th>구분</th>
                        <th>배송그룹</th>
                        <th>요청일</th>
                        <th>상태</th>
                        <th>검수</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="name">신제품 수프 체험팩<span class="sub">PRD-RV-260811-07 · P-807 · GT-SP-001</span></td>
                        <td>그린테이블</td>
                        <td>신규</td>
                        <td>기본 배송그룹 · 묶음배송</td>
                        <td>2026.08.11 10:40</td>
                        <td><span class="badge green">승인</span><span class="sub">판매중</span></td>
                        <td><button class="btn small" data-action="product-detail" data-id="PRD-RV-260811-07">검수</button></td>
                    </tr>
                    <tr>
                        <td class="name">저염 버섯수프 3팩<span class="sub">PRD-RV-260810-03 · P-809 · GT-MS-003</span></td>
                        <td>그린테이블</td>
                        <td>신규</td>
                        <td>묶음배송 · 상온</td>
                        <td>2026.08.10 09:15</td>
                        <td><span class="badge red">보완 요청</span><span class="sub">판매 대기</span></td>
                        <td><button class="btn small" data-action="product-detail" data-id="PRD-RV-260810-03">검수</button></td>
                    </tr>
                    <tr>
                        <td class="name">상온 크림 샘플팩<span class="sub">PRD-RV-260809-02 · P-810 · GT-CR-001</span></td>
                        <td>그린테이블</td>
                        <td>신규</td>
                        <td><span class="badge red">미지정</span></td>
                        <td>2026.08.09 13:20</td>
                        <td><span class="badge red">거절</span><span class="sub">등록 반려</span></td>
                        <td><button class="btn small" data-action="product-detail" data-id="PRD-RV-260809-02">검수</button></td>
                    </tr>
                    <tr>
                        <td class="name">대나무 욕실 정리 선반<span class="sub">PRD-RV-260811-028 · P51218 · ML-BT-811</span></td>
                        <td>문스앤 리빙</td>
                        <td>신규</td>
                        <td>개별배송 · 대형</td>
                        <td>2026.08.11 08:35</td>
                        <td><span class="badge yellow">심사 중</span><span class="sub">판매 대기</span></td>
                        <td><button class="btn small" data-action="product-detail" data-id="PRD-RV-260811-028">검수</button></td>
                    </tr>
                    <tr>
                        <td class="name">패브릭 수납 바스켓<span class="sub">PRD-RV-260806-003 · P51188 · ML-FB-204</span></td>
                        <td>문스앤 리빙</td>
                        <td>정보 변경</td>
                        <td>묶음배송 · 상온</td>
                        <td>2026.08.06 13:18</td>
                        <td><span class="badge green">승인</span><span class="sub">판매중</span></td>
                        <td><button class="btn small" data-action="product-detail" data-id="PRD-RV-260806-003">검수</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </article>
    <p class="footer">DONUTS 플랫폼 관리자 v1.4 · 클릭형 프로토타입 · 표시 데이터와 외부 연동은 시연용이며 처리 결과는 이 브라우저에 저장됩니다.</p>
</section>

<!-- 모달 -->
<div id="modal-root" class="hidden">
    <div class="modal-backdrop" data-action="close-modal">
        <section class="modal">
            <div class="modal-head">
                <h3>상품 검수</h3><button data-action="close-modal">×</button>
            </div>
            <div class="modal-body"><span class="eyebrow"><?php echo get_text($inspect_item['it_id']); ?></span>
                <h3 style="margin:6px 0 14px"><?php echo get_text($inspect_item['it_name']); ?></h3>
                <dl class="detail">
                    <dt>상품 ID</dt>
                    <dd><?php echo get_text($inspect_item['it_id']); ?></dd>
                    <dt>브랜드</dt>
                    <dd><?php echo get_text($inspect_item['it_brand']); ?></dd>
                    <dt>판매가</dt>
                    <dd><?php echo number_format((int) $inspect_item['it_price']); ?>원</dd>
                    <dt>요청 유형</dt>
                    <dd>신규</dd>
                    <dt>배송그룹</dt>
                    <dd>기본 배송그룹 · 묶음배송</dd>
                    <dt>판매 상태</dt>
                    <dd><?php echo $inspect_item['it_use'] ? '판매중' : '판매 대기'; ?></dd>
                </dl>
                <div class="doc-list">
                    <div class="doc-row">
                        <div><b>필수 고시정보 완료</b><small>검수 체크 1</small></div><span class="badge green">확인</span>
                    </div>
                    <div class="doc-row">
                        <div><b>대표 이미지 확인</b><small>검수 체크 2</small></div><span class="badge green">확인</span>
                    </div>
                    <div class="doc-row">
                        <div><b>배송그룹 지정</b><small>검수 체크 3</small></div><span class="badge green">확인</span>
                    </div>
                    <div class="doc-row">
                        <div><b>금지표현 자동검사 통과</b><small>검수 체크 4</small></div><span class="badge green">확인</span>
                    </div>
                </div>
                <div class="warning">플랫폼 승인 시 판매 상태가 ‘판매중’으로 바뀌고 브랜드 상품 목록에 승인 결과가 반영됩니다.</div>

                <form name="fitemform" action="./itemformupdate.php" method="post" enctype="MULTIPART/FORM-DATA" autocomplete="off" onsubmit="return fitemformcheck(this)">
                    <input type="hidden" name="w" value="<?php echo $w; ?>">
                    <input type="hidden" name="sca" value="<?php echo $sca; ?>">
                    <input type="hidden" name="sst" value="<?php echo $sst; ?>">
                    <input type="hidden" name="sod" value="<?php echo $sod; ?>">
                    <input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
                    <input type="hidden" name="stx" value="<?php echo $stx; ?>">
                    <input type="hidden" name="page" value="<?php echo $page; ?>">
                    <?php echo $pg_anchor; ?>

                    <section id="anc_sitfrm_cate">
                        <h2 class="h2_frm">상품분류</h2>

                        <div class="tbl_frm01 tbl_wrap !mt-4">
                            <table class="md:m-[-16px] m-0">
                                <caption>상품분류 입력</caption>
                                <colgroup>
                                    <col class="grid_4">
                                    <col>
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th scope="row">
                                            <label for="ca_id">기본분류</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php if ($w == "") echo help("기본분류를 선택하면, 판매/재고/HTML사용/판매자 E-mail 등을, 선택한 분류의 기본값으로 설정합니다."); ?>
                                            <select name="ca_id" id="ca_id" onchange="categorychange(this.form)">
                                                <option value="">선택하세요</option>
                                                <?php echo conv_selected_option($category_select, $it['ca_id']); ?>
                                            </select>
                                            <script>
                                                var ca_use = new Array();
                                                var ca_stock_qty = new Array();
                                                //var ca_explan_html = new Array();
                                                var ca_sell_email = new Array();
                                                var ca_opt1_subject = new Array();
                                                var ca_opt2_subject = new Array();
                                                var ca_opt3_subject = new Array();
                                                var ca_opt4_subject = new Array();
                                                var ca_opt5_subject = new Array();
                                                var ca_opt6_subject = new Array();
                                                <?php echo "\n$script"; ?>
                                            </script>
                                        </td>
                                    </tr>
                                    <?php for ($i = 2; $i <= 3; $i++) { ?>
                                        <tr>
                                            <th scope="row">
                                                <label for="ca_id<?php echo $i; ?>">
                                                    <?php echo $i; ?>차 분류</label>
                                                <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                    <input type="checkbox" name="review_fields[]" value="" id="">
                                                    보완 요청
                                                </label>
                                            </th>
                                            <td>
                                                <?php echo help($i . '차 분류는 기본 분류의 하위 분류 개념이 아니므로 기본 분류 선택시 해당 상품이 포함될 최하위 분류만 선택하시면 됩니다.'); ?>
                                                <select name="ca_id<?php echo $i; ?>" id="ca_id<?php echo $i; ?>">
                                                    <option value="">선택하세요</option>
                                                    <?php echo conv_selected_option($category_select, $it['ca_id' . $i]); ?>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <section id="anc_sitfrm_ini">
                        <h2 class="h2_frm">기본정보</h2>
                        <div class="tbl_frm01 tbl_wrap">
                            <table class="md:m-[-16px] m-0">
                                <caption>기본정보 입력</caption>
                                <colgroup>
                                    <col class="grid_4">
                                    <col>
                                    <col class="grid_3">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th scope="row">
                                            상품코드
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td colspan="2">
                                            <?php if ($w == '') { // 추가 
                                            ?>
                                                <?php echo help("상품의 코드는 10자리 숫자로 자동생성합니다. <b>직접 상품코드를 입력할 수도 있습니다.</b>\n상품코드는 영문자, 숫자, - 만 입력 가능합니다."); ?>
                                                <input type="text" name="it_id" value="<?php echo time(); ?>" id="it_id" required class="frm_input required" size="20" maxlength="20">
                                            <?php } else { ?>
                                                <input type="hidden" name="it_id" value="<?php echo $it['it_id']; ?>">
                                                <span class="frm_ca_id"><?php echo $it['it_id']; ?></span>
                                                <a href="<?php echo shop_item_url($it_id); ?>" class="btn btn_04">상품확인</a>
                                                <a href="<?php echo G5_ADMIN_URL; ?>/shop_admin/itemuselist.php?sfl=a.it_id&amp;stx=<?php echo $it_id; ?>" class="btn btn_04">사용후기</a>
                                                <a href="<?php echo G5_ADMIN_URL; ?>/shop_admin/itemqalist.php?sfl=a.it_id&amp;stx=<?php echo $it_id; ?>" class="btn btn_04">상품문의</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_name">상품명</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td colspan="2">
                                            <?php echo help("HTML 입력이 불가합니다."); ?>
                                            <input type="text" name="it_name" value="<?php echo get_text(cut_str($it['it_name'], 250, "")); ?>" id="it_name" required class="frm_input required" size="95">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_basic">기본설명</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php echo help("상품명 하단에 상품에 대한 추가적인 설명이 필요한 경우에 입력합니다. HTML 입력도 가능합니다."); ?>
                                            <input type="text" name="it_basic" value="<?php echo get_text(html_purifier($it['it_basic'])); ?>" id="it_basic" class="frm_input" size="95">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_order">출력순서</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php echo help("숫자가 작을 수록 상위에 출력됩니다. 음수 입력도 가능하며 입력 가능 범위는 -2147483648 부터 2147483647 까지입니다.\n<b>입력하지 않으면 자동으로 출력됩니다.</b>"); ?>
                                            <input type="text" name="it_order" value="<?php echo $it['it_order']; ?>" id="it_order" class="frm_input" size="12">
                                        </td>
                                    </tr>

                                    <?php if ($is_admin === 'super') { ?>
                                        <tr>
                                            <th scope="row">
                                                상품유형
                                                <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                    <input type="checkbox" name="review_fields[]" value="" id="">
                                                    보완 요청
                                                </label>
                                            </th>
                                            <td>
                                                <?php echo help("메인화면에 유형별로 출력할때 사용합니다.\n이곳에 체크하게되면 상품리스트에서 유형별로 정렬할때 체크된 상품이 가장 먼저 출력됩니다."); ?>
                                                <input type="checkbox" name="it_type1" value="1" <?php echo ($it['it_type1'] ? "checked" : ""); ?> id="it_type1">
                                                <label for="it_type1">히트 <img src="<?php echo G5_SHOP_URL; ?>/img/icon_hit.gif" alt=""></label>
                                                <input type="checkbox" name="it_type2" value="1" <?php echo ($it['it_type2'] ? "checked" : ""); ?> id="it_type2">
                                                <label for="it_type2">추천 <img src="<?php echo G5_SHOP_URL; ?>/img/icon_rec.gif" alt=""></label>
                                                <input type="checkbox" name="it_type3" value="1" <?php echo ($it['it_type3'] ? "checked" : ""); ?> id="it_type3">
                                                <label for="it_type3">신상품 <img src="<?php echo G5_SHOP_URL; ?>/img/icon_new.gif" alt=""></label>
                                                <input type="checkbox" name="it_type4" value="1" <?php echo ($it['it_type4'] ? "checked" : ""); ?> id="it_type4">
                                                <label for="it_type4">인기 <img src="<?php echo G5_SHOP_URL; ?>/img/icon_best.gif" alt=""></label>
                                                <input type="checkbox" name="it_type5" value="1" <?php echo ($it['it_type5'] ? "checked" : ""); ?> id="it_type5">
                                                <label for="it_type5">할인 <img src="<?php echo G5_SHOP_URL; ?>/img/icon_discount.gif" alt=""></label>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                    <tr>
                                        <th scope="row">
                                            <label for="it_maker">제조사</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php echo help("입력하지 않으면 상품상세페이지에 출력하지 않습니다."); ?>
                                            <input type="text" name="it_maker" value="<?php echo get_text($it['it_maker']); ?>" id="it_maker" class="frm_input" size="40">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_origin">원산지</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php echo help("입력하지 않으면 상품상세페이지에 출력하지 않습니다."); ?>
                                            <input type="text" name="it_origin" value="<?php echo get_text($it['it_origin']); ?>" id="it_origin" class="frm_input" size="40">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_brand">브랜드</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php
                                            $current_user_brand = isset($member['mb_id']) ? get_text($member['mb_id']) : '';
                                            $brand_value = $current_user_brand;
                                            $readonly_attr = 'readonly style="background-color:#f5f5f5; cursor:not-allowed;"';
                                            ?>
                                            <input type="text" name="it_brand" value="<?php echo $brand_value; ?>" id="it_brand" class="frm_input" size="40" <?php echo $readonly_attr; ?>>
                                            <span class="frm_info">현재 접속한 계정의 브랜드 정보가 자동으로 입력되며, 수정할 수 없습니다.</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_model">모델</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php echo help("입력하지 않으면 상품상세페이지에 출력하지 않습니다."); ?>
                                            <input type="text" name="it_model" value="<?php echo get_text($it['it_model']); ?>" id="it_model" class="frm_input" size="40">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_tel_inq">전화문의</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php echo help("상품 금액 대신 전화문의로 표시됩니다."); ?>
                                            <input type="checkbox" name="it_tel_inq" value="1" id="it_tel_inq" <?php echo ($it['it_tel_inq']) ? "checked" : ""; ?>> 예
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_use">판매가능</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php echo help("잠시 판매를 중단하거나 재고가 없을 경우에 체크를 해제해 놓으면 출력되지 않으며, 주문도 받지 않습니다."); ?>
                                            <input type="checkbox" name="it_use" value="1" id="it_use" <?php echo ($it['it_use']) ? "checked" : ""; ?>> 예
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_nocoupon">쿠폰적용안함</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php echo help("설정에 체크하시면 쿠폰 생성 때 상품 검색 결과에 노출되지 않습니다."); ?>
                                            <input type="checkbox" name="it_nocoupon" value="1" id="it_nocoupon" <?php echo ($it['it_nocoupon']) ? "checked" : ""; ?>> 예
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="ec_mall_pid">네이버쇼핑 상품ID</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td colspan="2">
                                            <?php echo help("네이버쇼핑에 입점한 경우 네이버쇼핑 상품ID를 입력하시면 네이버페이와 연동됩니다.<br>일부 쇼핑몰의 경우 네이버쇼핑 상품ID 대신 쇼핑몰 상품ID를 입력해야 하는 경우가 있습니다.<br>네이버페이 연동과정에서 이 부분에 대한 안내가 이뤄지니 안내받은 대로 값을 입력하시면 됩니다."); ?>
                                            <input type="text" name="ec_mall_pid" value="<?php echo get_text($it['ec_mall_pid']); ?>" id="ec_mall_pid" class="frm_input" size="20">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            상품설명
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td colspan="2"> <?php echo editor_html('it_explan', get_text(html_purifier($it['it_explan']), 0)); ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            모바일 상품설명
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td colspan="2"> <?php echo editor_html('it_mobile_explan', get_text(html_purifier($it['it_mobile_explan']), 0)); ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_sell_email">판매자 e-mail</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php echo help("운영자와 실제 판매자가 다른 경우 실제 판매자의 e-mail을 입력하면, 상품 주문 시점을 기준으로 실제 판매자에게도 주문서를 발송합니다."); ?>
                                            <input type="text" name="it_sell_email" value="<?php echo get_sanitize_input($it['it_sell_email']); ?>" id="it_sell_email" class="frm_input" size="40">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_shop_memo">상점메모</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td><textarea name="it_shop_memo" id="it_shop_memo"><?php echo html_purifier($it['it_shop_memo']); ?></textarea></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <section id="anc_sitfrm_compact">
                        <h2 class="h2_frm">상품요약정보</h2>
                        <div class="local_desc02 local_desc">
                            <p><strong>전자상거래 등에서의 상품 등의 정보제공에 관한 고시</strong>에 따라 총 35개 상품군에 대해 상품 특성 등을 양식에 따라 입력할 수 있습니다.</p>
                        </div>

                        <div id="sit_compact">
                            <?php echo help("상품군을 선택하면 자동으로 항목이 변환됩니다."); ?>
                            <select id="it_info_gubun" name="it_info_gubun">
                                <option value="">상품군을 선택하세요.</option>
                                <?php
                                if (!$it['it_info_gubun']) $it['it_info_gubun'] = 'wear';
                                foreach ($item_info as $key => $value) {
                                    $opt_value = $key;
                                    $opt_text  = $value['title'];
                                    echo '<option value="' . $opt_value . '" ' . get_selected($opt_value, $it['it_info_gubun']) . '>' . $opt_text . '</option>' . PHP_EOL;
                                }
                                ?>
                            </select>
                        </div>

                        <div id="sit_compact_fields">
                            <?php include_once(G5_ADMIN_PATH . '/shop_admin/iteminfo.php'); ?>
                        </div>
                    </section>


                    <script>
                        $(function() {
                            $(document).on("change", "#it_info_gubun", function() {
                                var gubun = $(this).val();
                                $.post(
                                    "<?php echo G5_ADMIN_URL; ?>/shop_admin/iteminfo.php", {
                                        it_id: "<?php echo $it['it_id']; ?>",
                                        gubun: gubun
                                    },
                                    function(data) {
                                        $("#sit_compact_fields").empty().html(data);
                                    }
                                );
                            });
                        });
                    </script>

                    <section id="anc_sitfrm_cost">
                        <h2 class="h2_frm">가격 및 재고</h2>

                        <div class="tbl_frm01 tbl_wrap">
                            <table class="md:m-[-16px] m-0">
                                <caption>가격 및 재고 입력</caption>
                                <colgroup>
                                    <col class="grid_4">
                                    <col>
                                    <col class="grid_3">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_price">판매가격</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <input type="text" name="it_price" value="<?php echo $it['it_price']; ?>" id="it_price" class="frm_input" size="8"> 원
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_cust_price">시중가격</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php echo help("입력하지 않으면 상품상세페이지에 출력하지 않습니다."); ?>
                                            <input type="text" name="it_cust_price" value="<?php echo $it['it_cust_price']; ?>" id="it_cust_price" class="frm_input" size="8"> 원
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_point_type">포인트 유형</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php echo help("포인트 유형을 설정할 수 있습니다. 비율로 설정했을 경우 설정 기준금액의 %비율로 포인트가 지급됩니다."); ?>
                                            <select name="it_point_type" id="it_point_type">
                                                <option value="0" <?php echo get_selected('0', $it['it_point_type']); ?>>설정금액</option>
                                                <option value="1" <?php echo get_selected('1', $it['it_point_type']); ?>>판매가기준 설정비율</option>
                                                <option value="2" <?php echo get_selected('2', $it['it_point_type']); ?>>구매가기준 설정비율</option>
                                            </select>
                                            <script>
                                                $(function() {
                                                    $("#it_point_type").change(function() {
                                                        if (parseInt($(this).val()) > 0)
                                                            $("#it_point_unit").text("%");
                                                        else
                                                            $("#it_point_unit").text("점");
                                                    });
                                                });
                                            </script>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_point">포인트</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php echo help("주문완료후 환경설정에서 설정한 주문완료 설정일 후 회원에게 부여하는 포인트입니다.\n또, 포인트부여를 '아니오'로 설정한 경우 신용카드, 계좌이체로 주문하는 회원께는 부여하지 않습니다."); ?>
                                            <input type="text" name="it_point" value="<?php echo $it['it_point']; ?>" id="it_point" class="frm_input" size="8"> <span id="it_point_unit"><?php if ($it['it_point_type']) echo '%';
                                                                                                                                                                                            else echo '점'; ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_supply_point">추가옵션상품 포인트</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php echo help("상품의 추가옵션상품 구매에 일괄적으로 지급하는 포인트입니다. 0으로 설정하시면 구매포인트를 지급하지 않습니다.\n주문완료후 환경설정에서 설정한 주문완료 설정일 후 회원에게 부여하는 포인트입니다.\n또, 포인트부여를 '아니오'로 설정한 경우 신용카드, 계좌이체로 주문하는 회원께는 부여하지 않습니다."); ?>
                                            <input type="text" name="it_supply_point" value="<?php echo $it['it_supply_point']; ?>" id="it_supply_point" class="frm_input" size="8"> 점
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_soldout">상품품절</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php echo help("잠시 판매를 중단하거나 재고가 없을 경우에 체크해 놓으면 품절상품으로 표시됩니다."); ?>
                                            <input type="checkbox" name="it_soldout" value="1" id="it_soldout" <?php echo ($it['it_soldout']) ? "checked" : ""; ?>> 예
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_stock_sms">재입고SMS 알림</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td colspan="2">
                                            <?php echo help("상품이 품절인 경우에 체크해 놓으면 상품상세보기에서 고객이 재입고SMS 알림을 신청할 수 있게 됩니다."); ?>
                                            <input type="checkbox" name="it_stock_sms" value="1" id="it_stock_sms" <?php echo ($it['it_stock_sms']) ? "checked" : ""; ?>> 예
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_stock_qty">재고수량</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php echo help("<b>주문관리에서 상품별 상태 변경에 따라 자동으로 재고를 가감합니다.</b> 재고는 규격/색상별이 아닌, 상품별로만 관리됩니다.<br>재고수량을 0으로 설정하시면 품절상품으로 표시됩니다."); ?>
                                            <input type="text" name="it_stock_qty" value="<?php echo $it['it_stock_qty']; ?>" id="it_stock_qty" class="frm_input" size="8"> 개
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_noti_qty">재고 통보수량</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php echo help("상품의 재고가 통보수량보다 작을 때 쇼핑몰관리 메인화면의 재고현황에 재고부족 상품으로 표시됩니다.<br>옵션이 있는 상품은 개별 옵션의 통보수량이 적용됩니다."); ?>
                                            <input type="text" name="it_noti_qty" value="<?php echo $it['it_noti_qty']; ?>" id="it_noti_qty" class="frm_input" size="8"> 개
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_buy_min_qty">최소구매수량</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php echo help("상품 구매시 최소 구매 수량을 설정합니다."); ?>
                                            <input type="text" name="it_buy_min_qty" value="<?php echo $it['it_buy_min_qty']; ?>" id="it_buy_min_qty" class="frm_input" size="8"> 개
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_buy_max_qty">최대구매수량</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php echo help("상품 구매시 최대 구매 수량을 설정합니다."); ?>
                                            <input type="text" name="it_buy_max_qty" value="<?php echo $it['it_buy_max_qty']; ?>" id="it_buy_max_qty" class="frm_input" size="8"> 개
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            <label for="it_notax">상품과세 유형</label>
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td>
                                            <?php echo help("상품의 과세유형(과세, 비과세)을 설정합니다."); ?>
                                            <select name="it_notax" id="it_notax">
                                                <option value="0" <?php echo get_selected('0', $it['it_notax']); ?>>과세</option>
                                                <option value="1" <?php echo get_selected('1', $it['it_notax']); ?>>비과세</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <?php
                                    $opt_subject = explode(',', $it['it_option_subject']);
                                    ?>
                                    <tr>
                                        <th scope="row">
                                            상품선택옵션
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td colspan="2">
                                            <div class="sit_option tbl_frm01">
                                                <?php echo help('옵션항목은 콤마(,) 로 구분하여 여러개를 입력할 수 있습니다. 옷을 예로 들어 [옵션1 : 사이즈 , 옵션1 항목 : XXL,XL,L,M,S] , [옵션2 : 색상 , 옵션2 항목 : 빨,파,노]<br><strong>옵션명과 옵션항목에 따옴표(\', ")는 입력할 수 없습니다.</strong>'); ?>
                                                <table class="md:m-[-16px] m-0">
                                                    <caption>상품선택옵션 입력</caption>
                                                    <colgroup>
                                                        <col class="grid_4">
                                                        <col>
                                                    </colgroup>
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">
                                                                <label for="opt1_subject">옵션1</label>
                                                                <input type="text" name="opt1_subject" value="<?php echo isset($opt_subject[0]) ? $opt_subject[0] : ''; ?>" id="opt1_subject" class="frm_input" size="15">
                                                            </th>
                                                            <td>
                                                                <label for="opt1"><b>옵션1 항목</b></label>
                                                                <input type="text" name="opt1" value="" id="opt1" class="frm_input" size="50">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
                                                                <label for="opt2_subject">옵션2</label>
                                                                <input type="text" name="opt2_subject" value="<?php echo isset($opt_subject[1]) ? $opt_subject[1] : ''; ?>" id="opt2_subject" class="frm_input" size="15">
                                                            </th>
                                                            <td>
                                                                <label for="opt2"><b>옵션2 항목</b></label>
                                                                <input type="text" name="opt2" value="" id="opt2" class="frm_input" size="50">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
                                                                <label for="opt3_subject">옵션3</label>
                                                                <input type="text" name="opt3_subject" value="<?php echo isset($opt_subject[2]) ? $opt_subject[2] : ''; ?>" id="opt3_subject" class="frm_input" size="15">
                                                            </th>
                                                            <td>
                                                                <label for="opt3"><b>옵션3 항목</b></label>
                                                                <input type="text" name="opt3" value="" id="opt3" class="frm_input" size="50">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="btn_confirm02 btn_confirm">
                                                    <button type="button" id="option_table_create" class="btn btn_04">옵션목록생성</button>
                                                </div>
                                            </div>
                                            <div id="sit_option_frm"><?php include_once(G5_ADMIN_PATH . '/shop_admin/itemoption.php'); ?></div>

                                            <script>
                                                $(function() {
                                                    <?php if ($it['it_id'] && $po_run) { ?>
                                                        //옵션항목설정
                                                        var arr_opt1 = new Array();
                                                        var arr_opt2 = new Array();
                                                        var arr_opt3 = new Array();
                                                        var opt1 = opt2 = opt3 = '';
                                                        var opt_val;

                                                        $(".opt-cell").each(function() {
                                                            opt_val = $(this).text().split(" > ");
                                                            opt1 = opt_val[0];
                                                            opt2 = opt_val[1];
                                                            opt3 = opt_val[2];

                                                            if (opt1 && $.inArray(opt1, arr_opt1) == -1)
                                                                arr_opt1.push(opt1);

                                                            if (opt2 && $.inArray(opt2, arr_opt2) == -1)
                                                                arr_opt2.push(opt2);

                                                            if (opt3 && $.inArray(opt3, arr_opt3) == -1)
                                                                arr_opt3.push(opt3);
                                                        });


                                                        $("input[name=opt1]").val(arr_opt1.join());
                                                        $("input[name=opt2]").val(arr_opt2.join());
                                                        $("input[name=opt3]").val(arr_opt3.join());
                                                    <?php } ?>
                                                    // 옵션목록생성
                                                    $("#option_table_create").click(function() {
                                                        var it_id = $.trim($("input[name=it_id]").val());
                                                        var opt1_subject = $.trim($("#opt1_subject").val());
                                                        var opt2_subject = $.trim($("#opt2_subject").val());
                                                        var opt3_subject = $.trim($("#opt3_subject").val());
                                                        var opt1 = $.trim($("#opt1").val());
                                                        var opt2 = $.trim($("#opt2").val());
                                                        var opt3 = $.trim($("#opt3").val());
                                                        var $option_table = $("#sit_option_frm");

                                                        if (!opt1_subject || !opt1) {
                                                            alert("옵션명과 옵션항목을 입력해 주십시오.");
                                                            return false;
                                                        }

                                                        $.post(
                                                            "<?php echo G5_ADMIN_URL; ?>/shop_admin/itemoption.php", {
                                                                it_id: it_id,
                                                                w: "<?php echo $w; ?>",
                                                                opt1_subject: opt1_subject,
                                                                opt2_subject: opt2_subject,
                                                                opt3_subject: opt3_subject,
                                                                opt1: opt1,
                                                                opt2: opt2,
                                                                opt3: opt3
                                                            },
                                                            function(data) {
                                                                $option_table.empty().html(data);
                                                            }
                                                        );
                                                    });

                                                    // 모두선택
                                                    $(document).on("click", "input[name=opt_chk_all]", function() {
                                                        if ($(this).is(":checked")) {
                                                            $("input[name='opt_chk[]']").attr("checked", true);
                                                        } else {
                                                            $("input[name='opt_chk[]']").attr("checked", false);
                                                        }
                                                    });

                                                    // 선택삭제
                                                    $(document).on("click", "#sel_option_delete", function() {
                                                        var $el = $("input[name='opt_chk[]']:checked");
                                                        if ($el.length < 1) {
                                                            alert("삭제하려는 옵션을 하나 이상 선택해 주십시오.");
                                                            return false;
                                                        }

                                                        $el.closest("tr").remove();
                                                    });

                                                    // 일괄적용
                                                    $(document).on("click", "#opt_value_apply", function() {
                                                        if ($(".opt_com_chk:checked").length < 1) {
                                                            alert("일괄 수정할 항목을 하나이상 체크해 주십시오.");
                                                            return false;
                                                        }

                                                        var opt_price = $.trim($("#opt_com_price").val());
                                                        var opt_stock = $.trim($("#opt_com_stock").val());
                                                        var opt_noti = $.trim($("#opt_com_noti").val());
                                                        var opt_use = $("#opt_com_use").val();
                                                        var $el = $("input[name='opt_chk[]']:checked");

                                                        // 체크된 옵션이 있으면 체크된 것만 적용
                                                        if ($el.length > 0) {
                                                            var $tr;
                                                            $el.each(function() {
                                                                $tr = $(this).closest("tr");

                                                                if ($("#opt_com_price_chk").is(":checked"))
                                                                    $tr.find("input[name='opt_price[]']").val(opt_price);

                                                                if ($("#opt_com_stock_chk").is(":checked"))
                                                                    $tr.find("input[name='opt_stock_qty[]']").val(opt_stock);

                                                                if ($("#opt_com_noti_chk").is(":checked"))
                                                                    $tr.find("input[name='opt_noti_qty[]']").val(opt_noti);

                                                                if ($("#opt_com_use_chk").is(":checked"))
                                                                    $tr.find("select[name='opt_use[]']").val(opt_use);
                                                            });
                                                        } else {
                                                            if ($("#opt_com_price_chk").is(":checked"))
                                                                $("input[name='opt_price[]']").val(opt_price);

                                                            if ($("#opt_com_stock_chk").is(":checked"))
                                                                $("input[name='opt_stock_qty[]']").val(opt_stock);

                                                            if ($("#opt_com_noti_chk").is(":checked"))
                                                                $("input[name='opt_noti_qty[]']").val(opt_noti);

                                                            if ($("#opt_com_use_chk").is(":checked"))
                                                                $("select[name='opt_use[]']").val(opt_use);
                                                        }
                                                    });
                                                });
                                            </script>
                                        </td>
                                    </tr>
                                    <?php
                                    $spl_subject = explode(',', $it['it_supply_subject']);
                                    $spl_count = count($spl_subject);
                                    ?>
                                    <tr>
                                        <th scope="row">
                                            상품추가옵션
                                            <label for="" class="mt-2 flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="" id="">
                                                보완 요청
                                            </label>
                                        </th>
                                        <td colspan="2">
                                            <div id="sit_supply_frm" class="sit_option tbl_frm01">
                                                <?php echo help('옵션항목은 콤마(,) 로 구분하여 여러개를 입력할 수 있습니다. 스마트폰을 예로 들어 [추가1 : 추가구성상품 , 추가1 항목 : 액정보호필름,케이스,충전기]<br><strong>옵션명과 옵션항목에 따옴표(\', ")는 입력할 수 없습니다.</strong>'); ?>
                                                <table class="md:m-[-16px] m-0">
                                                    <caption>상품추가옵션 입력</caption>
                                                    <colgroup>
                                                        <col class="grid_4">
                                                        <col>
                                                    </colgroup>
                                                    <tbody>
                                                        <?php
                                                        $i = 0;
                                                        do {
                                                            $seq = $i + 1;
                                                        ?>
                                                            <tr>
                                                                <th scope="row">
                                                                    <label for="spl_subject_<?php echo $seq; ?>">추가<?php echo $seq; ?></label>
                                                                    <input type="text" name="spl_subject[]" id="spl_subject_<?php echo $seq; ?>" value="<?php echo $spl_subject[$i]; ?>" class="frm_input" size="15">
                                                                </th>
                                                                <td>
                                                                    <label for="spl_item_<?php echo $seq; ?>"><b>추가<?php echo $seq; ?> 항목</b></label>
                                                                    <input type="text" name="spl[]" id="spl_item_<?php echo $seq; ?>" value="" class="frm_input" size="40">
                                                                    <?php
                                                                    if ($i > 0)
                                                                        echo '<button type="button" id="del_supply_row" class="btn btn_04">삭제</button>';
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                            $i++;
                                                        } while ($i < $spl_count);
                                                        ?>
                                                    </tbody>
                                                </table>
                                                <div id="sit_option_addfrm_btn"><button type="button" id="add_supply_row" class="btn btn_04">옵션추가</button></div>
                                                <div class="btn_confirm02 btn_confirm">
                                                    <button type="button" id="supply_table_create">옵션목록생성</button>
                                                </div>
                                            </div>
                                            <div id="sit_option_addfrm"><?php include_once(G5_ADMIN_PATH . '/shop_admin/itemsupply.php'); ?></div>

                                            <script>
                                                $(function() {
                                                    <?php if ($it['it_id'] && $ps_run) { ?>
                                                        // 추가옵션의 항목 설정
                                                        var arr_subj = new Array();
                                                        var subj, spl;

                                                        $("input[name='spl_subject[]']").each(function() {
                                                            subj = $.trim($(this).val());
                                                            if (subj && $.inArray(subj, arr_subj) == -1)
                                                                arr_subj.push(subj);
                                                        });

                                                        for (i = 0; i < arr_subj.length; i++) {
                                                            var arr_spl = new Array();
                                                            $(".spl-subject-cell").each(function(index) {
                                                                subj = $(this).text();
                                                                if (subj == arr_subj[i]) {
                                                                    spl = $(".spl-cell:eq(" + index + ")").text();
                                                                    arr_spl.push(spl);
                                                                }
                                                            });

                                                            $("input[name='spl[]']:eq(" + i + ")").val(arr_spl.join());
                                                        }
                                                    <?php } ?>
                                                    // 입력필드추가
                                                    $("#add_supply_row").click(function() {
                                                        var $el = $("#sit_supply_frm tr:last");
                                                        var fld = "<tr>\n";
                                                        fld += "<th scope=\"row\">\n";
                                                        fld += "<label for=\"\">추가</label>\n";
                                                        fld += "<input type=\"text\" name=\"spl_subject[]\" value=\"\" class=\"frm_input\" size=\"15\">\n";
                                                        fld += "</th>\n";
                                                        fld += "<td>\n";
                                                        fld += "<label for=\"\"><b>추가 항목</b></label>\n";
                                                        fld += "<input type=\"text\" name=\"spl[]\" value=\"\" class=\"frm_input\" size=\"40\">\n";
                                                        fld += "<button type=\"button\" id=\"del_supply_row\" class=\"btn btn_04\">삭제</button>\n";
                                                        fld += "</td>\n";
                                                        fld += "</tr>";

                                                        $el.after(fld);

                                                        supply_sequence();
                                                    });

                                                    // 입력필드삭제
                                                    $(document).on("click", "#del_supply_row", function() {
                                                        $(this).closest("tr").remove();

                                                        supply_sequence();
                                                    });

                                                    // 옵션목록생성
                                                    $("#supply_table_create").click(function() {
                                                        var it_id = $.trim($("input[name=it_id]").val());
                                                        var subject = new Array();
                                                        var supply = new Array();
                                                        var subj, spl;
                                                        var count = 0;
                                                        var $el_subj = $("input[name='spl_subject[]']");
                                                        var $el_spl = $("input[name='spl[]']");
                                                        var $supply_table = $("#sit_option_addfrm");

                                                        $el_subj.each(function(index) {
                                                            subj = $.trim($(this).val());
                                                            spl = $.trim($el_spl.eq(index).val());

                                                            if (subj && spl) {
                                                                subject.push(subj);
                                                                supply.push(spl);
                                                                count++;
                                                            }
                                                        });

                                                        if (!count) {
                                                            alert("추가옵션명과 추가옵션항목을 입력해 주십시오.");
                                                            return false;
                                                        }

                                                        $.post(
                                                            "<?php echo G5_ADMIN_URL; ?>/shop_admin/itemsupply.php", {
                                                                it_id: it_id,
                                                                w: "<?php echo $w; ?>",
                                                                'subject[]': subject,
                                                                'supply[]': supply
                                                            },
                                                            function(data) {
                                                                $supply_table.empty().html(data);
                                                            }
                                                        );
                                                    });

                                                    // 모두선택
                                                    $(document).on("click", "input[name=spl_chk_all]", function() {
                                                        if ($(this).is(":checked")) {
                                                            $("input[name='spl_chk[]']").attr("checked", true);
                                                        } else {
                                                            $("input[name='spl_chk[]']").attr("checked", false);
                                                        }
                                                    });

                                                    // 선택삭제
                                                    $(document).on("click", "#sel_supply_delete", function() {
                                                        var $el = $("input[name='spl_chk[]']:checked");
                                                        if ($el.length < 1) {
                                                            alert("삭제하려는 옵션을 하나 이상 선택해 주십시오.");
                                                            return false;
                                                        }

                                                        $el.closest("tr").remove();
                                                    });

                                                    // 일괄적용
                                                    $(document).on("click", "#spl_value_apply", function() {
                                                        if ($(".spl_com_chk:checked").length < 1) {
                                                            alert("일괄 수정할 항목을 하나이상 체크해 주십시오.");
                                                            return false;
                                                        }

                                                        var spl_price = $.trim($("#spl_com_price").val());
                                                        var spl_stock = $.trim($("#spl_com_stock").val());
                                                        var spl_noti = $.trim($("#spl_com_noti").val());
                                                        var spl_use = $("#spl_com_use").val();
                                                        var $el = $("input[name='spl_chk[]']:checked");

                                                        // 체크된 옵션이 있으면 체크된 것만 적용
                                                        if ($el.length > 0) {
                                                            var $tr;
                                                            $el.each(function() {
                                                                $tr = $(this).closest("tr");

                                                                if ($("#spl_com_price_chk").is(":checked"))
                                                                    $tr.find("input[name='spl_price[]']").val(spl_price);

                                                                if ($("#spl_com_stock_chk").is(":checked"))
                                                                    $tr.find("input[name='spl_stock_qty[]']").val(spl_stock);

                                                                if ($("#spl_com_noti_chk").is(":checked"))
                                                                    $tr.find("input[name='spl_noti_qty[]']").val(spl_noti);

                                                                if ($("#spl_com_use_chk").is(":checked"))
                                                                    $tr.find("select[name='spl_use[]']").val(spl_use);
                                                            });
                                                        } else {
                                                            if ($("#spl_com_price_chk").is(":checked"))
                                                                $("input[name='spl_price[]']").val(spl_price);

                                                            if ($("#spl_com_stock_chk").is(":checked"))
                                                                $("input[name='spl_stock_qty[]']").val(spl_stock);

                                                            if ($("#spl_com_noti_chk").is(":checked"))
                                                                $("input[name='spl_noti_qty[]']").val(spl_noti);

                                                            if ($("#spl_com_use_chk").is(":checked"))
                                                                $("select[name='spl_use[]']").val(spl_use);
                                                        }
                                                    });
                                                });

                                                function supply_sequence() {
                                                    var $tr = $("#sit_supply_frm tr");
                                                    var seq;
                                                    var th_label, td_label;

                                                    $tr.each(function(index) {
                                                        seq = index + 1;
                                                        $(this).find("th label").attr("for", "spl_subject_" + seq).text("추가" + seq);
                                                        $(this).find("th input").attr("id", "spl_subject_" + seq);
                                                        $(this).find("td label").attr("for", "spl_item_" + seq);
                                                        $(this).find("td label b").text("추가" + seq + " 항목");
                                                        $(this).find("td input").attr("id", "spl_item_" + seq);
                                                    });
                                                }
                                            </script>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </form>
            </div>

            <div class="modal-foot">
                <button class="btn" data-action="close-modal">닫기</button>
                <button class="btn danger" data-action="review-form" data-kind="product" data-decision="revision" data-id="<?php echo get_text($inspect_item['it_id']); ?>">보완 요청</button>
                <button class="btn danger" data-action="review-form" data-kind="product" data-decision="rejected" data-id="<?php echo get_text($inspect_item['it_id']); ?>">거절</button>
                <button class="btn primary" data-action="approve-product" data-id="<?php echo get_text($inspect_item['it_id']); ?>">승인·판매</button>
            </div>
        </section>
    </div>
</div>

<script>
    // HTML DOM을 모두 읽은 뒤 한 번 실행되는 함수들
    $(function() {
        $('[data-action="product-detail"]').on('click', function() {
            $('#modal-root').removeClass('hidden');
        });

        $('#modal-root [data-action="close-modal"]').on('click', function() {
            $('#modal-root').addClass('hidden');
        });

        $('#modal-root .modal').on('click', function(event) {
            event.stopPropagation();
        });
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

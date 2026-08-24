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
$inspect_item_id = '1786348186';

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

                <button type="button" id="inspect-image-toggle" class="mb-4 flex w-40 aspect-square items-center justify-center overflow-hidden rounded-xl border border-gray-200 bg-gray-50 transition-all duration-400">
                    <?php echo get_it_image($it['it_id'], 800, 800, false, 'inspect-item-image', ' alt="' . get_text($it['it_name']) . '"', true); ?>
                </button>

                <dl class="detail text-xs">
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

                <div class="warning text-xs">플랫폼 승인 시 판매 상태가 ‘판매중’으로 바뀌고 브랜드 상품 목록에 승인 결과가 반영됩니다.</div>

                <form name="fitemform" autocomplete="off" onsubmit="return false;">
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

                        <div class="tbl_frm01 tbl_wrap text-xs border border-gray-200 rounded-xl overflow-hidden mt-4">
                            <table class="border-collapse [&_tbody]:divide-y [&_tbody]:divide-gray-200 [&_th]:border-r [&_th]:border-gray-200 [&_tr>td:nth-child(2)]:border-r [&_tr>td:nth-child(2)]:border-gray-200">
                                <caption>상품분류 입력</caption>
                                <colgroup>
                                    <col class="grid_4">
                                    <col>
                                    <col class="grid_3">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th scope="row">
                                            <label for="ca_id">기본분류</label>
                                        </th>
                                        <td>
                                            <?php $category_path = get_shop_category_path($it['ca_id'], ' > '); ?>
                                            <input type="hidden" name="ca_id" value="<?php echo get_text($it['ca_id']); ?>">
                                            <span class="font-medium text-gray-900"><?php echo get_text($category_path ?: '미지정'); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_ca_id" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="ca_id" id="review_ca_id">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <?php for ($i = 2; $i <= 3; $i++) { ?>
                                        <tr>
                                            <th scope="row">
                                                <?php echo $i; ?>차 분류
                                            </th>
                                            <td>
                                                <?php
                                                $category_key = 'ca_id' . $i;
                                                $category_path = get_shop_category_path($it[$category_key], ' > ');
                                                ?>
                                                <span class="font-medium text-gray-900"><?php echo get_text($category_path ?: '미지정'); ?></span>
                                            </td>
                                            <td class="text-center">
                                                <label for="review_ca_id_<?php echo $i; ?>" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                    <input type="checkbox" name="review_fields[]" value="<?php echo $category_key; ?>" id="review_ca_id_<?php echo $i; ?>">
                                                    <span>보완 요청</span>
                                                </label>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>
                    </section>

                    <section id="anc_sitfrm_ini">
                        <h2 class="h2_frm">기본정보</h2>
                        <div class="tbl_frm01 tbl_wrap text-xs border border-gray-200 rounded-xl overflow-hidden mt-4">
                            <table class="border-collapse [&_tbody]:divide-y [&_tbody]:divide-gray-200 [&_th]:border-r [&_th]:border-gray-200 [&_tr>td:nth-child(2)]:border-r [&_tr>td:nth-child(2)]:border-gray-200">
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
                                        </th>
                                        <td>
                                            <span class="font-medium text-gray-900"><?php echo get_text($it['it_id']); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_id" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_id" id="review_it_id">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <label for="it_name">상품명</label>
                                        </th>
                                        <td>
                                            <span class="font-medium text-gray-900"><?php echo get_text($it['it_name']); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_name" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_name" id="review_it_name">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            기본설명
                                        </th>
                                        <td>
                                            <span class="block whitespace-pre-wrap text-gray-900"><?php echo get_text($it['it_basic']); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_basic" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_basic" id="review_it_basic">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            출력순서
                                        </th>
                                        <td>
                                            <span class="text-gray-900"><?php echo get_text($it['it_order']); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_order" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_order" id="review_it_order">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            제조사
                                        </th>
                                        <td>
                                            <span class="text-gray-900"><?php echo get_text($it['it_maker']); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_maker" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_maker" id="review_it_maker">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            원산지
                                        </th>
                                        <td>
                                            <span class="text-gray-900"><?php echo get_text($it['it_origin']); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_origin" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_origin" id="review_it_origin">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            브랜드
                                        </th>
                                        <td>
                                            <span class="text-gray-900"><?php echo get_text($it['it_brand']); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_brand" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_brand" id="review_it_brand">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            모델
                                        </th>
                                        <td>
                                            <span class="text-gray-900"><?php echo get_text($it['it_model']); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_model" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_model" id="review_it_model">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <label for="it_tel_inq">전화문의</label>
                                        </th>
                                        <td>
                                            <?php echo help("상품 금액 대신 전화문의로 표시됩니다."); ?>
                                            <input type="checkbox" name="it_tel_inq" value="1" id="it_tel_inq" <?php echo ($it['it_tel_inq']) ? "checked" : ""; ?>> 예
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_tel_inq" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_tel_inq" id="review_it_tel_inq">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <label for="it_use">판매가능</label>
                                        </th>
                                        <td>
                                            <?php echo help("잠시 판매를 중단하거나 재고가 없을 경우에 체크를 해제해 놓으면 출력되지 않으며, 주문도 받지 않습니다."); ?>
                                            <input type="checkbox" name="it_use" value="1" id="it_use" <?php echo ($it['it_use']) ? "checked" : ""; ?>> 예
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_use" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_use" id="review_it_use">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <label for="it_nocoupon">쿠폰적용안함</label>
                                        </th>
                                        <td>
                                            <?php echo help("설정에 체크하시면 쿠폰 생성 때 상품 검색 결과에 노출되지 않습니다."); ?>
                                            <input type="checkbox" name="it_nocoupon" value="1" id="it_nocoupon" <?php echo ($it['it_nocoupon']) ? "checked" : ""; ?>> 예
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_nocoupon" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_nocoupon" id="review_it_nocoupon">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            <label for="ec_mall_pid">네이버쇼핑 상품ID</label>
                                        </th>
                                        <td>
                                            <?php echo help("네이버쇼핑에 입점한 경우 네이버쇼핑 상품ID를 입력하시면 네이버페이와 연동됩니다.<br>일부 쇼핑몰의 경우 네이버쇼핑 상품ID 대신 쇼핑몰 상품ID를 입력해야 하는 경우가 있습니다.<br>네이버페이 연동과정에서 이 부분에 대한 안내가 이뤄지니 안내받은 대로 값을 입력하시면 됩니다."); ?>
                                            <input type="text" name="ec_mall_pid" value="<?php echo get_text($it['ec_mall_pid']); ?>" id="ec_mall_pid" class="frm_input" size="20">
                                        </td>
                                        <td class="text-center">
                                            <label for="review_ec_mall_pid" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="ec_mall_pid" id="review_ec_mall_pid">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            상품설명
                                        </th>
                                        <td>
                                            <div class="max-w-full overflow-x-auto break-words [&_img]:h-auto [&_img]:max-w-full">
                                                <?php echo conv_content($it['it_explan'], 1); ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_explan" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_explan" id="review_it_explan">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            모바일 상품설명
                                        </th>
                                        <td>
                                            <div class="max-w-full overflow-x-auto break-words [&_img]:h-auto [&_img]:max-w-full">
                                                <?php echo conv_content($it['it_mobile_explan'], 1); ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_mobile_explan" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_mobile_explan" id="review_it_mobile_explan">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            판매자 e-mail
                                        </th>
                                        <td>
                                            <span class="text-gray-900"><?php echo get_text($it['it_sell_email']); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_sell_email" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_sell_email" id="review_it_sell_email">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            상점메모
                                        </th>
                                        <td>
                                            <span class="block whitespace-pre-wrap text-gray-900"><?php echo get_text($it['it_shop_memo']); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_shop_memo" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_shop_memo" id="review_it_shop_memo">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <section id="anc_sitfrm_compact">
                        <h2 class="h2_frm">상품요약정보</h2>
                        <div class="local_desc02 local_desc text-xs border border-gray-200 rounded-xl overflow-hidden mt-4">
                            <p><strong>전자상거래 등에서의 상품 등의 정보제공에 관한 고시</strong>에 따라 총 35개 상품군에 대해 상품 특성 등을 양식에 따라 입력할 수 있습니다.</p>
                        </div>

                        <div id="sit_compact">
                            <?php
                            $info_gubun = isset($it['it_info_gubun']) ? $it['it_info_gubun'] : '';
                            $info_gubun_title = isset($item_info[$info_gubun]['title']) ? $item_info[$info_gubun]['title'] : '';
                            ?>
                            <span class="font-medium text-gray-900"><?php echo get_text($info_gubun_title); ?></span>
                        </div>

                        <div id="sit_compact_fields">
                            <?php
                            $info_value = array();

                            if (isset($it['it_info_value']) && $it['it_info_value']) {
                                $info_value = unserialize($it['it_info_value']);
                            }

                            $gubun = $it['it_info_gubun'];
                            $article = isset($item_info[$gubun]['article']) ? $item_info[$gubun]['article'] : array();
                            ?>

                            <div class="tbl_frm01 tbl_wrap text-xs border border-gray-200 rounded-xl overflow-hidden mt-4">
                                <table class="border-collapse [&_tbody]:divide-y [&_tbody]:divide-gray-200 [&_th]:border-r [&_th]:border-gray-200 [&_tr>td:nth-child(2)]:border-r [&_tr>td:nth-child(2)]:border-gray-200">
                                    <caption>상품요약정보 입력</caption>
                                    <colgroup>
                                        <col class="grid_4">
                                        <col>
                                        <col class="grid_3">
                                    </colgroup>
                                    <tbody>
                                        <?php foreach ($article as $key => $value) { ?>
                                            <?php
                                            $el_name = $key;
                                            $el_title = $value[0];
                                            $el_value = '';

                                            if ($gubun === $it['it_info_gubun'] && isset($info_value[$key])) {
                                                $el_value = $info_value[$key];
                                            }
                                            ?>
                                            <tr>
                                                <th scope="row">
                                                    <?php echo get_text($el_title); ?>
                                                </th>
                                                <td>
                                                    <span class="block whitespace-pre-wrap text-gray-900"><?php echo get_text($el_value); ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <label for="review_it_info_<?php echo $el_name; ?>" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                        <input type="checkbox" name="review_fields[]" value="it_info_value_<?php echo $el_name; ?>" id="review_it_info_<?php echo $el_name; ?>">
                                                        <span>보완 요청</span>
                                                    </label>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>

                    <section id="anc_sitfrm_cost">
                        <h2 class="h2_frm">가격 및 재고</h2>

                        <div class="tbl_frm01 tbl_wrap text-xs border border-gray-200 rounded-xl overflow-hidden mt-4">
                            <table class="border-collapse [&_tbody]:divide-y [&_tbody]:divide-gray-200 [&_th]:border-r [&_th]:border-gray-200 [&_tr>td:nth-child(2)]:border-r [&_tr>td:nth-child(2)]:border-gray-200">
                                <caption>가격 및 재고 입력</caption>
                                <colgroup>
                                    <col class="grid_4">
                                    <col>
                                    <col class="grid_3">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th scope="row">
                                            판매가격
                                        </th>
                                        <td>
                                            <span class="text-gray-900"><?php echo number_format((int) $it['it_price']); ?>원</span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_price" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_price" id="review_it_price">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            시중가격
                                        </th>
                                        <td>
                                            <?php if ((int) $it['it_cust_price']) { ?>
                                                <span class="text-gray-900"><?php echo number_format((int) $it['it_cust_price']); ?>원</span>
                                            <?php } ?>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_cust_price" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_cust_price" id="review_it_cust_price">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">
                                            포인트 유형
                                        </th>
                                        <td>
                                            <?php
                                            $point_type_labels = array(
                                                '0' => '설정금액',
                                                '1' => '판매가기준 설정비율',
                                                '2' => '구매가기준 설정비율',
                                            );
                                            $point_type_label = isset($point_type_labels[$it['it_point_type']]) ? $point_type_labels[$it['it_point_type']] : '';
                                            ?>
                                            <span class="text-gray-900"><?php echo get_text($point_type_label); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_point_type" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_point_type" id="review_it_point_type">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">포인트</th>
                                        <td>
                                            <?php $point_unit = $it['it_point_type'] ? '%' : '점'; ?>
                                            <span class="text-gray-900"><?php echo number_format((int) $it['it_point']); ?><?php echo $point_unit; ?></span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_point" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_point" id="review_it_point">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">추가옵션상품 포인트</th>
                                        <td>
                                            <span class="text-gray-900"><?php echo number_format((int) $it['it_supply_point']); ?>점</span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_supply_point" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_supply_point" id="review_it_supply_point">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">상품품절</th>
                                        <td>
                                            <span class="text-gray-900"><?php echo $it['it_soldout'] ? '예' : '아니오'; ?></span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_soldout" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_soldout" id="review_it_soldout">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">재입고SMS 알림</th>
                                        <td>
                                            <span class="text-gray-900"><?php echo $it['it_stock_sms'] ? '예' : '아니오'; ?></span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_stock_sms" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_stock_sms" id="review_it_stock_sms">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">재고수량</th>
                                        <td>
                                            <span class="text-gray-900"><?php echo number_format((int) $it['it_stock_qty']); ?>개</span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_stock_qty" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_stock_qty" id="review_it_stock_qty">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">재고 통보수량</th>
                                        <td>
                                            <span class="text-gray-900"><?php echo number_format((int) $it['it_noti_qty']); ?>개</span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_noti_qty" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_noti_qty" id="review_it_noti_qty">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">최소구매수량</th>
                                        <td>
                                            <span class="text-gray-900"><?php echo number_format((int) $it['it_buy_min_qty']); ?>개</span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_buy_min_qty" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_buy_min_qty" id="review_it_buy_min_qty">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">최대구매수량</th>
                                        <td>
                                            <span class="text-gray-900"><?php echo number_format((int) $it['it_buy_max_qty']); ?>개</span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_buy_max_qty" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_buy_max_qty" id="review_it_buy_max_qty">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th scope="row">상품과세 유형</th>
                                        <td>
                                            <span class="text-gray-900"><?php echo $it['it_notax'] ? '비과세' : '과세'; ?></span>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_notax" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_notax" id="review_it_notax">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <?php
                                    $option_subjects = explode(',', $it['it_option_subject']);
                                    $option_items = array();

                                    foreach ($option_subjects as $index => $option_subject) {
                                        $option_items[$index] = array();
                                    }

                                    $option_sql = " select io_id
                                                    from {$g5['g5_shop_item_option_table']}
                                                    where it_id = '" . sql_real_escape_string($it['it_id']) . "'
                                                    and io_type = '0'
                                                    order by io_no asc ";
                                    $option_result = sql_query($option_sql);

                                    for ($i = 0; $option_row = sql_fetch_array($option_result); $i++) {
                                        $option_values = explode(chr(30), $option_row['io_id']);

                                        foreach ($option_subjects as $index => $option_subject) {
                                            $option_value = isset($option_values[$index]) ? $option_values[$index] : '';

                                            if ($option_value !== '' && !in_array($option_value, $option_items[$index], true)) {
                                                $option_items[$index][] = $option_value;
                                            }
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <th scope="row">상품선택옵션</th>
                                        <td>
                                            <div class="space-y-3">
                                                <?php foreach ($option_subjects as $index => $option_subject) { ?>
                                                    <?php if ($option_subject === '') continue; ?>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900"><?php echo get_text($option_subject); ?></p>
                                                        <p class="mt-1 text-gray-700"><?php echo get_text(implode(', ', $option_items[$index])); ?></p>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_option" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_option" id="review_it_option">
                                                <span>보완 요청</span>
                                            </label>
                                        </td>
                                    </tr>

                                    <?php
                                    $supply_subjects = explode(',', $it['it_supply_subject']);
                                    $supply_items = array();

                                    foreach ($supply_subjects as $supply_subject) {
                                        if ($supply_subject !== '') {
                                            $supply_items[$supply_subject] = array();
                                        }
                                    }

                                    $supply_sql = " select io_id
                                                    from {$g5['g5_shop_item_option_table']}
                                                    where it_id = '" . sql_real_escape_string($it['it_id']) . "'
                                                    and io_type = '1'
                                                    order by io_no asc ";
                                    $supply_result = sql_query($supply_sql);

                                    for ($i = 0; $supply_row = sql_fetch_array($supply_result); $i++) {
                                        $supply_values = explode(chr(30), $supply_row['io_id']);
                                        $supply_subject = isset($supply_values[0]) ? $supply_values[0] : '';
                                        $supply_item = isset($supply_values[1]) ? $supply_values[1] : '';

                                        if ($supply_subject === '') {
                                            continue;
                                        }

                                        if (!isset($supply_items[$supply_subject])) {
                                            $supply_items[$supply_subject] = array();
                                        }

                                        if ($supply_item !== '' && !in_array($supply_item, $supply_items[$supply_subject], true)) {
                                            $supply_items[$supply_subject][] = $supply_item;
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <th scope="row">상품추가옵션</th>
                                        <td>
                                            <div class="space-y-3">
                                                <?php foreach ($supply_items as $supply_subject => $supply_values) { ?>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900"><?php echo get_text($supply_subject); ?></p>
                                                        <p class="mt-1 text-gray-700"><?php echo get_text(implode(', ', $supply_values)); ?></p>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <label for="review_it_supply" class="inline-flex items-center justify-center gap-1 text-xs font-normal">
                                                <input type="checkbox" name="review_fields[]" value="it_supply" id="review_it_supply">
                                                <span>보완 요청</span>
                                            </label>
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

        // 상품 검수 이미지 확대 축소 토글
        $('#inspect-image-toggle').on('click', function() {
            $(this).toggleClass('w-40 w-200');
        });
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

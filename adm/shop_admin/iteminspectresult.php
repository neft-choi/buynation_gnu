<?php
$sub_menu = '400320';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

add_stylesheet('<link rel="stylesheet" href="' . G5_ADMIN_URL . '/shop_admin/css/brand.css?ver=' . G5_CSS_VER . '">', 100);

$brand_id = isset($member['mb_id']) ? trim($member['mb_id']) : '';
$brand_id_sql = sql_real_escape_string($brand_id);

$brand = sql_fetch("
    select brand_id
    from donuts_brand
    where brand_id = '{$brand_id_sql}'
    limit 1
");

$g5['title'] = '상품검수(브랜드)';

include_once(G5_ADMIN_PATH . '/admin.head.php');
?>

<section id="view" class="content relative">
    <div class="head flex items-center justify-between mb-4">
        <div>
            <h2>상품 검수</h2>
            <p>신규 상품은 플랫폼 승인 전 쇼핑몰에 노출되지 않으며, 결과를 이곳에서 처리합니다.</p>
        </div>
        <div class="actions">
            <button class="btn primary" data-new-product="">+ 신규 상품 등록</button>
        </div>
    </div>
    <div class="process five">
        <div class="process-step done"><span class="step-no">1</span><b>임시저장</b><small>브랜드가 상품정보 작성</small></div>
        <div class="process-step done"><span class="step-no">2</span><b>검수 요청</b><small>필수값·배송그룹 확인 후 제출</small></div>
        <div class="process-step active"><span class="step-no">3</span><b>플랫폼 심사</b><small>상품정보·이미지·정책 검토</small></div>
        <div class="process-step"><span class="step-no">4</span><b>보완 요청</b><small>필요할 때만 수정·재신청</small></div>
        <div class="process-step"><span class="step-no">5</span><b>승인·판매</b><small>승인된 상품만 판매 활성화</small></div>
    </div>
    <div class="stats" style="margin-top:15px">
        <div class="stat "><span>임시저장</span><strong>0건</strong><small>아직 플랫폼 미제출</small></div>
        <div class="stat alert"><span>플랫폼 심사중</span><strong>1건</strong><small>브랜드 수정 제한</small></div>
        <div class="stat alert"><span>보완 요청</span><strong>2건</strong><small>수정 후 재신청</small></div>
        <div class="stat "><span>승인</span><strong>7건</strong><small>판매 가능</small></div>
    </div>
    <div class="toolbar"><select id="approvalFilter">
            <option>전체</option>
            <option>임시저장</option>
            <option>심사 중</option>
            <option>보완 요청</option>
            <option>거절</option>
            <option>승인</option>
        </select></div>
    <div class="card">
        <div class="tw">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>상품</th>
                        <th>요청번호</th>
                        <th>요청일</th>
                        <th>심사 상태</th>
                        <th>플랫폼 결과</th>
                        <th>판매상태</th>
                        <th>관리</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="name">유기농 그래놀라 500g<span class="sub">P-801 · GT-GR-500</span></td>
                        <td>-</td>
                        <td>-</td>
                        <td><span class="badge green">승인</span></td>
                        <td>결과 대기</td>
                        <td><span class="badge green">판매중</span></td>
                        <td>
                            <div class="actions"><button class="btn sm" data-product-approval-detail="P-801">상세</button></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="name">저당 단백질바 12개입<span class="sub">P-802 · GT-PB-012</span></td>
                        <td>-</td>
                        <td>-</td>
                        <td><span class="badge green">승인</span></td>
                        <td>결과 대기</td>
                        <td><span class="badge green">판매중</span></td>
                        <td>
                            <div class="actions"><button class="btn sm" data-product-approval-detail="P-802">상세</button></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="name">프리미엄 냉장 샐러드 6팩<span class="sub">P-803 · GT-CS-006</span></td>
                        <td>-</td>
                        <td>-</td>
                        <td><span class="badge green">승인</span></td>
                        <td>결과 대기</td>
                        <td><span class="badge green">판매중</span></td>
                        <td>
                            <div class="actions"><button class="btn sm" data-product-approval-detail="P-803">상세</button></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="name">선물용 대형 패키지<span class="sub">P-804 · GT-GF-001</span></td>
                        <td>-</td>
                        <td>-</td>
                        <td><span class="badge green">승인</span></td>
                        <td>결과 대기</td>
                        <td><span class="badge green">판매중</span></td>
                        <td>
                            <div class="actions"><button class="btn sm" data-product-approval-detail="P-804">상세</button></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="name">콜드프레스 주스 12병<span class="sub">P-805 · GT-JC-012</span></td>
                        <td>-</td>
                        <td>-</td>
                        <td><span class="badge green">승인</span></td>
                        <td>결과 대기</td>
                        <td><span class="badge green">판매중</span></td>
                        <td>
                            <div class="actions"><button class="btn sm" data-product-approval-detail="P-805">상세</button></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="name">견과 데일리팩 30봉<span class="sub">P-806 · GT-NM-030</span></td>
                        <td>-</td>
                        <td>-</td>
                        <td><span class="badge green">승인</span></td>
                        <td>결과 대기</td>
                        <td><span class="badge red">품절</span></td>
                        <td>
                            <div class="actions"><button class="btn sm" data-product-approval-detail="P-806">상세</button></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="name">신제품 수프 체험팩<span class="sub">P-807 · GT-SP-001</span></td>
                        <td>PRD-RV-260811-07</td>
                        <td>2026-08-11 10:40</td>
                        <td><span class="badge blue">심사 중</span></td>
                        <td>결과 대기</td>
                        <td><span class="badge yellow">판매대기</span></td>
                        <td>
                            <div class="actions"><button class="btn sm" data-product-approval-detail="P-807">상세</button><button class="btn sm" data-cancel-product-review="P-807">요청 취소</button></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="name">정기배송 혼합박스 24개입<span class="sub">P-808 · GT-BX-024</span></td>
                        <td>-</td>
                        <td>-</td>
                        <td><span class="badge green">승인</span></td>
                        <td>결과 대기</td>
                        <td><span class="badge green">판매중</span></td>
                        <td>
                            <div class="actions"><button class="btn sm" data-product-approval-detail="P-808">상세</button></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="name">저염 버섯수프 3팩<span class="sub">P-809 · GT-MS-003</span></td>
                        <td>PRD-RV-260810-03</td>
                        <td>2026-08-12 09:15</td>
                        <td><span class="badge red">보완 요청</span></td>
                        <td><span class="danger">원재료 및 알레르기 표시 이미지의 글자를 확인하기 어렵습니다.</span></td>
                        <td><span class="badge yellow">판매대기</span></td>
                        <td>
                            <div class="actions"><button class="btn sm" data-product-approval-detail="P-809">상세</button><button class="btn sm" data-product="P-809">수정·재신청</button></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="name">상온 크림 샘플팩<span class="sub">P-810 · GT-CR-001</span></td>
                        <td>PRD-RV-260809-02</td>
                        <td>2026-08-09 13:20</td>
                        <td><span class="badge red">거절</span></td>
                        <td><span class="danger">상품명과 제출한 상세 이미지의 상품명이 일치하지 않습니다.</span></td>
                        <td><span class="badge yellow">판매대기</span></td>
                        <td>
                            <div class="actions"><button class="btn sm" data-product-approval-detail="P-810">상세</button><button class="btn sm" data-product="P-810">수정·재신청</button></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="notice blue" style="margin-top:14px">
        <b>판매 반영 기준:</b> 임시저장·심사 중·보완 요청·거절 상품은 판매대기 상태를 유지합니다. 플랫폼 관리자가 승인하면 검수상태가 ‘승인’으로 바뀌고 판매 설정을 활성화할 수 있습니다. 브랜드 관리자에는 자체 승인 버튼을 두지 않았습니다.
    </div>

    <div id="new-product-modal" class="modal-bg hidden z-1002">
        <!-- 신규 상품 등록 모달 -->
        <div class="modal wide absolute">
            <div class="modal-head">
                <div>
                    <h3>상품 등록</h3>
                </div><button data-close="" aria-label="닫기">×</button>
            </div>
            <div class="modal-body">
                <div class="form">
                    <label class="field full"><span>상품명</span><input id="pn" value=""></label>
                    <label class="field"><span>SKU</span><input id="psku" value="" placeholder="브랜드 내부 관리코드"></label>
                    <label class="field"><span>카테고리</span><input id="pcategory" value=""></label>
                    <label class="field"><span>옵션명</span><input id="poption" value="기본"></label>
                    <label class="field"><span>판매 유형</span><select id="ptype">
                            <option selected="">일반</option>
                            <option>핫딜</option>
                            <option>추가 토핑</option>
                        </select></label>
                    <label class="field"><span>정상 판매가</span><input id="pprice" type="number" min="0" value=""></label>
                    <label class="field"><span>핫딜 판매가</span><input id="pcampaignPrice" type="number" min="0" value="0">
                        <div class="help">핫딜 유형에서만 사용합니다.</div>
                    </label>
                    <label class="field"><span>기본 기여율(%)</span><input id="pbase" type="number" min="0" max="100" step=".1" value="3.2">
                        <div class="help">정책 기준 3.2%이며 기본 판매수수료에 포함됩니다. 브랜드 정산에서 다시 차감하지 않습니다. 등록한 기본 기여율은 모든 도넛에 동일하게 적용됩니다.</div>
                    </label>
                    <label class="field"><span>추가 기여율(%)</span><input id="pextra" type="number" min="0" max="100" step=".1" value="0">
                        <div class="help">추가 토핑 유형에서만 사용합니다.</div>
                    </label>
                    <label class="field"><span>실재고</span><input id="pstock" type="number" min="0" value="0"></label>
                    <label class="field"><span>안전재고</span><input id="psafety" type="number" min="0" value="5"></label>
                    <label class="field"><span>배송그룹</span><select id="pgroup">
                            <option value="">미배정</option>
                            <option value="G1">기본 배송그룹 · 묶음</option>
                            <option value="G2">냉장 묶음배송 · 묶음</option>
                            <option value="G3">대형 개별배송 · 개별</option>
                            <option value="G4">정기배송 전용 · 묶음</option>
                        </select></label>
                    <label class="field"><span>판매상태</span><select id="pstatus">
                            <option>판매중</option>
                            <option selected="">판매대기</option>
                            <option>판매중지</option>
                            <option>품절</option>
                        </select></label>
                    <label class="field full"><span>도티 추천 자격</span><select id="pscope">
                            <option selected="">전체</option>
                            <option>카테고리: 건강·다이어트</option>
                            <option>지정 도넛: 테니스 커뮤니티</option>
                        </select>
                        <div class="help">추가 토핑 상품에만 적용되며 자동 추천 등록이 아닌 추천 가능한 범위입니다.</div>
                    </label>
                    <label class="field full"><span>저장·심사 방식</span><select id="pSubmitMode">
                            <option value="review">플랫폼 검수 요청</option>
                            <option value="draft">임시저장</option>
                        </select>
                        <div class="help">검수 요청 후에는 플랫폼 처리 결과가 나오기 전까지 상품정보를 수정할 수 없습니다.</div>
                    </label>
                </div>
            </div>

            <div class="modal-foot">
                <button class="btn" data-close="">닫기</button>
                <button class="btn primary" data-confirm="">상품 저장</button>
            </div>
        </div>
    </div>

    <div id="approval-detail-modal" class="modal-bg hidden z-1002">
        <!-- 상품 검수 상세 모달 -->
        <div class="modal wide absolute">
            <div class="modal-head">
                <div>
                    <h3>상품 검수 상세</h3>
                </div><button data-close="" aria-label="닫기">×</button>
            </div>
            <div class="modal-body">
                <div class="approval-summary">
                    <div><b>유기농 그래놀라 500g</b><small>P-801 · GT-GR-500</small></div><span class="badge green">승인</span>
                </div>
                <div class="summary-list">
                    <div class="summary-row"><span>요청번호</span><b>-</b></div>
                    <div class="summary-row"><span>검수 요청일</span><b>-</b></div>
                    <div class="summary-row"><span>플랫폼 처리일</span><b>-</b></div>
                    <div class="summary-row"><span>판매 상태</span><b><span class="badge green">판매중</span></b></div>
                    <div class="summary-row"><span>배송그룹</span><b>기본 배송그룹</b></div>
                </div>
                <div class="timeline" style="margin-top:15px">
                    <div class="timeline-item"><b>승인</b><small>플랫폼 승인 완료 · 판매 설정 가능</small></div>
                </div>
                <div class="callout">승인·보완요청·거절는 플랫폼 관리자에서만 처리됩니다. 브랜드 관리자는 결과 확인, 보완 수정, 재신청을 수행합니다.</div>
            </div>
            <div class="modal-foot"><button class="btn" data-close="">닫기</button></div>
        </div>
    </div>
</section>

<script>
    $(
        function() {
            // 신규 상품 등록 모달
            $('[data-new-product]').on('click', function() {
                $('#new-product-modal').removeClass('hidden');
            });

            // 상품 별 상세 모달
            $('[data-product-approval-detail]').on('click', function() {
                $('#approval-detail-modal').removeClass('hidden');
            });

            // 모달 닫기
            $('.modal-bg [data-close]').on('click', function() {
                $('.modal-bg').addClass('hidden');
            });

            // 외부 클릭 시 모달 닫기
            $('.modal-bg').on('click', function(event) {
                if (event.target === this) {
                    $(this).addClass('hidden');
                }
            });
        }
    );
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

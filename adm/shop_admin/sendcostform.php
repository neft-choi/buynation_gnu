     <style>
         :root {
             --bg: #f5f6f8;
             --surface: #ffffff;
             --surface-2: #fafafa;
             --line: #e7e9ed;
             --line-strong: #d8dce2;
             --text: #1c2028;
             --muted: #717782;
             --muted-2: #9aa0aa;
             --brand: #ff6a3d;
             --brand-dark: #e84d21;
             --brand-soft: #fff1eb;
             --green: #178a5b;
             --green-soft: #eaf8f1;
             --blue: #4169e1;
             --blue-soft: #eef2ff;
             --yellow: #9b6600;
             --yellow-soft: #fff8df;
             --red: #dc3f4b;
             --shadow: 0 12px 32px rgba(24, 29, 39, .08);
             --radius: 14px;
         }

         .register-main {
             min-width: 0;
             display: flex;
             flex-direction: column;
         }

         /* .register-screen {
             flex: 1;
             padding: 28px 30px 22px;
         } */

         .register-screen.active {
             display: block;
         }

         .register-screen-head {
             margin-bottom: 24px;
         }

         .register-screen-head h2 {
             margin-bottom: 5px;
             font-size: 20px;
             letter-spacing: -.03em;
         }

         .register-screen-head p {
             margin-bottom: 0;
             color: var(--muted);
             font-size: 12px;
         }

         .section-title {
             margin-bottom: 11px;
             display: flex;
             align-items: center;
             justify-content: space-between;
             font-size: 13px;
             font-weight: 800;
         }

         .condition-select-grid {
             display: grid;
             grid-template-columns: repeat(2, 1fr);
             gap: 10px;
         }

         .register-condition {
             position: relative;
             min-height: 90px;
             padding: 14px 42px 14px 14px;
             border: 1px solid var(--line-strong);
             border-radius: 11px;
             background: #fff;
             cursor: pointer;
         }

         .register-condition:hover {
             border-color: #bfc4cc;
         }

         .register-condition.selected {
             border-color: var(--brand);
             background: #fffaf8;
             box-shadow: 0 0 0 2px rgba(255, 106, 61, .08);
         }

         .register-condition strong {
             display: block;
             margin-bottom: 4px;
             font-size: 13px;
         }

         .register-condition small {
             display: block;
             color: var(--muted);
             font-size: 11px;
         }

         .badge {
             min-height: 22px;
             padding: 2px 7px;
             display: inline-flex;
             align-items: center;
             border-radius: 999px;
             color: #5a616b;
             background: #f0f1f3;
             font-size: 10px;
             font-weight: 780;
             white-space: nowrap;
         }

         .badge-base {
             color: var(--brand-dark);
             background: var(--brand-soft);
         }

         .independent-box {
             margin-top: 18px;
             padding: 16px;
             display: grid;
             grid-template-columns: 1fr 1fr;
             gap: 14px;
             border: 1px solid #dce2f6;
             border-radius: 11px;
             background: #f6f8ff;
         }

         .independent-part {
             padding-right: 10px;
         }

         .independent-part+.independent-part {
             padding-left: 14px;
             padding-right: 0;
             border-left: 1px solid #dce2f6;
         }

         .independent-part span {
             display: block;
             color: #7380a1;
             font-size: 10px;
             font-weight: 750;
         }

         .independent-part strong {
             display: block;
             margin: 3px 0;
             font-size: 12px;
         }

         .independent-part small {
             color: #6f7890;
             font-size: 10px;
         }

         .group-select-box {
             margin-top: 20px;
             padding-top: 20px;
             border-top: 1px solid var(--line);
         }

         .group-select-head {
             margin-bottom: 10px;
             display: flex;
             align-items: flex-start;
             justify-content: space-between;
             gap: 12px;
         }

         .group-select-head strong {
             display: block;
             font-size: 13px;
         }

         .group-select-head small {
             color: var(--muted);
             font-size: 10px;
         }

         .form-control {
             width: 100%;
             height: 44px;
             padding: 0 13px;
             border: 1px solid var(--line-strong);
             border-radius: 9px;
             color: var(--text);
             background: #fff;
         }

         .calc-result {
             margin-top: 10px;
             padding: 13px 14px;
             display: flex;
             align-items: flex-start;
             gap: 10px;
             border: 1px solid #f0e2b0;
             border-radius: 9px;
             color: #69531a;
             background: var(--yellow-soft);
             font-size: 11px;
         }

         .calc-result.grouped {
             color: #286549;
             border-color: #cfe8db;
             background: #f0faf5;
         }

         .info-dot {
             width: 18px;
             height: 18px;
             display: grid;
             place-items: center;
             flex: none;
             border: 1px solid currentColor;
             border-radius: 50%;
             font-size: 11px;
             font-weight: 800;
         }
     </style>

     <div class="register-screen">
         <div class="register-screen-head">
             <h2>배송설정</h2>
             <p>배송조건으로 금액을 정하고, 배송그룹 선택 여부로 묶음·개별을 결정합니다.</p>
         </div>
         <div class="section-title">배송조건</div>
         <div class="condition-select-grid" id="registerConditions">
             <button class="register-condition selected" type="button" data-supported="true" data-sc-type="2" data-sc-method="0" data-sc-price="3000" data-sc-minimum="50000" data-sc-qty="0" data-condition="기본 택배"><strong>기본 택배 <span class="badge badge-base">기본</span></strong><small>3,000원 · 50,000원 이상 구매 시 무료</small></button>
             <button class="register-condition" type="button" data-supported="false" data-condition="금액 구간별 배송"><strong>금액 구간별 배송</strong><small>0~1만원 3,000원 · 1~2만원 4,000원 · 2만원 이상 무료</small></button>
             <button class="register-condition" type="button" data-supported="true" data-sc-type="1" data-sc-method="0" data-sc-price="0" data-sc-minimum="0" data-sc-qty="0" data-condition="무료배송"><strong>무료배송</strong><small>주문금액과 관계없이 배송비 무료</small></button>
             <button class="register-condition" type="button" data-supported="true" data-sc-type="3" data-sc-method="0" data-sc-price="4000" data-sc-minimum="0" data-sc-qty="0" data-condition="냉장 기본"><strong>냉장 기본</strong><small>4,000원 · 제주 지역 3,000원 추가</small></button>
             <button class="register-condition" type="button" data-supported="true" data-sc-type="3" data-sc-method="0" data-sc-price="12000" data-sc-minimum="0" data-sc-qty="0" data-condition="대형배송"><strong>대형배송</strong><small>12,000원 · 그룹 미선택 시 개별 부과</small></button>
             <button class="register-condition" type="button" data-supported="true" data-sc-type="4" data-sc-method="0" data-sc-price="3500" data-sc-minimum="0" data-sc-qty="2" data-condition="수량별 배송"><strong>수량별 배송</strong><small>3,500원 · 상품 2개마다 반복 부과</small></button>
         </div>

         <div class="independent-box">
             <div class="independent-part"><span>배송조건 · 필수</span><strong>얼마를 부과할지 결정</strong><small>고정·무료·조건부·수량별·금액 구간별 정책</small></div>
             <div class="independent-part"><span>배송그룹 · 선택</span><strong>묶음 또는 개별을 결정</strong><small>선택하면 MIN/MAX 묶음, 미선택이면 개별 합산</small></div>
         </div>

         <div class="group-select-box">
             <div class="group-select-head">
                 <div><strong>묶음배송 그룹</strong><small>필요한 경우에만 선택합니다.</small></div><span class="badge badge-warning">선택사항</span>
             </div>
             <select name="shipping_group_id" id="regGroup" class="form-control">
                 <option value="0">선택 안 함 — 개별배송</option>
                 <option value="1">일반상품 묶음그룹 — MAX</option>
                 <option value="2">냉장배송 A — MAX</option>
                 <option value="3">냉장배송 B — MAX</option>
                 <option value="4">냉장배송 C — MIN</option>
             </select>
             <div class="calc-result" id="regCalcResult"><span class="info-dot">i</span><span><strong>개별배송으로 등록됩니다.</strong><br>배송그룹을 선택하지 않았으므로 이 상품의 배송비가 다른 상품과 별도로 합산됩니다.</span></div>
         </div>
     </div>

     <script>
         // 배송 조건 카드 클릭 시, 기존 배송비 전송 필드에 동기화
         function bindRegisterCondition(card) {
             $(card).on("click", function() {
                 $("#registerConditions .register-condition").removeClass("selected");
                 $(this).addClass("selected");

                 // it_sc_type는 배송유형, it_sc_method는 선불 후불, it_sc_price는 기본 배송비, it_sc_minimum 배송비 무료 최소 주문 금액 기준, 
                 $("#it_sc_type").val($(this).attr("data-sc-type"));
                 $("#it_sc_method").val($(this).attr("data-sc-method"));
                 $("#it_sc_price").val($(this).attr("data-sc-price"));
                 $("#it_sc_minimum").val($(this).attr("data-sc-minimum"));
                 $("#it_sc_qty").val($(this).attr("data-sc-qty"));
             });
         }

         document.querySelectorAll('#registerConditions .register-condition').forEach(bindRegisterCondition);

         <?php if ($w === '') { ?>
             $("#registerConditions .register-condition.selected").trigger("click");
         <?php } ?>
     </script>
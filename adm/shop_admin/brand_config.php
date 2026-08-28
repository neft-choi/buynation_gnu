<?php
$sub_menu = '400210';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '브랜드 정보';
include_once(G5_ADMIN_PATH . '/admin.head.php');
?>

<section class="space-y-4">
    <div class="flex items-center justify-between">
        <p id="brand-form-description">공개 프로필·사업자 연락처·정산계좌·배송 기본정보를 관리합니다.</p>
        <button type="submit" form="fbrand" class="rounded-lg text-white font-bold bg-gray-900 px-3 py-2">저장</button>
    </div>

    <form id="fbrand" method="post" autocomplete="off" aria-describedby="brand-form-description">
        <div class="border border-gray-300 rounded-lg p-4 space-y-4">
            <div class="grid grid-cols-1 pc:grid-cols-2 gap-4">
                <div>
                    <label for="brand_name" class="block font-semibold">브랜드명</label>
                    <input type="text" id="brand_name" name="brand_name" placeholder="브랜드명" class="w-full border border-gray-300 rounded-lg p-3 mt-2">
                </div>
                <div>
                    <label for="brand_category" class="block font-semibold">대표 카테고리</label>
                    <input type="text" id="brand_category" name="brand_category" placeholder="대표 카테고리" class="w-full border border-gray-300 rounded-lg p-3 mt-2">
                </div>
            </div>

            <div>
                <label for="brand_intro" class="block font-semibold">브랜드 소개</label>
                <textarea id="brand_intro" name="brand_intro" rows="5" placeholder="브랜드 소개" class="w-full border border-gray-300 rounded-lg p-3 mt-2"></textarea>
            </div>

            <div class="grid grid-cols-1 pc:grid-cols-2 gap-4">
                <div>
                    <label for="brand_manager_name" class="block font-semibold">담당자</label>
                    <input type="text" id="brand_manager_name" name="brand_manager_name" placeholder="담당자" class="w-full border border-gray-300 rounded-lg p-3 mt-2">
                </div>
                <div>
                    <label for="brand_customer_tel" class="block font-semibold">고객센터 전화</label>
                    <input type="tel" id="brand_customer_tel" name="brand_customer_tel" placeholder="고객센터 전화" class="w-full border border-gray-300 rounded-lg p-3 mt-2">
                </div>
                <div>
                    <label for="brand_customer_email" class="block font-semibold">고객센터 이메일</label>
                    <input type="email" id="brand_customer_email" name="brand_customer_email" placeholder="고객센터 이메일" class="w-full border border-gray-300 rounded-lg p-3 mt-2">
                </div>
                <div>
                    <label for="brand_business_no" class="block font-semibold">사업자등록번호</label>
                    <input type="text" id="brand_business_no" name="brand_business_no" value="123-45-*****" readonly aria-describedby="brand-business-no-guide" class="w-full border border-gray-300 rounded-lg p-3 mt-2 cursor-not-allowed bg-gray-200 text-gray-400">
                    <p id="brand-business-no-guide" class="text-2xs text-gray-500 mt-2">사업자번호 변경은 플랫폼 관리자 심사 대상입니다.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 pc:grid-cols-2 gap-4">
                <div>
                    <label for="brand_settlement_bank" class="block font-semibold">정산은행</label>
                    <input type="text" id="brand_settlement_bank" name="brand_settlement_bank" placeholder="정산은행" class="w-full border border-gray-300 rounded-lg p-3 mt-2">
                </div>
                <div>
                    <label for="brand_settlement_account" class="block font-semibold">정산계좌</label>
                    <input type="text" id="brand_settlement_account" name="brand_settlement_account" placeholder="정산계좌" class="w-full border border-gray-300 rounded-lg p-3 mt-2">
                </div>
                <div>
                    <label for="brand_account_holder" class="block font-semibold">예금주</label>
                    <input type="text" id="brand_account_holder" name="brand_account_holder" placeholder="예금주" class="w-full border border-gray-300 rounded-lg p-3 mt-2">
                </div>
            </div>
        </div>
    </form>

    <div class="text-2xs text-blue-800 bg-blue-50 rounded-lg p-3">
        <p>브랜드 관리자 역할의 쇼핑몰 화면은 읽기 전용입니다. 구매·좋아요·커뮤니티 활동은 같은 계정의 도트 역할로 전환해야 합니다.</p>
    </div>
</section>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

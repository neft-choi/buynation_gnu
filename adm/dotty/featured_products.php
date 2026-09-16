<?php
$sub_menu = '740100';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '추천상품 현황';
require_once '../admin.head.php';
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="text-gray-400">쇼핑 상품을 골라 도넛의 추천상품 영역에 등록합니다.</p>

        <button type="button" id="featured-guide-modal-open" class="shrink-0 border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
            <span>추천 · 기여 구조 안내</span>
        </button>
    </div>

    <div class="mt-4 rounded-lg bg-amber-50 p-3">
        <span class="font-bold text-blue-600">추천 정책</span>
        <span class="ml-2 text-gray-600">일반·핫딜 상품은 결제 시 도트가 기여 도넛을 선택합니다. 추가 토핑 상품은 추천한 테니스 커뮤니티(으)로 기여 도넛이 고정됩니다.</span>
    </div>

    <div class="mt-4 flex flex-col gap-2 pc:flex-row pc:items-center">
        <div class="flex shrink-0 rounded-lg bg-gray-100 p-1">
            <button type="button" aria-pressed="true" class="featured-product-tab rounded-lg bg-white px-3 py-2 font-bold text-gray-900">
                등록한 추천상품 2
            </button>

            <button type="button" aria-pressed="false" class="featured-product-tab rounded-lg px-3 py-2 text-gray-600">
                상품 검색
            </button>
        </div>

        <div class="flex min-w-0 flex-1 items-center rounded-lg border border-gray-300 bg-white">
            <input type="search" id="featured-product-search-input" aria-label="상품 검색" class="min-w-0 flex-1 rounded-lg px-3 py-2 outline-none" placeholder="상품명, 브랜드명 검색">

            <button type="button" aria-label="상품 검색 실행" class="shrink-0 px-3 py-2 text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.35-4.35" />
                </svg>
            </button>
        </div>

        <span id="featured-product-search-result" class="shrink-0 text-2xs text-gray-500">검색 결과 2개</span>
    </div>

    <ul id="featured-product-list" class="mt-4 grid grid-cols-1 pc:grid-cols-3 gap-4">
        <li>
            <article class="relative rounded-lg border border-gray-300 bg-white">
                <div class="flex h-40 items-center justify-center rounded-lg bg-amber-100 text-5xl"></div>
                <span class="absolute top-2 left-2 inline-block rounded-lg bg-gray-900 px-2 py-1 text-2xs font-bold text-white">일반 상품</span>

                <div class="mt-3 px-3">
                    <span class="featured-product-brand text-2xs text-gray-400">그린테이블 · P-801</span>
                    <h3 class="featured-product-name mt-2 text-sm font-bold text-gray-900">유기농 그래놀라 500g</h3>
                    <p class="mt-2 font-bold text-gray-900">18,900원</p>
                </div>

                <div class="mt-3 px-3">
                    <div class="rounded-lg bg-amber-50 text-2xs text-gray-700 p-3">
                        기본 기여율 <span class="font-bold">3.2%</span>
                    </div>

                    <div class="mt-3 rounded-lg border border-blue-100 bg-blue-50 p-3">
                        <span class="block font-bold text-blue-700">추천 가능</span>
                        <p class="mt-1 text-2xs text-blue-500">일반 상품 · 모든 도넛 추천 가능</p>
                    </div>

                    <div class="mt-3 rounded-lg border border-amber-200 bg-amber-50 p-3">
                        <span class="block text-2xs font-bold text-amber-700">도티의 추천 이유</span>
                        <p class="mt-1 text-2xs text-gray-600">활동 전후 편하게 활용할 수 있어 테니스 커뮤니티 도트들에게 추천합니다.</p>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-3 gap-2 border-t border-gray-300 p-3">
                    <button type="button" class="featured-remove-modal-open rounded-lg border border-red-300 bg-white px-2 py-2 text-2xs font-bold text-red-600">
                        추천 해제
                    </button>
                    <button type="button" class="featured-reason-modal-open rounded-lg border border-gray-300 bg-white px-2 py-2 text-2xs font-bold text-gray-900">
                        이유 수정
                    </button>
                    <button type="button" class="featured-detail-modal-open rounded-lg border border-gray-300 bg-white px-2 py-2 text-2xs font-bold text-gray-900">
                        상세
                    </button>
                </div>
            </article>
        </li>

        <li>
            <article class="relative rounded-lg border border-gray-300 bg-white">
                <div class="flex h-40 items-center justify-center rounded-lg bg-amber-100 text-5xl"></div>
                <span class="absolute top-2 left-2 inline-block rounded-lg bg-gray-900 px-2 py-1 text-2xs font-bold text-white">핫딜 상품</span>

                <div class="mt-3 px-3">
                    <span class="featured-product-brand text-2xs text-gray-400">그린테이블 · P-802</span>
                    <h3 class="featured-product-name mt-2 text-sm font-bold text-gray-900">저당 단백질바 12개입</h3>
                    <p class="mt-2 font-bold text-gray-900">21,900원</p>
                </div>

                <div class="mt-3 px-3">
                    <div class="rounded-lg bg-amber-50 text-2xs text-gray-700 p-3">
                        기본 기여율 <span class="font-bold">3.2%</span>
                    </div>

                    <div class="mt-3 rounded-lg border border-blue-100 bg-blue-50 p-3">
                        <span class="block font-bold text-blue-700">추천 가능</span>
                        <p class="mt-1 text-2xs text-blue-500">핫딜 상품 · 모든 도넛 추천 가능</p>
                    </div>

                    <div class="mt-3 rounded-lg border border-amber-200 bg-amber-50 p-3">
                        <span class="block text-2xs font-bold text-amber-700">도티의 추천 이유</span>
                        <p class="mt-1 text-2xs text-gray-600">활동 전후 편하게 활용할 수 있어 테니스 커뮤니티 도트들에게 추천합니다.</p>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-3 gap-2 border-t border-gray-300 p-3">
                    <button type="button" class="featured-remove-modal-open rounded-lg border border-red-300 bg-white px-2 py-2 text-2xs font-bold text-red-600">
                        추천 해제
                    </button>
                    <button type="button" class="featured-reason-modal-open rounded-lg border border-gray-300 bg-white px-2 py-2 text-2xs font-bold text-gray-900">
                        이유 수정
                    </button>
                    <button type="button" class="featured-detail-modal-open rounded-lg border border-gray-300 bg-white px-2 py-2 text-2xs font-bold text-gray-900">
                        상세
                    </button>
                </div>
            </article>
        </li>
    </ul>
</section>

<!-- 추천 · 기여 구조 안내 모달 -->
<div id="featured-guide-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="featured-guide-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="featured-guide-modal-container" role="dialog" aria-modal="true" aria-labelledby="featured-guide-modal-title" class="relative z-10 max-h-[90vh] w-full max-w-160 overflow-y-auto rounded-lg bg-white">
        <div id="featured-guide-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="featured-guide-modal-title" class="text-lg font-bold text-gray-900">
                추천상품 · 기여 구조
            </h3>

            <button type="button" id="featured-guide-modal-close" aria-label="추천 · 기여 구조 안내 모달 닫기" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="featured-guide-modal-body" class="space-y-2 p-4">
            <div class="rounded-lg bg-gray-50 p-3">
                <span class="block text-2xs text-gray-400">도티의 추천 이유</span>
                <p class="mt-2 text-gray-900">추천상품 등록 시 커뮤니티의 활동 맥락과 상품의 장점을 연결한 추천 이유를 필수로 작성합니다. 등록 후에도 추천상품 카드와 상세에서 수정할 수 있습니다.</p>
            </div>

            <div class="rounded-lg bg-gray-50 p-3">
                <span class="block text-2xs text-gray-400">일반 상품</span>
                <p class="mt-2 text-gray-900">플랫폼 기본 기여율이 적용되며, 도트는 결제 단계에서 주문상품별 기여 도넛을 선택합니다.</p>
            </div>

            <div class="rounded-lg bg-gray-50 p-3">
                <span class="block text-2xs text-gray-400">핫딜 상품</span>
                <p class="mt-2 text-gray-900">운영 기간 동안 적용되는 판매가를 확인한 뒤 일반 상품과 동일하게 추천할 수 있습니다.</p>
            </div>

            <div class="rounded-lg bg-gray-50 p-3">
                <span class="block text-2xs text-gray-400">추가 토핑 상품</span>
                <p class="mt-2 text-gray-900">상품은 일반 쇼핑 목록에도 노출되지만, 추가 혜택율·대상은 자격이 맞는 도티 추천 흐름에서만 노출됩니다.</p>
            </div>

            <div class="rounded-lg bg-gray-50 p-3">
                <span class="block text-2xs text-gray-400">브랜드 지정 범위</span>
                <p class="mt-2 text-gray-900">브랜드가 전체 도넛, 카테고리, 지정 도넛 중 하나로 추천 자격을 설정합니다.</p>
            </div>

            <div class="rounded-lg bg-gray-50 p-3">
                <span class="block text-2xs text-gray-400">추천 · 일반 구매 차이</span>
                <p class="mt-2 text-gray-900">추천 경로는 해당 도넛으로 고정됩니다. 일반 경로에서는 결제 시 선택한 도넛이 브랜드 대상과 일치할 때만 추가 혜택이 적용됩니다.</p>
            </div>
        </div>

        <div id="featured-guide-modal-footer" class="sticky bottom-0 z-10 flex justify-end border-t border-gray-300 bg-white p-4">
            <button type="button" id="featured-guide-modal-cancel" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-bold text-gray-900">
                닫기
            </button>
        </div>
    </div>
</div>

<!-- 추천 해제 모달 -->
<div id="featured-remove-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="featured-remove-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="featured-remove-modal-container" role="dialog" aria-modal="true" aria-labelledby="featured-remove-modal-title" class="relative z-10 w-full max-w-120 rounded-lg bg-white">
        <div id="featured-remove-modal-header" class="flex justify-end border-b border-gray-300 p-3">
            <button type="button" id="featured-remove-modal-close" aria-label="추천 해제 모달 닫기" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="featured-remove-modal-body" class="px-4 py-6 text-center">
            <div class="mx-auto h-14 w-14 rounded-full bg-amber-100"></div>

            <h3 id="featured-remove-modal-title" class="mt-4 text-xl font-bold text-gray-900">
                추천을 해제할까요?
            </h3>

            <p class="mt-3 text-gray-500">
                상품 판매 상태는 유지되며 도넛 추천상품 영역에서만 내려갑니다.
            </p>
        </div>

        <div id="featured-remove-modal-footer" class="flex justify-end gap-2 border-t border-gray-300 p-4">
            <button type="button" id="featured-remove-modal-cancel" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-bold text-gray-900">
                취소
            </button>

            <button type="button" class="rounded-lg bg-amber-300 px-4 py-3 text-sm font-bold text-gray-900">
                추천 해제
            </button>
        </div>
    </div>
</div>

<!-- 추천 이유 수정 모달 -->
<div id="featured-reason-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="featured-reason-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="featured-reason-modal-container" role="dialog" aria-modal="true" aria-labelledby="featured-reason-modal-title" class="relative z-10 max-h-[90vh] w-full max-w-160 overflow-y-auto rounded-lg bg-white">
        <div id="featured-reason-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="featured-reason-modal-title" class="text-lg font-bold text-gray-900">
                도티의 추천 이유 수정
            </h3>

            <button type="button" id="featured-reason-modal-close" aria-label="추천 이유 수정 모달 닫기" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <form id="featured-reason-modal-form" class="p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-amber-300 text-2xl"></div>

                <div>
                    <p class="font-bold text-gray-900">유기농 그래놀라 500g</p>
                    <span class="mt-1 block text-2xs text-gray-400">그린테이블 · 18,900원</span>
                </div>
            </div>

            <div class="mt-4 rounded-lg bg-amber-50 p-3 text-2xs text-gray-700">
                <span class="font-bold text-blue-600">일반 상품</span>
                <span class="ml-2">일반 상품은 도트가 결제 단계에서 기여 도넛을 선택합니다.</span>
            </div>

            <div class="mt-4">
                <div class="flex items-center justify-between gap-3">
                    <label for="featured-reason-modal-text" class="font-bold text-gray-900">
                        도티의 추천 이유
                    </label>

                    <span class="text-2xs text-gray-400">필수 · 최대 180자</span>
                </div>

                <textarea id="featured-reason-modal-text" maxlength="180" required class="mt-2 h-36 w-full rounded-lg border border-gray-300 p-3">활동 전후 편하게 활용할 수 있어 테니스 커뮤니티 도트들에게 추천합니다.</textarea>

                <p class="mt-2 text-2xs text-gray-400">
                    상품의 특징과 커뮤니티 활동 맥락을 연결하면 도트가 추천 의도를 더 쉽게 이해할 수 있습니다.
                </p>
            </div>
        </form>

        <div id="featured-reason-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="featured-reason-modal-cancel" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-bold text-gray-900">
                취소
            </button>

            <button type="submit" form="featured-reason-modal-form" class="rounded-lg bg-amber-300 px-4 py-3 text-sm font-bold text-gray-900">
                추천 이유 저장
            </button>
        </div>
    </div>
</div>

</div>

<!-- 상품 상세 모달 -->
<div id="featured-detail-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="featured-detail-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="featured-detail-modal-container" role="dialog" aria-modal="true" aria-labelledby="featured-detail-modal-title" class="relative z-10 max-h-[90vh] w-full max-w-160 overflow-y-auto rounded-lg bg-white">
        <div id="featured-detail-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="featured-detail-modal-title" class="text-lg font-bold text-gray-900">
                상품 상세
            </h3>

            <button type="button" id="featured-detail-modal-close" aria-label="상품 상세 모달 닫기" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="featured-detail-modal-body" class="p-4">
            <div class="h-40 rounded-lg bg-amber-100"></div>

            <dl class="mt-4 overflow-hidden rounded-lg border border-gray-300">
                <div class="grid grid-cols-[110px_1fr] border-b border-gray-300">
                    <dt class="bg-gray-50 p-3 text-gray-500">상품</dt>
                    <dd class="p-3 font-bold text-gray-900">유기농 그래놀라 500g</dd>
                </div>

                <div class="grid grid-cols-[110px_1fr] border-b border-gray-300">
                    <dt class="bg-gray-50 p-3 text-gray-500">브랜드</dt>
                    <dd class="p-3 font-bold text-gray-900">그린테이블</dd>
                </div>

                <div class="grid grid-cols-[110px_1fr] border-b border-gray-300">
                    <dt class="bg-gray-50 p-3 text-gray-500">판매가</dt>
                    <dd class="p-3 font-bold text-gray-900">18,900원</dd>
                </div>

                <div class="grid grid-cols-[110px_1fr] border-b border-gray-300">
                    <dt class="bg-gray-50 p-3 text-gray-500">상품 구분</dt>
                    <dd class="p-3 font-bold text-gray-900">일반 상품</dd>
                </div>

                <div class="grid grid-cols-[110px_1fr] border-b border-gray-300">
                    <dt class="bg-gray-50 p-3 text-gray-500">기본 기여율</dt>
                    <dd class="p-3 font-bold text-gray-900">3.2% · 플랫폼 설정</dd>
                </div>

                <div class="grid grid-cols-[110px_1fr]">
                    <dt class="bg-gray-50 p-3 text-gray-500">배송 정책</dt>
                    <dd class="p-3 font-bold text-gray-900">기본 배송그룹 · 묶음배송</dd>
                </div>
            </dl>

            <div class="mt-4 rounded-lg bg-gray-100 p-3">
                <span class="block text-2xs text-gray-500">도티의 추천 이유</span>
                <p class="mt-2 text-gray-900">활동 전후 편하게 활용할 수 있어 테니스 커뮤니티 도트들에게 추천합니다.</p>
            </div>

            <div class="mt-4 rounded-lg bg-amber-50 p-3">
                <p class="text-2xs text-gray-600">배송비와 배송그룹은 브랜드가 설정하며 도티는 변경할 수 없습니다.</p>
            </div>
        </div>

        <div id="featured-detail-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="featured-detail-modal-cancel" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-bold text-gray-900">
                닫기
            </button>

            <button type="button" id="featured-detail-modal-reason-open" class="rounded-lg bg-amber-300 px-4 py-3 text-sm font-bold text-gray-900">
                추천 이유 수정
            </button>
        </div>
    </div>
</div>

<script>
    // 추천 · 기여 구조 안내 모달 열기 닫기
    $('#featured-guide-modal-open').on('click', function() {
        $('#featured-guide-modal').prop('hidden', false);
    });

    $('#featured-guide-modal-close, #featured-guide-modal-cancel, #featured-guide-modal-backdrop').on('click', function() {
        $('#featured-guide-modal').prop('hidden', true);
    });

    // 추천 해제 모달 열기 닫기
    $('.featured-remove-modal-open').on('click', function() {
        $('#featured-remove-modal').prop('hidden', false);
    });

    $('#featured-remove-modal-close, #featured-remove-modal-cancel, #featured-remove-modal-backdrop').on('click', function() {
        $('#featured-remove-modal').prop('hidden', true);
    });

    // 이유 수정 모달 열기 닫기
    $('.featured-reason-modal-open').on('click', function() {
        $('#featured-reason-modal').prop('hidden', false);
    });

    $('#featured-reason-modal-close, #featured-reason-modal-cancel, #featured-reason-modal-backdrop').on('click', function() {
        $('#featured-reason-modal').prop('hidden', true);
    });

    // 상세 모달 열기 닫기
    $('.featured-detail-modal-open').on('click', function() {
        $('#featured-detail-modal').prop('hidden', false);
    });

    $('#featured-detail-modal-close, #featured-detail-modal-cancel, #featured-detail-modal-backdrop').on('click', function() {
        $('#featured-detail-modal').prop('hidden', true);
    });

    // 상세 모달에서 이유 수정 모달 열기
    $('#featured-detail-modal-reason-open').on('click', function() {
        $('#featured-detail-modal').prop('hidden', true);
        $('#featured-reason-modal').prop('hidden', false);
    });

    // 검색 필터
    const $featuredProductSearchInput = $('#featured-product-search-input');
    const $featuredProductItems = $('#featured-product-list > li');
    const $featuredProductSearchResult = $('#featured-product-search-result');

    // on input으로 실시간 입력 감지
    $featuredProductSearchInput.on('input', function() {
        const keyword = $.trim($(this).val()).toLowerCase();
        let matchedCount = 0;

        // 상품 카드마다 반복 실행
        $featuredProductItems.each(function() {
            // 카드 안의 브랜드명과 상품명을 가져온다
            const searchText = $(this).find('.featured-product-brand, .featured-product-name').text().toLowerCase();

            // 검색 키워드가 브랜드명 또는 상품명에 포함되면 true, 없으면 false
            const isMatched = searchText.includes(keyword);

            // true이면 카드 보이기, false이면 카드 숨기기
            $(this).toggle(isMatched);

            // 검색된 카드 숫자 증가
            if (isMatched) {
                matchedCount += 1;
            }
        });

        // 현재 검색 결과 수 표시
        $featuredProductSearchResult.text('검색 결과 ' + matchedCount + '개');
    });

    // 추천상품 목록 선택 UI
    $('.featured-product-tab').on('click', function() {
        $('.featured-product-tab')
            .attr('aria-pressed', 'false')
            .removeClass('bg-white font-bold text-gray-900')
            .addClass('text-gray-600');

        $(this)
            .attr('aria-pressed', 'true')
            .removeClass('text-gray-600')
            .addClass('bg-white font-bold text-gray-900');
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

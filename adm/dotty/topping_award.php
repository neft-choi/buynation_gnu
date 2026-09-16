<?php
$sub_menu = '750100';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '토핑 지급';
require_once '../admin.head.php';
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="text-gray-400">커뮤니티 운영 목적에 따라 도티가 도트에게 사용할 수 있는 토핑을 보냅니다.</p>

        <button type="button" id="topping-award-modal-open" class="shrink-0 border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
            <span>+ 토핑 지급</span>
        </button>
    </div>

    <div class="mt-4 grid grid-cols-1 gap-3 pc:grid-cols-5">
        <div class="flex items-center justify-between rounded-lg bg-amber-100 p-6 pc:col-span-3">
            <div>
                <h3 class="text-sm text-gray-700">
                    현재 지급 가능한 토핑
                </h3>

                <div class="mt-3 flex items-end gap-2">
                    <p class="text-3xl font-bold text-gray-900">
                        850,000
                    </p>

                    <span class="mb-1 font-bold text-gray-700">
                        토핑
                    </span>
                </div>
            </div>

            <div aria-hidden="true" class="flex h-16 w-16 items-center justify-center rounded-full bg-amber-300 text-2xl font-bold text-gray-900">
                T
            </div>
        </div>

        <div class="rounded-lg border border-gray-300 bg-white p-6 pc:col-span-2">
            <h3 class="text-xl font-bold text-gray-900">
                이번 달 지급
            </h3>

            <div class="mt-4 flex items-end gap-2">
                <p class="text-3xl font-bold text-gray-900">
                    0
                </p>

                <span class="mb-1 font-bold text-gray-700">
                    토핑
                </span>
            </div>

            <p class="mt-2 text-2xs text-gray-400">
                0명의 도트에게 0회 지급
            </p>
        </div>
    </div>

    <div class="mt-4 rounded-lg bg-amber-50 p-3">
        <p class="text-gray-700">
            <span class="font-bold text-blue-600">적립 구분</span>
            <span class="ml-2">도티만 도넛 기여포인트를 개별·전체 균등·활동 조건으로 배분할 수 있습니다. 배분된 금액은 도트의 ‘사용가능토핑’이 되며, 원천 주문의 철회 가능 기간까지는 홀딩됩니다. 지정 운영자는 배분할 수 없습니다.</span>
        </p>
    </div>

    <div class="mt-4 flex flex-col gap-2 pc:flex-row pc:items-center">
        <div class="flex min-w-0 flex-1 items-center rounded-lg border border-gray-300 bg-white">
            <input type="search" id="topping-award-search-input" aria-label="배분 내역 검색" class="min-w-0 flex-1 rounded-lg px-3 py-2 outline-none" placeholder="도트명, 지급 사유 또는 지급번호 검색">

            <button type="button" id="topping-award-search-button" aria-label="배분 내역 검색 실행" class="shrink-0 px-3 py-2 text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.35-4.35" />
                </svg>
            </button>
        </div>

        <span id="topping-award-search-result" class="shrink-0 text-2xs text-gray-500">
            검색 결과 4건
        </span>
    </div>

    <section aria-labelledby="topping-award-list-title" class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <header class="border-b border-gray-300 p-4">
            <h3 id="topping-award-list-title" class="text-lg font-bold text-gray-900">
                배분 내역
            </h3>

            <p class="mt-2 text-2xs text-gray-400">
                도티의 배분 기준과 활동·사용 가능 상태를 함께 기록합니다.
            </p>
        </header>

        <div class="overflow-x-auto">
            <table class="border-collapse min-w-200 w-full table-fixed text-left">
                <colgroup>
                    <col class="w-[15%]">
                    <col class="w-[16%]">
                    <col class="w-[18%]">
                    <col class="w-[18%]">
                    <col class="w-[14%]">
                    <col class="w-[12%]">
                    <col class="w-[7%]">
                </colgroup>

                <thead class="bg-gray-50 text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">배분번호</th>
                        <th scope="col">배분일시</th>
                        <th scope="col">받은 도트</th>
                        <th scope="col">배분 기준</th>
                        <th scope="col">배분자</th>
                        <th scope="col">사용가능토핑</th>
                        <th scope="col">상태</th>
                    </tr>
                </thead>

                <tbody class="text-gray-900 [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:whitespace-nowrap [&_td]:px-4 [&_td]:py-3">
                    <tr class="topping-award-search-row">
                        <td>TOP-240803-081</td>
                        <td>2026.08.03 14:05</td>
                        <td class="font-bold">김도티 (DOT-39811)</td>
                        <td>8월 베스트 활동 보상</td>
                        <td>도티 김도윤</td>
                        <td class="text-right font-bold">5,000 토핑</td>
                        <td>사용 가능</td>
                    </tr>

                    <tr class="topping-award-search-row">
                        <td>TOP-240802-066</td>
                        <td>2026.08.02 18:30</td>
                        <td class="font-bold">최서진 (DOT-40277)</td>
                        <td>우수 후기 콘텐츠 보상</td>
                        <td>운영자 김도현</td>
                        <td class="text-right font-bold">3,000 토핑</td>
                        <td>사용 가능</td>
                    </tr>

                    <tr class="topping-award-search-row">
                        <td>TOP-240801-031</td>
                        <td>2026.08.01 10:15</td>
                        <td class="font-bold">이윤진 (DOT-28770)</td>
                        <td>정기 모임 운영 지원</td>
                        <td>도티 김도윤</td>
                        <td class="text-right font-bold">2,000 토핑</td>
                        <td>사용 가능</td>
                    </tr>

                    <tr class="topping-award-search-row">
                        <td>TOP-240729-014</td>
                        <td>2026.07.29 16:42</td>
                        <td class="font-bold">박성훈 (DOT-37114)</td>
                        <td>커뮤니티 이벤트 참여</td>
                        <td>운영자 김도현</td>
                        <td class="text-right font-bold">1,000 토핑</td>
                        <td>사용 가능</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<!-- 토핑 지급 모달 -->
<div id="topping-award-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="topping-award-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="topping-award-modal-container" role="dialog" aria-modal="true" aria-labelledby="topping-award-modal-title" class="relative z-10 max-h-[75vh] w-full max-w-160 overflow-y-auto rounded-lg bg-white">
        <div id="topping-award-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="topping-award-modal-title" class="text-lg font-bold text-gray-900">
                도트에게 사용가능토핑 배분
            </h3>

            <button type="button" id="topping-award-modal-close" aria-label="토핑 지급 모달 닫기" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <form id="topping-award-modal-form" class="p-4">
            <div class="flex items-center justify-between rounded-lg bg-amber-100 p-4">
                <div>
                    <span class="text-2xs text-gray-700">도넛 기여포인트 잔액</span>

                    <div class="mt-2 flex items-end gap-2">
                        <p class="text-2xl font-bold text-gray-900">850,000</p>
                        <span class="mb-1 text-2xs font-bold text-gray-700">포인트</span>
                    </div>
                </div>

                <div aria-hidden="true" class="flex h-14 w-14 items-center justify-center rounded-full bg-amber-300 text-xl font-bold text-gray-900">
                    T
                </div>
            </div>

            <div class="mt-4">
                <label for="topping-award-modal-method" class="font-bold text-gray-900">
                    배분 방식
                </label>

                <select id="topping-award-modal-method" class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-4 py-3">
                    <option>개별 배분</option>
                    <option>전체 균등 배분</option>
                    <option>활동 조건 배분</option>
                </select>
            </div>

            <div class="mt-4">
                <label for="topping-award-modal-dot" class="font-bold text-gray-900">
                    받는 도트
                </label>

                <select id="topping-award-modal-dot" class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-4 py-3">
                    <option>가입 도트를 선택하세요</option>
                    <option>홍길동님 · 홍길동 (DOT-48102)</option>
                    <option>스매시킬러 · 김도현 (DOT-41092)</option>
                    <option>박도티 · 박도티 (DOT-39811)</option>
                    <option>테니스유진 · 이유진 (DOT-28770)</option>
                </select>
            </div>

            <div class="mt-4">
                <label for="topping-award-modal-amount" class="font-bold text-gray-900">
                    배분 토핑
                </label>

                <input type="number" id="topping-award-modal-amount" min="1" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3" placeholder="배분할 토핑을 입력하세요">
            </div>

            <div class="mt-4">
                <label for="topping-award-modal-reason" class="font-bold text-gray-900">
                    배분 기준
                </label>

                <select id="topping-award-modal-reason" class="mt-2 w-full rounded-lg border border-gray-300 bg-white px-4 py-3">
                    <option>커뮤니티 이벤트 참여</option>
                    <option>우수 콘텐츠</option>
                    <option>운영 활동</option>
                    <option>기타</option>
                </select>
            </div>

            <div class="mt-4">
                <div class="flex items-center justify-between gap-3">
                    <label for="topping-award-modal-memo" class="font-bold text-gray-900">
                        메모
                    </label>

                    <span class="text-2xs text-gray-400">
                        선택 사항
                    </span>
                </div>

                <textarea id="topping-award-modal-memo" class="mt-2 h-28 w-full rounded-lg border border-gray-300 p-4" placeholder="배분 근거 메모"></textarea>
            </div>

            <div class="mt-4 rounded-lg bg-amber-100 p-3">
                <p class="text-2xs text-gray-700">
                    배분된 사용가능토핑은 원천 주문의 철회 가능 기간까지 홀딩되며, 이후 1년간 사용할 수 있습니다.
                </p>
            </div>
        </form>

        <div id="topping-award-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="topping-award-modal-cancel" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-bold text-gray-900">
                취소
            </button>

            <button type="submit" form="topping-award-modal-form" class="rounded-lg bg-amber-300 px-4 py-3 text-sm font-bold text-gray-900">
                배분 확인
            </button>
        </div>
    </div>
</div>

<script>
    // 토핑 지급 모달 열기 닫기
    $('#topping-award-modal-open').on('click', function() {
        $('#topping-award-modal').prop('hidden', false);
    });

    $('#topping-award-modal-close, #topping-award-modal-cancel, #topping-award-modal-backdrop').on('click', function() {
        $('#topping-award-modal').prop('hidden', true);
    });

    // 검색
    const $toppingAwardSearchInput = $('#topping-award-search-input');
    const $toppingAwardSearchRows = $('.topping-award-search-row');
    const $toppingAwardSearchResult = $('#topping-award-search-result');

    // on input으로 실시간 입력 감지
    $toppingAwardSearchInput.on('input', function() {
        const keyword = $.trim($(this).val()).toLowerCase();
        let matchedCount = 0;

        // 배분 내역 행마다 반복 실행
        $toppingAwardSearchRows.each(function() {
            // 배분번호, 받은 도트, 배분 기준의 글자를 가져온다
            const searchText = $(this).children('td:nth-child(1), td:nth-child(3), td:nth-child(4)').text().toLowerCase();

            // 검색 키워드가 대상 글자 안에 포함되면 true, 없으면 false
            const isMatched = searchText.includes(keyword);

            // true이면 행 보이기, false이면 행 숨기기
            $(this).toggle(isMatched);

            // 검색된 행 숫자 증가
            if (isMatched) {
                matchedCount += 1;
            }
        });

        // 현재 검색 결과 수 표시
        $toppingAwardSearchResult.text('검색 결과 ' + matchedCount + '건');
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

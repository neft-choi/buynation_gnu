<?php
$sub_menu = '930100';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '브랜드 서류 심사';
require_once '../../admin.head.php';
?>

<section>
    <h2 class="sr-only">브랜드 서류 심사</h2>

    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="text-gray-600 font-normal">사업자등록증·통신판매업 신고증·정산계좌 서류와 법인 해당 서류를 개별 확인합니다.</p>

        <button type="button" class="shrink-0 border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
            <span>심사 목록</span>
        </button>
    </div>

    <div class="mt-4 flex items-center gap-3 bg-amber-50 rounded-lg text-2xs p-3">
        <span class="text-amber-600 font-bold">심사 경계</span>
        <p class="text-gray-600">브랜드가 관리자에서 서류를 제출하면 플랫폼이 승인·보완·최종 반려합니다. 상품은 브랜드 서류 승인과 별도의 상품 검수를 통과해야 판매됩니다.</p>
    </div>

    <div id="brand-review-filters" role="group" aria-label="서류 심사 상태 필터" class="mt-4 flex flex-wrap gap-2">
        <button type="button" data-status="all" aria-pressed="true" class="rounded-full bg-gray-900 text-2xs font-bold text-white px-3 py-2">
            전체
        </button>
        <button type="button" data-status="pending" aria-pressed="false" class="rounded-full border border-gray-300 bg-white text-2xs text-gray-700 px-3 py-2">
            심사 대기
        </button>
        <button type="button" data-status="supplement" aria-pressed="false" class="rounded-full border border-gray-300 bg-white text-2xs text-gray-700 px-3 py-2">
            보완 요청
        </button>
        <button type="button" data-status="approved" aria-pressed="false" class="rounded-full border border-gray-300 bg-white text-2xs text-gray-700 px-3 py-2">
            승인
        </button>
        <button type="button" data-status="rejected" aria-pressed="false" class="rounded-full border border-gray-300 bg-white text-2xs text-gray-700 px-3 py-2">
            최종 거절
        </button>
    </div>

    <section class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <div class="overflow-x-auto">
            <table class="border-collapse min-w-250 w-full table-fixed text-left">
                <caption class="sr-only">브랜드 서류 심사 요청 목록</caption>

                <colgroup>
                    <col class="w-[30%]">
                    <col class="w-[12%]">
                    <col class="w-[15%]">
                    <col class="w-[8%]">
                    <col class="w-[8%]">
                    <col class="w-[15%]">
                    <col class="w-[12%]">
                </colgroup>

                <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">요청번호 / 브랜드</th>
                        <th scope="col">유형</th>
                        <th scope="col">제출</th>
                        <th scope="col">서류</th>
                        <th scope="col">SLA</th>
                        <th scope="col">상태</th>
                        <th scope="col">심사</th>
                    </tr>
                </thead>

                <tbody id="brand-review-list" class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:whitespace-nowrap [&_td]:px-4 [&_td]:py-3">
                    <tr data-status="pending">
                        <td>
                            <span class="block font-bold">노르딕홈</span>
                            <span class="block text-zinc-400">BR-VRF-2026-0811 · BRD-00182</span>
                        </td>
                        <td>신규 입점</td>
                        <td>2026.08.08 09:20</td>
                        <td>0/4</td>
                        <td>D-2</td>
                        <td><span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-700 px-2 py-1">● 심사 대기</span></td>
                        <td>
                            <button type="button" class="rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>

                    <tr data-status="pending">
                        <td>
                            <span class="block font-bold">그린리프</span>
                            <span class="block text-zinc-400">BR-VRF-2026-0809 · BRD-00179</span>
                        </td>
                        <td>신규 입점</td>
                        <td>2026.08.07 15:10</td>
                        <td>0/3</td>
                        <td>D-1</td>
                        <td><span class="rounded-full bg-amber-100 text-2xs font-bold text-amber-700 px-2 py-1">● 심사 대기</span></td>
                        <td>
                            <button type="button" class="rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>

                    <tr data-status="supplement">
                        <td>
                            <span class="block font-bold">그린테이블</span>
                            <span class="block text-zinc-400">BR-VRF-2026-0718 · BRD-00204</span>
                        </td>
                        <td>서류 변경</td>
                        <td>2026.08.08 11:05</td>
                        <td>3/4</td>
                        <td>D-1</td>
                        <td><span class="rounded-full bg-red-100 text-2xs font-bold text-red-600 px-2 py-1">● 보완 요청</span></td>
                        <td>
                            <button type="button" class="rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>

                    <tr data-status="approved">
                        <td>
                            <span class="block font-bold">몬스테리</span>
                            <span class="block text-zinc-400">BR-VRF-2026-0602 · BRD-00168</span>
                        </td>
                        <td>신규 입점</td>
                        <td>2026.06.10 11:22</td>
                        <td>3/3</td>
                        <td>완료</td>
                        <td><span class="rounded-full bg-emerald-50 text-2xs font-bold text-emerald-700 px-2 py-1">● 승인</span></td>
                        <td>
                            <button type="button" class="rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>

                    <tr data-status="rejected">
                        <td>
                            <span class="block font-bold">하루마켓</span>
                            <span class="block text-zinc-400">BR-VRF-2026-0521 · BRD-00155</span>
                        </td>
                        <td>신규 입점</td>
                        <td>2026.07.14 09:05</td>
                        <td>0/3</td>
                        <td>종료</td>
                        <td><span class="rounded-full bg-red-100 text-2xs font-bold text-red-600 px-2 py-1">● 최종 거절</span></td>
                        <td>
                            <button type="button" class="rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                검토
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<script>
    $('#brand-review-filters button').on('click', function() {
        const status = $(this).data('status');

        $('#brand-review-filters button')
            .attr('aria-pressed', 'false')
            .removeClass('bg-gray-900 font-bold text-white')
            .addClass('border border-gray-300 bg-white text-gray-700');

        $(this)
            .attr('aria-pressed', 'true')
            .removeClass('border border-gray-300 bg-white text-gray-700')
            .addClass('bg-gray-900 font-bold text-white');

        $('#brand-review-list tr').prop('hidden', false);

        if (status !== 'all') {
            $('#brand-review-list tr').not('[data-status="' + status + '"]').prop('hidden', true);
        }
    });
</script>

<?php
require_once '../../admin.tail.php';

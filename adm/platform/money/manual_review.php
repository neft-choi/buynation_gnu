<?php
$sub_menu = '960100';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '검토 기록';
require_once '../../admin.head.php';
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="text-gray-600 font-normal">처리 업무의 결과와 별도로 남긴 확인 근거를 한곳에서 봅니다.</p>

        <button type="button" class="shrink-0 w-fit border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
            <span>기록 내려받기</span>
        </button>
    </div>

    <div class="mt-4 flex items-center gap-3 bg-blue-50 rounded-lg text-2xs p-3">
        <span class="text-blue-600 font-bold">기록 화면</span>
        <p class="text-blue-700 font-normal">새 사안은 처리 업무함에 개별 건으로 생성됩니다. 이 화면에서는 완료 결과와 추가 검토 메모만 조회합니다.</p>
    </div>

    <section class="mt-4 overflow-hidden rounded-lg border border-gray-300 bg-white">
        <header class="flex items-center justify-between gap-3 border-b border-gray-300 px-4 py-3">
            <div>
                <h3 class="text-sm font-bold text-gray-900">
                    처리 완료 기록
                </h3>

                <p class="mt-1 text-2xs text-gray-400">
                    처리 중에서 빠진 업무의 결과·담당자·근거
                </p>
            </div>

            <a href="<?php echo G5_ADMIN_URL . '/platform/work_queue.php'; ?>" class="shrink-0 inline-flex items-center gap-1 text-2xs text-gray-500">
                <span>처리 업무함</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right preview-icon w-3 h-3">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>
        </header>
        <div class="overflow-x-auto">
            <table class="border-collapse min-w-360 w-full table-fixed text-left">
                <caption class="sr-only">결제 대사 목록</caption>

                <colgroup>
                    <col class="w-[12%]">
                    <col class="w-[12%]">
                    <col class="w-[7%]">
                    <col class="w-[12%]">
                    <col class="w-[20%]">
                    <col class="w-[20%]">
                    <col class="w-[10%]">
                    <col class="w-[7%]">
                </colgroup>

                <thead class="bg-gray-50 text-2xs text-gray-500 [&_th]:whitespace-nowrap [&_th]:px-4 [&_th]:py-3 [&_th]:font-bold">
                    <tr>
                        <th scope="col">업무 ID / 항목</th>
                        <th scope="col">대상</th>
                        <th scope="col">처리 결과</th>
                        <th scope="col">전달 구분·대상</th>
                        <th scope="col">내부 처리 메모</th>
                        <th scope="col">상대방 전달 사유</th>
                        <th scope="col">완료</th>
                        <th scope="col">확인</th>
                    </tr>
                </thead>

                <tbody class="text-gray-900 text-2xs font-normal [&_tr]:border-t [&_tr]:border-gray-200 [&_td]:px-4 [&_td]:py-3">
                    <tr>
                        <td>
                            <span class="block font-bold">PRD-RV-260806-003</span>
                            <span class="block text-gray-400 font-bold">상품 검수</span>
                        </td>
                        <td>
                            <span class="block font-bold">패브릭 수납 바스켓 · P51188</span>
                        </td>
                        <td>
                            <span class="block w-fit rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 승인</span>
                        </td>
                        <td>
                            <span class="block w-fit rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">전달 필수</span>
                            <span class="block text-gray-400 font-normal">브랜드 · 노르딕홈 · 관리자 알림</span>
                        </td>
                        <td>
                            <span>필수 정보·배송그룹·표시사항 체크리스트 확인</span>
                        </td>
                        <td>
                            <span class="block">상품 검수가 완료되어 판매중으로 전환되었습니다.</span>
                            <span class="block text-gray-400 font-normal">전달 완료</span>
                        </td>
                        <td>
                            <span class="block">2026.08.11 09:18</span>
                            <span class="block text-gray-400 font-normal">상품 운영팀 박OO</span>
                        </td>
                        <td>
                            <button type="button" class="task-details-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">RPT-0218</span>
                            <span class="block text-gray-400 font-bold">커뮤니티 신고</span>
                        </td>
                        <td>
                            <span class="block font-bold">건강한 식탁 · 댓글 #9122</span>
                        </td>
                        <td>
                            <span class="block w-fit rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 완료</span>
                        </td>
                        <td>
                            <span class="block w-fit rounded-full bg-gray-100 text-2xs font-bold text-gray-600 px-2 py-1">내부 전용</span>
                            <span class="block text-gray-400 font-normal">내부 전용 · 없음</span>
                        </td>
                        <td>
                            <span>도티의 선조치와 증빙을 확인하여 플랫폼 개입 없이 종결</span>
                        </td>
                        <td>
                            <span class="block text-gray-400 font-normal">전달 없음</span>
                            <span class="block text-gray-400 font-normal">전달 없음</span>
                        </td>
                        <td>
                            <span class="block">2026.08.11 08:42</span>
                            <span class="block text-gray-400 font-normal">커뮤니티팀 이OO</span>
                        </td>
                        <td>
                            <button type="button" class="task-details-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">BR-VRF-260805-REJ</span>
                            <span class="block text-gray-400 font-bold">브랜드 사업자 서류</span>
                        </td>
                        <td>
                            <span class="block font-bold">샘플 브랜드 · BRD-00101</span>
                        </td>
                        <td>
                            <span class="block w-fit rounded-full bg-red-100 text-2xs font-bold text-red-600 px-2 py-1">● 반려</span>
                        </td>
                        <td>
                            <span class="block w-fit rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">전달 필수</span>
                            <span class="block text-gray-400 font-normal">브랜드 · 샘플 브랜드 · 관리자 알림·이메일</span>
                        </td>
                        <td>
                            <span>사업자등록증 대표자와 계좌 예금주 대조 완료</span>
                        </td>
                        <td>
                            <span class="block">등록 사업자와 정산계좌 예금주가 일치하지 않아 입점 신청이 반려되었습니다.</span>
                            <span class="block text-gray-400 font-normal">전달 완료</span>
                        </td>
                        <td>
                            <span class="block">2026.08.10 17:20</span>
                            <span class="block text-gray-400 font-normal">입점 심사팀 박OO</span>
                        </td>
                        <td>
                            <button type="button" class="task-details-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="block font-bold">SET-REVIEW-260810-04</span>
                            <span class="block text-gray-400 font-bold">도넛 정산 검토</span>
                        </td>
                        <td>
                            <span class="block font-bold">캠핑 위켄드 · 2026-07</span>
                        </td>
                        <td>
                            <span class="block w-fit rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 완료</span>
                        </td>
                        <td>
                            <span class="block w-fit rounded-full bg-blue-100 text-2xs font-bold text-blue-600 px-2 py-1">전달 선택</span>
                            <span class="block text-gray-400 font-normal">도티 · 캠핑 위켄드 · 관리자 알림</span>
                        </td>
                        <td>
                            <span>최소금액·사업자·계좌·차감 근거 확인</span>
                        </td>
                        <td>
                            <span class="block">7월 정산 검토가 완료되었습니다.</span>
                            <span class="block text-gray-400 font-normal">전달 완료</span>
                        </td>
                        <td>
                            <span class="block">2026.08.10 14:20</span>
                            <span class="block text-gray-400 font-normal">정산팀 김OO</span>
                        </td>
                        <td>
                            <button type="button" class="task-details-modal-open rounded-lg border border-gray-300 bg-white text-2xs font-bold text-gray-900 px-3 py-2">
                                상세
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</section>

<!-- 완료 업무 상세 모달 -->
<div id="task-details-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="task-details-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="task-details-modal-container" role="dialog" aria-modal="true" aria-labelledby="task-details-modal-title" class="relative z-10 w-full max-w-150 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div id="task-details-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="task-details-modal-title" class="text-sm font-bold text-gray-900">
                완료 업무 상세
            </h3>

            <button type="button" id="task-details-modal-close" aria-label="완료 업무 상세 모달 닫기" class="flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="task-details-modal-body" class="p-4">
            <div class="overflow-hidden rounded-lg border border-gray-300">
                <dl class="text-left text-2xs [&>div+div]:border-t [&>div+div]:border-gray-200">
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">업무 ID</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">PRD-RV-260806-003</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">업무 항목</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">상품 검수</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">대상</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">패브릭 수납 바스켓 · P51188</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">처리 결과</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">
                            <span class="block w-fit rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 승인</span>
                        </dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">처리자</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">상품 운영팀 박OO</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">완료 시각</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">2026.08.11 09:18</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">전달 구분</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">
                            <span class="block w-fit rounded-full bg-amber-100 text-2xs font-bold text-amber-600 px-2 py-1">전달 필수</span>
                        </dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">전달 대상</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">브랜드 · 노르딕홈 · 관리자 알림</dd>
                    </div>
                </dl>
            </div>

            <div class="mt-3 border border-gray-300 rounded-lg text-2xs p-3">
                <span class="block font-bold">내부 처리 메모</span>
                <p class="mt-1 text-gray-700 font-normal">필수 정보·배송그룹·표시사항 체크리스트 확인</p>
            </div>

            <div class="mt-3 border border-gray-300 rounded-lg text-2xs p-3">
                <span class="block font-bold">상대방 전달 사유</span>
                <p class="mt-1 text-gray-700 font-normal">상품 검수가 완료되어 판매중으로 전환되었습니다.</p>
                <span class="mt-1 block w-fit rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">전달 완료</span>
            </div>
        </div>

        <div id="task-details-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="task-details-modal-cancel" class="rounded-lg border border-gray-300 bg-white text-gray-900 font-bold px-4 py-3">
                닫기
            </button>
            <button type="button" class="rounded-lg border border-gray-300 bg-white text-gray-900 font-bold px-4 py-3">
                처리 로그 보기
            </button>
        </div>
    </div>
</div>

<script>
    // 완료 업무 상세 모달
    $('.task-details-modal-open').on('click', function() {
        $('#task-details-modal').prop('hidden', false);
    });

    $('#task-details-modal-close, #task-details-modal-cancel, #task-details-modal-backdrop').on('click', function() {
        $('#task-details-modal').prop('hidden', true);
    });
</script>

<?php
require_once '../../admin.tail.php';

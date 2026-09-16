<?php
$sub_menu = '760100';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '사업자 인증';
require_once '../admin.head.php';
?>

<section>
    <div class="flex flex-col pc:flex-row pc:items-center justify-between gap-3">
        <p class="text-gray-400">사업자 정보와 서류는 현재 선택한 도넛에만 적용됩니다.</p>

        <button type="button" id="business-account-modal-open" class="shrink-0 border border-gray-300 rounded-lg bg-white text-gray-900 font-bold px-3 py-2">
            <span>입금 계좌 변경</span>
        </button>
    </div>

    <div class="mt-4 flex items-center justify-between bg-emerald-50 border border-emerald-300 rounded-lg p-4">
        <div class="space-y-2">
            <p class="text-xl font-bold">사업자 인증이 완료되었습니다.</p>
            <p class="text-gray-500">2026.07.08 승인 · 등록 계좌로 도넛별 정산을 신청할 수 있습니다.</p>
        </div>

        <span class="bg-gray-50 rounded-full text-2xs font-bold px-2 py-1">● 인증 완료</span>
    </div>

    <div class="mt-4 flex gap-2 bg-blue-50 rounded-lg p-3">
        <span class="text-blue-500 font-bold">연결 식별자</span>
        <p class="text-gray-700">도넛 DONUT-TENNIS · 사업자 심사 DTV-TENNIS-260704</p>
    </div>

    <div class="mt-4 grid grid-cols-1 pc:grid-cols-4 gap-4">
        <div class="border border-gray-300 rounded-lg space-y-3 p-4">
            <span class="block text-gray-500 font-normal">도넛 유형</span>
            <span class="block text-2xl font-bold">사업자</span>
            <span class="block text-blue-500 font-normal">개설 시 선택</span>
        </div>

        <div class="border border-gray-300 rounded-lg space-y-3 p-4">
            <span class="block text-gray-500 font-normal">서류 제출 기한</span>
            <span class="block text-2xl font-bold">2026.07.15</span>
            <span class="block text-gray-500 font-normal">제출 2026.07.04</span>
        </div>

        <div class="border border-gray-300 rounded-lg space-y-3 p-4">
            <span class="block text-gray-500 font-normal">플랫폼 심사</span>
            <span class="block text-2xl font-bold">사업자 인증 완료</span>
            <span class="block text-blue-500 font-normal">현금 정산 가능</span>
        </div>

        <div class="border border-gray-300 rounded-lg space-y-3 p-4">
            <span class="block text-gray-500 font-normal">정산 가능 금액</span>
            <span class="block text-2xl font-bold">680,000<span class="ml-1 text-xs">원</span></span>
            <span class="block text-gray-500 font-normal">승인 완료 도넛만 정산 가능</span>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 gap-4 pc:grid-cols-5">
        <section aria-labelledby="business-information-title" class="rounded-lg border border-gray-300 bg-white p-4 pc:col-span-3">
            <h3 id="business-information-title" class="text-xl font-bold text-gray-900">
                사업자 정보
            </h3>

            <dl class="mt-4 grid grid-cols-1 gap-3 pc:grid-cols-2">
                <div>
                    <dt class="font-bold text-gray-900">사업자등록번호</dt>
                    <dd class="mt-2 rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-500">120-88-01984</dd>
                </div>

                <div>
                    <dt class="font-bold text-gray-900">상호·단체명</dt>
                    <dd class="mt-2 rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-500">테니스 커뮤니티 주식회사</dd>
                </div>

                <div>
                    <dt class="font-bold text-gray-900">대표자명</dt>
                    <dd class="mt-2 rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-500">김도윤</dd>
                </div>

                <div>
                    <dt class="font-bold text-gray-900">입금 은행</dt>
                    <dd class="mt-2 rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-500">신한은행</dd>
                </div>

                <div>
                    <dt class="font-bold text-gray-900">입금 계좌번호</dt>
                    <dd class="mt-2 rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-500">110-***-123456</dd>
                </div>

                <div>
                    <dt class="font-bold text-gray-900">예금주</dt>
                    <dd class="mt-2 rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-500">테니스 커뮤니티 주식회사</dd>
                </div>
            </dl>

            <h3 class="mt-6 text-xl font-bold text-gray-900">
                필수 서류
            </h3>

            <ul class="mt-4 space-y-2">
                <li class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                    <div class="flex min-w-0 items-center gap-3">
                        <div aria-hidden="true" class="h-10 w-10 shrink-0 rounded-lg bg-gray-100"></div>

                        <div class="min-w-0">
                            <span class="block font-bold text-gray-900">사업자등록증</span>
                            <span class="mt-1 block truncate text-2xs text-gray-400">사업자등록증_테니스커뮤니티.pdf</span>
                        </div>
                    </div>

                    <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                        <span aria-hidden="true" class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                        등록 완료
                    </span>
                </li>

                <li class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                    <div class="flex min-w-0 items-center gap-3">
                        <div aria-hidden="true" class="h-10 w-10 shrink-0 rounded-lg bg-gray-100"></div>

                        <div class="min-w-0">
                            <span class="block font-bold text-gray-900">통장 사본</span>
                            <span class="mt-1 block truncate text-2xs text-gray-400">신한은행_통장사본.pdf</span>
                        </div>
                    </div>

                    <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                        <span aria-hidden="true" class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                        등록 완료
                    </span>
                </li>

                <li class="flex items-center justify-between gap-3 rounded-lg border border-gray-300 p-3">
                    <div class="flex min-w-0 items-center gap-3">
                        <div aria-hidden="true" class="h-10 w-10 shrink-0 rounded-lg bg-gray-100"></div>

                        <div class="min-w-0">
                            <span class="block font-bold text-gray-900">대표자 확인 서류</span>
                            <span class="mt-1 block truncate text-2xs text-gray-400">대표자확인서.pdf</span>
                        </div>
                    </div>

                    <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                        <span aria-hidden="true" class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                        등록 완료
                    </span>
                </li>
            </ul>
        </section>

        <section aria-labelledby="business-process-title" class="rounded-lg border border-gray-300 bg-white p-4 pc:col-span-2">
            <h3 id="business-process-title" class="text-xl font-bold text-gray-900">
                인증 진행 과정
            </h3>

            <ol class="mt-4 ml-2 border-l border-gray-300">
                <li class="relative pb-5 pl-6">
                    <span aria-hidden="true" class="absolute -left-2.5 flex h-5 w-5 items-center justify-center rounded-full border border-emerald-600 bg-white">
                        <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                    </span>

                    <div class="flex items-center justify-between gap-3">
                        <span class="font-bold text-gray-900">도넛 유형 선택</span>
                        <span class="text-2xs text-gray-400">2026.07.01</span>
                    </div>

                    <p class="mt-1 text-2xs text-gray-400">개설 시 사업자 도넛 선택</p>
                </li>

                <li class="relative pb-5 pl-6">
                    <span aria-hidden="true" class="absolute -left-2.5 flex h-5 w-5 items-center justify-center rounded-full border border-emerald-600 bg-white">
                        <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                    </span>

                    <div class="flex items-center justify-between gap-3">
                        <span class="font-bold text-gray-900">사업자 서류 제출</span>
                        <span class="text-2xs text-gray-400">2026.07.04</span>
                    </div>

                    <p class="mt-1 text-2xs text-gray-400">정상 접수 완료</p>
                </li>

                <li class="relative pb-5 pl-6">
                    <span aria-hidden="true" class="absolute -left-2.5 flex h-5 w-5 items-center justify-center rounded-full border border-emerald-600 bg-white">
                        <span class="h-2 w-2 rounded-full bg-emerald-600"></span>
                    </span>

                    <div class="flex items-center justify-between gap-3">
                        <span class="font-bold text-gray-900">플랫폼 심사</span>
                        <span class="text-2xs text-gray-400">2026.07.08</span>
                    </div>

                    <p class="mt-1 text-2xs text-gray-400">사업자 인증 완료</p>
                </li>

                <li class="relative pl-6">
                    <span aria-hidden="true" class="absolute -left-2.5 flex h-5 w-5 items-center justify-center rounded-full border border-amber-500 bg-white">
                        <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                    </span>

                    <div class="flex items-center justify-between gap-3">
                        <span class="font-bold text-gray-900">현금 정산</span>
                        <span class="text-2xs text-gray-400">이용 가능</span>
                    </div>

                    <p class="mt-1 text-2xs text-gray-400">월말 마감 · 익월 12일 기준 · 담당자 검토 · 최소 5만원</p>
                </li>
            </ol>

            <div class="mt-6 rounded-lg bg-amber-100 p-3">
                <p class="text-2xs text-gray-700">
                    최초 사업자 개설 건은 14일 안에 서류를 정상 제출하고 최종 승인되면 개설일부터 누적된 적립분이 정산 대상이 됩니다. 최종 거절 시 해당 적립분은 현금화할 수 없습니다.
                </p>
            </div>
        </section>
    </div>
</section>

<!-- 정산 입금 계좌 변경 모달 -->
<div id="business-account-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="business-account-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="business-account-modal-container" role="dialog" aria-modal="true" aria-labelledby="business-account-modal-title" class="relative z-10 w-full max-w-160 rounded-lg bg-white">
        <div id="business-account-modal-header" class="flex items-center justify-between border-b border-gray-300 p-4">
            <h3 id="business-account-modal-title" class="text-lg font-bold text-gray-900">
                정산 입금 계좌 변경
            </h3>

            <button type="button" id="business-account-modal-close" aria-label="정산 입금 계좌 변경 모달 닫기" class="flex h-8 w-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <form id="business-account-modal-form" class="p-4">
            <div class="rounded-lg bg-amber-100 p-3">
                <p class="text-2xs text-gray-700">
                    계좌는 현재 선택한 테니스 커뮤니티 도넛에만 적용됩니다.
                </p>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-4 pc:grid-cols-2">
                <div>
                    <label for="business-account-modal-bank" class="font-bold text-gray-900">
                        은행
                    </label>

                    <input type="text" id="business-account-modal-bank" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3" value="신한은행">
                </div>

                <div>
                    <label for="business-account-modal-number" class="font-bold text-gray-900">
                        계좌번호
                    </label>

                    <input type="text" id="business-account-modal-number" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3" value="110-***-123456">
                </div>
            </div>

            <div class="mt-4">
                <label for="business-account-modal-holder" class="font-bold text-gray-900">
                    예금주
                </label>

                <input type="text" id="business-account-modal-holder" class="mt-2 w-full rounded-lg border border-gray-300 px-4 py-3" value="테니스 커뮤니티 주식회사">
            </div>
        </form>

        <div id="business-account-modal-footer" class="flex justify-end gap-2 border-t border-gray-300 p-4">
            <button type="button" id="business-account-modal-cancel" class="rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-bold text-gray-900">
                취소
            </button>

            <button type="button" class="rounded-lg bg-amber-300 px-4 py-3 text-sm font-bold text-gray-900">
                계좌 저장
            </button>
        </div>
    </div>
</div>

<script>
    // 정산 입금 계좌 변경 모달 열기 닫기
    $('#business-account-modal-open').on('click', function() {
        $('#business-account-modal').prop('hidden', false);
    });

    $('#business-account-modal-close, #business-account-modal-cancel, #business-account-modal-backdrop').on('click', function() {
        $('#business-account-modal').prop('hidden', true);
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

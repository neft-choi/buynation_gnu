<?php
$sub_menu = '960300';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '도넛 토핑 자격';
require_once '../../admin.head.php';
?>

<section>
    <h2 class="sr-only">도넛 토핑 자격</h2>

    <p class="text-gray-600 font-normal">현금화 가능 금액은 사업자 승인 여부와 적립 시점으로 계산합니다.</p>

    <div class="mt-4 flex items-center gap-3 bg-amber-50 rounded-lg text-2xs p-3">
        <span class="text-amber-600 font-bold">표시 원칙</span>
        <p class="text-amber-700 font-normal">비사업자 관리자에도 ‘정산하기’ 진입 장치는 보이지만 실제 지급은 생성되지 않습니다. 플랫폼에서는 사유와 사업자 전환 안내 상태만 확인합니다.</p>
    </div>

    <ul class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
        <li>
            <article class="h-full border border-gray-300 rounded-lg">
                <header class="flex items-start justify-between border-b border-gray-300 p-4">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-lg bg-amber-100"></div>
                        <div>
                            <h3 class="font-bold">테니스 커뮤니티</h3>
                            <p class="mt-1 block text-2xs text-gray-400 font-normal">DONUT-TENNIS · 김도윤 · 사업자 승인</p>
                        </div>
                    </div>
                    <span class="block w-fit rounded-full text-2xs text-emerald-600 font-bold bg-emerald-100 px-2 py-1">
                        <span aria-hidden="true">●</span> 사업자 승인</span>
                </header>
                <div class="p-4">
                    <div class="space-y-2">
                        <div class="text-2xs bg-emerald-50 border-l-4 border-emerald-600 rounded-r-lg px-3 py-2">
                            <p class="font-bold">현재 정산 가능 680,000T</p>
                            <p class="mt-1 text-gray-400 font-normal">도티가 지금 정산 신청할 수 있는 금액</p>
                        </div>
                        <div class="text-2xs bg-blue-50 border-l-4 border-blue-600 rounded-r-lg px-3 py-2">
                            <p class="font-bold">정산 검토 대상 214,600T</p>
                            <p class="mt-1 text-gray-400 font-normal">신청 자료 수집 후 담당자 확인 구간</p>
                        </div>
                        <div class="text-2xs bg-amber-50 border-l-4 border-amber-600 rounded-r-lg px-3 py-2">
                            <p class="font-bold">쇼핑 전용·심사 잠정 0T</p>
                            <p class="mt-1 text-gray-400 font-normal">잠금 토핑 없음</p>
                        </div>
                    </div>
                    <button type="button" class="donuts-details-modal-open mt-3 border border-gray-300 rounded-lg text-2xs font-bold px-2 py-1">도넛 상세</button>
                </div>
            </article>
        </li>
        <li>
            <article class="h-full border border-gray-300 rounded-lg">
                <header class="flex items-start justify-between border-b border-gray-300 p-4">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-lg bg-amber-100"></div>
                        <div>
                            <h3 class="font-bold">러닝 메이트</h3>
                            <p class="mt-1 block text-2xs text-gray-400 font-normal">DONUT-RUNNING · 김도윤 · 최초 사업자 심사 중</p>
                        </div>
                    </div>
                    <span class="block w-fit rounded-full text-2xs text-blue-600 font-bold bg-blue-100 px-2 py-1">
                        <span aria-hidden="true">●</span> 최초 심사 중</span>
                </header>
                <div class="p-4">
                    <div class="space-y-2">
                        <div class="text-2xs bg-emerald-50 border-l-4 border-emerald-600 rounded-r-lg px-3 py-2">
                            <p class="font-bold">현재 정산 가능 0T</p>
                            <p class="mt-1 text-gray-400 font-normal">현재 지급 대상 없음</p>
                        </div>
                        <div class="text-2xs bg-blue-50 border-l-4 border-blue-600 rounded-r-lg px-3 py-2">
                            <p class="font-bold">쇼핑 전용·심사 잠정 1,286,400T</p>
                            <p class="mt-1 text-gray-400 font-normal">승인되면 개설 후 심사 중 적립분을 현금화 가능 버킷으로 이동</p>
                        </div>
                    </div>
                    <button type="button" class="donuts-details-modal-open mt-3 border border-gray-300 rounded-lg text-2xs font-bold px-2 py-1">도넛 상세</button>
                </div>
            </article>
        </li>
        <li>
            <article class="h-full border border-gray-300 rounded-lg">
                <header class="flex items-start justify-between border-b border-gray-300 p-4">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-lg bg-amber-100"></div>
                        <div>
                            <h3 class="font-bold">사진 산책회</h3>
                            <p class="mt-1 block text-2xs text-gray-400 font-normal">DONUT-PHOTO · 김도윤 · 최초 사업자 심사 중</p>
                        </div>
                    </div>
                    <span class="block w-fit rounded-full text-2xs text-blue-600 font-bold bg-blue-100 px-2 py-1">
                        <span aria-hidden="true">●</span> 최초 심사 중</span>
                </header>
                <div class="p-4">
                    <div class="space-y-2">
                        <div class="text-2xs bg-emerald-50 border-l-4 border-emerald-600 rounded-r-lg px-3 py-2">
                            <p class="font-bold">현재 정산 가능 0T</p>
                            <p class="mt-1 text-gray-400 font-normal">현재 지급 대상 없음</p>
                        </div>
                        <div class="text-2xs bg-blue-50 border-l-4 border-blue-600 rounded-r-lg px-3 py-2">
                            <p class="font-bold">쇼핑 전용·심사 잠정 742,000T</p>
                            <p class="mt-1 text-gray-400 font-normal">승인되면 개설 후 심사 중 적립분을 현금화 가능 버킷으로 이동</p>
                        </div>
                    </div>
                    <button type="button" class="donuts-details-modal-open mt-3 border border-gray-300 rounded-lg text-2xs font-bold px-2 py-1">도넛 상세</button>
                </div>
            </article>
        </li>
        <li>
            <article class="h-full border border-gray-300 rounded-lg">
                <header class="flex items-start justify-between border-b border-gray-300 p-4">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-lg bg-amber-100"></div>
                        <div>
                            <h3 class="font-bold">홈베이킹 살롱</h3>
                            <p class="mt-1 block text-2xs text-gray-400 font-normal">DONUT-BAKING · 김도윤 · 비사업자</p>
                        </div>
                    </div>
                    <span class="block w-fit rounded-full text-2xs text-gray-600 font-bold bg-gray-100 px-2 py-1">
                        <span aria-hidden="true">●</span> 비사업자</span>
                </header>
                <div class="p-4">
                    <div class="space-y-2">
                        <div class="text-2xs bg-emerald-50 border-l-4 border-emerald-600 rounded-r-lg px-3 py-2">
                            <p class="font-bold">현재 정산 가능 0T</p>
                            <p class="mt-1 text-gray-400 font-normal">현재 지급 대상 없음</p>
                        </div>
                        <div class="text-2xs bg-amber-50 border-l-4 border-amber-600 rounded-r-lg px-3 py-2">
                            <p class="font-bold">쇼핑 전용·심사 잠정 918,000T</p>
                            <p class="mt-1 text-gray-400 font-normal">쇼핑몰에서 포인트처럼 사용 가능 · 현금 정산 불가</p>
                        </div>
                    </div>
                    <button type="button" class="donuts-details-modal-open mt-3 border border-gray-300 rounded-lg text-2xs font-bold px-2 py-1">도넛 상세</button>
                </div>
            </article>
        </li>
        <li>
            <article class="h-full border border-gray-300 rounded-lg">
                <header class="flex items-start justify-between border-b border-gray-300 p-4">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-lg bg-amber-100"></div>
                        <div>
                            <h3 class="font-bold">캠핑 위켄드</h3>
                            <p class="mt-1 block text-2xs text-gray-400 font-normal">DONUT-CAMPING · 김도윤 · 사업자 승인</p>
                        </div>
                    </div>
                    <span class="block w-fit rounded-full text-2xs text-emerald-600 font-bold bg-emerald-100 px-2 py-1">
                        <span aria-hidden="true">●</span> 사업자 승인</span>
                </header>
                <div class="p-4">
                    <div class="space-y-2">
                        <div class="text-2xs bg-emerald-50 border-l-4 border-emerald-600 rounded-r-lg px-3 py-2">
                            <p class="font-bold">현재 정산 가능 0T</p>
                            <p class="mt-1 text-gray-400 font-normal">현재 지급 대상 없음</p>
                        </div>
                        <div class="text-2xs bg-blue-50 border-l-4 border-blue-600 rounded-r-lg px-3 py-2">
                            <p class="font-bold">정산 검토 대상 192,000T</p>
                            <p class="mt-1 text-gray-400 font-normal">신청 자료 수집 후 담당자 확인 구간</p>
                        </div>
                        <div class="text-2xs bg-blue-50 border-l-4 border-blue-600 rounded-r-lg px-3 py-2">
                            <p class="font-bold">검토 필요 금액 860,000T</p>
                            <p class="mt-1 text-gray-400 font-normal">지급 상태는 이 화면에서 자동 변경하지 않음</p>
                        </div>
                        <div class="text-2xs bg-amber-50 border-l-4 border-amber-600 rounded-r-lg px-3 py-2">
                            <p class="font-bold">쇼핑 전용·심사 잠정 0T</p>
                            <p class="mt-1 text-gray-400 font-normal">잠금 토핑 없음</p>
                        </div>
                    </div>
                    <button type="button" class="donuts-details-modal-open mt-3 border border-gray-300 rounded-lg text-2xs font-bold px-2 py-1">도넛 상세</button>
                </div>
            </article>
        </li>
    </ul>
</section>

<!-- 도넛 통합 상세 모달 -->
<div id="donuts-details-modal" class="fixed inset-0 z-1000 flex items-center justify-center p-4" hidden>
    <div id="donuts-details-modal-backdrop" class="absolute inset-0 bg-black/40"></div>

    <div id="donuts-details-modal-container" role="dialog" aria-modal="true" aria-labelledby="donuts-details-modal-title" class="relative z-10 w-full max-w-150 max-h-[90vh] overflow-y-auto rounded-lg bg-white">
        <div id="donuts-details-modal-header" class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-300 bg-white p-4">
            <h3 id="donuts-details-modal-title" class="text-sm font-bold text-gray-900">
                도넛 통합 상세
            </h3>

            <button type="button" id="donuts-details-modal-close" aria-label="도넛 통합 상세 모달 닫기" class="flex w-8 h-8 items-center justify-center rounded-full hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div id="donuts-details-modal-body" class="p-4">
            <p class="text-xl font-bold">테니스 커뮤니티</p>

            <div class="mt-4 overflow-hidden rounded-lg border border-gray-300">
                <dl class="text-left text-2xs [&>div+div]:border-t [&>div+div]:border-gray-200">
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">도넛 ID</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">DONUT-TENNIS</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">도티</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">김도윤 · DOTI-0001</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">사업자 상태</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">
                            <span class="block w-fit rounded-full bg-emerald-100 text-2xs font-bold text-emerald-600 px-2 py-1">● 사업자 승인</span>
                        </dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">정산 계좌</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">신한 110-***-123456 · 테니스 커뮤니티 주식회사</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">현금화 가능</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">680,000T</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">쇼핑 전용</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">0T</dd>
                    </div>
                    <div class="flex">
                        <dt class="w-[21%] shrink-0 bg-gray-50 font-normal text-gray-500 p-3">심사 잠정</dt>
                        <dd class="flex-1 font-bold text-gray-900 p-3">0T</dd>
                    </div>
                </dl>
            </div>

            <div class="mt-3 text-2xs bg-emerald-50 border-l-4 border-emerald-600 rounded-r-lg px-3 py-2">
                <p class="font-bold">현금화 가능 버킷 680,000T</p>
                <p class="mt-1 text-gray-400 font-normal">사업자 승인 후 발생했거나 최초 심사 승인으로 자격이 확정된 금액</p>
            </div>

            <div class="mt-3 text-2xs bg-amber-50 border-l-4 border-amber-600 rounded-r-lg px-3 py-2">
                <p class="font-bold">쇼핑 전용·심사 잠정 0T</p>
                <p class="mt-1 text-gray-400 font-normal">잠금 토핑 없음</p>
            </div>

            <div class="mt-3 border border-transparent rounded-lg bg-gray-50 text-2xs p-3">
                <span class="block text-gray-400 font-normal">사업자 신청</span>
                <p class="mt-1 text-gray-700 font-normal">DTV-TENNIS-260704 · 최초 사업자 인증 · approved</p>
            </div>

            <div class="mt-3 border border-transparent rounded-lg bg-gray-50 text-2xs p-3">
                <span class="block text-gray-400 font-normal">도트 운영자</span>
                <p class="mt-1 text-gray-700 font-normal">홍길동(1개 권한)</p>
            </div>
        </div>

        <div id="donuts-details-modal-footer" class="sticky bottom-0 z-10 flex justify-end gap-2 border-t border-gray-300 bg-white p-4">
            <button type="button" id="donuts-details-modal-cancel" class="rounded-lg border border-gray-300 bg-white text-gray-900 font-bold px-4 py-3">
                닫기
            </button>
            <button type="button" class="rounded-lg border border-gray-300 bg-white text-gray-900 font-bold px-4 py-3">
                사업자 신청 보기
            </button>
        </div>
    </div>
</div>

<script>
    // 도넛 통합 상세 모달
    $('.donuts-details-modal-open').on('click', function() {
        $('#donuts-details-modal').prop('hidden', false);
    });

    $('#donuts-details-modal-close, #donuts-details-modal-cancel, #donuts-details-modal-backdrop').on('click', function() {
        $('#donuts-details-modal').prop('hidden', true);
    });
</script>

<?php
require_once '../../admin.tail.php';

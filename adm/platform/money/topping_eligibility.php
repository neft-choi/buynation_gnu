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
                    <button type="button" class="mt-3 border border-gray-300 rounded-lg text-2xs font-bold px-2 py-1">도넛 상세</button>
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
                    <button type="button" class="mt-3 border border-gray-300 rounded-lg text-2xs font-bold px-2 py-1">도넛 상세</button>
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
                    <button type="button" class="mt-3 border border-gray-300 rounded-lg text-2xs font-bold px-2 py-1">도넛 상세</button>
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
                    <button type="button" class="mt-3 border border-gray-300 rounded-lg text-2xs font-bold px-2 py-1">도넛 상세</button>
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
                    <button type="button" class="mt-3 border border-gray-300 rounded-lg text-2xs font-bold px-2 py-1">도넛 상세</button>
                </div>
            </article>
        </li>
    </ul>
</section>

<?php
require_once '../../admin.tail.php';

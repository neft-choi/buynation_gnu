<?php
$sub_menu = '970300';
include_once('../_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '시스템·연동';
require_once '../../admin.head.php';
?>

<section>
    <header class="flex items-center justify-between">
        <p class="text-gray-400 font-normal">자동 기록과 사람의 판단 경계, 외부 연동 상태를 확인합니다.</p>

        <button type="button" class="w-fit border border-transparent rounded-lg bg-amber-300 text-gray-900 font-bold px-3 py-2">
            상태 다시 확인
        </button>
    </header>

    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="border border-gray-300 rounded-lg p-3">
            <span class="block text-xs text-gray-900 font-bold">자동 기록 허용</span>
            <p class="mt-1 text-2xs text-gray-400 font-normal">주문·결제 결과 수집, 토핑 발생 로그 생성, 규칙 위반 표시, 알림과 감사 로그</p>
        </div>

        <div class="border border-gray-300 rounded-lg p-3">
            <span class="block text-xs text-gray-900 font-bold">담당자 판단 필수</span>
            <p class="mt-1 text-2xs text-gray-400 font-normal">로그 정정 승인, 정산 확정·실제 지급, 분쟁 결론, 예외 승인, 정책 시행</p>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
        <section class="rounded-lg border border-gray-300 bg-white p-4">
            <h3 class="text-xl font-bold text-gray-900">
                자료 수집·검증
            </h3>

            <ul class="mt-4 space-y-2 divide-y divide-gray-300 [&_li:not(:last-child)]:pb-2">
                <li>
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <span class="block text-2xs font-bold text-gray-900">
                                주문·결제·토핑 발생 기록
                            </span>

                            <p class="mt-1 text-2xs text-gray-400">
                                발생 사실과 로그 근거를 변경 불가능한 이력으로 저장
                            </p>
                        </div>

                        <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                            <span aria-hidden="true" class="mr-1">●</span>자동 기록
                        </span>
                    </div>
                </li>

                <li>
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <span class="block text-2xs font-bold text-gray-900">
                                규칙 위반·불일치 표시
                            </span>

                            <p class="mt-1 text-2xs text-gray-400">
                                이상 여부를 알려주되 금액과 지급 상태는 바꾸지 않음
                            </p>
                        </div>

                        <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                            <span aria-hidden="true" class="mr-1">●</span>자동 기록
                        </span>
                    </div>
                </li>

                <li>
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <span class="block text-2xs font-bold text-gray-900">
                                감사 로그
                            </span>

                            <p class="mt-1 text-2xs text-gray-400">
                                누가 언제 무엇을 확인·승인했는지 기록
                            </p>
                        </div>

                        <span class="shrink-0 rounded-full bg-amber-50 px-2 py-1 text-2xs font-bold text-amber-700">
                            <span aria-hidden="true" class="mr-1">●</span>확인
                        </span>
                    </div>
                </li>

                <li>
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <span class="block text-2xs font-bold text-gray-900">
                                정책 변경
                            </span>

                            <p class="mt-1 text-2xs text-gray-400">
                                사유·시행 시점 기록 후 담당자 게시
                            </p>
                        </div>

                        <span class="shrink-0 rounded-full bg-amber-50 px-2 py-1 text-2xs font-bold text-amber-700">
                            <span aria-hidden="true" class="mr-1">●</span>담당자
                        </span>
                    </div>
                </li>
            </ul>
        </section>

        <section class="rounded-lg border border-gray-300 bg-white p-4">
            <h3 class="text-xl font-bold text-gray-900">
                외부 연동
            </h3>

            <ul class="mt-4 space-y-2 divide-y divide-gray-300 [&_li:not(:last-child)]:pb-2">
                <li>
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <span class="block text-2xs font-bold text-gray-900">
                                결제사
                            </span>

                            <p class="mt-1 text-2xs text-gray-400">
                                대사 불일치 2건 · 자동 해소 없음
                            </p>
                        </div>

                        <span class="shrink-0 rounded-full bg-amber-50 px-2 py-1 text-2xs font-bold text-amber-700">
                            <span aria-hidden="true" class="mr-1">●</span>확인
                        </span>
                    </div>
                </li>

                <li>
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <span class="block text-2xs font-bold text-gray-900">
                                사업자 상태 확인
                            </span>

                            <p class="mt-1 text-2xs text-gray-400">
                                최근 자료 조회 성공
                            </p>
                        </div>

                        <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                            <span aria-hidden="true" class="mr-1">●</span>정상
                        </span>
                    </div>
                </li>

                <li>
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <span class="block text-2xs font-bold text-gray-900">
                                택배·송장
                            </span>

                            <p class="mt-1 text-2xs text-gray-400">
                                최근 수집 15:42
                            </p>
                        </div>

                        <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                            <span aria-hidden="true" class="mr-1">●</span>정상
                        </span>
                    </div>
                </li>

                <li>
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <span class="block text-2xs font-bold text-gray-900">
                                알림 발송
                            </span>

                            <p class="mt-1 text-2xs text-gray-400">
                                보완·승인 결과 발송
                            </p>
                        </div>

                        <span class="shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-2xs font-bold text-emerald-700">
                            <span aria-hidden="true" class="mr-1">●</span>정상
                        </span>
                    </div>
                </li>
            </ul>
        </section>
    </div>
</section>

<?php
require_once '../../admin.tail.php';

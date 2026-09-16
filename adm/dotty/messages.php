<?php
$sub_menu = '720100';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '브랜드 쪽지';
require_once '../admin.head.php';
?>

<section class="space-y-4">
    <div class="flex items-center justify-between gap-3">
        <p class="text-gray-400">
            캠핑 위켄드과 브랜드가 쪽지를 주고받고 읽지 않은 메시지를 확인합니다.
        </p>

        <span class="shrink-0 rounded-full bg-green-50 px-3 py-2 text-2xs font-bold text-green-700">
            <span aria-hidden="true">●</span>
            새 쪽지 없음
        </span>
    </div>

    <div class="flex w-fit rounded-lg bg-gray-100 p-1">
        <button type="button" id="messages-tab-chat" aria-pressed="true" class="messages-tab rounded-lg bg-white px-3 py-2 text-2xs font-bold text-gray-900">
            대화 0
        </button>

        <button type="button" id="messages-tab-liked" aria-pressed="false" class="messages-tab rounded-lg px-3 py-2 text-2xs font-bold text-gray-600">
            좋아요한 브랜드 1
        </button>

        <button type="button" id="messages-tab-browse" aria-pressed="false" class="messages-tab rounded-lg px-3 py-2 text-2xs font-bold text-gray-600">
            브랜드 둘러보기
        </button>
    </div>

    <section id="messages-panel-chat">
        <div class="flex min-h-120 flex-col overflow-hidden rounded-lg border border-gray-300 bg-white pc:flex-row">
            <aside class="flex shrink-0 flex-col border-b border-gray-300 pc:w-80 pc:border-r pc:border-b-0" aria-labelledby="messages-conversation-title">
                <div class="border-b border-gray-300 p-4">
                    <h3 id="messages-conversation-title" class="font-bold text-gray-900">
                        캠핑 위켄드 대화 0건
                    </h3>

                    <p class="mt-2 text-2xs text-gray-400">
                        브랜드와 도트 사이에서 먼저 쪽지를 보낼 수 있습니다.
                    </p>
                </div>

                <div class="flex flex-1 items-start justify-center p-6 text-center">
                    <p class="text-2xs text-gray-400">
                        아직 주고받은 쪽지가 없습니다.<br>
                        좋아요한 브랜드나 브랜드 둘러보기에서 먼저 쪽지를 보낼 수 있습니다.
                    </p>
                </div>
            </aside>

            <div class="min-w-0 flex-1 p-4 text-center">
                <p class="text-2xs text-gray-400">
                    왼쪽에서 대화를 선택해 주세요.
                </p>
            </div>
        </div>
    </section>

    <section id="messages-panel-liked" hidden>
        <div class="rounded-lg bg-blue-50 p-3">
            <p class="text-2xs text-blue-800">
                <span class="font-bold">좋아요한 브랜드</span>
                <span class="ml-2">관심 브랜드를 저장하고 궁금한 내용은 바로 쪽지로 문의할 수 있습니다.</span>
            </p>
        </div>

        <ul class="mt-4 grid grid-cols-1 pc:grid-cols-2 gap-4">
            <li>
                <article class="rounded-lg border border-gray-300 bg-white p-4">
                    <div class="flex gap-3">
                        <div aria-hidden="true" class="h-14 w-14 shrink-0 rounded-lg bg-amber-100"></div>

                        <div class="min-w-0 flex-1">
                            <h3 class="font-bold text-gray-900">
                                캠프베이스
                            </h3>

                            <span class="mt-1 block text-2xs text-gray-400">
                                캠핑 · 아웃도어 · 등록 상품 9개
                            </span>
                        </div>
                    </div>

                    <p class="mt-4 text-2xs text-gray-500">
                        주말 아웃도어를 위한 실용적인 캠핑 장비 브랜드입니다.
                    </p>

                    <dl class="mt-4 grid grid-cols-2 gap-2">
                        <div class="rounded-lg bg-gray-50 p-3">
                            <dt class="text-2xs text-gray-400">대표 상품</dt>
                            <dd class="mt-1 font-bold text-gray-900">캠팩트 캠핑 테이블</dd>
                        </div>

                        <div class="rounded-lg bg-gray-50 p-3">
                            <dt class="text-2xs text-gray-400">기본 기여율</dt>
                            <dd class="mt-1 font-bold text-gray-900">3.8%</dd>
                        </div>
                    </dl>

                    <div class="mt-4 flex gap-2">
                        <button type="button" aria-label="켐프베이스 좋아요" class="shrink-0 flex items-center justify-center h-11 w-11 border border-red-300 rounded-lg text-red-500 fill-red-500 bg-red-50">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart w-5 h-5"><path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"/></svg>
                        </button>

                        <button type="button" class="messages-compose-modal-open min-w-0 flex-1 rounded-lg bg-amber-300 px-4 py-3 font-bold text-gray-900">
                            쪽지 보내기
                        </button>
                    </div>
                </article>
            </li>
        </ul>
    </section>

    <section id="messages-panel-browse" hidden>
        <div class="rounded-lg bg-blue-50 p-3">
            <p class="text-2xs text-blue-800">
                <span class="font-bold">프로토타입 브랜드 목록</span>
                <span class="ml-2">관심 브랜드를 저장하고 궁금한 내용은 바로 쪽지로 문의할 수 있습니다.</span>
            </p>
        </div>

        <ul class="mt-4 grid grid-cols-1 gap-4 pc:grid-cols-2 [&_article]:h-full">
            <li>
                <article class="rounded-lg border border-gray-300 bg-white p-4">
                    <div class="flex gap-3">
                        <div aria-hidden="true" class="h-14 w-14 shrink-0 rounded-lg bg-amber-100"></div>

                        <div class="min-w-0 flex-1">
                            <h3 class="font-bold text-gray-900">
                                그린테이블
                            </h3>

                            <span class="mt-1 block text-2xs text-gray-400">
                                식품 · 건강 · 등록 상품 6개
                            </span>
                        </div>
                    </div>

                    <p class="mt-4 text-2xs text-gray-500">
                        건강한 식재료와 간편한 한 끼를 제안합니다.
                    </p>

                    <dl class="mt-4 grid grid-cols-2 gap-2">
                        <div class="rounded-lg bg-gray-50 p-3">
                            <dt class="text-2xs text-gray-400">대표 상품</dt>
                            <dd class="mt-1 font-bold text-gray-900">저당 단백질바 12개입</dd>
                        </div>

                        <div class="rounded-lg bg-gray-50 p-3">
                            <dt class="text-2xs text-gray-400">기본 기여율</dt>
                            <dd class="mt-1 font-bold text-gray-900">3.2%</dd>
                        </div>
                    </dl>

                    <div class="mt-4 flex gap-2">
                        <button type="button" aria-label="그린베이스 좋아요" class="shrink-0 flex items-center justify-center h-11 w-11 border border-gray-300 rounded-lg text-gray-600 bg-white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart w-5 h-5"><path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"/></svg>
                        </button>

                        <button type="button" class="messages-compose-modal-open min-w-0 flex-1 rounded-lg bg-amber-300 px-4 py-3 font-bold text-gray-900">
                            쪽지 보내기
                        </button>
                    </div>
                </article>
            </li>

            <li>
                <article class="rounded-lg border border-gray-300 bg-white p-4">
                    <div class="flex gap-3">
                        <div aria-hidden="true" class="h-14 w-14 shrink-0 rounded-lg bg-amber-100"></div>

                        <div class="min-w-0 flex-1">
                            <h3 class="font-bold text-gray-900">
                                액티브핏
                            </h3>

                            <span class="mt-1 block text-2xs text-gray-400">
                                스포츠 · 아웃도어 · 등록 상품 8개
                            </span>
                        </div>
                    </div>

                    <p class="mt-4 text-2xs text-gray-500">
                        운동하는 사람을 위한 기능성 스포츠 상품을 소개합니다.
                    </p>

                    <dl class="mt-4 grid grid-cols-2 gap-2">
                        <div class="rounded-lg bg-gray-50 p-3">
                            <dt class="text-2xs text-gray-400">대표 상품</dt>
                            <dd class="mt-1 font-bold text-gray-900">퍼포먼스 러닝 키트</dd>
                        </div>

                        <div class="rounded-lg bg-gray-50 p-3">
                            <dt class="text-2xs text-gray-400">기본 기여율</dt>
                            <dd class="mt-1 font-bold text-gray-900">3.5%</dd>
                        </div>
                    </dl>

                    <div class="mt-4 flex gap-2">
                        <button type="button" aria-label="액티브핏 좋아요" class="shrink-0 flex items-center justify-center h-11 w-11 border border-gray-300 rounded-lg text-gray-600 bg-white">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart w-5 h-5"><path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"/></svg>
                        </button>

                        <button type="button" class="messages-compose-modal-open min-w-0 flex-1 rounded-lg bg-amber-300 px-4 py-3 font-bold text-gray-900">
                            쪽지 보내기
                        </button>
                    </div>
                </article>
            </li>
        </ul>
    </section>
</section>

<script>
    $('#messages-tab-chat').on('click', function() {
        $('#messages-panel-chat').prop('hidden', false);
        $('#messages-panel-liked, #messages-panel-browse').prop('hidden', true);

        $('.messages-tab')
            .attr('aria-pressed', 'false')
            .removeClass('bg-white font-bold text-gray-900')
            .addClass('text-gray-600');

        $(this)
            .attr('aria-pressed', 'true')
            .removeClass('text-gray-600')
            .addClass('bg-white font-bold text-gray-900');
    });

    $('#messages-tab-liked').on('click', function() {
        $('#messages-panel-liked').prop('hidden', false);
        $('#messages-panel-chat, #messages-panel-browse').prop('hidden', true);

        $('.messages-tab')
            .attr('aria-pressed', 'false')
            .removeClass('bg-white font-bold text-gray-900')
            .addClass('text-gray-600');

        $(this)
            .attr('aria-pressed', 'true')
            .removeClass('text-gray-600')
            .addClass('bg-white font-bold text-gray-900');
    });

    $('#messages-tab-browse').on('click', function() {
        $('#messages-panel-browse').prop('hidden', false);
        $('#messages-panel-chat, #messages-panel-liked').prop('hidden', true);

        $('.messages-tab')
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

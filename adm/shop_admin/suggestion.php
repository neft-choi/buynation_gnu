<?php
$sub_menu = '400330';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'r');

$g5['title'] = '도넛 쪽지';
include_once(G5_ADMIN_PATH . '/admin.head.php');
?>

<section class="space-y-4">
    <div role="tablist" aria-label="도넛 협업 메뉴" class="flex items-center gap-1 w-fit rounded-lg bg-gray-100 p-1">
        <button type="button" id="suggestion-tab-chat" role="tab" aria-selected="true" aria-controls="suggestion-panel-chat" class="rounded-md bg-gray-900 px-3 py-2 text-2xs font-bold text-white">
            대화 4
        </button>
        <button type="button" id="suggestion-tab-liked" role="tab" aria-selected="false" aria-controls="suggestion-panel-liked" class="rounded-md px-3 py-2 text-2xs font-bold text-gray-500">
            좋아요한 도넛 2
        </button>
        <button type="button" id="suggestion-tab-browse" role="tab" aria-selected="false" aria-controls="suggestion-panel-browse" class="rounded-md px-3 py-2 text-2xs font-bold text-gray-500">
            도넛 둘러보기
        </button>
    </div>

    <section id="suggestion-panel-chat" role="tabpanel" aria-labelledby="suggestion-tab-chat" tabindex="0">
        <!-- 대화 화면 -->
        <div class="flex items-center justify-between gap-3">
            <p class="mt-1 text-2xs text-gray-400">브랜드와 도넛이 양방향으로 제안하고 전용 기획전·상품 조건을 직접 협의합니다.</p>
            <button type="button" class="shrink-0 rounded-lg bg-gray-900 px-3 py-2 text-2xs font-bold text-white">
                + 협업 도넛 찾기
            </button>
        </div>

        <div class="flex overflow-hidden rounded-lg border border-gray-300 mt-4">
            <aside class="shrink-0 w-80 border-r border-gray-300" aria-labelledby="suggestion-conversation-heading">
                <div class="flex flex-col items-start justify-center h-18 border-b border-gray-300 p-4">
                    <h3 id="suggestion-conversation-heading" class="text-sm text-gray-900 font-bold">도넛 대화 4건</h3>
                    <p class="text-2xs text-gray-400">양쪽 모두 먼저 제안할 수 있습니다.</p>
                </div>

                <nav aria-label="도넛 대화 목록">
                    <ul class="divide-y divide-gray-200">
                        <li>
                            <button type="button" aria-current="true" class="flex w-full items-start gap-3 border-l-4 border-amber-400 bg-amber-50 px-4 py-4 text-left">
                                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-gray-900 font-bold text-amber-300" aria-hidden="true">러</span>

                                <span class="min-w-0 flex-1">
                                    <span class="block truncate font-bold text-gray-900">러닝 메이트</span>
                                    <time datetime="2026-08-19T14:10" class="shrink-0 text-2xs text-gray-400">2026.08.19 14:10</time>
                                    <span class="block truncate text-2xs text-gray-500">정기런 참여 회원을 위한 회복식 기획전을 제안합니다.</span>
                                </span>
                            </button>
                        </li>

                        <li>
                            <button type="button" class="flex w-full items-start gap-3 bg-gray-50 p-4 text-left">
                                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-gray-900 font-bold text-amber-300" aria-hidden="true">테</span>

                                <span class="min-w-0 flex-1">
                                    <span class="block truncate font-bold text-gray-900">테니스 커뮤니티</span>
                                    <time datetime="2026-08-19T14:10" class="shrink-0 text-2xs text-gray-400">2026.08.18 09:10</time>
                                    <span class="block truncate text-2xs text-gray-500">조건 확인했습니다. 정기 모임 일정과 맞춰 노출 시작일을 9월 3일로 조정할 수 있을까요?</span>
                                </span>
                            </button>
                        </li>
                    </ul>
                </nav>
            </aside>

            <article class="min-w-0 flex-1">
                <header class="flex flex-col items-start justify-center h-18 border-b border-gray-200 p-4">
                    <h3 class="truncate text-sm font-bold text-gray-900">러닝 회원 전용 회복식 기획전 제안</h3>
                    <p class="mt-1 text-2xs text-gray-400">그린테이블 ↔ 러닝 메이트 · MSG-DOTI-GT-RUNNING</p>
                </header>

                <div class="content-chat">
                    <aside class="flex items-center justify-between border-b border-blue-100 bg-blue-50 px-4 py-2 text-2xs text-blue-800" aria-label="도넛 협업 안내">
                        <div>
                            <p class="font-semibold">도넛의 협업 제안</p>
                            <p class="mt-1">내용을 확인한 뒤 협업 의사가 있을 때만 수락해 주세요. 메시지 내용으로 상태를 자동 판별하지 않습니다.</p>
                        </div>

                        <button type="button" class="shrink-0 rounded-lg bg-amber-400 px-4 py-2 text-2xs font-bold text-gray-900">
                            협업 수락
                        </button>
                    </aside>

                    <aside class="border-b border-amber-100 bg-amber-50 px-4 py-2 text-2xs text-amber-900" aria-label="전용 기획전 협의 안내">
                        <p><span class="font-semibold">전용 기획전 협의 · </span>쪽지에는 읽지 않음만 표시하며, 협업 진행은 상대방 수락과 실제 기획전 등록으로 구분합니다.</p>
                    </aside>

                    <ol class="space-y-2 p-4" aria-label="러닝 메이트와의 메시지">
                        <li class="flex justify-start">
                            <article class="flex max-w-[80%] flex-col items-start text-left">
                                <p class="text-2xs font-semibold text-gray-900">러닝 메이트</p>
                                <p class="mt-1 w-fit max-w-full rounded-2xl rounded-tl-none border border-gray-200 bg-white p-3 text-xs text-gray-800">
                                    정기런 참여 회원을 위한 회복식 기획전을 제안드립니다. 2주간 500세트 규모와 도넛 전용 구성을 협의할 수 있을까요?
                                </p>
                                <time datetime="2026-08-19T14:10" class="mt-1 block text-2xs text-gray-400">2026.08.19 14:10</time>
                            </article>
                        </li>

                        <li class="flex justify-end">
                            <article class="flex max-w-[80%] flex-col items-end text-right">
                                <p class="text-2xs font-semibold text-gray-900">그린 테이블</p>
                                <p class="mt-1 max-w-full rounded-2xl rounded-tr-none border border-amber-200 bg-amber-50 p-3 text-xs text-gray-800">
                                    가능할 것 같습니다.
                                </p>
                                <time datetime="2026-08-19T14:13" class="mt-1 block text-2xs text-gray-400">2026.08.19 14:13</time>
                            </article>
                        </li>
                    </ol>

                    <form id="suggestion-message-form" method="post" class="flex gap-2 border-t border-gray-300 p-4">
                        <label for="suggestion-message" class="sr-only">메시지 내용</label>
                        <textarea id="suggestion-message" name="message_content" rows="3" required placeholder="도넛에 보낼 메시지를 입력해 주세요." class="min-h-18 flex-1 rounded-lg border border-gray-300 p-3 text-xs text-gray-900"></textarea>

                        <button type="submit" class="shrink-0 rounded-lg bg-amber-400 px-5 py-3 text-xs font-bold text-gray-900">
                            보내기
                        </button>
                    </form>
                </div>
            </article>
        </div>
    </section>

    <section id="suggestion-panel-liked" role="tabpanel" aria-labelledby="suggestion-tab-liked" tabindex="0" hidden>
        <!-- 좋아요한 도넛 목록 -->
        <div class="flex items-center justify-between gap-3">
            <p class="mt-1 text-2xs text-gray-400">도넛을 좋아요하고 바로 연락해 전용 기획전 협업을 제안합니다.</p>
            <button type="button" class="shrink-0 border border-gray-300 rounded-lg bg-white px-3 py-2 text-2xs text-gray-900 font-bold">
                대화 목록
            </button>
        </div>

        <div class="mt-4 rounded-lg bg-blue-50 p-3 text-2xs text-blue-800">
            <span class="font-semibold">좋아요한 도넛: </span><span>가입 도트 수와 이번 달 기여토핑은 협업 검토를 위한 프로토타입 지표입니다. 도넛도 브랜드에 먼저 제안할 수 있습니다.</span>
        </div>

        <ul class="grid grid-cols-1 pc:grid-cols-2 items-stretch gap-4 mt-4">
            <li>
                <article class="h-full border border-gray-300 rounded-lg p-4 space-y-2">
                    <div class="flex gap-3">
                        <div class="w-14 aspect-square rounded-lg bg-amber-100"></div>
                        <div class="flex-1 min-w-0 space-y-1">
                            <p class="font-bold">러닝 메이트</p>
                            <span class="block text-2xs text-gray-400">취미 · 스포츠 · 공개 도넛</span>
                            <span class="block w-fit rounded-full text-2xs text-red-400 font-bold bg-red-50 px-2 py-1">♥ 서로 좋아요</span>
                        </div>
                    </div>

                    <p class="text-2xs text-gray-400">테니스를 사랑하는 도트들의 활발한 소통 공간입니다.</p>

                    <dl class="flex items-center gap-2">
                        <div class="w-full rounded-lg bg-gray-50 px-3 py-2">
                            <dt class="block text-2xs text-gray-400">가입 도트</dt>
                            <dd class="block font-bold">8,720명</dd>
                        </div>
                        <div class="w-full rounded-lg bg-gray-50 px-3 py-2">
                            <dt class="block text-2xs text-gray-400">이번 달 기여토핑</dt>
                            <dd class="block font-bold">2,184,600T</dd>
                        </div>
                    </dl>

                    <div class="flex items-center gap-2">
                        <button type="button" aria-label="테니스 커뮤니티 좋아요 취소" class="inline-flex h-10.5 items-center justify-center rounded-lg border border-red-400 bg-red-50 px-3 text-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="h-5 w-5">
                                <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                            </svg>
                        </button>
                        <button type="button" class="min-w-0 flex-1 rounded-lg border border-gray-300 p-3 text-center text-xs font-bold">대화 열기</button>
                    </div>
                </article>
            </li>

            <li>
                <article class="h-full border border-gray-300 rounded-lg p-4 space-y-2">
                    <div class="flex gap-3">
                        <div class="w-14 aspect-square rounded-lg bg-amber-100"></div>
                        <div class="flex-1 min-w-0 space-y-1">
                            <p class="font-bold">테니스 커뮤니티</p>
                            <span class="block text-2xs text-gray-400">취미 · 스포츠 · 공개 도넛</span>
                        </div>
                    </div>

                    <p class="text-2xs text-gray-400">함께 달리고 서로의 기록을 응원하는 러닝 커뮤니티입니다.</p>

                    <dl class="flex items-center gap-2">
                        <div class="w-full rounded-lg bg-gray-50 px-3 py-2">
                            <dt class="block text-2xs text-gray-400">가입 도트</dt>
                            <dd class="block font-bold">4,360명</dd>
                        </div>
                        <div class="w-full rounded-lg bg-gray-50 px-3 py-2">
                            <dt class="block text-2xs text-gray-400">이번 달 기여토핑</dt>
                            <dd class="block font-bold">1,286,400T</dd>
                        </div>
                    </dl>

                    <div class="flex items-center gap-2">
                        <button type="button" aria-label="러닝 메이트 좋아요 취소" class="inline-flex h-10.5 items-center justify-center rounded-lg border border-red-400 bg-red-50 px-3 text-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="h-5 w-5">
                                <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                            </svg>
                        </button>
                        <button type="button" class="min-w-0 flex-1 rounded-lg border border-gray-300 p-3 text-center text-xs font-bold">대화 열기</button>
                    </div>
                </article>
            </li>
        </ul>
    </section>

    <section id="suggestion-panel-browse" role="tabpanel" aria-labelledby="suggestion-tab-browse" tabindex="0" hidden>
        <!-- 도넛 둘러보기 목록 -->
        <div class="flex items-center justify-between gap-3">
            <p class="mt-1 text-2xs text-gray-400">도넛을 좋아요하고 바로 연락해 전용 기획전 협업을 제안합니다.</p>
            <button type="button" class="shrink-0 border border-gray-300 rounded-lg bg-white px-3 py-2 text-2xs text-gray-900 font-bold">
                대화 목록
            </button>
        </div>

        <div class="mt-4 rounded-lg bg-blue-50 p-3 text-2xs text-blue-800">
            <span class="font-semibold">공개 도넛 탐색: </span><span>가입 도트 수와 이번 달 기여토핑은 협업 검토를 위한 프로토타입 지표입니다. 도넛도 브랜드에 먼저 제안할 수 있습니다.</span>
        </div>

        <ul class="grid grid-cols-1 pc:grid-cols-2 items-stretch gap-4 mt-4">
            <li>
                <article class="h-full border border-gray-300 rounded-lg p-4 space-y-2">
                    <div class="flex gap-3">
                        <div class="w-14 aspect-square rounded-lg bg-amber-100"></div>
                        <div class="flex-1 min-w-0 space-y-1">
                            <p class="font-bold">러닝 메이트</p>
                            <span class="block text-2xs text-gray-400">취미 · 스포츠 · 공개 도넛</span>
                            <span class="block w-fit rounded-full text-2xs text-red-400 font-bold bg-red-50 px-2 py-1">♥ 서로 좋아요</span>
                        </div>
                    </div>

                    <p class="text-2xs text-gray-400">테니스를 사랑하는 도트들의 활발한 소통 공간입니다.</p>

                    <dl class="flex items-center gap-2">
                        <div class="w-full rounded-lg bg-gray-50 px-3 py-2">
                            <dt class="block text-2xs text-gray-400">가입 도트</dt>
                            <dd class="block font-bold">8,720명</dd>
                        </div>
                        <div class="w-full rounded-lg bg-gray-50 px-3 py-2">
                            <dt class="block text-2xs text-gray-400">이번 달 기여토핑</dt>
                            <dd class="block font-bold">2,184,600T</dd>
                        </div>
                    </dl>

                    <div class="flex items-center gap-2">
                        <button type="button" aria-label="테니스 커뮤니티 좋아요 취소" class="inline-flex h-10.5 items-center justify-center rounded-lg border border-red-400 bg-red-50 px-3 text-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="h-5 w-5">
                                <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                            </svg>
                        </button>
                        <button type="button" class="min-w-0 flex-1 rounded-lg border border-gray-300 p-3 text-center text-xs font-bold">대화 열기</button>
                    </div>
                </article>
            </li>

            <li>
                <article class="h-full border border-gray-300 rounded-lg p-4 space-y-2">
                    <div class="flex gap-3">
                        <div class="w-14 aspect-square rounded-lg bg-amber-100"></div>
                        <div class="flex-1 min-w-0 space-y-1">
                            <p class="font-bold">테니스 커뮤니티</p>
                            <span class="block text-2xs text-gray-400">취미 · 스포츠 · 공개 도넛</span>
                        </div>
                    </div>

                    <p class="text-2xs text-gray-400">함께 달리고 서로의 기록을 응원하는 러닝 커뮤니티입니다.</p>

                    <dl class="flex items-center gap-2">
                        <div class="w-full rounded-lg bg-gray-50 px-3 py-2">
                            <dt class="block text-2xs text-gray-400">가입 도트</dt>
                            <dd class="block font-bold">4,360명</dd>
                        </div>
                        <div class="w-full rounded-lg bg-gray-50 px-3 py-2">
                            <dt class="block text-2xs text-gray-400">이번 달 기여토핑</dt>
                            <dd class="block font-bold">1,286,400T</dd>
                        </div>
                    </dl>

                    <div class="flex items-center gap-2">
                        <button type="button" aria-label="러닝 메이트 좋아요 취소" class="inline-flex h-10.5 items-center justify-center rounded-lg border border-red-400 bg-red-50 px-3 text-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="h-5 w-5">
                                <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                            </svg>
                        </button>
                        <button type="button" class="min-w-0 flex-1 rounded-lg border border-gray-300 p-3 text-center text-xs font-bold">대화 열기</button>
                    </div>
                </article>
            </li>

            <li>
                <article class="h-full border border-gray-300 rounded-lg p-4 space-y-2">
                    <div class="flex gap-3">
                        <div class="w-14 aspect-square rounded-lg bg-amber-100"></div>
                        <div class="flex-1 min-w-0 space-y-1">
                            <p class="font-bold">사진 산책회</p>
                            <span class="block text-2xs text-gray-400">취미 · 사진 · 공개 도넛</span>
                        </div>
                    </div>

                    <p class="text-2xs text-gray-400">도시를 걸으며 일상의 장면을 기록하는 사진 커뮤니티입니다.</p>

                    <dl class="flex items-center gap-2">
                        <div class="w-full rounded-lg bg-gray-50 px-3 py-2">
                            <dt class="block text-2xs text-gray-400">가입 도트</dt>
                            <dd class="block font-bold">1,850명</dd>
                        </div>
                        <div class="w-full rounded-lg bg-gray-50 px-3 py-2">
                            <dt class="block text-2xs text-gray-400">이번 달 기여토핑</dt>
                            <dd class="block font-bold">742,000T</dd>
                        </div>
                    </dl>

                    <div class="flex items-center gap-2">
                        <button type="button" class="inline-flex h-10.5 items-center justify-center rounded-lg border border-red-400 bg-red-50 px-3 text-red-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="h-5 w-5">
                                <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                            </svg>
                        </button>
                        <button type="button" class="min-w-0 flex-1 rounded-lg border border-gray-300 p-3 text-center text-xs font-bold">대화 열기</button>
                    </div>
                </article>
            </li>

            <li>
                <article class="h-full border border-gray-300 rounded-lg p-4 space-y-2">
                    <div class="flex gap-3">
                        <div class="w-14 aspect-square rounded-lg bg-amber-100"></div>
                        <div class="flex-1 min-w-0 space-y-1">
                            <p class="font-bold">홈베이킹 살롱</p>
                            <span class="block text-2xs text-gray-400">푸드 · 베이킹 · 공개 도넛</span>
                        </div>
                    </div>

                    <p class="text-2xs text-gray-400">집에서 만드는 디저트와 레시피를 공유하는 커뮤니티입니다.</p>

                    <dl class="flex items-center gap-2">
                        <div class="w-full rounded-lg bg-gray-50 px-3 py-2">
                            <dt class="block text-2xs text-gray-400">가입 도트</dt>
                            <dd class="block font-bold">3,260명</dd>
                        </div>
                        <div class="w-full rounded-lg bg-gray-50 px-3 py-2">
                            <dt class="block text-2xs text-gray-400">이번 달 기여토핑</dt>
                            <dd class="block font-bold">918,000T</dd>
                        </div>
                    </dl>

                    <div class="flex items-center gap-2">
                        <button type="button" class="inline-flex h-10.5 items-center justify-center rounded-lg border border-gray-300 text-gray-400 px-3">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="h-5 w-5">
                                <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                            </svg>
                        </button>
                        <button type="button" class="min-w-0 flex-1 rounded-lg text-center text-xs text-gray-900 font-bold bg-amber-300 p-3">쪽지 보내기</button>
                    </div>
                </article>
            </li>

            <li>
                <article class="h-full border border-gray-300 rounded-lg p-4 space-y-2">
                    <div class="flex gap-3">
                        <div class="w-14 aspect-square rounded-lg bg-amber-100"></div>
                        <div class="flex-1 min-w-0 space-y-1">
                            <p class="font-bold">캠핑 위켄드</p>
                            <span class="block text-2xs text-gray-400">여행 · 아웃도어 · 공개 도넛</span>
                        </div>
                    </div>

                    <p class="text-2xs text-gray-400">주말 캠핑 장소와 장비 경험을 공유하는 커뮤니티입니다.</p>

                    <dl class="flex items-center gap-2">
                        <div class="w-full rounded-lg bg-gray-50 px-3 py-2">
                            <dt class="block text-2xs text-gray-400">가입 도트</dt>
                            <dd class="block font-bold">3,940명</dd>
                        </div>
                        <div class="w-full rounded-lg bg-gray-50 px-3 py-2">
                            <dt class="block text-2xs text-gray-400">이번 달 기여토핑</dt>
                            <dd class="block font-bold">1,052,000T</dd>
                        </div>
                    </dl>

                    <div class="flex items-center gap-2">
                        <button type="button" class="inline-flex h-10.5 items-center justify-center rounded-lg border border-gray-300 text-gray-400 px-3">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="h-5 w-5">
                                <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                            </svg>
                        </button>
                        <button type="button" class="min-w-0 flex-1 rounded-lg text-center text-xs text-gray-900 font-bold bg-amber-300 p-3">쪽지 보내기</button>
                    </div>
                </article>
            </li>
        </ul>
    </section>

</section>

<script>
    $('#suggestion-tab-chat').on('click', function() {
        $('#suggestion-panel-chat').removeAttr('hidden');
        $('#suggestion-panel-liked, #suggestion-panel-browse').attr('hidden', true);

        $('#suggestion-tab-chat').addClass('bg-gray-900 text-white').removeClass('text-gray-500');
        $('#suggestion-tab-liked, #suggestion-tab-browse').removeClass('bg-gray-900 text-white').addClass('text-gray-500');
    });

    $('#suggestion-tab-liked').on('click', function() {
        $('#suggestion-panel-liked').removeAttr('hidden');
        $('#suggestion-panel-chat, #suggestion-panel-browse').attr('hidden', true);

        $('#suggestion-tab-liked').addClass('bg-gray-900 text-white').removeClass('text-gray-500');
        $('#suggestion-tab-chat, #suggestion-tab-browse').removeClass('bg-gray-900 text-white').addClass('text-gray-500');
    });

    $('#suggestion-tab-browse').on('click', function() {
        $('#suggestion-panel-browse').removeAttr('hidden');
        $('#suggestion-panel-chat, #suggestion-panel-liked').attr('hidden', true);

        $('#suggestion-tab-browse').addClass('bg-gray-900 text-white').removeClass('text-gray-500');
        $('#suggestion-tab-chat, #suggestion-tab-liked').removeClass('bg-gray-900 text-white').addClass('text-gray-500');
    });
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

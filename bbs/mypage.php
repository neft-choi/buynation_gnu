<?php
include_once('./_common.php');

// 비로그인 상태에서 마이페이지 접속 시 로그인 페이지로 이동
if ($is_guest) {
    goto_url(G5_BBS_URL . '/login.php?url=' . login_url($_SERVER['REQUEST_URI']));
}

$g5['title'] = '내정보';

include_once(G5_THEME_PATH . '/head.php');
?>

<main class="w-full max-w-full mx-auto bg-white py-8">

    <section id="mypage-profile" class="p-4 mb-4 bg-white">
        <div class="flex flex-col items-center gap-3">
            <div class="relative">
                <div class="w-25 h-25 overflow-hidden rounded-full bg-gray-300">
                    <?php echo get_member_profile_img($member['mb_id'], 100, 100); ?>
                </div>

                <button type="button"
                    class="absolute right-0 bottom-0 z-10 inline-flex items-center justify-center rounded-full text-[#757575] bg-white shadow-[2px_2px_4px_0_#0000001A] p-2">
                    <svg width="11" height="11" viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.3018 3.66504L3.84473 10.4785L0.5 10.4971V7.40137L7.35156 0.703125L10.3018 3.66504Z"
                            fill="white" stroke="#757575" />
                    </svg>
                </button>
            </div>

            <div class="flex items-center gap-2">
                <p class="text-xl font-bold text-zinc-900">
                    <?php echo get_text($member['mb_nick']); ?>님
                </p>
                <a href="<?php echo G5_BBS_URL; ?>/logout.php" class="text-xs text-[#757575]">로그아웃</a>
            </div>

            <p class="text-base text-[#757575]">가입한 도넛 00 · 함께한 지 00일</p>
        </div>
    </section>

    <section class="px-4 mt-4">
        <div class="flex flex-col gap-4 rounded-[25px] bg-[#FAC74066] p-4">
            <div class="flex items-center justify-between">
                <span class="text-base font-bold">사용가능한 토핑</span>
                <a href="#" class="inline-flex items-center gap-1">
                    <span class="text-sm">적립 내역</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-full">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </a>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1">
                    <span class="text-2xl font-bold">3,000</span>
                    <span class="text-xs text-[#00000080] font-bold">토핑</span>
                </div>

                <div class="bg-[#FAC740] rounded-full px-2 py-1.5 text-xs">
                    <span>이번달 <span class="font-bold">+540</span> 적립</span>
                </div>
            </div>

            <a href="#"
                class="inline-flex items-center justify-center w-full text-sm text-white bg-black rounded-full p-4">
                <span>토핑으로 쇼핑하기</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-full">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>
        </div>
    </section>

    <section class="px-4 mt-4">
        <div class="flex flex-col gap-4 rounded-[25px] bg-[#FAC74066] p-4">
            <div class="flex items-center justify-between">
                <div class="inline-flex items-center gap-2">
                    <span class="text-base font-bold">기여토핑</span>
                    <div class="relative">
                        <button type="button" class="topping-info-open text-[#B7B7B7]">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-info-icon lucide-info w-4 h-full">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 16v-4" />
                                <path d="M12 8h.01" />
                            </svg>
                        </button>

                        <div
                            class="topping-info-popover hidden absolute top-5 left-0 w-max border border-black rounded bg-white text-xs p-2">
                            <p>기여토핑이란?<br>내가 구매한 모든 상품<br>총액에 대한 기여</p>
                            <button type="button" class="topping-info-close absolute top-1 right-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-x-icon lucide-x w-4 h-full">
                                    <path d="M18 6 6 18" />
                                    <path d="m6 6 12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <a href="#" class="inline-flex items-center gap-1">
                    <span class="text-sm">적립 내역</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-full">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </a>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-1">
                    <span class="text-2xl font-bold">3,000</span>
                    <span class="text-xs text-[#00000080] font-bold">토핑</span>
                </div>

                <div class="bg-[#FAC740] rounded-full px-2 py-1.5 text-xs">
                    <span>이번달 <span class="font-bold">+540</span> 적립</span>
                </div>
            </div>
        </div>
    </section>

    <section class="px-4 mt-8">
        <div class="flex flex-col gap-4 rounded-[25px] bg-[#F7F7F7] p-4">
            <p class="text-base font-bold">나의 커뮤니티 활동</p>

            <div>
                <div class="flex items-center gap-2" role="tablist">
                    <button type="button" class="rounded-full bg-black px-4 py-2 text-xs text-white" role="tab"
                        data-community-tab="posts" aria-selected="true">
                        작성글
                    </button>
                    <button type="button" class="rounded-full bg-white px-4 py-2 text-xs text-black" role="tab"
                        data-community-tab="comments" aria-selected="false">
                        작성 댓글
                    </button>
                    <button type="button" class="rounded-full bg-white px-4 py-2 text-xs text-black" role="tab"
                        data-community-tab="commented" aria-selected="false">
                        댓글 단 글
                    </button>
                    <button type="button" class="rounded-full bg-white px-4 py-2 text-xs text-black" role="tab"
                        data-community-tab="liked" aria-selected="false">
                        좋아요한 글
                    </button>
                </div>
            </div>

            <ul class="flex flex-col gap-4" data-community-panel="posts">
                <li class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-full bg-gray-300"></div>

                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold">작성 글 제목</p>

                        <div class="flex items-center gap-2 text-xs text-[#A9A9A9] mt-1">
                            <span>2000.00.00</span>
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 10 10" fill="currentColor" class="w-3.5 h-full"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.37891 0V6.89062H6.41016L4.69629 10L2.96484 6.89062H0V0H9.37891ZM4.6875 2.64844C4.24474 2.6486 3.88574 3.00649 3.88574 3.44727C3.88594 3.88788 4.24487 4.24593 4.6875 4.24609C5.13027 4.24609 5.49003 3.88798 5.49023 3.44727C5.49023 3.00638 5.1304 2.64844 4.6875 2.64844ZM7.29883 2.64844C6.85609 2.64862 6.49707 3.0065 6.49707 3.44727C6.49727 3.88786 6.85621 4.24591 7.29883 4.24609C7.7416 4.24609 8.10136 3.88798 8.10156 3.44727C8.10156 3.00638 7.74172 2.64844 7.29883 2.64844ZM2.08496 2.64551C1.64211 2.64556 1.28223 3.00349 1.28223 3.44434C1.28239 3.88504 1.64221 4.24311 2.08496 4.24316C2.52776 4.24316 2.88753 3.88508 2.8877 3.44434C2.8877 3.00345 2.52786 2.64551 2.08496 2.64551Z" />
                                </svg>
                                <span>007</span>
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 21 15" fill="currentColor" class="w-4 h-full"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.467 3.579A12.3 12.3 0 0 0 0 7.503a12.1 12.1 0 0 0 2.374 3.706c4.467 4.766 11.699 4.766 16.165 0A12.3 12.3 0 0 0 21 7.279a12 12 0 0 0-2.369-3.7c-4.466-4.772-11.698-4.772-16.164 0m8.033 8.016a4.208 4.208 0 1 1 4.207-4.207 4.21 4.21 0 0 1-4.207 4.207">
                                    </path>
                                    <path
                                        d="M10.5 5.607c-.985 0-1.787.801-1.787 1.787S9.516 9.18 10.5 9.18a1.787 1.787 0 0 0 0-3.573">
                                    </path>
                                </svg>
                                <span>007</span>
                            </span>
                        </div>
                    </div>

                    <div class="shrink-0 rounded-full text-[10px] bg-white px-2 py-1">
                        <span>좋아요 </span>
                        <span class="font-bold">+50</span>
                    </div>
                </li>

                <li class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-full bg-gray-300"></div>

                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold">작성 글 제목</p>

                        <div class="flex items-center gap-2 text-xs text-[#A9A9A9] mt-1">
                            <span>2000.00.00</span>
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 10 10" fill="currentColor" class="w-3.5 h-full"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.37891 0V6.89062H6.41016L4.69629 10L2.96484 6.89062H0V0H9.37891ZM4.6875 2.64844C4.24474 2.6486 3.88574 3.00649 3.88574 3.44727C3.88594 3.88788 4.24487 4.24593 4.6875 4.24609C5.13027 4.24609 5.49003 3.88798 5.49023 3.44727C5.49023 3.00638 5.1304 2.64844 4.6875 2.64844ZM7.29883 2.64844C6.85609 2.64862 6.49707 3.0065 6.49707 3.44727C6.49727 3.88786 6.85621 4.24591 7.29883 4.24609C7.7416 4.24609 8.10136 3.88798 8.10156 3.44727C8.10156 3.00638 7.74172 2.64844 7.29883 2.64844ZM2.08496 2.64551C1.64211 2.64556 1.28223 3.00349 1.28223 3.44434C1.28239 3.88504 1.64221 4.24311 2.08496 4.24316C2.52776 4.24316 2.88753 3.88508 2.8877 3.44434C2.8877 3.00345 2.52786 2.64551 2.08496 2.64551Z" />
                                </svg>
                                <span>007</span>
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 21 15" fill="currentColor" class="w-4 h-full"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.467 3.579A12.3 12.3 0 0 0 0 7.503a12.1 12.1 0 0 0 2.374 3.706c4.467 4.766 11.699 4.766 16.165 0A12.3 12.3 0 0 0 21 7.279a12 12 0 0 0-2.369-3.7c-4.466-4.772-11.698-4.772-16.164 0m8.033 8.016a4.208 4.208 0 1 1 4.207-4.207 4.21 4.21 0 0 1-4.207 4.207">
                                    </path>
                                    <path
                                        d="M10.5 5.607c-.985 0-1.787.801-1.787 1.787S9.516 9.18 10.5 9.18a1.787 1.787 0 0 0 0-3.573">
                                    </path>
                                </svg>
                                <span>007</span>
                            </span>
                        </div>
                    </div>

                    <div class="shrink-0 rounded-full text-[10px] bg-white px-2 py-1">
                        <span>좋아요 </span>
                        <span class="font-bold">+50</span>
                    </div>
                </li>

                <li class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-full bg-gray-300"></div>

                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold">작성 글 제목</p>

                        <div class="flex items-center gap-2 text-xs text-[#A9A9A9] mt-1">
                            <span>2000.00.00</span>
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 10 10" fill="currentColor" class="w-3.5 h-full"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.37891 0V6.89062H6.41016L4.69629 10L2.96484 6.89062H0V0H9.37891ZM4.6875 2.64844C4.24474 2.6486 3.88574 3.00649 3.88574 3.44727C3.88594 3.88788 4.24487 4.24593 4.6875 4.24609C5.13027 4.24609 5.49003 3.88798 5.49023 3.44727C5.49023 3.00638 5.1304 2.64844 4.6875 2.64844ZM7.29883 2.64844C6.85609 2.64862 6.49707 3.0065 6.49707 3.44727C6.49727 3.88786 6.85621 4.24591 7.29883 4.24609C7.7416 4.24609 8.10136 3.88798 8.10156 3.44727C8.10156 3.00638 7.74172 2.64844 7.29883 2.64844ZM2.08496 2.64551C1.64211 2.64556 1.28223 3.00349 1.28223 3.44434C1.28239 3.88504 1.64221 4.24311 2.08496 4.24316C2.52776 4.24316 2.88753 3.88508 2.8877 3.44434C2.8877 3.00345 2.52786 2.64551 2.08496 2.64551Z" />
                                </svg>
                                <span>007</span>
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 21 15" fill="currentColor" class="w-4 h-full"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.467 3.579A12.3 12.3 0 0 0 0 7.503a12.1 12.1 0 0 0 2.374 3.706c4.467 4.766 11.699 4.766 16.165 0A12.3 12.3 0 0 0 21 7.279a12 12 0 0 0-2.369-3.7c-4.466-4.772-11.698-4.772-16.164 0m8.033 8.016a4.208 4.208 0 1 1 4.207-4.207 4.21 4.21 0 0 1-4.207 4.207">
                                    </path>
                                    <path
                                        d="M10.5 5.607c-.985 0-1.787.801-1.787 1.787S9.516 9.18 10.5 9.18a1.787 1.787 0 0 0 0-3.573">
                                    </path>
                                </svg>
                                <span>007</span>
                            </span>
                        </div>
                    </div>

                    <div class="shrink-0 rounded-full text-[10px] bg-white px-2 py-1">
                        <span>좋아요 </span>
                        <span class="font-bold">+50</span>
                    </div>
                </li>
            </ul>

            <ul class="hidden flex flex-col gap-4" data-community-panel="comments">
            </ul>

            <ul class="hidden flex flex-col gap-4" data-community-panel="commented">
            </ul>

            <ul class="hidden flex flex-col gap-4" data-community-panel="liked">
            </ul>

            <a href="#" class="flex items-center justify-center gap-1 border-t border-[#75757533] pt-4 pb-2">
                <span class="text-xs">전체 내역 보기</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-full">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>
        </div>
    </section>

    <section class="px-4 mt-4">
        <div class="flex flex-col gap-4 rounded-[25px] bg-[#F7F7F7] p-4">
            <p class="text-base font-bold">나의 쇼핑</p>


            <div class="flex items-center gap-2" role="tablist">
                <button type="button" class="rounded-full bg-black px-4 py-2 text-xs text-white" role="tab"
                    data-shopping-tab="orders" aria-selected="true">
                    주문 배송 조회
                </button>
                <button type="button" class="rounded-full bg-white px-4 py-2 text-xs text-black" role="tab"
                    data-shopping-tab="recent" aria-selected="false">
                    최근 본 상품
                </button>
                <button type="button" class="rounded-full bg-white px-4 py-2 text-xs text-black" role="tab"
                    data-shopping-tab="frequent" aria-selected="false">
                    자주 산 상품
                </button>
            </div>

            <ul class="flex flex-col gap-4" data-shopping-panel="orders">
                <li class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded bg-gray-300"></div>

                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold">통풍 옷걸이 2개입</p>

                        <div class="flex items-center gap-2 text-xs text-[#A9A9A9] mt-1">
                            <span>39,000원</span>
                            <span>·</span>
                            <span>1일 전 구매</span>
                        </div>
                    </div>

                    <div class="shrink-0 rounded-full text-[10px] bg-white px-2 py-1">
                        <span>토핑 </span>
                        <span class="font-bold">+100</span>
                    </div>
                </li>

                <li class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded bg-gray-300"></div>

                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold">통풍 옷걸이 2개입</p>

                        <div class="flex items-center gap-2 text-xs text-[#A9A9A9] mt-1">
                            <span>39,000원</span>
                            <span>·</span>
                            <span>1일 전 구매</span>
                        </div>
                    </div>

                    <div class="shrink-0 rounded-full text-[10px] bg-white px-2 py-1">
                        <span>토핑 </span>
                        <span class="font-bold">+100</span>
                    </div>
                </li>

                <li class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded bg-gray-300"></div>

                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold">통풍 옷걸이 2개입</p>

                        <div class="flex items-center gap-2 text-xs text-[#A9A9A9] mt-1">
                            <span>39,000원</span>
                            <span>·</span>
                            <span>1일 전 구매</span>
                        </div>
                    </div>

                    <div class="shrink-0 rounded-full text-[10px] bg-white px-2 py-1">
                        <span>토핑 </span>
                        <span class="font-bold">+100</span>
                    </div>
                </li>
            </ul>

            <ul class="hidden flex flex-col gap-4" data-shopping-panel="recent">
            </ul>

            <ul class="hidden flex flex-col gap-4" data-shopping-panel="frequent">
            </ul>

            <a href="#" class="flex items-center justify-center gap-1 border-t border-[#75757533] pt-4 pb-2">
                <span class="text-xs">전체 내역 보기</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-full">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>
        </div>
    </section>

    <nav class="px-4 mt-8">
        <ul class="flex flex-col gap-3">
            <li>
                <a href="#"
                    class="flex items-center justify-between rounded-full text-base font-bold bg-[#F5F5F5] px-4 py-2.5">
                    <span>상품 문의</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-full">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </a>
            </li>

            <li>
                <a href="#"
                    class="flex items-center justify-between rounded-full text-base font-bold bg-[#F5F5F5] px-4 py-2.5">
                    <span>알림 설정</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-full">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </a>
            </li>

            <li>
                <a href="#"
                    class="flex items-center justify-between rounded-full text-base font-bold bg-[#F5F5F5] px-4 py-2.5">
                    <span>계정 관리</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-full">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </a>
            </li>

            <li>
                <a href="#"
                    class="flex items-center justify-between rounded-full text-base font-bold bg-[#F5F5F5] px-4 py-2.5">
                    <span>고객센터 도움말</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-full">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </a>
            </li>
        </ul>
    </nav>
</main>

<script>
    $(function () {
        $(".topping-info-open").on("click", function () {
            $(this).next(".topping-info-popover").removeClass("hidden");
        });

        $(".topping-info-close").on("click", function () {
            $(this).closest(".topping-info-popover").addClass("hidden");
        });

        // 나의 커뮤니티 활동 탭 기능
        const $communityTabs = $("[data-community-tab]");
        const $communityPanels = $("[data-community-panel]");
        const activeClasses = "bg-black text-white";
        const inactiveClasses = "bg-white text-black";

        $communityTabs.on("click", function () {
            const selectedTab = $(this).attr("data-community-tab");

            $communityTabs.removeClass(activeClasses).addClass(inactiveClasses).attr("aria-selected", "false");
            $(this).removeClass(inactiveClasses).addClass(activeClasses).attr("aria-selected", "true");

            $communityPanels.addClass("hidden");
            $communityPanels.filter('[data-community-panel="' + selectedTab + '"]').removeClass("hidden");
        });

        // 나의 쇼핑 탭 기능
        const $shoppingTabs = $("[data-shopping-tab]");
        const $shoppingPanels = $("[data-shopping-panel]");

        $shoppingTabs.on("click", function () {
            const selectedTab = $(this).attr("data-shopping-tab");

            $shoppingTabs.removeClass(activeClasses).addClass(inactiveClasses).attr("aria-selected", "false");
            $(this).removeClass(inactiveClasses).addClass(activeClasses).attr("aria-selected", "true");

            $shoppingPanels.addClass("hidden");
            $shoppingPanels.filter('[data-shopping-panel="' + selectedTab + '"]').removeClass("hidden");
        });
    });
</script>

<?php include_once(G5_THEME_PATH . '/tail.php'); ?>
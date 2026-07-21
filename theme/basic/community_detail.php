<?php
if (!defined('_GNUBOARD_'))
    exit;

if (G5_COMMUNITY_USE === false) {
    include_once(G5_THEME_SHOP_PATH . '/index.php');
    return;
}

include_once(G5_THEME_PATH . '/head.php');
?>

<main id="community-detail-page" class="pb-10">

    <section class="px-4">
        <div class="relative w-full aspect-[345/200] overflow-hidden rounded-[25px] bg-gray-300">
            <div class="absolute bottom-4 right-4 flex items-center gap-1.5">
                <span
                    class="inline-flex items-center justify-center w-7 h-7 border border-white/20 rounded-full text-white bg-white/20 px-0.5 py-1.5">
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12.38 5.04285C13.8286 5.04285 15 3.91159 15 2.52143C15 1.13126 13.8245 0 12.38 0C10.9354 0 9.75993 1.13126 9.75993 2.52143C9.75993 2.66084 9.77649 2.79627 9.79718 2.92772L4.77235 5.72001C4.3005 5.06675 3.51407 4.63655 2.62003 4.63655C1.17136 4.63655 0 5.76781 0 7.15798C0 8.54815 1.1755 9.6794 2.62003 9.6794C3.51407 9.6794 4.3005 9.24921 4.77235 8.59197L9.80132 11.3843C9.77649 11.5277 9.75993 11.675 9.75993 11.8264C9.75993 13.2206 10.9354 14.3478 12.38 14.3478C13.8245 14.3478 15 13.2166 15 11.8264C15 10.4362 13.8245 9.30498 12.38 9.30498C11.6391 9.30498 10.9727 9.59974 10.4967 10.0738L5.24007 7.154C5.24007 7.154 5.24007 7.154 5.24007 7.15001L10.4719 4.24221C10.9478 4.73215 11.6267 5.03887 12.38 5.03887V5.04285Z"
                            fill="currentcolor" />
                    </svg>
                </span>

                <span
                    class="inline-flex items-center justify-center w-7 h-7 border border-white/20 rounded-full text-white bg-white/20 px-0.5 py-1.5">
                    <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M1.52441 1.53516C2.88659 0.155046 5.09331 0.155353 6.4502 1.53418L7.13867 2.23438L7.49512 2.5957L7.85156 2.23438L8.1875 1.89258L8.19238 1.89746L8.5459 1.53906C9.86517 0.198627 11.977 0.156293 13.3457 1.41309L13.4756 1.53906C14.8407 2.92212 14.8416 5.1711 13.4756 6.55957L12.7861 7.25977L7.5 12.6201L2.21387 7.25977L1.52441 6.55957H1.52344C0.158297 5.17151 0.158059 2.92209 1.52344 1.53418L1.52441 1.53516Z"
                            stroke="currentcolor" />
                    </svg>
                </span>
            </div>
        </div>
        <div class="flex flex-col items-center gap-3 mt-5">
            <h2 class="text-lg font-bold">테니스 커뮤니티</h2>
            <p>설명을 적습니다.</p>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-2">
                    <svg viewBox="0 0 9 8" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-3">
                        <path
                            d="M4.91078 0.710181L4.49724 1.13011L4.0837 0.710181C3.15186 -0.236727 1.63555 -0.236727 0.70095 0.710181C-0.23365 1.65983 -0.23365 3.19684 0.70095 4.1465L1.11449 4.56643L4.5 8L7.88551 4.56643L8.29905 4.1465C9.23365 3.19684 9.23365 1.65983 8.29905 0.712926C7.36445 -0.236727 5.84814 -0.236727 4.91354 0.712926L4.91078 0.710181Z"
                            fill="black" fill-opacity="0.3" />
                    </svg>
                    <span>001</span>
                </span>
                <span class="inline-flex items-center gap-2">
                    <svg viewBox="0 0 11 11" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-3">
                        <g clip-path="url(#clip0_3026_700)">
                            <path
                                d="M2.17878 9.96651C3.08151 10.617 4.18789 10.9999 5.3885 10.9999C6.58911 10.9999 7.68333 10.62 8.58303 9.97867C8.24261 8.1732 7.01768 6.6808 5.37938 5.96347C4.70157 5.66864 3.95689 5.50146 3.16966 5.50146C1.97817 5.50146 0.874824 5.8814 -0.0248718 6.52274C0.242605 7.93611 1.05112 9.158 2.17574 9.96651H2.17878Z"
                                fill="black" fill-opacity="0.3" />
                            <path
                                d="M6.8414 5.55314C8.1028 6.4042 9.05417 7.67776 9.48274 9.17624C10.1423 8.44675 10.6074 7.53794 10.7988 6.5349C9.89611 5.88444 8.78973 5.50146 7.58912 5.50146C7.3338 5.50146 7.08456 5.5197 6.8414 5.55618V5.55314Z"
                                fill="black" fill-opacity="0.3" />
                            <path
                                d="M6.0724 3.83891C6.46754 4.20973 6.99945 4.43769 7.58608 4.43769C8.811 4.43769 9.80188 3.44377 9.80188 2.21885C9.80188 0.993921 8.811 0 7.58608 0C6.99337 0 6.45538 0.231003 6.06024 0.610942C6.3338 1.08815 6.48881 1.64438 6.48881 2.23708C6.48881 2.82979 6.33684 3.36474 6.0724 3.83891Z"
                                fill="black" fill-opacity="0.3" />
                            <path
                                d="M3.1727 4.43769C4.39814 4.43769 5.39155 3.44428 5.39155 2.21885C5.39155 0.993411 4.39814 0 3.1727 0C1.94727 0 0.953857 0.993411 0.953857 2.21885C0.953857 3.44428 1.94727 4.43769 3.1727 4.43769Z"
                                fill="black" fill-opacity="0.3" />
                        </g>
                        <defs>
                            <clipPath id="clip0_3026_700">
                                <rect width="10.8207" height="11" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>
                    <span>50명</span>
                </span>
            </div>
        </div>

        <div
            class="flex items-center justify-between text-xs text-black font-bold bg-white px-3 py-2.5 rounded-full mt-4">
            <p>[공지] 오프라인 모임 신청 폼</p>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-4">
                <path d="m9 18 6-6-6-6" />
            </svg>
        </div>
    </section>

    <section id="community-content" class="mt-5">
        <nav id="community-tabs">
            <ul class="grid grid-cols-3 border-b border-black/30">
                <li>
                    <button type="button" class="w-full border-b-[3px] border-transparent px-1 py-3 text-xs text-black">
                        추천상품
                    </button>
                </li>
                <li>
                    <button type="button"
                        class="w-full border-b-[3px] border-black px-1 py-3 text-xs font-bold text-black">
                        커뮤니티 01
                    </button>
                </li>
                <li>
                    <button type="button" class="w-full border-b-[3px] border-transparent px-1 py-3 text-xs text-black">
                        커뮤니티 02
                    </button>
                </li>
            </ul>
        </nav>


        <div id="panel-recommend" class="hidden px-4 pt-6 bg-white"></div>

        <div id="panel-community-01" class="px-4 pt-6 bg-white">
            <div class="flex flex-col gap-6 [&>article]:pb-3 ">
                <article>
                    <header class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-300"></div>
                            <div class="flex flex-col">
                                <span class="text-base font-medium">작성자명</span>
                                <span class="mt-1 text-xs font-medium">4시간 전</span>
                            </div>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-ellipsis-vertical-icon lucide-ellipsis-vertical w-5 h-5">
                            <circle cx="12" cy="12" r="1" />
                            <circle cx="12" cy="5" r="1" />
                            <circle cx="12" cy="19" r="1" />
                        </svg>
                    </header>

                    <p class="mt-3 text-xs">
                        오늘 저녁은 불향 가득한 꼬치구이 파티!🍢✨<br>
                        주말을 맞아 집에서 노릇노릇하게 고기 꼬치를 구워봤어요.<br>
                        육즙 가득한 닭날개랑 고기 꼬치에 고소한 빵까지 곁들이니까 웬만한 맛집 부럽지 않네요. 시원한 맥주 한잔이 절로 생각나는
                        비주얼이죠? 🍺<br>
                        다들 오늘 저녁 맛있는 거 드셨나요? 여러분의 오늘 저녁 메뉴도 댓글로 자랑해 주세요! 👇
                    </p>

                    <button type="button" class="mt-1 text-xs text-black/50">...더보기</button>

                    <div class="aspect-[345/200] w-full rounded-[25px] bg-gray-300 mt-3"></div>

                    <div class="flex items-center justify-between mt-3 text-xs text-black/30">
                        <span class="inline-flex items-center gap-1">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.971 5.381H0V15h2.971zM8.983 0H5.98L4.471 5.207v9.76h9.017L15 5.148H8.959z"
                                    fill="currentColor" />
                            </svg>
                            <span>000</span>
                        </span>

                        <span class="inline-flex items-center gap-1">
                            <svg width="21" height="15" viewBox="0 0 21 15" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M2.467 3.579A12.3 12.3 0 0 0 0 7.503a12.1 12.1 0 0 0 2.374 3.706c4.467 4.766 11.699 4.766 16.165 0A12.3 12.3 0 0 0 21 7.279a12 12 0 0 0-2.369-3.7c-4.466-4.772-11.698-4.772-16.164 0m8.033 8.016a4.208 4.208 0 1 1 4.207-4.207 4.21 4.21 0 0 1-4.207 4.207" />
                                <path
                                    d="M10.5 5.607c-.985 0-1.787.801-1.787 1.787S9.516 9.18 10.5 9.18a1.787 1.787 0 0 0 0-3.573" />
                            </svg>
                            <span>000</span>
                        </span>
                    </div>

                    <section class="mt-4 border-t border-[#DFDFDF] py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="inline-flex items-center gap-1 text-sm">
                                <span class="font-bold">댓글</span>
                                <span>0개</span>
                            </h3>
                            <div class="flex items-center gap-1 text-xs">
                                <button type="button" class="font-bold">등록순</button>
                                <span>|</span>
                                <button type="button">최신순</button>
                            </div>
                        </div>

                        <form class="mt-6 flex items-center gap-3">
                            <input type="text" aria-label="댓글 입력" placeholder="첫 댓글을 남겨보세요."
                                class="min-w-0 flex-1 rounded-full bg-[#F5F5F5] p-4 text-xs">
                            <button type="submit" aria-label="댓글 등록"
                                class="shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-[#A9A9A9] text-white shadow-[0_0_10.7px_0_#0000000D]">
                                <svg width="15" height="11" viewBox="0 0 15 11" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.74557 9.48532L13.9882 5.24268L9.74557 1.00004" stroke="currentcolor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M13.8311 5.4043H1" stroke="white" stroke-width="2"
                                        stroke-linecap="round" />
                                </svg>
                            </button>
                        </form>
                    </section>
                </article>

                <article>
                    <header class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-300"></div>
                            <div class="flex flex-col">
                                <span class="text-base font-medium">작성자명</span>
                                <span class="mt-1 text-xs font-medium">4시간 전</span>
                            </div>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-ellipsis-vertical-icon lucide-ellipsis-vertical w-5 h-5">
                            <circle cx="12" cy="12" r="1" />
                            <circle cx="12" cy="5" r="1" />
                            <circle cx="12" cy="19" r="1" />
                        </svg>
                    </header>

                    <p class="mt-3 text-xs">이번 캠핑 하이라이트 요리 대공개! 🔥<br>역시 야외에서 숯불에 구워 먹는 꼬치구이는 치트키네요... <br>제대로 겉바속촉으로
                        구워져서
                        순식간에
                        흡입했습니다. 같이 구운 빵이랑
                        조합이 아주 미쳤어요 🤤<br>날씨도 좋은데 이번 주말에 캠핑이나 글램핑 계획 있으신 분들, 꼬치구이 메뉴 적극 추천합니다!</p>

                    <button type="button" class="mt-1 text-xs text-black/50">...더보기</button>

                    <div class="aspect-[345/200] w-full rounded-[25px] bg-gray-300 mt-3"></div>

                    <div class="flex items-center justify-between mt-3 text-xs text-black/30">
                        <span class="inline-flex items-center gap-1">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.971 5.381H0V15h2.971zM8.983 0H5.98L4.471 5.207v9.76h9.017L15 5.148H8.959z"
                                    fill="currentColor" />
                            </svg>
                            <span>000</span>
                        </span>

                        <span class="inline-flex items-center gap-1">
                            <svg width="21" height="15" viewBox="0 0 21 15" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M2.467 3.579A12.3 12.3 0 0 0 0 7.503a12.1 12.1 0 0 0 2.374 3.706c4.467 4.766 11.699 4.766 16.165 0A12.3 12.3 0 0 0 21 7.279a12 12 0 0 0-2.369-3.7c-4.466-4.772-11.698-4.772-16.164 0m8.033 8.016a4.208 4.208 0 1 1 4.207-4.207 4.21 4.21 0 0 1-4.207 4.207" />
                                <path
                                    d="M10.5 5.607c-.985 0-1.787.801-1.787 1.787S9.516 9.18 10.5 9.18a1.787 1.787 0 0 0 0-3.573" />
                            </svg>
                            <span>000</span>
                        </span>
                    </div>

                    <section class="mt-4 border-t border-[#DFDFDF] py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="inline-flex items-center gap-1 text-sm">
                                <span class="font-bold">댓글</span>
                                <span>3개</span>
                            </h3>
                            <div class="flex items-center gap-1 text-xs">
                                <button type="button" class="font-bold">등록순</button>
                                <span>|</span>
                                <button type="button">최신순</button>
                            </div>
                        </div>

                        <ul class="flex flex-col gap-4 divide-y divide-[#DFDFDF] [&>li]:pb-4 mt-3">
                            <li class="pl-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7.5 h-7.5 rounded-full bg-gray-300"></div>
                                    <span class="text-sm font-medium">작성자명</span>
                                </div>
                                <p class="mt-2 text-xs">댓글 내용입니다.</p>
                                <div class="mt-2 flex items-center gap-2 text-xs text-black/30">
                                    <span>5분 전</span>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1">
                                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M2.971 5.381H0V15h2.971zM8.983 0H5.98L4.471 5.207v9.76h9.017L15 5.148H8.959z"
                                                    fill="currentcolor" />
                                            </svg>
                                            <span>000</span>
                                        </span>

                                        <span class="inline-flex items-center gap-1">
                                            <svg width="15" height="18" viewBox="0 0 15 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M15.0049 2V13.0254H10.2559L7.51367 18L4.74414 13.0254H0V2H15.0049ZM7.5 6.2373C6.79154 6.2373 6.21603 6.80941 6.21582 7.51465C6.21582 8.22006 6.79141 8.79297 7.5 8.79297C8.20859 8.79297 8.78418 8.22006 8.78418 7.51465C8.78397 6.80941 8.20846 6.2373 7.5 6.2373ZM11.6777 6.2373C10.9694 6.2374 10.3947 6.80947 10.3945 7.51465C10.3945 8.22 10.9692 8.79287 11.6777 8.79297C12.3863 8.79297 12.9619 8.22006 12.9619 7.51465C12.9617 6.80941 12.3862 6.2373 11.6777 6.2373ZM3.33594 6.23242C2.62735 6.23242 2.05176 6.80533 2.05176 7.51074C2.05192 8.21602 2.62745 8.78809 3.33594 8.78809C4.04441 8.78806 4.61996 8.216 4.62012 7.51074C4.62012 6.80535 4.0445 6.23245 3.33594 6.23242Z"
                                                    fill="currentcolor" />
                                            </svg>
                                            <span>000</span>
                                        </span>
                                    </div>
                                </div>

                                <ul
                                    class="mt-4 flex flex-col gap-4 divide-y divide-[#DFDFDF] [&>li]:pb-4 [&>li:last-child]:pb-0">
                                    <li class="pl-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7.5 h-7.5 rounded-full bg-gray-300"></div>
                                            <span class="text-sm font-medium">작성자명</span>
                                        </div>
                                        <p class="mt-2 text-xs">댓글 내용입니다.</p>
                                        <div class="mt-2 flex items-center gap-2 text-xs text-black/30">
                                            <span>1분 전</span>
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center gap-1">
                                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <g clip-path="url(#clip0_3026_1219)">
                                                            <path d="M2.97099 5.38147H0V14.9999H2.97099V5.38147Z"
                                                                fill="black" fill-opacity="0.3" />
                                                            <path
                                                                d="M8.98344 0H5.9793L4.47101 5.20671V14.9667H13.4876L15 5.14845H8.95858L8.98344 0Z"
                                                                fill="currentcolor" />
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_3026_1219">
                                                                <rect width="15" height="15" fill="white" />
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                    <span>000</span>
                                                </span>

                                                <span class="inline-flex items-center gap-1">
                                                    <svg width="15" height="18" viewBox="0 0 15 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M15.0049 2V13.0254H10.2559L7.51367 18L4.74414 13.0254H0V2H15.0049ZM7.5 6.2373C6.79154 6.2373 6.21603 6.80941 6.21582 7.51465C6.21582 8.22006 6.79141 8.79297 7.5 8.79297C8.20859 8.79297 8.78418 8.22006 8.78418 7.51465C8.78397 6.80941 8.20846 6.2373 7.5 6.2373ZM11.6777 6.2373C10.9694 6.2374 10.3947 6.80947 10.3945 7.51465C10.3945 8.22 10.9692 8.79287 11.6777 8.79297C12.3863 8.79297 12.9619 8.22006 12.9619 7.51465C12.9617 6.80941 12.3862 6.2373 11.6777 6.2373ZM3.33594 6.23242C2.62735 6.23242 2.05176 6.80533 2.05176 7.51074C2.05192 8.21602 2.62745 8.78809 3.33594 8.78809C4.04441 8.78806 4.61996 8.216 4.62012 7.51074C4.62012 6.80535 4.0445 6.23245 3.33594 6.23242Z"
                                                            fill="currentcolor" />
                                                    </svg>
                                                    <span>000</span>
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>

                            <li class="pl-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7.5 h-7.5 rounded-full bg-gray-300"></div>
                                    <span class="text-sm font-medium">작성자명</span>
                                </div>
                                <p class="mt-2 text-xs">댓글 내용입니다.</p>
                                <div class="mt-2 flex items-center gap-2 text-xs text-black/30">
                                    <span>5분 전</span>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1">
                                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M2.971 5.381H0V15h2.971zM8.983 0H5.98L4.471 5.207v9.76h9.017L15 5.148H8.959z"
                                                    fill="currentcolor" />
                                            </svg>
                                            <span>000</span>
                                        </span>

                                        <span class="inline-flex items-center gap-1">
                                            <svg width="15" height="18" viewBox="0 0 15 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M15.0049 2V13.0254H10.2559L7.51367 18L4.74414 13.0254H0V2H15.0049ZM7.5 6.2373C6.79154 6.2373 6.21603 6.80941 6.21582 7.51465C6.21582 8.22006 6.79141 8.79297 7.5 8.79297C8.20859 8.79297 8.78418 8.22006 8.78418 7.51465C8.78397 6.80941 8.20846 6.2373 7.5 6.2373ZM11.6777 6.2373C10.9694 6.2374 10.3947 6.80947 10.3945 7.51465C10.3945 8.22 10.9692 8.79287 11.6777 8.79297C12.3863 8.79297 12.9619 8.22006 12.9619 7.51465C12.9617 6.80941 12.3862 6.2373 11.6777 6.2373ZM3.33594 6.23242C2.62735 6.23242 2.05176 6.80533 2.05176 7.51074C2.05192 8.21602 2.62745 8.78809 3.33594 8.78809C4.04441 8.78806 4.61996 8.216 4.62012 7.51074C4.62012 6.80535 4.0445 6.23245 3.33594 6.23242Z"
                                                    fill="currentcolor" />
                                            </svg>
                                            <span>000</span>
                                        </span>
                                    </div>
                                </div>
                            </li>
                        </ul>

                        <button type="button"
                            class="flex items-center justify-center w-full border-y border-[#DFDFDF] py-4 text-sm font-bold">
                            더 보기
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                                class="w-4 h-4">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                        </button>

                        <form class="mt-6 flex items-center gap-3">
                            <input type="text" aria-label="댓글 입력" placeholder="첫 댓글을 남겨보세요."
                                class="min-w-0 flex-1 rounded-full bg-[#F5F5F5] p-4 text-xs">
                            <button type="submit" aria-label="댓글 등록"
                                class="shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-[#A9A9A9] text-white shadow-[0_0_10.7px_0_#0000000D]">
                                <svg width="15" height="11" viewBox="0 0 15 11" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.74557 9.48532L13.9882 5.24268L9.74557 1.00004" stroke="currentcolor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M13.8311 5.4043H1" stroke="white" stroke-width="2"
                                        stroke-linecap="round" />
                                </svg>
                            </button>
                        </form>
                    </section>
                </article>

                <article>
                    <header class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-300"></div>
                            <div class="flex flex-col">
                                <span class="text-base font-medium">작성자명</span>
                                <span class="mt-1 text-xs font-medium">4시간 전</span>
                            </div>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-ellipsis-vertical-icon lucide-ellipsis-vertical w-5 h-5">
                            <circle cx="12" cy="12" r="1" />
                            <circle cx="12" cy="5" r="1" />
                            <circle cx="12" cy="19" r="1" />
                        </svg>
                    </header>

                    <p class="mt-3 text-xs">
                        오늘 저녁은 불향 가득한 꼬치구이 파티!🍢✨<br>
                        주말을 맞아 집에서 노릇노릇하게 고기 꼬치를 구워봤어요.<br>
                        육즙 가득한 닭날개랑 고기 꼬치에 고소한 빵까지 곁들이니까 웬만한 맛집 부럽지 않네요. 시원한 맥주 한잔이 절로 생각나는
                        비주얼이죠? 🍺<br>
                        다들 오늘 저녁 맛있는 거 드셨나요? 여러분의 오늘 저녁 메뉴도 댓글로 자랑해 주세요! 👇
                    </p>

                    <button type="button" class="mt-1 text-xs text-black/50">...더보기</button>

                    <div class="aspect-[345/200] w-full rounded-[25px] bg-gray-300 mt-3"></div>

                    <div class="flex items-center justify-between mt-3 text-xs text-black/30">
                        <span class="inline-flex items-center gap-1">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.971 5.381H0V15h2.971zM8.983 0H5.98L4.471 5.207v9.76h9.017L15 5.148H8.959z"
                                    fill="currentColor" />
                            </svg>
                            <span>000</span>
                        </span>

                        <span class="inline-flex items-center gap-1">
                            <svg width="21" height="15" viewBox="0 0 21 15" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M2.467 3.579A12.3 12.3 0 0 0 0 7.503a12.1 12.1 0 0 0 2.374 3.706c4.467 4.766 11.699 4.766 16.165 0A12.3 12.3 0 0 0 21 7.279a12 12 0 0 0-2.369-3.7c-4.466-4.772-11.698-4.772-16.164 0m8.033 8.016a4.208 4.208 0 1 1 4.207-4.207 4.21 4.21 0 0 1-4.207 4.207" />
                                <path
                                    d="M10.5 5.607c-.985 0-1.787.801-1.787 1.787S9.516 9.18 10.5 9.18a1.787 1.787 0 0 0 0-3.573" />
                            </svg>
                            <span>000</span>
                        </span>
                    </div>

                    <section class="mt-4 border-t border-[#DFDFDF] py-4">
                        <div class="flex items-center justify-between">
                            <h3 class="inline-flex items-center gap-1 text-sm">
                                <span class="font-bold">댓글</span>
                                <span>0개</span>
                            </h3>
                            <div class="flex items-center gap-1 text-xs">
                                <button type="button" class="font-bold">등록순</button>
                                <span>|</span>
                                <button type="button">최신순</button>
                            </div>
                        </div>

                        <form class="mt-6 flex items-center gap-3">
                            <input type="text" aria-label="댓글 입력" placeholder="첫 댓글을 남겨보세요."
                                class="min-w-0 flex-1 rounded-full bg-[#F5F5F5] p-4 text-xs">
                            <button type="submit" aria-label="댓글 등록"
                                class="shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-[#A9A9A9] text-white shadow-[0_0_10.7px_0_#0000000D]">
                                <svg width="15" height="11" viewBox="0 0 15 11" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.74557 9.48532L13.9882 5.24268L9.74557 1.00004" stroke="currentcolor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M13.8311 5.4043H1" stroke="white" stroke-width="2"
                                        stroke-linecap="round" />
                                </svg>
                            </button>
                        </form>
                    </section>
                </article>
            </div>
        </div>

        <div id="panel-community-02" class="hidden px-4 pt-6 bg-white"></div>
    </section>
</main>

<button type="button" aria-label="글 작성"
    class="fixed bottom-28 right-5 z-30 flex items-center justify-center w-15 h-15 rounded-full bg-[#FAC740] shadow-[0_0_11.9px_0_#00000026]">
    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M23.947 22.2809H0V24.7095H23.947V22.2809Z" fill="black" />
        <path d="M4.82251 12.5489V19.196L11.9039 19.1561L24 6.38093L17.6472 0L4.82251 12.5489Z" fill="black" />
    </svg>
</button>

<div id="community-join-modal" class="fixed inset-0 z-60 flex items-center justify-center bg-black/65 px-5">
    <div role="dialog" aria-modal="true" aria-labelledby="community-join-modal-title" class="w-full">
        <div class="relative rounded-[25px] bg-white px-5 pb-4 pt-18">
            <div class="absolute left-1/2 top-0 w-25 h-25 -translate-x-1/2 -translate-y-1/2 rounded-full bg-gray-300">
            </div>

            <div class="text-center">
                <h2 id="community-join-modal-title" class="text-lg font-bold">테니스 커뮤니티</h2>
                <p class="mt-1.5 text-xs">설명을 적습니다.</p>

                <div class="mt-3 flex items-center justify-center gap-3 text-xs text-black/30">
                    <span class="inline-flex items-center gap-1">
                        <svg viewBox="0 0 25 22" fill="none" xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-full fill-current">
                            <path
                                d="M2.65527 2.65918C4.79228 0.515683 8.21567 0.4497 10.4248 2.45703L10.6348 2.6582L11.7832 3.81348C11.9708 4.00204 12.2262 4.10738 12.4922 4.10742C12.7582 4.10742 13.0135 4.00198 13.2012 3.81348L14.25 2.75781C14.2873 2.72961 14.3245 2.70014 14.3584 2.66602C16.5635 0.447801 20.1386 0.447796 22.3438 2.66602L22.3447 2.66699C24.5509 4.88006 24.5523 8.47524 22.3438 10.6973L21.1953 11.8516L12.5 20.582L3.80371 11.8516L2.65527 10.6973C0.448057 8.47606 0.448251 4.88051 2.65527 2.65918Z"
                                stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                        </svg>
                        <span>000</span>
                    </span>
                    <span class="inline-flex items-center gap-1">
                        <svg viewBox="0 0 15 15" fill="currentColor" class="w-4 h-full fill-current"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M2.971 13.59A7.45 7.45 0 0 0 7.348 15a7.45 7.45 0 0 0 4.356-1.393A7.516 7.516 0 0 0-.034 8.895a7.5 7.5 0 0 0 3 4.696zM9.33 7.573a8.94 8.94 0 0 1 3.601 4.94 7.45 7.45 0 0 0 1.795-3.602 7.45 7.45 0 0 0-5.397-1.334zM8.28 5.235a3 3 0 0 0 2.065.816 3.023 3.023 0 0 0 3.021-3.025A3.023 3.023 0 0 0 10.345 0a3 3 0 0 0-2.081.833c.373.65.584 1.41.584 2.218s-.207 1.537-.567 2.184m-3.955.816a3.026 3.026 0 1 0 0-6.051 3.026 3.026 0 0 0 0 6.051" />
                        </svg>
                        <span>50명</span>
                    </span>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3">
                <button type="button" aria-label="관심 커뮤니티 등록"
                    class="flex h-12 w-12 shrink-0 items-center justify-center text-black/50">
                    <svg width="25" height="22" viewBox="0 0 25 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M2.65527 2.65918C4.79228 0.515683 8.21567 0.4497 10.4248 2.45703L10.6348 2.6582L11.7832 3.81348C11.9708 4.00204 12.2262 4.10738 12.4922 4.10742C12.7582 4.10742 13.0135 4.00198 13.2012 3.81348L14.25 2.75781C14.2873 2.72961 14.3245 2.70014 14.3584 2.66602C16.5635 0.447801 20.1386 0.447796 22.3438 2.66602L22.3447 2.66699C24.5509 4.88006 24.5523 8.47524 22.3438 10.6973L21.1953 11.8516L12.5 20.582L3.80371 11.8516L2.65527 10.6973C0.448057 8.47606 0.448251 4.88051 2.65527 2.65918Z"
                            stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                    </svg>
                </button>

                <button type="button" data-modal-close
                    class="flex-1 rounded-full bg-black p-4 text-xs text-white font-bold cursor-pointer hover:bg-black/80">
                    도넛 가입하고 둘러보기
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        const $joinModal = $("#community-join-modal");
        const $html = $("html");

        if ($joinModal.length && !$joinModal.hasClass("hidden")) {
            $html.css({
                overflow: "hidden",
                "scrollbar-gutter": "stable"
            });
        }

        $joinModal.on("click", "[data-modal-close]", function () {
            $joinModal.addClass("hidden");

            $html.css({
                overflow: "",
                "scrollbar-gutter": ""
            });
        });
    });
</script>

<?php
include_once(G5_THEME_PATH . '/tail.php');
?>
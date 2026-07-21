<?php
if (!defined('_GNUBOARD_'))
    exit;

if (G5_COMMUNITY_USE === false) {
    include_once(G5_THEME_SHOP_PATH . '/index.php');
    return;
}

$g5['title'] = '좋아요';

include_once(G5_THEME_PATH . '/head.php');
?>

<main id="likes-page" class="p-4 pb-8">
    <section>
        <div>
            <div class="flex items-center gap-2" role="tablist">
                <button type="button" class="rounded-full bg-black px-4 py-2 text-sm font-bold text-white" role="tab"
                    aria-selected="true">
                    커뮤니티
                </button>
                <button type="button" class="rounded-full bg-white px-4 py-2 text-sm font-bold text-black" role="tab"
                    aria-selected="false">
                    브랜드
                </button>
                <button type="button" class="rounded-full bg-white px-4 py-2 text-sm font-bold text-black" role="tab"
                    aria-selected="false">
                    상품
                </button>
            </div>
        </div>

        <div class="mt-6">
            <ul class="flex flex-col gap-4">
                <li class="flex items-center gap-3">
                    <div class="w-15 h-15 rounded-full bg-gray-300"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold">테니스 커뮤니티</p>
                        
                        <div class="flex items-center gap-2 text-xs text-[#A9A9A9] mt-1">
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 25 22" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="w-3 h-full fill-current">
                                    <path
                                        d="M2.65527 2.65918C4.79228 0.515683 8.21567 0.4497 10.4248 2.45703L10.6348 2.6582L11.7832 3.81348C11.9708 4.00204 12.2262 4.10738 12.4922 4.10742C12.7582 4.10742 13.0135 4.00198 13.2012 3.81348L14.25 2.75781C14.2873 2.72961 14.3245 2.70014 14.3584 2.66602C16.5635 0.447801 20.1386 0.447796 22.3438 2.66602L22.3447 2.66699C24.5509 4.88006 24.5523 8.47524 22.3438 10.6973L21.1953 11.8516L12.5 20.582L3.80371 11.8516L2.65527 10.6973C0.448057 8.47606 0.448251 4.88051 2.65527 2.65918Z"
                                        stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                                </svg>
                                <span>000</span>
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 15 15" fill="currentColor" class="w-3 h-full fill-current"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.971 13.59A7.45 7.45 0 0 0 7.348 15a7.45 7.45 0 0 0 4.356-1.393A7.516 7.516 0 0 0-.034 8.895a7.5 7.5 0 0 0 3 4.696zM9.33 7.573a8.94 8.94 0 0 1 3.601 4.94 7.45 7.45 0 0 0 1.795-3.602 7.45 7.45 0 0 0-5.397-1.334zM8.28 5.235a3 3 0 0 0 2.065.816 3.023 3.023 0 0 0 3.021-3.025A3.023 3.023 0 0 0 10.345 0a3 3 0 0 0-2.081.833c.373.65.584 1.41.584 2.218s-.207 1.537-.567 2.184m-3.955.816a3.026 3.026 0 1 0 0-6.051 3.026 3.026 0 0 0 0 6.051" />
                                </svg>
                                <span>50명</span>
                            </span>
                            <span>·</span>
                            <span>1시간 전 활동</span>
                        </div>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-full">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </div>
                </li>

                                <li class="flex items-center gap-3">
                    <div class="w-15 h-15 rounded-full bg-gray-300"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold">테니스 커뮤니티</p>
                        
                        <div class="flex items-center gap-2 text-xs text-[#A9A9A9] mt-1">
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 25 22" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="w-3 h-full fill-current">
                                    <path
                                        d="M2.65527 2.65918C4.79228 0.515683 8.21567 0.4497 10.4248 2.45703L10.6348 2.6582L11.7832 3.81348C11.9708 4.00204 12.2262 4.10738 12.4922 4.10742C12.7582 4.10742 13.0135 4.00198 13.2012 3.81348L14.25 2.75781C14.2873 2.72961 14.3245 2.70014 14.3584 2.66602C16.5635 0.447801 20.1386 0.447796 22.3438 2.66602L22.3447 2.66699C24.5509 4.88006 24.5523 8.47524 22.3438 10.6973L21.1953 11.8516L12.5 20.582L3.80371 11.8516L2.65527 10.6973C0.448057 8.47606 0.448251 4.88051 2.65527 2.65918Z"
                                        stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                                </svg>
                                <span>000</span>
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 15 15" fill="currentColor" class="w-3 h-full fill-current"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.971 13.59A7.45 7.45 0 0 0 7.348 15a7.45 7.45 0 0 0 4.356-1.393A7.516 7.516 0 0 0-.034 8.895a7.5 7.5 0 0 0 3 4.696zM9.33 7.573a8.94 8.94 0 0 1 3.601 4.94 7.45 7.45 0 0 0 1.795-3.602 7.45 7.45 0 0 0-5.397-1.334zM8.28 5.235a3 3 0 0 0 2.065.816 3.023 3.023 0 0 0 3.021-3.025A3.023 3.023 0 0 0 10.345 0a3 3 0 0 0-2.081.833c.373.65.584 1.41.584 2.218s-.207 1.537-.567 2.184m-3.955.816a3.026 3.026 0 1 0 0-6.051 3.026 3.026 0 0 0 0 6.051" />
                                </svg>
                                <span>50명</span>
                            </span>
                            <span>·</span>
                            <span>1시간 전 활동</span>
                        </div>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-full">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </div>
                </li>

                                <li class="flex items-center gap-3">
                    <div class="w-15 h-15 rounded-full bg-gray-300"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold">테니스 커뮤니티</p>
                        
                        <div class="flex items-center gap-2 text-xs text-[#A9A9A9] mt-1">
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 25 22" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="w-3 h-full fill-current">
                                    <path
                                        d="M2.65527 2.65918C4.79228 0.515683 8.21567 0.4497 10.4248 2.45703L10.6348 2.6582L11.7832 3.81348C11.9708 4.00204 12.2262 4.10738 12.4922 4.10742C12.7582 4.10742 13.0135 4.00198 13.2012 3.81348L14.25 2.75781C14.2873 2.72961 14.3245 2.70014 14.3584 2.66602C16.5635 0.447801 20.1386 0.447796 22.3438 2.66602L22.3447 2.66699C24.5509 4.88006 24.5523 8.47524 22.3438 10.6973L21.1953 11.8516L12.5 20.582L3.80371 11.8516L2.65527 10.6973C0.448057 8.47606 0.448251 4.88051 2.65527 2.65918Z"
                                        stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                                </svg>
                                <span>000</span>
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 15 15" fill="currentColor" class="w-3 h-full fill-current"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.971 13.59A7.45 7.45 0 0 0 7.348 15a7.45 7.45 0 0 0 4.356-1.393A7.516 7.516 0 0 0-.034 8.895a7.5 7.5 0 0 0 3 4.696zM9.33 7.573a8.94 8.94 0 0 1 3.601 4.94 7.45 7.45 0 0 0 1.795-3.602 7.45 7.45 0 0 0-5.397-1.334zM8.28 5.235a3 3 0 0 0 2.065.816 3.023 3.023 0 0 0 3.021-3.025A3.023 3.023 0 0 0 10.345 0a3 3 0 0 0-2.081.833c.373.65.584 1.41.584 2.218s-.207 1.537-.567 2.184m-3.955.816a3.026 3.026 0 1 0 0-6.051 3.026 3.026 0 0 0 0 6.051" />
                                </svg>
                                <span>50명</span>
                            </span>
                            <span>·</span>
                            <span>1시간 전 활동</span>
                        </div>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-full">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </div>
                </li>

                                <li class="flex items-center gap-3">
                    <div class="w-15 h-15 rounded-full bg-gray-300"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold">테니스 커뮤니티</p>
                        
                        <div class="flex items-center gap-2 text-xs text-[#A9A9A9] mt-1">
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 25 22" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="w-3 h-full fill-current">
                                    <path
                                        d="M2.65527 2.65918C4.79228 0.515683 8.21567 0.4497 10.4248 2.45703L10.6348 2.6582L11.7832 3.81348C11.9708 4.00204 12.2262 4.10738 12.4922 4.10742C12.7582 4.10742 13.0135 4.00198 13.2012 3.81348L14.25 2.75781C14.2873 2.72961 14.3245 2.70014 14.3584 2.66602C16.5635 0.447801 20.1386 0.447796 22.3438 2.66602L22.3447 2.66699C24.5509 4.88006 24.5523 8.47524 22.3438 10.6973L21.1953 11.8516L12.5 20.582L3.80371 11.8516L2.65527 10.6973C0.448057 8.47606 0.448251 4.88051 2.65527 2.65918Z"
                                        stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                                </svg>
                                <span>000</span>
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 15 15" fill="currentColor" class="w-3 h-full fill-current"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.971 13.59A7.45 7.45 0 0 0 7.348 15a7.45 7.45 0 0 0 4.356-1.393A7.516 7.516 0 0 0-.034 8.895a7.5 7.5 0 0 0 3 4.696zM9.33 7.573a8.94 8.94 0 0 1 3.601 4.94 7.45 7.45 0 0 0 1.795-3.602 7.45 7.45 0 0 0-5.397-1.334zM8.28 5.235a3 3 0 0 0 2.065.816 3.023 3.023 0 0 0 3.021-3.025A3.023 3.023 0 0 0 10.345 0a3 3 0 0 0-2.081.833c.373.65.584 1.41.584 2.218s-.207 1.537-.567 2.184m-3.955.816a3.026 3.026 0 1 0 0-6.051 3.026 3.026 0 0 0 0 6.051" />
                                </svg>
                                <span>50명</span>
                            </span>
                            <span>·</span>
                            <span>1시간 전 활동</span>
                        </div>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-full">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </div>
                </li>

                                <li class="flex items-center gap-3">
                    <div class="w-15 h-15 rounded-full bg-gray-300"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold">테니스 커뮤니티</p>
                        
                        <div class="flex items-center gap-2 text-xs text-[#A9A9A9] mt-1">
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 25 22" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="w-3 h-full fill-current">
                                    <path
                                        d="M2.65527 2.65918C4.79228 0.515683 8.21567 0.4497 10.4248 2.45703L10.6348 2.6582L11.7832 3.81348C11.9708 4.00204 12.2262 4.10738 12.4922 4.10742C12.7582 4.10742 13.0135 4.00198 13.2012 3.81348L14.25 2.75781C14.2873 2.72961 14.3245 2.70014 14.3584 2.66602C16.5635 0.447801 20.1386 0.447796 22.3438 2.66602L22.3447 2.66699C24.5509 4.88006 24.5523 8.47524 22.3438 10.6973L21.1953 11.8516L12.5 20.582L3.80371 11.8516L2.65527 10.6973C0.448057 8.47606 0.448251 4.88051 2.65527 2.65918Z"
                                        stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                                </svg>
                                <span>000</span>
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 15 15" fill="currentColor" class="w-3 h-full fill-current"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.971 13.59A7.45 7.45 0 0 0 7.348 15a7.45 7.45 0 0 0 4.356-1.393A7.516 7.516 0 0 0-.034 8.895a7.5 7.5 0 0 0 3 4.696zM9.33 7.573a8.94 8.94 0 0 1 3.601 4.94 7.45 7.45 0 0 0 1.795-3.602 7.45 7.45 0 0 0-5.397-1.334zM8.28 5.235a3 3 0 0 0 2.065.816 3.023 3.023 0 0 0 3.021-3.025A3.023 3.023 0 0 0 10.345 0a3 3 0 0 0-2.081.833c.373.65.584 1.41.584 2.218s-.207 1.537-.567 2.184m-3.955.816a3.026 3.026 0 1 0 0-6.051 3.026 3.026 0 0 0 0 6.051" />
                                </svg>
                                <span>50명</span>
                            </span>
                            <span>·</span>
                            <span>1시간 전 활동</span>
                        </div>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-full">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </div>
                </li>

                                <li class="flex items-center gap-3">
                    <div class="w-15 h-15 rounded-full bg-gray-300"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold">테니스 커뮤니티</p>
                        
                        <div class="flex items-center gap-2 text-xs text-[#A9A9A9] mt-1">
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 25 22" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="w-3 h-full fill-current">
                                    <path
                                        d="M2.65527 2.65918C4.79228 0.515683 8.21567 0.4497 10.4248 2.45703L10.6348 2.6582L11.7832 3.81348C11.9708 4.00204 12.2262 4.10738 12.4922 4.10742C12.7582 4.10742 13.0135 4.00198 13.2012 3.81348L14.25 2.75781C14.2873 2.72961 14.3245 2.70014 14.3584 2.66602C16.5635 0.447801 20.1386 0.447796 22.3438 2.66602L22.3447 2.66699C24.5509 4.88006 24.5523 8.47524 22.3438 10.6973L21.1953 11.8516L12.5 20.582L3.80371 11.8516L2.65527 10.6973C0.448057 8.47606 0.448251 4.88051 2.65527 2.65918Z"
                                        stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                                </svg>
                                <span>000</span>
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 15 15" fill="currentColor" class="w-3 h-full fill-current"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.971 13.59A7.45 7.45 0 0 0 7.348 15a7.45 7.45 0 0 0 4.356-1.393A7.516 7.516 0 0 0-.034 8.895a7.5 7.5 0 0 0 3 4.696zM9.33 7.573a8.94 8.94 0 0 1 3.601 4.94 7.45 7.45 0 0 0 1.795-3.602 7.45 7.45 0 0 0-5.397-1.334zM8.28 5.235a3 3 0 0 0 2.065.816 3.023 3.023 0 0 0 3.021-3.025A3.023 3.023 0 0 0 10.345 0a3 3 0 0 0-2.081.833c.373.65.584 1.41.584 2.218s-.207 1.537-.567 2.184m-3.955.816a3.026 3.026 0 1 0 0-6.051 3.026 3.026 0 0 0 0 6.051" />
                                </svg>
                                <span>50명</span>
                            </span>
                            <span>·</span>
                            <span>1시간 전 활동</span>
                        </div>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-full">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </div>
                </li>

                                <li class="flex items-center gap-3">
                    <div class="w-15 h-15 rounded-full bg-gray-300"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold">테니스 커뮤니티</p>
                        
                        <div class="flex items-center gap-2 text-xs text-[#A9A9A9] mt-1">
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 25 22" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="w-3 h-full fill-current">
                                    <path
                                        d="M2.65527 2.65918C4.79228 0.515683 8.21567 0.4497 10.4248 2.45703L10.6348 2.6582L11.7832 3.81348C11.9708 4.00204 12.2262 4.10738 12.4922 4.10742C12.7582 4.10742 13.0135 4.00198 13.2012 3.81348L14.25 2.75781C14.2873 2.72961 14.3245 2.70014 14.3584 2.66602C16.5635 0.447801 20.1386 0.447796 22.3438 2.66602L22.3447 2.66699C24.5509 4.88006 24.5523 8.47524 22.3438 10.6973L21.1953 11.8516L12.5 20.582L3.80371 11.8516L2.65527 10.6973C0.448057 8.47606 0.448251 4.88051 2.65527 2.65918Z"
                                        stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                                </svg>
                                <span>000</span>
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 15 15" fill="currentColor" class="w-3 h-full fill-current"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.971 13.59A7.45 7.45 0 0 0 7.348 15a7.45 7.45 0 0 0 4.356-1.393A7.516 7.516 0 0 0-.034 8.895a7.5 7.5 0 0 0 3 4.696zM9.33 7.573a8.94 8.94 0 0 1 3.601 4.94 7.45 7.45 0 0 0 1.795-3.602 7.45 7.45 0 0 0-5.397-1.334zM8.28 5.235a3 3 0 0 0 2.065.816 3.023 3.023 0 0 0 3.021-3.025A3.023 3.023 0 0 0 10.345 0a3 3 0 0 0-2.081.833c.373.65.584 1.41.584 2.218s-.207 1.537-.567 2.184m-3.955.816a3.026 3.026 0 1 0 0-6.051 3.026 3.026 0 0 0 0 6.051" />
                                </svg>
                                <span>50명</span>
                            </span>
                            <span>·</span>
                            <span>1시간 전 활동</span>
                        </div>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-full">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </div>
                </li>

                                <li class="flex items-center gap-3">
                    <div class="w-15 h-15 rounded-full bg-gray-300"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold">테니스 커뮤니티</p>
                        
                        <div class="flex items-center gap-2 text-xs text-[#A9A9A9] mt-1">
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 25 22" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="w-3 h-full fill-current">
                                    <path
                                        d="M2.65527 2.65918C4.79228 0.515683 8.21567 0.4497 10.4248 2.45703L10.6348 2.6582L11.7832 3.81348C11.9708 4.00204 12.2262 4.10738 12.4922 4.10742C12.7582 4.10742 13.0135 4.00198 13.2012 3.81348L14.25 2.75781C14.2873 2.72961 14.3245 2.70014 14.3584 2.66602C16.5635 0.447801 20.1386 0.447796 22.3438 2.66602L22.3447 2.66699C24.5509 4.88006 24.5523 8.47524 22.3438 10.6973L21.1953 11.8516L12.5 20.582L3.80371 11.8516L2.65527 10.6973C0.448057 8.47606 0.448251 4.88051 2.65527 2.65918Z"
                                        stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                                </svg>
                                <span>000</span>
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 15 15" fill="currentColor" class="w-3 h-full fill-current"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.971 13.59A7.45 7.45 0 0 0 7.348 15a7.45 7.45 0 0 0 4.356-1.393A7.516 7.516 0 0 0-.034 8.895a7.5 7.5 0 0 0 3 4.696zM9.33 7.573a8.94 8.94 0 0 1 3.601 4.94 7.45 7.45 0 0 0 1.795-3.602 7.45 7.45 0 0 0-5.397-1.334zM8.28 5.235a3 3 0 0 0 2.065.816 3.023 3.023 0 0 0 3.021-3.025A3.023 3.023 0 0 0 10.345 0a3 3 0 0 0-2.081.833c.373.65.584 1.41.584 2.218s-.207 1.537-.567 2.184m-3.955.816a3.026 3.026 0 1 0 0-6.051 3.026 3.026 0 0 0 0 6.051" />
                                </svg>
                                <span>50명</span>
                            </span>
                            <span>·</span>
                            <span>1시간 전 활동</span>
                        </div>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-full">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </div>
                </li>

                                <li class="flex items-center gap-3">
                    <div class="w-15 h-15 rounded-full bg-gray-300"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold">테니스 커뮤니티</p>
                        
                        <div class="flex items-center gap-2 text-xs text-[#A9A9A9] mt-1">
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 25 22" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="w-3 h-full fill-current">
                                    <path
                                        d="M2.65527 2.65918C4.79228 0.515683 8.21567 0.4497 10.4248 2.45703L10.6348 2.6582L11.7832 3.81348C11.9708 4.00204 12.2262 4.10738 12.4922 4.10742C12.7582 4.10742 13.0135 4.00198 13.2012 3.81348L14.25 2.75781C14.2873 2.72961 14.3245 2.70014 14.3584 2.66602C16.5635 0.447801 20.1386 0.447796 22.3438 2.66602L22.3447 2.66699C24.5509 4.88006 24.5523 8.47524 22.3438 10.6973L21.1953 11.8516L12.5 20.582L3.80371 11.8516L2.65527 10.6973C0.448057 8.47606 0.448251 4.88051 2.65527 2.65918Z"
                                        stroke="currentColor" stroke-width="2" stroke-linejoin="round" />
                                </svg>
                                <span>000</span>
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <svg viewBox="0 0 15 15" fill="currentColor" class="w-3 h-full fill-current"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.971 13.59A7.45 7.45 0 0 0 7.348 15a7.45 7.45 0 0 0 4.356-1.393A7.516 7.516 0 0 0-.034 8.895a7.5 7.5 0 0 0 3 4.696zM9.33 7.573a8.94 8.94 0 0 1 3.601 4.94 7.45 7.45 0 0 0 1.795-3.602 7.45 7.45 0 0 0-5.397-1.334zM8.28 5.235a3 3 0 0 0 2.065.816 3.023 3.023 0 0 0 3.021-3.025A3.023 3.023 0 0 0 10.345 0a3 3 0 0 0-2.081.833c.373.65.584 1.41.584 2.218s-.207 1.537-.567 2.184m-3.955.816a3.026 3.026 0 1 0 0-6.051 3.026 3.026 0 0 0 0 6.051" />
                                </svg>
                                <span>50명</span>
                            </span>
                            <span>·</span>
                            <span>1시간 전 활동</span>
                        </div>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-full">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </div>
                </li>
            </ul>
        </div>
    </section>
</main>

<?php
include_once(G5_THEME_PATH . '/tail.php');
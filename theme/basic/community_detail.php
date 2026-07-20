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

        <div class="flex items-center justify-between text-xs text-black font-bold bg-white px-3 py-2.5 rounded-full mt-4">
            <p>[공지] 오프라인 모임 신청 폼</p>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-chevron-right-icon lucide-chevron-right w-4 h-4">
                <path d="m9 18 6-6-6-6" />
            </svg>
        </div>
    </section>
</main>

<?php
include_once(G5_THEME_PATH . '/tail.php');
?>
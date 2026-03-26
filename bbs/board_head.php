<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 게시판 관리의 상단 내용
if (G5_IS_MOBILE) {
    // 모바일의 경우 설정을 따르지 않는다.
    include_once(G5_BBS_PATH . '/_head.php');
    echo run_replace('board_mobile_content_head', html_purifier(stripslashes($board['bo_mobile_content_head'])), $board);
} else {
    // 상단 파일 경로를 입력하지 않았다면 기본 상단 파일도 include 하지 않음
    if (trim($board['bo_include_head'])) {
        if (is_include_path_check($board['bo_include_head'])) {  //파일경로 체크
            @include($board['bo_include_head']);
        } else {    //파일경로가 올바르지 않으면 기본파일을 가져옴
            include_once(G5_BBS_PATH . '/_head.php');
        }
    }

    $head_html = run_replace('board_content_head', html_purifier(stripslashes($board['bo_content_head'])), $board);

    // 기존 게시판 상단 출력
    // echo $head_html;

    $board_tab = isset($_GET['board_tab']) ? preg_replace('/[^a-z_]/', '', $_GET['board_tab']) : 'community';
    if (!$board_tab) {
        $board_tab = 'community';
    }

    // bo_content_head의 첫 이미지 src를 카드 커버로 재사용 (임시)(공부 필요)
    $cover_src = '';
    if (preg_match('/<img[^>]*src=["\']([^"\']+)["\']/i', $board['bo_content_head'], $m)) {
        $cover_src = $m[1];
        // $cover_src = str_replace(
        //     'http://172.30.1.93',
        //     'https://' . $_SERVER['HTTP_HOST'],
        //     $cover_src
        // );
    }
?>

    <section class="mx-auto w-full max-w-full">
        <div class="overflow-hidden rounded-b-xl border border-gray-200 bg-white shadow-md">
            <?php if ($cover_src) { ?>
                <div class="w-full bg-gray-100">
                    <img src="<?php echo htmlspecialchars($cover_src, ENT_QUOTES); ?>" alt="" class="h-auto w-full object-cover">
                </div>
            <?php } else { ?>
                <div class="aspect-[4/3] w-full bg-gray-100"></div>
            <?php } ?>

            <div class="flex flex-col p-6 items-center text-center space-y-4">
                <!-- <div class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-crown-icon lucide-crown">
                        <path d="M11.562 3.266a.5.5 0 0 1 .876 0L15.39 8.87a1 1 0 0 0 1.516.294L21.183 5.5a.5.5 0 0 1 .798.519l-2.834 10.246a1 1 0 0 1-.956.734H5.81a1 1 0 0 1-.957-.734L2.02 6.02a.5.5 0 0 1 .798-.519l4.276 3.664a1 1 0 0 0 1.516-.294z" />
                        <path d="M5 21h14" />
                    </svg>
                </div> -->

                <h2 class="text-2xl font-extrabold text-gray-900">
                    <?php echo get_text($board['bo_subject']); ?>
                </h2>

                <div class="flex items-center justify-center gap-4 text-base font-bold text-gray-900">
                    <span class="inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <path d="M16 3.128a4 4 0 0 1 0 7.744" />
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                            <circle cx="9" cy="7" r="4" />
                        </svg>
                        바이머 11.3k
                    </span>

                    <span class="text-gray-300">|</span>

                    <button type="button" class="inline-flex items-center gap-2 text-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-arrow-out-up-right-icon lucide-square-arrow-out-up-right">
                            <path d="M21 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6" />
                            <path d="m21 3-9 9" />
                            <path d="M15 3h6v6" />
                        </svg>
                        공유
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto my-4 w-full px-4">
        <nav class="flex gap-2 items-center justify-around">
            <a href="<?php echo get_pretty_url($bo_table, '', 'board_tab=product'); ?>" class="py-1 text-base font-semibold text-center min-w-16 <?php echo $board_tab === 'product' ? 'border-t-2 border-gray-900 text-gray-900' : 'text-gray-500'; ?>">
                추천 상품
            </a>
            <a href="<?php echo get_pretty_url($bo_table, '', 'board_tab=info'); ?>" class="py-1 text-base font-semibold text-center min-w-16 <?php echo $board_tab === 'info' ? 'border-t-2 border-gray-900 text-gray-900' : 'text-gray-500'; ?>">
                정보
            </a>
            <a href="<?php echo get_pretty_url($bo_table, '', 'board_tab=community'); ?>" class="py-1 text-base font-semibold text-center min-w-16 <?php echo $board_tab === 'community' ? 'border-t-2 border-gray-900 text-gray-900' : 'text-gray-500'; ?>">
                커뮤니티
            </a>
        </nav>
    </section>
<?php
}

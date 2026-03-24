<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . $latest_skin_url . '/style.css">', 0);
$list_count = (is_array($list) && $list) ? count($list) : 0;
?>

<div class="lat_custom">
    <div class="flex flex-col items-start gap-4">
        <?php
        global $g5;

        // 카드 대표 이미지: 게시판 설정의 bo_content_head(상단내용)
        $sql = " select bo_content_head from {$g5['board_table']} where bo_table = '$bo_table' ";
        $board = sql_fetch($sql);
        $head_html = run_replace('board_content_head', html_purifier(stripslashes($board['bo_content_head'])), $board);
        $cover_src = '';

        // bo_content_head 안의 첫 번째 <img ... src="..."> 주소를 추출
        if (preg_match('/<img[^>]*src=["\']([^"\']+)["\']/i', $head_html, $m)) {
            $cover_src = $m[1];
            // 개발/내부 IP로 저장된 이미지 경로를 현재 도메인으로 치환한다.
            $cover_src = str_replace(
                'http://172.30.1.93',
                'https://' . $_SERVER['HTTP_HOST'],
                $cover_src
            );
        }
        ?>

        <a href="<?php echo get_pretty_url($bo_table); ?>" class="block w-full">
            <div class="w-full aspect-[2/1] overflow-hidden rounded-lg bg-gray-100">
                <?php if ($cover_src) { ?>
                    <img class="h-full w-full object-cover" src="<?= $cover_src ?>" alt="">
                <?php } else { ?>
                    <div class="flex h-full w-full items-center justify-center">
                        <p class="text-xs font-medium text-gray-500">사진이 없습니다</p>
                    </div>
                <?php } ?>
            </div>
        </a>

        <div class="space-y-1">
            <h2 class="lat_title_custom text-sm font-bold"><a href="<?php echo get_pretty_url($bo_table); ?>"><?php echo $bo_subject ?></a></h2>

            <p class="text-sm text-zinc-500 font-medium">여기에 게시판 설명이 들어갑니다.</p>

            <div class="flex items-center gap-1 text-xs text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart">
                    <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                </svg>
                <span>11.3k</span>
            </div>
        </div>
    </div>
    <ul class="hidden">
        <?php for ($i = 0; $i < $list_count; $i++) {  ?>
            <li class="basic_li">
                <?php
                if ($list[$i]['icon_secret']) echo "<i class=\"fa fa-lock\" aria-hidden=\"true\"></i><span class=\"sound_only\">비밀글</span> ";

                echo "<a href=\"" . get_pretty_url($bo_table, $list[$i]['wr_id']) . "\"> ";
                if ($list[$i]['is_notice'])
                    echo "<strong>" . $list[$i]['subject'] . "</strong>";
                else
                    echo $list[$i]['subject'];

                echo "</a>";

                if ($list[$i]['icon_hot']) echo "<span class=\"hot_icon\"><i class=\"fa fa-heart\" aria-hidden=\"true\"></i><span class=\"sound_only\">인기글</span></span>";
                if ($list[$i]['icon_new']) echo "<span class=\"new_icon\">N<span class=\"sound_only\">새글</span></span>";
                // if ($list[$i]['link']['count']) { echo "[{$list[$i]['link']['count']}]"; }
                // if ($list[$i]['file']['count']) { echo "<{$list[$i]['file']['count']}>"; }

                echo $list[$i]['icon_reply'] . " ";
                if (isset($list[$i]['icon_file']) && $list[$i]['icon_file']) echo " <i class=\"fa fa-download\" aria-hidden=\"true\"></i>";
                if ($list[$i]['icon_link']) echo " <i class=\"fa fa-link\" aria-hidden=\"true\"></i>";

                if ($list[$i]['comment_cnt'])  echo "
            <span class=\"lt_cmt\"><span class=\"sound_only\">댓글</span>" . $list[$i]['comment_cnt'] . "</span>";

                ?>
                <div class="lt_info">
                    <span class="lt_nick"><?php echo $list[$i]['name'] ?></span>
                    <span class="lt_date"><?php echo $list[$i]['datetime2'] ?></span>
                </div>
            </li>
        <?php }  ?>
        <?php if ($list_count == 0) { //게시물이 없을 때  
        ?>
            <li class="empty_li">게시물이 없습니다.</li>
        <?php }  ?>
    </ul>

</div>

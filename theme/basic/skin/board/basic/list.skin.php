<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 1;

if ($is_checkbox) $colspan++;
// if ($is_good) $colspan++;
// if ($is_nogood) $colspan++;

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . $board_skin_url . '/style.css">', 0);

$board_tab = isset($_GET['board_tab']) ? preg_replace('/[^a-z_]/', '', $_GET['board_tab']) : 'community';
if (!$board_tab) {
    $board_tab = 'community';
}

// 좋아요와 댓글 숫자에 따라 소수점 표시 및 숫자 뒤에 한글로 표시
if (!function_exists('format_k_count')) {
    function format_k_count($value)
    {
        $n = (int)$value;

        if ($n < 1000) {
            return number_format($n);
        }

        if ($n < 10000) {
            $v = floor(($n / 1000) * 10) / 10;
            $s = number_format($v, 1);
            $s = rtrim(rtrim($s, '0'), '.');
            return $s . '천';
        }

        $v = floor(($n / 10000) * 10) / 10;
        $s = number_format($v, 1);
        $s = rtrim(rtrim($s, '0'), '.');
        return $s . '만';
    }
}
?>
<style>
    #container_title {
        display: none;
    }

    /* 임시 */
    #container {
        margin: 0;
    }

    #hd_wrapper {
        display: none;
    }
</style>

<!-- 게시판 목록 시작 { -->
<div id="bo_list" style="width:<?php echo $width; ?>; margin:0">

    <!-- 게시판 카테고리 시작 { -->
    <?php if ($is_category) { ?>
        <nav id="bo_cate">
            <h2><?php echo $board['bo_subject'] ?> 카테고리</h2>
            <ul id="bo_cate_ul">
                <?php echo $category_option ?>
            </ul>
        </nav>
    <?php } ?>
    <!-- } 게시판 카테고리 끝 -->

    <!-- 커뮤니티 탭 분기 시작 -->
    <?php if ($board_tab === 'community') { ?>
        <form name="fboardlist" id="fboardlist" action="<?php echo G5_BBS_URL; ?>/board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">

            <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
            <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
            <input type="hidden" name="stx" value="<?php echo $stx ?>">
            <input type="hidden" name="spt" value="<?php echo $spt ?>">
            <input type="hidden" name="sca" value="<?php echo $sca ?>">
            <input type="hidden" name="sst" value="<?php echo $sst ?>">
            <input type="hidden" name="sod" value="<?php echo $sod ?>">
            <input type="hidden" name="page" value="<?php echo $page ?>">
            <input type="hidden" name="board_tab" value="<?php echo $board_tab ?>">
            <input type="hidden" name="sw" value="">

            <!-- 커뮤니티 카드 목록 -->
            <div class="mx-auto grid w-full max-w-full grid-cols-1 gap-2 p-4">
                <?php for ($i = 0; $i < count($list); $i++) { ?>
                    <?php
                    // 작성자명 정리: HTML 제거 후 빈 값이면 익명 사용
                    $author_name = trim(strip_tags($list[$i]['name']));
                    if ($author_name === '') {
                        $author_name = '익명';
                    }

                    // 회원 프로필 이미지 우선 사용 (mb_id가 있을 때만 조회)
                    $profile_html = '';
                    if (isset($list[$i]['mb_id'])) {
                        $profile_html = get_member_profile_img($list[$i]['mb_id'], 44, 44, $author_name);
                    }
                    // 반환된 <img>에 Tailwind 클래스 주입 (원형/크롭)
                    if ($profile_html) {
                        $profile_html = preg_replace('/<img /', '<img class="h-11 w-11 rounded-full object-cover" ', $profile_html, 1);
                    }

                    // 카드 본문 우선순위: wr_content -> content -> subject
                    $card_content_raw = '';
                    if (isset($list[$i]['wr_content']) && $list[$i]['wr_content']) {
                        $card_content_raw = $list[$i]['wr_content'];
                    } else if (isset($list[$i]['content']) && $list[$i]['content']) {
                        $card_content_raw = $list[$i]['content'];
                    } else {
                        $card_content_raw = $list[$i]['subject'];
                    }

                    // 본문 가공: HTML 태그 제거 전 줄바꿈 태그를 개행으로 치환하고 엔티티를 디코드
                    $card_content_text = str_ireplace(
                        array('<br>', '<br/>', '<br />', '</p>', '</div>', '</li>'),
                        "\n",
                        $card_content_raw
                    );
                    $card_content_text = strip_tags($card_content_text);
                    $card_content_text = html_entity_decode($card_content_text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $card_content_text = str_replace(array("\r\n", "\r"), "\n", $card_content_text);
                    $card_content_text = preg_replace('/[ \t]+/u', ' ', $card_content_text);
                    $card_content_text = preg_replace("/\n{3,}/u", "\n\n", $card_content_text);
                    $card_content_text = trim($card_content_text);
                    if ($card_content_text === '') {
                        $card_content_text = trim(html_entity_decode(strip_tags($list[$i]['subject']), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                    }

                    $detail_href = $list[$i]['href'];
                    if (strpos($detail_href, 'board_tab=') === false) {
                        $detail_href .= (strpos($detail_href, '?') !== false ? '&' : '?') . 'board_tab=' . urlencode($board_tab);
                    }

                    // 시간 표기 가공: 1~6일 전 / 1~4주일 전으로 변환
                    $card_datetime = $list[$i]['datetime2'];
                    if (!empty($list[$i]['wr_datetime'])) {
                        $post_time = strtotime($list[$i]['wr_datetime']);
                        if ($post_time) {
                            $today_start = strtotime(G5_TIME_YMD . ' 00:00:00');
                            $post_day_start = strtotime(date('Y-m-d 00:00:00', $post_time));

                            if ($today_start !== false && $post_day_start !== false && $today_start > $post_day_start) {
                                $past_days = (int)(($today_start - $post_day_start) / 86400);

                                if ($past_days >= 1 && $past_days <= 6) {
                                    $card_datetime = $past_days . '일전';
                                } else if ($past_days >= 7 && $past_days <= 28) {
                                    $past_weeks = (int)($past_days / 7);
                                    if ($past_weeks < 1) $past_weeks = 1;
                                    if ($past_weeks > 4) $past_weeks = 4;
                                    $card_datetime = $past_weeks . '주일 전';
                                }
                            }
                        }
                    }
                    ?>

                    <!-- 카드 시작 -->
                    <article class="rounded border border-gray-200 bg-white p-4 w-full">
                        <div class="grid gap-4 w-full">
                            <div class="grid grid-cols-1 gap-2">
                                <div class="flex items-end gap-2">
                                    <?php if ($profile_html) { ?>
                                        <?php echo $profile_html; ?>
                                    <?php } else { ?>
                                        <!-- 프로필 이미지가 없을 때 기본 아이콘 -->
                                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-icon lucide-user">
                                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                                                <circle cx="12" cy="7" r="4" />
                                            </svg>
                                        </span>
                                    <?php } ?>

                                    <p class="truncate w-fit text-xs font-medium px-1 text-white bg-gray-900 rounded"><?php echo $author_name; ?></p>

                                    <span class="text-xs text-gray-500"><?php echo $card_datetime; ?></span>
                                </div>
                                <p class="overflow-hidden whitespace-pre-line text-sm font-medium text-gray-900" style="display:-webkit-box;-webkit-line-clamp:4;-webkit-box-orient:vertical;"><?php echo get_text($card_content_text); ?></p>
                                <a href=" <?php echo $detail_href; ?>" class="inline-flex w-fit text-sm font-medium text-gray-600 hover:text-gray-900">자세히 보기</a>
                                <?php
                                preg_match_all('/<img[^>]+src="([^">]+)"/i', $list[$i]['wr_content'], $matches);

                                $images = $matches[1] ?? [];
                                ?>
                                <style>
                                    .post-carousel .owl-nav {
                                        position: absolute;
                                        top: 50%;
                                        left: 0;
                                        width: 100%;
                                        transform: translateY(-50%);
                                    }

                                    .post-carousel .owl-nav button {
                                        position: absolute;
                                        background: #fff !important;
                                        color: #fff;
                                        border-radius: 50%;
                                        width: 32px;
                                        height: 32px;
                                    }



                                    .post-carousel .owl-nav .owl-prev {
                                        left: 10px;
                                    }

                                    .post-carousel .owl-nav .owl-next {
                                        right: 10px;
                                    }
                                </style>
                                <div class="owl-carousel post-carousel">
                                    <?php foreach ($images as $img) { ?>
                                        <div class="item">
                                            <img src="<?= $img ?>" class="w-full rounded-2xl" loading="lazy">
                                        </div>
                                    <?php } ?>

                                </div>
                                <script>
                                    $(document).ready(function() {
                                        $('.post-carousel').owlCarousel({
                                            items: 1,
                                            loop: true,
                                            margin: 10,
                                            nav: true,
                                            navText: [
                                                '<span class="prev"><svg class="mx-auto" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg></span>',
                                                '<span class="next"><svg class="mx-auto" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right-icon lucide-chevron-right"><path d="m9 18 6-6-6-6"/></svg></span>'
                                            ]
                                            // dots: true
                                        });

                                    });
                                </script>

                                <?php
                                // 특정 게시글 전용 좋아요 요청 URL 만들기
                                // 로그인 사용자만 가능하고 게시판 설정에서 추천 사용이 켜진 경우만 가능
                                $list_good_href = '';
                                if ($is_member && $board['bo_use_good']) {
                                    $list_good_href = G5_BBS_URL . '/good.php?bo_table=' . $bo_table . '&amp;wr_id=' . $list[$i]['wr_id'] . '&amp;good=good';
                                }
                                ?>
                                <div class="flex items-center gap-8">
                                    <div class="flex items-center gap-2">
                                        <button type="button" data-href="<?php echo $list_good_href; ?>" class="js-list-good cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-thumbs-up-icon lucide-thumbs-up">
                                                <path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2a3.13 3.13 0 0 1 3 3.88Z" />
                                                <path d="M7 10v12" />
                                            </svg>
                                        </button>
                                        <span class="js-list-good-count text-sm"><?php echo format_k_count($list[$i]['wr_good']); ?></span>
                                    </div>

                                    <button type="button" class="cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-share-icon lucide-share">
                                            <path d="M12 2v13" />
                                            <path d="m16 6-4-4-4 4" />
                                            <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8" />
                                        </svg>
                                    </button>

                                    <a href="<?php echo $detail_href; ?>#bo_v" class="flex items-center gap-2 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square-text-icon lucide-message-square-text">
                                            <path d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z" />
                                            <path d="M7 11h10" />
                                            <path d="M7 15h6" />
                                            <path d="M7 7h8" />
                                        </svg>
                                        <span class="text-sm"><?php echo format_k_count($list[$i]['wr_comment']); ?></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php } ?>
                <?php if (count($list) == 0) { ?>
                    <!-- 목록 비어있을 때 안내 -->
                    <div class="h-60 flex items-center justify-center bg-white p-4 text-sm text-gray-500">게시물이 없습니다.</div>
                <?php } ?>
            </div>

            <!-- 게시판 페이지 정보 및 버튼 시작 (기존 코드) { -->
            <?php
            if ($board['bo_admin'] === $member['mb_id'] || $member['mb_id'] == 'admin') {
            ?>
                <div class="m-4 p-4 border rounded border-gray-200 ">

                    <div id="bo_btn_top" class="">
                        <div id="bo_list_total">
                            <div class="text-lg font-medium text-black">관리자 패널</div>
                        </div>

                        <ul class="btn_bo_user">
                            <?php if ($admin_href) { ?><li><a href="<?php echo $admin_href ?>" class="btn_admin btn" title="관리자"><i class="fa fa-cog fa-spin fa-fw"></i><span class="sound_only">관리자</span></a></li><?php } ?>
                            <?php if ($rss_href) { ?><li><a href="<?php echo $rss_href ?>" class="btn_b01 btn" title="RSS"><i class="fa fa-rss" aria-hidden="true"></i><span class="sound_only">RSS</span></a></li><?php } ?>
                            <li>
                                <button type="button" class="btn_bo_sch btn_b01 btn" title="게시판 검색">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search">
                                        <path d="m21 21-4.34-4.34" />
                                        <circle cx="11" cy="11" r="8" />
                                    </svg><span class="sound_only">게시판 검색</span></button>
                            </li>
                            <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b01 btn flex justify-center items-center" title="글쓰기">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pen-icon lucide-pen" style="line-height: 35px;height: 35px;">
                                            <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                                        </svg><span class="sound_only">글쓰기</span>
                                    </a></li><?php } ?>
                            <?php if ($is_admin == 'super' || $is_auth) {  ?>
                                <li>
                                    <button type="button" class="btn_more_opt is_list_btn btn_b01 btn" title="게시판 리스트 옵션"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ellipsis-vertical-icon lucide-ellipsis-vertical">
                                            <circle cx="12" cy="12" r="1" />
                                            <circle cx="12" cy="5" r="1" />
                                            <circle cx="12" cy="19" r="1" />
                                        </svg><span class="sound_only">게시판 리스트 옵션</span></button>
                                    <?php if ($is_checkbox) { ?>
                                        <ul class="more_opt is_list_btn">
                                            <li><button type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value"><i class="fa fa-trash-o" aria-hidden="true"></i> 선택삭제</button></li>
                                            <li><button type="submit" name="btn_submit" value="선택복사" onclick="document.pressed=this.value"><i class="fa fa-files-o" aria-hidden="true"></i> 선택복사</button></li>
                                            <li><button type="submit" name="btn_submit" value="선택이동" onclick="document.pressed=this.value"><i class="fa fa-arrows" aria-hidden="true"></i> 선택이동</button></li>
                                        </ul>
                                    <?php } ?>
                                </li>
                            <?php }  ?>
                        </ul>


                    </div>
                    <!-- } 게시판 페이지 정보 및 버튼 끝 -->

                    <div class="tbl_head01 tbl_wrap">
                        <table>
                            <caption><?php echo $board['bo_subject'] ?> 목록</caption>
                            <thead>
                                <tr>
                                    <?php if ($is_checkbox) { ?>
                                        <th scope="col" class="all_chk chk_box">
                                            <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);" class="selec_chk">
                                            <label for="chkall">
                                                <span></span>
                                                <b class="sound_only">현재 페이지 게시물 전체선택</b>
                                            </label>
                                        </th>
                                    <?php } ?>
                                    <th scope="col" class="!text-left">제목</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // 짝수 행마다 even 클래스 붙여서 배경색 처리 (현재는 !bg-white로 고정 중)
                                for ($i = 0; $i < count($list); $i++) {
                                    if ($i % 2 == 0) $lt_class = "even";
                                    else $lt_class = "";
                                    $detail_href = $list[$i]['href'];
                                    if (strpos($detail_href, 'board_tab=') === false) {
                                        $detail_href .= (strpos($detail_href, '?') !== false ? '&' : '?') . 'board_tab=' . urlencode($board_tab);
                                    }
                                ?>
                                    <tr class="<?php if ($list[$i]['is_notice']) echo "bo_notice"; ?> <?php echo $lt_class ?>">
                                        <?php if ($is_checkbox) { ?>
                                            <td class="td_chk chk_box">
                                                <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>" class="selec_chk">
                                                <label for="chk_wr_id_<?php echo $i ?>">
                                                    <span></span>
                                                    <b class="sound_only"><?php echo $list[$i]['subject'] ?></b>
                                                </label>
                                            </td>
                                        <?php } ?>
                                        <td class="td_num2 !hidden">
                                            <?php
                                            if ($list[$i]['is_notice']) // 공지사항
                                                echo '<strong class="notice_icon">공지</strong>';
                                            else if ($wr_id == $list[$i]['wr_id'])
                                                echo "<span class=\"bo_current\">열람중</span>";
                                            else
                                                echo $list[$i]['num'];
                                            ?>
                                        </td>

                                        <td class="td_subject" style="padding-left:<?php echo $list[$i]['reply'] ? (strlen($list[$i]['wr_reply']) * 10) : '0'; ?>px">
                                            <?php
                                            if ($is_category && $list[$i]['ca_name']) {
                                            ?>
                                                <a href="<?php echo $list[$i]['ca_name_href'] ?>" class="bo_cate_link"><?php echo $list[$i]['ca_name'] ?></a>
                                            <?php } ?>
                                            <div class="bo_tit !font-medium">
                                                <a href="<?php echo $detail_href; ?>">
                                                    <?php echo $list[$i]['icon_reply'] ?>
                                                    <?php
                                                    if (isset($list[$i]['icon_secret'])) echo rtrim($list[$i]['icon_secret']);
                                                    ?>
                                                    <?php echo $list[$i]['subject'] ?>
                                                </a>
                                                <?php
                                                // if ($list[$i]['icon_new']) echo "<span class=\"new_icon\">N<span class=\"sound_only\">새글</span></span>";
                                                // // if ($list[$i]['file']['count']) { echo '<'.$list[$i]['file']['count'].'>'; }
                                                // if (isset($list[$i]['icon_hot'])) echo rtrim($list[$i]['icon_hot']);
                                                // if (isset($list[$i]['icon_file'])) echo rtrim($list[$i]['icon_file']);
                                                // if (isset($list[$i]['icon_link'])) echo rtrim($list[$i]['icon_link']);
                                                ?>

                                                <?php
                                                // 댓글이 존재하면 카운트 표시 (현재는 강제 false)
                                                if ($list[$i]['comment_cnt'] = false) { ?><span class="sound_only">댓글</span><span class="cnt_cmt"><?php echo $list[$i]['wr_comment']; ?></span><span class="sound_only">개</span><?php } ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if (count($list) == 0) {
                                    echo '<tr><td colspan="' . $colspan . '" class="empty_table">게시물이 없습니다.</td></tr>';
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php } ?>
            <!-- 페이지 -->
            <?php echo $write_pages; ?>
            <!-- 페이지 -->

            <?php if ($list_href || $is_checkbox || $write_href) { ?>
                <div class="bo_fx !hidden">
                    <?php if ($list_href || $write_href) { ?>
                        <ul class="btn_bo_user">
                            <?php if ($admin_href) { ?><li><a href="<?php echo $admin_href ?>" class="btn_admin btn" title="관리자"><i class="fa fa-cog fa-spin fa-fw"></i><span class="sound_only">관리자</span></a></li><?php } ?>
                            <?php if ($rss_href) { ?><li><a href="<?php echo $rss_href ?>" class="btn_b01 btn" title="RSS"><i class="fa fa-rss" aria-hidden="true"></i><span class="sound_only">RSS</span></a></li><?php } ?>
                            <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b01 btn" title="글쓰기"><i class="fa fa-pencil" aria-hidden="true"></i><span class="sound_only">글쓰기</span></a></li><?php } ?>
                        </ul>
                    <?php } ?>
                </div>
            <?php } ?>
        </form>

        <!-- 게시판 검색 시작 { -->
        <div class="bo_sch_wrap">
            <fieldset class="bo_sch">
                <h3>검색</h3>
                <form name="fsearch" method="get">
                    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
                    <input type="hidden" name="sca" value="<?php echo $sca ?>">
                    <input type="hidden" name="board_tab" value="<?php echo $board_tab ?>">
                    <input type="hidden" name="sop" value="and">
                    <label for="sfl" class="sound_only">검색대상</label>
                    <select name="sfl" id="sfl">
                        <?php echo get_board_sfl_select_options($sfl); ?>
                    </select>
                    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
                    <div class="sch_bar">
                        <input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" required id="stx" class="sch_input" size="25" maxlength="20" placeholder=" 검색어를 입력해주세요">
                        <button type="submit" value="검색" class="sch_btn"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">검색</span></button>
                    </div>
                    <button type="button" class="bo_sch_cls" title="닫기"><i class="fa fa-times" aria-hidden="true"></i><span class="sound_only">닫기</span></button>
                </form>
            </fieldset>
            <div class="bo_sch_bg"></div>
        </div>
        <script>
            jQuery(function($) {
                // 게시판 검색
                $(".btn_bo_sch").on("click", function() {
                    $(".bo_sch_wrap").toggle();
                })
                $('.bo_sch_bg, .bo_sch_cls').click(function() {
                    $('.bo_sch_wrap').hide();
                });

                // 리스트 좋아요
                $(".js-list-good").on("click", function() {
                    var $btn = $(this);
                    var href = $btn.data("href");
                    var $count = $btn.closest(".flex.items-center.gap-2").find(".js-list-good-count");

                    if (!href) {
                        return false;
                    }

                    $.post(
                        href, {
                            js: "on"
                        },
                        function(data) {
                            if (data && data.error) {
                                alert(data.error);
                                return false;
                            }

                            if (data && typeof data.count !== "undefined") {
                                var nextCount = parseInt(data.count, 10);
                                if (!isNaN(nextCount)) {
                                    $count.text(formatCountForUi(nextCount));
                                }
                            }
                        },
                        "json"
                    );

                    return false;
                });
            });

            function formatCountForUi(value) {
                var n = parseInt(value, 10);

                if (isNaN(n) || n < 1000) {
                    return String(isNaN(n) ? 0 : n);
                }

                if (n < 10000) {
                    var v1 = Math.floor((n / 1000) * 10) / 10;
                    return String(v1).replace(/\.0$/, "") + "천";
                }

                var v2 = Math.floor((n / 10000) * 10) / 10;
                return String(v2).replace(/\.0$/, "") + "만";
            }
        </script>
        <!-- } 게시판 검색 끝 -->
    <?php } elseif ($board_tab === 'product') { ?>
        <section class="mx-auto w-full max-w-full p-4">
            <div class="grid grid-cols-1 gap-6">
                <!-- <div id="product_category_tabs" class="grid grid-cols-5 gap-1">
                    <button type="button" class="product-chip rounded-full bg-gray-900 p-2 text-sm font-medium text-white" data-active="1">BEST</button>
                    <button type="button" class="product-chip rounded-full bg-gray-100 p-2 text-sm font-medium text-gray-900">굿즈</button>
                    <button type="button" class="product-chip rounded-full bg-gray-100 p-2 text-sm font-medium text-gray-900">화장품</button>
                    <button type="button" class="product-chip rounded-full bg-gray-100 p-2 text-sm font-medium text-gray-900">패션</button>
                    <button type="button" class="product-chip rounded-full bg-gray-100 p-2 text-sm font-medium text-gray-900">IT/기기</button>
                </div> -->

                <?php
                $product_map = array(
                    'if_kim_woo_bin' => array(
                        array('name' => '칸타타 프리미엄 라떼 275ml 24개', 'origin_price' => '32,000', 'discount_rate' => '33%', 'sale_price' => '24,000', 'image' => '01.png'),
                        array('name' => '피그 앤 로터스 플라워 코롱', 'origin_price' => '120,000', 'discount_rate' => '20%', 'sale_price' => '96,000', 'image' => '02.png'),
                        array('name' => '유니 파워포켓 집업플리스', 'origin_price' => '300,000', 'discount_rate' => '60%', 'sale_price' => '120,000', 'image' => '03.png'),
                    ),
                    'if_han_ji_min' => array(
                        array('name' => '글로우 테라피 앰플 (30ml)', 'origin_price' => '52,000', 'discount_rate' => '21%', 'sale_price' => '40,000', 'image' => '01.png'),
                        array('name' => '원글루타넥스 글로우 부스터 크림 50ml, 1개', 'origin_price' => '55,000', 'discount_rate' => '20%', 'sale_price' => '44,000', 'image' => '02.png'),
                        array('name' => '에버콜라겐 스킨앤헤어 20ml x 28포 4개', 'origin_price' => '164,000', 'discount_rate' => '60%', 'sale_price' => '99,000', 'image' => '03.png'),
                    ),
                    'if_jo_in_sung' => array(
                        array('name' => '바바 라떼 350ml 20개입', 'origin_price' => '24,000', 'discount_rate' => '22%', 'sale_price' => '19,000', 'image' => '01.png'),
                        array('name' => '프로메가 알티지 오메가3 듀얼 6박스', 'origin_price' => '105,000', 'discount_rate' => '32%', 'sale_price' => '71,400', 'image' => '02.png'),
                        array('name' => '오뚜기 엑소만두 X.O. 교자 324g, 3개', 'origin_price' => '21,000', 'discount_rate' => '30%', 'sale_price' => '14,700', 'image' => '03.png'),
                    ),
                    'club_kukrule' => array(
                        array('name' => '빅토리랩 여름 하계용 테니스 장갑 스트레치 글러브', 'origin_price' => '21,000', 'discount_rate' => '38%', 'sale_price' => '12,900', 'image' => '01.png'),
                        array('name' => '요넥스 테니스라켓 팀 스매시(290g)', 'origin_price' => '140,000', 'discount_rate' => '15%', 'sale_price' => '119,000', 'image' => '02.png'),
                        array('name' => '윌슨 챔피언쉽 볼 테니스공 시합구 36개', 'origin_price' => '169,000', 'discount_rate' => '21%', 'sale_price' => '135,000', 'image' => '03.png'),
                    ),
                    'club_sumisa' => array(
                        array('name' => '미즈노 습식 스포츠타올 44x68cm N2JYB010', 'origin_price' => '41,600', 'discount_rate' => '25%', 'sale_price' => '21,200', 'image' => '01.png'),
                        array('name' => '스피도 하이드로 롤 탑 워터프루프 드라이백 레드', 'origin_price' => '34,000', 'discount_rate' => '40%', 'sale_price' => '20,400', 'image' => '02.png'),
                        array('name' => '아레나로고 숄더백 15L', 'origin_price' => '40,000', 'discount_rate' => '20%', 'sale_price' => '32,000', 'image' => '03.png'),
                    ),
                    'club_running_maniac' => array(
                        array('name' => '나이키 바람막이 우븐 재킷 러닝 드라이핏', 'origin_price' => '82,900', 'discount_rate' => '24%', 'sale_price' => '62,900', 'image' => '01.png'),
                        array('name' => '론빅 프리미엄 러닝 조끼', 'origin_price' => '48,000', 'discount_rate' => '20%', 'sale_price' => '38,000', 'image' => '02.png'),
                        array('name' => '액션캠 스트랩', 'origin_price' => '19,000', 'discount_rate' => '33%', 'sale_price' => '13,000', 'image' => '03.png'),
                    ),
                    'rg_hallelujah_church' => array(
                        array('name' => 'GOD LOVES U 스티커 (30매) - 할렐루야 교회 굿즈', 'origin_price' => '3,000', 'discount_rate' => '26%', 'sale_price' => '2,100', 'image' => '01.png'),
                        array('name' => '미니 기도손 십자가 - 할렐루야 교회 굿즈', 'origin_price' => '6,000', 'discount_rate' => '20%', 'sale_price' => '4,800', 'image' => '02.png'),
                        array('name' => '천연소가죽 포켓 성경책 - 할렐루야 교회 굿즈', 'origin_price' => '130,000', 'discount_rate' => '26%', 'sale_price' => '95,000', 'image' => '03.png'),
                    ),
                    'rg_hiswill_stone_ch' => array(
                        array('name' => '사도신경 키링 - 성암교회 굿즈', 'origin_price' => '7,000', 'discount_rate' => '28%', 'sale_price' => '5,000', 'image' => '01.png'),
                        array('name' => '성경읽기표 1세트 (4매) - 주의뜻 성암교회 굿즈', 'origin_price' => '3,000', 'discount_rate' => '20%', 'sale_price' => '2,400', 'image' => '02.png'),
                        array('name' => '초미니성경책 - 성암교회 굿즈', 'origin_price' => '7,400', 'discount_rate' => '18%', 'sale_price' => '6,000', 'image' => '03.png'),
                    ),
                    'rg_hyewon_shimindang' => array(
                        array('name' => '발우 공양 밥그릇', 'origin_price' => '100,000', 'discount_rate' => '50%', 'sale_price' => '50,000', 'image' => '01.png'),
                        array('name' => '불교개구리 3종+연잎접시세트', 'origin_price' => '28,000', 'discount_rate' => '11%', 'sale_price' => '25,000', 'image' => '02.png'),
                        array('name' => '108 번뇌 소멸 18염주 씨앗 손목 불교염주팔찌', 'origin_price' => '18,500', 'discount_rate' => '20%', 'sale_price' => '14,800', 'image' => '03.png'),
                    ),
                );
                $product_items = isset($product_map[$bo_table]) ? $product_map[$bo_table] : array();
                ?>
                <div class="grid grid-cols-1 gap-4">
                    <?php if (count($product_items)) { ?>
                        <?php for ($i = 0; $i < count($product_items); $i++) { ?>
                            <?php
                            $image_url = G5_DATA_URL . '/product/' . $bo_table . '/' . $product_items[$i]['image'];
                            ?>
                            <article class="grid grid-cols-[1fr_auto] items-start gap-4 rounded border border-gray-200 bg-white p-4">
                                <div class="grid grid-cols-1 gap-5">
                                    <p class="text-base font-semibold text-gray-900"><?php echo $product_items[$i]['name']; ?></p>
                                    <div>
                                        <p class="text-sm font-semibold text-zinc-500 line-through"><?php echo $product_items[$i]['origin_price']; ?></p>
                                        <div class="flex items-center gap-1">
                                            <p class="text-base font-semibold text-red-600"><?php echo $product_items[$i]['discount_rate']; ?></p>
                                            <p class="text-base font-semibold text-gray-900"><?php echo $product_items[$i]['sale_price']; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <img src="<?php echo $image_url; ?>" alt="상품 썸네일" class="w-[105px] h-[105px] border border-gray-200 object-cover">
                            </article>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="h-60 flex items-center justify-center rounded border border-gray-200 bg-white p-4 text-sm text-gray-500">등록된 상품이 없습니다.</div>
                    <?php } ?>
                </div>
            </div>
        </section>

        <script>
            jQuery(function($) {
                $("#product_category_tabs .product-chip").on("click", function() {
                    $("#product_category_tabs .product-chip")
                        .removeAttr("data-active")
                        .removeClass("bg-gray-900 text-white")
                        .addClass("bg-gray-100 text-gray-900");

                    $(this)
                        .attr("data-active", "1")
                        .removeClass("bg-gray-100 text-gray-900")
                        .addClass("bg-gray-900 text-white");
                });
            });
        </script>

    <?php } elseif ($board_tab === 'info') { ?>
        <section class="mx-auto w-full max-w-full p-4">
            <div class="mb-4 grid grid-cols-1 gap-4">
                <div class="grid min-h-44 place-items-center rounded border border-gray-200 bg-gray-50">
                    <div class="grid grid-cols-1 justify-items-center text-gray-700">

                        <?php
                        $tail_html = run_replace('board_content_tail', html_purifier(stripslashes($board['bo_content_tail'])), $board);
                        $cover_src = '';
                        if (preg_match('/<img[^>]*src=["\']([^"\']+)["\']/i', $board['bo_content_tail'], $m)) {
                            $cover_src = $m[1];
                            // $cover_src = str_replace(
                            //     'http://172.30.1.93',
                            //     'https://' . $_SERVER['HTTP_HOST'],
                            //     $cover_src
                            // );
                        }
                        ?>
                        <img src="<?= $cover_src; ?>" alt="">
                    </div>

                </div>

                <span class="inline-flex w-fit rounded-full bg-gray-100 px-4 py-2 text-sm font-medium text-gray-900">정보</span>

                <div class="grid grid-cols-1 gap-2  text-left text-gray-900">
                    <?php
                    echo $content_without_img = preg_replace('/<img[^>]*>/i', '', $tail_html);
                    ?>
                </div>
            </div>
        </section>
    <?php } ?>
</div>

<?php if ($is_checkbox) { ?>
    <noscript>
        <p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
    </noscript>
<?php } ?>

<?php if ($is_checkbox) { ?>
    <script>
        function all_checked(sw) {
            var f = document.fboardlist;

            for (var i = 0; i < f.length; i++) {
                if (f.elements[i].name == "chk_wr_id[]")
                    f.elements[i].checked = sw;
            }
        }

        function fboardlist_submit(f) {
            var chk_count = 0;

            for (var i = 0; i < f.length; i++) {
                if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
                    chk_count++;
            }

            if (!chk_count) {
                alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
                return false;
            }

            if (document.pressed == "선택복사") {
                select_copy("copy");
                return;
            }

            if (document.pressed == "선택이동") {
                select_copy("move");
                return;
            }

            if (document.pressed == "선택삭제") {
                if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
                    return false;

                f.removeAttribute("target");
                f.action = g5_bbs_url + "/board_list_update.php";
            }

            return true;
        }

        // 선택한 게시물 복사 및 이동
        function select_copy(sw) {
            var f = document.fboardlist;

            if (sw == "copy")
                str = "복사";
            else
                str = "이동";

            var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

            f.sw.value = sw;
            f.target = "move";
            f.action = g5_bbs_url + "/move.php";
            f.submit();
        }

        // 게시판 리스트 관리자 옵션
        jQuery(function($) {
            $(".btn_more_opt.is_list_btn").on("click", function(e) {
                e.stopPropagation();
                $(".more_opt.is_list_btn").toggle();
            });
            $(document).on("click", function(e) {
                if (!$(e.target).closest('.is_list_btn').length) {
                    $(".more_opt.is_list_btn").hide();
                }
            });
        });
    </script>
<?php } ?>

<script>
    // board_tab 전환 시 클릭 직전 스크롤 위치를 저장하고 다음 페이지 로드 후 복원
    document.addEventListener("DOMContentLoaded", function() {
        const STORAGE_FLAG_KEY = "board_tab_scroll_restore";
        const STORAGE_Y_KEY = "board_tab_scroll_y";

        // 현재 URL ?board_tab 값 읽기
        function getBoardTabFromUrl() {
            try {
                const params = new URLSearchParams(window.location.search || "");
                return params.get("board_tab") || "";
            } catch (e) {
                return "";
            }
        }

        function restoreScrollPositionIfNeeded() {
            const boardTab = getBoardTabFromUrl();
            if (!boardTab) {
                return;
            }

            const restoreFlag = sessionStorage.getItem(STORAGE_FLAG_KEY);
            const savedY = parseInt(sessionStorage.getItem(STORAGE_Y_KEY) || "", 10);

            if (restoreFlag !== "1" || isNaN(savedY)) {
                return;
            }

            sessionStorage.removeItem(STORAGE_FLAG_KEY);
            sessionStorage.removeItem(STORAGE_Y_KEY);

            // 뒤로가기/이동 시 기존 스크롤 복원과 충돌 방지
            window.history.scrollRestoration = "manual";

            requestAnimationFrame(function() {
                window.scrollTo(0, savedY);
                setTimeout(function() {
                    window.scrollTo(0, savedY);
                }, 120);
            });
        }

        document.addEventListener("click", function(e) {
            const link = e.target.closest("a[href*='board_tab=']");
            if (!link) {
                return;
            }

            // window.scrollY 현재 세로 스크롤
            // window.pageYOffset 구버전 호환 보조
            sessionStorage.setItem(STORAGE_FLAG_KEY, "1");
            sessionStorage.setItem(STORAGE_Y_KEY, String(window.scrollY || window.pageYOffset || 0));
        });

        restoreScrollPositionIfNeeded();
    });
</script>
<!-- } 게시판 목록 끝 -->

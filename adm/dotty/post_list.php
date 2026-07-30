<?php
$sub_menu = '710500';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "r");

$g5['title'] = '게시글관리';

$dotty_mb_id = $member['mb_id'];
$dotty_mb_id_sql = sql_real_escape_string($dotty_mb_id);

$search_type = isset($_GET['post_search_type']) ? trim($_GET['post_search_type']) : 'post_subject';
$search_text = isset($_GET['post_search']) ? trim($_GET['post_search']) : '';

$allowed_search_types = array('post_subject', 'post_content');
if (!in_array($search_type, $allowed_search_types, true)) {
    $search_type = 'post_subject';
}

$where = " WHERE dotty_mb_id = '{$dotty_mb_id_sql}' ";

if ($search_text !== '') {
    $search_text_sql = sql_real_escape_string($search_text);
    $where .= " AND {$search_type} LIKE '%{$search_text_sql}%' ";
}

$count_row = sql_fetch("
    SELECT COUNT(*) AS cnt
    FROM donuts_dotty_posts
    {$where}
");

$total_count = (int)$count_row['cnt'];

$rows = isset($config['cf_page_rows']) ? (int)$config['cf_page_rows'] : 20;
if ($rows < 1) $rows = 20;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$total_page = $total_count > 0 ? (int)ceil($total_count / $rows) : 1;
$from_record = ($page - 1) * $rows;

$sql = "
    SELECT
        post_id,
        post_subject,
        post_content,
        view_count,
        comment_count,
        use_yn,
        created_at,
        updated_at
    FROM donuts_dotty_posts
    {$where}
    ORDER BY post_id DESC
    LIMIT {$from_record}, {$rows}
";

$result = sql_query($sql);

$qstr = 'post_search_type=' . urlencode($search_type)
      . '&amp;post_search=' . urlencode($search_text);

// 관리자 토큰은 페이지당 한 번만 생성해서 모든 링크에서 동일하게 사용합니다.
// get_admin_token()을 링크마다 호출하면 마지막에 생성된 토큰만 세션에 남아
// 앞에서 생성된 숨김/삭제 링크가 "올바른 방법으로 이용해 주십시오." 오류가 날 수 있습니다.
$admin_token = get_admin_token();

require_once '../admin.head.php';
?>
<section class="relative">
    <h2>게시글관리</h2>

    <div class="text-lg">
        안녕하세요 <?php echo get_text($member['mb_name'] ? $member['mb_name'] : $member['mb_id']); ?> 도티님
    </div>

    <div class="mt-8 space-y-2">
        <div class="flex items-center justify-between">
            <p>총 게시글 수 <?php echo number_format($total_count); ?>개</p>
            <a href="./post_form.php" class="btn btn_01">게시글 등록</a>
        </div>

        <form method="get" action="./post_list.php" class="space-y-2">
            <div class="flex items-center gap-2">
                <label for="post_search_type" class="pr-12">타입</label>
                <select id="post_search_type" name="post_search_type" class="frm_input w-50">
                    <option value="post_subject" <?php echo get_selected($search_type, 'post_subject'); ?>>제목</option>
                    <option value="post_content" <?php echo get_selected($search_type, 'post_content'); ?>>내용</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <input type="text"
                       id="post_search_input"
                       name="post_search"
                       value="<?php echo get_text($search_text); ?>"
                       class="w-full border border-gray-300 rounded px-4 py-2"
                       placeholder="게시글 검색">
                <button type="submit" class="btn btn_04 shrink-0">검색</button>
            </div>
        </form>

        <div>
            <p>게시글 리스트</p>

            <div class="tbl_head01 tbl_wrap !mt-2">
                <table>
                    <thead>
                    <tr>
                        <th scope="col" class="th_left">제목</th>
                        <th scope="col">댓글 수</th>
                        <th scope="col">조회 수</th>
                        <th scope="col">노출</th>
                        <th scope="col">등록일</th>
                        <th scope="col" class="th_right">관리</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    $list_count = 0;

                    while ($row = sql_fetch_array($result)) {
                        $list_count++;
                    ?>
                        <tr>
                            <td class="td_left"><?php echo get_text($row['post_subject']); ?></td>
                            <td><?php echo number_format($row['comment_count']); ?></td>
                            <td><?php echo number_format($row['view_count']); ?></td>
                            <td><?php echo $row['use_yn'] === 'Y' ? '노출' : '숨김'; ?></td>
                            <td><?php echo substr($row['created_at'], 0, 10); ?></td>

                            <td class="td_right">
                                <div class="inline-flex items-center gap-1">
                                    <button type="button"
                                            class="post_modal_view btn_xs btn_04"
                                            data-post-id="<?php echo (int)$row['post_id']; ?>">
                                        댓글 보기
                                    </button>

                                    <a href="./post_view.php?post_id=<?php echo (int)$row['post_id']; ?>"
                                       class="post_view btn_xs btn_02">
                                        보기
                                    </a>

                                    <a href="./post_form.php?w=u&amp;post_id=<?php echo (int)$row['post_id']; ?>"
                                       class="post_edit btn_xs btn_03">
                                        수정
                                    </a>

                                    <a href="./post_toggle.php?post_id=<?php echo (int)$row['post_id']; ?>&amp;token=<?php echo urlencode($admin_token); ?>"
                                       class="btn_xs btn_03">
                                        <?php echo $row['use_yn'] === 'Y' ? '숨김' : '노출'; ?>
                                    </a>

                                    <a href="./post_delete.php?post_id=<?php echo (int)$row['post_id']; ?>&amp;token=<?php echo urlencode($admin_token); ?>"
                                       class="post_delete btn_xs btn_01"
                                       onclick="return confirm('해당 게시글을 삭제하시겠습니까?\n댓글까지 모두 삭제됩니다');">
                                        삭제
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php
                    }

                    if (!$list_count) {
                        echo '<tr><td colspan="6" class="empty_table">등록된 게시글이 없습니다.</td></tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
        if ($total_count > 0) {
            echo get_paging(
                G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'],
                $page,
                $total_page,
                $_SERVER['SCRIPT_NAME'] . '?' . $qstr . '&amp;page='
            );
        }
        ?>
    </div>

    <div id="post_info_modal_overlay" class="hidden absolute inset-0 z-40 flex items-center justify-center">
        <div id="post_info_modal"
             class="max-w-150 border w-full border-gray-400 bg-white rounded space-y-4 p-4"
             role="dialog">
            <div class="flex justify-between items-center">
                <h3 id="post_modal_subject" class="text-base"></h3>
                <button type="button" id="post_modal_close" class="btn_xs btn_01">닫기</button>
            </div>

            <div id="post_modal_content"></div>

            <div>
                <p>댓글 <span id="post_modal_comment_count">0</span>개</p>

                <div class="tbl_head01 tbl_wrap !mt-2">
                    <table>
                        <thead>
                        <tr>
                            <th scope="col" class="th_left">회원아이디</th>
                            <th scope="col">이름</th>
                            <th scope="col">닉네임</th>
                            <th scope="col">내용</th>
                            <th scope="col" class="th_right">관리</th>
                        </tr>
                        </thead>

                        <tbody id="post_modal_comment_body">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(function () {
    function closePostModal() {
        $("#post_info_modal_overlay").addClass("hidden");
    }

    $(".post_modal_view").on("click", function () {
        const postId = $(this).data("post-id");

        $.ajax({
            url: "./ajax.post_info.php",
            type: "GET",
            dataType: "json",
            data: { post_id: postId },
            success: function (res) {
                if (!res.success) {
                    alert(res.message || "게시글 정보를 가져오지 못했습니다.");
                    return;
                }

                $("#post_modal_subject").text(res.post.post_subject);
                $("#post_modal_content").html(res.post.post_content);
                $("#post_modal_comment_count").text(res.comments.length);

                const $body = $("#post_modal_comment_body");
                $body.empty();

                if (!res.comments.length) {
                    $body.append('<tr><td colspan="5" class="empty_table">댓글이 없습니다.</td></tr>');
                } else {
                    $.each(res.comments, function (_, comment) {
                        const tr = $("<tr>");

                        $("<td>", { class: "td_left" }).text(comment.mb_id || "-").appendTo(tr);
                        $("<td>").text(comment.comment_name || "-").appendTo(tr);
                        $("<td>").text(comment.comment_nick || "-").appendTo(tr);
                        $("<td>", { class: "td_left" }).text(comment.comment_content).appendTo(tr);

                        const manage = $("<td>", { class: "td_right" });
                        const wrap = $("<div>", { class: "inline-flex items-center gap-1" });

                        $("<a>", {
                            href: "./comment_delete.php?comment_id=" + comment.comment_id + "&token=" + encodeURIComponent(res.token),
                            class: "comment_delete btn_xs btn_01",
                            text: "삭제"
                        }).on("click", function () {
                            return confirm("해당 댓글을 삭제하시겠습니까?");
                        }).appendTo(wrap);

                        wrap.appendTo(manage);
                        manage.appendTo(tr);
                        tr.appendTo($body);
                    });
                }

                $("#post_info_modal_overlay").removeClass("hidden");
            },
            error: function () {
                alert("게시글 정보를 가져오는 중 오류가 발생했습니다.");
            }
        });
    });

    $("#post_modal_close").on("click", closePostModal);

    $("#post_info_modal_overlay").on("click", function (event) {
        if (event.target === event.currentTarget) {
            closePostModal();
        }
    });
});
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');

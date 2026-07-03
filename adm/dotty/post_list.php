<?php
$sub_menu = '710500';
include_once('./_common.php');

$g5['title'] = '게시글관리';
require_once '../admin.head.php';
?>
<section class="relative">
    <h2>게시글관리</h2>
    <div class="text-lg">안녕하세요 김우빈(임시) 도티님</div>

    <div class="mt-8 space-y-2">
        <p>총 게시글 수 15개(임시)</p>

        <div class="flex items-center gap-2">
            <label for="post_search_type" class="pr-12">타입(임시)</label>
            <select id="post_search_type" name="post_search_type" class="frm_input w-50">
                <option value="wr_subject">제목(기본값)</option>
                <option value="wr_content">내용</option>
            </select>
        </div>

        <div class="flex items-center gap-2">
            <input type="text" id="post_search_input" name="post_search"
                class="w-full border border-gray-300 rounded px-4 py-2" placeholder="게시글 검색(임시)">
            <button type="button" class="btn btn_04 shrink-0">검색</button>
        </div>

        <div>
            <p>게시글 리스트(임시)</p>
            <div class="tbl_head01 tbl_wrap !mt-2">
                <table>
                    <thead>
                        <tr>
                            <th scope="col" class="th_left">제목</th>
                            <th scope="col">댓글 수</th>
                            <th scope="col" class="th_right">관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="td_left">김우빈 모델 발탁 !</td>
                            <td>12</td>
                            <td class="td_right">
                                <div class="inline-flex items-center gap-1">
                                    <button type="button" class="post_modal_view btn_xs btn_04">댓글 보기</button>
                                    <button type="button" class="post_view btn_xs btn_02">보기</button>
                                    <button type="button" class="post_edit btn_xs btn_03">수정</button>
                                    <button type="button" class="post_delete btn_xs btn_01">삭제</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="post_info_modal_overlay" class="hidden absolute inset-0 z-40 flex items-center justify-center">
        <div id="post_info_modal" class="max-w-150 border w-full border-gray-400 bg-white rounded space-y-4 p-4"
            role="dialog">
            <h3 class="text-base">김우빈 모델 발탁 !</h3>
            <p>본문 내용</p>
            <div>
                <p>댓글 12개</p>
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
                        <tbody>
                            <tr>
                                <td class="td_left">dot_1</td>
                                <td>홍길동</td>
                                <td>도트_1</td>
                                <td>역시대박...</td>
                                <td class="td_right">
                                    <div class="inline-flex items-center gap-1">
                                        <button type="button" class="post_view btn_xs btn_02">보기</button>
                                        <button type="button" class="comment_delete btn_xs btn_01">삭제</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(function () {
        // 포스트 정보 모달 보기
        $(".post_modal_view").on("click", function () {
            const $postInfoModalOverlay = $("#post_info_modal_overlay");

            $postInfoModalOverlay.removeClass("hidden");
        });

        // 모달 외부 클릭 시 닫기
        $("#post_info_modal_overlay").on("click", function (event) {
            if (event.target !== event.currentTarget) {
                return;
            }

            $(this).addClass("hidden");
        });

        // 포스트 삭제 (임시)
        $(".post_delete").on("click", function () {
            const isConfirmed = window.confirm("해당 게시글을 삭제하시겠습니까?\n댓글까지 모두 삭제됩니다");

            if (!isConfirmed) {
                return;
            }
        });

        // 포스트 댓글 삭제 (임시)
        $(".comment_delete").on("click", function () {
            const isConfirmed = window.confirm("해당 댓글을 삭제하시겠습니까?");

            if (!isConfirmed) {
                return;
            }
        });
    });
</script>
<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
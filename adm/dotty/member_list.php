<?php
$sub_menu = '710400';
include_once('./_common.php');

$g5['title'] = '회원관리';
require_once '../admin.head.php';
?>
<section class="relative">
    <h2>회원관리</h2>
    <div class="text-lg">안녕하세요 김우빈(임시) 도티님</div>

    <div class="mt-8 space-y-2">
        <p>총 도트 수 15명(임시)</p>
        <div class="flex items-center gap-2">
            <label for="mb_search_type" class="pr-12">타입(임시)</label>
            <select id="mb_search_type" name="mb_search_type" class="frm_input w-50">
                <option value="mb_id">회원 아이디(기본값)</option>
                <option value="mb_nick">닉네임</option>
            </select>
        </div>

        <div class="flex items-center gap-2">
            <input type="text" id="mb_search_input" name="mb_search"
                class="w-full border border-gray-300 rounded px-4 py-2" placeholder="회원 검색(임시)">
            <button type="button" class="btn btn_04 shrink-0">검색</button>
        </div>

        <div>
            <p>도트 리스트(임시)</p>
            <div class="tbl_head01 tbl_wrap !mt-2">
                <table>
                    <thead>
                        <tr>
                            <th scope="col" class="th_left">회원아이디</th>
                            <th scope="col">이름</th>
                            <th scope="col">닉네임</th>
                            <th scope="col">기여금</th>
                            <th scope="col" class="th_right">관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="td_left">dot_1</td>
                            <td>홍길동</td>
                            <td>도트_1</td>
                            <td>250원</td>
                            <td class="td_right">
                                <div class="inline-flex items-center gap-1">
                                    <button type="button" class="dot_view btn_xs btn_02">보기</button>
                                    <button type="button" class="dot_delete btn_xs btn_01">삭제</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="td_left">dot_2</td>
                            <td>홍길동1</td>
                            <td>도트_2</td>
                            <td>300원</td>
                            <td class="td_right">
                                <div class="inline-flex items-center gap-1">
                                    <button type="button" class="dot_view btn_xs btn_02">보기</button>
                                    <button type="button" class="dot_delete btn_xs btn_01">삭제</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="dot_info_modal_overlay" class="hidden absolute inset-0 z-40 flex items-center justify-center">
        <div id="dot_info_modal" class="max-w-120 h-full overflow-y-auto border w-full border-gray-400 bg-white rounded p-4" role="dialog">
            <h3 class="text-base">dot_1님의 정보</h3>
            <div class="mt-4 space-y-2">
                <div>
                    <p>구매내역</p>
                    <div class="tbl_head01 tbl_wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col">브랜드</th>
                                    <th scope="col">주문번호</th>
                                    <th scope="col">상품</th>
                                    <th scope="col">금액</th>
                                    <th scope="col">상태</th>
                                    <th scope="col">기여금</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>CJ</td>
                                    <td>12345</td>
                                    <td>세탁망</td>
                                    <td>10000</td>
                                    <td>입금완료</td>
                                    <td>250원</td>
                                </tr>
                                <tr>
                                    <td>CJ</td>
                                    <td>12345</td>
                                    <td>세탁망</td>
                                    <td>10000</td>
                                    <td>입금완료</td>
                                    <td>250원</td>
                                </tr>
                                <tr>
                                    <td>CJ</td>
                                    <td>12345</td>
                                    <td>세탁망</td>
                                    <td>10000</td>
                                    <td>입금완료</td>
                                    <td>250원</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    <p>댓글 작성 내역</p>
                    <div class="tbl_head01 tbl_wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col" class="th_left">제목</th>
                                    <th scope="col" class="th_left">내용</th>
                                    <th scope="col">날짜</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="td_left">김우빈모델...</td>
                                    <td class="td_left">역시 모델 될줄 알았...</td>
                                    <td>2026-06-20</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    <p>기여금 내역</p>
                    <div class="tbl_head01 tbl_wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col">기여금</th>
                                    <th scope="col">날짜</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>250원</td>
                                    <td>2026-06-24</td>
                                </tr>
                                <tr>
                                    <td>200원</td>
                                    <td>2026-06-20</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(function () {
        // 도트 정보 모달 열기
        $(".dot_view").on("click", function () {
            const $dotInfoModalOverlay = $("#dot_info_modal_overlay");

            $dotInfoModalOverlay.removeClass("hidden");
        });

        // 흰색 모달 바깥 영역 클릭 시 닫기
        $("#dot_info_modal_overlay").on("click", function (event) {
            if (event.target !== event.currentTarget) {
                return;
            }

            $(this).addClass("hidden");
        });

        // 도트 삭제 로직 (임시)
        $(".dot_delete").on("click", function () {
            const isConfirmed = window.confirm("해당 도트를 삭제하시겠습니까?");

            if (!isConfirmed) {
                return;
            }
        });
    });
</script>
<?php
require_once '../admin.tail.php';

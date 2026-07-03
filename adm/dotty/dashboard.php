<?php
$sub_menu = '710100';
require_once './_common.php';

$g5['title'] = '도티 대시보드';
require_once '../admin.head.php';
?>

<section>
    <h2>도티 대시보드</h2>
    <div class="text-lg">안녕하세요 김우빈(임시) 도티님</div>
    <div class="flex items-center gap-4 mt-8">
        <div class="flex flex-col w-full h-30 pc:h-40 text-sm border border-gray-300 rounded px-6 py-4">
            <p>정산 예정(임시)</p>
            <p class="mt-auto self-end">250원</p>
        </div>
        <div class="flex flex-col w-full h-30 pc:h-40 text-sm border border-gray-300 rounded px-6 py-4">
            <p>정산 완료(임시)</p>
            <p class="mt-auto self-end">0원</p>
        </div>
    </div>

    <!-- 총 도트 수  -->
    <section>
        <div>총 도트 수 10명(임시)</div>

        <div class="tbl_head01 tbl_wrap">
            <table>
                <thead>
                    <tr>
                        <th scope="col">회원아이디</th>
                        <th scope="col">이름</th>
                        <th scope="col">닉네임</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>dot_1</td>
                        <td>홍길동</td>
                        <td>도트_1</td>
                    </tr>
                    <tr>
                        <td>dot_2</td>
                        <td>홍길동1</td>
                        <td>도트_2</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="btn_list03 btn_list">
            <a href="./member_list.php">전체 도트보기</a>
        </div>
    </section>

    <!-- 최근 게시물 댓글 -->
    <section>
        <div>최근 게시물 댓글(임시)</div>

        <div class="tbl_head01 tbl_wrap">
            <table>
                <thead>
                    <tr>
                        <th scope="col" class="th_left">제목</th>
                        <th scope="col">이름</th>
                        <th scope="col">닉네임</th>
                        <th scope="col" class="th_left">내용</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="td_left">김우빈모델...</td>
                        <td>홍길동</td>
                        <td>도트_1</td>
                        <td class="td_left">역시 모델 될줄 알았...</td>
                    </tr>
                    <tr>
                        <td class="td_left">김우빈모델...</td>
                        <td>홍길동1</td>
                        <td>도트_2</td>
                        <td class="td_left">나는 한지민이 더 좋은...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="btn_list03 btn_list">
            <a href="./post_list.php">게시글 관리</a>
        </div>
    </section>

     <!-- 작성한 게시물 목록 -->
    <section>
        <div>작성한 게시물 목록(임시)</div>

        <div class="tbl_head01 tbl_wrap">
            <table>
                <thead>
                    <tr>
                        <th scope="col" class="th_left">제목</th>
                        <th scope="col">이름</th>
                        <th scope="col">닉네임</th>
                        <th scope="col" class="th_left">내용</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="td_left">김우빈 모델 발탁 !</td>
                        <td>홍길동</td>
                        <td>도트_1</td>
                        <td class="td_left">안녕하세요 ! 이번에...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="btn_list03 btn_list">
            <a href="./post_list.php">게시글 관리</a>
        </div>
    </section>

    <!-- 최근 주문 목록 -->
    <section>
        <div>최근 주문 목록(임시)</div>

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

        <div class="btn_list03 btn_list">
            <a href="./order_list.php">주문목록 보기</a>
        </div>
    </section>
</section>

<?php
require_once '../admin.tail.php';

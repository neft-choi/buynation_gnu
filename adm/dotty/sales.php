<?php
$sub_menu = '710700';
include_once('./_common.php');

$g5['title'] = '매출관리';
require_once '../admin.head.php';
?>
<section>
    <h2>매출관리</h2>
    <div class="text-lg">안녕하세요 김우빈(임시) 도티님</div>

    <div class="mt-8 space-y-2">
        <div class="text-sm">
            <div class="flex items-center gap-4">
                <div class="flex flex-col w-full h-30 pc:h-40 border border-gray-300 rounded-lg px-6 pc:px-8 py-4">
                    <p>금일 매출(임시)</p>
                    <p class="self-end mt-auto">100원</p>
                </div>
                <div class="flex flex-col w-full h-30 pc:h-40 border border-gray-300 rounded-lg px-6 pc:px-8 py-4">
                    <p>일주일 매출(임시)</p>
                    <p class="self-end mt-auto">1000원</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12">
        <div id="sidx_graph">
            <!-- 세로축 금액 -->
            <ul id="sidx_graph_price">
                <li><span></span>1,000</li>
                <li><span></span>800</li>
                <li><span></span>600</li>
                <li><span></span>400</li>
                <li><span></span>200</li>
            </ul>

            <!-- 가로축 날짜 -->
            <ul id="sidx_graph_date">
                <li><span></span>05-27 (수)</li>
                <li><span></span>05-28 (목)</li>
                <li><span></span>05-29 (금)</li>
                <li><span></span>05-30 (토)</li>
                <li><span></span>05-31 (일)</li> 
                <li><span></span>06-02 (화)</li>
            </ul>

            <!-- 매출·취소 막대 -->
            <ul id="sidx_graph_area">
                <li>
                    <div class="graph order !bg-blue-500" style="height:60px" title="주문: 250원"></div>
                    <div class="graph cancel !bg-red-500" style="height:10px" title="취소: 50원"></div>
                </li>
                <li>
                    <div class="graph order !bg-blue-500" style="height:100px" title="주문: 400원"></div>
                    <div class="graph cancel !bg-red-500" style="height:20px" title="취소: 80원"></div>
                </li>
                <li>
                    <div class="graph order !bg-blue-500" style="height:140px" title="주문: 550원"></div>
                    <div class="graph cancel !bg-red-500" style="height:15px" title="취소: 60원"></div>
                </li>
                <li>
                    <div class="graph order !bg-blue-500" style="height:80px" title="주문: 300원"></div>
                    <div class="graph cancel !bg-red-500" style="height:0" title="취소: 0원"></div>
                </li>
                <li>
                    <div class="graph order !bg-blue-500" style="height:180px" title="주문: 700원"></div>
                    <div class="graph cancel !bg-red-500" style="height:30px" title="취소: 120원"></div>
                </li>
                <li>
                    <div class="graph order !bg-blue-500" style="height:220px" title="주문: 850원"></div>
                    <div class="graph cancel !bg-red-500" style="height:25px" title="취소: 100원"></div>
                </li>
                <li>
                    <div class="graph order !bg-blue-500" style="height:160px" title="주문: 600원"></div>
                    <div class="graph cancel !bg-red-500" style="height:10px" title="취소: 40원"></div>
                </li>
            </ul>

            <!-- 범례 -->
            <div id="sidx_graph_legend">
                <span id="legend_order" class="!bg-blue-500"></span> 주문
                <span id="legend_cancel" class="!bg-red-500"></span> 취소
            </div>
        </div>
    </div>

    <div>
        <p>주문 리스트(임시)</p>
        <div class="tbl_head01 tbl_wrap !mt-2">
            <table>
                <thead>
                    <tr>
                        <th scope="col" class="th_left">상태변경</th>
                        <th scope="col" class="th_left">건수</th>
                        <th scope="col">금액</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="td_left">주문 > 입금</td>
                        <td class="td_left">5</td>
                        <td class="td_right">123,490</td>
                    </tr>
                    <tr>
                        <td class="td_left">입금 > 준비</td>
                        <td class="td_left">5</td>
                        <td class="td_right">123,490</td>
                    </tr>
                    <tr>
                        <td class="td_left">준비 > 배송</td>
                        <td class="td_left">5</td>
                        <td class="td_right">123,490</td>
                    </tr>
                    <tr>
                        <td class="td_left">배송 > 완료</td>
                        <td class="td_left">5</td>
                        <td class="td_right">123,490</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
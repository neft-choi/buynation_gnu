<?php
$sub_menu = '400010';
include_once('./_common.php');
include_once(G5_LIB_PATH . '/donuts_delivery_policy.lib.php');

$brand = sql_fetch("
    SELECT brand_id
    FROM donuts_brand
    WHERE brand_id = '{$member['mb_id']}'
");

$is_brand = false;

if ($brand['brand_id']) {
    $is_brand = true;
}

$max_limit = 7; // 몇행 출력할 것인지?

$g5['title'] = ' 쇼핑몰현황';
include_once(G5_ADMIN_PATH . '/admin.head.php');

$pg_anchor = '<ul class="anchor sidx_anchor">
<li><a href="#anc_sidx_ord">주문현황</a></li>
<li><a href="#anc_sidx_rdy">입금완료미배송내역</a></li>
<li><a href="#anc_sidx_wait">미입금주문내역</a></li>
<li><a href="#anc_sidx_ps">사용후기</a></li>
<li><a href="#anc_sidx_qna">상품문의</a></li>
</ul>';

$brand_order_where = "";

if ($is_brand) {

    $brand_order_where = "
        AND od_id IN (
            SELECT DISTINCT c.od_id
            FROM {$g5['g5_shop_cart_table']} c
            INNER JOIN {$g5['g5_shop_item_table']} i
                ON c.it_id = i.it_id
            WHERE i.it_brand = '{$member['mb_id']}'
        )
    ";
}

// 배송관리 정책 기준 대시보드 합계
function donuts_dashboard_order_quote($row)
{
    global $member, $is_brand;

    $brand_id = $is_brand ? $member['mb_id'] : '';

    $addr = trim(
        $row['od_b_addr1'] . ' ' .
            $row['od_b_addr2'] . ' ' .
            $row['od_b_addr3']
    );

    $zip =
        (string)$row['od_b_zip1'] .
        (string)$row['od_b_zip2'];

    return donuts_delivery_policy_quote(
        $row['od_id'],
        $brand_id,
        $addr,
        $zip,
        false
    );
}

// 주문상태에 따른 합계 금액
function get_order_status_sum($status)
{
    global $g5, $brand_order_where;

    $status_sql = sql_real_escape_string($status);

    $result = sql_query("
        SELECT *
        FROM {$g5['g5_shop_order_table']}
        WHERE od_status = '{$status_sql}'
        {$brand_order_where}
    ");

    $count = 0;
    $price = 0;

    while ($row = sql_fetch_array($result)) {
        $quote = donuts_dashboard_order_quote($row);

        $order_total = (int)$quote['order_total'];

        if ((int)$quote['item_total'] <= 0) {
            $order_total =
                (int)$row['od_cart_price'] +
                (int)$row['od_send_cost'] +
                (int)$row['od_send_cost2'];
        }

        $price += $order_total - (int)$row['od_cancel_price'];
        $count++;
    }

    return array(
        'count' => $count,
        'price' => $price,
        'href' => './orderlist.php?od_status=' . urlencode($status)
    );
}

// 일자별 주문 합계 금액
function get_order_date_sum($date)
{
    global $g5, $brand_order_where;

    $date_sql = sql_real_escape_string($date);

    $result = sql_query("
        SELECT *
        FROM {$g5['g5_shop_order_table']}
        WHERE SUBSTRING(od_time, 1, 10) = '{$date_sql}'
        {$brand_order_where}
    ");

    $orderprice = 0;
    $cancelprice = 0;

    while ($row = sql_fetch_array($result)) {
        $quote = donuts_dashboard_order_quote($row);

        $order_total = (int)$quote['order_total'];

        if ((int)$quote['item_total'] <= 0) {
            $order_total =
                (int)$row['od_cart_price'] +
                (int)$row['od_send_cost'] +
                (int)$row['od_send_cost2'];
        }

        $orderprice += $order_total;
        $cancelprice += (int)$row['od_cancel_price'];
    }

    return array(
        'order' => $orderprice,
        'cancel' => $cancelprice
    );
}

// 일자별 결제수단 주문 합계 금액
function get_order_settle_sum($date)
{
    global $g5, $brand_order_where;

    $case = array('신용카드', '계좌이체', '가상계좌', '무통장', '휴대폰');
    $info = array();
    $date_sql = sql_real_escape_string($date);

    foreach ($case as $val) {
        $val_sql = sql_real_escape_string($val);

        $result = sql_query("
            SELECT *
            FROM {$g5['g5_shop_order_table']}
            WHERE SUBSTRING(od_time, 1, 10) = '{$date_sql}'
              AND od_settle_case = '{$val_sql}'
            {$brand_order_where}
        ");

        $price = 0;
        $count = 0;

        while ($row = sql_fetch_array($result)) {
            $quote = donuts_dashboard_order_quote($row);

            $order_total = (int)$quote['order_total'];

            if ((int)$quote['item_total'] <= 0) {
                $order_total =
                    (int)$row['od_cart_price'] +
                    (int)$row['od_send_cost'] +
                    (int)$row['od_send_cost2'];
            }

            $price +=
                $order_total -
                (int)$row['od_receipt_point'] -
                (int)$row['od_cart_coupon'] -
                (int)$row['od_coupon'] -
                (int)$row['od_send_coupon'];

            $count++;
        }

        $info[$val] = array(
            'price' => $price,
            'count' => $count
        );
    }

    // 포인트/쿠폰은 배송비 계산과 무관하므로 기존 DB값 그대로 합산
    $row = sql_fetch("
        SELECT SUM(od_receipt_point) AS price, COUNT(*) AS cnt
        FROM {$g5['g5_shop_order_table']}
        WHERE SUBSTRING(od_time, 1, 10) = '{$date_sql}'
        {$brand_order_where}
          AND od_receipt_point > 0
    ");

    $info['포인트'] = array(
        'price' => (int)$row['price'],
        'count' => (int)$row['cnt']
    );

    $row = sql_fetch("
        SELECT SUM(od_cart_coupon + od_coupon + od_send_coupon) AS price,
               COUNT(*) AS cnt
        FROM {$g5['g5_shop_order_table']}
        WHERE SUBSTRING(od_time, 1, 10) = '{$date_sql}'
        {$brand_order_where}
          AND (od_cart_coupon > 0 OR od_coupon > 0 OR od_send_coupon > 0)
    ");

    $info['쿠폰'] = array(
        'price' => (int)$row['price'],
        'count' => (int)$row['cnt']
    );

    return $info;
}

function get_max_value($arr)
{
    foreach ($arr as $key => $val) {
        if (is_array($val)) {
            $arr[$key] = get_max_value($val);
        }
    }

    sort($arr);

    return array_pop($arr);
}
?>
<?php if (!auth_check_menu($auth, '400400', 'r', true)) { ?>

    <div class="sidx">
        <section>
            <div class="grid grid-cols-2 pc:grid-cols-4 items-stretch gap-4">
                <div class="border border-gray-300 rounded-lg text-xs text-gray-500 p-4">
                    <p class="text-gray-400">최근 영업일 결제액</p>
                    <p class="text-2xl font-black text-gray-900 mt-6">136,900원</p>
                    <div class="flex items-center gap-1 mt-3">
                        <span>2026-08-12 · 2건</span>
                        <span class="rounded-full text-green-600 bg-green-100 px-2 py-1">전일 대비 +85.8%</span>
                    </div>
                </div>

                <div class="border border-gray-300 rounded-lg text-xs text-gray-500 p-4">
                    <p class="text-gray-400">출고 전 주문</p>
                    <p class="text-2xl font-black text-gray-900 mt-6">2건</p>
                    <div class="flex items-center gap-1 mt-3">
                        <span>결제완료·상품준비</span>
                        <span class="rounded-full text-amber-600 bg-amber-100 px-2 py-1">처리 필요</span>
                    </div>
                </div>

                <div class="border border-gray-300 rounded-lg text-xs text-gray-500 p-4">
                    <p class="text-gray-400">진행 중 클레임</p>
                    <p class="text-2xl font-black text-gray-900 mt-6">2건</p>
                    <div class="flex items-center gap-1 mt-3">
                        <span>취소·반품·교환</span>
                    </div>
                </div>

                <div class="border border-gray-300 rounded-lg text-xs text-gray-500 p-4">
                    <p class="text-gray-400">재고 경고</p>
                    <p class="text-2xl font-black text-gray-900 mt-6">2개 SKU</p>
                    <div class="flex items-center gap-1 mt-3">
                        <span>품절·안전재고 이하</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-4">
            <div class="grid grid-cols-1 pc:grid-cols-3 items-stretch gap-4">
                <div class="overflow-hidden border border-gray-300 rounded-lg">
                    <div class="flex items-center justify-between bg-gray-50 p-4">
                        <div>
                            <p class="font-bold">처리 필요</p>
                            <span class="text-2xs text-gray-400 mt-1">브랜드 담당자가 지금 직접 처리할 업무</span>
                        </div>
                        <button type="button" class="text-2xs text-white bg-gray-900 rounded-full font-semibold px-2 py-1">14건</button>
                    </div>

                    <div class="flex flex-col border-y border-gray-300 divide-y divide-gray-300">
                        <div class="flex items-center gap-2 p-3">
                            <span class="inline-flex items-center justify-center w-8 aspect-square rounded-lg text-xs text-amber-600/30 bg-amber-100/30">쪽</span>
                            <div class="flex-1 min-w-0 text-2xs">
                                <p class="text-gray-900/30 font-bold">읽지 않은 도넛 쪽지 · 현재 없음</p>
                                <p class="text-gray-300/30">대화를 열어 새 답장과 협의 내용을 확인합니다.</p>
                            </div>
                            <button type="button" class="border border-gray-500/30 rounded-lg text-2xs text-gray-900/30 font-bold px-4 py-1">보기</button>
                        </div>

                        <div class="flex items-center gap-2 p-3">
                            <span class="inline-flex items-center justify-center w-8 aspect-square rounded-lg text-xs text-amber-600 bg-amber-100">제</span>
                            <div class="flex-1 min-w-0 text-2xs">
                                <p class="font-bold">도넛이 보낸 협업 제안 · 1건</p>
                                <p class="text-gray-300">도넛의 제안을 검토하고 협업 수락 여부를 결정합니다.</p>
                            </div>
                            <button type="button" class="border border-gray-500 rounded-lg text-2xs font-bold px-4 py-1">확인</button>
                        </div>

                        <div class="flex items-center gap-2 p-3">
                            <span class="inline-flex items-center justify-center w-8 aspect-square rounded-lg text-xs text-amber-600 bg-amber-100">기</span>
                            <div class="flex-1 min-w-0 text-2xs">
                                <p class="font-bold">전용 기획전 등록 · 2건</p>
                                <p class="text-gray-300">수락된 협업 조건을 실제 기획전 정보로 등록합니다.</p>
                            </div>
                            <button type="button" class="border border-gray-500 rounded-lg text-2xs font-bold px-4 py-1">확인</button>
                        </div>

                        <div class="flex items-center gap-2 p-3">
                            <span class="inline-flex items-center justify-center w-8 aspect-square rounded-lg text-xs text-amber-600 bg-amber-100">서</span>
                            <div class="flex-1 min-w-0 text-2xs">
                                <p class="font-bold">사업자 서류 보완 · 1건</p>
                                <p class="text-gray-300">플랫폼 사유를 확인하고 서류를 교체·재제출합니다.</p>
                            </div>
                            <button type="button" class="border border-gray-500 rounded-lg text-2xs font-bold px-4 py-1">확인</button>
                        </div>

                        <div class="flex items-center gap-2 p-3">
                            <span class="inline-flex items-center justify-center w-8 aspect-square rounded-lg text-xs text-amber-600 bg-amber-100">주</span>
                            <div class="flex-1 min-w-0 text-2xs">
                                <p class="font-bold">신규 주문 확인 · 1건</p>
                                <p class="text-gray-300">재고를 확인하고 상품준비 처리를 시작합니다.</p>
                            </div>
                            <button type="button" class="border border-gray-500 rounded-lg text-2xs font-bold px-4 py-1">확인</button>
                        </div>

                        <div class="flex items-center gap-2 p-3">
                            <span class="inline-flex items-center justify-center w-8 aspect-square rounded-lg text-xs text-amber-600 bg-amber-100">배</span>
                            <div class="flex-1 min-w-0 text-2xs">
                                <p class="font-bold">발송 정보 등록 · 1건</p>
                                <p class="text-gray-300">택배사·송장번호 또는 출고 지연 사유를 등록합니다.</p>
                            </div>
                            <button type="button" class="border border-gray-500 rounded-lg text-2xs font-bold px-4 py-1">확인</button>
                        </div>
                    </div>

                    <div class="text-2xs text-gray-500 bg-gray-50 px-4 py-2">
                        0건도 업무 범위 확인을 위해 표시합니다.
                    </div>
                </div>

                <div class="overflow-hidden border border-gray-300 rounded-lg"></div>

                <div class="overflow-hidden border border-gray-300 rounded-lg">
                    <div class="flex items-center justify-between bg-gray-50 p-4">
                        <div>
                            <p class="font-bold">주의·예외</p>
                            <span class="text-2xs text-gray-400 mt-1">판매 차단·지연·권한 위험을 사람이 확인할 업무</span>
                        </div>
                        <button type="button" class="text-2xs text-white bg-gray-900 rounded-full font-semibold px-2 py-1">4건</button>
                    </div>

                    <div class="flex flex-col border-y border-gray-300 divide-y divide-gray-300">
                        <div class="flex items-center gap-2 p-3">
                            <span class="inline-flex items-center justify-center w-8 aspect-square rounded-lg text-xs text-amber-600 bg-amber-100">재</span>
                            <div class="flex-1 min-w-0 text-2xs">
                                <p class="text-gray-900 font-bold">재고 부족·품절 · 2건</p>
                                <p class="text-gray-300">판매 가능 수량과 안전재고를 확인해 입고·품절 처리합니다.</p>
                            </div>
                            <button type="button" class="border border-gray-500 rounded-lg text-2xs text-gray-900 font-bold px-4 py-1">확인</button>
                        </div>

                        <div class="flex items-center gap-2 p-3">
                            <span class="inline-flex items-center justify-center w-8 aspect-square rounded-lg text-xs text-amber-600 bg-amber-100">그</span>
                            <div class="flex-1 min-w-0 text-2xs">
                                <p class="font-bold">배송그룹 미배정 · 1건</p>
                                <p class="text-gray-300">판매 전 모든 상품에 유효한 배송그룹을 지정합니다.</p>
                            </div>
                            <button type="button" class="border border-gray-500 rounded-lg text-2xs font-bold px-4 py-1">확인</button>
                        </div>

                        <div class="flex items-center gap-2 p-3">
                            <span class="inline-flex items-center justify-center w-8 aspect-square rounded-lg text-xs text-amber-600/30 bg-amber-100/30">지</span>
                            <div class="flex-1 min-w-0 text-2xs">
                                <p class="text-gray-900/30 font-bold">전용 기획전 등록 · 2건</p>
                                <p class="text-gray-300/30">수락된 협업 조건을 실제 기획전 정보로 등록합니다.</p>
                            </div>
                            <button type="button" class="border border-gray-500/30 rounded-lg text-2xs text-gray-900/30 font-bold px-4 py-1">보기</button>
                        </div>

                        <div class="flex items-center gap-2 p-3">
                            <span class="inline-flex items-center justify-center w-8 aspect-square rounded-lg text-xs text-amber-600 bg-amber-100">끝</span>
                            <div class="flex-1 min-w-0 text-2xs">
                                <p class="font-bold">기간 종료 기획전 · 1건</p>
                                <p class="text-gray-300">종료일이 지났지만 진행 중인 기획전을 중지하거나 기간을 수정합니다.</p>
                            </div>
                            <button type="button" class="border border-gray-500 rounded-lg text-2xs font-bold px-4 py-1">확인</button>
                        </div>

                        <div class="flex items-center gap-2 p-3">
                            <span class="inline-flex items-center justify-center w-8 aspect-square rounded-lg text-xs text-amber-600/30 bg-amber-100/30">권</span>
                            <div class="flex-1 min-w-0 text-2xs">
                                <p class="text-gray-900/30 font-bold">중지된 담당자 계정 · 현재 없음</p>
                                <p class="text-gray-300/30">권한 중지 사유와 업무 인수인계를 확인합니다.</p>
                            </div>
                            <button type="button" class="border border-gray-500/30 rounded-lg text-2xs text-gray-900/30 font-bold px-4 py-1">보기</button>
                        </div>

                        <div class="flex items-center gap-2 p-3">
                            <span class="inline-flex items-center justify-center w-8 aspect-square rounded-lg text-xs text-amber-600/30 bg-amber-100/30">연</span>
                            <div class="flex-1 min-w-0 text-2xs">
                                <p class="text-gray-900/30 font-bold">연결 상품 없음 · 현재 없음</p>
                                <p class="text-gray-300/30">삭제·누락 상품과 연결된 기획전을 정리합니다.</p>
                            </div>
                            <button type="button" class="border border-gray-500/30 rounded-lg text-2xs text-gray-900/30 font-bold px-4 py-1">보기</button>
                        </div>
                    </div>

                    <div class="text-2xs text-gray-500 bg-gray-50 px-4 py-2">
                        0건도 업무 범위 확인을 위해 표시합니다.
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-4">
            <div class="border border-gray-300 rounded-lg overflow-hidden">
                <div class="flex items-center justify-between border-b border-gray-300 p-4">
                    <span class="text-sm font-bold">운영 요약</span>
                    <button type="button" class="border border-gray-300 rounded-lg text-2xs text-gray-900 font-bold bg-white px-2 py-1">분석 보기</button>
                </div>

                <dl class="divide-y divide-gray-300 text-2xs px-4 [&>div]:flex [&>div]:items-center [&>div]:justify-between [&>div]:p-2">
                    <div>
                        <dl>판매중 상품</dl>
                        <dt>6개</dt>
                    </div>
                    <div>
                        <dl>배송그룹 배정</dl>
                        <dt>9 / 10개</dt>
                    </div>
                    <div>
                        <dl>사업자 서류 변경</dl>
                        <dt><span class="text-red-600 font-bold bg-red-50 rounded-full px-2 py-1">보완요청</span></dt>
                    </div>
                    <div>
                        <dl>진행 중 캠페인</dl>
                        <dt>2건</dt>
                    </div>
                </dl>
            </div>
        </section>

        <section class="mt-4">
            <div class="flex items-end justify-between gap-3">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">최근 주문</h3>
                    <p class="mt-1 text-2xs text-gray-400">
                        주문 처리 결과는 재고와 활동 로그에 반영됩니다.
                    </p>
                </div>

                <button type="button" class="shrink-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                    전체 보기
                </button>
            </div>

            <div class="mt-3 overflow-hidden rounded-lg border border-gray-200 bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-300 table-fixed border-collapse text-left text-xs">
                        <colgroup>
                            <col class="w-[14%]">
                            <col class="w-[8%]">
                            <col class="w-[23%]">
                            <col class="w-[20%]">
                            <col class="w-[12%]">
                            <col class="w-[11%]">
                            <col class="w-[12%]">
                        </colgroup>

                        <thead class="border-b border-gray-200 bg-gray-50 text-2xs text-gray-500 [&_th]:px-3 [&_th]:py-3 [&_th]:font-semibold">
                            <tr>
                                <th>주문번호·일시</th>
                                <th>구매자</th>
                                <th>상품</th>
                                <th>결제금액</th>
                                <th>배송정보</th>
                                <th>상태</th>
                                <th>관리</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 text-gray-700 [&_td]:px-3 [&_td]:py-4 [&_td]:align-middle">
                            <tr>
                                <td>
                                    <span class="block font-bold text-gray-900">ORD-1038</span>
                                    <span class="mt-1 block text-2xs text-gray-400">2026-08-11 10:24</span>
                                </td>
                                <td>홍길동</td>
                                <td>
                                    <span class="block text-gray-900">유기농 그래놀라 500g × 2</span>
                                    <span class="mt-1 block text-2xs text-gray-400">기본 배송그룹</span>
                                </td>
                                <td>
                                    <span class="block font-bold text-gray-900">40,800원</span>
                                    <span class="mt-1 block text-2xs text-gray-400">상품 37,800원 · 배송 3,000원</span>
                                </td>
                                <td>
                                    <span class="text-gray-500">송장 미등록</span>
                                </td>
                                <td>
                                    <span class="inline-flex rounded-full bg-green-50 px-2 py-1 text-2xs font-semibold text-green-600">
                                        결제완료
                                    </span>
                                </td>
                                <td>
                                    <button type="button" data-order="ORD-1038" class="whitespace-nowrap rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                        상세·처리
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="block font-bold text-gray-900">ORD-1059</span>
                                    <span class="mt-1 block text-2xs text-gray-400">2026-08-11 09:18</span>
                                </td>
                                <td>박도트</td>
                                <td>
                                    <span class="block text-gray-900">프리미엄 냉장 샐러드 6팩 × 1</span>
                                    <span class="mt-1 block text-2xs text-gray-400">냉장 묶음배송</span>
                                </td>
                                <td>
                                    <span class="block font-bold text-gray-900">32,900원</span>
                                    <span class="mt-1 block text-2xs text-gray-400">상품 32,900원 · 배송 0원</span>
                                </td>
                                <td>
                                    <span class="text-gray-500">송장 미등록</span>
                                </td>
                                <td>
                                    <span class="inline-flex rounded-full bg-amber-50 px-2 py-1 text-2xs font-semibold text-amber-600">
                                        상품준비
                                    </span>
                                </td>
                                <td>
                                    <button type="button" data-order="ORD-1059" class="whitespace-nowrap rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                        상세·처리
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="block font-bold text-gray-900">ORD-1055</span>
                                    <span class="mt-1 block text-2xs text-gray-400">2026-08-12 16:40</span>
                                </td>
                                <td>최도트</td>
                                <td>
                                    <span class="block text-gray-900">선물용 대형 패키지 × 2</span>
                                    <span class="mt-1 block text-2xs text-gray-400">대형 개별배송</span>
                                </td>
                                <td>
                                    <span class="block font-bold text-gray-900">112,000원</span>
                                    <span class="mt-1 block text-2xs text-gray-400">상품 96,000원 · 배송 16,000원</span>
                                </td>
                                <td>
                                    <span class="block text-gray-900">CJ대한통운</span>
                                    <span class="mt-1 block text-2xs text-gray-400">1234567890</span>
                                </td>
                                <td>
                                    <span class="inline-flex rounded-full bg-green-50 px-2 py-1 text-2xs font-semibold text-green-600">
                                        배송중
                                    </span>
                                </td>
                                <td>
                                    <button type="button" data-order="ORD-1055" class="whitespace-nowrap rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                        상세·처리
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="block font-bold text-gray-900">ORD-1048</span>
                                    <span class="mt-1 block text-2xs text-gray-400">2026-08-12 11:12</span>
                                </td>
                                <td>윤도트</td>
                                <td>
                                    <span class="block text-gray-900">저당 단백질바 12개입 × 1</span>
                                    <span class="mt-1 block text-2xs text-gray-400">기본 배송그룹</span>
                                </td>
                                <td>
                                    <span class="block font-bold text-gray-900">24,900원</span>
                                    <span class="mt-1 block text-2xs text-gray-400">상품 21,900원 · 배송 3,000원</span>
                                </td>
                                <td>
                                    <span class="block text-gray-900">한진택배</span>
                                    <span class="mt-1 block text-2xs text-gray-400">5566778899</span>
                                </td>
                                <td>
                                    <span class="inline-flex rounded-full bg-green-50 px-2 py-1 text-2xs font-semibold text-green-600">
                                        배송완료
                                    </span>
                                </td>
                                <td>
                                    <button type="button" data-order="ORD-1048" class="whitespace-nowrap rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                        상세·처리
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <span class="block font-bold text-gray-900">ORD-1041</span>
                                    <span class="mt-1 block text-2xs text-gray-400">2026-08-09 14:22</span>
                                </td>
                                <td>정도트</td>
                                <td>
                                    <span class="block text-gray-900">콜드프레스 주스 12병 × 2</span>
                                    <span class="mt-1 block text-2xs text-gray-400">냉장 묶음배송</span>
                                </td>
                                <td>
                                    <span class="block font-bold text-gray-900">79,800원</span>
                                    <span class="mt-1 block text-2xs text-gray-400">상품 79,800원 · 배송 0원</span>
                                </td>
                                <td>
                                    <span class="block text-gray-900">CJ대한통운</span>
                                    <span class="mt-1 block text-2xs text-gray-400">9988776655</span>
                                </td>
                                <td>
                                    <span class="inline-flex rounded-full bg-green-50 px-2 py-1 text-2xs font-semibold text-green-600">
                                        배송완료
                                    </span>
                                </td>
                                <td>
                                    <button type="button" data-order="ORD-1041" class="whitespace-nowrap rounded-lg border border-gray-300 bg-white px-3 py-2 text-2xs font-bold text-gray-900">
                                        상세·처리
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section id="anc_sidx_ord" class="!hidden">
            <h2>주문현황</h2>
            <?php echo $pg_anchor; ?>

            <?php
            $arr_order = array();
            $x_val = array();
            for ($i = 6; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime('-' . $i . ' days', G5_SERVER_TIME));

                $x_val[] = $date;
                $arr_order[] = get_order_date_sum($date);
            }

            $max_y = get_max_value($arr_order);

            if ($max_y <= 0) {
                $max_y = 1000;
            }

            $max_y = ceil(($max_y) / 1000) * 1000;
            $y_val = array();
            $y_val[] = $max_y;

            for ($i = 4; $i >= 1; $i--) {
                $y_val[] = $max_y * (($i * 2) / 10);
            }

            $max_height = 230;
            $h_val = array();
            $js_val = array();
            $offset = 10; // 금액이 상대적으로 작아 높이가 0일 때 기본 높이로 사용
            foreach ($arr_order as $val) {
                if ($val['order'] > 0)
                    $h1 = intval(($max_height * $val['order']) / $max_y) + $offset;
                else
                    $h1 = 0;

                if ($val['cancel'] > 0)
                    $h2 = intval(($max_height * $val['cancel']) / $max_y) + $offset;
                else
                    $h2 = 0;

                $h_val['order'][] = $h1;
                $h_val['cancel'][] = $h2;
            }
            ?>

            <div id="sidx_graph">
                <ul id="sidx_graph_price">
                    <?php
                    foreach ($y_val as $val) {
                    ?>
                        <li><span></span><?php echo number_format($val); ?></li>
                    <?php
                    }
                    ?>
                </ul>
                <ul id="sidx_graph_area">
                    <?php
                    for ($i = 0; $i < count($x_val); $i++) {
                        $order_title = date("n월 j일", strtotime($x_val[$i])) . ' 주문: ' . display_price($arr_order[$i]['order']);
                        $cancel_title = date("n월 j일", strtotime($x_val[$i])) . ' 취소: ' . display_price($arr_order[$i]['cancel']);
                        $k = 10 - $i;
                        $li_bg = 'bg' . ($i % 2);
                    ?>
                        <li class="<?php echo $li_bg; ?>" style="z-index:<?php echo $k; ?>">
                            <div class="graph order !bg-blue-500" title="<?php echo $order_title; ?>">

                            </div>
                            <div class="graph cancel !bg-red-500" title="<?php echo $cancel_title; ?>">

                            </div>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
                <ul id="sidx_graph_date">
                    <?php
                    foreach ($x_val as $val) {
                    ?>
                        <li><span></span><?php echo substr($val, 5, 5) . ' (' . get_yoil($val) . ')'; ?></li>
                    <?php
                    }
                    ?>
                </ul>
                <div id="sidx_graph_legend">
                    <span id="legend_order" class="!bg-blue-500"></span> 주문
                    <span id="legend_cancel" class="!bg-red-500"></span> 취소
                </div>
            </div>
        </section>

        <div id="sidx_stat" class="!hidden">
            <section id="anc_sidx_act">
                <h2>처리할 주문</h2>
                <?php echo $pg_anchor; ?>

                <div id="sidx_take_act" class="tbl_head01 tbl_wrap">
                    <table>
                        <thead>
                            <tr>
                                <th scope="col" class="td_mng">상태변경</th>
                                <th scope="col">건수</th>
                                <th scope="col">금액</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php
                                $info = get_order_status_sum('주문');
                                ?>
                                <th scope="row">주문 -&gt; 입금</th>
                                <td class="td_num"><a
                                        href="<?php echo $info['href']; ?>"><?php echo number_format($info['count']); ?></a>
                                </td>
                                <td class="td_price"><a
                                        href="<?php echo $info['href']; ?>"><?php echo number_format($info['price']); ?></a>
                                </td>
                            </tr>
                            <tr>
                                <?php
                                $info = get_order_status_sum('입금');
                                ?>
                                <th scope="row">입금 -&gt; 준비</th>
                                <td class="td_num"><a
                                        href="<?php echo $info['href']; ?>"><?php echo number_format($info['count']); ?></a>
                                </td>
                                <td class="td_price"><a
                                        href="<?php echo $info['href']; ?>"><?php echo number_format($info['price']); ?></a>
                                </td>
                            </tr>
                            <tr>
                                <?php
                                $info = get_order_status_sum('준비');
                                ?>
                                <th scope="row">준비 -&gt; 배송</th>
                                <td class="td_num"><a
                                        href="<?php echo $info['href']; ?>"><?php echo number_format($info['count']); ?></a>
                                </td>
                                <td class="td_price"><a
                                        href="<?php echo $info['href']; ?>"><?php echo number_format($info['price']); ?></a>
                                </td>
                            </tr>
                            <tr>
                                <?php
                                $info = get_order_status_sum('배송');
                                ?>
                                <th scope="row">배송 -&gt; 완료</th>
                                <td class="td_num"><a
                                        href="<?php echo $info['href']; ?>"><?php echo number_format($info['count']); ?></a>
                                </td>
                                <td class="td_price"><a
                                        href="<?php echo $info['href']; ?>"><?php echo number_format($info['price']); ?></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="anc_sidx_stock">
                <h2>재고현황</h2>
                <?php echo $pg_anchor; ?>

                <?php
                // 재고부족 상품
                $item_noti = 0;
                $brand_item_where = "";

                if ($is_brand) {
                    $brand_item_where = " and it_brand = '{$member['mb_id']}' ";
                }
                $sql = " select count(*) as cnt
                        from {$g5['g5_shop_item_table']}
                        where it_use = '1'
                        and it_option_subject = ''
                        and it_stock_qty <= it_noti_qty
                        {$brand_item_where}";
                $row = sql_fetch($sql);
                $item_noti = (int) $row['cnt'];

                // 재고부족 옵션
                $option_noti = 0;
                $sql = "
            select count(*) as cnt
            from {$g5['g5_shop_item_option_table']} o
            inner join {$g5['g5_shop_item_table']} i
                on o.it_id = i.it_id
            where o.io_use = '1'
            and o.io_stock_qty <= o.io_noti_qty
            ";

                if ($is_brand) {
                    $sql .= " and i.it_brand = '{$member['mb_id']}' ";
                }
                $row = sql_fetch($sql);
                $option_noti = (int) $row['cnt'];

                // SMS 정보
                $userinfo = array('coin' => 0);
                if ($config['cf_sms_use'] && $config['cf_icode_id'] && $config['cf_icode_pw']) {
                    $userinfo = get_icode_userinfo($config['cf_icode_id'], $config['cf_icode_pw']);
                }
                ?>
                <div id="sidx_stock" class="tbl_head01 tbl_wrap">
                    <table>
                        <thead>
                            <tr>
                                <th scope="col">재고부족 상품</th>
                                <th scope="col">재고부족 옵션</th>
                                <th scope="col">SMS 잔여금액</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="td_num2"><a
                                        href="./itemstocklist.php"><?php echo number_format($item_noti); ?></a></td>
                                <td class="td_num2"><a
                                        href="./optionstocklist.php"><?php echo number_format($option_noti); ?></a></td>
                                <td class="td_price"><?php echo display_price(intval($userinfo['coin'])); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <section id="anc_sidx_settle" class="!hidden">
        <h2>결제수단별 주문현황</h2>
        <?php echo $pg_anchor; ?>

        <div id="sidx_settle" class="tbl_head01 tbl_wrap">
            <table>
                <thead>
                    <tr>
                        <th scope="col" rowspan="2">구분</th>
                        <?php
                        $term = 3;
                        $info = array();
                        $info_key = array();
                        for ($i = ($term - 1); $i >= 0; $i--) {
                            $date = date("Y-m-d", strtotime('-' . $i . ' days', G5_SERVER_TIME));
                            $info[$date] = get_order_settle_sum($date);

                            $day = substr($date, 5, 5) . ' (' . get_yoil($date) . ')';
                            $info_key[] = $date;
                        ?>
                            <th scope="col" colspan="2"><?php echo $day; ?></th>
                        <?php } ?>
                    </tr>
                    <tr>
                        <?php
                        for ($i = 0; $i < $term; $i++) {
                        ?>
                            <th scope="col">건수</th>
                            <th scope="col">금액</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $case = array('신용카드', '계좌이체', '가상계좌', '무통장', '휴대폰', '포인트', '쿠폰');

                    $val_cnt = 0;
                    foreach ($case as $val) {
                        $val_cnt++;
                    ?>
                        <tr>
                            <th scope="row" id="th_val_<?php echo $val_cnt; ?>" class="td_category"><?php echo $val; ?></th>
                            <?php
                            foreach ($info_key as $date) {
                            ?>
                                <td><?php echo number_format($info[$date][$val]['count']); ?></td>
                                <td><?php echo number_format($info[$date][$val]['price']); ?></td>
                            <?php
                            }
                            ?>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <script>
        jQuery(function($) {
            graph_draw();

            $("#sidx_graph_area div").hover(
                function() {
                    if ($(this).is(":animated"))
                        return false;

                    var title = $(this).attr("title");
                    if (title && $(this).data("title") == undefined)
                        $(this).data("title", title);
                    var left = parseInt($(this).css("left")) + 10;
                    var bottom = $(this).height() + 5;

                    $(this)
                        .attr("title", "")
                        .append("<div id=\"price_tooltip\"><div></div></div>");
                    $("#price_tooltip")
                        .find("div")
                        .html(title)
                        .end()
                        //                .css({ left: left+"px", bottom: bottom+"px" })
                        .show(200);
                },
                function() {
                    if ($(this).is(":animated"))
                        return false;

                    $(this).attr("title", $(this).data("title"));
                    $("#price_tooltip").remove();
                }
            );
        });

        function graph_draw() {
            var g_h1 = new Array("<?php echo implode('", "', $h_val['order']); ?>");
            var g_h2 = new Array("<?php echo implode('", "', $h_val['cancel']); ?>");
            var duration = 600;

            var $el = $("#sidx_graph_area li");
            var h1, h2;
            var $g1, $g2;

            $el.each(function(index) {
                h1 = g_h1[index];
                h2 = g_h2[index];

                $g1 = $(this).find(".order");
                $g2 = $(this).find(".cancel");

                $g1.animate({
                    height: h1 + "px"
                }, duration);
                $g2.animate({
                    height: h2 + "px"
                }, duration);
            });
        }
    </script>

<?php } //endif 
?>
<?php if ($is_admin === 'super') { ?>
    <div class="sidx sidx_cs !hidden">
        <section id="anc_sidx_oneq">
            <h2>1:1문의</h2>
            <?php echo $pg_anchor; ?>

            <div class="ul_01 ul_wrap">
                <ul>
                    <?php
                    $sql = " select * from {$g5['qa_content_table']}
                          where qa_status = '0'
                            and qa_type = '0'
                          order by qa_num
                          limit $max_limit ";
                    $result = sql_query($sql);
                    for ($i = 0; $row = sql_fetch_array($result); $i++) {
                        $sql1 = " select * from {$g5['member_table']} where mb_id = '{$row['mb_id']}' ";
                        $row1 = sql_fetch($sql1);

                        $name = get_sideview($row['mb_id'], get_text($row['qa_name']), $row1['mb_email'], $row1['mb_homepage']);
                    ?>
                        <li>
                            <span class="oneq_cate oneq_span"><?php echo get_text($row['qa_category']); ?></span>
                            <a href="<?php echo G5_BBS_URL; ?>/qaview.php?qa_id=<?php echo $row['qa_id']; ?>" target="_blank"
                                class="oneq_link"><?php echo conv_subject($row['qa_subject'], 40); ?></a>
                            <?php echo $name; ?>
                        </li>
                    <?php
                    }

                    if ($i == 0)
                        echo '<li class="empty_list">자료가 없습니다.</li>';
                    ?>
                </ul>
            </div>

            <div class="btn_list03 btn_list">
                <a href="<?php echo G5_BBS_URL; ?>/qalist.php" target="_blank">1:1문의 더보기</a>
            </div>
        </section>

        <section id="anc_sidx_qna">
            <h2>상품문의</h2>
            <?php echo $pg_anchor; ?>

            <div class="ul_01 ul_wrap">
                <ul>
                    <?php
                    $sql = " select * from {$g5['g5_shop_item_qa_table']}
                          where iq_answer = ''
                          order by iq_id desc
                          limit $max_limit ";
                    $result = sql_query($sql);
                    for ($i = 0; $row = sql_fetch_array($result); $i++) {
                        $sql1 = " select * from {$g5['member_table']} where mb_id = '{$row['mb_id']}' ";
                        $row1 = sql_fetch($sql1);

                        $name = get_sideview($row['mb_id'], get_text($row['iq_name']), $row1['mb_email'], $row1['mb_homepage']);
                    ?>
                        <li>
                            <a href="./itemqaform.php?w=u&amp;iq_id=<?php echo $row['iq_id']; ?>"
                                class="qna_link"><?php echo conv_subject($row['iq_subject'], 40); ?></a>
                            <?php echo $name; ?>
                        </li>
                    <?php
                    }

                    if ($i == 0)
                        echo '<li class="empty_list">자료가 없습니다.</li>';
                    ?>
                </ul>
            </div>

            <div class="btn_list03 btn_list">
                <a href="./itemqalist.php?sort1=iq_answer&amp;sort2=asc">상품문의 더보기</a>
            </div>
        </section>

        <section id="anc_sidx_ps">
            <h2>사용후기</h2>
            <?php echo $pg_anchor; ?>

            <div class="ul_01 ul_wrap">
                <ul>
                    <?php
                    $sql = " select * from {$g5['g5_shop_item_use_table']}
                      where is_confirm = 0
                      order by is_id desc
                      limit $max_limit ";
                    $result = sql_query($sql);
                    for ($i = 0; $row = sql_fetch_array($result); $i++) {
                        $sql1 = " select * from {$g5['member_table']} where mb_id = '{$row['mb_id']}' ";
                        $row1 = sql_fetch($sql1);

                        $name = get_sideview($row['mb_id'], get_text($row['is_name']), $row1['mb_email'], $row1['mb_homepage']);
                    ?>
                        <li>
                            <a href="./itemuseform.php?w=u&amp;is_id=<?php echo $row['is_id']; ?>"
                                class="ps_link"><?php echo conv_subject($row['is_subject'], 40); ?></a>
                            <?php echo $name; ?>
                        </li>
                    <?php
                    }
                    if ($i == 0)
                        echo '<li class="empty_list">자료가 없습니다.</li>';
                    ?>
                </ul>
            </div>

            <div class="btn_list03 btn_list">
                <a href="./itemuselist.php?sort1=is_confirm&amp;sort2=asc">사용후기 더보기</a>
            </div>
        </section>
    </div>
<?php
}   //end if
include_once(G5_ADMIN_PATH . '/admin.tail.php');

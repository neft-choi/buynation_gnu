<?php
include_once('./_common.php');

// reorder.php 페이지 전용 상수
define('_REORDER_', true);

// $is_member는 현재 페이지에 들어온 사람이 회원인지 확인
// 회원이 아니면 로그인 후 다시 reorder.php 로 돌아오게 만든다
if (!$is_member) {
    goto_url(G5_BBS_URL . '/login.php?url=' . urlencode(G5_SHOP_URL . '/reorder.php'));
}

// 로그인한 회원의 정상 주문된 상품만 자주구매 집계 대상으로 사용한다.
$sql_common = " from {$g5['g5_shop_cart_table']} c
                 where c.mb_id = '{$member['mb_id']}'
                   and c.ct_status in ('주문', '입금', '준비', '배송', '완료') ";

// 중복되는 상품 번호
$sql_group = " group by c.it_id
               having count(distinct c.od_id) >= 1 ";

// 자주구매 상품의 전체 개수
$sql = " select count(*) as cnt
           from (
                select c.it_id
                  {$sql_common}
                  {$sql_group}
           ) as reorder_items ";
$row = sql_fetch($sql);
$total_count = (int) $row['cnt'];

// 한 페이지에 표시할 자주구매 상품 카드 개수
$rows = 10;
$total_page = ceil($total_count / $rows);

if ($page < 1) {
    $page = 1;
}

// 현재 페이지에서 조회할 자주구매 상품 범위
$from_record = ($page - 1) * $rows;
$limit = " limit {$from_record}, {$rows} ";

// 주문 횟수, 총 구매수량, 최근 주문일을 기준으로 자주구매 상품을 정렬한다.
$sql = " select c.it_id,
                 max(c.it_name) as it_name,
                 count(distinct c.od_id) as purchase_count,
                 sum(c.ct_qty) as purchase_qty,
                 max(c.ct_time) as last_order_time
           {$sql_common}
           {$sql_group}
          order by purchase_count desc, purchase_qty desc, last_order_time desc
          {$limit} ";
$result = sql_query($sql);

// 현재 페이지에 출력할 자주구매 상품 목록
$reorder_items = array();
for ($i = 0; $row = sql_fetch_array($result); $i++) {
    $reorder_items[] = $row;
}

$g5['title'] = '자주구매 상품';
include_once('./_head.php');
?>

<!-- 회원 요약 정보 (PC) -->
<?php include_once(G5_THEME_SHOP_PATH . '/_mypage_summary_pc.php'); ?>

<!-- 자주구매 상품 -->
<div class="block pc:flex gap-6 pc:px-5 pc:py-12">

    <!-- 마이페이지 메뉴 (PC) -->
    <?php include_once(G5_THEME_SHOP_PATH . '/_mypage_menu_pc.php'); ?>

    <!-- 자주구매 상품 목록 -->
    <section class="min-w-0 flex-1">

        <!-- 기본 화면 헤더 -->
        <div class="flex pc:hidden items-center justify-between p-4">
            <button type="button" class="inline-flex items-center justify-center text-zinc-700" aria-label="뒤로가기"
                onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '<?php echo G5_URL ?>'; }">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-chevron-left-icon lucide-chevron-left w-6 h-6">
                    <path d="m15 18-6-6 6-6" />
                </svg>
            </button>
            <h1 class="text-lg font-semibold text-zinc-900 leading-0"><?php echo $g5['title']; ?></h1>
            <div class="w-6 h-6" aria-hidden="true"></div>
        </div>

        <!-- PC 제목 -->
        <div class="hidden pc:block px-4">
            <h2 class="text-2xl font-bold pb-4 border-b-2 border-gray-900">
                <?php echo $g5['title']; ?>
            </h2>
        </div>

        <?php if (empty($reorder_items)) { ?>
            <p class="p-4 text-sm text-zinc-500">자주구매 상품이 없습니다.</p>
        <?php } else { ?>
            <div class="divide-y divide-[var(--color-semantic-border-solid-subtle)]">
                <?php foreach ($reorder_items as $index => $item) { ?>
                    <?php
                    $rank = $from_record + $index + 1;
                    $item_url = shop_item_url($item['it_id']);
                    $thumb_html = get_it_image($item['it_id'], 80, 102);
                    $item_name = get_text($item['it_name']);
                    $purchase_count = (int) $item['purchase_count'];
                    $purchase_qty = (int) $item['purchase_qty'];
                    $last_order_date = str_replace('-', '.', substr($item['last_order_time'], 0, 10));
                    ?>

                    <article class="grid grid-cols-[80px_1fr] gap-4 py-3">
                        <a href="<?php echo $item_url; ?>" class="overflow-hidden rounded bg-zinc-100">
                            <?php echo $thumb_html ? $thumb_html : '<div class="h-full w-full aspect-[80/102]"></div>'; ?>
                        </a>

                        <div class="flex min-w-0 flex-col justify-center gap-1.5 text-[var(--color-semantic-label-solid-default)]">
                            <p class="text-xs font-semibold text-[var(--color-semantic-label-solid-subtle)]">
                                <?php echo number_format($rank); ?>위
                            </p>
                            <a href="<?php echo $item_url; ?>" class="truncate text-[15px] font-normal">
                                <?php echo $item_name; ?>
                            </a>
                            <p class="text-sm text-[var(--color-semantic-label-solid-subtle)]">
                                <?php echo number_format($purchase_count); ?>회 주문 <span class="mx-1">|</span> 총 <?php echo number_format($purchase_qty); ?>개 구매
                            </p>
                            <p class="!hidden text-xs text-[var(--color-semantic-label-solid-subtle)]">
                                최근 주문 <?php echo $last_order_date; ?>
                            </p>
                        </div>
                    </article>
                <?php } ?>
            </div>
        <?php } ?>

    </section>

</div>

<script>
    syncWithPcBreakpoint(function (isPc) {
        if (isPc) {
            $('#hd').css('display', '');
        } else {
            $('#hd').css('display', 'none');
        }
    });
</script>

<?php
include_once('./_tail.php');

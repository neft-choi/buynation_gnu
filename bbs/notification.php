<?php
include_once('./_common.php');

if ($is_guest) {
    alert('로그인 후 이용하실 수 있습니다.', G5_BBS_URL . '/login.php?url=' . urlencode(G5_BBS_URL . '/notification.php'));
}

$g5['title'] = '알림';
include_once('./_head.php');

$sql = "
SELECT *
FROM g5_shop_order
WHERE mb_id ='{$member['mb_id']}'
ORDER BY od_time DESC
LIMIT 20
";

$result = sql_query($sql);

?>

<section class="mx-auto w-full max-w-full space-y-4 px-4 py-4">

    <div class="flex items-center justify-between">
        <button type="button" onclick="goBackFromNotification();" class="inline-flex h-8 w-8 items-center justify-center text-zinc-700" aria-label="뒤로가기">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m15 18-6-6 6-6"></path>
            </svg>
        </button>
        <div class="text-lg font-semibold text-zinc-900">알림</div>
        <div class="h-8 w-8" aria-hidden="true"></div>
    </div>
    <div class="p-4 ">
        <?php
        $queries = [];

        $sql_bo = "SELECT bo_table FROM g5_board";
        $res_bo = sql_query($sql_bo);

        while ($bo = sql_fetch_array($res_bo)) {

            $bo_table = $bo['bo_table'];
            $write_table = "g5_write_" . $bo_table;

            // 👉 내 글 댓글
            $queries[] = "
            SELECT 
                 a.wr_id,
                a.wr_parent,
                a.wr_content,
                a.wr_datetime,

                a.mb_id,
                a.wr_name,
                b.wr_subject,

                '{$bo_table}' as bo_table,
                'comment' as type
            FROM {$write_table} a
            JOIN {$write_table} b ON a.wr_parent = b.wr_id
            WHERE a.wr_comment > 0
            AND a.wr_seo_title = ''
            AND b.mb_id = '{$member['mb_id']}'
            AND a.wr_comment_reply = ''  
            AND a.wr_is_comment !=0
            AND a.mb_id != '{$member['mb_id']}'
            
            ";


            $queries[] = "
            SELECT 
                a.wr_id,
                a.wr_parent,
                a.wr_content,
                a.wr_datetime,

                a.mb_id,
                a.wr_name,
                b.wr_subject,
                '{$bo_table}' as bo_table,
                'reply' as type
            FROM {$write_table} a
            JOIN {$write_table} b 
                ON a.wr_parent = b.wr_id
            JOIN {$write_table} c 
                ON a.wr_parent = c.wr_parent 
                AND a.wr_comment = c.wr_comment
            WHERE a.wr_comment_reply != '' 
            AND c.mb_id = '{$member['mb_id']}'
            AND a.mb_id != '{$member['mb_id']}'

            ";
        }

        $sql2 = implode(" UNION ", $queries);
        $sql2 .= " ORDER BY wr_datetime DESC LIMIT 20";

        $result2 = sql_query($sql2);

        ?>
        <?php while ($row = sql_fetch_array($result2)) { ?>

            <?php
            $link = G5_BBS_URL . "/board.php?bo_table={$row['bo_table']}&wr_id={$row['wr_parent']}#c_" . $row['wr_id'];
            ?>

            <a href="<?= $link ?>" class="noti-item block border-t p-4 border-gray-200" data-id="<?= $link ?>">

                <div class="font-medium text-gray-900">

                    <?php if ($row['type'] == 'comment') { ?>
                        <b><?= $row['wr_name'] ?></b> 님이
                        "<b><?= $row['wr_subject'] ?></b>" 글에 댓글을 남겼습니다.
                    <?php } else { ?>
                        <b><?= $row['wr_name'] ?></b> 님이
                        내 댓글에 답글을 남겼습니다.
                    <?php } ?>
                </div>

                <div class="text-xs text-gray-500 mt-1">
                    <?= mb_substr(strip_tags($row['wr_content']), 0, 100) ?>...
                </div>

            </a>

        <?php } ?>
        <?php while ($od = sql_fetch_array($result)) { ?>
            <?php
            $od_id = $od['od_id'];

            $sql2 = "
            SELECT c.*, i.it_img1
            FROM g5_shop_cart c
            LEFT JOIN g5_shop_item i ON c.it_id = i.it_id
            WHERE c.od_id = '{$od_id}'
            ";
            $res2 = sql_query($sql2);

            // 상품명용 첫번째 상품
            $first_item = sql_fetch_array($res2);

            // 다시 처음부터 돌리기 위해 재쿼리 (중요🔥)
            $res2 = sql_query($sql2);

            // 상품 개수
            $item_count = sql_num_rows($res2);
            $noti_id = "order_" . $od['od_id']; // 주문
            ?>

            <a href="/shop/orderinquiryview.php?od_id=<?php echo $od_id ?>"
                data-id="<?= $noti_id ?>"
                class="noti-item block font-medium text-gray-900 border-gray-200 border-t p-4 ">

                <!-- 상품 이미지들 -->
                <div class="flex">
                    <div class="">

                        주문하신 상품
                        <?php if ($item_count > 1) { ?>
                            <?php echo $first_item['it_name'] ?> 외 <?php echo $item_count - 1 ?>건
                        <?php } else { ?>
                            <?php echo $first_item['it_name'] ?>
                        <?php } ?>

                        (이/가) <?php echo $od['od_status'] ?> 상태입니다
                    </div>
                </div>
                <div class="flex gap-2 mt-2">
                    <?php while ($item = sql_fetch_array($res2)) { ?>
                        <div class="w-12 h-12 overflow-hidden rounded border border-gray-200">
                            <?php echo get_it_image($item['it_id'], 48, 48); ?>
                        </div>
                    <?php } ?>
                </div>

                <!-- 텍스트 -->


            </a>

        <?php } ?>


    </div>
</section>
<script>
    if (g5_is_member) {

        const userId = "<?= $member['mb_id'] ?>"; // PHP에서 로그인한 유저 ID
        const storageKey = "readNoti_" + userId;
        const readList = JSON.parse(localStorage.getItem(storageKey) || "[]");

        document.querySelectorAll(".noti-item").forEach(el => {
            const id = el.dataset.id;

            if (readList.includes(id)) {
                el.classList.add("opacity-50"); // 읽은 표시
            }

            el.addEventListener("click", () => {
                if (!readList.includes(id)) {
                    readList.push(id);
                    localStorage.setItem(storageKey, JSON.stringify(readList));
                }
            });
        });
    }
</script>
<script>
    function goBackFromNotification() {
        var referrer = document.referrer || '';
        var isLoginReferrer = /\/bbs\/login\.php(?:\?|$)/i.test(referrer);

        if (isLoginReferrer) {
            location.href = '<?php echo G5_URL; ?>';
            return;
        }

        history.back();
    }
</script>

<?php
include_once('./_tail.php');

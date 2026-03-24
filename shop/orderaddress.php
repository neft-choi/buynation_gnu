<?php
include_once('./_common.php');

if (!$is_member)
    alert_close('회원이시라면 회원로그인 후 이용해 주십시오.');

$ad_id = isset($_REQUEST['ad_id']) ? (int) $_REQUEST['ad_id'] : 0;

if ($w == 'd') {
    $sql = " delete from {$g5['g5_shop_order_address_table']} where mb_id = '{$member['mb_id']}' and ad_id = '$ad_id' ";
    sql_query($sql);
    goto_url($_SERVER['SCRIPT_NAME']);
}

$sql_common = " from {$g5['g5_shop_order_address_table']} where mb_id = '{$member['mb_id']}' ";

$sql = " select count(ad_id) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) {
    $page = 1;
} // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
            $sql_common
            order by ad_default desc, ad_id desc
            limit $from_record, $rows";

$result = sql_query($sql);

if (!sql_num_rows($result))
    alert_close('배송지 목록 자료가 없습니다.');

$order_action_url = G5_HTTPS_SHOP_URL . '/orderaddressupdate.php';

if (G5_IS_MOBILE) {
    include_once(G5_MSHOP_PATH . '/orderaddress.php');
    return;
}

// 테마에 orderaddress.php 있으면 include
if (defined('G5_THEME_SHOP_PATH')) {
    $theme_orderaddress_file = G5_THEME_SHOP_PATH . '/orderaddress.php';
    if (is_file($theme_orderaddress_file)) {
        include_once($theme_orderaddress_file);
        return;
        unset($theme_orderaddress_file);
    }
}

$g5['title'] = '배송지 목록';
include_once(G5_PATH . '/head.sub.php');
?>

<form name="forderaddress" method="post" action="<?php echo $order_action_url; ?>" autocomplete="off">
    <div id="sod_addr" class="mx-auto w-full max-w-full bg-white p-4">
        <div class="flex items-center justify-between">
            <button type="button" class="inline-flex items-center justify-center text-zinc-700" aria-label="뒤로가기" onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '<?php echo G5_URL ?>'; }">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left-icon lucide-chevron-left">
                    <path d="m15 18-6-6 6-6" />
                </svg>
            </button>
            <h1 id="win_title_custom" class="text-base font-semibold text-zinc-900">배송지 목록</h1>
            <div class="h-8 w-8" aria-hidden="true"></div>
        </div>

        <div class="mt-4 grid gap-3">
            <?php
            $sep = chr(30);
            for ($i = 0; $row = sql_fetch_array($result); $i++) {
                $addr = $row['ad_name'] . $sep . $row['ad_tel'] . $sep . $row['ad_hp'] . $sep . $row['ad_zip1'] . $sep . $row['ad_zip2'] . $sep . $row['ad_addr1'] . $sep . $row['ad_addr2'] . $sep . $row['ad_addr3'] . $sep . $row['ad_jibeon'] . $sep . $row['ad_subject'];
                $addr = get_text($addr);
            ?>
                <div class="rounded-lg border border-gray-200 bg-white p-4 space-y-4">
                    <input type="hidden" name="ad_id[<?php echo $i; ?>]" value="<?php echo $row['ad_id']; ?>">

                    <div class="flex items-start gap-2">
                        <div>
                            <input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>" class="peer sr-only">
                            <label for="chk_<?php echo $i; ?>" class="addr-check-label inline-flex cursor-pointer items-center justify-center" aria-label="배송지선택">
                                <svg class="addr-check-off block" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="3" y="3" width="22" height="22" rx="11" fill="white" />
                                    <rect x="3.75" y="3.75" width="20.5" height="20.5" rx="10.25" stroke="#8D8D8D" stroke-opacity="0.52" stroke-width="1.5" />
                                    <path d="M18.0975 10.058C18.4337 9.74377 18.961 9.76159 19.2753 10.0977C19.5895 10.4339 19.5717 10.9611 19.2356 11.2755L12.8189 17.2755C12.4986 17.5749 12.0012 17.5749 11.6809 17.2755L8.76421 14.5482C8.42809 14.2339 8.41028 13.7067 8.7245 13.3705C9.03881 13.0344 9.56606 13.0165 9.90223 13.3308L12.2492 15.5255L18.0975 10.058Z" fill="#8D8D8D" fill-opacity="0.52" />
                                </svg>
                                <svg class="addr-check-on hidden" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="3" y="3" width="22" height="22" rx="11" fill="#393939" />
                                    <path d="M18.0975 10.058C18.4337 9.74377 18.961 9.76159 19.2753 10.0977C19.5895 10.4339 19.5717 10.9611 19.2356 11.2755L12.8189 17.2755C12.4986 17.5749 12.0012 17.5749 11.6809 17.2755L8.76421 14.5482C8.42809 14.2339 8.41028 13.7067 8.7245 13.3705C9.03881 13.0344 9.56606 13.0165 9.90223 13.3308L12.2492 15.5255L18.0975 10.058Z" fill="white" />
                                </svg>
                            </label>
                            <span class="sound_only">배송지선택</span>
                        </div>


                        <div class="flex-1 space-y-4">
                            <div>
                                <label for="ad_subject<?php echo $i; ?>" class="sound_only">배송지명</label>
                                <input type="text" name="ad_subject[<?php echo $i; ?>]" id="ad_subject<?php echo $i; ?>" class="h-12 w-full rounded border border-gray-900 px-3 text-base font-medium text-gray-900 focus:outline-none" maxlength="20" value="<?php echo get_text($row['ad_subject']); ?>">
                            </div>
                            <div class="grid gap-1">
                                <p class="text-gray-900 text-xs font-medium">이름</p>
                                <p class="text-gray-900 text-lg font-bold"><?php echo get_text($row['ad_name']); ?></p>
                            </div>

                            <div class="grid gap-1">
                                <p class="text-gray-900 text-xs font-medium">배송지정보</p>
                                <p class="text-gray-900 text-lg font-bold"><?php echo print_address($row['ad_addr1'], $row['ad_addr2'], $row['ad_addr3'], $row['ad_jibeon']); ?></p>
                            </div>

                            <?php
                            $ad_tel_plain = preg_replace('/[^0-9]/', '', (string) $row['ad_tel']);  // 전화번호
                            $ad_hp_plain  = preg_replace('/[^0-9]/', '', (string) $row['ad_hp']);  // 핸드폰번호
                            ?>

                            <div class="grid gap-1">
                                <p class="text-gray-900 text-xs font-medium">전화번호</p>
                                <p class="text-gray-900 text-lg font-bold"><?php echo $ad_tel_plain; ?> / <?php echo $ad_hp_plain ?></p>
                            </div>

                            <div class="grid grid-cols-3 gap-2">
                                <div>
                                    <input type="hidden" value="<?php echo $addr; ?>">
                                    <button type="button" class="sel_address inline-flex w-full items-center justify-center rounded-md border border-[var(--color-primary)] bg-[var(--color-primary)] px-8 py-4 text-base font-medium text-gray-900 whitespace-nowrap">선택</button>
                                </div>

                                <a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?w=d&amp;ad_id=<?php echo $row['ad_id']; ?>" class="del_address inline-flex items-center justify-center rounded-md border border-gray-900 bg-gray-900 px-8 py-4 text-base font-medium text-white whitespace-nowrap">삭제</a>

                                <div class="relative">
                                    <input type="radio" name="ad_default" value="<?php echo $row['ad_id']; ?>" id="ad_default<?php echo $i; ?>" class="peer absolute h-0 w-0 opacity-0" <?php if ($row['ad_default']) echo 'checked="checked"'; ?>>
                                    <label for="ad_default<?php echo $i; ?>" class="inline-flex w-full items-center justify-center rounded-md border border-gray-300 px-8 py-4 text-base font-medium text-gray-900 whitespace-nowrap">기본</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>

        <div class="mt-4 grid grid-cols-2 gap-2">
            <input type="submit" name="act_button" value="선택수정" class="btn_submit_custom inline-flex items-center justify-center rounded-md border border-[var(--color-primary)] bg-[var(--color-primary)] py-4 text-base font-medium text-gray-900">
            <button type="button" onclick="window.location.href='<?php echo G5_SHOP_URL; ?>/mypage.php';" class="btn_close_custom inline-flex items-center justify-center rounded-md border border-gray-900 bg-gray-900 py-4 text-base font-medium text-white">닫기</button>
        </div>
    </div>
</form>

<?php echo get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

<style>
    .peer:checked+.addr-check-label .addr-check-off {
        display: none;
    }

    .peer:checked+.addr-check-label .addr-check-on {
        display: block;
    }
</style>

<script>
    $(function() {
        $(".sel_address").on("click", function() {
            var addr = $(this).siblings("input").val().split(String.fromCharCode(30));

            var f = window.opener.forderform;
            f.od_b_name.value = addr[0];
            f.od_b_tel.value = addr[1];
            f.od_b_hp.value = addr[2];
            f.od_b_zip.value = addr[3] + addr[4];
            f.od_b_addr1.value = addr[5];
            f.od_b_addr2.value = addr[6];
            f.od_b_addr3.value = addr[7];
            f.od_b_addr_jibeon.value = addr[8];
            f.ad_subject.value = addr[9];

            var zip1 = addr[3].replace(/[^0-9]/g, "");
            var zip2 = addr[4].replace(/[^0-9]/g, "");

            if (zip1 != "" && zip2 != "") {
                var code = String(zip1) + String(zip2);

                if (window.opener.zipcode != code) {
                    window.opener.zipcode = code;
                    window.opener.calculate_sendcost(code);
                }
            }

            window.close();
        });

        $(".del_address").on("click", function() {
            return confirm("배송지 목록을 삭제하시겠습니까?");
        });

        $(".btn_submit").on("click", function() {
            if ($("input[name^='chk[']:checked").length == 0) {
                alert("수정하실 항목을 하나 이상 선택하세요.");
                return false;
            }
        });

    });
</script>

<?php
include_once(G5_PATH . '/tail.sub.php');

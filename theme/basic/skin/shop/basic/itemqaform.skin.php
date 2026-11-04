<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . G5_SHOP_SKIN_URL . '/style.css">', 0);
?>

<!-- 상품문의 쓰기 시작 { -->
<div id="sit_qa_write">
    <header class="hidden sm:flex items-center justify-between px-4 py-4">
        <h1 class="text-2xl font-bold">상품 문의</h1>
        <button type="button" onclick="window.close();" class="inline-flex h-6 w-6 shrink-0 items-center justify-center text-zinc-700" aria-label="확대창 닫기">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6" aria-hidden="true">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
            </svg>
        </button>
    </header>

    <header class="flex sm:hidden items-center justify-between p-4 border-b border-gray-300">
        <div class="h-6 w-6 shrink-0" aria-hidden="true"></div>
        <h1 class="text-lg font-semibold leading-none text-zinc-900">상품 문의</h1>
        <button type="button" onclick="window.close();" class="inline-flex h-6 w-6 shrink-0 items-center justify-center text-zinc-700" aria-label="확대창 닫기">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6" aria-hidden="true">
                <path d="M18 6 6 18" />
                <path d="m6 6 12 12" />
            </svg>
        </button>
    </header>

    <div class="flex items-center gap-4 px-4 pc:px-5 py-4 pc:py-6">
        <div class="shrink-0 border border-gray-300 rounded-lg overflow-hidden">
            <?php echo get_it_image($row['it_id'], 88, 88, false, '', get_text($row['it_name'])); ?>
        </div>
        <div class="min-w-0">
            <p class="break-words text-base font-semibold"><?php echo get_text($row['it_name']); ?></p>
            <p class="mt-2 text-base font-bold"><?php echo display_price($row['it_price'], $row['it_tel_inq']); ?></p>
        </div>
    </div>

    <form name="fitemqa" method="post" action="<?php echo G5_SHOP_URL; ?>/itemqaformupdate.php" onsubmit="return fitemqa_submit(this);" autocomplete="off">
        <input type="hidden" name="w" value="<?php echo $w; ?>">
        <input type="hidden" name="it_id" value="<?php echo $it_id; ?>">
        <input type="hidden" name="iq_id" value="<?php echo $iq_id; ?>">

        <div class="p-4">
            <ul class="text-sm [&>li]:grid-cols-[100px_minmax(0,1fr)] [&>li]:pb-4 space-y-4">
                <!--
                    <li>
                        <div class="form_left">
                            <label for="iq_email" class="sound_only">이메일</label>
                            <input type="text" name="iq_email" id="iq_email" value="<?php echo get_text($qa['iq_email']); ?>" class="frm_input full_input" size="30" placeholder="이메일"><br>
                            <span class="frm_info">이메일을 입력하시면 답변 등록 시 답변이 이메일로 전송됩니다.</span>
                        </div>
                        <div class="form_right">
                            <label for="iq_hp" class="sound_only">휴대폰</label>
                            <input type="text" name="iq_hp" id="iq_hp" value="<?php echo get_text($qa['iq_hp']); ?>" class="frm_input full_input" size="20" placeholder="휴대폰"><br>
                            <span class="frm_info">휴대폰번호를 입력하시면 답변 등록 시 답변등록 알림이 SMS로 전송됩니다.</span>
                        </div>
                    </li>
                -->

                <li class="grid items-center gap-6 border-b border-gray-200">
                    <label for="iq_type" class="font-semibold">문의 유형 <span class="text-yellow-600" aria-hidden="true">*</span></label>
                    <select name="iq_type" id="iq_type" required class="h-12 w-full min-w-0 rounded-md bg-white">
                        <option value="상품">상품</option>
                        <option value="배송">배송</option>
                        <option value="취소">취소</option>
                        <option value="교환">교환</option>
                        <option value="기타">기타</option>
                    </select>
                </li>

                <li class="grid items-center gap-6 border-b border-gray-200">
                    <label for="iq_subject" class="font-semibold">제목 <span class="text-yellow-600" aria-hidden="true">*</span></label>
                    <input type="text" name="iq_subject" value="<?php echo get_text($qa['iq_subject']); ?>" id="iq_subject" required class="required frm_input min-w-0" maxlength="250" placeholder="제목을 입력해 주세요">
                </li>

                <li class="grid items-start gap-6 border-b border-gray-200">
                    <label for="iq_question" class="pt-3 font-semibold">문의 내용 <span class="text-yellow-600" aria-hidden="true">*</span></label>
                    <div class="min-w-0">
                        <?php echo $editor_html; ?>
                        <p class="mt-3 text-sm text-gray-500">개인정보가 노출되지 않도록 주의해 주세요.</p>
                    </div>
                </li>
            </ul>

            <div class="py-5">
                <label for="iq_secret" class="inline-flex cursor-pointer items-center gap-2">
                    <input type="checkbox" name="iq_secret" id="iq_secret" value="1" <?php echo $chk_secret; ?> class="h-4 w-4 shrink-0 cursor-pointer accent-yellow-400">
                    <span>비밀글로 문의하기</span>
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="self.close();" class="h-12 w-32 cursor-pointer rounded-md border border-gray-300 bg-white font-semibold text-gray-900">취소</button>
                <button type="submit" class="h-12 w-32 cursor-pointer rounded-md bg-yellow-400 font-semibold text-gray-900">문의 등록</button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    function fitemqa_submit(f) {
        <?php echo $editor_js; ?>

        return true;
    }
</script>
<!-- } 상품문의 쓰기 끝 -->
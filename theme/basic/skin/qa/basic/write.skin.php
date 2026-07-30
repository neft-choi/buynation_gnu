<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가

include_once(G5_THEME_PATH . '/upload_form.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="' . $qa_skin_url . '/style.css">', 0);
?>

<!-- 모바일 헤더 -->
<div class="flex pc:hidden items-center justify-between h-[var(--mobile-header-height)] px-4">
    <button type="button" class="inline-flex items-center justify-center text-zinc-700" aria-label="뒤로가기"
        onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '<?php echo G5_URL ?>'; }">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-chevron-left-icon lucide-chevron-left w-6 h-6">
            <path d="m15 18-6-6 6-6" />
        </svg>
    </button>
    <h1 class="text-lg font-semibold text-zinc-900 leading-0">문의하기</h1>
    <div class="w-6 h-6" aria-hidden="true"></div>
</div>

<!-- 회원 요약 정보 (PC) -->
<?php include_once(G5_THEME_SHOP_PATH . '/_mypage_summary_pc.php'); ?>

<div class="block pc:flex gap-6 pc:px-5 pc:py-12">

    <!-- 마이페이지 메뉴 (PC) -->
    <?php
    include_once(G5_THEME_SHOP_PATH . '/_mypage_menu_pc.php');
    ?>

    <!-- 문의하기 -->
    <section id="" class="flex-1 min-w-0">

        <!-- PC 너비 타이틀 -->
        <div class="hidden pc:block px-4">
            <h2 class="text-2xl font-bold pb-4 border-b-2 border-gray-900">문의하기</h2>
        </div>

        <!-- 게시물 작성/수정 시작 { -->
        <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);"
            method="post" enctype="multipart/form-data" autocomplete="off" class="mx-auto w-full max-w-full p-4">
            <input type="hidden" name="w" value="<?php echo $w ?>">
            <input type="hidden" name="qa_id" value="<?php echo $qa_id ?>">
            <input type="hidden" name="sca" value="<?php echo $sca ?>">
            <input type="hidden" name="stx" value="<?php echo $stx ?>">
            <input type="hidden" name="page" value="<?php echo $page ?>">
            <input type="hidden" name="token" value="<?php echo $token ?>">
            <?php
            $option = '';
            $option_hidden = '';
            $option = '';

            if ($is_dhtml_editor) {
                $option_hidden .= '<input type="hidden" name="qa_html" value="1">';
            } else {
                $option .= "\n" . '<input type="checkbox" id="qa_html" name="qa_html" onclick="html_auto_br(this);" value="' . $html_value . '" ' . $html_checked . '>' . "\n" . '<label for="qa_html">html</label>';
            }

            echo $option_hidden;
            ?>

            <div class="form_01">
                <?php if ($category_option) { ?>
                    <div class="relative inline-block w-full mb-2 pc:mb-4">
                        <label for="qa_category" class="sound_only">분류<strong>필수</strong></label>
                        <select name="qa_category" id="qa_category"
                            class="appearance-none w-full border border-[var(--color-semantic-border-normal-default)] rounded pl-4 pr-10 py-2"
                            required>
                            <option value="">분류를 선택하세요</option>
                            <?php echo $category_option ?>
                        </select>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-chevron-down-icon lucide-chevron-down pointer-events-none absolute top-1/2 -translate-y-1/2 right-3 w-4 h-4">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </div>
                <?php } ?>

                <div>
                    <div class="flex flex-col pc:flex-row items-start gap-2 pc:gap-4">
                        <?php if ($is_email) { ?>
                            <div class="bo_w_mail chk_box w-full">
                                <label for="qa_email" class="sound_only">이메일</label>
                                <input type="text" name="qa_email" value="<?php echo get_text($write['qa_email']); ?>"
                                    id="qa_email" <?php echo $req_email; ?>
                                    class="<?php echo $req_email . ' '; ?>input-text w-full" maxlength="100"
                                    placeholder="이메일">
                            </div>
                        <?php } ?>

                        <?php if ($is_hp) { ?>
                            <div class="bo_w_hp chk_box w-full">
                                <label for="qa_hp" class="sound_only">휴대폰</label>
                                <input type="text" name="qa_hp" value="<?php echo get_text($write['qa_hp']); ?>" id="qa_hp"
                                    <?php echo $req_hp; ?> class="<?php echo $req_hp . ' '; ?>input-text w-full"
                                    placeholder="휴대폰">
                                <?php if ($qaconfig['qa_use_sms']) { ?>
                                    <input type="checkbox" name="qa_sms_recv" id="qa_sms_recv" value="1" <?php if ($write['qa_sms_recv'])
                                        echo 'checked="checked"'; ?> class="selec_chk">
                                    <label for="qa_sms_recv" class="frm_info"><span></span>답변 등록 시 SMS로 알림</label>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>

                    <?php if ($is_email) { ?>
                        <div class="flex items-center gap-1 pc:gap-2 mt-2 pc:mt-4">
                            <div class="input-checkbox-control">
                                <input type="checkbox" name="qa_email_recv" id="qa_email_recv" value="1" <?php if ($write['qa_email_recv'])
                                    echo 'checked="checked"'; ?> class="input-checkbox">

                                <svg viewBox="0 0 16 16" fill="none" class="input-checkbox-icon"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12.098 4.058a.833.833 0 0 1 1.138 1.217l-6.417 6c-.32.3-.818.3-1.138 0L2.764 8.548a.833.833 0 0 1 1.138-1.217L6.25 9.525z"
                                        fill="currentColor" />
                                </svg>
                            </div>
                            <label for="qa_email_recv" class="">답변 등록 시 이메일로 알림</label>
                        </div>
                    <?php } ?>
                </div>

                <div class="bo_w_sbj mt-4">
                    <label for="qa_subject" class="sound_only">제목<strong class="sound_only">필수</strong></label>
                    <input type="text" name="qa_subject" value="<?php echo get_text($write['qa_subject']); ?>"
                        id="qa_subject" required class="required input-text w-full" maxlength="255" placeholder="제목">
                </div>

                <div class="qa_content_wrap <?php echo $is_dhtml_editor ? $config['cf_editor'] : ''; ?> mt-2 pc:mt-4 mb-4">
                    <label for="qa_content" class="sound_only">내용<strong class="sound_only">필수</strong></label>
                    <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 
                    ?>
                </div>

                <?php if ($option) { ?>
                    <div class="flex gap-2 items-center">
                        옵션
                        <?php echo $option; ?>
                    </div>
                <?php } ?>

                <fieldset class="space-y-3">
                    <legend class="text-base font-medium text-gray-900">첨부 파일</legend>

                    <?php echo get_file_upload_field('bf_file[1]', 'bf_file_1'); ?>
                    
                    <?php 
                    // echo get_file_upload_field('bf_file[2]', 'bf_file_2'); 
                    ?>
                </fieldset>

                <?php echo get_file_upload_script(); ?>
            </div>

            <div class="flex items-center justify-end gap-2 mt-4">
                <a href="<?php echo $list_href; ?>"
                    class="rounded text-sm text-[var(--color-semantic-label-solid-subtler)] font-medium bg-[var(--color-semantic-fill-solid-default)] px-4 py-2">취소</a>
                <button type="submit" id="btn_submit" accesskey="s"
                    class="rounded text-sm text-[var(--color-semantic-label-solid-default)] font-medium bg-[var(--color-semantic-primary-normal)] px-4 py-2">작성
                    완료</button>
            </div>
        </form>

        <script>
            function html_auto_br(obj) {
                if (obj.checked) {
                    result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
                    if (result)
                        obj.value = "2";
                    else
                        obj.value = "1";
                } else
                    obj.value = "";
            }

            function fwrite_submit(f) {
                <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   
                ?>

                var subject = "";
                var content = "";
                $.ajax({
                    url: g5_bbs_url + "/ajax.filter.php",
                    type: "POST",
                    data: {
                        "subject": f.qa_subject.value,
                        "content": f.qa_content.value
                    },
                    dataType: "json",
                    async: false,
                    cache: false,
                    success: function (data, textStatus) {
                        subject = data.subject;
                        content = data.content;
                    }
                });

                if (subject) {
                    alert("제목에 금지단어('" + subject + "')가 포함되어있습니다");
                    f.qa_subject.focus();
                    return false;
                }

                if (content) {
                    alert("내용에 금지단어('" + content + "')가 포함되어있습니다");
                    if (typeof (ed_qa_content) != "undefined")
                        ed_qa_content.returnFalse();
                    else
                        f.qa_content.focus();
                    return false;
                }
                <?php if ($is_hp) { ?>
                    var hp = f.qa_hp.value.replace(/[0-9\-]/g, "");
                    if (hp.length > 0) {
                        alert("휴대폰번호는 숫자, - 으로만 입력해 주십시오.");
                        return false;
                    }
                <?php } ?>

                $.ajax({
                    type: "POST",
                    url: g5_bbs_url + "/ajax.write.token.php",
                    data: {
                        'token_case': 'qa_write'
                    },
                    cache: false,
                    async: false,
                    dataType: "json",
                    success: function (data) {
                        if (typeof data.token !== "undefined") {
                            token = data.token;

                            if (typeof f.token === "undefined")
                                $(f).prepend('<input type="hidden" name="token" value="">');

                            $(f).find("input[name=token]").val(token);
                        }
                    }
                });

                document.getElementById("btn_submit").disabled = "disabled";

                return true;
            }

            // 반응형 쇼핑몰 헤더 숨기기
            syncWithPcBreakpoint(function (isPc) {
                if (isPc) {
                    $('#hd').css('display', '');
                } else {
                    $('#hd').css('display', 'none');
                }
            });
        </script>
    </section>
</div>
<!-- } 게시물 작성/수정 끝 -->
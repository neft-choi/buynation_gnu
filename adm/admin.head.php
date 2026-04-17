<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

$g5_debug['php']['begin_time'] = $begin_time = get_microtime();

$files = glob(G5_ADMIN_PATH . '/css/admin_extend_*');
if (is_array($files)) {
    foreach ((array) $files as $k => $css_file) {

        $fileinfo = pathinfo($css_file);
        $ext = $fileinfo['extension'];

        if ($ext !== 'css') {
            continue;
        }

        $css_file = str_replace(G5_ADMIN_PATH, G5_ADMIN_URL, $css_file);
        add_stylesheet('<link rel="stylesheet" href="' . $css_file . '">', $k);
    }
}

require_once G5_PATH . '/head.sub.php';

function print_menu1($key, $no = '')
{
    global $menu;

    $str = print_menu2($key, $no);

    return $str;
}

function print_menu2($key, $no = '')
{
    global $menu, $auth_menu, $is_admin, $auth, $g5, $sub_menu;

    $str = "<ul>";
    for ($i = 1; $i < count($menu[$key]); $i++) {
        if (!isset($menu[$key][$i])) {
            continue;
        }

        if ($is_admin != 'super' && (!array_key_exists($menu[$key][$i][0], $auth) || !strstr($auth[$menu[$key][$i][0]], 'r'))) {
            continue;
        }

        $gnb_grp_div = $gnb_grp_style = '';

        if (isset($menu[$key][$i][4])) {
            if (($menu[$key][$i][4] == 1 && $gnb_grp_style == false) || ($menu[$key][$i][4] != 1 && $gnb_grp_style == true)) {
                $gnb_grp_div = 'gnb_grp_div';
            }

            if ($menu[$key][$i][4] == 1) {
                $gnb_grp_style = 'gnb_grp_style';
            }
        }

        $current_class = '';

        if ($menu[$key][$i][0] == $sub_menu) {
            $current_class = ' on';
        }

        $str .= '<li data-menu="' . $menu[$key][$i][0] . '" class="hover:bg-blue-100"><a href="' . $menu[$key][$i][2] . '" class="gnb_2da block px-4 py-2 text-gray-900 font-medium ' . $gnb_grp_style . ' ' . $gnb_grp_div . $current_class . '">' . $menu[$key][$i][1] . '</a></li>';

        $auth_menu[$menu[$key][$i][0]] = $menu[$key][$i][1];
    }
    $str .= "</ul>";

    return $str;
}

$adm_menu_cookie = array(
    'container' => '',
    'gnb' => '',
    'btn_gnb' => '',
);
?>

<script>
    const g5_admin_csrf_token_key = "<?php echo (function_exists('admin_csrf_token_key')) ? admin_csrf_token_key() : ''; ?>";
    let tempX = 0;
    let tempY = 0;

    function imageview(id, w, h) {

        menu(id);

        const el_id = document.getElementById(id);

        //submenu = eval(name+".style");
        const submenu = el_id.style;
        submenu.left = tempX - (w + 11);
        submenu.top = tempY - (h / 2);

        selectBoxVisible();

        if (el_id.style.display !== 'none')
            selectBoxHidden(id);
    }
</script>

<div id="to_content" class="fixed left-0 top-0 z-[100000]">
    <a href="#container"
        class="absolute left-0 top-0 block h-0 w-0 overflow-hidden text-[0] focus:h-[70px] focus:w-full focus:bg-white focus:text-center focus:text-3xl focus:font-bold focus:no-underline">본문
        바로가기</a>
</div>

<header id="hd" class="w-full max-w-full text-xs">
    <h1 class="sr-only"><?php echo $config['cf_title'] ?></h1>
    <div id="hd_top" class="fixed left-0 top-0 z-[1000] flex items-center px-4 h-[var(--admin-header-height)] w-full max-w-full border-b border-gray-200 bg-white">
        <div id="logo">
            <a href="<?php echo correct_goto_url(G5_ADMIN_URL); ?>" class="inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="23.999" viewBox="0 0 24 23.999" aria-hidden="true">
                    <path id="빼기_1" data-name="빼기 1" d="M12,40a12,12,0,1,1,3.4-23.512A6.626,6.626,0,0,0,23.512,24.6,12,12,0,0,1,12,40Zm.138-15.448a3.586,3.586,0,1,0,3.586,3.586A3.59,3.59,0,0,0,12.138,24.552Z" transform="translate(0 -16)" fill="#ffdd56" />
                </svg>
                <span class="text-black text-[20px] font-black">DONUTS ADMIN</span>
            </a>
        </div>

        <div id="tnb" class="ml-auto">
            <ul class="flex items-center gap-4">
                <?php if (defined('G5_USE_SHOP') && G5_USE_SHOP) { ?>
                    <li class="tnb_li">
                        <a href="<?php echo G5_SHOP_URL ?>/" class="tnb_shop text-gray-500" target="_blank" title="쇼핑몰 바로가기" aria-label="쇼핑몰 바로가기">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-store-icon lucide-store h-5 w-5">
                                <path d="M15 21v-5a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v5" />
                                <path d="M17.774 10.31a1.12 1.12 0 0 0-1.549 0 2.5 2.5 0 0 1-3.451 0 1.12 1.12 0 0 0-1.548 0 2.5 2.5 0 0 1-3.452 0 1.12 1.12 0 0 0-1.549 0 2.5 2.5 0 0 1-3.77-3.248l2.889-4.184A2 2 0 0 1 7 2h10a2 2 0 0 1 1.653.873l2.895 4.192a2.5 2.5 0 0 1-3.774 3.244" />
                                <path d="M4 10.95V19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8.05" />
                            </svg>
                        </a>
                    </li>
                <?php } ?>

                <li class="tnb_li">
                    <a href="<?php echo G5_URL ?>/" class="tnb_community text-gray-500" target="_blank" title="커뮤니티 바로가기" aria-label="커뮤니티 바로가기">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list-icon lucide-clipboard-list h-5 w-5">
                            <rect width="8" height="4" x="8" y="2" rx="1" ry="1" />
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2" />
                            <path d="M12 11h4" />
                            <path d="M12 16h4" />
                            <path d="M8 11h.01" />
                            <path d="M8 16h.01" />
                        </svg>
                    </a>
                </li>

                <li id="tnb_logout" class="block">
                    <a href="<?php echo G5_BBS_URL ?>/logout.php" class="flex items-center text-white bg-red-500 px-3 py-2 rounded">로그아웃</a>
                </li>
            </ul>
        </div>
    </div>

    <nav id="gnb" class="gnb_large <?php echo $adm_menu_cookie['gnb']; ?> fixed left-0 top-0 z-[999] h-full w-[var(--admin-sidebar-width)] max-w-[var(--admin-sidebar-width)] pt-[var(--admin-header-height)]">
        <h2 class="sr-only">관리자 주메뉴</h2>
        <ul class="gnb_ul relative block h-full w-[50px] bg-gray-50 border-r border-gray-200">
            <?php
            $jj = 1;
            // 메뉴 키 별 아이콘 매핑
            $menu_icon_svg_map = array(
                '100' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-icon lucide-settings h-4 w-4"><path d="M9.671 4.136a2.34 2.34 0 0 1 4.659 0 2.34 2.34 0 0 0 3.319 1.915 2.34 2.34 0 0 1 2.33 4.033 2.34 2.34 0 0 0 0 3.831 2.34 2.34 0 0 1-2.33 4.033 2.34 2.34 0 0 0-3.319 1.915 2.34 2.34 0 0 1-4.659 0 2.34 2.34 0 0 0-3.32-1.915 2.34 2.34 0 0 1-2.33-4.033 2.34 2.34 0 0 0 0-3.831A2.34 2.34 0 0 1 6.35 6.051a2.34 2.34 0 0 0 3.319-1.915"/><circle cx="12" cy="12" r="3"/></svg>',
                '200' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-icon lucide-user-round h-4 w-4"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>',
                '300' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-list-icon lucide-clipboard-list h-4 w-4"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>',
                '400' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag-icon lucide-shopping-bag h-4 w-4"><path d="M16 10a4 4 0 0 1-8 0"/><path d="M3.103 6.034h17.794"/><path d="M3.4 5.467a2 2 0 0 0-.4 1.2V20a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6.667a2 2 0 0 0-.4-1.2l-2-2.667A2 2 0 0 0 17 2H7a2 2 0 0 0-1.6.8z"/></svg>',
                '500' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chart-pie-icon lucide-chart-pie h-4 w-4"><path d="M21 12c.552 0 1.005-.449.95-.998a10 10 0 0 0-8.953-8.951c-.55-.055-.998.398-.998.95v8a1 1 0 0 0 1 1z"/><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/></svg>',
                '900' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mail-icon lucide-mail h-4 w-4"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>',
            );
            // 매핑이 없는 메뉴 키 대비 기본 아이콘
            $menu_icon_svg_default = '<svg viewBox="0 0 24 24" aria-hidden="true" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"></circle></svg>';
            foreach ($amenu as $key => $value) {
                $href1 = $href2 = '';

                if (isset($menu['menu' . $key][0][2]) && $menu['menu' . $key][0][2]) {
                    $href1 = '<a href="' . $menu['menu' . $key][0][2] . '" class="gnb_1da">';
                    $href2 = '</a>';
                } else {
                    continue;
                }

                $current_class = "";
                if (isset($sub_menu) && (substr($sub_menu, 0, 3) == substr($menu['menu' . $key][0][0], 0, 3))) {
                    $current_class = " on";
                }

                $button_title = $menu['menu' . $key][0][1];
                // 키별 아이콘 선택 (없으면 기본 아이콘 fallback)
                $menu_icon_svg = isset($menu_icon_svg_map[$key]) ? $menu_icon_svg_map[$key] : $menu_icon_svg_default;
            ?>
                <li class="gnb_li<?php echo $current_class; ?>">
                    <button type="button" class="btn_op h-[50px] w-[50px] bg-gray-50 text-gray-500 border-r border-gray-200 overflow-hidden hover:bg-blue-50 menu-<?php echo $key; ?> menu-order-<?php echo $jj; ?>"
                        title="<?php echo $button_title; ?>">
                        <span aria-hidden="true" class="inline-flex h-5 w-5 items-center justify-center">
                            <?php echo $menu_icon_svg; ?>
                        </span>
                        <span class="sound_only"><?php echo $button_title; ?></span>
                    </button>
                    <div class="gnb_oparea_wr">
                        <div class="gnb_oparea">
                            <h3 class="p-4 text-sm font-black text-gray-900"><?php echo $menu['menu' . $key][0][1]; ?></h3>
                            <?php echo print_menu1('menu' . $key, 1); ?>
                        </div>
                    </div>
                </li>
            <?php
                $jj++;
            }     //end foreach
            ?>
            <li class="gnb_li gnb_li_custom_bottom" hidden>
                <button type="button" class="btn_op menu-custom-user" title="관리자 정보">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" aria-hidden="true" class="h-5 w-5 text-gray-700">
                        <path d="M20 21a8 8 0 0 0-16 0"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span class="sound_only">관리자 정보</span>
                </button>
                <div class="gnb_oparea_wr">
                    <div class="gnb_oparea absolute left-[50px] top-0 h-full w-[170px] border-r border-gray-200 bg-white p-[20px]">
                        <h3 class="mb-[10px] text-lg font-semibold text-gray-900">관리자</h3>
                        <ul>
                            <li data-menu="custom-admin-profile">
                                <a href="<?php echo G5_ADMIN_URL; ?>/member_form.php?w=u&amp;mb_id=<?php echo $member['mb_id']; ?>" class="gnb_2da">관리자정보</a>
                            </li>
                            <li data-menu="custom-admin-logout">
                                <a href="<?php echo G5_BBS_URL; ?>/logout.php" class="gnb_2da">로그아웃</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>
            
            <li class="gnb_li gnb_li_toggle_bottom !mt-auto">
                <button type="button"
                    class="btn_op js-btn-gnb-toggle h-[50px] w-[50px] border-r border-gray-200 bg-gray-50 text-gray-500 hover:bg-blue-50"
                    title="사이드바 토글"
                    aria-label="사이드바 토글">
                    <span aria-hidden="true" class="inline-flex h-4 w-4 items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left-from-line-icon lucide-arrow-left-from-line icon-open h-4 w-4">
                            <path d="m9 6-6 6 6 6" />
                            <path d="M3 12h14" />
                            <path d="M21 19V5" />
                        </svg>

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right-from-line-icon lucide-arrow-right-from-line icon-close hidden h-4 w-4">
                            <path d="M3 5v14" />
                            <path d="M21 12H7" />
                            <path d="m15 18 6-6-6-6" />
                        </svg>
                    </span>
                    <span class="sound_only">사이드바 토글</span>
                </button>
            </li>
        </ul>
    </nav>

    <div id="gnb_overlay" aria-hidden="true"></div>

</header>
<script>
    jQuery(function($) {

        const menu_cookie_key = 'g5_admin_btn_gnb';
        const mobile_media_query = "(max-width: 768px)";
        const $gnb_toggle_buttons = $(".js-btn-gnb-toggle");
        
        // 사이드바 상태에 맞춰 토글 아이콘 전환
        function syncSidebarToggleIcon() {
            const is_sidebar_open = $("#gnb").hasClass("gnb_small");
            $(".gnb_li_toggle_bottom .icon-open").toggleClass("hidden", is_sidebar_open);
            $(".gnb_li_toggle_bottom .icon-close").toggleClass("hidden", !is_sidebar_open);
        }

        function closeMobileSidebar() {
            if (!window.matchMedia(mobile_media_query).matches) {
                return;
            }

            delete_cookie(menu_cookie_key);
            $("#container").removeClass("container-small");
            $("#gnb").removeClass("gnb_small");
            $gnb_toggle_buttons.removeClass("btn_gnb_open");
            $("#gnb_overlay").removeClass("is-active");
            syncSidebarToggleIcon();
        }

        $(".tnb_mb_btn").click(function() {
            $(".tnb_mb_area").toggle();
        });

        $gnb_toggle_buttons.on("click", function() {
            const is_open_before_toggle = $("#gnb").hasClass("gnb_small");

            try {
                if (!is_open_before_toggle) {
                    set_cookie(menu_cookie_key, 1, 60 * 60 * 24 * 365);
                } else {
                    delete_cookie(menu_cookie_key);
                }
            } catch (err) {}

            $("#container").toggleClass("container-small");
            $("#gnb").toggleClass("gnb_small");

            // 토글 버튼이 js-btn-gnb-toggle 클래스로 동작
            const is_open_after_toggle = $("#gnb").hasClass("gnb_small");
            $gnb_toggle_buttons.toggleClass("btn_gnb_open", is_open_after_toggle);
            $("#gnb_overlay").toggleClass("is-active", is_open_after_toggle);
            syncSidebarToggleIcon();
        });

        $("#gnb_overlay").on("click", function() {
            closeMobileSidebar();
        });

        if (window.matchMedia(mobile_media_query).matches && $("#gnb").hasClass("gnb_small")) {
            $("#gnb_overlay").addClass("is-active");
        }
        syncSidebarToggleIcon();

        $(window).on("resize", function() {
            if (!window.matchMedia(mobile_media_query).matches) {
                $("#gnb_overlay").removeClass("is-active");
            }
        });

        // 접힘 상태에서 아이콘 클릭시 펼치기 (on 유지)
        $(".gnb_ul li:not(.gnb_li_toggle_bottom) .btn_op").on("click", function() {
            const is_mobile_view = window.matchMedia(mobile_media_query).matches;
            const is_collapsed = !is_mobile_view && $("#gnb").hasClass("gnb_small");

            if (is_collapsed) {
                $("#container").removeClass("container-small");
                $("#gnb").removeClass("gnb_small");
                $(".js-btn-gnb-toggle").removeClass("btn_gnb_open");
                $("#gnb_overlay").removeClass("is-active");
                syncSidebarToggleIcon();
                return;
            }

            $(this).parent().addClass("on").siblings().removeClass("on");
        });

        $("#gnb").on("click", "a", function() {
            closeMobileSidebar();
        });




        // const getEditorIdsInPanel = (panel) =>
        //     Array.from(panel.querySelectorAll("textarea.smarteditoㅣ2[id]"))
        //     .map((el) => el.id)
        //     .filter((id) => id !== "");

        // const getEditorIframe = (textarea) => {
        //     let node = textarea.nextSibling;

        //     while (node && node.nodeType !== 1) {
        //         node = node.nextSibling;
        //     }

        //     if (!node || node.tagName !== "IFRAME") {
        //         return null;
        //     }

        //     return node;
        // };

        // const refreshEditorById = (editorId, attempt = 0) => {
        //     const maxRetry = 12;

        //     if (!window.oEditors || !oEditors.getById || !oEditors.getById[editorId]) {
        //         if (attempt < maxRetry) {
        //             setTimeout(() => refreshEditorById(editorId, attempt + 1), 80);
        //         }
        //         return;
        //     }

        //     const editor = oEditors.getById[editorId];
        //     if (!editor || typeof editor.exec !== "function") {
        //         return;
        //     }

        //     editor.exec("MSG_EDITING_AREA_SIZE_CHANGED", []);
        //     editor.exec("SE_FIT_IFRAME", []);
        //     editor.exec("HIDE_EDITING_AREA_COVER", []);

        //     const textarea = document.getElementById(editorId);
        //     if (!textarea) {
        //         return;
        //     }

        //     const skinIframe = getEditorIframe(textarea);
        //     if (!skinIframe) {
        //         return;
        //     }

        //     const skinIframeHeight = parseInt(skinIframe.style.height || "0", 10);
        //     if (!skinIframeHeight) {
        //         skinIframe.style.height = "300px";
        //     }

        //     let skinDoc = null;
        //     try {
        //         skinDoc = skinIframe.contentWindow.document;
        //     } catch (e) {
        //         return;
        //     }

        //     if (!skinDoc) {
        //         return;
        //     }

        //     const editingArea = skinDoc.querySelector(".husky_seditor_editing_area_container");
        //     const innerIframe = skinDoc.getElementById("se2_iframe");

        //     if (!editingArea || !innerIframe) {
        //         return;
        //     }

        //     const targetHeight = editingArea.clientHeight || 300;
        //     innerIframe.style.height = targetHeight + "px";
        // };

        // const refreshEditorsInPanel = (panelId) => {
        //     if (!panelId) {
        //         return;
        //     }

        //     const panel = document.getElementById(panelId);
        //     if (!panel || panel.hidden) {
        //         return;
        //     }

        //     const editorIds = getEditorIdsInPanel(panel);
        //     editorIds.forEach((id) => refreshEditorById(id));
        // };

        // const scheduleEditorRefresh = (panelId) => {
        //     requestAnimationFrame(() => {
        //         requestAnimationFrame(() => {
        //             refreshEditorsInPanel(panelId);
        //         });
        //     });
        // };

        // $(document).on("click", ".anchor a[data-tab-target]", function() {
        //     const panelId = this.getAttribute("data-tab-target") || "";
        //     scheduleEditorRefresh(panelId);
        // });

        // $(window).on("hashchange", function() {
        //     const panelId = window.location.hash.replace("#", "");
        //     scheduleEditorRefresh(panelId);
        // });

        // const initialPanelId = window.location.hash.replace("#", "");
        // if (initialPanelId) {
        //     scheduleEditorRefresh(initialPanelId);
        // }

    });
</script>

<div id="wrapper" class="min-h-[480px] w-full max-w-full text-xs font-medium">

    <div id="container" class="<?php echo $adm_menu_cookie['container']; ?> mt-[calc(var(--admin-header-height)+50px)] w-full max-w-full bg-gray-50 pl-[var(--admin-sidebar-width)] md:pb-0 pb-[var(--admin-bottom-nav-height)] pt-4">

        <h1 id="container_title"
            class="fixed left-0 top-[var(--admin-header-height)] z-[99] w-full max-w-full border-b border-gray-200 bg-white px-[20px] pl-[calc(var(--admin-sidebar-width)+16px)] text-sm font-black text-gray-900 h-[50px] flex items-center">
            <?php echo $g5['title'] ?>
        </h1>
        <div class="container_wr md:mx-4 mx-0 mt-0 mb-4 md:p-4 px-2 bg-white md:border border-y border-gray-200 md:rounded rounded-none">

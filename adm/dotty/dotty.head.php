<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

$g5_debug['php']['begin_time'] = $begin_time = get_microtime();

// <div id="hd_login_msg">을 여기서 생성한다
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

<div id="app-layout">
    <aside class="sidebar" id="sidebar">
        <a href="<?php echo correct_goto_url(G5_ADMIN_URL); ?>/dotty">
            <div class="logo">DONUTS<i>.</i></div>
        </a>

        <div class="community-mini"><button class="community-mini-switch" data-action="donut-switcher"><small>현재 관리 중인 도넛</small><b>🏃 러닝 메이트</b><span>가입 도트 4,360명 · 승인형 · 공개</span></button></div>
        <button class="portfolio-home-link" data-action="portfolio-home">← 전체 도넛 관리 홈</button>
        <nav class="nav"><button data-nav="dashboard" class="nav-main active"><span class="nav-icon">▦</span>대시보드</button><button data-nav="messages" class="nav-main "><span class="nav-icon">✉</span>브랜드 쪽지</button>
            <div class="nav-group"><button data-group="community" class="nav-main  "><span class="nav-icon">◉</span>커뮤니티<span class="nav-arrow">⌄</span></button></div>
            <div class="nav-group"><button data-group="shopping" class="nav-main  "><span class="nav-icon">◇</span>쇼핑<span class="nav-arrow">⌄</span></button></div>
            <div class="nav-group"><button data-group="topping" class="nav-main  "><span class="nav-icon">T</span>토핑<span class="nav-arrow">⌄</span></button></div>
            <div class="nav-group"><button data-group="business" class="nav-main  "><span class="nav-icon">₩</span>사업자·정산<span class="nav-arrow">⌄</span></button></div>
        </nav>
        <div class="sidebar-bottom">
            <div class="side-user"><span class="avatar">D</span><span><b>도티 김도윤</b><small>대표 운영자</small></span></div><button data-action="account-switch">↔ 계정 전환</button>
        </div>
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
    </aside>

    <div id="app-content" class="ml-0 pc:ml-59">
        <header id="header" class="topbar">
            <div class="header-mobile pc:hidden">
                <!-- 모바일 헤더 -->
            </div>

            <div class="header-desktop hidden pc:flex items-center justify-between w-full">
                <!-- PC 헤더 -->
                <div class="content-title">
                    <h1 class="text-xl font-semibold"><?php echo $g5['title'] ?></h1>
                    <p class="text-xs text-(--muted) mt-1"><?php echo $title_sub ?></p>
                </div>

                <div class="top-actions flex items-center gap-2">
                    <button class="relative inline-flex items-center justify-center w-10 h-10 border border-(--line) rounded-lg" data-action="notifications">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell-icon lucide-bell w-5 h-5">
                            <path d="M10.268 21a2 2 0 0 0 3.464 0" />
                            <path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326" />
                        </svg>
                        <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-4 h-4 text-[10px] text-white bg-red-500 rounded-full">5</span>
                    </button>

                    <button class="account-switch h-10 border border-(--line) rounded-lg text-xs font-bold px-4 py-2" data-action="account-switch">
                        <span>계정 전환</span>
                    </button>
                    
                    <div class="top-user text-right">
                        <p class="text-xs font-bold">도티 김도윤</p>
                        <p class="text-[10px]">러닝 메이트</p>
                    </div>
                </div>
            </div>

            <button class="mobile-menu" data-action="mobile-menu">☰</button>


        </header>

        <main id="container" class="min-h-[480px] w-full max-w-full bg-gray-50 p-4">
            <div class="<?php echo $adm_menu_cookie['container']; ?> container_wr border border-gray-200 rounded-lg bg-white">
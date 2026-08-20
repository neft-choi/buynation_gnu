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

<div id="wrapper">
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
    </aside>
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

    <div class="ml-0 pc:ml-59">
        <header class="topbar">
            <button class="mobile-menu" data-action="mobile-menu">☰</button>

            <div class="title-wrap">
                <h1 id="container_title"><?php echo $g5['title'] ?></h1>
                <p><?php echo $title_sub ?></p>
            </div>

            <div class="top-actions">
                <button class="icon-btn" data-action="notifications">♢<em>5</em></button>
                <button class="account-switch" data-action="account-switch">↔ <span>계정 전환</span></button>
                <div class="top-user"><b>도티 김도윤</b><small>러닝 메이트</small></div>
            </div>
        </header>

        <div id="container" class="min-h-[480px] w-full max-w-full bg-gray-50 p-4">
            <div class="<?php echo $adm_menu_cookie['container']; ?> container_wr border border-gray-200 rounded-lg bg-white">
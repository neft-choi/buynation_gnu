<?php
if (!defined('_GNUBOARD_')) {
    exit;
}

$g5_debug['php']['begin_time'] = $begin_time = get_microtime();

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
    <div id="hd_top" class="flex items-center px-4 h-[var(--admin-header-height)] w-full max-w-full border-b border-gray-200 bg-white">
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

    <aside class="sidebar" id="sidebar">
        <div class="logo">DONUTS<i>.</i></div>
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
</header>

<div id="wrapper" class="min-h-[480px] w-full max-w-full text-xs font-medium">
    <div id="container" class="<?php echo $adm_menu_cookie['container']; ?> w-full max-w-full bg-gray-50 pl-[var(--admin-sidebar-width)] md:pb-0 pb-[var(--admin-bottom-nav-height)] flow-root">
        <header class="topbar"><button class="mobile-menu" data-action="mobile-menu">☰</button>
            <div class="title-wrap">
                <h1 id="container_title"><?php echo $g5['title'] ?></h1>
                <p><?php echo $title_sub ?></p>
            </div>
            <div class="top-actions"><button class="icon-btn" data-action="notifications">♢<em>5</em></button><button class="account-switch" data-action="account-switch">↔ <span>계정 전환</span></button>
                <div class="top-user"><b>도티 김도윤</b><small>러닝 메이트</small></div>
            </div>
        </header>

        <div class="container_wr md:m-4 mx-0 my-4 md:p-4 px-2 bg-white md:border border-y border-gray-200 md:rounded rounded-none">
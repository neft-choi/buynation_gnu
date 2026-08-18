<?php
define('G5_IS_ADMIN', true);
require_once __DIR__ . '/../../common.php';
require_once G5_ADMIN_PATH . '/admin.lib.php';

// dotty 전용 메뉴/권한 로드
require_once __DIR__ . '/dotty.menu.php';

run_event('admin_common');

add_stylesheet('<link rel="stylesheet" href="' . G5_ADMIN_URL . '/dotty/css/dotty.css?ver=' . G5_CSS_VER . '">', 100);
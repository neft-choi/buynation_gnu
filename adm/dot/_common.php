<?php
define('G5_IS_ADMIN', true);
require_once __DIR__ . '/../../common.php';
require_once G5_ADMIN_PATH . '/admin.lib.php';

// dot 전용 메뉴
require_once __DIR__ . '/dot.menu.php';

run_event('admin_common');

add_stylesheet('<link rel="stylesheet" href="' . G5_ADMIN_URL . '/dot/css/dot.css?ver=' . G5_CSS_VER . '">', 100);
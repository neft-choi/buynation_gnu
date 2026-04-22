<?php
define('G5_IS_ADMIN', true);
require_once __DIR__ . '/../../common.php';
require_once G5_ADMIN_PATH . '/admin.lib.php';

// buygent 전용 메뉴/권한 로드
require_once __DIR__ . '/buygent.menu.php';

run_event('admin_common');
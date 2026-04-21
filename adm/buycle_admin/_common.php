<?php
define('G5_IS_ADMIN', true);
require_once __DIR__ . '/../../common.php';
require_once G5_ADMIN_PATH . '/admin.lib.php';

run_event('admin_common');
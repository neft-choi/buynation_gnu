<?php
$sub_menu = '400620';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, 'w');

if ($is_admin !== 'super') {
    alert('최고관리자만 상품재고를 수정할 수 있습니다.', './itemstocklist.php');
}

/*
 * 실제 저장 로직은 기존 영카트 파일을 그대로 사용합니다.
 * 최고관리자 여부를 먼저 검사한 뒤 기존 저장 파일을 실행합니다.
 */
include('./itemstocklistupdate.php');

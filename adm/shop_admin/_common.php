<?php
define('G5_IS_ADMIN', true);
define('G5_IS_SHOP_ADMIN_PAGE', true);
include_once ('../../common.php');

if (!defined('G5_USE_SHOP') || !G5_USE_SHOP)
    die('<p>쇼핑몰 설치 후 이용해 주십시오.</p>');

include_once(G5_ADMIN_PATH.'/admin.lib.php');
include_once('./admin.shop.lib.php');

run_event('admin_common');


// 여기에서 브랜드 관련 변수 가져오는 코드짜기
// $brand['속성']
// $brand = SELECT * FROM do_brand -> $brand['속성']
// SELECT * FROM do_brand WHERE $member['mb_id'] and $brand['brand_id']
// 브랜드 아이디를 만들때 테이블에 정보를 생성 해야된다?
// label 명이 컬럼명을 de_admin_company_saupja_no 와 같이 동일하게 사용

check_order_inicis_tmps();
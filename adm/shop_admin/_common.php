<?php
define('G5_IS_ADMIN', true);
define('G5_IS_SHOP_ADMIN_PAGE', true);
include_once ('../../common.php');

if (!defined('G5_USE_SHOP') || !G5_USE_SHOP)
    die('<p>쇼핑몰 설치 후 이용해 주십시오.</p>');

include_once(G5_ADMIN_PATH.'/admin.lib.php');
include_once('./admin.shop.lib.php');

run_event('admin_common');

/*
 * 쇼핑몰 관리자 접근 제한
 *
 * - 최고관리자(super)는 donuts_brand 등록 여부와 관계없이 접근 허용
 * - 그 외 계정은 donuts_brand.brand_id = 로그인 mb_id 인 경우만 접근 허용
 * - g5_auth에 쇼핑몰 권한이 잘못 부여되어 있어도 브랜드 테이블에 없으면 차단
 */
if ($is_admin !== 'super') {
    $shop_admin_mb_id = isset($member['mb_id'])
        ? trim((string)$member['mb_id'])
        : '';

    if ($shop_admin_mb_id === '') {
        alert('로그인이 필요합니다.', G5_URL);
    }

    $shop_admin_mb_id_sql = sql_real_escape_string($shop_admin_mb_id);

    $shop_admin_brand = sql_fetch("
        SELECT brand_id
        FROM donuts_brand
        WHERE LOWER(TRIM(brand_id)) = LOWER('{$shop_admin_mb_id_sql}')
        LIMIT 1
    ", false);

    if (empty($shop_admin_brand['brand_id'])) {
        alert(
            '등록된 브랜드 계정만 쇼핑몰 관리에 접근할 수 있습니다.',
            G5_URL
        );
    }

    // shop_admin 하위 페이지에서 공통으로 사용할 수 있는 현재 브랜드 정보
    $shop_admin_brand_id = trim((string)$shop_admin_brand['brand_id']);
}


// 여기에서 브랜드 관련 변수 가져오는 코드짜기
// $brand['속성']
// $brand = SELECT * FROM do_brand -> $brand['속성']
// SELECT * FROM do_brand WHERE $member['mb_id'] and $brand['brand_id']
// 브랜드 아이디를 만들때 테이블에 정보를 생성 해야된다?
// label 명이 컬럼명을 de_admin_company_saupja_no 와 같이 동일하게 사용

check_order_inicis_tmps();
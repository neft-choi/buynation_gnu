<?php
$sub_menu = '400100';
include_once('./_common.php');

check_demo();

auth_check_menu($auth, $sub_menu, "w");

check_admin_token();


// ------------------------------------------------------------
// 브랜드별 쇼핑몰 설정 저장
// 최고관리자는 아래 기존 영카트 저장 로직을 그대로 사용합니다.
// ------------------------------------------------------------
$is_brand_config = false;
$brand_id = '';

if ($is_admin !== 'super') {
    $login_brand_id = sql_real_escape_string($member['mb_id']);
    $brand_chk = sql_fetch(" SELECT brand_id FROM donuts_brand WHERE brand_id = '{$login_brand_id}' LIMIT 1 ");
    if (!empty($brand_chk['brand_id'])) {
        $is_brand_config = true;
        $brand_id = $brand_chk['brand_id'];
    }
}

if ($is_brand_config) {
    // 브랜드 설정 테이블 자동 생성
    sql_query(" CREATE TABLE IF NOT EXISTS `donuts_brand_config` (
        `id` int NOT NULL AUTO_INCREMENT,
        `brand_id` varchar(20) NOT NULL,
        `brand_name` varchar(255) NOT NULL DEFAULT '',
        `brand_commission_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
        `business_license` varchar(255) NOT NULL DEFAULT '',
        `settings_json` longtext NOT NULL,
        `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uk_brand_id` (`brand_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ", false);

    $brand_id_sql = sql_real_escape_string($brand_id);
    $old_brand_config = sql_fetch(" SELECT * FROM donuts_brand_config WHERE brand_id = '{$brand_id_sql}' LIMIT 1 ");

    $brand_name = isset($_POST['brand_name']) ? trim($_POST['brand_name']) : '';
    $brand_name = clean_xss_tags($brand_name, 1, 1);
    $brand_commission_rate = isset($_POST['brand_commission_rate']) ? (float)$_POST['brand_commission_rate'] : 0;
    if ($brand_commission_rate < 0) $brand_commission_rate = 0;
    if ($brand_commission_rate > 100) $brand_commission_rate = 100;

    // 체크되지 않은 체크박스도 브랜드 설정에서는 0으로 저장해야
    // 최고관리자 기본값이 다시 나타나는 현상을 막을 수 있습니다.
    $brand_checkbox_fields = array(
        'cf_use_point',
        'de_type1_list_use','de_type2_list_use','de_type3_list_use','de_type4_list_use','de_type5_list_use',
        'de_mobile_type1_list_use','de_mobile_type2_list_use','de_mobile_type3_list_use','de_mobile_type4_list_use','de_mobile_type5_list_use',
        'de_rel_list_use','de_mobile_rel_list_use',
        'de_samsung_pay_use','de_inicis_lpay_use','de_inicis_kakaopay_use','de_inicis_cartpoint_use',
        'de_guest_cart_use','de_member_reg_coupon_use','de_tax_flag_use',
        'de_sms_use1','de_sms_use2','de_sms_use3','de_sms_use4','de_sms_use5'
    );

    $settings = $_POST;
    unset($settings['token'], $settings['brand_name'], $settings['brand_commission_rate']);
    unset($settings['business_license_del'], $settings['logo_img_del'], $settings['logo_img_del2'], $settings['mobile_logo_img_del'], $settings['mobile_logo_img_del2']);

    foreach ($brand_checkbox_fields as $field) {
        if (!isset($settings[$field])) {
            $settings[$field] = '0';
        }
    }

    // 간편결제 체크박스 배열은 화면에서 사용하는 문자열 값도 같이 보관
    if (!isset($settings['de_easy_pays']) || !is_array($settings['de_easy_pays'])) {
        $settings['de_easy_pays'] = array();
    }
    $easy_pays = array();
    foreach ($settings['de_easy_pays'] as $v) {
        $easy_pays[] = preg_replace('/[^0-9a-z_\-]/i', '', $v);
    }
    $settings['de_easy_pays'] = $easy_pays;
    $settings['de_easy_pay_services'] = implode(',', $easy_pays);

    // 현금영수증 발급수단
    $taxsave_types = array('account');
    if (!empty($_POST['de_taxsave_types_vbank'])) $taxsave_types[] = 'vbank';
    if (!empty($_POST['de_taxsave_types_transfer'])) $taxsave_types[] = 'transfer';
    $settings['de_taxsave_types'] = implode(',', $taxsave_types);

    // 설정값 재귀 정리
    $clean_brand_value = function ($value) use (&$clean_brand_value) {
        if (is_array($value)) {
            $out = array();
            foreach ($value as $k => $v) {
                $out[$k] = $clean_brand_value($v);
            }
            return $out;
        }
        if (is_string($value)) {
            return trim($value);
        }
        return $value;
    };
    $settings = $clean_brand_value($settings);

    // 브랜드별 업로드 폴더
    $brand_dir = G5_DATA_PATH . '/brand_config/' . $brand_id;
    if (!is_dir(G5_DATA_PATH . '/brand_config')) {
        @mkdir(G5_DATA_PATH . '/brand_config', G5_DIR_PERMISSION, true);
        @chmod(G5_DATA_PATH . '/brand_config', G5_DIR_PERMISSION);
    }
    if (!is_dir($brand_dir)) {
        @mkdir($brand_dir, G5_DIR_PERMISSION, true);
        @chmod($brand_dir, G5_DIR_PERMISSION);
    }

    // 안전한 이미지 업로드 함수
    $save_brand_image = function ($file_key, $save_name) use ($brand_dir) {
        if (!isset($_FILES[$file_key]) || !is_uploaded_file($_FILES[$file_key]['tmp_name'])) {
            return '';
        }

        $image_info = @getimagesize($_FILES[$file_key]['tmp_name']);
        if (!$image_info || !in_array($image_info[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP))) {
            alert('이미지 파일만 업로드할 수 있습니다.');
        }

        $dest = $brand_dir . '/' . $save_name;
        if (!move_uploaded_file($_FILES[$file_key]['tmp_name'], $dest)) {
            alert('이미지 업로드에 실패했습니다. data/brand_config 폴더 권한을 확인해 주세요.');
        }
        @chmod($dest, G5_FILE_PERMISSION);
        return $save_name;
    };

    // 사업자등록증
    $business_license = !empty($old_brand_config['business_license']) ? $old_brand_config['business_license'] : '';
    if (!empty($_POST['business_license_del']) && $business_license) {
        @unlink($brand_dir . '/' . $business_license);
        $business_license = '';
    }
    if (isset($_FILES['business_license']) && is_uploaded_file($_FILES['business_license']['tmp_name'])) {
        $ext = strtolower(pathinfo($_FILES['business_license']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, array('jpg','jpeg','png','gif','webp'))) $ext = 'jpg';
        $new_name = 'business_license.' . $ext;
        $saved = $save_brand_image('business_license', $new_name);
        if ($saved) $business_license = $saved;
    }

    // 로고 삭제/업로드
    $logo_map = array(
        'logo_img' => 'logo_img',
        'logo_img2' => 'logo_img2',
        'mobile_logo_img' => 'mobile_logo_img',
        'mobile_logo_img2' => 'mobile_logo_img2'
    );
    $delete_map = array(
        'logo_img' => 'logo_img_del',
        'logo_img2' => 'logo_img_del2',
        'mobile_logo_img' => 'mobile_logo_img_del',
        'mobile_logo_img2' => 'mobile_logo_img_del2'
    );

    foreach ($logo_map as $file_key => $save_name) {
        if (!empty($_POST[$delete_map[$file_key]])) {
            @unlink($brand_dir . '/' . $save_name);
        }
        if (isset($_FILES[$file_key]) && is_uploaded_file($_FILES[$file_key]['tmp_name'])) {
            $save_brand_image($file_key, $save_name);
        }
    }

    $json = json_encode($settings, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        alert('브랜드 설정 데이터를 저장하는 중 JSON 변환 오류가 발생했습니다.');
    }

    $brand_name_sql = sql_real_escape_string($brand_name);
    $business_license_sql = sql_real_escape_string($business_license);
    $json_sql = sql_real_escape_string($json);

    $sql = " INSERT INTO donuts_brand_config
                SET brand_id = '{$brand_id_sql}',
                    brand_name = '{$brand_name_sql}',
                    brand_commission_rate = '{$brand_commission_rate}',
                    business_license = '{$business_license_sql}',
                    settings_json = '{$json_sql}',
                    created_at = '" . G5_TIME_YMDHIS . "',
                    updated_at = '" . G5_TIME_YMDHIS . "'
             ON DUPLICATE KEY UPDATE
                    brand_name = VALUES(brand_name),
                    brand_commission_rate = VALUES(brand_commission_rate),
                    business_license = VALUES(business_license),
                    settings_json = VALUES(settings_json),
                    updated_at = VALUES(updated_at) ";

    $save_result = sql_query($sql, false);
    if (!$save_result) {
        alert('브랜드 쇼핑몰 설정 저장에 실패했습니다.');
    }

    run_event('shop_admin_brand_configformupdate', $brand_id, $settings);
    alert('브랜드 쇼핑몰 설정이 저장되었습니다.', './configform.php');
}

// 대표전화번호 유효성 체크
if(! (isset($_POST['de_admin_company_tel']) && check_vaild_callback($_POST['de_admin_company_tel'])) )
    alert('대표전화번호를 올바르게 입력해 주세요.');

// 로그인을 바로 이 주소로 하는 경우 쇼핑몰설정값이 사라지는 현상을 방지
if (!$_POST['de_admin_company_owner']) goto_url("./configform.php");

if (! empty($_POST['logo_img_del']))  @unlink(G5_DATA_PATH."/common/logo_img");
if (! empty($_POST['logo_img_del2']))  @unlink(G5_DATA_PATH."/common/logo_img2");
if (! empty($_POST['mobile_logo_img_del']))  @unlink(G5_DATA_PATH."/common/mobile_logo_img");
if (! empty($_POST['mobile_logo_img_del2']))  @unlink(G5_DATA_PATH."/common/mobile_logo_img2");

if ($_FILES['logo_img']['name']) upload_file($_FILES['logo_img']['tmp_name'], "logo_img", G5_DATA_PATH."/common");
if ($_FILES['logo_img2']['name']) upload_file($_FILES['logo_img2']['tmp_name'], "logo_img2", G5_DATA_PATH."/common");
if ($_FILES['mobile_logo_img']['name']) upload_file($_FILES['mobile_logo_img']['tmp_name'], "mobile_logo_img", G5_DATA_PATH."/common");
if ($_FILES['mobile_logo_img2']['name']) upload_file($_FILES['mobile_logo_img2']['tmp_name'], "mobile_logo_img2", G5_DATA_PATH."/common");

$de_kcp_mid = isset($_POST['de_kcp_mid']) ? substr($_POST['de_kcp_mid'], 0, 3) : '';
$cf_icode_server_port = isset($cf_icode_server_port) ? preg_replace('/[^0-9]/', '', $cf_icode_server_port) : '7295';

$de_shop_skin = isset($_POST['de_shop_skin']) ? preg_replace(array('#\.+(\/|\\\)#', '#[\'\"]#'), array('', ''), $_POST['de_shop_skin']) : 'basic';
$de_shop_mobile_skin = isset($_POST['de_shop_mobile_skin']) ? preg_replace(array('#\.+(\/|\\\)#', '#[\'\"]#'), array('', ''), $_POST['de_shop_mobile_skin']) : 'basic';

$skins = get_skin_dir('shop');

if(defined('G5_THEME_PATH') && $config['cf_theme']) {
    $dirs = get_skin_dir('shop', G5_THEME_PATH.'/'.G5_SKIN_DIR);
    if(!empty($dirs)) {
        foreach($dirs as $dir) {
            $skins[] = 'theme/'.$dir;
        }
    }
}

$mobile_skins = get_skin_dir('shop', G5_MOBILE_PATH.'/'.G5_SKIN_DIR);

if(defined('G5_THEME_PATH') && $config['cf_theme']) {
    $dirs = get_skin_dir('shop', G5_THEME_MOBILE_PATH.'/'.G5_SKIN_DIR);
    if(!empty($dirs)) {
        foreach($dirs as $dir) {
            $mobile_skins[] = 'theme/'.$dir;
        }
    }
}

$de_shop_skin = in_array($de_shop_skin, $skins) ? $de_shop_skin : 'basic';
$de_shop_mobile_skin = in_array($de_shop_mobile_skin, $mobile_skins) ? $de_shop_mobile_skin : 'basic';

$check_skin_keys = array('de_type1_list_skin', 'de_type2_list_skin', 'de_type3_list_skin', 'de_type4_list_skin', 'de_type5_list_skin', 'de_mobile_type1_list_skin', 'de_mobile_type2_list_skin', 'de_mobile_type3_list_skin', 'de_mobile_type4_list_skin', 'de_mobile_type5_list_skin', 'de_rel_list_skin', 'de_mobile_rel_list_skin', 'de_search_list_skin', 'de_mobile_search_list_skin', 'de_listtype_list_skin', 'de_mobile_listtype_list_skin');

foreach($check_skin_keys as $key){
    $$key = $_POST[$key] = isset($_POST[$key]) ? preg_replace(array('#\.+(\/|\\\)#', '#[\'\"]#'), array('', ''), strip_tags($_POST[$key])) : '';

    if( isset($_POST[$key]) && preg_match('#\.+(\/|\\\)#', $_POST[$key]) ){
        alert('스킨설정에 유효하지 문자가 포함되어 있습니다.');
    }
    
    $$key = $_POST[$key] = sql_real_escape_string($_POST[$key]);
}

// 현금영수증 발급수단
$de_taxsave_types = 'account';	// 무통장

if(isset($_POST['de_taxsave_types_vbank']) && $_POST['de_taxsave_types_vbank']){	//가상계좌
	$de_taxsave_types .= ',vbank';
}
if(isset($_POST['de_taxsave_types_transfer']) && $_POST['de_taxsave_types_transfer']){		//실시간계좌이체
	$de_taxsave_types .= ',transfer';
}

// NHN_KCP 간편결제 체크
$de_easy_pay_services = '';
if(isset($_POST['de_easy_pays'])){
    $tmps = array();
    foreach( (array) $_POST['de_easy_pays'] as $v ){
        $tmps[] = preg_replace('/[^0-9a-z_\-]/i', '', $v);
    }
    $de_easy_pay_services = implode(",", $tmps);
}

//KVE-2019-0689, KVE-2019-0691, KVE-2019-0694
$check_sanitize_keys = array(
'de_admin_company_name',        //회사명
'de_admin_company_saupja_no',   //사업자등록번호
'de_admin_company_owner',       //대표자명
'de_admin_company_tel',         //대표전화번호
'de_admin_company_fax',         //팩스번호
'de_admin_tongsin_no',          //통신판매업 신고번호
'de_admin_buga_no',             //부가통신 사업자번호
'de_admin_company_zip',         //사업자우편번호
'de_admin_company_addr',        //사업장주소
'de_admin_info_name',           //정보관리책임자명
'de_admin_info_email',          //정보책임자e-mail
'de_type1_list_mod',            //히트상품출력 이미지수
'de_type1_list_row',            //히트상품출력 줄수
'de_type1_img_width',           //히트상품출력 이미지 폭
'de_type1_img_height',          //히트상품출력 이미지 높이
'de_type2_list_mod',            //추천상품출력 이미지 수
'de_type2_list_row',            //추천상품출력 줄수
'de_type2_img_width',           //추천상품출력 이미지 폭
'de_type2_img_height',          //추천상품출력 이미지 높이
'de_type3_list_mod',            //최신상품출력 이미지 수
'de_type3_list_row',            //최신상품출력 줄수
'de_type3_img_width',           //최신상품출력 이미지 폭
'de_type3_img_height',          //최신상품출력 이미지 높이
'de_type4_list_mod',            //인기상품출력 이미지 수
'de_type4_list_row',            //인기상품출력 줄수
'de_type4_img_width',           //인기상품출력 이미지 폭
'de_type4_img_height',          //인기상품출력 이미지 높이
'de_type5_list_mod',            //할인상품출력 이미지 수
'de_type5_list_row',            //할인상품출력 줄수
'de_type5_img_width',           //할인상품출력 이미지 폭
'de_type5_img_height',          //할인상품출력 이미지 높이
'de_mobile_type1_list_mod',     //모바일 히트상품출력 이미지수
'de_mobile_type1_list_row',     //모바일 히트상품출력 줄수
'de_mobile_type1_img_width',    //모바일 히트상품출력 이미지 폭
'de_mobile_type1_img_height',   //모바일 히트상품출력 이미지 높이
'de_mobile_type2_list_mod',     //모바일 추천상품출력 이미지수
'de_mobile_type2_list_row',     //모바일 추천상품출력 줄수
'de_mobile_type2_img_width',    //모바일 추천상품출력 이미지 폭
'de_mobile_type2_img_height',   //모바일 추천상품출력 이미지 높이
'de_mobile_type3_list_mod',     //모바일 최신상품출력 이미지수
'de_mobile_type3_list_row',     //모바일 최신상품출력 줄수
'de_mobile_type3_img_width',    //모바일 최신상품출력 이미지 폭
'de_mobile_type3_img_height',   //모바일 최신상품출력 이미지 높이
'de_mobile_type4_list_mod',     //모바일 인기상품출력 이미지수
'de_mobile_type4_list_row',     //모바일 인기상품출력 줄수
'de_mobile_type4_img_width',    //모바일 인기상품출력 이미지 폭
'de_mobile_type4_img_height',   //모바일 인기상품출력 이미지 높이
'de_mobile_type5_list_mod',     //모바일 할인상품출력 이미지수
'de_mobile_type5_list_row',     //모바일 할인상품출력 줄수
'de_mobile_type5_img_width',    //모바일 할인상품출력 이미지 폭
'de_mobile_type5_img_height',   //모바일 할인상품출력 이미지 높이
'de_bank_use',                  //무통장입금사용
'de_bank_account',              //은행계좌번호
'de_iche_use',                  //계좌이체 결제사용
'de_vbank_use',                 //가상계좌 결제사용
'de_hp_use',                    //휴대폰결제 결제사용
'de_card_use',                  //신용카드 결제사용
'de_card_noint_use',            //신용카드 무이자할부사용
'de_easy_pay_use',              //PG사 간편결제 버튼 사용
'de_taxsave_use',               //현금영수증 발급사용
'cf_use_point',                 //포인트 사용
'de_settle_min_point',          //결제 최소포인트
'de_settle_max_point',          //최대 결제포인트
'de_settle_point_unit',         //결제 포인트단위
'de_card_point',                //포인트부여
'de_point_days',                //주문완료 포인트
'de_pg_service',                //결제대행사
'de_kcp_mid',                   //KCP SITE CODE
'de_kcp_site_key',              //NHN KCP SITE KEY
'cf_lg_mid',                    //LG유플러스 상점아이디
'cf_lg_mert_key',               //LG유플러스 MERT KEY
'cf_toss_client_key',           //토스페이먼츠 MERT KEY
'cf_toss_secret_key',           //토스페이먼츠 MERT KEY
'de_inicis_mid',                //KG이니시스 상점아이디
'de_inicis_iniapi_key',         //KG이니시스 INIAPI KEY
'de_inicis_iniapi_iv',          //KG이니시스 INIAPI IV
'de_inicis_sign_key',           //KG이니시스 웹결제 사인키
'de_samsung_pay_use',           //KG이니시스 삼성페이 사용
'de_inicis_lpay_use',           //KG이니시스 Lpay 사용
'de_inicis_kakaopay_use',       //KG이니시스 카카오페이 사용
'de_inicis_cartpoint_use',      //KG이니시스 신용카드 포인트 결제
'de_nicepay_mid',               //NICEPAY 상점아이디
'de_nicepay_key',               //NICEPAY 상점키
'de_kakaopay_mid',              //카카오페이 상점MID
'de_kakaopay_key',              //카카오페이 상점키
'de_kakaopay_enckey',           //카카오페이 상점 EncKey
'de_kakaopay_hashkey',          //카카오페이 상점 HashKey
'de_kakaopay_cancelpwd',        //카카오페이 결제취소 비밀번호
'de_naverpay_mid',              //네이버페이 가맹점 아이디
'de_naverpay_cert_key',         //네이버페이 가맹점 인증키
'de_naverpay_button_key',       //네이버페이 버튼 인증키
'de_naverpay_test',             //네이버페이 결제테스트
'de_naverpay_mb_id',            //네이버페이 결제테스트 아이디
'de_naverpay_sendcost',         //네이버페이 추가배송비 안내
'de_escrow_use',                //에스크로 사용
'de_card_test',                 //결제 테스트
'de_tax_flag_use',              //복합과세 결제
'de_delivery_company',          //배송업체
'de_send_cost_case',            //배송비유형
'de_send_cost_limit',           //배송비상한가
'de_send_cost_list',            //배송비
'de_hope_date_use',             //희망배송일사용
'de_hope_date_after',           //희망배송일지정
'de_rel_img_width',             //관련상품출력 이미지폭
'de_rel_img_height',            //관련상품출력 이미지높이
'de_rel_list_mod',              //관련상품출력 1줄당 이미지 수
'de_rel_list_use',              //관련상품출력 출력여부
'de_mobile_rel_img_width',      //모바일 관련상품출력 이미지폭
'de_mobile_rel_img_height',     //모바일 관련상품출력 이미지높이
'de_mobile_rel_list_mod',       //모바일 관련상품출력 1줄당 이미지 수
'de_mobile_rel_list_use',       //모바일 관련상품출력 출력여부
'de_search_img_width',          //검색상품출력 이미지폭
'de_search_img_height',         //검색상품출력 이미지높이
'de_search_list_mod',           //검색상품출력 1줄당 이미지 수
'de_search_list_row',           //검색상품출력 출력할 줄 수
'de_mobile_search_img_width',   //모바일 검색상품출력 이미지폭
'de_mobile_search_img_height',  //모바일 검색상품출력 이미지높이
'de_mobile_search_list_mod',    //모바일 검색상품출력 1줄당 이미지 수
'de_mobile_search_list_row',    //모바일 검색상품출력 출력할 줄 수
'de_listtype_img_width',        //유형별 상품리스트 이미지폭
'de_listtype_list_mod',         //유형별 상품리스트 1줄당 이미지 수
'de_listtype_list_row',         //유형별 상품리스트 출력할 줄 수
'de_mobile_listtype_img_width', //모바일 유형별 상품리스트 이미지폭
'de_mobile_listtype_img_height',//모바일 유형별 상품리스트 이미지높이
'de_mobile_listtype_list_mod',  //모바일 유형별 상품리스트 1줄당 이미지 수
'de_mobile_listtype_list_row',  //모바일 유형별 상품리스트 출력할 줄 수
'de_simg_width',                //이미지(소) 폭
'de_simg_height',               //이미지(소) 높이
'de_mimg_width',                //이미지(중) 폭
'de_mimg_height',               //이미지(중) 높이
'de_item_use_write',            //사용후기 작성
'de_item_use_use',              //사용후기
'de_level_sell',                //상품구입 권한
'de_code_dup_use',              //코드 중복검사
'de_cart_keep_term',            //장바구니 보관기간
'de_guest_cart_use',            //비회원 장바구니
'de_member_reg_coupon_use',     //신규회원 쿠폰발행 여부
'de_member_reg_coupon_price',   //신규회원 쿠폰발행 쿠폰할인금액
'de_member_reg_coupon_minimum', //주문최소금액
'de_member_reg_coupon_term',    //쿠폰유효기간
'cf_sms_use',                   //SMS 사용
'cf_sms_type',                  //SMS 전송유형
'de_sms_hp',                    //관리자 휴대폰번호
'cf_icode_id',                  //아이코드 회원아이디
'cf_icode_pw',                  //아이코드 비밀번호
'de_sms_use1',                  //SMS 회원가입시 고객님께 발송
'de_sms_use2',                  //SMS 주문시 고객님께 발송
'de_sms_use3',                  //SMS 주문시 주문시 관리자에게 발송
'de_sms_use4',                  //SMS 입금확인시 고객님께 발송
'de_sms_use5',                  //SMS 상품배송시 고객님께 발송
'cf_icode_server_ip',           // 아이코드 ip
'cf_icode_server_port',         // 아이코드 port
'cf_icode_token_key',           // 아이코드 토큰키 (JSON버전)
);

foreach( $check_sanitize_keys as $key ){
    if( in_array($key, array('de_bank_account')) ){
        $$key = isset($_POST[$key]) ? clean_xss_tags($_POST[$key], 1, 1, 0, 0) : '';
    } else {
        $$key = isset($_POST[$key]) ? clean_xss_tags($_POST[$key], 1, 1) : '';
    }
}

$warning_msg = '';

// kcp 전자결제를 사용할 때 site key 입력체크
if($de_pg_service == 'kcp' && ! $de_card_test && ($de_iche_use || $de_vbank_use || $de_hp_use || $de_card_use)) {
    if(! trim($de_kcp_site_key))
        alert('NHN KCP SITE KEY를 입력해 주십시오.');
}

if( $de_kakaopay_enckey && ($de_pg_service === 'inicis' || $de_inicis_lpay_use || $de_inicis_kakaopay_use) ){
    
    $warning_msg = 'KG 이니시스 결제 또는 L.pay 또는 KG이니시스 카카오페이를 사용시 결제모듈 중복문제로 카카오페이를 활성화 할수 없습니다. \\n\\n카카오페이 사용을 비활성화 합니다.';
    $de_kakaopay_enckey = '';
}

//
// 영카트 default
//
$sql = " update {$g5['g5_shop_default_table']}
            set de_admin_company_owner        = '{$de_admin_company_owner}',
                de_admin_company_name         = '{$de_admin_company_name}',
                de_admin_company_saupja_no    = '{$de_admin_company_saupja_no}',
                de_admin_company_tel          = '{$de_admin_company_tel}',
                de_admin_company_fax          = '{$de_admin_company_fax}',
                de_admin_tongsin_no           = '{$de_admin_tongsin_no}',
                de_admin_company_zip          = '{$de_admin_company_zip}',
                de_admin_company_addr         = '{$de_admin_company_addr}',
                de_admin_info_name            = '{$de_admin_info_name}',
                de_admin_info_email           = '{$de_admin_info_email}',
                de_shop_skin                  = '{$de_shop_skin}',
                de_shop_mobile_skin           = '{$de_shop_mobile_skin}',
                de_type1_list_use             = '{$_POST['de_type1_list_use']}',
                de_type1_list_skin            = '{$_POST['de_type1_list_skin']}',
                de_type1_list_mod             = '{$de_type1_list_mod}',
                de_type1_list_row             = '{$de_type1_list_row}',
                de_type1_img_width            = '{$de_type1_img_width}',
                de_type1_img_height           = '{$de_type1_img_height}',
                de_type2_list_use             = '{$de_type2_list_use}',
                de_type2_list_skin            = '{$de_type2_list_skin}',
                de_type2_list_mod             = '{$de_type2_list_mod}',
                de_type2_list_row             = '{$de_type2_list_row}',
                de_type2_img_width            = '{$de_type2_img_width}',
                de_type2_img_height           = '{$de_type2_img_height}',
                de_type3_list_use             = '{$de_type3_list_use}',
                de_type3_list_skin            = '{$de_type3_list_skin}',
                de_type3_list_mod             = '{$de_type3_list_mod}',
                de_type3_list_row             = '{$de_type3_list_row}',
                de_type3_img_width            = '{$de_type3_img_width}',
                de_type3_img_height           = '{$de_type3_img_height}',
                de_type4_list_use             = '{$de_type4_list_use}',
                de_type4_list_skin            = '{$de_type4_list_skin}',
                de_type4_list_mod             = '{$de_type4_list_mod}',
                de_type4_list_row             = '{$de_type4_list_row}',
                de_type4_img_width            = '{$de_type4_img_width}',
                de_type4_img_height           = '{$de_type4_img_height}',
                de_type5_list_use             = '{$de_type5_list_use}',
                de_type5_list_skin            = '{$de_type5_list_skin}',
                de_type5_list_mod             = '{$de_type5_list_mod}',
                de_type5_list_row             = '{$de_type5_list_row}',
                de_type5_img_width            = '{$de_type5_img_width}',
                de_type5_img_height           = '{$de_type5_img_height}',
                de_mobile_type1_list_use      = '{$de_mobile_type1_list_use}',
                de_mobile_type1_list_skin     = '{$de_mobile_type1_list_skin}',
                de_mobile_type1_list_mod      = '{$de_mobile_type1_list_mod}',
                de_mobile_type1_list_row      = '{$de_mobile_type1_list_row}',
                de_mobile_type1_img_width     = '{$de_mobile_type1_img_width}',
                de_mobile_type1_img_height    = '{$de_mobile_type1_img_height}',
                de_mobile_type2_list_use      = '{$de_mobile_type2_list_use}',
                de_mobile_type2_list_skin     = '{$de_mobile_type2_list_skin}',
                de_mobile_type2_list_mod      = '{$de_mobile_type2_list_mod}',
                de_mobile_type2_list_row      = '{$de_mobile_type2_list_row}',
                de_mobile_type2_img_width     = '{$de_mobile_type2_img_width}',
                de_mobile_type2_img_height    = '{$de_mobile_type2_img_height}',
                de_mobile_type3_list_use      = '{$de_mobile_type3_list_use}',
                de_mobile_type3_list_skin     = '{$de_mobile_type3_list_skin}',
                de_mobile_type3_list_mod      = '{$de_mobile_type3_list_mod}',
                de_mobile_type3_list_row      = '{$de_mobile_type3_list_row}',
                de_mobile_type3_img_width     = '{$de_mobile_type3_img_width}',
                de_mobile_type3_img_height    = '{$de_mobile_type3_img_height}',
                de_mobile_type4_list_use      = '{$de_mobile_type4_list_use}',
                de_mobile_type4_list_skin     = '{$de_mobile_type4_list_skin}',
                de_mobile_type4_list_mod      = '{$de_mobile_type4_list_mod}',
                de_mobile_type4_list_row      = '{$de_mobile_type4_list_row}',
                de_mobile_type4_img_width     = '{$de_mobile_type4_img_width}',
                de_mobile_type4_img_height    = '{$de_mobile_type4_img_height}',
                de_mobile_type5_list_use      = '{$de_mobile_type5_list_use}',
                de_mobile_type5_list_skin     = '{$de_mobile_type5_list_skin}',
                de_mobile_type5_list_mod      = '{$de_mobile_type5_list_mod}',
                de_mobile_type5_list_row      = '{$de_mobile_type5_list_row}',
                de_mobile_type5_img_width     = '{$de_mobile_type5_img_width}',
                de_mobile_type5_img_height    = '{$de_mobile_type5_img_height}',
                de_rel_list_use               = '{$de_rel_list_use}',
                de_rel_list_skin              = '{$_POST['de_rel_list_skin']}',
                de_rel_list_mod               = '{$de_rel_list_mod}',
                de_rel_img_width              = '{$de_rel_img_width}',
                de_rel_img_height             = '{$de_rel_img_height}',
                de_mobile_rel_list_use        = '{$de_mobile_rel_list_use}',
                de_mobile_rel_list_skin       = '{$_POST['de_mobile_rel_list_skin']}',
                de_mobile_rel_list_mod        = '{$de_mobile_rel_list_mod}',
                de_mobile_rel_img_width       = '{$de_mobile_rel_img_width}',
                de_mobile_rel_img_height      = '{$de_mobile_rel_img_height}',
                de_search_list_skin           = '{$_POST['de_search_list_skin']}',
                de_search_list_mod            = '{$de_search_list_mod}',
                de_search_list_row            = '{$de_search_list_row}',
                de_search_img_width           = '{$de_search_img_width}',
                de_search_img_height          = '{$de_search_img_height}',
                de_mobile_search_list_skin    = '{$_POST['de_mobile_search_list_skin']}',
                de_mobile_search_list_mod     = '{$de_mobile_search_list_mod}',
                de_mobile_search_list_row     = '{$de_mobile_search_list_row}',
                de_mobile_search_img_width    = '{$de_mobile_search_img_width}',
                de_mobile_search_img_height   = '{$de_mobile_search_img_height}',
                de_listtype_list_skin         = '{$_POST['de_listtype_list_skin']}',
                de_listtype_list_mod          = '{$de_listtype_list_mod}',
                de_listtype_list_row          = '{$de_listtype_list_row}',
                de_listtype_img_width         = '{$de_listtype_img_width}',
                de_listtype_img_height        = '{$_POST['de_listtype_img_height']}',
                de_mobile_listtype_list_skin  = '{$_POST['de_mobile_listtype_list_skin']}',
                de_mobile_listtype_list_mod   = '{$de_mobile_listtype_list_mod}',
                de_mobile_listtype_list_row   = '{$de_mobile_listtype_list_row}',
                de_mobile_listtype_img_width  = '{$de_mobile_listtype_img_width}',
                de_mobile_listtype_img_height = '{$de_mobile_listtype_img_height}',
                de_bank_use                   = '{$de_bank_use}',
                de_bank_account               = '{$de_bank_account}',
                de_card_test                  = '{$de_card_test}',
                de_card_use                   = '{$de_card_use}',
                de_easy_pay_use               = '{$de_easy_pay_use}',
                de_easy_pay_services          = '{$de_easy_pay_services}',
                de_samsung_pay_use            = '{$de_samsung_pay_use}',
                de_inicis_lpay_use            = '{$de_inicis_lpay_use}',
                de_inicis_kakaopay_use        = '{$de_inicis_kakaopay_use}',
                de_inicis_cartpoint_use       = '{$de_inicis_cartpoint_use}',
                de_nicepay_mid                = '{$de_nicepay_mid}',
                de_nicepay_key                = '{$de_nicepay_key}',
                de_card_noint_use             = '{$de_card_noint_use}',
                de_card_point                 = '{$de_card_point}',
                de_settle_min_point           = '{$de_settle_min_point}',
                de_settle_max_point           = '{$de_settle_max_point}',
                de_settle_point_unit          = '{$de_settle_point_unit}',
                de_level_sell                 = '{$de_level_sell}',
                de_delivery_company           = '{$de_delivery_company}',
                de_send_cost_case             = '{$de_send_cost_case}',
                de_send_cost_limit            = '{$de_send_cost_limit}',
                de_send_cost_list             = '{$de_send_cost_list}',
                de_hope_date_use              = '{$de_hope_date_use}',
                de_hope_date_after            = '{$de_hope_date_after}',
                de_baesong_content            = '{$_POST['de_baesong_content']}',
                de_change_content             = '{$_POST['de_change_content']}',
                de_point_days                 = '{$de_point_days}',
                de_simg_width                 = '{$de_simg_width}',
                de_simg_height                = '{$de_simg_height}',
                de_mimg_width                 = '{$de_mimg_width}',
                de_mimg_height                = '{$de_mimg_height}',
                de_pg_service                 = '{$de_pg_service}',
                de_kcp_mid                    = '{$de_kcp_mid}',
                de_kcp_site_key               = '{$de_kcp_site_key}',
                de_inicis_mid                 = '{$de_inicis_mid}',
                de_inicis_iniapi_key          = '{$de_inicis_iniapi_key}',
                de_inicis_iniapi_iv           = '{$de_inicis_iniapi_iv}',
                de_inicis_sign_key            = '{$de_inicis_sign_key}',
                de_iche_use                   = '{$de_iche_use}',
                de_sms_cont1                  = '{$_POST['de_sms_cont1']}',
                de_sms_cont2                  = '{$_POST['de_sms_cont2']}',
                de_sms_cont3                  = '{$_POST['de_sms_cont3']}',
                de_sms_cont4                  = '{$_POST['de_sms_cont4']}',
                de_sms_cont5                  = '{$_POST['de_sms_cont5']}',
                de_sms_use1                   = '{$de_sms_use1}',
                de_sms_use2                   = '{$de_sms_use2}',
                de_sms_use3                   = '{$de_sms_use3}',
                de_sms_use4                   = '{$de_sms_use4}',
                de_sms_use5                   = '{$de_sms_use5}',
                de_sms_hp                     = '{$de_sms_hp}',
                de_item_use_use               = '{$de_item_use_use}',
                de_item_use_write             = '{$de_item_use_write}',
                de_code_dup_use               = '{$de_code_dup_use}',
                de_cart_keep_term             = '{$de_cart_keep_term}',
                de_guest_cart_use             = '{$de_guest_cart_use}',
                de_admin_buga_no              = '{$de_admin_buga_no}',
                de_vbank_use                  = '{$de_vbank_use}',
                de_taxsave_use                = '{$de_taxsave_use}',
				de_taxsave_types              = '{$de_taxsave_types}',
                de_guest_privacy              = '{$_POST['de_guest_privacy']}',
                de_hp_use                     = '{$de_hp_use}',
                de_escrow_use                 = '{$de_escrow_use}',
                de_tax_flag_use               = '{$de_tax_flag_use}',
                de_kakaopay_mid               = '{$de_kakaopay_mid}',
                de_kakaopay_key               = '{$de_kakaopay_key}',
                de_kakaopay_enckey            = '{$de_kakaopay_enckey}',
                de_kakaopay_hashkey           = '{$de_kakaopay_hashkey}',
                de_kakaopay_cancelpwd         = '{$de_kakaopay_cancelpwd}',
                de_member_reg_coupon_use      = '{$de_member_reg_coupon_use}',
                de_member_reg_coupon_term     = '{$de_member_reg_coupon_term}',
                de_member_reg_coupon_price    = '{$de_member_reg_coupon_price}',
                de_member_reg_coupon_minimum  = '{$de_member_reg_coupon_minimum}'
                ";
if (defined('G5_SHOP_DIRECT_NAVERPAY') && G5_SHOP_DIRECT_NAVERPAY) {
    $sql .= "  ,de_naverpay_mid               = '{$de_naverpay_mid}',
                de_naverpay_cert_key          = '{$de_naverpay_cert_key}',
                de_naverpay_button_key        = '{$de_naverpay_button_key}',
                de_naverpay_test              = '{$de_naverpay_test}',
                de_naverpay_mb_id             = '{$de_naverpay_mb_id}',
                de_naverpay_sendcost          = '{$de_naverpay_sendcost}' ";
}
sql_query($sql);

// 환경설정 > 포인트 사용
sql_query(" update {$g5['config_table']} set cf_use_point = '{$cf_use_point}' ");

// LG, 아이코드 설정
$sql = " update {$g5['config_table']}
            set cf_sms_use              = '{$cf_sms_use}',
                cf_sms_type             = '{$cf_sms_type}',
                cf_icode_id             = '{$cf_icode_id}',
                cf_icode_pw             = '{$cf_icode_pw}',
                cf_icode_server_ip      = '{$_POST['cf_icode_server_ip']}',
                cf_icode_server_port    = '{$_POST['cf_icode_server_port']}',
                cf_icode_token_key      = '{$cf_icode_token_key}',
                cf_lg_mid               = '{$cf_lg_mid}',
                cf_lg_mert_key          = '{$cf_lg_mert_key}',
                cf_toss_client_key      = '{$cf_toss_client_key}',
                cf_toss_secret_key      = '{$cf_toss_secret_key}' ";
sql_query($sql);

run_event('shop_admin_configformupdate');

if( $warning_msg ){
    alert($warning_msg, "./configform.php");
} else {
    goto_url("./configform.php");
}
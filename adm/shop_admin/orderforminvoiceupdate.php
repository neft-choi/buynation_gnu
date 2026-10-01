<?php
$sub_menu = '400400';
include_once('./_common.php');

/*
 * ============================================================
 * 상품 등록/수정 HTTP Request Body 디버그 로그
 * ============================================================
 *
 * 저장 위치:
 *   G5_DATA_PATH . /log/itemform_request_YYYYMMDD.txt
 *
 * multipart/form-data(이미지 업로드 포함)는 PHP 환경에 따라 php://input이
 * 비어 있을 수 있으므로 $_POST와 $_FILES를 함께 기록합니다.
 *
 * 디버깅이 끝나면 ORDERFORM_REQUEST_DEBUG를 false로 변경해 주세요.
 */
if (!defined('ORDERFORM_REQUEST_DEBUG')) {
    define('ORDERFORM_REQUEST_DEBUG', true);
}

if (ORDERFORM_REQUEST_DEBUG) {
    $itemform_log_dir = G5_DATA_PATH . '/log';

    if (!is_dir($itemform_log_dir)) {
        @mkdir($itemform_log_dir, G5_DIR_PERMISSION, true);
    }

    $itemform_log_file = $itemform_log_dir . '/orderform_request_' . date('Ymd') . '.txt';

    $itemform_content_type = isset($_SERVER['CONTENT_TYPE'])
        ? (string)$_SERVER['CONTENT_TYPE']
        : '';

    $itemform_raw_body = '';

    /*
     * multipart/form-data는 파일 바이너리까지 포함되어 로그가 지나치게 커질 수 있고
     * PHP 설정에 따라 php://input이 비어 있을 수 있습니다.
     * 일반 POST/JSON 요청일 때만 raw body를 추가로 기록합니다.
     */
    if (stripos($itemform_content_type, 'multipart/form-data') === false) {
        $itemform_raw_body = file_get_contents('php://input');

        if ($itemform_raw_body === false) {
            $itemform_raw_body = '';
        }
    }

    $itemform_file_log = array();

    foreach ($_FILES as $field => $file_info) {
        $itemform_file_log[$field] = $file_info;
    }

    $itemform_log_data = array(
        'time' => date('Y-m-d H:i:s'),
        'method' => isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '',
        'request_uri' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '',
        'content_type' => $itemform_content_type,
        'content_length' => isset($_SERVER['CONTENT_LENGTH']) ? $_SERVER['CONTENT_LENGTH'] : '',
        'remote_addr' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
        'member_id' => isset($member['mb_id']) ? $member['mb_id'] : '',
        'raw_body' => $itemform_raw_body,
        'post' => $_POST,
        'files' => $itemform_file_log,
    );

    $itemform_log_text  = PHP_EOL;
    $itemform_log_text .= str_repeat('=', 100) . PHP_EOL;
    $itemform_log_text .= '[ITEMFORM REQUEST] ' . $itemform_log_data['time'] . PHP_EOL;
    $itemform_log_text .= str_repeat('=', 100) . PHP_EOL;
    $itemform_log_text .= 'METHOD         : ' . $itemform_log_data['method'] . PHP_EOL;
    $itemform_log_text .= 'REQUEST_URI    : ' . $itemform_log_data['request_uri'] . PHP_EOL;
    $itemform_log_text .= 'CONTENT_TYPE   : ' . $itemform_log_data['content_type'] . PHP_EOL;
    $itemform_log_text .= 'CONTENT_LENGTH : ' . $itemform_log_data['content_length'] . PHP_EOL;
    $itemform_log_text .= 'REMOTE_ADDR    : ' . $itemform_log_data['remote_addr'] . PHP_EOL;
    $itemform_log_text .= 'MEMBER_ID      : ' . $itemform_log_data['member_id'] . PHP_EOL;
    $itemform_log_text .= PHP_EOL . '[RAW BODY]' . PHP_EOL;

    if (stripos($itemform_content_type, 'multipart/form-data') !== false) {
        $itemform_log_text .= '[multipart/form-data: raw binary body 생략 - POST/FILES 항목 참조]' . PHP_EOL;
    } elseif ($itemform_log_data['raw_body'] !== '') {
        $itemform_log_text .= $itemform_log_data['raw_body'] . PHP_EOL;
    } else {
        $itemform_log_text .= '[empty]' . PHP_EOL;
    }

    $itemform_log_text .= PHP_EOL . '[POST]' . PHP_EOL;
    $itemform_log_text .= print_r($itemform_log_data['post'], true);

    $itemform_log_text .= PHP_EOL . '[FILES]' . PHP_EOL;
    $itemform_log_text .= print_r($itemform_log_data['files'], true);
    $itemform_log_text .= PHP_EOL;

    /*
     * 동시에 여러 요청이 들어와도 로그가 섞이지 않도록 LOCK_EX 사용.
     */
    @file_put_contents(
        $itemform_log_file,
        $itemform_log_text,
        FILE_APPEND | LOCK_EX
    );
}
auth_check_menu($auth, $sub_menu, "w");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') alert('올바른 방법으로 이용해 주십시오.', './orderlist.php');
$od_id = isset($_POST['od_id']) ? safe_replace_regex($_POST['od_id'], 'od_id') : '';
if (!$od_id) alert('주문번호가 없습니다.', './orderlist.php');

if (!sql_query(" SELECT ct_delivery_company FROM {$g5['g5_shop_cart_table']} LIMIT 1 ", false)) {
    sql_query("ALTER TABLE `{$g5['g5_shop_cart_table']}` ADD `ct_delivery_company` VARCHAR(100) NOT NULL DEFAULT '' AFTER `ct_status`, ADD `ct_invoice` VARCHAR(100) NOT NULL DEFAULT '' AFTER `ct_delivery_company`, ADD `ct_invoice_time` DATETIME NULL DEFAULT NULL AFTER `ct_invoice`", true);
}

$ct_ids = isset($_POST['ct_id']) && is_array($_POST['ct_id']) ? $_POST['ct_id'] : array();
$companies = isset($_POST['ct_delivery_company']) && is_array($_POST['ct_delivery_company']) ? $_POST['ct_delivery_company'] : array();
$invoices = isset($_POST['ct_invoice']) && is_array($_POST['ct_invoice']) ? $_POST['ct_invoice'] : array();
$member_sql = sql_real_escape_string(trim((string)$member['mb_id']));
$brand = sql_fetch("SELECT brand_id FROM donuts_brand WHERE LOWER(TRIM(brand_id))=LOWER('{$member_sql}') LIMIT 1");
$is_brand = !empty($brand['brand_id']);
$od_sql = sql_real_escape_string($od_id);
$updated=0;

foreach ($ct_ids as $idx=>$v) {
    $ct_id=(int)$v; if ($ct_id<1) continue;
    $company=isset($companies[$idx])?trim(clean_xss_tags($companies[$idx],1,1)):'';
    $invoice=isset($invoices[$idx])?trim(clean_xss_tags($invoices[$idx],1,1)):'';
    $company=function_exists('mb_substr')?mb_substr($company,0,100,'UTF-8'):substr($company,0,100);
    $invoice=function_exists('mb_substr')?mb_substr($invoice,0,100,'UTF-8'):substr($invoice,0,100);
    $row=sql_fetch("SELECT c.ct_id,c.it_id,c.ct_invoice,i.it_brand FROM {$g5['g5_shop_cart_table']} c LEFT JOIN {$g5['g5_shop_item_table']} i ON c.it_id=i.it_id WHERE c.ct_id='{$ct_id}' AND c.od_id='{$od_sql}' LIMIT 1");
    if (empty($row['ct_id'])) continue;
    if ($is_brand && strcasecmp(trim((string)$row['it_brand']),trim((string)$member['mb_id']))!==0) continue;
    $company_sql=sql_real_escape_string($company); $invoice_sql=sql_real_escape_string($invoice);
    if ($invoice==='') $time='NULL';
    elseif ((string)$row['ct_invoice']!==$invoice) $time="'".G5_TIME_YMDHIS."'";
    else $time='ct_invoice_time';
    if (sql_query("UPDATE {$g5['g5_shop_cart_table']} SET ct_delivery_company='{$company_sql}',ct_invoice='{$invoice_sql}',ct_invoice_time={$time} WHERE ct_id='{$ct_id}' AND od_id='{$od_sql}'",false)) $updated++;
}
$q='od_id='.urlencode($od_id);
foreach(array('sort1','sort2','sel_field','search','page') as $k) if(isset($_POST[$k])&&$_POST[$k]!=='') $q.='&'.$k.'='.urlencode($_POST[$k]);
alert($updated.'개의 상품 운송장 정보를 저장했습니다.','./orderform.php?'.$q);

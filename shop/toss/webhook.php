<?php
include_once('./_common.php');
require_once(G5_SHOP_PATH.'/toss/toss.inc.php');

header('Content-Type: application/json; charset=utf-8');

function toss_webhook_finish($httpCode, $message)
{
    http_response_code($httpCode);
    echo json_encode(array('message' => $message), JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    toss_webhook_finish(405, 'Method Not Allowed');
}

$payload = json_decode(file_get_contents('php://input'), true);
if (!is_array($payload)) {
    toss_webhook_finish(400, 'Invalid JSON');
}

$eventType = isset($payload['eventType']) ? $payload['eventType'] : '';
$eventData = isset($payload['data']) && is_array($payload['data']) ? $payload['data'] : $payload;
$orderId = isset($eventData['orderId']) ? preg_replace('/[^A-Za-z0-9_-]/', '', $eventData['orderId']) : '';

if ($eventType !== 'PAYMENT_STATUS_CHANGED' || !$orderId) {
    // 이 모듈은 범용 결제 상태 이벤트만 처리한다.
    toss_webhook_finish(200, 'Ignored');
}

$toss = new TossPayments(
    $config['cf_toss_client_key'],
    $config['cf_toss_secret_key'],
    $config['cf_lg_mid']
);
$toss->setPaymentHeader();

// 웹훅 본문을 그대로 신뢰하지 않고 토스 API로 결제 상태를 다시 조회한다.
if (!$toss->getPaymentByOrderId($orderId)) {
    toss_webhook_finish(503, 'Payment verification failed');
}

$payment = $toss->responseData;
$status = isset($payment['status']) ? $payment['status'] : '';
$method = isset($payment['method']) ? $payment['method'] : '';
$paymentKey = isset($payment['paymentKey']) ? $payment['paymentKey'] : '';
$totalAmount = isset($payment['totalAmount']) ? (int) $payment['totalAmount'] : 0;

if ($status !== 'DONE' || $method !== '가상계좌') {
    toss_webhook_finish(200, 'No order update required');
}

sql_query('START TRANSACTION');
$od = sql_fetch(" select * from {$g5['g5_shop_order_table']} where od_id = '$orderId' for update ");

if (!$od || $od['od_pg'] !== 'toss' || $od['od_settle_case'] !== '가상계좌') {
    sql_query('ROLLBACK');
    toss_webhook_finish(200, 'Order not found or not a Toss virtual account order');
}

$expectedAmount = (int) $od['od_cart_price']
    + (int) $od['od_send_cost']
    + (int) $od['od_send_cost2']
    - (int) $od['od_cart_coupon']
    - (int) $od['od_coupon']
    - (int) $od['od_send_coupon']
    - (int) $od['od_receipt_point'];

if ($expectedAmount !== $totalAmount || !hash_equals((string) $od['od_tno'], (string) $paymentKey)) {
    sql_query('ROLLBACK');
    toss_webhook_finish(409, 'Payment amount or key mismatch');
}

if ($od['od_status'] === '입금' && (int) $od['od_misu'] === 0) {
    sql_query('COMMIT');
    toss_webhook_finish(200, 'Already processed');
}

$approvedAt = isset($payment['approvedAt']) ? date('Y-m-d H:i:s', strtotime($payment['approvedAt'])) : G5_TIME_YMDHIS;
$sql = " update {$g5['g5_shop_order_table']}
            set od_status = '입금',
                od_receipt_price = '$totalAmount',
                od_receipt_time = '$approvedAt',
                od_misu = '0'
          where od_id = '$orderId' ";
if (!sql_query($sql, false)) {
    sql_query('ROLLBACK');
    toss_webhook_finish(500, 'Order update failed');
}

$sql = " update {$g5['g5_shop_cart_table']}
            set ct_status = '입금'
          where od_id = '$orderId'
            and ct_status = '주문' ";
if (!sql_query($sql, false)) {
    sql_query('ROLLBACK');
    toss_webhook_finish(500, 'Cart update failed');
}

sql_query('COMMIT');
toss_webhook_finish(200, 'OK');


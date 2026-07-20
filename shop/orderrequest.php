<?php
include_once('./_common.php');

// orderrequest.sub.php 으로 직접 실행을 막기 위한 상수
define("_ORDERREQUEST_", true);

// $is_member는 현재 페이지에 들어온 사람이 회원인지 확인
// 회원이 아니면 로그인 후 다시 orderrequest.php 로 돌아오게 만든다
if (!$is_member) {
    goto_url(G5_BBS_URL . '/login.php?url=' . urlencode(G5_SHOP_URL . '/orderrequest.php'));
}

// $g5['g5_shop_order_table'] 는 영카트 주문 테이블 이름
// o 는 주문 테이블에 붙인 별칭 (o.mb_id, o.id_id, o.od_status 같이 사용)
// 주문한 회원 아이디 o.mb_id 가 현재 로그인한 회원의 아이디 $member['mb_id'] 와 일치하는 지 확인
$sql_common = " from {$g5['g5_shop_order_table']} o
                 where o.mb_id = '{$member['mb_id']}' ";

// exists () 는 괄호 안의 조회 결과가 하나라도 존재하는지 검사하는 SQL 문법
// $g5['g5_shop_cart_table'] 는 영카트 주문상품 테이블 이름이고 c 라는 별칭을 붙임
// c.od_id = o.od_id 를 통해 현재 주문 o에 속한 주문상품 c만 검사
// 그리고 현재 주문에 속한 상품 중 상태가 취소 또는 반품인 상품만 찾는다
$sql_common .= " and exists (
                    select 1
                      from {$g5['g5_shop_cart_table']} c
                     where c.od_id = o.od_id
                       and c.ct_status in ('취소', '반품')
                ) ";

// 취소 또는 반품 상품이 포함된 주문의 전체 개수
// 조건에 맞는 전체 주문 개수를 세야 한 페이지에 보여줄 주문 수를 정할 수 있음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);

// 조건에 맞는 전체 주문 갯수
$total_count = (int) $row['cnt'];

// 한 페이지에 표시할 주문 카드 개수
$rows = 10;

// 총 페이지 수 계산
// ceil() 은 소수점 올림 함수
$total_page = ceil($total_count / $rows);

// 페이지 값이 없거나 0 이하라면 첫 페이지를 사용
// common.php 에서 URL의 page 값을 이미 읽고 있으므로 $_GET['page'] 는 필요하지 않음
if ($page < 1) {
    $page = 1;
}

// 현재 페이지에서 조회를 시작할 주문 위치
// 예를 들어 2페이지면 $rows가 10일 때 11번째 주문부터 보여준다 (0이 첫번째 주문이므로)
$from_record = ($page - 1) * $rows;

// 현재 페이지에서 조회할 주문 범위
// limit 건너뛰기, 가져오기
$limit = " limit {$from_record}, {$rows} ";

$g5['title'] = '취소/교환/반품 내역';
include_once('./_head.php');
?>

<!-- 회원 요약 정보 (PC) -->
<?php include_once(G5_THEME_SHOP_PATH . '/_mypage_summary_pc.php'); ?>

<!-- 취소/교환/반품 내역 -->
<div class="block pc:flex gap-6 pc:px-5 pc:py-12">

    <!-- 마이페이지 메뉴 (PC) -->
    <?php include_once(G5_THEME_SHOP_PATH . '/_mypage_menu_pc.php'); ?>

    <!-- 주문 요청 내역 -->
    <?php include './orderrequest.sub.php'; ?>

</div>

<?php
include_once('./_tail.php');
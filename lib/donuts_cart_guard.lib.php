<?php
if (!defined('_GNUBOARD_')) exit;
function donuts_cart_guard_table() {
 sql_query("CREATE TABLE IF NOT EXISTS donuts_cart_guard (od_id varchar(60) NOT NULL, ct_id bigint NOT NULL, snapshot longtext NOT NULL, created_at datetime NOT NULL, PRIMARY KEY (od_id,ct_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",false);
}
function donuts_cart_guard_state($od_id) {
 global $g5;
 $od=sql_real_escape_string($od_id);$out=array();
 $q=sql_query("SELECT c.*, i.it_name AS live_name, i.it_price AS live_price, i.it_seller AS seller, i.it_use AS live_use, i.it_stock_qty AS live_stock, i.it_sc_type AS live_sc_type, i.it_sc_method AS live_sc_method, i.it_sc_price AS live_sc_price, i.it_sc_minimum AS live_sc_minimum, i.it_sc_qty AS live_sc_qty FROM {$g5['g5_shop_cart_table']} c LEFT JOIN {$g5['g5_shop_item_table']} i ON i.it_id=c.it_id WHERE c.od_id='{$od}' AND c.ct_status='쇼핑' ORDER BY c.ct_id",false);
 if (!$q) return $out;
 while($r=sql_fetch_array($q)) {
  $seller=trim((string)$r['seller']);$se=sql_real_escape_string($seller);
  $it=sql_real_escape_string($r['it_id']);$io=sql_real_escape_string($r['io_id']);
  $opt=array();
  if ($io!=='') $opt=sql_fetch("SELECT io_price,io_use,io_stock_qty FROM {$g5['g5_shop_item_option_table']} WHERE it_id='{$it}' AND io_id='{$io}' AND io_type='".(int)$r['io_type']."' LIMIT 1",false);
  $shipping=array();
  foreach(array(
   'donuts_delivery_conditions'=>"SELECT dc_id,dc_name,dc_type,dc_price,dc_minimum,dc_qty,dc_jeju_use,dc_jeju_price,dc_island_use,dc_island_price,is_default,use_yn FROM donuts_delivery_conditions WHERE LOWER(TRIM(brand_id))=LOWER('{$se}') ORDER BY dc_id",
   'donuts_delivery_product_settings'=>"SELECT condition_id,group_id FROM donuts_delivery_product_settings WHERE it_id='{$it}' AND LOWER(TRIM(brand_id))=LOWER('{$se}') ORDER BY dps_id",
   'donuts_delivery_groups'=>"SELECT dg_id,calc_method,use_yn FROM donuts_delivery_groups WHERE LOWER(TRIM(brand_id))=LOWER('{$se}') ORDER BY dg_id",
   'donuts_delivery_condition_ranges'=>"SELECT r.dc_id,r.min_amount,r.max_amount,r.dr_price,r.sort_order FROM donuts_delivery_condition_ranges r INNER JOIN donuts_delivery_conditions d ON d.dc_id=r.dc_id WHERE LOWER(TRIM(d.brand_id))=LOWER('{$se}') ORDER BY r.dc_id,r.sort_order,r.dr_id",
   'donuts_brand_sendcost'=>"SELECT sc_zip1,sc_zip2,sc_price FROM donuts_brand_sendcost WHERE LOWER(TRIM(brand_id))=LOWER('{$se}') ORDER BY sc_zip1,sc_zip2"
  ) as $key=>$sql) {
   $shipping[$key]=array();$res=sql_query($sql,false);
   if ($res) while($sr=sql_fetch_array($res)) {
    foreach($sr as $k=>$v) if (is_int($k)) unset($sr[$k]);
    $shipping[$key][]=$sr;
   }
  }
  $out[(string)$r['ct_id']]=array(
   'name'=>(string)$r['live_name'],'seller'=>strtolower($seller),
   'price'=>(string)$r['live_price'],'use'=>(string)$r['live_use'],
   'stock'=>(string)$r['live_stock'],'qty'=>(string)$r['ct_qty'],
   'option'=>(string)$r['io_id'],'option_type'=>(string)$r['io_type'],
   'option_price'=>isset($opt['io_price'])?(string)$opt['io_price']:'',
   'option_use'=>isset($opt['io_use'])?(string)$opt['io_use']:'',
   'shipping'=>hash('sha256',json_encode(array($r['live_sc_type'],$r['live_sc_method'],$r['live_sc_price'],$r['live_sc_minimum'],$r['live_sc_qty'],$shipping),JSON_UNESCAPED_UNICODE))
  );
 }
 return $out;
}
function donuts_cart_guard_capture($od_id,$overwrite=false) {
 donuts_cart_guard_table();$od=sql_real_escape_string($od_id);
 foreach(donuts_cart_guard_state($od_id) as $ct=>$state) {
  $json=sql_real_escape_string(json_encode($state,JSON_UNESCAPED_UNICODE));
  $verb=$overwrite?'REPLACE':'INSERT IGNORE';
  sql_query("{$verb} INTO donuts_cart_guard (od_id,ct_id,snapshot,created_at) VALUES ('{$od}',".(int)$ct.",'{$json}',NOW())",false);
 }
}
function donuts_cart_guard_check($od_id,$selected_only=false) {
 global $g5;
 donuts_cart_guard_table();$od=sql_real_escape_string($od_id);
 $now=donuts_cart_guard_state($od_id);$errors=array();
 $q=sql_query("SELECT g.ct_id,g.snapshot,c.ct_select,c.it_name FROM donuts_cart_guard g INNER JOIN {$g5['g5_shop_cart_table']} c ON c.ct_id=g.ct_id AND c.od_id=g.od_id WHERE g.od_id='{$od}' AND c.ct_status='쇼핑'",false);
 $found=0;
 if($q) while($r=sql_fetch_array($q)) {
  if($selected_only && (int)$r['ct_select']!==1) continue;
  $found++;
  $ct=(string)$r['ct_id'];$old=json_decode($r['snapshot'],true);
  if (!isset($now[$ct]) || !is_array($old)) { $errors[]='장바구니 상품 정보 확인 실패';continue; }
  $diff=array();
  foreach(array('name'=>'상품명','seller'=>'판매자','price'=>'판매가격','use'=>'판매상태','stock'=>'재고','qty'=>'수량','option'=>'옵션','option_type'=>'옵션 종류','option_price'=>'옵션 가격','option_use'=>'옵션 판매상태','shipping'=>'배송비 및 배송정책') as $k=>$label) {
   if ((string)($old[$k]??'')!==(string)($now[$ct][$k]??'')) $diff[]=$label;
  }
  if($diff) $errors[]=$r['it_name'].': '.implode(', ',$diff).' 변경';
 }
 if($selected_only && !$found) return '주문할 상품 정보가 없습니다.';
 if($errors) return "장바구니에 담은 이후 상품 정보가 변경되었습니다.\n".implode("\n",array_slice($errors,0,8))."\n장바구니를 다시 확인해 주세요.";
 return '';
}

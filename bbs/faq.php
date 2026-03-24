<?php
include_once('./_common.php');

//dbconfig파일에 $g5['faq_table'] , $g5['faq_master_table'] 배열변수가 있는지 체크
if( !isset($g5['faq_table']) || !isset($g5['faq_master_table']) ){
    die('<meta charset="utf-8">관리자 모드에서 게시판관리->FAQ관리를 먼저 확인해 주세요.');
}

// FAQ MASTER
$faq_master_list = array();
$sql = " select * from {$g5['faq_master_table']} order by fm_order,fm_id ";
$result = sql_query($sql);
while ($row=sql_fetch_array($result))
{
    $key = $row['fm_id'];
    $faq_master_list[$key] = $row;
}

$fm = array();
$fm_id = isset($fm_id) ? (int) $fm_id : 0;
$is_all_faq = !$fm_id;

if (!$is_all_faq) {
    if (!isset($faq_master_list[$fm_id])) {
        alert('등록된 내용이 없습니다.');
    }

    $qstr .= '&amp;fm_id=' . $fm_id; // 마스터faq key_id
    $fm = $faq_master_list[$fm_id];
    $g5['title'] = $fm['fm_subject'];
} else {
    $fm = array(
        'fm_id' => 0,
        'fm_subject' => '전체'
    );
    $g5['title'] = 'FAQ';
}

$skin_file = $faq_skin_path.'/list.skin.php';

include_once('./_head.php');

if(is_file($skin_file)) {
    $admin_href = '';
    $himg_src = '';
    $timg_src = '';
    if($is_admin && !$is_all_faq)
        $admin_href = G5_ADMIN_URL.'/faqmasterform.php?w=u&amp;fm_id='.$fm_id;

    if(!G5_IS_MOBILE && !$is_all_faq) {
        $himg = G5_DATA_PATH.'/faq/'.$fm_id.'_h';
        if (is_file($himg)){
            $himg_src = G5_DATA_URL.'/faq/'.$fm_id.'_h';
        }

        $timg = G5_DATA_PATH.'/faq/'.$fm_id.'_t';
        if (is_file($timg)){
            $timg_src = G5_DATA_URL.'/faq/'.$fm_id.'_t';
        }
    }

    $category_href = G5_BBS_URL.'/faq.php';
    $category_stx = '';
    $faq_list = array();
    $sql_fm = '';

    $stx = trim($stx);
    $sql_search = '';

    if($stx) {
       $sql_search = " and ( INSTR(a.fa_subject, '$stx') > 0 or INSTR(a.fa_content, '$stx') > 0 ) ";
    }

    if(!$is_all_faq) {
        $sql_fm = " and a.fm_id = '$fm_id' ";
    }

    if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)

    $page_rows = G5_IS_MOBILE ? $config['cf_mobile_page_rows'] : $config['cf_page_rows'];

    $sql = " select count(*) as cnt
                from {$g5['faq_table']} a
                where 1=1
                $sql_fm
                $sql_search ";
    $total = sql_fetch($sql);
    $total_count = $total['cnt'];

    $total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
    $from_record = ($page - 1) * $page_rows; // 시작 열을 구함

    $sql = " select a.*, m.fm_subject as master_subject
                from {$g5['faq_table']} a
                left join {$g5['faq_master_table']} m on a.fm_id = m.fm_id
                where 1=1
                $sql_fm
                $sql_search
                order by a.fa_order , a.fa_id
                limit $from_record, $page_rows ";
    $result = sql_query($sql);
    for ($i=0;$row=sql_fetch_array($result);$i++){
        $faq_list[] = $row;
        if($stx) {
            $faq_list[$i]['fa_subject'] = search_font($stx, conv_content($faq_list[$i]['fa_subject'], 1));
            $faq_list[$i]['fa_content'] = search_font($stx, conv_content($faq_list[$i]['fa_content'], 1));
        }
    }
    include_once($skin_file);
} else {
    echo '<p>'.str_replace(G5_PATH.'/', '', $skin_file).'이 존재하지 않습니다.</p>';
}

include_once('./_tail.php');

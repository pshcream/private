<?php
header('Access-Control-Allow-Origin: *');
include_once('./_common.php');
@include_once("../plugin/sms5/JSON.php");

// 
if (!function_exists('json_encode')) {
    function json_encode($data)
    {
        $json = new Services_JSON();
        return ($json->encode($data));
    }
}
if (!isset($_REQUEST['submitNm']) || !trim($_REQUEST['submitNm'])) {
    die(0);
}
if (!isset($_REQUEST['submitNb']) || !trim($_REQUEST['submitNb'])) {
    die(0);
}

// 기본값
$idx = estimate_strtotime(date("Y-m-d h:i:s"));
$location = addslashes(clean_xss_tags(trim($_REQUEST['location'])));
$stockType = addslashes(clean_xss_tags(trim($_REQUEST['stockType'])));
// 기본상담신청 데이터
$submitNm = addslashes(clean_xss_tags(trim($_REQUEST['submitNm'])));
$submitNb = addslashes(clean_xss_tags(trim($_REQUEST['submitNb'])));
$submitRg = addslashes(clean_xss_tags(trim($_REQUEST['submitRg'])));
$submitMm = addslashes(clean_xss_tags(trim($_REQUEST['submitMm'])));

// 1.일반 상담신청시
if (!isset($_REQUEST['dataNum'])) {
    $sql = "insert into {$g5['chadeal_application_table_new']}
            set idx = '$idx',
            location = '$location',
            submitNm = '$submitNm',
            submitNb = '$submitNb',            
            submitRg = '$submitRg',            
            submitMm = '$submitMm',  
            prodType = '',             
            companyNm = '',             
            brandNo = '',             
            brandNm = '',             
            modelNo = '',             
            modelNm = '',             
            lineupNo = '',             
            lineupNm = '',             
            trimNo = '',             
            trimNm = '',             
            optNmArr = '',             
            optDescArr = '',             
            optPriceArr = '',             
            colorNm = '',             
            innerColorNm = '',             
            cost = '',             
            tax = '',             
            bond = '',             
            totalPrice = '',             
            carPrice = '',             
            optionPrice = '',             
            period = '',             
            distance = '',             
            deposit = '',             
            prepaid = '',             
            chadeal_datetime = '" . G5_TIME_YMDHIS . "',           
            ";
    sql_query($sql);
} else {
    $dataNum = addslashes(clean_xss_tags(trim($_REQUEST['dataNum'])));
    for ($i = 0; $i < $dataNum; $i++) {


        $sql = "insert into {$g5['chadeal_application_table_new']}
        set idx = '$idx',
        location = '$location',
        submitNm = '$submitNm',
        submitNb = '$submitNb',            
        submitRg = '$submitRg',            
        submitMm = '$submitMm',  
        prodType = '$prodType',             
        companyNm = '$companyNm',             
        brandNo = '$brandNo',             
        brandNm = '$brandNm',             
        modelNo = '$modelNo',             
        modelNm = '$modelNm',             
        lineupNo = '$lineupNo',             
        lineupNm = '$lineupNm',             
        trimNo = '$trimNo',             
        trimNm = '$trimNm',             
        optNmArr = '$optNmArr',             
        optDescArr = '$optDescArr',             
        optPriceArr = '$optPriceArr',             
        colorNm = '$colorNm',             
        innerColorNm = '$innerColorNm',             
        cost = '$cost',             
        tax = '$tax',             
        bond = '$bond',             
        totalPrice = '$totalPrice',             
        carPrice = '$carPrice',             
        optionPrice = '$optionPrice',             
        period = '$period',             
        distance = '$distance',             
        deposit = '$deposit',             
        prepaid = '$prepaid',             
        chadeal_datetime = '" . G5_TIME_YMDHIS . "',           
        ";
    }
}

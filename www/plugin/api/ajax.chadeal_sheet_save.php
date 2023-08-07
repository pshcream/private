<?php
include_once('./_common.php');
@include_once(G5_PLUGIN_PATH . "/sms5/JSON.php");

if (!function_exists('json_encode')) {
    function json_encode($data)
    {
        $json = new Services_JSON();
        return ($json->encode($data));
    }
}

function arr_sort($array, $key, $sort)
{
    $keys = array();
    $vals = array();
    foreach ($array as $k => $v) {
        $i = $v[$key] . '.' . $k;
        $vals[$i] = $v;
        array_push($keys, $k);
    }
    unset($array);

    if ($sort == 'asc') {
        ksort($vals);
    } else {
        krsort($vals);
    }

    $ret = array_combine($keys, $vals);

    unset($keys);
    unset($vals);

    return $ret;
}

// ################# //

if (!isset($_REQUEST['g_est_idx']) || !$_REQUEST['g_est_idx']) {
    die(0);
} else {
    $est_idx = addslashes(clean_xss_tags(trim($_REQUEST['g_est_idx'])));
}
if (!isset($_REQUEST['g_stockType']) || !$_REQUEST['g_stockType']) {
    die(0);
} else {
    $stockType = addslashes(clean_xss_tags(trim($_REQUEST['g_stockType'])));
}
if (!isset($_REQUEST['g_cost']) || !$_REQUEST['g_cost']) {
    die(0);
} else {
    $cost = addslashes(clean_xss_tags(trim($_REQUEST['g_cost'])));
}
if (!isset($_REQUEST['g_tax']) || !$_REQUEST['g_tax']) {
    die(0);
} else {
    $tax = addslashes(clean_xss_tags(trim($_REQUEST['g_tax'])));
}
if (!isset($_REQUEST['g_bond']) || !$_REQUEST['g_bond']) {
    die(0);
} else {
    $bond = addslashes(clean_xss_tags(trim($_REQUEST['g_bond'])));
}

$idx = "s" . $est_idx . strtotime(date("Y-m-d h:i:s"));
$cashback = addslashes(clean_xss_tags(trim($_REQUEST['g_cashback'])));
$period = addslashes(clean_xss_tags(trim($_REQUEST['g_period'])));
$prepaid = addslashes(clean_xss_tags(trim($_REQUEST['g_prepaid'])));
$rate = addslashes(clean_xss_tags(trim($_REQUEST['g_rate'])));
$monprice = addslashes(clean_xss_tags(trim($_REQUEST['g_monprice'])));
$deposit = addslashes(clean_xss_tags(trim($_REQUEST['g_deposit'])));
$distance = addslashes(clean_xss_tags(trim($_REQUEST['g_distance'])));
$qlist = addslashes(clean_xss_tags(trim($_REQUEST['g_qlist'])));

if ($stockType == "F") {
    if (!isset($_REQUEST['g_cashback']) || !$_REQUEST['g_cashback']) {
        die(0);
    }
    $sql = " insert into {$g5['chadeal_sheetlist_table']}
         set idx = '$idx',
             est_idx = '$est_idx',            
             stockType = '$stockType',
             cost = '$cost',
             tax = '$tax',
             bond = '$bond',  
             cashback = '$cashback',
             period = '$period',
             prepaid = '$prepaid',
             rate = '$rate',
             monprice = '$monprice',  
             deposit = '$deposit',  
             distance = '$distance',  
             qlist = '$qlist',  
             chadeal_datetime = '" . G5_TIME_YMDHIS . "' ";
    sql_query($sql);
}
// 할부
else if ($stockType == "I") {

    if (!isset($_REQUEST['g_period']) || !$_REQUEST['g_period']) {
        die(0);
    }

    if (!isset($_REQUEST['g_prepaid']) || !$_REQUEST['g_prepaid']) {
        die(0);
    }

    if (!isset($_REQUEST['g_rate']) || !$_REQUEST['g_rate']) {
        die(0);
    }

    if (!isset($_REQUEST['g_monprice']) || !$_REQUEST['g_monprice']) {
        die(0);
    }

    $sql = " insert into {$g5['chadeal_sheetlist_table']}
    set idx = '$idx',
        est_idx = '$est_idx',            
        stockType = '$stockType',
        cost = '$cost',
        tax = '$tax',
        bond = '$bond',  
        cashback = '$cashback',
        period = '$period',
        prepaid = '$prepaid',
        rate = '$rate',
        monprice = '$monprice',  
        deposit = '$deposit',  
        distance = '$distance',  
        qlist = '$qlist', 
        chadeal_datetime = '" . G5_TIME_YMDHIS . "' ";
    sql_query($sql);
}
// 렌트/리스
else if ($stockType == "R" || $stockType == "L") {

    if (!isset($_REQUEST['g_period']) || !$_REQUEST['g_period']) {
        die(0);
    }

    if (!isset($_REQUEST['g_prepaid']) || !$_REQUEST['g_prepaid']) {
        die(0);
    }

    if (!isset($_REQUEST['g_monprice']) || !$_REQUEST['g_monprice']) {
        die(0);
    }

    if (!isset($_REQUEST['g_deposit']) || !$_REQUEST['g_deposit']) {
        die(0);
    }

    $sql = " insert into {$g5['chadeal_sheetlist_table']}
    set idx = '$idx',  
        est_idx = '$est_idx',            
        stockType = '$stockType',
        cost = '$cost',
        tax = '$tax',
        bond = '$bond',  
        cashback = '$cashback',
        period = '$period',
        prepaid = '$prepaid',
        rate = '$rate',
        monprice = '$monprice',  
        deposit = '$deposit',  
        distance = '$distance',  
        qlist = '$qlist', 
        chadeal_datetime = '" . G5_TIME_YMDHIS . "' ";
    sql_query($sql);
}

echo $idx;

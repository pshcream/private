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
// --------------------------------------------------------- //

if (!isset($_REQUEST['modelNo']) || !$_REQUEST['modelNo']) {
    die(0);
} else {
    $modelNo = addslashes(clean_xss_tags(trim($_REQUEST['modelNo'])));
}

$row = sql_fetch("SELECT * FROM shopping_count WHERE MODL_C_NO=$modelNo");


if (!$row) {
    $pre_count = 1;
    $sql = "INSERT INTO shopping_count SET MODL_C_NO=$modelNo, PRE_COUNT=$pre_count, EP_COUNT=0";
} else {
    $pre_count = (int)$row["PRE_COUNT"];
    $pre_count = $pre_count + 1;
    $sql = "UPDATE shopping_count SET PRE_COUNT=$pre_count WHERE MODL_C_NO=$modelNo";
}

sql_query($sql);

echo $pre_count;

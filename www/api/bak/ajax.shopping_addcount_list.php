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
    $ep_count = 1;
    $sql = "INSERT INTO shopping_count SET MODL_C_NO=$modelNo, EP_COUNT=$ep_count, PRE_COUNT=0";
} else {
    $ep_count = (int)$row["EP_COUNT"];
    $ep_count = $ep_count + 1;
    $sql = "UPDATE shopping_count SET EP_COUNT=$ep_count WHERE MODL_C_NO=$modelNo";
}

sql_query($sql);

echo $ep_count;

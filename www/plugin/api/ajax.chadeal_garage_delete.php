
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

// ################# //

if (!isset($_REQUEST['idx']) || !$_REQUEST['idx']) {
    die(0);
} else {
    $idx = addslashes(clean_xss_tags(trim($_REQUEST['idx'])));
}

$garageList = array();
$garageQry = sql_query("select * FROM {$g5['chadeal_garagelist_table']} WHERE idx = '{$idx}'");
while ($garageRes = sql_fetch_array($garageQry)) array_push($garageList, $garageRes);

if (count($garageList) > 0) {
    echo $garageList[0]["mb_id"];
    sql_query("DELETE FROM {$g5['chadeal_garagelist_table']} WHERE idx = '{$idx}'");
}

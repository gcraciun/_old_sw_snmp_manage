<?php

include_once("include/header.php");
include_once("include/const.php");
include_once("include/functions.php");


$ip = &$_GET['ip'];
if ($ip == NULL) die ('error');

//$i = 0;

//$type = snmpwalk("$ip", "$community_ro", "$ifType");

//foreach($type as $key => $value) 
//echo "$key -> $value<br>";

/*
$time_start = microtime_float();
foreach ($type as $value) {
  if (stripos("$value", "ethernet", (int)8))
echo "$value<br>";
}
$time_end = microtime_float();
$time = $time_end - $time_start;
echo "About $time seconds\n";
*/


write_table_head();
write_middle_table($ip);
write_table_end();

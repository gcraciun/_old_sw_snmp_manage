<?php

include_once("include/header.php");
include_once("include/const.php");
include_once("include/functions.php");


$ip = &$_GET['ip'];
$index = &$_GET['index'];
$action = &$_GET['action'];

if (($ip == NULL) || ($index == NULL) || ($action == NULL)) die ('error');

if (!valid_ip($ip)) die ('Invalid IP');

$result = snmpset("$ip", "$community_rw", "$ifAdminStatus.$index", "i", "$action");
if (!$result) die('Error: Could not set interface');

echo '<META HTTP-EQUIV="Refresh" CONTENT="2; URL=list_int.php?ip='."$ip".'">';

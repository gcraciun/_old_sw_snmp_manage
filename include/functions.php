<?php

include("const.php");
//include("include/const.phpds");

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function write_table_head()
{
  echo '<table class="tabel" cellspacing="0" cellpadding="0" align="center" onmouseover="goaway">
  <tbody>
    <tr align="center" valign="center" bgcolor="blue">
      <td class="captabel">&nbsp;Interface&nbsp;</td>
      <td class="captabel">&nbsp;Description&nbsp;</td>
      <td class="captabel">&nbsp;Status&nbsp;</td>
      <td class="captabel">&nbsp;Trafic IN&nbsp;</td>
      <td class="captabel">&nbsp;Trafic OUT&nbsp;</td>
      <td class="captabel">&nbsp;Action&nbsp;</td>
    </tr>';
}

function write_table_end()
{
  echo '</tbody>
  </table>';
}


function write_middle_table($ip)
{
include("const.php");
  $index = snmp2_walk("$ip", "$community_ro", "$ifIndex");
  $type = snmp2_walk("$ip", "$community_ro", "$ifType");
  $name = snmp2_walk("$ip", "$community_ro", "$ifName");  
  $c_desc = snmp2_walk("$ip", "$community_ro", "$ifAlias");
  $o_status = snmp2_walk("$ip", "$community_ro", "$ifOperStatus");
  $t_in = snmp2_walk("$ip", "$community_ro", "$bytes_in_64bit");
  $t_out = snmp2_walk("$ip", "$community_ro", "$bytes_out_64bit");
  // hmmm...
  $counter = 0;
  $counter_x = 0;
  $temp_table = array();
  $fast_table = array();
  $gi_table = array();
  
  while (isset($type[$counter]) && ether_pos($type[$counter]))
    {
      if(fa_pos(split_result($name[$counter]))) {
        $temp_table = array (split_result($name[$counter]), split_result($c_desc[$counter]), split_status(split_result($o_status[$counter])) , gmp_strval(gmp_div_q(split_result($t_in[$counter]), "1048576")), gmp_strval(gmp_div_q(split_result($t_out[$counter]), "1048576")), split_result($index[$counter]));
        array_push($fast_table, $temp_table);
      } else if (gi_pos(split_result($name[$counter]))) {
        $temp_table = array (split_result($name[$counter]), split_result($c_desc[$counter]), split_status(split_result($o_status[$counter])) , gmp_strval(gmp_div_q(split_result($t_in[$counter]), "1048576")), gmp_strval(gmp_div_q(split_result($t_out[$counter]), "1048576")), split_result($index[$counter]));
        array_push($gi_table, $temp_table);
        }
      $counter++;
    }

natsort2d(& $fast_table);
display_table($fast_table, $ip);
natsort2d(& $gi_table);
display_table_x($gi_table, $ip);
}



function sh_no_sh()
{
}



function eric(& $aryInput ) {
  $aryTemp = $aryOut = array();
  foreach ( $aryInput as $key => $value) {
    echo "mooo<br>";
  }
}


function natsort2d (& $aryInput ) {
  $aryTemp = $aryOut = array();
  foreach ( $aryInput as $key => $value ) {
    reset ( $value );
    $aryTemp [ $key ]= current ( $value );
  }
  natsort ( $aryTemp );
  foreach ( $aryTemp as $key => $value ) {
    $aryOut [] = $aryInput [ $key ];
  }
  $aryInput = $aryOut ;
} 

function display_table($table, $ip)
{
  $counter = 0;
  while (isset($table[$counter][0])) {
    echo "<tr class='tr' onmouseover=\"className='tr_on'\" onmouseout=\"className='tr'\" bgcolor=".bgcolor($table[$counter][2]).">
          <td>&nbsp".$table[$counter][0]."&nbsp</td>
          <td>&nbsp".$table[$counter][1]."&nbsp</td>
          <td>&nbsp".$table[$counter][2]."&nbsp</td>
          <td align=\"right\">&nbsp ".$table[$counter][3]." MB&nbsp</td>
          <td align=\"right\">&nbsp ".$table[$counter][4]." MB&nbsp</td>
          <td>&nbsp<input type=\"button\" class=\"butonorange\" name=\"shut\" value=\"Shutdown\" onclick=\"location.href='action.php?ip=$ip&index=".trim($table[$counter][5])."&action=2'\"> /
          <input type=\"button\" class=\"butonblue\" name=\"noshut\" value=\"No Shutdown\" onclick=\"location.href='action.php?ip=$ip&index=".trim($table[$counter][5])."&action=1'\"></td></tr>";
    $counter++;
    }
}

function display_table_x($table, $ip)
{
  $counter = 0;
  while (isset($table[$counter][0])) {
    echo "<tr class='tr' onmouseover=\"className='tr_on'\" onmouseout=\"className='tr'\" bgcolor=".bgcolor($table[$counter][2]).">
          <td>&nbsp".$table[$counter][0]."&nbsp</td>
          <td>&nbsp".$table[$counter][1]."&nbsp</td>
          <td>&nbsp".$table[$counter][2]."&nbsp</td>
          <td align=\"right\">&nbsp ".$table[$counter][3]." MB&nbsp</td>
          <td align=\"right\">&nbsp ".$table[$counter][4]." MB&nbsp</td>
          <td>&nbsp Restricted / Restricted</td></tr>";
    $counter++;
    }
}


function gi_pos($gi)
{
  if (stripos("$gi", "Gi"))
  return true;
}


function fa_pos($fa)
{
  if (stripos("$fa", "Fa"))
  return true;
}


function split_name($result)
{
  $result = end(explode("/", $result));
  return $result;
}

function split_result($result)
{
  $result = end(explode(":", $result));
  return $result;
}

function ether_pos($ether)
{
  if (stripos("$ether", "ethernet", (int)8))
  return true;
}

function split_status($result)
{
  $result = reset(explode("(", "$result"));
  return $result;
}

function bgcolor($result)
{
  if (stripos("$result", "down"))
    //return ('red');
    return('#E94112');
  else 
    //return ('green');
    //return ('#3DC246');
    return ('#78FF8C');
}

function valid_ip($ip)
  {
    if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
      {
        return (false);
      }
    else
      {
        return (true);
      }
  }
        
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
  $type = snmp2_walk("$ip", "$community_ro", "$ifType");
  $name = snmp2_walk("$ip", "$community_ro", "$ifName");  
  $c_desc = snmp2_walk("$ip", "$community_ro", "$ifAlias");
  $o_status = snmp2_walk("$ip", "$community_ro", "$ifOperStatus");
  $t_in = snmp2_walk("$ip", "$community_ro", "$bytes_in_64bit");
  $t_out = snmp2_walk("$ip", "$community_ro", "$bytes_out_64bit");
  // fuckit
  $counter = 0;
  $counter_x = 0;
  $temp_table = array();
  $fast_table = array();
  $gi_table = array();
  
  while (isset($type[$counter]) && ether_pos($type[$counter]))
    {
      echo "<tr class='tr' onmouseover=\"className='tr_on'\" onmouseout=\"className='tr'\" bgcolor=".bgcolor($o_status[$counter]).">
      <td>&nbsp".split_result($name[$counter])."&nbsp</td>
      <td>&nbsp".split_result($c_desc[$counter])."&nbsp</td>
      <td>&nbsp".split_status(split_result($o_status[$counter]))."&nbsp</td>
      <td align=\"right\">&nbsp ".gmp_strval(gmp_div_q(split_result($t_in[$counter]), "1048576"))." MB&nbsp</td>
      <td align=\"right\">&nbsp ".gmp_strval(gmp_div_q(split_result($t_out[$counter]), "1048576"))." MB&nbsp</td>
      <td>&nbsp Shutdown / No Shutdown &nbsp</td></tr>";
      /*
      $data_table[$counter][$counter_x] = split_result($name[$counter]); $counter_x++;
      $data_table[$counter][$counter_x] = split_result($c_desc[$counter]); $counter_x++;
      $data_table[$counter][$counter_x] = split_status(split_result($o_status[$counter])); $counter_x++;
      $data_table[$counter][$counter_x] = gmp_strval(gmp_div_q(split_result($t_in[$counter]), "1048576")); $counter_x++;
      $data_table[$counter][$counter_x] = gmp_strval(gmp_div_q(split_result($t_out[$counter]), "1048576")); $counter_x++;
      */
/*      
      if(fa_pos(split_result($name[$counter]))) {
        //$fast_table[$counter] = array (split_result($name[$counter]), split_result($c_desc[$counter]), split_status(split_result($o_status[$counter])) , gmp_strval(gmp_div_q(split_result($t_in[$counter]), "1048576")), gmp_strval(gmp_div_q(split_result($t_out[$counter]), "1048576")));
        $temp_table = array (split_result($name[$counter]), split_result($c_desc[$counter]), split_status(split_result($o_status[$counter])) , gmp_strval(gmp_div_q(split_result($t_in[$counter]), "1048576")), gmp_strval(gmp_div_q(split_result($t_out[$counter]), "1048576")));
        array_push($fast_table, $temp_table);
      } else if (gi_pos(split_result($name[$counter]))) {
        $temp_table = array (split_result($name[$counter]), split_result($c_desc[$counter]), split_status(split_result($o_status[$counter])) , gmp_strval(gmp_div_q(split_result($t_in[$counter]), "1048576")), gmp_strval(gmp_div_q(split_result($t_out[$counter]), "1048576")));
        array_push($gi_table, $temp_table);
        //$gi_table[$counter] = array (split_result($name[$counter]), split_result($c_desc[$counter]), split_status(split_result($o_status[$counter])) , gmp_strval(gmp_div_q(split_result($t_in[$counter]), "1048576")), gmp_strval(gmp_div_q(split_result($t_out[$counter]), "1048576")));
        }
      */
      $counter++;
    }
/*
    for ($counter_x = 0; $counter_x < $counter; $counter_x++) {
      if (fa_pos($data_table[$counter_x][0])) {
        echo "go_away $counter_x <br>";
      } 
      else if (gi_pos($data_table[$counter_x][0])) {
        echo "no go_away $counter_x <br>";
        }

    }
*/            

//echo "and now do split_name".split_name($gi_table[1][0]);

//if (isset($gi_table[2][0])) echo "goooooooooooooooo";

//display_fast($fast_table);
//sort($fast_table);


}



function display_fast($table)
{
  $counter = 0;
  while (isset($table[$counter][0])) {
    echo "<tr class='tr' onmouseover=\"className='tr_on'\" onmouseout=\"className='tr'\" bgcolor=".bgcolor($table[$counter][2]).">
          <td>&nbsp".$table[$counter][0]."&nbsp</td>
          <td>&nbsp".$table[$counter][1]."&nbsp</td>
          <td>&nbsp".$table[$counter][2]."&nbsp</td>
          <td align=\"right\">&nbsp ".$table[$counter][3]." MB&nbsp</td>
          <td align=\"right\">&nbsp ".$table[$counter][4]." MB&nbsp</td>
          <td>&nbsp Shutdown / No Shutdown &nbsp</td></tr>";
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
    return ('red');
  else 
    return ('green');
}

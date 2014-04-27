<?php

function debug_output($str) {
  echo "\n\n<pre>$str</pre>\n\n";
}

function test() {
  return "hello world";
}

function redirect($url) {
  header('location: '.$url);
  exit();
}

function retrieve_player_tables() {
  $dom = $_SESSION['player_list']->display_player_list();
  return $dom;
}

function retrieve_player() {
  $dom = $_SESSION['player']->display_player();
  return $dom;
}

function retrieve_item_tables() {
  $dom = $_SESSION['item_list']->display_item_list();
  return $dom;
}

function retrieve_item() {
  $dom = $_SESSION['item']->display_item();
  return $dom;
}

function retrieve_raid_tables() {
  $dom = $_SESSION['raid_list']->display_raid_list();
  return $dom;
}

function retrieve_raid() {
  $dom = $_SESSION['raid']->display_raid();
  return $dom;
}

function retrieve_value($name) {
  return $_SESSION['values'][$name];
}

function get_wowhead_link($name, $id, $blizz_id, $mode='internal') {
  switch($name) {
    case "Study of Advanced Smelting": $quality = "q3"; break;
    case "Warglaive of Azzinoth":
    case "Reforged Hammer of Ancient Kings":
    case "Shadowfrost Shard":
    case "Fragment of Val'anyr": $quality = "q5"; break;
    default: $quality = "q4"; // default to epic
  }

  if ($mode == 'external') {
    $href = 'http://www.wowhead.com/?item='.$blizz_id.'" target="_blank';
  }
  else {
    $href = 'item.php?id='.$id;
  }
  return '<a title="'.$name.'" href="'.$href.'" rel="item='.$blizz_id.'" class="'.$quality.'">['.$name.']</a>';
}

function retrieve_tier_levels() {
  $sql = "SELECT DISTINCT(tier_level) FROM instance ORDER BY tier_level";
  $rs = DB::query_db($sql);
  $total_xml = '<root>';
  while ($row = mysql_fetch_row($rs)) {
    if ($row[0] == $_SESSION['cur_tier']) {
      $total_xml .= '<level selected="true">'.$row[0].'</level>';
    }
    else {
      $total_xml .= '<level>'.$row[0].'</level>';
    }
  }
  $total_xml .= '</root>';
  $total_dom = new DOMDocument;
  $total_dom->loadXML($total_xml);
  return $total_dom;
}

require_once('db.php');
session_start();
DB::init_db();

/*set default tier*/
if (!isset($_SESSION['cur_tier'])) {
  $_SESSION['cur_tier'] = constant('DEFAULT_TIER');
}
else if (isset($_GET['tier'])) {
  $_SESSION['cur_tier'] = $_GET['tier'];
  $getparams = array();
  foreach ($_GET AS $key => $val) {
    if ($key != 'tier') {
      $getparams[] = $key.'='.$val;
    }
  }
  $get_string = '';
  if (!empty($getparams)) {
    $get_string = '?'.implode('&',$getparams);
  }
  redirect(basename($_SERVER['PHP_SELF']).$get_string);
}

function retrieve_wowhead_link() {
  return $_SESSION['values']['wowhead_link'];
}

?>

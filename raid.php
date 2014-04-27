<?php
require_once(dirname(__FILE__).'/lib/page.php');
require_once(dirname(__FILE__).'/lib/raid.php');
require_once(dirname(__FILE__).'/lib/results.php');
require_once(dirname(__FILE__).'/lib/lib.inc.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  /*no hacking here!*/
  redirect('raid_list.php');
}
else {
  $sql = "SELECT COUNT(*) FROM raid WHERE raid_id = ".$_GET['id'];
  $rows = DB::query_int_db($sql);
  if ($rows == 0) {
    /*no hacking here!*/
    redirect('raid_list.php');
  }
}

$page_obj = new Page();
$_SESSION['raid'] = new raid();
$_SESSION['raid']->load_raid($_GET['id']);

//echo retrieve_player_tables()->saveXML();
$page_obj->display_page('xml/raid.xml');

unset($page_obj);
?>

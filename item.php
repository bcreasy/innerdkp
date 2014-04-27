<?php
require_once(dirname(__FILE__).'/lib/page.php');
require_once(dirname(__FILE__).'/lib/item.php');
require_once(dirname(__FILE__).'/lib/results.php');
require_once(dirname(__FILE__).'/lib/lib.inc.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  /*no hacking here!*/
  redirect('raid_list.php');
}
else {
  $sql = "SELECT COUNT(*) FROM item WHERE item_id = ".$_GET['id'];
  $rows = DB::query_int_db($sql);
  if ($rows == 0) {
    /*no hacking here!*/
    redirect('player_list.php');
  }
}

$page_obj = new Page();
$_SESSION['item'] = new item();
$_SESSION['item']->load_item($_GET['id']);

//echo retrieve_player_tables()->saveXML();
$page_obj->display_page('xml/item.xml');

unset($page_obj);
?>

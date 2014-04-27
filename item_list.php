<?php
require_once(dirname(__FILE__).'/lib/page.php');
require_once(dirname(__FILE__).'/lib/item.php');
require_once(dirname(__FILE__).'/lib/results.php');
require_once(dirname(__FILE__).'/lib/lib.inc.php');

$page_obj = new Page();
$_SESSION['item_list'] = new item();
$_SESSION['item_list']->load_item_list();

//echo retrieve_player_tables()->saveXML();
$page_obj->display_page('xml/item_list.xml');

unset($page_obj);
?>

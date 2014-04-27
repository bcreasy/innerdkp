<?php
require_once(dirname(__FILE__).'/lib/page.php');
require_once(dirname(__FILE__).'/lib/player.php');
require_once(dirname(__FILE__).'/lib/player_list.php');
require_once(dirname(__FILE__).'/lib/results.php');
require_once(dirname(__FILE__).'/lib/lib.inc.php');

unset($_SESSION['player_results']);

$page_obj = new Page();

$_SESSION['player_list'] = new player_list();
$_SESSION['player_list']->load_player_list();
//echo retrieve_player_tables()->saveXML();
$page_obj->display_page('xml/player_list.xml');

unset($page_obj);
?>

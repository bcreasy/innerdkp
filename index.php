<?php
require_once(dirname(__FILE__).'/lib/page.php');
require_once(dirname(__FILE__).'/lib/db.php');
require_once(dirname(__FILE__).'/lib/lib.inc.php');

/*
$db_obj = new DB();

$page_obj = new Page();

$page_obj->display_page('xml/index.xml');
*/

header('Location: player_list.php');
unset($page_obj);
unset($db_obj);
unset($_SESSION['players']);
unset($_SESSION['player_results']);
unset($_SESSION['player_list']);
?>

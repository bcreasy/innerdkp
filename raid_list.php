<?php
require_once(dirname(__FILE__).'/lib/page.php');
require_once(dirname(__FILE__).'/lib/raid.php');
require_once(dirname(__FILE__).'/lib/raid_list.php');
require_once(dirname(__FILE__).'/lib/results.php');
require_once(dirname(__FILE__).'/lib/lib.inc.php');

$page_obj = new Page();

$_SESSION['raid_list'] = new raid_list();
$_SESSION['raid_list']->load_raid_list();
//echo retrieve_raid_tables()->saveXML();
$page_obj->display_page('xml/raid_list.xml');

unset($page_obj);
?>

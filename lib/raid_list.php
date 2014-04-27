<?php
require_once(dirname(__FILE__).'/config.inc.php');
require_once(dirname(__FILE__).'/results.php');
require_once(dirname(__FILE__).'/lib.inc.php');

/* raid_list class */
class raid_list {
  public $raids;

  function __construct() {
    $this->raids = array();
  }

  function __destruct() {
  }

  function load_raid_list() {
    $sql = "SELECT raid.raid_id, event.event_id, raid.date AS \"Date\",
              instance.name AS \"Instance\", event.name AS \"Event\",
              raid.note, (SELECT count(*) 
                          FROM attendance 
                          WHERE raid_id = raid.raid_id) AS \"Attendees\", 
                         (SELECT count(*) 
                          FROM loot 
                          WHERE raid_id = raid.raid_id) AS \"Drops\"
            FROM event JOIN instance JOIN raid 
            WHERE event.instance_id = instance.instance_id 
              AND event.event_id = raid.event_id";
    if (isset($_SESSION['cur_tier'])) {
      $sql .= " AND tier_level = '".mysql_real_escape_string($_SESSION['cur_tier'])."'";
    }
    $rs = DB::query_db($sql);
    while ($row = mysql_fetch_assoc($rs)) {
      $row['click'] = 'raid.php?id='.$row['raid_id'];
      $this->raids[] = $row;
    }
  }

  function display_raid_list() {
    $total_xml = '<raidResults>';

    $_SESSION['raid_results'] = new results();
    $_SESSION['raid_results']->columns = array('Date', 'Instance', 'Event', 'Attendees', 'Drops');
    $_SESSION['raid_results']->order_by = 'Date';
    $_SESSION['raid_results']->sort = 'DESC';
    $_SESSION['raid_results']->results_array = $this->raids;
    
    $total_xml .= $_SESSION['raid_results']->display_results();

    $total_xml .= '</raidResults>';
    $total_dom = new DOMDocument;
    $total_dom->loadXML($total_xml);
    return $total_dom;
  }

}

?>

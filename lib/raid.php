<?php
require_once(dirname(__FILE__).'/config.inc.php');
require_once(dirname(__FILE__).'/results.php');
require_once(dirname(__FILE__).'/lib.inc.php');

/* raid class */
class raid {
  public $raid_id;
  public $loot;
  public $attendance;
  public $attendance_count;

  function __construct() {
    $this->raid_id = 0;
    $this->loot = array();
    $this->attendance = array();
    $this->attendance_count = 0;
  }

  function __destruct() {
  }

  function load_raid($id='') {
    if ($id != '') {
      $this->raid_id = $id;
    }

    $this->load_loot();
    $this->load_attendance();

    $sql = "SELECT raid.date AS \"raid_date\",
              event.name AS \"raid_event\", raid.note AS \"raid_note\",
              instance.name AS \"raid_instance\"
            FROM raid JOIN event USING (event_id) JOIN instance USING (instance_id)
            WHERE raid.raid_id = ".$this->raid_id;
    $_SESSION['values'] = DB::query_assoc_db($sql);
  }

  function load_loot() {
    $sql = "SELECT player.name AS \"Looter\", item.name AS \"Item\", item.item_id, item.dkp AS \"DKP\", loot.offspec AS \"Offspec\", item.blizz_id
            FROM raid JOIN loot USING (raid_id) JOIN item USING (item_id) JOIN player USING (player_id)
            WHERE raid.raid_id = ".$this->raid_id;
    $rs = DB::query_db($sql);
    while ($row = mysql_fetch_assoc($rs)) {
      if ($row['blizz_id'] != 0) {
        $row['Item'] = get_wowhead_link($row['Item'], $row['item_id'], $row['blizz_id']);
      }

      if ($row['Offspec'] == 0) {
        $row['Offspec'] = 'No';
      }
      else {
        $row['Offspec'] = 'Yes';
        $row['DKP'] = 0;
      }
      $this->loot[] = $row;
    }
  }

  function load_attendance() {
    $sql = "SELECT COUNT(player_id)
            FROM attendance 
            WHERE raid_id = ".$this->raid_id;
    $this->attendance_count = DB::query_int_db($sql);

    $sql = "SELECT player_id, name AS \"Attendance (".$this->attendance_count.")\"
            FROM player JOIN attendance USING (player_id)
            WHERE raid_id = ".$this->raid_id;
    $rs = DB::query_db($sql);
    while ($row = mysql_fetch_assoc($rs)) {
      $row['click'] = 'player_view.php?id='.$row['player_id'];
      $this->attendance[] = $row;
    }
  }

  function display_raid() {
    $total_xml = '<raidResults>';

    $_SESSION['raid_loot'] = new results();
    $_SESSION['raid_loot']->columns = array('Item', 'Offspec', 'Looter', 'DKP');
    $_SESSION['raid_loot']->order_by = 'Item';
    $_SESSION['raid_loot']->sort = 'ASC';
    $_SESSION['raid_loot']->results_array = $this->loot;
    $total_xml .= $_SESSION['raid_loot']->display_results();

    $_SESSION['raid_attendance'] = new results();
    $_SESSION['raid_attendance']->columns = array('Attendance ('.$this->attendance_count.')');
    $_SESSION['raid_attendance']->order_by = 'Attendance ('.$this->attendance_count.')';
    $_SESSION['raid_attendance']->sort = 'ASC';
    $_SESSION['raid_attendance']->results_array = $this->attendance;
    $total_xml .= $_SESSION['raid_attendance']->display_results();

    $total_xml .= '</raidResults>';

    $total_dom = new DOMDocument;
    $total_dom->loadXML($total_xml);
    return $total_dom;

  }

}

?>

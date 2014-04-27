<?php
require_once(dirname(__FILE__).'/config.inc.php');
require_once(dirname(__FILE__).'/results.php');
require_once(dirname(__FILE__).'/lib.inc.php');

/* player class */
class player {
  public $player_id;
  public $player_name;
  public $rank;
  public $loot;
  public $attendance;
  public $dkp;

  function __construct() {
    $this->player_id = 0;
    $this->player_name = '';
    $this->rank = '';
    $this->loot = array();
    $this->attendance = array();
    $this->dkp = array();
  }

  function __destruct() {
  }

  function load_player($id, $mode='full') {
    $this->player_id = $id;
    $sql = "SELECT player.name AS player_name, rank AS player_rank, class.name AS player_class, player.status AS player_status
            FROM player JOIN rank USING (rank_id) JOIN class USING (class_id)
            WHERE player_id = $id";
    $_SESSION['values'] = DB::query_assoc_db($sql);
    $this->player_name = $_SESSION['values']['player_name'];
    $this->rank = $_SESSION['values']['player_rank'];
    $this->status = $_SESSION['values']['player_status'];

    if ($mode == 'full') {
      $sql = "SELECT COUNT(raid_id)
              FROM attendance JOIN raid USING (raid_id)
                JOIN event USING (event_id)
                JOIN instance USING (instance_id)
              WHERE player_id = $id";
      if (isset($_SESSION['cur_tier'])) {
        $sql .= " AND tier_level = '".mysql_real_escape_string($_SESSION['cur_tier'])."'";
      }
      $my_raids = DB::query_int_db($sql);

      $sql = "SELECT COUNT(raid_id)
              FROM raid
                JOIN event USING (event_id)
                JOIN instance USING (instance_id)";
      if (isset($_SESSION['cur_tier'])) {
        $sql .= " WHERE tier_level = '".mysql_real_escape_string($_SESSION['cur_tier'])."'";
      }
      $all_raids = DB::query_int_db($sql);
      
      if ($all_raids == 0) {
        $_SESSION['values']['raid_percent'] = '0';
      }
      else {
        $_SESSION['values']['raid_percent'] = ceil(($my_raids / $all_raids) * 100).'%';
      }

      $this->load_loot();
      $this->load_attendance();
      $this->load_dkp();
    }
  }

  function fetch_player_row() {
    if ($this->player_id == 0 || $this->rank == "Retired") {
      return 0;
    }

    $dkp_vals = $this->calculate_dkp();

    if ($this->rank == "Recruit" || $this->status == 1) {
      $tag = ' [';
      if ($this->rank == "Recruit") {
        $tag = $tag.'R';
      }
      if ($this->status == 1) {
        $tag = $tag.'L';
      }
      $tag = $tag.']';
    }

    $tagged_name = '<a href="player_view.php?id='.$this->player_id.'">'.$this->player_name.'</a>'.$tag;

    return array('Name' => $tagged_name,
                 'Rank' => $this->rank, 
                 'Current DKP' => $dkp_vals['current'], 
                 'Earned DKP' => $dkp_vals['earned'], 
                 'Spent DKP' => $dkp_vals['spent'], 
                 'Last Raid' => $raid_vals[0][1],
                 'Loot' => $this->get_mainspec_items_looted());
  }

  function calculate_dkp() {
    /*TODO*/
    /*Make this actually work*/
    $dkp_spent=0;
    $dkp_earned=0;
    $sql = "SELECT raid_id
            FROM attendance
              JOIN raid USING (raid_id)
              JOIN event USING (event_id)
              JOIN instance USING (instance_id)
            WHERE player_id = ".$this->player_id;
    if (isset($_SESSION['cur_tier'])) {
      $sql .= " AND tier_level = '".mysql_real_escape_string($_SESSION['cur_tier'])."'";
    }
    $raid_array = DB::query_array_db($sql);
    if ($raid_array == '') {
      return array('current' => 0, 'earned' => 0, 'spent' => 0);
    }

    $sql = "SELECT raid_id, SUM(dkp)
            FROM raid JOIN loot USING (raid_id) JOIN item USING (item_id) 
            WHERE raid_id IN ($raid_array)
              AND offspec = 0
            GROUP BY raid_id
            ORDER BY raid_id;";
    $rs = DB::query_db($sql);
    $raids = array();
    while ($row = mysql_fetch_row($rs)) {
      $raids[$row[0]] = $row[1];
    }
    
    $sql = "SELECT raid_id, COUNT(player_id)
            FROM raid JOIN attendance USING (raid_id) 
            WHERE raid_id IN ($raid_array)
            GROUP BY raid_id
            ORDER BY raid_id;";
    $rs = DB::query_db($sql);
    while ($row = mysql_fetch_row($rs)) {
      $dkp_earned += $raids[$row[0]] / $row[1];
    }
    
    /* main spec gear */
    $sql = "SELECT sum(dkp)
            FROM item JOIN loot USING (item_id)
              JOIN raid USING (raid_id) JOIN event USING (event_id)
              JOIN instance USING (instance_id)
            WHERE player_id = ".$this->player_id."
            AND offspec = 0";
    if (isset($_SESSION['cur_tier'])) {
      $sql .= " AND tier_level = '".mysql_real_escape_string($_SESSION['cur_tier'])."'";
    }
    $dkp_spent += DB::query_double_db($sql);

    /* offspec gear */
/*
    $sql = "SELECT sum(dkp)/5
            FROM item JOIN loot USING (item_id)
              JOIN raid USING (raid_id) JOIN event USING (event_id)
              JOIN instance USING (instance_id)
            WHERE player_id = ".$this->player_id."
            AND offspec = 1";
    if (isset($_SESSION['cur_tier'])) {
      $sql .= " AND tier_level = '".mysql_real_escape_string($_SESSION['cur_tier'])."'";
    }
    $dkp_spent += DB::query_double_db($sql);
*/

    $dkp_current = $dkp_earned - $dkp_spent;

    return array('current' => round($dkp_current, 3), 'earned' => round($dkp_earned, 3), 'spent' => round($dkp_spent, 3));
  }

  function get_mainspec_items_looted() {
    // this isn't used before cataclysm
    if ($_SESSION['cur_tier'] < 11) {
      return null;
    }
    $sql = "SELECT sum(item.dkp) AS \"Looted\"
            FROM raid JOIN loot USING (raid_id) JOIN item USING (item_id) JOIN player USING (player_id)
              JOIN event USING (event_id) JOIN instance USING (instance_id)
            WHERE player_id = ".$this->player_id."
              AND offspec = 0
              AND tier_level = '".mysql_real_escape_string($_SESSION['cur_tier'])."'";
    $rs = DB::query_int_db($sql);
    return $rs;
  }

  /**
  * Function to return an array of all raids this
  * player has attended
  */
  function load_attendance() {
    $sql = "SELECT raid_id, raid.date AS \"Raid Date\",
              instance.name AS \"Instance\", event.name AS \"Event\"
            FROM attendance JOIN raid USING (raid_id) JOIN event USING (event_id)
              JOIN instance USING (instance_id)
            WHERE player_id = ".$this->player_id;
    if (isset($_SESSION['cur_tier'])) {
      $sql .= " AND tier_level = '".mysql_real_escape_string($_SESSION['cur_tier'])."'";
    }
    $rs = DB::query_db($sql);
    while ($row = mysql_fetch_assoc($rs)) {
      $row['click'] = 'raid.php?id='.$row['raid_id'];
      $this->attendance[] = $row;
    }
  }

  function load_loot() {
    $sql = "SELECT raid.date AS \"Date\", raid.raid_id AS \"Raid\", item.name AS \"Item\", item.item_id, item.dkp AS \"DKP\", loot.offspec AS \"Offspec\", item.blizz_id
            FROM raid JOIN loot USING (raid_id) JOIN item USING (item_id) JOIN player USING (player_id)
              JOIN event USING (event_id) JOIN instance USING (instance_id)
            WHERE player_id = ".$this->player_id;
    if (isset($_SESSION['cur_tier'])) {
      $sql .= " AND tier_level = '".mysql_real_escape_string($_SESSION['cur_tier'])."'";
    }
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

      if ($row['Raid'] != 0) {
        $sql = "select event.name from event join raid using (event_id) where raid.raid_id = ".$row['Raid'];
        $event = DB::query_str_db($sql);
        $row['Raid'] = '<a href="raid.php?id='.$row['Raid'].'">'.$event.'</a>';
      }

      $this->loot[] = $row;
    }
  }

  function load_dkp() {
    $dkp = $this->calculate_dkp();
    $_SESSION['values']['current_dkp'] = $dkp['current'];
    $_SESSION['values']['earned_dkp'] = $dkp['earned'];
    $_SESSION['values']['spent_dkp'] = $dkp['spent'];
    $_SESSION['values']['mainspec_loot'] = $this->get_mainspec_items_looted();
  }


  function display_player() {
    $total_xml = '<playerView>';

    $_SESSION['player_loot'] = new results();
    $_SESSION['player_loot']->columns = array('Date', 'Raid', 'Item', 'Offspec', 'DKP');
    $_SESSION['player_loot']->order_by = 'Date';
    $_SESSION['player_loot']->sort = 'DESC';
    $_SESSION['player_loot']->results_array = $this->loot;
    $total_xml .= $_SESSION['player_loot']->display_results('Loot');

    $_SESSION['player_attendance'] = new results();
    $_SESSION['player_attendance']->columns = array('Raid Date', 'Instance', 'Event');
    $_SESSION['player_attendance']->order_by = 'Raid Date';
    $_SESSION['player_attendance']->sort = 'DESC';
    $_SESSION['player_attendance']->results_array = $this->attendance;
    $total_xml .= $_SESSION['player_attendance']->display_results('Attendance');

    $total_xml .= '</playerView>';

    $total_dom = new DOMDocument;
    $total_dom->loadXML($total_xml);
    return $total_dom;

  }

}

?>

<?php
require_once(dirname(__FILE__).'/config.inc.php');
require_once(dirname(__FILE__).'/results.php');
require_once(dirname(__FILE__).'/lib.inc.php');

/* player class */
class item {
  public $item_id;
  public $item_name;
  public $loot;
  public $items;

  function __construct() {
    $this->item_id = 0;
    $this->loot = array();
    $this->items = array();
  }

  function __destruct() {
  }

  function load_item($id) {
    $this->item_id = $id;
    $sql = "SELECT name AS \"item_name\", dkp AS \"dkp_value\", blizz_id
            FROM item
            WHERE item_id = $id";
    $_SESSION['values'] = DB::query_assoc_db($sql);

    $sql = "SELECT COUNT(*)
            FROM loot
            WHERE item_id = $id
              AND offspec = 0";
    $_SESSION['values']['total_loot_times'] = DB::query_int_db($sql);

    $sql = "SELECT COUNT(*)
            FROM loot
            WHERE item_id = $id
              AND offspec = 1";
    $_SESSION['values']['offspec_loot_times'] = DB::query_int_db($sql);

    if ($_SESSION['values']['blizz_id'] != 0) {
      $xml = '<wLinks><wLink>';
      $xml .= get_wowhead_link($_SESSION['values']['item_name'], $id, $_SESSION['values']['blizz_id'], 'external');
      $xml .= '</wLink></wLinks>';
      $dom = new DOMDocument;
      $dom->loadXML($xml);
      $_SESSION['values']['wowhead_link'] = $dom;
    }

    $sql = "SELECT item_id AS primary_key, raid.date AS \"Date\",
              event.name AS \"Raid\", player.name AS \"Player\", offspec AS \"Offspec\", dkp AS \"DKP\", 
              player_id, raid_id
            FROM item JOIN loot USING (item_id) JOIN player USING (player_id) JOIN raid USING (raid_id)
              JOIN event USING (event_id) JOIN instance USING (instance_id)
            WHERE item_id = $id";

    $rs = DB::query_db($sql);
    while ($row = mysql_fetch_assoc($rs)) {
      if ($row['Offspec'] == 0) {
        $row['Offspec'] = 'No';
      }
      else {
        $row['Offspec'] = 'Yes';
        $row['DKP'] = 0;
      }

      if (is_numeric($row['player_id'])) {
        $sql = "SELECT player.name 
                FROM player
                WHERE player.player_id = ".$row['player_id'];
        $player = DB::query_str_db($sql);
        $row['Player'] = '<a href="player_view.php?id='.$row['player_id'].'">'.$player.'</a>';
      }

      if (is_numeric($row['raid_id'])) {
        $sql = "SELECT event.name 
                FROM event join raid using (event_id) 
                WHERE raid.raid_id = ".$row['raid_id'];
        $event = DB::query_str_db($sql);
        $row['Raid'] = '<a href="raid.php?id='.$row['raid_id'].'">'.$event.'</a>';
      }

      $this->loot_info[] = $row;
    }

  }

  function display_item() {
    $total_xml = '<itemView>';

    $_SESSION['item_loot'] = new results();
    $_SESSION['item_loot']->columns = array('Date', 'Raid', 'Player', 'Offspec', 'DKP');
    $_SESSION['item_loot']->order_by = 'Date';
    $_SESSION['item_loot']->sort = 'DESC';
    $_SESSION['item_loot']->results_array = $this->loot_info;
    $total_xml .= $_SESSION['item_loot']->display_results('Loot');

    $total_xml .= '</itemView>';

    $total_dom = new DOMDocument;
    $total_dom->loadXML($total_xml);
    return $total_dom;
  }

  function load_item_list() {
    $sql = "SELECT item_id, item.name AS \"Item\", dkp AS \"DKP\", blizz_id,
              COUNT(loot.item_id) AS \"Times Looted\", MAX(raid.date) AS \"Last Drop\"
            FROM item JOIN loot USING (item_id) JOIN raid USING (raid_id)";
    if (isset($_SESSION['cur_tier'])) {
      $sql .= " WHERE item_id IN (SELECT item_id
                                  FROM loot JOIN raid USING (raid_id)
                                    JOIN event USING (event_id)
                                    JOIN instance USING (instance_id)
                                  WHERE tier_level = '".mysql_real_escape_string($_SESSION['cur_tier'])."')";
    }
    $sql .= " GROUP BY item_id, item.name, dkp, blizz_id";
            
    $rs = DB::query_db($sql);
    while ($row = mysql_fetch_assoc($rs)) {
      $row['click'] = 'item.php?id='.$row['item_id'];
      if ($row['blizz_id'] != 0) {
        $row['Item'] = get_wowhead_link($row['Item'], $row['item_id'], $row['blizz_id']);
      }
      $this->items[] = $row;
    }
  }

  function display_item_list() {
    $total_xml = '<itemResults>';

    $_SESSION['item_results'] = new results();
    $_SESSION['item_results']->columns = array('Item', 'DKP', 'Times Looted', 'Last Drop');
    $_SESSION['item_results']->order_by = 'Name';
    $_SESSION['item_results']->sort = 'DESC';
    $_SESSION['item_results']->results_array = $this->items;

    $total_xml .=  $_SESSION['item_results']->display_results();
    $total_xml .= '</itemResults>';
    $total_dom = new DOMDocument;
    $total_dom->loadXML($total_xml);
    return $total_dom;
  }

}

?>

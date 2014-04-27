<?php
require_once(dirname(__FILE__).'/config.inc.php');
require_once(dirname(__FILE__).'/results.php');
require_once(dirname(__FILE__).'/lib.inc.php');

/* player_list class */
class player_list {
  public $players;

  function __construct() {
    $this->players = array();
  }

  function __destruct() {
  }

  function load_player_list() {
    $sql = "SELECT name, class_id
            FROM class
            ORDER BY name";
    $rs = DB::query_db($sql);
    while ($row = mysql_fetch_row($rs)) {
      $sql = "SELECT player_id
              FROM player
              WHERE class_id = ".$row[1]."
              ORDER BY name";
      $player_rs = DB::query_db($sql);
      while ($player_row = mysql_fetch_row($player_rs)) {
        $player_obj = new player();
        $player_obj->load_player($player_row[0], 'basic');
        $player_row = $player_obj->fetch_player_row();
        if ($player_row != 0) {
          $this->players[$row[0]][] = $player_row;
        }
      }
    }
  }

  function display_player_list() {
    /*they want em all, so do it by class*/
    $total_xml = '<playerResults>';
    foreach ($this->players AS $class => $players) {
      $total_xml .= $this->display_player_class_list($class);
    }
    $total_xml .= '</playerResults>';
    $total_dom = new DOMDocument;
    $total_dom->loadXML($total_xml);
    return $total_dom;
  }

  function display_player_class_list($class) {
    if (!isset($_SESSION['player_results'][$class]) || get_class($_SESSION['player_results'][$class]) != 'results') {
      $_SESSION['player_results'][$class] = new results();
      if ($_SESSION['cur_tier'] > 10) {
        $_SESSION['player_results'][$class]->columns = array('Name', 'Loot');
        $_SESSION['player_results'][$class]->order_by = 'Loot';
      }
      else {
        $_SESSION['player_results'][$class]->columns = array('Name', 'Current DKP');
        $_SESSION['player_results'][$class]->order_by = 'Current DKP';
      }
      $_SESSION['player_results'][$class]->sort = 'DESC';
    }
    $_SESSION['player_results'][$class]->results_array = $this->players[$class];
    return $_SESSION['player_results'][$class]->display_results($class);
  }

}

?>

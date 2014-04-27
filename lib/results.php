<?php
require_once(dirname(__FILE__).'/config.inc.php');
require_once(dirname(__FILE__).'/lib.inc.php');

/* results class for displaying tables*/
class results {
  public $results_array;
  public $page;
  public $order_by;
  public $sort;
  public $rpp;

  function __construct() {
    $this->results_array = array();
    $this->page = 1;
    $this->order_by = '';
    $this->sort = 'ASC';
    $this->rpp = 10;
  }

  function __destruct() {
  }

  function display_results($label = '') {
    if (isset($_GET['order'])) {
      $this->order_by = $_GET['order'];
    }
    if (isset($_GET['sort'])) {
      $this->sort = $_GET['sort'];
    }
    if (isset($_GET['page'])) {
      $this->page = $_GET['page'];
    }
    $total_vals = sizeof($this->results_array);
    if ($total_vals == 0) {
      $this->rpp = 1;
    }
    else {
      $this->rpp = $total_vals;
    }
    $start_val = ($this->page - 1) * $this->rpp;
    $end_val = $start_val + $this->rpp;
    if ($end_val > $total_vals) {
      $end_val = $total_vals;
    }
    $total_pages = ceil($total_vals / $this->rpp);

    /*sort the results according to sort/order_by*/
    $key_arr = $sort_arr = array();
    foreach ($this->results_array AS $key => $val) {
      $key_arr[$key] = $val['primary_key'];
      $sort_arr[$key] = strtolower($val[$this->order_by]);
    }
    if ($this->sort == 'DESC') {
      array_multisort($sort_arr, SORT_DESC, $key_arr, SORT_ASC, $this->results_array);
    }
    else {
      array_multisort($sort_arr, SORT_ASC, $key_arr, SORT_ASC, $this->results_array);
    }

    $urlp = '';
    if (isset($_GET['id'])) {
      $urlp = '&amp;id='.$_GET['id'];
    }

    $xml = '<resultTable label="'.$label.'">
              <head>';
    if ($this->page != 1) {
      $prefix = '<a href="?page='.($this->page - 1).'&amp;order='.$this->order_by.'&amp;sort='.$this->sort.$urlp.'">&lt;&lt;</a>';
    }
    if ($this->page != $total_pages) {
      $suffix = '<a href="?page='.($this->page + 1).'&amp;order='.$this->order_by.'&amp;sort='.$this->sort.$urlp.'">&gt;&gt;</a>';
    }
    $xml .= '<pagination>'.$prefix.($start_val + 1).'-'.$end_val.' of '.$total_vals.$suffix.'</pagination>';
    $xml .= '<columns>';
    foreach ($this->columns AS $column) {
      if ($this->order_by == $column && $this->sort == 'ASC') {
        $xml .= '<column><a href="?page='.$this->page.'&amp;order='.$column.'&amp;sort=DESC'.$urlp.'">'.$column.'</a></column>';
      }
      else {
        $xml .= '<column><a href="?page='.$this->page.'&amp;order='.$column.'&amp;sort=ASC'.$urlp.'">'.$column.'</a></column>';
      }
    }
    $xml .= '  </columns>
             </head>
             <body>
               <rows>';

    if (empty($this->results_array)) {
      $xml .= '<row><td colspan="9000"><em>No Results Found</em></td></row>';
    }
    for ($i=$start_val; $i<$end_val; $i++) {
      $cur_row = $this->results_array[$i];
      if (isset($cur_row['click'])) {
        $xml .= '<row clickable="true" href="'.$cur_row['click'].'">';
      }
      else {
        $xml .= '<row>';
      }
      foreach ($this->columns AS $col) {
        $xml .= '<td>'.$cur_row[$col].'</td>';
      }
      $xml .= '</row>';
    }

    $xml .= '  </rows>
            </body>
          </resultTable>';
    return $xml;
  }

}

?>

<?php
require_once(dirname(__FILE__).'/config.inc.php');
require_once(dirname(__FILE__).'/lib.inc.php');

/* db class */
class DB {
  public static $db_host;
  public static $db_name;
  public static $db_user;
  public static $db_pass;
  public static $db_conn;

  public static function init_db() {
    self::$db_host = constant('DATABASE_HOST');
    self::$db_name = constant('DATABASE_NAME');
    self::$db_user = constant('DATABASE_USER');
    self::$db_pass = constant('DATABASE_PASS');

    self::connect_db();
  }

  public function __destruct() {
    self::disconnect_db();
  }

  public static function connect_db() {
    self::$db_conn = mysql_connect(self::$db_host, self::$db_user, self::$db_pass);
    mysql_select_db(self::$db_name, self::$db_conn);
  }

  public static function disconnect_db() {
    mysql_close(self::$db_conn);
  }

  public static function query_db($query) {
    $rs = mysql_query($query) or die('Unable to query database: '.mysql_error());

    return $rs;
  }

  public static function query_str_db($query) {
    $rs = self::query_db($query);
    $row = mysql_fetch_row($rs);
    return (string)$row[0];
  }

  public static function query_int_db($query) {
    $rs = self::query_db($query);
    $row = mysql_fetch_row($rs);
    return (int)$row[0];
  }

  public static function query_double_db($query) {
    $rs = self::query_db($query);
    $row = mysql_fetch_row($rs);
    return (double)$row[0];
  }

  public static function query_row_db($query) {
    $rs = self::query_db($query);
    $row = mysql_fetch_row($rs);
    return $row;
  }

  public static function query_assoc_db($query) {
    $rs = self::query_db($query);
    $row = mysql_fetch_assoc($rs);
    return $row;
  }

  public static function query_assoc_multi_db($query) {
    $rs = self::query_db($query);
    $return_arr = array();
    while ($row = mysql_fetch_row($rs)) {
      $return_arr[$row[0]] = $row[1];
    }
    return $return_arr;
  }

  public static function query_array_db($query) {
    $rs = self::query_db($query);
    $rets = array();
    while ($row = mysql_fetch_row($rs)) {
      $rets[] = $row[0];
    }
    $ret = implode(',', $rets);
    return $ret;
  }
}
?>

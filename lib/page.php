<?php
require_once(dirname(__FILE__).'/config.inc.php');

/* page class */
class Page {
  var $xsl;
  var $xml;

  function __construct() {
  }

  function display_page($xml_input = NULL) {
    $this->xsl = new DOMDocument;
    $this->xsl->load('xsl/layout.xsl');

    $this->xml = new DOMDocument;
    $this->xml->load($xml_input);

    $proc = new XSLTProcessor();
    $proc->registerPhpFunctions();
    $proc->importStyleSheet($this->xsl);

    echo $proc->transformToXml($this->xml);
  }
}
?>

<?php
// $Header: /cvsroot/tikiwiki/lib/html2pdf/css.display.inc.php,v 1.1.1.1 2006-02-06 15:38:39 nikchankov Exp $

class CSSDisplay extends CSSProperty {
  function CSSDisplay() { $this->CSSProperty(false, false); }

  function get_parent() { 
    if (isset($this->_stack[1])) {
      return $this->_stack[1][0]; 
    } else {
      return 'block';
    };
  }

  function default_value() { return "inline"; }

  function parse($value) { return $value; }
}

register_css_property('display', new CSSDisplay);

function is_inline_element($display) {
  return 
    $display == "inline" ||
    $display == "inline-table" ||
    $display == "compact" ||
    $display == "run-in" || 
    $display == "-button" ||
    $display == "-checkbox" ||
    $display == "-iframe" ||
    $display == "-image" ||
    $display == "inline-block" ||
    $display == "-radio" ||
    $display == "-select";
}
?>
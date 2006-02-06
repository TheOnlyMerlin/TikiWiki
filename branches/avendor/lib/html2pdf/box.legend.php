<?php
// $Header: /cvsroot/tikiwiki/lib/html2pdf/box.legend.php,v 1.1.1.1 2006-02-06 15:39:10 nikchankov Exp $

class LegendBox extends GenericContainerBox {
  function &create(&$root) {
    $box = new LegendBox($root);
    $box->create_content($root);
    return $box;
  }

  function LegendBox(&$root) {
    // Call parent constructor
    $this->GenericContainerBox();

    $this->_current_x = 0;
    $this->_current_y = 0;
  }

  // Flow-control
  function reflow(&$parent, &$context) {
    GenericBox::reflow($parent, $context);

    // Determine upper-left _content_ corner position of current box 
    $this->put_left($parent->get_left_padding());
    $this->put_top($parent->get_top_padding());

    // Legends will not wrap
    $this->put_full_width($this->get_max_width($context));

    // Reflow contents
    $this->reflow_content($context);

    // Adjust legend position
    $height = $this->get_full_height();
    $this->offset(units2pt(LEGEND_HORIZONTAL_OFFSET) + $this->get_extra_left(),
                  $height/2);
    // Adjust parent position
    $parent->offset(0, -$height/2);
    // Adjust parent content position
    for ($i=0; $i<count($parent->content); $i++) {
      if ($parent->content[$i]->uid != $this->uid) {
        $parent->content[$i]->offset(0, -$height/2);
      }
    }
    $parent->_current_y -= $height/2;
    
    $parent->extend_height($this->get_bottom_margin());
  }

  function show(&$driver) {
    // draw generic box
    return GenericContainerBox::show($driver);
  }
}
?>
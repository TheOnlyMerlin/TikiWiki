<?php
// $Header: /cvsroot/tikiwiki/lib/html2pdf/encoding.inc.php,v 1.1.1.1 2006-02-06 15:39:49 nikchankov Exp $

function code_to_utf8($code) {
  if ($code < 128) {
    return chr($code);
  };

  if ($code < 2048) {
    return chr(0xC0 | (($code >> 6) & 0x1F)) . chr(0x80 | ($code & 0x3F));
  };

  if ($code < 65536) {
    return chr(0xE0 | (($code >> 12) & 0x0F)) . chr(0x80 | (($code >> 6) & 0x3F)) . chr(0x80 | ($code & 0x3F));
  };

  return 
    chr(0xF0 | (($code >> 18) & 0x07)) . 
    chr(0x80 | (($code >> 12) & 0x3F)) . 
    chr(0x80 | (($code >>  6) & 0x3F)) . 
    chr(0x80 | ($code & 0x3F));
}

function something_to_utf8($html, &$mapping) {
  for ($i=0; $i < strlen($html); $i++) {
    $replacement = $mapping[$html{$i}];
    if ($replacement != $html{$i}) {
      $html = substr_replace($html, $replacement, $i, 1);
      $i += strlen($replacement) - 1;
    };
  };
  return $html;
}
?>
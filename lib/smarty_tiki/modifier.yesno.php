<?php

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}

function smarty_modifier_yesno($string) {
        switch ( $string ) {
                case 'y': return tra('Yes'); break;
                case 'n': return tra('No'); break;
                default: return $string;
        }
}

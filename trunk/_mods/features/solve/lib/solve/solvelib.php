<?php
/**
 * @version $Id: solvelib.php,v 1.1 2005-09-21 21:18:45 michael_davey Exp $
 * @package Solve
 * @copyright (C) 2005 the Tiki community
 * @license http://www.gnu.org/copyleft/lgpl.html GNU/LGPL
 */

@set_magic_quotes_runtime( 0 );

/**
 * Solve class
 *
 * Provide a few supporting API functions
 * @package Solve
 */
class SolveLib {
	/** @var database Internal database class pointer */
	var $_db=null;

	/**
	* Class constructor
	* @param database A database connection object
	* @param string The url option
	* @param string The path of the TikiWiki directory
	*/
	function Solve( &$db, $option, $basePath ) {
		$this->_db =& $db;
	}

    function _getPath( $option, $basePath='.' ) {
        $option = strtolower( $option );

        if (strlen($option) > 5) {
          $name = substr( $option, 5 );
        } else {
          $name = $option;
        }
        $name = "solve" . $name;

        // components
        if (file_exists( "$basePath/lib/solve/$name.php" )) {
                return "$basePath/lib/solve/$name.php";
        }
    }
}

    function solve_getBody( $option, $basePath='.' ) {
        global $gid, $task, $base_url, $access;
        $sh = new SolveHelper();
        if ($path = $sh->_getPath( $option, $basePath )) {
            $access->check_page($user, 'feature_crm');
            require_once( $path );
        } else {
            header ("Status: 402 Found"); /* PHP3 */
            header ("HTTP/1.0 402 Found"); /* PHP4 */
            header("Location: $base_url/tiki-index.php?page=$option");
            die;
            exit;
        }

    }


/**
* Utility function to return a value from a named array or a specified default
*/
function solve_get_param( &$arr, $name ) {
    if (isset( $arr[$name] )) {
        if (is_string( $arr[$name] )) {
            $arr[$name] = trim( $arr[$name] );
            $arr[$name] = strip_tags( $arr[$name] );
            if (!get_magic_quotes_gpc()) {
                $arr[$name] = addslashes( $arr[$name] );
            }
        }
        return $arr[$name];
    } else {
        return null;
    }
}
?>

<?php
    /**
    * Include the library {@link PluginsLib}
    */
    require_once "lib/wiki/pluginslib.php";
    /**
    * Backlinks plugin
    * List all pages which link to specific pages (same as tiki-backlinks.php)
    *
    * Params:
    * <ul>
    * <li>info (allows multiple columns, joined by '|') : hits,lastModif,user,ip,len,comment, 
    * creator, version, flag, versions,links,backlinks
    * <li> exclude (allows multiple pagenames) : HomePage|RecentChanges
    * <li> include_self     : by default, false
    * <li> noheader         : by default, false
    * <li> page             :by default, the current page.
    * </ul>
    *
    * @package TikiWiki
    * @subpackage TikiPlugins
    * @author Claudio Bustos
    * @version 1.0
    */
    class WikiPluginBackLinks extends PluginsLib {
        var $expanded_params = array("exclude", "info");
        function getDefaultArguments() {
            return array('exclude' => array(),
                'include_self' => 0,
                'noheader' => 0,
                'page' => $_REQUEST["page"],
                'info' => false );
        }
        function getName() {
            return tra("BackLinks");
        }
        function getDescription() {
            return tra("List all pages which link to specific pages").":<br />~np~{BACKLINKS(info=>hits|user,exclude=>HomePage|SandBox,include_self=>1,noheader=>0,page=>HomePage)}{BACKLINKS}~/np~";
        }
        function getVersion() {
            return preg_replace("/[Revision: $]/", '',
                "\$Revision: 1.4 $");
        }
        function run ($data, $params) {
            global $wikilib;
            $params = $this->getParams($params, true);
            $aInfoPreset = array_keys($this->aInfoPresetNames);
            extract ($params);
            /////////////////////////////////
            // Create a valid list for $info
            /////////////////////////////////
            //
            if ($info) {
                $info_temp = array();
                foreach($info as $sInfo) {
                    if (in_array(trim($sInfo), $aInfoPreset)) {
                        $info_temp[] = trim($sInfo);
                    }
                    $info = $info_temp?$info_temp:
                    false;
                }
            }
            //
            /////////////////////////////////
            // Process backlinks
            /////////////////////////////////
            //
            $backlinks = $wikilib->get_backlinks($page);
            foreach($backlinks as $backlink) {
                if (!in_array($backlink["fromPage"], $exclude)) {
                    $aBackRequest[] = $backlink["fromPage"];
                }
            }
            if ($include_self) {
                $aBackRequest[] = $page;
            }
            $sOutput = "";
            $aPages = $this->list_pages(0, -1, 'pageName_desc', $aBackRequest);
            //
            /////////////////////////////////
            // Start of Output
            /////////////////////////////////
            //
            if (!$noheader) {
                // Create header
                $count = $aPages["cant"];
                if (!$count) {
                    $sOutput  .= tra("No pages link to")." (($page))";
                } elseif ($count == 1) {
                    $sOutput  .= tra("One page links to")." (($page))";
                } else {
                    $sOutput = "$count ".tra("pages links to")." (($page))";
                }
                $sOutput  .= "\n";
            }

            $sOutput.=PluginsLibUtil::createTablePages($aPages["data"],$info);
            return $sOutput;
        }
    }
    function wikiplugin_backlinks($data, $params) {
        $plugin=new WikiPluginBackLinks();
        return $plugin->run($data, $params);
    }
    function wikiplugin_backlinks_help() {
        $plugin=new WikiPluginBackLinks();
        return $plugin->getDescription();
    }
?>
<?
require_once('tiki-setup.php');
if($rss_forum != 'y') {
 die;
}
if(!isset($_REQUEST["forumId"])) {
  die;
}

header("content-type: text/xml");
$foo = parse_url($_SERVER["REQUEST_URI"]);
$foo1=str_replace("tiki-forum_rss.php",$tikiIndex,$foo["path"]);
$foo2=str_replace("tiki-forum_rss.php","img/tiki.jpg",$foo["path"]);
$foo3=str_replace("tiki-forum_rss","tiki-view_forum_thread",$foo["path"]);
$home = 'http://'.$_SERVER["SERVER_NAME"].$foo1;
$img = 'http://'.$_SERVER["SERVER_NAME"].$foo2;
$read = 'http://'.$_SERVER["SERVER_NAME"].$foo3;

$now = date("U");
$changes = $tikilib->list_forum_topics($_REQUEST["forumId"],0,$max_rss_forum,'commentDate_desc', '');

//print_r($changes);die;
print('<');
print('?xml version="1.0" ?');
print('>');
?>
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
         xmlns="http://purl.org/rss/1.0/">
<channel rdf:about="<?=$home?>">
  <title>Tiki RSS feed for forums</title>
  <link><?=$home?></link>
  <description>
    Last topics in forums
  </description>
  <image rdf:resource="<?=$img?>" />
  <items>
    <rdf:Seq>
      <?php
        
        foreach($changes["data"] as $chg) {
          print('<rdf:li resource="'.$read.'?parentId='.$chg["threadId"].'">'."\n");
          print('<title>'.$chg["title"].': '.date("m/d/Y h:i",$chg["commentDate"]).'</title>'."\n");
          print('<link>'.$read.'?parentId='.$chg["threadId"].'</link>'."\n");
          $data = date("m/d/Y h:i",$chg["commentDate"]);
          print('<description>'.$chg["data"].'</description>'."\n");
          print('</rdf:li>'."\n");
        }        
      ?>
    </rdf:Seq>  
  </items>
</channel>
</rdf:RDF>       
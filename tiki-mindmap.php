<?php
require_once 'tiki-setup.php';
require_once 'lib/wiki/wikilib.php';

$access->check_feature( 'feature_wiki_mindmap' );

if( ! file_exists( 'files/visorFreemind.swf' ) ) {
	$smarty->assign( 'missing', 'files/visorFreemind.swf' );
	$smarty->assign('mid','tiki-mindmap.tpl');
	$smarty->display("tiki.tpl");
	exit;
}

if( isset( $_REQUEST['export'] ) ) { // {{{
	// Output the page relations in WikiMindMap format (derived from Freemind)

	$dom = new DOMDocument;
	$dom->appendChild( $root = $dom->createElement( 'map' ) );
	$root->setAttribute( 'version', '0.8.0' );

	function create_node( $dom, $text, $link = true )
	{
		global $wikilib;

		$node = $dom->createElement('node');
		$node->setAttribute( 'TEXT', $text );
		$node->setAttribute( 'STYLE', 'bubble' );

		if( $link ) {
			$node->setAttribute( 'WIKILINK', $wikilib->sefurl( $text ) );
			$node->setAttribute( 'MMLINK', 'tiki-mindmap.php?page=' . urlencode( $text ) );
		}

		return $node;
	}

	function populate_node( $node, $pageName, $remainingLevels = 3, $pages = array() )
	{
		global $wikilib, $tikilib, $user;
		$child = $wikilib->wiki_get_neighbours( $pageName );
		$child = array_diff( $child, $pages );

		foreach( $child as $page ) {
			if( ! $tikilib->user_has_perm_on_object( $user, $page, 'wiki page', 'tiki_p_view' ) )
				continue;

			$node->appendChild( $new = create_node( $node->ownerDocument, $page ) );

			if( $remainingLevels != 0 )
				populate_node( $new, $page, $remainingLevels - 1, array_merge( $pages, array( $page, $pageName ) ) );
			else
				$new->setAttribute( 'STYLE', 'fork' );
		}

		if( count( $child ) == 0 )
			$node->setAttribute( 'STYLE', 'fork' );
	}

	if( ! isset( $_REQUEST['export'] ) ) {
		$root->appendChild( create_node( $dom, tra('No page provided.'), false ) );
	} elseif( ! $tikilib->page_exists( $_REQUEST['export'] ) ) {
		$root->appendChild( create_node( $dom, tr('Page "%0" does not exist', $_REQUEST['export']), false ) );
	} else {
		$root->appendChild( $parent = create_node( $dom, $_REQUEST['export'] ) );
		populate_node( $parent, $_REQUEST['export'] );
	}

	header( 'Content-Type: text/xml' );
	echo $dom->saveXML();
	exit;
} // }}}

$page = isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : $prefs['wikiHomePage'];

$ePage = urlencode( $page );

$plugin = $tikilib->plugin_execute( 'flash', '', array(
	'movie' => 'files/visorFreemind.swf',
	'bgcolor' => '#cccccc',
	'width' => 600,
	'height' => 500,
	'openUrl' => '_blank',
	'initLoadFile' => "tiki-mindmap.php?export={$ePage}",
	'startCollapsedToLevel' => 1,
	'mainNodeShape' => 'bubble',
) );
$parsed = $tikilib->parse_data( $plugin );

$smarty->assign( 'mindmap', $parsed );
$smarty->assign( 'page', $page );

$smarty->assign('mid','tiki-mindmap.tpl');
$smarty->display("tiki.tpl");

<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/**
 * For TextBacklink Protocol
 */
class HtmlFeed_Remote
{
	var $feedUrl = "";
	var $feedName = "";
	var $name = "";
	var $links = array();
	var $link = array();
	var $contents = array();
	var $lastModif = 0;
	
	public function __construct($feedUrl = "")
	{
		$this->feedUrl = $feedUrl;
		
		$feedName = str_replace("http://", "", $feedUrl);
		$feedName = str_replace("https://", "", $feedName);
		$feedName = array_shift(explode('/', $feedName));
		
		$this->feedName = $feedName."_remote_htmlfeed";
	}
	
	private function replace()
	{
		$file = FileGallery_File::filename($this->feedName);
		
		$new = $this->contents;
		$old = json_decode($file->data());
		
		if (!$file->exists()) {
			$file
				->setParam("filename", $this->feedName)
				->setParam("description", tr("An html feed from ") . $this->feedUrl)
				->create(json_encode($this->contents));
				
			return;
		}
		
		if ($old->feed->date < $new->feed->date) {
			$file->replace(json_encode($this->contents));	
		}
	}
	
	public function getLinks()
	{
		global $tikilib;
		if (!empty($this->links)) return $this->links;
		
		$contents = file_get_contents($this->feedUrl);
		$contents = json_decode($contents);
		
		if (!empty($contents->feed->entry) && $contents->feed->type == "htmlfeed")
		{
			$this->contents = $contents;
			$this->links = $contents->feed->entry;
			$this->replace();
		}
		
		return $this->links;
	}
	
	public function listLinkNames()
	{
		$result = array();
		foreach($this->getLinks() as $link) {
			if (!empty($link->name)) {
				$result[] = htmlspecialchars($link->name);
			}
		}
		return $result;
	}
	
	public function getLink($name)
	{
		foreach($this->getLinks() as $item) {
			if ($name == $item->name) {
				$this->link = $item;
			}
		}
		return $this->link;
	}
}
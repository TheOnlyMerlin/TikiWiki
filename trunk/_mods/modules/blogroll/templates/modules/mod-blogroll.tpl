{* $Header: /cvsroot/tikiwiki/_mods/modules/blogroll/templates/modules/mod-blogroll.tpl,v 1.1 2006-06-08 21:24:11 amette Exp $ *}

{if $feature_file_galleries eq 'y'}
{eval var="{tr}Blogroll{/tr}" assign="tpl_module_title"}
{tikimodule title=$tpl_module_title name="blogroll" flip=$module_params.flip decorations=$module_params.decorations}
<a href="tiki-download_file.php?fileId={$fileId}">Get the OPML</a>
{if $nonums eq 'y'}
	<ul>
{else}
	<ol>
{/if}
{section name=ix loop=$feeds}
	{if $feeds[ix].type eq 'complete'}
		<li>
			<a href="{$feeds[ix].attributes.HTMLURL}" title="{$feeds[ix].attributes.DESCRIPTION}">{$feeds[ix].attributes.TITLE}</a>
			<a href="{$feeds[ix].attributes.XMLURL}"><img alt="RSS" style="border: 0; vertical-align: text-bottom;" src="img/rss.png" /></a>
		</li>
	{elseif $feeds[ix].type eq 'open'}
		<li>
			{$feeds[ix].attributes.TEXT}
		</li>
		{if $nonums eq 'y'}
			<ul>
		{else}
			<ol>
		{/if}
	{elseif $feeds[ix].type eq 'close'}
		{if $nonums eq 'y'}
			</ul>
		{else}
			</ol>
		{/if}
	{/if}
{/section}
{if $nonums eq 'y'}
</ul>
{else}
</ol>
{/if}
{/tikimodule}
{/if}

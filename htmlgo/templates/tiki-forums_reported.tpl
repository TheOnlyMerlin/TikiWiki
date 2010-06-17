{popup_init src="lib/overlib.js"}

{title help="Forums" admpage="forums"}{tr}Reported messages for forum{/tr}&nbsp;{$forum_info.name|escape}{/title}

<div class="navbar">
	{button href="tiki-view_forum.php?forumId=$forumId" _text="{tr}Back to forum{/tr}"}
</div>

<h2>{tr}List of messages{/tr} ({$cant})</h2>
{* FILTERING FORM *}
{if $items or ($find ne '')}
<form action="tiki-forums_reported.php" method="post">
<input type="hidden" name="forumId" value="{$forumId|escape}" />
<input type="hidden" name="offset" value="{$offset|escape}" />
<input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />
<table>
<tr>
<td>
	<small>{tr}Find{/tr}</small>
	<input size="8" type="text" name="find" value="{$find|escape}" />
	<input type="submit" name="filter" value="{tr}Filter{/tr}" />
</td>
</tr>
</table>	
</form>
{/if}
{*END OF FILTERING FORM *}

{*LISTING*}
<form action="tiki-forums_reported.php" method="post">
<input type="hidden" name="forumId" value="{$forumId|escape}" />
<input type="hidden" name="offset" value="{$offset|escape}" />
<input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />
<input type="hidden" name="find" value="{$find|escape}" />
<table class="normal">
<tr>
{if $items}
<th></th>
{/if}
<th>{tr}Message{/tr}</th>
<th>{tr}Reported by{/tr}</th>
</tr>
{cycle values="odd,even" print=false}
{section name=ix loop=$items}
<tr>
	<td style="text-align:center;" class="{cycle advance=false}">
	  <input type="checkbox" name="msg[{$items[ix].threadId}]" />
	</td>
  
	<td class="{cycle advance=false}" style="text-align:left;">
		<a class="link" href="tiki-view_forum_thread.php?topics_offset=0&amp;topics_sort_mode=commentDate_desc&amp;topics_threshold=0&amp;topics_find=&amp;forumId={$items[ix].forumId}&amp;comments_parentId={$items[ix].parentId}">{$items[ix].title|escape}</a>
	</td>
	
	<td class="{cycle}" style="text-align:left;">
		{$items[ix].user|username}
	</td>

</tr>
{sectionelse}
<tr>
	<td class="{cycle advance=false}" colspan="2">
	{tr}No records to display{/tr}
	</td>
</tr>	
{/section}
</table>
{if $items}
{tr}Perfom action with checked:{/tr} <input type="submit" name="del" value=" {tr}Un-report{/tr} " />
{/if}

</form>
{* END OF LISTING *}

{pagination_links cant=$cant_pages step=$prefs.maxRecords offset=$offset}{/pagination_links}

{* $Id$ *}
{strip}
	{if $showtitle eq 'y'}<div class="pagetitle">{$tracker_info.name}</div>{/if}
	{if $showdesc eq 'y'}
<div class="wikitext">
		 {if $tracker_info.descriptionIsParsed eq 'y'}
		 	 {wiki}{$tracker_info.description}{/wiki}
		 {else}
		 	 {$tracker_info.description}
		 {/if}
</div>
	{/if}
	{if isset($user_watching_tracker)}
		{if $user_watching_tracker eq 'n'}
			<a href="{$smarty.server.REQUEST_URI}{if strstr($smarty.server.REQUEST_URI, '?')}&amp;{else}?{/if}trackerId={$trackerId}&amp;watch=add" title="{tr}Monitor{/tr}" class="trackerlistwatch">
				{icon _id='eye' align="right" hspace="1" alt="{tr}Monitor{/tr}"}
			</a>
		{elseif $user_watching_tracker eq 'y'}
			<a href="{$smarty.server.REQUEST_URI}{if strstr($smarty.server.REQUEST_URI, '?')}&amp;{else}?{/if}trackerId={$trackerId}&amp;watch=stop" title="{tr}Stop Monitor{/tr}" class="trackerlistwatch">
			   {icon _id='no_eye' align="right" hspace="1" alt="{tr}Stop Monitor{/tr}"}
			</a>
		{/if}
	{/if}
	{if $showrss eq 'y'}
			<a href="tiki-tracker_rss.php?trackerId={$trackerId}">{icon _id='feed' align="right" hspace="1" alt="{tr}RSS feed{/tr}"}</a>
	{/if}

{if !empty($sortchoice)}
	<div class="trackerlistsort">
		<form method="post">
			{include file='tracker_sort_input.tpl'}
			<input type="submit" name="sort" value="{tr}Sort{/tr}" />
		</form>
	</div>
{/if}

	{if $shownbitems eq 'y'}<div class="nbitems">{tr}Items found:{/tr} {$count_item}</div>{/if}

	{if $cant_pages > 1 or $tr_initial or $showinitials eq 'y'}
		{initials_filter_links _initial='tr_initial'}
	{/if}

	{if $checkbox && $items|@count gt 0 && empty($tpl)}<form method="post" action="{if empty($checkbox.action)}#{else}$checkbox.action{/if}">{/if}

	{if $trackerlistmapview}
		{wikiplugin _name="googlemap" name=$trackerlistmapname type="objectlist" width="400" height="400"}{/wikiplugin}
	{/if}

	{if empty($tpl)}
<table class="normal wikiplugin_trackerlist">

		{if $showfieldname ne 'n' and empty($tpl)}
	<tr>

			{if $checkbox}<th>{$checkbox.title}</td>{/if}
			{if ($showstatus ne 'n') and ($tracker_info.showStatus eq 'y' or ($tracker_info.showStatusAdminOnly eq 'y' and $tiki_p_admin_trackers eq 'y'))}
		<th class="auto" style="width:20px;">&nbsp;</th>
			{/if}
			{if $showitemrank eq 'y'}<th>{tr}Rank{/tr}</th>{/if}
			{foreach key=jx item=ix from=$fields}
				{if $ix.isPublic eq 'y' and ($ix.isHidden eq 'n' or $ix.isHidden eq 'c' or $ix.isHidden eq 'p' or $tiki_p_admin_trackers eq 'y') 
					and $ix.type ne 'x' and $ix.type ne 'h' and in_array($ix.fieldId, $listfields) and ($ix.type ne 'p' or $ix.options_array[0] ne 'password') 
					and (empty($ix.visibleBy) or in_array($default_group, $ix.visibleBy) or $tiki_p_admin_trackers eq 'y')}
					{if $ix.type eq 'l'}
		<th class="auto field{$ix.fieldId}">{$ix.name|default:"&nbsp;"}</th>
					{elseif $ix.type eq 's' and $ix.name eq "Rating"}
						{if $tiki_p_admin_trackers eq 'y' or $perms.tiki_p_tracker_view_ratings eq 'y'}
		<th class="auto field{$ix.fieldId}">
							{self_link _sort_arg='tr_sort_mode'|cat:$iTRACKERLIST _sort_field='f_'|cat:$ix.fieldId}{$ix.name|default:"&nbsp;"}{/self_link}</th>
						{/if}
					{else}
		<th class="auto field{$ix.fieldId}">
						{self_link _sort_arg='tr_sort_mode'|cat:$iTRACKERLIST _sort_field='f_'|cat:$ix.fieldId}{$ix.name|default:"&nbsp;"}{/self_link}
		</th>
					{/if}
				{/if}
			{/foreach}
			{if $showcreated eq 'y'}
		<th>{self_link _sort_arg='tr_sort_mode'|cat:$iTRACKERLIST _sort_field='created'}{tr}Created{/tr}{/self_link}</th>
			{/if}
			{if $showlastmodif eq 'y'}
		<th>{self_link _sort_arg='tr_sort_mode'|cat:$iTRACKERLIST _sort_field='lastModif'}{tr}LastModif{/tr}{/self_link}</th>
			{/if}
			{if $tracker_info.useComments eq 'y' and $tracker_info.showComments eq 'y' and $tiki_p_tracker_view_comments ne 'n'}
		<th style="width:5%">{tr}Coms{/tr}</th>
			{/if}
			{if $tracker_info.useAttachments eq 'y' and  $tracker_info.showAttachments eq 'y'}
		<th style="width:5%">{tr}atts{/tr}</th>
			{/if}
			{if $showdelete eq 'y' && ($tiki_p_admin_trackers eq 'y' or $perms.tiki_p_modify_tracker_items eq 'y')}
		<th>{tr}Action{/tr}</th>
			{/if}

	</tr>
		{/if}
	{/if}

	{cycle values="odd,even" print=false}
	{assign var=itemoff value=0}
	{section name=user loop=$items}

{* ------- popup ---- *}
		{if !empty($popupfields)}
			{capture name=popup}
<div class="cbox">
	<table>
				{cycle values="odd,even" print=false}
				{foreach from=$items[user].field_values item=f}
					{if in_array($f.fieldId, $popupfields)}
		<tr><th class="{cycle advance=false}">{$f.name}</th><td class="{cycle}">{include file='tracker_item_field_value.tpl' field_value=$f item=$items[user]}</td></tr>
					{/if}
				{/foreach}
	</table>
</div>
			{/capture}
			{assign var=showpopup value='y'}
		{else}
			{assign var=showpopup value='n'}
		{/if}


		{if empty($tpl)}

	<tr class="{cycle}">
			{if $checkbox}
		<td><input type={if $checkbox.radio eq 'y'}"radio"{else}"checkbox"{/if} name="{$checkbox.name}[]" value="{if isset($items[user].field_values[$checkbox.ix])}{$items[user].field_values[$checkbox.ix].value|escape}{else}{$items[user].itemId}{/if}" /></td>
			{/if}
			{if ($showstatus ne 'n') and ($tracker_info.showStatus eq 'y' or ($tracker_info.showStatusAdminOnly eq 'y' and $tiki_p_admin_trackers eq 'y'))}
		<td class="auto" style="width:20px;">
				{assign var=ustatus value=$items[user].status|default:"c"}
				{html_image file=$status_types.$ustatus.image title=$status_types.$ustatus.label alt=$status_types.$ustatus.label}
		</td>
			{/if}
			{if $showitemrank eq 'y'}
		<td>{math equation="x+y" x=$smarty.section.user.rownum y=$tr_offset}</td>
			{/if}

{* ------------------------------------ *}
			{if !isset($list_mode)}{assign var=list_mode value="y"}{/if}
			{section name=ix loop=$items[user].field_values}
				{if $items[user].field_values[ix].isPublic eq 'y' and ($items[user].field_values[ix].isHidden eq 'n' or $items[user].field_values[ix].isHidden eq 'c' 
					or $items[user].field_values[ix].isHidden eq 'p' or $tiki_p_admin_trackers eq 'y') and $items[user].field_values[ix].type ne 'x' and $items[user].field_values[ix].type ne 'h' 
					and in_array($items[user].field_values[ix].fieldId, $listfields) and ($items[user].field_values[ix].type ne 'p' or $items[user].field_values[ix].options_array[0] ne 'password') 
					and (empty($items[user].field_values[ix].visibleBy) or in_array($default_group, $items[user].field_values[ix].visibleBy) or $tiki_p_admin_trackers eq 'y')}
		<td class={if $items[user].field_values[ix].type eq 'n' or $items[user].field_values[ix].type eq 'q' or $items[user].field_values[ix].type eq 'b'}"numeric"{else}"auto"{/if}
					{if $items[user].field_values[ix].type eq 'b'} style="padding-right:5px"{/if}>
					{if $items[user].field_values[ix].isHidden eq 'c' and $items[user].itemUser ne $user and $tiki_p_admin_trackers ne 'y'}
					{elseif isset($perms)}
						{include file='tracker_item_field_value.tpl' item=$items[user] field_value=$items[user].field_values[ix] list_mode=$list_mode
						tiki_p_view_trackers=$perms.tiki_p_view_trackers tiki_p_modify_tracker_items=$perms.tiki_p_modify_tracker_items tiki_p_modify_tracker_items_pending=$perms.tiki_p_modify_tracker_items_pending 
						tiki_p_modify_tracker_items_closed=$perms.tiki_p_modify_tracker_items_closed tiki_p_comment_tracker_items=$perms.tiki_p_comment_tracker_items}
					{else}
						{include file='tracker_item_field_value.tpl' item=$items[user] field_value=$items[user].field_values[ix] list_mode=$list_mode}
					{/if}
		</td>
				{/if}
			{/section}
{* ------------------------------------ *}

			{if $showcreated eq 'y'}
		<td>{if $tracker_info.showCreatedFormat}{$items[user].created|tiki_date_format:$tracker_info.showCreatedFormat}{else}{$items[user].created|tiki_short_datetime}{/if}</td>
			{/if}
			{if $showlastmodif eq 'y'}
		<td>{if $tracker_info.showLastModifFormat}{$items[user].lastModif|tiki_date_format:$tracker_info.showLastModifFormat}{else}{$items[user].lastModif|tiki_short_datetime}{/if}</td>
			{/if}
			{if $tracker_info.useComments eq 'y' and $tracker_info.showComments eq 'y' and $tiki_p_tracker_view_comments ne 'n'}
		<td style="text-align:center;">{$items[user].comments}</td>
			{/if}
			{if $tracker_info.useAttachments eq 'y' and $tracker_info.showAttachments eq 'y'}
		<td style="text-align:center;"><a href="tiki-view_tracker_item.php?trackerId={$trackerId}&amp;itemId={$items[user].itemId}&amp;show=att" 
link="{tr}List Attachments{/tr}"><img src="img/icons/folderin.gif" alt="{tr}List Attachments{/tr}" 
/></a>{$items[user].attachments}</td>
			{/if}
			{if $showdelete eq 'y' && ($tiki_p_admin_trackers eq 'y' or $perms.tiki_p_modify_tracker_items eq 'y')}
		<td>
				{if $tiki_p_admin_trackers eq 'y' or ($perms.tiki_p_modify_tracker_items eq 'y' and $items[user].status ne 'p' and $items[user].status ne 'c') or ($perms.tiki_p_modify_tracker_items_pending eq 'y' and $items[user].status eq 'p') or ($perms.tiki_p_modify_tracker_items_closed eq 'y' and $items[user].status eq 'c')}
					{self_link delete=`$items[user].itemId`}{icon _id=cross alt='{tr}Remove{/tr}'}{/self_link}
				{/if}
		</td>
			{/if}
	</tr>
		{assign var=itemoff value=$itemoff+1}
		{else}{* a pretty tpl *}
{* ------------------------------------ *}
   			{include file='tracker_pretty_item.tpl' fields=$items[user].field_values item=$items[user] wiki=$tpl}
		{/if}
	{/section}

	{if empty($tpl)}
		{if !empty($computedFields) and $items|@count gt 0}
		{assign var=itemoff value=0}
		<tr class='compute'>
			{if $checkbox}<td></td>{/if}
			{if ($showstatus ne 'n') and ($tracker_info.showStatus eq 'y' or ($tracker_info.showStatusAdminOnly eq 'y' and $tiki_p_admin_trackers eq 'y'))}<td></td>{/if}
			{if $showitemrank eq 'y'}<td></td>{/if}
			{foreach key=jx item=ix from=$fields}
				{if $ix.isPublic eq 'y' and ($ix.isHidden eq 'n' or $ix.isHidden eq 'c' or $ix.isHidden eq 'p' or $tiki_p_admin_trackers eq 'y') and $ix.type ne 'x' and $ix.type ne 'h' 
					and in_array($ix.fieldId, $listfields) and ($ix.type ne 'p' or $ix.options_array[0] ne 'password') and (empty($ix.visibleBy) or in_array($default_group, $ix.visibleBy) 
					or $tiki_p_admin_trackers eq 'y')}	
					{if isset($computedFields[$ix.fieldId])}
						<td class="numeric" style="padding-right:2px">
						{foreach from=$computedFields[$ix.fieldId] item=computedField name=computedField}
							{if $computedField.operator eq 'avg'}{tr}Average{/tr}{else}{tr}Sum{/tr}{/if}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							{include file='tracker_item_field_value.tpl' item=$items[user] field_value=$computedField list_mode=$list_mode}<br />
							{if !$smarty.foreach.computedField.last}{/if}
						{/foreach}
						</td>
					{else}
						<td></td>
					{/if}
				{/if}
			{/foreach}
			{if $showcreated eq 'y'}<td></td>{/if}
			{if $showlastmodif eq 'y'}<td></td>{/if}
			{if $tracker_info.useComments eq 'y' and $tracker_info.showComments eq 'y' and $tiki_p_tracker_view_comments ne 'n'}<td></td>{/if}
			{if $tracker_info.useAttachments eq 'y' and $tracker_info.showAttachments eq 'y'}<td></td>{/if}
		</tr>
		{/if}
</table>
		{if $items|@count eq 0}
			{tr}No records found{/tr}
		{elseif $checkbox}
			{if $checkbox.tpl}{include file="$checkbox.tpl"}{/if}
			{if !empty($checkbox.submit) and !empty($checkbox.title)}
				<br />
				<input type="submit" name="{$checkbox.submit}" value="{tr}{$checkbox.title}{/tr}" />
			{/if}
			</form>
		{/if}
	{/if}



	{if $more eq 'y'}
	<div class="more">
		{capture assign=moreUrl}
			{if $moreurl}{$moreurl}{else}tiki-view_tracker.php{/if}?trackerId={$trackerId}{if isset($tr_sort_mode)}&amp;sort_mode={$tr_sort_mode}{/if}
		{/capture}
		{button class='more' href="$moreUrl" _text="{tr}More...{/tr}"}
	</div>
	{elseif $showpagination ne 'n'}
		{pagination_links cant=$count_item step=$max offset=$tr_offset offset_arg=tr_offset}{/pagination_links}
	{/if}
	{if $export eq 'y' && ($tiki_p_admin_trackers eq 'y' || $perms.tiki_p_export_tracker eq 'y')}
		{button href="$exportUrl" _text="{tr}Export{/tr}"}
	{/if}
{/strip}

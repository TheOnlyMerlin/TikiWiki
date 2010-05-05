{strip}
{* $Id$ *}
{* param: list_mode(csv|y|n, default n), showlinks(y|n, default y), tiki_p_perm for this tracker, $field_value(type,value,displayedvalue,linkId,trackerId,itemId,links,categs,options_array, isMain), item(itemId,trackerId), parse(default y), showpopup, url *}

{if $field_value.type ne 'x'}
{* ******************** link to the item ******************** *}
{if $showlinks ne 'y' or (isset($field_value.showlinks) and $field_value.showlinks eq 'n')}
	{assign var='is_link' value='n'}
{elseif $field_value.isMain eq 'y'
 and ($tiki_p_view_trackers eq 'y' 
 	 or ($tiki_p_modify_tracker_items eq 'y' and $item.status ne 'p' and $item.status ne 'c')
	 or ($tiki_p_modify_tracker_items_pending eq 'y' and $item.status eq 'p')
	 or ($tiki_p_modify_tracker_items_closed eq 'y' and $item.status eq 'c')
	 or $tiki_p_comment_tracker_items eq 'y'
 	 or ($tracker_info.writerCanModify eq 'y' and $user and $my eq $user) or ($tracker_info.writerGroupCanModify eq 'y' and $group and $ours eq $group))}
	{if empty($url) and !empty($item.itemId)}
		{assign var=urll value="tiki-view_tracker_item.php?itemId=`$item.itemId`&amp;trackerId=`$item.trackerId`&amp;show=view"}
	{elseif strstr($url, 'itemId') and !empty($item.itemId)}
		{assign var=urll value=$url|regex_replace:"/itemId=?/":"itemId=`$item.itemId`"}
	{else}
		{assign var=urll value=$url}
	{/if}
	{if !empty($urll)}
		{assign var='is_link' value='y'}
	{else}
		{assign var='is_link' value='n'}
	{/if}
{else}
	{assign var='is_link' value='n'}
{/if}
{if $is_link eq 'y'}
	<a class="tablename" href="{$urll}{if $offset}&amp;offset={$offset}{/if}{if isset($reloff)}&amp;reloff={$reloff}{/if}{if $item_count}&amp;cant={$item_count}{/if}{foreach key=urlkey item=urlval from=$urlquery}{if $urlval}&amp;{$urlkey}={$urlval|escape:"url"}{/if}{/foreach}"{if $showpopup eq 'y'} {popup text=$smarty.capture.popup|escape:"javascript"|escape:"html" fullhtml="1" hauto=true vauto=true sticky=$stickypopup}{/if}>
{/if}
{* ******************** field with preprend ******************** *}
{if ($field_value.type eq 't' or $field_value.type eq 'n' or $field_value.type eq 'c') and !empty($field_value.options_array[2]) and $field_value.value != ''}
	<span class="formunit">{$field_value.options_array[2]}</span>
{/if}
{if $field_value.type eq 'q' and !empty($field_value.options_array[1])}
	<span class="formunit">{$field_value.options_array[1]}</span>
{/if}

{* ******************** field handling emptiness in a specific way  ******************** *}
{* -------------------- category -------------------- *}
{if $field_value.type eq 'e'}
	{if !empty($field_value.value) and $history == 'y'} {*history*}
		{$field_value.value|escape}
	{/if}
	{foreach from=$field_value.categs item=categ name=fcategs}
		{$categ.name|tr_if}
		{if !$smarty.foreach.fcategs.last}<br />{/if}
	{/foreach}

{* -------------------- items list -------------------- *}
{elseif $field_value.type eq 'l'}
	{foreach key=tid item=tlabel from=$field_value.links}
		{if $tlabel}
			{if $list_mode ne 'csv' and count($field_value.links) > 1}
				<div>
			{/if}
			{if $field_value.options_array[4] eq '1' and $showlinks ne 'n' and $list_mode ne 'csv' and !empty($field_value.options_array[0]) and !empty($tid)}
				<a href="tiki-view_tracker_item.php?itemId={$tid}&amp;trackerId={$field_value.options_array[0]}">
			{/if}
			{if $list_mode eq 'y' and $field_value.otherField.type eq 't'}
				{$tlabel|truncate:255:"..."}
			{else}
				{$tlabel}
			{/if}
			{if $field_value.options_array[4] eq '1' and $showlinks ne 'n' and $list_mode ne 'csv' and !empty($field_value.options_array[0]) and !empty($tid)}
				</a>
			{/if}
			{if $list_mode ne 'csv' and count($field_value.links) > 1}
				</div>
			{/if}
		{/if}
	{/foreach}

{* -------------------- static text -------------------- *}
{elseif $field_value.type eq 'S'}
	{if $field_value.options_array[1] ne '' and $list_mode eq 'y'}
		{if $field_value.options_array[0] eq 1}
			{wiki}{$field_value.description|truncate:$field_value.options_array[1]:"...":true}{/wiki}
		{else}
			{$field_value.description|truncate:$field_value.options_array[1]:"...":true|escape|nl2br}
		{/if}
	{else}
		{if $field_value.options_array[0] eq 1}
			{wiki}{$field_value.description}{/wiki}
		{else}
			{$field_value.description|escape|nl2br}
		{/if}
	{/if}

{* -------------------- empty field -------------------- *}
{elseif empty($field_value.value) and $field_value.value != '0' and $field_value.type ne 'U' and $field_value.type ne '*' and $field_value.type ne 's' and $field_value.type ne 'q' and $field_value.type ne 'n' and $field_value.type ne 'C'}
	{if $list_mode ne 'csv' and $is_link eq 'y'}&nbsp;{/if} {* to have something to click on *}

{* -------------------- text field, numeric, drop down, radio,user/group/IP selector, autopincrement, dynamic list *}
{elseif $field_value.type eq 'd' or $field_value.type eq 'D' or $field_value.type eq 'R'}
	{if $list_mode eq 'y'}
		{$field_value.value|tr_if|truncate:255:"..."|default:"&nbsp;"}
	{else}
		{$field_value.value|tr_if}
	{/if}

{* -------------------- text field, numeric, drop down, radio,user/group/IP selector, autopincrement, dynamic list *} 
{elseif $field_value.type eq  't' or $field_value.type eq 'n' or $field_value.type eq 'd' or $field_value.type eq 'D' or $field_value.type eq 'R' or $field_value.type eq 'u' or $field_value.type eq 'g' or $field_value.type eq 'I' or $field_value.type eq 'q' or $field_value.type eq 'w' or ($field_value.type eq 'C' and $field_value.computedtype ne 'f')}
	{if $list_mode eq 'y'}
		{if $field_value.type eq 'u' }
			{$field_value.value|username:true:true:false|truncate:255:"..."|escape|default:"&nbsp;"}
		{elseif !empty($field_value.value) || $is_link eq 'y'}			
			{$field_value.value|truncate:255:"..."|escape|default:"&nbsp;"}
		{elseif empty($field_value.value) && ($field_value.type eq 'n' or ($field_value.type eq 'C' and $field_value.computedtype ne 'f'))}
			{$field_value.value}
		{/if}		
	{elseif $list_mode eq 'csv'}
		{$field_value.value}
	{else}
		{if $field_value.type eq 'u' }
			{$field_value.value|username}
		{else}
			{$field_value.value|escape}
		{/if}		
	{/if}



{* -------------------- image -------------------- *}
{elseif $field_value.type eq 'i'}
	{if $list_mode eq 'csv'}
		{$field_value.value}
	{elseif $field_value.value ne ''}
		{if $list_mode ne 'n'}
			{if !empty($field_value.options_array[5]) and $prefs.feature_shadowbox eq 'y'}
				<a href="{$field_value.value}" rel="{if $field_value.options_array[5] eq 'item'}shadowbox[{$item.itemId}]{elseif $field_value.options_array[5]} eq 'individual'}shadowbox{else}shadowbox[{$field_value.options_array[5]}{/if};type=img">
			{/if}
			<img src="{$field_value.value}"{if $field_value.options_array[0]} width="{$field_value.options_array[0]}"{/if}{if $field_value.options_array[1]} height="{$field_value.options_array[1]}"{/if} alt="" />
			{if $field_value.options_array[5] and $prefs.feature_shadowbox eq 'y'}</a>{/if}
		{else}
			<img src="{$field_value.value}"{if $field_value.options_array[2]} width="{$field_value.options_array[2]}"{/if}{if $field_value.options_array[3]} height="{$field_value.options_array[3]}"{/if} alt="" />
		{/if}
	{else}
		<img src="img/icons/na_pict.gif" alt="n/a" />
	{/if}

{* -------------------- Multimedia -------------------- *}
{elseif $field_value.type eq 'M'}
	{if $field_value.value ne ''}	
	{if isset($cur_field.options_array[1]) and $field_value.options_array[1] ne '' }
		{assign var='Height' value=$prefs.MultimediaDefaultHeight}
	{else}
		{assign var='Height' value=$field_value.options_array[1]}
	{/if}
	{if isset($cur_field.options_array[2]) and $field_value.options_array[2] ne '' }
		{assign var='Length' value=$field_value.options_array[2]}
	{else}
		{assign var='Length' value=$prefs.MultimediaDefaultLength}
	{/if}
	{if $ModeVideo eq 'y' } { assign var="Height" value=$Height+$prefs.VideoHeight}{/if}
	{include file='multiplayer.tpl' url=$field_value.value w=$Length h=$Height video=$ModeVideo}
	{/if}

{* -------------------- file -------------------- *}
{elseif $field_value.type eq 'A'}
	{if $list_mode eq 'csv'}
		{$field_value.value}
	{else if $list_mode eq 'y' and !empty($field_value.options_array[0])}
		{if strstr($field_value.options_array[0], 'n')}
			{$field_value.info.filename|escape}&nbsp;
		{/if}
		{if strstr($field_value.options_array[0], 's')}
			[{$field_value.info.filesize|kbsize}]
		{/if}
		{if strstr($field_value.options_array[0], 't')}
			{$field_value.info.filename|iconify}&nbsp;
		{/if}
		{if strstr($field_value.options_array[0], 'm') && (strstr($field_value.info.filetype, 'audio') || strstr($field_value.info.filetype, 'video'))}
			{if $field_value.value ne ''}
			{wiki}{literal}{MEDIAPLAYER(mp3="tiki-download_item_attachment.php?attId={/literal}{$field_value.value}",style="max"){literal}}{/literal}{/wiki}
			{/if}
		{else}
			<a href="tiki-download_item_attachment.php?attId={$field_value.value}" title="{tr}Download{/tr}">{icon _id='disk' alt="{tr}Download{/tr}"}</a>
		{/if}
	{/if}

{* -------------------- preference -------------------- *}
{elseif $field_value.type eq 'p'}
	{if $list_mode eq 'csv'}
		{$field_value.value}
	{else}
		{$field_value.value|escape}
	{/if}

{* -------------------- page selector ------------------------- *} 
{elseif $field_value.type eq  'k'}
	{if isset($field_value.options_array[3]) && $field_value.options_array[3] eq 'n'}
		{$field_value.value}
	{elseif $list_mode eq 'y'}
		{wiki}(({$field_value.value|escape})){/wiki}
	{elseif $list_mode eq 'csv'}
		{$field_value.value}
	{else}
		{wiki}(({$field_value.value|escape})){/wiki}
	{/if}

{* -------------------- textarea -------------------- *}
{elseif $field_value.type eq 'a'}
	{if $field_value.options_array[4] ne '' and $field_value.options_array[4] ne 0 and $list_mode eq 'y'}
		{if $parse ne 'n'}
			{wiki}{$field_value.value|truncate:$field_value.options_array[4]:"...":true}{/wiki}
		{else}
			{$field_value.value|truncate:$field_value.options_array[4]:"...":true}
		{/if}
	{else}
		{if $parse ne 'n'} {* the field is not necessary parsed if you come from a itm list field *}
			{if $field_value.pvalue}{$field_value.pvalue}{else}{wiki}{$field_value.value}{/wiki}{/if}
		{elseif $list_mode eq 'csv'}
			{$field_value.value}
		{else}
			{$field_value.value|escape}
		{/if}	
	{/if}

{* -------------------- date -------------------- *}
{elseif $field_value.type eq 'f' or $field_value.type eq 'j' or $field_value.computedtype eq 'f'}
	{if $field_value.value}
		{if $field_value.options_array[0] eq 'd'}
			{$field_value.value|tiki_short_date}
		{elseif $field_value.options_array[0] eq 't'}
			{$field_value.value|tiki_short_time}
		{else}
			{$field_value.value|tiki_short_datetime}
		{/if}
	{else}&nbsp;{/if}

{* -------------------- checkbox -------------------- *}
{elseif $field_value.type eq 'c'}
	{if $field_value.value eq 'y' or $field_value.value eq 'on' or strtolower($field_value.value) eq 'yes'}
		{tr}Yes{/tr}
	{elseif $field_value.value eq 'n' or strtolower($field_value.value) eq 'no'}
		{tr}No{/tr}
	{else}
		{$field_value.value}
	{/if}

{* -------------------- item link -------------------- *}
{elseif $field_value.type eq 'r'}
    {if $field_value.options_array[2] eq '1' and $list_mode ne 'csv'}
		<a href="tiki-view_tracker_item.php?trackerId={$field_value.options_array[0]}&amp;itemId={$field_value.linkId}" class="link">
	{/if}
	{if $field_value.displayedvalue ne ""}
        {$field_value.displayedvalue}
    {else}
        {$field_value.value}
    {/if}
	{if $field_value.options_array[2] eq '1' and $list_mode ne 'csv'}
		</a>
	{/if}

{* -------------------- country -------------------- *}
{elseif $field_value.type eq 'y'}
	{if !empty($field_value.value) and $field_value.value ne 'None'}
		{assign var=o_opt value=$field_value.options_array[0]}
		{capture name=flag}
		{tr}{$field_value.value|replace:'_':' '}{/tr}
		{/capture}
		{if $o_opt ne '1' and $list_mode ne 'csv'}<img src="img/flags/{$field_value.value}.gif" title="{$smarty.capture.flag|replace:'_':' '}" alt="{$smarty.capture.flag|replace:'_':' '}" />{/if}
		{if $o_opt ne '1' and $o_opt ne '2' and $list_mode ne 'csv'}&nbsp;{/if}
		{if $o_opt ne '2'}{$smarty.capture.flag|replace:'_':' '}{/if}
	{else}
		&nbsp;
	{/if}

{* -------------------- mail -------------------- *}
{elseif $field_value.type eq 'm'}
	{if $list_mode ne 'csv' and $field_value.options_array[0] eq '1' and $field_value.value}
		{mailto address=$field_value.value|escape encode="hex"}
	{elseif $list_mode ne 'csv' and $field_value.options_array[0] eq '2' and $field_value.value}
		{mailto address=$field_value.value|escape encode="none"}
	{elseif $list_mode ne 'csv'}
		{$field_value.value}
	{else}
		{$field_value.value|escape|default:"&nbsp;"}
	{/if}

{* -------------------- url -------------------- *}
{elseif $field_value.type eq 'L'}
	{if $list_mode eq 'csv'}
		{$field_value.value|escape}
	{else}
		{wiki line='1'}{$field_value.value}{/wiki}
	{/if}

{* -------------------- rating, star -------------------- *}
{elseif ($field_value.type eq '*' or ($field_value.type eq 's' and ($field_value.name eq "Rating" or $field_value.name eq tra("Rating")))) and $tiki_p_tracker_view_ratings eq 'y'}
	{if $list_mode eq 'csv'}
		{$field_value.value}/{$field_value.voteavg}
	{else}
		{capture name=stat}
			{if empty($field_value.numvotes)}
				{tr}Number of votes{/tr}: 0
			{else}
				{tr}Number of votes{/tr}: {$field_value.numvotes|default:"0"}, {tr}Average{/tr}: {$field_value.voteavg|default:"0"}
				{if $tiki_p_tracker_vote_ratings eq 'y'}
					, {if isset($field_value.my_rate) && $field_value.my_rate !== false}{tr}Your rating{/tr}: {$field_value.my_rate}{else}{tr}You did not vote yet{/tr}{/if}
				{/if}
			{/if}
		{/capture}
		{capture name=myvote}
			{tr}My rating:{/tr} {$field_value.my_rate}
		{/capture}
		<span class="rating">
		<span style="white-space:nowrap">
		{section name=i loop=$field_value.options_array}
			{if $tiki_p_tracker_vote_ratings eq 'y' and isset($field_value.my_rate) and $field_value.options_array[i] === $field_value.my_rate}
				<b class="highlight">
					{if $field_value.voteavg >= $field_value.options_array[i]}
				   		{icon _id='star' alt=$field_value.options_array[i] title=$smarty.capture.myvote}
					{else}
						{icon _id='star_grey' alt=$field_value.options_array[i] title=$smarty.capture.myvote}
					{/if}
				</b>
			{else}
				{if ($tiki_p_tracker_vote_ratings eq 'y' && (!isset($field_value.my_rate) || $field_value.my_rate === false)) ||
					($tiki_p_tracker_revote_ratings eq 'y' && isset($field_value.my_rate) && $field_value.my_rate !== false)}
					{capture name=thisvote}{tr}Click to vote for this value:{/tr} {$field_value.options_array[i]}{/capture}
					<a href="{$smarty.server.REQUEST_URI}{if empty($smarty.server.QUERY_STRING)}?{else}&amp;{/if}itemId={$item.itemId}&amp;ins_{$field_value.fieldId}={$field_value.options_array[i]}&amp;vote=y">
				{/if}
				{if $field_value.voteavg >= $field_value.options_array[i]}
					{icon _id='star' alt=$field_value.options_array[i] title=$smarty.capture.thisvote}
				{else}
					{icon _id='star_grey' alt=$field_value.options_array[i] title=$smarty.capture.thisvote}
				{/if}
				{if ($tiki_p_tracker_vote_ratings eq 'y' && (!isset($field_value.my_rate) || $field_value.my_rate === false)) ||
					($tiki_p_tracker_revote_ratings eq 'y' && isset($field_value.my_rate) && $field_value.my_rate !== false)}
					</a>
				{/if}	
			{/if}
			{assign var='previousvote' value=$field_value.options_array[i]}
		{/section}
		</span>
		{if $item.itemId}
			<small title="{tr}Votes{/tr}">
				({$field_value.numvotes})
			</small>
			{icon _id='help' title=$smarty.capture.stat}
		{/if}
		{if $tiki_p_tracker_revote_ratings eq 'y' and  isset($field_value.my_rate) and in_array($field_value.my_rate, $field_value.options_array)}
			<a href="{$smarty.server.REQUEST_URI}{if empty($smarty.server.QUERY_STRING)}?{else}&amp;{/if}itemId={$item.itemId}&amp;ins_{$field_value.fieldId}=NULL&amp;vote=y" title="{tr}Clik to delete your vote{/tr}">x</a>
		{/if}
		<span>
	{/if}

{* -------------------- header ------------------------- *}
{elseif $field_value.type eq 'h'}
	<h2>{$field_value.value}</h2>

{* -------------------- subscription -------------------- *}
{elseif $field_value.type eq 'U'}
	{$field_value.value|how_many_user_inscriptions}{if $list_mode ne 'csv'} {tr}Subscriptions{/tr}{/if}
	{if $list_mode eq 'n'}
	{if $field_value.maxsubscriptions}(max : {$field_value.maxsubscriptions}){/if} :
	{foreach from=$field_value.users_array name=U_user item=U_user}
		{$U_user.login|userlink}{if $U_user.friends} (+{$U_user.friends}){/if}{if $smarty.foreach.U_user.last}{else},&nbsp;{$last}{/if}
	{/foreach}
	{if $user}
		<br />
		{if $field_value.user_subscription} {tr}You have ever subscribed{/tr}.{else}{tr}You have not yet subscribed{/tr}.{/if}
		<form method="POST">
		<input type="hidden" name="U_fieldId" value="{$field_value.fieldId}" />
		<input type="hidden" name="itemId" value="{$itemId}" />
		<input type="hidden" name="trackerId" value="{$trackerId}" />
		<input type="submit" name="user_subscribe" value="{tr}Subscribe{/tr}" /> {tr}with{/tr}
		{if $U_liste}
			{html_options options=$U_liste name="user_friends" selected=$field_value.user_nb_friends} {tr}friends{/tr}
		{else}
			<input type="text" size="4" name="user_friends" value="{$field_value.user_nb_friends}" /> {tr}friends{/tr}
		{/if}
		{if $field_value.user_subscription}<br /><input type="submit" name="user_unsubscribe" value="{tr}Unsubscribe{/tr}" />{/if}
		</form>
	{/if}
	{/if}

{* -------------------- google map -------------------- *}
{elseif $field_value.type eq 'G'}
	{if $prefs.feature_gmap eq 'y'}
		{if $list_mode eq 'y'}
			{include file='tracker_item_field_googlemap_value.tpl' width=200 height=200 control='n'}
		{elseif $list_mode eq 'csv'}
			{$field_value.value}
		{else}
			{include file='tracker_item_field_googlemap_value.tpl' width=500 height=400 control='y'}
		{/if}
	{else}
	  {tr}Google Maps is not enabled.{/tr}
	{/if}

{* -------------------- in group -------------------- *}
{elseif $field_value.type eq 'N'}
	{if isset($field_value.options_array[1]) and $field_value.options_array[1] eq 'date'}
		{if !empty($field_value.value)}
			{$field_value.value|tiki_short_date}
		{/if}
	{else}
		{tr}{$field_value.value}{/tr}
	{/if}

{* -------------------- other field -------------------- *}
{* w *}
{else}
	{if $list_mode eq 'y'}
		{$field_value.value|truncate:255:"..."|default:"&nbsp;"}
	{else}
		{$field_value.value}
	{/if}
{/if}

{* ******************** append ******************** *}
{if ($field_value.type eq 't' or $field_value.type eq 'n' or $field_value.type eq 'c') and $field_value.options_array[3] and $field_value.value != ''}
	<span class="formunit">{$field_value.options_array[3]}</span>
{/if}
{if $field_value.type eq 'q' and !empty($field_value.options_array[2])}
	<span class="formunit">{$field_value.options_array[2]}</span>
{/if}

{* ******************** link ******************** *}
{if $is_link eq 'y'}
	</a>
{/if}

{/if}
{/strip}

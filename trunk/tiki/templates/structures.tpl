{if count($showstructs) ne 0}
<tr class="formcolor">
	<td>{tr}Structures:{/tr}</td>
	<td>
  [ <a title="{tr}Click here to show structures{/tr}" class="link" href="javascript:show('showstructs');">{tr}show structures{/tr}</a>
  | <a title="{tr}Click here to hide structures{/tr}" class="link" href="javascript:hide('showstructs');">{tr}hide structures{/tr}</a> ]
	<div id="showstructs" style="display:none;">
	<table>
		{foreach from=$showstructs item=page_info }
			<tr><td>{$page_info.pageName}{if !empty($page_info.page_alias)}({$page_info.page_alias}){/if}</td></tr>
		{/foreach}  
	</table>
  
  {if $tiki_p_edit_structures eq 'y'}
    <a title="{tr}Click here to manage structures{/tr}" href="tiki-admin_structures.php" class="link">{tr}Manage structures{/tr}</a>
  {/if}
  </div>
  </td>
</tr>
{/if}

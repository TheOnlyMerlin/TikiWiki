<a class="pagetitle" href="tiki-admin_calendars.php">{tr}Admin Calendars{/tr}</a>
<br /><br />
<div class="tabs">
<span id="tab1" class="tab tabActive">{tr}List Calendars{/tr}</span>
<span id="tab2" class="tab">{tr}Create/edit Calendars{/tr}</span>
</div>

{* --- tab with list --- *}
<div id="content1" class="content">
<h2>{tr}List of Calendars{/tr}</h2>
{if count($calendars) gt 0}
<div align="center">
<table class="findtable">
<tr><td>{tr}Find{/tr}</td>
   <td>
   <form method="get" action="tiki-admin_calendars.php">
     <input type="text" name="find" value="{$find|escape}" />
     <input type="submit" value="{tr}find{/tr}" name="search" />
     <input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />
   </form>
   </td>
</tr>
</table>
<table>
<tr>
<th><a href="tiki-admin_calendars.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'calendarId_desc'}calendarId_asc{else}calendarId_desc{/if}">{tr}ID{/tr}</a></th>
<th><a href="tiki-admin_calendars.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'name_desc'}name_asc{else}name_desc{/if}">{tr}name{/tr}</a></th>
<th><a href="tiki-admin_calendars.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'customlocations_desc'}customlocations_asc{else}customlocations_desc{/if}">{tr}location{/tr}</a></th>
<th><a href="tiki-admin_calendars.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'customcategories_desc'}customcategories_asc{else}customcategories_desc{/if}">{tr}category{/tr}</a></th>
<th><a href="tiki-admin_calendars.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'customlanguages_desc'}customlanguages_asc{else}customlanguages_desc{/if}">{tr}language{/tr}</a></th>
<th><a href="tiki-admin_calendars.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'custompriorities_desc'}custompriorities_asc{else}custompriorities_desc{/if}">{tr}priority{/tr}</a></th>
<th>{tr}action{/tr}</th>
</tr>
{cycle values="odd,even" print=false}
{foreach key=id item=cal from=$calendars}
<tr>
<td class="{cycle advance=false}">{$id}</td>
<td class="{cycle advance=false}"><a class="tablename" href="tiki-calendar.php?calIds[]={$id}">{$cal.name}</a></td>
<td class="{cycle advance=false}">{$cal.customlocations}</td>
<td class="{cycle advance=false}">{$cal.customcategories}</td>
<td class="{cycle advance=false}">{$cal.customlanguages}</td>
<td class="{cycle advance=false}">{$cal.custompriorities}</td>
<td class="{cycle}">
   <a title="Click here to delete this calendar" 
   href="tiki-admin_calendars.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;drop={$id}" 
   onclick="return confirmTheLink(this,'{tr}Are you sure you want to delete this calendar?{/tr}')"><img alt="{tr}Remove{/tr}" src="img/icons2/delete.gif" /></a>
   <a title="" href="tiki-admin_calendars.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;calendarId={$id}"><img alt="{tr}Edit{/tr}" src="img/icons/edit.gif" /></a>
</td>
</tr>
{/foreach}
</table>
<br />

<div class="mini">
{if $prev_offset >= 0}
[<a class="prevnext" href="tiki-admin_calendars.php?find={$find}&amp;offset={$prev_offset}&amp;sort_mode={$sort_mode}">{tr}prev{/tr}</a>] 
{/if}
{tr}Page{/tr}: {$actual_page}/{$cant_pages}
{if $next_offset >= 0}
 [<a class="prevnext" href="tiki-admin_calendars.php?find={$find}&amp;offset={$next_offset}&amp;sort_mode={$sort_mode}">{tr}next{/tr}</a>]
{/if}
{if $direct_pagination eq 'y'}
<br />
{section loop=$cant_pages name=foo}
{assign var=selector_offset value=$smarty.section.foo.index|times:$maxRecords}
<a class="prevnext" href="tiki-admin_calendars.php?find={$find}&amp;offset={$selector_offset}&amp;sort_mode={$sort_mode}">
{$smarty.section.foo.index_next}</a> 
{/section}
{/if}
</div>
</div>
{else}
<b>{tr}No records found{/tr}</b>
{/if}
</div>

{* --- tab with form --- *}
<div id="content2" class="content">
<h2>{tr}Create/edit Calendars{/tr}</h2>

<form action="tiki-admin_calendars.php" method="post">
<input type="hidden" name="calendarId" value="{$calendarId|escape}" />
<table>
<tr><td><label>{tr}Name{/tr}:</label></td><td><input type="text" name="name" value="{$name|escape}" /></td></tr>
<tr><td><label>{tr}Description{/tr}:</label></td><td><textarea name="description" rows="5" wrap="virtual" style="width:100%;">{$description|escape}</textarea></td></tr>
<tr><td><label>{tr}Custom Locations{/tr}:</label></td><td>
<select name="customlocations">
<option value='y' {if $customlocations eq 'y'}selected="selected"{/if}>{tr}yes{/tr}</option>
<option value='n' {if $customlocations eq 'n'}selected="selected"{/if}>{tr}no{/tr}</option>
</select>
</td></tr>
<tr><td><label>{tr}Custom Categories{/tr}:</label></td><td>
<select name="customcategories">
<option value='y' {if $customcategories eq 'y'}selected="selected"{/if}>{tr}yes{/tr}</option>
<option value='n' {if $customcategories eq 'n'}selected="selected"{/if}>{tr}no{/tr}</option>
</select>
</td></tr>
<tr><td><label>{tr}Custom Languages{/tr}:</label></td><td>
<select name="customlanguages">
<option value='y' {if $customlanguages eq 'y'}selected="selected"{/if}>{tr}yes{/tr}</option>
<option value='n' {if $customlanguages eq 'n'}selected="selected"{/if}>{tr}no{/tr}</option>
</select>
</td></tr>
<tr><td><label>{tr}Custom Priorities{/tr}:</label></td><td>
<select name="custompriorities">
<option value='y' {if $custompriorities eq 'y'}selected="selected"{/if}>{tr}yes{/tr}</option>
<option value='n' {if $custompriorities eq 'n'}selected="selected"{/if}>{tr}no{/tr}</option>
</select>
</td></tr>
<tr><td>&nbsp;</td><td><input type="submit" name="save" value="{tr}Save{/tr}" /></td></tr>
</table>
</form>
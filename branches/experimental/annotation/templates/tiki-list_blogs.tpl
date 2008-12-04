{* $Id$ *}
{title help="Blogs" admpage="blogs"}{tr}Blogs{/tr}{/title}

{if $tiki_p_create_blogs eq 'y'}
  <div class="navbar">
		{button href="tiki-edit_blog.php" _text="{tr}Create New Blog{/tr}"}
	</div>
{/if}
<div align="center">

{if $listpages or ($find ne '')}
  {include file='find.tpl' _sort_mode='y'}
{/if}

<table class="normal">
<tr>
{if $prefs.blog_list_title eq 'y'}
	<th><a href="tiki-list_blogs.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'title_desc'}title_asc{else}title_desc{/if}">{tr}Title{/tr}</a></th>
{/if}
{if $prefs.blog_list_description eq 'y'}
	<th>{tr}Description{/tr}</th>
{/if}
{if $prefs.blog_list_created eq 'y'}
	<th><a href="tiki-list_blogs.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'created_desc'}created_asc{else}created_desc{/if}">{tr}Created{/tr}</a></th>
{/if}
{if $prefs.blog_list_lastmodif eq 'y'}
	<th><a href="tiki-list_blogs.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'lastModif_desc'}lastModif_asc{else}lastModif_desc{/if}">{tr}Last post{/tr}</a></th>
{/if}
{if $prefs.blog_list_user ne 'disabled'}
	<th><a href="tiki-list_blogs.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'user_desc'}user_asc{else}user_desc{/if}">{tr}User{/tr}</a></th>
{/if}
{if $prefs.blog_list_posts eq 'y'}
	<th style="text-align:right;"><a href="tiki-list_blogs.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'posts_desc'}posts_asc{else}posts_desc{/if}">{tr}Posts{/tr}</a></th>
{/if}
{if $prefs.blog_list_visits eq 'y'}
	<th style="text-align:right;"><a href="tiki-list_blogs.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'hits_desc'}hits_asc{else}hits_desc{/if}">{tr}Visits{/tr}</a></th>
{/if}
{if $prefs.blog_list_activity eq 'y'}
	<th style="text-align:right;"><a href="tiki-list_blogs.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'activity_desc'}activity_asc{else}activity_desc{/if}">{tr}Activity{/tr}</a></th>
{/if}
<th>{tr}Action{/tr}</th>
</tr>
{cycle values="odd,even" print=false}
{section name=changes loop=$listpages}
<tr>
{if $prefs.blog_list_title eq 'y'}
	<td class="{cycle advance=false}">{if ($tiki_p_admin eq 'y') or ($listpages[changes].individual eq 'n') or ($listpages[changes].individual_tiki_p_read_blog eq 'y' ) }<a class="blogname" href="{$listpages[changes].blogId|sefurl:blog}" title="{$listpages[changes].title}">{/if}{if $listpages[changes].title}{$listpages[changes].title|truncate:$prefs.blog_list_title_len:"...":true}{else}&nbsp;{/if}{if ($tiki_p_admin eq 'y') or ($listpages[changes].individual eq 'n') or ($listpages[changes].individual_tiki_p_read_blog eq 'y' ) }</a>{/if}</td>
{/if}
{if $prefs.blog_list_description eq 'y'}
	<td class="{cycle advance=false}">{$listpages[changes].description}</td>
{/if}
{if $prefs.blog_list_created eq 'y'}
	<td class="{cycle advance=false}">&nbsp;{$listpages[changes].created|tiki_short_date}&nbsp;</td><!--tiki_date_format:"%b %d" -->
{/if}
{if $prefs.blog_list_lastmodif eq 'y'}
	<td class="{cycle advance=false}">&nbsp;{$listpages[changes].lastModif|tiki_short_datetime}&nbsp;</td><!--tiki_date_format:"%d of %b [%H:%M]"-->
{/if}
{if $prefs.blog_list_user ne 'disabled'}
{if $prefs.blog_list_user eq 'link'}
	<td class="{cycle advance=false}">&nbsp;{$listpages[changes].user|userlink}&nbsp;</td>
{elseif $prefs.blog_list_user eq 'avatar'}
	<td class="{cycle advance=false}">&nbsp;{$listpages[changes].user|avatarize}&nbsp;<br />
	&nbsp;{$listpages[changes].user|userlink}&nbsp;</td>
{else}
	<td class="{cycle advance=false}">&nbsp;{$listpages[changes].user}&nbsp;</td>
{/if}
{/if}
{if $prefs.blog_list_posts eq 'y'}
	<td style="text-align:right;" class="{cycle advance=false}">&nbsp;{$listpages[changes].posts}&nbsp;</td>
{/if}
{if $prefs.blog_list_visits eq 'y'}
	<td style="text-align:right;" class="{cycle advance=false}">&nbsp;{$listpages[changes].hits}&nbsp;</td>
{/if}
{if $prefs.blog_list_activity eq 'y'}	
	<td style="text-align:right;" class="{cycle advance=false}">&nbsp;{$listpages[changes].activity}&nbsp;</td>
{/if}
<td class="{cycle}" nowrap="nowrap">
	{if ($user and $listpages[changes].user eq $user) or ($tiki_p_blog_admin eq 'y')}
		{if ($tiki_p_admin eq 'y') or ($listpages[changes].individual eq 'n') or ($listpages[changes].individual_tiki_p_blog_create_blog eq 'y' ) }
			<a class="icon" href="tiki-edit_blog.php?blogId={$listpages[changes].blogId}">{icon _id='page_edit'}</a>
		{/if}
	{/if}
	{if $tiki_p_blog_post eq 'y'}
		{if ($tiki_p_admin eq 'y') or ($listpages[changes].individual eq 'n') or ($listpages[changes].individual_tiki_p_blog_post eq 'y' ) }
			{if ($user and $listpages[changes].user eq $user) or ($tiki_p_blog_admin eq 'y') or ($listpages[changes].public eq 'y')}
				<a class="icon" href="tiki-blog_post.php?blogId={$listpages[changes].blogId}">{icon _id='pencil_add' alt='{tr}Post{/tr}'}</a>
			{/if}
		{/if}
	{/if}
	{if $tiki_p_admin eq 'y' || $tiki_p_assign_perm_blog eq 'y'}
	    {if $listpages[changes].individual eq 'y'}
		<a class="icon" href="tiki-objectpermissions.php?objectName={$listpages[changes].title|escape:"url"}&amp;objectType=blog&amp;permType=blogs&amp;objectId={$listpages[changes].blogId}">{icon _id='key_active' alt='{tr}Active Perms{/tr}'}</a>
	    {else}
		<a class="icon" href="tiki-objectpermissions.php?objectName={$listpages[changes].title|escape:"url"}&amp;objectType=blog&amp;permType=blogs&amp;objectId={$listpages[changes].blogId}">{icon _id='key' alt='{tr}Perms{/tr}'}</a>
	    {/if}
	{/if}
        {if ($user and $listpages[changes].user eq $user) or ($tiki_p_blog_admin eq 'y')}
                {if ($tiki_p_admin eq 'y') or ($listpages[changes].individual eq 'n') or ($listpages[changes].individual_tiki_p_blog_create_blog eq 'y' ) }
                        &nbsp;&nbsp;<a class="icon" href="tiki-list_blogs.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$listpages[changes].blogId}">{icon _id='cross' alt='{tr}Remove{/tr}'}</a>
                {/if}
        {/if}
	
</td>
</tr>
{sectionelse}
<tr><td colspan="9" class="odd">
{tr}No records found{/tr}
</td></tr>
{/section}
</table>

{pagination_links cant=$cant step=$maxRecords offset=$offset}{/pagination_links}

</div>


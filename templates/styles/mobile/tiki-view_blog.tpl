{* $Id$ *}
{if !isset($show_heading) or $show_heading neq "n"}
	{if strlen($heading) > 0 and $prefs.feature_blog_heading eq 'y'}
		{eval var=$heading}
	{else}
		{include file='blog_heading.tpl'}
	{/if}

	<div align="right">
		{* <span class="blogactions"> *} {* mobile *}
		{if $prefs.mobile_mode eq "y"}<div class="navbar" data-role="controlgroup" data-type="horizontal">{/if} {* mobile *}
			{if $tiki_p_blog_post eq "y"}
				{if ($user and $creator eq $user) or $tiki_p_blog_admin eq "y" or $public eq "y"}
					<a {if $prefs.mobile_mode eq "y"}data-role="button" {/if}class="bloglink" href="tiki-blog_post.php?blogId={$blogId}">{icon _id='pencil_add' alt="{tr}Post{/tr}"}</a> {* mobile *}
				{/if}
			{/if}
			{if $prefs.feed_blog eq "y"}
				<a {if $prefs.mobile_mode eq "y"}data-role="button" {/if}class="bloglink" href="tiki-blog_rss.php?blogId={$blogId}">{icon _id='feed' alt="{tr}RSS feed{/tr}"}</a> {* mobile *}
			{/if}
			{if ($user and $creator eq $user) or $tiki_p_blog_admin eq "y"}
				<a {if $prefs.mobile_mode eq "y"}data-role="button" {/if}class="bloglink" href="tiki-edit_blog.php?blogId={$blogId}">{icon _id='page_edit' alt="{tr}Edit Blog{/tr}"}</a> {* mobile *}
				{if $allow_comments eq 'y'}
					<a {if $prefs.mobile_mode eq "y"}data-role="button" {/if}class='bloglink' href='tiki-list_comments.php?types_section=blogs&amp;blogId={$blogId}'>{icon _id='comments' alt="{tr}List all comments{/tr}" title="{tr}List all comments{/tr}"}</a> {* mobile *}
				{/if}
			{/if}

			{if $user and $prefs.feature_user_watches eq 'y'}
				{if $user_watching_blog eq 'n'}
					<a {if $prefs.mobile_mode eq "y"}data-role="button" {/if}href="tiki-view_blog.php?blogId={$blogId}&amp;watch_event=blog_post&amp;watch_object={$blogId}&amp;watch_action=add" class="icon">{icon _id='eye' alt="{tr}Monitor this Blog{/tr}"}</a> {* mobile *}
				{else}
					<a {if $prefs.mobile_mode eq "y"}data-role="button" {/if}href="tiki-view_blog.php?blogId={$blogId}&amp;watch_event=blog_post&amp;watch_object={$blogId}&amp;watch_action=remove" class="icon">{icon _id='no_eye' alt="{tr}Stop Monitoring this Blog{/tr}"}</a> {* mobile *}
				{/if}
			{/if}
			{if $prefs.feature_group_watches eq 'y' and ( $tiki_p_admin_users eq 'y' or $tiki_p_admin eq 'y' )}
				<a {if $prefs.mobile_mode eq "y"}data-role="button" {/if}href="tiki-object_watches.php?objectId={$blogId|escape:"url"}&amp;watch_event=blog_post&amp;objectType=blog&amp;objectName={$title|escape:"url"}&amp;objectHref={'tiki-view_blog.php?blogId='|cat:$blogId|escape:"url"}" class="icon">{icon _id='eye_group' alt="{tr}Group Monitor{/tr}"}</a> {* mobile *}
			{/if}
		{if $prefs.mobile_mode eq "y"}</div>{/if} {* mobile *}
		{* </span> *} {* mobile *}

		{if $user and $prefs.feature_user_watches eq 'y'}
			{if $category_watched eq 'y'}
				{tr}Watched by categories:{/tr}
				{section name=i loop=$watching_categories}
					<a {if $prefs.mobile_mode eq "y"}data-role="button" {/if}href="tiki-browse_categories.php?parentId={$watching_categories[i].categId}" class="icon">{$watching_categories[i].name|escape}</a>&nbsp; {* mobile *}
				{/section}
			{/if}
		{/if}
	</div>
	
	{if $use_find eq 'y'}
		<div class="blogtools">
			{include file='find.tpl'}
		</div>
	{/if}
{/if}

{if $excerpt eq 'y'}
	{assign "request_context" "excerpt"}
{else}
	{assign "request_context" "view_blog"}
{/if}

{foreach from=$listpages item=post_info}
	<article class="blogpost post{if !empty($container_class)} {$container_class}{/if}">
		{include file='blog_wrapper.tpl' blog_post_context=$request_context}
	</article>
{/foreach}

{pagination_links cant=$cant step=$maxRecords offset=$offset}{/pagination_links}

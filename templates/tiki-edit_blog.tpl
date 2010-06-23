{if $blogId > 0}
  {title help="Blogs" url="tiki-edit_blog.php?blogId=$blogId" admpage="blogs"}{tr}Edit Blog:{/tr} {$title|escape}{/title}
{else}
  {title help="Blogs"}{tr}Create Blog{/tr}{/title}
{/if}

<div class="navbar">
	{button href="tiki-list_blogs.php" _text="{tr}List Blogs{/tr}"}
	 
  {if $blogId > 0}
		{assign var=thisblogId value=$blogId|sefurl:blog}
		{button href=$thisblogId _text="{tr}View Blog{/tr}"}
	{/if}
</div>

{if $category_needed eq 'y'}
	{remarksbox type='Warning' title='{tr}Warning{/tr}'}
	<div class="highlight"><em class='mandatory_note'>{tr}A category is mandatory{/tr}</em></div>
	{/remarksbox}
{/if}

{if $individual eq 'y'}
  <a class="link" href="tiki-objectpermissions.php?objectName={$title|escape:"url"}&amp;objectType=blog&amp;permType=blogs&amp;objectId={$blogId}">{tr}There are individual permissions set for this blog{/tr}</a>
{/if}

<form method="post" action="tiki-edit_blog.php" id="blog-edit-form">
  <input type="hidden" name="blogId" value="{$blogId|escape}" />
  {tabset name='tabs_editblog'}
    {tab name='{tr}General Settings{/tr}'}
      <table class="normal">
        <tr class="editblogform">
          <td><label for="blog-title">{tr}Title{/tr}</label></td>
          <td><input type="text" name="title" id="blog-title" value="{$title|escape}" /></td>
        </tr>
        <tr class="editblogform">
          <td>
            <label for="blog-desc">{tr}Description{/tr}</label>
            <br />
            {include file='textareasize.tpl' area_name='blog-desc' formId='blog-edit-form'}
          </td>
          <td>
            <textarea class="wikiedit" name="description" id="blog-desc" rows="{$rows}" cols="{$cols}" wrap="virtual">{$description|escape}</textarea>
          </td>
        </tr>
        <tr class="editblogform">
          <td>{tr}Creator{/tr}</td>
          <td>
            <select name="creator">
              {if $tiki_p_admin eq 'y' or $tiki_p_blog_admin eq 'y'}
                {foreach from=$users key=userId item=u}
                  <option value="{$u|escape}"{if $u eq $creator} selected="selected"{/if}>{$u|escape}</option>
                {/foreach}
              {else}
                <option value="{$user|escape}" selected="selected">{$user|escape}</option>
              {/if}
            </select> 
          </td>
        </tr>
        <tr class="editblogform">
          <td><label for="blogs-allow_others">{tr}Allow other users to post in this blog{/tr}</label></td>
          <td><input type="checkbox" name="public" id="blogs-allow_others" {if $public eq 'y'}checked='checked'{/if}/></td>
        </tr>	
        <tr class="editblogform">
          <td><label for="blogs-always_owner">{tr}If others post to blog, Author should always be Owner{/tr}</label></td>
          <td><input type="checkbox" name="alwaysOwner" id="blogs-always_owner" {if $alwaysOwner eq 'y'}checked='checked'{/if}/></td>
        </tr>
        <tr class="editblogform">
          <td><label for="blogs-search">{tr}Allow search{/tr}</label></td>
          <td><input type="checkbox" name="use_find" id="blogs-search" {if $use_find eq 'y'}checked='checked'{/if}/></td>
        </tr>
        <tr class="editblogform">
          <td><label for="blogs-comments">{tr}Allow comments{/tr}</label></td>
          <td>
            <input type="checkbox" name="allow_comments" id="blogs-comments" {if $allow_comments eq 'y' or $allow_comments eq 'c'}checked='checked'{/if}{if $prefs.feature_blogposts_comments ne 'y'} disabled="disabled"{/if} />
            {if $prefs.feature_blogposts_comments ne 'y'}Global post-level comments is disabled.{/if}
          </td>
        </tr>

        {include file='categorize.tpl'}

      </table>
    {/tab}
    {tab name='{tr}Display Options{/tr}'}
      <table class="normal">
        <tr class="editblogform">
          <td><label for="blogs-number">{tr}Number of posts to show{/tr}</label></td>
          <td><input type="text" name="maxPosts" id="blogs-number" value="{$maxPosts|escape}" /></td>
        </tr>
        <tr class="editblogform">
          <td><label for="blogs-titles">{tr}Display titles in blog posts{/tr}</label></td>	 
          <td><input type="checkbox" name="use_title" id="blogs-titles" {if $use_title eq 'y'}checked='checked'{/if}/></td>	 
        </tr>	 
    	<tr class="editblogform">
          <td><label for="blogs-author">{tr}Display author in blog posts{/tr}</label></td>	 
          <td><input type="checkbox" name="use_author" id="blogs-author" {if $use_author eq 'y'}checked='checked'{/if}/></td>	 
        </tr>
        <tr class="editblogform">
          <td><label for="blogs-date">{tr}Display published date in blog posts{/tr}</label></td>	 
          <td><input type="checkbox" name="add_date" id="blogs-date" {if $add_date eq 'y'}checked='checked'{/if}/></td>	 
        </tr>	
        <tr class="editblogform">
          <td>{tr}Show user avatar{/tr}</td>
          <td><input type="checkbox" name="show_avatar" {if $show_avatar eq 'y'}checked='checked'{/if} /></td>
        </tr>

        {if $prefs.feature_blog_heading eq 'y' and $tiki_p_edit_templates eq 'y'}
          <tr class="editblogform">
            <td>
              <label for="blogs-heading">{tr}Blog heading{/tr}</label>
              <br />
              {include file='textareasize.tpl' area_name='blogs-heading' formId='blog-edit-form'}
            </td>
            <td>
              <textarea name="heading" id="blogs-heading" rows='10' cols='{$cols}'>{$heading|escape}</textarea>
            </td>
          </tr>

          {if strlen($heading) > 0 and $blogId > 0}
            <tr class="editblogform">
              <td></td>
              <td>
                {button href="#" _flip_id='blog_heading_preview' _class='link' _text="{tr}Blog heading preview{/tr}" _flip_default_open='n'}
                <div id="blog_heading_preview" style="display: none;">
                  {eval var=$heading}
                </div>
              </td>
            </tr>
          {/if}
        {/if}

      </table>
    {/tab}
  {/tabset}
  <input type="submit" class="wikiaction" name="preview" value="{tr}Preview{/tr}" />
  <input type="submit" class="wikiaction" name="save" value="{tr}Save{/tr}" />
</form>
<br />

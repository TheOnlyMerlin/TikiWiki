{* $Id$ *}

{if $prefs.feature_articles eq 'y'}
{if !isset($tpl_module_title)}
{if $nonums eq 'y'}
{eval var="<a href=\"tiki-view_articles.php?topic=$topicId&amp;type=$type\">{tr}Last `$module_rows` articles{/tr}</a>" assign="tpl_module_title"}
{else}
{eval var="<a href=\"tiki-view_articles.php?topic=$topicId&amp;type=$type\">{tr}Last articles{/tr}</a>" assign="tpl_module_title"}
{/if}
{/if}
{tikimodule error=$module_params.error title=$tpl_module_title name="last_articles" flip=$module_params.flip decorations=$module_params.decorations nobox=$module_params.nobox notitle=$module_params.notitle}
{if $nonums != 'y'}<ol class="module">{else}<ul class="module">{/if}
    {section name=ix loop=$modLastArticles}
      <li>
		{if !empty($showImg) or $showDate eq 'y'}
		<div class="module">
			{if $showDate eq 'y'}
				<div class="date">{$modLastArticles[ix].publishDate|tiki_short_date}</div>
			{/if}
			{if isset($showImg)}
			{if $modLastArticles[ix].hasImage eq 'y'}<div class="image"><img alt="" src="article_image.php?id={$modLastArticles[ix].articleId}" width="{$showImg}" /></div>{elseif $modLastArticles[ix].topicId}<div class="image"><img alt="" src="article_image.php?image_type=topic&amp;id={$modLastArticles[ix].topicId}" width="{$showImg}" /></div>{/if}
			{/if}
		</div>		
		{/if}
 		  {if $absurl == 'y'}
          <a class="linkmodule" href="{$base_url}{$modLastArticles[ix].articleId|sefurl:article}" title="{$modLastArticles[ix].publishDate|tiki_short_datetime}, {tr}by{/tr} {$modLastArticles[ix].author}">
            {$modLastArticles[ix].title}
          </a>
		  {else}
		  <a class="linkmodule" href="{$modLastArticles[ix].articleId|sefurl:article}" title="{$modLastArticles[ix].publishDate|tiki_short_datetime}, {tr}by{/tr} {$modLastArticles[ix].author}">
            {$modLastArticles[ix].title}
          </a>
		  {/if}
		  {if isset($showHeading)}
		  <div class="heading">
		  	   {if $showHeading > 0 and $showHeading ne 'y'}{$modLastArticles[ix].parsedHeading|truncate:$showHeading}{else}{$modLastArticles[ix].parsedHeading}{/if}
		  </div>
		  {/if}
        </li>
    {/section}
{if $nonums != 'y'}</ol>{else}</ul>{/if}
{if $module_params.more eq 'y'}
	<div class="more">
		 {assign var=sep value='?'}
		 <span class="button2"><a href="tiki-view_articles.php{if $module_params.topicId}{$sep}topic={$module_params.topicId}{assign var=sep value='&amp;'}{/if}{if $module_params.topic}{$sep}topicName={$module_params.topic|escape:url}{assign var=sep value='&amp;'}{/if}{if $module_params.categId}{$sep}categId={$module_params.categId}{assign var=sep value='&amp;'}{/if}{if $module_params.type}{$sep}type={$module_params.type|escape:url}{assign var=sep value='&amp;'}{/if}{if $module_params.lang}{$sep}lang={$module_params.lang|escape:url}{assign var=sep value='&amp;'}{/if}">{tr}More...{/tr}</a></span>
	</div>
{/if}
{/tikimodule}
{/if}

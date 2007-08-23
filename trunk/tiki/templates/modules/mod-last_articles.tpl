{* $Header: /cvsroot/tikiwiki/tiki/templates/modules/mod-last_articles.tpl,v 1.16 2007-08-23 20:54:39 sylvieg Exp $ *}

{if $feature_articles eq 'y'}
{if !isset($tpl_module_title)}
{if $nonums eq 'y'}
{eval var="<a href=\"tiki-view_articles.php?topic=$topicId&type=$type\">{tr}Last `$module_rows` articles{/tr}</a>" assign="tpl_module_title"}
{else}
{eval var="<a href=\"tiki-view_articles.php?topic=$topicId&type=$type\">{tr}Last articles{/tr}</a>" assign="tpl_module_title"}
{/if}
{/if}
{tikimodule title=$tpl_module_title name="last_articles" flip=$module_params.flip decorations=$module_params.decorations}
  <table  border="0" cellpadding="1" cellspacing="0" width="100%">
    {section name=ix loop=$modLastArticles}
      <tr>
        {if $nonums != 'y'}<td class="module">{$smarty.section.ix.index_next})</td>{/if}
		{if $showTopicImg eq 'y' or $showDate eq 'y'}
		<td class="module">
			{if $showDate eq 'y'}
			<div class="date">{$modLastArticles[ix].publishDate|tiki_short_date}</div>
			{/if}
			{if isset($showImg)}
			{if $modLastArticles[ix].hasImage eq 'y'}<div class="image"><img alt="" border="0" src="article_image.php?id={$modLastArticles[ix].articleId}" width="{$showImg}" /></div>{elseif $modLastArticles[ix].topicId}<div class="image"><img alt="" border="0" src="topic_image.php?id={$modLastArticles[ix].topicId}" width="{$showImg}" /></div>{/if}
			{/if}
		</td>		
		{/if}
        <td class="module">
		  {if $absurl == 'y'}
          <a class="linkmodule" href="{$base_url}tiki-read_article.php?articleId={$modLastArticles[ix].articleId}" title="{$modLastArticles[ix].publishDate|tiki_short_datetime}, {tr}by{/tr} {$modLastArticles[ix].author}">
            {$modLastArticles[ix].title}
          </a>
		  {else}
		  <a class="linkmodule" href="tiki-read_article.php?articleId={$modLastArticles[ix].articleId}" title="{$modLastArticles[ix].publishDate|tiki_short_datetime}, {tr}by{/tr} {$modLastArticles[ix].author}">
            {$modLastArticles[ix].title}
          </a>
		  {/if}
		  {if isset($showHeading)}
		  <div class="heading">
		  	   {if $showHeading > 0 and $showHeading ne 'y'}{$modLastArticles[ix].parsedHeading|truncate:$showHeading}{else}{$modLastArticles[ix].parsedHeading}{/if}
		  </div>
		  {/if}
        </td>
      </tr>
    {/section}
  </table>
{/tikimodule}
{/if}

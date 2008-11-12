{title help="FAQs"}{tr}{$faq_info.title}{/tr}{/title}

<div class="navbar">
	{button href="tiki-list_faqs.php" _text="{tr}List FAQs{/tr}"}
	
	{if $tiki_p_admin_faqs eq 'y'}
		{button href="tiki-list_faqs.php?faqId=$faqId" _text="{tr}Edit this FAQ{/tr}"}
	{/if}
	{if $tiki_p_admin_faqs eq 'y'}
		{button href="tiki-faq_questions.php?faqId=$faqId" _text="{tr}New Question{/tr}"}
	{/if}
</div>

<h2>{tr}Questions{/tr}</h2>
{if !$channels}
{tr}There are no questions in this FAQ.{/tr}
{else}
<div class="faqlistquestions">
<ol>
{section name=ix loop=$channels}
<li><a class="link" href="#q{$channels[ix].questionId}">{$channels[ix].question}</a></li>
{/section}
</ol>
</div>
<h2>{tr}Answers{/tr}</h2>
{section name=ix loop=$channels}
<a name="q{$channels[ix].questionId}"></a>
<div class="faqqa">
<div class="faqquestion">
  {if $prefs.faq_prefix neq 'none'}
    <span class="faq_question_prefix">
    {if $prefs.faq_prefix eq 'QA'}
      {tr}Q{/tr}:
    {elseif $prefs.faq_prefix eq 'question_id'}
      {$smarty.section.ix.index_next}.&nbsp;
    {/if}
    </span>
  {/if}
  {$channels[ix].question}
</div>
<div class="faqanswer">
  {if $prefs.faq_prefix eq 'QA'}
    <span class="faq_answer_prefix">
      {tr}A{/tr}:&nbsp;
    </span>
  {/if}
  {$channels[ix].parsed}
</div>
</div>
{/section}
{/if}

<div id="page-bar">
	{if $faq_info.canSuggest eq 'y' and $tiki_p_suggest_faq eq 'y'}
		{button href="javascript:flip('faqsugg');" _flip_id="faqsugg" _text="{tr}Add Suggestion{/tr}"}
	{/if}

	{if $prefs.feature_faq_comments == 'y'
			&& (($tiki_p_read_comments == 'y'
			&& $comments_cant != 0)
		|| $tiki_p_post_comments == 'y'
		|| $tiki_p_edit_comments == 'y')
	}
		{if $comments_cant gt 0}
			{assign var=thisbuttonclass value='highlight'}
		{else}
			{assign var=thisbuttonclass value=''}
		{/if}
		{if $comments_cant == 0 or ($tiki_p_read_comments == 'n' and $tiki_p_post_comments == 'y')}
			{assign var=thistext value="{tr}Add Comment{/tr}"}
		{elseif $comments_cant == 1}
			{assign var=thistext value="{tr}1 comment{/tr}"}
		{else}
			{assign var=thistext value="$comments_cant&nbsp;{tr}Comments{/tr}"}
		{/if}
		{button href="#comments" _flip_id="comzone" _class=$thisbuttonclass _text=$thistext}
	{/if}
</div>

{if $faq_info.canSuggest eq 'y' and $tiki_p_suggest_faq eq 'y'}
<div class="faq_suggestions" id="faqsugg" style="display:{if !empty($error)}block{else}none{/if};">
{if !empty($error)}
<br />
 <div class="simplebox highlight">{icon _id=exclamation alt="{tr}Error{/tr}" style="vertical-align:middle"} {$error}</div>
{/if}
<br />
<form action="tiki-view_faq.php" method="post">
<input type="hidden" name="faqId" value="{$faqId|escape}" />
<table class="normal">
<tr><td class="formcolor">{tr}Question{/tr}:</td><td class="formcolor"><textarea rows="2" cols="80" name="suggested_question" style="width:95%;">{if $pendingquestion}{$pendingquestion}{/if}</textarea></td></tr>
<tr><td class="formcolor">{tr}Answer{/tr}:</td><td class="formcolor"><textarea rows="2" cols="80" name="suggested_answer" style="width:95%;">{if $pendinganswer}{$pendinganswer}{/if}</textarea></td></tr>
{if $prefs.feature_antibot eq 'y' && $user eq ''}
{include file="antibot.tpl"}
{/if}
<tr><td class="formcolor">&nbsp;</td><td class="formcolor"><input type="submit" name="sugg" value=" {tr}Add{/tr} " /></td></tr>
</table>
</form>
{if count($suggested) != 0}
<br />
<table class="normal">
<tr><th>{tr}Suggested questions{/tr}</th></tr>
{cycle values="odd,even" print=false}
{section name=ix loop=$suggested}
<tr><td class="{cycle}">{$suggested[ix].question}</td></tr>
{/section}
</table>
{/if}
</div>
{/if}

{if $prefs.faqs_feature_copyrights eq 'y' and $prefs.wikiLicensePage}
  {if $prefs.wikiLicensePage == $page}
    {if $tiki_p_edit_copyrights eq 'y'}
      <p class="editdate">{tr}To edit the copyright notices{/tr} <a href="copyrights.php?page={$copyrightpage}">{tr}Click Here{/tr}</a>.</p>
    {/if}
  {else}
    <p class="editdate">{tr}The content on this page is licensed under the terms of the{/tr} <a href="tiki-index.php?page={$prefs.wikiLicensePage}&amp;copyrightpage={$page|escape:"url"}">{$prefs.wikiLicensePage}</a>.</p>
  {/if}
{/if}
 


{if $prefs.feature_faq_comments == 'y'
&& (($tiki_p_read_comments == 'y'
&& $comments_cant != 0)
|| $tiki_p_post_comments == 'y'
|| $tiki_p_edit_comments == 'y')}
{include file=comments.tpl}
{/if}

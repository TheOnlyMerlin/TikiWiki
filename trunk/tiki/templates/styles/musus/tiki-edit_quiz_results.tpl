<a class="pagetitle" href="tiki-edit_quiz_results.php?quizId={$quizId}">Edit quiz results</a><br /><br />
<a class="linkbut" href="tiki-list_quizzes.php">{tr}list quizzes{/tr}</a>
<a class="linkbut" href="tiki-quiz_stats.php">{tr}quiz stats{/tr}</a>
<a class="linkbut" href="tiki-quiz_stats_quiz.php?quizId={$quizId}">{tr}this quiz stats{/tr}</a>
<a class="linkbut" href="tiki-edit_quiz.php?quizId={$quizId}">{tr}edit this quiz{/tr}</a>
<a class="linkbut" href="tiki-edit_quiz.php">{tr}admin quizzes{/tr}</a><br /><br />
<h2>{tr}Create/edit questions for quiz{/tr}: <a href="tiki-edit_quiz.php?quizId={$quiz_info.quizId}" class="pageTitle">{$quiz_info.name}</a></h2>
<form action="tiki-edit_quiz_results.php" method="post">
<input type="hidden" name="quizId" value="{$quizId|escape}" />
<input type="hidden" name="resultId" value="{$resultId|escape}" />
<table>
<tr><td>{tr}From Points{/tr}:</td><td><input type="text" name="fromPoints" value="{$fromPoints|escape}" /></td></tr>
<tr><td>{tr}To Points{/tr}:</td><td><input type="text" name="toPoints" value="{$toPoints|escape}" /></td></tr>
<tr><td>{tr}Answer{/tr}:</td><td><textarea name="answer" rows="10" cols="40">{$answer|escape}</textarea></td></tr>
<tr><td>&nbsp;</td><td><input type="submit" name="save" value="{tr}Save{/tr}" /></td></tr>
</table>
</form>
<h2>{tr}Results{/tr}</h2>
<div  align="center">
<table class="findtable">
<tr><td>{tr}Find{/tr}</td>
   <td>
   <form method="get" action="tiki-edit_quiz_results.php">
     <input type="text" name="find" value="{$find|escape}" />
     <input type="submit" value="{tr}find{/tr}" name="search" />
     <input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />
     <input type="hidden" name="quizId" value="{$quizId|escape}" />
   </form>
   </td>
</tr>
</table>
<table>
<tr>
<th><a href="tiki-edit_quiz_results.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'fromPoints_desc'}fromPoints_asc{else}fromPoints_desc{/if}">{tr}From Points{/tr}</a></th>
<th><a href="tiki-edit_quiz_results.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'toPoints_desc'}toPoints_asc{else}toPoints_desc{/if}">{tr}To Points{/tr}</a></th>
<th><a href="tiki-edit_quiz_results.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'position_desc'}answer_asc{else}answer_desc{/if}">{tr}answer{/tr}</a></th>
<th>{tr}action{/tr}</th>
</tr>
{section name=user loop=$channels}
{if $smarty.section.user.index % 2}
<tr class="odd">
<td>{$channels[user].fromPoints}</td>
<td>{$channels[user].toPoints}</td>
<td>{$channels[user].answer|truncate:230:"(...)":true}</td>
<td>
   <a  href="tiki-edit_quiz_results.php?quizId={$quizId}&amp;offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$channels[user].resultId}">{tr}remove{/tr}</a>
   <a href="tiki-edit_quiz_results.php?quizId={$quizId}&amp;offset={$offset}&amp;sort_mode={$sort_mode}&amp;resultId={$channels[user].resultId}">{tr}edit{/tr}</a>
</td>
</tr>
{else}
<tr class="even">
<td>{$channels[user].fromPoints}</td>
<td>{$channels[user].toPoints}</td>
<td>{$channels[user].answer|truncate:230:"(...)":true}</td>
<td>
   <a href="tiki-edit_quiz_results.php?quizId={$quizId}&amp;offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$channels[user].resultId}">{tr}remove{/tr}</a>
   <a href="tiki-edit_quiz_results.php?quizId={$quizId}&amp;offset={$offset}&amp;sort_mode={$sort_mode}&amp;resultId={$channels[user].resultId}">{tr}edit{/tr}</a>
</td>
</tr>
{/if}
{/section}
</table>
<div class="mini">
{if $prev_offset >= 0}
[<a class="prevnext" href="tiki-edit_quiz_results.php?quizId={$quizId}&amp;find={$find}&amp;offset={$prev_offset}&amp;sort_mode={$sort_mode}">{tr}prev{/tr}</a>]&nbsp;
{/if}
{tr}Page{/tr}: {$actual_page}/{$cant_pages}
{if $next_offset >= 0}
&nbsp;[<a class="prevnext" href="tiki-edit_quiz_results.php?quizId={$quizId}&amp;find={$find}&amp;offset={$next_offset}&amp;sort_mode={$sort_mode}">{tr}next{/tr}</a>]
{/if}
{if $direct_pagination eq 'y'}
<br />
{section loop=$cant_pages name=foo}
{assign var=selector_offset value=$smarty.section.foo.index|times:$maxRecords}
<a class="prevnext" href="tiki-edit_quiz_results.php?quizId={$quizId}&amp;find={$find}&amp;offset={$selector_offset}&amp;sort_mode={$sort_mode}">
{$smarty.section.foo.index_next}</a>&nbsp;
{/section}
{/if}
</div>
</div>
{* $Id$ *}
{title help="Quiz"}{tr}Stats for quizzes{/tr}{/title}

<div class="navbar"><a href="tiki-list_quizzes.php">{tr}List Quizzes{/tr}</a>
  <a href="tiki-edit_quiz.php">{tr}Admin Quizzes{/tr}</a>
</div>

<h2>{tr}Quizzes{/tr}</h2>
<!-- begin find field -->
<div  align="center">
{if $channels}
<table class="findtable">
<tr><td class="findtable">{tr}Find{/tr}</td>
   <td class="findtable">
   <form method="get" action="tiki-quiz_stats.php">
     <input type="text" name="find" value="{$find|escape}" />
     <input type="submit" value="{tr}Find{/tr}" name="search" />
     <input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />
     <input type="hidden" name="quizId" value="{$quizId|escape}" />
   </form>
   </td>
</tr>
</table>
{/if}
<table class="normal">
<tr>
<th><a href="tiki-quiz_stats.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'quizName_desc'}quizName_asc{else}quizName_desc{/if}">{tr}Quiz{/tr}</a>
</th>
<th>
<a href="tiki-quiz_stats.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'timesTaken_desc'}timesTaken_asc{else}timesTaken_desc{/if}">{tr}taken{/tr}</a>
</th>
<th>
<a href="tiki-quiz_stats.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'avgavg_desc'}avgavg_asc{else}avgavg_desc{/if}">{tr}Av score{/tr}</a>
</th>
<th><a href="tiki-quiz_stats.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'avgtime_desc'}avgtime_asc{else}avgtime_desc{/if}">{tr}Av time{/tr}</a></th>
</tr>
  {cycle values="odd,even" print=false}
  {section name=user loop=$channels}
    {if ($tiki_p_admin eq 'y') or ($channels[user].individual eq 'n' and $tiki_p_view_quiz_stats eq 'y') or ($channels[user].individual_tiki_p_view_quiz_stats eq 'y')}
      <tr>
        <td class="{cycle advance=false}"><a class="tablename" href="tiki-quiz_stats_quiz.php?quizId={$channels[user].quizId}">{$channels[user].quizName}</a></td>
        <td class="{cycle advance=false}">{$channels[user].timesTaken}</td>
        <td class="{cycle advance=false}">{$channels[user].avgavg}%</td>
        <td class="{cycle}">{$channels[user].avgtime} secs</td>
    </tr>
{/if}
{sectionelse}
<tr><td class="{cycle}" colspan="4">{tr}No records{/tr}</td></tr>
{/section}
</table>
<div class="mini">
{if $prev_offset >= 0}
[<a class="prevnext" href="tiki-quiz_stats.php?quizId={$quizId}&amp;find={$find}&amp;offset={$prev_offset}&amp;sort_mode={$sort_mode}">{tr}Prev{/tr}</a>]&nbsp;
{/if}
{tr}Page{/tr}: {$actual_page}/{$cant_pages}
{if $next_offset >= 0}
&nbsp;[<a class="prevnext" href="tiki-quiz_stats.php?quizId={$quizId}&amp;find={$find}&amp;offset={$next_offset}&amp;sort_mode={$sort_mode}">{tr}Next{/tr}</a>]
{/if}
{if $prefs.direct_pagination eq 'y'}
<br />
{section loop=$cant_pages name=foo}
{assign var=selector_offset value=$smarty.section.foo.index|times:$prefs.maxRecords}
<a class="prevnext" href="tiki-quiz_stats.php?quizId={$quizId}&amp;find={$find}&amp;offset={$selector_offset}&amp;sort_mode={$sort_mode}">
{$smarty.section.foo.index_next}</a>&nbsp;
{/section}
{/if}
</div>
</div>


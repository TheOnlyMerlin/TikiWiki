{if $feature_help eq "y"}
<div class="simplebox">{tr}Tip{/tr}: {tr}you can see users rank by score in the module users_rank, for that go to{/tr} "<a class="link" href="tiki-admin_modules.php">{tr}Admin modules{/tr}</a>"</div><br />
{/if}

<div class="cbox">
  <div class="cbox-title">{tr}Score settings{/tr}</div>
  <div class="cbox-data">
    <div class="simplebox">
      <form action="tiki-admin.php?page=score" method="post">

<table class="admin">
<tr>
  <td style="padding-left:5px"></td>
  <td class="form"></td>
  <td class="form"><b>{tr}Points{/tr}</b></td>
  <td class="form"><b>{tr}Expiration{/tr}</b></td>
</tr>

{section name=e loop=$events}
{if $events[e].category != $categ}
  {assign var=categ value=$events[e].category}
<tr>
  <td colspan="4" class="form"><b>{tr}{$events[e].category}{/tr}</b></td>
</tr>
{/if}

<tr>
  <td></td>
  <td class="form">{tr}{$events[e].description}{/tr}</td>
  <td class="form">
    <input type="text" size="3" name="events[{$events[e].event}][score]" value="{$events[e].score}" />
  </td>
  <td class="form">
    <input type="text" size="4" name="events[{$events[e].event}][expiration]" value="{$events[e].expiration}" />
  </td>
</tr>
{/section}
<tr>
  <td class="button" colspan="4">
    <input type="submit" name="scoreevents" value="{tr}Save{/tr}" />
  </td>
</tr>
</table>

      </form>
    </div>
  </div>
</div>


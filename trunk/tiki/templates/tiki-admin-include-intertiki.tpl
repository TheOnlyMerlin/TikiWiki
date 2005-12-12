<div class="cbox">
<div class="cbox-title">{tr}InterTiki{/tr}
{help url="Intertiki" desc="{tr}Intertiki exchange feature{/tr}"}
</div>
</div>

<div class="cbox">
<div class="cbox-title">
  {tr}Intertiki client{/tr}
</div>
<div class="cbox-data">
<form action="tiki-admin.php?page=intertiki" method="post" name="intertiki">
<table class="admin">
<tr><td class="form">Tiki Unique key</td><td><input type="text" name="tiki_key" value="{$tiki_key}" size="32" />
</td></tr>
{if $interlist}
{foreach key=k item=i from=$interlist}
<tr><td class="button" colspan="2">
<a href="tiki-admin.php?page=intertiki&amp;del={$k|escape:'url'}"><img src="img/icons2/delete.gif" width="16" height="16" border="0" alt="{tr}delete{/tr}" /></a>
{tr}InterTiki Server{/tr} <b>{$k}</b></td></tr>
<tr><td class="form">name</td><td><input type="text" name="interlist[{$k}][name]" value="{$i.name}" /></td></tr>
<tr><td class="form">host</td><td><input type="text" name="interlist[{$k}][host]" value="{$i.host}" /></td></tr>
<tr><td class="form">port</td><td><input type="text" name="interlist[{$k}][port]" value="{$i.port}" /></td></tr>
<tr><td class="form">path</td><td><input type="text" name="interlist[{$k}][path]" value="{$i.path}" /></td></tr>
<tr><td class="form">groups</td><td><input type="text" name="interlist[{$k}][groups]" value="{foreach item=g from=$i.groups name=f}{$g}{if !$smarty.foreach.f.last},{/if}{/foreach}" /></td></tr>
{/foreach}
{/if}
<tr><td class="button" colspan="2">{tr}Add new server{/tr}</td></tr>
<tr><td class="form">name</td><td><input type="text" name="new[name]" value="" /></td></tr>
<tr><td class="form">host</td><td><input type="text" name="new[host]" value="" /></td></tr>
<tr><td class="form">port</td><td><input type="text" name="new[port]" value="" /></td></tr>
<tr><td class="form">path</td><td><input type="text" name="new[path]" value="" /></td></tr>
<tr><td class="form">groups</td><td><input type="text" name="new[groups]" value="" /></td></tr>

<tr><td colspan="2" class="button"><input type="submit" name="intertikiclient" value="{tr}Save{/tr}" /></td></tr>
</table>
</form>
</div>
</div>

<div class="cbox">
<div class="cbox-title">
  {tr}Intertiki server{/tr}
</div>
<div class="cbox-data">
<form action="tiki-admin.php?page=intertiki" method="post" name="intertiki">
<table class="admin">
<tr><td class="form">{tr}Intertiki Server enabled{/tr}:</td><td><input type="checkbox" name="feature_intertiki_server" {if $feature_intertiki_server eq 'y'}checked="checked"{/if}/></td></tr>
<tr><td class="form">{tr}Access Log file{/tr}:</td><td><input type="text" name="intertiki_logfile" value="{$intertiki_logfile}" size="42" /></td></tr>
<tr><td class="form">{tr}Errors Log file{/tr}:</td><td><input type="text" name="intertiki_errfile" value="{$intertiki_errfile}" size="42" /></td></tr>
<tr><td colspan="2" class="button">{tr}Known hosts{/tr}</td></tr>
<tr><td colspan="2" class="form">
<table>
<tr><td>&nbsp;</td><td>{tr}Name{/tr}</td><td>{tr}Key{/tr}</td><td>{tr}IP{/tr}</td><td>{tr}Contact{/tr}</td></tr>
{if $known_hosts}
{foreach key=k item=i from=$known_hosts}
<tr><td><a href="tiki-admin.php?page=intertiki&amp;delk={$k|escape:'url'}"><img src="img/icons2/delete.gif" width="16" height="16" border="0" alt="{tr}delete{/tr}" /></a></td>
<td class="form"><input type="text" name="known_hosts[{$k}][name]" value="{$i.name}" size="12" /></td>
<td><input type="text" name="known_hosts[{$k}][key]" value="{$i.key}" size="32" /></td>
<td><input type="text" name="known_hosts[{$k}][ip]" value="{$i.ip}" size="12" /></td>
<td><input type="text" name="known_hosts[{$k}][contact]" value="{$i.contact}" size="22" /></td></tr>
{/foreach}
{/if}
<tr class="formrow"><td>{tr}New{/tr}:</td>
<td><input type="text" name="newhost[name]" value="" size="12" /></td>
<td><input type="text" name="newhost[key]" value="" size="32" /></td>
<td><input type="text" name="newhost[ip]" value="" size="12" /></td>
<td><input type="text" name="newhost[contact]" value="" size="22" /></td></tr>
</table>
</td></tr>
<tr><td colspan="2" class="button"><input type="submit" name="intertikiserver" value="{tr}Save{/tr}" /></td></tr>
</table>
</form>
</div>
</div>


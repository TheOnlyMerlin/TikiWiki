{* $Header: /cvsroot/tikiwiki/tiki/templates/modules/mod-assistant.tpl,v 1.9 2007-03-06 19:30:41 sylvieg Exp $ *}
{if !isset($tpl_module_title)}{eval assign=tpl_module_title var="{tr}Tikiwiki Assistant{/tr}"}{/if}
{tikimodule title=$tpl_module_title name="assistant" flip=$module_params.flip decorations=$module_params.decorations}
<div><em>{tr}Thank you for installing Tikiwiki{/tr}!</em><br />
{tr}Click the :: options in the Menu for more options.{/tr}
{tr}Please, also see{/tr} <a class="link" href="http://tikiwiki.org/TikiMovies">TikiMovies</a> {tr}for more setup details.{/tr}
{if $tiki_p_admin eq 'y'}
<p><strong>{tr}Note 1:{/tr}</strong> {tr}You can remove this module in{/tr} <a class="link" href="tiki-admin_modules.php">{tr}Admin{/tr}&#160;»&#160;{tr}Modules{/tr}</a> {tr}as well as assign or edit many others.{/tr}<br />
<strong>{tr}Note 2:{/tr}</strong> {tr}The menu module installed by default is named{/tr} <em>mnu_application_menu</em> &ndash; {tr}it is a "custom module" which includes menu ID {/tr}42. {tr}That menu is stored in database and it can be edited from {/tr}<a class="link" href="tiki-admin_menus.php">{tr}Admin{/tr}&#160;»&#160;{tr}Menus{/tr}</a>.<br />
({tr}Do not mix this with the original <em>application_menu</em> module{/tr}. {tr}That one can be heavily customized to match style used but it can be currently done only by editing mod-application_menu.tpl file "manually"{/tr})</p>
{/if}
</div>
{/tikimodule}

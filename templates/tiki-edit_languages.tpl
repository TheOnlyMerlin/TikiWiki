{title}{tr}Edit or export/import languages{/tr}{/title}

<div class="navbar">
	{if $interactive_translation_mode eq 'on'}
		{button href="tiki-edit_languages.php?interactive_translation_mode=off" _text="{tr}Toggle interactive translation off{/tr}" _ajax="n"}
	{else}
		{button href="tiki-edit_languages.php?interactive_translation_mode=on" _text="{tr}Toggle interactive translation on{/tr}" _ajax="n"}
	{/if}
</div>

{tabset}
	{tab name="{tr}Edit languages{/tr}"}
		<form action="tiki-edit_languages.php" id="edit_translations" method="post">
			<div class="adminoptionbox">
				<label for="edit_language">{tr}Select the language to edit{/tr}:</label>
				<select id="edit_language" class="edit_translations "name="edit_language">
					{section name=ix loop=$languages}
						<option value="{$languages[ix].value|escape}" {if $edit_language eq $languages[ix].value}selected="selected"{/if}>{$languages[ix].name}</option>
					{/section}
				</select>
			</div>
			<div class="adminoptionbox">
				<input id="add_tran_sw" class="edit_translations" align="right" type="radio" name="action" value="add_tran_sw" {if $action eq 'add_tran_sw'}checked="checked"{/if}/>
				<label for="add_tran_sw">{tr}Add a translation{/tr}</label>
			</div>
			<div class="adminoptionbox">
				<input id="edit_tran_sw" class="edit_translations" align="right" type="radio" name="action" value="edit_tran_sw" {if $action eq 'edit_tran_sw'}checked="checked"{/if}/>
				<label for="edit_tran_sw">{tr}Edit translations{/tr}</label>
				<div class="adminoptionboxchild">
					<input id="only_db_translations" class="edit_translations" type="checkbox" name="only_db_translations" {if $only_db_translations eq 'y'}checked="checked"{/if}>
					<label for="only_db_translations">{tr}Show only database stored translations{/tr}</label>
				</div>
			</div>

			{if $prefs.record_untranslated eq 'y'}
				<div class="adminoptionbox">
					<input id="edit_rec_sw" class="edit_translations" align="right" type="radio" name="action" value="edit_rec_sw" {if $action eq 'edit_rec_sw'}checked="checked"{/if}/>
					<label for="edit_rec_sw">{tr}Translate recorded{/tr}</label>
				</div>
			{/if}
		</form>

		<form action="tiki-edit_languages.php" method="post">
			<input type="hidden" name="edit_language" value="{$edit_language}" />
			{if $action eq 'add_tran_sw'}
				<div class="simplebox">
					<h4>{tr}Add a translation{/tr}:</h4>
					<table class="formcolor">
						<tr>
							<td>{tr}Original{/tr}:</td>
							<td><input name="add_tran_source" size=20 maxlength=255></td>
							<td>{tr}Translation{/tr}:</td>
							<td><input name="add_tran_tran" size=20 maxlength=255></td>
							<td align="center"><input type="submit" name="add_tran" value="{tr}Add{/tr}" /></td>
						</tr>
					</table>
				</div>
			{/if}
			{if $action eq 'edit_tran_sw' || $action eq 'edit_rec_sw'}
				<div class="simplebox">
					<h4>{if $action eq 'edit_tran_sw'}{tr}Edit translations:{/tr}{else}{tr}Translate recorded:{/tr}{/if}</h4>
					<table class="formcolor">
						<tr>
							<td align="left" colspan=4>
								<input name="tran_search" value="{$tran_search|escape}" size=10 />
								<input type="submit" name="tran_search_sm" value="{tr}Search{/tr}" />
								{if $action eq 'edit_rec_sw'}
									<tr><td align="center"><input type="submit" name="tran_reset" value="{tr}Delete all{/tr}" /></td></tr>
								{/if}
							</td>
						</tr>
						{foreach from=$translations name=translations key=source item=translation}
							<tr>
								<td>{tr}Original{/tr}:</td>
								<td><input name="source_{$smarty.foreach.translations.index}" value="{$source|escape}" readonly="readonly"/></td>
								<td>{tr}Translation{/tr}:</td>
								<td><input name="tran_{$smarty.foreach.translations.index}" value="{$translation|escape}" size=20 /></td>
								<td align="center"><input type="submit" name="edit_tran_{$smarty.foreach.translations.index}" value="{tr}Translate{/tr}" /></td>
								{if $action eq 'edit_tran_sw'}
									<td align="center"><input type="submit" name="del_tran_{$smarty.foreach.translations.index}" value="{tr}Delete{/tr}" /></td>
								{/if}
							</tr>
						{/foreach}
					</table>
					<input type="hidden" name="offset" value="{$offset|escape}" />
					<input type="submit" name="translate_all" value="{tr}Translate all{/tr}" />
					{pagination_links cant=$untr_numrows step=$maxRecords offset=$offset}{/pagination_links}
				</div>
			{/if}
		</form>
	{/tab}

	{tab name="{tr}Export languages{/tr}"}
		<form action="tiki-edit_languages.php" method="post">
			{if isset($expmsg)}
			    {remarksbox type="note" title="{tr}Note:{/tr}"}
					{$expmsg}
				{/remarksbox}
			{/if}
			{if (empty($db_languages))}
			    {remarksbox type="note" title="{tr}Note:{/tr}"}
					{tr}No translations in the database available to export. First translate strings using interactive translation or "Edit languages" tab.{/tr}
				{/remarksbox}
			{else}
				<div class="adminoptionbox">
					<label for="exp_language">{tr}Select the language to Export{/tr}:</label>
					<select id="exp_language" name="exp_language">
						{section name=ix loop=$db_languages}
							<option value="{$db_languages[ix].value|escape}"
								{if $exp_language eq $db_languages[ix].value}selected="selected"{/if}>
								{$db_languages[ix].name}
							</option>
						{/section}
					</select>
				</div>
			    {remarksbox type="note" title="{tr}Note:{/tr}"}
					{tr}If you click "Download database translations", you will download a file with all the translations in the database.{/tr}
					{if $tiki_p_admin eq 'y' and $langIsWritable}
						{tr}If you click "Write to language.php", the translations in the database will be merged with the other translations in language.php. Note that after writing translations to language.php they are removed from the database.{/tr}
					{/if}
				{/remarksbox}
				{if !$langIsWritable}
					{remarksbox type="note" title="{tr}Note:{/tr}"}
						{tr}To be able to write your translations back to language.php make sure that the web server has write permission in the lang/ directory.{/tr}
					{/remarksbox}
				{/if}
				<div class="adminoptionbox">
					<input type="submit" name="downloadFile" value="{tr}Download database translations{/tr}" />
					{if $tiki_p_admin eq 'y' and $langIsWritable}
						<input type="submit" name="exportToLanguage" value="{tr}Write to language.php{/tr}" />
					{/if}
				</div>
			{/if}
		</form>
	{/tab}
{/tabset}

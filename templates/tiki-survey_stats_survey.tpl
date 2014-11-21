{title}{tr}Stats for survey:{/tr} {$survey_info.name}{/title}

<div class="t_navbar form-group">
	{self_link print='y'}{icon _id='printer' align='right' hspace='1' alt="{tr}Print{/tr}"}{/self_link}
	{button href="tiki-list_surveys.php" class="btn btn-default" _text="{tr}List Surveys{/tr}"}
	{button href="tiki-survey_stats.php" class="btn btn-default" _text="{tr}Survey Stats{/tr}"}
	{if $tiki_p_admin_surveys eq 'y'}
		{button _keepall='y' href="tiki-admin_surveys.php" surveyId=$surveyId class="btn btn-default" _text="{tr}Edit this Survey{/tr}"}
		{button _keepall='y' href="tiki-survey_stats_survey.php" surveyId=$surveyId clear=$surveyId class="btn btn-default" _text="{tr}Clear Stats{/tr}"}
		{button href="tiki-admin_surveys.php" class="btn btn-default" _text="{tr}Admin Surveys{/tr}"}
	{/if}
</div>
<br>

<div>
<form method="post" action="tiki-survey_stats_survey.php">
{tr}Select a user's responses{/tr} {tr}to be marked as{/tr} {icon _id="user" alt="{tr}User voted{/tr}"}
	<select name="uservoted">
		<option value="" {if empty($uservoted)}selected="selected"{/if}></option>
		{foreach from=$usersthatvoted item=usr}
		<option value="{$usr|escape}" {if $uservoted == $usr}selected="selected"{/if}>{$usr|username}</option>
		{/foreach}
	</select>
	<input type="hidden" name="surveyId" value="{$surveyId|escape}" />
	<input type="submit" class="btn btn-default btn-sm" name="selectuservoted" value="{tr}Select User{/tr}" />
</form>
</div>

{section name=ix loop=$channels}
	<table class="formcolor">
		<tr>
			<th colspan="4">{$channels[ix].question|escape|nl2br}</th>
		</tr>
		{if $channels[ix].type eq 'r'}
			<tr>
				<td class="odd">{tr}Votes:{/tr}</td>
				<td class="odd">{$channels[ix].votes}</td>
			</tr>
			<tr>
				<td class="odd">{tr}Average:{/tr}</td>
				<td class="odd">{$channels[ix].average|string_format:"%.2f"}</td>
			</tr>
		{elseif $channels[ix].type eq 's'}
			<tr>
				<td class="odd">{tr}Votes:{/tr}</td>
				<td class="odd">{$channels[ix].votes}</td>
			</tr>
			<tr>
				<td class="odd">{tr}Average:{/tr}</td>
				<td class="odd">{$channels[ix].average|string_format:"%.2f"}/10</td>
			</tr>
		{elseif $channels[ix].type neq 'h'}
			{section name=jx loop=$channels[ix].qoptions}
				<tr>
					<td class="odd">
						{if $channels[ix].qoptions[jx].uservoted}{icon _id='user' alt="{tr}User voted{/tr}"}{/if}
						{if $channels[ix].type eq 'g'}
							<div style="float:left">
								{thumb _id=$channels[ix].qoptions[jx].qoption _max=40 name='thumb' style='margin:3px;'}
							</div>
							<div>
								{fileinfo _id=$channels[ix].qoptions[jx].qoption _field='name' _link='thumb'}
								<br>{fileinfo _id=$channels[ix].qoptions[jx].qoption _field='description'}
							</div>
						{elseif !$channels[ix].qoptions[jx].qoption}
							({tr}no answer{/tr})
						{else}
							{$channels[ix].qoptions[jx].qoption}
						{/if}
					</td>
					<td class="odd">{$channels[ix].qoptions[jx].votes}</td>
					<td class="odd">{$channels[ix].qoptions[jx].average|string_format:"%.2f"}%</td>
					<td class="odd">{quotabar length=$channels[ix].qoptions[jx].width}</td>
				</tr>
			{/section}
		{/if}
	</table>
	<br>
{/section}

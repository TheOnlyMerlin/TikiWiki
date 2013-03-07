<form action="tiki-admin.php?page=userfiles" method="post">
	<div class="heading input_submit_container" style="text-align: right">
		<input type="submit" name="userfilesprefs" value="{tr}Change preferences{/tr}" />
	</div>

	<fieldset class="admin">
		<legend>{tr}Activate the feature{/tr}</legend>
		{preference name=feature_userfiles visible="always"}
		<div class="adminoptionboxchild" id="feature_userfiles_childcontainer">
			{preference name=feature_use_fgal_for_user_files}
		</div>
	</fieldset>

	{if $prefs.feature_use_fgal_for_user_files eq 'n'}
	<fieldset class="admin">
		<legend>{tr}Settings{/tr}</legend>
		{preference name=userfiles_quota}
		{preference name=userfiles_private}

		<table class="admin">

			<tr>
				<td>{tr}Use database to store userfiles:{/tr}</td>
				<td>
					<input type="radio" name="uf_use_db" value="y" {if $prefs.uf_use_db eq 'y'}checked="checked"{/if}/>
				</td>
			</tr>
			<tr>
				<td>{tr}Use a directory to store userfiles:{/tr}</td>
				<td>
					<input type="radio" name="uf_use_db" value="n" {if $prefs.uf_use_db eq 'n'}checked="checked"{/if}/> {tr}Path:{/tr}
					<br>
					<input type="text" name="uf_use_dir" value="{$prefs.uf_use_dir|escape}" size="50" />
				</td>
			</tr>
		</table>
	</fieldset>
	{else}
		{remarksbox type="tip" title="{tr}Tip{/tr}"}
			{tr}Use <a href="tiki-admin.php?page=fgal&alt=File+Galleries">file gallery admin</a> and <a href="{'tiki-list_file_gallery.php?galleryId='|cat:$prefs.fgal_root_user_id|sefurl}">the file galleries</a> themselves to manage user files settings.{/tr}
		{/remarksbox}
	{/if}
	<div class="heading input_submit_container" style="text-align: center">
 		<input type="submit" name="userfilesprefs" value="{tr}Change preferences{/tr}" />
	</div>
</form>

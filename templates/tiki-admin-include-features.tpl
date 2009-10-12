{* $Id$ *}

{remarksbox type="tip" title="{tr}Tip{/tr}"}{tr}Please see the <a class='rbox-link' target='tikihelp' href='http://doc.tikiwiki.org/Features'>evaluation of each feature</a> on Tiki's developer site.{/tr}{/remarksbox}

	<form class="admin" id="features" name="features" action="tiki-admin.php?page=features" method="post">
		<div class="heading input_submit_container" style="text-align: right">
			<input type="submit" name="features" value="{tr}Apply{/tr}" />
			<input type="reset" name="featuresreset" value="{tr}Reset{/tr}" />
		</div>

{tabset name="admin_features"}
{*
 * The following section is typically for features that act like Tikiwiki
 * sections and add a configuration icon to the sections list
 *}
{* ---------- Main features ------------ *}
{tab name="{tr}Global features{/tr}"}

		<fieldset>
			<legend>{tr}Main feature{/tr}</legend>

			<div class="admin clearfix featurelist">
				{preference name=feature_wiki}
				{preference name=feature_blogs}
				{preference name=feature_file_galleries}
				{preference name=feature_articles}
				{preference name=feature_forums}
				{preference name=feature_trackers}
				{preference name=feature_polls}
				{preference name=feature_calendar}
				{preference name=feature_newsletters}
				{preference name=feature_banners}
			</div>

		</fieldset>

		<fieldset>
			<legend>{tr}Site Global{/tr}</legend>

			<div class="admin featurelist">
				{preference name=feature_categories}
				{preference name=feature_score}
				{preference name=feature_freetags}
				{preference name=feature_search}
				{preference name=feature_actionlog}
				{preference name=feature_contribution}
				{preference name=feature_multilingual}
				{preference name=feature_fullscreen}
			</div>
		</fieldset>

		<fieldset>
			<legend>{tr}Additional{/tr}</legend>

			<div class="admin featurelist">
				{preference name=feature_surveys}
				{preference name=feature_directory}
				{preference name=feature_quizzes}
				{preference name=feature_copyright}
				{preference name=feature_shoutbox}
				{preference name=feature_maps}
				{preference name=feature_gmap}
				{preference name=feature_live_support}
				{preference name=feature_tell_a_friend}
				{preference name=feature_minichat}
				{preference name=feature_comments_moderation}
				{preference name=feature_comments_locking}
				{preference name=feature_comments_post_as_anonymous}				
			</div>
		</fieldset>

{/tab}
			
{tab name="{tr}Programmer{/tr}"}
			<div class="admin featurelist">
				{preference name=feature_integrator}
				{preference name=feature_xmlrpc}
				{preference name=feature_debug_console}
				{preference name=feature_tikitests}
				{preference name=feature_webservices}
			</div>
{/tab}

{* ---------- Experimental features ------------ *}
{tab name="{tr}Experimental{/tr}"}
			<div class="admin featurelist">
				<fieldset>
					<legend class="heading">{icon _id="accept"}<span>{tr}New{/tr}</span></legend>
					<span class="description">{tr}These features are relatively new. You should expect growing pains and possibly lacking documentation, as you would of a version 1.0 application{/tr}</span>
						{preference name=feature_perspective}
						{preference name=feature_quick_object_perms}
						{preference name=feature_kaltura}
						{preference name=feature_wiki_mindmap}
						{preference name=feature_print_indexed}
				</fieldset>

			<div class="admin featurelist">
				<fieldset>
					<legend class="heading">{icon _id="error"}<span>{tr}Phased out{/tr}</span></legend>
					<span class="description">{tr}These features generally work but will probably be phased out in the future, because they are superseded by other features or because of evolution in Web technology{/tr}</span>
						{preference name=feature_html_pages}
						{preference name=feature_galleries}
						{preference name=feature_faqs}
				</fieldset>

				<fieldset>
					<legend class="heading">{icon _id="accept"}<span>{tr}Seem ok{/tr}</span></legend>
					<span class="description">{tr}Features that may change or might be re-worked in the future{/tr}</span>
						{preference name=feature_ajax}
						{preference name=feature_mobile}
						{preference name=feature_morcego}
						{preference name=feature_webmail}
						{preference name=feature_comm}
				</fieldset>

				<fieldset>
					<legend class="heading">{icon _id="error"}<span>{tr}Need polish{/tr}</span></legend>
					<span class="description">{tr}Features that need admin help and user patience to work well{/tr}</span>
						{preference name=feature_intertiki}
						{preference name=feature_mailin}
						{preference name=feature_sefurl}
						{preference name=feature_sheet}
						{preference name=feature_wysiwyg}
						{preference name=feature_friends}
						{preference name=feature_ajax_autosave}
						{preference name=feature_wiki_save_draft}

				</fieldset>

				<fieldset>
					<legend class="heading">{icon _id="exclamation"}<span>{tr}Malfunctioning{/tr}</span></legend>
					<span class="description">{tr}These features have critical faults - not recommended{/tr}</span>
						{preference name=feature_workspaces}
				</fieldset>

				<fieldset>
					<legend class="heading">{icon _id="information_gray"}<span>{tr}Neglected{/tr}</span></legend>
					<span class="description">{tr}Old features no longer maintained{/tr}</span>
						{preference name=feature_multimedia}
						{preference name=feature_custom_home}
				</fieldset>
			</div>
{/tab}
{/tabset}


	<div class="input_submit_container" style="margin-top: 5px; text-align: center">
		<input type="submit" name="features" value="{tr}Apply{/tr}" />
	</div>
</form>

{* $Header: /cvsroot/tikiwiki/tiki/templates/confirm.tpl,v 1.7 2006-03-16 13:43:12 sylvieg Exp $ *}
        <div class="cbox">
        <br />
        <div class="cbox-data">
				<form action="{$confirmaction}" method="post">
{if $ticket}<input value="{$ticket}" name="ticket" type="hidden" />{/if}
				<input type="submit" name="daconfirm" value="{$confirmation_text}" />
        <span class="button2"><a href="javascript:history.back()" class="linkbut">{tr}Go back{/tr}</a></span>
        <span class="button2"><a href="{$tikiIndex}" class="linkbut">{tr}Return to home page{/tr}</a></span>
				</form>
        </div>
        </div><br />

      </div>

{* $Header: /cvsroot/tikiwiki/tiki/templates/modules/mod-shoutbox.tpl,v 1.10 2003-08-01 10:31:14 redflo Exp $ *}

{if $feature_shoutbox eq 'y' and $tiki_p_view_shoutbox eq 'y'}
  <div class="box">
    <div class="box-title">{tr}Shoutbox{/tr}</div>
    <div class="box-data">
    {if $tiki_p_post_shoutbox eq 'y'}
      <form action="{$shout_ownurl}" method="post">
      <div align="center">
        <textarea rows="3" class="tshoutbox"  name="shout_msg" maxlength="250"></textarea>
	<br/>
	<input type="submit" name="shout_send" value="{tr}send{/tr}" />
      </div><br/>
      </form>
    {/if}

    {section loop=$shout_msgs name=ix}
      <div class="shoutboxmodmsg">

        {* TODO: IMHO Using 'modifier' is not best solution here
         *       so I forced to hack its result to inject 'title' attribute!
         *}

        {assign var=userlink value=$shout_msgs[ix].user|userlink:"linkmodule"}

        {capture name=date}{strip} {* Print date *}
          {$shout_msgs[ix].timestamp|tiki_short_time}, {$shout_msgs[ix].timestamp|tiki_short_date}
        {/strip}{/capture}

	{* Show user message in style according to 'tooltip' module parameter *}
	{if $tooltip == 1}
          <b>{strip}{$userlink|replace:" class=":" title='$smarty.capture.date' class="}{/strip}</b>:
        {else}
          <b>{strip}{$userlink}{/strip}</b>, {$smarty.capture.date}:
        {/if}
        {$shout_msgs[ix].message}
        {if $tiki_p_admin_shoutbox eq 'y'}
          [<a href="{$shout_ownurl}shout_remove={$shout_msgs[ix].msgId}" class="linkmodule">x</a>|<a href="tiki-shoutbox.php?msgId={$shout_msgs[ix].msgId}" class="linkmodule">e</a>]
        {/if}
      </div>
    {/section}
    </div>
  </div>
{/if}

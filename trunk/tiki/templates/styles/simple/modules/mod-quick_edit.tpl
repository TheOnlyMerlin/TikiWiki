{* $Header: /cvsroot/tikiwiki/tiki/templates/styles/simple/modules/mod-quick_edit.tpl,v 1.1 2003-09-19 19:38:21 luciash Exp $ *}
{if $tiki_p_edit eq 'y'}
  <div class="box">
    <div class="box-title">
      {include file="modules/module-title.tpl" module_title="{tr}Quick edit a Wiki page{/tr}" module_name="quick_edit"}
    </div>
    <div class="box-data">
      <form method="get" action="tiki-editpage.php">
        <input type="text" size="15" name="page" />
        <input type="submit" name="quickedit" value="{tr}edit{/tr}" />
      </form>
    </div>
  </div>
{/if}

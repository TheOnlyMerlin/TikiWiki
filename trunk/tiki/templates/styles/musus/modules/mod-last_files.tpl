{* $Header: /cvsroot/tikiwiki/tiki/templates/styles/musus/modules/mod-last_files.tpl,v 1.2 2004-01-16 18:37:49 musus Exp $ *}

{if $feature_file_galleries eq 'y'}
{if $nonums eq 'y'}
{eval var="{tr}Last `$module_rows` Files{/tr}" assign="tpl_module_title"}
{else}
{eval var="{tr}Last Files{/tr}" assign="tpl_module_title"}
{/if}
{tikimodule title=$tpl_module_title name="last_files"}
  <table border="0" cellpadding="0" cellspacing="0">
    {section name=ix loop=$modLastFiles}
      <tr class="module">
        {if $nonums != 'y'}<td>{$smarty.section.ix.index_next})</td>{/if}
        <td>
          <a class="linkmodule" href="tiki-download_file.php?fileId={$modLastFiles[ix].fileId}">
            {$modLastFiles[ix].filename}
          </a>
        </td>
      </tr>
    {/section}
  </table>
{/tikimodule}
{/if}

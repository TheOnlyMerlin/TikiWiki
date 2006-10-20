{if $toc_type eq 'fancy' and $structure_tree.description}
	{$leafspace}<li class="fancytoclevel">{if $numbering}{$structure_tree.prefix} {/if}{if $showdesc}{$structure_tree.description}: {/if}<a href="tiki-index.php?page_ref_id={$structure_tree.page_ref_id}" class="link" title="{$structure_tree.description|escape}">{if $hilite}<b>{/if}{$structure_tree.pageName}{if $hilite}</b>{/if}</a> {if !$showdesc}: {$structure_tree.description}{/if}
{else}
	{$leafspace}<li class="toclevel">{if $numbering}{$structure_tree.prefix} {/if}<a href="tiki-index.php?page_ref_id={$structure_tree.page_ref_id}" class="link" title="{if $showdesc}{$structure_tree.pageName}{else}{$structure_tree.description|escape}{/if}">{if $hilite}<b>{/if}{if $showdesc}{$structure_tree.description}{else}{$structure_tree.pageName}{/if}{if $hilite}</b>{/if}</a>
{/if}

{if $smarty.server.SCRIPT_NAME|stristr:"tiki-register.php" eq false}
	{tikimodule error=$module_params.error title=$tpl_module_title name="register" flip=$module_params.flip decorations=$module_params.decorations nobox=$module_params.nobox notitle=$module_params.notitle}
		{include file='tiki-register.tpl'}
	{/tikimodule}
{/if}

<div class="pagecontrols">
	<div class="clearfix top">
		<h1>{$controls.heading}</h1>
		<div class="pageactions">
			<ul class="clearfix cssmenu" id="pagecontrols">
				{foreach from=$controls.menus item=menu key=m}
					{if $m !== 'watchgroup' and $m !== 'structwatchgroup'}
					<li{if $menu.items} class="menuSection"{/if}><a href="#" title="{$menu} items">{$menu}</a>
						<ul>
							{foreach from=$menu.items key=k item=item}
								{if $k !== 'watch' and $k !== 'structwatch'}
									{if $item.selected}
									<li class="selected">{$item}</li>
									{else}
									<li>{$item}</li>
									{/if}
								{/if}
							{/foreach}
						</ul>
					</li>
					{/if}
				{/foreach}
				{if $controls.help}
					<li>{$controls.help.full}</li>
				{/if}
				</ul>
			</div>
		</div>
	<div class="tabs">
		<div class="icons">
			{if $controls.actions.watch}
				{$controls.actions.watch.icon}
			{/if}

			{if $controls.actions.structwatch}
				{$controls.actions.structwatch.icon}
			{/if}

			{if $controls.watchgroup}
				{popup_link block=page_group_watch}{$controls.watchgroup.icon}{/popup_link}
			{/if}

			{if $controls.structwatchgroup}
				{popup_link block=structure_group_watch}{$controls.structwatchgroup.icon}{/popup_link}
			{/if}
			<div id="page_group_watch" class="popup-group-watch">
				{foreach from=$controls.watchgroup.items item=watch}
					<div>{$watch.icon} {$watch.text}</div>
				{/foreach}
			</div>
			<div id="structure_group_watch" class="popup-group-watch">
				{foreach from=$controls.structwatchgroup.items item=watch}
					<div>{$watch.icon} {$watch.text}</div>
				{/foreach}
			</div>
		</div>
		{foreach from=$controls.tabs item=tab}
			<span class="tabmark {if $tab.selected}tabactive{else}tabinactive{/if}">
				{$tab}
			</span>
		{/foreach}
	</div>
</div>

<h1>{$userWS_title}</h1>

<table>
<tr>
   <th>Name</th>
   <th>Description</th>
   <th>Path</th>
</tr> 
{foreach from=$listWS item=data}
	<tr>
		<td><a href = {$data.href}>{$data.name}</a></td>
		<td>{$data.description}</td>
		<td>{$data.wspath}</td>
	</tr>
{/foreach}
</table>

<table>
<tr>
   <th>Object Name</th>
   <th>Type</th>
   <th>Description</th>
</tr> 
{foreach from=$resources item=data}
	<tr>
		<td><a>{$data.name}</a></td>
		<td>{$data.type}</td>
		<td>{$data.description}</td>
	</tr>
{/foreach}
</table>

<p> "I am still learning how to deal with smartasses..." </p>

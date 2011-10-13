<?php
require_once('tiki-setup.php');

$sheetlib = TikiLib::lib("sheet");
$sheetlib->setup_jquery_sheet();

$headerlib = TikiLib::lib("header");

$headerlib->add_cssfile("lib/jquery/jtrack/css/jtrack.css");
$headerlib->add_jsfile("lib/jquery/jtrack/js/domcached-0.1-jquery.js");
$headerlib->add_jsfile("lib/jquery/jtrack/js/jtrack.js");


$headerlib->add_jq_onready("
	jTask.init();
	
	var table = $('<table title=/>').attr('title', tr('Overview'));
	table.append('<tr><td>Task</td><td>Estimate</td><td>Time Spent</td></tr>');
	
	var rowI = 1;
	for (var item in $.DOMCached.storage) {
		var row = $('<tr />').appendTo(table);
		
		row.append('<td>' + item + '</td>');
		row.append('<td>' + $.DOMCached.storage[item].estimate.value + '</td>');
		row.append('<td>' + $.DOMCached.storage[item].timer.value + '</td>');
		rowI++;
	}
	var row = $('<tr />').appendTo(table);		
	row.append('<td>Totals</td>');
	row.append($('<td></td>').attr('formula', '=(SUM(B2:B' + rowI +  '))'));
	row.append($('<td></td>').attr('formula', '=(SUM(C2:C' + rowI +  ') / 60) + \'Minutes\''));
	
	$('#timesheetSpreadsheet')
		.html(table)
		.sheet({
			buildSheet: true,
			editable: false
		});
");
$smarty->assign('mid', 'tiki-timesheet.tpl');
// use tiki_full to include include CSS and JavaScript
$smarty->display("tiki.tpl");
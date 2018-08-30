function closeDialog(dialogName){
	$('#' + dialogName).window('close');
}

function openDialog(dialogName){
	$('#' + dialogName).dialog('open');
}

function reloadGrid(gridName){
	$('#' + gridName).datagrid('reload');
}

function getGridRow(gridName, index){
	$('#' + gridName).datagrid('selectRow', index);
	return $('#' + gridName).datagrid('getSelected');
}

function editTitle(dialogName, titleName){
	$('#' + dialogName).window({title:titleName});
}

function setButton(gridName, btn){
	$('#' + gridName).datagrid('getPager').pagination({
        buttons: btn
    });
}
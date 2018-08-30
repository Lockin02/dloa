function configurationGrid() {
	$('#configuration_table').datagrid({
		idField: 'id',
		singleSelect: true,
		toolbar: configurationGridButton(),
		pagination: false
	});
}

function configurationGridButton() {
	var button = new Array();
	button.push({
		iconCls: 'icon-add',
		text: '����BOM',
		handler: function() {
			openImport(true);
		}
	});
	button.push({
		iconCls: 'icon-remove',
		text: '����BOM',
		handler: function() {
			export_bom();
		}
	});
	button.push({
		iconCls: 'icon-remove',
		text: 'ɾ��BOM',
		handler: function() {
			deleteProduct();
		}
	});
	/*
	button.push({
		iconCls: 'icon-search',
		text: '�鿴/����BOM',
		handler: function() {
			openViewDialog('view');
		}
	});
	button.push({
		iconCls: 'icon-search',
		text: '���Ʒ���õ�',
		handler: function() {
			reviewMuchProduct();
		}
	});
	button.push({
		iconCls: 'icon-edit',
		text: '��������',
		handler: function() {
			export_bom();
		}
	});*/
	return button;
}


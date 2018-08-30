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
		text: '导入BOM',
		handler: function() {
			openImport(true);
		}
	});
	button.push({
		iconCls: 'icon-remove',
		text: '导出BOM',
		handler: function() {
			export_bom();
		}
	});
	button.push({
		iconCls: 'icon-remove',
		text: '删除BOM',
		handler: function() {
			deleteProduct();
		}
	});
	/*
	button.push({
		iconCls: 'icon-search',
		text: '查看/创建BOM',
		handler: function() {
			openViewDialog('view');
		}
	});
	button.push({
		iconCls: 'icon-search',
		text: '多产品配置单',
		handler: function() {
			reviewMuchProduct();
		}
	});
	button.push({
		iconCls: 'icon-edit',
		text: '物料类型',
		handler: function() {
			export_bom();
		}
	});*/
	return button;
}


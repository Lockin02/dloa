var show_page = function(page) {
	$("#inventoryGrid").yxgrid_inventory("reload");
};
$(function() {
	$("#tree").yxtree({
		// data : getDeptTreeData(),
		url : "?model=deptuser_user_user&action=deptusertree&deptIds="
				+ $('#parentId').val(),
		param : ['id', 'Depart_x', 'Dflag'],
		nameCol : "name",
		event : {
			"node_click" : function(event, treeId, treeNode) {
				var inventory = $("#inventoryGrid").data('inventoryGrid');
				inventory.options.extParam['typeId'] = treeNode.id;

				inventory.reload();
				$("#parentId").val(treeNode.id);
				$("#parentName").val(treeNode.typeName);

			}
		}
	});
	$("#inventoryGrid").yxgrid_inventory({
		isAddAction : false,
		buttonsEx : [{
			name : 'add',
			text : "新增",
			icon : 'add',
			action : function(row) {
				showThickboxWin("?model=system_portal_portlet&action=toAdd"
						+ "&typeId="
						+ $('#parentId').val()
						+ "&typeName="
						+ $('#parentName').val()
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		}, {
			name : 'import',
			text : '导入',
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=hr_invent_inventory&action=toImport"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=550");
			}
		}]
	});
});
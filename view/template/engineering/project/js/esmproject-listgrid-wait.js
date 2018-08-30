$(function() {
	$("#esmprojectGrid").esmprojectgrid({
		action : 'pageJsonWait',
		title : '待立项项目',
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=engineering_project_esmproject&action=readTab"
							+ "&id=" + row.id);
				} else {
					alert("请选中一条数据");
				}
			}

		}, {
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '部门审批') {
					return false;
				}
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=engineering_project_esmproject&action=editTab"
							+ "&id=" + row.id);
				} else {
					alert("请选中一条数据");
				}
			}
		}]

	});

});
$(function() {
	$("#esmprojectGrid").esmprojectgrid({
		action : 'pageJsonEnd',
		title : '已关闭项目',

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
		}]
	});

});
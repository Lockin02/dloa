$(function() {
	$("#esmprojectGrid").esmprojectgrid({
		action : 'pageJsonNotRec',
		title : '未接收项目',

		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=engineering_project_esmproject&action=readTab"
							+ "&id=" + row.id);
				} else {
					alert("请选中一条数据");
				}
			}
		},
		{
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status != '7' && row.status != '8' &&row.status != '2'&&row.status != '6' ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(row){
					showOpenWin("?model=engineering_project_esmproject&action=editTab"
						+ "&id="
						+ row.id);
				}else{
					alert("请选中一条数据");
				}
			}
		}, {
			name : 'exam',
			text : '指定项目经理',
			icon : 'edit',
			action : function(row) {
				showThickboxWin("?model=engineering_project_esmproject&action=designateManager"
						+ "&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 250 + "&width=" + 600);
			}
		}]
	});

});
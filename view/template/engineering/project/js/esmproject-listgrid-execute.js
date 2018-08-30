$(function() {
	$("#esmprojectGrid").esmprojectgrid({
		action : 'pageJsonExecute',
		title : '正执行项目',
		// 扩展右键菜单
		menusEx : [
		{
			text : '查看',
			icon : 'view',
			action :function(row,rows,grid) {
				if(row){
					showOpenWin("?model=engineering_project_esmproject&action=readTab"
						+ "&id="
						+ row.id);
				}else{
					alert("请选中一条数据");
				}
			}
		},{
			text : '反馈',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '6') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=engineering_project_esmproject&action=showFeedback"
						+ "&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 300 + "&width=" + 600
					);
			}
		},{
			text : '关闭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '6') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=engineering_project_esmproject&action=closeProject"
						+ "&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 200 + "&width=" + 600
					);
			}
		}]

	});

});
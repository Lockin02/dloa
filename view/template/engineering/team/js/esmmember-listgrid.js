// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$("#esmmemberGrid").esmmemberGrid("reload");
};

(function($) {
	$.woo.yxgrid.subclass('woo.esmmemberGrid', {
		options : {
			sortname : "id",
			isViewAction : false,
			isEditAction : false,
			// 默认搜索顺序
			model : 'engineering_team_esmmember',
			action : 'pageJson&pjId=' + pjId,
			sortorder : "ASC",
			colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					}, {
						display : '成员名称',
						name : 'memberName',
						sortable : true,
						width : 180
					}, {
						display : '角色',
						name : 'projectCode',
						sortable : true
					}, {
						display : '任务',
						name : 'officeName',
						sortable : true
					}, {
						display : '项目架构图',
						name : 'managerName',
						sortable : true
					}],// 快速搜索
			searchitems : [{
						display : '成员名称',
						name : 'searchmemberName'
					}],
			toAddConfig : {
				text : '新增',
				/**
				 * 默认点击新增按钮触发事件
				 */
				toAddFn : function(p) {
					showThickboxWin("?model="
							+ p.model
							+ "&action=toAdd"
							+ '&pjId=' + pjId
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ 200 + "&width=" + 600);
				}
			}
		}
	});
})(jQuery);
(function($) {
	// 这里要取最新的变更信息，明天搞
	$.woo.yxgrid.subclass('woo.yxgrid_changeLogDetail', {
		options : {
			model : 'common_changeLog',
			action : 'pageJsonDetail',
			showcheckbox : false,
			isToolBar : false,
			/**
			 * 是否显示右键菜单，如果为flase，则右键菜单失效
			 *
			 * @type Boolean
			 */
			isRightMenu : false,
			// 表单
			colModel : [{
						name : 'objField',
						width : 200,
						display : '对象名称'
					}, {
						name : 'changeFieldCn',
						width : 150,
						display : '变更属性'
					}, {
						name : 'oldValue',
						width : 150,
						display : '变更前值'
					}, {
						name : 'newValue',
						width : 150,
						display : '变更后值'
					}],
			sortorder : "DESC",
			sortname : "id",
			title : '变更信息',
			buttonsEx : [{
				text : "查看本次变更",
				icon : 'view',
				action : function(rowData, rows, rowIds, g) {
					g.options.param.isLast=true;
					g.reload();
				}
			},{
				text : "查看所有",
				icon : 'view',
				action : function(rowData, rows, rowIds, g) {
					delete g.options.param.isLast;
					g.reload();
				}
			}]
		}
	});
})(jQuery);
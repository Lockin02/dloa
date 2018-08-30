var show_page = function(page) {
	$("#logoperationGrid").yxgrid("reload");
};
$(function() {
	$("#logoperationGrid").yxgrid({
		model : 'syslog_operation_logoperation',
		action : 'pageDetailJson',
		param : {
			"tableName" : $("#tableName").val()
		},
		title : '操作日志',
		isAddAction : false,
		isViewAction : true,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'logSettingId',
					display : '日志设置id',
					sortable : true,
					hide : true
				}, {
					name : 'businessName',
					display : '业务名称',
					sortable : true
				}, {
					name : 'tableName',
					display : '表名',
					sortable : true,
					width : 150,
					hide : true
				}, {
					name : 'operationType',
					display : '操作类型',
					sortable : true,
					width : 50
				}, {
					name : 'pkValue',
					display : '业务主键字段值',
					sortable : true,
					hide : true
				}, {
					name : 'logContent',
					display : '日志详细内容',
					sortable : true,
					width : 500
				}, {
					name : 'createName',
					display : '操作人',
					sortable : true
				}, {
					name : 'createTime',
					display : '操作时间',
					sortable : true,
					width : 150

				}],
        buttonsEx : [{
			name : 'view',
			text : "高级查询",
			icon : 'view',
			action : function() {
				showThickboxWin("?model=syslog_operation_logoperation&action=toSearch&"
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
			}
        }],
		menusEx : [{
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				return true;
			},
			action : function(row, rows, grid) {
				if (window.confirm("确认要删除?")) {
					$.ajax({
						type : "POST",
						url : "?model=syslog_operation_logoperation&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('删除成功！');
							} else {
								alert('删除失败，该对象可能已经被引用!');
							}
						}
					});
				}
			}
		}],
		searchitems : [{
					display : "业务名称",
					name : 'businessName'
				}, {
					display : "操作人",
					name : 'createName'
				}]
	});
});
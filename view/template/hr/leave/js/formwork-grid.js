var show_page = function(page) {
	$("#formworkGrid").yxgrid("reload");
};
$(function() {
	$("#formworkGrid").yxgrid({
		model : 'hr_leave_formwork',
		title : '离职清单模板',
		showcheckbox : false,
		isDelAction : false,
		isOpButton : false,
		event : {
				'row_dblclick' : function(e, row, data) {
					showThickboxWin("?model=hr_leave_formwork&action=toView&id=" + data.id
									+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900"
					);
 				}
			},
		// 扩展右键菜单
		menusEx : [{
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_leave_formwork&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							alert(msg);
							if (msg == 1) {
								alert('删除成功！');
								$("#formworkGrid").yxgrid("reload");
							}
						}
					});
				}
			}

		}],
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'items',
			display : '交接项目',
			sortable : true,
			width : 200
		}, {
			name : 'recipientName',
			display : '接收人',
			sortable : true
		}, {
			name : 'createName',
			display : '创建人',
			sortable : true
		}, {
			name : 'createTime',
			display : '创建时间',
			sortable : true,
			width : 200
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "搜索字段",
			name : 'XXX'
		}]
	});
});
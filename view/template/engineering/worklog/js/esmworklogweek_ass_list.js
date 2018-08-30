var show_page = function(page) {
	$(".assworklogweekGrid").yxgrid("reload");
};
$(function() {
	$(".assworklogweekGrid").yxgrid({
		model : 'engineering_worklog_esmworklogweek',
		title : '周报考核信息',
		showcheckbox: false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'weekTitle',
			display : '日志标题',
			width : 300,
			sortable : true
		}, {
			name : 'weekTimes',
			display : '周次',
			sortable : true
		}, {
			name : 'rankCode',
			display : '级别',
			sortable : true
		}, {
			name : 'directlyId',
			display : '直属上级id',
			sortable : true,
			hide : true
		}, {
			name : 'directlyName',
			display : '直属上级名称',
			sortable : true
		}, {
			name : 'createName',
			display : '创建人名称',
			sortable : true
		}, {
			name : 'subStatus',
			display : '提交状态',
			datacode : 'ZBZT',
			sortable : true
		}],
		// 扩展按钮
		buttonsEx : [{

			name : 'excel',
			text : '导出EXCEL(按条件)',
			icon : 'excel',
			action : function(row, rows, grid) {
						showThickboxWin("?model=engineering_worklog_esmworklogweek&action=exportExcelpage&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900");
			}
		},{

			name : 'excel',
			text : '导出EXCEL(全部)',
			icon : 'excel',
			action : function(row, rows, grid) {
						showThickboxWin("?model=engineering_worklog_esmworklogweek&action=exportExcels&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900");
			}
		}],
		// 扩展右键菜单
		menusEx : [{
			name : 'viewass',
			text : '查看详细',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=engineering_worklog_esmworklogweek&action=myassTaskview&id="
							+ row.id
							+ "&subStatus="
							+ row.subStatus
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=700&width=900");
				} else {
					alert("请选中一条数据");
				}
			}
		}],
		// 快速搜索
		searchitems : [{
			display : '日志标题',
			name : 'weekTitle'
		}],
		// title : '客户信息',
		// 业务对象名称
		boName : '日志',
		// 默认搜索字段名
		sortname : "weekTitle",
		// 默认搜索顺序
		sortorder : "ASC",
		// 显示查看按钮
		isViewAction : false,
		// 隐藏添加按钮
		isAddAction : false,
		// 隐藏删除按钮
		isDelAction : false,
		// 隐藏编辑按钮
		isEditAction : false,
		toViewConfig : {
			action : 'toRead',
			/**
			 * 查看表单默认宽度
			 */
			formWidth : 900,
			/**
			 * 查看表单默认高度
			 */
			formHeight : 800
		}
	})
})

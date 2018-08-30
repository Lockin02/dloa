var show_page = function(page) {
	$(".pjlogGrid").yxgrid("reload");
};
$(function() {
	$(".pjlogGrid").yxgrid({
		model : 'engineering_worklog_esmworklogweek',
		action : 'projectWeekLog',
		param : {
			"pjId" : $("#pjId").val()
		},
		title : '项目周志',
		isToolBar : false,
		showcheckbox: false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'weekTitle',
			display : '标题',
			width : 300,
			sortable : true
		}, {
			name : 'weekTimes',
			display : '周次',
			sortable : true,
			// 特殊处理字段函数
			process : function(v, row) {
				return "第" + v + "周";
			}
		}, {
			name : 'rankCode',
			display : '级别',
			sortable : true
		}, {
			name : 'rankCodeId',
			display : '级别id',
			sortable : true,
			hide : true
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
			display : '提交人名称',
			sortable : true
		}, {
			name : 'subStatus',
			display : '提交状态',
			datacode : 'ZBZT',
			sortable : true
		}],
		// 扩展按钮
		buttonsEx : [],
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
			display : '周次',
			name : 'weekTimes'
		}],
		// title : '客户信息',
		// 业务对象名称
		boName : '周志',
		// 默认搜索字段名
		sortname : "weekTitle",
		// 默认搜索顺序
		sortorder : "ASC",
		// 隐藏查看按钮
		isViewAction : false,
		// 隐藏编辑按钮
		isEditAction : false,
		// 隐藏添加按钮
		isAddAction : false,
		// 隐藏删除按钮
		isDelAction : false,
		// 隐藏编辑按钮
		idEditAction : false

	})
});

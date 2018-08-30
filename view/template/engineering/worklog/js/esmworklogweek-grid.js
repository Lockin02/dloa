var show_page = function(page) {
	$(".esmworklogweekGrid").yxgrid("reload");
};
$(function() {
	$(".esmworklogweekGrid").yxgrid({
		model : 'engineering_worklog_esmworklogweek',
		action : 'getmyweeklog',
		title : '工作日志周报',
		showcheckbox : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'weekTimes',
			display : '周次',
			// 特殊处理字段函数
			process : function(v, row) {
				return "第" + v + "周";
			},
			sortable : true
		}, {
			name : 'weekTitle',
			display : '标题',
			width : 300,
			sortable : true
		}, {
			name : 'weekBeginDate',
			display : '开始日期',
			sortable : true
		}, {
			name : 'weekEndDate',
			display : '结束日期',
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
			name : 'existence',
			display : '本周存在问题及得到的帮助',
			sortable : true,
			hide : true
		}, {
			name : 'improvement',
			display : '本周技能提升举证',
			sortable : true,
			hide : true
		}, {
			name : 'createName',
			display : '填写人',
			sortable : true
		}, {
			name : 'assessmentName',
			display : '考核人',
			sortable : true
		}, {
			name : 'subStatus',
			display : '状态',
			datacode : 'ZBZT',
			sortable : true
		}],
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		// 快速搜索
		searchitems : [{
			display : '周志标题',
			name : 'weekTitle'
		}],
		// title : '客户信息',
		// 业务对象名称
		boName : '周志',
		// 默认搜索字段名
		sortname : "weekTitle",
		toAddConfig : {
			text : '填写工作日志',
			/**
			 * 默认点击查看按钮触发事件
			 */
			toAddFn : function(p, g) {
				showThickboxWin("?model=engineering_worklog_esmworklog&action=toAdd&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 400 + "&width=" + 800);
			}
		},
		toEditConfig : {
			text : '总结',
			/**
			 * 默认点击查看按钮触发事件
			 */
			toAddFn : function(p, g) {
				showThickboxWin("?model=engineering_worklog_esmworklogweek&action=toEdit&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 600 + "&width=" + 800);
			}
		},
		// 扩展右键菜单
		menusEx : [{
			name : 'submit',
			text : '提交',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.subStatus == 'ZBSHZ' || row.subStatus == 'ZBYKH') {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=engineering_worklog_esmworklogweek&action=toSubmit&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			name : 'submit',
			text : '修改提交',
			icon : 'edit',
			showMenuFn : function(row) {
				if ( row.subStatus == 'ZBYKH' || row.subStatus == 'ZBWTJ' ) {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=engineering_worklog_esmworklogweek&action=toSubmit&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
				} else {
					alert("请选中一条数据");
				}
			}
		},{
			name : 'summary',
			text : '总结',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.subStatus == 'ZBSHZ' || row.subStatus == 'ZBYKH') {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=engineering_worklog_esmworklogweek&action=toEdit&id="
							+ row.id
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ 600 + "&width=" + 800);
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
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
		// 扩展工具栏按钮
		buttonsEx : []
	})

});
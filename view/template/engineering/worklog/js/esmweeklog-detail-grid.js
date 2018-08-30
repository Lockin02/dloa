var show_page = function(page) {
	$("#esmweeklogGrid").yxgrid("reload");
};

$(function() {
	$("#esmweeklogGrid").yxgrid({
		model : 'engineering_worklog_esmweeklog',
		param : {
			'createId' : $('#createId').val()
		},
		title : '周报',
		showcheckbox : false,
		// 列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'weekTitle',
				display : '周报标题',
				sortable : true,
				width : '300'
			}, {
				name : 'weekTimes',
				display : '周次',
				sortable : true,
				width : 50
			}, {
				name : 'weekBeginDate',
				display : '开始日期',
				sortable : true
			}, {
				name : 'weekEndDate',
				display : '结束日期',
				sortable : true
			}, {
				name : 'depId',
				display : '部门id',
				sortable : true,
				hide : true
			}, {
				name : 'depName',
				display : '部门名称',
				sortable : true
			}, {
				name : 'assessmentId',
				display : '考核人ID',
				sortable : true,
				hide : true
			}, {
				name : 'assessmentName',
				display : '考核人',
				sortable : true
			}, {
				name : 'subStatus',
				display : '提交状态',
				sortable : true
			}, {
				name : 'updateTime',
				display : '更新时间',
				sortable : true,
				width : 150
			}],
		toAddConfig : {
			toAddFn : function(p) {
				showModalWin("?model=engineering_worklog_esmworklog&action=toAdd&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=750");
			}
		},
		menusEx : [{
			text : '打开周报',
			icon : 'view',
			action : function(row, rows, grid) {
				showModalWin("?model=engineering_worklog_esmweeklog&action=init&perm=view&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=1100");
			}
		}],
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		sortorder : "DESC",
		sortname : "weekBeginDate",
		searchitems : [{
			display : "部门名称",
			name : 'depName'
		}, {
			display : "考核人",
			name : 'assessmentName'
		}, {
			display : "修改时间",
			name : 'updateTime'
		}]
	});
});
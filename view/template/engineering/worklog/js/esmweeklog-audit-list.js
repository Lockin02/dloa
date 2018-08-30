var show_page = function(page) {
	$("#esmweeklogGrid").yxsubgrid("reload");
};

$(function() {
	$("#esmweeklogGrid").yxsubgrid({
		model : 'engineering_worklog_esmweeklog',
		param : {
			'assessmentId' : $('#loginId').val()
		},
		title : '周报',
		showcheckbox : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'weekTitle',
				display : '周报标题',
				sortable : true,
				width : '300',
				hide : true
			}, {
				name : 'createName',
				display : '填写人',
				sortable : true
			}, {
				name : 'depId',
				display : '部门id',
				sortable : true,
				hide : true
			}, {
				name : 'depName',
				display : '所属部门',
				sortable : true,
				width : 80
			}, {
				name : 'weekTimes',
				display : '周次',
				sortable : true,
				process : function(v,row){
					return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_worklog_esmweeklog&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ +"\")'>" + v + "</a>";
				},
				width : 60
			}, {
				name : 'weekBeginDate',
				display : '开始日期',
				sortable : true,
				width : 80
			}, {
				name : 'weekEndDate',
				display : '结束日期',
				sortable : true,
				width : 80
			}, {
				name : 'assessmentId',
				display : '考核人ID',
				sortable : true,
				hide : true
			}, {
				name : 'subStatus',
				display : '提交状态',
				sortable : true,
				process : function(v) {
					if (v == "WTJ") {
						return "未提交";
					} else if (v == "YTJ") {
						return "已提交";
					} else if( v=="BTG"){
						return " 不通过";
					}else{
						return "已确认";
					}
				},
				width : 70
			}, {
				name : 'assessmentName',
				display : '考 核 人',
				sortable : true
			}, {
				name : 'exaDate',
				display : '考核日期',
				sortable : true,
				width : 80
			}, {
				name : 'rsLevel',
				display : '考核等级',
				sortable : true,
				hide : true,
				width : 80
			}, {
				name : 'rsScore',
				display : '考核分数',
				sortable : true,
				width : 80
			}, {
				name : 'updateTime',
				display : '更新时间',
				sortable : true,
				width : 130
			}
		],
		subGridOptions : {
			url : '?model=engineering_worklog_esmworklog&action=pageJson',
			param : [{
					paramId : 'weekId',
					colId : 'id'
				}
			],
			colModel : [{
					name : 'projectName',
					display : '项目名称',
					width : 160
				}, {
					name : 'executionDate',
					display : '执行日期',
					width : 80
				}, {
					name : 'province',
					display : '所在省份',
					width : 80
				}, {
					name : 'city',
					display : '所在城市',
					width : 80
				}, {
					name : 'workStatus',
					display : '工作状态',
					datacode : 'GXRYZT',
					width : 60
				}, {
					name : 'workloadDay',
					display : '工作量',
					width : 60
				}, {
					name : 'workloadUnitName',
					display : '工作量单位',
					width : 60
				}, {
					name : 'description',
					display : '工作内容',
					width : 300
				}
			]
		},
		comboEx : [{
			text : '状态',
			key : 'subStatus',
			value : 'YTJ',
			data : [{
				text : '已提交',
				value : 'YTJ'
			}, {
				text : '已确认',
				value : 'YQR'
			}, {
					text : '不通过',
					value : 'BTG'
			}]
		}],
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				showModalWin("?model=engineering_worklog_esmweeklog&action=init&perm=view&id="
						+ row.id );
			}
		}, {
			text : '考核确认',
			icon : 'business',
			showMenuFn : function(row) {
				if (row.subStatus == "YTJ") {
					return true;
				} else {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				showModalWin("?model=engineering_worklog_esmweeklog&action=toAudit&id="+row.id);
			}
		}],
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		sortorder : "DESC",
		sortname : "weekBeginDate",
		searchitems : [{
					display : "填写人",
					name : 'createName'
				}, {
					display : "部门名称",
					name : 'depName'
				}, {
					display : "确认人",
					name : 'assessmentName'
				}, {
					display : "周 次",
					name : 'weekTimes'
				}, {
					display : "周报日期",
					name : 'worklogDate'
				}, {
					display : "修改时间",
					name : 'updateTime'
				}]
	});
});
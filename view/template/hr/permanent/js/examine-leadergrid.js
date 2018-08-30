var show_page = function (page) {
	$("#examineGrid").yxgrid("reload");
};
$(function () {
	$("#examineGrid").yxgrid({
		model : 'hr_permanent_examine',
		title : '员工转正考核评估表',
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		param : {
			leaderId : $("#leaderid").val(),
			statusArr : "3,4,5,6,7,8"
		},
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'formCode',
				display : '单据编号',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_permanent_examine&action=toView&id="+row.id+"\")'>"+v+"</a>"
				},
				width : 120
			}, {
				name : 'formDate',
				display : '单据日期',
				sortable : true
			}, {
				name : 'statusC',
				display : '状态'
			}, {
				name : 'ExaStatus',
				display : '审核状态',
				sortable : true
			}, {
				name : 'userNo',
				display : '员工编号',
				sortable : true
			}, {
				name : 'useName',
				display : '姓名',
				sortable : true
			}, {
				name : 'sex',
				display : '性别',
				sortable : true,
				width:30
			}, {
				name : 'deptName',
				display : '部门',
				sortable : true,
				width:40
			}, {
				name : 'positionName',
				display : '职位',
				sortable : true
			}, {
				name : 'permanentType',
				display : '转正类型',
				sortable : true,
				width : 80
			}, {
				name : 'begintime',
				display : '入职时间',
				sortable : true,
				width : 80
			}, {
				name : 'finishtime',
				display : '计划转正时间',
				sortable : true,
				width : 80
			},{
				name : 'permanentDate',
				display : '实际转正日期',
				width : 80
			},{
				name : 'selfScore',
				display : '考核总分',
				sortable : true,
				width : 80
			}, {
				name : 'totalScore',
				display : '复核成绩',
				sortable : true,
				width : 80
			}
		],
		toEditConfig : {
			text : '填写意见',
			showMenuFn : function(row) {
				if (row.status == 3&&row.ExaStatus== "未审核") {
					return true;
				} else
					return false;
			},
			toEditFn : function(p, g){
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_permanent_examine&action=toLeaderEdit&id=" + rowData[p.keyField]);
				}
			}
		},
		toViewConfig : {
			toViewFn : function(p, g){
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_permanent_examine&action=toView&id=" + rowData[p.keyField]);
				}
			}
		},
		menusEx : [{
			text : '添加领导意见',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 3&&row.ExaStatus== "未审核") {
					return true;
				} else
					return false;
			},
			action : function(row, rows, grid){
					showOpenWin("?model=hr_permanent_examine&action=toLeaderEdit&id=" + row.id);
			}
		},{
			text : '提交总监审核',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 3&&row.ExaStatus== "未审核") {
					return true;
				} else
					return false;
			},
			action : function(row, rows, grid) {
				location = "controller/hr/permanent/ewf_examine_index.php?actTo=ewfSelect&billId="+row.id+"&examCode=oa_hr_permanent_examine&formName=试用考核评估";
			}
		}],
		comboEx : [{
			text : '状态',
			key : 'status',
			data : [{
						text : '领导审核',
						value : '3'
					}, {
						text : '员工确认',
						value : '6'
					}, {
						text : '完成',
						value : '7'
					}, {
						text : '关闭',
						value : '8'
					}]
		},{
			text : '审批状态',
			key : 'ExaStatus',
			data : [{
						text : '部门审批',
						value : '部门审批'
					}, {
						text : '未审核',
						value : '未审核'
					}, {
						text : '完成',
						value : '完成'
					}, {
						text : '打回',
						value : '打回'
					}]
			}],
		searchitems : [{
				display : "员工姓名",
				name : 'useName'
			},{
				display : "员工是否同意",
				name : 'isAgree'
			},{
				display : "填表日期",
				name : 'reformDT'
			},{
				display : "部门",
				name : 'deptName'
			},{
				display : "职位",
				name : 'positionName'
			}
		]
	});
});
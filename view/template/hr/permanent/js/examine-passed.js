var show_page = function (page) {
	$("#examineGrid").yxgrid("reload");
};
$(function () {
	var info = $("#userid").val();//获取账户信息
	$("#examineGrid").yxgrid({
		model : 'hr_permanent_examine',
		title : '已转正列表',
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		param : {
			LinkAccount : info,
			statusArr : "7,8"
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
				display : '试用开始时间',
				sortable : true,
				width : 80
			}, {
				name : 'finishtime',
				display : '试用结束时间',
				sortable : true,
				width : 80
			}, {
				name : 'permanentDate',
				display : '实际转正日期',
				width : 80
			}
		],
		toViewConfig : {
			toViewFn : function(p, g){
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_permanent_examine&action=toView&id=" + rowData[p.keyField]);
				}
			}
		},
		comboEx : [{
			text : '状态',
			key : 'status',
			data : [ {
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
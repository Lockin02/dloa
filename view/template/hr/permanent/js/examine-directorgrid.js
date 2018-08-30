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
		showcheckbox : false,
		param : {
//			DeptArr : $("#userid").val(),
			statusArr : "4,5,6,7,8"
		},
		isOpButton:false,
		bodyAlign:'center',

		//列信息
		colModel : [ {
			name : 'formCode',
			display : '单据编号',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showOpenWin(\"?model=hr_permanent_examine&action=toView&id="+row.id+"\")'>"+v+"</a>"
			},
			width : 130
		},{
			name : 'formDate',
			display : '单据日期',
			sortable : true,
			width:80
		},{
			name : 'statusC',
			display : '状态',
			width:60
		},{
			name : 'ExaStatus',
			display : '审核状态',
			sortable : true,
			width:60
		},{
			name : 'userNo',
			display : '员工编号',
			sortable : true,
			width:80
		},{
			name : 'useName',
			display : '姓名',
			sortable : true,
			width:60
		},{
			name : 'sex',
			display : '性别',
			sortable : true,
			width:30
		},{
			name : 'deptName',
			display : '部门',
			sortable : true
		},{
			name : 'positionName',
			display : '职位',
			sortable : true
		},{
			name : 'permanentType',
			display : '转正类型',
			sortable : true,
			width : 80
		},{
			name : 'begintime',
			display : '入职时间',
			sortable : true,
			width : 80
		},{
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
			display : '自我评分',
			sortable : true,
			width : 80
		},{
			name : 'totalScore',
			display : '导师评分',
			sortable : true,
			width : 70
		},{
			name : 'leaderScore',
			display : '领导评分',
			sortable : true,
			width : 70
		}],

		lockCol:['userNo','useName','deptName'],//锁定的列名

		toEditConfig : {
			showMenuFn : function(row) {
				if (row.isAgree == 2 && row.status == 7) {
					return true;
				} else {
					return false;
				}
			},
			toEditFn : function(p, g){
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_permanent_examine&action=toDirectorSet&id=" + rowData[p.keyField]);
				}
			}
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_permanent_examine&action=toView&id=" + rowData[p.keyField] + "&isDirector=1");
				}
			}
		},

		menusEx : [{
			text : '修改薪酬',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isAgree == 2 && row.status == 7) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				showOpenWin("?model=hr_permanent_examine&action=toDirectorSet&id=" + row.id);
			}
		}],

		comboEx : [{
			text : '状态',
			key : 'status',
			data : [{
				text : '领导审核',
				value : '3'
			},{
				text : '总监审核',
				value : '4'
			},{
				text : '人力审核',
				value : '5'
			},{
				text : '员工确认',
				value : '6'
			},{
				text : '完成',
				value : '7'
			},{
				text : '关闭',
				value : '8'
			}]
		},{
			text : '审批状态',
			key : 'ExaStatus',
			data : [{
				text : '部门审批',
				value : '部门审批'
			},{
				text : '未审核',
				value : '未审核'
			},{
				text : '完成',
				value : '完成'
			},{
				text : '打回',
				value : '打回'
			}]
		},{
			text : '是否同意',
			key : 'isAgree',
			data : [{
				text : '是',
				value : '1'
			},{
				text : '否',
				value : '2'
			},{
				text : '未填写',
				value : '0'
			}]
		}],

		searchitems : [{
			display : "员工编号",
			name : 'userNo'
		},{
			display : "姓名",
			name : 'useName'
		},{
			display : "部门",
			name : 'deptName'
		},{
			display : "单据编号",
			name : 'formCode'
		},{
			display : "单据日期",
			name : 'formCode'
		},{
			display : "性别",
			name : 'sex'
		},{
			display : "职位",
			name : 'positionName'
		},{
			display : "转正类型",
			name : 'permanentType'
		},{
			display : "入职时间",
			name : 'begintime'
		},{
			display : "计划转正时间",
			name : 'finishtime'
		},{
			display : "实际转正日期",
			name : 'permanentDate'
		},{
			display : "自我评分",
			name : 'selfScore'
		},{
			display : "导师评分",
			name : 'totalScore'
		},{
			display : "领导评分",
			name : 'leaderScore'
		}]
	});
});
var show_page = function (page) {
	$("#examineGrid").yxgrid("reload");
};

$(function () {
	$("#examineGrid").yxgrid({
		model : 'hr_permanent_examine',
		title : '员工转正考核评估表',
		action : 'hrJson',
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		showcheckbox : true,
		param : {
			statusArr : "1,2,3,4,5,6,7,8,9"
		},
		isOpButton:false,
		bodyAlign:'center',
		buttonsEx : [ {
			name : 'expport',
			text : "导出",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=hr_permanent_examine&action=toExport&docType=RKPURCHASE"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=650");
			}
		},{
			name : 'excelOut',
			text : "导出列表",
			icon : 'excel',
			action : function(row) {
				window.open("?model=hr_permanent_examine&action=excelOut"
					+ "&status=" + $("#status").val()
					+ "&ExaStatus=" + $("#ExaStatus").val()
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=40&width=60");
			}
		},{
			text:'邮件提醒',
			icon:'add',
			action:function(row,rows,grid){
				rowsId = [];
				useNameArr = [];
				userAccountArr = [];
				if(row){
					for(var i = 0 ;i < rows.length ;i++) {
						rowsId.push(rows[i].id);
						useNameArr.push(rows[i].useName);
						userAccountArr.push(rows[i].userAccount);
					}
					showThickboxWin("?model=hr_permanent_examine&action=toMailMsg&id="
						+ rowsId
						+ "&userName=" + useNameArr
						+ "&userAccount=" + userAccountArr
						+ "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
				} else {
					alert("请选择一条数据");
				}
			}
		}],

		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'userNo',
			display : '员工编号',
			sortable : true,
			width : 70
			// process : function(v,row){
			// 	return "<a href='#' onclick='showOpenWin(\"?model=hr_personnel_personnel&action=toCodeView&userNo="+v+"\")'>"+v+"</a>"
			// }
		},{
			name : 'useName',
			display : '姓名',
			sortable : true,
			width : 60
		},{
			name : 'permanentMonth',
			display : '实际转正月份',
			sortable : true,
			width : 70
		},{
			name : 'statusC',
			display : '状态',
			width : 60
		},{
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
			width : 80
		},{
			name : 'ExaStatus',
			display : '审核状态',
			sortable : true,
			width : 60
		},{
			name : 'isAgree',
			display : '员工同意情况',
			sortable : true,
			width : 70,
			process : function(v ,row) {
				if(v == 0)
					return "未填写";
				else if(v == 1) {
					return "同意";
				}else if(v == 2) {
					return "不同意" ;
				}
			}
		},{
			name : 'permanentType',
			display : '转正类型',
			sortable : true,
			width : 70
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
			width : 70
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
		},{
			name : 'interviewSalary',
			display : '面试确定转正工资'
		},{
			name : 'suggestSalary',
			display : '转正工资建议薪点'
		},{
			name : 'suggestJobName',
			display : '建议职位',
			width : 80
		},{
			name : 'schemeName',
			display : '考核方案',
			width : 80,
			hide : true
		}],

		lockCol:['userNo','useName'],//锁定的列名

		toViewConfig : {
			showMenuFn : function(row) {
				if (row.formCode != "") {
					return true;
				} else {
					return false;
				}
			},
			toViewFn : function(p, g){
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					if(rowData.formCode != ""){
						showOpenWin("?model=hr_permanent_examine&action=toView&id=" + rowData[p.keyField]);
					}
				}
			}
		},

		menusEx : [{
			text : '填写hr建议',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 5 && row.ExaStatus == "未审核" ) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=hr_permanent_examine&action=toEditSalary&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
			}
		},{
			text : '提交审核',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 5 && row.ExaStatus== "未审核" || row.ExaStatus == '打回') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				location = "controller/hr/permanent/ewf_hr_index.php?actTo=ewfSelect&billId=" + row.id + "&examCode=oa_hr_permanent_examine&billDept="+row.deptId;
			}
		},{
			text : '修改薪酬',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isAgree != 1 && row.ExaStatus== "完成") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				showOpenWin("?model=hr_permanent_examine&action=toDirectorSet&id=" + row.id);
			}
		},{
			text : '邮件提醒',
			icon : 'add',
			action : function(row, rows, grid) {
				showThickboxWin("?model=hr_permanent_examine&action=toMailMsg&id="
					+ row.id
					+ "&userName=" + row.useName
					+ "&userAccount=" + row.userAccount
					+ "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
			}
		}],

		comboEx : [{
			text : '状态',
			key : 'status',
			data : [{
				text : '导师审核',
				value : '2'
			},{
				text : '领导审核',
				value : '3'
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
			},{
				text : '未填写',
				value : '9'
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
		}],

		searchitems : [{
			display : "员工编号",
			name : 'userNo'
		},{
			display : "姓名",
			name : 'useName'
		},{
			display : "单据编号",
			name : 'formCode'
		},{
			display : "单据日期",
			name : 'formCode'
		},{
			display : "员工是否同意",
			name : 'isAgree'
		},{
			display : "转正类型",
			name : 'permanentType'
		},{
			display : "填表日期",
			name : 'reformDT'
		},{
			display : "性别",
			name : 'sex'
		},{
			display : "部门",
			name : 'deptName'
		},{
			display : "职位",
			name : 'positionName'
		},{
			display : "转正年份",
			name : 'finishYear'
		},{
			display : "转正月份",
			name : 'finishMonth'
		},{
			display : "实际转正月份",
			name : 'permanentMonth'
		},{
			display : "建议职位",
			name : 'suggestJobName'
		},{
			display : "考核方案",
			name : 'schemeName'
		}],

		// 默认搜索字段名
		sortname : "statuss",

		// 默认搜索顺序
		sortorder : "ASC"
	});
});
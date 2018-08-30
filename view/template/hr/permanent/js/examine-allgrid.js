var show_page = function (page) {
	$("#examineGrid").yxgrid("reload");
};

$(function () {
	var info = $("#userid").val();//获取账户信息
	$("#examineGrid").yxgrid({
		model : 'hr_permanent_examine',
		title : '转正考核评估表',
		isDelAction : false,
		isAddAction : true,
		showcheckbox : false,
		param : {
			LinkAccount : info
//			statusArr : "1,2,3,4,5,6,7,8"
		},
		isOpButton:false,
		bodyAlign:'center',

		//列信息
		colModel : [{
			name : 'formCode',
			display : '单据编号',
			sortable : true,
			process : function(v ,row) {
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
		}],

		lockCol:['formCode','formDate','useName'],//锁定的列名

		toEditConfig : {
			showMenuFn : function(row) {
				if (row.status == 1 && row.userAccount == info) {
					return true;
				} else {
					return false;
				}
			},
			toEditFn : function(p, g){
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_permanent_examine&action=toEdit&id=" + rowData[p.keyField]);
				}
			}
		},
		toAddConfig : {
			toAddFn : function(p, g){
				alert("您好，新OA已上线，请到新OA提交申请。谢谢！");
				return false;
				showOpenWin("?model=hr_permanent_examine&action=toAdd");
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
			text : '提交导师审核',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.userAccount == info && row.status == 1 && row.planstatus == 0 && row.summarystatus == 0 && row.tutorId != '') {
					return true;
				} else {
					return false;
				}
			},
			action : function(rowData, rows, rowIds, g) {
				if (window.confirm(("确定要提交?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_permanent_examine&action=giveMaster",
						data : {
							id : rowData.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('提交成功！');
								g.reload();
							} else {
								alert('提交失败！');
							}
						}
					});
				}
			}
		},{
			text : '提交领导审核',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.userAccount == info&&row.status == 1&& row.planstatus == 0&& row.summarystatus == 0&& row.tutorId=='') {
					return true;
				} else {
					return false;
				}
			},
			action : function(rowData, rows, rowIds, g) {
				if (window.confirm(("确定要提交?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_permanent_examine&action=giveLeader",
						data : {
							id : rowData.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('提交成功！');
								g.reload();
							} else {
								alert('提交失败！');
							}
						}
					});
				}
			}
		},{
			text : '提交总监审核',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.userAccount == info && row.status == 1 && row.planstatus == 1 && row.summarystatus == 1) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				location = "?model=hr_permanent_examine&action=isAccept&id=" + row.id + "&schemeId=" + row.schemeId;
			}
		},{
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.userAccount == info && row.status == 1) {
					return true;
				} else {
					return false;
				}
			},
			action : function(rowData, rows, rowIds, g) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_permanent_examine&action=ajaxdeletes",
						data : {
							id : rowData.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								g.reload();
							}
						}
					});
				}
			}
		},{
			text : '确认转正详情',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.userAccount == info && row.status == 6) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showOpenWin("?model=hr_permanent_examine&action=tolastedit&id="+row.id)
			}
		},{
		//////////////////////////员工操作////////////////////////
			text : '添加导师意见',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.tutorId == info && row.status == 2) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid){
				showOpenWin("?model=hr_permanent_examine&action=toMasterEdit&id=" + row.id);
			}
		},{
			text : '提交直接领导审核',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.tutorId == info && row.status == 2) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				if (window.confirm(("确定要提交?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_permanent_examine&action=giveLeader",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('提交成功！');
								show_page();
							} else {
								alert('提交失败！');
							}
						}
					});
				}
			}
		},{
		/////////////////////导师操作//////////////////////////
			text : '添加领导意见',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.leaderId == info && row.status == 3 && row.ExaStatus == "未审核") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid){
				showModalWin("?model=hr_permanent_examine&action=toLeaderEdit&id=" + row.id);
			}
		},{
			text : '提交HR审核',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.leaderId == info && row.status == 3 && row.ExaStatus == "未审核") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				if (window.confirm(("确定要提交?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_permanent_examine&action=giveHr",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('提交成功！');
								show_page();
							} else {
								alert('提交失败！');
							}
						}
					});
				}
			}
//		},{
//			text : '提交总监审核',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row.leaderId == info&&row.status == 3&&row.ExaStatus== "未审核") {
//					return true;
//				} else
//					return false;
//			},
//			action : function(row, rows, grid) {
//				location = "controller/hr/permanent/ewf_examine_index.php?actTo=ewfSelect&billId="+row.id+"&examCode=oa_hr_permanent_examine&formName=试用考核评估";
//			}
		/////////////////领导操作//////////////////////
		}],

		comboEx : [{
			text : '状态',
			key : 'status',
			data : [ {
				text : '保存',
				value : '1'
			},{
				text : '导师审核',
				value : '2'
			},{
				text : '领导审核',
				value : '3'
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
		}],

		searchitems : [{
			display : "单据编号",
			name : 'formCode'
		},{
			display : "单据日期",
			name : 'formCode'
		},{
			display : "员工编号",
			name : 'userNo'
		},{
			display : "姓名",
			name : 'useName'
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
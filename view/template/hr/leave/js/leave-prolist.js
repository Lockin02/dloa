var show_page = function(page) {
	$("#leaveGrid").yxgrid("reload");
};

$(function() {
	$("#leaveGrid").yxgrid({
		model : 'hr_leave_leave',
		param : {
			'userAccount' : $("#userId").val()
		},
		title : '离职申请',
		showcheckbox : false,
		isDelAction : false,
		isEditAction : true,
		isAddAction : false,
		isOpButton : false,
		bodyAlign:'center',

		buttonsEx : [{
			name : 'Add',
			text : "新增",
			icon : 'add',
			action : function(row) {
				alert("您好，新OA已上线，请到新OA提交申请。谢谢！");
				return false;
				showThickboxWin('?model=hr_leave_leave&action=staffToAdd&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
			}
		}],

		event : {
			row_dblclick : function(e, row, data) {
				showThickboxWin("?model=hr_leave_leave&action=toView&id=" + data.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
		},

		// 扩展右键菜单
		menusEx : [{
			text : '查看交接清单',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.isHandover == '1' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=hr_leave_handover&action=toViewByApply&leaveId=' + row.id );
			}
		},{
			text : '确认交接清单',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' && row.isHandover=='1' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=hr_leave_handover&action=handoverProlist&leaveId=' + row.id );
			}
		},{
			text : '提交',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
//				if (window.confirm(("确定要提交?"))) {
//					$.ajax({
//						type : "POST",
//						url : "?model=hr_leave_leave&action=ajaxSubmit",
//						data : {
//							id : row.id,
//							state:1
//						},
//						success : function(msg) {
//							if (msg == 1) {
//								alert('提交成功！');
//								$("#leaveGrid").yxgrid("reload");
//							}
//						}
//					});
//				}
				if (window.confirm(("您好，新OA已上线，跳转到新OA重新提交申请?"))) {
					location.href = "index1.php?model=common_otherdatas&action=toSignInAws";
				}
			}
		},{
			name : 'cancel',
			text : '撤回申请',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.state == '2' && row.ExaStatus == '部门审批') || (row.state == '1' && row.ExaStatus == '未提交')|| (row.state == '2'&& row.ExaStatus == '未提交')) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if(row.ExaStatus == "未提交"){
						$.ajax({
							type : "POST",
							url : "?model=hr_leave_leave&action=ajaxSubmit",
							data : {
								id : row.id,
								state:0
							},
							success : function(msg) {
								if (msg == 1) {
									alert('撤回成功！');
									$("#leaveGrid").yxgrid("reload");
								}
							}
						});
					} else {
						var ewfurl = 'controller/hr/leave/ewf_index1.php?actTo=delWork&billId=';
						$.ajax({
							type : "POST",
							url : "?model=common_workflow_workflow&action=isAudited",
							data : {
								billId : row.id,
								examCode : 'oa_hr_leave'
							},
							success : function(msg) {
								if (msg == '1') {
									alert('单据已经存在审批信息，不能撤回审批！');
									$("#leaveGrid").yxgrid("reload");
									return false;
								} else {
									if(confirm('确定要撤回审批吗？')){
										$.ajax({
											type: "GET",
											url: ewfurl,
											data: {"billId" : row.id },
											async: false,
											success: function(data){
												$.ajax({
													type : "POST",
													url : "?model=hr_leave_leave&action=ajaxSubmit",
													data : {
														id : row.id,
														state:0
													},
													success : function(msg) {
														if (msg == 1) {
															alert('撤回成功！');
															$("#leaveGrid").yxgrid("reload");
														}
													}
												});
											}
										});
									}
								}
							}
						});
					}
				} else {
					alert("请选中一条数据");
				}
			}
		},{
			name : 'aduit',
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_leave&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		},{
			name : 'close',
			text : '关闭原因',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.state == '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=hr_leave_leave&action=toCloseReason&id='
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");
			}
		},{
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_leave_leave&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								$("#leaveGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			text : '撤销',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要撤销?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_leave_leave&action=updateExaStatus",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('撤销成功！');
								$("#leaveGrid").yxgrid("reload");
							} else{
								alert('撤销失败！');
								$("#leaveGrid").yxgrid("reload");
							}
						}
					});
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
			name : 'leaveCode',
			display : '单据编号',
			sortable : true,
			process : function(v, row) {
				return '<a href="javascript:void(0)" title="点击查看" onclick="javascript:showThickboxWin(\'?model=hr_leave_leave&action=toView&id='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
					+ "<font color = '#4169E1'>"
					+ v
					+ "</font>"
					+ '</a>';
			}
		},{
			name : 'userNo',
			display : '员工编号',
			width : 80,
			sortable : true
		},{
			name : 'userName',
			display : '员工姓名',
			width : 60,
			sortable : true
		},{
			name : 'deptName',
			display : '部门',
			width : 80,
			sortable : true
		},{
			name : 'jobName',
			display : '职位',
			width : 80,
			sortable : true
		},{
			name : 'entryDate',
			display : '入职日期',
			width : 80,
			sortable : true
		},{
			name : 'state',
			display : '单据状态',
			sortable : true,
			width:70,
			process:function(v ,row) {
				if (v == "0") {
					return "未提交";
				} else if (v == "1") {
					return "未确认类型 ";
				} else if (v == "2") {
					if(row.ExaStatus =='完成' && row.nowDate > row.comfirmQuitDate) {
						return "档案待更新";
					} else {
						return "已确认类型";
					}
				} else if (v == '4') {
					return "已关闭";
				} else {
					return "已更新档案";
				}
			}
		},{
			name : 'quitTypeName',
			display : '离职类型',
			width : 60,
			sortable : true
		},{
			name : 'requireDate',
			display : '要求离职日期',
			width : 80,
			sortable : true
		},{
			name : 'ExaStatus',
			display : '审批状态',
			width : 60,
			sortable : true
		},{
			name : 'createName',
			display : '申请人',
			width : 60,
			sortable : true
		},{
			name : 'quitReson',
			display : '离职原因',
			width : 300,
			sortable : true,
			align : 'left',
			process :　function(v) {
				//提取离职原因，替换特殊字符
				var str = v.substring(-5);
				if (str == "^nbsp") { //没有包含其他原因
					v = v.replace(/\^nbsp/g,"；");
				} else {
					var num =  v.split("^nbsp").length - 1;
					for (var i = 0; i < num - 1; i++) {
						v = v.replace(/\^nbsp/,"；");
					}
					v = v.replace(/\^nbsp/,":"); //最后一个为其他
				}

				return v;
			}
		}],

		lockCol:['leaveCode','userNo','useName'],//锁定的列名

		toEditConfig : {
			showMenuFn : function(row) {
				if (row.state == '0') {
					return true;
				}
				return false;
			},
			formHeight : 600,
			action : 'toEdit'
		},
		toViewConfig : {
			formHeight : 600,
			action : 'toView'
		},

		searchitems : [{
			display : "单据编号",
			name : 'leaveCode'
		},{
			display : "员工编号",
			name : 'userNo'
		},{
			display : "员工姓名",
			name : 'userName'
		}]
	});
});
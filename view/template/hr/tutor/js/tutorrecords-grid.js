var show_page = function(page) {
	$("#tutorrecordsGrid").yxgrid("reload");
};
$(function() {
	// 表头按钮数组
	buttonsArr = [
	// {
	// name : 'view',
	// text : "高级查询",
	// icon : 'view',
	// action : function() {
	// alert('功能暂未开发完成');
	// showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
	// +
	// '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
	// }
	// }
	];

	// 表头按钮数组
	excelOutArr = {
		name : 'exportIn',
		text : "导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_tutor_tutorrecords&action=toExcelIn"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};

	excelOutArr2 = {
		name : 'exportOut',
		text : "导出",
		icon : 'excel',
		action : function(row) {
			location = "?model=hr_tutor_tutorrecords&action=export";
		}
	};

	$.ajax({
				type : 'POST',
				url : '?model=hr_tutor_tutorrecords&action=getLimits',
				data : {
					'limitName' : '导入权限'
				},
				async : false,
				success : function(data) {
					if (data == 1) {
						buttonsArr.push(excelOutArr);
						buttonsArr.push(excelOutArr2);
					}
				}
			});

	$("#tutorrecordsGrid").yxgrid({
		model : 'hr_tutor_tutorrecords',
		title : '导师经历',
		showcheckbox:false,
		isAddAction : true,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		bodyAlign:'center',
		customCode : 'hr_tutor_hrlist',
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'userNo',
					display : '导师员工编号',
					width:80,
					sortable : true
				},  {
					name : 'userName',
					display : '导师姓名',
					width:70,
					sortable : true,
					process : function(v, row) {
						return "<a href='#' onclick='showThickboxWin(\"?model=hr_tutor_tutorrecords&action=toView&id="
								+ row.id
								+ '&skey='
								+ row.skey_
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>"
								+ v + "</a>";
					}
				},  {
					name : 'jobName',
					display : '导师职位',
					width:80,
					sortable : true
				}, {
					name : 'deptName',
					display : '导师部门',
					sortable : true,
					width:80,
					hide : true
				}, {
					name : 'studentNo',
					display : '学员员工编号',
					sortable : true,
					width:80,
					hide : true
				}, {
					name : 'studentName',
					display : '学员姓名',
					width:70,
					sortable : true
				}, {
					name : 'studentDeptName',
					display : '学员部门',
					width:80,
					sortable : true
				}, {
					name : 'realBecomeDate',
					display : '转正时间',
					width:80,
					process : function(v, row) {
						if(v!=""){
							return v;
						}else{
							return row.becomeDate;
						}
					},
					sortable : true
				}, {
					name : 'status',
					display : '当前状态',
					width:80,
					sortable : true,
					process : function(v, row) {
						switch (v) {
							case '0' :
								return "辅导期";
								break;
							case '1' :
								return "辅导期";
								break;
							case '3' :
								return "导师考核";
								break;
							case '4' :
								return "完成";
								break;
							case '5' :
								return "已关闭";
								break;
						}
					}
				}, {
					name : 'beginDate',
					display : '教学开始时间',
					width:80,
					sortable : true
				}, {
					name : 'assessmentScore',
					display : '考核分数',
					width:60,
					sortable : true
				}, {
					name : 'rewardPrice',
					display : '奖 励(元)',
					width:80,
					sortable : true
				},{
					name : 'isCoachplan',
					display : '是否需要制定辅导计划',
					sortable : true,
					width : 130
				},{
					name : 'isWeekly',
					display : '是否需要按照HR的模式提交周报',
					sortable : true,
					width : 170
				}, {
					name : 'closeReason',
					display : '关闭理由',
					sortable : true,
					width : 130
				}],
		lockCol:['userName','userNo','jobName'],//锁定的列名
		menusEx : [{
			text : '更换领导',
			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row.status == '0' || row.status == '1') {
//					return true;
//				}
//				return false;
//			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=hr_tutor_tutorrecords&action=toEditLeader&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=800");
			}
		},{
			text : '周报',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.status != '5') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row.role == "导师") {
					showModalWin("?model=hr_tutor_weekly&role=导师&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				} else if (row.role == "学员") {
					showModalWin("?model=hr_tutor_weekly&role=学员&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				} else {
					showModalWin("?model=hr_tutor_weekly&role=其他&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				}
			}
		}, {
			text : '员工辅导计划',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.status != '5') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row.role == "导师") {
					showModalWin("?model=hr_tutor_coachplan&action=toCoachplan&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				} else {
					showModalWin("?model=hr_tutor_coachplan&action=toView&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				}
			}
		}, {
			text : '导师考核表',
			icon : 'add',
			// showMenuFn : function(row) {
			// if (row.status == 1 || row.status == 4) {
			// return true;
			// } else {
			// return false;
			// }
			// },
			action : function(row, rows, grid) {
				showModalWin("?model=hr_tutor_scheme&action=toTutorAssess&id="
						+ row.id);
			}
		}, {
			text : '导师考核评分',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 3) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if (row) {
					showModalWin("?model=hr_tutor_scheme&action=toScore&tutorId="
							+ row.id + "&role=HR");
				}
			}
		}, {
			text : '编辑考核分数',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 4||row.status == 5) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if (row) {
					showThickboxWin("?model=hr_tutor_tutorrecords&action=toEditScore&id="
							+ row.id
							+ "&role=HR&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=800");
				}
			}
		},{
			text : '编辑',
			icon : 'edit',
			action : function(row) {
				if (row) {
					showThickboxWin("?model=hr_tutor_tutorrecords&action=toEditModel&id="
							+ row.id + "&role=HR&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=800");
				}
			}
		},{
			text : '完成',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status != 4 && row.status != 5) {
					return true;
				} else {
					return false;
				}
			},
			action:function(row){
				if(window.confirm(("确定要将这条记录状态改为完成?"))){
					$.ajax({
						type : "POST",
						url : "?model=hr_tutor_tutorrecords&action=complete",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('状态修改成功！');
								$("#tutorrecordsGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			text : '关闭',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status != 4 && row.status != 5) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if (row) {
					showThickboxWin("?model=hr_tutor_tutorrecords&action=toCloseTutorrecords&id="
							+ row.id
							+ "&role=HR&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		}],
		toEditConfig : {
			showMenuFn : function(row) {
				if ((row.id == "noId")) {
					return false;
				}
			},
			action : 'toEdit',
			formWidth : '800',
			formHeight : '400'
		},
		toViewConfig : {
			showMenuFn : function(row) {
				if ((row.id == "noId")) {
					return false;
				}
			},
			action : 'toView',
			formWidth : '800',
			formHeight : '400'
		},
		toDelConfig : {
			showMenuFn : function(row) {
				if ((row.id == "noId")) {
					return false;
				}
			}
		},
		buttonsEx : buttonsArr,
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		comboEx : [{
					text : '状态',
					key : 'statusArr',
					data : [{
								text : '辅导期',
								value : '1'
							}
							// , {
							// text : '员工考核',
							// value : '2'
							// }
							, {
								text : '导师考核',
								value : '3'
							}, {
								text : '完成',
								value : '4'
							}, {
								text : '已关闭',
								value : '5'
							}]
				}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
					display : "导师员工编号",
					name : 'userNoM'
				}, {
					display : "导师姓名",
					name : 'userNameM'
				}, {
					display : "学员姓名",
					name : 'studentNameM'
				}, {
					display : "学员部门",
					name : 'studentDeptNameM'
				}]
	});
});
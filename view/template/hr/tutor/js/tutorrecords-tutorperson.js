var show_page = function(page) {
	$("#personGrid").yxgrid("reload");
};
$(function() {
	// 表头按钮数组
	// buttonsArr = [
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
	// ];

	$("#personGrid").yxgrid({
		model : 'hr_tutor_tutorrecords',
		action : 'personJson',
		param : {
			'personIdSearch' : $("#userId").val()
		},
		title : '导师管理',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isOpButton : false,
		bodyAlign:'center',
		customCode : 'hr_tutor_mylist',
		// 扩展右键菜单
		menusEx : [{
			text : '辅导周报',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.role == "导师"&&row.isWeekly!="否") {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row.status == 4) {
					showModalWin("?model=hr_tutor_weekly&role=其他&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				} else {

					showModalWin("?model=hr_tutor_weekly&role=导师&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")

				}
			}
		}, {
			text : '提交周报',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.role == "学员"&&row.isWeekly!="否") {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row.status == 4) {
					showModalWin("?model=hr_tutor_weekly&role=其他&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				} else {

					showModalWin("?model=hr_tutor_weekly&role=学员&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")

				}
			}
		}, {
			text : '制定辅导计划',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.role == "导师"&&row.isCoachplan!="否"&&row.isAddPlan==0) {
					return true;
				}
				return false;
			},
			action : function(row) {
					showModalWin("?model=hr_tutor_coachplan&action=toAdd&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
		},{
			text : '辅导计划',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.role == "导师"&&row.isCoachplan!="否"&&row.isAddPlan==1) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row.status == 4) {
					showModalWin("?model=hr_tutor_coachplan&action=toView&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				}else{
					showModalWin("?model=hr_tutor_coachplan&action=toCoachplan&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				}
			}
		},  {
			text : '辅导计划',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.role == "学员"&&row.isCoachplan!="否") {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row.status == 4) {
					showModalWin("?model=hr_tutor_coachplan&action=toView&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				} else {

					showModalWin("?model=hr_tutor_coachplan&action=toStudentView&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				}
			}
		}, {
			text : '导师考核评分',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '3'&&((row.role == "导师"&&row.tutorScore=='0')||(row.role == "学员"&&row.staffScore=='0'))) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					if (row.role == "导师") {
						var roleh = "tut";
					} else {
						var roleh = "stu";
					}
					showModalWin("?model=hr_tutor_scheme&action=toScore&tutorId="
							+ row.id + "&role=" + roleh);
				}
			}
		}],
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'role',
					display : '角色',
					sortable : false,
					width:60,
					process : function(v, row) {
						return "<span style='color:#FF5151'>" + v + "</span>";
					}
				}, {
					name : 'status',
					display : '当前状态',
					width:60,
					sortable : true,
					process : function(v, row) {
						switch (v) {
							case '1' :
								return "辅导期";
								break;
							case '2' :
								return "员工考核";
								break;
							case '3' :
								return "导师考核";
								break;
							case '4' :
								return "完成";
								break;
						}
					}
				}, {
					name : 'userNo',
					display : '导师员工编号',
					width:80,
					sortable : true
				},  {
					name : 'userName',
					display : '导师姓名',
					sortable : true,
					width:60,
					process : function(v, row) {
						return "<a href='#' onclick='showThickboxWin(\"?model=hr_tutor_tutorrecords&action=toView&id="
								+ row.id
								+ '&skey='
								+ row.skey_
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>"
								+ v + "</a>";
					}
				}, {
					name : 'jobName',
					display : '导师职位',
					width:80,
					sortable : true
				}, {
					name : 'deptName',
					display : '导师部门',
					sortable : true,
					width:80
				}, {
					name : 'studentNo',
					display : '学员员工编号',
					sortable : true,
					hide : true
				}, {
					name : 'studentName',
					display : '学员姓名',
					width:60,
					sortable : true
				}, {
					name : 'studentDeptName',
					display : '学员部门',
					width:80,
					sortable : true
				}, {
					name : 'beginDate',
					display : '教学开始时间',
					width:80,
					sortable : true
				}, {
					name : 'assessmentScore',
					display : '考核分数',
					width:60,
					sortable : true,
					process : function(v, row) {
						if (row.isPublish == 1&&row.role != "学员") {
							return v;
						};
					}
				}, {
					name : 'rewardPrice',
					display : '奖 励(元)',
					sortable : true,
					width:60,
					process : function(v, row) {
						if (row.role != "学员"&& row.isPublish == 1) {
							return v;
						};
					}
				},{
					name : 'isCoachplan',
					display : '是否需要制定辅导计划',
					sortable : true,
					width : 130
				},{
					name : 'isWeekly',
					display : '是否需要按照HR的模式提交周报',
					sortable : true,
					width : 150
				}, {
					name : 'remark',
					display : '备注',
					sortable : true,
					width : 130,
					hide : true
				}],
		lockCol:['role','status','userName'],//锁定的列名
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
		comboEx : [{
					text : '状态',
					key : 'status',
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
							}]
				}],
		// buttonsEx : buttonsArr,
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		/**
		 * 快速搜索
		 */
		searchitems : [{
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
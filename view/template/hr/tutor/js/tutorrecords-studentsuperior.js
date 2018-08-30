var show_page = function(page) {
	$("#tutorrecordsGrid").yxgrid("reload");
};
$(function() {
	// 表头按钮数组
	buttonsArr = [];
	$("#tutorrecordsGrid").yxgrid({
		model : 'hr_tutor_tutorrecords',
		param : {
			'studentSuperiorId' : $('#userId').val()
		},
		title : '导师经历信息表',
		isAddAction : false,
		isOpButton : false,
		bodyAlign:'center',
		customCode : 'hr_tutor_studentlist',
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'userNo',
					display : '导师员工编号',
					width:'80',
					sortable : true
				},  {
					name : 'userName',
					display : '导师姓名',
					width:'60',
					sortable : true,
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
					width:'80',
					sortable : true
				},  {
					name : 'deptName',
					display : '导师部门',
					width:'80',
					sortable : true,
					hide : true
				}, {
					name : 'studentNo',
					display : '学员员工编号',
					width:'80',
					sortable : true,
					hide : true
				},  {
					name : 'studentName',
					display : '学员姓名',
					width:'60',
					sortable : true
				}, {
					name : 'studentDeptName',
					display : '学员部门',
					width:'80',
					sortable : true
				}, {
					name : 'status',
					display : '当前状态',
					width:'80',
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
					name : 'beginDate',
					display : '教学开始时间',
					width:'80',
					sortable : true
				}, {
					name : 'assessmentScore',
					display : '考核分数',
					width:'60',
					sortable : true,
					process : function(v, row) {
						if (row.isPublish == '1') {
							return v;
						}
					}
				}, {
					name : 'rewardPrice',
					display : '奖 励(元)',
					width:'60',
					sortable : true,
					process : function(v, row) {
						if (row.isPublish == 1) {
							return v;
						};
					}
				}, {
					name : 'isCoachplan',
					display : '是否需要制定辅导计划',
					sortable : true,
					width : 130
				},{
					name : 'isWeekly',
					display : '是否需要按照HR的模式提交周报',
					sortable : true,
					width : 180
				}],
		lockCol:['userNo','userName','jobName'],//锁定的列名
		// 扩展右键菜单
		menusEx : [{
			text : '周报',
			icon : 'view',
			// showMenuFn : function(row) {
			// if (row.status == '1' || row.status == '0') {
			// return true;
			// }
			// return false;
			// },
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
			// showMenuFn : function(row) {
			// if (row.status == '1' || row.status == '0') {
			// return true;
			// }
			// return false;
			// },
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
			text : '导师考核评分',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '3'&&row.supScore==0) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showModalWin("?model=hr_tutor_scheme&action=toScore&tutorId="
							+ row.id + "&role=sup");
				}
			}
		}, {
			text : '导师考核表',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.status == 1 || row.status == 4) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				showModalWin("?model=hr_tutor_scheme&action=toView&id="
						+ row.id);
			}
		}],
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
		// buttonsEx : buttonsArr,
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		/**
		 * 快速搜索
		 */
		searchitems : [{
					display : "员工编号",
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
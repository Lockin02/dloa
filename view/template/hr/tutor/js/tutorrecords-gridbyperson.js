var show_page = function(page) {
	$("#tutorrecordsGrid").yxgrid("reload");
};
$(function() {
	//表头按钮数组
	buttonsArr = [
        {
			name : 'view',
			text : "高级查询",
			icon : 'view',
			action : function() {
				alert('功能暂未开发完成');
//				showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
//					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
			}
        }
    ];

	//表头按钮数组
	excelOutArr = {
		name : 'exportIn',
		text : "导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_tutor_tutorrecords&action=toExcelIn"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
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
			}
		}
	});

	$("#tutorrecordsGrid").yxgrid({
		model : 'hr_tutor_tutorrecords',
		param : {
//			'userAccount' : $('#userAccount').val()
			'userNo2' : $('#userNo').val()
		},
		title : '导师经历信息表',
		isAddAction : false,
		isOpButton : false,
		bodyAlign:'center',
		//列信息
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
			}, {
				name : 'userName',
				display : '导师姓名',
				width:80,
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_tutor_tutorrecords&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			},  {
				name : 'jobName',
				display : '导师职位',
				sortable : true
			}, {
				name : 'deptName',
				display : '导师部门',
				sortable : true,
				hide : true
			}, {
				name : 'studentNo',
				display : '学员员工编号',
				sortable : true,
				hide : true
			}, {
				name : 'studentAccount',
				display : '学员员工账号',
				sortable : true,
				hide : true
			}, {
				name : 'studentName',
				display : '学员姓名',
				sortable : true
			}, {
				name : 'studentDeptName',
				display : '学员部门',
				sortable : true
			}, {
				name : 'role',
				display : '角色',
				sortable : false
			}, {
				name : 'status',
				display : '当前状态',
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
				display : '教学开始日期',
				sortable : true
			}, {
				name : 'assessmentScore',
				display : '考核分数',
				sortable : true,
				process : function(v, row) {
					if (row.role == "导师"&&row.isPublish == 1) {
						return v;
					}
				}
			},{
				name : 'rewardPrice',
				display : '奖励',
				sortable : true,
				process : function(v, row) {
					if (row.role == "导师"&&row.isPublish == 1) {
						return v;
					}
				}
			}],

		menusEx : [{
			text : '周报',
			icon : 'view',
			action : function(row) {
					showThickboxWin("?model=hr_tutor_weekly&role=其他&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800")
				}
		}, {
			text : '员工辅导计划',
			icon : 'view',
			action : function(row) {
					showOpenWin("?model=hr_tutor_coachplan&action=toView&tutorId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
				}
		}, {
			text : '导师考核表',
			icon : 'view',
			action : function(row, rows, grid) {
				showModalWin("?model=hr_tutor_scheme&action=toTutorAssessView&id="
						+ row.id);
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
//		buttonsEx : buttonsArr,
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
		},{
			display : "导师姓名",
			name : 'userNameM'
		},{
			display : "学员姓名",
			name : 'studentNameM'
		},{
			display : "学员部门",
			name : 'studentDeptNameM'
		}]
	});
});
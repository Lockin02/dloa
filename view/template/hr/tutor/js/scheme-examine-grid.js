var show_page = function(page) {
	$("#schemeGrid").yxgrid("reload");
};
$(function() {
	$("#schemeGrid").yxgrid({
		model : 'hr_tutor_scheme',
		title : '导师考核表',
		showcheckbox:false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		bodyAlign:'center',
		customCode : 'hr_tutor_schemelist',
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'userNo',
					display : '导师编号',
					width : '60',
					sortable : true
				}, {
					name : 'userName',
					display : '导师姓名',
					width : '60',
					sortable : true
				}, {
					name : 'jobName',
					display : '导师职位',
					width : '85',
					sortable : true
				}, {
					name : 'deptName',
					display : '导师部门',
					width : '80',
					sortable : true
				}, {
					name : 'studentNo',
					display : '学员编号',
					hide : true,
					sortable : true
				}, {
					name : 'studentName',
					display : '学员姓名',
					width : '60',
					sortable : true
				}, {
					name : 'studentDeptName',
					display : '学员部门',
					width : '80',
					sortable : true
				},
				{
					name : 'status',
					display : '当前状态',
					width : '80',
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
				},
				{
					name : 'assessmentScore',
					display : '考核分数',
					width : '60',
					sortable : true,
					process : function(v,row) {
						if (v > 0 && v != null) {
							return v;
						} else {
							return '';
						}
					}
				}, {
					name : 'tryBeginDate',
					display : '入职日期',
					width : '80',
					sortable : true
				}, {
					name : 'tryEndDate',
					display : '转正日期',
					width : '80',
					sortable : true
				}, {
					name : 'tutorScore',
					display : '导师自评',
					sortable : false,
					width : '50'
				}, {
					name : 'supScore',
					display : '上级评分',
					sortable : false,
					width : '50'
				}, {
					name : 'hrScore',
					display : 'HR评分',
					sortable : false,
					width : '50'
				}, {
					name : 'assistantScore',
					display : '部门助理评分',
					sortable : false,
					width : '50'
				}, {
					name : 'staffScore',
					display : '学员评分',
					sortable : false,
					width : '50'
				}],
		lockCol:['userNo','userName','jobName'],//锁定的列名
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=hr_tutor_scheme&action=toView&id="
							+ row.id);
				}
			}
		}, {
			text : '编辑考核分数',
			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row.status == 4 || row.status == 5) {
//					return true;
//				} else {
//					return false;
//				}
//			},
			action : function(row) {
				if (row) {
					showThickboxWin("?model=hr_tutor_tutorrecords&action=toEditScore&id="
							+ row.tutorId
							+ "&role=HR&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}
		// , {
		// text : '评分',
		// icon : 'edit',
		// action : function(row) {
		// if (row) {
		// showModalWin("?model=hr_tutor_scheme&action=toScore&id="
		// + row.id);
		// }
		// }
		// }
		],
		// 主从表格设置
		subGridOptions : {
			url : '?model=hr_tutor_schemeinfo&action=pageItemJson',
			// param : [{
			// paramId : 'mainId',
			// colId : 'id'
			// }],
			colModel : [{
						name : 'id',
						display : '从表字段'
					}]
		},

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "导师编号",
					name : 'userNo'
				}, {
					display : "导师姓名",
					name : 'userName'
				}, {
					display : "导师职位",
					name : 'jobName'
				}, {
					display : "导师部门",
					name : 'deptName'
				}, {
					display : "学员姓名",
					name : 'studentName'
				}, {
					display : "学员部门",
					name : 'studentDeptName'
				}]
	});
});
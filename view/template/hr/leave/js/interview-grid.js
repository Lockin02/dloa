var show_page = function(page) {
	$("#interviewGrid").yxgrid("reload");
};

$(function() {
	$("#interviewGrid").yxgrid({
		model : 'hr_leave_interview',
		title : '离职--面谈记录表',
		showcheckbox : false,
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		isOpButton:false,
		isViewAction : false,
		bodyAlign:'center',
		event : {
			'row_dblclick' : function(e, row, data) {
				showModalWin("?model=hr_leave_interview&action=toView&id=" + data.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900"
					);
			}
		},

		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
               showModalWin('?model=hr_leave_interview&action=toView&id='
							+ row.id );
			}

//		},{
//			text : '编辑',
//			icon : 'view',
//			action : function(row) {
//				showModalWin('?model=hr_leave_interview&action=toEdit&id=' + row.id );
//			}
//
		},{
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成') {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_leave_interview&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								$("#interviewGrid").yxgrid("reload");
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
			name : 'userNo',
			display : '员工编号',
			sortable : true,
			width:80
		},{
			name : 'userName',
			display : '员工姓名',
			sortable : true,
			width:70
		},{
			name : 'deptName',
			display : '部门名称',
			sortable : true,
			width:80
		},{
			name : 'jobName',
			display : '职位',
			sortable : true,
			width:80
		},{
			name : 'entryDate',
			display : '入职日期',
			sortable : true,
			width:80
		},{
			name : 'quitDate',
			display : '离职日期',
			sortable : true,
			width:80
		},{
			name : 'interviewer',
			display : '面谈者',
			sortable : true,
			width:200
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},

		searchitems : [{
			display : "员工编号",
			name : 'userNoSearch'
		},{
			display : "员工姓名",
			name : 'userNameSearch'
		},{
			display : "部门名称",
			name : 'deptNameSearch'
		},{
			display : "职位",
			name : 'jobNameSearch'
		},{
			display : "入职日期",
			name : 'entryDate'
		},{
			display : "离职日期",
			name : 'quitDate'
		},{
			display : "面谈者",
			name : 'interviewer'
		}]
	});
});
var show_page = function(page) {
	$("#interviewGrid").yxgrid("reload");
};
$(function() {
	$("#interviewGrid").yxgrid({
		model : 'hr_leave_interview',
		param : {
			'interviewerIdSearch' : $("#userId").val()
		},
		title : '离职--面谈记录表',
		showcheckbox : false,
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		isViewAction : false,
		isOpButton : false,
		event : {
			'row_dblclick' : function(e, row, data) {
				showModalWin("?model=hr_leave_interview&action=toView&id="
						+ data.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
		},
		// 扩展右键菜单

		menusEx : [{
			text : '填写面谈记录',
			icon : 'add',
			action : function(row) {
				showModalWin('?model=hr_leave_interview&action=toWrite&id='
						+ row.id);
			}

		}, {
			text : '查看',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=hr_leave_interview&action=toPersonView&id='
						+ row.id);
			}

		}],
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'userNo',
					display : '员工编号',
					sortable : true
				}, {
					name : 'userName',
					display : '员工姓名',
					sortable : true
				}, {
					name : 'deptName',
					display : '部门名称',
					sortable : true
				}, {
					name : 'entryDate',
					display : '入职日期',
					sortable : true
				}, {
					name : 'jobName',
					display : '职位',
					sortable : true
				}, {
					name : 'quitDate',
					display : '离职日期',
					sortable : true
				}, {
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
				}]
	});
});
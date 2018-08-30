var show_page = function(page) {
	$("#interviewCommentGrid").yxgrid("reload");
};
$(function() {
	$("#interviewCommentGrid").yxgrid({
		model : 'hr_recruitment_interviewComment',
		title : '面试评价',
		//列信息
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'parentCode',
			display : '源单编号',
			sortable : true
		}, {
			name : 'resumeCode',
			display : '简历编号',
			sortable : true
		}, {
			name : 'applicantName',
			display : '应聘者姓名',
			sortable : true
		}, {
			name : 'invitationCode',
			display : '面试通知编号',
			sortable : true
		}, {
			name : 'interviewType',
			display : '面试类型',
			sortable : true
		}, {
			name : 'userAccount',
			display : '员工账号',
			sortable : true
		}, {
			name : 'userName',
			display : '姓名',
			sortable : true
		}, {
			name : 'sexy',
			display : '性别',
			sortable : true
		}, {
			name : 'positionsName',
			display : '应聘岗位',
			sortable : true
		}, {
			name : 'deptName',
			display : '用人部门',
			sortable : true
		}, {
			name : 'projectGroup',
			display : '所在项目组',
			sortable : true
		}, {
			name : 'useWriteEva',
			display : '用人-笔试评价',
			sortable : true
		}, {
			name : 'interviewEva',
			display : '用人-面试评价',
			sortable : true
		}, {
			name : 'interviewer',
			display : '面试官',
			sortable : true
		}, {
			name : 'interviewDate',
			display : '面试日期',
			sortable : true
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "姓名",
			name : 'userName'
		},{
			display : "应聘岗位",
			name : 'positionsName'
		},{
			display : "用人部门",
			name : 'deptName'
		},{
			display : "面试官",
			name : 'interviewer'
		}]
	});
}); 
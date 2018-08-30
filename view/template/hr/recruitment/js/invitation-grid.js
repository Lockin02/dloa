var show_page = function(page) {
	$("#invitationGrid").yxgrid("reload");
};

$(function() {
	$("#invitationGrid").yxgrid({
		model : 'hr_recruitment_invitation',
		title : '面试通知',
		isDelAction : false,
		isEditAction : false,
		showcheckbox : false,

		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '单据编号',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_invitation&action=toView&id=" + row.id +"\",1)'>" + v + "</a>";
			}
		},{
			name : 'parentCode',
			display : '源单编号',
			sortable : true
		},{
			name : 'applicantName',
			display : '应聘者姓名',
			sortable : true
		},{
			name : 'sex',
			display : '性别',
			sortable : true,
			width : 30
		},{
			name : 'resumeCode',
			display : '简历编号',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_resume&action=toRead&code=" + v +"\",1)'>" + v + "</a>";
			}
		},{
			name : 'phone',
			display : '联系电话',
			sortable : true
		},{
			name : 'email',
			display : '电子邮箱',
			sortable : true
		},{
			name : 'positionsName',
			display : '应聘岗位',
			sortable : true
		},{
			name : 'deptName',
			display : '用人部门',
			sortable : true
		},{
			name : 'interviewDate',
			display : '面试时间',
			sortable : true
		},{
			name : 'interviewPlace',
			display : '面试地点',
			sortable : true
		},{
			name : 'stateC',
			display : '状态',
			sortable : true
		},{
			name : 'interviewerName',
			display : '部门面试官',
			sortable : true
		},{
			name : 'hrInterviewer',
			display : '人力面试官',
			sortable : true
		},{
			name : 'userWrite',
			display : '笔试评价',
			sortable : true
		},{
			name : 'interview',
			display : '面试评价',
			sortable : true
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},

		searchitems : [{
			display : "单据编号",
			name : 'formCode'
		},{
			display : "源单编号",
			name : 'parentCode'
		},{
			display : "应聘者姓名",
			name : 'applicantName'
		},{
			display : "性别",
			name : 'sex'
		},{
			display : "简历编号",
			name : 'resumeCode'
		},{
			display : "联系电话",
			name : 'phone'
		},{
			display : "电子邮箱",
			name : 'email'
		},{
			display : "应聘岗位",
			name : 'positionsName'
		},{
			display : "用人部门",
			name : 'deptName'
		},{
			display : "面试时间",
			name : 'interviewDateSea'
		},{
			display : "面试地点",
			name : 'interviewPlace'
		},{
			display : "部门面试官",
			name : 'interviewerName'
		},{
			display : "人力面试官",
			name : 'hrInterviewer'
		}]
	});
});
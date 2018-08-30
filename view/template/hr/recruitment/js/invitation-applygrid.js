var show_page = function(page) {
	$("#invitationGrid").yxgrid("reload");
};
$(function() {
	$("#invitationGrid").yxgrid({
		model : 'hr_recruitment_invitation',
		title : '面试通知',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isOpButton:false,
		bodyAlign:'center',
		param : {
			"interviewType":$("#interviewType").val(),
			parentId : $("#applyid").val()
		},
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'formCode',
			display : '单据编号',
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_invitation&action=toView&id=" + row.id +"\",1)'>" + v + "</a>";
				}
		}, {
			name : 'parentCode',
			display : '源单编号',
			width:130,
			sortable : true
		}, {
			name : 'applicantName',
			display : '应聘者姓名',
			width:70,
			sortable : true
		}, {
			name : 'sex',
			display : '性别',
			width:60,
			sortable : true
		}, {
			name : 'resumeCode',
			display : '简历编号',
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_resume&action=toRead&code=" + v +"\",1)'>" + v + "</a>";
				}
		}, {
			name : 'phone',
			display : '联系电话',
			sortable : true
		}, {
			name : 'email',
			display : '电子邮箱',
			width:150,
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
			name : 'interviewDate',
			display : '面试时间',
			width:70,
			sortable : true
		}, {
			name : 'interviewPlace',
			display : '面试地点',
			width:70,
			sortable : true
		}, {
			name : 'stateC',
			display : '状态',
			width:70
		},{
			name : 'interviewerName',
			display : '面试官',
			sortable : true
		}],
		lockCol:['formCode','parentCode','applicantName'],//锁定的列名
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "姓名",
			name : 'applicantName'
		},{
			display : "应聘职位",
			name : 'positionsName'
		},{
			display : "用人部门",
			name : 'deptName'
		}]
	});
});
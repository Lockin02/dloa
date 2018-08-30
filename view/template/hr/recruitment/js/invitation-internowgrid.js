var show_page = function(page) {
	$("#invitationGrid").yxgrid("reload");
};

$(function() {
	$("#invitationGrid").yxgrid({
		model : 'hr_recruitment_invitation',
		title : '面试通知',
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		showcheckbox : false,
		isOpButton:false,
		bodyAlign:'center',
		param : {
			interviewDate : "1",//实际上没有作用，但要用到searchArr
			linkid : $("#linkid").val(),
			state : 1
		},

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
				return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_invitation&action=toView&id=" + row.id +"&skey="+row['skey_']+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800" +"\",1)'>" + v + "</a>";
			}
		},{
			name : 'parentCode',
			display : '源单编号',
			width:130,
			sortable : true
		},{
			name : 'applicantName',
			display : '应聘者姓名',
			width:70,
			sortable : true
		},{
			name : 'sex',
			display : '性别',
			sortable : true,
			width : 50
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
			width:120,
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
			width:70,
			sortable : true
		},{
			name : 'interviewPlace',
			display : '面试地点',
			width:70,
			sortable : true
		},{
			name : 'stateC',
			display : '状态',
			width:60,
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

		lockCol:['formCode','parentCode','applicantName'],//锁定的列名

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},

		menusEx : [{
			text : '添加面试评价',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.sign == 0) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showOpenWin("?model=hr_recruitment_interviewComment&action=toAdd&setid=" + row.id,'1');
			}
		},{
			text : '编辑面试评价',
			icon : 'edit',
			showMenuFn : function(row) {
				if(row.sign==1){
					return true;
				}else{
					return false;
				}
			},
			action : function(row) {
				showOpenWin("?model=hr_recruitment_interviewComment&action=toEdit&id=" + row.commentid,'1');
			}
		}],

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
var show_page = function(page) {
	$("#investigationGrid").yxgrid("reload");
};

$(function() {
	$("#investigationGrid").yxgrid({
		model : 'hr_recruitment_investigation',
		title : '背景调查记录表',
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		isViewAction : false,
		param : {
			InvestigationManId : $("#userAccount").val(),
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
			process : function (v, row) {
				return "<a href='#' onclick='location=\"?model=hr_recruitment_investigation&action=toView&id=" + row.id +"\"'>" + v + "</a>";
			}
		},{
			name : 'formDate',
			display : '单据日期',
			sortable : true
		},{
			name : 'state',
			display : '状态',
			hide : true,
			sortable : true
		},{
			name : 'ExaStatus',
			display : '审核状态',
			hide : true,
			sortable : true
		},{
			name : 'interviewType',
			display : '面试类型',
			hide : true,
			sortable : true
		},{
			name : 'userName',
			display : '姓名',
			sortable : true
		},{
			name : 'sex',
			display : '性别',
			width : 40,
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
			name : 'consultationName',
			display : '咨询人名称',
			sortable : true
		},{
			name : 'consultationCompanyName',
			display : '咨询人公司名称',
			sortable : true
		},{
			name : 'consultationPostiton',
			display : '咨询人职位',
			sortable : true
		},{
			name : 'consultationTel',
			display : '咨询人电话',
			sortable : true
		},{
			name : 'consultationEmail',
			display : '咨询人邮箱',
			sortable : true
		},{
			name : 'workBeginDate',
			display : '工作开始时间',
			sortable : true
		},{
			name : 'workEndDate',
			display : '工作结束时间',
			sortable : true
		},{
			name : 'userCompany',
			display : '候选人公司名称',
			sortable : true
		},{
			name : 'userPosition',
			display : '候选人职位名称',
			sortable : true
		},{
			name : 'relationshipName',
			display : '与咨询人关系',
			sortable : true
		},{
			name : 'InvestigationMan',
			display : '调查人',
			sortable : true
		},{
			name : 'InvestigationDate',
			display : '调查时间',
			sortable : true
		},{
			name : 'ExaDT',
			display : '审核日期',
			hide : true,
			sortable : true
		}],

		lockCol:['formCode','formDate','userName'],//锁定的列名

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},

		menusEx:[{
			text:'查看',
			icon:'view',
			action:function(row ,rows ,grid) {
				if(row) {
					location = "?model=hr_recruitment_investigation&action=toView&id=" + row.id;
				}
			}
		}],

		buttonsEx : [{
			text : '新增',
			icon : 'add',
			action : function (row) {
				location = "?model=hr_recruitment_investigation&action=toAdd";
			}
		},{
			text : '删除',
			icon : 'delete',
			action : function (row) {
				if(row) {
					if(window.confirm("确认要删除?")) {
						$.ajax({
							type : "POST",
							url : "?model=hr_recruitment_investigation&action=ajaxdeletes",
							data : {
								id:row.id
							},
							success:function(msg) {
								if(msg == 1) {
									alert('删除成功!');
									show_page();
								} else {
									alert('删除失败!');
									show_page();
								}
							}
						});
					}
				}
			}
		}],

		searchitems : [{
			display : "单据编号",
			name : 'formCode_d'
		},{
			display : "单据日期",
			name : 'formDate'
		},{
			display : "姓名",
			name : 'userName'
		},{
			display : "性别",
			name : 'sex'
		},{
			display : "应聘岗位",
			name : 'positionsName'
		},{
			display : "用人部门",
			name : 'deptName'
		},{
			display : "咨询人姓名",
			name : 'consultationName'
		},{
			display : "咨询人公司名称",
			name : 'consultationCompanyName'
		},{
			display : "咨询人职位",
			name : 'consultationPostiton'
		},{
			display : "咨询人电话",
			name : 'consultationTel'
		},{
			display : "咨询人邮箱",
			name : 'consultationEmail'
		},{
			display : "工作开始时间",
			name : 'workBeginDate'
		},{
			display : "工作结束时间",
			name : 'workEndDate'
		},{
			display : "候选人公司名称",
			name : 'userCompany'
		},{
			display : "候选人职位名称",
			name : 'userPosition'
		},{
			display : "与咨询人关系",
			name : 'relationshipName'
		},{
			display : "调查人",
			name : 'InvestigationMan'
		},{
			display : "调查时间",
			name : 'InvestigationDate'
		}]
	});
 });
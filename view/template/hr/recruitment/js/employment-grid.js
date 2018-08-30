var show_page = function(page) {
	$("#employmentGrid").yxgrid("reload");
};
$(function() {
	$("#employmentGrid").yxgrid({
		model : 'hr_recruitment_employment',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : true,
		isOpButton:false,
		bodyAlign:'center',
		title : '职位申请表',

		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=hr_recruitment_employment&action=toView&id='
					+ row.id + "&skey=" + row['skey_'],'1');
			}
		},{
			text : '编辑',
			icon : 'edit',
			action : function(row) {
				showModalWin('?model=hr_recruitment_employment&action=toEdit&id='
					+ row.id + "&skey=" + row['skey_'],'1');
			}
		},{
			text : '删除',
			icon : 'delete',
			action : function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_employment&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								$("#employmentGrid").yxgrid("reload");
							}else{
								alert('删除失败，存在关联数据！');
							}
						}
					});
				}
			}
//		},{
//			text : '添加面试评估',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.resumeType == 2) {
//					return false;
//				}
//				return true;
//			},
//			action : function(row) {
//					showModalWin('?model=hr_recruitment_interview&action=toAddByEmployment&employmentId='
//							+ row.id + "&skey=" + row['skey_']);
//			}
		}],

		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'employmentCode',
			display : '编号',
			sortable : true,
			width : 130,
			process : function(v, row) {
				return '<a href="javascript:void(0)" title="点击查看" onclick="javascript:showModalWin(\'?model=hr_recruitment_employment&action=toView&id='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
					+ "<font color = '#4169E1'>"
					+ v
					+ "</font>"
					+ '</a>';
			}
		},{
			name : 'name',
			display : '姓名',
			width : 60,
			sortable : true
		},{
			name : 'sex',
			display : '性别',
			width : 60,
			sortable : true
		},{
			name : 'nation',
			display : '民族',
			width : 60,
			sortable : true
		},{
			name : 'age',
			display : '年龄',
			width : 60,
			sortable : true
		},{
			name : 'highEducationName',
			display : '学历',
			width : 80,
			sortable : true
		},{
			name : 'highSchool',
			display : '毕业学校',
			sortable : true
		},{
			name : 'professionalName',
			display : '专业',
			sortable : true
		},{
			name : 'telephone',
			display : '固定电话',
			sortable : true
		},{
			name : 'mobile',
			display : '移动电话',
			sortable : true
		},{
			name : 'personEmail',
			display : '个人电子邮箱',
			sortable : true
		},{
			name : 'QQ',
			display : 'QQ',
			sortable : true
		}],

		lockCol:['employmentCode','name'],//锁定的列名

		toAddConfig : {
			formHeight : 500,
			formWidth : 900,
			toAddFn : function(p,g) {
				showModalWin("?model=hr_recruitment_employment&action=toAdd",'1');
			}
		},

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},

		searchitems : [{
			display : "编号",
			name : 'employmentCode'
		},{
			display : "姓名",
			name : 'name'
		},{
			display : "性别",
			name : 'sex'
		},{
			display : "民族",
			name : 'nation'
		},{
			display : "年龄",
			name : 'age'
		},{
			display : "学历",
			name : 'highEducationName'
		},{
			display : "毕业学校",
			name : 'highSchool'
		},{
			display : "专业",
			name : 'professionalName'
		},{
			display : "固定电话",
			name : 'telephone'
		},{
			display : "移动电话",
			name : 'mobile'
		},{
			display : "个人电子邮箱",
			name : 'personEmail'
		},{
			display : "QQ",
			name : 'QQ'
		}]
	});
});
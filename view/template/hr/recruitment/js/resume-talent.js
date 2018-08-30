var show_page = function(page) {
	$("#talentGrid").yxgrid("reload");
};
$(function() {
	//表头按钮数组
	buttonsArr = [];
	$("#talentGrid").yxgrid({
		model : 'hr_recruitment_resume',
		title : '人才库',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'resumeGrid',
		// 扩展右键菜单

		menusEx : [{
			text : '查看简历',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=hr_recruitment_resume&action=toView&id='
						+ row.id + "&skey=" + row['skey_'],'1');
			}
		}],
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'resumeCode',
			display : '简历编号',
			sortable : true,
			process : function(v, row) {
				return '<a href="javascript:void(0)" title="点击查看简历" onclick="javascript:showModalWin(\'?model=hr_recruitment_resume&action=toView&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'applicantName',
			display : '应聘者姓名',
			sortable : true
		}, {
			name : 'isInform',
			display : '面试通知',
			sortable : true
		}, {
			name : 'post',
			display : '应聘职位',
			sortable : true,
			datacode : 'YPZW'
		}, {
			name : 'phone',
			display : '联系电话',
			sortable : true
		}, {
			name : 'email',
			display : '电子邮箱',
			sortable : true,
			width : 200
		}],
		comboEx : [{
			text : '简历类型',
			key : 'resumeType',
			data : [{
				text : '公司简历',
				value : '0'
			}, {
				text : '员工简历',
				value : '1'
			}, {
				text : '黑名单',
				value : '2'
			}, {
				text : '储备简历',
				value : '3'
			}, {
				text : '淘汰简历',
				value : '4'
			}, {
				text : '在职简历',
				value : '5'
			}, {
				text : '离职简历',
				value : '6'
			}]
		}],
		buttonsEx : buttonsArr,

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "简历编号",
			name : 'resumeCode'
		},{
			display : "应聘者名称",
			name : 'applicantName'
		}]
	});
});
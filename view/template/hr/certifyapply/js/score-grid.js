var show_page = function(page) {
	$("#scoreGrid").yxgrid("reload");
};
$(function() {
	$("#scoreGrid").yxgrid({
		model : 'hr_certifyapply_score',
		title : '��ְ�ʸ���ί���',
		isAddAction : false,
		isDelAction : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userName',
				display : '����Ա��',
				sortable : true
			}, {
				name : 'userAccount',
				display : '����Ա���ʺ�',
				sortable : true,
				hide : true
			}, {
				name : 'managerName',
				display : '������',
				sortable : true
			}, {
				name : 'managerId',
				display : '������Աid',
				sortable : true,
				hide : true
			}, {
				name : 'assessDate',
				display : '��������',
				sortable : true
			}, {
				name : 'score',
				display : '��Ȩ�÷�',
				sortable : true
			}, {
				name : 'createId',
				display : '������Id',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '������',
				sortable : true
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true,
				width : 130
			}, {
				name : 'updateId',
				display : '�޸���Id',
				sortable : true,
				hide : true
			}, {
				name : 'updateName',
				display : '�޸���',
				sortable : true
			}, {
				name : 'updateTime',
				display : '�޸�ʱ��',
				sortable : true,
				width : 130
			}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "������",
			name : 'managerNameSearch'
		}]
	});
});
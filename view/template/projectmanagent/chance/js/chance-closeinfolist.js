var show_page = function(page) {
	$("#closeinfoGrid").yxgrid("reload");
};
$(function() {
	buttonsArr = []
	$("#closeinfoGrid").yxgrid({
		model : 'projectmanagent_chance_close',
		title : '�����̻�',
		param : {'chanceId' : $("#chanceId").val()},
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'handleType',
			display : '��������',
			sortable : true
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			width : 150
		}, {
			name : 'createName',
			display : '������',
			sortable : true
		}, {
			name : 'closeInfo',
			display : '��ע��Ϣ',
			sortable : true,
			width : 200
		}],
		buttonsEx : buttonsArr,

		// ��������
		searchitems : [],
		// Ĭ������˳��
		sortorder : "DSC",
		// ��ʾ�鿴��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false
	});
});

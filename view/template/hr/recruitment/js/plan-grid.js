var show_page = function(page) {
	$("#planGrid").yxgrid("reload");
};

$(function() {

	buttonsArr = [{
		name : 'exportOut',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_recruitment_plan&action=toExcelIn"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800")
		}
	}];
	$("#planGrid").yxgrid({
				model : 'hr_recruitment_plan',
				title : '��Ƹ�ƻ�',
				isDelAction : false,
				isAddAction : false,
				isEditAction : false,
				isOpButton : false,
				// ����Ϣ
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'formCode',
							display : '���ݱ��',
							sortable : true
						}, {
							name : 'formManName',
							display : '�����',
							sortable : true
						}, {
							name : 'deptName',
							display : '������',
							sortable : true
						},{
							name : 'postTypeName',
							display : 'ְλ����',
							sortable : true
						},{
							name : 'positionName',
							display : '����ְλ',
							sortable : true
						}, {
							name : 'isEmergency',
							display : '�Ƿ����',
							sortable : true
						}, {
							name : 'hopeDate',
							display : 'ϣ������ʱ��',
							sortable : true
						}, {
							name : 'addType',
							display : '��Ա����',
							sortable : true
						}, {
							name : 'needNum',
							display : '��������',
							sortable : true
						}, {
							name : 'entryNum',
							display : '����ְ����',
							sortable : true
						}, {
							name : 'beEntryNum',
							display : '����ְ����',
							sortable : true
						}, {
							name : 'ExaStatus',
							display : '���״̬',
							sortable : true
						}],
				buttonsEx : buttonsArr,
				toViewConfig : {

				},
				searchitems : [{
							display : "���ݱ��",
							name : 'formCode'
						}, {
							display : "�����",
							name : 'formManName'
						}, {
							display : "������",
							name : 'deptName'
						}]
			});
});
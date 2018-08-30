var show_page = function(page) {
	$("#externalGrid").yxgrid("reload");
};
$(function() {
	var purchType = $('#purchType').val();
	var orderId = $('#orderId').val();
	$("#externalGrid").yxgrid({
		model : 'purchase_plan_basic',
		action : 'pagebyorder',
		title : '��ͬ�ɹ��б�',
		param : { 'isTemp':0,'purchType': purchType,'sourceID':orderId },
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=purchase_plan_basic&action=read&id='
						+ row.id
						+ '&skey='
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		}],
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'purchType',
			display : '�ɹ�����',
			width : 120,
			sortable : true,
			hide : true
		}, {
			name : 'planNumb',
			display : '�ɹ�������',
			width : 120,
			sortable : true
		}, {
			name : 'sourceNumb',
			display : 'Դ���ݺ�',
			width : 120,
			sortable : true
		}, {
			name : 'contractName',
			display : '��ͬ����',
			width : 90,
			sortable : true
		}, {
			name : 'sendTime',
			display : '��������',
			width : 180,
			sortable : true
		}, {
			name : 'dateHope',
			display : '�����������',
			width : 120,
			sortable : true
		}, {
			name : 'sendName',
			display : '����������',
			sortable : true,
			width : 60
		}, {
			name : 'department',
			display : '���벿��',
			sortable : true
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : 'Դ���ݺ�',
			name : 'sourceID'
		}, {
			display : '��ͬ����',
			name : 'contractName'
		}, {
			display : '�ɹ�������',
			name : 'planNumb'
		}]
	});
});
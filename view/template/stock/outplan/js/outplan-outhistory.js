var show_page = function(page) {
	$("#outplanGrid").yxgrid("reload");
};
$(function() {
	$("#outplanGrid").yxgrid({
		model : 'stock_outplan_outplan',
		title : '�����ƻ�',
		param : { "docId" : $("#docId").val() , "docType" : $("#docType").val() },
		showcheckbox :false,
		isAddAction : true,
		isEditAction : false,
		isViewAction : false,
		isAddAction : false,
		isDelAction : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'planCode',
			display : '�ƻ����',
			sortable : true
		}, {
			name : 'docId',
			display : '��ͬId',
			hide : true,
			sortable : true
		}, {
			name : 'docCode',
			display : '��ͬ��',
			sortable : true
		}, {
			name : 'docName',
			display : '��ͬ����',
			sortable : true
		}, {
			name : 'week',
			display : '�ܴ�',
			sortable : true
		}, {
			name : 'planIssuedDate',
			display : '�´�����',
			sortable : true
//		}, {
//			name : 'docType',
//			display : '��������',
//			datacode : 'FHJHLX',
//			sortable : true
		}, {
			name : 'stockName',
			display : '�����ֿ�',
			sortable : true
		}, {
			name : 'type',
			display : '����',
			datacode : 'FHXZ',
			sortable : true
		}, {
			name : 'purConcern',
			display : '�ɹ���Ա��ע�ص�',
			hide : true,
			sortable : true
		}, {
			name : 'shipConcern',
			display : '������Ա��ע',
			hide : true,
			sortable : true
		}, {
			name : 'issuedStatus',
			display : '�´�״̬',
			sortable : true
		}, {
			name : 'docStatus',
			display : '״̬',
			sortable : true
		}, {
			name : 'shipPlanDate',
			display : '�ƻ���������',
			sortable : true
		}, {
			name : 'isOnTime',
			display : '�Ƿ�ʱ����',
			sortable : true
		}, {
			name : 'delayType',
			display : '����ԭ�����',
			hide : true,
			sortable : true
		}, {
			name : 'delayReason',
			display : 'δ������ԭ��',
			hide : true,
			sortable : true
		}],

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=stock_outplan_outplan&action=toView&id='
						+ row.id
						+ '&docType='
						+ row.docType
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		}]
	});
});
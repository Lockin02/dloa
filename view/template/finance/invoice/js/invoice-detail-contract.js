var show_page = function(page) {
	$("#invoiceDetail").yxgrid("reload");
};
$(function() {

	$("#invoiceDetail").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		model : 'finance_invoice_invoice',
		action : 'contractPagejson',
		param : {'exObjCode' : $('#objCode').val(),'exObjType' : $('#objType').val()},
        /**
		 * �Ƿ���ʾ�鿴��ť/�˵�
		 */
		isViewAction : false,
		/**
		 * �Ƿ���ʾ�޸İ�ť/�˵�
		 */
		isEditAction : false,
		/**
		 * �Ƿ���ʾɾ����ť/�˵�
		 */
        isDelAction : false,
        //�Ƿ���ʾ��Ӱ�ť
        isAddAction : false,
        //�Ƿ���ʾ������
        isToolBar : false,
        //�Ƿ���ʾcheckbox
         showcheckbox : false,
		// ��չ�Ҽ��˵�
		menusEx : [
		{
			text : '�鿴��Ʊ����',
			icon : 'view',
			action : function(row){
				showThickboxWin('?model=finance_invoiceapply_invoiceapply&action=init'
				+ '&id=' + row.applyId
				+ '&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		},
		{
			text : '�鿴��Ʊ��¼',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=finance_invoice_invoice&action=init&perm=view&id='
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&width=900&height=500");
			}
		}],
		// ��
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			display : '��Ʊ��',
			name : 'invoiceNo',
			sortable : true,
			width : 100

		},{
			display : '��Ʊ����id',
			name : 'applyId',
			hide : true

		}, {
			display : '��Ʊ���뵥��',
			name : 'applyNo',
			sortable : true,

			width : 150
		}, {
			display : '��Ʊ����',
			name : 'invoiceType',
			sortable : true,
			width : 100,
			datacode : 'FPLX'
		}, {
			display : '�ܽ��',
			name : 'invoiceMoney',
			sortable : true,
			width : 100,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '������',
			name : 'softMoney',
			sortable : true,
			width : 100,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : 'Ӳ�����',
			name : 'hardMoney',
			sortable : true,
			width : 100,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '������',
			name : 'serviceMoney',
			sortable : true,
            width : 100,
			process : function(v){
				return moneyFormat2(v);
			}

		}, {
			display : 'ά�޽��',
			name : 'repairMoney',
			sortable : true,
			width : 100,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '�豸���޽��',
			name : 'equRentalMoney',
			sortable : true,
            width : 90,
			process : function(v,row){
				if(row.isRed == 0){
					return moneyFormat2(v);
				}else{
					return '-' + moneyFormat2(v);
				}
			}

		}, {
			display : '�������޽��',
			name : 'spaceRentalMoney',
			sortable : true,
			width : 90,
			process : function(v,row){
				if(row.isRed == 0){
					return moneyFormat2(v);
				}else{
					return '-' + moneyFormat2(v);
				}
			}
		}, {
			display : '��Ʊ��',
			name : 'createName',
			sortable : true,
			width : 100
		}, {
			display : '��Ʊ����',
			name : 'invoiceTime',
			sortable : true,
			width : 100
		}],

		/**
		 * ��������
		 */
		searchitems : [{
			display : '��Ʊ��',
			name : 'invoiceNo'
		}],
		sortorder : "ASC",
		title : '��Ʊ��¼'
	});
});
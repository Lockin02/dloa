var show_page = function(page) {
	$("#salesAudited").yxgrid("reload");
};
$(function() {

	$("#salesAudited").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ

		model : 'contract_sales_sales',
		action : 'auditedPageJson',
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

		menusEx : [{
			text : '��ͬ��Ϣ',
			icon : 'view',
			action: function(row){
                showOpenWin('?model=contract_sales_sales&action=readDetailedInfoNoedit&id='
						+ row.contractId);
			}
		},{
			text : '�������',
			icon : 'view',
			action : function(row) {
				showThickboxWin('controller/common/readview.php?itemtype=oa_contract_sales&pid='
		             +row.contractId
		             + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}],



		// ��
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			display : 'contractId',
			name : 'contractId',
			hide : true
		},{
			display : '������ͬ��',
			name : 'contNumber',
			sortable : true,
			width : 150
		}, {
			display : '��������',
			name : 'task',
			sortable : true,
			width : 80
		}, {
			display : '��ͬ����',
			name : 'contName',
			sortable : true,
			width : 160
		}, {
			display : '�ͻ�����',
			name : 'customerName',
			sortable : true,
			width : 130
		}, {
			display : '�ͻ�����',
			name : 'customerType',
			datacode : 'KHLX',
			sortable : true,
			width : 100
		}, {
			display : '�ͻ�����ʡ��',
			name : 'province',
			sortable : true,
			width : 80
		}, {
			display : '����������',
			name : 'principalName',
			sortable : true
		}, {
			display : '����״̬',
			name : 'ExaStatus',
			sortable : true,
			width : 80
		}],

		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ����',
			name : 'contName'
		}],
		sortorder : "DESC",
		title : '�����������ۺ�ͬ'
	});
});
var show_page = function(page) {
	$("#salesUnaudit").yxgrid("reload");
};
$(function() {

	$("#salesUnaudit").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ

		model : 'contract_sales_sales',
		action : 'unauditPageJson',
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
			text : '����',
			icon : 'edit',
			action: function(row){
                parent.location = 'controller/contract/sales/ewf_index.php?taskId='+
                	row.task +
                	'&spid=' +
                	row.id +
                	'&billId=' +
                	row.contractId + '&examCode=oa_contract_sales&actTo=ewfExam';
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
			display : '�ͻ���ͬ��',
			name : 'customerContNum',
			sortable : true,
			width : 100
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
			width : 90
		}, {
			display : '����������',
			name : 'principalName',
			sortable : true
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
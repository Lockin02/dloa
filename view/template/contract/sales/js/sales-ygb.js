var show_page = function(page) {
	$("#salesYGB").yxgrid("reload");
};
$(function() {

	$("#salesYGB").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ

        param : {"contStatus":"9"},
		model : 'contract_sales_sales',
		action : 'pageJsonClose',
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
			text : '��ͬ��Ϣ',
			icon : 'view',

			action: function(row){
                showOpenWin('?model=contract_sales_sales&action=infoTab&id='
						+ row.id
						+ '&contNumber='
						+ row.contNumber
						);
			}
		},{

			text : '�������',
			icon : 'view',
			action : function(row) {
				showThickboxWin('controller/contract/sales/readview.php?itemtype=oa_contract_sales&pid='
				             +row.id
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
			display : '������ͬ��',
			name : 'contNumber',
			sortable : true,
			width : 150
		}, {
			display : '��ͬ����',
			name : 'contName',
			sortable : true,
			width : 150
		}, {
			display : '�ͻ�����',
			name : 'customerName',
			sortable : true,
			width : 100
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
			width : 100
		}, {
			display : '����������',
			name : 'principalName',
			sortable : true,
			width : 150
		},{
			display : '�ر�����',
			name : 'excuteDate',
			sortable : true,
			width : 150
		},{
			display : '�ر�����',
			name : 'doType',
			sortable : true,
			datacode : 'GBYY'
		}],

		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ����',
			name : 'contName'
		}],
		sortorder : "DESC",
		title : '�ѹرյ����ۺ�ͬ'
	});
});
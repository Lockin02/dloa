var show_page = function(page) {
	$("#salesZBG").yxgrid("reload");
};
$(function() {

	$("#salesZBG").yxgrid({
		model : 'contract_sales_sales',
		action : 'zbgPageJson',
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
			text : '���ǰ�汾',
			icon : 'view',

			action: function(row){
                showThickboxWin('?model=contract_sales_sales&action=readBaseInfoNoedit&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		},{
			text : '����а汾',
			icon : 'view',

			action: function(row){
                showThickboxWin('?model=contract_change_change&action=showAction&id='
						+ row.changeId
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
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
				width : 120
			}, {
				display : '�ͻ���ͬ��',
				name : 'customerContNum',
				sortable : true
			}, {
				display : '�ͻ�����',
				name : 'customerType',
				datacode : 'KHLX',
				sortable : true
			}, {
				display : '�ͻ�����ʡ��',
				name : 'province',
				sortable : true,
				width : 80
			}, {
				display : '������뵥��',
				name : 'formNumber',
				sortable : true,
				width : 150
			}, {
				display : '����������',
				name : 'principalName',
				sortable : true
			}, {
				display : '�����������',
				name : 'applyTime',
				sortable : true
			},{
				display : '���뵥״̬',
				name : 'changeFormStatus',
				sortable : true,
				width : 100
			}],

		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ����',
			name : 'contName'
		}],
		sortorder : "DESC",
		title : '�ڱ�������ۺ�ͬ'
	});
});
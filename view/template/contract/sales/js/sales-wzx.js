var show_page = function(page) {
	$("#salesWZX").yxgrid("reload");
};
$(function() {
	// var proIdValue = parent.document.getElementById("proId").value;

	$("#salesWZX").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ

        param : {"contStatus":"0","exaStatus":"���"},

		model : 'contract_sales_sales',
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
                showOpenWin('?model=contract_sales_sales&action=readDetailedInfoNoedit&id='
						+ row.id );
			}



		},{
			text : '�������',
			icon : 'view',
			action : function(row) {
				showThickboxWin('controller/common/readview.php?itemtype=oa_contract_sales&pid='
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
		}],

		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ����',
			name : 'contName'
		}],
		sortorder : "DESC",
		title : 'δִ�е����ۺ�ͬ'
	});
});
var show_page = function(page) {
	$("#salesInfoGrid").yxgrid("reload");
};
$(function() {
	$("#salesInfoGrid").yxgrid({
		model : 'projectmanagent_order_order',
		title : '��ǩ���ĺ�ͬ',
        action : 'salesInfoPageJson&id='+$('#id').val(),
		isDelAction : false,
		isToolBar : false, //�Ƿ���ʾ������
		showcheckbox : false,
		// ��
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			},{
				display : 'contId',
				name : 'contId',
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
		},{
			display : '��ͬ���',
			name : 'orderCodeOrTempSearch'
		}],
          menusEx : [{
			text : '�鿴',
			icon : 'view',
			action: function(row){

				showOpenWin('?model=contract_sales_sales&action=readDetailedInfoNoedit&id='
					+ row.contId );
			}
		   }],

          //���ò鿴ҳ����
          toViewConfig : {
			formHeight : 500 ,
			formWidth : 900
          }

	});
});
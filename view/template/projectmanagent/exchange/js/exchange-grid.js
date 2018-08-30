var show_page = function(page) {
	$("#exchangeGrid").yxgrid("reload");
};
$(function() {
	$("#exchangeGrid").yxgrid({
		model : 'projectmanagent_exchange_exchange',
		title : '���ۻ���',
		isDelAction : false,
		isToolBar : true, //�Ƿ���ʾ������
		showcheckbox : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'exchangeCode',
			display : '���������',
			sortable : true,
			width : 120
		}, {
			name : 'contractCode',
			display : 'Դ����',
			sortable : true,
			width : 120
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true,
			width : 250
		}, {
			name : 'saleUserName',
			display : '���۸�����',
			sortable : true
		}, {
			name : 'createName',
			display : '������',
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true
		}, {
			name : 'ExaDT',
			display : '��������',
			sortable : true
		}, {
			name : 'reason',
			display : '����ԭ��',
			sortable : true
		}],
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "����",
			icon : 'add',

			action : function(row) {
				showModalWin('?model=projectmanagent_exchange_exchange&action=toAdd');

			}
		}],
		menusEx : [

		{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=projectmanagent_exchange_exchange&action=init&id='
						+ row.id
						+ "&skey="+row['skey_']
						+ '&perm=view' );
			}
		},{
			text : '�������',
			icon : 'view',
            showMenuFn : function (row) {
               if (row.ExaStatus=='δ����'){
                   return false;
               }
                   return true;
            },
			action : function(row) {

				showThickboxWin('controller/projectmanagent/return/readview.php?itemtype=oa_sale_return	&pid='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���������',
			name : 'exchangeCode'
		}]
	});
});
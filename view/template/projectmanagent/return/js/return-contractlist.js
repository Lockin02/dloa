var show_page = function(page) {
	$("#contractlistGrid").yxsubgrid("reload");
};
$(function() {
	$("#contractlistGrid").yxsubgrid({
		model : 'projectmanagent_return_return',
		param : { 'contractId' : $("#contractId").val()},
		title : '�����˻�',
		isDelAction : false,
		isToolBar : false, //�Ƿ���ʾ������
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
			name : 'returnCode',
			display : '�˻������',
			sortable : true
		}, {
			name : 'contractCode',
			display : 'Դ����',
			sortable : true
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
		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_return_returnequ&action=pageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'returnId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������

			}],
			// ��ʾ����
			colModel : [{
						name : 'productCode',
						width : 200,
						display : '��Ʒ���'
					},{
						name : 'productName',
						width : 200,
						display : '��Ʒ����'
					}, {
					    name : 'number',
					    display : '��������',
						width : 80
					}, {
					    name : 'executedNum',
					    display : '��ִ������',
						width : 80
					}]
		},
		menusEx : [

		{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showOpenWin('?model=projectmanagent_return_return&action=init&id='
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
			display : '�˻������',
			name : 'renturnCode'
		}]
	});
});
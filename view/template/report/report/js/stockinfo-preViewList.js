var show_page = function(page) {
	$("#presentGrid").yxsubgrid("reload");
};
$(function() {
	$("#presentGrid").yxsubgrid({
		model : 'projectmanagent_present_present',
		title : '��������',
		param : {'ids' : $("#pids").val()},
		// ��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'Code',
					display : '���',
					sortable : true
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					sortable : true
				}, {
					name : 'salesName',
					display : '������',
					sortable : true
				}, {
					name : 'reason',
					display : '��������',
					sortable : true
				}, {
					name : 'remark',
					display : '��ע',
					sortable : true
				}, {
					name : 'ExaStatus',
					display : '����״̬',
					sortable : true
				}, {
					name : 'objCode',
					display : 'ҵ����',
					width : 120
				}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_present_presentequ&action=listpageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'presentId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������

			}],
			// ��ʾ����
			colModel : [{
						name : 'productNo',
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
					}, {
					    name : 'executedNum',
					    display : '��ִ������',
						width : 80
					}]
		},
		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_present_present&action=viewTab&id="
							+ row.id + "&skey=" + row['skey_']);
				}
			}
		}],
		/**
		 * ��������
		 */
		searchitems : [{
					display : 'Դ�����',
					name : 'orderCode'
				}, {
					display : '�ͻ�����',
					name : 'customerName'
				}, {
					display : 'ҵ����',
					name : 'objCode'
				}]
	});
});
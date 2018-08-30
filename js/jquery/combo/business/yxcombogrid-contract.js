/**
 * �����ͻ�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_contract', {
		options : {
			hiddenId : 'contractId',
			nameCol : 'contName',
			gridOptions : {
				model : 'contract_sales_sales',
				// ����Ϣ
				colModel : [{
							display : '�������',
							name : 'temporaryNo',
							sortable : true,
							hide :true
						}, {
							display : '��ͬ��',
							name : 'contNumber',
							width:130
						}, {
							display : '��ͬ����',
							name : 'contName',
							sortable : true,
							width:130
						}, {
							display : '��ͬ��λId',
							name : 'customerId',
							hide :true
						}, {
							display : '����Id',
							name : 'principalId',
							sortable : true,
							hide :true
						}, {
							display : '������',
							name : 'principalName',
							sortable : true
						}, {
							display : '�ͻ�����',
							name : 'customerName',
							sortable : true,
							width:170
						}, {
							display : '��ͬ״̬',
							name : 'contStatus',
							sortable : true,
							hide:true
						}],
				// ��������
				searchitems : [{
							display : '��ͬ��',
							name : 'contNumber'
						}, {
							display : '��ͬ����',
							name : 'contName',
							isdefault : true
						}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "DESC"
			}
		}
	});
})(jQuery);
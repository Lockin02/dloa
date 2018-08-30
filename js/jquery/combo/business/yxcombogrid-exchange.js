/**
 * �������� ����
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_exchange', {
		options : {
			hiddenId : 'id',
			nameCol : 'exchangeCode',
			gridOptions : {
				showcheckbox : false,
				model : 'projectmanagent_exchange_exchange',
				action : 'pageJson',
				param : {
					'ExaStatus' : '���'
				},
				pageSize : 10,
				// ����Ϣ
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'exchangeCode',
					display : '���������',
					sortable : true
				}, {
					name : 'contractCode',
					display : 'Դ����',
					sortable : true
				}, {
					name : 'customerName',
					display : '�ͻ�����',
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
				/**
				 * ��������
				 */
				searchitems : [{
					display : '���������',
					name : 'exchangeCode'
				}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
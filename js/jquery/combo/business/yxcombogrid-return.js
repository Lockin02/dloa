/**
 * �˻����� ����
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_return', {
		options : {
			hiddenId : 'id',
			nameCol : 'returnCode',
			gridOptions : {
				showcheckbox : false,
				model : 'projectmanagent_return_return',
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
				/**
				 * ��������
				 */
				searchitems : [{
					display : '�˻������',
					name : 'renturnCode'
				}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
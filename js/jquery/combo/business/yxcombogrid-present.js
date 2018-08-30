/**
 * �����û����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_present', {
		options : {
			hiddenId : 'id',
			nameCol : 'Code',
			gridOptions : {
				showcheckbox : false,
				model : 'projectmanagent_present_present',
				action : 'pageJson',
				param : {
					"ExaStatus" : "���"
				},
				pageSize : 10,
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
				// ��������
				searchitems : [{
					display : 'Դ�����',
					name : 'orderCode'
				}, {
					display : '�ͻ�����',
					name : 'customerName'
				}, {
					display : 'ҵ����',
					name : 'objCode'
				}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
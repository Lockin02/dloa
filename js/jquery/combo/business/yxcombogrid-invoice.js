/**
 * ������Ʒ�ɹ���Ʊ���
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_invoice.js', {
		options : {
			hiddenId : 'id',
			nameCol : 'invoiceNo',
			gridOptions : {
				showcheckbox : false,
				model : 'finance_invoice_invoice',
				pageSize : 10,
				// ����Ϣ
				colModel : [{
							display : 'id',
							name : 'id',
							hide : true
						}, {
							display : '��Ʊ����',
							name : 'invoiceNo'
						}, {
							display : '��Ʊ���ݱ��',
							name : 'invoiceCode',
							width : 140
						}, {
							display : '�ͻ�����',
							name : 'invoiceUnitName'
						},{
							display : '����ҵ�񵥾ݱ��',
							name : 'objCode',
							width : 140
						},{
							display : '������������',
							name : 'objType',
							datacode : 'KPRK',
							hide : true
						}, {
							display : '����',
							name : 'invoiceTime'
						}],
				// ��������
				searchitems : [{
							display : '��Ʊ���',
							name : 'invoiceNo'
						}],
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
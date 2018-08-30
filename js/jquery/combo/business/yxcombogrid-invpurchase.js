/**
 * ������Ʒ�ɹ���Ʊ���
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_invpurchase', {
		options : {
			hiddenId : 'invpurId',
			gridOptions : {
				showcheckbox : false,
				model : 'finance_invpurchase_invpurchase',
				pageSize : 10,
				// ����Ϣ
				colModel : [{
							display : 'id',
							name : 'id',
							hide : true
						}, {
							display : '��Ʊ���',
							name : 'objCode',
							width : 160
						},{
							display : '��Ӧ������',
							name : 'supplierName',
							width : 130
						}, {
							display : '����',
							name : 'formDate'
						}, {
							display : '��Ʊ���',
							name : 'amount',
							process : function(v){
								return moneyFormat2(v);
							}
						}],
				// ��������
				searchitems : [{
							display : '��Ʊ���',
							name : 'objCode'
						}],
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
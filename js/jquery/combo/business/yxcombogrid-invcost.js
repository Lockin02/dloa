/**
 * ������Ʒ�ɹ���Ʊ���
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_invCost.js', {
		options : {
			hiddenId : 'invCostId',
			gridOptions : {
				showcheckbox : false,
				model : 'finance_invcost_invcost',
				pageSize : 10,
				// ����Ϣ
				colModel : [{
							display : 'id',
							name : 'id',
							hide : true
						}, {
							display : '��Ʊ���',
							name : 'objCode'
						}, {
							display : '��Ʊ����',
							name : 'objNo'
						},{
							display : '��Ӧ������',
							name : 'supplierName',
							width : 150
						}, {
							display : '����',
							name : 'createTime',
							process:function (v){
								return v.substr(0,10);
							},
							width : 80
						},{
							display : '���',
							name : 'amount',
							process :function(v){
								return moneyFormat2(v);
							},
							width : 80
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
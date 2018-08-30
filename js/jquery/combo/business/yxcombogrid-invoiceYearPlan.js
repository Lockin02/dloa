/**
 * ������Ʒ�ɹ���Ʊ���
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_invoiceYearPlan.js', {
		options : {
			gridOptions : {
				showcheckbox : false,
				model : 'finance_invoice_yearPlan',
				pageSize : 10,
				// ����Ϣ
				colModel : [{
							display : 'id',
							name : 'id',
							hide : true
						}, {
							display : '���',
							name : 'year'
						}, {
							display : '��һ����',
							name : 'salesOne',
							process : function(v){
								return moneyFormat2(v);
							},
							hide : true
						}, {
							display : '�ڶ�����',
							name : 'salesTwo',
							process : function(v){
								return moneyFormat2(v);
							},
							hide : true
						}, {
							display : '��������',
							name : 'salesThree',
							process : function(v){
								return moneyFormat2(v);
							},
							hide : true
						}, {
							display : '���ļ���',
							name : 'salesFour',
							process : function(v){
								return moneyFormat2(v);
							},
							hide : true
						}, {
							display : '���ۿɿ����',
							name : 'salesAll',
							process : function(v){
								return moneyFormat2(v);
							}
						}, {
							display : '��һ����',
							name : 'serviceOne',
							process : function(v){
								return moneyFormat2(v);
							},
							hide : true
						}, {
							display : '�ڶ�����',
							name : 'serviceTwo',
							process : function(v){
								return moneyFormat2(v);
							},
							hide : true
						}, {
							display : '��������',
							name : 'serviceThree',
							process : function(v){
								return moneyFormat2(v);
							},
							hide : true
						}, {
							display : '���ļ���',
							name : 'serviceFour',
							process : function(v){
								return moneyFormat2(v);
							},
							hide : true
						}, {
							display : '����ɿ����',
							name : 'serviceAll',
							process : function(v){
								return moneyFormat2(v);
							}
						}],
				// ��������
				searchitems : [{
							display : '��',
							name : 'year'
						}]
			}
		}
	});
})(jQuery);
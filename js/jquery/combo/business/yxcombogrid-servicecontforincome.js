/**
 * ���������ͬ������ create by can
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_serviceContForIncome', {
		options : {
			hiddenId : 'id',
			nameCol : 'orderCode',
			gridOptions : {
				model : 'engineering_serviceContract_serviceContract',
				action : 'orderForIncomePj',
				// ����Ϣ
				colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					}, {
						name : 'orderCode',
						display : '������ͬ��',
						sortable : true
					}, {
						name : 'orderTempCode',
						display : '��ʱ��ͬ��',
						sortable : true
					}, {
						name : 'cusName',
						display : '�ͻ�����',
						sortable : true,
						hide : true
					}, {
						name : 'customerType',
						display : '�ͻ�����',
						datacode : 'KHLX',
						hide : true
					}, {
						name : 'orderMoney',
						display : '��ͬ���',
						sortable : true,
						process : function(v) {
							return moneyFormat2(v);
						},
						width : 80
					},{
						name : 'invoiceMoney',
	  					display : '��Ʊ���',
	  					process : function(v){
	  						return moneyFormat2(v);
	  					},
	  					width : 80
	                },{
						name : 'unInvoiceMoney',
	  					display : '����Ʊ���',
	  					process : function(v){
	  						return moneyFormat2(v);
	  					},
	  					width : 80
	                },{
						name : 'incomeMoney',
	  					display : '������',
	  					process : function(v){
	  						return moneyFormat2(v);
	  					},
	  					width : 80
	                },{
						name : 'unIncomeMoney',
	  					display : 'δ�ս��',
	  					process : function(v){
	  						return moneyFormat2(v);
	  					},
	  					width : 80
	                }, {
						name : 'orderPrincipal',
						display : '��ͬ������',
						sortable : true,
						width : 80
					}, {
						name : 'areaPrincipal',
						display : '��������',
						sortable : true,
						width : 80
					}, {
						name : 'areaName',
						display : '��ͬ����',
						sortable : true,
						width : 80
					}
				],
				// Ĭ������˳��
				sortorder : "DESC"
			}
		}
	});
})(jQuery);
/**
 * �������޺�ͬ������ create by can
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_allorderforincome', {
				options : {
					hiddenId : 'orgid',
					nameCol : 'orderCode',
					searchName : 'orderCodeOrTempSearch',
					focusoutCheckAction : 'getCountByNameForView',
					autoHiddenName : {
						'objCode' : 'rObjCode'
					},
					gridOptions : {
						model : 'projectmanagent_order_order',
						action : 'allOrderForIncomePj',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'tablename',
									display : '��ͬ����',
									sortable : true,
									datacode : 'KPRK',
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
									name : 'customerName',
									display : '�ͻ�����',
									sortable : true,
									hide : true
								}, {
									name : 'customerType',
									display : '�ͻ�����',
									datacode : 'KHLX',
									hide : true
								}, {
									name : 'contractMoney',
									display : '��ͬ���',
									sortable : true,
									process : function(v, row) {
										return moneyFormat2(v);
									},
									width : 80
								}, {
									name : 'invoiceMoney',
									display : '��Ʊ���',
									process : function(v) {
										return moneyFormat2(v);
									},
									width : 80
								}, {
									name : 'unInvoiceMoney',
									display : '����Ʊ���',
									process : function(v) {
										return moneyFormat2(v);
									},
									width : 80
								}, {
									name : 'incomeMoney',
									display : '������',
									process : function(v) {
										return moneyFormat2(v);
									},
									width : 80
								}, {
									name : 'unIncomeMoney',
									display : 'δ�ս��',
									process : function(v) {
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
								}, {
									name : 'ExaStatus',
									display : '����״̬',
									sortable : true,
									width : 80
								}, {
									name : 'objCode',
									display : 'ҵ����',
									width : 120
								}],
						// Ĭ������˳��
						sortorder : "DESC"
					}
				}
			});
})(jQuery);
/**
 * ���������ƻ����
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_outplan', {
				options : {
					hiddenId : 'id',
					nameCol : 'planCode',
					gridOptions : {
						showcheckbox : false,
						model : 'stock_outplan_outplan',
						pageSize : 10,
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true
								}, {
									display : '�����ƻ����',
									name : 'planCode'
								}, {
									display : '�ܴ�',
									name : 'week'
								}, {
									display : 'Դ������',
									name : 'docType',
									process : function(v) {
										if (v == 'oa_contract_contract') {
											return "��ͬ";
										} else if (v == 'oa_contract_exchangeapply') {
											return "��������";
										} else if (v == 'oa_borrow_borrow') {
											return "����������";
										} else if (v == 'oa_present_present') {
											return "��������";
										}
									}
								}, {
									display : '��ͬId',
									name : 'docId',
									hide : true
								}, {
									display : '��ͬ��',
									name : 'docCode'
								}, {
									display : '��ͬ����',
									name : 'docName',
									hide : true
								}, {
									display : '�ֿ�Id',
									name : 'stockId',
									hide : true,
									width : 80
								}, {
									display : '�ֿ����',
									name : 'stockCode',
									hide : true,
									width : 80
								}, {
									display : '�ֿ�����',
									name : 'stockName',
									width : 80
								}, {
									display : '������ʽ',
									name : 'shipType',
									hide : true,
									width : 80
								}, {
									display : '�ͻ�����',
									name : 'customerName',
									width : 80
								}, {
									display : '�ͻ�id',
									name : 'customerId',
									hide : true
								}],
						// ��������
						searchitems : [{
									display : '�ƻ����',
									name : 'planCode'
								}],
						// Ĭ������˳��
						sortorder : "DESC"
					}
				}
			});
})(jQuery);
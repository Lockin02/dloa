/**
 * �����ͻ�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_stock', {
				options : {
					hiddenId : 'stockId',
					nameCol : 'stockName',
					gridOptions : {
						model : 'stock_stockinfo_stockinfo',
						// ����Ϣ
						colModel : [{
									display : '�ֿ�Id',
									name : 'id',
									hide : true,
									width:130
								},{
									display : '�ֿ�����',
									name : 'stockName',
									width:100
								}, {
									display : '�ֿ�����',
									name : 'stockUse',
									sortable : true,
									datacode : 'CKLX',
									width:'100',
									align : 'center'
								},{
									display : '�ֿ����',
									name : 'stockCode',
									width:100
								},{
									display : '�ֿ����Ա',
									name : 'chargeUser',
									width:150
								},{
									display : '�ֿ��ַ',
									name : 'adress',
									width:150
								}],
						// ��������
						searchitems : [{
									display : '�ֿ�����',
									name : 'stockName'
								}],
						// Ĭ�������ֶ���
						sortname : "stockName",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);
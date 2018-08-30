/**
 * ����������������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_stockoutitem', {
		options : {
			hiddenId : 'productId',
			nameCol : 'productName',
			gridOptions : {
				model : 'stock_outstock_stockoutitem',
				// ����Ϣ
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'mainId',
					display : '�������뵥id',
					sortable : true,
					hide : true
				}, {
					name : 'productId',
					display : '��Ʒid',
					sortable : true,
					hide : true
				}, {
					name : 'productNo',
					display : '��Ʒ���',
					sortable : true
				}, {
					name : 'productModel',
					display : '��Ʒ�ͺ�',
					sortable : true,
					hide : true
				}, {
					name : 'productName',
					display : '��Ʒ����',
					sortable : true
				}, {
					name : 'outPrice',
					display : '�����',
					sortable : true,
					hide : true
				}, {
					name : 'stockId',
					display : '�����ֿ�id',
					sortable : true,
					hide : true
				}, {
					name : 'stockName',
					display : '�����ֿ�����',
					sortable : true,
					hide : true
				}, {
					name : 'stockCode',
					display : '�����ֿ����',
					sortable : true,
					hide : true
				}, {
					name : 'subOutNum',
					display : '�ѳ�������',
					sortable : true,
					hide : true
				}, {
					name : 'applyNum',
					display : '�����������',
					sortable : true,
					hide : true
				}],
				// ��������
				searchitems : [{
					display : '��Ʒ����',
					name : 'productName'
				}],
				// Ĭ�������ֶ���
				sortname : "productName",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
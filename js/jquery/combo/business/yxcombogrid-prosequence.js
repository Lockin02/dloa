/**
 * �����ͻ�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_prosequence', {
				options : {
					hiddenId : 'id',
					nameCol : 'sequence',
					gridOptions : {
						model : 'stock_productserialno_serialno',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true,
									width : 130
								}, {
									display : '������뵥id',
									name : 'storageAppId',
									hide : true,
									width : 130

								},{
									display : '��Ʒid',
									name : 'productId',
									hide : true,
									width : 130
								}, {
									display : '��Ʒ����',
									name : 'productName',
									width : 100
								}, {
									display : '��Ʒ���',
									name : 'productCode',
									width : 100
								}, {
									display : '���к�',
									name : 'sequence',
									width : 150
								}, {
									display : '��ⵥ���',
									name : 'storageAppCode',
									hide:true,
									width : 100
								}, {
									display : '���ⵥ���',
									name : 'outStockCode',
									hide:true,
									width : 100
								}, {
									display : '���ⵥid',
									name : 'outStockId',
									hide : true,
									width : 150
								}, {
									display : '��ע',
									name : 'remark',
									width : 150
								}],
						// ��������
						searchitems : [{
									display : '���к�',
									name : 'sequence'
								}],
						// Ĭ�������ֶ���
						sortname : "sequence",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);
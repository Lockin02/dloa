/**
 * ��������Ʒ������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_inventory', {
				options : {
					nameCol : 'productName',
					gridOptions : {
						model : 'stock_inventoryinfo_inventoryinfo',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									name : 'stockId',
									display : '�ֿ�id',
									sortable : true,
									hide : true
								}, {
									name : 'stockName',
									display : '�ֿ�����',
									sortable : true
								}, {
									name : 'stockCode',
									display : '�ֿ����',
									sortable : true,
									hide : true
								}, {
									name : 'proTypeId',
									display : '��������id',
									sortable : true,
									hide : true
								}, {
									name : 'proType',
									display : '������������',
									sortable : true,
									hide : true
								}, {
									name : 'productId',
									display : '��Ʒid',
									sortable : true,
									hide : true
								}, {
									name : 'productCode',
									display : '���ϱ��',
									sortable : true
								}, {
									name : 'productName',
									display : '��������',
									width:'200',
									sortable : true
								}, {
									name : 'pattern',
									display : '����ͺ�',
									hide : true
								}, {
									name : 'unitName',
									display : '��λ',
									hide : true
								}, {
									name : 'initialNum',
									display : '��ʼ���',
									sortable : true,
									hide : true
								}, {
									name : 'actNum',
									display : '���п��',
									sortable : true
								}, {
									name : 'safeNum',
									display : '��ȫ���',
									sortable : true,
									hide : true
								}, {
									name : 'exeNum',
									display : '��ִ�п��',
									sortable : true,
									hide : true
								}, {
									name : 'price',
									display : '�۸�ɱ�',
									sortable : true,
									hide : true
								}],
						// ��������
						searchitems : [{
									display : '��������',
									name : 'productName'
								}, {
									display : '���ϱ��',
									name : 'productCode'
								}],
						// Ĭ�������ֶ���
						sortname : "productId",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);
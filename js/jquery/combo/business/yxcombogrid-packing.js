/**
 * ������Ʒ������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_packing', {
			options : {
				hiddenId : 'id',
				nameCol : 'productName',
				gridOptions : {
					showcheckbox : false,
					model : 'stock_inventoryinfo_inventoryinfo',
					param : {'stockCode':'PACKAGING'},
					pageSize : 10,
					// ����Ϣ
					colModel : [{
								name : 'id',
								display : 'id',
								hide : true
							},{
								display : '��Ʒ����',
								name : 'productName',
								width : 180
							},{
								display : ' ��ƷID',
								name : 'productId',
								hide : true
							}, {
								display : '��Ʒ���',
								name : 'sequence',
								width : 130
							},{
								display : '�ֿ�Id',
								name : 'stockId',
								width : 130,
								hide : true
							}],
					// ��������
					searchitems : [{
								display : '��Ʒ����',
								name : 'productName'
							}],
					// Ĭ�������ֶ���
					sortname : "id",
					// Ĭ������˳��
					sortorder : "ASC"
			}
		}
	});
})(jQuery);
/**
 * �����ͻ�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_storage', {
				options : {
					hiddenId : 'id',
					nameCol : 'storageAppCode',
					gridOptions : {
						model : 'stock_storage_storageapply',
						action : 'pageComboJson',//��������(�����)

						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true,
									width:130
								},{
									display : '������뵥��',
									name : 'storageAppCode',
									width:100
								},{
									display : '������뵥����',
									name : 'storageType',
									datacode : 'STORAGEAPPLY',
									width:150,
									hide : true
								},{
									display : '���״̬',
									name : 'isInStock',
									width:150,
									hide : true
								},{
									display : '���ֿ�����',
									name : 'inStockName',
									width:150
								},{
									display : '���ֿ�Id',
									name : 'inStockId',
									width:150,
									hide : true
								},{
									display : '���ֿ����',
									name : 'inStockCode',
									width:150
								},{
									display : '��Ӧ������',
									name : 'supplierName',
									width:150
								},{
									display : '��Ӧ��Id',
									name : 'supplierId',
									width:150,
									hide : true
								}],

						// ��������
						searchitems : [{
									display : '������뵥��',
									name : 'storageAppCode'
								}],
						// Ĭ�������ֶ���
						sortname : "storageAppCode",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);
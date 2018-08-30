/**
 * �����˻���������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_delivered', {
				options : {
					hiddenId : 'id',
					nameCol : 'deliveredCode',
					gridOptions : {
						model : 'stock_delivered_delivered',
						// ����Ϣ
					colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'deliveredCode',
							display : '�˻�����',
							sortable : true
						}, {
							name : 'supplierName',
							display : '��Ӧ������',
							sortable : true
						}, {
							name : 'supplierId',
							display : '��Ӧ��id',
							sortable : true,
							hide : true
						}, {
							name : 'deStockName',
							display : '�˻��ֿ�',
							sortable : true
						}, {
							name : 'deStockCode',
							display : '�˻��ֿ����',
							sortable : true
						}, {
							name : 'deStockId',
							display : '�˻��ֿ�id',
							sortable : true,
							hide : true
						}],
						// ��������
						searchitems : [{
									display : '�˻�����',
									name : 'deliveredCode'
								}],
						// Ĭ�������ֶ���
						sortname : "deliveredCode",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);
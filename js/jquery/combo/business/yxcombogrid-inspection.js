/**
 * �����ʼ쵥������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_inspection', {
				options : {
					hiddenId : 'id',
					nameCol : 'documentCode',
					gridOptions : {
						model : 'quality_inspection_inspection',
					// ����Ϣ
					colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					}, {
						name : 'documentCode',
						display : '���ݺ�',
						sortable : true
					}, {
						name : 'inspectDate',
						display : '��������',
						sortable : true,
						hide : true
					}, {
						name : 'inspectType',
						display : '�������뵥����',
						sortable : true
					}, {
						name : 'materialCode',
						display : '���ϱ���',
						sortable : true,
						hide : true
					}, {
						name : 'materialName',
						display : '��������',
						sortable : true
					}, {
						name : 'materialId',
						display : '����id',
						sortable : true,
						hide : true
					}, {
						name : 'inspectProgram',
						display : '���鷽��',
						sortable : true,
						hide : true
					}, {
						name : 'recStockName',
						display : '���ϲֿ�����',
						sortable : true,
						hide : true
					}, {
						name : 'recStockCode',
						display : '���ϲֿ����',
						sortable : true,
						hide : true
					}, {
						name : 'recStockId',
						display : '���ϲֿ�id',
						sortable : true,
						hide : true
					}, {
						name : 'supplierId',
						display : '��Ӧ��id',
						sortable : true,
						hide : true
					}, {
						name : 'supplierName',
						display : '��Ӧ������',
						sortable : true,
						hide : true
					}, {
						name : 'batchNum',
						display : '����',
						sortable : true,
						hide : true
					}, {
						name : 'checkNum',
						display : '��������',
						sortable : true
					}, {
						name : 'acceptNum',
						display : '�ϸ���',
						sortable : true
					}, {
						name : 'rejectNum',
						display : '���ϸ���',
						sortable : true
					}, {
						name : 'checkResult',
						display : '������',
						sortable : true,
						hide : true
					}],
						// ��������
						searchitems : [{
									display : '���ݺ�',
									name : 'documentCode'
								}],
						// Ĭ�������ֶ���
						sortname : "id",
						// Ĭ������˳��
						sortorder : "DESC"
					}
				}
			});
})(jQuery);
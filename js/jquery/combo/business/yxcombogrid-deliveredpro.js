/**
 * �����˻�����Ʒ������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_deliveredpro', {
		options : {
			hiddenId : 'productId',
			nameCol : 'productName',
			gridOptions : {
				showcheckbox : false,
				model : 'stock_delivered_deliveredpro',
				pageSize : 10,
				// ����Ϣ
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'deliveredId',
					display : '�˻���Id',
					sortable : true,
					hide : true
				}, {
					name : 'productId',
					display : '��Ʒid',
					sortable : true,
					hide : true
				}, {
					name : 'productName',
					display : '��Ʒ����',
					sortable : true
				}, {
					name : 'sequence',
					display : '��Ʒ���',
					sortable : true
				}, {
					name : 'serialNumber',
					display : '���к�',
					sortable : true,
					hide : true
				}, {
					name : 'productNum',
					display : '�˻�����',
					sortable : true
				}, {
					name : 'remark',
					display : '˵��',
					sortable : true,
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
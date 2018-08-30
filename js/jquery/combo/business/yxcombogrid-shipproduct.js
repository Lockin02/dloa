/**
 * ������������Ʒ������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_shipproduct', {
		options : {
			hiddenId : 'productId',
			nameCol : 'productName',
			gridOptions : {
				model : 'stock_shipapply_shipproduct',
				// ����Ϣ
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'shipApplyId',
					display : '�������뵥id',
					sortable : true,
					hide : true
				}, {
					name : 'productName',
					display : '��Ʒ����',
					sortable : true
				}, {
					name : 'productNo',
					display : '��Ʒ���',
					sortable : true
				}, {
					name : 'productId',
					display : '��Ʒid',
					sortable : true,
					hide : true
				}, {
					name : 'shipNum',
					display : '��������',
					sortable : true
				}, {
					name : 'stockId',
					display : '�ֿ�Id',
					sortable : true,
					hide : true
				}, {
					name : 'stockName',
					display : '�ֿ�����',
					sortable : true,
					hide : true
				}, {
					name : 'equOnlyId',
					display : '��Ʒ�嵥���',
					sortable : true,
					hide : true
				}, {
					name : 'contractNo',
					display : 'ҵ����',
					sortable : true,
					hide : true
				}, {
					name : 'version',
					display : 'ҵ��汾��',
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
/**
 * ������Ʒ������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_equipment', {
		options : {
			hiddenId : 'productId',
			nameCol : 'productName',
			gridOptions : {
				showcheckbox : false,
				model : 'contract_equipment_equipment',
				// ����Ϣ
				colModel : [{
							display : '��Ʒ���',
							name : 'productNumber',
							width : 130
						}, {
							display : '��Ʒ����',
							name : 'productName',
							width : 180
						}, {
							display : '����������',
							name : 'amount'
						}, {
							display : '��Ʒ����',
							name : 'ptype',
							hide : true
						}, {
							display : '��ƷId',
							name : 'productId',
							hide : true
						}, {
							display : '��Ʒ�ͺ�',
							name : 'productModel',
							hide: true
						}, {
							display : '��ͬ��־λ',
							name : 'contOnlyId',
							hide: true
						}],
				// ��������
				searchitems : [{
						display : '��Ʒ����',
						name : 'productName'
					}],
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
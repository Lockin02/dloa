/**
 * ������Ʒ�ɹ���Ʊ���
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_logistics.js', {
		options : {
			hiddenId : 'id',
			nameCol : 'companyName',
			gridOptions : {
				showcheckbox : false,
				model : 'mail_logistics_logistics',
				pageSize : 10,
				// ����Ϣ
				colModel : [{
							display : 'id',
							name : 'id',
							hide : true
						}, {
							display : '��˾����',
							name : 'companyName'
						}, {
							display : '��ϵ�绰',
							name : 'phone'
						},{
							display : '���˷�Χ',
							name : 'rangeDelivery',
							width : 120
						},{
							display : '�����ٶ�',
							name : 'speed'
						}, {
							display : '���˰�ȫ��',
							name : 'security',
							width : 80
						}],
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
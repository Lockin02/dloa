/**
 * �����ͻ�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_licenseType', {
				options : {
					hiddenId : 'id',
					nameCol : 'typeName',
//					checkbox : true,
					gridOptions : {
						model : 'product_licensetype_licensetype',
						// ����Ϣ
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true
								}, {
									display : 'license����',
									name : 'typeName',
									sortable : true
								}],
//						// ��������
						searchitems : [{
									display : 'license����',
									name : 'typeName'
								}],
						// Ĭ�������ֶ���
						sortname : "typeName",
						// Ĭ������˳��
						sortorder : "ASC"
					}
				}
			});
})(jQuery);
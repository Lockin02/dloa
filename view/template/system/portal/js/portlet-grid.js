(function($) {

	$.woo.yxgrid.subclass('woo.yxgrid_portlet', {
				options : {
					model : 'system_portal_portlet',
					// ��
					colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : 'portlet����',
								name : 'portletName'
							}, {
								display : '����',
								name : 'typeName'
							}, {
								display : '·��',
								name : 'url',
								width : 300
							}],
					/**
					 * ��������
					 */
					searchitems : [{
								display : 'portlet����',
								name : 'portletName'
							}],
					sortorder : "DESC",
					sortname : "id",
					title : 'portlet��Ϣ'
				}
			});
})(jQuery);
(function($) {

	$.woo.yxgrid.subclass('woo.yxgrid_portlettype', {
				options : {
					model : 'system_portal_portlettype',
					// ��
					colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
							}, {
								display : '�ϼ�����',
								name : 'parentName'
							}, {
								display : '��������',
								name : 'typeName'
							}],
					/**
					 * ��������
					 */
					searchitems : [{
								display : '��������',
								name : 'typeName'
							}],
					sortorder : "DESC",
					sortname : "id",
					title : '��������'
				}
			});
})(jQuery);
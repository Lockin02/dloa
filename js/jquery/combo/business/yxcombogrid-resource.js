/**
 * ��ԴĿ¼����combogrid
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_resource', {
				options : {
					hiddenId : 'id',
					nameCol : 'resourceName',
					gridOptions : {
						model : 'engineering_baseinfo_resource',

						// ��
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '��Դ����',
									name : 'resourceCode',
									sortable : true,
									width : 150
								}, {
									display : '��Դ����',
									name : 'resourceName',
									sortable : true,
									width : 150
								}, {
									display : '��Դ����',
									name : 'parentName',
									sortable : true,
									width : 150
								}],

						/**
						 * ��������
						 */
						searchitems : [{
									display : '��Դ����',
									name : 'resourceCode'
								}],
						pageSize : 10,
						sortorder : "desc",
						title : '��ԴĿ¼'
					}
				}
			});
})(jQuery);
/**
 * Ԥ����Ŀ����combogrid
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_esmrole', {
		options : {
			hiddenId : 'id',
			nameCol : 'roleName',
			gridOptions : {
				model : 'engineering_role_esmrole',
				action : 'pageJsonOrg',
				// ��
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							display : '��ɫ����',
							name : 'roleName',
							sortable : true
						}, {
							display : '��������',
							name : 'activityName',
							sortable : true,
							width : 200
						}, {
							display : '��������',
							name : 'jobDescription',
							sortable : true,
							width : 150
						}],

				/**
				 * ��������
				 */
				searchitems : [{
					display : '��ɫ����',
					name : 'roleNameSearch'
				}],
				pageSize : 10,
				sortorder : "ASC",
				sortname : "id",
				title : '��Ŀ��ɫ'
			}
		}
	});
})(jQuery);
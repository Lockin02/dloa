/**
 * Ԥ����Ŀ����combogrid
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_esmmember', {
		options : {
			hiddenId : 'id',
			nameCol : 'memberName',
			gridOptions : {
				model : 'engineering_member_esmmember',
				action : 'pageJsonOrg',
				// ��
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'memberName',
					display : '����',
					sortable : true,
					width : 120
				}, {
					name : 'memberId',
					display : '��Աid',
					sortable : true,
					hide : true
				}, {
					name : 'personLevel',
					display : '����',
					sortable : true,
					width : 120
				}, {
					name : 'roleName',
					display : '��ɫ',
					sortable : true,
					width : 120
				}],

				/**
				 * ��������
				 */
				searchitems : [{
					display : '����',
					name : 'memberNameSearch'
				}],
				pageSize : 10,
				sortorder : "ASC",
				sortname : "id",
				title : '��Ŀ��Ա'
			}
		}
	});
})(jQuery);
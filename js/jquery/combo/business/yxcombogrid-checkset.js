/**
 * ������������������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_checkset', {
		isDown : true,
		options : {
			hiddenId : 'id',
			nameCol : 'clause',
			gridOptions : {
				showcheckbox : false,
				model : 'contract_checksetting_checksetting',
				action : 'pageJson',
				pageSize : 10,
				// ����Ϣ
				colModel : [{
					name : 'clause',
					display : '��������',
					sortable : true,
					width : 100
				}, {
					name : 'dateName',
					display : '����ʱ��ڵ�',
					sortable : true
				}, {
					name : 'dateCode',
					display : '����ʱ��ڵ����',
					sortable : true,
					hide : true
				}, {
					name : 'days',
					display : '��������',
					sortable : true
				}, {
					name : 'description',
					display : '˵��',
					sortable : true,
					width : 300
				} ]
			}
		}
	});
})(jQuery);
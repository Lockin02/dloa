/**
 * �ʼ������Ŀ����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_qualitydimension', {
		options : {
			hiddenId : 'id',
			nameCol : 'dimName',
			gridOptions : {
				showcheckbox : false,
				model : 'produce_quality_dimension',
				action : 'pageJson',
				pageSize : 10,
				// ����Ϣ
				colModel : [ {
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'dimName',
					display : '������Ŀ����',
					sortable : true

				} ],
				// ��������
				searchitems : [ {
					display : '������Ŀ����',
					name : 'dimName'
				} ],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "desc"
			}
		}
	});
})(jQuery);
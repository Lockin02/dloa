/**
 * �ʼ�������׼����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_qualitystandard', {
		options : {
			hiddenId : 'id',
			nameCol : 'standardName',
			gridOptions : {
				showcheckbox : false,
				model : 'produce_quality_standard',
				action : 'pageJson',
				pageSize : 10,
				// ����Ϣ
				colModel : [ {
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'standardName',
					display : 'ָ���׼����',
					sortable : true

				} ],
				// ��������
				searchitems : [ {
					display : 'ָ���׼����',
					name : 'standardName'
				} ],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "desc"
			}
		}
	});
})(jQuery);
/**
 * �ʼ���鷽ʽ����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_qualitychecktype', {
		options : {
			hiddenId : 'id',
			nameCol : 'checkType',
			gridOptions : {
				showcheckbox : false,
				model : 'produce_quality_checktype',
				action : 'pageJson',
				pageSize : 10,
				// ����Ϣ
				colModel : [ {
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'checkType',
					display : '���鷽ʽ',
					sortable : true

				} ],
				// ��������
				searchitems : [ {
					display : '���鷽ʽ',
					name : 'checkType'
				} ],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "desc"
			}
		}
	});
})(jQuery);
/**
 * ��������-��Ŀ�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_process', {
		options : {
			hiddenId : 'processName',
			nameCol : 'processName',
			width : 400,
			isFocusoutCheck : false,
			gridOptions : {
				showcheckbox : false,
				model : 'manufacture_basic_processequ',
				param : {
					'groupBy' : "processName"
				},
				//����Ϣ
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'processName',
					display : '��Ŀ����',
					width : 350,
					sortable : true
				}],

				// ��������
				searchitems : [{
					display : '��Ŀ����',
					name : 'processName'
				}],

				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "DESC"
			}
		}
	});
})(jQuery);
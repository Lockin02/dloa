/**
 * �ʼ������Ŀ����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_qualityprogram', {
		options : {
			hiddenId : 'id',
			nameCol : 'programName',
			gridOptions : {
				showcheckbox : false,
				model : 'produce_quality_quaprogram',
				action : 'pageJson',
				pageSize : 10,
				// ����Ϣ
				colModel : [ {
					display : 'id',
					name : 'id',
					hide : true
				}, {
					name : 'programName',
					display : '��������',
					sortable : true,
					width : 150
				}, {
					name : 'standardName',
					display : '������׼',
					sortable : true,
					width : 150
				}, {
					name : 'remark',
					display : '��ע',
					sortable : true,
					width : 200
				}],
				// ��������
				searchitems : [ {
					display : '��������',
					name : 'programName'
				} ],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "desc"
			}
		}
	});
})(jQuery);
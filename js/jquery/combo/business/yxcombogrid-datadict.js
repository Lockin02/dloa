/**
 * �����ͻ�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_datadict', {
		options : {
			hiddenId : 'dataCode',
			nameCol : 'dataName',
			valueCol : 'dataCode',
			isFocusoutCheck : false,
			gridOptions : {
				model : 'system_datadict_datadict',
				// ����Ϣ
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '����',
					name : 'dataName',
					sortable : true,
					width : 300

				}, {
					display : '����',
					name : 'dataCode',
					sortable : true,
					width : 200
				}],
				// ��������
				searchitems : [{
					display : '����',
					name : 'dataName'
				}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
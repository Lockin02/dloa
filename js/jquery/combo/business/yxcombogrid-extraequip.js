/**
 * �����豸����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_extraequip', {
		options : {
			hiddenId : 'id',
			nameCol : 'equipName',
			gridOptions : {
				showcheckbox : false,
				model : 'stock_extra_equipment',
				action : 'pageJson',
				pageSize : 10,
				// ����Ϣ
				colModel : [ {
					display : '�豸����',
					name : 'equipName',
					width : 180
				}, {
					display : '�Ƿ�ͣ��',
					name : 'isProduce',
					process : function(v, row) {
						if (v == "0") {
							return "�ڲ�";
						} else {
							return "ͣ��";
						}
					},
					width : 80
				}, {
					display : '��ע',
					name : 'remark'
				} ],
				// ��������
				searchitems : [ {
					display : '�豸����',
					name : 'equipName'

				} ],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
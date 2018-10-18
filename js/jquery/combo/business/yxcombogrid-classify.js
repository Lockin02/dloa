/**
 * �����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_classify', {
		options : {
			hiddenId : 'id',
			nameCol : 'templateName',
			width : 400,
			isFocusoutCheck : false,
			gridOptions : {
				showcheckbox : false,
				model : 'manufacture_basic_template',
				param : {
					isEnable : '��'
				},
				//����Ϣ
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'templateName',
					display : 'ģ������',
					width : 180,
					sortable : true
				},{
					name : 'createName',
					display : '¼����',
					width : 80,
					sortable : true
				},{
					name : 'createTime',
					display : '¼��ʱ��',
					width : 120,
					sortable : true
				}],

				// ��������
				searchitems : [{
					display : 'ģ������',
					name : 'templateName'
				}],

				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
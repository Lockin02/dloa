/**
 * ģ����Ϣ����������
 */
/**
 * �����ͻ�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_protemplate', {
		options : {
			hiddenId : 'id',
			nameCol : 'templateName',
			valueCol : 'templateName',
			gridOptions : {
				showcheckbox : false,
				model : 'stock_template_protemplate',
				// ����Ϣ
				colModel : [{
						display : 'ID',
						name : 'id',
					},{
						display : 'ģ������',
						name : 'templateName',
					}, {
						display : '��ע',
						name : 'remark',
					}
				],
				// ��������
				searchitems : [{
						display : 'ģ������',
						name : 'templateName'
					}
				],
				// Ĭ�������ֶ���
				sortname : "templateName",
				// Ĭ������˳��
				sortorder : "DESC"
			}
		}
	});
})(jQuery);
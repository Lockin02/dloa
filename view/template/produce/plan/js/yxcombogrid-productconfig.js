/**
 * �����������ϻ�����Ϣ����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_productconfig', {
		options : {
			hiddenId : 'productId',
			nameCol : 'productCode',
			gridOptions : {
				showcheckbox : false,
				model : 'produce_task_configproduct',
				action : 'pageJson',

				// ����Ϣ
				colModel : [{
					display : '���ñ���',
					name : 'productCode',
					width : 80
				},{
					display : '��������',
					name : 'productName',
					width : 180
				},{
					display : '��������',
					name : 'num',
					width : 60
				},{
					display : '���´�����',
					name : 'planNum',
					width : 60
				}],

				// ��������
				searchitems : [{
					display : '���ñ���',
					name : 'productCode'
				},{
					display : '��������',
					name : 'productName'
				}],

				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "DESC"
			}
		}
	});
})(jQuery);
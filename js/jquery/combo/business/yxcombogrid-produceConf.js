/**
 * ��Ʒ�������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_produceConf', {
		options : {
			hiddenId : 'id',
			nameCol : 'produceName',
			width : 400,
			isFocusoutCheck : false,
			gridOptions : {
				showcheckbox : false,
				model : 'manufacture_basic_produceconfiguration',
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
					name : 'produceName',
					display : '��Ʒ����',
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
					display : '��Ʒ����',
					name : 'produceName'
				}],

				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
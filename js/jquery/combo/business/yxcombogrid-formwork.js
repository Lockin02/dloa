/**
 * ����ģ��
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_formwork', {
		options : {
			isDown : true,
			hiddenId : 'id',
			gridOptions : {
				model : 'hr_formwork_formwork',
                param : {'isUse':"0"},
				// ����Ϣ
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'formworkName',
					display : 'ģ������',
					sortable : true
				}, {
					name : 'isUse',
					display : '�Ƿ�����',
					sortable : true,
					process : function(v, row) {
						if (v == '0') {
							return "����";
						} else if (v == '1') {
							return "ֹͣ";
						}
					}
				}],
				// // ��������
				// searchitems : [{
				// display : '��ͬ���',
				// name : 'orderCodeOrTempSearch'
				// }, {
				// display : '��ͬ����',
				// name : 'orderName',
				// isdefault : true
				// }],

				// Ĭ������˳��
				sortorder : "DESC"
			}
		}
	});
})(jQuery);
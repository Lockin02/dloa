/**
 * ��ѵ�ƻ�������
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_trialplantem', {
		options : {
			hiddenId : 'id',
			// param : { 'customerId' :$('customerId').val() },
			nameCol : 'planName',
			title : 'Ա����ѵ�ƻ�ģ��',
			gridOptions : {
				model : 'hr_baseinfo_trialplantem&action=page',

				// ��
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'planName',
					display : '�ƻ�����',
					sortable : true,
					width:250
				},{
					name : 'description',
					display : '����',
					sortable : true,
					width:250
				}],

				/**
				 * ��������
				 */
				searchitems : [{
					display : "�ƻ�����",
					name : 'planNameSearch'
				}],
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
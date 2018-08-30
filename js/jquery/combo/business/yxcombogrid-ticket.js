/**
 * ������Ϣ����������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_ticket', {
		options : {
			hiddenId : 'id',
			nameCol : 'institutionName',//ҪĬ����ʵ
			height : 270,
			gridOptions : {
				title : '��Ʊ����',
				isTitle : true,
				showcheckbox : false,
				model : 'flights_ticketagencies_ticket',
				action : 'pageJson',
				pageSize : 10,
				// ����Ϣ
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'institutionId',
					display : '��������',
					sortable : true,
					width:100
				}, {
					name : 'institutionName',
					display : '��������',
					sortable : true,
					width:100
				}, {
					name : 'bookingBusiness',
					display : '��Ʊҵ��',
					sortable : true,
					width:150
				}, {
					name : 'institutionBusiness',
					display : '��������',
					sortable : true,
					width:100
				}],
				// ��������
				searchitems : [{
					display : '��������',
					name : 'institutionIdSearch'
				},{
					display : '��������',
					name : 'institutionNameSearch'
				}],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "desc"
			}
		}
	});
})(jQuery);
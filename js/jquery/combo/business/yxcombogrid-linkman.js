/**
 * ��ϵ�˱�����
 */

(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_linkman', {
		options : {
			hiddenId : 'id',
//			param : { 'customerId' :$('customerId').val() },
			nameCol : 'linkmanName',
//			openPageOptions : {
//				url : '?model=customer_linkman_linkman&action=selectLinkman'
//			},
			gridOptions : {
				model : 'customer_linkman_linkman',

					// ��
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '�ͻ�',
					name : 'customerName',
					sortable : true,
					width : 150
				}, {
					display : '����',
					name : 'linkmanName',
					sortable : true,

					width : 150
				}, {
					display : '�绰����',
					name : 'phone',
					sortable : true,
					width : 150
				}, {
					display : '�ֻ�����',
					name : 'mobile',
					sortable : true,
					width : 150
				}, {
					display : 'MSN',
					name : 'MSN',
					sortable : true,
					width : 150
				}, {
					display : 'QQ',
					name : 'QQ',
					sortable : true,
					width : 150
				}, {
					display : 'email',
					name : 'email',
					sortable : true,
					width : 150
				}],

				/**
				 * ��������
				 */
				searchitems : [{
					display : '����',
					name : 'linkmanName'
				}],
				sortorder : "ASC",
				title : '���пͻ���ϵ��'
			}
		}
	});
})(jQuery);
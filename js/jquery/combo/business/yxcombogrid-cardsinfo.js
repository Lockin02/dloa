/**
 * ���Կ�����combogrid
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_cardsinfo', {
		options : {
			hiddenId : 'id',
			nameCol : 'cardNo',
			gridOptions : {
				model : 'cardsys_cardsinfo_cardsinfo&action=listJson1',
				// ��
				colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					}, {
						display : '����',
						name : 'cardNo',
						sortable : true,
						width : 80
					},{
			            name : 'pinNo',
			            display : '����',
			            sortable : true,
						width : 70
			        } ,{
			            name : 'openerName',
			            display : '������',
			            sortable : true,
						width : 70
			        }, {
			            name : 'cardTypeName',
			            display : '����',
			            sortable : true,
						width : 70
			        }, {
						name : 'location',
						display : '������',
						sortable : true,
						width : 70
					}, {
						name : 'packageType',
						display : '�ײ�',
						sortable : true,
						width : 70
					}, {
						name : 'ratesOf',
						display : '�ʷ�����',
						sortable : true,
						width : 150
					}
				],
				/**
				 * ��������
				 */
				searchitems : [{
					display : '����',
					name : 'cardNoSearch'
				}],
				pageSize : 10,
				sortorder : "ASC",
				title : '���Կ�'
			}
		}
	});
})(jQuery);
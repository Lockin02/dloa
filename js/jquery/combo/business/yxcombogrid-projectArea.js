/**
 * ������������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_projectArea', {
		options : {
			hiddenId : 'id',
			nameCol : 'area',
			gridOptions : {
				model : 'engineering_device_esmdevice',
				action : 'projectAreaJson',
				// ��
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							display : '����',
							name : 'Name',
							sortable : true
						}, {
							display : '�������',
							name : 'amount',
							sortable : true
						}, {
							display : '��������',
							name : 'borrowNum',
							sortable : true
						}, {
							display : 'ʣ������',
							name : 'surplus',
							sortable : true
						}, {
				            name : 'del',
				            display : 'del',
				            sortable : true,
							width : 150,
							hide : true
				        }],

				/**
				 * ��������
				 */
				searchitems : [{
					display : '����',
					name : 'name'
				}],
				pageSize : 10,
				sortorder : "ASC",
				title : ''
			}
		}
	});
})(jQuery);
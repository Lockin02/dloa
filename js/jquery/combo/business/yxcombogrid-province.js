/**
 * ʡ�ݱ�����
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_province', {
				options : {
					hiddenId : 'provinceId',
					nameCol : 'provinceName',
					width:550,
					gridOptions : {
						model : 'system_procity_province',
						colModel : [{

									display : 'ʡ������	',
									name : 'provinceName',
									sortable : true,
									width : 200
								}, {
									display : 'ʡ�ݱ��	',
									name : 'provinceCode',
									sortable : true,
									width : 200,
									hide : true
								}],

						/**
						 * ��������
						 */
						searchitems : [{
									display : 'ʡ������',
									name : 'provinceName'
								}],
						sortorder : "ASC",
						title : 'ʡ������'
					}
				}
			});

})(jQuery);

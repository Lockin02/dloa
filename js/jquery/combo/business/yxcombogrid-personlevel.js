/**
 * Ԥ����Ŀ����combogrid
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_personlevel', {
		options : {
			hiddenId : 'id',
			nameCol : 'personLevel',
			gridOptions : {
				model : 'hr_basicinfo_level',
				// ��
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					display : '��Ա�ȼ�',
					name : 'personLevel',
					sortable : true
				},{
					name : 'remark',
					display : '��ע',
					sortable : true,
					width : 150
				}],

				/**
				 * ��������
				 */
				searchitems : [{
					display : '��Ա�ȼ�',
				 	name : 'personLevel'
				}],
				pageSize : 10,
				sortorder : "ASC",
				sortname : "personLevel",
				title : '��Ա�ȼ�'
			}
		}
	});
 })(jQuery);
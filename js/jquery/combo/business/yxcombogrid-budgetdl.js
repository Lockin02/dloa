/**
 * Ԥ����Ŀ����combogrid
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_budgetdl', {
		options : {
			hiddenId : 'id',
			nameCol : 'budgetName',
			isFocusoutCheck : false,
			gridOptions : {
				model : 'engineering_baseinfo_budget',
				action : 'pageJsonDL',
				isTitle : true,
				// ��
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							display : 'Ԥ������',
							name : 'budgetName',
							sortable : true,
							width : 300
						}, {
							display : '�ϼ�id',
							name : 'parentId',
							sortable : true,
							hide : true
						}, {
							display : '��������',
							name : 'parentName',
							sortable : true,
							width : 200
						}],

				/**
				 * ��������
				 */
				searchitems : [{
					display : 'Ԥ������',
					name : 'budgetNameDLSearch'
				},{
					display : '��������',
					name : 'parentNameDLSearch'
				}],
				pageSize : 10,
				sortorder : "ASC",
				sortname : "budgetCode",
				title : 'Ԥ����Ŀ'
			}
		}
	});
})(jQuery);
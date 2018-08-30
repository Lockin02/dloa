/**
 * 预算项目下拉combogrid
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
				// 表单
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							display : '预算名称',
							name : 'budgetName',
							sortable : true,
							width : 300
						}, {
							display : '上级id',
							name : 'parentId',
							sortable : true,
							hide : true
						}, {
							display : '所属分类',
							name : 'parentName',
							sortable : true,
							width : 200
						}],

				/**
				 * 快速搜索
				 */
				searchitems : [{
					display : '预算名称',
					name : 'budgetNameDLSearch'
				},{
					display : '所属分类',
					name : 'parentNameDLSearch'
				}],
				pageSize : 10,
				sortorder : "ASC",
				sortname : "budgetCode",
				title : '预算项目'
			}
		}
	});
})(jQuery);
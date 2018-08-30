/**
 * 预算项目下拉combogrid
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_personlevel', {
		options : {
			hiddenId : 'id',
			nameCol : 'personLevel',
			gridOptions : {
				model : 'hr_basicinfo_level',
				// 表单
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					display : '人员等级',
					name : 'personLevel',
					sortable : true
				},{
					name : 'remark',
					display : '备注',
					sortable : true,
					width : 150
				}],

				/**
				 * 快速搜索
				 */
				searchitems : [{
					display : '人员等级',
				 	name : 'personLevel'
				}],
				pageSize : 10,
				sortorder : "ASC",
				sortname : "personLevel",
				title : '人员等级'
			}
		}
	});
 })(jQuery);
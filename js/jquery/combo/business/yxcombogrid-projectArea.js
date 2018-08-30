/**
 * 工程区域下拉
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_projectArea', {
		options : {
			hiddenId : 'id',
			nameCol : 'area',
			gridOptions : {
				model : 'engineering_device_esmdevice',
				action : 'projectAreaJson',
				// 表单
				colModel : [{
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							display : '名称',
							name : 'Name',
							sortable : true
						}, {
							display : '库存总数',
							name : 'amount',
							sortable : true
						}, {
							display : '借用数量',
							name : 'borrowNum',
							sortable : true
						}, {
							display : '剩余数量',
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
				 * 快速搜索
				 */
				searchitems : [{
					display : '名称',
					name : 'name'
				}],
				pageSize : 10,
				sortorder : "ASC",
				title : ''
			}
		}
	});
})(jQuery);
/**
 * 下拉公司信息
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_branch', {
		options : {
			hiddenId : 'NamePT',
			nameCol : 'NameCN',
			valueCol : 'NamePT',
			gridOptions : {
				title : '公司信息',
				showcheckbox : false,
				model : 'deptuser_branch_branch',
				// 列信息
				colModel : [{
						display : 'id',
						name : 'id',
						hide : true
					}, {
						display : '公司名称',
						name : 'NameCN'
					}, {
						display : '公司编码',
						name : 'NamePT'
					}
				],
				// 快速搜索
//				searchitems : [{
//						display : '区域名称',
//						name : 'agencyName'
//					}
//				],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
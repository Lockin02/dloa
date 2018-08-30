/**
 * 下拉客户表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_agency', {
		options : {
			hiddenId : 'agencyCode',
			nameCol : 'agencyName',
			valueCol : 'agencyCode',
			gridOptions : {
				showcheckbox : false,
				model : 'asset_basic_agency',
				// 列信息
				colModel : [{
						display : '区域名称',
						name : 'agencyName',
						width : '80'
					}, {
						display : '区域编码',
						name : 'agencyCode',
						width : '70'
					}, {
						display : '区域负责人',
						name : 'chargeName',
						width : '80'
					}, {
						display : '备注',
						name : 'remark'
					}
				],
				// 快速搜索
				searchitems : [{
						display : '区域名称',
						name : 'agencyName'
					}
				],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
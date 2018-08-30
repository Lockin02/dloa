/**
 *下拉资产卡片表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_assetinfo', {
		options : {
			isFocusoutCheck : false,
			gridOptions : {
				showcheckbox : false,
				model : 'asset_assetcard_assetcard',
				// 列信息
				colModel : [{
						display : '资产名称',
						name : 'assetName',
						width : '200'
					},{
						display : '手机频段',
						name : 'mobileBand',
						width : '80'
					},{
						display : '手机网络',
						name : 'mobileNetwork',
						width : '80'
					}
				],
				// 快速搜索
				searchitems : [{
						display : '资产名称',
						name : 'assetName'
					},{
						display : '手机频段',
						name : 'mobileBand'
					},{
						display : '手机网络',
						name : 'mobileNetwork'
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
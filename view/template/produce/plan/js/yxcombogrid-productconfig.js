/**
 * 生产配置物料基本信息下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_productconfig', {
		options : {
			hiddenId : 'productId',
			nameCol : 'productCode',
			gridOptions : {
				showcheckbox : false,
				model : 'produce_task_configproduct',
				action : 'pageJson',

				// 列信息
				colModel : [{
					display : '配置编码',
					name : 'productCode',
					width : 80
				},{
					display : '配置名称',
					name : 'productName',
					width : 180
				},{
					display : '任务数量',
					name : 'num',
					width : 60
				},{
					display : '已下达数量',
					name : 'planNum',
					width : 60
				}],

				// 快速搜索
				searchitems : [{
					display : '配置编码',
					name : 'productCode'
				},{
					display : '配置名称',
					name : 'productName'
				}],

				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "DESC"
			}
		}
	});
})(jQuery);
/**
 * 生产任务物料基本信息下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_product2', {
		options : {
			hiddenId : 'productId',
			nameCol : 'productCode',
			gridOptions : {
				showcheckbox : false,
				model : 'produce_apply_produceapplyitem',
				action : 'productJson',
				param : {
					canOrder : true,
					state : 0
				},

				// 列信息
				colModel : [{
					display : '物料编码',
					name : 'productCode',
					width : 80
				},{
					display : '物料名称',
					name : 'productName',
					width : 180
				},{
					display : '申请数量',
					name : 'produceNum',
					width : 60
				},{
					display : '已下达数量',
					name : 'exeNum',
					width : 60
				},{
					display : '规格型号',
					name : 'pattern',
					width : 80
				},{
					display : '单位',
					name : 'unitName'
				}],

				// 快速搜索
				searchitems : [{
					display : '物料编码',
					name : 'productCode'
				},{
					display : '物料名称',
					name : 'productName'
				},{
					display : '规格型号',
					name : 'pattern'
				}],

				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "DESC"
			}
		}
	});
})(jQuery);
/**
 * 产品配置组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_produceConf', {
		options : {
			hiddenId : 'id',
			nameCol : 'produceName',
			width : 400,
			isFocusoutCheck : false,
			gridOptions : {
				showcheckbox : false,
				model : 'manufacture_basic_produceconfiguration',
				param : {
					isEnable : '是'
				},
				//列信息
				colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'produceName',
					display : '产品名称',
					width : 180,
					sortable : true
				},{
					name : 'createName',
					display : '录入人',
					width : 80,
					sortable : true
				},{
					name : 'createTime',
					display : '录入时间',
					width : 120,
					sortable : true
				}],

				// 快速搜索
				searchitems : [{
					display : '产品名称',
					name : 'produceName'
				}],

				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
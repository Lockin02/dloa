/**
 * 下拉车辆信息
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_carinfo', {
		options : {
			hiddenId : 'carId',
			nameCol : 'carNo',
			gridOptions : {
				showcheckbox : true,
				model : 'carrental_carinfo_carinfo',
				// 列信息
				colModel : [{
						display : '车辆id',
						name : 'carId',
						hide: true
					},  {
						display : '车牌号',
						name : 'carNo',
			  			width : 70
					},{
						display : '车型',
        				name : 'carTypeName',
			  			width : 70
                  	},{
			        	name : 'brand',
			  			display : '品牌',
			  			sortable : true,
			  			width : 80
			        },{
			        	name : 'displacement',
			  			display : '排量',
			  			sortable : true,
			  			width : 60
			        },{
						name : 'owners',
			  			display : '车主',
			  			sortable : true,
			  			width : 70
			        },{
						name : 'driver',
			  			display : '司机',
			  			sortable : true,
			  			width : 70
			        },{
						name : 'linkPhone',
			  			display : '联系方式',
			  			sortable : true,
			  			width : 80
			        }],
				// 快速搜索
				searchitems : [{
					display : '车牌号',
					name : 'carNo'
				}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
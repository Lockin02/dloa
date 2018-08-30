/**
 * 下拉客户表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_carrecords', {
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
									name : 'carNo'
								},{
									display : '车辆型号',
                    				name : 'carType'
                              	},{
                              		display : '限载人数',
                    				name : 'limitedSeating'
                              },{
									display : '单位id',
									name : 'unitsId',
									hide : true
								}, {
									display : '单位名称',
									name : 'unitsName',
									width : 130
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
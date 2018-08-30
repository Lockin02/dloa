/**
 * 下拉车辆供应商组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_outsuppvehicle', {
		options : {
			hiddenId : 'suppId',
			nameCol : 'suppName',
			width : 400,
			isFocusoutCheck : true,
			gridOptions : {
				showcheckbox : false,
				model : 'outsourcing_outsourcessupp_vehiclesupp',
				param : {
					"suppLevelNeq" : '0'
				},
				//列信息
				colModel : [{
 					display : 'id',
 					name : 'id',
 					sortable : true,
 					hide : true
				},{
        			name : 'suppCode',
  					display : '供应商编号',
  					width:80,
  					sortable : true
				},{
					name : 'suppName',
  					display : '外包供应商',
  					width:260,
  					sortable : true
				},{
					name : 'linkmanName',
  					display : '联系人姓名',
  					width:80,
  					sortable : true
				},{
					name : 'linkmanPhone',
  					display : '联系人电话',
  					width:100,
  					sortable : true
				}],
				// 快速搜索
				searchitems : [{
					display : '外包供应商',
					name : 'suppName'
				}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
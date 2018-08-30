/**
 * 物料基本信息下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_area', {
		options : {
			hiddenId : 'areaPrincipalId',
			nameCol : 'areaName',
			//isFocusoutCheck:false,
			gridOptions : {
				showcheckbox : false,
				model : 'system_region_region',
				action : 'pageJson',
				param : {'isStart':'0'},
				pageSize : 10,
				// 列信息
				colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'areaPrincipal',
                  					display : '区域负责人',
                  					sortable : true

                              },{
                    					name : 'areaName',
                  					display : '区域名称',
                  					sortable : true
                              },{
                    					name : 'areaCode',
                  					display : '区域编码',
                  					sortable : true,
                  					hide : true
                              },{
                    					name : 'areaPrincipalId',
                  					display : '区域负责人Id',
                  					sortable : true,
                  					hide : true
                              },{
                    					name : 'remark',
                  					display : '备注',
                  					sortable : true,
                  					width : 300,
                  					hide : false
                              }],
				// 快速搜索
				searchitems : [{
							display : '区域名称',
							name : 'areaName'
						}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
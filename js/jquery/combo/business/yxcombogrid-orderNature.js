/**
 * 合同属性下拉选择
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_orderNature', {
		options : {
			hiddenId : 'dataCode',
			nameCol : 'parentName',
			valueCol : 'dataCode',
			gridOptions : {
				showcheckbox : false,
				model : 'system_datadict_datadict',
				action : 'orderNaturePageJson',
				pageSize : 10,
				// 列信息
				colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'parentName',
                  					display : '所属类型',
                  					sortable : true

                              },{
                    					name : 'dataName',
                  					display : '属性名称',
                  					sortable : true
                              },{
                    					name : 'dataCode',
                  					display : '属性名称',
                  					sortable : true,
                  					hide : true
                              }],
				// 快速搜索
				searchitems : [{
							display : '属性名称',
							name : 'dataName'
						}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
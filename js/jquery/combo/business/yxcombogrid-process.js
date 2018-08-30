/**
 * 下拉工序组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_process', {
		options : {
			hiddenId : 'processId',
			nameCol : 'templateName',
			width : 400,
			isFocusoutCheck : true,
			gridOptions : {
				showcheckbox : false,
				model : 'manufacture_basic_process',
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
					name : 'templateName',
					display : '模板名称',
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
					display : '模板名称',
					name : 'templateName'
				}],

				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);
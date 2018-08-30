/**
 * 下拉租车申请组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_rentalcar', {
		options : {
			hiddenId : 'id',
			nameCol : 'formCode',
			width : 600,
			isFocusoutCheck : false,
			gridOptions : {
				showcheckbox : false,
				model : 'outsourcing_vehicle_rentalcar',
				param : {
					"ExaStatus" : "完成"
				},
				//列信息
				colModel : [{
 					display : 'id',
 					name : 'id',
 					sortable : true,
 					hide : true
				},{
        			name : 'formCode',
  					display : '租车申请单号',
  					width:180,
  					sortable : true
				},{
					name : 'projectName',
  					display : '项目名称',
  					width:260,
  					sortable : true
				},{
        			name : 'createName',
  					display : '申请人',
  					width:80,
  					sortable : true
				}],
				// 快速搜索
				searchitems : [{
					display : '申请单号',
					name : 'formCode'
				}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "DESC"
			}
		}
	});
})(jQuery);
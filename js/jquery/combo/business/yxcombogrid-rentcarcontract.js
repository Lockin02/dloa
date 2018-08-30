/**
 * 下拉租车合同信息
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_rentcarcontract', {
		options : {
			hiddenId : 'id',
			nameCol : 'orderCode',
			width : 600,
			isFocusoutCheck : false,
			gridOptions : {
				showcheckbox : false,
				model : 'outsourcing_contract_rentcar',
				action : 'getRentcarInformation',
				//列信息
				colModel : [{
 					display : 'id',
 					name : 'id',
 					sortable : true,
 					hide : true
				},{
        			name : 'orderCode',
  					display : '租车合同编号',
  					width:100,
  					sortable : true
				},{
					name : 'projectName',
  					display : '项目名称',
  					width:275,
  					sortable : true
				},{
					name : 'suppName',
  					display : '供应商名称',
  					width:150,
  					sortable : true
				}],
				// 快速搜索
				searchitems : [{
					display : '租车合同编号',
					name : 'orderCode'
				}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "DESC"
			}
		}
	});
})(jQuery);
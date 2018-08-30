/**
 * 下拉采购合同表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_purchcontract', {
				options : {
					hiddenId : 'id',
					nameCol : 'hwapplyNumb',
					gridOptions : {
						model : 'purchase_contract_purchasecontract',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true,
									width:130
								},{
									display : '订单编号',
									name : 'hwapplyNumb',
									width:100
								},{
									display : '采购员',
									name : 'sendName',
									width:150
								},{
									display : '供应商名称',
									name : 'suppName',
									width:150
								},{
									display : '供应商Id',
									name : 'suppId',
									width:150,
									hide : true
								}],
						// 快速搜索
						searchitems : [{
									display : '订单编号',
									name : 'hwapplyNumb'
								}],
						// 默认搜索字段名
						sortname : "hwapplyNumb",
						// 默认搜索顺序
						sortorder : "DESC"
					}
				}
			});
})(jQuery);
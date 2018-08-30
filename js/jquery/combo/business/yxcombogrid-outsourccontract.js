/**
 * 下拉采购合同表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_outsourccontract', {
				options : {
					hiddenId : 'id',
					nameCol : 'outContractCode',
					gridOptions : {
						model : 'contract_outsourcing_outsourcing',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									hide : true,
									width:130
								},{
									display : '鼎利合同编号',
									name : 'orderCode',
									width:100
													},{
						            name : 'orderName',
						            display : '合同名称',
						            sortable : true,
						            width : 130
						        },{
						            name : 'outContractCode',
						            display : '外包合同号',
						            sortable : true,
						            width : 130
						        },{
						            name : 'signCompanyName',
						            display : '签约公司',
						            sortable : true,
						            width : 130
						        }],
						// 快速搜索
						searchitems : [{
									display : '外包合同号',
									name : 'outContractCode'
								}],
						// 默认搜索字段名
						sortname : "outContractCode",
						// 默认搜索顺序
						sortorder : "DESC"
					}
				}
			});
})(jQuery);
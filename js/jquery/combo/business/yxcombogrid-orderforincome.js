/**
 * 下拉订单表格组件
 * create by can
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_orderforincome', {
		options : {
			hiddenId : 'id',
			nameCol : 'orderCode',
			gridOptions : {
			model : 'projectmanagent_order_order',
			action : 'orderForIncomePj',
			// 列信息
			colModel :
			[
			  {
 					display : 'id',
 					name : 'id',
 					sortable : true,
 					hide : true
			  },{
					name : 'orderCode',
  					display : '鼎利合同号',
  					sortable : true
              },{
					name : 'orderTempCode',
  					display : '临时合同号',
  					sortable : true
              },{
					name : 'customerName',
  					display : '客户名称',
  					hide : true
              },{
					name : 'customerType',
  					display : '客户类型',
  					datacode : 'KHLX',
  					hide : true
              },{
					name : 'orderMoney',
  					display : '合同金额',
  					sortable : true,
  					process : function(v){
  						return moneyFormat2(v);
  					},
  					width : 80
              },{
					name : 'invoiceMoney',
  					display : '开票金额',
  					process : function(v){
  						return moneyFormat2(v);
  					},
  					width : 80
              },{
					name : 'unInvoiceMoney',
  					display : '待开票金额',
  					process : function(v){
  						return moneyFormat2(v);
  					},
  					width : 80
              },{
					name : 'incomeMoney',
  					display : '到款金额',
  					process : function(v){
  						return moneyFormat2(v);
  					},
  					width : 80
              },{
					name : 'unIncomeMoney',
  					display : '未收金额',
  					process : function(v){
  						return moneyFormat2(v);
  					},
  					width : 80
              },{
					name : 'prinvipalName',
  					display : '合同负责人',
  					sortable : true,
  					width : 80
              },{
					name : 'areaPrincipal',
  					display : '区域负责人',
  					sortable : true,
  					width : 80
              },{
					name : 'areaName',
  					display : '合同区域',
  					sortable : true,
  					width : 80
              }
          ],
				// 默认搜索字段名
				sortname : "updatetime",
				// 默认搜索顺序
				sortorder : "DESC"
			}
		}
	});
})(jQuery);
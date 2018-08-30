function getQueryStringPay(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	var r = window.location.search.substr(1).match(reg);
	if (r != null)
		return unescape(r[2]);
	return null;
}
var show_page = function(page) {
	$("#arrivalContractGrid").yxgrid("reload");
};
$(function() {
			var gdbtable = getQueryStringPay('gdbtable');
			$("#arrivalContractGrid").yxgrid({
				model : 'purchase_arrival_arrival',
				action:'contractPageJson',
               	title : '收料通知单 -- 采购订单 :' + $("#objCode").val(),
               	isToolBar:false,
               	showcheckbox:false,
        		param : {"purchaseId" : $("#objId").val(),"gdbtable" : gdbtable},
				isOpButton:false,
				bodyAlign:'center',
						//列信息
					colModel : [ {
										name : 'sequence',
										display : '物料编号',
										width:80
									}, {
										name : 'productName',
										width : 200,
										display : '物料名称'
									},{
										name : 'batchNum',
										display : "批次号"
									},{
										name : 'arrivalDate',
										display : "收料日期",
										width:70
									},{
										name : 'arrivalNum',
										display : "收料数量",
										width:60
									},{
										name : 'storageNum',
										display : "已入库数量",
										width:60
									},
									{
     								display : 'id',
     								name : 'id',
     								sortable : true,
     								hide : true
						        },{
                					name : 'arrivalCode',
                  					display : '收料单号',
                  					sortable : true,
                  					width:80
                              },{
                    					name : 'state',
                  					display : '收料单状态',
                  					sortable : true,
									width:80,
									process : function(v, row) {
										if (row.state == '0') {
											return "未执行";
										} else {
											return "已执行";
										}
									}
                              },{
                    					name : 'supplierName',
                  					display : '供应商名称',
                  					sortable : true,
      								width:150

                              },{
                    					name : 'purchManName',
                  					display : '采购员',
                  					sortable : true,
									width:60
                              },{
                    					name : 'deliveryPlace',
                  					display : '交货地点',
                  					sortable : true,
										width:80
                              },{
                    					name : 'stockName',
                  					display : '收料仓库名称',
                  					sortable : true
                              }],
				        buttonsEx : [
				        	{
								text : '付款申请历史',
								icon : 'view',
								action : function(row) {
									location="?model=finance_payablesapply_payablesapply&action=toHistory"
										+ "&obj[objId]=" + $("#objId").val()
									    + "&obj[objCode]=" + $("#objCode").val()
									    + "&obj[objType]=" + $("#objType").val()
									    + "&obj[supplierId]=" + $("#supplierId").val()
									    + "&obj[supplierName]=" + $("#supplierName").val()
									    + "&gdbtable=" + gdbtable
									    + "&skey=" + $("#skey").val()
								}
							},{
								text : '付款记录历史',
								icon : 'view',
								action : function(row) {
									location="?model=finance_payables_payables&action=toHistory"
										+ "&obj[objId]=" + $("#objId").val()
									    + "&obj[objCode]=" + $("#objCode").val()
									    + "&obj[objType]=" + $("#objType").val()
									    + "&obj[supplierId]=" + $("#supplierId").val()
									    + "&obj[supplierName]=" + $("#supplierName").val()
									    + "&gdbtable=" + gdbtable
									    + "&skey=" + $("#skey").val()
								}
							}
							,{
								text : '采购发票记录',
								icon : 'view',
								action : function(row) {
									location="?model=finance_invpurchase_invpurchase&action=toHistory"
										+ "&obj[objId]=" + $("#objId").val()
									    + "&obj[objCode]=" + $("#objCode").val()
									    + "&obj[objType]=" + $("#objType").val()
									    + "&obj[supplierId]=" + $("#supplierId").val()
									    + "&obj[supplierName]=" + $("#supplierName").val()
									    + "&gdbtable=" + gdbtable
									    + "&skey=" + $("#skey").val()
								}
							}
							,{
								text : '收料记录',
								icon : 'edit',
								action : function(row) {
									location="?model=purchase_arrival_arrival&action=toListByOrder"
										+ "&obj[objId]=" + $("#objId").val()
									    + "&obj[objCode]=" + $("#objCode").val()
									    + "&obj[objType]=" + $("#objType").val()
									    + "&obj[supplierId]=" + $("#supplierId").val()
									    + "&obj[supplierName]=" + $("#supplierName").val()
									    + "&gdbtable=" + gdbtable
									    + "&skey=" + $("#skey").val()
								}
							}
				        ],
						//扩展右键菜单
						menusEx : [
						{
							text : '查看',
							icon : 'view',
							action :function(row,rows,grid) {
								if(row){
									showThickboxWin("?model=purchase_arrival_arrival&action=init&perm=view&id="+row.id+"&skey="+row['skey_']+"&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800"+"&gdbtable=" + gdbtable);
								}else{
									alert("请选中一条数据");
								}
							}

						}
						],
							// 默认搜索顺序
							sortorder : "DESC",
							sortname:"updateTime"
 		});
 });
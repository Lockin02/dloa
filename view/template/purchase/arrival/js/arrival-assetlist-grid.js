var show_page = function(page) {
	$("#arrivalAssetGrid").yxsubgrid("reload");
};
$(function() {
	$("#arrivalAssetGrid")
			.yxsubgrid(
					{
						model : 'purchase_arrival_arrival',
						title : '资产收料通知单',
						// action:'myPageJson',
						param : {
							'arrivalType' : 'asset'
						},
						isEditAction : false,
						isEditAction : false,
						isViewAction : false,
						isDelAction : false,
						isAddAction : false,
						showcheckbox : false,
						// 列信息
						colModel : [ {
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						}, {
							name : 'arrivalCode',
							display : '收料单号',
							sortable : true,
							width : 180
						}, {
							name : 'state',
							display : '收料通知单状态',
							sortable : true,
							process : function(v, row) {
								if (row.state == '0') {
									return "未执行";
								} else if (row.state == '4'){
									return "部分执行";
								}else if (row.state == '2'){
									return "已执行";
								}
							}
						}, {
							name : 'purchaseId',
							display : '订单id',
							hide : true
						}, {
							name : 'purchaseCode',
							display : '采购订单编号',
							sortable : true,
							width : 200
						}, {
							name : 'supplierName',
							display : '供应商名称',
							sortable : true,
							width : 200
						}, {
							name : 'supplierId',
							display : '供应商id',
							hide : true
						}, {
							name : 'purchManId',
							display : '采购员ID',
							hide : true
						}, {
							name : 'purchManName',
							display : '采购员',
							sortable : true,
							width : 120
						}, {
							name : 'purchMode',
							display : '采购方式',
							hide : true,
							datacode : 'cgfs'
						}, {
							name : 'stockId',
							display : '收料仓库Id',
							hide : true
						}, {
							name : 'stockName',
							display : '收料仓库名称',
							sortable : true,
							width : 120
						} ],
						// 主从表格设置
						subGridOptions : {
							url : '?model=purchase_arrival_equipment&action=pageJson',
							param : [ {
								paramId : 'arrivalId',
								colId : 'id'
							} ],
							colModel : [ {
								name : 'sequence',
								display : '物料编号'
							}, {
								name : 'productName',
								width : 200,
								display : '物料名称'
							}, {
								name : 'batchNum',
								display : "批次号"
							}, {
								name : 'arrivalDate',
								display : "收料日期"
							}, {
								name : 'month',
								display : "月份"
							}, {
								name : 'oldArrivalNum',
								display : "收料数量"
							}, {
								name : 'storageNum',
								display : "已入库数量"
							} , {
								name : 'deliveredNum',
								display : "退料数量"
							} ]
						},
						// 扩展右键菜单
						menusEx : [
								{
									name : 'view',
									text : '查看',
									icon : 'view',
									action : function(row, rows, grid) {
										if (row) {
											showThickboxWin("?model=purchase_arrival_arrival&action=init&perm=view&id="
													+ row.id
													+ "&skey="
													+ row['skey_']
													+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900");
										} else {
											alert("请选中一条数据");
										}
									}
								},
								{
									name : 'edit',
									text : '下推验收单',
									icon : 'edit',
									showMenuFn : function(row) {
										if (row.state == 0||row.state == 4) {
											return true;
										}
										return false;
									},
									action : function(row, rows, grid) {
										if (row) {
											showThickboxWin("?model=asset_purchase_receive_receive&action=toArrivalPush&arrivalId="
													+ row.id
													+ "&skey="
													+ row['skey_']
													+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900");
										} else {
											alert("请选中一条数据");
										}
									}
								},
								{
									name : 'edit',
									text : '下推退料单',
									icon : 'edit',
									showMenuFn : function(row) {
//										if (row.state == 0) {
											return true;
//										}
//										return false;
									},
									action : function(row, rows, grid) {
										if (row) {
											showThickboxWin("?model=purchase_delivered_delivered&action=toPushByArrival&arrivalId="
													+ row.id
													+ "&skey="
													+ row['skey_']
													+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900");
										} else {
											alert("请选中一条数据");
										}
									}
//								},
//								{
//									name : 'edit',
//									text : '收料确认',
//									icon : 'edit',
//									showMenuFn : function(row) {
//										if (row.state == 0) {
//											return true;
//										}
//										return false;
//									},
//									action : function(row, rows, grid) {
//										if (row) {
//											showThickboxWin("?model=purchase_arrival_arrival&action=toConfAsset&id="
//													+ row.id
//													+ "&skey="
//													+ row['skey_']
//													+ "&placeValuesBefore&TB_iframe=true&modal=false&height=700&width=900");
//										} else {
//											alert("请选中一条数据");
//										}
//									}
								},
									{
									    text:'关闭',
									    icon:'delete',
										showMenuFn : function(row) {
											if (row.state != 2) {
												return true;
											}
											return false;
										},
									    action:function(row,rows,grid){
									    	if(row){
									    		if(window.confirm("确认要关闭?")){
									    		     $.ajax({
									    		         type:"POST",
									    		         url:"?model=purchase_arrival_arrival&action=changStateClose",
									    		         data:{
									    		         	id:row.id
									    		         },
									    		         success:function(msg){
									    		            if(msg==1){
									    		                alert('关闭成功!');
									    		                show_page();
									    		            }
									    		         }
									    		     });
									    		}
									    	}
									    }
									} ],

						comboEx : [ {
							text : '收料通知单状态',
							key : 'state',
							data : [ {
								text : '未执行',
								value : '0'
							}, {
								text : '已执行',
								value : '2'
							} ]
						} ],
						searchitems : [ {
							display : '收料单号',
							name : 'arrivalCode'
						}, {
							display : '采购员',
							name : 'purchManName'
						}, {
							display : '供应商',
							name : 'supplierName'
						}, {
							display : '物料名称',
							name : 'productName'
						}, {
							display : '物料编号',
							name : 'sequence'
						} ],
						// 默认搜索顺序
						sortorder : "DESC",
						sortname : "updateTime"
					});
});
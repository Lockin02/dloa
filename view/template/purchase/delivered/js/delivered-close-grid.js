var show_page = function(page) {
	$("#deliveredCloseGrid").yxsubgrid("reload");
};
$(function() {
			$("#deliveredCloseGrid").yxsubgrid({
				model : 'purchase_delivered_delivered',
               	title : '已执行退料通知单',
               	isAddAction:false,
               	isDelAction:false,
               	isEditAction:false,
               	isViewAction:false,
               	showcheckbox:false,
               	param:{"state":"2"},
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'returnCode',
                  					display : '退料单号',
                  					sortable : true
                              },{
                    					name : 'state',
                  					display : '退料单状态',
                  					sortable : true,
									process : function(v, row) {
										if (row.state == '0') {
											return "未执行";
										} else if(row.state == '2') {
											return "已执行";
										}
									}
                              },{
                    					name : 'returnType',
                  					display : '退料类型',
                  					hide : true
                              },{
                    					name : 'sourceId',
                  					display : '关联id',
                  					hide : true
                              },{
                    					name : 'sourceCode',
                  					display : '关联编号',
                  					sortable : true
                              },{
                    					name : 'supplierName',
                  					display : '供应商名称',
                  					sortable : true
                              },{
                    					name : 'supplierId',
                  					display : '供应商id',
                  					hide : true
                              },{
                    					name : 'purchManId',
                  					display : '采购员ID',
                  					hide : true
                              },{
                    					name : 'purchManName',
                  					display : '采购员',
                  					sortable : true
                              },{
                    					name : 'stockId',
                  					display : '退料仓库Id',
                  					hide : true
                              },{
                    					name : 'stockName',
                  					display : '退料仓库名称',
                  					sortable : true
                              },{
                    					name : 'returnDate',
                  					display : '退料日期',
                  					sortable : true
                              }],
							// 主从表格设置
							subGridOptions : {
								url : '?model=purchase_delivered_equipment&action=pageJson',
								param : [{
											paramId : 'basicId',
											colId : 'id'
										}],
								colModel : [{
											name : 'productNumb',
											display : '物料编号'
										}, {
											name : 'productName',
											width : 200,
											display : '物料名称'
										},{
											name : 'batchNum',
											display : "批次号"
										},{
											name : 'deliveredNum',
											display : "退料数量"
										},{
											name : 'factNum',
											display : "实际入库数量"
										}]
							},
                              toAddConfig : {

									/**
									 * 新增表单默认宽度
									 */
									formWidth : 900,
									/**
									 * 新增表单默认高度
									 */
									formHeight : 500
								},
								// 扩展右键菜单
								menusEx : [{
									name : 'view',
									text : '查看',
									icon : 'view',
									action : function(row, rows, grid) {
										if (row) {
											showThickboxWin("?model=purchase_delivered_delivered&action=init&perm=view&id="
													+ row.id
													+"&skey="+row['skey_']
													+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
													+ 400 + "&width=" + 800);
										} else {
											alert("请选中一条数据");
										}
									}
								},{
									name : 'view',
									text : '审批情况',
									icon : 'view',
									showMenuFn : function(row) {
										if (row.ExaStatus == '打回' || row.ExaStatus == '完成'|| row.ExaStatus == '部门审批') {
											return true;
										}
										return false;
									},
									action : function(row, rows, grid) {
										if (row) {
											showThickboxWin("controller/common/readview.php?itemtype=oa_purchase_delivered&pid="
													+ row.id
													+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
										}
									}
								}],
							searchitems : [{
								display : '退料单号',
								name : 'returnCode'
							}, {
								display : '采购员',
								name : 'purchManName'
							},{
								display : '供应商',
								name : 'supplierName'
							},{
								display : '物料名称',
								name : 'productName'
							},{
								display : '物料编号',
								name : 'productNumb'
							}],
							// 默认搜索顺序
							sortorder : "DESC",
							sortname:"updateTime"
 		});
 });
var show_page = function(page) {
	$("#allocationitemGrid").yxgrid("reload");
};
$(function() {
			$("#allocationitemGrid").yxgrid({
				      model : 'stock_allocation_allocationitem',
               	title : '调拨单物料清单',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'mainId',
                  					display : '调拨单id',
                  					sortable : true
                              },{
                    					name : 'productId',
                  					display : '物料id',
                  					sortable : true
                              },{
                    					name : 'productCode',
                  					display : '物料编号',
                  					sortable : true
                              },{
                    					name : 'productName',
                  					display : '物料名称',
                  					sortable : true
                              },{
                    					name : 'pattern',
                  					display : '规格型号',
                  					sortable : true
                              },{
                    					name : 'unitName',
                  					display : '单位',
                  					sortable : true
                              },{
                    					name : 'aidUnit',
                  					display : '辅助单位',
                  					sortable : true
                              },{
                    					name : 'converRate',
                  					display : '换算率',
                  					sortable : true
                              },{
                    					name : 'batchNum',
                  					display : '批次',
                  					sortable : true
                              },{
                    					name : 'cost',
                  					display : '单位成本',
                  					sortable : true
                              },{
                    					name : 'subCost',
                  					display : '成本',
                  					sortable : true
                              },{
                    					name : 'allocatNum',
                  					display : '调拨数量',
                  					sortable : true
                              },{
                    					name : 'shelfLife',
                  					display : '保质期',
                  					sortable : true
                              },{
                    					name : 'prodDate',
                  					display : '生产（采购）日期',
                  					sortable : true
                              },{
                    					name : 'validDate',
                  					display : '有效期至',
                  					sortable : true
                              },{
                    					name : 'exportStockId',
                  					display : '调出仓库id',
                  					sortable : true
                              },{
                    					name : 'exportStockCode',
                  					display : '调出仓库代码',
                  					sortable : true
                              },{
                    					name : 'exportStockName',
                  					display : '调出仓库名称',
                  					sortable : true
                              },{
                    					name : 'importStockId',
                  					display : '调入仓库id',
                  					sortable : true
                              },{
                    					name : 'importStockCode',
                  					display : '调入仓库代码',
                  					sortable : true
                              },{
                    					name : 'importStockName',
                  					display : '调入仓库名称',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '备注',
                  					sortable : true
                              }]
 		});
 });
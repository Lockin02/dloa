var show_page = function(page) {
	$("#stockinitemGrid").yxgrid("reload");
};
$(function() {
			$("#stockinitemGrid").yxgrid({
				      model : 'stock_instock_stockinitem',
               	title : '入库单物料清单',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'mainId',
                  					display : '入库单id',
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
                    					name : 'inStockId',
                  					display : '收料仓库id',
                  					sortable : true
                              },{
                    					name : 'inStockCode',
                  					display : '收料仓库代码',
                  					sortable : true
                              },{
                    					name : 'inStockName',
                  					display : '收料仓库名称',
                  					sortable : true
                              },{
                    					name : 'contractId',
                  					display : '合同id',
                  					sortable : true
                              },{
                    					name : 'contractCode',
                  					display : '合同编号',
                  					sortable : true
                              },{
                    					name : 'contractName',
                  					display : '合同名称',
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
                    					name : 'price',
                  					display : '价格',
                  					sortable : true
                              },{
                    					name : 'subPrice',
                  					display : '金额',
                  					sortable : true
                              },{
                    					name : 'storageNum',
                  					display : '应收数量',
                  					sortable : true
                              },{
                    					name : 'actNum',
                  					display : '实收数量',
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
                    					name : 'hookNumber',
                  					display : '已勾稽数量',
                  					sortable : true
                              },{
                    					name : 'hookAmount',
                  					display : '已勾稽金额',
                  					sortable : true
                              },{
                    					name : 'unHookNumber',
                  					display : '未勾稽数量',
                  					sortable : true
                              },{
                    					name : 'unHookAmount',
                  					display : '未勾稽金额',
                  					sortable : true
                              }]
 		});
 });
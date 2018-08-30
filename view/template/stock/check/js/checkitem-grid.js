var show_page = function(page) {
	$("#checkitemGrid").yxgrid("reload");
};
$(function() {
			$("#checkitemGrid").yxgrid({
				      model : 'stock_check_checkitem',
               	title : '盘点物料清单',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'checkId',
                  					display : '库存盘点id',
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
                    					name : 'billNum',
                  					display : '帐存数量',
                  					sortable : true
                              },{
                    					name : 'actNum',
                  					display : '实存数量',
                  					sortable : true
                              },{
                    					name : 'adjustNum',
                  					display : '调整数量',
                  					sortable : true
                              },{
                    					name : 'unitName',
                  					display : '单位',
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
                    					name : 'stockId',
                  					display : '仓库id',
                  					sortable : true
                              },{
                    					name : 'stockCode',
                  					display : '仓库代码',
                  					sortable : true
                              },{
                    					name : 'stockName',
                  					display : '仓库名称',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '备注',
                  					sortable : true
                              }]
 		});
 });
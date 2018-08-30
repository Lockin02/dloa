var show_page = function(page) {
	$("#systeminfoGrid").yxgrid("reload");
};
$(function() {
			$("#systeminfoGrid").yxgrid({
				      model : 'stock_stockinfo_systeminfo',
               	title : '仓存管理基础信息设置',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'salesStockId',
                  					display : '销售默认仓库id',
                  					sortable : true
                              },{
                    					name : 'salesStockName',
                  					display : '销售默认人仓库名称',
                  					sortable : true
                              },{
                    					name : 'salesStockCode',
                  					display : '销售默认仓库代码',
                  					sortable : true
                              },{
                    					name : 'packingStockId',
                  					display : '包装物默认仓库id',
                  					sortable : true
                              },{
                    					name : 'packingStockName',
                  					display : '包装物默认仓库名称',
                  					sortable : true
                              },{
                    					name : 'packingStockCode',
                  					display : '包装物默认仓库代码',
                  					sortable : true
                              }]
 		});
 });
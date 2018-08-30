var show_page = function(page) {
	$("#lockGrid").yxgrid("reload");
};
$(function() {
			$("#lockGrid").yxgrid({
				      model : 'stock_lock_lock',
               	title : '仓库锁定记录表',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'stockId',
                  					display : '锁定仓库id',
                  					sortable : true
                              },{
                    					name : 'inventoryId',
                  					display : '锁定库存id',
                  					sortable : true
                              },{
                    					name : 'lockNum',
                  					display : '锁定数量',
                  					sortable : true
                              },{
                    					name : 'lockType',
                  					display : '锁定类型',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '创建人',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '创建人id',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '创建日期',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '修改人',
                  					sortable : true
                              },{
                    					name : 'updateId',
                  					display : '修改人id',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '修改日期',
                  					sortable : true
                              }]
 		});
 });
var show_page = function(page) {
	$("#protaskequGrid").yxgrid("reload");
};
$(function() {
			$("#protaskequGrid").yxgrid({
				      model : 'produce_protask_protaskequ',
               	title : '生产任务清单',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'mainId',
                  					display : '任务id',
                  					sortable : true
                              },{
                    					name : 'mainCode',
                  					display : '任务编号',
                  					sortable : true
                              },{
                    					name : 'relDocCode',
                  					display : '业务编号',
                  					sortable : true
                              },{
                    					name : 'relDocName',
                  					display : '业务名称',
                  					sortable : true
                              },{
                    					name : 'productId',
                  					display : '产品id',
                  					sortable : true
                              },{
                    					name : 'productNo',
                  					display : '产品编号',
                  					sortable : true
                              },{
                    					name : 'productName',
                  					display : '产品名称',
                  					sortable : true
                              },{
                    					name : 'productModel',
                  					display : '产品类型',
                  					sortable : true
                              },{
                    					name : 'number',
                  					display : '数量',
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
                    					name : 'referDate',
                  					display : '计划交货日期',
                  					sortable : true
                              },{
                    					name : 'license',
                  					display : '配置',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '备注',
                  					sortable : true
                              }]
 		});
 });
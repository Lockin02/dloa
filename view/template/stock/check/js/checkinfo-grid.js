var show_page = function(page) {
	$("#checkinfoGrid").yxgrid("reload");
};
$(function() {
			$("#checkinfoGrid").yxgrid({
				      model : 'stock_check_checkinfo',
               	title : '盘点基本信息',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'docCode',
                  					display : '单据编号',
                  					sortable : true
                              },{
                    					name : 'checkType',
                  					display : '盘点类型',
                  					sortable : true
                              },{
                    					name : 'stockId',
                  					display : '仓库id',
                  					sortable : true
                              },{
                    					name : 'stockCode',
                  					display : '仓库编号',
                  					sortable : true
                              },{
                    					name : 'stockName',
                  					display : '仓库名称',
                  					sortable : true
                              },{
                    					name : 'auditStatus',
                  					display : '盘点状态',
                  					sortable : true
                              },{
                    					name : 'dealUserId',
                  					display : '经办人id',
                  					sortable : true
                              },{
                    					name : 'dealUserName',
                  					display : '经办人',
                  					sortable : true
                              },{
                    					name : 'auditUserName',
                  					display : '审核人',
                  					sortable : true
                              },{
                    					name : 'auditUserId',
                  					display : '审核人id',
                  					sortable : true
                              },{
                    					name : 'ExaStatus',
                  					display : '审批状态',
                  					sortable : true
                              },{
                    					name : 'ExaDT',
                  					display : '审批时间',
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
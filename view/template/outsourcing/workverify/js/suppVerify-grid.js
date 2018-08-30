var show_page = function(page) {	   $("#suppVerifyGrid").yxgrid("reload");};
$(function() {			$("#suppVerifyGrid").yxgrid({				      model : 'outsourcing_workverify_suppVerify',
               	title : '外包供应商工作量确认单',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'parentCode',
                  					display : '确认单编号',
                  					sortable : true
                              }                    ,{
                    					name : 'formCode',
                  					display : '单据编号',
                  					sortable : true
                              },{
                    					name : 'formDate',
                  					display : '单据时间',
                  					sortable : true
                              },{
                    					name : 'beginDate',
                  					display : '周期开始日期',
                  					sortable : true
                              },{
                    					name : 'endDate',
                  					display : '周期结束日期',
                  					sortable : true
                              }                    ,{
                    					name : 'outsourceSuppCode',
                  					display : '外包公司编号',
                  					sortable : true
                              },{
                    					name : 'outsourceSupp',
                  					display : '外包公司',
                  					sortable : true
                              }                    ,{
                    					name : 'projectCode',
                  					display : '项目编号',
                  					sortable : true
                              },{
                    					name : 'projecttName',
                  					display : '项目名称',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '备注',
                  					sortable : true
                              },{
                    					name : 'status',
                  					display : '状态',
                  					sortable : true
                              },{
                    					name : 'statusName',
                  					display : '状态名称',
                  					sortable : true
                              },{
                    					name : 'closeDate',
                  					display : '关闭日期',
                  					sortable : true
                              },{
                    					name : 'closeDesc',
                  					display : '关闭说明',
                  					sortable : true
                              },{
                    					name : 'ExaStatus',
                  					display : '审批状态',
                  					sortable : true
                              },{
                    					name : 'ExaDT',
                  					display : '审批日期',
                  					sortable : true
                              },{
                    					name : 'approveId',
                  					display : '审核人',
                  					sortable : true
                              },{
                    					name : 'approveName',
                  					display : '审核人名称',
                  					sortable : true
                              },{
                    					name : 'approveTime',
                  					display : '审核时间',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '创建人',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '创建人名称',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '创建时间',
                  					sortable : true
                              },{
                    					name : 'updateId',
                  					display : '更新人',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '更新人名称',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '更新时间',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=outsourcing_workverify_NULL&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'XXX',
						display : '从表字段'
					}]
		},
      
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "搜索字段",
					name : 'XXX'
				}]
 		});
 });
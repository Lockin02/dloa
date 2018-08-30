var show_page = function(page) {	   $("#esmcostdetailGrid").yxgrid("reload");};
$(function() {			$("#esmcostdetailGrid").yxgrid({				      model : 'engineering_cost_esmcostdetail',
               	title : '费用明细',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'projectCode',
                  					display : '项目编号',
                  					sortable : true
                              },{
                    					name : 'projectName',
                  					display : '项目名称',
                  					sortable : true
                              }                    ,{
                    					name : 'activityName',
                  					display : '任务名称',
                  					sortable : true
                              }                    ,{
                    					name : 'costType',
                  					display : '费用类型',
                  					sortable : true
                              }                    ,{
                    					name : 'costMoney',
                  					display : '费用金额',
                  					sortable : true
                              },{
                    					name : 'days',
                  					display : '天数',
                  					sortable : true
                              },{
                    					name : 'status',
                  					display : '状态',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '创建人Id',
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
                  					display : '修改人Id',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '修改人名称',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '修改时间',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=engineering_cost_NULL&action=pageItemJson',
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
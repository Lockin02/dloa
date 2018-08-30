var show_page = function(page) {	   $("#trialplandetailGrid").yxgrid("reload");};
$(function() {			$("#trialplandetailGrid").yxgrid({				      model : 'hr_trialplan_trialplandetail',
               	title : '员工试用计划明细',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'taskName',
                  					display : '任务名称',
                  					sortable : true
                              },{
                    					name : 'description',
                  					display : '任务描述',
                  					sortable : true
                              },{
                    					name : 'managerName',
                  					display : '任务负责人',
                  					sortable : true
                              },{
                    					name : 'managerId',
                  					display : '任务负责人描述',
                  					sortable : true
                              },{
                    					name : 'weights',
                  					display : '所占比例',
                  					sortable : true
                              },{
                    					name : 'memberName',
                  					display : '任务执行人',
                  					sortable : true
                              },{
                    					name : 'memberId',
                  					display : '任务执行人id',
                  					sortable : true
                              },{
                    					name : 'status',
                  					display : '状态',
                  					sortable : true
                              },{
                    					name : 'score',
                  					display : '评分',
                  					sortable : true
                              },{
                    					name : 'scoreDesc',
                  					display : '评分说明',
                  					sortable : true
                              },{
                    					name : 'beforeId',
                  					display : '前置任务id',
                  					sortable : true
                              },{
                    					name : 'beforeName',
                  					display : '前置任务名称',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=hr_trialplan_NULL&action=pageItemJson',
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
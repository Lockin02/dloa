var show_page = function(page) {	   $("#trialplanGrid").yxgrid("reload");};
$(function() {			$("#trialplanGrid").yxgrid({				      model : 'hr_trialplan_trialplan',
               	title : '员工试用培训计划',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'planName',
                  					display : '计划名称',
                  					sortable : true
                              },{
                    					name : 'description',
                  					display : '描述信息',
                  					sortable : true
                              },{
                    					name : 'memberName',
                  					display : '计划执行人',
                  					sortable : true
                              },{
                    					name : 'memberId',
                  					display : '计划执行人id',
                  					sortable : true
                              },{
                    					name : 'status',
                  					display : '状态',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '创建人',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '创建人ID',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '创建时间',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '修改人',
                  					sortable : true
                              },{
                    					name : 'updateId',
                  					display : '修改人ID',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '修改时间',
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
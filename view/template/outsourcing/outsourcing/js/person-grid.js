var show_page = function(page) {	   $("#personGrid").yxgrid("reload");};
$(function() {			$("#personGrid").yxgrid({				      model : 'outsourcing_outsourcing_person',
               	title : '人员租借详细',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'riskCode',
                  					display : '级别/职位编码',
                  					sortable : true
                              },{
                    					name : 'peopleCount',
                  					display : '租借人员数量',
                  					sortable : true
                              },{
                    					name : 'startTime',
                  					display : '预计使用开始时间',
                  					sortable : true
                              },{
                    					name : 'endTime',
                  					display : '预计使用结束时间',
                  					sortable : true
                              },{
                    					name : 'skill',
                  					display : '人员技能描述',
                  					sortable : true
                              },{
                    					name : 'inBudget',
                  					display : '人力成本（内部预算）',
                  					sortable : true
                              },{
                    					name : 'outBudget',
                  					display : '外包金额',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=outsourcing_outsourcing_NULL&action=pageItemJson',
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
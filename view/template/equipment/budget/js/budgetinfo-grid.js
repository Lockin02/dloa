var show_page = function(page) {	   $("#budgetinfoGrid").yxgrid("reload");};
$(function() {			$("#budgetinfoGrid").yxgrid({				      model : 'equipment_budget_budgetinfo',
               	title : '设备预算表从表',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'name',
                  					display : '配置名称',
                  					sortable : true
                              },{
                    					name : 'info',
                  					display : '配置详细',
                  					sortable : true
                              },{
                    					name : 'price',
                  					display : '配置单价',
                  					sortable : true
                              },{
                    					name : 'num',
                  					display : '配置数量',
                  					sortable : true
                              },{
                    					name : 'money',
                  					display : '配置金额',
                  					sortable : true
                              }],	   	
      
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : {
					display : "搜索字段",
					name : 'XXX'
				}
 		});
 });
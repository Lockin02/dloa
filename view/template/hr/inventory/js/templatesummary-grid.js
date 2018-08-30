var show_page = function(page) {	   $("#templatesummaryGrid").yxgrid("reload");};
$(function() {			$("#templatesummaryGrid").yxgrid({				      model : 'hr_inventory_templatesummary',
               	title : '盘点总结属性',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'question',
                  					display : '问题',
                  					sortable : true
                              },{
                    					name : 'orderIndex',
                  					display : '顺序',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '备注',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=hr_inventory_NULL&action=pageItemJson',
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
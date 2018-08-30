var show_page = function(page) {	   $("#inventorysummaryvalueGrid").yxgrid("reload");};
$(function() {			$("#inventorysummaryvalueGrid").yxgrid({				      model : 'hr_inventory_inventorysummaryvalue',
               	title : '盘点总结值',
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
                    					name : 'answer',
                  					display : '答案',
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
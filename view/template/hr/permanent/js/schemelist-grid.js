var show_page = function(page) {	   $("#schemelistGrid").yxgrid("reload");};
$(function() {			$("#schemelistGrid").yxgrid({				      model : 'hr_permanent_schemelist',
               	title : '员工试用考核项目细则',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'schemeCode',
                  					display : '方案编码',
                  					sortable : true
                              },{
                    					name : 'schemeName',
                  					display : '方案名称',
                  					sortable : true
                              }                    ,{
                    					name : 'standardCode',
                  					display : '考核项目编码',
                  					sortable : true
                              },{
                    					name : 'standard',
                  					display : '考核项目名称',
                  					sortable : true
                              },{
                    					name : 'standardProportion',
                  					display : '考核项目权重',
                  					sortable : true
                              },{
                    					name : 'standardContent',
                  					display : '考核内容',
                  					sortable : true
                              },{
                    					name : 'standardPoint',
                  					display : '考核要点',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=hr_permanent_NULL&action=pageItemJson',
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
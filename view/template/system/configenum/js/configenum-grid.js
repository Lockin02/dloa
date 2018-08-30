var show_page = function(page) {	   $("#configenumGrid").yxgrid("reload");};
$(function() {			$("#configenumGrid").yxgrid({				      model : 'system_configenum_configenum',
               	title : '系统管理配置枚举',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'configEnum1',
                  					display : '配置枚举1',
                  					sortable : true
                              },{
                    					name : 'configEnum2',
                  					display : '配置枚举2',
                  					sortable : true
                              },{
                    					name : 'configEnum3',
                  					display : '配置枚举3',
                  					sortable : true
                              },{
                    					name : 'configEnum4',
                  					display : '配置枚举4',
                  					sortable : true
                              },{
                    					name : 'configEnum5',
                  					display : '配置枚举5',
                  					sortable : true
                              },{
                    					name : 'configEnum6',
                  					display : '配置枚举6',
                  					sortable : true
                              },{
                    					name : 'configEnum7',
                  					display : '配置枚举7',
                  					sortable : true
                              },{
                    					name : 'configEnum8',
                  					display : '配置枚举8',
                  					sortable : true
                              },{
                    					name : 'configEnum9',
                  					display : '配置枚举9',
                  					sortable : true
                              },{
                    					name : 'configEnum10',
                  					display : '配置枚举10',
                  					sortable : true
                              },{
                    					name : 'configEnumName',
                  					display : '配置枚举名称',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=system_configenum_NULL&action=pageItemJson',
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
var show_page = function(page) {	   $("#abilityGrid").yxgrid("reload");};
$(function() {			$("#abilityGrid").yxgrid({				      model : 'hr_position_ability',
               	title : '职位能力要求',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'parentCode',
                  					display : '职位表编码',
                  					sortable : true
                              },{
                    					name : 'positionName',
                  					display : '职位名称',
                  					sortable : true
                              },{
                    					name : 'featureItem',
                  					display : '特征项',
                  					sortable : true
                              },{
                    					name : 'contents',
                  					display : '具体描述',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=hr_position_NULL&action=pageItemJson',
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
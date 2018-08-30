var show_page = function(page) {	   $("#workGrid").yxgrid("reload");};
$(function() {			$("#workGrid").yxgrid({				      model : 'hr_position_work',
               	title : '职位工作职责',
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
                    					name : 'jobContents',
                  					display : '职责内容',
                  					sortable : true
                              },{
                    					name : 'specificContents',
                  					display : '具体内容',
                  					sortable : true
                              },{
                    					name : 'jobTarget',
                  					display : '目标',
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
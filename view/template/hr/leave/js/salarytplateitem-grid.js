var show_page = function(page) {	   $("#salarytplateitemGrid").yxgrid("reload");};
$(function() {			$("#salarytplateitemGrid").yxgrid({				      model : 'hr_leave_salarytplateitem',
               	title : '工资清单模板清单',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'salaryContent',
                  					display : '交接项目',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '备注说明',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=hr_leave_NULL&action=pageItemJson',
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
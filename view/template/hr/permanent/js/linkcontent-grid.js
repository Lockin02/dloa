var show_page = function(page) {	   $("#linkcontentGrid").yxgrid("reload");};
$(function() {			$("#linkcontentGrid").yxgrid({				      model : 'hr_permanent_linkcontent',
               	title : '员工转正考核工作相关',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'parentCode',
                  					display : '源单编号',
                  					sortable : true
                              },{
                    					name : 'workPoint',
                  					display : '工作要点',
                  					sortable : true
                              },{
                    					name : 'outPoint',
                  					display : '输出成果',
                  					sortable : true
                              },{
                    					name : 'finishTime',
                  					display : '完成时间节点',
                  					sortable : true
                              },{
                    					name : 'ownType',
                  					display : '所属类型',
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
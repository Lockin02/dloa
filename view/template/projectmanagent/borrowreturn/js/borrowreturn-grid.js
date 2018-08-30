var show_page = function(page) {	   $("#borrowreturnGrid").yxgrid("reload");};
$(function() {			$("#borrowreturnGrid").yxgrid({				      model : 'projectmanagent_borrowreturn_borrowreturn',
               	title : '借试用归还管理',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'borrowId',
                  					display : '借用单ID',
                  					sortable : true
                              },{
                    					name : 'borrowCode',
                  					display : '借用单编号',
                  					sortable : true
                              },{
                    					name : 'borrowLimit',
                  					display : '借用类型',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '备注',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '修改时间',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '修改人名称',
                  					sortable : true
                              },{
                    					name : 'updateId',
                  					display : '修改人Id',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '创建时间',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '创建人名称',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '创建人ID',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=projectmanagent_borrowreturn_NULL&action=pageItemJson',
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
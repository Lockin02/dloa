var show_page = function(page) {	   $("#requirebackGrid").yxgrid("reload");};
$(function() {			$("#requirebackGrid").yxgrid({				      model : 'asset_require_requireback',
               	title : 'oa_asset_requireback',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'requireId',
                  					display : 'requireId',
                  					sortable : true
                              },{
                    					name : 'backReason',
                  					display : 'backReason',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '创建人',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '创建人id',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '创建日期',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=asset_require_NULL&action=pageJson',
			param : {
						paramId : 'mainId',
						colId : 'id'
					},
			colModel : {
						name : 'XXX',
						display : '从表字段'
					}
		},
      
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
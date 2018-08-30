var show_page = function(page) {	   $("#requireitemGrid").yxgrid("reload");};
$(function() {			$("#requireitemGrid").yxgrid({				      model : 'asset_require_requireitem',
               	title : '资产需求申请明细',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'requireId',
                  					display : '资产需求ID',
                  					sortable : true
                              },{
                    					name : 'description',
                  					display : '设备描述',
                  					sortable : true
                              },{
                    					name : 'number',
                  					display : '数量',
                  					sortable : true
                              },{
                    					name : 'executedNum',
                  					display : '已发货数量',
                  					sortable : true
                              },{
                    					name : 'dateHope',
                  					display : '期望交货日期',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '备注',
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
                              },{
                    					name : 'updateName',
                  					display : '修改人',
                  					sortable : true
                              },{
                    					name : 'updateId',
                  					display : '修改人id',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '修改日期',
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
var show_page = function(page) {	   $("#equipmentproGrid").yxgrid("reload");};
$(function() {			$("#equipmentproGrid").yxgrid({				      model : 'stock_extra_equipmentpro',
               	title : '常用设备附属物料',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'mainId',
                  					display : '基本id',
                  					sortable : true
                              },{
                    				name : 'productId',
                  					display : '物料id',
                  					sortable : true
                              },{
                    					name : 'productCode',
                  					display : '物料编号',
                  					sortable : true
                              },{
                    					name : 'productName',
                  					display : '物料名称',
                  					sortable : true
                              },{
                    					name : 'pattern',
                  					display : '规格型号',
                  					sortable : true
                              },{
                    					name : 'unitName',
                  					display : '单位',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '备注',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=stock_extra_NULL&action=pageItemJson',
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
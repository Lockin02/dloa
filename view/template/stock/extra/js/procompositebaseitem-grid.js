var show_page = function(page) {	   $("#procompositebaseitemGrid").yxgrid("reload");};
$(function() {			$("#procompositebaseitemGrid").yxgrid({				      model : 'stock_extra_procompositebaseitem',
               	title : '产品物料库存采购销售综合表清单',
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
                    					name : 'goodsId',
                  					display : '产品id',
                  					sortable : true
                              },{
                    					name : 'goodsName',
                  					display : '产品名称',
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
                    					name : 'purchDays',
                  					display : '采购时间（天）',
                  					sortable : true
                              },{
                    					name : 'forecastSaleNum',
                  					display : '销售预测数量',
                  					sortable : true
                              },{
                    					name : 'planPurchNum',
                  					display : '计划采购数量',
                  					sortable : true
                              },{
                    					name : 'availableNum',
                  					display : '可用库存',
                  					sortable : true
                              },{
                    					name : 'isProduce',
                  					display : '是否停产',
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
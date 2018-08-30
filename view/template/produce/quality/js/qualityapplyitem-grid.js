var show_page = function(page) {	   $("#qualityapplyitemGrid").yxgrid("reload");};
$(function() {			$("#qualityapplyitemGrid").yxgrid({				      model : 'produce_quality_qualityapplyitem',
               	title : '质检申请单清单',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                                                            ,{
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
                    					name : 'fittings',
                  					display : '配置',
                  					sortable : true
                              },{
                    					name : 'qualityNum',
                  					display : '报检数量',
                  					sortable : true
                              },{
                    					name : 'assignNum',
                  					display : '已下达数量',
                  					sortable : true
                              },{
                    					name : 'standardNum',
                  					display : '合格数量',
                  					sortable : true
                              },{
                    					name : 'planEndDate',
                  					display : '期望完成时间',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '备注',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=produce_quality_NULL&action=pageItemJson',
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
var show_page = function(page) {	   $("#bomitemGrid").yxgrid("reload");};
$(function() {			$("#bomitemGrid").yxgrid({				      model : 'produce_bom_bomitem',
               	title : 'BOM分录表',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                                        ,{
                    					name : 'productCode',
                  					display : '物料编码',
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
                    					name : 'properties',
                  					display : '物料属性',
                  					sortable : true
                              },{
                    					name : 'useNum',
                  					display : '用量',
                  					sortable : true
                              },{
                    					name : 'useStatus',
                  					display : '使用状态',
                  					sortable : true
                              },{
                    					name : 'planPercent',
                  					display : '计划百分比',
                  					sortable : true
                              },{
                    					name : 'lossRate',
                  					display : '损耗率',
                  					sortable : true
                              },{
                    					name : 'effectiveDate',
                  					display : '生效日期',
                  					sortable : true
                              },{
                    					name : 'expirationDate',
                  					display : '失效日期',
                  					sortable : true
                              },{
                    					name : 'isAllow',
                  					display : '是否禁用',
                  					sortable : true
                              },{
                    					name : 'productType',
                  					display : '子项类型',
                  					sortable : true
                              },{
                    					name : 'configPro',
                  					display : '配置属性',
                  					sortable : true
                              },{
                    					name : 'isCharacter',
                  					display : '是否有特性',
                  					sortable : true
                              },{
                    					name : 'isKeyObj',
                  					display : '关键件',
                  					sortable : true
                              },{
                    					name : 'stockCode',
                  					display : '发料仓库代码',
                  					sortable : true
                              },{
                    					name : 'stockName',
                  					display : '发料仓库名称',
                  					sortable : true
                              }                    ,{
                    					name : 'remark',
                  					display : '备注',
                  					sortable : true
                              }],	   	
      
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
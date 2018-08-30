var show_page = function(page) {	   $("#projectRentalGrid").yxgrid("reload");};
$(function() {			$("#projectRentalGrid").yxgrid({				      model : 'outsourcing_approval_projectRental',
               	title : '外包立项整包分包表',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                                        ,{
                    					name : 'costType',
                  					display : '费用类型',
                  					sortable : true
                              }                    ,{
                    					name : 'parentName',
                  					display : '上级类型',
                  					sortable : true
                              },{
                    					name : 'feeType',
                  					display : '类型',
                  					sortable : true
                              },{
                    					name : 'price',
                  					display : '价格',
                  					sortable : true
                              },{
                    					name : 'number',
                  					display : '数量',
                  					sortable : true
                              },{
                    					name : 'period',
                  					display : '周期',
                  					sortable : true
                              },{
                    					name : 'amount',
                  					display : '金额',
                  					sortable : true
                              }                    ,{
                    					name : 'suppCode',
                  					display : '归属外包供应商Code',
                  					sortable : true
                              },{
                    					name : 'suppName',
                  					display : '归属外包供应商',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '备注',
                  					sortable : true
                              },{
                    					name : 'isSelf',
                  					display : '是否本公司',
                  					sortable : true
                              },{
                    					name : 'isOtherFee',
                  					display : '是否其他费用',
                  					sortable : true
                              },{
                    					name : 'isManageFee',
                  					display : '是否管理费用',
                  					sortable : true
                              },{
                    					name : 'isProfit',
                  					display : '是否利润',
                  					sortable : true
                              },{
                    					name : 'isTax',
                  					display : '是否税费',
                  					sortable : true
                              },{
                    					name : 'isServerCost',
                  					display : '是否服务成本',
                  					sortable : true
                              },{
                    					name : 'isAllCost',
                  					display : '是否总成本',
                  					sortable : true
                              },{
                    					name : 'isChoosed',
                  					display : '是否被选用',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=outsourcing_approval_NULL&action=pageItemJson',
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
var show_page = function(page) {	   $("#invotherdetailGrid").yxgrid("reload");};
$(function() {			$("#invotherdetailGrid").yxgrid({				      model : 'finance_invother_invotherdetail',
               	title : '应付其他发票明细',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'invOthId',
                  					display : '其他发票id',
                  					sortable : true
                              },{
                    					name : 'productName',
                  					display : '发票条目',
                  					sortable : true
                              },{
                    					name : 'productNo',
                  					display : '发票条目编码',
                  					sortable : true
                              },{
                    					name : 'productId',
                  					display : '发票条目id',
                  					sortable : true
                              },{
                    					name : 'number',
                  					display : '数量',
                  					sortable : true
                              },{
                    					name : 'unit',
                  					display : '单位',
                  					sortable : true
                              },{
                    					name : 'price',
                  					display : '单价',
                  					sortable : true
                              },{
                    					name : 'rate',
                  					display : '税率',
                  					sortable : true
                              },{
                    					name : 'taxPrice',
                  					display : '含税单价',
                  					sortable : true
                              },{
                    					name : 'assessment',
                  					display : '税额',
                  					sortable : true
                              },{
                    					name : 'amount',
                  					display : '总金额(含税)',
                  					sortable : true
                              },{
                    					name : 'allCount',
                  					display : '总额(不含税)',
                  					sortable : true
                              },{
                    					name : 'objId',
                  					display : '源单id',
                  					sortable : true
                              },{
                    					name : 'objCode',
                  					display : '源单编号',
                  					sortable : true
                              },{
                    					name : 'objType',
                  					display : '源单类型',
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
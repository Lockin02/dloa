var show_page = function(page) {	   $("#orderequGrid").yxgrid("reload");};
$(function() {			$("#orderequGrid").yxgrid({				      model : 'outsourcing_workorder_orderequ',
               	title : '工单从表',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'personName',
                  					display : '施工人员',
                  					sortable : true
                              },{
                    					name : 'personId',
                  					display : '施工人员ID',
                  					sortable : true
                              },{
                    					name : 'IdCard',
                  					display : '身份证号码',
                  					sortable : true
                              },{
                    					name : 'email',
                  					display : '邮箱',
                  					sortable : true
                              },{
                    					name : 'exceptStart',
                  					display : '项目预计开始时间',
                  					sortable : true
                              },{
                    					name : 'exceptEnd',
                  					display : '项目预计结束时间',
                  					sortable : true
                              },{
                    					name : 'price',
                  					display : '工价（元）',
                  					sortable : true
                              },{
                    					name : 'payWay',
                  					display : '结算方式',
                  					sortable : true
                              },{
                    					name : 'payExplain',
                  					display : '结算说明',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '备注',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=outsourcing_workorder_NULL&action=pageItemJson',
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
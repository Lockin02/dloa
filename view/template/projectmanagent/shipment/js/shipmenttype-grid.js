var show_page = function(page) {	   $("#shipmenttypeGrid").yxgrid("reload");};
$(function() {			$("#shipmenttypeGrid").yxgrid({				      model : 'projectmanagent_shipment_shipmenttype',
               	title : '发货需求自定义类型',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'type',
                  					display : '类型名称',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '备注',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=projectmanagent_shipment_NULL&action=pageJson',
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
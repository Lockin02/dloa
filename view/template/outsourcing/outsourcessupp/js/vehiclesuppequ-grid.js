var show_page = function(page) {
	$("#vehiclesuppequGrid").yxgrid("reload");
};
$(function() {
	$("#vehiclesuppequGrid").yxgrid({
		model : 'outsourcing_outsourcessupp_vehiclesuppequ',
       	title : '车辆供应商-车辆资源信息',
			//列信息
			colModel : [{
 					display : 'id',
 						name : 'id',
 					sortable : true,
 						hide : true
			  },{
            			name : 'area',
  					display : '区域',
  					sortable : true
              },{
    					name : 'areaId',
  					display : '区域id',
  					sortable : true
              },{
    					name : 'rentPrice',
  					display : '租车费单价',
  					sortable : true,
  					process : function (v) {
  						return moneyFormat2(v);
  					}
              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=outsourcing_outsourcessupp_NULL&action=pageItemJson',
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
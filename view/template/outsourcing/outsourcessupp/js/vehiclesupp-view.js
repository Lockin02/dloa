$(document).ready(function() {

	$("#vehicleListInfo").yxeditgrid({
		objName : 'vehiclesupp[vehicle]',
		url : '?model=outsourcing_outsourcessupp_vehiclesuppequ&action=listJsonView',
		param : {
			dir : 'ASC',
			parentId : $("#id").val()
		},
		type : 'view',
		colModel : [{
            			name : 'area',
  					display : '区域'
              },{
    					name : 'areaId',
  					display : '区域id',
  						type : 'hidden'
              },{
    					name : 'carAmount',
  					display : '经济型车辆数量'
              },{
    					name : 'driverAmount',
  					display : '司机数量'
              },{
    					name : 'rentPrice',
  					display : '租车费单价',
  					process : function (v) {
  						return moneyFormat2(v);
  					}
              }]
	});

})
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
  					display : '����'
              },{
    					name : 'areaId',
  					display : '����id',
  						type : 'hidden'
              },{
    					name : 'carAmount',
  					display : '�����ͳ�������'
              },{
    					name : 'driverAmount',
  					display : '˾������'
              },{
    					name : 'rentPrice',
  					display : '�⳵�ѵ���',
  					process : function (v) {
  						return moneyFormat2(v);
  					}
              }]
	});

})
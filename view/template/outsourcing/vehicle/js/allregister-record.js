$(document).ready(function() {

	$("#registerInfo").yxeditgrid({
		url : '?model=outsourcing_vehicle_register&action=recordJson',
		param : {
			dir : 'ASC',
			sort : 'useCarDate',
			allregisterId : $("#id").val(),
			carNum : $("#carNum").val()
		},
		bodyAlign : 'center',
		type : 'view',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			name : 'useCarDate',
			display : '日  期',
			width : 70
		},{
			name : 'startMileage',
			display : '开始里程',
			width : 60,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'endMileage',
			display : '结束里程',
			width : 60,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'effectMileage',
			display : '有效里程',
			width : 60,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'rentalCarCost',
			display : '租车费',
			width : 60,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'gasolinePrice',
			display : '油价',
			width : 40,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'reimbursedFuel',
			display : '油费',
			width : 50,
			type : 'statictext',
			process : function (v ,row) {
				if(v != 0) {
					return moneyFormat2(v ,2);
				} else{
					return moneyFormat2(row.gasolineKMCost ,2);
				}
			}
		},{
			name : 'parkingCost',
			display : '停车费',
			width : 50,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'tollCost',
			display : '路桥费',
			width : 50,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'mealsCost',
			display : '餐饮费',
			width : 50,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'accommodationCost',
			display : '住宿费',
			width : 50,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'overtimePay',
			display : '加班费',
			width : 50,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'specialGas',
			display : '特殊油费',
			width : 50,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'drivingReason',
			display : '行车事由',
			width : 150,
			align : 'left'
		},{
			name : 'effectLogTime',
			width : 80,
			display : '有效LOG时长'
		},{
			name : '',
			width : 70,
			display : '用车人签名'
		},{
			name : '',
			width : 60,
			display : '司机签名'
		}]
	});

});
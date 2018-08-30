var show_page = function(page) {
	$("#registerGrid").yxgrid("reload");
};

$(function() {
	var param = {
		'carNum' : $("#carNum").val(),
		'allregisterId' : $("#allregisterId").val(),
		'state' : 1,
		'dir' : "ASC",
		'sort' : 'useCarDate'
	};
	if($("#needConDateFielt").val() != undefined){// 需要根据合同期来区分长短租登记
		param.needConDateFielt = $("#needConDateFielt").val();
	}
	$("#registerGrid").yxgrid({
		model : 'outsourcing_vehicle_register',
		param : param,
		title : '租车登记表',
		bodyAlign : 'center',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'driverName',
			display : '司机姓名',
			sortable : true,
			width : 70
		},{
			name : 'createName',
			display : '录入人',
			sortable : true,
			width : 80
		},{
			name : 'createTime',
			display : '录入时间',
			sortable : true,
			width : 120
		},{
			name : 'useCarDate',
			display : '用车日期',
			sortable : true,
			width : 80
		},{
			name : 'projectName',
			display : '项目名称',
			sortable : true,
			width : 200
		},{
			name : 'province',
			display : '省份',
			sortable : true,
			width : 80
		},{
			name : 'city',
			display : '城市',
			sortable : true,
			width : 80
		},{
			name : 'carNum',
			display : '车  牌',
			sortable : true,
			width : 80
		},{
			name : 'carModel',
			display : '车  型',
			sortable : true,
			width : 100
		},{
			name : 'startMileage',
			display : '开始里程',
			sortable : true,
			width : 80,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'endMileage',
			display : '结束里程',
			sortable : true,
			width : 80,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'effectMileage',
			display : '有效里程',
			sortable : true,
			width : 80,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'gasolinePrice',
			display : '油价（元/升）',
			sortable : true,
			width : 80,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'gasolineKMPrice',
			display : '按公里计价油费单价（元）',
			sortable : true,
			width : 150,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'reimbursedFuel',
			display : '实报实销油费（元）',
			sortable : true,
			width : 120,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'gasolineKMCost',
			display : '按公里计价油费（元）',
			sortable : true,
			width : 120,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'parkingCost',
			display : '停车费（元）',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'tollCost',
			display : '路桥费（元）',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'rentalCarCost',
			display : '租车费（元）',
			sortable : true,
			process : function (v ,row) {
				if (row.rentalPropertyCode == 'ZCXZ-02') {
					return moneyFormat2(row.shortRent, 2);
				} else {
					return moneyFormat2(v, 2);
				}
			}
		},{
			name : 'mealsCost',
			display : '餐饮费（元）',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'accommodationCost',
			display : '住宿费（元）',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'overtimePay',
			display : '加班费（元）',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'specialGas',
			display : '特殊油费（元）',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v, 2);
			}
		},{
			name : 'effectLogTime',
			display : '有效LOG时长',
			sortable : true
		}],

		toViewConfig : {
			formWidth : 1000,
			formHeight : 620,
			action : 'toView'
		},
 		buttonsEx : [{
			name : 'excelOut',
			text : "导出",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=outsourcing_vehicle_register&action=pageViewOut&carNum=" + $("#carNum").val()
						+ "&allregisterId=" + $("#allregisterId").val()
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=700");
			}
		}],

		menusEx : [{
			text : "变更原因",
			icon : 'view',
			showMenuFn : function(row) {
				if (row.changeReason) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin('?model=outsourcing_vehicle_register&action=toChangeReason&id=' + row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=690&width=1000");
			}
        },{
			name : 'view',
			text : "操作日志",
			icon : 'view',
			showMenuFn : function(row) {
				if (row.changeReason) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
						+ row.id
						+ "&tableName=oa_outsourcing_register"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}],

		searchitems : [{
			display : "司机姓名",
			name : 'driverNameSea'
		},{
			display : "录入人",
			name : 'createNameSea'
		},{
			display : "录入时间",
			name : 'createTimeSea'
		},{
			display : "用车日期",
			name : 'useCarDateSea'
		},{
			display : "项目名称",
			name : 'projectNameSea'
		},{
			display : "省份",
			name : 'provinceSea'
		},{
			display : "城市",
			name : 'citySea'
		}]
	});
});
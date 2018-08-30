$(document).ready(function() {
	$("#suppInfo").yxeditgrid({
		objName : 'rentalcar[supp]',
		dir : 'ASC',
		url : '?model=outsourcing_vehicle_rentalcarequ&action=listJson',
		param : {
			dir : 'ASC',
			parentId : $("#id").val()
		},
		type : 'view',
		colModel : [{
			name : 'suppName',
			display : '供应商名称',
			width : 150
		},{
			name : 'suppCode',
			display : '供应商编号',
			type : 'hidden'
		},{
			name : 'suppId',
			display : '供应商ID',
			type : 'hidden'
		},{
			name : 'linkManName',
			display : '联系人姓名',
			width : 60
		},{
			name : 'linkManPhone',
			display : '联系人电话',
			width : 80
		},{
			name : 'vehicleModel',
			display : '租车车型',
			width : 100
		},{
			name : 'powerSupply',
			display : '车辆供电情况',
			width : 90,
			process : function (v) {
				if (v == 1) {
					return "满足项目需求";
				}else {
					return "不满足项目需求";
				}
			}
		},{
			name : 'spotPrice',
			display : '现场沟通价格',
			width : 80,
			process : function (v) {
				return moneyFormat2(v);
			}
		},{
			name : 'useCarAmount',
			display : '用车数量',
			width : 60
		},{
			name : 'spotPriceExplain',
			display : '现场沟通价格说明',
			type : 'textarea',
			rows : 3,
			width : 200,
			align:'left'
		},{
			name : 'paymentCycle',
			display : '付款周期',
			width : 70
		},{
			name : 'vehicleMileage',
			display : '车辆里程数',
			width : 80,
			process : function (v) {
				return moneyFormat2(v);
			}
		},{
			name : 'isProvideInvoice',
			display : '是否提供发票',
			width : 80,
			process : function (v) {
				if (v == 1) {
					return "是";
				}else {
					return "否";
				}
			}
		},{
			name : 'invoice',
			display : '发票种类',
			width : 70,
			process : function (v ,row) {
				if (row.invoiceCode != '') {
					return v;
				} else {
					return '';
				}
			}
		},{
			name : 'taxPoint',
			display : '发票税点',
			width : 50,
			process : function (v) {
				if (v != '') {
					return v + "%";
				}
			}
		},{
			name : 'taxationBears',
			display : '税费承担方',
			width : 90
		},{
			name : 'remark',
			display : '备注',
			type : 'textarea',
			rows : 3,
			width : 300,
			align:'left'
		}, {
            display : '附件上传',
            name : 'file',
            type : 'file',
            serviceType:'rentalcar_supp'
        }]
	});

	//CDMA用车地点
	if ($("#provinceId").val() == 43) {
		$("#usePlace").parent().show().prev().show().parent().show();
	}
});
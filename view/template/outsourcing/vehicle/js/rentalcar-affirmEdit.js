$(document).ready(function() {

	//从表部分
	var itemTableObj = $("#suppInfo");
	$("#suppInfo").yxeditgrid({
		objName : 'rentalcar[supp]',
		dir : 'ASC',
		url : '?model=outsourcing_vehicle_rentalcarequ&action=listJson',
		param : {
			dir : 'ASC',
			parentId : $("#id").val()
		},
		isAddAndDel : false,
		colModel : [{
			name : 'id',
			display : 'id',
			type : 'hidden'
		},{
			name : 'suppAffirm',
			display : '供应商确认',
			width : 60,
			type : 'checkbox'
		},{
			name : 'deptName',
			display : '部门',
			width : 80,
			type : 'statictext'
		},{
			name : 'suppName',
			display : '供应商名称',
			type : 'statictext'
		},{
			name : 'linkManName',
			display : '联系人姓名',
			width : 60,
			type : 'statictext'
		},{
			name : 'linkManPhone',
			display : '联系人电话',
			width : 80,
			type : 'statictext'
		},{
			name : 'useCarAmount',
			display : '用车数量',
			width : 50,
			type : 'statictext'
		},{
			name : 'certificate',
			display : '证件情况',
			type : 'statictext'
		},{
			name : 'powerSupply',
			display : '车辆供电情况',
			type : 'statictext',
			width : 90,
			process : function (v) {
				if (v == 1) {
					return "满足项目需求";
				}else {
					return "不满足项目需求";
				}
			}
		},{
			name : 'paymentCycle',
			display : '付款周期',
			width : 70,
			type : 'statictext'
		},{
			name : 'invoice',
			display : '发票属性',
			width : 60,
			type : 'statictext'
		},{
			name : 'taxPoint',
			display : '发票税点',
			width : 50,
			type : 'statictext',
			datacode : 'WBZZSD' // 数据字典编码
		},{
			name : 'rentalFee',
			display : '租车费（包含司机工资）',
			width : 150,
			type : 'statictext'
		},{
			name : 'gasolineFee',
			display : '油费',
			type : 'statictext'
		},{
			name : 'catering',
			display : '餐饮费',
			type : 'statictext'
		},{
			name : 'accommodationFee',
			display : '住宿费',
			type : 'statictext'
		},{
			name : 'remark',
			display : '备注',
			type : 'statictext',
			width : 300,
			align : 'left'
		}]
	});
});

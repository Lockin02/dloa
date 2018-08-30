$(document).ready(function() {
	$("#registerInfo").yxeditgrid({
		objName : 'allregister[register]',
		url : '?model=outsourcing_vehicle_register&action=listJson',
		param : {
			sort : 'carNum,useCarDate',
			dir : 'ASC',
			allregisterId : $("#id").val(),
			state : 1,
			useCarDateLimit : $("#useCarDate").val()
		},
		isAddAndDel : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '是否打回<input type="checkbox" id="backAll" style="width:60px;">',
			name : 'back',
			type : 'checkbox',
			checkVal : '1',
			width : 60
		},{
			name : 'useCarDate',
			display : '用车日期',
			width : 80,
			type : 'statictext'
		},{
			name : 'createName',
			display : '录入人',
			width : 80,
			type : 'statictext'
		},{
			name : 'carNum',
			display : '车  牌',
			width : 80,
			type : 'statictext'
		},{
			name : 'carModel',
			display : '车  型',
			width : 100,
			type : 'statictext'
		},{
			name : 'rentalProperty',
			display : '租车性质',
			width : 70,
			type : 'statictext'
		},{
			name : 'suppName',
			display : '供应商名称',
			width : 130,
			type : 'statictext'
		},{
			display : '租车合同ID',
			name : 'rentalContractId',
			type : 'hidden'
		},{
			display : '车牌Hidden',
			name : 'carNum',
			type : 'hidden'
		},{
			name : 'rentalContractCode',
			display : '租车合同编号',
			width : 120,
			type : 'statictext',
			process : function (v ,row) {
				if (row.rentalContractId > 0) {
					return v;
				} else {
					return '';
				}
			}
		},{
			name : 'effectMileage',
			display : '有效里程',
			width : 80,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'gasolinePrice',
			display : '油价（元）',
			width : 80,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'gasolineKMPrice',
			display : '按公里计价油费单价（元）',
			width : 140,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'gasolineKMCost',
			display : '按公里计价油费（元）',
			width : 120,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'reimbursedFuel',
			display : '实报实销油费（元）',
			width : 120,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'parkingCost',
			display : '停车费（元）',
			type : 'statictext',
			width : 120,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'tollCost',
			display : '路桥费（元）',
			type : 'statictext',
			width : 120,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'rentalCarCost',
			display : '租车费（元）',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'mealsCost',
			display : '餐饮费（元）',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'accommodationCost',
			display : '住宿费（元）',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'overtimePay',
			display : '加班费（元）',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'specialGas',
			display : '特殊油费（元）',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'allCost',
			display : '总费用（元）',
			width : 90,
			type : 'statictext',
			process : function (v ,row) {
				var allCost = parseFloat(row.rentalCarCost ? row.rentalCarCost : 0)
						+ parseFloat(row.reimbursedFuel ? row.reimbursedFuel : 0)
						+ parseFloat(row.gasolineKMCost ? row.gasolineKMCost : 0)
						+ parseFloat(row.parkingCost ? row.parkingCost : 0)
						+ parseFloat(row.mealsCost ? row.mealsCost : 0)
						+ parseFloat(row.accommodationCost ? row.accommodationCost : 0)
						+ parseFloat(row.overtimePay ? row.overtimePay : 0)
						+ parseFloat(row.specialGas ? row.specialGas : 0);
				return moneyFormat2(allCost ,2);
			}
		},{
			name : 'effectLogTime',
			display : '有效LOG时长',
			width : 120,
			type : 'statictext'
		},{
			name : 'deductInformation',
			display : '扣款信息',
			width : 200,
			type : 'statictext'
		},{
			name : 'estimate',
			display : '评价',
			width : 200,
			type : 'statictext'
		}]
	});

    //是否打回全选
	$('#backAll').click(function () {
		var backObj = $("#registerInfo").yxeditgrid('getCmpByCol' ,'back');
		if ($(this).attr('checked') == true) {
			backObj.each(function () {
				$(this).attr('checked' ,'checked');
			});
		} else {
			backObj.each(function () {
				$(this).attr('checked' ,'');
			});
		}
	});
});

//提交检验
function checkData() {
	var backObj = $("#registerInfo").yxeditgrid('getCmpByCol' ,'back');
	var result = false;
	backObj.each(function () {
		if ($(this).attr('checked') == true) {
			result = true;
			return false; //退出循环
		}
	});
	if (!result) { //没有一个选中
		alert('请至少选择一条记录！');
		return false;
	}

	return true;
}
$(document).ready(function() {

	var limitRelativeCarNum = ($("#relativeCarNum").val() != undefined && $("#relativeCarNum").val() != '')? $("#relativeCarNum").val() : '';
	var limitRentalProperty = ($("#rentalProperty").val() != undefined && $("#rentalProperty").val() != '')? $("#rentalProperty").val() : '';

	$("#registerInfo").yxeditgrid({
		url : '?model=outsourcing_vehicle_register&action=statisticsJson',
		param : {
			dir : 'ASC',
			allregisterId : $("#id").val(),
			useCarDateLimit : $("#useCarDate").val(),
			limitRelativeCarNum : limitRelativeCarNum,
			limitRentalProperty : limitRentalProperty
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
			display : '用车日期',
			width : 80,
			process : function (v) {
				return v.substr(0, 7);
			}
		},{
			name : 'createName',
			display : '录入人',
			width : 80
		},{
			name : 'carNum',
			display : '车  牌',
			width : 80,
			process : function(v ,row){
                var needConDateFielt = (row.rentalContractId > 0)? '1' : '2';
				return "<a href='#' onclick='toSeeInfoInPageInnerWin(\"?model=outsourcing_vehicle_register&action=pageView"
						+ "&needConDateFielt=" + needConDateFielt + "&carNum=" + row.carNum
						+ "&allregisterId=" + $("#id").val()
						+ "&placeValuesBefore&TB_iframe=true&modal=false\",\"1\")'>" + v + "</a>";
			}
		},{
			name : 'carModel',
			display : '车  型',
			width : 70,
			type : 'statictext'
		},{
			name : 'rentalContractNature',
			display : '合同性质',
			type : 'statictext',
			width : 70
		},{
			name : 'deductMoney',
			display : '扣款金额',
			type : 'statictext',
			width : 70
		},{
			name : 'deductReason',
			display : '扣款理由',
			type : 'statictext',
			width : 200
		},{
			name : 'payInfoMoney1',
			display : '支付金额1',
			type : 'statictext',
			width : 70,
			process : function(v,row){
				var str = '';
				if(row.rentalProperty != '短租' && row.isFirstCar == 1 && v > 0 && row.isFirstCzCar == 0){
					str = '-';
				}else{
					if(v != '-' && v != ''){
						if(row.isFirstCar == 1){
							str = (v == "未生成")? "未生成" : "<a href='javascript:void(0)' onclick='toViewCost(this,\""+row.payInfoId1+"\",\""+row.expenseTmpId1+"\")'>"+v+"</a>";
							str = (v == 0)? '-' : str;
						}else{
							str = "-";
						}
					}else if(row.pay1payTypeCode == "HETFK"){
						str = "<a href='javascript:void(0)' onclick='toViewCost(this,\""+row.payInfoId1+"\",\""+row.expenseTmpId1+"\")'>"+v+"</a>";
					}else{
						str = "-";
					}
				}
				return str;
			}
		},{
			name : 'payInfoMoney2',
			display : '支付金额2',
			type : 'statictext',
			width : 70,
			process : function(v,row){
				var str = '';
				if(row.rentalProperty != '短租' && row.isFirstCar == 1 && v > 0 && row.isFirstCzCar == 0){
					str = '-';
				}else{
					if(v != '-' && v != ''){
						str = (v == "未生成")? "未生成" : (v > 0)? "<a href='javascript:void(0)' onclick='toViewCost(this,\""+row.payInfoId2+"\",\""+row.expenseTmpId2+"\")'>"+v+"</a>" : '-';
					}else if(row.pay2payTypeCode == "HETFK"){
						str = "<a href='javascript:void(0)' onclick='toViewCost(this,\""+row.payInfoId2+"\",\""+row.expenseTmpId2+"\")'>"+v+"</a>";
					}else{
						str = "-";
					}
				}
				return str;
			}
		},{
			name : 'rentalProperty',
			display : '租车性质',
			width : 70
		},{
			name : 'contractUseDay',
			display : '合同用车天数',
			width : 70,
			type : 'statictext',
			process : function (v ,row) {
				if (row.rentalPropertyCode == 'ZCXZ-02') {
					return '';
				} else {
					return v;
				}
			}
		},{
			name : 'registerNum',
			display : '实际用车天数',
			width : 70,
			type : 'statictext'
		},{
			name : 'suppName',
			display : '供应商名称',
			width : 130
		},{
			name : 'rentalContractCode',
			display : '租车合同编号',
			width : 120,
			process : function (v,row){
				var str = '';
				if(row.rentalContractId != '' && v != ''){
					str = "<a href='javascript:void(0)' onclick='toSeeInfoInPageInnerWin(\"index1.php?model=outsourcing_contract_rentcar&action=viewTab&notCloseBtn=1&id="+row.rentalContractId+"\",\"租车合同信息\")'>"+v+"</a>"
				}
				return str;
			}
		},{
			name : 'city',
			display : '城市',
			width : 80
		},{
			name : 'effectMileage',
			display : '有效里程',
			width : 80,
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
			name : 'gasolineKMCost',
			display : '按公里计价油费（元）',
			width : 120,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'parkingCost',
			display : '停车费（元）',
			width : 120,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'tollCost',
			display : '路桥费（元）',
			width : 120,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'rentalCarCost',
			display : '租车费（元）',
			width : 90,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'mealsCost',
			display : '餐饮费（元）',
			width : 90,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'accommodationCost',
			display : '住宿费（元）',
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
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'effectLogTime',
			width : 90,
			display : '有效LOG时长'
		},{
			name : 'estimate',
			display : '评价',
			align : 'left',
			width : 200
		}]
	});

});

var toViewCost = function(elmSelf,payInfoId,expenseTmpId){
	var url = "?model=outsourcing_vehicle_rentalcar&action=viewCostExpenseTmp&payInfoId="+payInfoId+"&expenseTmpId="+expenseTmpId +
		"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800";
	showThickboxWin(url);
}

var toSeeInfoInPageInnerWin = function(url,title){
	$("#pageInnerWindowIframe").attr("src",url);
	$("#pageInnerWindow").dialog({
		title : title,
		height : 700,
		width : 1000,
		modal: true
	}).dialog('open');
	// 在IE上关闭后再打开Dialog页面,弹框页面会出不来,暂时这样处理,强行把弹框显示出来
	$("#pageInnerWindow").parent('.panel.window').show();
	$("#pageInnerWindow").parent('.panel.window').next(".window-shadow").show();
	$("#pageInnerWindow").parent('.panel.window').next(".window-shadow").next(".window-mask").show();
};
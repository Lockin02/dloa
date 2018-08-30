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
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			name : 'useCarDate',
			display : '�ó�����',
			width : 80,
			process : function (v) {
				return v.substr(0, 7);
			}
		},{
			name : 'createName',
			display : '¼����',
			width : 80
		},{
			name : 'carNum',
			display : '��  ��',
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
			display : '��  ��',
			width : 70,
			type : 'statictext'
		},{
			name : 'rentalContractNature',
			display : '��ͬ����',
			type : 'statictext',
			width : 70
		},{
			name : 'deductMoney',
			display : '�ۿ���',
			type : 'statictext',
			width : 70
		},{
			name : 'deductReason',
			display : '�ۿ�����',
			type : 'statictext',
			width : 200
		},{
			name : 'payInfoMoney1',
			display : '֧�����1',
			type : 'statictext',
			width : 70,
			process : function(v,row){
				var str = '';
				if(row.rentalProperty != '����' && row.isFirstCar == 1 && v > 0 && row.isFirstCzCar == 0){
					str = '-';
				}else{
					if(v != '-' && v != ''){
						if(row.isFirstCar == 1){
							str = (v == "δ����")? "δ����" : "<a href='javascript:void(0)' onclick='toViewCost(this,\""+row.payInfoId1+"\",\""+row.expenseTmpId1+"\")'>"+v+"</a>";
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
			display : '֧�����2',
			type : 'statictext',
			width : 70,
			process : function(v,row){
				var str = '';
				if(row.rentalProperty != '����' && row.isFirstCar == 1 && v > 0 && row.isFirstCzCar == 0){
					str = '-';
				}else{
					if(v != '-' && v != ''){
						str = (v == "δ����")? "δ����" : (v > 0)? "<a href='javascript:void(0)' onclick='toViewCost(this,\""+row.payInfoId2+"\",\""+row.expenseTmpId2+"\")'>"+v+"</a>" : '-';
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
			display : '�⳵����',
			width : 70
		},{
			name : 'contractUseDay',
			display : '��ͬ�ó�����',
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
			display : 'ʵ���ó�����',
			width : 70,
			type : 'statictext'
		},{
			name : 'suppName',
			display : '��Ӧ������',
			width : 130
		},{
			name : 'rentalContractCode',
			display : '�⳵��ͬ���',
			width : 120,
			process : function (v,row){
				var str = '';
				if(row.rentalContractId != '' && v != ''){
					str = "<a href='javascript:void(0)' onclick='toSeeInfoInPageInnerWin(\"index1.php?model=outsourcing_contract_rentcar&action=viewTab&notCloseBtn=1&id="+row.rentalContractId+"\",\"�⳵��ͬ��Ϣ\")'>"+v+"</a>"
				}
				return str;
			}
		},{
			name : 'city',
			display : '����',
			width : 80
		},{
			name : 'effectMileage',
			display : '��Ч���',
			width : 80,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'reimbursedFuel',
			display : 'ʵ��ʵ���ͷѣ�Ԫ��',
			width : 120,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'gasolineKMCost',
			display : '������Ƽ��ͷѣ�Ԫ��',
			width : 120,
			type : 'statictext',
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'parkingCost',
			display : 'ͣ���ѣ�Ԫ��',
			width : 120,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'tollCost',
			display : '·�ŷѣ�Ԫ��',
			width : 120,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'rentalCarCost',
			display : '�⳵�ѣ�Ԫ��',
			width : 90,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'mealsCost',
			display : '�����ѣ�Ԫ��',
			width : 90,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'accommodationCost',
			display : 'ס�޷ѣ�Ԫ��',
			width : 90,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'overtimePay',
			display : '�Ӱ�ѣ�Ԫ��',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'specialGas',
			display : '�����ͷѣ�Ԫ��',
			type : 'statictext',
			width : 90,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'allCost',
			display : '�ܷ��ã�Ԫ��',
			width : 90,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'effectLogTime',
			width : 90,
			display : '��ЧLOGʱ��'
		},{
			name : 'estimate',
			display : '����',
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
	// ��IE�Ϲرպ��ٴ�Dialogҳ��,����ҳ��������,��ʱ��������,ǿ�аѵ�����ʾ����
	$("#pageInnerWindow").parent('.panel.window').show();
	$("#pageInnerWindow").parent('.panel.window').next(".window-shadow").show();
	$("#pageInnerWindow").parent('.panel.window').next(".window-shadow").next(".window-mask").show();
};
$(function(){
	//根据合同类型显示
	var $contractTypeCode = $("#contractTypeCode");
	if ($contractTypeCode.val() == 'ZCHTLX-01') {
		$("#oilPriceTd1").show();
		$("#oilPriceTd2").show();
	}else if ($contractTypeCode.val() == 'ZCHTLX-02') {
		$("#fuelChargeTd1").show();
		$("#fuelChargeTd2").show();
	}

	//是否使用油卡
	if ($("#isUseOilcardVal").val() == 1) {
		$("#oilcardMoneyTd1").show();
		$("#oilcardMoneyTd2").show();
	} else {
		$("#oilcardMoneyTd1").hide();
		$("#oilcardMoneyTd2").hide();
	}

	$("#vehicleInfo").yxeditgrid({
		objName : 'rentcar[vehicle]',
		dir : 'ASC',
		url : '?model=outsourcing_contract_vehicle&action=listJson',
		param : {
			dir : 'ASC',
			contractId : $("#pid").val(),
			isTemp : '1'
		},
		type : 'view',
		colModel : [{
			name : 'id',
			display : 'id',
			type : 'hidden'
		},{
			name : 'carModel',
			display : '车型',
			width : '15%'
		},{
			name : 'carNumber',
			display : '车牌号',
			width : '15%'
		},{
			name : 'driver',
			display : '驾驶员',
			width : '20%'
		},{
			name : 'idNumber',
			display : '驾驶员身份证号',
			width : '25%',
			validation : {
				required : true
			}
		},{
			name : 'displacement',
			display : '排量、使用何种汽油',
			width : '25%'
		}]
	});

	$("#feeInfo").yxeditgrid({
		objName : 'rentcar[fee]',
		dir : 'ASC',
		url : '?model=outsourcing_contract_rentcarfee&action=listJson',
		param : {
			dir : 'ASC',
			contractId : $("#pid").val(),
			isTemp : '1'
		},
		type : 'view',
		colModel : [{
			name : 'id',
			display : 'id',
			type : 'hidden'
		},{
			name : 'feeName',
			display : '费用名称',
			width : 110,
			validation : {
				required : true
			}
		},{
			name : 'feeAmount',
			display : '费用金额',
			width : 110,
			validation : {
				custom : ['money']
			}
		},{
			name : 'remark',
			display : '备  注',
			type : 'textarea',
			width : 220,
			rows : 2,
			align : 'left'
		}]
	});

	var thisObj ;
	$.ajax({
	    type: "POST",
	    url: "?model=common_changeLog&action=getChangeInformation",
	    data: {
	    	"tempId" : $("#pid").val(),
	    	"logObj" : "rentcar"
	    },
	    async: false,
	    success: function(data){
	    	if(data){
	   			data = eval("(" + data + ")");
				for(var i = 0; i < data.length ; i++){
					if (data[i]['changeField'] == 'isNeedStamp') { //是否盖章换成中文
						data[i]['oldValue'] = (data[i]['oldValue'] == 1) ? '是' : '否';
						data[i]['newValue'] = (data[i]['newValue'] == 1) ? '是' : '否';
					}
					if (data[i]['changeField'] == 'isUseOilcard') { //是否使用油卡换成中文
						data[i]['oldValue'] = (data[i]['oldValue'] == 1) ? '是' : '否';
						data[i]['newValue'] = (data[i]['newValue'] == 1) ? '是' : '否';
					}
					thisObj = $("#" + data[i]['changeField']);
					if(thisObj.attr("class") == "formatMoney"){
						thisObj.html( moneyFormat2(data[i]['oldValue']) + " => " + moneyFormat2(data[i]['newValue']));
					} else {
						thisObj.html(data[i]['oldValue'] + ' => ' +  data[i]['newValue']);
					}
					thisObj.attr('style','color:red');
				}
	   		}
		}
	});
});
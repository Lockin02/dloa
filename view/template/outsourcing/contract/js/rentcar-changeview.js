$(function(){
	//���ݺ�ͬ������ʾ
	var $contractTypeCode = $("#contractTypeCode");
	if ($contractTypeCode.val() == 'ZCHTLX-01') {
		$("#oilPriceTd1").show();
		$("#oilPriceTd2").show();
	}else if ($contractTypeCode.val() == 'ZCHTLX-02') {
		$("#fuelChargeTd1").show();
		$("#fuelChargeTd2").show();
	}

	//�Ƿ�ʹ���Ϳ�
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
			display : '����',
			width : '15%'
		},{
			name : 'carNumber',
			display : '���ƺ�',
			width : '15%'
		},{
			name : 'driver',
			display : '��ʻԱ',
			width : '20%'
		},{
			name : 'idNumber',
			display : '��ʻԱ���֤��',
			width : '25%',
			validation : {
				required : true
			}
		},{
			name : 'displacement',
			display : '������ʹ�ú�������',
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
			display : '��������',
			width : 110,
			validation : {
				required : true
			}
		},{
			name : 'feeAmount',
			display : '���ý��',
			width : 110,
			validation : {
				custom : ['money']
			}
		},{
			name : 'remark',
			display : '��  ע',
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
					if (data[i]['changeField'] == 'isNeedStamp') { //�Ƿ���»�������
						data[i]['oldValue'] = (data[i]['oldValue'] == 1) ? '��' : '��';
						data[i]['newValue'] = (data[i]['newValue'] == 1) ? '��' : '��';
					}
					if (data[i]['changeField'] == 'isUseOilcard') { //�Ƿ�ʹ���Ϳ���������
						data[i]['oldValue'] = (data[i]['oldValue'] == 1) ? '��' : '��';
						data[i]['newValue'] = (data[i]['newValue'] == 1) ? '��' : '��';
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
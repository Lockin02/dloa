$(document).ready(function() {

	//��Ŀ����
	$("#projectName").yxcombogrid_esmproject({
		isDown : false,
		hiddenId : 'projectId',
		nameCol : 'projectName',
		height : 250,
		isFocusoutCheck : true,
		gridOptions : {
			isTitle : true,
			showcheckbox : false,
			param : {'statusArr':'GCXMZT01,GCXMZT02'},
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#projectId").val(data.id);
					$("#projectCode").val(data.projectCode);
					$("#officeId").val(data.officeId);
					$("#officeName").val(data.officeName);
					$("#projectType").val(data.natureName);
					$("#projectTypeCode").val(data.nature);
					$("#projectManager").val(data.managerName);
					$("#projectManagerId").val(data.managerId);
					$("#province").val(data.provinceId).trigger('change');
					$("#city").val(data.cityId).trigger('change');
				}
			}
		},
		event : {
			'clear' : function() {
				$("#projectId").val("");
				$("#projectCode").val("");
				$("#officeId").val("");
				$("#officeName").val("");
				$("#projectType").val("");
				$("#projectTypeCode").val("");
				$("#projectManager").val("");
				$("#province").val("").trigger('change');
				$("#city").val("").trigger('change');
			}
		}
	});

	//������Ӧ������
	$("#suppName").yxcombogrid_outsuppvehicle({
		hiddenId : 'suppId',
		isFocusoutCheck : true,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e,row,data) {
					$("#suppCode").val(data.suppCode);
					$("#suppName").val(data.suppName);
				}
			}
		},
		event : {
			'clear' : function() {
				$("#suppCode").val("");
				$("#suppName").val("");
				$("#suppId").val("");
			}
		}
	});

	$("#startMileage_v").blur(function (){
		countMileage();
	});
	$("#endMileage_v").blur(function (){
		countMileage();
	});

	//��ͬ���͸ı��¼�
	$("#contractTypeCode").change(function (){
		if ($(this).val() == 'ZCHTLX-03') {
			$("#extTr").show();
			$("#reimbursedFuelTd1").show();
			$("#reimbursedFuelTd2").show();
			$("#reimbursedFuel_v").addClass("validate[required]");
		} else {
			$("#extTr").hide();
			$("#reimbursedFuelTd1").hide();
			$("#reimbursedFuelTd2").hide();
			$("#reimbursedFuel_v").removeClass("validate[required]").val("");
			$("#reimbursedFuel").val("");
		}
		contractAndRental();
	});
	$("#contractTypeCode").trigger("change");

	//�⳵���ʸı��¼�
	$("#rentalPropertyCode").change(function () {
		if ($(this).val() == 'ZCXZ-02') {
			$("#shortRent").parent().show().prev().show();
			$("#shortRent_v").addClass("validate[required]");
		} else {
			$("#shortRent").val("").parent().hide().prev().hide();
			$("#shortRent_v").removeClass("validate[required]").val("");
		}
		contractAndRental();
	});

	//����
	$("#carNum").change(function () {
		if ($.trim($(this).val())) {
			getRentFree();
		} else {
			$("#feeInfo").empty();
		}
	});

	validate({
		"projectName" : {
			required : true
		},
		"useCarDate" : {
			required : true
		},
		"driverName" : {
			required : true
		},
		"rentalPropertyCode" : {
			required : true
		},
		"province" : {
			required : true
		},
		"city" : {
			required : true
		},
		"isCardPay" : {
			required : true
		},
		"carNum" : {
			required : true
		},
		"carModelCode" : {
			required : true
		},
		"startMileage_v" : {
			required : true
		},
		"endMileage_v" : {
			required : true
		},
		"effectLogTime_v" : {
			required : true
		},
		"suppName" : {
			required : true
		},
		"contractTypeCode" : {
			required : true
		}
	});

});

//������Ч�����
function countMileage() {
	var endMileage = $("#endMileage").val().trim();
	var startMileage = $("#startMileage").val().trim();
	if (endMileage && startMileage) {
		var effectMileage = (endMileage - startMileage).toFixed(2);
		if (effectMileage < 0) {
			effectMileage = 0;
			alert("��Ч�����Ч��")
		}
		$("#effectMileage_v").val(moneyFormat2(effectMileage ,2));
		$("#effectMileage").val(effectMileage);
	}
}

//��ͬ���ʺ��⳵�����¼��������Ƿ���д��������Ƽ��ͷѵ��ۡ���
function contractAndRental() {
	if ($("#contractTypeCode").val() == 'ZCHTLX-02' && $("#rentalPropertyCode").val() == 'ZCXZ-02') {
		$("#extTr").show();
		$("#gasolineKMPrice").parent().show().prev().show();
		$("#gasolineKMPrice_v").addClass("validate[required]");
	} else {
		if($("#contractTypeCode").val() != 'ZCHTLX-03'){
			$("#extTr").hide();
		}
		$("#gasolineKMPrice").val("").parent().hide().prev().hide();
		$("#gasolineKMPrice_v").removeClass("validate[required]").val("");
	}
}

//��ȡ��ͬ���ӷ���
function getRentFree() {
	$.ajax({
		type : "POST",
		url : "?model=outsourcing_contract_rentcarfee&action=listJsonByCar",
		data : {
			useCarDate : $("#useCarDate").val(),
			carNum : $("#carNum").val()
		},
		// async : false,
		success : function (data) {
			if (data != 'false') {
				data = eval("(" + data + ")");

				$("#feeInfo").yxeditgrid({
					objName : 'register[fee]',
					isAddAndDel : false,
					data : data,
					colModel : [{
						name : 'contractId',
						display : '��ͬID',
						type : 'hidden'
					},{
						name : 'orderCode',
						display : '��ͬ���',
						type : 'hidden'
					},{
						name : 'feeName',
						display : '��������',
						type : 'statictext',
						align : 'left',
						width : '25%'
					},{
						name : 'feeName',
						display : '�������ƺ�̨��',
						type : 'hidden'
					},{
						name : 'feeAmount',
						display : '���ý��',
						type : 'statictext',
						width : '15%',
						process : function (v) {
							return moneyFormat2(v ,2);
						}
					},{
						name : 'yesOrNo',
						display : '�Ƿ�ѡ��',
						type : 'checkbox',
						checkVal : 1,
						width : '10%'
					},{
						name : 'feeAmount',
						display : '���ý���̨��',
						type : 'hidden'
					},{
						name : 'remark',
						display : '��  ע',
						type : 'statictext',
						align : 'left',
						width : '50%'
					},{
						name : 'remark',
						display : '��ע��̨��',
						type : 'hidden'
					}]
				});
			} else {
				$("#feeInfo").empty();
			}
		}
	});
}

var chkExistRecords = function(){
	var useCarMonth = $("#useCarDate").val();
	useCarMonth = (useCarMonth != '')? useCarMonth.substr(0,7) : '';// ��Ӧ�·�
	var projectCode = $("#projectCode").val();// ��Ŀ���
	var suppCode = $("#suppCode").val();// ��Ӧ�̱���
	var suppName = $("#suppName").val();
	var carNum = $("#carNum").val();// ���ƺ���

	var chkResult = $.ajax({
		type : "POST",
		url : "?model=outsourcing_vehicle_register&action=ajaxChkRentCarRecord",
		data : {
			useCarMonth : useCarMonth,
			projectCode : projectCode,
			suppCode : suppCode,
			carNum : carNum
		},
		async: false
	}).responseText;

	if(chkResult == 'false' || chkResult == ''){
		return true;
	}else{
		alert("�� "+useCarMonth+" �·���, ��������ĿΪ��"+projectCode+"��,��Ӧ��Ϊ��"+suppName+"���ҳ��ƺ�Ϊ ��"+carNum+"���ĵǼǻ�����Ϣ������״̬Ϊ�����л���ɣ�, ��������������صļ�¼, ������Ŀ����ͨ�����");
		return false;
	}
};

//������֤
function checkData() {
	var isCanAdd = $.ajax({
						type : "POST",
						url : "?model=outsourcing_vehicle_register&action=isCanAdd",
						data : {
							projectId : $("#projectId").val(),
							useCarDate : $("#useCarDate").val(),
							carNum : $("#carNum").val()
						},
						async: false
					}).responseText;
	if (isCanAdd == 0) {
		alert('��Ŀ�ϸó����Ѵ����ó�����Ϊ' + $("#useCarDate").val() + '�ļ�¼');
		return false;
	}else if(!chkExistRecords()){
		return false;
	}else{
		return 'pass';
	}
}
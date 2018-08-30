$(document).ready(function() {

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

	//��ͬ�����¼�
	if ($("#contractTypeCode").val() == 'ZCHTLX-03') {
		$("#extTr").show();
		$("#reimbursedFuelTd1").show();
		$("#reimbursedFuelTd2").show();
	} else {
		$("#extTr").hide();
		$("#reimbursedFuelTd1").hide();
		$("#reimbursedFuelTd2").hide();
	}

	//�⳵�����¼�
	if ($("#rentalPropertyCode").val() == 'ZCXZ-02') {
		$("#shortRent").parent().show().prev().show();
		$("#shortRent_v").addClass("validate[required]");
		if ($("#contractTypeCode").val() == 'ZCHTLX-02') {
			$("#extTr").show();
			$("#gasolineKMPrice").parent().show().prev().show();
			$("#gasolineKMPrice_v").addClass("validate[required]");
		}
	}

	//�Ƿ�ʹ���Ϳ�֧��
	$("#isCardPay").val($("#isCardPayVal").val());

	$("#startMileage_v").blur(function (){
		countMileage();
	});
	$("#endMileage_v").blur(function (){
		countMileage();
	});

	//����
	$("#carNum").change(function () {
		getRentFree();
	});

	$("#feeInfo").yxeditgrid({
		objName : 'register[fee]',
		isAddAndDel : false,
		url : '?model=outsourcing_vehicle_registerfee&action=listJson',
		param : {
			dir : 'ASC',
			registerId : $("#id").val()
		},
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
			name : 'remark',
			display : '��  ע',
			type : 'statictext',
			align : 'left',
			width : '50%'
		}]
	});

	validate({
		"driverName" : {
			required : true
		},
		"carNum" : {
			required : true
		},
		"changeReason" : {
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
	var isCanAddByChange = $.ajax({
						type: "POST",
						url: "?model=outsourcing_vehicle_register&action=isCanAddByChange",
						data: {
							'projectId' : $("#projectId").val(),
							'useCarDate' : $("#useCarDate").val(),
							'carNum' : $("#carNum").val(),
							'id' : $("#id").val()
						},
						async: false
					}).responseText;
	if (isCanAddByChange == 0) {
		alert('��Ŀ�ϸó����Ѵ����ó�����Ϊ' + $("#useCarDate").val() + '�ļ�¼');
		return false;
	}else if(!chkExistRecords()){
		return false;
	}else{
		return 'pass';
	}
}

var toSubmit = function () {
	if(checkData() == 'pass'){
		$("#form1").submit();
	}
}

//��ȡ��ͬ���ӷ���
function getRentFree() {
	$.ajax({
		type : "POST",
		url : "?model=outsourcing_contract_rentcarfee&action=listJsonByCar",
		data : {
			'useCarDate' : $("#useCarDate").val(),
			'carNum' : $("#carNum").val()
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
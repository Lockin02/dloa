$(document).ready(function() {

	$("#contractNatureCode").trigger("change"); //��ͬ����
	$("#contractTypeCode").trigger("change"); //��ͬ����

	//ʡ�ݳ��д���
	$("#companyProvinceCode option").each(function () {
		if ($(this).text() == $("#companyProvince").attr("val")) {
			$(this).attr("selected" ,"selected");
			$("#companyProvinceCode").trigger("change");
		}
	});
	$("#companyCityCode option").each(function () {
		if ($(this).text() == $("#companyCity").attr("val")) {
			$(this).attr("selected" ,"selected");
			$("#companyCity").val($(this).text());
		}
	});

	//�Ƿ���Ҫ����
	$("input[name=rentcar[isNeedStamp]][value=" + $("#isNeedStampVal").val() +"]").attr("checked" ,true).trigger("change");

	//�Ƿ�ʹ���Ϳ�
	$("input[name=rentcar[isUseOilcard]][value=" + $("#isUseOilcardVal").val() +"]").attr("checked",true).trigger("change");

	//����������
	$('#payApplyMan option').each(function () {
		if ($(this).val() == $("#payApplyMan").attr("val")) {
			$(this).attr("selected" ,"selected");
			return false; //�˳�ѭ�������Ч��
		}
	});

	//��ʼ��ǩԼ��˾
	var suppIds = $.ajax({
		type : "POST",
		url : "?model=outsourcing_vehicle_rentalcarequ&action=getSuppByParent",
		data : {
			parentId : $("#rentalcarId").val()
		},
		async : false
	}).responseText;
	$("#suppIds").val();
	setSignCompany();

	$("#vehicleInfo").yxeditgrid({
		objName : 'rentcar[vehicle]',
		dir : 'ASC',
		url : '?model=outsourcing_contract_vehicle&action=listJson',
		param : {
			dir : 'ASC',
			contractId : $("#id").val()
		},
		isFristRowDenyDel : true,
		colModel : [{
			name : 'id',
			display : 'id',
			type : 'hidden'
		},{
			name : 'carModelCode',
			display : '����',
			width : '15%',
			type : 'select',
			datacode : 'WBZCCX' // �����ֵ����
		},{
			name : 'carNumber',
			display : '���ƺ�',
			width : '10%',
			validation : {
				required : true
			}
		},{
			name : 'driver',
			display : '��ʻԱ',
			width : '10%',
			validation : {
				required : true
			}
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
			width : '15%'
		},{
			name : 'oilCarUse',
			display : '�Ϳ��ֳ�',
			width : '10%',
			type : 'select',
			options : [{
				name : "��",
				value : "��"
			},{
				name : "��",
				value : "��"
			}]
		},{
			name : 'oilCarAmount',
			display : '�Ϳ����',
			width : '15%',
			type : 'money'
		}]
	});

	$("#feeInfo").yxeditgrid({
		objName : 'rentcar[fee]',
		dir : 'ASC',
		url : '?model=outsourcing_contract_rentcarfee&action=listJson',
		param : {
			dir : 'ASC',
			contractId : $("#id").val()
		},
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
			rows : 2
		}]
	});

	validate({
		"changeReason" : {
			required : true
		}
	});
});
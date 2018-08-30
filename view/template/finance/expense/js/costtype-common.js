$(document).ready(function() {
	validate({
		"CostTypeName" : {
			required : true
		}
	});

	//��ʾ����
	setSelect('showDays');
	//������Ʊ
	setSelect('isReplace');
	//¼���嵥
	setSelect('isEqu');
	//�Ƿ���
	setSelect('isSubsidy');
	//��ֵ������Ʊ
	canReplace();	
	//�Ƿ�ر� chenrf
	setSelect('isClose');
});

//����Ĭ�Ϸ�Ʊ��������
function setInvoiceTypeName(thisVal){
	var invoiceTypeName = $("#invoiceType").find("option:selected").text();
	$("#invoiceTypeName").val(invoiceTypeName);
}

//��ֵ������Ʊ
function canReplace(){
	//Ĭ��������Ʊʱ�Ĳ���
	var isReplace = $("#isReplace").val();
	if(isReplace == '0'){
		$("#invoiceTypeInfo").attr('class','blue');
	}else{
		if($("#isSubsidy").val() !="1" ){
			$("#invoiceTypeInfo").attr('class','none');
		}
	}
}

//�����Ƿ���
function setIsSubsidy(){
	//Ĭ��������Ʊʱ�Ĳ���
	var isSubsidy = $("#isSubsidy").val();
	if(isSubsidy == '1'){
		$("#invoiceTypeInfo").attr('class','blue');
	}else{
		if($("#isReplace").val() !="0" ){
			$("#invoiceTypeInfo").attr('class','none');
		}
	}
}

//����֤
function checkForm(){
	//Ĭ��������Ʊʱ�Ĳ���
	var isReplace = $("#isReplace").val();
	if(isReplace == "0"){//�����������Ʊʱ������ѡһ�ַ�Ʊ
		if($("#invoiceType").val() == ""){
			alert('��������Ʊʱ������ѡ��Ĭ�Ϸ�Ʊ����');
			return false;
		}
	}

	//Ĭ���Ƿ���ʱ�Ĳ���
	var isSubsidy = $("#isSubsidy").val();
	if(isSubsidy == "1"){//����ǲ���ʱ������ѡһ�ַ�Ʊ
		if($("#invoiceType").val() == ""){
			alert('�����������óɲ���ʱ���뽫����һ����Ӧ�ķ�Ʊ����');
			return false;
		}
	}

	return true;
}
//�����Ƿ�رշ�������
function selClose(){
	var isClose=$("#isClose").val();
	if(isClose=='1'){
		alert('���Ϊ���ڵ㣬�����ӽڵ㲻����!');
	}
	
}
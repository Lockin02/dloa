/**
 * ��ȡʡ������
 */
function getProvince() {
	var responseText = $.ajax({
		url : 'index1.php?model=system_procity_province&action=getProvinceNameArr',
		type : "POST",
		async : false
	}).responseText;
	var o = eval("(" + responseText + ")");
	return o;
}

/**
 * ���ʡ��������ӵ�������
 */
function addDataToProvince(data, selectId) {
	for (var i = 0, l = data.length; i < l; i++) {
		$("#" + selectId).append("<option title='" + data[i].text
				+ "' value='" + data[i].value + "'>" + data[i].text
				+ "</option>");
	}
}

/**
 * ��ʡ�ݸı�ʱ�ԣ���esmproject[proCode]��title��ֵ��ֵ��esmproject[proName]
 */
function setProName(){
	$('#proName').val($("#proCode").find("option:selected").attr("title"));
}

//����������رղ���֤
function timeCheck($t){
	var s = plusDateInfo('planBeginDate','planEndDate');
	if(s < 0) {
		alert("����ʱ�䲻�ܱȹر�ʱ����");
		$t.value = "";
		return false;
	}
}

//������Ŀ��Ԥ��
function countBudgetAll(){
	var budgetAll = accAdd($("#budgetField").val(),$("#budgetOther").val(),2);
	budgetAll = accAdd(budgetAll,$("#budgetOutsourcing").val(),2);

	setMoney('budgetAll',budgetAll);

	//������ý���
	countProcess();
}

//������Ŀ�ܷ���
function countFeeAll(){
	var feeAll = accAdd($("#feeField").val(),$("#feeOther").val(),2);
	feeAll = accAdd(feeAll,$("#feeOutsourcing").val(),2);

	setMoney('feeAll',feeAll);
	//������ý���
	countProcess();
}

//������ý���
function countProcess(){
	//��ȡԤ��
	var budgetAll = $("#budgetAll").val();
	if(budgetAll == 0){
		$("#feeAllProcess").val(0);
	}else{
		var feeAllProcess = accMul(accDiv($("#feeField").val(),budgetAll,4),100,2);
		$("#feeAllProcess").val(feeAllProcess);
	}

	//��ȡԤ��
	var budgetField = $("#budgetField").val();
	if(budgetField == 0){
		$("#feeFieldProcess").val(0);
	}else{
		var feeFieldProcess = accMul(accDiv($("#feeField").val(),budgetField,4),100,2);
		$("#feeFieldProcess").val(feeFieldProcess);
	}
}



$(document).ready(function(){

	//��ȡʡ�����鲢��ֵ��provinceArr
	provinceArr = getProvince();

	//��ʡ������provinceArr��ֵ��proCode
	addDataToProvince(provinceArr,'proCode');

	//��ѡ��������
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

	//��ѡ���´�
	$("#officeName").yxcombogrid_office({
		hiddenId : 'officeId',
		gridOptions : {
			showcheckbox : false
		}
	});

	//��ѡ��Ŀ����
	$("#managerName").yxselect_user({
			hiddenId : 'managerId',
			isGetDept : [true, "deptId", "deptName"]
		});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"projectName" : {
			required : true,
			length : [0,100]
		}
//		,
//		"customerName" : {
//			required : true,
//			length : [0,100]
//		},
//		"officeName" : {
//			required : true,
//			length : [0,20]
//		},
//		"managerName" : {
//			required : true
//		},
//		"deptName" : {
//			required : true
//		},
//		"planBeginDate" : {
//			custom : ['date']
//		},
//		"planEndDate" : {
//			custom : ['date']
//		},
//		"peopleNumber" : {
//			required : false,
//			custom : ['onlyNumber']
//		},
//		"budgetAll_v" : {
//			required : true
//		},
//		"budgetField_v" : {
//			required : true
//		},
//		"budgetOther_v" : {
//			required : true
//		},
//		"workRate" : {
//			required : true,
//			custom : ['percentage']
//		}
	});

	$.each($("input:text"),function(){
		if($(this).val() == '0000-00-00'){
			$(this).val('');
		}
	});
});
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

$(document).ready(function(){
	//��ȡʡ�����鲢��ֵ��provinceArr
	provinceArr = getProvince();
	//��ʡ������provinceArr��ֵ��proCode
	addDataToProvince(provinceArr,'proCode');

	//��ѡ����
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

	// ��ѡ�ͻ�
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		gridOptions : {
			showcheckbox : false
		}
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
		"customerName" : {
			required : true,
			length : [0,100]
		},
		"officeName" : {
			required : true,
			length : [0,20]
		},
		"projectName" : {
			required : true,
			length : [0,100]
		},
		"managerName" : {
			required : true
		},
		"deptName" : {
			required : true
		},
		"planBeginDate" : {
			custom : ['date']
		},
		"planEndDate" : {
			custom : ['date']
		},
		"peopleNumber" : {
			required : false,
			custom : ['money']
		},
		"budgetAll" : {
			required : false,
			custom : ['money']
		},
		"budgetField" : {
			required : false,
			custom : ['money']
		},
		"budgetOther" : {
			required : false,
			custom : ['money']
		},
		"workRate" : {
			required : true,
			custom : ['money']
		}
	});

	/**
	 * ���Ψһ����֤
	 */
	var url = "?model=engineering_project_esmproject&action=checkRepeat";
	$("#projectCode").ajaxCheck({
		url : url,
		alertText : "* �ñ���Ѵ���",
		alertTextOk : "* �ñ�ſ���"
	});
});


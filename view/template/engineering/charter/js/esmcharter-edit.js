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

	//��ѡ���´�
	$("#officeName").yxcombogrid_office({
		hiddenId : 'officeId',
		gridOptions : {
			showcheckbox : false
		}
	});

	// ��ѡ�ͻ�
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		gridOptions : {
			showcheckbox : false
		}
	});

	//��ѡ����
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

	//��ѡ��Ŀ����
	$("#managerName").yxselect_user({
			hiddenId : 'managerId'
	});

	// ��֤��Ϣ
	validate({
		"projectCode" : {
			required : true,
			length : [0,100]
		},
		"projectName" : {
			required : true,
			length : [0,100]
		},
		"officeName" : {
			required : true,
			length : [0,20]
		},
		"deptName" : {
			required : true
		},
		"managerName" : {
			required : true,
			length : [0,20]
		},
		"planBeginDate" : {
			custom : ['date']
		},
		"planEndDate" : {
			custom : ['date']
		},
		"workRate" : {//����ռ��
			//required : false,
			custom : ['percentage']
		},
		"description" : {
			required : false,
			length : [0,2000]
		},
		"remark" : {
			required : false,
			length : [0,2000]
		}
	});

	/**
	 * ���Ψһ����֤
	 */
	var url = "?model=engineering_charter_esmcharter&action=checkRepeat";
	$("#projectCode").ajaxCheck({
		url : url,
		alertText : "* �ñ���Ѵ���",
		alertTextOk : "* �ñ�ſ���"
	});
});
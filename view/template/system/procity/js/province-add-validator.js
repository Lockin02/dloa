$(document).ready(function (){


	//��֤������
	validate({
		"country" : {
			required : true
		}
	});

	var url = "?model=system_procity_province&action=checkRepeat";
	$("#provinceName").ajaxCheck({
		url : url,
		alertText : "* ʡ�������Ѵ���",
		alertTextOk : "* ����"
	});

	$("#provinceCode").ajaxCheck({
		url : url,
		alertText : "* ʡ�ݱ���Ѵ���",
		alertTextOk : "* ����"
	});

	$("#country").yxcombotree({
		hiddenId : 'countryId',
		nameCol:'name',
		treeOptions : {
			checkable : false,//��ѡ
			url : "index1.php?model=system_procity_country&action=getChildren"//��ȡ����url
		}
	});
})
$(document).ready(function (){
	var countryId=$("#countryId").val();
	/*��ȡ���ҵķ���*/
	$.ajax({
		url : "?model=system_procity_country&action=listJson",
		success : function(data){
			data = eval("("+ data + ")");
			for(var i=0;i<data.length;i++){
				if(data[i].id==countryId){
					$("#country").val(data[i].countryName);
					break;
				}
			}
		}


	})

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
$(document).ready(function (){
	var countryId=$("#countryId").val();
	/*获取国家的方法*/
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

	//验证必填项
	validate({
		"country" : {
			required : true
		}
	});

	var url = "?model=system_procity_province&action=checkRepeat";
	$("#provinceName").ajaxCheck({
		url : url,
		alertText : "* 省份名称已存在",
		alertTextOk : "* 可用"
	});

	$("#provinceCode").ajaxCheck({
		url : url,
		alertText : "* 省份编号已存在",
		alertTextOk : "* 可用"
	});

	$("#country").yxcombotree({
		hiddenId : 'countryId',
		nameCol:'name',
		treeOptions : {
			checkable : false,//多选
			url : "index1.php?model=system_procity_country&action=getChildren"//获取数据url
		}
	});
})
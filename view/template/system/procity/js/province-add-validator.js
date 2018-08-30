$(document).ready(function (){


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
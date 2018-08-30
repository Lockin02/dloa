$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onsuccess : function() {
			if($("#incomeUnitId").val() == ""){
				alert("请通过下拉表格正确选择到款单位，若下拉表格中查询不到的客户名称，请点击'+'按钮直接新增");
				return false;
			}
			if($("#incomeMoney").val() == "" || $("#incomeMoney").val()*1 == 0 ){
				alert("到款金额不能为0或者空");
				return false;
			}
			if (confirm("你输入成功,确定提交吗?")) {
				$('#allotAble').val($('#incomeMoney').val());
				return true;
			} else {
				return false;
			}
		}
	});

	/** 到款单位验证* */
	$("#incomeUnitName").formValidator({
		empty : false,
		onshow : "请选择到款单位",
		onfocus : "选择到款单位",
		oncorrect : "您选择了到款单位"
	}).inputValidator({
		min : 1,
		onerror : "请选择到款单位"
	});

});
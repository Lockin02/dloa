// 初始化license选单
$(function() {
			$("#objType").attr("disabled", false);
		});

// 保存LICENSE
function saveTemplate() {
	var licenseType = $("#objType").val();
	var oldlicenseType = $("#licenseType").val();
	var licenseId = $("#licenseId").val();
	var templateId = $("#templateId").val();
	var thisVal = $("#thisVal").val();
	var actType = $("#actType").val();
	var fileName = $("#fileName").val();

	// 空字符处理
	var extVal = $.obj2json(dataStr);
	// alert(extVal)

	if (thisVal == "" && licenseType != "") {
		alert("没有选择任何加密配置！");
		return false;
	}

	if (licenseType == "") {// 如果没有选择类型，返回
		alert('请选择一种类型!');
		return false;
	} else {// 保存license
	// return false;
		$.post("?model=yxlicense_license_tempKey&action=addRecord", {
					"licenseType" : licenseType,
					"thisVal" : thisVal,
					"extVal" : extVal,
					"templateId" : templateId
				}, function(data) {
					if (data != 0) {
						alert('保存成功');
						// location =
						// '?model=goods_goods_properties&action=toChooseStep'
						// + '&goodsId=' + $("#goodsId").val()
						// + '&goodsName=' + $("#goodsName").val()
						// + '&number=' + $("#number").val()
						// + '&price=' + $("#price").val()
						// + '&money=' + $("#money").val()
						// + '&warrantyPeriod=' + $("#warrantyPeriod").val()
						// + '&licenseId=' + strTrim(data)

						// 输出json
						outJson = {
							"goodsId" : $("#goodsId").val(),
							"goodsName" : $("#goodsName").val(),
							"number" : $("#number").val(),
							"price" : $("#price").val(),
							"money" : $("#money").val(),
							"warrantyPeriod" : $("#warrantyPeriod").val(),
							"licenseId" : strTrim(data),
							"cacheId" : $("#cacheId").val()
						};
						parent.window.returnValue = outJson;

						// $.showDump(outJson);
						parent.window.close();
					} else {
						alert('保存失败');
						return false;
					}
				});
	}
}
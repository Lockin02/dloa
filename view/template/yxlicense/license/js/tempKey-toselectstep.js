// ��ʼ��licenseѡ��
$(function() {
			$("#objType").attr("disabled", false);
		});

// ����LICENSE
function saveTemplate() {
	var licenseType = $("#objType").val();
	var oldlicenseType = $("#licenseType").val();
	var licenseId = $("#licenseId").val();
	var templateId = $("#templateId").val();
	var thisVal = $("#thisVal").val();
	var actType = $("#actType").val();
	var fileName = $("#fileName").val();

	// ���ַ�����
	var extVal = $.obj2json(dataStr);
	// alert(extVal)

	if (thisVal == "" && licenseType != "") {
		alert("û��ѡ���κμ������ã�");
		return false;
	}

	if (licenseType == "") {// ���û��ѡ�����ͣ�����
		alert('��ѡ��һ������!');
		return false;
	} else {// ����license
	// return false;
		$.post("?model=yxlicense_license_tempKey&action=addRecord", {
					"licenseType" : licenseType,
					"thisVal" : thisVal,
					"extVal" : extVal,
					"templateId" : templateId
				}, function(data) {
					if (data != 0) {
						alert('����ɹ�');
						// location =
						// '?model=goods_goods_properties&action=toChooseStep'
						// + '&goodsId=' + $("#goodsId").val()
						// + '&goodsName=' + $("#goodsName").val()
						// + '&number=' + $("#number").val()
						// + '&price=' + $("#price").val()
						// + '&money=' + $("#money").val()
						// + '&warrantyPeriod=' + $("#warrantyPeriod").val()
						// + '&licenseId=' + strTrim(data)

						// ���json
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
						alert('����ʧ��');
						return false;
					}
				});
	}
}